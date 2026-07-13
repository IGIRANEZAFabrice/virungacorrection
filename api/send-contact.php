
&lt;?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Path to PHPMailer
$phpmailerPath = __DIR__ . '/../ecotours/PHPMailer/src/';
if (file_exists($phpmailerPath . 'Exception.php') &amp;&amp; file_exists($phpmailerPath . 'PHPMailer.php') &amp;&amp; file_exists($phpmailerPath . 'SMTP.php')) {
    require_once $phpmailerPath . 'Exception.php';
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';
} elseif (file_exists(__DIR__ . '/../homestay/vendor/autoload.php')) {
    require_once __DIR__ . '/../homestay/vendor/autoload.php';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'Invalid request method']);
    exit;
}

// Validate reCAPTCHA
$recaptchaSecret = '6LcJCDotAAAAAOnWISjoFTd_zJv6xdTgjA5Yq9YZ';
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'Please complete the reCAPTCHA']);
    exit;
}

$verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
$verifyData = [
    'secret' =&gt; $recaptchaSecret,
    'response' =&gt; $recaptchaResponse,
    'remoteip' =&gt; $_SERVER['REMOTE_ADDR'] ?? ''
];

$options = [
    'http' =&gt; [
        'header' =&gt; "Content-type: application/x-www-form-urlencoded\r\n",
        'method' =&gt; 'POST',
        'content' =&gt; http_build_query($verifyData)
    ]
];
$context = stream_context_create($options);
$verifyResult = file_get_contents($verifyUrl, false, $context);
$verifyJson = json_decode($verifyResult);

if (!$verifyJson || !$verifyJson-&gt;success) {
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'reCAPTCHA verification failed']);
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
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'Please fill in all required fields']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'Please enter a valid email address']);
    exit;
}

try {
    $mail = new PHPMailer(true);
    $mail-&gt;isSMTP();
    $mail-&gt;Host = 'smtp.gmail.com';
    $mail-&gt;SMTPAuth = true;
    $mail-&gt;Username = 'fabrdaa@gmail.com';
    $mail-&gt;Password = 'mofrqznkhkthzfog';
    $mail-&gt;SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail-&gt;Port = 465;
    $mail-&gt;SMTPOptions = [
        'ssl' =&gt; [
            'verify_peer' =&gt; false,
            'verify_peer_name' =&gt; false,
            'allow_self_signed' =&gt; true
        ]
    ];

    // Send to admin
    $mail-&gt;setFrom('fabrdaa@gmail.com', 'Virunga Collective Website');
    $mail-&gt;addAddress('hello@virungacollective.com');
    $mail-&gt;addAddress('virungahomestay@gmail.com');
    $mail-&gt;addReplyTo($email, $name);
    $mail-&gt;isHTML(true);
    $mail-&gt;Subject = "New Contact Form: " . $subject;

    $mail-&gt;Body = "
        &lt;div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid #eee;'&gt;
            &lt;div style='background: #1b3a2b; padding: 25px 30px;'&gt;
                &lt;h2 style='color: #f6f2e9; margin: 0; font-family: \"Cormorant Garamond\", serif;'&gt;New Message Received&lt;/h2&gt;
            &lt;/div&gt;
            &lt;div style='padding: 40px 30px; background: white;'&gt;
                &lt;p style='color: #1f2620; font-size: 16px; margin-bottom: 24px;'&gt;
                    You have received a new message from the Virunga Collective contact form.
                &lt;/p&gt;
                &lt;table style='width: 100%; border-collapse: collapse; margin-bottom: 24px;'&gt;
                    &lt;tr&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270; width: 120px;'&gt;Name:&lt;/td&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620; font-weight: bold;'&gt;" . htmlspecialchars($name) . "&lt;/td&gt;&lt;/tr&gt;
                    &lt;tr&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'&gt;Email:&lt;/td&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'&gt;&lt;a href='mailto:" . htmlspecialchars($email) . "' style='color: #c9a24b; text-decoration: none;'&gt;" . htmlspecialchars($email) . "&lt;/a&gt;&lt;/td&gt;&lt;/tr&gt;
                    " . (!empty($phone) ? "&lt;tr&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'&gt;Phone:&lt;/td&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'&gt;" . htmlspecialchars($phone) . "&lt;/td&gt;&lt;/tr&gt;" : "") . "
                    &lt;tr&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #6e8270;'&gt;Subject:&lt;/td&gt;&lt;td style='padding: 10px 0; border-bottom: 1px solid #eee; color: #1f2620;'&gt;" . htmlspecialchars($subject) . "&lt;/td&gt;&lt;/tr&gt;
                &lt;/table&gt;
                &lt;div style='padding: 20px; background: #f6f2e9;'&gt;
                    &lt;strong style='display: block; margin-bottom: 12px; color: #1b3a2b;'&gt;Message:&lt;/strong&gt;
                    &lt;p style='color: #1f2620; margin: 0; line-height: 1.6;'&gt;" . nl2br(htmlspecialchars($message)) . "&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
            &lt;div style='background: #1b3a2b; padding: 20px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'&gt;
                &amp;copy; 2026 Virunga Collective. All rights reserved.
            &lt;/div&gt;
        &lt;/div&gt;
    ";
    $mail-&gt;AltBody = "New Message Received\n\nName: $name\nEmail: $email" . (!empty($phone) ? "\nPhone: $phone" : "") . "\nSubject: $subject\n\nMessage:\n$message";

    $mail-&gt;send();

    // Send confirmation to user
    $mail-&gt;clearAddresses();
    $mail-&gt;clearReplyTos();
    $mail-&gt;addAddress($email, $name);
    $mail-&gt;setFrom('fabrdaa@gmail.com', 'Virunga Collective');
    $mail-&gt;Subject = "Thank you for contacting Virunga Collective";

    $mail-&gt;Body = "
        &lt;div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid #eee;'&gt;
            &lt;div style='background: #1b3a2b; padding: 25px 30px;'&gt;
                &lt;h2 style='color: #f6f2e9; margin: 0; font-family: \"Cormorant Garamond\", serif;'&gt;Thank you, " . htmlspecialchars($name) . "!&lt;/h2&gt;
            &lt;/div&gt;
            &lt;div style='padding: 40px 30px; background: white;'&gt;
                &lt;p style='color: #1f2620; font-size: 16px; line-height: 1.8;'&gt;
                    We have received your message and will get back to you within 24 hours.
                &lt;/p&gt;
                &lt;div style='padding: 20px; background: #f6f2e9; border-left: 4px solid #c9a24b; margin: 24px 0;'&gt;
                    &lt;p style='margin: 0; color: #1f2620; font-style: italic;'&gt;
                        \"Your journey to the heart of Rwanda is important to us.\"
                    &lt;/p&gt;
                &lt;/div&gt;
                &lt;p style='color: #1f2620; font-size: 16px; line-height: 1.8;'&gt;
                    In the meantime, feel free to explore our website or contact us via phone at +250 784 513 435.
                &lt;/p&gt;
            &lt;/div&gt;
            &lt;div style='background: #1b3a2b; padding: 20px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'&gt;
                Virunga Collective | Musanze, Rwanda
            &lt;/div&gt;
        &lt;/div&gt;
    ";
    $mail-&gt;AltBody = "Thank you, $name!\n\nWe have received your message and will get back to you within 24 hours.\n\nBest regards,\nThe Virunga Collective Team";

    $mail-&gt;send();

    echo json_encode(['status' =&gt; 'success']);

} catch (Exception $e) {
    $errorLog = __DIR__ . '/contact-error.log';
    $logMessage = "[" . date('Y-m-d H:i:s') . "] PHPMailer Error: " . $e-&gt;getMessage() . "\n";
    file_put_contents($errorLog, $logMessage, FILE_APPEND);
    echo json_encode(['status' =&gt; 'error', 'message' =&gt; 'An error occurred while sending your message. Please try again later.']);
}
