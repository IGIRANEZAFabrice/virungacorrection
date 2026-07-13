<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Load PHPMailer
$rootDir = dirname(__DIR__, 2);

if (file_exists($rootDir . '/vendor/autoload.php')) {
    require_once $rootDir . '/vendor/autoload.php';
}

if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    $phpmailerFiles = [
        $rootDir . '/PHPMailer/src/Exception.php',
        $rootDir . '/PHPMailer/src/PHPMailer.php',
        $rootDir . '/PHPMailer/src/SMTP.php',
    ];
    foreach ($phpmailerFiles as $file) {
        if (!file_exists($file)) {
            echo json_encode(['status' => 'error', 'message' => 'Mail system unavailable.']);
            exit;
        }
        require_once $file;
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Validate reCAPTCHA
$recaptchaSecret = '6LcJCDotAAAAAOnWISjoFTd_zJv6xdTgjA5Yq9YZ';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    echo json_encode(['status' => 'error', 'message' => 'Please complete the reCAPTCHA']);
    exit;
}

$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$verifyData = [
    'secret'   => $recaptchaSecret,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($verifyData)
    ]
];
$context      = stream_context_create($options);
$verifyResult = file_get_contents($verifyUrl, false, $context);
$verifyJson   = json_decode($verifyResult);

if (!$verifyJson || !$verifyJson->success) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed']);
    exit;
}

// Get form data
$fname   = trim($_POST['fname'] ?? '');
$lname   = trim($_POST['lname'] ?? '');
$name    = trim($fname . ' ' . $lname);
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$program = trim($_POST['program'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if (empty($fname) || empty($lname) || empty($email) || empty($program) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address']);
    exit;
}

// SMTP config (same as contactUsHandlers.php)
$email_config = [
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port'     => 587,
    'smtp_username' => 'virungahomestay@gmail.com',
    'smtp_password' => 'mvkumfdesmiedtnl',
    'from_email'    => 'virungahomestay@gmail.com',
    'from_name'     => 'Virunga Ecotours Training',
];

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $email_config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $email_config['smtp_username'];
    $mail->Password   = $email_config['smtp_password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $email_config['smtp_port'];

    // Send notification to admin
    $mail->setFrom($email_config['from_email'], $email_config['from_name']);
    $mail->addAddress('virungahomestay@gmail.com');
    $mail->addAddress('info@virungaecotours.com');
    $mail->addReplyTo($email, $name);
    $mail->isHTML(true);
    $mail->Subject = 'VETI Training Inquiry - ' . htmlspecialchars($name);

    $mail->Body = '
    <div style="background:#f6f8fb;padding:24px;font-family:\'Helvetica Neue\',Arial,sans-serif;color:#1f2a36;">
      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width:620px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,0.06);">
        <tr>
          <td style="padding:28px 28px 12px;text-align:center;">
            <img src="https://www.virungaecotours.com/images/logos/icon.png" alt="Virunga Ecotours" width="72" style="display:block;margin:0 auto 12px;">
            <div style="font-size:14px;letter-spacing:0.4px;color:#61707f;">VETI Training Inquiry</div>
          </td>
        </tr>
        <tr>
          <td style="padding:8px 28px 0;">
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1f2a36;">New Training Inquiry</h1>
            <p style="margin:12px 0 0;font-size:15px;line-height:1.6;color:#2f3b47;">A new inquiry has been submitted via the VETI Training page.</p>
          </td>
        </tr>
        <tr>
          <td style="padding:18px 28px;">
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;">
              <tr><td style="padding:10px 0;border-bottom:1px solid #eee;color:#6b7a8a;width:140px;">Name:</td><td style="padding:10px 0;border-bottom:1px solid #eee;color:#1f2a36;font-weight:600;">' . htmlspecialchars($name) . '</td></tr>
              <tr><td style="padding:10px 0;border-bottom:1px solid #eee;color:#6b7a8a;">Email:</td><td style="padding:10px 0;border-bottom:1px solid #eee;"><a href="mailto:' . htmlspecialchars($email) . '" style="color:#1f7a5a;text-decoration:none;">' . htmlspecialchars($email) . '</a></td></tr>
              ' . (!empty($phone) ? '<tr><td style="padding:10px 0;border-bottom:1px solid #eee;color:#6b7a8a;">Phone:</td><td style="padding:10px 0;border-bottom:1px solid #eee;color:#1f2a36;">' . htmlspecialchars($phone) . '</td></tr>' : '') . '
              <tr><td style="padding:10px 0;border-bottom:1px solid #eee;color:#6b7a8a;">Program:</td><td style="padding:10px 0;border-bottom:1px solid #eee;color:#1f2a36;font-weight:600;">' . htmlspecialchars($program) . '</td></tr>
            </table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 28px 18px;">
            <div style="padding:16px;background:#f0f6f2;border-radius:8px;border-left:4px solid #016905;">
              <strong style="display:block;margin-bottom:8px;color:#016905;">Message:</strong>
              <p style="margin:0;font-size:15px;line-height:1.7;color:#2f3b47;">' . nl2br(htmlspecialchars($message)) . '</p>
            </div>
          </td>
        </tr>
        <tr>
          <td style="padding:0 28px 24px;">
            <p style="margin:0;font-size:12px;color:#999;">Sent from the VETI Training page on ' . date('F j, Y \a\t g:i A') . '</p>
          </td>
        </tr>
      </table>
    </div>';

    $mail->AltBody = "New VETI Training Inquiry\n\nName: $name\nEmail: $email" . (!empty($phone) ? "\nPhone: $phone" : "") . "\nProgram: $program\n\nMessage:\n$message";

    $mail->send();

    // Send confirmation to user
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->addAddress($email, $name);
    $mail->setFrom($email_config['from_email'], 'Virunga Ecotours Training');
    $mail->Subject = 'Thank you for your VETI Training inquiry';

    $mail->Body = '
    <div style="background:#f6f8fb;padding:24px;font-family:\'Helvetica Neue\',Arial,sans-serif;color:#1f2a36;">
      <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width:620px;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 6px 24px rgba(0,0,0,0.06);">
        <tr>
          <td style="padding:28px 28px 12px;text-align:center;">
            <img src="https://www.virungaecotours.com/images/logos/icon.png" alt="Virunga Ecotours" width="72" style="display:block;margin:0 auto 12px;">
            <div style="font-size:14px;letter-spacing:0.4px;color:#61707f;">Virunga Ecotours Training Institute</div>
          </td>
        </tr>
        <tr>
          <td style="padding:8px 28px 0;">
            <h1 style="margin:0;font-size:22px;font-weight:700;color:#1f2a36;">Hi ' . htmlspecialchars($fname) . ', thanks for your interest!</h1>
            <p style="margin:12px 0 0;font-size:15px;line-height:1.6;color:#2f3b47;">We received your inquiry about the <strong>' . htmlspecialchars($program) . '</strong> program. Our training team will review your submission and get back to you shortly.</p>
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
            <p style="margin:0;font-size:13px;line-height:1.6;color:#6b7a8a;">We\'re excited about your interest in building a career in sustainable tourism!</p>
          </td>
        </tr>
      </table>
    </div>';

    $mail->AltBody = "Hi $fname,\n\nThank you for your interest in the $program program at VETI.\n\nWe have received your inquiry and will get back to you shortly.\n\nBest regards,\nThe VETI Training Team\nVirunga Ecotours";

    $mail->send();

    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    $logFile = dirname(__DIR__, 2) . '/tour_booking_email_errors.log';
    $logMsg  = '[' . date('Y-m-d H:i:s') . '] Training Contact Error: ' . $e->getMessage() . PHP_EOL;
    file_put_contents($logFile, $logMsg, FILE_APPEND | LOCK_EX);
    echo json_encode(['status' => 'error', 'message' => 'An error occurred while sending your message. Please try again later.']);
}
