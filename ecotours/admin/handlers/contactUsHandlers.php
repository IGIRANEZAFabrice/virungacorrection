<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../config/connection.php';

function ensurePHPMailerLoaded() {
    static $loaded = null;
    if ($loaded !== null) {
        if (!$loaded) {
            throw new \RuntimeException('PHPMailer could not be loaded.');
        }
        return;
    }

    $rootDir = dirname(__DIR__, 2);

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

function logContactEmailMessage($message) {
    $logFile = dirname(__DIR__, 2) . '/tour_booking_email_errors.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function buildContactCustomerEmail($customerName, $subjectLine) {
    $safeName = htmlspecialchars($customerName);
    $safeSubject = htmlspecialchars($subjectLine);
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
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1f2a36;">Hi ' . $safeName . ', thanks for reaching out.</h1>
            <p style="margin:12px 0 0;font-size:15px;line-height:1.6;color:#2f3b47;">We received your message about "<strong>' . $safeSubject . '</strong>". Our team will reply shortly.</p>
          </td>
        </tr>
        <tr>
          <td style="padding:14px 28px 18px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#e8f3ec;border:1px solid #c9e5d4;border-radius:10px;padding:14px;">
              <tr>
                <td style="font-size:14px;line-height:1.7;color:#1f2a36;">
                  <strong>Need anything urgent?</strong><br/>
                  <span style="display:block;margin-top:6px;">📧 <a href="mailto:info@virungaecotours.com" style="color:#1f7a5a;text-decoration:none;">info@virungaecotours.com</a></span>
                  <span style="display:block;">📱 +250 784 513 435 (WhatsApp / Call)</span>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 28px 24px;">
            <p style="margin:0;font-size:13px;line-height:1.6;color:#6b7a8a;">We\'re here to help and will get back to you soon.</p>
          </td>
        </tr>
      </table>
    </div>';
}

function sendContactNotificationEmail($recipientEmail, $subject, $bodyHtml) {
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
        logContactEmailMessage('Email send failed to ' . $recipientEmail . ': ' . $e->getMessage());
        return false;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    
    try {
        // Validate required fields
        $requiredFields = ['firstName', 'lastName', 'email', 'subject', 'message'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Please fill in all required fields");
            }
        }

        // Sanitize inputs
        $firstName = $conn->real_escape_string(trim($_POST['firstName']));
        $lastName = $conn->real_escape_string(trim($_POST['lastName']));
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $phone = isset($_POST['phone']) ? $conn->real_escape_string(trim($_POST['phone'])) : null;
        $subject = $conn->real_escape_string(trim($_POST['subject']));
        $message = $conn->real_escape_string(trim($_POST['message']));
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please enter a valid email address");
        }

        // Prepare and execute query
        $query = "INSERT INTO contact_submissions 
                 (first_name, last_name, email, phone, subject, message, ip_address) 
                 VALUES ('$firstName', '$lastName', '$email', '$phone', '$subject', '$message', '$ipAddress')";
        
        if (!$conn->query($query)) {
            throw new Exception("Error submitting your message. Please try again later.");
        }

        // 2) Send email notifications (both recipients) right after DB save.
        $recipients = ['virungahomestay@gmail.com', 'info@virungaecotours.com'];
        $fullName = trim($firstName . ' ' . $lastName);
        $subjectEmail = 'New Contact Message - ' . $fullName;

        $bodyHtml = '
            <h2 style="color:#2a4858;">New Contact Us Message</h2>
            <p>
                User <strong>' . htmlspecialchars($fullName) . '</strong>
                (email: <strong>' . htmlspecialchars($email) . '</strong>)
                contacted you on the main website saying:<br/>
                "<em>' . nl2br(htmlspecialchars($message)) . '</em>"
            </p>
            <p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>
            ' . (!empty($phone) ? '<p><strong>Phone:</strong> ' . htmlspecialchars($phone) . '</p>' : '') . '
            <hr/>
            <p style="color:#666; font-size:12px;">Sent automatically from Virunga Ecotours contact form.</p>
        ';

        $failedRecipients = [];
        foreach ($recipients as $recipientEmail) {
            if (!sendContactNotificationEmail($recipientEmail, $subjectEmail, $bodyHtml)) {
                $failedRecipients[] = $recipientEmail;
            }
        }
        if (!empty($failedRecipients)) {
            logContactEmailMessage('Some notification emails failed: ' . implode(', ', $failedRecipients));
        }

        // Send confirmation email to the customer
        $customerBody = buildContactCustomerEmail($fullName, $subject);
        $customerSubject = 'We received your message - Virunga Ecotours';
        if (!sendContactNotificationEmail($email, $customerSubject, $customerBody)) {
            logContactEmailMessage('Customer contact confirmation failed for ' . $email);
        }

        // Store success message in session
        $_SESSION['contact_message'] = [
            'type' => 'success',
            'text' => 'Thank you for your message! We will get back to you soon.'
        ];
        
    } catch (Exception $e) {
        $_SESSION['contact_message'] = [
            'type' => 'error',
            'text' => $e->getMessage()
        ];
    }
    
    $conn->close();
    header("Location: ../../pages/contactus.php");
    exit;
}

// Invalid request method
http_response_code(405);
echo "Invalid request method";
exit;
?>
