<?php
require_once __DIR__ . '/../../config/recaptcha.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon">
    <title>Contact Virunga Ecotours</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/contact.css?v=<?php echo time(); ?>">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  </head>
  <body>
    <!-- Header -->
    <?php include "./includes/header.php"; ?>

    <div class="contact-container-content">
        <div class="page-title-content">
            <h1>Get in Touch With Us</h1>
        </div>

        <div class="contact-content-content">
            <div class="contact-info-content">
                <div class="info-card-content">
                    <h3>Contact Information</h3>
                    <div class="info-detail-content">
                        <i class="fas fa-map-marker-alt"></i>
                        <p>NM 61 ST, Ruhengeri, Musanze, Rwanda.</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-phone-alt"></i>
                        <p>(+250) 784 513 435‬</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-envelope"></i>
                        <p>info@virungaecotours.com</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-envelope"></i>
                        <p>P.o. Box: 157 Musanze-Rwanda</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-clock"></i>
                        <p>Monday-Friday: 9am - 6pm EST</p>
                    </div>
                    <div class="social-links-content">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="info-card-content">
                    <h3>Our Support Hours</h3>
                    <div class="info-detail">
                        <i class="fas fa-headset"></i>
                        <p>Customer Support: 24/7</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-comments"></i>
                        <p>Live Chat: 9am - 6pm EST</p>
                    </div>
                    <div class="info-detail-content">
                        <i class="fas fa-reply"></i>
                        <p>Average Response Time: 2 hours</p>
                    </div>
                </div>
            </div>

            <div class="contact-form-content">
                <div class="form-header-content">
                    <h2>Send Us a Message</h2>
                    <p>Fill out the form below and we'll get back to you as soon as possible.</p>
                    <?php 
                    if (isset($_SESSION['contact_message'])): 
                        $message = $_SESSION['contact_message'];
                        unset($_SESSION['contact_message']);
                    ?>
                        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : 'danger'; ?>">
                            <?php echo htmlspecialchars($message['text']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <!-- <div id="formResponse" class="alert" style="display:none;">
                </div> -->
                <form id="contactForm-content" method="POST" action="../admin/handlers/contactUsHandlers.php">
                    <div class="form-row-content">
                        <div class="form-group-content">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control-content" id="firstName" name="firstName" required>
                        </div>
                        <div class="form-group-content">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control-content" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="form-group-content">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control-content" id="email" name="email" required>
                    </div>
                    <div class="form-group-content">
                        <label for="phone">Phone Number (Optional)</label>
                        <input type="tel" class="form-control-content" id="phone" name="phone">
                    </div>
                    <div class="form-group-content">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control-content" id="subject" name="subject" required>
                    </div>
                    <div class="form-group-content">
                        <label for="message">Your Message</label>
                        <textarea class="form-control-content" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="form-group-content" style="margin-bottom: 20px;">
                        <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
                    </div>
                    <button type="submit" class="submit-btn-content" id="submitBtn">
                        <span id="submitText">Send Message</span>
                        <span id="submitSpinner" style="display:none;">
                            <i class="fas fa-spinner fa-spin"></i> Sending...
                        </span>
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="map-section-content">
            <h2>Find Us on the Map</h2>
            <div class="map-container-content">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.4580752914685!2d29.629988274965942!3d-1.4961735984897848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dc5a4203062269%3A0x9911bb9e4e9bc6ea!2sVIRUNGA%20ECOTOURS!5e0!3m2!1sen!2srw!4v1742715963993!5m2!1sen!2srw" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="faq-section-content">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item-content">
                <div class="faq-question-content">
                    How quickly will you respond to my inquiry? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer-content">
                    We strive to respond to all inquiries within 24 hours during business days. For urgent matters, we recommend calling our customer support line for immediate assistance.
                </div>
            </div>
            <div class="faq-item-content">
                <div class="faq-question-content">
                    Can I schedule a meeting with your team? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer-content">
                    Absolutely! You can request a meeting through our contact form or by calling our office. We offer both in-person and virtual meetings depending on your preference and location.
                </div>
            </div>
            <div class="faq-item-content">
                <div class="faq-question-content">
                    Do you offer support on weekends? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer-content">
                    Yes, our customer support team is available 24/7, including weekends. However, our main office and administrative staff are available Monday through Friday from 9am to 5pm EST.
                </div>
            </div>
            <div class="faq-item-content">
                <div class="faq-question-content">
                    How can I submit a complaint or feedback? <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer-content">
                    You can submit complaints or feedback through our contact form, by email, or by phone. All feedback is reviewed by our customer experience team and we strive to address any concerns promptly.
                </div>
            </div>
        </div>

        <div class="newsletter-content">
            <h2>Subscribe to Our Newsletter</h2>
            <p>Stay updated with our latest news, offers, and updates.</p>
            <form class="newsletter-form-content">
                <input type="email" class="newsletter-input-content" placeholder="Enter your email address" required>
                <button type="submit" class="newsletter-btn-content"><i class="fas fa-paper-plane"></i></button>
            </form>
        </div>

        <div class="contact-cta-content">
            <div class="cta-card-content">
                <div class="cta-icon-content">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3 class="cta-title-content">Knowledge Base</h3>
                <p class="cta-text-content">Find answers to common questions in our comprehensive knowledge base.</p>
                <a href="#" class="cta-link-content">Browse Articles <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="cta-card-content">
                <div class="cta-icon-content">
                    <i class="fas fa-comments"></i>
                </div>
                <h3 class="cta-title-content">Live Chat</h3>
                <p class="cta-text-content">Get immediate assistance from our customer support team.</p>
                <a href="#" class="cta-link-content">Start Chat <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="cta-card-content">
                <div class="cta-icon-content">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3 class="cta-title-content">Careers</h3>
                <p class="cta-text-content">Interested in joining our team? Check out current openings.</p>
                <a href="#" class="cta-link-content">View Opportunities <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script src="../js/header.js" defer></script>
    <script src="../js/contact.js?v=<?php echo time(); ?>" defer></script>
  </body>
</html>