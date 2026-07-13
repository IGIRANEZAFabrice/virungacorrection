<?php
// Use absolute path so includes work regardless of current working directory
require_once __DIR__ . '/../../admin/config/connection.php';

// Handle form submission (guarded for CLI runs where REQUEST_METHOD may be undefined)
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $response = ['success' => false, 'message' => ''];
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP");
            $stmt->bind_param("s", $email);
            
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Thank you for subscribing!'];
            } else {
                $response = ['success' => false, 'message' => 'Subscription failed. Please try again.'];
            }
            $stmt->close();
        } catch (Exception $e) {
            $response = ['success' => false, 'message' => 'An error occurred. Please try again later.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Please enter a valid email address.'];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
<!-- Footer -->
<footer class="site-footer">
  <div class="footer-container">
    <!-- Top Footer Section -->
    <div class="footer-top">
      <div class="footer-column company-info">
        <h3>MORE FROM Virunga Ecotours</h3>
        <ul class="footer-links">
            <li><a class="footer-link" href="./kids.php">Kids Tourism</a></li>
            <li><a href="./training.php" class="footer-link">Training Support</a></li>
            <li><a href="./kids.php" class="footer-link">Advanture for Kids</a></li>
            <li><a href="./lgbtq.php" class="footer-link">Inclusive Tourism(LGBTQ+)</a></li>
            <li><a href="../community/inclusive.php" class="footer-link">CBT in Inclusive Education</a></li>
            <li><a href="./animal.php" class="footer-link">Animal Welfare Care Awareness</a></li>
            <li><a href="./payments.php" class="footer-link">Authorized Payments Methods</a></li>
            <li><a href="./faq-page.php" class="footer-link">FAQs</a></li>
            <li><a href="../community/impact.php" class="footer-link">Community Impact</a></li>
            <li><a href="./complaints.php" class="footer-link">Common complaints & solutions</a></li>
            <li><a href="./requirements.php" class="footer-link">Park Entry Requirements</a></li>
            <li><a href="./styleguide.php" class="footer-link">The Virunga Ecotours differences</a></li>
            <li><a href="./activity.php" class="footer-link">Beyond The Park Experience</a></li>
            <li><a href="./honeymoon.php" class="footer-link">Honeymoon in CBT</a></li>
            <li><a href="../community/galadinner.php" class="footer-link">Gala Dinner</a></li>
            <li><a href="./christmas.php" class="footer-link">Community Christmas Give Away</a></li>
            <li><a href="./carhire.php" class="footer-link">Car Hire</a></li>
            <li><a href="./congo-nile-trail.php" class="footer-link">Congo Nile Trail</a></li>
            <li><a href="./photograph.php" class="footer-link">Kids Photography</a></li>
            <li><a href="./agrotours.php" class="footer-link">Agro Tours</a></li>
            <li><a href="./beekeeping.php" class="footer-link">Beekeeping Experiences</a></li>
          </ul>
      </div>

      <div class="footer-column social-connect">
        <h3>FOLLOW US</h3>
        <div class="social-icons">
          <a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="social-icon" aria-label="LinkedIn"
            ><i class="fab fa-linkedin-in"></i
          ></a>
          <a href="https://www.youtube.com/@VirungaEcotours-o7b" class="social-icon" aria-label="YouTube"
            ><i class="fab fa-youtube"></i
          ></a>
          <a href="https://www.facebook.com/VirungaPrograms?mibextid=LQQJ4d" target="_blank" class="social-icon" aria-label="Facebook"
            ><i class="fab fa-facebook-f"></i
          ></a>
          <a href="https://www.instagram.com/virunga_ecotours?igsh=YWtnY3FmZjcwdzFl&utm_source=qr" target="_blank" class="social-icon" aria-label="Instagram"
            ><i class="fab fa-instagram"></i
          ></a>
        </div>

        <div class="travel-associations">
          <img
            src="../images/paterners/5826ffd90d544fbc8a11b429ef2fb87b.png"
            alt="ABTA"
            class="association-logo"
          />
          <img
            src="../images/paterners/FoT Master_RGB.png"
            alt="AITO"
            class="association-logo"
          />
          <img
            src="../images/paterners/Mellon_Foundation_logo_2022.svg.png"
            alt="Travel Aware"
            class="association-logo"
          />
          <img
            src="../images/paterners/Water-bottles.jpg"
            alt="Travel Aware"
            class="association-logo"
          />
          <img
            src="../images/paterners/WEB-LOGO-01.png"
            alt="Travel Aware"
            class="association-logo"
          />
        </div>
      </div>

      <div class="footer-column newsletter">
        <h3>SIGN UP TO OUR NEWSLETTER</h3>
        <p>
          You'll be the first to hear about the latest travel news,
          destination insights and special offers.
        </p>
        <form class="newsletter-form" id="newsletterForm">
          <input
            type="email"
            name="email"
            id="email"
            placeholder="Enter your email"
            required
          />
          <button type="submit" class="newsletter-button">Subscribe</button>
        </form>
        <div id="subscriptionMessage"></div>
      </div>
    </div>

    <!-- Bottom Footer Section -->
    <div class="footer-bottom">
      <div class="copyright">
        <p>&copy; 2017- <?php echo date('Y'); ?> Copyright Virunga Ecotours</p>
      </div>
      <div class="legal-links">
        <a href="./faq-page.php" class="legal-link">Booking Conditions</a>
        <a href="./Privacy.php" class="legal-link">Data Protection Privacy Notice</a>
        <a href="./Privacy.php" class="legal-link">Website Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<style>
#subscriptionMessage {
    margin-top: 10px;
    padding: 10px;
    display: none;
}
#subscriptionMessage.success {
    color: #155724;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
}
#subscriptionMessage.error {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}
</style>

<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const messageDiv = document.getElementById('subscriptionMessage');
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Disable button during submission
    submitButton.disabled = true;
    
    fetch('./includes/save-subscriber.php', {  
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        messageDiv.textContent = data.message;
        messageDiv.className = data.success ? 'success' : 'error';
        messageDiv.style.display = 'block';
        
        if (data.success) {
            document.getElementById('email').value = '';
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.textContent = 'An error occurred. Please try again.';
        messageDiv.className = 'error';
        messageDiv.style.display = 'block';
    })
    .finally(() => {
        submitButton.disabled = false;
    });
});
</script>
