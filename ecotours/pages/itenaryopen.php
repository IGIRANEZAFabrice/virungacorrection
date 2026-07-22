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

      <div class="specialist-container">
        <div class="phone-number">
          <i class="fas fa-phone phone-icon"></i>
          <span>+250 784 513 435</span>
        </div>
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
          <form method="POST" action="./itenaryopenhandler.php" id="contactForm">
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
                  placeholder="info@virungaecotours.com"
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
            alert("Please complete the reCAPTCHA verification.");
          }
        });
      }
    });
    </script>
    <?php include('./includes/footer.php'); ?>
  </body>
  <script src="js/new.js" defer></script>
</html>
