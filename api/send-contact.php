<?php
require_once __DIR__ . '/../config/recaptcha.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Path to PHPMailer
$phpmailerPath = __DIR__ . '/../ecotours/PHPMailer/src/';
if (file_exists($phpmailerPath . 'Exception.php') && file_exists($phpmailerPath . 'PHPMailer.php') && file_exists($phpmailerPath . 'SMTP.php')) {
    require_once $phpmailerPath . 'Exception.php';
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';
} elseif (file_exists(__DIR__ . '/../homestay/vendor/autoload.php')) {
    require_once __DIR__ . '/../homestay/vendor/autoload.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Validate reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    echo json_encode(['status' => 'error', 'message' => 'Please complete the reCAPTCHA']);
    exit;
}

if (!verify_recaptcha($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '')) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed']);
    exit;
}

// Get form data
$fname = trim($_POST['fname'] ?? '');
$lname = trim($_POST['lname'] ?? '');
$name = trim($_POST['name'] ?? ($fname . ' ' . $lname));
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate
if (empty($name) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
    exit;
}

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_EMAIL;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];

    // Send to admin
    $mail->setFrom(SMTP_EMAIL, 'Virunga Collective Website');
    $mail->addAddress('hello@virungacollective.com');
    $mail->addAddress('virungahomestay@gmail.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = "New Contact Form: " . $subject;

    $mail->Body = "
        <div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid #eee;'>
            <div style='background: #1b3a2b; padding: 25px 30px;'>
                <h2 style='color: #f6f2e9; margin: 0; font-family: \"Cormorant Garamond\", serif;'>New Message Received</h2>
            </div>
            <div style='padding: 40px 30px; background: white;'>
                <p style='color: #1f2620; font-size: 16px; margin-bottom: 24px;'>
                    You have received a new message from the Virunga Collective contact form.
                </p>
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 24px;'>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270; width: 120px;'>Name:</td><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620; font-weight: bold;'>" . htmlspecialchars($name) . "</td></tr>
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'>Email:</td><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #c9a24b; text-decoration: none;'>" . htmlspecialchars($email) . "</a></td></tr>
                    " . (!empty($phone) ? "<tr><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'>Phone:</td><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'>" . htmlspecialchars($phone) . "</td></tr>" : "") . "
                    <tr><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'>Subject:</td><td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'>" . htmlspecialchars($subject) . "</td></tr>
                </table>
                <div style='padding: 20px; background: #f6f2e9;'>
                    <strong style='display: block; margin-bottom: 12px; color: #1b3a2b;'>Message:</strong>
                    <p style='color: #1f2620; margin: 0; line-height: 1.6;'>" . nl2br(htmlspecialchars($message)) . "</p>
                </div>
            </div>
            <div style='background: #1b3a2b; padding: 20px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'>
                &copy; 2026 Virunga Collective. All rights reserved.
            </div>
        </div>
    ";
    $mail->AltBody = "New Message Received\n\nName: $name\nEmail: $email" . (!empty($phone) ? "\nPhone: $phone" : "") . "\nSubject: $subject\n\nMessage:\n$message";

    $mail->send();

    // Send confirmation to user
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->addAddress($email, $name);
    $mail->setFrom(SMTP_EMAIL, 'Virunga Collective');
    $mail->Subject = "Thank you for contacting Virunga Collective";

    $mail->Body = "
        <div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid #eee;'>
            <div style='background: #1b3a2b; padding: 25px 30px;'>
                <h2 style='color: #f6f2e9; margin: 0; font-family: \"Cormorant Garamond\", serif;'>Thank you, " . htmlspecialchars($name) . "!</h2>
            </div>
            <div style='padding: 40px 30px; background: white;'>
                <p style='color: #1f2620; font-size: 16px; line-height: 1.8;'>
                    We have received your message and will get back to you within 24 hours.
                </p>
                <div style='padding: 20px; background: #f6f2e9; border-left: 4px solid #c9a24b; margin: 24px 0;'>
                    <p style='margin: 0; color: #1f2620; font-style: italic;'>
                        \"Your journey to the heart of Rwanda is important to us.\"
                    </p>
                </div>
                <p style='color: #1f2620; font-size: 16px; line-height: 1.8;'>
                    In the meantime, feel free to explore our website or contact us via phone at +250 784 513 435.
                </p>
            </div>
            <div style='background: #1b3a2b; padding: 20px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'>
                Virunga Collective | Musanze, Rwanda
            </div>
        </div>
    ";
    $mail->AltBody = "Thank you, $name!\n\nWe have received your message and will get back to you within 24 hours.\n\nBest regards,\nThe Virunga Collective Team";

    $mail->send();

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    $errorLog = __DIR__ . '/contact-error.log';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] PHPMailer Error: " . $e->getMessage() . "\n";
    file_put_contents($errorLog, $logMessage, FILE_APPEND);
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while sending your message. Please try again later.']);
}

