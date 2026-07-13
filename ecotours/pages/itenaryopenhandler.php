<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once '../admin/config/database.php';

/**
 * Loads PHPMailer (via Composer autoload if available, otherwise via local PHPMailer folder).
 * We keep this local to this handler so including this file doesn't trigger other email scripts.
 */
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

function logBookingEmailMessage($message) {
    $logFile = dirname(__DIR__) . '/tour_booking_email_errors.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

/**
 * Customer-facing booking confirmation email (inline CSS for email client support).
 */
function buildCustomerBookingEmail($customerName, $tourName, $tourDate, $guestCount) {
    $safeName  = htmlspecialchars($customerName);
    $safeTour  = htmlspecialchars($tourName);
    $safeDate  = htmlspecialchars($tourDate);
    $safeGuests = htmlspecialchars($guestCount);

    return '
    <div style="background:#f6f8fb;padding:24px;font-family:\'Helvetica Neue\',Arial,sans-serif;color:#1f2a36;">
      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width:620px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,0.06);">
        <tr>
          <td style="padding:28px 28px 12px;text-align:center;">
            <img src="https://www.virungaecotours.com/images/logos/icon.png" alt="Virunga Ecotours" width="72" style="display:block;margin:0 auto 12px;">
            <div style="font-size:14px;letter-spacing:0.4px;color:#61707f;">Virunga Ecotours</div>
          </td>
        </tr>
        <tr>
          <td style="padding:8px 28px 0;">
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1f2a36;">Hi ' . $safeName . ', your booking is in!</h1>
            <p style="margin:12px 0 0;font-size:15px;line-height:1.6;color:#2f3b47;">We received your tour request and are preparing the next steps.</p>
          </td>
        </tr>
        <tr>
          <td style="padding:18px 28px 8px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f1f5f9;border-radius:10px;padding:16px;">
              <tr>
                <td style="font-size:14px;line-height:1.6;color:#1f2a36;">
                  <strong>Tour:</strong> ' . $safeTour . '<br/>
                  <strong>Date:</strong> ' . $safeDate . '<br/>
                  <strong>Guests:</strong> ' . $safeGuests . '
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:12px 28px 6px;">
            <p style="margin:0 0 12px;font-size:15px;line-height:1.6;color:#2f3b47;">
              We\'re excited to host you. Our team will follow up shortly with confirmation and payment options.
            </p>
          </td>
        </tr>
        <tr>
          <td style="padding:0 28px 18px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#e8f3ec;border:1px solid #c9e5d4;border-radius:10px;padding:14px;">
              <tr>
                <td style="font-size:14px;line-height:1.7;color:#1f2a36;">
                  <strong>Need help?</strong><br/>
                  <span style="display:block;margin-top:6px;">📧 <a href="mailto:info@virungaecotours.com" style="color:#1f7a5a;text-decoration:none;">info@virungaecotours.com</a></span>
                  <span style="display:block;">📱 +250 784 513 435 (WhatsApp / Call)</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 28px 24px;">
            <p style="margin:0;font-size:13px;line-height:1.6;color:#6b7a8a;">Thank you for choosing Virunga Ecotours. We look forward to welcoming you.</p>
          </td>
        </tr>
      </table>
    </div>';
}

/**
 * Sends a single booking notification email to one recipient.
 */
function sendBookingNotificationEmail($recipientEmail, $subject, $bodyHtml) {
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
    } catch (Exception $e) {
        logBookingEmailMessage('Email send failed to ' . $recipientEmail . ': ' . $e->getMessage());
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $full_name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $travel_date = $_POST['date'] ?? '';
    $guest_count = isset($_POST['guests']) ? trim((string)$_POST['guests']) : 'Not specified';
    $tour_id = $_POST['tour_id'] ?? '';
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($phone) || empty($travel_date) || empty($tour_id)) {
        header("Location: itenaryopen.php?id=$tour_id&error=missing_fields");
        exit;
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: itenaryopen.php?id=$tour_id&error=invalid_email");
        exit;
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO tour_bookings (tour_id, full_name, email, phone, travel_date, ip_address) 
                VALUES (:tour_id, :full_name, :email, :phone, :travel_date, :ip_address)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':tour_id', $tour_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':travel_date', $travel_date);
        $stmt->bindParam(':ip_address', $ip_address);
        
        // Execute the statement
        $stmt->execute();

        // Get booking id (if the column is auto-increment).
        // This does not change your DB insert logic; it's only used for the notification email.
        $booking_id = $pdo->lastInsertId();

        // Fetch tour title for a nicer email subject/body (optional, but useful).
        $tour_title = '';
        try {
            $tourTitleStmt = $pdo->prepare("SELECT title FROM tours WHERE tour_id = :tour_id");
            $tourTitleStmt->execute(['tour_id' => $tour_id]);
            $tour_title = $tourTitleStmt->fetchColumn() ?: '';
        } catch (\Throwable $e) {
            // Don't block booking if this fails.
        }

        // 2) Send email notifications (both recipients) right after DB save.
        $recipients = ['virungahomestay@gmail.com', 'info@virungaecotours.com'];

        $titleForEmail = !empty($tour_title) ? $tour_title : ('Tour ID ' . $tour_id);
        $subject = 'Booked Itinerary - ' . $titleForEmail;

        $bodyHtml = '
            <h2 style="color:#2a4858;">New Itinerary Booking</h2>
            <p>
                User <strong>' . htmlspecialchars($full_name) . '</strong>
                (email: <strong>' . htmlspecialchars($email) . '</strong>)
                booked an itinerary titled <strong>' . htmlspecialchars($titleForEmail) . '</strong>.
            </p>
            <p><strong>Travel Date:</strong> ' . htmlspecialchars((string)$travel_date) . '</p>
            <p><strong>Phone:</strong> ' . htmlspecialchars((string)$phone) . '</p>
            <p><strong>Booking ID:</strong> ' . htmlspecialchars((string)$booking_id) . '</p>
            <p><strong>Guests (as entered):</strong> ' . htmlspecialchars((string)$guest_count) . '</p>
            <p><strong>IP Address:</strong> ' . htmlspecialchars((string)$ip_address) . '</p>
            <hr/>
            <p style="color:#666; font-size:12px;">Sent automatically from Virunga Ecotours itinerary booking form.</p>
        ';

        $failedRecipients = [];
        foreach ($recipients as $recipientEmail) {
            if (!sendBookingNotificationEmail($recipientEmail, $subject, $bodyHtml)) {
                $failedRecipients[] = $recipientEmail;
            }
        }

        if (!empty($failedRecipients)) {
            logBookingEmailMessage('Some notification emails failed: ' . implode(', ', $failedRecipients));
        }

        // 3) Send confirmation email to the customer.
        $readableDate = $travel_date;
        if (!empty($travel_date)) {
            $ts = strtotime($travel_date);
            if ($ts) {
                $readableDate = date('F j, Y', $ts);
            }
        }
        $customerBody = buildCustomerBookingEmail(
            $full_name,
            $titleForEmail,
            $readableDate,
            !empty($guest_count) ? $guest_count : 'Not specified'
        );
        $customerSubject = 'Your tour booking was received - ' . $titleForEmail;
        if (!sendBookingNotificationEmail($email, $customerSubject, $customerBody)) {
            logBookingEmailMessage('Customer confirmation failed for ' . $email);
        }
        
        // Redirect with success message
        header("Location: itenaryopen.php?id=$tour_id&success=1");
        exit;
        
    } catch(PDOException $e) {
        // Redirect with error message
        header("Location: itenaryopen.php?id=$tour_id&error=database");
        exit;
    }
}

// Handle page load and data fetching
$tour_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Fetch main tour data
    $tourQuery = "SELECT * FROM tours WHERE tour_id = :tour_id";
    $tourStmt = $pdo->prepare($tourQuery);
    $tourStmt->execute(['tour_id' => $tour_id]);
    $tour = $tourStmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        header('Location: itenary.php');
        exit();
    }

    // Fetch tour highlights/images
    $highlightsQuery = "SELECT * FROM tour_highlights WHERE tour_id = :tour_id ORDER BY display_order";
    $highlightsStmt = $pdo->prepare($highlightsQuery);
    $highlightsStmt->execute(['tour_id' => $tour_id]);
    $highlights = $highlightsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch tour days
    $daysQuery = "SELECT * FROM tour_days WHERE tour_id = :tour_id ORDER BY day_number";
    $daysStmt = $pdo->prepare($daysQuery);
    $daysStmt->execute(['tour_id' => $tour_id]);
    $days = $daysStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch included items
    $includedQuery = "SELECT * FROM tour_included WHERE tour_id = :tour_id";
    $includedStmt = $pdo->prepare($includedQuery);
    $includedStmt->execute(['tour_id' => $tour_id]);
    $included = $includedStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch excluded items
    $excludedQuery = "SELECT * FROM tour_excluded WHERE tour_id = :tour_id";
    $excludedStmt = $pdo->prepare($excludedQuery);
    $excludedStmt->execute(['tour_id' => $tour_id]);
    $excluded = $excludedStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch items to bring
    $toBringQuery = "SELECT * FROM tour_to_bring WHERE tour_id = :tour_id";
    $toBringStmt = $pdo->prepare($toBringQuery);
    $toBringStmt->execute(['tour_id' => $tour_id]);
    $toBring = $toBringStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch pricing tiers (if any)
    $pricingTiers = [];
    $pricingYear = null;
    $pricingTiersQuery = "SELECT group_size, price_per_person, updated_at FROM pricing_tiers WHERE tour_id = :tour_id ORDER BY id";
    $pricingTiersStmt = $pdo->prepare($pricingTiersQuery);
    $pricingTiersStmt->execute(['tour_id' => $tour_id]);
    $pricingTiers = $pricingTiersStmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($pricingTiers)) {
        // Determine the most recent year from updated_at to display in the heading
        $latestTimestamp = max(array_map(static function ($tier) {
            return strtotime($tier['updated_at'] ?? '1970-01-01');
        }, $pricingTiers));
        $pricingYear = $latestTimestamp ? date('Y', $latestTimestamp) : null;
    }

    // Fetch pricing notes (if any)
    $pricingNotes = [];
    $pricingNotesQuery = "SELECT note FROM pricing_notes WHERE tour_id = :tour_id ORDER BY id";
    $pricingNotesStmt = $pdo->prepare($pricingNotesQuery);
    $pricingNotesStmt->execute(['tour_id' => $tour_id]);
    $pricingNotes = $pricingNotesStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Fetch related tours
    $relatedToursQuery = "SELECT * FROM tours WHERE category = :category AND tour_id != :tour_id LIMIT 3";
    $relatedToursStmt = $pdo->prepare($relatedToursQuery);
    $relatedToursStmt->execute([
        'category' => $tour['category'],
        'tour_id' => $tour_id
    ]);
    $relatedTours = $relatedToursStmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    error_log("Error fetching tour data: " . $e->getMessage());
    header('Location: itenary.php');
    exit();
}
