<?php
ob_start(); // Start output buffering
require_once './admin/config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
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
    ob_end_clean(); // Clean output buffer before exit
    exit;
}

// End output buffering for normal page render if no form submission
if (ob_get_level() > 0) {
    ob_end_flush();
}
?>
<style>
.cta-social-buttons {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
  margin-top: 1rem;
}

.cta-social-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background-color: var(--primary-green);
  color: white;
  text-decoration: none;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.cta-social-button:hover {
  background-color: var(--primary-green);
  color: white;
}

.cta-social-button i {
  font-size: 1.1rem;
}
</style>
<!-- Call to Action Section -->
      <section class="cta-section">
        <div class="container">
          <div class="cta-wrapper">
            <!-- Begin Journey CTA -->
            <div class="cta-box journey-cta">
              <h3>Begin your journey</h3>
              <p style="text-align: left; word-spacing: normal; letter-spacing: normal;">
                Enquire online or contact one of our Eco travel consultants.
              </p>
              <div class="cta-buttons">
                <a
                  href="./pages/build.php"
                  class="cta-button primary-button"
                  style="font-size: small;"
                  >Book Now</a
                >
                <a href="tel:0784513435" class="cta-button secondary-button">
                  <i class="fas fa-phone"></i> +(250) 784 513 435
                </a>
              </div>
            </div>

            <!-- Brochure CTA -->
            <div class="cta-box brochure-cta">
              <h3>Follow Our Journeys</h3>
              <p>
                Connect with Virunga Ecotours on social media for stunning photos, traveler stories, and behind-the-scenes glimpses of our adventures.
              </p>
              <div class="cta-buttons">
                <a href="https://www.facebook.com/VirungaPrograms?mibextid=LQQJ4d" target="_blank" class="cta-social-button">
                  <i class="fab fa-facebook-f"></i>
                  Facebook
                </a>
                <a href="https://www.instagram.com/virunga_ecotours?igsh=YWtnY3FmZjcwdzFl&utm_source=qr" target="_blank" class="cta-social-button">
                  <i class="fab fa-instagram"></i>
                  Instagram
                </a>
                <a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1?utm_source=share&utm_campaign=share_via&utm_content=profile&utm_medium=ios_app" target="_blank" class="cta-social-button"><i class="fab fa-linkedin-in">
                  </i
          > Linked in</a>
                
              </div>
            </div>
          </div>
        </div>
      </section>
<!-- Footer -->
<footer class="site-footer">
  <div class="footer-container">
    <!-- Top Footer Section -->
    <div class="footer-top">
      <div class="footer-column company-info">
        <h3>MORE FROM Virunga Ecotours</h3>
        <ul class="footer-links" id="companyLinks">

          <li><a class="footer-link" href="./pages/kids.php">Kids Tourism</a></li>
          <li><a href="./pages/training.php" class="footer-link">Training Support</a></li>
          <li><a href="./community/impact.php" class="footer-link">Community Impact</a></li>
          <li><a href="./pages/kids.php" class="footer-link">Advanture for Kids</a></li>
          <li><a href="./pages/payments.php" class="footer-link">Authorized Payments Methods</a></li>
          <li><a href="./pages/lgbtq.php" class="footer-link">Inclusive Tourism(LGBTQ+)</a></li>
          <li><a href="./pages/faq-page.php" class="footer-link">FAQs</a></li>
          <li><a href="./pages/complaints.php" class="footer-link">Common complaints & solutions</a></li>
          <li><a href="./pages/requirements.php" class="footer-link">Park Entry Requirements</a></li>
          <li><a href="./community/inclusive.php" class="footer-link">CBT in Inclusive Education</a></li>
          <li><a href="./pages/animal.php" class="footer-link">Animal Welfare Care Awareness</a></li>
          <li><a href="./pages/honeymoon.php" class="footer-link">Honeymoon in CBT</a></li>
          <li><a href="./community/galadinner.php" class="footer-link">Gala Dinner</a></li>
          
          <li><a href="./pages/christmas.php" class="footer-link">Community Christmas Give Away</a></li>
          <li><a href="./pages/carhire.php" class="footer-link">Car Hire</a></li>
          <li><a href="./pages/congo-nile-trail.php" class="footer-link">Congo Nile Trail</a></li>
          
          <li><a href="./pages/agrotours.php" class="footer-link">Agro Tours</a></li>
          <li><a href="./pages/beekeeping.php" class="footer-link">Beekeeping Experiences</a></li>
          
          <li><a href="./pages/photograph.php" class="footer-link">Kids Photography</a></li>
          <li>
            <a href="./pages/styleguide.php" class="footer-link"
              >The Virunga Ecotours differences</a
            >
          </li>
          <li><a href="./pages/activity.php" class="footer-link">Beyond The Park Experience</a></li>
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
            src="./images/paterners/5826ffd90d544fbc8a11b429ef2fb87b.png"
            alt="ABTA"
            class="association-logo"
          />
          <img
            src="./images/paterners/FoT Master_RGB.png"
            alt="AITO"
            class="association-logo"
          />
       
          <img
            src="./images/paterners/Mellon_Foundation_logo_2022.svg.png"
            alt="Travel Aware"
            class="association-logo"
          />
          <img
            src="./images/paterners/Water-bottles.jpg"
            alt="Travel Aware"
            class="association-logo"
          />
          <img
            src="./images/paterners/WEB-LOGO-01.png"
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
        <p>&copy; 2017-<?php echo date('Y'); ?> Copyright Virunga Ecotours</p>
      </div>
      <div class="legal-links">
        <a href="./pages/faq-page.php" class="legal-link">Booking Conditions</a>
        <a href="./pages/Privacy.php" class="legal-link">Data Protection Privacy Notice</a>
        <a href="./pages/Privacy.php" class="legal-link">Website Terms of Use</a>
      </div>
    </div>
  </div>
</footer>

<script>
document.getElementById('newsletterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const messageDiv = document.getElementById('subscriptionMessage');
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Disable button during submission
    submitButton.disabled = true;
    
    fetch('includes/save-subscriber.php', {  // Updated path
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
