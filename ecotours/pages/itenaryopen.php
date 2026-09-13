<?php
require_once './itenaryopenhandler.php';
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
        <a href="./itinerary_print.php?id=<?php echo $tour_id; ?>" target="_blank" class="print-itinerary-link" style="display: inline-flex; align-items: center; gap: 8px; color: #1b3a2b; font-weight: 600; font-size: 0.92rem; text-decoration: none; padding: 11px 20px; border: 1px solid #c9a24b; border-radius: 50px; background: #ffffff; transition: all 0.25s ease; box-shadow: 0 4px 12px rgba(0,0,0,0.06);">
          <i class="fas fa-print"></i> Print Dossier
        </a>
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

    <!-- html2pdf Library for Multilingual PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <!-- HIDDEN LUXURY PDF PRINTABLE TEMPLATE (Pre-rendered for html2pdf.js export) -->
    <div id="pdfPrintableTemplate" style="display: none;">
      <div class="pdf-document-body" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1c211d; background: #ffffff; width: 794px; box-sizing: border-box; line-height: 1.55;">

        <!-- ============ COVER ============ -->
        <div style="position: relative; width: 100%; height: 460px; overflow: hidden;">
          <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>"
               style="width: 100%; height: 100%; object-fit: cover; display: block;" alt="Cover">
          <div style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(10,15,12,0.05) 0%, rgba(10,15,12,0.15) 45%, rgba(10,15,12,0.88) 100%);"></div>

          <div style="position: absolute; top: 28px; left: 34px; color: #f6f2e9; font-size: 11px; letter-spacing: 3px; text-transform: uppercase; font-weight: 600;">
            Virunga Ecotours
          </div>
          <div style="position: absolute; top: 28px; right: 34px; color: rgba(246,242,233,0.85); font-size: 10px; letter-spacing: 1px;">
            www.virungajourneys.com
          </div>

          <div style="position: absolute; left: 34px; right: 34px; bottom: 28px;">
            <div style="display: inline-block; color: #c9a24b; font-size: 10.5px; letter-spacing: 2.5px; text-transform: uppercase; font-weight: 700; margin-bottom: 10px;">
              <?php
                $countryName = ucfirst(trim($tour['country']));
                if (strtolower($countryName) === 'congo' || stripos($countryName, 'congo') !== false) {
                    $countryName = 'DR Congo';
                }
                echo htmlspecialchars(strtoupper($tour['category'])) . ' &nbsp;·&nbsp; ' . htmlspecialchars(strtoupper($countryName));
              ?>
            </div>
            <h1 style="margin: 0; color: #ffffff; font-size: 34px; font-weight: 700; letter-spacing: -0.3px; line-height: 1.15; max-width: 620px;">
              <?php echo htmlspecialchars($tour['title']); ?>
            </h1>
          </div>
        </div>

        <!-- ============ QUICK FACTS STRIP ============ -->
        <table style="width: 100%; border-collapse: collapse; border-bottom: 1px solid #e9e5db;">
          <tr>
            <?php
              $facts = [
                'Duration' => (int)$tour['days_count'] . ' Day' . ($tour['days_count'] > 1 ? 's' : ''),
                'Destination' => $countryName,
                'Category' => $tour['category'],
                'Format' => 'Private & Guided',
              ];
              $i = 0; $count = count($facts);
              foreach ($facts as $label => $value):
                $i++;
                $borderRight = $i < $count ? 'border-right: 1px solid #e9e5db;' : '';
            ?>
            <td style="width: 25%; padding: 16px 10px; text-align: center; <?php echo $borderRight; ?>">
              <div style="font-size: 9px; text-transform: uppercase; letter-spacing: 1.5px; color: #8a9089; margin-bottom: 4px;">
                <?php echo htmlspecialchars($label); ?>
              </div>
              <div style="font-size: 14px; font-weight: 700; color: #1c211d;">
                <?php echo htmlspecialchars($value); ?>
              </div>
            </td>
            <?php endforeach; ?>
          </tr>
        </table>

        <!-- ============ OVERVIEW ============ -->
        <div style="padding: 26px 34px 6px;">
          <p style="margin: 0; font-size: 16px; line-height: 1.75; color: #333d35; font-weight: 300;">
            <?php echo htmlspecialchars($tour['short_description']); ?>
          </p>
        </div>

        <!-- ============ ITINERARY TIMELINE ============ -->
        <div style="padding: 30px 34px 10px;">
          <div style="font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: #c9a24b; font-weight: 700; margin-bottom: 18px;">
            Day-by-Day Itinerary
          </div>

          <?php $dayCount = count($days); foreach ($days as $idx => $day): $isLast = ($idx === $dayCount - 1); ?>
            <div style="page-break-inside: avoid; break-inside: avoid; position: relative; padding-left: 44px; padding-bottom: <?php echo $isLast ? '4px' : '22px'; ?>;">

              <?php if (!$isLast): ?>
              <div style="position: absolute; left: 13px; top: 28px; bottom: 0; width: 1px; background: #e2ded2;"></div>
              <?php endif; ?>

              <div style="position: absolute; left: 0; top: 0; width: 28px; height: 28px; border-radius: 50%; border: 1.5px solid #122a1f; color: #122a1f; font-size: 11px; font-weight: 700; display: flex; align-items: center; justify-content: center; background: #ffffff;">
                <?php echo sprintf('%02d', $day['day_number']); ?>
              </div>

              <div style="font-size: 15px; font-weight: 700; color: #122a1f; margin-bottom: 5px; padding-top: 4px;">
                <?php echo htmlspecialchars($day['day_title']); ?>
              </div>
              <div style="font-size: 12.5px; color: #4a544c; line-height: 1.65;">
                <?php echo nl2br(htmlspecialchars($day['day_description'])); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <?php if (!empty($tour['why_attend'])): ?>
        <!-- ============ WHY THIS JOURNEY ============ -->
        <div style="page-break-inside: avoid; break-inside: avoid; padding: 22px 34px; margin-top: 6px; border-top: 1px solid #e9e5db; border-bottom: 1px solid #e9e5db;">
          <div style="font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: #c9a24b; font-weight: 700; margin-bottom: 10px;">
            Why This Journey
          </div>
          <div style="font-size: 12.5px; line-height: 1.7; color: #333d35;">
            <?php echo nl2br(htmlspecialchars($tour['why_attend'])); ?>
          </div>
        </div>
        <?php endif; ?>

        <!-- ============ INCLUSIONS / EXCLUSIONS ============ -->
        <div style="page-break-inside: avoid; break-inside: avoid; padding: 26px 34px 6px;">
          <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <td style="width: 50%; vertical-align: top; padding-right: 20px;">
                <div style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #122a1f; font-weight: 700; margin-bottom: 10px; border-bottom: 1px solid #122a1f; padding-bottom: 6px;">
                  Included
                </div>
                <?php if (!empty($included)): foreach ($included as $inc): ?>
                  <div style="font-size: 11.5px; color: #333d35; margin-bottom: 6px; padding-left: 14px; position: relative;">
                    <span style="position: absolute; left: 0; color: #122a1f; font-weight: 700;">–</span>
                    <?php echo htmlspecialchars($inc['item_description']); ?>
                  </div>
                <?php endforeach; else: ?>
                  <div style="font-size: 11.5px; color: #333d35;">Dedicated vehicle, expert guide, accommodation & breakfast.</div>
                <?php endif; ?>
              </td>
              <td style="width: 50%; vertical-align: top; padding-left: 20px; border-left: 1px solid #e9e5db;">
                <div style="font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #8a5a3a; font-weight: 700; margin-bottom: 10px; border-bottom: 1px solid #8a5a3a; padding-bottom: 6px;">
                  Not Included
                </div>
                <?php if (!empty($excluded)): foreach ($excluded as $exc): ?>
                  <div style="font-size: 11.5px; color: #4f4237; margin-bottom: 6px; padding-left: 14px; position: relative;">
                    <span style="position: absolute; left: 0; color: #8a5a3a; font-weight: 700;">–</span>
                    <?php echo htmlspecialchars($exc['item_description']); ?>
                  </div>
                <?php endforeach; else: ?>
                  <div style="font-size: 11.5px; color: #4f4237;">International flights, visas, tips, personal expenses.</div>
                <?php endif; ?>
              </td>
            </tr>
          </table>
        </div>

        <?php if (!empty($toBring)): ?>
        <!-- ============ WHAT TO BRING ============ -->
        <div style="page-break-inside: avoid; break-inside: avoid; padding: 10px 34px 6px; font-size: 11.5px; color: #4a544c;">
          <span style="text-transform: uppercase; letter-spacing: 1.5px; font-size: 10px; font-weight: 700; color: #122a1f;">Packing &amp; Preparation&nbsp;&nbsp;</span>
          <?php
            $tbList = [];
            foreach ($toBring as $tb) { $tbList[] = htmlspecialchars($tb['item_description']); }
            echo implode(' &nbsp;·&nbsp; ', $tbList);
          ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($pricingTiers)): ?>
        <!-- ============ RATES ============ -->
        <div style="page-break-inside: avoid; break-inside: avoid; padding: 26px 34px 6px;">
          <div style="font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: #c9a24b; font-weight: 700; margin-bottom: 14px;">
            Rates (<?php echo htmlspecialchars($pricingYear ?? date('Y')); ?>)
          </div>
          <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
              <tr style="border-bottom: 1.5px solid #122a1f;">
                <th style="text-align: left; padding: 6px 4px; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: #122a1f;">Group Size</th>
                <th style="text-align: right; padding: 6px 4px; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: #122a1f;">Per Person (USD)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pricingTiers as $tier): ?>
                <tr style="border-bottom: 1px solid #efece3;">
                  <td style="padding: 8px 4px; color: #333d35;"><?php echo htmlspecialchars($tier['group_size']); ?></td>
                  <td style="padding: 8px 4px; text-align: right; font-weight: 700; color: #122a1f;">
                    $<?php echo number_format((float)$tier['price_per_person'], 2); ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <?php if (!empty($pricingNotes)): ?>
            <div style="font-size: 10.5px; color: #8a9089; margin-top: 8px; line-height: 1.6;">
              <?php foreach ($pricingNotes as $n): ?>
                <div>· <?php echo htmlspecialchars($n['note']); ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ============ HIGHLIGHT GALLERY (bottom) ============ -->
        <?php if (!empty($highlights)): ?>
        <div style="page-break-inside: avoid; break-inside: avoid; padding: 30px 34px 6px;">
          <div style="font-size: 10px; letter-spacing: 2.5px; text-transform: uppercase; color: #c9a24b; font-weight: 700; margin-bottom: 14px;">
            Journey Highlights
          </div>
          <table style="width: 100%; border-collapse: collapse;">
            <tr>
              <?php
                $galleryShots = array_slice($highlights, 0, 4);
                $shotCount = count($galleryShots);
                foreach ($galleryShots as $gIdx => $shot):
                  $padRight = ($gIdx < $shotCount - 1) ? '6px' : '0';
                  $padLeft  = ($gIdx > 0) ? '6px' : '0';
              ?>
              <td style="width: <?php echo (100 / $shotCount); ?>%; padding-right: <?php echo $padRight; ?>; padding-left: <?php echo $padLeft; ?>;">
                <img src="../<?php echo htmlspecialchars($shot['image_path']); ?>"
                     style="width: 100%; height: 130px; object-fit: cover; display: block; border-radius: 3px;" alt="">
              </td>
              <?php endforeach; ?>
            </tr>
          </table>
        </div>
        <?php endif; ?>

        <!-- ============ FOOTER ============ -->
        <div style="page-break-inside: avoid; break-inside: avoid; margin-top: 24px; padding: 22px 34px; border-top: 1px solid #e9e5db; text-align: center;">
          <div style="font-size: 13px; font-weight: 700; color: #122a1f; letter-spacing: 0.3px; margin-bottom: 4px;">
            Ready to plan or customize this journey?
          </div>
          <div style="font-size: 11px; color: #6b756e;">
            WhatsApp +250 784 513 435 &nbsp;·&nbsp; info@virungajourneys.com &nbsp;·&nbsp; www.virungajourneys.com
          </div>
          <div style="font-size: 9.5px; color: #a3ab9f; margin-top: 6px; letter-spacing: 0.5px;">
            VIRUNGA ECOTOURS &nbsp;·&nbsp; MUSANZE, NORTHERN PROVINCE, RWANDA
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

      const template = document.getElementById('pdfPrintableTemplate');
      if (!template) {
        if (btn) { btn.disabled = false; btn.innerHTML = originalBtnHtml; }
        if (floatingBtn) { floatingBtn.disabled = false; floatingBtn.innerHTML = originalFloatingHtml; }
        window.open('./itinerary_print.php?id=<?php echo $tour_id; ?>', '_blank');
        return;
      }

      // Create an off-screen render container
      const renderContainer = document.createElement('div');
      renderContainer.id = 'activePdfRenderContainer';
      renderContainer.style.position = 'fixed';
      renderContainer.style.left = '0';
      renderContainer.style.top = '0';
      renderContainer.style.width = '794px'; // Standard A4 pixel width at 96 DPI
      renderContainer.style.background = '#ffffff';
      renderContainer.style.zIndex = '-99999';
      renderContainer.style.opacity = '1';
      renderContainer.style.pointerEvents = 'none';
      renderContainer.innerHTML = template.innerHTML;
      document.body.appendChild(renderContainer);

      // Preload images inside container
      const images = renderContainer.querySelectorAll('img');
      const imgPromises = Array.from(images).map(img => {
        return new Promise(resolve => {
          if (img.complete && img.naturalHeight !== 0) {
            resolve();
          } else {
            img.onload = () => resolve();
            img.onerror = () => resolve();
          }
        });
      });

      Promise.all(imgPromises).then(() => {
        const tourTitle = <?php echo json_encode($tour['title']); ?>;
        const opt = {
          margin:       [8, 8, 10, 8],
          filename:     'Virunga_Itinerary_' + (tourTitle.replace(/[^a-zA-Z0-9]/g, '_')) + '.pdf',
          image:        { type: 'jpeg', quality: 0.98 },
          html2canvas:  { scale: 2, useCORS: true, logging: false, scrollY: 0, windowWidth: 794 },
          jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
          pagebreak:    { mode: ['css', 'legacy'] }
        };

        html2pdf().set(opt).from(renderContainer).save().then(() => {
          renderContainer.remove();
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
          }
          if (floatingBtn) {
            floatingBtn.disabled = false;
            floatingBtn.innerHTML = originalFloatingHtml;
          }
        }).catch((err) => {
          console.error("PDF generation error:", err);
          renderContainer.remove();
          if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalBtnHtml;
          }
          if (floatingBtn) {
            floatingBtn.disabled = false;
            floatingBtn.innerHTML = originalFloatingHtml;
          }
          // Fallback to print dossier
          window.open('./itinerary_print.php?id=<?php echo $tour_id; ?>', '_blank');
        });
      }).catch(() => {
        renderContainer.remove();
        if (btn) {
          btn.disabled = false;
          btn.innerHTML = originalBtnHtml;
        }
        if (floatingBtn) {
          floatingBtn.disabled = false;
          floatingBtn.innerHTML = originalFloatingHtml;
        }
      });
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
