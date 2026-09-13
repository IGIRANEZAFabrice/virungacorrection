<?php
require_once __DIR__ . '/../../config/recaptcha.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// --- EMAIL CONFIGURATION ---
define('ADMIN_EMAIL', 'virungahomestay@gmail.com');
define('BUSINESS_NAME', 'Virunga Homestay');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate reCAPTCHA first
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptchaResponse)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please complete the reCAPTCHA'
        ]);
        exit;
    }
    
    if (!verify_recaptcha($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '')) {
        echo json_encode([
            'status' => 'error',
            'message' => 'reCAPTCHA verification failed'
        ]);
        exit;
    }
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? 'New Inquiry from Website';
    $message = $_POST['message'] ?? '';
    $phone = $_POST['phone'] ?? 'Not provided';
    $source = $_POST['source'] ?? 'General Website Inquiry';

    if (empty($name) || empty($email) || empty($message)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in all required fields (Name, Email, Message).'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_EMAIL;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // --- 1. SEND NOTIFICATION TO ADMINS ---
        $mail->setFrom(SMTP_EMAIL, BUSINESS_NAME . ' Website');
        $mail->addAddress(ADMIN_EMAIL, 'Virunga Homestay Operations');
        $mail->addAddress('info@virungajourneys.com', 'Virunga Journeys Concierge');
        $mail->addReplyTo($email, $name);

        // --- 1B. SAVE TO DATABASE ---
        try {
            require_once __DIR__ . '/../config/db.php';
            if (isset($conn) && !$conn->connect_error) {
                $dbStmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, status, created_at) VALUES (?, ?, ?, ?, 'unread', NOW())");
                if ($dbStmt) {
                    $combinedSubject = $subject . ($phone !== 'Not provided' ? " | Tel: $phone" : "") . " | " . $source;
                    $dbStmt->bind_param("ssss", $name, $email, $combinedSubject, $message);
                    $dbStmt->execute();
                    $dbStmt->close();
                }
            }
        } catch (\Throwable $dbe) {
            error_log("Database save failed in homestay send_mail.php: " . $dbe->getMessage());
        }

        $mail->isHTML(true);
        $mail->Subject = "New Website Inquiry: " . $subject;
        
        $mail->Body = "
            <div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #fdfaf7; border: 1px solid #eee; border-radius: 12px; overflow: hidden;'>
                <div style='background: #c8711a; padding: 25px 30px;'>
                   <table style='width: 100%; border-collapse: collapse;'>
                       <tr>
                           <td style='vertical-align: middle; text-align: left;'>
                               <img src='https://virungahomestay.com/img/logo/logo.png' alt='Virunga Homestay' style='max-width: 120px;'>
                           </td>
                           <td style='vertical-align: middle; text-align: right; color: #000000; font-size: 20px; font-weight: 300; letter-spacing: 0.05em;'>
                               Virunga Homestay
                           </td>
                       </tr>
                   </table>
                </div>
                <div style='padding: 40px 30px; background: #ffffff;'>
                    <h2 style='color: #150f0b; margin-top: 0;'>New Inquiry Received</h2>
                    <p style='color: #666; font-size: 14px; margin-bottom: 30px;'>You have a new message from your website contact form.</p>
                    
                    <table style='width: 100%; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888; width: 100px;'>From:</td>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333; font-weight: bold;'>{$name}</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'>Email:</td>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0;'><a href='mailto:{$email}' style='color: #c8711a; text-decoration: none;'>{$email}</a></td>
                        </tr>
                        <tr>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'>Phone:</td>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'>{$phone}</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'>Subject:</td>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'>{$subject}</td>
                        </tr>
                        <tr>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'>Source:</td>
                            <td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'>{$source}</td>
                        </tr>
                    </table>
                    
                    <div style='margin-top: 30px; padding: 20px; background: #f9f4ef; border-radius: 8px; color: #444; line-height: 1.6;'>
                        <strong style='display: block; margin-bottom: 10px; color: #150f0b;'>Message:</strong>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                </div>
                <div style='background: #fdfaf7; padding: 20px; text-align: center; font-size: 12px; color: #999;'>
                    This inquiry was sent from the Virunga Homestay website contact form.
                </div>
            </div>
        ";
        $mail->AltBody = "New Inquiry Received\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$subject}\nSource: {$source}\n\nMessage:\n{$message}";

        $mail->send();

        // Send multilingual confirmation to user
        if (file_exists(__DIR__ . '/../../config/localization.php')) {
            require_once __DIR__ . '/../../config/localization.php';
            $userLang = $_POST['user_lang'] ?? null;
            $details = "<strong>Subject:</strong> " . htmlspecialchars($subject) . "<br><strong>Message:</strong> " . htmlspecialchars($message);
            send_multilingual_confirmation_email($email, $name, $subject, $details, $userLang);
        }

        echo json_encode(['status' => 'success']);

    } catch (Exception $e) {
        $errorFile = dirname(__DIR__) . "/error_log.txt";
        $logMessage = "[" . date('Y-m-d H:i:s') . "] PHPMailer Error: " . $mail->ErrorInfo . " | Exception: " . $e->getMessage() . "\n";
        file_put_contents($errorFile, $logMessage, FILE_APPEND);

        echo json_encode([
            'status' => 'error',
            'message' => "We're experiencing a temporary issue with our email system. Please contact us directly via WhatsApp at +250 781 234 567 or try again later."
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

