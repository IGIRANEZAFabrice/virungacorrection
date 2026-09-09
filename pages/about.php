<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Primary Meta Tags -->
    <title>About Us | Virunga Collective &mdash; A Deeper Way of Experiencing the Virunga</title>
    <meta
      name="description"
      content="Virunga Collective is a destination-led house of immersive travel, hospitality and distinctive experiences rooted in the landscapes, cultures and communities of the Virunga region."
    />
    <meta name="keywords" content="about Virunga Collective, Virunga Journeys, Virunga House, Virunga Signatures, Virunga Impact, Virunga Academy, Virunga Coffee, Rwanda sustainable travel, Virunga region travel">
    <meta name="author" content="Virunga Collective">
    <meta name="robots" content="index, follow">

    <!-- Canonical -->
    <link rel="canonical" href="https://virungajourneys.com/about" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://virungajourneys.com/about">
    <meta property="og:title" content="About Virunga Collective | A Deeper Way of Experiencing the Virunga">
    <meta property="og:description" content="A destination-led house of immersive travel, hospitality and distinctive experiences rooted in the Virunga.">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Virunga Collective">
    <meta property="og:locale" content="en_US">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://virungajourneys.com/about">
    <meta name="twitter:title" content="About Virunga Collective | A Deeper Way of Experiencing the Virunga">
    <meta name="twitter:description" content="A destination-led house of immersive travel, hospitality and distinctive experiences rooted in the Virunga.">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta name="twitter:site" content="@virungacollective">
    <meta name="twitter:creator" content="@virungacollective">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "About Virunga Collective",
      "url": "https://virungajourneys.com/about",
      "description": "Virunga Collective is a destination-led house of immersive travel, hospitality and distinctive experiences rooted in the Virunga region.",
      "mainEntity": {
        "@type": "Organization",
        "name": "Virunga Collective",
        "url": "https://virungajourneys.com",
        "logo": "https://virungajourneys.com/img/icon.png",
        "sameAs": [
          "https://instagram.com/virungacollective",
          "https://facebook.com/virungacollective"
        ]
      }
    }
    </script>

    <!-- Favicon & Manifest -->
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($baseLink('img/icon.png')); ?>" />
    <link rel="manifest" href="<?php echo htmlspecialchars($baseLink('manifest.json')); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />

    <style>
      /* =========================================
         DESIGN SYSTEM — simplified, quieter scale
         ========================================= */
      :root {
        --forest: #1b3a2b;
        --forest-deep: #0e2118;
        --forest-mid: #16301f;
        --gold: #b6924c;
        --gold-quiet: #cdae74;
        --cream: #f7f4ec;
        --cream-warm: #fbf9f3;
        --ink: #202620;
        --ink-soft: rgba(32, 38, 32, 0.72);
        --sage: #7c8f7c;
        --line: rgba(27, 58, 43, 0.1);
        --line-dark: rgba(247, 244, 236, 0.1);
        --max-w: 1120px;
        --font-display: "Cormorant Garamond", serif;
        --font-body: "Jost", sans-serif;
        --ease: cubic-bezier(0.22, 1, 0.36, 1);
      }

      * { margin: 0; padding: 0; box-sizing: border-box; }
      html { scroll-behavior: smooth; }

      body {
        font-family: var(--font-body);
        color: var(--ink);
        background: var(--cream) !important;
        line-height: 1.7;
        font-size: 16px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }

      img { max-width: 100%; display: block; }
      a { color: inherit; text-decoration: none; }

      a:focus-visible,
      button:focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 3px;
      }

      .vcaWrap {
        max-width: var(--max-w);
        margin: 0 auto;
        padding: 0 24px;
      }

      /* =========================================
         SHARED SECTION STYLES
         ========================================= */
      .vca-section {
        padding: 88px 0;
      }

      .vca-section--light { background: var(--cream-warm) !important; }
      .vca-section--dark  { background: var(--forest-deep) !important; color: var(--cream) !important; }

      .vcaSectionLabel {
        display: block;
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        color: var(--gold);
        font-weight: 500;
        margin-bottom: 12px;
      }

      .vca-section--dark .vcaSectionLabel { color: var(--gold-quiet); }

      .vcaSectionTitle {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 3vw, 2.1rem);
        font-weight: 600;
        line-height: 1.25;
        margin-bottom: 20px;
        color: var(--forest-deep);
      }

      .vca-section--dark .vcaSectionTitle { color: var(--cream); }

      .vcaSectionText {
        font-size: 1rem;
        line-height: 1.8;
        color: var(--ink-soft);
        max-width: 56ch;
        margin-bottom: 16px;
      }

      .vca-section--dark .vcaSectionText { color: rgba(247, 244, 236, 0.75); }

      .vcaSectionLink {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        font-size: 0.94rem;
        font-weight: 500;
        color: var(--forest-deep);
        border-bottom: 1px solid var(--gold);
        padding-bottom: 3px;
        transition: gap 0.25s var(--ease), opacity 0.25s var(--ease);
      }

      .vca-section--dark .vcaSectionLink { color: var(--cream); }

      .vcaSectionLink:hover { gap: 12px; opacity: 0.85; }

      .vcaSectionDivider {
        height: 1px;
        background: var(--line);
      }

      .vca-section--dark + .vcaSectionDivider,
      .vcaSectionDivider + .vca-section--dark {
        background: var(--line-dark);
      }

      /* =========================================
         HERO — quiet, no shimmer or particles
         ========================================= */
      .vcaHero {
        --header-clearance: 140px;
        position: relative;
        min-height: 78vh;
        display: flex;
        align-items: flex-end;
        background: var(--forest-deep) !important;
        overflow: hidden;
      }

      .vcaHeroMedia {
        position: absolute;
        inset: 0;
      }

      .vcaHeroMedia img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.68;
      }

      .vcaHeroMedia::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(14, 33, 24, 0.35) 0%, rgba(14, 33, 24, 0.9) 100%);
      }

      .vcaHeroInner {
        position: relative;
        z-index: 1;
        width: 100%;
        padding: var(--header-clearance) 24px 64px;
        max-width: var(--max-w);
        margin: 0 auto;
        color: var(--cream);
      }

      .vcaHeroEyebrow {
        font-size: 0.78rem;
        letter-spacing: 0.04em;
        color: var(--gold-quiet);
        margin-bottom: 14px;
      }

      .vcaHero h1 {
        font-family: var(--font-display);
        font-size: clamp(2.1rem, 4.4vw, 3rem);
        font-weight: 600;
        line-height: 1.18;
        max-width: 16ch;
        margin-bottom: 18px;
      }

      .vcaHero p {
        font-size: 1.05rem;
        line-height: 1.75;
        max-width: 46ch;
        color: rgba(247, 244, 236, 0.82);
      }

      /* =========================================
         OUR STORY — image + text
         ========================================= */
      .vcaStoryGrid {
        display: grid;
        grid-template-columns: 0.9fr 1fr;
        gap: 56px;
        align-items: center;
      }

      .vcaStoryMedia {
        aspect-ratio: 4 / 5;
        overflow: hidden;
      }

      .vcaStoryMedia img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      /* =========================================
         THE VIRUNGA WAY — dark, centered
         ========================================= */
      .vcaWayContent {
        max-width: 640px;
      }

      .vcaWayQuote {
        font-family: var(--font-display);
        font-size: clamp(1.25rem, 2.2vw, 1.5rem);
        font-weight: 600;
        line-height: 1.5;
        color: var(--cream);
        margin: 24px 0;
        padding-left: 20px;
        border-left: 2px solid var(--gold);
      }

      /* =========================================
         OUR COLLECTION — quiet grid, no shadows
         ========================================= */
      .vcaCollectionIntro {
        max-width: 640px;
        margin-bottom: 48px;
      }

      .vcaCollectionGrid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1px;
        background: var(--line);
        border: 1px solid var(--line);
      }

      .vcaCollectionCard {
        background: var(--cream-warm) !important;
        padding: 32px 28px;
        transition: background-color 0.3s var(--ease);
      }

      .vcaCollectionCard:hover {
        background: #fff !important;
      }

      .vcaCollectionCard .vcaCardIcon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gold);
        font-size: 1.05rem;
        border: 1px solid var(--line);
        margin-bottom: 20px;
      }

      .vcaCollectionCard .vcaCardRole {
        font-size: 0.76rem;
        letter-spacing: 0.03em;
        color: var(--gold);
        font-weight: 500;
        margin-bottom: 6px;
      }

      .vcaCollectionCard h3 {
        font-family: var(--font-display);
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 10px;
      }

      .vcaCollectionCard p {
        font-size: 0.94rem;
        line-height: 1.7;
        color: var(--ink-soft);
      }

      .vcaCollectionCta {
        margin-top: 40px;
        text-align: center;
      }

      /* =========================================
         CLOSING
         ========================================= */
      .vcaClosing {
        text-align: center;
        padding: 100px 24px;
        background: var(--forest-deep) !important;
        color: var(--cream) !important;
      }

      .vcaClosingLine {
        font-family: var(--font-display);
        font-size: clamp(1.35rem, 2.6vw, 1.9rem);
        font-weight: 600;
        max-width: 30ch;
        margin: 0 auto 20px;
        line-height: 1.4;
      }

      .vcaClosingSub {
        font-size: 0.95rem;
        color: rgba(247, 244, 236, 0.65);
        max-width: 46ch;
        margin: 0 auto;
      }

      .vcaClosingPillars {
        margin-top: 36px;
        font-size: 0.88rem;
        letter-spacing: 0.03em;
        color: var(--gold-quiet);
      }

      /* =========================================
         FOOTER
         ========================================= */
      footer {
        background: var(--forest-deep) !important;
        color: rgba(247, 244, 236, 0.7);
        padding: 40px 0 24px;
        font-size: 0.86rem;
        border-top: 1px solid var(--line-dark);
      }

      .vcaFooterGrid {
        display: flex;
        justify-content: space-between;
        flex-wrap: vcaWrap;
        gap: 24px;
        margin-bottom: 24px;
      }

      .vcaFooterBrand {
        font-family: var(--font-display);
        color: var(--cream);
        font-size: 1.05rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .vcaFooterBrand img { height: 28px; width: auto; }

      .vcaFooterNote { font-size: 0.8rem; opacity: 0.7; max-width: 320px; }

      .vcaFooterLinks { display: flex; gap: 24px; list-style: none; flex-wrap: vcaWrap; }

      .vcaFooterLinks a { transition: color 0.2s ease; }
      .vcaFooterLinks a:hover { color: var(--gold); }

      .vcaFooterBottom {
        border-top: 1px solid var(--line-dark);
        padding-top: 18px;
        display: flex;
        justify-content: space-between;
        flex-wrap: vcaWrap;
        gap: 10px;
        font-size: 0.78rem;
        opacity: 0.65;
      }

      /* =========================================
         REVEAL — one quiet fade, nothing scattered
         ========================================= */
      .vcaReveal {
        opacity: 0;
        transform: translateY(16px);
        transition: opacity 0.7s var(--ease), transform 0.7s var(--ease);
      }

      .vcaReveal.vca-in-view { opacity: 1; transform: translateY(0); }

      @media (prefers-reduced-motion: reduce) {
        .vcaReveal { opacity: 1; transform: none; transition: none; }
        html { scroll-behavior: auto; }
      }

      /* =========================================
         RESPONSIVE
         ========================================= */
      @media (max-width: 900px) {
        .vcaStoryGrid { grid-template-columns: 1fr; gap: 32px; }
        .vcaStoryMedia { aspect-ratio: 16 / 10; }
        .vcaCollectionGrid { grid-template-columns: repeat(2, 1fr); }
      }

      @media (max-width: 620px) {
        .vca-section { padding: 64px 0; }
        .vcaHero { min-height: 64vh; --header-clearance: 108px; }
        .vcaHeroInner { padding-bottom: 44px; }
        .vcaCollectionGrid { grid-template-columns: 1fr; }
        .vcaClosing { padding: 72px 24px; }
      }
    </style>
  </head>
  <body>
    <?php include __DIR__ . '/header.php'; ?>

    <!-- ========== HERO ========== -->
    <section class="vcaHero" id="aboutHero">
      <div class="vcaHeroMedia">
        <img src="<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>" alt="Volcanic peaks and forest canopy of the Virunga" loading="eager" />
      </div>
      <div class="vcaHeroInner">
        <span class="vcaHeroEyebrow vcaReveal">About Virunga Collective</span>
        <h1 class="vcaReveal">A deeper way of experiencing the Virunga.</h1>
        <p class="vcaReveal">
          Virunga Collective is a destination-led house of immersive travel, hospitality and distinctive experiences rooted in the landscapes, cultures and communities of the Virunga region. We bring together different ways of experiencing and engaging with this remarkable place — from journeys and hospitality to culture, conservation, learning and coffee.
        </p>
      </div>
    </section>

    <!-- ========== OUR STORY ========== -->
    <section class="vca-section vca-section--light" id="story">
      <div class="vcaWrap vcaStoryGrid">
        <div class="vcaStoryMedia vcaReveal">
          <img src="<?php echo htmlspecialchars($baseLink('img/intro.jpeg')); ?>" alt="Virunga landscape with volcanic peaks and lush forest canopy" loading="lazy" />
        </div>
        <div class="vcaReveal">
          <span class="vcaSectionLabel">Our Story</span>
          <h2 class="vcaSectionTitle">Rooted in the Virunga. Created with purpose.</h2>
          <p class="vcaSectionText">
            The Virunga is more than a landscape. It is a living region shaped by volcanoes, forests, wildlife, communities, traditions and generations of knowledge.
          </p>
          <p class="vcaSectionText">
            Virunga Collective was created to bring these dimensions together through meaningful experiences and ventures that create deeper connections between people and place.
          </p>
          <a href="<?php echo htmlspecialchars($baseLink('about/story')); ?>" class="vcaSectionLink">
            Discover Our Story <i class="fas fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>

    <div class="vcaSectionDivider"></div>

    <!-- ========== THE VIRUNGA WAY ========== -->
    <section class="vca-section vca-section--dark" id="the-virunga-way">
      <div class="vcaWrap">
        <div class="vcaWayContent vcaReveal">
          <span class="vcaSectionLabel">The Virunga Way</span>
          <h2 class="vcaSectionTitle">Our philosophy</h2>
          <p class="vcaSectionText">
            Learning from place. Connecting meaningfully. Creating lasting value.
          </p>
          <div class="vcaWayQuote">
            The Virunga Way guides how we create our journeys, welcome our guests, share knowledge, engage with communities and contribute to the places that make these experiences possible.
          </div>
          <p class="vcaSectionText">
            It is our shared approach across everything we do.
          </p>
          <a href="<?php echo htmlspecialchars($baseLink('about/the-virunga-way')); ?>" class="vcaSectionLink">
            Discover The Virunga Way <i class="fas fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>

    <div class="vcaSectionDivider"></div>

    <!-- ========== OUR COLLECTION ========== -->
    <section class="vca-section vca-section--light" id="collection">
      <div class="vcaWrap">
        <div class="vcaCollectionIntro vcaReveal">
          <span class="vcaSectionLabel">Our Collection</span>
          <h2 class="vcaSectionTitle">One Collective. Distinct expressions.</h2>
          <p class="vcaSectionText">
            Each expression of Virunga Collective has a clear role, while sharing the same connection to place and purpose.
          </p>
        </div>

        <div class="vcaCollectionGrid vcaReveal">
          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-route" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Immersive Travel</span>
            <h3>Virunga Journeys</h3>
            <p>Private, signature and bespoke journeys shaped around meaningful travel.</p>
          </div>

          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-house-chimney" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Hospitality</span>
            <h3>Virunga House</h3>
            <p>A welcoming place to stay, slow down and experience the Virunga.</p>
          </div>

          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-compass-drafting" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Curated Experiences</span>
            <h3>Virunga Signatures</h3>
            <p>Distinctive experiences connecting guests with culture, nature, food, craft and community.</p>
          </div>

          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-hands-holding-circle" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Community &amp; Conservation</span>
            <h3>Virunga Impact</h3>
            <p>Work that supports conservation, community participation and lasting local value.</p>
          </div>

          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-graduation-cap" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Learning &amp; Knowledge</span>
            <h3>Virunga Academy</h3>
            <p>Place-based learning, knowledge exchange and opportunities to deepen understanding of the Virunga.</p>
          </div>

          <div class="vcaCollectionCard">
            <div class="vcaCardIcon"><i class="fas fa-mug-hot" aria-hidden="true"></i></div>
            <span class="vcaCardRole">Coffee &amp; Origin</span>
            <h3>Virunga Coffee</h3>
            <p>A coffee expression rooted in the Virunga highlands, connecting origin, craft and the people behind every cup.</p>
          </div>
        </div>

        <div class="vcaCollectionCta vcaReveal">
          <a href="<?php echo htmlspecialchars($baseLink('collection')); ?>" class="vcaSectionLink">
            Explore Our Collection <i class="fas fa-arrow-right" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ========== CLOSING ========== -->
    <section class="vcaClosing" id="aboutClosing">
      <div class="vcaClosingLine vcaReveal">
        One Collective. Distinct expressions. One living region.
      </div>
      <p class="vcaClosingSub vcaReveal">
        Virunga Collective brings journeys, hospitality, culture, impact, learning and coffee together under one shared vision for the Virunga.
      </p>
      <p class="vcaClosingPillars vcaReveal">Journeys · House · Signatures · Impact · Academy · Coffee</p>
    </section>

    <!-- ========== SIGNATURE CTA ========== -->
    <?php
      $cta_id = 'planner';
      $cta_title = 'PLAN YOUR JOURNEY';
      $cta_lead = 'Whether you are drawn to intimate wildlife encounters, quiet mountain hospitality, or living cultural traditions, we invite you to begin your journey.';
      $cta_primary_text = 'PLAN YOUR JOURNEY';
      $cta_primary_url = 'https://wa.me/250784513435?text=' . urlencode('Hello Virunga Collective, I would like to plan my journey.');
      $cta_secondary_text = 'DISCOVER OUR COLLECTION';
      $cta_secondary_url = '#collection';
      include __DIR__ . '/cta.php';
    ?>

    <!-- ========== FOOTER ========== -->
    <?php include __DIR__ . '/footer.php'; ?>

    <script>
      (function initReveal() {
        var els = document.querySelectorAll('.vcaReveal');
        if (!('IntersectionObserver' in window)) {
          els.forEach(function (el) { el.classList.add('vca-in-view'); });
          return;
        }
        var observer = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add('vca-in-view');
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.1, rootMargin: '0px 0px -40px 0px' }
        );
        els.forEach(function (el) { observer.observe(el); });
      })();
    </script>
  </body>
</html>
