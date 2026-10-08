<?php
require_once __DIR__ . '/../config/branding.php';
require_once __DIR__ . '/../config/recaptcha.php';
$pageTitle = 'Membership — Virunga Collective';
$pageDescription = 'Join the Virunga Collective Membership. Travel, belong, and make a positive impact in the Virunga region.';
?>
<!doctype html>
<html lang="en">
<head>
  <link rel="canonical" href="https://virungajourneys.com/membership">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?></title>
  <meta name="description" content="<?php echo $pageDescription; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <link rel="stylesheet" href="<?php echo htmlspecialchars($baseLink('pages/membership.css'), ENT_QUOTES); ?>">
</head>
<body class="membership-page">

  <!-- Include Header -->
  <?php include __DIR__ . '/header.php'; ?>

  <main id="main-content">
  <!-- Hero Section -->
  <section class="hero">
    <div class="wrap hero-grid">
      <div class="hero-copy">
        <span class="eyebrow"><span></span> Virunga Collective Membership</span>
        <h1>A little closer to<br>the places you love.</h1>
        <p class="tagline">Travel. Belong. Make an impact.</p>
        <p class="intro">Stay connected to the people, landscapes and experiences of the Virunga. Join a community of curious travelers, with something meaningful to look forward to.</p>
        <div class="hero-actions">
          <a class="hero-join" href="#join-section">Become a member <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
          <a class="hero-explore" href="#membership-levels">Explore the benefits <i class="fas fa-arrow-down" aria-hidden="true"></i></a>
        </div>
        <p class="hero-note"><i class="fas fa-check-circle" aria-hidden="true"></i> Free to join as an Explorer</p>
      </div>
      <figure class="hero-visual">
        <img src="<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg'), ENT_QUOTES); ?>" alt="The green landscapes of the Virunga region" fetchpriority="high">
        <figcaption><span>Rooted in place. Connected by people.</span><span>Musanze, Rwanda <i class="fas fa-location-dot" aria-hidden="true"></i></span></figcaption>
      </figure>
    </div>
  </section>

  <!-- How It Works -->
  <section>
    <div class="wrap">
      <div class="section-title reveal">
        <h2>How It Works</h2>
        <p>A simple journey to deeper belonging and lasting impact</p>
      </div>

      <div class="how-it-works-grid">
        <div class="how-card reveal">
          <div class="how-num">1</div>
          <i class="fas fa-user-plus how-icon"></i>
          <h3>Join</h3>
          <p>Create your free Virunga Collective Membership account to start your journey of belonging.</p>
        </div>

        <div class="how-card reveal">
          <div class="how-num">2</div>
          <i class="fas fa-compass how-icon"></i>
          <h3>Experience</h3>
          <p>Travel with Virunga Collective through our custom programs:</p>
          <ul>
            <li><i class="fas fa-check"></i> Virunga Journeys</li>
            <li><i class="fas fa-check"></i> Virunga House</li>
            <li><i class="fas fa-check"></i> Community Experiences</li>
            <li><i class="fas fa-check"></i> Signature Journeys</li>
          </ul>
        </div>

        <div class="how-card reveal">
          <div class="how-num">3</div>
          <i class="fas fa-ribbon how-icon"></i>
          <h3>Belong</h3>
          <p>Unlock exclusive privileges and become an active part of a community creating meaningful impact.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Membership Levels -->
  <section class="levels-section" id="membership-levels">
    <div class="wrap">
      <div class="section-title reveal">
        <h2>Membership Levels</h2>
        <p>Explore our tiers of engagement and unique privileges</p>
      </div>

      <div class="levels-grid">
        <!-- Explorer -->
        <div class="level-card reveal">
          <div class="level-header">
            <h3>Explorer</h3>
            <span class="level-price">Free Membership</span>
            <p class="level-desc">For every traveler who wants to stay connected.</p>
          </div>
          <ul class="level-benefits">
            <li><i class="fas fa-check-circle"></i> Welcome to the Virunga Collective community</li>
            <li><i class="fas fa-check-circle"></i> Travel inspiration and updates</li>
            <li><i class="fas fa-check-circle"></i> Early access to new experiences</li>
            <li><i class="fas fa-check-circle"></i> Member-only announcements</li>
            <li><i class="fas fa-check-circle"></i> Digital membership profile</li>
          </ul>
          <a href="#join-section" class="level-btn">Join Now</a>
        </div>

        <!-- Ambassador -->
        <div class="level-card featured reveal">
          <div class="featured-badge">Most Popular</div>
          <div class="level-header">
            <h3>Ambassador</h3>
            <span class="level-price">Active Supporters</span>
            <p class="level-desc">Unlocked after multiple experiences or qualifying stays.</p>
          </div>
          <ul class="level-benefits">
            <li><i class="fas fa-check-circle"></i> Priority booking support</li>
            <li><i class="fas fa-check-circle"></i> Personalized travel recommendations</li>
            <li><i class="fas fa-check-circle"></i> Exclusive member experiences</li>
            <li><i class="fas fa-check-circle"></i> Special recognition as a Virunga Collective Ambassador</li>
            <li><i class="fas fa-check-circle"></i> Impact updates showing your contribution</li>
            <li><i class="fas fa-plus"></i> All Explorer benefits included</li>
          </ul>
          <a href="#join-section" class="level-btn">Join & Experience</a>
        </div>

        <!-- Legacy Circle -->
        <div class="level-card reveal">
          <div class="level-header">
            <h3>Legacy Circle</h3>
            <span class="level-price">By Invitation Only</span>
            <p class="level-desc">For our most committed guests, partners, and supporters.</p>
          </div>
          <ul class="level-benefits">
            <li><i class="fas fa-check-circle"></i> Dedicated travel concierge</li>
            <li><i class="fas fa-check-circle"></i> Private signature experiences</li>
            <li><i class="fas fa-check-circle"></i> VIP access to special events</li>
            <li><i class="fas fa-check-circle"></i> Direct connection with Virunga Collective initiatives</li>
            <li><i class="fas fa-check-circle"></i> Annual impact briefing</li>
            <li><i class="fas fa-plus"></i> All Ambassador benefits included</li>
          </ul>
          <a href="#join-section" class="level-btn">Enquire Here</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Your Impact Matters -->
  <section class="impact-bg">
    <div class="wrap">
      <div class="section-title reveal">
        <h2>Your Impact Matters</h2>
        <p style="color: rgba(246, 242, 233, 0.75);">Every journey with us contributes directly to local communities and nature</p>
      </div>

      <div class="impact-grid">
        <div class="impact-card reveal">
          <i class="fas fa-seedling icon"></i>
          <h3>Conservation</h3>
          <p>Funding biodiversity preservation, tree planting, and environmental monitoring in the Virunga region.</p>
        </div>

        <div class="impact-card reveal">
          <i class="fas fa-users icon"></i>
          <h3>Local Development</h3>
          <p>Supporting clean water, local cooperative projects, and directly funding community-led initiatives.</p>
        </div>

        <div class="impact-card reveal">
          <i class="fas fa-graduation-cap icon"></i>
          <h3>Hospitality Education</h3>
          <p>Empowering local youths with world-class hospitality training through the dedicated Virunga Academy.</p>
        </div>

        <div class="impact-card reveal">
          <i class="fas fa-store icon"></i>
          <h3>Local Enterprises</h3>
          <p>Investing in micro-businesses, honey producers, and local crafts to generate sustainable livelihoods.</p>
        </div>
      </div>

      <p class="impact-statement reveal">
        "Your membership is not just about benefits. It is about belonging to a movement."
      </p>
    </div>
  </section>

  <!-- Signup Form Section -->
  <section class="signup-section" id="join-section">
    <div class="wrap signup-layout">
      <div class="section-title reveal">
        <span class="eyebrow">Your next chapter</span>
        <h2>Belong to something meaningful.</h2>
        <p>Join travelers from around the world who believe tourism can create a positive legacy.</p>
        <div class="signup-details"><p><i class="fas fa-check" aria-hidden="true"></i> Travel inspiration, thoughtfully shared</p><p><i class="fas fa-check" aria-hidden="true"></i> A closer connection to the Virunga</p><p><i class="fas fa-check" aria-hidden="true"></i> Start with a free Explorer membership</p></div>
      </div>

      <div class="signup-container reveal" id="signupFormContainer">
        <form id="membershipForm" method="POST">
          <h3 class="form-heading">Join the Collective</h3>
          <p class="form-note">A few details to get started. Required fields are marked *.</p>
          <div class="form-row">
            <div class="form-group">
              <label for="firstName">First Name *</label>
              <input type="text" id="firstName" name="firstName" class="form-control" placeholder="First name" autocomplete="given-name" required>
            </div>
            <div class="form-group">
              <label for="lastName">Last Name *</label>
              <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last name" autocomplete="family-name" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" autocomplete="email" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="phone">Phone / WhatsApp</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="+250 780 000 000" autocomplete="tel">
            </div>
            <div class="form-group">
              <label for="interest">Preferred Level *</label>
              <select id="interest" name="interest" class="form-control" required >
                <option value="Explorer">Explorer (Free)</option>
                <option value="Ambassador">Ambassador (Returning Guest)</option>
                <option value="Legacy">Legacy Circle (Invitation/Enquiry)</option>
              </select>
            </div>
          </div>

          <div class="form-group-checkbox">
            <input type="checkbox" id="consent" name="consent" required>
            <label for="consent">I want to receive travel updates, stories from the field, and support community impact initiatives in the Virunga region.*</label>
          </div>

          <div class="form-group" style="display:flex; justify-content:center; margin-bottom: 25px;">
            <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
          </div>

          <button type="submit" class="submit-btn" id="submitBtn">
            <span class="submit-spinner" id="submitSpinner"></span>
            <span id="submitBtnText">Join Free Membership</span>
          </button>
        </form>
      </div>
    </div>
  </section>

  </main>

  <!-- Shared Footer -->
    <?php include __DIR__ . '/footer.php'; ?>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      document.querySelectorAll('.level-btn').forEach((link, index) => {
        link.addEventListener('click', () => {
          document.getElementById('interest').value = ['Explorer', 'Ambassador', 'Legacy'][index];
        });
      });

      // Handle Membership Form Submission
      const form = document.getElementById("membershipForm");
      if (form) {
        form.addEventListener("submit", (e) => {
          e.preventDefault();
          
          const res = form.querySelector('[name="g-recaptcha-response"]');
          if (res && !res.value) {
            showRecaptchaModal("Please verify that you are human by checking the <strong>\"I'm not a robot\"</strong> box before submitting your membership application.");
            return;
          }

          const btn = document.getElementById("submitBtn");
          const btnText = document.getElementById("submitBtnText");
          const spinner = document.getElementById("submitSpinner");

          if (btn) btn.disabled = true;
          if (spinner) spinner.style.display = "inline-block";
          if (btnText) btnText.innerText = "Processing...";

          const formData = new FormData(form);
          formData.set('user_lang', (document.cookie.match(/googtrans=\/en\/([a-z\-]{2,5})/) || [])[1] || 'en');

          fetch("<?php echo $baseLink('api/join-membership.php'); ?>", {
            method: "POST",
            body: formData
          })
          .then(r => r.json())
          .then(data => {
            if (data.status === "success") {
              // Smoothly transition into a Live simulated Dashboard with the user's details!
              const container = document.getElementById("signupFormContainer");
              const userLevel = document.getElementById("interest").value;
              const userFirstName = document.getElementById("firstName").value;
              const userLastName = document.getElementById("lastName").value;
              
              container.innerHTML = `
                <div class="success-panel" style="animation: fadeInUp 0.5s forwards;">
                  <i class="fas fa-check-circle" style="font-size: 4rem; color: var(--gold);"></i>
                  <h3 style="font-size: 2rem; margin-bottom: 8px;">Welcome to the Collective!</h3>
                  <p style="color: #666; margin-bottom: 40px; font-size: 1.1rem;">Your membership account has been successfully created. Explore your member portal preview below.</p>
                  
                  <div class="dashboard-preview" style="text-align: left; box-shadow: 0 10px 30px rgba(0,0,0,0.06); border: 1px solid var(--gold);">
                    <div class="dash-header">
                      <div class="dash-welcome">
                        <h4>Welcome, ${userFirstName} ${userLastName}</h4>
                        <p>Member ID: VC-${Math.floor(10000 + Math.random() * 90000)} • Joined Just Now</p>
                      </div>
                      <span class="dash-badge">${userLevel} Level</span>
                    </div>
                    <div class="dash-body">
                      <div class="dash-grid">
                        <div class="dash-panel">
                          <h5 class="dash-section-title">Your Journeys</h5>
                          <p style="font-size: 0.95rem; color: #555; font-style: italic; margin-top: 5px;">Your travel history is clean. Book your first experience to start receiving loyalty updates!</p>
                        </div>
                        <div class="dash-panel">
                          <h5 class="dash-section-title">Your Impact</h5>
                          <ul class="dash-list">
                            <li><i class="fas fa-seedling icon-stat" style="color: #2eb8a0;"></i> Active membership status</li>
                            <li><i class="fas fa-shield-alt icon-stat" style="color: #2eb8a0;"></i> Supporting community growth</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              `;
              
              // Scroll success view into focus smoothly
              container.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
              alert("Error: " + data.message);
              if (btn) btn.disabled = false;
              if (spinner) spinner.style.display = "none";
              if (btnText) btnText.innerText = "Join Free Membership";
            }
          })
          .catch(err => {
            console.error("AJAX Error:", err);
            alert("Network error. Please try again.");
            if (btn) btn.disabled = false;
            if (spinner) spinner.style.display = "none";
            if (btnText) btnText.innerText = "Join Free Membership";
          });
        });
      }
    });

    function showRecaptchaModal(msg) {
      let modal = document.getElementById('customRecaptchaModal');
      if (!modal) return;
      if (msg) {
        let textEl = document.getElementById('customRecaptchaModalMessage');
        if (textEl) textEl.innerHTML = msg;
      }
      modal.style.display = 'flex';
      setTimeout(() => modal.classList.add('show'), 10);
    }

    function closeRecaptchaModal() {
      let modal = document.getElementById('customRecaptchaModal');
      if (!modal) return;
      modal.classList.remove('show');
      setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
  </script>

  <!-- Custom Verification Modal Backdrop & Card -->
  <div id="customRecaptchaModal" class="recaptcha-modal-backdrop" style="display:none;" onclick="if(event.target===this) closeRecaptchaModal();">
    <div class="recaptcha-modal-card">
      <div class="recaptcha-modal-icon">
        <i class="fas fa-shield-halved"></i>
      </div>
      <h3 class="recaptcha-modal-title">Verification Required</h3>
      <p id="customRecaptchaModalMessage" class="recaptcha-modal-text">
        Please verify that you are human by checking the <strong>"I'm not a robot"</strong> reCAPTCHA box before submitting your form.
      </p>
      <button type="button" class="recaptcha-modal-btn" onclick="closeRecaptchaModal()">
        Got It, Verify Now <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
      </button>
    </div>
  </div>


</body>
</html>
