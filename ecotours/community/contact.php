
&lt;?php
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

function logCommunityEmailMessage($message) {
    $logFile = dirname(__DIR__) . '/tour_booking_email_errors.log';
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function sendCommunityNotificationEmail($recipientEmail, $subject, $bodyHtml) {
    $email_config = [
        'smtp_host' =&gt; 'smtp.gmail.com',
        'smtp_port' =&gt; 587,
        'smtp_username' =&gt; 'virungahomestay@gmail.com',
        'smtp_password' =&gt; 'mvkumfdesmiedtnl',
        'from_email' =&gt; 'virungahomestay@gmail.com',
        'from_name' =&gt; 'Virunga Ecotours System',
    ];

    try {
        ensurePHPMailerLoaded();

        $mail = new PHPMailer(true);
        $mail-&gt;isSMTP();
        $mail-&gt;Host = $email_config['smtp_host'];
        $mail-&gt;SMTPAuth = true;
        $mail-&gt;Username = $email_config['smtp_username'];
        $mail-&gt;Password = $email_config['smtp_password'];
        $mail-&gt;SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail-&gt;Port = $email_config['smtp_port'];

        $mail-&gt;setFrom($email_config['from_email'], $email_config['from_name']);
        $mail-&gt;addReplyTo($email_config['from_email'], $email_config['from_name']);
        $mail-&gt;addAddress($recipientEmail);

        $mail-&gt;isHTML(true);
        $mail-&gt;Subject = $subject;
        $mail-&gt;Body = $bodyHtml;
        $mail-&gt;AltBody = strip_tags($bodyHtml);

        $mail-&gt;send();
        return true;
    } catch (\Throwable $e) {
        logCommunityEmailMessage('Email send failed to ' . $recipientEmail . ': ' . $e-&gt;getMessage());
        return false;
    }
}

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate reCAPTCHA first
    $recaptchaSecret = '6LcJCDotAAAAAOnWISjoFTd_zJv6xdTgjA5Yq9YZ';
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptchaResponse)) {
        $error_message = 'Please complete the reCAPTCHA.';
    } else {
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
            $error_message = 'reCAPTCHA verification failed. Please try again.';
        } else {
            // Sanitize and validate input
            $name = mysqli_real_escape_string($conn, trim($_POST['name']));
            $email = mysqli_real_escape_string($conn, trim($_POST['email']));
            $subject = mysqli_real_escape_string($conn, trim($_POST['subject']));
            $message = mysqli_real_escape_string($conn, trim($_POST['message']));
            $phone = mysqli_real_escape_string($conn, trim($_POST['phone'] ?? ''));
            $country = mysqli_real_escape_string($conn, trim($_POST['country'] ?? ''));
            $program_interest = mysqli_real_escape_string($conn, trim($_POST['program_interest'] ?? ''));
            $volunteer_interest = isset($_POST['volunteer_interest']) ? 1 : 0;
            $donation_interest = isset($_POST['donation_interest']) ? 1 : 0;
            
            // Get client information
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $user_agent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT']);
            
            // Validate required fields
            if (empty($name) || empty($email) || empty($message)) {
                $error_message = 'Please fill in all required fields.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_message = 'Please enter a valid email address.';
            } else {
                // Insert message into database
                $insert_query = "INSERT INTO community_messages 
                    (name, email, subject, message, phone, country, program_interest, volunteer_interest, donation_interest, ip_address, user_agent) 
                    VALUES 
                    ('$name', '$email', '$subject', '$message', '$phone', '$country', '$program_interest', $volunteer_interest, $donation_interest, '$ip_address', '$user_agent')";
                
                if (mysqli_query($conn, $insert_query)) {
                    // Send email notifications (both recipients) right after DB save.
                    $recipients = ['virungahomestay@gmail.com', 'info@virungaecotours.com'];

                    $emailBody = '
                        &lt;h2 style="color:#2a4858;"&gt;New Community Contact Message&lt;/h2&gt;
                        &lt;p&gt;
                            User &lt;strong&gt;' . htmlspecialchars($name) . '&lt;/strong&gt;
                            (email: &lt;strong&gt;' . htmlspecialchars($email) . '&lt;/strong&gt;)
                            contacted you on the community saying:&lt;br/&gt;
                            "&lt;em&gt;' . nl2br(htmlspecialchars($message)) . '&lt;/em&gt;"
                        &lt;/p&gt;
                        &lt;p&gt;&lt;strong&gt;Subject:&lt;/strong&gt; ' . htmlspecialchars($subject) . '&lt;/p&gt;
                        &lt;p&gt;&lt;strong&gt;Program interest:&lt;/strong&gt; ' . htmlspecialchars($program_interest) . '&lt;/p&gt;
                    ';

                    $subjectEmail = 'New Community Contact - ' . htmlspecialchars($name);
                    $failedRecipients = [];
                    foreach ($recipients as $recipientEmail) {
                        if (!sendCommunityNotificationEmail($recipientEmail, $subjectEmail, $emailBody)) {
                            $failedRecipients[] = $recipientEmail;
                        }
                    }
                    if (!empty($failedRecipients)) {
                        logCommunityEmailMessage('Some notification emails failed: ' . implode(', ', $failedRecipients));
                    }

                    $message_sent = true;
                } else {
                    $error_message = 'Sorry, there was an error sending your message. Please try again.';
                }
            }
        }
    }
}

// Get action parameter for pre-filling form
$action = isset($_GET['action']) ? $_GET['action'] : '';
?&gt;

&lt;!DOCTYPE html&gt;
&lt;html lang="en"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Contact Us - Virunga Ecotours Community&lt;/title&gt;
    &lt;meta name="description" content="Get in touch with Virunga Ecotours Community Programs. Contact us for volunteering opportunities, partnerships, donations, or general inquiries."&gt;
    
    &lt;!-- CSS Files --&gt;
    &lt;link rel="stylesheet" href="../css/earthy-theme.css"&gt;
    &lt;link rel="stylesheet" href="assets/css/community.css"&gt;
    &lt;link rel="stylesheet" href="assets/css/contact.css"&gt;
    
    &lt;!-- FontAwesome --&gt;
    &lt;link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"&gt;
    
    &lt;!-- Google Fonts --&gt;
    &lt;link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"&gt;
    
    &lt;!-- Favicon --&gt;
    &lt;link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg"&gt;
    
    &lt;!-- Google reCAPTCHA --&gt;
    &lt;script src="https://www.google.com/recaptcha/api.js" async defer&gt;&lt;/script&gt;

    &lt;style&gt;
        /* Contact Information Container */
        .contact-contact-info-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .contact-contact-info {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border-left: 4px solid var(--primary-green);
        }

        .contact-contact-info h3 {
            font-size: 1.5rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .contact-contact-info h4 {
            font-size: 1.2rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .contact-contact-info &gt; p {
            color: var(--text-medium);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .contact-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1rem;
            border-radius: var(--border-radius-md);
            transition: background-color 0.3s ease;
        }

        .contact-contact-item:hover {
            background-color: var(--neutral-light);
        }

        .contact-contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-sage) 100%);
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .contact-contact-details h4 {
            color: var(--primary-green);
            margin-bottom: 0.5rem;
            font-weight: 600;
            font-size: 1rem;
        }

        .contact-contact-details p {
            color: var(--text-medium);
            margin-bottom: 0.25rem;
            line-height: 1.5;
        }

        .contact-contact-details a {
            color: var(--accent-terracotta);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .contact-contact-details a:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }

        /* Contact Social Media Section */
        .contact-social-media {
            background: white;
            border-radius: var(--border-radius-lg);
            padding: 2rem;
            box-shadow: var(--shadow-md);
            border-left: 4px solid var(--accent-terracotta);
        }

        .contact-social-media h4 {
            font-size: 1.2rem;
            color: var(--primary-green);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .contact-social-links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .contact-social-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            border-radius: var(--border-radius-md);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .contact-social-link.facebook {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #1877f2;
        }

        .contact-social-link.facebook:hover {
            background: #1877f2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(24, 119, 242, 0.3);
        }

        .contact-social-link.instagram {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #e4405f;
        }

        .contact-social-link.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(228, 64, 95, 0.3);
        }

        .contact-social-link.linkedin {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #0077b5;
        }

        .contact-social-link.linkedin:hover {
            background: #0077b5;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 119, 181, 0.3);
        }

        .contact-social-link.youtube {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            color: #ff0000;
        }

        .contact-social-link.youtube:hover {
            background: #ff0000;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 0, 0, 0.3);
        }

        .contact-social-link i {
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .map-image {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;!-- Include Header --&gt;
    &lt;?php include 'includes/header.php'; ?&gt;

    &lt;!-- Page Header --&gt;
    &lt;section class="page-header"&gt;
        &lt;div class="page-header-background"&gt;
            &lt;img src="../images/stories/vol.JPG" alt="Contact Virunga Ecotours Community" loading="lazy"&gt;
            &lt;div class="page-header-overlay"&gt;&lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="container"&gt;
            &lt;div class="page-header-content"&gt;
                &lt;nav class="breadcrumb"&gt;
                    &lt;a href="index.php"&gt;Community&lt;/a&gt;
                    &lt;span class="separator"&gt;&lt;i class="fas fa-chevron-right"&gt;&lt;/i&gt;&lt;/span&gt;
                    &lt;span class="current"&gt;Contact Us&lt;/span&gt;
                &lt;/nav&gt;
                &lt;h1&gt;Get In Touch&lt;/h1&gt;
                &lt;p&gt;Ready to make a difference? Contact us to learn about volunteering opportunities, partnerships, or how you can support our community programs.&lt;/p&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Contact Section --&gt;
    &lt;section class="contact-section"&gt;
        &lt;div class="container"&gt;
            &lt;div class="contact-grid"&gt;
                &lt;!-- Contact Form --&gt;
                &lt;div class="contact-form-container"&gt;
                    &lt;div class="form-header"&gt;
                        &lt;h2&gt;Send Us a Message&lt;/h2&gt;
                        &lt;p&gt;We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.&lt;/p&gt;
                    &lt;/div&gt;

                    &lt;?php if ($message_sent): ?&gt;
                        &lt;div class="success-message"&gt;
                            &lt;i class="fas fa-check-circle"&gt;&lt;/i&gt;
                            &lt;h3&gt;Thank You!&lt;/h3&gt;
                            &lt;p&gt;Your message has been sent successfully. We'll get back to you within 24 hours.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;?php else: ?&gt;
                        &lt;?php if ($error_message): ?&gt;
                            &lt;div class="error-message"&gt;
                                &lt;i class="fas fa-exclamation-triangle"&gt;&lt;/i&gt;
                                &lt;p&gt;&lt;?php echo htmlspecialchars($error_message); ?&gt;&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;?php endif; ?&gt;

                        &lt;form method="POST" class="contact-form" id="contactForm"&gt;
                            &lt;div class="form-row"&gt;
                                &lt;div class="form-group"&gt;
                                    &lt;label for="name"&gt;Full Name *&lt;/label&gt;
                                    &lt;input type="text" id="name" name="name" required&gt;
                                &lt;/div&gt;
                                &lt;div class="form-group"&gt;
                                    &lt;label for="email"&gt;Email Address *&lt;/label&gt;
                                    &lt;input type="email" id="email" name="email" required&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;div class="form-row"&gt;
                                &lt;div class="form-group"&gt;
                                    &lt;label for="phone"&gt;Phone Number&lt;/label&gt;
                                    &lt;input type="tel" id="phone" name="phone"&gt;
                                &lt;/div&gt;
                                &lt;div class="form-group"&gt;
                                    &lt;label for="country"&gt;Country&lt;/label&gt;
                                    &lt;select id="country" name="country"&gt;
                                        &lt;option value=""&gt;Select Country&lt;/option&gt;
                                        &lt;option value="rwanda"&gt;Rwanda&lt;/option&gt;
                                        &lt;option value="uganda"&gt;Uganda&lt;/option&gt;
                                        &lt;option value="congo"&gt;DRC Congo&lt;/option&gt;
                                        &lt;option value="other"&gt;Other&lt;/option&gt;
                                    &lt;/select&gt;
                                &lt;/div&gt;
                            &lt;/div&gt;

                            &lt;div class="form-group"&gt;
                                &lt;label for="subject"&gt;Subject&lt;/label&gt;
                                &lt;input type="text" id="subject" name="subject" 
                                       value="&lt;?php 
                                       if ($action === 'volunteer') echo 'Volunteering Opportunity';
                                       elseif ($action === 'partner') echo 'Partnership Inquiry';
                                       elseif ($action === 'donate') echo 'Donation Inquiry';
                                       ?&gt;"&gt;
                            &lt;/div&gt;

                            &lt;div class="form-group"&gt;
                                &lt;label for="program_interest"&gt;Program of Interest&lt;/label&gt;
                                &lt;select id="program_interest" name="program_interest"&gt;
                                    &lt;option value=""&gt;Select a program (optional)&lt;/option&gt;
                                    &lt;option value="education"&gt;Education Programs&lt;/option&gt;
                                    &lt;option value="health"&gt;Health Programs&lt;/option&gt;
                                    &lt;option value="conservation"&gt;Conservation Programs&lt;/option&gt;
                                    &lt;option value="economic"&gt;Economic Development&lt;/option&gt;
                                    &lt;option value="women"&gt;Women's Empowerment&lt;/option&gt;
                                    &lt;option value="infrastructure"&gt;Infrastructure Development&lt;/option&gt;
                                    &lt;option value="general"&gt;General Inquiry&lt;/option&gt;
                                &lt;/select&gt;
                            &lt;/div&gt;

                            &lt;div class="form-group"&gt;
                                &lt;label for="message"&gt;Message *&lt;/label&gt;
                                &lt;textarea id="message" name="message" rows="6" required 
                                          placeholder="Tell us about your interest in our community programs..."&gt;&lt;/textarea&gt;
                            &lt;/div&gt;

                            &lt;div class="form-group checkbox-group"&gt;
                                &lt;label class="checkbox-label"&gt;
                                    &lt;input type="checkbox" name="volunteer_interest" value="1" 
                                           &lt;?php echo $action === 'volunteer' ? 'checked' : ''; ?&gt;&gt;
                                    &lt;span class="checkmark"&gt;&lt;/span&gt;
                                    I'm interested in volunteering opportunities
                                &lt;/label&gt;
                                &lt;label class="checkbox-label"&gt;
                                    &lt;input type="checkbox" name="donation_interest" value="1"
                                           &lt;?php echo $action === 'donate' ? 'checked' : ''; ?&gt;&gt;
                                    &lt;span class="checkmark"&gt;&lt;/span&gt;
                                    I'm interested in supporting through donations
                                &lt;/label&gt;
                            &lt;/div&gt;
                            
                            &lt;div class="form-group"&gt;
                                &lt;div class="g-recaptcha" data-sitekey="6LcJCDotAAAAAPwVRmfKOpAf_NhK2QSJhUEiO-Cv"&gt;&lt;/div&gt;
                            &lt;/div&gt;

                            &lt;button type="submit" class="btn btn-primary submit-btn"&gt;
                                &lt;i class="fas fa-paper-plane"&gt;&lt;/i&gt;
                                Send Message
                            &lt;/button&gt;
                        &lt;/form&gt;
                    &lt;?php endif; ?&gt;
                &lt;/div&gt;

                &lt;!-- Contact Information --&gt;
                &lt;div class="contact-contact-info-container"&gt;
                    &lt;div class="contact-contact-info"&gt;
                        &lt;h3&gt;Contact Information&lt;/h3&gt;
                        &lt;p&gt;Get in touch with our community programs team through any of the following channels:&lt;/p&gt;

                        &lt;div class="contact-contact-item"&gt;
                            &lt;div class="contact-contact-icon"&gt;
                                &lt;i class="fas fa-envelope"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;div class="contact-contact-details"&gt;
                                &lt;h4&gt;Email&lt;/h4&gt;
                                &lt;p&gt;&lt;a href="mailto:community@virungaecotours.com"&gt;virungacommunityprograms@gmail.com&lt;/a&gt;&lt;/p&gt;
                                &lt;p&gt;&lt;a href="mailto:info@virungaecotours.com"&gt;info@virungaecotours.com&lt;/a&gt;&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;div class="contact-contact-item"&gt;
                            &lt;div class="contact-contact-icon"&gt;
                                &lt;i class="fas fa-phone"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;div class="contact-contact-details"&gt;
                                &lt;h4&gt;Phone&lt;/h4&gt;
                                &lt;p&gt;&lt;a href="tel:+250784513435"&gt;+(250) 784 513 435&lt;/a&gt;&lt;/p&gt;
                                &lt;p&gt;Office Hours: 9:00 AM - 6:00 PM (EAT)&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;div class="contact-contact-item"&gt;
                            &lt;div class="contact-contact-icon"&gt;
                                &lt;i class="fas fa-map-marker-alt"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;div class="contact-contact-details"&gt;
                                &lt;h4&gt;Office Location&lt;/h4&gt;
                                &lt;p&gt;Kigali, Rwanda&lt;br&gt;
                                Virunga Massif Region&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;

                        &lt;div class="contact-contact-item"&gt;
                            &lt;div class="contact-contact-icon"&gt;
                                &lt;i class="fab fa-whatsapp"&gt;&lt;/i&gt;
                            &lt;/div&gt;
                            &lt;div class="contact-contact-details"&gt;
                                &lt;h4&gt;WhatsApp&lt;/h4&gt;
                                &lt;p&gt;&lt;a href="https://wa.me/250784513435" target="_blank"&gt;+(250) 784 513 435&lt;/a&gt;&lt;/p&gt;
                            &lt;/div&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;!-- Social Media --&gt;
                    &lt;div class="contact-social-media"&gt;
                        &lt;h4&gt;Follow Our Work&lt;/h4&gt;
                        &lt;div class="contact-social-links"&gt;
                            &lt;a href="https://www.facebook.com/VirungaPrograms" target="_blank" rel="noopener" class="contact-social-link facebook"&gt;
                                &lt;i class="fab fa-facebook-f"&gt;&lt;/i&gt;
                                &lt;span&gt;Facebook&lt;/span&gt;
                            &lt;/a&gt;
                            &lt;a href="https://www.instagram.com/virunga_ecotours" target="_blank" rel="noopener" class="contact-social-link instagram"&gt;
                                &lt;i class="fab fa-instagram"&gt;&lt;/i&gt;
                                &lt;span&gt;Instagram&lt;/span&gt;
                            &lt;/a&gt;
                            &lt;a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1" target="_blank" rel="noopener" class="contact-social-link linkedin"&gt;
                                &lt;i class="fab fa-linkedin-in"&gt;&lt;/i&gt;
                                &lt;span&gt;LinkedIn&lt;/span&gt;
                            &lt;/a&gt;
                            &lt;a href="https://www.youtube.com/@virungaecotours8285" target="_blank" rel="noopener" class="contact-social-link youtube"&gt;
                                &lt;i class="fab fa-youtube"&gt;&lt;/i&gt;
                                &lt;span&gt;YouTube&lt;/span&gt;
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;

                    &lt;!-- Quick Actions --&gt;
                    &lt;div class="quick-actions"&gt;
                        &lt;h4&gt;Quick Actions&lt;/h4&gt;
                        &lt;div class="action-buttons"&gt;
                            &lt;a href="programs.php" class="contact-action-btn"&gt;
                                &lt;i class="fas fa-eye"&gt;&lt;/i&gt;
                                View Programs
                            &lt;/a&gt;
                            &lt;a href="about.php" class="contact-action-btn"&gt;
                                &lt;i class="fas fa-info-circle"&gt;&lt;/i&gt;
                                Learn About Us
                            &lt;/a&gt;
                            &lt;a href="../pages/gallery.php" class="contact-action-btn"&gt;
                                &lt;i class="fas fa-images"&gt;&lt;/i&gt;
                                Photo Gallery
                            &lt;/a&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Map Section --&gt;
    &lt;section class="map-section"&gt;
        &lt;div class="container"&gt;
            &lt;div class="section-header"&gt;
                &lt;h2&gt;Our Operating Region&lt;/h2&gt;
                &lt;p&gt;We operate across the Virunga Massif region, spanning Rwanda, DRC Congo, and Uganda.&lt;/p&gt;
            &lt;/div&gt;
            &lt;div class="map-container"&gt;
                &lt;div class="map-placeholder"&gt;
                    &lt;img src="assets/images/Virunga-Conservation-Area.png" alt="Virunga Region Map" loading="lazy" class="map-image"&gt;
                    &lt;div class="map-overlay"&gt;
                        &lt;div class="map-info"&gt;
                            &lt;h3&gt;Virunga Massif Region&lt;/h3&gt;
                            &lt;p&gt;Our community programs operate across this biodiverse region, home to mountain gorillas and vibrant local communities.&lt;/p&gt;
                        &lt;/div&gt;
                    &lt;/div&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;!-- Include Footer --&gt;
    &lt;?php include 'includes/footer.php'; ?&gt;

    &lt;!-- JavaScript Files --&gt;
    &lt;script src="assets/js/community.js"&gt;&lt;/script&gt;
    &lt;script src="assets/js/contact.js"&gt;&lt;/script&gt;
&lt;/body&gt;
&lt;/html&gt;

&lt;?php
// Close database connection
mysqli_close($conn);
?&gt;
