<?php
/**
 * Automated Email Summary System
 * Sends daily summaries of new submissions from database tables
 * Uses PHPMailer with Gmail SMTP
 */

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer with fallback options
$phpmailerLoaded = false;

// Try Composer autoload first
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    try {
        require_once __DIR__ . '/vendor/autoload.php';
        if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
            $phpmailerLoaded = true;
            logMessage("PHPMailer loaded via Composer");
        }
    } catch (Exception $e) {
        logMessage("Composer autoload failed: " . $e->getMessage());
    }
}

// Try manual includes if Composer failed
if (!$phpmailerLoaded) {
    $phpmailerFiles = [
        __DIR__ . '/PHPMailer/src/Exception.php',
        __DIR__ . '/PHPMailer/src/PHPMailer.php',
        __DIR__ . '/PHPMailer/src/SMTP.php'
    ];

    $allFilesExist = true;
    foreach ($phpmailerFiles as $file) {
        if (!file_exists($file)) {
            $allFilesExist = false;
            break;
        }
    }

    if ($allFilesExist) {
        try {
            require_once $phpmailerFiles[0];
            require_once $phpmailerFiles[1];
            require_once $phpmailerFiles[2];
            $phpmailerLoaded = true;
            logMessage("PHPMailer loaded manually");
        } catch (Exception $e) {
            logMessage("Manual PHPMailer loading failed: " . $e->getMessage());
        }
    } else {
        logMessage("PHPMailer files not found");
    }
}

if (!$phpmailerLoaded) {
    throw new Exception("PHPMailer could not be loaded. Please run install_composer.php or manually install PHPMailer.");
}

// Error reporting and logging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors on screen
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/email_automation_errors.log');

// Start output buffering to capture any output
ob_start();

try {
    // Database configuration - Using same pattern as admin/config/connection.php
   $db_host = 'localhost';
    $db_user = 'u703364579_ecotours';
    $db_pass = 'Igiraneza@118';
    $db_name = 'u703364579_ecotours';

    // Email configuration
    $email_config = [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => 'fabrdaa@gmail.com',
        'smtp_password' => 'mofrqznkhkthzfog', 
        'from_email' => 'fabrdaa@gmail.com',
        'from_name' => 'Virunga Ecotours System',
        'to_email' => 'virungahomestay@gmail.com',
        'subject' => 'Daily Summary Report - ' . date('Y-m-d H:i:s')
    ];

    // Log script start
    logMessage("Email automation script started at " . date('Y-m-d H:i:s'));

    // Connect to database using same pattern as admin connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    logMessage("Database connection successful");

    // Initialize email content
    $email_body = generateEmailHeader();
    $total_new_items = 0;

    // Check each table for new submissions
    $tables = [
        'tour_bookings' => 'Tour Bookings',
        'contact_submissions' => 'Contact Submissions',
        'build_submissions' => 'Build Submissions',
        'community_messages' => 'Community Messages'
    ];

    foreach ($tables as $table_name => $display_name) {
        $new_items = processTable($conn, $table_name, $display_name);
        $email_body .= $new_items['content'];
        $total_new_items += $new_items['count'];
    }

    // Add summary footer
    $email_body .= generateEmailFooter($total_new_items);

    // Send email only if there are new items or if it's a daily summary
    if ($total_new_items > 0 || shouldSendDailySummary()) {
        $email_sent = sendEmailWithPHPMailer($email_config, $email_body, $total_new_items);

        if ($email_sent) {
            logMessage("Email sent successfully. New items: $total_new_items");
            echo "SUCCESS: Email sent with $total_new_items new items\n";
        } else {
            logMessage("Failed to send email");
            echo "ERROR: Failed to send email\n";
        }
    } else {
        logMessage("No new items found, email not sent");
        echo "INFO: No new items found\n";
    }

    // Close database connection
    $conn->close();
    logMessage("Script completed successfully");

} catch (Exception $e) {
    $error_message = "Error in email automation: " . $e->getMessage();
    logMessage($error_message);
    echo "ERROR: " . $error_message . "\n";

    // Send error notification email
    sendErrorNotification($email_config ?? null, $error_message);
}

// Clean output buffer
ob_end_clean();

/**
 * Process a single table for new submissions
 */
function processTable($conn, $table_name, $display_name) {
    $content = "";
    $count = 0;

    try {
        // Check if table exists and has emailed column
        $check_query = "SHOW COLUMNS FROM `$table_name` LIKE 'emailed'";
        $check_result = $conn->query($check_query);

        if (!$check_result || $check_result->num_rows == 0) {
            // Add emailed column if it doesn't exist
            $alter_query = "ALTER TABLE `$table_name` ADD COLUMN `emailed` TINYINT(1) DEFAULT 0";
            if (!$conn->query($alter_query)) {
                throw new Exception("Failed to add emailed column to $table_name: " . $conn->error);
            }
            logMessage("Added emailed column to table: $table_name");
        }

        // Detect primary key column
        $pk_field = 'id';
        $pk_query = "SHOW KEYS FROM `$table_name` WHERE Key_name = 'PRIMARY'";
        $pk_result = $conn->query($pk_query);
        if ($pk_result && $pk_result->num_rows > 0) {
            $pk_row = $pk_result->fetch_assoc();
            $pk_field = $pk_row['Column_name'];
        }
        // Special case for tour_bookings table
        if ($table_name === 'tour_booking' || $table_name === 'tour_bookings') {
            $pk_field = 'booking_id';
        }

        // Get new submissions (emailed = 0)
        // Special handling for tour_bookings to get tour name
        if ($table_name === 'tour_booking' || $table_name === 'tour_bookings') {
            $query = "SELECT tb.*, t.title as tour_name
                     FROM `$table_name` tb
                     LEFT JOIN tours t ON tb.tour_id = t.tour_id
                     WHERE tb.emailed = 0
                     ORDER BY tb.`$pk_field` DESC";
        } else {
            $query = "SELECT * FROM `$table_name` WHERE emailed = 0 ORDER BY `$pk_field` DESC";
        }
        $result = $conn->query($query);

        if (!$result) {
            throw new Exception("Query failed for table $table_name: " . $conn->error);
        }

        $count = $result->num_rows;

        if ($count > 0) {
            $content .= "\n<h2 style='color: #2a4858; border-bottom: 2px solid #2a4858; padding-bottom: 10px;'>$display_name ($count new)</h2>\n";

            $item_ids = [];
            while ($row = $result->fetch_assoc()) {
                $content .= formatRowData($row, $table_name);
                $item_ids[] = $row[$pk_field];
            }

            // Mark items as emailed
            if (!empty($item_ids)) {
                $ids_string = implode(',', array_map('intval', $item_ids));
                $update_query = "UPDATE `$table_name` SET emailed = 1 WHERE `$pk_field` IN ($ids_string)";

                if ($conn->query($update_query)) {
                    logMessage("Marked $count items as emailed in table: $table_name");
                } else {
                    logMessage("Failed to update emailed status for table $table_name: " . $conn->error);
                }
            }
        } else {
            $content .= "\n<h2 style='color: #2a4858; border-bottom: 2px solid #2a4858; padding-bottom: 10px;'>$display_name</h2>\n";
            $content .= "<p style='color: #666; font-style: italic;'>No new submissions</p>\n";
        }

    } catch (Exception $e) {
        $content .= "\n<h2 style='color: #d32f2f;'>$display_name - ERROR</h2>\n";
        $content .= "<p style='color: #d32f2f;'>Error processing table: " . htmlspecialchars($e->getMessage()) . "</p>\n";
        logMessage("Error processing table $table_name: " . $e->getMessage());
    }

    return ['content' => $content, 'count' => $count];
}

/**
 * Format row data for email display
 */
function formatRowData($row, $table_name) {
    $content = "<div style='background: #f5f5f5; margin: 10px 0; padding: 15px; border-radius: 5px; border-left: 4px solid #2a4858;'>\n";

    // Format specific fields based on table - only show essential information
    switch ($table_name) {
        case 'tour_booking':
        case 'tour_bookings':
            // Only show: tour name, traveler name, email, phone, travel date
            // Note: field names are 'full_name' and 'travel_date' in the database
            $important_fields = ['tour_name', 'full_name', 'email', 'phone', 'travel_date'];
            break;
        case 'contact_submissions':
            // Only show: name, email, subject, message (remove id)
            $important_fields = ['name', 'email', 'subject', 'message'];
            break;
        case 'build_submission':
        case 'build_submissions':
            // Only show: name, email, phone, project_type, budget, description (remove id, date, created_at)
            $important_fields = ['name', 'email', 'phone', 'project_type', 'budget', 'description'];
            break;
        case 'community_messages':
            $important_fields = ['name', 'email', 'message_type', 'subject', 'message'];
            break;
        default:
            // For unknown tables, exclude common unnecessary fields
            $excluded_fields = ['id', 'created_at', 'updated_at', 'ip_address', 'tour_id', 'booking_id', 'emailed', 'date', 'timestamp', 'submitted_at'];
            $important_fields = array_diff(array_keys($row), $excluded_fields);
    }

    // Display important fields only
    foreach ($important_fields as $field) {
        if (isset($row[$field]) && !empty($row[$field])) {
            // Custom labels for better readability
            $custom_labels = [
                'tour_name' => 'Tour/Itinerary',
                'full_name' => 'Traveler Name',
                'travel_date' => 'Travel Date',
                'project_type' => 'Project Type'
            ];

            $label = isset($custom_labels[$field]) ? $custom_labels[$field] : ucwords(str_replace('_', ' ', $field));
            $value = htmlspecialchars($row[$field]);

            // Truncate long text fields (except for message fields which should show more)
            if (in_array($field, ['message', 'description']) && strlen($value) > 500) {
                $value = substr($value, 0, 500) . '...';
            } elseif (!in_array($field, ['message', 'description']) && strlen($value) > 200) {
                $value = substr($value, 0, 200) . '...';
            }

            $content .= "<p><strong>$label:</strong> $value</p>\n";
        }
    }

    $content .= "</div>\n";
    return $content;
}

/**
 * Generate email header
 */
function generateEmailHeader() {
    $header = "<!DOCTYPE html>\n<html>\n<head>\n<meta charset='UTF-8'>\n";
    $header .= "<title>Daily Summary Report</title>\n</head>\n<body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>\n";
    $header .= "<div style='max-width: 800px; margin: 0 auto; padding: 20px;'>\n";
    $header .= "<h1 style='color: #2a4858; text-align: center; border-bottom: 3px solid #2a4858; padding-bottom: 15px;'>";
    $header .= "Virunga Ecotours - Daily Summary Report</h1>\n";
    $header .= "<p style='text-align: center; color: #666; font-size: 14px;'>Generated on " . date('F j, Y \a\t g:i A') . "</p>\n";
    return $header;
}

/**
 * Generate email footer
 */
function generateEmailFooter($total_items) {
    $footer = "\n<hr style='margin: 30px 0; border: none; border-top: 2px solid #2a4858;'>\n";
    $footer .= "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px; text-align: center;'>\n";
    $footer .= "<h3 style='color: #2a4858; margin: 0 0 10px 0;'>Summary</h3>\n";
    $footer .= "<p style='margin: 0; font-size: 16px;'><strong>Total New Items: $total_items</strong></p>\n";
    $footer .= "<p style='margin: 10px 0 0 0; font-size: 12px; color: #666;'>";
    $footer .= "This is an automated email from your Virunga Ecotours system.</p>\n";
    $footer .= "</div>\n</div>\n</body>\n</html>";
    return $footer;
}

/**
 * Send email using PHPMailer with Gmail SMTP
 */
function sendEmailWithPHPMailer($config, $body, $item_count) {
    try {
        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];

        // Recipients
        $mail->setFrom($config['from_email'], $config['from_name']);
        $mail->addAddress($config['to_email']);
        $mail->addReplyTo($config['from_email'], $config['from_name']);

        // Content
        $subject = $config['subject'];
        if ($item_count > 0) {
            $subject .= " - $item_count New Items";
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); // Plain text version

        // Send the email
        $result = $mail->send();

        if ($result) {
            logMessage("Email sent successfully via PHPMailer");
            return true;
        } else {
            logMessage("Failed to send email via PHPMailer");
            return false;
        }

    } catch (Exception $e) {
        logMessage("PHPMailer Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send error notification using PHPMailer
 */
function sendErrorNotification($config, $error_message) {
    if (!$config) return false;

    try {
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP();
        $mail->Host = $config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $config['smtp_username'];
        $mail->Password = $config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $config['smtp_port'];

        // Recipients
        $mail->setFrom($config['from_email'], 'System Error');
        $mail->addAddress($config['to_email']);

        // Content
        $subject = "ERROR: Email Automation Script - " . date('Y-m-d H:i:s');
        $body = "<h2>Email Automation Error</h2>";
        $body .= "<p><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
        $body .= "<p><strong>Error:</strong> " . htmlspecialchars($error_message) . "</p>";
        $body .= "<p>Please check the server logs for more details.</p>";

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        return $mail->send();

    } catch (Exception $e) {
        logMessage("Error notification failed: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if daily summary should be sent (even with no new items)
 */
function shouldSendDailySummary() {
    // Send daily summary at 9 AM regardless of new items
    $current_hour = (int)date('H');
    return $current_hour == 9;
}

/**
 * Log messages to file
 */
function logMessage($message) {
    $log_entry = "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    file_put_contents(__DIR__ . '/email_automation.log', $log_entry, FILE_APPEND | LOCK_EX);
}

?>