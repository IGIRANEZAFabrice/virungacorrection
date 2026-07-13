<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once '../admin/config/connection.php';

function ensurePHPMailerLoaded() {
    static $loaded = null;
    if ($loaded !== null) {
        if (!$loaded) {
            throw new \RuntimeException('PHPMailer could not be loaded.');
        }
        return;
    }

    $rootDir = dirname(__DIR__);

    // Composer autoload first
    if (file_exists($rootDir . '/vendor/autoload.php')) {
        try {
            require_once $rootDir . '/vendor/autoload.php';
        } catch (\Throwable $e) {
            // Fall back to manual includes below
        }
    }

    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $loaded = true;
        return;
    }

    // Manual includes as a fallback
    $phpmailerFiles = [
        $rootDir . '/PHPMailer/src/Exception.php',
        $rootDir . '/PHPMailer/src/PHPMailer.php',
        $rootDir . '/PHPMailer/src/SMTP.php',
    ];

    foreach ($phpmailerFiles as $file) {
        if (!file_exists($file)) {
            $loaded = false;
            throw new \RuntimeException('PHPMailer could not be loaded (files missing).');
        }
    }

    require_once $phpmailerFiles[0];
    require_once $phpmailerFiles[1];
    require_once $phpmailerFiles[2];

    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $loaded = false;
        throw new \RuntimeException('PHPMailer could not be loaded.');
    }

    $loaded = true;
}

function logBuildEmailMessage($message) {
    $logFile = dirname(__DIR__) . '/tour_booking_email_errors.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function sendBuildNotificationEmail($recipientEmail, $subject, $bodyHtml) {
    $email_config = [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_username' => 'virungahomestay@gmail.com',
        'smtp_password' => 'mvkumfdesmiedtnl',
        'from_email' => 'virungahomestay@gmail.com',
        'from_name' => 'Virunga Ecotours System',
    ];

    try {
        ensurePHPMailerLoaded();

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $email_config['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $email_config['smtp_username'];
        $mail->Password = $email_config['smtp_password'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $email_config['smtp_port'];

        $mail->setFrom($email_config['from_email'], $email_config['from_name']);
        $mail->addReplyTo($email_config['from_email'], $email_config['from_name']);
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $bodyHtml;
        $mail->AltBody = strip_tags($bodyHtml);

        $mail->send();
        return true;
    } catch (\Throwable $e) {
        logBuildEmailMessage('Email send failed to ' . $recipientEmail . ': ' . $e->getMessage());
        return false;
    }
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $names = isset($_POST['names']) ? $conn->real_escape_string(trim($_POST['names'])) : '';
    $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $phone = isset($_POST['phone']) ? $conn->real_escape_string(trim($_POST['phone'])) : '';
    $referral_source = isset($_POST['referral_source']) ? $conn->real_escape_string(trim($_POST['referral_source'])) : '';
    $travelers_info = isset($_POST['travelers_info']) ? $conn->real_escape_string(trim($_POST['travelers_info'])) : '';
    $trip_days = isset($_POST['trip_days']) ? $conn->real_escape_string(trim($_POST['trip_days'])) : '';
    $group_size = isset($_POST['group_size']) ? $conn->real_escape_string(trim($_POST['group_size'])) : '';
    $travel_date = isset($_POST['travel_date']) ? $conn->real_escape_string(trim($_POST['travel_date'])) : '';
    $budget_notes = isset($_POST['budget_notes']) ? $conn->real_escape_string(trim($_POST['budget_notes'])) : '';
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;
    $weekly_emails = isset($_POST['weekly-emails']) ? 1 : 0;

    // Validate required fields
    $missing_fields = [];
    if (empty($names)) $missing_fields[] = 'names';
    if (empty($email)) $missing_fields[] = 'email';
    if (empty($phone)) $missing_fields[] = 'phone';
    if (empty($trip_days)) $missing_fields[] = 'trip_days';
    if (empty($group_size)) $missing_fields[] = 'group_size';
    if (empty($travel_date)) $missing_fields[] = 'travel_date';
    
    if (!empty($missing_fields)) {
        header('Location: build.php?error=missing_fields&fields=' . urlencode(implode(',', $missing_fields)));
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: build.php?error=invalid_email');
        exit;
    }

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO build_submissions (names, email, phone, referral_source, travelers_info, trip_days, group_size, travel_date, budget_notes, newsletter, weekly_emails) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssii", $names, $email, $phone, $referral_source, $travelers_info, $trip_days, $group_size, $travel_date, $budget_notes, $newsletter, $weekly_emails);

    if ($stmt->execute()) {
        // 2) Send email notifications (both recipients) right after DB save.
        $recipients = ['virungahomestay@gmail.com', 'info@virungaecotours.com'];

        $subject = 'New Trip Planner Request - ' . $names;
        $bodyHtml = '
            <h2 style="color:#2a4858;">New Trip Planner Request</h2>
            <p>
                User <strong>' . htmlspecialchars($names) . '</strong>
                (email: <strong>' . htmlspecialchars($email) . '</strong>)
                planned a trip on the main website.
            </p>
            <p><strong>Planned trip starting:</strong> ' . htmlspecialchars($travel_date) . '</p>
            <p><strong>Trip length:</strong> ' . htmlspecialchars((string)$trip_days) . ' days</p>
            <p><strong>Group size:</strong> ' . htmlspecialchars((string)$group_size) . '</p>
            <p><strong>Phone:</strong> ' . htmlspecialchars((string)$phone) . '</p>
            <p><strong>Referral source:</strong> ' . htmlspecialchars((string)$referral_source) . '</p>
            <p><strong>Budget notes:</strong> ' . nl2br(htmlspecialchars((string)$budget_notes)) . '</p>
            <p><strong>Traveler info:</strong> ' . nl2br(htmlspecialchars((string)$travelers_info)) . '</p>
            <hr/>
            <p style="color:#666; font-size:12px;">Sent automatically from Virunga Ecotours trip planner form.</p>
        ';

        $failedRecipients = [];
        foreach ($recipients as $recipientEmail) {
            if (!sendBuildNotificationEmail($recipientEmail, $subject, $bodyHtml)) {
                $failedRecipients[] = $recipientEmail;
            }
        }
        if (!empty($failedRecipients)) {
            logBuildEmailMessage('Some notification emails failed: ' . implode(', ', $failedRecipients));
        }

        header('Location: build.php?success=true');
    } else {
        header('Location: build.php?error=database_error');
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: build.php');
    exit;
}
?>