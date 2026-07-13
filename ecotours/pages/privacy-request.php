<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Request - Virunga Ecotours</title>
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/privacy-request.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="hero">
        <div class="hero-background"></div>
        <div class="hero-content">
            <h1>Privacy Request</h1>
            <p>Exercise your data protection rights with Virunga Ecotours</p>
        </div>
    </div>

    <main class="privacy-request-main">
        <div class="container">
            <div class="request-intro">
                <h2>Your Privacy Rights</h2>
                <p>
                    At Virunga Ecotours, we respect your privacy rights and are committed to protecting your personal data. 
                    You have the right to know what personal information we collect, how we use it, and to request changes 
                    or deletion of your data.
                </p>
            </div>

            <div class="request-types">
                <h3>Types of Privacy Requests</h3>
                <div class="request-types-grid">
                    <div class="request-type-card">
                        <div class="type-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h4>Data Access</h4>
                        <p>Request a copy of all personal data we have about you</p>
                    </div>
                    <div class="request-type-card">
                        <div class="type-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h4>Data Deletion</h4>
                        <p>Request deletion of your personal information from our systems</p>
                    </div>
                    <div class="request-type-card">
                        <div class="type-icon">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4>Data Correction</h4>
                        <p>Request correction of inaccurate or incomplete information</p>
                    </div>
                    <div class="request-type-card">
                        <div class="type-icon">
                            <i class="fas fa-download"></i>
                        </div>
                        <h4>Data Portability</h4>
                        <p>Request your data in a portable, machine-readable format</p>
                    </div>
                </div>
            </div>

            <div class="request-form-section">
                <h3>Submit Your Privacy Request</h3>
                <div class="form-container">
                    <form id="privacy-request-form" class="privacy-form">
                        <div class="form-group">
                            <label for="request_type">Request Type *</label>
                            <select id="request_type" name="request_type" required>
                                <option value="">Select a request type</option>
                                <option value="data_access">Data Access Request</option>
                                <option value="data_deletion">Data Deletion Request</option>
                                <option value="data_correction">Data Correction Request</option>
                                <option value="data_portability">Data Portability Request</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" required 
                                   placeholder="Enter the email address associated with your account">
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required 
                                   placeholder="Brief description of your request">
                        </div>

                        <div class="form-group">
                            <label for="message">Detailed Request *</label>
                            <textarea id="message" name="message" rows="6" required 
                                      placeholder="Please provide detailed information about your privacy request. Include any specific data or information you're referring to."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="identity_verification" name="identity_verification" required>
                                <span class="checkmark"></span>
                                I confirm that I am the data subject or have authorization to make this request on behalf of the data subject *
                            </label>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="privacy_policy_agreement" name="privacy_policy_agreement" required>
                                <span class="checkmark"></span>
                                I have read and understand the <a href="./privacy.php" target="_blank">Privacy Policy</a> *
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                Submit Request
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="request-info">
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h4>Response Time</h4>
                        <p>We will respond to your request within 30 days as required by applicable privacy laws.</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Secure Processing</h4>
                        <p>Your request will be processed securely and handled only by authorized personnel.</p>
                    </div>
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h4>Email Confirmation</h4>
                        <p>You will receive an email confirmation once your request has been submitted.</p>
                    </div>
                </div>
            </div>

            <div class="contact-section">
                <h3>Need Help?</h3>
                <p>
                    If you have questions about your privacy rights or need assistance with your request, 
                    please contact our Data Protection Officer:
                </p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <span>privacy@virungaecotours.com</span>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <span>+250 788 123 456</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include './includes/footer.php'; ?>

    <!-- Success Modal -->
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-check-circle"></i> Request Submitted Successfully</h3>
            </div>
            <div class="modal-body">
                <p>Your privacy request has been submitted successfully. We will review your request and respond within 30 days.</p>
                <p>You will receive an email confirmation shortly at the address you provided.</p>
                <div class="modal-actions">
                    <button class="btn btn-primary" onclick="closeModal()">
                        <i class="fas fa-check"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="error-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header error">
                <h3><i class="fas fa-exclamation-triangle"></i> Submission Error</h3>
            </div>
            <div class="modal-body">
                <p id="error-message">There was an error submitting your request. Please try again.</p>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/header.js" defer></script>
    <script src="../js/privacy-request.js" defer></script>
</body>
</html>
