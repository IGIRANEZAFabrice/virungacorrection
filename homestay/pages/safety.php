<?php
  $pageTitle = 'Safety & Guest Confidence - Virunga Homestay';
  $pageDescription = 'Experience authentic Rwandan hospitality with confidence. Our commitment to your safety, cleanliness, and personal care at Virunga Homestay.';
  $pageKeywords = 'safety, cleanliness, guest confidence, Musanze, Rwanda, homestay, travel safety';
  $pageCss = ['safety.css'];
  $pageHeroKey = null; // This page has its own immersive hero
  $pageScripts = ['safety.js'];
  include 'includes/header.php';
?>

<div class="safety-page">
  <!-- HERO -->
  <header class="hero" aria-labelledby="hero-heading">
    <div class="hero-bg" aria-hidden="true"></div>
    <div class="hero-line-top" aria-hidden="true"></div>

    <p class="hero-eyebrow">Virunga Homestay · Musanze, Rwanda</p>

    <h1 id="hero-heading">
      Safety &amp;
      <em>Guest Confidence</em>
    </h1>

    <p class="hero-sub">At Virunga Homestay, we believe that true hospitality is built on trust, care, and attention to detail — not policies, but people.</p>

    <div class="hero-trust" role="list" aria-label="Key trust indicators">
      <div class="hero-trust-item" role="listitem">
        <span class="num">100%</span>
        <span class="lbl">Personal care</span>
      </div>
      <div class="hero-divider" aria-hidden="true"></div>
      <div class="hero-trust-item" role="listitem">
        <span class="num">Small</span>
        <span class="lbl">Guest groups</span>
      </div>
      <div class="hero-divider" aria-hidden="true"></div>
      <div class="hero-trust-item" role="listitem">
        <span class="num">Local</span>
        <span class="lbl">Hosting family</span>
      </div>
    </div>

    <div class="hero-scroll" aria-hidden="true">
      <span>Explore</span>
      <div class="scroll-line"></div>
    </div>
  </header>

  <!-- SECTION 1: CLEANLINESS -->
  <section class="section" aria-labelledby="s1-heading">
    <div class="section-inner">
      <div class="img-block reveal">
        <div class="img-frame">
          <img src="./img/rooms/1752288311_7964952.jpg" alt="Clean, prepared guest room" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
        </div>
        <div class="img-tag" aria-hidden="true">Daily Standard</div>
        <div class="img-accent" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </div>
      </div>

      <div class="txt-block">
        <p class="section-num reveal reveal-delay-1">01 — Guest Care Standards</p>
        <div class="gold-rule reveal reveal-delay-1" aria-hidden="true"></div>
        <h2 class="section-heading reveal reveal-delay-2" id="s1-heading">
          <em>Cleanliness</em><br>& Preparation
        </h2>
        <p class="section-body reveal reveal-delay-2">We don't just promise comfort — we maintain clear daily standards so every guest arrives to a room that is genuinely ready for them.</p>
        <ul class="feat-list reveal reveal-delay-3" aria-label="Cleanliness standards">
          <li><div class="feat-dot" aria-hidden="true"></div>Every room cleaned and prepared before guest arrival</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Fresh linens and sanitized surfaces for each stay</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Regular inspection of all guest areas</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Natural ventilation and calm, low-density surroundings</li>
        </ul>
      </div>
    </div>
  </section>

  <div class="section-divider" aria-hidden="true"></div>

  <!-- SECTION 2: FOOD -->
  <section class="section" aria-labelledby="s2-heading">
    <div class="section-inner reverse">
      <div class="img-block reveal">
        <div class="img-frame">
          <img src="./img/gallery/food.jpeg" alt="Guests sharing a home-cooked Rwandan meal" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
        </div>
        <div class="img-tag" aria-hidden="true">Fresh Daily</div>
      </div>

      <div class="txt-block">
        <p class="section-num reveal reveal-delay-1">02 — Food & Hospitality</p>
        <div class="gold-rule reveal reveal-delay-1" aria-hidden="true"></div>
        <h2 class="section-heading reveal reveal-delay-2" id="s2-heading">
          Food made<br>with <em>care</em>
        </h2>
        <p class="section-body reveal reveal-delay-2">Every meal is prepared fresh in a clean kitchen environment by our hosting family. We treat what you eat with the same care we give your room.</p>
        <ul class="feat-list reveal reveal-delay-3" aria-label="Food care standards">
          <li><div class="feat-dot" aria-hidden="true"></div>Freshly prepared meals in a clean kitchen</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Dietary preferences respected when communicated in advance</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Hygienic food handling by trained staff</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Local ingredients, seasonal and honest</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- PHILOSOPHY BANNER -->
  <div class="banner reveal" role="complementary" aria-label="Brand statement">
    <div class="banner-bg" aria-hidden="true"></div>
    <blockquote class="banner-quote">"We are not a large hotel chain. We are a home. Our strength is not scale, but human connection."</blockquote>
    <p class="banner-attr">— Virunga Homestay Philosophy</p>
  </div>

  <div class="section-divider" aria-hidden="true"></div>

  <!-- SECTION 3: PERSONAL HOSTING -->
  <section class="section" aria-labelledby="s3-heading">
    <div class="section-inner">
      <div class="img-block reveal">
        <div class="img-frame">
          <img src="./img/gallery/family.jpeg" alt="Host family welcoming guests" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
        </div>
        <div class="img-tag" aria-hidden="true">Personal Care</div>
        <div class="img-accent" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
      </div>

      <div class="txt-block">
        <p class="section-num reveal reveal-delay-1">03 — Personal Hosting</p>
        <div class="gold-rule reveal reveal-delay-1" aria-hidden="true"></div>
        <h2 class="section-heading reveal reveal-delay-2" id="s3-heading">
          <em>People</em><br>not systems
        </h2>
        <p class="section-body reveal reveal-delay-2">Because we host only a small number of guests at a time, you receive attention that no large hotel can provide. You are known by name, not room number.</p>
        <ul class="feat-list reveal reveal-delay-3" aria-label="Personal hosting standards">
          <li><div class="feat-dot" aria-hidden="true"></div>Small number of guests at any one time</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Direct care from a dedicated hosting family</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Support before, during, and after your stay</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Cultural immersion guided by real community members</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- PILLARS -->
  <div class="pillars" aria-labelledby="pillars-heading">
    <div class="pillars-header reveal">
      <p class="section-num" style="text-align:center;margin-bottom:0.75rem">Our Commitments</p>
      <h2 class="section-heading serif" id="pillars-heading" style="text-align:center;font-size:clamp(1.8rem,4vw,2.8rem)">
        Built on <em>four pillars</em>
      </h2>
    </div>

    <div class="pillars-grid" role="list">
      <div class="pillar reveal reveal-delay-1" role="listitem">
        <div class="pillar-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <h3 class="pillar-title">Health &amp; Travel Awareness</h3>
        <p class="pillar-body">We stay informed through official Rwandan travel and health advisories and align our operations with current guidance at all times.</p>
      </div>

      <div class="pillar reveal reveal-delay-2" role="listitem">
        <div class="pillar-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <h3 class="pillar-title">Flexible Booking</h3>
        <p class="pillar-body">Travel plans change. We offer flexible date adjustments, rescheduling support, and direct communication for itinerary planning.</p>
      </div>

      <div class="pillar reveal reveal-delay-3" role="listitem">
        <div class="pillar-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        </div>
        <h3 class="pillar-title">Globally Welcoming</h3>
        <p class="pillar-body">We welcome guests from every country and background. Our home is a place where the world feels small and connection feels immediate.</p>
      </div>
    </div>
  </div>

  <!-- TRUST TAGLINE BAR -->
  <div class="trust-bar reveal" role="complementary">
    <p class="trust-tagline">
      <span>Locally hosted.</span> Personally cared for.
      <span>Officially compliant.</span> Globally welcoming.
    </p>
  </div>

  <!-- SECTION 4: HEALTH AWARENESS -->
  <section class="section" aria-labelledby="s4-heading">
    <div class="section-inner reverse">
      <div class="img-block reveal">
        <div class="img-frame">
          <img src="./img/gallery/mountain.jpeg" alt="Virunga landscape — volcanoes and green hills" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0">
        </div>
        <div class="img-tag" aria-hidden="true">Always Informed</div>
      </div>

      <div class="txt-block">
        <p class="section-num reveal reveal-delay-1">04 — Health &amp; Travel Awareness</p>
        <div class="gold-rule reveal reveal-delay-1" aria-hidden="true"></div>
        <h2 class="section-heading reveal reveal-delay-2" id="s4-heading">
          Calm, informed,<br><em>prepared</em>
        </h2>
        <p class="section-body reveal reveal-delay-2">We encourage every guest to arrive well-informed and we make ourselves available for any questions before and during your stay.</p>
        <ul class="feat-list reveal reveal-delay-3">
          <li><div class="feat-dot" aria-hidden="true"></div>Stay updated on travel requirements before arrival</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Communicate with us directly for any concerns</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Follow standard international travel health recommendations</li>
          <li><div class="feat-dot" aria-hidden="true"></div>Operations aligned with current guidance at all times</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- PHILOSOPHY SECTION -->
  <div class="philosophy reveal" role="complementary" aria-labelledby="philosophy-heading">
    <div class="philosophy-line" aria-hidden="true"></div>
    <h2 id="philosophy-heading">
      Every guest experience<br>
      is shaped by <em>people,</em><br>
      not systems.
    </h2>
    <p class="section-body" style="max-width:500px;margin:0 auto;text-align:center">
      We are a home. Our strength is not scale, but human connection, personal care, and authenticity. When you stay with us, you stay with a family.
    </p>
  </div>

  <!-- CLOSING -->
  <div class="closing reveal" role="complementary" aria-labelledby="closing-heading">
    <h2 id="closing-heading">We look forward to<br><em>welcoming you.</em></h2>
    <p>Your stay at Virunga Homestay is designed to be calm, safe, and meaningful — allowing you to fully experience the Virunga region through nature, culture, and human connection.</p>
    <a href="<?php echo $baseLink('contact'); ?>" class="cta-btn" aria-label="Book your stay at Virunga Homestay">
      Book your stay
      <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
