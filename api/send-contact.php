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
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Read input (handles both JSON and multipart/form-data POST)
$inputData = [];
if (!empty($_POST)) {
    $inputData = $_POST;
} else {
    $rawInput = file_get_contents('php://input');
    if (!empty($rawInput)) {
        $json = json_decode($rawInput, true);
        if (is_array($json)) {
            $inputData = $json;
        }
    }
}

// Validate reCAPTCHA
$recaptchaResponse = $inputData['g-recaptcha-response'] ?? '';
if (empty($recaptchaResponse)) {
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Please complete the reCAPTCHA verification.']);
    exit;
}
if (!verify_recaptcha($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '')) {
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'reCAPTCHA verification failed. Please try again.']);
    exit;
}

// Get form data
$fname = trim($inputData['fname'] ?? '');
$lname = trim($inputData['lname'] ?? '');
$name = trim($inputData['name'] ?? ($fname . ' ' . $lname));
$email = trim($inputData['email'] ?? '');
$phone = trim($inputData['phone'] ?? '');
$subject = trim($inputData['subject'] ?? 'New Journey Designer Request');
$message = trim($inputData['message'] ?? '');

// Validate
if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'Please enter a valid email address']);
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

    // Send to both admin recipients
    $mail->setFrom(SMTP_EMAIL, 'Virunga Collective Journeys');
    $mail->addAddress('info@virungajourneys.com', 'Virunga Journeys Concierge');
    $mail->addAddress('virungahomestay@gmail.com', 'Virunga Homestay Operations');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = "New Journey Inquiry: " . $subject;

    $mail->Body = "
        <div style='max-width: 620px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif; background: #fbf9f4; border: 1px solid #e2ded5;'>
            <div style='background: #122a1f; padding: 28px 32px; border-bottom: 2px solid #c9a24b;'>
                <span style='color: #c9a24b; font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; font-weight: 600; display: block; margin-bottom: 6px;'>Virunga Collective · Bespoke Concierge</span>
                <h2 style='color: #f6f2e9; margin: 0; font-size: 22px; font-family: Georgia, serif;'>New Journey Request Received</h2>
            </div>
            <div style='padding: 36px 32px; background: #ffffff;'>
                <p style='color: #2b332c; font-size: 15px; margin-bottom: 24px; line-height: 1.6;'>
                    A traveler has submitted an inquiry through the <strong>Design Your Virunga Journey</strong> concierge planner.
                </p>
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 28px; font-size: 14px;'>
                    <tr>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #5a6e60; width: 130px; font-weight: 600;'>Traveler Name:</td>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #122a1f; font-weight: bold;'>" . htmlspecialchars($name) . "</td>
                    </tr>
                    <tr>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #5a6e60;'>Email Address:</td>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #122a1f;'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #b08d38; text-decoration: none; font-weight: 600;'>" . htmlspecialchars($email) . "</a></td>
                    </tr>
                    " . (!empty($phone) ? "<tr><td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #5a6e60;'>WhatsApp / Phone:</td><td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #122a1f; font-weight: 600;'>" . htmlspecialchars($phone) . "</td></tr>" : "") . "
                    <tr>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #5a6e60;'>Journey Scope:</td>
                        <td style='padding: 11px 0; border-bottom: 1px solid #eee; color: #122a1f; font-weight: 600;'>" . htmlspecialchars($subject) . "</td>
                    </tr>
                </table>
                <div style='padding: 22px; background: #fbf9f4; border-left: 3px solid #c9a24b;'>
                    <strong style='display: block; margin-bottom: 10px; color: #122a1f; font-size: 13px; letter-spacing: 0.08em; text-transform: uppercase;'>Inquiry Notes & Vision:</strong>
                    <p style='color: #2b332c; margin: 0; line-height: 1.7; font-size: 14px; white-space: pre-wrap;'>" . nl2br(htmlspecialchars($message)) . "</p>
                </div>
            </div>
            <div style='background: #122a1f; padding: 20px 32px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'>
                &copy; 2026 Virunga Collective · Private Journeys & Bespoke Hospitality · Rwanda
            </div>
        </div>
    ";
    $mail->AltBody = "New Journey Request\n\nName: $name\nEmail: $email" . (!empty($phone) ? "\nPhone: $phone" : "") . "\nSubject: $subject\n\nMessage:\n$message";

    $mail->send();

    // Persist submission in database
    try {
        require_once __DIR__ . '/../ecotours/admin/config/database.php';
        if (isset($pdo)) {
            $dbStmt = $pdo->prepare("INSERT INTO contact_submissions (first_name, last_name, email, phone, subject, message, ip_address, emailed) VALUES (:fname, :lname, :email, :phone, :subject, :message, :ip, 1)");
            $dbStmt->execute([
                ':fname' => $fname,
                ':lname' => $lname,
                ':email' => $email,
                ':phone' => $phone,
                ':subject' => $subject,
                ':message' => $message,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ]);
        }
    } catch (\Throwable $dbe) {
        error_log("Database save failed in send-contact.php: " . $dbe->getMessage());
    }

    // Require localization helper for multilingual email receipt
    if (file_exists(__DIR__ . '/../config/localization.php')) {
        require_once __DIR__ . '/../config/localization.php';
        $userLang = $inputData['user_lang'] ?? null;
        $details = "<strong>Subject:</strong> " . htmlspecialchars($subject) . "<br><strong>Message:</strong> " . nl2br(htmlspecialchars($message));
        send_multilingual_confirmation_email($email, $name, $subject, $details, $userLang);
    }

    echo json_encode(['status' => 'success', 'success' => true]);

} catch (Exception $e) {
    $errorLog = __DIR__ . '/contact-error.log';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] PHPMailer Error: " . $e->getMessage() . "\n";
    file_put_contents($errorLog, $logMessage, FILE_APPEND);
    echo json_encode(['status' => 'error', 'success' => false, 'message' => 'An error occurred while sending your message. Please try again later.']);
}

