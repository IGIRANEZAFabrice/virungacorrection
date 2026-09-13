<?php
require_once __DIR__ . '/../config/recaptcha.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// Include PHPMailer
$phpmailerPath = __DIR__ . '/../ecotours/PHPMailer/src/';
$mailLoaded = false;
if (file_exists($phpmailerPath . 'Exception.php') && file_exists($phpmailerPath . 'PHPMailer.php') && file_exists($phpmailerPath . 'SMTP.php')) {
    require_once $phpmailerPath . 'Exception.php';
    require_once $phpmailerPath . 'PHPMailer.php';
    require_once $phpmailerPath . 'SMTP.php';
    $mailLoaded = true;
} elseif (file_exists(__DIR__ . '/../homestay/vendor/autoload.php')) {
    require_once __DIR__ . '/../homestay/vendor/autoload.php';
    $mailLoaded = true;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// Validate reCAPTCHA
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (empty($recaptchaResponse)) {
    echo json_encode(['status' => 'error', 'message' => 'Please complete the reCAPTCHA verification']);
    exit;
}

if (!verify_recaptcha($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '')) {
    echo json_encode(['status' => 'error', 'message' => 'reCAPTCHA verification failed. Please try again.']);
    exit;
}

// Get form data
$firstName = trim($_POST['firstName'] ?? '');
$lastName = trim($_POST['lastName'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$interest = trim($_POST['interest'] ?? 'Explorer');
$country = trim($_POST['country'] ?? '');

if (empty($firstName) || empty($lastName) || empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'First name, last name, and email are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

$mailSent = false;
$mailError = '';

if ($mailLoaded) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_EMAIL;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        // Send to admin
        $mail->setFrom(SMTP_EMAIL, 'Virunga Collective Membership');
        $mail->addAddress(SMTP_EMAIL);
        $mail->addAddress('info@virungajourneys.com');
        $mail->addAddress('virungahomestay@gmail.com');
        $mail->addReplyTo($email, "$firstName $lastName");

        $mail->isHTML(true);
        $mail->Subject = "New Membership Application: $firstName $lastName ($interest)";
        
        $mail->Body = "
            <div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #f6f2e9; border: 1px solid #eee; border-radius: 8px; overflow: hidden;'>
                <div style='background: #1b3a2b; padding: 25px 30px; text-align: center;'>
                    <h2 style='color: #c9a24b; margin: 0; font-family: \"Cormorant Garamond\", serif; font-size: 24px;'>New Membership Application</h2>
                    <p style='color: #f6f2e9; margin: 5px 0 0; font-size: 14px;'>Virunga Collective Ecosystem</p>
                </div>
                <div style='padding: 30px; background: #ffffff;'>
                    <p style='color: #1f2620; font-size: 15px; margin-bottom: 20px;'>
                        A new membership registration has been submitted on the website.
                    </p>
                    <table style='width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 14px;'>
                        <tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; width: 140px; font-weight: bold;'>Applicant Name:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1f2620; font-weight: bold;'>" . htmlspecialchars("$firstName $lastName") . "</td></tr>
                        <tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; font-weight: bold;'>Email Address:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1f2620;'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #c9a24b; text-decoration: none;'>" . htmlspecialchars($email) . "</a></td></tr>
                        " . (!empty($phone) ? "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; font-weight: bold;'>Phone:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1f2620;'>" . htmlspecialchars($phone) . "</td></tr>" : "") . "
                        " . (!empty($country) ? "<tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; font-weight: bold;'>Country:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1f2620;'>" . htmlspecialchars($country) . "</td></tr>" : "") . "
                        <tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; font-weight: bold;'>Membership Tier:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1b3a2b; font-weight: bold; font-size: 16px;'>" . htmlspecialchars($interest) . "</td></tr>
                        <tr><td style='padding: 10px; border-bottom: 1px solid #eee; color: #6e8270; font-weight: bold;'>Submitted Date:</td><td style='padding: 10px; border-bottom: 1px solid #eee; color: #1f2620;'>" . date('Y-m-d H:i:s') . "</td></tr>
                    </table>
                </div>
                <div style='background: #1b3a2b; padding: 15px; text-align: center; font-size: 12px; color: rgba(246,242,233,0.7);'>
                    &copy; " . date('Y') . " Virunga Collective. All rights reserved.
                </div>
            </div>
        ";

        $mail->AltBody = "New Membership Application\n\nName: $firstName $lastName\nEmail: $email\nPhone: $phone\nCountry: $country\nTier: $interest\nDate: " . date('Y-m-d H:i:s');

        $mail->send();
        $mailSent = true;

        // Persist application in database
        try {
            require_once __DIR__ . '/../ecotours/admin/config/database.php';
            if (isset($pdo)) {
                $dbStmt = $pdo->prepare("INSERT INTO membership_applications (first_name, last_name, email, phone, interest_tier, country, ip_address, status, emailed) VALUES (:fname, :lname, :email, :phone, :interest, :country, :ip, 'pending', 1)");
                $dbStmt->execute([
                    ':fname' => $firstName,
                    ':lname' => $lastName,
                    ':email' => $email,
                    ':phone' => $phone,
                    ':interest' => $interest,
                    ':country' => $country,
                    ':ip' => $_SERVER['REMOTE_ADDR'] ?? ''
                ]);
            }
        } catch (\Throwable $dbe) {
            error_log("Database save failed in join-membership.php: " . $dbe->getMessage());
        }

        // Send multilingual confirmation email to applicant
        if (file_exists(__DIR__ . '/../config/localization.php')) {
            require_once __DIR__ . '/../config/localization.php';
            $userLang = $_POST['user_lang'] ?? null;
            $details = "<strong>Applicant Name:</strong> " . htmlspecialchars("$firstName $lastName") . "<br><strong>Tier Requested:</strong> " . htmlspecialchars($interest);
            send_multilingual_confirmation_email($email, "$firstName $lastName", "Membership Application - $interest", $details, $userLang);
        }

    } catch (Exception $e) {
        $mailError = $e->getMessage();
    }
}

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you! Your membership application has been submitted successfully.',
    'firstName' => $firstName,
    'lastName' => $lastName,
    'interest' => $interest
]);
exit;
