<?php
  $pageTitle = 'Virunga House | Locally Rooted Stay in Musanze, Rwanda';
  $pageDescription = 'An intimate place to stay in Musanze beneath the Virunga volcanoes. Comfortable base for discovering the landscape, people and experiences of the Virunga.';
  $pageKeywords = 'Virunga House, luxury homestay Musanze, boutique stay Rwanda, Volcanoes National Park accommodation, Musanze lodging';
  $pageCss = ['room-cards.css','activity.css','home.css','day-at-virunga.css'];
  $pageHeroKey = null; // home has its own hero
  $pageScripts = ['home.js'];
  include 'includes/header.php';
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
  /* Base & Typography Variables */
  :root {
    --vh-forest: #122a1f;
    --vh-forest-deep: #0b1d15;
    --vh-green-mid: #1b3a2b;
    --vh-gold: #c9a24b;
    --vh-gold-light: #e8d7a5;
    --vh-gold-dark: #8e681c;
    --vh-cream: #fbfaf7;
    --vh-warm-bg: #f5f1ea;
    --vh-border: #e6e0d2;
    --vh-text-dark: #202924;
    --vh-text-muted: #5e6d64;
    --vh-font-display: 'Cormorant Garamond', Georgia, serif;
    --vh-font-body: 'Jost', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  }

  /* Universal Section Formatting */
  .vh-sec {
    padding: 85px 0;
    position: relative;
  }
  .vh-sec--light {
    background: #ffffff;
  }
  .vh-sec--warm {
    background: var(--vh-warm-bg);
  }
  .vh-sec--cream {
    background: var(--vh-cream);
  }
  .vh-sec--dark {
    background: var(--vh-forest);
    color: #ffffff;
  }
  .vh-wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
  }
  .vh-header {
    margin-bottom: 48px;
  }
  .vh-header.center {
    text-align: center;
  }
  .vh-eyebrow {
    display: inline-block;
    font-family: var(--vh-font-body);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--vh-gold-dark);
    margin-bottom: 12px;
  }
  .vh-sec--dark .vh-eyebrow {
    color: var(--vh-gold-light);
  }
  .vh-title {
    font-family: var(--vh-font-display);
    font-size: clamp(2rem, 3.4vw, 2.75rem);
    font-weight: 600;
    line-height: 1.18;
    color: var(--vh-forest);
    margin: 0 0 16px 0;
  }
  .vh-sec--dark .vh-title {
    color: #ffffff;
  }
  .vh-lead {
    font-family: var(--vh-font-body);
    font-size: 1.05rem;
    line-height: 1.65;
    color: var(--vh-text-muted);
    max-width: 780px;
    margin: 0 auto;
  }
  .vh-sec--dark .vh-lead {
    color: rgba(246, 242, 233, 0.88);
  }

  /* 01: THE HOUSE PILLARS BELT */
  .vh-pillars-belt {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 12px 18px;
    margin-top: 26px;
    padding: 14px 20px;
    background: var(--vh-cream);
    border: 1px solid var(--vh-border);
    border-radius: 4px;
  }
  .vh-pillar-item {
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--vh-forest);
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .vh-pillar-item::after {
    content: '·';
    color: var(--vh-gold);
    font-weight: 700;
    margin-left: 14px;
  }
  .vh-pillar-item:last-child::after {
    content: '';
    margin-left: 0;
  }

  /* 02: ROOM CARDS ENHANCED */
  .vh-room-badge-inc {
    position: absolute;
    top: 14px;
    right: 14px;
    background: var(--vh-green-mid);
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    padding: 4px 10px;
    border-radius: 4px;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  }
  .vh-room-amenities-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px 14px;
    margin: 12px 0 16px;
    font-size: 0.82rem;
    color: var(--vh-text-muted);
  }
  .vh-room-amenities-row span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
  }
  .vh-room-amenities-row i {
    color: var(--vh-gold);
  }

  /* 04 & 05: FOOD & HOUSE TABLE */
  .vh-food-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
    margin-bottom: 40px;
  }
  .vh-food-card {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    padding: 36px 32px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    position: relative;
  }
  .vh-food-tag {
    display: inline-block;
    background: #f4ede0;
    color: var(--vh-gold-dark);
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 4px;
    margin-bottom: 14px;
  }
  .vh-food-title {
    font-family: var(--vh-font-display);
    font-size: 1.65rem;
    color: var(--vh-forest);
    font-weight: 700;
    margin: 0 0 12px 0;
  }
  .vh-food-desc {
    font-size: 0.94rem;
    line-height: 1.65;
    color: var(--vh-text-dark);
    margin-bottom: 18px;
  }
  .vh-food-list {
    list-style: none;
    padding: 0;
    margin: 0 0 20px 0;
    font-size: 0.88rem;
    color: var(--vh-text-muted);
  }
  .vh-food-list li {
    padding: 5px 0;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .vh-food-list li i {
    color: var(--vh-gold);
    font-size: 0.75rem;
  }
  .vh-food-flow {
    background: var(--vh-cream);
    border-left: 3px solid var(--vh-gold);
    padding: 10px 14px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--vh-forest);
    letter-spacing: 0.04em;
    margin-top: 10px;
  }

  /* 05: DISTINCTION COMPARISON */
  .vh-distinction-box {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 8px;
    padding: 36px;
    margin-top: 30px;
  }
  .vh-distinction-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 32px;
    position: relative;
  }
  .vh-distinction-grid::after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 50%;
    width: 1px;
    background: var(--vh-border);
  }
  .vh-dist-col {
    padding: 0 12px;
  }
  .vh-dist-brand {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--vh-gold-dark);
    margin-bottom: 6px;
  }
  .vh-dist-heading {
    font-family: var(--vh-font-display);
    font-size: 1.5rem;
    color: var(--vh-forest);
    font-weight: 700;
    margin: 0 0 10px 0;
  }
  .vh-dist-text {
    font-size: 0.9rem;
    line-height: 1.6;
    color: var(--vh-text-muted);
  }

  /* 08: STAY + JOURNEY */
  .vh-synergy-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 28px;
  }
  .vh-synergy-card {
    background: var(--vh-cream);
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    padding: 34px 28px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .vh-synergy-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(18,42,31,0.08);
  }

  /* 16: BOOKING PROCESS ROADMAP */
  .vh-roadmap-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-top: 36px;
  }
  .vh-roadmap-step {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    padding: 22px 16px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }
  .vh-step-num {
    width: 32px;
    height: 32px;
    background: var(--vh-forest);
    color: var(--vh-gold-light);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    font-weight: 700;
    margin: 0 auto 12px auto;
  }
  .vh-step-title {
    font-family: var(--vh-font-display);
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--vh-forest);
    margin-bottom: 6px;
    line-height: 1.25;
  }
  .vh-step-desc {
    font-size: 0.8rem;
    line-height: 1.45;
    color: var(--vh-text-muted);
  }

  /* 17: FAQ ACCORDION */
  .vh-faq-wrap {
    max-width: 820px;
    margin: 0 auto;
  }
  .vh-faq-item {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    margin-bottom: 12px;
    overflow: hidden;
    transition: border-color 0.25s ease;
  }
  .vh-faq-item.open {
    border-color: var(--vh-gold);
  }
  .vh-faq-question {
    padding: 20px 24px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-family: var(--vh-font-display);
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--vh-forest);
    user-select: none;
  }
  .vh-faq-icon {
    font-size: 0.85rem;
    color: var(--vh-gold);
    transition: transform 0.3s ease;
  }
  .vh-faq-item.open .vh-faq-icon {
    transform: rotate(180deg);
  }
  .vh-faq-answer {
    padding: 0 24px 20px 24px;
    font-size: 0.92rem;
    line-height: 1.65;
    color: var(--vh-text-dark);
    display: none;
  }
  .vh-faq-item.open .vh-faq-answer {
    display: block;
  }

  /* Responsive Adjustments */
  @media (max-width: 960px) {
    .vh-food-grid,
    .vh-distinction-grid,
    .vh-synergy-grid {
      grid-template-columns: 1fr;
    }
    .vh-distinction-grid::after {
      display: none;
    }
    .vh-roadmap-grid {
      grid-template-columns: repeat(3, 1fr);
    }
  }
  @media (max-width: 600px) {
    .vh-sec {
      padding: 60px 0;
    }
    .vh-roadmap-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    .vh-food-card {
      padding: 24px 20px;
    }
  }
</style>

<!-- ====================================================
     HERO SECTION
==================================================== -->
<section class="hero" id="hero">
  <?php
  require_once __DIR__ . '/../config/db.php';
  $sqlHero = "SELECT * FROM hero_images WHERE status = 'active' AND is_active = 1 ORDER BY display_order ASC LIMIT 1";
  $heroResult = $conn->query($sqlHero);

  $heroTitle = 'A locally rooted stay beneath the Virunga volcanoes.';
  $heroDesc = 'An intimate place to stay in Musanze, offering a comfortable base for discovering the landscape, people and experiences of the Virunga.';
  $imgAttr = './img/hero/1.jpg';

  if ($heroResult && $hero = $heroResult->fetch_assoc()) {
      if (!empty($hero['title'])) $heroTitle = $hero['title'];
      if (!empty($hero['paragraph'])) $heroDesc = htmlspecialchars($hero['paragraph']);
      
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
  }
  ?>
  <div class="hero-static-bg" style="background-image: url('<?php echo htmlspecialchars($imgAttr); ?>');"></div>
  <div class="hero-content">
    <p class="hero-tag">VIRUNGA HOUSE</p>
    <h1 class="hero-title"><?php echo $heroTitle; ?></h1>
    <p class="hero-desc"><?php echo $heroDesc; ?></p>
    <div class="hero-actions">
      <a href="<?php echo $baseLink('rooms'); ?>" class="btn-primary">
        CHECK AVAILABILITY <i class="fas fa-arrow-right" style="margin-left: 6px;"></i>
      </a>
      <a href="https://wa.me/250784513435?text=Hello%20Virunga%20House,%20I%20would%20like%20to%20enquire%20about%20the%20rooms%20and%20availability%20to%20stay." target="_blank" rel="noopener" class="btn-outline">
        <i class="fab fa-whatsapp" style="margin-right: 6px;"></i> ENQUIRE TO STAY
      </a>
    </div>
  </div>
</section>

<!-- ====================================================
     01 — THE HOUSE
==================================================== -->
<section id="home-about" class="vh-sec vh-sec--light reveal" data-reveal>
  <div class="vh-wrap">
    <div class="about-grid">
      <div class="about-copy" data-reveal>
        <span class="vh-eyebrow">1. THE HOUSE</span>
        <h2 class="vh-title" style="text-align: left;">Stay closer to the Virunga.</h2>
        <p class="about-body">
          Virunga House is a locally rooted stay in Musanze for travellers looking for a comfortable, personal place to stay while exploring northern Rwanda.
        </p>
        <p class="about-body" style="margin-top: 12px;">
          A quieter alternative to a conventional hotel, the House offers a simple connection to the surrounding community, landscape and everyday life of the Virunga.
        </p>
        <div class="vh-pillars-belt">
          <span class="vh-pillar-item">Locally Rooted</span>
          <span class="vh-pillar-item">Intimate</span>
          <span class="vh-pillar-item">Personal</span>
          <span class="vh-pillar-item">Connected to Place</span>
        </div>
        <div style="margin-top: 24px;">
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
            DISCOVER THE HOUSE <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="about-visual" data-reveal data-delay="300">
        <div class="about-image-frame">
          <img src="./img/about.jpeg" alt="Virunga House Sanctuary in Musanze" class="about-image" loading="lazy">
          <div class="about-card" id="about" data-reveal data-delay="500">
            <div class="about-card__metric">
              <span class="metric-num" data-count data-target="6">6</span>
              <span class="metric-label">Boutique Rooms</span>
            </div>
            <div class="about-card__metric">
              <span class="metric-num" data-count data-target="15" data-suffix=" min">15 min</span>
              <span class="metric-label">To Volcanoes HQ</span>
            </div>
            <div class="about-card__metric">
              <span class="metric-num" data-count data-target="100" data-suffix="%">100%</span>
              <span class="metric-label">Breakfast Included</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     02 — THE ROOMS
==================================================== -->
<section class="vh-sec vh-sec--warm" id="rooms">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">2. THE ROOMS</span>
      <h2 class="vh-title">YOUR ROOM AT VIRUNGA HOUSE</h2>
      <p class="vh-lead">
        Our six rooms provide a comfortable, welcoming base for discovering Musanze and the Virunga.<br>
        Each room is designed for a simple, relaxed stay, with breakfast included and the essentials you need after a day of exploring.
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
          $rMeters = isset($r['meters']) && $r['meters'] > 0 ? (int)$r['meters'] : 26;
          $rGuests = isset($r['guest_number']) && $r['guest_number'] > 0 ? (int)$r['guest_number'] : 2;
          $rBed = isset($r['bed_type']) && !empty($r['bed_type']) ? htmlspecialchars($r['bed_type']) : 'King Bed';
          $rSingle = isset($r['price_single']) ? (int)$r['price_single'] : 65;
          $rDouble = isset($r['price_double']) ? (int)$r['price_double'] : 85;
          $rWhatsappMsg = rawurlencode("Hello! I would like to check availability and book " . $rTitle . " at Virunga House");
          $rUrl = "https://wa.me/250784513435?text=" . $rWhatsappMsg;
      ?>
      <div class="hs-room-card">
        <div class="hs-room-media">
          <img src="<?php echo htmlspecialchars($rImg); ?>" alt="<?php echo $rTitle; ?>" loading="lazy">
          <span class="vh-room-badge-inc"><i class="fas fa-coffee"></i> Breakfast Included</span>
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
          <div class="vh-room-amenities-row">
            <span><i class="fa-solid fa-bath"></i> Private En-suite</span>
            <span><i class="fa-solid fa-wifi"></i> Fiber Wi-Fi</span>
            <span><i class="fa-solid fa-utensils"></i> Breakfast Included</span>
          </div>
          <div class="hs-room-hover-details">
            <div class="hs-room-facts">
              <span><i class="fa-solid fa-maximize"></i> <?php echo $rMeters; ?> m²</span>
              <span><i class="fa-solid fa-user-group"></i> Sleeps 1–<?php echo $rGuests; ?></span>
              <span><i class="fa-solid fa-bed"></i> <?php echo $rBed; ?></span>
            </div>
            <a href="<?php echo $rUrl; ?>" target="_blank" rel="noopener" class="hs-link-btn hs-link-btn--transparent">
              Check Availability <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php 
        endwhile;
      else:
      ?>
      <!-- Fallback Default Rooms -->
      <div class="hs-room-card">
        <div class="hs-room-media">
          <img src="./img/hero/room.jpg" alt="Deluxe Mountain Room" loading="lazy">
          <span class="vh-room-badge-inc"><i class="fas fa-coffee"></i> Breakfast Included</span>
          <div class="hs-room-overlay"></div>
        </div>
        <span class="hs-room-badge">Room 01</span>
        <div class="hs-room-body">
          <div class="hs-room-main-info">
            <h3 class="hs-room-name">Deluxe Mountain Room</h3>
            <div class="hs-room-pricing">
              <div class="hs-price-item"><span class="hs-price-val">$65</span><span class="hs-price-lbl">Single / night</span></div>
              <div class="hs-price-item"><span class="hs-price-val">$85</span><span class="hs-price-lbl">Double / night</span></div>
            </div>
          </div>
          <div class="vh-room-amenities-row">
            <span><i class="fa-solid fa-bath"></i> Private En-suite</span>
            <span><i class="fa-solid fa-wifi"></i> Fiber Wi-Fi</span>
            <span><i class="fa-solid fa-utensils"></i> Breakfast Included</span>
          </div>
          <div class="hs-room-hover-details">
            <div class="hs-room-facts">
              <span><i class="fa-solid fa-maximize"></i> 28 m²</span>
              <span><i class="fa-solid fa-user-group"></i> Sleeps 1–2</span>
              <span><i class="fa-solid fa-bed"></i> King Bed</span>
            </div>
            <a href="<?php echo $baseLink('rooms'); ?>" class="hs-link-btn hs-link-btn--transparent">
              View Room & Rates <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <div class="homestay-cta-center" data-reveal style="margin-top: 36px;">
      <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
        VIEW ALL ROOM DETAILS & RATES <i class="fas fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ====================================================
     04 — FOOD AT VIRUNGA HOUSE (THE HOUSE TABLE)
==================================================== -->
<section class="vh-sec vh-sec--light" id="dining">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">4. FOOD AT VIRUNGA HOUSE</span>
      <h2 class="vh-title">THE HOUSE TABLE</h2>
      <p class="vh-lead">
        Food is part of the hospitality at Virunga House.<br>
        The approach is simple: good food, local character and the comfort of being able to eat where you stay.<br>
        <strong style="color: var(--vh-forest); font-weight: 600;">Virunga House is not positioned as a restaurant. Food is prepared primarily for staying guests.</strong>
      </p>
    </div>

    <div class="vh-food-grid" data-reveal>
      <!-- BREAKFAST -->
      <div class="vh-food-card">
        <div>
          <span class="vh-food-tag"><i class="fas fa-sun"></i> Included with your stay</span>
          <h3 class="vh-food-title">Breakfast at the House</h3>
          <p class="vh-food-desc">
            A simple, nourishing start to your day. Prepared fresh for guests each morning, with local and seasonal ingredients sourced from nearby mountain farms.
          </p>
          <ul class="vh-food-list">
            <li><i class="fas fa-check"></i> Fresh farm eggs prepared to order</li>
            <li><i class="fas fa-check"></i> Fresh bread and local baked items</li>
            <li><i class="fas fa-check"></i> Tropical fruits (mountain bananas, papaya, pineapple)</li>
            <li><i class="fas fa-check"></i> Rich Rwandan highland tea & freshly brewed coffee</li>
          </ul>
        </div>
        <div class="vh-food-flow">
          Served daily in the dining area or garden terrace before your trek.
        </div>
      </div>

      <!-- DINNER AT THE HOUSE -->
      <div class="vh-food-card">
        <div>
          <span class="vh-food-tag"><i class="fas fa-moon"></i> By arrangement for staying guests</span>
          <h3 class="vh-food-title">Dinner at the House</h3>
          <p class="vh-food-desc">
            After a full day of trekking or exploring, guests can choose to stay in and enjoy an intimate evening meal at the House. Intentionally simple, fresh and personal rather than a restaurant-style service.
          </p>
          <ul class="vh-food-list">
            <li><i class="fas fa-check"></i> Locally inspired, home-cooked evening dishes</li>
            <li><i class="fas fa-check"></i> Menu adapted to guest dietary preferences & allergies</li>
            <li><i class="fas fa-check"></i> Limited daily capacity ensuring maximum freshness</li>
            <li><i class="fas fa-check"></i> Quiet, unhurried atmosphere around the table or hearth</li>
          </ul>
        </div>
        <div class="vh-food-flow">
          How it works: Request → Confirm → Prepare → Dine
        </div>
      </div>
    </div>

    <!-- 05 — FOOD DISTINCTION -->
    <div class="vh-distinction-box" data-reveal>
      <div class="vh-header center" style="margin-bottom: 24px;">
        <span class="vh-eyebrow">5. FOOD & VIRUNGA JOURNEYS</span>
        <h3 class="vh-title" style="font-size: 1.85rem;">Two Different Experiences</h3>
        <p class="vh-lead" style="font-size: 0.95rem;">
          We maintain a clear, purposeful distinction between our in-house guest dining and our hosted cultural food journeys.
        </p>
      </div>

      <div class="vh-distinction-grid">
        <div class="vh-dist-col">
          <div class="vh-dist-brand">VIRUNGA HOUSE</div>
          <h4 class="vh-dist-heading">The House Table</h4>
          <p class="vh-dist-text">
            <strong>Hospitality for staying guests:</strong> Daily breakfast included with your stay, and intimate home-style dinners by advance arrangement. Comfort, relaxation, and nourishment under our roof.
          </p>
        </div>
        <div class="vh-dist-col">
          <div class="vh-dist-brand">VIRUNGA JOURNEYS</div>
          <h4 class="vh-dist-heading">The Musanze Table</h4>
          <p class="vh-dist-text">
            <strong>A separate hosted food experience:</strong> Designed as part of a Virunga journey. An encounter built around food, farmers, coffee masters, cultural heritage, and shared stories across the region.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     06 & 07 — THE SETTING & THE HOUSE EXPERIENCE
==================================================== -->
<section class="vh-sec vh-sec--cream" id="evenings">
  <div class="vh-wrap">
    <div class="hs-split-grid hs-split-grid--reverse" data-reveal>
      <div class="hs-split-content">
        <span class="vh-eyebrow">6 & 7. THE SETTING & EXPERIENCE</span>
        <h2 class="vh-title" style="text-align: left;">Beneath the Volcanoes.<br>More Than a Room.</h2>
        <p class="hs-lead" style="margin-bottom: 14px;">
          Virunga House offers a peaceful, scenic base to slow down and experience northern Rwanda.
        </p>
        <div class="hs-evenings-lines">
          <p><i class="fas fa-mountain" style="color: var(--vh-gold); margin-right: 8px;"></i> Panoramic volcano views & lush gardens</p>
          <p><i class="fas fa-fire" style="color: var(--vh-gold); margin-right: 8px;"></i> Outdoor campfire & quiet reading corners</p>
          <p><i class="fas fa-coffee" style="color: var(--vh-gold); margin-right: 8px;"></i> Fresh mountain air & evening conversations</p>
        </div>
        <p class="hs-text" style="margin-top: 18px;">
          Whether resting between gorilla treks or sitting by the evening fire, the House gives you space to simply be in the moment.
        </p>
        <div style="margin-top: 24px;">
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-primary">
            PLAN YOUR STAY <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
      <div class="hs-split-media">
        <img src="./img/day/7.jpeg" alt="Campfire and Volcano Atmosphere at Virunga House" loading="lazy">
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     08 — STAY + JOURNEY
==================================================== -->
<section class="vh-sec vh-sec--light" id="stay-journey">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">8. STAY + JOURNEY</span>
      <h2 class="vh-title">Stay at Virunga House. Explore with Virunga Journeys.</h2>
      <p class="vh-lead">
        Combine your boutique stay in Musanze with expertly coordinated private journeys across Rwanda, Uganda, and the DRC.
      </p>
    </div>

    <div class="vh-synergy-grid" data-reveal>
      <!-- Virunga House -->
      <div class="vh-synergy-card">
        <div>
          <span class="vh-food-tag">YOUR STAY</span>
          <h3 class="vh-food-title">VIRUNGA HOUSE</h3>
          <p class="vh-food-desc">
            Comfortable boutique accommodation, daily breakfast, genuine House hospitality, relaxing garden spaces, and dinners by arrangement.
          </p>
        </div>
        <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-outline" style="align-self: flex-start;">
          Explore Rooms & Rates <i class="fas fa-arrow-right"></i>
        </a>
      </div>

      <!-- Virunga Journeys -->
      <div class="vh-synergy-card" style="background: #122a1f; color: #ffffff; border-color: #1b3a2b;">
        <div>
          <span class="vh-food-tag" style="background: rgba(201,162,75,0.2); color: #e8d7a5;">YOUR EXPERIENCES</span>
          <h3 class="vh-food-title" style="color: #ffffff;">VIRUNGA JOURNEYS</h3>
          <p class="vh-food-desc" style="color: rgba(246, 242, 233, 0.88);">
            Private trekking, cultural discoveries, gorilla permits, private safari vehicles, bilingual guides, and seamless regional logistics.
          </p>
        </div>
        <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-gold" style="align-self: flex-start;">
          Plan Your Experiences <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     09 & 10 — LOCATION & WHO VIRUNGA HOUSE IS FOR
==================================================== -->
<section class="vh-sec vh-sec--warm" id="who-its-for">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">9 & 10. LOCATION & SUITABILITY</span>
      <h2 class="vh-title">In Musanze, Beneath the Volcanoes</h2>
      <p class="vh-lead">
        Located in quiet Musanze town, just 15 minutes from Volcanoes National Park Headquarters in Kinigi and 2 hours from Kigali International Airport.
      </p>
    </div>

    <div class="why-pillars-grid" data-reveal>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-mountain"></i></span>
        <h3 class="why-pillar-title">Trekking Basecamp</h3>
        <p class="why-pillar-desc">A peaceful, comfortable base for gorilla, golden monkey, and volcano expeditions.</p>
      </div>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-heart"></i></span>
        <h3 class="why-pillar-title">Locally Rooted</h3>
        <p class="why-pillar-desc">A personal, authentic alternative to conventional corporate hotels.</p>
      </div>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-utensils"></i></span>
        <h3 class="why-pillar-title">Breakfast Included</h3>
        <p class="why-pillar-desc">Fresh morning meals included, with intimate evening dinners available by arrangement.</p>
      </div>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-compass"></i></span>
        <h3 class="why-pillar-title">Seamless Journeys</h3>
        <p class="why-pillar-desc">Effortless direct access to Virunga Journeys guiding, transport, and cultural encounters.</p>
      </div>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-moon"></i></span>
        <h3 class="why-pillar-title">Quieter Pace</h3>
        <p class="why-pillar-desc">A calm haven to return to after a full day in the rainforest and mountains.</p>
      </div>
      <div class="why-pillar-card">
        <span class="why-pillar-num"><i class="fas fa-shield-halved"></i></span>
        <h3 class="why-pillar-title">Secure & Gated</h3>
        <p class="why-pillar-desc">Complimentary on-site gated parking, high-speed fiber Wi-Fi, and solar hot showers.</p>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     13 — PRACTICAL INFORMATION ("GOOD TO KNOW")
==================================================== -->
<section class="vh-sec vh-sec--light" id="good-to-know">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">13. PRACTICAL INFORMATION</span>
      <h2 class="vh-title">GOOD TO KNOW BEFORE YOU STAY</h2>
      <p class="vh-lead">
        Transparent, factual details to help you prepare for your stay in Musanze.
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
        <h4>Private En-Suite</h4>
        <p>All rooms feature private en-suite bathrooms, fresh towels, and artisan bedding.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">04</div>
        <h4>Breakfast Included</h4>
        <p>Fresh daily breakfast is included with every room reservation.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">05</div>
        <h4>Dinner at the House</h4>
        <p>Available by arrangement for staying guests (advance notice requested).</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">06</div>
        <h4>Check-in / Check-out</h4>
        <p>Check-in from 14:00. Check-out by 11:00. Early luggage storage available.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">07</div>
        <h4>Connectivity</h4>
        <p>Reliable high-speed fiber Wi-Fi throughout all rooms and shared garden spaces.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">08</div>
        <h4>Water & Power</h4>
        <p>Solar-assisted hot water showers, continuous power, and reading desks.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">09</div>
        <h4>Secure Parking</h4>
        <p>Complimentary gated on-site parking for safari 4x4s and private vehicles.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">10</div>
        <h4>Direct Booking</h4>
        <p>Quick booking confirmation via WhatsApp or online reservation form.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">11</div>
        <h4>Payment Methods</h4>
        <p>Major cards (Visa/Mastercard), Bank Transfer, Mobile Money (MoMo), and Cash.</p>
      </div>
      <div class="hs-practical-item">
        <div class="hs-practical-num">12</div>
        <h4>Cancellation Terms</h4>
        <p>Flexible cancellation terms and date-transfer options with advance notice.</p>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     16 — BOOKING PROCESS ROADMAP
==================================================== -->
<section class="vh-sec vh-sec--warm" id="booking-process">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">16. SIMPLE BOOKING PROCESS</span>
      <h2 class="vh-title">How It Works</h2>
      <p class="vh-lead">
        Six straightforward steps from checking availability to arriving at your mountain basecamp.
      </p>
    </div>

    <div class="vh-roadmap-grid" data-reveal>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">1</div>
        <div class="vh-step-title">Check Dates</div>
        <div class="vh-step-desc">Choose your travel dates, number of guests, and room preference.</div>
      </div>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">2</div>
        <div class="vh-step-title">Select Room</div>
        <div class="vh-step-desc">Review room details, rates, and confirmed breakfast inclusion.</div>
      </div>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">3</div>
        <div class="vh-step-title">Dinner Request</div>
        <div class="vh-step-desc">Optionally add dinner for any evening you would like to eat at the House.</div>
      </div>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">4</div>
        <div class="vh-step-title">Confirmation</div>
        <div class="vh-step-desc">Virunga House confirms availability, rates, and dietary arrangements.</div>
      </div>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">5</div>
        <div class="vh-step-title">Finalize</div>
        <div class="vh-step-desc">Complete the simple confirmation step or secure deposit.</div>
      </div>
      <div class="vh-roadmap-step">
        <div class="vh-step-num">6</div>
        <div class="vh-step-title">Arrive & Rest</div>
        <div class="vh-step-desc">Your room, mountain views, and hospitality services are ready.</div>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     17 — FREQUENTLY ASKED QUESTIONS (FAQ)
==================================================== -->
<section class="vh-sec vh-sec--light" id="hospitality-faq">
  <div class="vh-wrap">
    <div class="vh-header center" data-reveal>
      <span class="vh-eyebrow">17. QUESTIONS & ANSWERS</span>
      <h2 class="vh-title">Frequently Asked Questions</h2>
      <p class="vh-lead">
        Clear answers regarding stays, dining arrangements, and journey planning.
      </p>
    </div>

    <div class="vh-faq-wrap" data-reveal>
      <!-- Q1 -->
      <div class="vh-faq-item open">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Is breakfast included with my stay?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Breakfast is included with every room reservation at Virunga House and is prepared fresh daily with seasonal local ingredients before your morning activities.
        </div>
      </div>

      <!-- Q2 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Can I have dinner at Virunga House?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Dinner at the House is available by advance arrangement for staying guests. It provides a relaxed, home-cooked evening meal after a day of trekking.
        </div>
      </div>

      <!-- Q3 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Is Virunga House a restaurant?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          No. Virunga House is an intimate residential stay. Food is a hospitality service prepared primarily for staying guests rather than a walk-in public restaurant.
        </div>
      </div>

      <!-- Q4 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Do I need to request dinner in advance?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Advance notice allows our kitchen to source fresh ingredients and prepare appropriately according to the number of guests staying each evening.
        </div>
      </div>

      <!-- Q5 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Can dietary requirements and allergies be accommodated?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Please share any vegetarian, vegan, gluten-free, or specific allergy requirements when booking so we can tailor the menu for you.
        </div>
      </div>

      <!-- Q6 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Can I book Virunga Journeys experiences separately?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Virunga Journeys operates private guided expeditions, gorilla permits, and cultural encounters that can be combined seamlessly with your stay or booked independently.
        </div>
      </div>

      <!-- Q7 -->
      <div class="vh-faq-item">
        <div class="vh-faq-question" onclick="toggleVhFaq(this)">
          <span>Can I combine my stay with gorilla trekking in Volcanoes National Park?</span>
          <i class="fas fa-chevron-down vh-faq-icon"></i>
        </div>
        <div class="vh-faq-answer">
          Yes. Virunga House is located just 15 minutes from the Kinigi park headquarters, making it an ideal basecamp for early morning briefing and departure.
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ====================================================
     18 — FINAL CTA (INVITATION)
==================================================== -->
<section class="vh-sec vh-sec--dark" id="invitation" style="background-image: linear-gradient(rgba(12, 28, 20, 0.88), rgba(12, 28, 20, 0.92)), url('./img/day/7.jpeg'); background-size: cover; background-position: center; text-align: center;">
  <div class="vh-wrap">
    <div class="hs-invitation-box" data-reveal>
      <span class="vh-eyebrow" style="color: var(--vh-gold-light);">18. STAY IN THE VIRUNGA</span>
      <h2 class="vh-title" style="color: #ffffff; margin-bottom: 18px;">A locally rooted base for discovering northern Rwanda.</h2>
      <p class="vh-lead" style="color: rgba(246, 242, 233, 0.9); max-width: 740px; margin: 0 auto 36px;">
        <em>Stay. Eat. Rest. Discover.</em><br>
        Virunga House gives you everything you need for a comfortable stay, while Virunga Journeys opens the door to deeper experiences beyond the House.
      </p>
      <div class="hs-dual-actions" style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
        <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-gold">
          CHECK AVAILABILITY <i class="fas fa-arrow-right"></i>
        </a>
        <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-outline-light">
          PLAN YOUR VIRUNGA <i class="fas fa-compass"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<script>
  function toggleVhFaq(btn) {
    const item = btn.closest('.vh-faq-item');
    if (item) {
      item.classList.toggle('open');
    }
  }
</script>

<?php include 'includes/footer.php'; ?>