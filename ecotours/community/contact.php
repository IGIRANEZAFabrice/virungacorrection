
<?php
require_once __DIR__ . '/../../config/recaptcha.php';
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
        logCommunityEmailMessage('Email send failed to ' . $recipientEmail . ': ' . $e->getMessage());
        return false;
    }
}

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate reCAPTCHA first
    $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if (empty($recaptchaResponse)) {
        $error_message = 'Please complete the reCAPTCHA.';
    } else {
        if (!verify_recaptcha($recaptchaResponse, $_SERVER['REMOTE_ADDR'] ?? '')) {
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
                        <h2 style="color:#2a4858;">New Community Contact Message</h2>
                        <p>
                            User <strong>' . htmlspecialchars($name) . '</strong>
                            (email: <strong>' . htmlspecialchars($email) . '</strong>)
                            contacted you on the community saying:<br/>
                            "<em>' . nl2br(htmlspecialchars($message)) . '</em>"
                        </p>
                        <p><strong>Subject:</strong> ' . htmlspecialchars($subject) . '</p>
                        <p><strong>Program interest:</strong> ' . htmlspecialchars($program_interest) . '</p>
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Virunga Ecotours Community</title>
    <meta name="description" content="Get in touch with Virunga Ecotours Community Programs. Contact us for volunteering opportunities, partnerships, donations, or general inquiries.">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
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

        .contact-contact-info > p {
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
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="../images/stories/vol.JPG" alt="Contact Virunga Ecotours Community" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Contact Us</span>
                </nav>
                <h1>Get In Touch</h1>
                <p>Ready to make a difference? Contact us to learn about volunteering opportunities, partnerships, or how you can support our community programs.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-container">
                    <div class="form-header">
                        <h2>Send Us a Message</h2>
                        <p>We'd love to hear from you. Fill out the form below and we'll get back to you as soon as possible.</p>
                    </div>

                    <?php if ($message_sent): ?>
                        <div class="success-message">
                            <i class="fas fa-check-circle"></i>
                            <h3>Thank You!</h3>
                            <p>Your message has been sent successfully. We'll get back to you within 24 hours.</p>
                        </div>
                    <?php else: ?>
                        <?php if ($error_message): ?>
                            <div class="error-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p><?php echo htmlspecialchars($error_message); ?></p>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="contact-form" id="contactForm" onsubmit="const r = this.querySelector('[name=\'g-recaptcha-response\']'); if(r &amp;&amp; !r.value){ alert('Please complete the reCAPTCHA verification.'); return false; }">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" id="name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone">
                                </div>
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <select id="country" name="country">
                                        <option value="">Select Country</option>
                                        <option value="rwanda">Rwanda</option>
                                        <option value="uganda">Uganda</option>
                                        <option value="congo">DRC Congo</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" 
                                       value="<?php 
                                       if ($action === 'volunteer') echo 'Volunteering Opportunity';
                                       elseif ($action === 'partner') echo 'Partnership Inquiry';
                                       elseif ($action === 'donate') echo 'Donation Inquiry';
                                       ?>">
                            </div>

                            <div class="form-group">
                                <label for="program_interest">Program of Interest</label>
                                <select id="program_interest" name="program_interest">
                                    <option value="">Select a program (optional)</option>
                                    <option value="education">Education Programs</option>
                                    <option value="health">Health Programs</option>
                                    <option value="conservation">Conservation Programs</option>
                                    <option value="economic">Economic Development</option>
                                    <option value="women">Women's Empowerment</option>
                                    <option value="infrastructure">Infrastructure Development</option>
                                    <option value="general">General Inquiry</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="message">Message *</label>
                                <textarea id="message" name="message" rows="6" required 
                                          placeholder="Tell us about your interest in our community programs..."></textarea>
                            </div>

                            <div class="form-group checkbox-group">
                                <label class="checkbox-label">
                                    <input type="checkbox" name="volunteer_interest" value="1" 
                                           <?php echo $action === 'volunteer' ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    I'm interested in volunteering opportunities
                                </label>
                                <label class="checkbox-label">
                                    <input type="checkbox" name="donation_interest" value="1"
                                           <?php echo $action === 'donate' ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                    I'm interested in supporting through donations
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                            </div>

                            <button type="submit" class="btn btn-primary submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                Send Message
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Contact Information -->
                <div class="contact-contact-info-container">
                    <div class="contact-contact-info">
                        <h3>Contact Information</h3>
                        <p>Get in touch with our community programs team through any of the following channels:</p>

                        <div class="contact-contact-item">
                            <div class="contact-contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-contact-details">
                                <h4>Email</h4>
                                <p><a href="mailto:community@virungaecotours.com">virungacommunityprograms@gmail.com</a></p>
                                <p><a href="mailto:info@virungaecotours.com">info@virungaecotours.com</a></p>
                            </div>
                        </div>

                        <div class="contact-contact-item">
                            <div class="contact-contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-contact-details">
                                <h4>Phone</h4>
                                <p><a href="tel:+250784513435">+(250) 784 513 435</a></p>
                                <p>Office Hours: 9:00 AM - 6:00 PM (EAT)</p>
                            </div>
                        </div>

                        <div class="contact-contact-item">
                            <div class="contact-contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-contact-details">
                                <h4>Office Location</h4>
                                <p>Kigali, Rwanda<br>
                                Virunga Massif Region</p>
                            </div>
                        </div>

                        <div class="contact-contact-item">
                            <div class="contact-contact-icon">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <div class="contact-contact-details">
                                <h4>WhatsApp</h4>
                                <p><a href="https://wa.me/250784513435" target="_blank">+(250) 784 513 435</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="contact-social-media">
                        <h4>Follow Our Work</h4>
                        <div class="contact-social-links">
                            <a href="https://www.facebook.com/VirungaPrograms" target="_blank" rel="noopener" class="contact-social-link facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://www.instagram.com/virunga_ecotours" target="_blank" rel="noopener" class="contact-social-link instagram">
                                <i class="fab fa-instagram"></i>
                                <span>Instagram</span>
                            </a>
                            <a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1" target="_blank" rel="noopener" class="contact-social-link linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            <a href="https://www.youtube.com/@virungaecotours8285" target="_blank" rel="noopener" class="contact-social-link youtube">
                                <i class="fab fa-youtube"></i>
                                <span>YouTube</span>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <h4>Quick Actions</h4>
                        <div class="action-buttons">
                            <a href="programs.php" class="contact-action-btn">
                                <i class="fas fa-eye"></i>
                                View Programs
                            </a>
                            <a href="about.php" class="contact-action-btn">
                                <i class="fas fa-info-circle"></i>
                                Learn About Us
                            </a>
                            <a href="../pages/gallery.php" class="contact-action-btn">
                                <i class="fas fa-images"></i>
                                Photo Gallery
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Operating Region</h2>
                <p>We operate across the Virunga Massif region, spanning Rwanda, DRC Congo, and Uganda.</p>
            </div>
            <div class="map-container">
                <div class="map-placeholder">
                    <img src="assets/images/Virunga-Conservation-Area.png" alt="Virunga Region Map" loading="lazy" class="map-image">
                    <div class="map-overlay">
                        <div class="map-info">
                            <h3>Virunga Massif Region</h3>
                            <p>Our community programs operate across this biodiverse region, home to mountain gorillas and vibrant local communities.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/community.js"></script>
    <script src="assets/js/contact.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>

