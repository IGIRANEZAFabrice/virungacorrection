<?php
require_once './itenaryopenhandler.php';

if (!function_exists('virungaImgBase64')) {
    function virungaImgBase64($relPath) {
        if (empty($relPath)) return '';
        $clean = ltrim(str_replace('../', '', $relPath), '/');
        $fullPath = dirname(__DIR__) . '/' . $clean;
        if (file_exists($fullPath)) {
            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
            $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');
            $data = file_get_contents($fullPath);
            return 'data:' . $mime . ';base64,' . base64_encode($data);
        }
        return '../' . $clean;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/new.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <script src="../js/script.js" defer></script>
    <title><?php echo htmlspecialchars($tour['country'] . '  || ' . $tour['title']); ?></title>
    <link rel="stylesheet" href="../css/open.css" />
    <script src="../js/header.js" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- Structured Data for Tour -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Tour",
      "name": "<?php echo htmlspecialchars($tour['title']); ?>",
      "description": "<?php echo htmlspecialchars($tour['short_description']); ?>",
      "image": "https://virungaecotours.com/<?php echo htmlspecialchars($tour['cover_image_path']); ?>",
      "tourDuration": "P<?php echo $tour['days_count']; ?>D",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "USD",
        "price": "<?php echo !empty($pricingTiers) ? $pricingTiers[0]['price_per_person'] : '0'; ?>",
        "availability": "https://schema.org/InStock",
        "url": "https://virungaecotours.com/pages/itenaryopen.php?id=<?php echo $tour['tour_id']; ?>"
      },
      "itinerary": [
        <?php foreach ($days as $index => $day): ?>
        {
          "@type": "Day",
          "name": "<?php echo htmlspecialchars($day['day_title']); ?>",
          "description": "<?php echo htmlspecialchars(substr(str_replace(["\r", "\n"], ' ', $day['day_description']), 0, 300)); ?>"
        }<?php echo ($index < count($days) - 1) ? ',' : ''; ?>
        <?php endforeach; ?>
      ],
      "provider": {
        "@type": "Organization",
        "name": "Virunga Ecotours",
        "url": "https://virungaecotours.com"
      }
    }
    </script>
  </head>
  <body>
    <?php include('./includes/header.php'); ?>

    <section class="hero">
      <div class="hero-image-container">
        <img
          src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>"
          alt="<?php echo htmlspecialchars($tour['title']); ?>"
          class="hero-image"
        />
      </div>
      <h1 class="hero-title"><?php echo htmlspecialchars($tour['title']); ?></h1>
    </section>

    <section class="overlay-card">
      <h2 class="info-title">Did you know we can tailor any tour?</h2>
      <p class="info-subtitle">We are happy to plan your tailor-made holiday</p>

      <div class="specialist-container" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: center; margin-top: 15px;">
        <div class="phone-number">
          <i class="fas fa-phone phone-icon"></i>
          <span>+250 784 513 435</span>
        </div>
        <button id="downloadPdfBtn" class="download-pdf-btn" onclick="generateItineraryPdf();">
          <i class="fas fa-file-pdf"></i> Download Itinerary PDF
        </button>
      </div>
    </section>

    <section class="second-section">
      <div class="tour-info">
        <div class="tour-header">
          <div class="info-item">
            <i class="fas fa-calendar-alt info-icon"></i>
            <div class="info-label">Duration</div>
            <div class="info-value"><?php echo $tour['days_count']; ?> days</div>
          </div>
          <div class="info-item">
            <i class="fas fa-bookmark info-icon"></i>
            <div class="info-label">Category</div>
            <div class="info-value"><?php echo htmlspecialchars($tour['category']); ?></div>
          </div>
          <div class="info-item">
            <i class="fas fa-map-marker info-icon"></i>
            <div class="info-label">Country</div>
            <div class="info-value">
              
              <?php 
                  $country = trim($tour['country']); // Get and trim country value to remove whitespace
                  
                  // Try multiple approaches to match "congo"
                  if (strtolower($country) == "congo" || 
                      stripos($country, "congo") !== false) {
                      echo "DR Congo";
                  } else {
                      echo htmlspecialchars($country); // Display all other countries normally
                  }
              ?>

          </div>
          </div>
        </div>

        <div class="main-content">
          <div class="image-column">
            <?php foreach(array_slice($highlights, 0, 2) as $highlight): ?>
              <div class="placeholder-image">
                <img src="../<?php echo htmlspecialchars($highlight['image_path']); ?>" alt="" />
              </div>
            <?php endforeach; ?>
          </div>

          <div class="center-column">
            <div class="tour-subtitle">Tour Highlights</div>
            <h1 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h1>
            <p class="tour-description"><?php echo htmlspecialchars($tour['short_description']); ?></p>
          </div>

          <div class="image-column">
            <?php foreach(array_slice($highlights, 2, 2) as $highlight): ?>
              <div class="placeholder-image">
                <img src="../<?php echo htmlspecialchars($highlight['image_path']); ?>" alt="" />
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <section class="itinerary" id="itenary">
      <div class="container">
        <h1>Itinerary</h1>
        <div class="itinerary-days">
          <?php foreach($days as $index => $day): ?>
            <div class="itinerary-day" id="day<?php echo $day['day_number']; ?>">
              <div class="day-header">
                <h3><?php echo htmlspecialchars($day['day_title']); ?></h3>
                <div class="day-toggle"><?php echo $index === 0 ? '&#8722;' : '&#43;'; ?></div>
              </div>
              <div class="day-content <?php echo $index === 0 ? 'active' : ''; ?>">
                <p class="day-description"><?php echo nl2br(htmlspecialchars($day['day_description'])); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <script>
        document.addEventListener("DOMContentLoaded", function () {
          // Get all day elements
          const days = document.querySelectorAll(".itinerary-day");

          // Set up each day
          days.forEach((day) => {
            const header = day.querySelector(".day-header");
            const content = day.querySelector(".day-content");
            const toggle = day.querySelector(".day-toggle");

            // Set initial state (day1 is open, others are closed)
            if (day.id === "day1") {
              content.classList.add("active");
              toggle.innerHTML = "&#8722;"; // Minus sign
              toggle.style.transform = "rotate(180deg)";
            } else {
              content.classList.remove("active");
              toggle.innerHTML = "&#43;"; // Plus sign
              toggle.style.transform = "rotate(0deg)";
            }

            // Add click event listener
            header.addEventListener("click", function () {
              // Toggle the active class
              content.classList.toggle("active");

              // Update the toggle icon and rotation
              if (content.classList.contains("active")) {
                toggle.innerHTML = "&#8722;"; // Minus sign
                toggle.style.transform = "rotate(180deg)";
              } else {
                toggle.innerHTML = "&#43;"; // Plus sign
                toggle.style.transform = "rotate(0deg)";
              }
            });
          });
        });
      </script>
    </section>

    <?php if (!empty($pricingTiers)): ?>
      <section class="pricing-section">
        <div class="container">
          <h2 class="info-title">
            Rates (<?php echo htmlspecialchars($pricingYear ?? date('Y')); ?>)
          </h2>

          <div class="pricing-table-wrapper">
            <table class="pricing-table">
              <thead>
                <tr>
                  <th>Group size</th>
                  <th>Price per person</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pricingTiers as $tier): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($tier['group_size']); ?></td>
                    <td>
                      $<?php echo number_format((float)$tier['price_per_person'], 2); ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <?php if (!empty($pricingNotes)): ?>
            <div class="pricing-notes">
              <h3>Seasons / Discounts</h3>
              <ul>
                <?php foreach ($pricingNotes as $note): ?>
                  <li><?php echo nl2br(htmlspecialchars($note['note'])); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Membership Advertisement Banner -->
    <section class="membership-ad-section" style="background: linear-gradient(135deg, #122a1f 0%, #1b3a2b 100%); color: #f6f2e9; padding: 44px 32px; border-radius: 16px; margin: 40px auto; max-width: 1100px; box-shadow: 0 12px 35px rgba(0,0,0,0.18); position: relative; overflow: hidden;">
      <div style="position: absolute; right: -40px; bottom: -40px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(201, 162, 75, 0.18) 0%, rgba(0,0,0,0) 70%); border-radius: 50%; pointer-events: none;"></div>
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px; position: relative; z-index: 2;">
        <div style="flex: 1 1 500px;">
          <div style="display: inline-block; background: rgba(201, 162, 75, 0.18); border: 1px solid rgba(201, 162, 75, 0.4); color: #c9a24b; padding: 4px 14px; border-radius: 50px; font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600; margin-bottom: 12px;">
            <i class="fas fa-crown" style="margin-right: 6px;"></i> Exclusive Virunga Membership
          </div>
          <h3 style="font-family: var(--font-display), 'Cormorant Garamond', serif; font-size: 2rem; color: #f6f2e9; margin-bottom: 10px; line-height: 1.25;">
            Unlock VIP Travel Perks & Community Conservation Benefits
          </h3>
          <p style="color: rgba(246, 242, 233, 0.88); font-size: 1.02rem; margin-bottom: 0; line-height: 1.6;">
            Join the <strong>Virunga Collective Membership</strong> for up to 15% off boutique homestays, complimentary gorilla trek concierge service, priority booking, and direct community conservation support.
          </p>
        </div>
        <div style="flex-shrink: 0;">
          <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('membership')) : '../../pages/membership.php'; ?>" style="display: inline-flex; align-items: center; gap: 10px; background: #c9a24b; color: #122a1f; padding: 14px 28px; border-radius: 8px; font-weight: 600; font-size: 1rem; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(201, 162, 75, 0.3);">
            Explore Membership Tiers <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <section class="contact-section">
      <div class="contact-decoration"></div>

      <div class="contact-container">
        <!-- Contact Form -->
        <div class="contact-form-container">
          <h4>Book: <?php echo htmlspecialchars($tour['title']); ?></h4>

          <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
              <?php 
                switch($_GET['error']) {
                  case 'missing_fields': echo 'Please fill all required fields'; break;
                  case 'invalid_email': echo 'Please enter a valid email address'; break;
                  case 'recaptcha_failed': echo 'Please complete the reCAPTCHA verification'; break;
                  case 'database': echo 'Booking failed. Please try again later'; break;
                  default: echo 'An error occurred';
                }
              ?>
            </div>
          <?php endif; ?>

          <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="success-message">
              Booking successful! We'll contact you shortly || <a href="../pages/payments.php">Payment methods</a>
            </div>
          <?php endif; ?>
          <form method="POST" action="./itenaryopenhandler.php" id="contactForm" onsubmit="var l=(document.cookie.match(/googtrans=\/en\/([a-z\-]{2,5})/) || [])[1] || 'en'; this.querySelector('[name=user_lang]').value=l;">
            <input type="hidden" name="user_lang" value="en">
            <div class="form-row">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  placeholder="Your names "
                  required
                />
                <div class="input-icon">
                  <i class="fas fa-user"></i>
                </div>
              </div>

              <div class="form-group">
                <label for="email">Email Address</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="info@virungajourneys.com"
                  required
                />
                <div class="input-icon">
                  <i class="fas fa-envelope"></i>
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="phone">Phone Number</label>
                <input
                  type="tel"
                  id="phone"
                  name="phone"
                  placeholder="+250784513435"
                  required
                />
                <div class="input-icon">
                  <i class="fas fa-phone"></i>
                </div>
              </div>

              <div class="form-group">
                <label for="date">Travel Date</label>
                <input type="date" id="date" name="date" required />
                <div class="input-icon">
                  <i class="fas fa-calendar"></i>
                </div>
              </div>
            </div>

            <input type="hidden" name="tour_id" value="<?php echo $tour_id; ?>">
            <div class="form-group" style="margin-bottom: 20px;">
              <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
            </div>
            <button type="submit" class="submit-btn">Book Now</button>
          </form>

          <div class="form-footer">
            We usually respond within <span>24 hours</span> || <a href="../pages/payments.php" target="_blank">Payment methods</a>
          </div>
        </div>

        <!-- Map Container -->
        <div class="map-container">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.4580752914685!2d29.629988274965942!3d-1.4961735984897848!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dc5a4203062269%3A0x9911bb9e4e9bc6ea!2sVIRUNGA%20ECOTOURS!5e0!3m2!1sen!2srw!4v1742665095418!5m2!1sen!2srw"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>

          <div class="map-overlay">
            <h4>VIRUNGA ECOTOURS</h4>
            <p>Visit us for unforgettable adventures in Rwanda</p>
          </div>
        </div>
      </div>
    </section>

    <div class="inclusion-container">
      <div class="inclusion-items">
        <h3>What's Included</h3>
        <div class="inclusion-list">
          <?php foreach($included as $item): ?>
            <div class="inclusion-item">
              <i class="fas fa-check"></i>
              <span><?php echo htmlspecialchars($item['item_description']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <h3 style="margin-top: 20px">What's Not Included</h3>
        <div class="inclusion-list">
          <?php foreach($excluded as $item): ?>
            <div class="inclusion-item">
              <i class="fas fa-times"></i>
              <span><?php echo htmlspecialchars($item['item_description']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <h3 style="margin-top: 20px">What to bring</h3>
        <div class="inclusion-list">
          <?php foreach($toBring as $item): ?>
            <div class="inclusion-item">
              <i class="fas fa-check"></i>
              <span><?php echo htmlspecialchars($item['item_description']); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <!-- Special Notes -->
    <div class="special-notes">
      <div class="special-notes-inner">
        <h3>Special Notes</h3>
        <div class="notes-list">
          <div class="note-item">
            <h4>Pricing for International and Local Guests</h4>
            <p>All listed prices are in USD and apply to bookings made from outside Rwanda. For Rwandan residents, please contact us directly to make a booking and receive rates in local currency.</p>
          </div>
          <div class="note-item">
            <h4>Currency and Tax Information</h4>
            <p>Prices shown are in USD and include all applicable Rwandan taxes. If you wish to pay in Rwandan Francs (RWF), please request an offline booking. More details can be found in our Payment Options.</p>
          </div>
          <div class="note-item">
            <h4>Travel and Medical Insurance</h4>
            <p>Please note that foreign medical insurance does not cover helicopter evacuation in Rwanda. For your safety, all our tours include emergency evacuation insurance as standard. We also strongly recommend that every traveler carries comprehensive emergency evacuation coverage.</p>
          </div>
          <div class="note-item">
            <h4>Commitment to Fair Employment</h4>
            <p>At Virunga Ecotours, we are dedicated to ethical practices. We pay fair wages and make regular contributions to pensions, maternity benefits, and community health schemes for all our employees.</p>
          </div>
        </div>
      </div>
    </div>

    <section class="included">
      <h1>Why Attend?</h1>

      <div class="container">
        <div class="included-column">
          <?php echo nl2br(htmlspecialchars($tour['why_attend'])); ?>
        </div>
      </div>
    </section>
    <div class="also-like">
      <h2 class="section-title">Recommended Experiences</h2>
      <div class="tours-cards" id="toursContainer">
        <?php if (empty($relatedTours)): ?>
          <p class="no-tours">No similar tours available at the moment.</p>
        <?php else: ?>
          <?php foreach ($relatedTours as $relatedTour): ?>
            <div class="tour-card">
              <div class="tour-card-image">
                <img
                  src="../<?php echo htmlspecialchars($relatedTour['cover_image_path']); ?>"
                  alt="<?php echo htmlspecialchars($relatedTour['title']); ?>"
                />
                <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($relatedTour['category'])); ?></div>
                <div class="tour-offer">AVAILABLE</div>
              </div>
              <div class="tour-card-content">
                <div class="tour-tags"></div>
                <h3><?php echo htmlspecialchars($relatedTour['title']); ?></h3>
                <div class="tour-duration"><?php echo $relatedTour['days_count']; ?> DAY<?php echo $relatedTour['days_count'] > 1 ? 'S' : ''; ?></div>
                <p><?php echo htmlspecialchars(substr($relatedTour['short_description'], 0, 200)) . '...'; ?></p>
                <a href="itenaryopen.php?id=<?php echo $relatedTour['tour_id']; ?>" class="read-more-btn">READ MORE</a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const form = document.getElementById('contactForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          const r = this.querySelector('[name="g-recaptcha-response"]');
          if (r && !r.value) {
            e.preventDefault();
            showRecaptchaModal("Please verify that you are human by checking the <strong>\"I'm not a robot\"</strong> box before submitting your tour booking.");
          }
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

    <style>
    .recaptcha-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(13, 31, 22, 0.85);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      z-index: 999999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    .recaptcha-modal-backdrop.show {
      opacity: 1;
    }
    .recaptcha-modal-card {
      background: #122a1f;
      border: 1px solid rgba(201, 162, 75, 0.4);
      border-radius: 16px;
      padding: 36px 28px;
      max-width: 440px;
      width: 100%;
      text-align: center;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(201, 162, 75, 0.2);
      transform: translateY(20px) scale(0.95);
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      color: #f6f2e9;
      font-family: var(--font-body, 'Jost', sans-serif);
    }
    .recaptcha-modal-backdrop.show .recaptcha-modal-card {
      transform: translateY(0) scale(1);
    }
    .recaptcha-modal-icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 18px;
      background: rgba(201, 162, 75, 0.18);
      border: 1px solid rgba(201, 162, 75, 0.5);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #c9a24b;
      font-size: 1.8rem;
    }
    .recaptcha-modal-title {
      font-family: var(--font-display, 'Cormorant Garamond', serif);
      font-size: 1.85rem;
      color: #f6f2e9;
      margin-bottom: 10px;
      font-weight: 600;
    }
    .recaptcha-modal-text {
      font-size: 1rem;
      color: rgba(246, 242, 233, 0.88);
      margin-bottom: 24px;
      line-height: 1.6;
    }
    .recaptcha-modal-btn {
      background: #c9a24b;
      color: #122a1f;
      border: none;
      padding: 13px 28px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 4px 15px rgba(201, 162, 75, 0.3);
      width: 100%;
    }
    .download-pdf-btn {
      background: linear-gradient(135deg, #c9a24b 0%, #a37f30 100%);
      color: #1b3a2b;
      border: none;
      padding: 12px 26px;
      font-size: 0.95rem;
      font-weight: 700;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 6px 18px rgba(201, 162, 75, 0.35);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-family: inherit;
    }
    .download-pdf-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(201, 162, 75, 0.5);
      background: linear-gradient(135deg, #d8b056 0%, #b89139 100%);
    }

    /* Floating Bottom-Right PDF Download Button */
    .floating-pdf-btn-container {
      position: fixed;
      bottom: 28px;
      right: 28px;
      z-index: 99999;
      opacity: 0;
      visibility: hidden;
      transform: translateY(25px) scale(0.92);
      transition: opacity 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                  transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                  visibility 0.35s ease;
      pointer-events: none;
    }

    .floating-pdf-btn-container.visible {
      opacity: 1;
      visibility: visible;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }

    .floating-pdf-btn {
      background: linear-gradient(135deg, #122a1f 0%, #1b3a2b 100%);
      color: #f6f2e9;
      border: 1.5px solid #c9a24b;
      padding: 13px 24px;
      font-size: 0.92rem;
      font-weight: 700;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35), 0 0 18px rgba(201, 162, 75, 0.35);
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      font-family: inherit;
      transition: all 0.25s ease;
    }

    .floating-pdf-btn:hover {
      background: linear-gradient(135deg, #c9a24b 0%, #a37f30 100%);
      color: #122a1f;
      border-color: #f6f2e9;
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 14px 35px rgba(0, 0, 0, 0.45), 0 0 25px rgba(201, 162, 75, 0.5);
    }

    .floating-pdf-btn .floating-pdf-icon {
      color: #c9a24b;
      font-size: 1.1rem;
      transition: color 0.25s ease;
    }

    .floating-pdf-btn:hover .floating-pdf-icon {
      color: #122a1f;
    }

    @media (max-width: 768px) {
      .floating-pdf-btn-container {
        bottom: 18px;
        right: 18px;
      }
      .floating-pdf-btn {
        padding: 10px 18px;
        font-size: 0.82rem;
        gap: 8px;
      }
    }
    </style>
    <!-- Direct High-Resolution PDF Export Engine -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- HIDDEN LUXURY PDF PRINTABLE TEMPLATE -->
    <div id="pdfPrintableTemplate" style="display: none;">
      <div style="font-family: 'Jost', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #202924; background: #ffffff; width: 800px; padding: 35px 40px; box-sizing: border-box; line-height: 1.5; font-size: 13px;">
        
        <!-- HEADER -->
        <table style="width: 100%; border-collapse: collapse; border-bottom: 2px solid #c9a24b; padding-bottom: 15px; margin-bottom: 22px;">
          <tr>
            <td style="vertical-align: middle;">
              <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 26px; font-weight: 700; letter-spacing: 2px; color: #122a1f; text-transform: uppercase; line-height: 1;">VIRUNGA ECOTOURS</div>
              <div style="font-size: 10.5px; color: #607066; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 5px; font-weight: 500;">Regenerative Safaris & Community Journeys in East Africa</div>
            </td>
            <td style="text-align: right; font-size: 11px; color: #3b4740; line-height: 1.45;">
              <strong style="color: #8e681c; font-size: 11.5px;">www.virungajourneys.com</strong><br>
              WhatsApp: +250 784 513 435<br>
              info@virungajourneys.com
            </td>
          </tr>
        </table>

        <!-- TITLE & METRICS -->
        <div>
          <div style="display: inline-block; background: #f4ede0; color: #8e681c; font-weight: 700; font-size: 10px; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">
            <?php 
              $countryName = ucfirst(trim($tour['country']));
              if (strtolower($countryName) === 'congo' || stripos($countryName, 'congo') !== false) {
                  $countryName = 'DR Congo';
              }
              echo htmlspecialchars(strtoupper($tour['category'])) . ' · ' . htmlspecialchars(strtoupper($countryName));
            ?>
          </div>
          <h1 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 28px; color: #122a1f; font-weight: 700; line-height: 1.15; margin: 0 0 14px 0;">
            <?php echo htmlspecialchars($tour['title']); ?>
          </h1>

          <table style="width: 100%; border-collapse: collapse; background: #fbfaf7; border: 1px solid #e6e2d8; border-radius: 6px; margin-bottom: 20px;">
            <tr>
              <td style="width: 25%; padding: 10px 14px; border-right: 1px solid #e6e2d8; text-align: center;">
                <div style="font-size: 9.5px; text-transform: uppercase; color: #607066; letter-spacing: 0.5px; font-weight: 600;">Duration</div>
                <div style="font-size: 14px; font-weight: 700; color: #122a1f; margin-top: 2px;"><?php echo (int)$tour['days_count']; ?> Day<?php echo $tour['days_count'] > 1 ? 's' : ''; ?></div>
              </td>
              <td style="width: 25%; padding: 10px 14px; border-right: 1px solid #e6e2d8; text-align: center;">
                <div style="font-size: 9.5px; text-transform: uppercase; color: #607066; letter-spacing: 0.5px; font-weight: 600;">Destination</div>
                <div style="font-size: 14px; font-weight: 700; color: #122a1f; margin-top: 2px;"><?php echo htmlspecialchars($countryName); ?></div>
              </td>
              <td style="width: 25%; padding: 10px 14px; border-right: 1px solid #e6e2d8; text-align: center;">
                <div style="font-size: 9.5px; text-transform: uppercase; color: #607066; letter-spacing: 0.5px; font-weight: 600;">Category</div>
                <div style="font-size: 14px; font-weight: 700; color: #122a1f; margin-top: 2px;"><?php echo htmlspecialchars($tour['category']); ?></div>
              </td>
              <td style="width: 25%; padding: 10px 14px; text-align: center;">
                <div style="font-size: 9.5px; text-transform: uppercase; color: #607066; letter-spacing: 0.5px; font-weight: 600;">Format</div>
                <div style="font-size: 14px; font-weight: 700; color: #122a1f; margin-top: 2px;">Private & Guided</div>
              </td>
            </tr>
          </table>
        </div>

        <!-- COVER PHOTO (FULL WIDTH TOP) -->
        <div style="margin-bottom: 20px; border-radius: 6px; overflow: hidden; border: 1px solid #dcd7cc;">
          <img src="<?php echo virungaImgBase64($tour['cover_image_path']); ?>" alt="Cover Photo" style="width: 100%; height: 260px; object-fit: cover; display: block;">
        </div>

        <!-- EXECUTIVE OVERVIEW -->
        <div style="background: #fdfbf7; border-left: 4px solid #c9a24b; border-top: 1px solid #efeae0; border-right: 1px solid #efeae0; border-bottom: 1px solid #efeae0; border-radius: 0 6px 6px 0; padding: 14px 18px; margin-bottom: 24px;">
          <div style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 1px; color: #8e681c; font-weight: 700; margin-bottom: 4px;">Executive Journey Overview</div>
          <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 15px; line-height: 1.55; font-style: italic; color: #26312a;">
            "<?php echo htmlspecialchars($tour['short_description']); ?>"
          </div>
        </div>

        <!-- DAY-BY-DAY ITINERARY -->
        <div style="border-bottom: 2px solid #122a1f; padding-bottom: 6px; margin: 26px 0 16px 0;">
          <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 19px; color: #122a1f; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Detailed Day-by-Day Itinerary</div>
        </div>

        <?php foreach ($days as $day): ?>
          <div style="page-break-inside: avoid; break-inside: avoid; background: #ffffff; border: 1px solid #e6e2d8; border-radius: 6px; padding: 13px 16px; margin-bottom: 11px;">
            <table style="width: 100%; border-collapse: collapse;">
              <tr>
                <td style="width: 68px; vertical-align: top;">
                  <span style="background: #1b3a2b; color: #f4ede0; font-weight: 700; font-size: 10px; padding: 4px 8px; border-radius: 4px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;">DAY <?php echo sprintf('%02d', $day['day_number']); ?></span>
                </td>
                <td style="vertical-align: top; padding-left: 8px;">
                  <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 16px; color: #122a1f; font-weight: 700; margin: 0 0 6px 0;"><?php echo htmlspecialchars($day['day_title']); ?></div>
                  <div style="font-size: 12px; line-height: 1.6; color: #3b4740;"><?php echo nl2br(htmlspecialchars($day['day_description'])); ?></div>
                </td>
              </tr>
            </table>
          </div>
        <?php endforeach; ?>

        <?php if (!empty($tour['why_attend'])): ?>
          <!-- WHY CHOOSE THIS JOURNEY -->
          <div style="page-break-inside: avoid; break-inside: avoid; background: #f5f9f6; border: 1px solid #cfe2d5; border-radius: 6px; padding: 15px 18px; margin-bottom: 20px;">
            <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 16px; font-weight: 700; color: #1b3a2b; text-transform: uppercase; margin-bottom: 6px;">✦ Why Choose This Experience</div>
            <div style="font-size: 12px; line-height: 1.6; color: #24352b;"><?php echo nl2br(htmlspecialchars($tour['why_attend'])); ?></div>
          </div>
        <?php endif; ?>

        <!-- INCLUSIONS & EXCLUSIONS -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; page-break-inside: avoid; break-inside: avoid;">
          <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 7px;">
              <div style="background: #f8faf8; border: 1px solid #d3e4d7; border-radius: 6px; padding: 14px 16px;">
                <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 15px; font-weight: 700; color: #174223; border-bottom: 1px solid #d3e4d7; padding-bottom: 5px; margin: 0 0 8px 0;">✔ What is Included</h4>
                <ul style="padding-left: 16px; font-size: 11.5px; line-height: 1.55; margin: 0;">
                  <?php if (!empty($included)): ?>
                    <?php foreach ($included as $inc): ?>
                      <li style="color: #2c3d32; margin-bottom: 4px;"><?php echo htmlspecialchars($inc['item_description']); ?></li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li style="color: #2c3d32; margin-bottom: 4px;">Dedicated safari vehicle & expert tour guide</li>
                    <li style="color: #2c3d32; margin-bottom: 4px;">All scheduled itinerary activities</li>
                    <li style="color: #2c3d32; margin-bottom: 4px;">Selected accommodation & breakfast</li>
                  <?php endif; ?>
                </ul>
              </div>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 7px;">
              <div style="background: #fdfafb; border: 1px solid #edd5d5; border-radius: 6px; padding: 14px 16px;">
                <h4 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 15px; font-weight: 700; color: #782626; border-bottom: 1px solid #edd5d5; padding-bottom: 5px; margin: 0 0 8px 0;">✖ What is Not Included</h4>
                <ul style="padding-left: 16px; font-size: 11.5px; line-height: 1.55; margin: 0;">
                  <?php if (!empty($excluded)): ?>
                    <?php foreach ($excluded as $exc): ?>
                      <li style="color: #4f3333; margin-bottom: 4px;"><?php echo htmlspecialchars($exc['item_description']); ?></li>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <li style="color: #4f3333; margin-bottom: 4px;">International flights and entry visas</li>
                    <li style="color: #4f3333; margin-bottom: 4px;">Personal expenses, tips & gratuities</li>
                    <li style="color: #4f3333; margin-bottom: 4px;">Unlisted drinks and activities</li>
                  <?php endif; ?>
                </ul>
              </div>
            </td>
          </tr>
        </table>

        <?php if (!empty($toBring)): ?>
          <!-- WHAT TO BRING -->
          <div style="page-break-inside: avoid; break-inside: avoid; background: #fbfaf7; border: 1px solid #e6e2d8; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; font-size: 11.5px; color: #404d45;">
            <strong style="color: #122a1f; font-size: 12.5px; font-family: 'Cormorant Garamond', Georgia, serif;">🎒 Recommended Packing & Preparation: </strong>
            <?php 
            $tbList = [];
            foreach ($toBring as $tb) {
                $tbList[] = htmlspecialchars($tb['item_description']);
            }
            echo implode(' · ', $tbList);
            ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($pricingTiers)): ?>
          <!-- PRICING TIERS -->
          <div style="margin-bottom: 20px; page-break-inside: avoid; break-inside: avoid;">
            <div style="border-bottom: 2px solid #122a1f; padding-bottom: 6px; margin: 15px 0 16px 0;">
              <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 19px; color: #122a1f; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Standard Rates (<?php echo htmlspecialchars($pricingYear ?? date('Y')); ?>)</div>
            </div>
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #e6e2d8; font-size: 12px;">
              <thead>
                <tr style="background: #fbfaf7;">
                  <th style="padding: 8px 12px; border: 1px solid #e6e2d8; text-align: left; font-weight: 700;">Group Size</th>
                  <th style="padding: 8px 12px; border: 1px solid #e6e2d8; text-align: right; font-weight: 700;">Price per Person (USD)</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pricingTiers as $tier): ?>
                  <tr>
                    <td style="padding: 8px 12px; border: 1px solid #e6e2d8;"><?php echo htmlspecialchars($tier['group_size']); ?></td>
                    <td style="padding: 8px 12px; border: 1px solid #e6e2d8; text-align: right; font-weight: 700; color: #122a1f;">$<?php echo number_format((float)$tier['price_per_person'], 2); ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <?php if (!empty($pricingNotes)): ?>
              <div style="font-size: 11px; color: #607066; margin-top: 6px;">
                <?php foreach ($pricingNotes as $n): ?>
                  <div>• <?php echo htmlspecialchars($n['note']); ?></div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($highlights)): ?>
          <!-- JOURNEY HIGHLIGHTS GALLERY (BOTTOM - 4 PER ROW) -->
          <div style="margin-bottom: 22px; page-break-inside: avoid; break-inside: avoid;">
            <div style="border-bottom: 2px solid #122a1f; padding-bottom: 6px; margin: 15px 0 12px 0;">
              <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 19px; color: #122a1f; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Journey Photo Highlights</div>
            </div>
            <?php 
              $highlightRows = array_chunk($highlights, 4);
              foreach ($highlightRows as $rIdx => $row):
                $isLastRow = ($rIdx === count($highlightRows) - 1);
            ?>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: <?php echo $isLastRow ? '0' : '10px'; ?>;">
              <tr>
                <?php for ($i = 0; $i < 4; $i++): 
                  $shot = $row[$i] ?? null;
                  $padRight = ($i < 3) ? '6px' : '0';
                  $padLeft = ($i > 0) ? '6px' : '0';
                ?>
                <td style="width: 25%; padding-right: <?php echo $padRight; ?>; padding-left: <?php echo $padLeft; ?>; vertical-align: top;">
                  <?php if ($shot): ?>
                    <img src="<?php echo virungaImgBase64($shot['image_path']); ?>"
                         style="width: 100%; height: 125px; object-fit: cover; display: block; border-radius: 4px; border: 1px solid #dcd7cc;" alt="Highlight">
                  <?php endif; ?>
                </td>
                <?php endfor; ?>
              </tr>
            </table>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- PEACE OF MIND & ETHICAL TOURISM -->
        <div style="background: #fbfaf7; border-left: 3px solid #7d8e83; padding: 10px 14px; margin-bottom: 20px; font-size: 11px; color: #4e5e54; line-height: 1.5; page-break-inside: avoid;">
          <strong>Guest Safety & Ethics:</strong> Standard emergency medical & evacuation coverage is included on all expeditions. We uphold ethical fair wages, local community reinvestment, and conservation stewardship on every journey.
        </div>

        <!-- BOOKING FOOTER -->
        <div style="page-break-inside: avoid; break-inside: avoid; background: #122a1f; color: #f4ede0; border-radius: 8px; padding: 20px 24px; text-align: center; margin-top: 24px;">
          <div style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: 19px; font-weight: 700; color: #c9a24b; margin-bottom: 5px;">Ready to Plan or Customize This Journey?</div>
          <div style="font-size: 12px; color: #dbe4dc; line-height: 1.5; margin-bottom: 12px;">
            Our dedicated journey planners are available to adapt dates, accommodation levels, and private activities to your exact travel wishes.
          </div>
          <div style="font-size: 12.5px; font-weight: 600; color: #ffffff;">
            <span>📱 WhatsApp: +250 784 513 435</span>
            <span style="margin: 0 8px; color: #c9a24b;">·</span>
            <span>✉ Email: info@virungajourneys.com</span>
            <span style="margin: 0 8px; color: #c9a24b;">·</span>
            <span>🌐 www.virungajourneys.com</span>
          </div>
          <div style="font-size: 10.5px; color: #9cb1a3; margin-top: 8px;">
            Virunga Ecotours · Musanze, Northern Province, Rwanda · East Africa
          </div>
        </div>

      </div>
    </div>

    <!-- Floating Bottom-Right PDF Download Button -->
    <div id="floatingPdfBtnContainer" class="floating-pdf-btn-container">
      <button id="floatingPdfBtn" class="floating-pdf-btn" onclick="generateItineraryPdf();" title="Download Itinerary PDF">
        <span class="floating-pdf-icon"><i class="fas fa-file-pdf"></i></span>
        <span class="floating-pdf-text">Download PDF</span>
      </button>
    </div>

    <script>
    function generateItineraryPdf() {
      const btn = document.getElementById("downloadPdfBtn");
      const floatingBtn = document.getElementById("floatingPdfBtn");
      const originalBtnHtml = btn ? btn.innerHTML : '';
      const originalFloatingHtml = floatingBtn ? floatingBtn.innerHTML : '';

      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
      }
      if (floatingBtn) {
        floatingBtn.disabled = true;
        floatingBtn.innerHTML = '<span class="floating-pdf-icon"><i class="fas fa-spinner fa-spin"></i></span> <span class="floating-pdf-text">Generating...</span>';
      }

      // Create or show luxury loading overlay
      let overlay = document.getElementById('pdfLoadingOverlay');
      if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'pdfLoadingOverlay';
        overlay.style.position = 'fixed';
        overlay.style.inset = '0';
        overlay.style.background = 'rgba(18, 42, 31, 0.92)';
        overlay.style.backdropFilter = 'blur(6px)';
        overlay.style.zIndex = '999999';
        overlay.style.display = 'flex';
        overlay.style.flexDirection = 'column';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.color = '#ffffff';
        overlay.style.fontFamily = "'Jost', -apple-system, BlinkMacSystemFont, sans-serif";
        overlay.innerHTML = `
          <div style="width: 52px; height: 52px; border: 3.5px solid rgba(201, 162, 75, 0.25); border-top-color: #c9a24b; border-radius: 50%; animation: spinPdf 0.85s linear infinite; margin-bottom: 20px;"></div>
          <div style="font-size: 20px; font-weight: 700; letter-spacing: 0.5px; color: #ffffff;">Preparing Your Itinerary PDF</div>
          <div style="font-size: 13px; color: #c9a24b; margin-top: 8px; font-weight: 500;">Compiling highlights, itinerary timeline & rates...</div>
          <style>@keyframes spinPdf { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
        `;
        document.body.appendChild(overlay);
      } else {
        overlay.style.display = 'flex';
      }

      const template = document.getElementById('pdfPrintableTemplate');
      if (!template) {
        finishLoading();
        return;
      }

      // Render container: placed in DOM at 0,0 underneath the overlay
      const renderContainer = document.createElement('div');
      renderContainer.id = 'activePdfRenderContainer';
      renderContainer.style.position = 'absolute';
      renderContainer.style.left = '0px';
      renderContainer.style.top = '0px';
      renderContainer.style.width = '800px';
      renderContainer.style.background = '#ffffff';
      renderContainer.style.zIndex = '999998';
      renderContainer.style.display = 'block';
      renderContainer.innerHTML = template.innerHTML;
      document.body.appendChild(renderContainer);

      function finishLoading() {
        if (renderContainer && renderContainer.parentNode) {
          renderContainer.remove();
        }
        if (overlay) {
          overlay.style.display = 'none';
        }
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalBtnHtml;
        }
        if (floatingBtn) {
          floatingBtn.disabled = false;
          floatingBtn.innerHTML = originalFloatingHtml;
        }
      }

      setTimeout(() => {
        html2canvas(renderContainer, {
          scale: 2,
          useCORS: true,
          allowTaint: true,
          logging: false,
          scrollY: 0,
          scrollX: 0,
          windowWidth: 800
        }).then(canvas => {
          if (!canvas || canvas.width === 0 || canvas.height === 0) {
            console.error("Canvas capture produced empty output");
            finishLoading();
            return;
          }

          const { jsPDF } = window.jspdf;
          const pdf = new jsPDF('p', 'mm', 'a4');
          const pageWidth = 210;
          const pageHeight = 297;
          const imgData = canvas.toDataURL('image/jpeg', 0.95);
          const imgWidth = pageWidth;
          const imgHeight = (canvas.height * imgWidth) / canvas.width;

          let heightLeft = imgHeight;
          let position = 0;

          // First page
          pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight, '', 'FAST');
          heightLeft -= pageHeight;

          // Multi-page slicing
          while (heightLeft > 0) {
            position -= pageHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight, '', 'FAST');
            heightLeft -= pageHeight;
          }

          const tourTitle = <?php echo json_encode($tour['title']); ?>;
          const safeName = 'Virunga_Itinerary_' + (tourTitle.replace(/[^a-zA-Z0-9]/g, '_')) + '.pdf';
          pdf.save(safeName);
          finishLoading();
        }).catch(err => {
          console.error("PDF generation error:", err);
          finishLoading();
        });
      }, 400);
    }

    // Scroll Detection: Show Floating PDF Button when scrolling past the top section
    document.addEventListener("DOMContentLoaded", function() {
      const mainBtn = document.getElementById("downloadPdfBtn");
      const floatingContainer = document.getElementById("floatingPdfBtnContainer");

      if (mainBtn && floatingContainer) {
        if ('IntersectionObserver' in window) {
          const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                floatingContainer.classList.remove("visible");
              } else {
                const rect = mainBtn.getBoundingClientRect();
                if (rect.top < 0) {
                  floatingContainer.classList.add("visible");
                } else {
                  floatingContainer.classList.remove("visible");
                }
              }
            });
          }, { threshold: 0.1 });

          observer.observe(mainBtn);
        } else {
          window.addEventListener("scroll", function() {
            const rect = mainBtn.getBoundingClientRect();
            if (rect.bottom < 0) {
              floatingContainer.classList.add("visible");
            } else {
              floatingContainer.classList.remove("visible");
            }
          });
        }
      }
    });
    </script>
    <?php include('./includes/footer.php'); ?>
  </body>
  <script src="js/new.js" defer></script>
</html>
