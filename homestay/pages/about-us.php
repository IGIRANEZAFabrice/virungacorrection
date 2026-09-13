<?php
  $pageTitle = 'Our Story | Virunga House - Locally Rooted Stay in Musanze';
  $pageDescription = 'Discover the story of Virunga House — from a family home in 2020 to an intimate boutique stay beneath the Virunga volcanoes in Musanze, Rwanda.';
  $pageKeywords = 'Virunga House story, Virunga Homestay history, Francisco and Aline, Musanze homestay, Virunga Collective, Rwanda boutique stay';
  $pageCss = ['page-hero.css', 'about.css'];
  $pageHeroKey = 'about-us';
  $pageScripts = ['about.js'];
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

  .vh-story-wrap {
    font-family: var(--vh-font-body);
    color: var(--vh-text-dark);
    background: var(--vh-cream);
  }

  /* Shared Section Utility */
  .vh-sec {
    padding: 90px 0;
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
  .vh-container {
    max-width: 1140px;
    margin: 0 auto;
    padding: 0 24px;
  }

  /* Editorial Headers */
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
    font-size: clamp(2.1rem, 3.8vw, 3rem);
    font-weight: 600;
    line-height: 1.18;
    color: var(--vh-forest);
    margin: 0 0 18px 0;
  }
  .vh-sec--dark .vh-title {
    color: #ffffff;
  }
  .vh-lead {
    font-size: 1.12rem;
    line-height: 1.75;
    color: var(--vh-text-muted);
  }
  .vh-sec--dark .vh-lead {
    color: rgba(246, 242, 233, 0.9);
  }

  /* Buttons */
  .btn-hs-primary {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--vh-forest);
    color: #ffffff;
    padding: 14px 28px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.25s ease, transform 0.25s ease;
  }
  .btn-hs-primary:hover {
    background: var(--vh-green-mid);
    color: #ffffff;
    transform: translateY(-2px);
  }
  .btn-hs-gold {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: var(--vh-gold);
    color: var(--vh-forest-deep);
    padding: 14px 30px;
    font-size: 0.82rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.25s ease, transform 0.25s ease;
  }
  .btn-hs-gold:hover {
    background: var(--vh-gold-light);
    color: var(--vh-forest-deep);
    transform: translateY(-2px);
  }
  .btn-hs-outline-light {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    color: #ffffff;
    border: 1px solid rgba(255,255,255,0.4);
    padding: 14px 28px;
    font-size: 0.82rem;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    border-radius: 4px;
    text-decoration: none;
    transition: background 0.25s ease, border-color 0.25s ease;
  }
  .btn-hs-outline-light:hover {
    background: rgba(255,255,255,0.1);
    border-color: #ffffff;
    color: #ffffff;
  }

  /* ── SECTION 1: MANIFESTO / INTRO ── */
  .story-intro-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    gap: 56px;
    align-items: center;
  }
  .story-intro-quote {
    font-family: var(--vh-font-display);
    font-size: 1.55rem;
    line-height: 1.45;
    color: var(--vh-forest);
    font-style: italic;
    border-left: 3px solid var(--vh-gold);
    padding-left: 24px;
    margin: 28px 0;
  }
  .story-intro-image-frame {
    position: relative;
  }
  .story-intro-image {
    width: 100%;
    height: 480px;
    object-fit: cover;
    border-radius: 6px;
    box-shadow: 0 16px 40px rgba(18,42,31,0.12);
  }
  .story-intro-badge {
    position: absolute;
    bottom: -16px;
    left: -16px;
    background: var(--vh-forest);
    color: var(--vh-gold-light);
    padding: 14px 20px;
    border-radius: 4px;
    font-family: var(--vh-font-display);
    font-size: 1.1rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  }

  /* ── SECTION 2: TIMELINE MILESTONES ── */
  .story-timeline-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 40px;
  }
  .story-milestone-card {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    padding: 30px 24px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .story-milestone-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(18,42,31,0.06);
  }
  .milestone-date {
    font-family: var(--vh-font-body);
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: var(--vh-gold-dark);
    margin-bottom: 8px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }
  .milestone-title {
    font-family: var(--vh-font-display);
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--vh-forest);
    margin: 0 0 10px 0;
  }
  .milestone-desc {
    font-size: 0.92rem;
    line-height: 1.6;
    color: var(--vh-text-muted);
  }

  /* ── SECTION 3: SPLIT CHAPTER ── */
  .story-split-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
  }
  .story-split-img {
    width: 100%;
    height: 420px;
    object-fit: cover;
    border-radius: 6px;
    box-shadow: 0 16px 36px rgba(18,42,31,0.08);
  }
  .story-callout-box {
    background: #ffffff;
    border-left: 3px solid var(--vh-gold);
    padding: 18px 22px;
    margin: 22px 0;
    font-size: 0.98rem;
    line-height: 1.65;
    color: var(--vh-forest);
  }

  /* ── SECTION 4: PILLARS CARDS ── */
  .story-pillars-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    margin-top: 44px;
  }
  .story-pillar-card {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 6px;
    padding: 34px 28px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
  }
  .story-pillar-icon {
    width: 48px;
    height: 48px;
    background: var(--vh-warm-bg);
    color: var(--vh-gold-dark);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 20px;
  }
  .story-pillar-title {
    font-family: var(--vh-font-display);
    font-size: 1.45rem;
    font-weight: 700;
    color: var(--vh-forest);
    margin: 0 0 12px 0;
  }
  .story-pillar-desc {
    font-size: 0.92rem;
    line-height: 1.65;
    color: var(--vh-text-muted);
  }

  /* ── SECTION 5: VIRUNGA COLLECTIVE ECOSYSTEM ── */
  .collective-eco-box {
    background: #ffffff;
    border: 1px solid var(--vh-border);
    border-radius: 8px;
    padding: 44px;
    margin-top: 36px;
    box-shadow: 0 12px 36px rgba(18,42,31,0.04);
  }
  .collective-eco-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
    margin-bottom: 32px;
  }
  .collective-eco-col {
    padding: 26px 24px;
    border-radius: 6px;
    background: var(--vh-cream);
    border: 1px solid var(--vh-border);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .collective-eco-col--active {
    background: var(--vh-forest);
    color: #ffffff;
    border-color: var(--vh-green-mid);
  }
  .eco-brand-eyebrow {
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: var(--vh-gold-dark);
    margin-bottom: 8px;
  }
  .collective-eco-col--active .eco-brand-eyebrow {
    color: var(--vh-gold-light);
  }
  .eco-brand-title {
    font-family: var(--vh-font-display);
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--vh-forest);
    margin: 0 0 10px 0;
  }
  .collective-eco-col--active .eco-brand-title {
    color: #ffffff;
  }
  .eco-brand-desc {
    font-size: 0.88rem;
    line-height: 1.6;
    color: var(--vh-text-muted);
  }
  .collective-eco-col--active .eco-brand-desc {
    color: rgba(246, 242, 233, 0.9);
  }
  .collective-summary-text {
    text-align: center;
    max-width: 820px;
    margin: 0 auto;
    font-family: var(--vh-font-display);
    font-size: 1.35rem;
    line-height: 1.55;
    color: var(--vh-forest);
    font-weight: 500;
    padding-top: 24px;
    border-top: 1px solid var(--vh-border);
  }

  /* Responsive Adjustments */
  @media (max-width: 960px) {
    .story-intro-grid,
    .story-split-grid {
      grid-template-columns: 1fr;
      gap: 36px;
    }
    .story-timeline-grid,
    .story-pillars-grid,
    .collective-eco-grid {
      grid-template-columns: 1fr;
    }
    .collective-eco-box {
      padding: 24px;
    }
    .story-intro-badge {
      position: static;
      margin-top: 12px;
      display: inline-block;
    }
  }
</style>

<?php include 'page-hero.php'; ?>

<div class="vh-story-wrap" id="about-page">

  <!-- ====================================================
       CHAPTER 1: THE ORIGIN (OUR STORY)
  ==================================================== -->
  <section class="vh-sec vh-sec--cream" id="story-origin">
    <div class="vh-container">
      <div class="story-intro-grid" data-reveal>
        <div class="story-intro-copy">
          <span class="vh-eyebrow">OUR STORY</span>
          <h2 class="vh-title">A house rooted in the Virunga.</h2>
          <p class="vh-lead">
            Before it welcomed travellers, Virunga House was simply a family home.
          </p>
          <p class="about-body" style="font-size: 1.02rem; line-height: 1.75; color: var(--vh-text-dark); margin-top: 14px;">
            In Musanze, beneath the Virunga volcanoes, it was a place of everyday family life. In 2020, that home opened its doors to travellers with a simple idea: to promote the homestay concept and create a genuine connection between visitors and local people.
          </p>
          <div class="story-intro-quote">
            "The purpose was never simply to provide a room. It was to help visitors connect more naturally with the Virunga and the people who call it home."
          </div>
        </div>
        <div class="story-intro-image-frame">
          <img src="./img/ourstory.JPG" alt="Virunga House Family Home in Musanze" class="story-intro-image" loading="lazy">
          <div class="story-intro-badge">
            <span>Rooted in Musanze since 2020</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       CHAPTER 2: FROM HOME TO HOMESTAY (THE TIMELINE)
  ==================================================== -->
  <section class="vh-sec vh-sec--light" id="from-home-to-homestay">
    <div class="vh-container">
      <div class="vh-header center" data-reveal>
        <span class="vh-eyebrow">THE BEGINNING</span>
        <h2 class="vh-title">From Home to Homestay</h2>
        <p class="vh-lead" style="max-width: 820px; margin: 0 auto;">
          On 1 March 2020, Francisco, Aline and other members of the family welcomed their first guests and began the story of Virunga Homestay.
        </p>
      </div>

      <div class="story-timeline-grid" data-reveal>
        <!-- Milestone 1 -->
        <div class="story-milestone-card">
          <span class="milestone-date"><i class="fas fa-calendar-check"></i> 1 MARCH 2020</span>
          <h3 class="milestone-title">First Guests Welcome</h3>
          <p class="milestone-desc">
            The House started with just two rooms. Among its earliest guests were four travellers from France, whose stay marked the beginning of a new chapter for the family home.
          </p>
        </div>

        <!-- Milestone 2 -->
        <div class="story-milestone-card">
          <span class="milestone-date"><i class="fas fa-utensils"></i> 16 MARCH 2020</span>
          <h3 class="milestone-title">The House Table Begins</h3>
          <p class="milestone-desc">
            What began with a place to sleep soon became something more. The Homestay began serving meals, including dinner, giving guests a place not only to stay but also to share food and time with their hosts.
          </p>
        </div>

        <!-- Milestone 3 -->
        <div class="story-milestone-card">
          <span class="milestone-date"><i class="fas fa-compass"></i> 17 MARCH 2020</span>
          <h3 class="milestone-title">Helping Guests Discover</h3>
          <p class="milestone-desc">
            The family began helping guests with gorilla trekking, transport and other local experiences. Helping visitors connect more naturally with the Virunga landscapes and community.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       CHAPTER 3: GROWING WITH THE PLACE
  ==================================================== -->
  <section class="vh-sec vh-sec--warm" id="growing-with-the-place">
    <div class="vh-container">
      <div class="story-split-grid" data-reveal>
        <div class="story-split-media">
          <img src="./img/about.jpeg" alt="Sanctuary and Gardens at Virunga House" class="story-split-img" loading="lazy">
        </div>
        <div class="story-split-content">
          <span class="vh-eyebrow">THE EVOLUTION</span>
          <h2 class="vh-title" style="text-align: left;">Growing with the Place</h2>
          <p class="about-body" style="font-size: 1.02rem; line-height: 1.75; color: var(--vh-text-dark);">
            Over time, Virunga Homestay evolved. The House became more established, the hospitality grew, and the relationship between staying, discovering and connecting with the Virunga became increasingly important.
          </p>
          <div class="story-callout-box">
            <strong>28 August 2026:</strong> The name evolved from <em>Virunga Homestay</em> to <strong>Virunga House</strong>.
          </div>
          <p class="about-body" style="font-size: 0.98rem; line-height: 1.7; color: var(--vh-text-muted);">
            The new name reflects that journey. Virunga House is still rooted in the same spirit that shaped it from the beginning: warmth, personal hospitality and a genuine connection to place. But it now represents a more established and distinct place to stay in the Virunga.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       CHAPTER 4: A DIFFERENT KIND OF STAY
  ==================================================== -->
  <section class="vh-sec vh-sec--light" id="a-different-kind-of-stay">
    <div class="vh-container">
      <div class="vh-header center" data-reveal>
        <span class="vh-eyebrow">HOSPITALITY &amp; CHARACTER</span>
        <h2 class="vh-title">A Different Kind of Stay</h2>
        <p class="vh-lead" style="max-width: 840px; margin: 0 auto;">
          Today, Virunga House is a locally rooted stay in Musanze, beneath the volcanoes.
        </p>
        <p style="max-width: 840px; margin: 16px auto 0; font-size: 1rem; line-height: 1.7; color: var(--vh-text-dark);">
          It is a place to settle in after a day in the mountains, wake to the surrounding landscape, share breakfast around the table, enjoy dinner by arrangement, sit by the fire and take time to experience the Virunga at a slower pace.
        </p>
      </div>

      <div class="story-pillars-grid" data-reveal>
        <div class="story-pillar-card">
          <div class="story-pillar-icon"><i class="fas fa-bed"></i></div>
          <h3 class="story-pillar-title">Intentionally Personal</h3>
          <p class="story-pillar-desc">
            With only six rooms, the House is never crowded. It is not positioned as a conventional hotel or a restaurant, but as an intimate sanctuary shaped by real warmth and personal care.
          </p>
        </div>

        <div class="story-pillar-card">
          <div class="story-pillar-icon"><i class="fas fa-utensils"></i></div>
          <h3 class="story-pillar-title">The House Table</h3>
          <p class="story-pillar-desc">
            Breakfast is freshly prepared and included with your stay. In the evening, guests can enjoy an intimate home-cooked dinner by advance arrangement.
          </p>
        </div>

        <div class="story-pillar-card">
          <div class="story-pillar-icon"><i class="fas fa-mountain"></i></div>
          <h3 class="story-pillar-title">Volcanic Sanctuary</h3>
          <p class="story-pillar-desc">
            Located just 15 minutes from Volcanoes National Park HQ, offering panoramic views, quiet reading nooks, evening campfires, and fresh mountain air.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       CHAPTER 5: PART OF A WIDER VISION (VIRUNGA COLLECTIVE)
  ==================================================== -->
  <section class="vh-sec vh-sec--warm" id="wider-vision">
    <div class="vh-container">
      <div class="vh-header center" data-reveal>
        <span class="vh-eyebrow">THE WIDER VISION</span>
        <h2 class="vh-title">Part of a Wider Vision</h2>
        <p class="vh-lead" style="max-width: 820px; margin: 0 auto;">
          As the vision grew beyond accommodation, so did the wider idea behind it. <strong>Virunga Collective</strong> emerged as the master brand connecting distinct expressions of the Virunga experience.
        </p>
      </div>

      <div class="collective-eco-box" data-reveal>
        <div class="collective-eco-grid">
          <!-- Virunga House -->
          <div class="collective-eco-col collective-eco-col--active">
            <div>
              <span class="eco-brand-eyebrow">ACCOMMODATION</span>
              <h3 class="eco-brand-title">VIRUNGA HOUSE</h3>
              <p class="eco-brand-desc">
                <strong>The place to stay.</strong> Locally rooted boutique accommodation in Musanze, personal hospitality, daily breakfast, and home-cooked dinners by arrangement.
              </p>
            </div>
            <div style="margin-top: 20px;">
              <a href="<?php echo $baseLink('rooms'); ?>" class="btn-hs-gold" style="font-size: 0.75rem; padding: 10px 18px;">
                Explore Rooms &rarr;
              </a>
            </div>
          </div>

          <!-- Virunga Journeys -->
          <div class="collective-eco-col">
            <div>
              <span class="eco-brand-eyebrow">JOURNEYS &amp; EXPEDITIONS</span>
              <h3 class="eco-brand-title">VIRUNGA JOURNEYS</h3>
              <p class="eco-brand-desc">
                <strong>The journeys and experiences.</strong> Guided gorilla trekking, volcano climbs, cultural encounters, private 4x4 safaris, and seamless regional logistics.
              </p>
            </div>
            <div style="margin-top: 20px;">
              <a href="<?php echo $baseLink('activity'); ?>" class="btn-hs-primary" style="font-size: 0.75rem; padding: 10px 18px;">
                Explore Journeys &rarr;
              </a>
            </div>
          </div>

          <!-- Virunga Collective -->
          <div class="collective-eco-col">
            <div>
              <span class="eco-brand-eyebrow">MASTER BRAND</span>
              <h3 class="eco-brand-title">VIRUNGA COLLECTIVE</h3>
              <p class="eco-brand-desc">
                <strong>The wider connection between them.</strong> Connecting stays, transformative journeys, conservation storytelling, and community empowerment.
              </p>
            </div>
            <div style="margin-top: 20px;">
              <a href="<?php echo $baseLink('home'); ?>" class="btn-hs-primary" style="font-size: 0.75rem; padding: 10px 18px;">
                Collective Home &rarr;
              </a>
            </div>
          </div>
        </div>

        <div class="collective-summary-text">
          "The House therefore remains what it has always been at its heart: a place to arrive, stay, connect and return to.<br>
          From a family home, to a two-room homestay, to Virunga House today, the journey has continued without losing its beginning."
        </div>
      </div>
    </div>
  </section>

  <!-- ====================================================
       CHAPTER 6: INVITATION / CTA
  ==================================================== -->
  <section class="vh-sec vh-sec--dark" id="invitation" style="background-image: linear-gradient(rgba(12, 28, 20, 0.88), rgba(12, 28, 20, 0.92)), url('./img/day/7.jpeg'); background-size: cover; background-position: center; text-align: center;">
    <div class="vh-container">
      <div class="hs-invitation-box" data-reveal>
        <span class="vh-eyebrow" style="color: var(--vh-gold-light);">STAY IN THE VIRUNGA</span>
        <h2 class="vh-title" style="color: #ffffff; margin-bottom: 18px;">Stay closer to the Virunga.</h2>
        <p class="vh-lead" style="color: rgba(246, 242, 233, 0.9); max-width: 740px; margin: 0 auto 36px;">
          <em>Stay. Eat. Rest. Discover.</em><br>
          Whether resting between gorilla treks or sitting by the evening campfire, we invite you to experience northern Rwanda from within.
        </p>
        <div style="display: flex; justify-content: center; gap: 16px; flex-wrap: wrap;">
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

</div>

<?php include 'includes/footer.php'; ?>