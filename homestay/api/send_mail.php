&lt;?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

header('Content-Type: application/json');

// --- EMAIL CONFIGURATION ---
define('ADMIN_EMAIL', 'virungahomestay@gmail.com');
define('BUSINESS_NAME', 'Virunga Homestay');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate reCAPTCHA first
    $recaptchaSecret = '6LcJCDotAAAAAOnWISjoFTd_zJv6xdTgjA5Yq9YZ';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptchaResponse)) {
        echo json_encode([
            'status' =&gt; 'error',
            'message' =&gt; 'Please complete the reCAPTCHA'
        ]);
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
        echo json_encode([
            'status' =&gt; 'error',
            'message' =&gt; 'reCAPTCHA verification failed'
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
            'status' =&gt; 'error',
            'message' =&gt; 'Please fill in all required fields (Name, Email, Message).'
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail-&gt;isSMTP();
        $mail-&gt;Host = 'smtp.gmail.com';
        $mail-&gt;SMTPAuth = true;
        $mail-&gt;Username = 'fabrdaa@gmail.com';
        $mail-&gt;Password = 'mofrqznkhkthzfog';
        $mail-&gt;SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail-&gt;Port = 465;

        $mail-&gt;SMTPOptions = array(
            'ssl' =&gt; array(
                'verify_peer' =&gt; false,
                'verify_peer_name' =&gt; false,
                'allow_self_signed' =&gt; true
            )
        );

        // --- 1. SEND NOTIFICATION TO ADMIN ---
        $mail-&gt;setFrom('fabrdaa@gmail.com', BUSINESS_NAME . ' Website');
        $mail-&gt;addAddress(ADMIN_EMAIL); 
        $mail-&gt;addReplyTo($email, $name);

        $mail-&gt;isHTML(true);
        $mail-&gt;Subject = "New Website Inquiry: " . $subject;
        
        $mail-&gt;Body = "
            &lt;div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #fdfaf7; border: 1px solid #eee; border-radius: 12px; overflow: hidden;'&gt;
                &lt;div style='background: #c8711a; padding: 25px 30px;'&gt;
                   &lt;table style='width: 100%; border-collapse: collapse;'&gt;
                       &lt;tr&gt;
                           &lt;td style='vertical-align: middle; text-align: left;'&gt;
                               &lt;img src='https://virungahomestay.com/img/logo/logo.png' alt='Virunga Homestay' style='max-width: 120px;'&gt;
                           &lt;/td&gt;
                           &lt;td style='vertical-align: middle; text-align: right; color: #000000; font-size: 20px; font-weight: 300; letter-spacing: 0.05em;'&gt;
                               Virunga Homestay
                           &lt;/td&gt;
                       &lt;/tr&gt;
                   &lt;/table&gt;
                &lt;/div&gt;
                &lt;div style='padding: 40px 30px; background: #ffffff;'&gt;
                    &lt;h2 style='color: #150f0b; margin-top: 0;'&gt;New Inquiry Received&lt;/h2&gt;
                    &lt;p style='color: #666; font-size: 14px; margin-bottom: 30px;'&gt;You have a new message from your website contact form.&lt;/p&gt;
                    
                    &lt;table style='width: 100%; border-collapse: collapse;'&gt;
                        &lt;tr&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888; width: 100px;'&gt;From:&lt;/td&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333; font-weight: bold;'&gt;{$name}&lt;/td&gt;
                        &lt;/tr&gt;
                        &lt;tr&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'&gt;Email:&lt;/td&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0;'&gt;&lt;a href='mailto:{$email}' style='color: #c8711a; text-decoration: none;'&gt;{$email}&lt;/a&gt;&lt;/td&gt;
                        &lt;/tr&gt;
                        &lt;tr&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'&gt;Phone:&lt;/td&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'&gt;{$phone}&lt;/td&gt;
                        &lt;/tr&gt;
                        &lt;tr&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'&gt;Subject:&lt;/td&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'&gt;{$subject}&lt;/td&gt;
                        &lt;/tr&gt;
                        &lt;tr&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #888;'&gt;Source:&lt;/td&gt;
                            &lt;td style='padding: 10px 0; border-bottom: 1px solid #f0f0f0; color: #333;'&gt;{$source}&lt;/td&gt;
                        &lt;/tr&gt;
                    &lt;/table&gt;
                    
                    &lt;div style='margin-top: 30px; padding: 20px; background: #f9f4ef; border-radius: 8px; color: #444; line-height: 1.6;'&gt;
                        &lt;strong style='display: block; margin-bottom: 10px; color: #150f0b;'&gt;Message:&lt;/strong&gt;
                        " . nl2br(htmlspecialchars($message)) . "
                    &lt;/div&gt;
                &lt;/div&gt;
                &lt;div style='background: #fdfaf7; padding: 20px; text-align: center; font-size: 12px; color: #999;'&gt;
                    This inquiry was sent from the Virunga Homestay website contact form.
                &lt;/div&gt;
            &lt;/div&gt;
        ";
        $mail-&gt;AltBody = "New Inquiry Received\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\nSubject: {$subject}\nSource: {$source}\n\nMessage:\n{$message}";

        $mail-&gt;send();

        // --- 2. SEND CONFIRMATION TO USER ---
        $mail-&gt;clearAddresses();
        $mail-&gt;clearReplyTos();
        $mail-&gt;addAddress($email, $name);
        $mail-&gt;setFrom('fabrdaa@gmail.com', BUSINESS_NAME);
        
        $mail-&gt;Subject = "We've received your inquiry - " . BUSINESS_NAME;
        
        $mail-&gt;Body = "
            &lt;div style='max-width: 600px; margin: 0 auto; font-family: Arial, sans-serif; background: #fdfaf7; border: 1px solid #eee; border-radius: 12px; overflow: hidden;'&gt;
                &lt;div style='background: #150f0b; padding: 25px 30px;'&gt;
                   &lt;table style='width: 100%; border-collapse: collapse;'&gt;
                       &lt;tr&gt;
                           &lt;td style='vertical-align: middle; text-align: left;'&gt;
                               &lt;img src='https://virungahomestay.com/img/logo/logo.png' alt='Virunga Homestay' style='max-width: 120px;'&gt;
                           &lt;/td&gt;
                           &lt;td style='vertical-align: middle; text-align: right; color: #ffffff; font-size: 20px; font-weight: 300; letter-spacing: 0.05em;'&gt;
                               Virunga Homestay
                           &lt;/td&gt;
                       &lt;/tr&gt;
                   &lt;/table&gt;
                &lt;/div&gt;
                &lt;div style='padding: 40px 30px; background: #ffffff;'&gt;
                    &lt;h2 style='color: #150f0b; font-weight: 300; margin-top: 0;'&gt;Hello {$name},&lt;/h2&gt;
                    &lt;p style='color: #444; line-height: 1.8; font-size: 16px;'&gt;
                        Thank you for reaching out to &lt;strong&gt;" . BUSINESS_NAME . "&lt;/strong&gt;. We have successfully received your inquiry regarding &lt;em&gt;'{$subject}'&lt;/em&gt;.
                    &lt;/p&gt;
                    &lt;p style='color: #444; line-height: 1.8; font-size: 16px;'&gt;
                        Our team is currently reviewing your message and we will get back to you with a personal response within the next 24 hours.
                    &lt;/p&gt;
                    
                    &lt;div style='background: #f9f4ef; border-left: 4px solid #c8711a; padding: 20px; margin: 30px 0; border-radius: 4px;'&gt;
                        &lt;p style='margin: 0; color: #555; font-size: 15px; font-style: italic;'&gt;
                            \"Your journey to the heart of Rwanda is important to us. We're excited to help you plan your perfect stay.\"
                        &lt;/p&gt;
                    &lt;/div&gt;
                    
                    &lt;p style='color: #444; line-height: 1.8; font-size: 16px;'&gt;
                        In the meantime, feel free to explore our curated experiences or check out our rooms.
                    &lt;/p&gt;
                    
                    &lt;div style='margin-top: 40px; text-align: center;'&gt;
                        &lt;a href='https://virungahomestay.com/rooms' style='background: #c8711a; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 50px 50px 0 0; font-weight: 600; display: inline-block; letter-spacing: 0.1em; text-transform: uppercase; font-size: 12px; margin: 5px;'&gt;Explore Rooms&lt;/a&gt;
                        &lt;a href='https://virungahomestay.com/activity' style='background: #150f0b; color: #ffffff; padding: 16px 32px; text-decoration: none; border-radius: 50px; font-weight: 600; display: inline-block; letter-spacing: 0.1em; text-transform: uppercase; font-size: 12px; margin: 5px;'&gt;Community Activities&lt;/a&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
               
               &lt;div style='background: #fdfaf7; padding: 30px; text-align: center; border-top: 1px solid #eee;'&gt;
                   &lt;p style='margin: 0 0 15px; color: #888; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 600;'&gt;Get in touch&lt;/p&gt;
                   &lt;div style='display: inline-block; margin: 0 10px; color: #150f0b; font-size: 13px;'&gt;
                       &lt;span style='color: #c8711a;'&gt;Phone:&lt;/span&gt; +250 784 513 435
                   &lt;/div&gt;
                   &lt;div style='display: inline-block; margin: 0 10px; color: #150f0b; font-size: 13px;'&gt;
                       &lt;span style='color: #c8711a;'&gt;Email:&lt;/span&gt; virungahomestay@gmail.com
                   &lt;/div&gt;
                   &lt;p style='margin-top: 25px; color: #999; font-size: 11px; line-height: 1.5;'&gt;
                       Musanze, Northern Province, Rwanda&lt;br&gt;
                       &amp;copy; " . date('Y') . " Virunga Homestay. All rights reserved.
                   &lt;/p&gt;
               &lt;/div&gt;
            &lt;/div&gt;
        ";
        $mail-&gt;AltBody = "Hello {$name},\n\nThank you for reaching out to Virunga Homestay. We have received your inquiry regarding '{$subject}'. Our team will get back to you within 24 hours.\n\nWarm regards,\nThe Virunga Homestay Team";

        $mail-&gt;send();

        echo json_encode(['status' =&gt; 'success']);

    } catch (Exception $e) {
        $errorFile = dirname(__DIR__) . "/error_log.txt";
        $logMessage = "[" . date('Y-m-d H:i:s') . "] PHPMailer Error: " . $mail-&gt;ErrorInfo . " | Exception: " . $e-&gt;getMessage() . "\n";
        file_put_contents($errorFile, $logMessage, FILE_APPEND);

        echo json_encode([
            'status' =&gt; 'error',
            'message' =&gt; "We're experiencing a temporary issue with our email system. Please contact us directly via WhatsApp at +250 781 234 567 or try again later."
        ]);
    }
} else {
    echo json_encode([
        'status' =&gt; 'error',
        'message' =&gt; 'Invalid request method.'
    ]);
}
