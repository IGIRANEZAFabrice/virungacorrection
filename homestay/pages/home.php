<?php
  $pageTitle = 'Virunga House & Luxury Homestay Musanze | Virunga Collective';
  $pageDescription = 'Experience boutique luxury hospitality in Musanze at Virunga House. Authentic Rwandan warmth, volcano views, and bespoke immersion near Volcanoes National Park.';
  $pageKeywords = 'Virunga Homestay, luxury homestay Musanze, Virunga House, Volcanoes National Park accommodation, boutique stay Rwanda';
  $pageCss = ['room-cards.css','activity.css','home.css','day-at-virunga.css'];
  $pageHeroKey = null; // home has its own hero
  $pageScripts = ['home.js'];
  include 'includes/header.php';
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400&display=swap" rel="stylesheet">

<!-- --- HERO -------------------------------------------------- -->
<section class="hero" id="hero">
  <?php
  require_once __DIR__ . '/../config/db.php';
  // Fetch the first active hero image
  $sqlHero = "SELECT * FROM hero_images WHERE status = 'active' AND is_active = 1 ORDER BY display_order ASC LIMIT 1";
  $heroResult = $conn->query($sqlHero);

  if ($heroResult && $hero = $heroResult->fetch_assoc()) {
      $title = $hero['title']; // Titles often contain HTML like <em> so we don't always escape if trusted
      $paragraph = htmlspecialchars($hero['paragraph']);
      
      $imgVal = !empty($hero['image']) ? $hero['image'] : 'hero/1.jpg';
      if (strpos($imgVal, 'http') === 0) {
          $imgAttr = $imgVal;
      } else {
          $imgVal = preg_replace('/^(\.\/)?img\//', '', ltrim($imgVal, '/'));
          if (strpos($imgVal, '/') === false) {
              $imgVal = 'hero/' . $imgVal;
          }
          $imgAttr = './img/' . $imgVal;
      }
      ?>
      <div class="hero-static-bg" style="background-image: url('<?php echo htmlspecialchars($imgAttr); ?>');"></div>
      <div class="hero-content">
        <p class="hero-tag">VIRUNGA HOUSE</p>
        <h1 class="hero-title"><?php echo $title; ?></h1>
        <p class="hero-desc"><?php echo $paragraph; ?></p>
        <div class="hero-actions">
          <a href="https://wa.me/250784513435?text=Hello%20Virunga%20House,%20I%20would%20like%20to%20enquire%20about%20the%20rooms%20and%20availability%20to%20stay." target="_blank" rel="noopener" class="btn-primary">
            <i class="fab fa-whatsapp" style="margin-right: 8px;"></i> ENQUIRE TO STAY
          </a>
        </div>
      </div>
  <?php } ?>
</section>
    <!-- --- Stacked hero section (below slider) ----------------------- -->
    <!-- --- Home About (belt) --------------------------------------- -->
    <section id="home-about" class="reveal" data-reveal>
      <?php
      require_once __DIR__ . '/../config/db.php';
      $sqlAbout = "SELECT * FROM home_about LIMIT 1";
      $resAbout = $conn->query($sqlAbout);
      $about = $resAbout ? $resAbout->fetch_assoc() : null;

      $label = $about ? htmlspecialchars($about['label']) : 'Welcome to VIRUNGA HOUSE';
      $heading = $about ? htmlspecialchars($about['heading']) : 'Your Musanze basecamp for volcano sunrises, slow-evening fires, and effortless guided days.';
      $body = $about ? htmlspecialchars($about['body']) : 'Live inside a warm local home, wake to mountain air, and lean on accredited bilingual specialists for every trek, transfer, and taste of Rwanda, Uganda, or DRC. We blend heartfelt hosting with pro-level trip support so you can explore boldly and unwind completely.';
      
      $badge1 = ($about && !empty($about['badge_1'])) ? htmlspecialchars($about['badge_1']) : 'Family-run';
      $badge2 = ($about && !empty($about['badge_2'])) ? htmlspecialchars($about['badge_2']) : 'Tourist Info Centre';
      $badge3 = ($about && !empty($about['badge_3'])) ? htmlspecialchars($about['badge_3']) : 'Volcano & gorilla ready';

      $m1_num = $about ? (int)$about['metric_1_num'] : 12;
      $m1_suf = $about ? htmlspecialchars($about['metric_1_suffix']) : '+';
      $m1_lbl = $about ? htmlspecialchars($about['metric_1_label']) : 'Years hosting';

      $m2_num = $about ? (int)$about['metric_2_num'] : 840;
      $m2_suf = $about ? htmlspecialchars($about['metric_2_suffix']) : '';
      $m2_lbl = $about ? htmlspecialchars($about['metric_2_label']) : 'Stays curated';

      $m3_num = $about ? (int)$about['metric_3_num'] : 98;
      $m3_suf = $about ? htmlspecialchars($about['metric_3_suffix']) : '%';
      $m3_lbl = $about ? htmlspecialchars($about['metric_3_label']) : 'Guests recommend';
      ?>
      <div class="section-container about-grid">
        <div class="about-copy" data-reveal>
          <p class="section-label"><?php echo $label; ?></p>
          <h2 class="section-heading"><?php echo $heading; ?></h2>
          <p class="about-body"><?php echo $body; ?></p>
          <style>
            #home-about .about-badges {
              display: flex;
              flex-wrap: wrap;
              gap: 14px;
              margin-top: 26px;
            }
            #home-about a.modern-badge,
            #home-about .modern-badge {
              display: inline-flex !important;
              align-items: center !important;
              gap: 14px !important;
              padding: 14px 32px !important;
              background: #1b3a2b !important;
              background-color: #1b3a2b !important;
              color: #ffffff !important;
              text-decoration: none !important;
              border: 1px solid #1b3a2b !important;
              border-radius: 0 !important;
              font-family: var(--font-body, 'Jost', sans-serif) !important;
              font-size: 0.82rem !important;
              font-weight: 600 !important;
              letter-spacing: 0.16em !important;
              text-transform: uppercase !important;
              position: relative !important;
              overflow: hidden !important;
              cursor: pointer !important;
              box-shadow: 0 4px 18px rgba(18, 42, 31, 0.15) !important;
              transition: background 0.35s ease, border-color 0.35s ease, color 0.35s ease, transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease !important;
            }
            #home-about .about-badges *,
            #home-about .modern-badge * {
              background: transparent !important;
              background-color: transparent !important;
              border-radius: 0 !important;
              padding: 0 !important;
              border: none !important;
              box-shadow: none !important;
              text-decoration: none !important;
            }
            #home-about a.modern-badge::after,
            #home-about .modern-badge::after {
              content: '';
              position: absolute;
              top: 0;
              left: -100%;
              width: 100%;
              height: 100%;
              background: linear-gradient(
                90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.25) 50%,
                transparent 100%
              ) !important;
              transition: left 0.6s cubic-bezier(0.16, 1, 0.3, 1);
              pointer-events: none;
            }
            #home-about a.modern-badge .badge-arrow,
            #home-about .modern-badge .badge-arrow {
              display: inline-flex !important;
              align-items: center !important;
              justify-content: center !important;
              font-size: 0.85rem !important;
              position: relative !important;
              z-index: 2 !important;
              color: #c9a24b !important;
              transform: translateX(0);
              transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), color 0.35s ease;
            }
            #home-about a.modern-badge:hover,
            #home-about .modern-badge:hover {
              background: #c9a24b !important;
              background-color: #c9a24b !important;
              border-color: #c9a24b !important;
              color: #0d1f16 !important;
              transform: translateY(-2px);
              box-shadow: 0 10px 28px rgba(201, 162, 75, 0.38) !important;
              text-decoration: none !important;
            }
            #home-about a.modern-badge:hover::after,
            #home-about .modern-badge:hover::after {
              left: 100%;
            }
            #home-about a.modern-badge:hover .badge-arrow,
            #home-about .modern-badge:hover .badge-arrow {
              transform: translateX(6px);
              color: #0d1f16 !important;
            }
          </style>
          <div class="about-badges">
            <?php if ($badge1): ?>
              <a href="<?php echo $baseLink('rooms'); ?>" class="modern-badge">
                <?php echo htmlspecialchars($badge1); ?>
                <i class="fas fa-arrow-right badge-arrow"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
        <div class="about-visual" data-reveal data-delay="300">
          <div class="about-image-frame">
            <img src="./img/about.jpeg" alt="Cycling adventure at VIRUNGA HOUSE" class="about-image" loading="lazy">
            <div class="about-card" id="about" data-reveal data-delay="500">
              <div class="about-card__metric">
                <span class="metric-num" data-count data-target="<?php echo $m1_num; ?>" <?php if($m1_suf) echo 'data-suffix="'.$m1_suf.'"'; ?>>0</span>
                <span class="metric-label"><?php echo $m1_lbl; ?></span>
              </div>
              <div class="about-card__metric">
                <span class="metric-num" data-count data-target="<?php echo $m2_num; ?>" <?php if($m2_suf) echo 'data-suffix="'.$m2_suf.'"'; ?>>0</span>
                <span class="metric-label"><?php echo $m2_lbl; ?></span>
              </div>
              <div class="about-card__metric">
                <span class="metric-num" data-count data-target="<?php echo $m3_num; ?>" <?php if($m3_suf) echo 'data-suffix="'.$m3_suf.'"'; ?>>0</span>
                <span class="metric-label"><?php echo $m3_lbl; ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>


    <!-- ====================================================
         02 — WHY STAY AT VIRUNGA HOUSE?
    ==================================================== -->
    <section class="homestay-sec homestay-sec--why" id="why-stay">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">WHY STAY AT VIRUNGA HOUSE?</span>
          <h2 class="homestay-title">YOUR STAY CAN BE PART OF THE EXPERIENCE</h2>
          <p class="homestay-lead">
            You could stay in a hotel anywhere.<br>
            Here, your stay gives you a closer connection to Musanze and to experiences that are difficult to discover from a conventional hotel stay.
          </p>
        </div>

        <div class="why-pillars-grid" data-reveal>
          <div class="why-pillar-card">
            <span class="why-pillar-num">01</span>
            <h3 class="why-pillar-title">Stay for the night.</h3>
            <p class="why-pillar-desc">Rest in quiet, comfortable rooms framed by fresh mountain breezes.</p>
          </div>
          <div class="why-pillar-card">
            <span class="why-pillar-num">02</span>
            <h3 class="why-pillar-title">Meet people.</h3>
            <p class="why-pillar-desc">Encounter hosts, guides, and neighbours with authentic local roots.</p>
          </div>
          <div class="why-pillar-card">
            <span class="why-pillar-num">03</span>
            <h3 class="why-pillar-title">Share a meal.</h3>
            <p class="why-pillar-desc">Taste regional highland dishes made with ingredients from local farms.</p>
          </div>
          <div class="why-pillar-card">
            <span class="why-pillar-num">04</span>
            <h3 class="why-pillar-title">Create something.</h3>
            <p class="why-pillar-desc">Work with local artisans, makers, and painters on meaningful craft.</p>
          </div>
          <div class="why-pillar-card">
            <span class="why-pillar-num">05</span>
            <h3 class="why-pillar-title">Discover a story.</h3>
            <p class="why-pillar-desc">Listen to first-hand conservation insights and community heritage.</p>
          </div>
          <div class="why-pillar-card">
            <span class="why-pillar-num">06</span>
            <h3 class="why-pillar-title">Take your time.</h3>
            <p class="why-pillar-desc">Slow down, unhurried, leaving space to simply be in the moment.</p>
          </div>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <span class="homestay-tag-badge">STAY CLOSE. DISCOVER DEEPER.</span>
        </div>
      </div>
    </section>

    <!-- ====================================================
         ROOMS
    ==================================================== -->
    <section class="homestay-sec homestay-sec--rooms" id="rooms">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">ROOMS</span>
          <h2 class="homestay-title">YOUR ROOM AT VIRUNGA HOUSE</h2>
          <p class="homestay-lead">
            Our six rooms provide a comfortable, welcoming base for discovering Musanze and the Virunga.<br>
            Each room is designed for a simple, relaxed stay, with the essentials you need after a day of exploring.
          </p>
        </div>

        <div class="hs-rooms-grid" data-reveal>
          <?php
          require_once __DIR__ . '/../config/db.php';
          $sqlRooms = "SELECT * FROM rooms WHERE status = 'active' ORDER BY id ASC LIMIT 6";
          $resRooms = $conn->query($sqlRooms);
          if ($resRooms && $resRooms->num_rows > 0):
            while ($r = $resRooms->fetch_assoc()):
              $rImg = !empty($r['image']) ? $r['image'] : 'services/1.JPG';
              if (strpos($rImg, 'http') !== 0) {
                $rImg = preg_replace('/^(\.\/)?img\//', '', ltrim($rImg, '/'));
                if (strpos($rImg, '/') === false) $rImg = 'rooms/' . $rImg;
                $rImg = './img/' . $rImg;
              }
              $rTitle = htmlspecialchars($r['title']);
              $rMeters = isset($r['meters']) && $r['meters'] > 0 ? (int)$r['meters'] : 24;
              $rGuests = isset($r['guest_number']) && $r['guest_number'] > 0 ? (int)$r['guest_number'] : 2;
              $rBed = isset($r['bed_type']) && !empty($r['bed_type']) ? htmlspecialchars($r['bed_type']) : 'King Bed';
              $rSingle = isset($r['price_single']) ? (int)$r['price_single'] : 0;
              $rDouble = isset($r['price_double']) ? (int)$r['price_double'] : 0;
              $rWhatsappMsg = rawurlencode("Hello! I would like to enquire about " . $rTitle . " at Virunga House");
              $rUrl = "https://wa.me/250784513435?text=" . $rWhatsappMsg;
          ?>
          <div class="hs-room-card">
            <div class="hs-room-media">
              <img src="<?php echo htmlspecialchars($rImg); ?>" alt="<?php echo $rTitle; ?>" loading="lazy">
              <div class="hs-room-overlay"></div>
            </div>
            <span class="hs-room-badge">Room <?php echo htmlspecialchars($r['id'] ?? ''); ?></span>
            <div class="hs-room-body">
              <div class="hs-room-main-info">
                <h3 class="hs-room-name"><?php echo $rTitle; ?></h3>
                <div class="hs-room-pricing">
                  <?php if ($rSingle > 0): ?>
                    <div class="hs-price-item">
                      <span class="hs-price-val">$<?php echo $rSingle; ?></span>
                      <span class="hs-price-lbl">Single / night</span>
                    </div>
                  <?php endif; ?>
                  <?php if ($rDouble > 0): ?>
                    <div class="hs-price-item">
                      <span class="hs-price-val">$<?php echo $rDouble; ?></span>
                      <span class="hs-price-lbl">Double / night</span>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="hs-room-hover-details">
                <div class="hs-room-facts">
                  <span><i class="fa-solid fa-maximize"></i> <?php echo $rMeters; ?> m²</span>
                  <span><i class="fa-solid fa-user-group"></i> Up to <?php echo $rGuests; ?> Guests</span>
                  <span><i class="fa-solid fa-bed"></i> <?php echo $rBed; ?></span>
                </div>
                <a href="<?php echo $rUrl; ?>" target="_blank" rel="noopener" class="hs-link-btn hs-link-btn--transparent">
                  Book Now <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          <?php 
            endwhile;
          else:
          ?>
          <!-- Fallback Room Cards -->
          <div class="hs-room-card">
            <div class="hs-room-media">
              <img src="./img/hero/room.jpg" alt="Deluxe Mountain Room" loading="lazy">
              <div class="hs-room-overlay"></div>
            </div>
            <span class="hs-room-badge">Room 01</span>
            <div class="hs-room-body">
              <div class="hs-room-main-info">
                <h3 class="hs-room-name">Deluxe Mountain Room</h3>
                <div class="hs-room-pricing">
                  <div class="hs-price-item">
                    <span class="hs-price-val">$65</span>
                    <span class="hs-price-lbl">Single / night</span>
                  </div>
                  <div class="hs-price-item">
                    <span class="hs-price-val">$85</span>
                    <span class="hs-price-lbl">Double / night</span>
                  </div>
                </div>
              </div>
              <div class="hs-room-hover-details">
                <div class="hs-room-facts">
                  <span><i class="fa-solid fa-maximize"></i> 28 m²</span>
                  <span><i class="fa-solid fa-user-group"></i> 2 Guests</span>
                  <span><i class="fa-solid fa-bed"></i> King Bed</span>
                </div>
                <a href="<?php echo $baseLink('rooms'); ?>" class="hs-link-btn hs-link-btn--transparent">
                  Book Now <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="hs-room-card">
            <div class="hs-room-media">
              <img src="./img/hero/room1.jpg" alt="Garden View Room" loading="lazy">
              <div class="hs-room-overlay"></div>
            </div>
            <span class="hs-room-badge">Room 02</span>
            <div class="hs-room-body">
              <div class="hs-room-main-info">
                <h3 class="hs-room-name">Garden View Room</h3>
                <div class="hs-room-pricing">
                  <div class="hs-price-item">
                    <span class="hs-price-val">$55</span>
                    <span class="hs-price-lbl">Single / night</span>
                  </div>
                  <div class="hs-price-item">
                    <span class="hs-price-val">$75</span>
                    <span class="hs-price-lbl">Double / night</span>
                  </div>
                </div>
              </div>
              <div class="hs-room-hover-details">
                <div class="hs-room-facts">
                  <span><i class="fa-solid fa-maximize"></i> 24 m²</span>
                  <span><i class="fa-solid fa-user-group"></i> 2 Guests</span>
                  <span><i class="fa-solid fa-bed"></i> Queen Bed</span>
                </div>
                <a href="<?php echo $baseLink('rooms'); ?>" class="hs-link-btn hs-link-btn--transparent">
                  Book Now <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="hs-room-card">
            <div class="hs-room-media">
              <img src="./img/hero/1776268009_Mgahinga.jpg" alt="Twin Balcony Suite" loading="lazy">
              <div class="hs-room-overlay"></div>
            </div>
            <span class="hs-room-badge">Room 03</span>
            <div class="hs-room-body">
              <div class="hs-room-main-info">
                <h3 class="hs-room-name">Twin Balcony Suite</h3>
                <div class="hs-room-pricing">
                  <div class="hs-price-item">
                    <span class="hs-price-val">$70</span>
                    <span class="hs-price-lbl">Single / night</span>
                  </div>
                  <div class="hs-price-item">
                    <span class="hs-price-val">$90</span>
                    <span class="hs-price-lbl">Double / night</span>
                  </div>
                </div>
              </div>
              <div class="hs-room-hover-details">
                <div class="hs-room-facts">
                  <span><i class="fa-solid fa-maximize"></i> 30 m²</span>
                  <span><i class="fa-solid fa-user-group"></i> 2 Guests</span>
                  <span><i class="fa-solid fa-bed"></i> Twin Beds</span>
                </div>
                <a href="<?php echo $baseLink('rooms'); ?>" class="hs-link-btn hs-link-btn--transparent">
                  Book Now <i class="fas fa-arrow-right"></i>
                </a>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
            EXPLORE THE SIX ROOMS <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         EXPERIENCES FROM THE HOUSE
    ==================================================== -->
    <section class="homestay-sec homestay-sec--experiences" id="experiences">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">EXPERIENCES FROM THE HOUSE</span>
          <h2 class="homestay-title">MORE THAN A PLACE TO STAY</h2>
          <p class="homestay-lead">
            Some of the most memorable moments of your time in Musanze can happen beyond the usual itinerary.<br>
            For guests staying at Virunga House, selected Virunga Collective experiences can become part of their stay.
          </p>
        </div>

        <div class="hs-exp-grid" data-reveal>
          <!-- 01: Understand -->
          <div class="hs-exp-card">
            <div class="hs-exp-media">
              <img src="./img/hero/1776268009_Mgahinga.jpg" alt="The Virunga Conservation Salon" loading="lazy">
              <span class="hs-exp-tag">UNDERSTAND</span>
            </div>
            <div class="hs-exp-body">
              <div>
                <h3 class="hs-exp-title">The Virunga Conservation Salon</h3>
                <p class="hs-exp-desc">
                  A private encounter exploring the people, stories and realities behind conservation in the Virunga.
                </p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                Explore Experience <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- 02: Create -->
          <div class="hs-exp-card">
            <div class="hs-exp-media">
              <img src="./img/activities/1778427637_twinlake.jpeg" alt="The Virunga Living Canvas" loading="lazy">
              <span class="hs-exp-tag">CREATE</span>
            </div>
            <div class="hs-exp-body">
              <div>
                <h3 class="hs-exp-title">The Virunga Living Canvas</h3>
                <p class="hs-exp-desc">
                  A participatory encounter where guests create alongside local artists.
                </p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                Explore Experience <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- 03: Share -->
          <div class="hs-exp-card">
            <div class="hs-exp-media">
              <img src="./img/activities/1778346998_coffee.jpeg" alt="The Virunga Table" loading="lazy">
              <span class="hs-exp-tag">SHARE</span>
            </div>
            <div class="hs-exp-body">
              <div>
                <h3 class="hs-exp-title">The Virunga Table</h3>
                <p class="hs-exp-desc">
                  Food, conversation and connection around a shared table.
                </p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                Explore Experience <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-primary">
            EXPLORE ALL EXPERIENCES <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         DISCOVER MORE (PRIVATE DISCOVERIES)
    ==================================================== -->
    <section class="homestay-sec homestay-sec--discoveries" id="discoveries">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">DISCOVER MORE</span>
          <h2 class="homestay-title">PRIVATE DISCOVERIES</h2>
          <p class="homestay-lead">
            For guests who want to go beyond the signature experiences:
          </p>
        </div>

        <div class="hs-disc-grid" data-reveal>
          <!-- Brewing Table -->
          <div class="hs-disc-card">
            <div class="hs-disc-media">
              <img src="./img/activities/1778346998_coffee.jpeg" alt="The Virunga Brewing Table" loading="lazy">
            </div>
            <div class="hs-disc-body">
              <div>
                <span class="hs-disc-num">Private Discovery</span>
                <h3 class="hs-disc-title">The Virunga Brewing Table</h3>
                <p class="hs-disc-desc">Participate in a local brewing tradition.</p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                View Discovery <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Maker's Table -->
          <div class="hs-disc-card">
            <div class="hs-disc-media">
              <img src="./img/activities/1778425219_kidgorilla.jpeg" alt="The Virunga Maker's Table" loading="lazy">
            </div>
            <div class="hs-disc-body">
              <div>
                <span class="hs-disc-num">Private Discovery</span>
                <h3 class="hs-disc-title">The Virunga Maker's Table</h3>
                <p class="hs-disc-desc">Create alongside local makers.</p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                View Discovery <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>

          <!-- Family Table -->
          <div class="hs-disc-card">
            <div class="hs-disc-media">
              <img src="./img/about.jpeg" alt="The Virunga Family Table" loading="lazy">
            </div>
            <div class="hs-disc-body">
              <div>
                <span class="hs-disc-num">Private Discovery</span>
                <h3 class="hs-disc-title">The Virunga Family Table</h3>
                <p class="hs-disc-desc">A closer encounter with everyday life and shared traditions.</p>
              </div>
              <a href="<?php echo $baseLink('activity'); ?>" class="hs-link-arrow">
                View Discovery <i class="fas fa-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-outline">
            VIEW ALL PRIVATE DISCOVERIES <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         MAKE MUSANZE YOUR BASE
    ==================================================== -->
    <section class="homestay-sec homestay-sec--musanze" id="musanze-base">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">MAKE MUSANZE YOUR BASE</span>
          <h2 class="homestay-title">MORE TIME. MORE TO DISCOVER.</h2>
          <p class="homestay-lead">
            Musanze does not have to be a one-night stop between major activities.<br>
            With more time, guests can explore different sides of the region—wildlife, nature, conservation, food, creativity and local life—without rushing from one activity to another.
          </p>
        </div>

        <div class="hs-musanze-grid" data-reveal>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-paw"></i></div>
            <h3>Gorillas & Golden Monkeys</h3>
            <p>Bespoke permits, morning briefings, and guided treks through ancient volcanic bamboo forests.</p>
          </div>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-mountain"></i></div>
            <h3>Volcanoes & Nature</h3>
            <p>Bisoke crater lake hikes, Karisimbi alpine ascents, and panoramic trails around the twin lakes.</p>
          </div>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-tree"></i></div>
            <h3>Buhanga</h3>
            <p>Sacred historical coronation forest steeped in ancient Rwandan royalty and biodiversity.</p>
          </div>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-feather"></i></div>
            <h3>Gishwati</h3>
            <p>Restored montane rainforest canopy reserve, home to golden monkeys and rare bird species.</p>
          </div>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-water"></i></div>
            <h3>Lake Kivu</h3>
            <p>Tranquil highland bays, sunset boat journeys, and singing night fishermen in traditional canoes.</p>
          </div>
          <div class="hs-musanze-card">
            <div class="hs-musanze-icon"><i class="fas fa-palette"></i></div>
            <h3>Local Life & Creativity</h3>
            <p>Art collectives, basket-weaving cooperatives, ceramic ateliers, and bustling mountain markets.</p>
          </div>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-primary">
            EXPLORE MUSANZE <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         STAY LONGER / PRIVATE VIRUNGA JOURNEYS (COMPACT MODERN CTA)
    ==================================================== -->
    <section class="homestay-sec homestay-sec--longer" id="stay-longer">
      <div class="homestay-wrap">
        <div class="hs-compact-cta" data-reveal>
          <div class="hs-compact-cta__content">
            <span class="hs-compact-cta__eyebrow">STAY LONGER · PRIVATE VIRUNGA JOURNEYS</span>
            <h2 class="hs-compact-cta__title">Your Time, Your Pace</h2>
            <p class="hs-compact-cta__desc">
              For guests staying several nights, we curate a private sequence of experiences tailored to your interests, creative discoveries, and space to simply slow down.
            </p>
          </div>
          <div class="hs-compact-cta__action">
            <a href="https://wa.me/250784513435?text=Hello!%20I%20am%20interested%20in%20a%20Private%20Virunga%20Journey" target="_blank" rel="noopener" class="btn-hs-gold hs-btn-compact">
              ENQUIRE A PRIVATE JOURNEY <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         STAY YOUR WAY
    ==================================================== -->
    <section class="homestay-sec homestay-sec--stay-way" id="stay-your-way">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">STAY YOUR WAY</span>
          <h2 class="homestay-title">CHOOSE THE STAY THAT SUITS YOU</h2>
          <p class="homestay-lead">
            Different travellers look for different accommodation styles. Through our own house and selected partners, we can offer guests a choice while keeping the Virunga Collective experience accessible.
          </p>
        </div>

        <div class="hs-partners-grid" data-reveal>
          <!-- 1: Virunga House -->
          <div class="hs-partner-card hs-partner-card--featured">
            <div>
              <div class="hs-partner-badge">Our House</div>
              <h3 class="hs-partner-name">VIRUNGA HOUSE</h3>
              <p class="hs-partner-subtitle">Our locally rooted house</p>
              <p class="hs-partner-desc">
                Six rooms, a personal atmosphere and direct connection to Virunga Collective's experiences.
              </p>
            </div>
            <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
              DISCOVER VIRUNGA HOUSE <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- 2: Virunga Hotel -->
          <div class="hs-partner-card">
            <div>
              <div class="hs-partner-badge">Hotel Partner</div>
              <h3 class="hs-partner-name">VIRUNGA HOTEL</h3>
              <p class="hs-partner-subtitle">Our hotel partner</p>
              <p class="hs-partner-desc">
                For guests who prefer a more conventional hotel setting and its particular level of comfort, facilities and hospitality.
              </p>
            </div>
            <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-outline">
              EXPLORE VIRUNGA HOTEL <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- 3: Virunga Inn -->
          <div class="hs-partner-card">
            <div>
              <div class="hs-partner-badge">Accommodation Partner</div>
              <h3 class="hs-partner-name">VIRUNGA INN</h3>
              <p class="hs-partner-subtitle">Our accommodation partner</p>
              <p class="hs-partner-desc">
                For guests whose preferences align with its own accommodation style, comfort and value.
              </p>
            </div>
            <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-outline">
              EXPLORE VIRUNGA INN <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>

        <p class="hs-partners-note" data-reveal>
          The choice is yours. Your access to Virunga Collective experiences remains the same.
        </p>
      </div>
    </section>

    <!-- ====================================================
         DINING / THE VIRUNGA TABLE
    ==================================================== -->
    <section class="homestay-sec homestay-sec--dining" id="dining">
      <div class="homestay-wrap">
        <div class="hs-split-grid" data-reveal>
          <div class="hs-split-media">
            <img src="./img/activities/1778348756_silvereat.jpeg" alt="The Virunga Table Dining" loading="lazy">
          </div>
          <div class="hs-split-content">
            <span class="homestay-eyebrow">DINING</span>
            <h2 class="homestay-title">THE VIRUNGA TABLE</h2>
            <p class="homestay-lead" style="margin: 0 0 16px;">
              Food is one of the simplest ways to come closer to a place.
            </p>
            <p class="hs-text">
              Discover local flavours, ingredients and stories through meals shared at Virunga House.<br><br>
              The Virunga Table can exist both as a dining expression within the House and as one of Virunga Collective's signature experiences.
            </p>
            <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-primary">
              DISCOVER THE VIRUNGA TABLE <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         EVENINGS
    ==================================================== -->
    <section class="homestay-sec homestay-sec--evenings" id="evenings">
      <div class="homestay-wrap">
        <div class="hs-split-grid hs-split-grid--reverse" data-reveal>
          <div class="hs-split-content">
            <span class="homestay-eyebrow">EVENINGS</span>
            <h2 class="homestay-title">WHEN THE DAY SLOWS DOWN</h2>
            <div class="hs-evenings-lines">
              <p>Some evenings are for conversation.</p>
              <p>Some are for coffee.</p>
              <p>Some are for sharing a meal.</p>
            </div>
            <p class="hs-text" style="margin-top: 20px;">
              And when conditions and programming allow, guests may gather around the fire and listen to stories from the region.
            </p>
            <a href="<?php echo $baseLink('about-us'); ?>" class="btn-hs-primary">
              DISCOVER LIFE AT THE HOUSE <i class="fas fa-arrow-right"></i>
            </a>
          </div>
          <div class="hs-split-media">
            <img src="./img/day/7.jpeg" alt="Evenings around the fire at Virunga House" loading="lazy">
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         PRACTICAL INFORMATION
    ==================================================== -->
    <section class="homestay-sec homestay-sec--practical" id="good-to-know">
      <div class="homestay-wrap">
        <div class="homestay-header center" data-reveal>
          <span class="homestay-eyebrow">PRACTICAL INFORMATION</span>
          <h2 class="homestay-title">GOOD TO KNOW</h2>
          <p class="homestay-lead">
            Everything guests need before arriving:
          </p>
        </div>

        <div class="hs-practical-grid" data-reveal>
          <div class="hs-practical-item">
            <div class="hs-practical-num">01</div>
            <h4>Location</h4>
            <p>Musanze town basecamp, 15 min from Volcanoes National Park HQ in Kinigi.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">02</div>
            <h4>Six-Room Capacity</h4>
            <p>Intimate boutique setting ensuring personal hospitality and peaceful nights.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">03</div>
            <h4>Room Details</h4>
            <p>Private en-suite bathrooms, artisan bedding, reading desks, and garden views.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">04</div>
            <h4>Amenities</h4>
            <p>Solar-assisted hot showers, daily housekeeping, organic toiletries, and lounge.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">05</div>
            <h4>Dining</h4>
            <p>Fresh daily breakfast included. Farm-to-table lunch and dinners prepared on request.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">06</div>
            <h4>Check-in / Check-out</h4>
            <p>Check-in from 14:00. Check-out by 11:00. Flexible luggage storage for early trekkers.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">07</div>
            <h4>Parking</h4>
            <p>Complimentary secure gated parking on-site for safari 4x4s and private vehicles.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">08</div>
            <h4>Connectivity</h4>
            <p>Reliable high-speed fiber Wi-Fi throughout guest rooms and shared spaces.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">09</div>
            <h4>Booking Information</h4>
            <p>Direct bookings via WhatsApp or online enquiry with custom itinerary support.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">10</div>
            <h4>Cancellation Policy</h4>
            <p>Transparent cancellation terms and date-transfer flexibility with advance notice.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">11</div>
            <h4>Payment Information</h4>
            <p>Major credit cards (Visa/Mastercard), Bank Transfer, Mobile Money (MoMo), and Cash.</p>
          </div>
          <div class="hs-practical-item">
            <div class="hs-practical-num">12</div>
            <h4>What to Expect</h4>
            <p>Heartfelt hosting, genuine local connection, quiet evenings, and mountain views.</p>
          </div>
        </div>

        <div class="homestay-cta-center" data-reveal>
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
            PLAN YOUR STAY <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         FINAL INVITATION
    ==================================================== -->
    <section class="homestay-sec homestay-sec--invitation" id="invitation" style="background-image: linear-gradient(rgba(12, 28, 20, 0.86), rgba(12, 28, 20, 0.9)), url('./img/day/7.jpeg');">
      <div class="homestay-wrap">
        <div class="hs-invitation-box" data-reveal>
          <span class="homestay-eyebrow" style="color: var(--gold, #c9a24b);">FINAL INVITATION</span>
          <h2 class="homestay-title" style="color: #ffffff;">COME FOR THE VIRUNGA. STAY FOR THE CONNECTION.</h2>
          <p class="homestay-lead" style="color: rgba(246, 242, 233, 0.88); max-width: 720px; margin: 0 auto 36px;">
            Whether you come for the gorillas, discover Musanze after a trek, or simply want to experience Rwanda differently, Virunga House gives you a place to slow down, connect and discover more.
          </p>
          <div class="hs-dual-actions">
            <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-gold">
              ENQUIRE TO STAY <i class="fas fa-arrow-right"></i>
            </a>
            <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-outline-light">
              EXPLORE EXPERIENCES <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>
    <?php include 'includes/footer.php'; ?>