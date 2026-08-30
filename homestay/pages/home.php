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
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-primary">ENQUIRE TO STAY</a>
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
          <div class="about-badges">
            <?php if ($badge1): ?>
              <a href="<?php echo $baseLink('about-us'); ?>" class="modern-badge">
                <span><?php echo htmlspecialchars($badge1); ?></span>
                <span class="badge-arrow">→</span>
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


    <section id="experience" class="experience-section">
      <div class="section-container">
        <div class="experience-header" style="margin-bottom: 60px;" data-reveal>
          <h2 class="section-heading" style="color: var(--color-dark) !important; opacity: 1 !important; visibility: visible !important; display: block !important; margin-top: 10px;">Beyond the Ordinary</h2>
        </div>
        <?php
        require_once __DIR__ . '/../config/db.php';
        $expItems = [];
        $resExp = $conn->query("SELECT * FROM home_experience WHERE status = 'active' ORDER BY display_order ASC, id ASC");
        if ($resExp && $resExp->num_rows > 0) {
            while ($rowExp = $resExp->fetch_assoc()) {
                $expItems[] = $rowExp;
            }
        }
        ?>
        <div class="experience-rows">
          <?php if (!empty($expItems)): ?>
            <?php foreach ($expItems as $idx => $exp): ?>
              <?php
              $expEyebrow = !empty($exp['eyebrow']) ? htmlspecialchars($exp['eyebrow']) : 'Heritage';
              $expTitle = !empty($exp['title']) ? htmlspecialchars($exp['title']) : 'Experience Title';
              
              $raw_exp_desc = !empty($exp['description']) ? $exp['description'] : 'Description goes here...';
              if (mb_strlen($raw_exp_desc) > 200) {
                  $expDescription = htmlspecialchars(mb_substr($raw_exp_desc, 0, 200)) . '...';
              } else {
                  $expDescription = htmlspecialchars($raw_exp_desc);
              }

              $expFeatures = !empty($exp['features']) ? explode('|', $exp['features']) : [];
              $expImgVal = !empty($exp['image']) ? $exp['image'] : 'hero/2.jpg';
              
              if (strpos($expImgVal, 'http') === 0) {
                  $expImage = $expImgVal;
              } else {
                  $expImgVal = preg_replace('/^(\.\/)?img\//', '', ltrim($expImgVal, '/'));
                  if (strpos($expImgVal, '/') === false) {
                      $expImgVal = 'activities/' . $expImgVal;
                  }
                  $expImage = './img/' . $expImgVal;
              }
              $expImage = htmlspecialchars($expImage);
              $isEven = ($idx % 2 === 0);
              ?>
              <div class="exp-row <?php echo $isEven ? 'exp-row--normal' : 'exp-row--reverse'; ?>">
                <div class="exp-row__media">
                  <img src="<?php echo $expImage; ?>" alt="<?php echo $expTitle; ?>" loading="lazy" />
                </div>
                <div class="exp-row__content">
                  <p class="exp-row__eyebrow"><?php echo $expEyebrow; ?></p>
                  <h2 class="exp-row__title"><?php echo $expTitle; ?></h2>
                  <p class="exp-row__body"><?php echo $expDescription; ?></p>
                  
                  <?php if (!empty($expFeatures)): ?>
                    <ul class="exp-row__list">
                      <?php foreach ($expFeatures as $feature): ?>
                        <li><?php echo trim(htmlspecialchars($feature)); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                  
                  <div class="exp-row__actions">
                    <a href="<?php echo $baseLink('activity'); ?>?category=<?php echo $exp['id']; ?>" class="btn-view-more">
                      VIEW MORE <i class="fa-solid fa-arrow-right fa-xs"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <!-- ══ THREE WAYS TO EXPERIENCE ══ -->
    <section class="ways-section">
      <div class="section-container">
        <div class="ways-header">
          <p class="ways-eyebrow">WHY VIRUNGA</p>
          <h2 class="ways-title">Three ways to experience the region.</h2>
        </div>
        <div class="ways-grid">
          <div class="way-item">
            <div class="way-line"></div>
            <p class="way-number">01</p>
            <h3 class="way-name">Cultural Living</h3>
            <p class="way-text">Wake up in a real Rwandan homestead where daily life reflects tradition, hospitality, and simplicity.</p>
          </div>
          <div class="way-item">
            <div class="way-line"></div>
            <p class="way-number">02</p>
            <h3 class="way-name">Park Gateway</h3>
            <p class="way-text">Perfectly located for gorilla trekking, golden monkey encounters, and forest exploration.</p>
          </div>
          <div class="way-item">
            <div class="way-line"></div>
            <p class="way-number">03</p>
            <h3 class="way-name">Local Connection</h3>
            <p class="way-text">Share meals, stories, and moments with hosts who bring the Virunga region to life.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ══ A DAY AT VIRUNGA ══ -->
    <section class="day-section" id="daySection">
      <div class="day-sticky-wrapper">
        <div class="day-brand">VIRUNGA HOUSE</div>
        <nav class="day-progress" aria-label="Section navigation">
          <div class="day-dot active" data-index="0"></div>
          <div class="day-dot" data-index="1"></div>
          <div class="day-dot" data-index="2"></div>
          <div class="day-dot" data-index="3"></div>
          <div class="day-dot" data-index="4"></div>
          <div class="day-dot" data-index="5"></div>
          <div class="day-dot" data-index="6"></div>
        </nav>
        <div class="day-counter" aria-hidden="true">
          <strong class="day-cur">01</strong> / <span class="day-tot">07</span>
        </div>

        <!-- Bottom bar: skip button only -->
        <div class="day-bottom-bar">
          <button class="day-skip-btn" onclick="scrollToTestimonials()" aria-label="View Testimonials">
            <svg
              width="11"
              height="11"
              viewBox="0 0 14 14"
              fill="none"
              stroke-width="1.8"
              stroke-linecap="round"
              stroke-linejoin="round"
            >
              <line x1="5" y1="1" x2="13" y2="7" />
              <line x1="13" y1="7" x2="5" y2="13" />
              <line x1="1" y1="1" x2="1" y2="13" />
            </svg>
            View Testimonials
          </button>
        </div>

        <!-- SLIDES -->
        <!-- SLIDES -->
<div class="day-slides" role="region" aria-label="A day in life at VIRUNGA HOUSE">

  <!-- 0: WAKE UP -->
  <div class="day-slide s0 active" data-index="0">
    <div class="day-bg-layer" style="background-image: url('./img/day/1.jpeg');">
      <div class="atmo" style="width:58%;height:68%;top:-8%;left:12%;background:radial-gradient(ellipse,rgba(90,150,220,0.18) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:30%;height:35%;bottom:12%;right:8%;background:radial-gradient(ellipse,rgba(200,180,110,0.09) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">First light · The quiet hour</span>
      </div>
      <h2 class="day-headline">Before the world<br /><em>wakes up</em></h2>
      <p class="day-body-text">The first thing you notice is the silence then the mist. The Virunga volcanoes emerge slowly through your window as birdsong replaces the alarm. There is no rush. A warm towel, fresh water, and the unhurried beginning of a day that is entirely yours.</p>
    </div>
  </div>

  <!-- 1: BREAKFAST -->
  <div class="day-slide s1" data-index="1">
    <div class="day-bg-layer" style="background-image: url('./img/day/2.jpeg');">
      <div class="atmo" style="width:62%;height:65%;top:-5%;right:-8%;background:radial-gradient(ellipse,rgba(210,130,55,0.2) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:38%;height:45%;bottom:8%;left:2%;background:radial-gradient(ellipse,rgba(160,85,28,0.13) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">Breakfast · Around the table</span>
      </div>
      <h2 class="day-headline">A table set<br /><em>with intention</em></h2>
      <p class="day-body-text">Breakfast here is never an afterthought. Ripe passion fruit, freshly baked bread, honey from a neighbour's hive, and coffee grown on the slopes you can see from your seat. Your host sits with you. Stories are shared before the day has properly begun.</p>
    </div>
  </div>

  <!-- 2: ADVENTURE -->
  <div class="day-slide s2" data-index="2">
    <div class="day-bg-layer" style="background-image: url('./img/day/3.jpeg');">
      <div class="atmo" style="width:70%;height:80%;top:-18%;left:-8%;background:radial-gradient(ellipse,rgba(55,165,90,0.16) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:34%;height:38%;bottom:2%;right:12%;background:radial-gradient(ellipse,rgba(35,120,65,0.12) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">Into the wild · The forest calls</span>
      </div>
      <h2 class="day-headline">Where the<br /><em>volcanoes begin</em></h2>
      <p class="day-body-text">Your guide meets you at the gate. Today the forest calls gorilla trekking through ancient bamboo, or a climb toward the crater of Bisoke where the world falls away beneath you. This is not a tour. It is an encounter with something that cannot be scheduled.</p>
    </div>
  </div>

  <!-- 3: RETURN & LUNCH -->
  <div class="day-slide s3" data-index="3">
    <div class="day-bg-layer" style="background-image: url('./img/day/4.jpeg');">
      <div class="atmo" style="width:55%;height:58%;top:8%;left:18%;background:radial-gradient(ellipse,rgba(165,78,210,0.16) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:28%;height:32%;bottom:18%;right:2%;background:radial-gradient(ellipse,rgba(100,38,148,0.1) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">The return · Dust on your boots</span>
      </div>
      <h2 class="day-headline">Rest, eat,<br /><em>breathe again</em></h2>
      <p class="day-body-text">You return with dust on your boots and something quieter in your chest. A light lunch is already prepared garden vegetables, grilled plantain, cold juice pressed that morning. The veranda faces the hills. You sit. There is nowhere else to be.</p>
    </div>
  </div>

  <!-- 4: COMMUNITY EVENING -->
  <div class="day-slide s4" data-index="4">
    <div class="day-bg-layer" style="background-image: url('./img/day/5.jpeg');">
      <div class="atmo" style="width:65%;height:68%;top:-12%;right:-5%;background:radial-gradient(ellipse,rgba(210,95,38,0.22) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:38%;height:48%;bottom:0;left:6%;background:radial-gradient(ellipse,rgba(165,65,18,0.14) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">As the light softens · The village</span>
      </div>
      <h2 class="day-headline">Where<br /><em>Rwanda lives</em></h2>
      <p class="day-body-text">As the afternoon cools, the village opens up. A cooperative of weavers at work. Children returning from school along red-earth paths. Your host walks with you not as a guide, but as a neighbour introducing you to theirs. This is the part of Rwanda that most visitors never reach.</p>
    </div>
  </div>

  <!-- 5: COOKING CLASS -->
  <div class="day-slide s5" data-index="5">
    <div class="day-bg-layer" style="background-image: url('./img/day/6.jpeg');">
      <div class="atmo" style="width:60%;height:65%;top:-5%;right:-5%;background:radial-gradient(ellipse,rgba(210,110,40,0.2) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:35%;height:40%;bottom:5%;left:5%;background:radial-gradient(ellipse,rgba(160,75,20,0.13) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">Into the kitchen · Hands in the flour</span>
      </div>
      <h2 class="day-headline">You don't just<br /><em>eat you cook</em></h2>
      <p class="day-body-text">Before dinner is served, you help make it. Beside your host, you learn to prepare isombe, shape the dough, season by smell rather than measure. The kitchen is small and warm and full of laughter. What you cook together, you will remember long after the taste is gone.</p>
    </div>
  </div>

  <!-- 6: FIRE & NIGHT -->
  <div class="day-slide s6" data-index="6">
    <div class="day-bg-layer" style="background-image: url('./img/day/7.jpeg');">
      <div class="atmo" style="width:52%;height:62%;top:-2%;left:24%;background:radial-gradient(ellipse,rgba(75,118,210,0.13) 0%,transparent 70%);"></div>
      <div class="atmo" style="width:18%;height:22%;bottom:22%;right:18%;background:radial-gradient(ellipse,rgba(200,195,175,0.07) 0%,transparent 70%);"></div>
    </div>
    <div class="day-content">
      <div class="day-eyebrow">
        <div class="day-eyebrow-line"></div>
        <span class="day-eyebrow-text">When the mountains go dark · The fire</span>
      </div>
      <h2 class="day-headline">The fire,<br /><em>the stars, the end</em></h2>
      <p class="day-body-text">Dinner is served around an open fire. Local ibirayi, slow-braised beans, a glass of something warm. Conversation drifts between languages and laughter. Then the volcanoes go dark, the stars take over, and you carry the quiet back to your room already knowing tomorrow will be just as good.</p>
    </div>
  </div>

</div>

        <div class="day-curtain" aria-hidden="true"></div>
      </div>
    </section>
    <!-- ══ TESTIMONIAL TEASER ══ -->
    <section id="testimonials">
      <div class="section-container">
        <div class="testi-card">
          <div class="testi-copy">
            <p class="section-label">What Our Guests Say</p>
            <h2 class="section-heading">Voices from the Virunga Experiences</h2>
          </div>
          <a
            class="btn-primary testi-cta"
            href="https://www.tripadvisor.com/Hotel_Review-g317075-d20326735-Reviews-Virunga_Homestay-Ruhengeri_Musanze_District_Northern_Province.html"
            target="_blank"
            rel="noopener"
          >
            <i class="fa-brands fa-tripadvisor"></i> Review Us on TripAdvisor
          </a>
        </div>
      </div>
    </section>
    <!-- REAL GUEST STORIES -->
    <section id="guest-reviews">
      <div class="section-container">
        <p class="guest-reviews__lead">
          Every stay at VIRUNGA HOUSE becomes a story worth sharing. Here is what our guests have experienced in their own words:
        </p>

        <div class="guest-reviews__grid">
          <!-- Cards remain the same -->
          <article class="guest-review-card" data-reveal title="John, Emmy, and the whole team made our stay unforgettable. Amazing volcano views, thoughtful service, and help with park trips VIRUNGA HOUSE comes highly recommended!">
            <h3>A Dream Stay in Musanze</h3>
            <p class="guest-review-card__source">Tripadvisor Review</p>
            <p>
              John, Emmy, and the whole team made our stay unforgettable. Amazing volcano views, thoughtful service, and help with park trips VIRUNGA HOUSE comes highly recommended!
            </p>
          </article>

          <article class="guest-review-card" data-reveal title="Our honeymoon stay at VIRUNGA HOUSE last week was a truly remarkable experience. The warm welcome, the peaceful setting, and the personal touches made us feel right at home from the very first day. As part of our celebration, we were gifted a once-in-a-lifetime gorilla visit....">
            <h3>Our Honeymoon Stay at VIRUNGA HOUSE</h3>
            <p class="guest-review-card__source">Tripadvisor Review</p>
            <p>
              Our honeymoon stay at VIRUNGA HOUSE last week was a truly remarkable experience. The warm welcome, the peaceful setting, and the personal touches made us feel right at home from the very first day. As part of our celebration, we were gifted a once-in-a-lifetime gorilla visit....
            </p>
          </article>

          <article class="guest-review-card" data-reveal title="Welcomed like family, we cooked traditional dishes, explored local farms, and made banana beer. Evenings were full of laughter, music, and delicious meals. A true Rwandan experience with lasting memories highly recommended in Musanze!">
            <h3>Unforgettable Moments at VIRUNGA HOUSE!</h3>
            <p class="guest-review-card__source">Tripadvisor Review</p>
            <p>
              Welcomed like family, we cooked traditional dishes, explored local farms, and made banana beer. Evenings were full of laughter, music, and delicious meals. A true Rwandan experience with lasting memories highly recommended in Musanze!
            </p>
          </article>

          <article class="guest-review-card" data-reveal title="An unforgettable 10-day, 11-night journey through Rwanda was made exceptional by Emmy’s professionalism and warm hospitality from VIRUNGA HOUSE. His deep knowledge and flexibility enriched every moment, turning the trip into an inspiring exploration of Rwanda’s culture and landscapes. With four nights in a clean, safe, and welcoming homestay that felt like home, the experience was deeply rewarding highly recommended for those seeking authentic connection and lasting memories.">
            <h3>Feel Rwanda, Not Just Visit</h3>
            <p class="guest-review-card__source">Tripadvisor Review</p>
            <p>
              An unforgettable 10-day, 11-night journey through Rwanda was made exceptional by Emmy’s professionalism and warm hospitality from VIRUNGA HOUSE. His deep knowledge and flexibility enriched every moment, turning the trip into an inspiring exploration of Rwanda’s culture and landscapes. With four nights in a clean, safe, and welcoming homestay that felt like home, the experience was deeply rewarding highly recommended for those seeking authentic connection and lasting memories.
            </p>
          </article>
        </div>
    </section>
    <!-- ══ QUICK BOOKING CTA ══ -->
    <section id="booking-cta" class="journey-section" data-reveal>
      <div class="journey-bg" style="background-image: url('./img/cta.jpeg');"></div>
      <div class="journey-overlay"></div>
      <div class="section-container journey-inner">
        <div class="journey-header">
          <p class="journey-eyebrow">BEGIN</p>
          <h2 class="journey-title">Begin Your <span>Virunga</span> Journey.</h2>
          <p class="journey-subtitle">Book your stay and experience Rwanda beyond the ordinary.</p>
        </div>
        <div class="journey-actions">
          <a href="<?php echo $baseLink('rooms'); ?>" class="btn-journey">PLAN MY STAY</a>
        </div>
      </div>
    </section>
    <?php include 'includes/footer.php'; ?>