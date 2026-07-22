<?php
require_once __DIR__ . '/../config/recaptcha.php';
$pageTitle = 'Membership — Virunga Collective';
$pageDescription = 'Join the Virunga Collective Membership. Travel, belong, and make a positive impact in the Virunga region.';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?></title>
  <meta name="description" content="<?php echo $pageDescription; ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <style>
    :root {
      --forest: #1b3a2b;
      --forest-deep: #0d1f16;
      --forest-mid: #152e22;
      --gold: #c9a24b;
      --gold-light: #e4c97a;
      --gold-glow: rgba(201, 162, 75, 0.15);
      --cream: #f6f2e9;
      --cream-warm: #faf7f0;
      --charcoal: #1f2620;
      --white: #ffffff;
      --danger: #c62828;
      --font-display: "Cormorant Garamond", serif;
      --font-sans: "Jost", sans-serif;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: var(--cream-warm);
      color: var(--charcoal);
      font-family: var(--font-sans);
      font-size: 1.05rem;
      line-height: 1.65;
      overflow-x: hidden;
    }

    .wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* Typography */
    h1, h2, h3, h4 {
      font-family: var(--font-display);
      font-weight: 500;
      color: var(--forest-deep);
    }

    /* Hero Section */
    .hero {
      position: relative;
      background: linear-gradient(rgba(13, 31, 22, 0.75), rgba(13, 31, 22, 0.9)), url('<?php echo $baseLink("img/abouthero.jpeg"); ?>') no-repeat center center / cover;
      padding: 180px 0 100px;
      text-align: center;
      color: var(--cream);
    }

    .hero h1 {
      font-size: clamp(2.5rem, 6vw, 4rem);
      color: var(--gold);
      margin-bottom: 12px;
      letter-spacing: 0.03em;
      text-transform: uppercase;
      font-weight: 600;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s forwards;
    }

    .hero p.tagline {
      font-size: clamp(1.2rem, 3vw, 1.8rem);
      font-family: var(--font-display);
      font-style: italic;
      color: var(--cream);
      margin-bottom: 24px;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s 0.2s forwards;
    }

    .hero p.intro {
      max-width: 700px;
      margin: 0 auto;
      font-size: 1.15rem;
      opacity: 0.9;
      line-height: 1.8;
      font-weight: 300;
      opacity: 0;
      transform: translateY(20px);
      animation: fadeInUp 0.8s 0.4s forwards;
    }

    /* Section Styling */
    section {
      padding: 100px 0;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: clamp(2rem, 4vw, 3rem);
      color: var(--forest-deep);
      position: relative;
      padding-bottom: 16px;
      margin-bottom: 16px;
    }

    .section-title h2::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 2px;
      background-color: var(--gold);
    }

    .section-title p {
      color: #666;
      font-size: 1.1rem;
      max-width: 600px;
      margin: 0 auto;
    }

    /* How It Works (Simple 1-2-3 Grid) */
    .how-it-works-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      margin-top: 40px;
    }

    .how-card {
      background: var(--white);
      border-radius: 12px;
      padding: 40px 30px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      border: 1px solid rgba(27, 58, 43, 0.05);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .how-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 40px rgba(27, 58, 43, 0.08);
      border-color: var(--gold);
    }

    .how-num {
      position: absolute;
      top: 15px;
      right: 25px;
      font-size: 4.5rem;
      font-weight: 700;
      color: rgba(201, 162, 75, 0.1);
      line-height: 1;
      font-family: var(--font-display);
    }

    .how-icon {
      font-size: 2.2rem;
      color: var(--gold);
      margin-bottom: 24px;
      display: inline-block;
    }

    .how-card h3 {
      font-size: 1.5rem;
      margin-bottom: 12px;
      color: var(--forest-deep);
    }

    .how-card p {
      color: #555;
      font-size: 0.98rem;
    }

    .how-card ul {
      margin-top: 15px;
      list-style: none;
      padding-left: 0;
    }

    .how-card ul li {
      margin-bottom: 8px;
      font-size: 0.95rem;
      color: #444;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .how-card ul li i {
      color: var(--gold);
      font-size: 0.8rem;
    }

    /* Membership Levels (Tier Cards) */
    .levels-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 30px;
      align-items: stretch;
    }

    .level-card {
      background: var(--white);
      border-radius: 16px;
      padding: 50px 35px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.03);
      border: 1px solid rgba(27, 58, 43, 0.06);
      display: flex;
      flex-direction: column;
      position: relative;
      transition: all 0.3s ease;
    }

    .level-card.featured {
      border: 2px solid var(--gold);
      box-shadow: 0 20px 50px rgba(27, 58, 43, 0.1);
      transform: scale(1.03);
    }

    @media (max-width: 991px) {
      .level-card.featured {
        transform: scale(1);
      }
    }

    .featured-badge {
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--gold);
      color: var(--forest-deep);
      padding: 6px 20px;
      border-radius: 50px;
      font-size: 0.78rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.1em;
    }

    .level-card:hover:not(.featured) {
      border-color: rgba(201, 162, 75, 0.5);
      transform: translateY(-5px);
    }

    .level-header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 25px;
      border-bottom: 1px solid #f0edeb;
    }

    .level-header h3 {
      font-size: 2rem;
      color: var(--forest-deep);
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 5px;
    }

    .level-price {
      font-size: 1.1rem;
      font-weight: 500;
      color: var(--gold);
      font-family: var(--font-sans);
    }

    .level-desc {
      font-size: 0.95rem;
      color: #666;
      margin-top: 10px;
      font-style: italic;
    }

    .level-benefits {
      list-style: none;
      margin-bottom: 40px;
      flex-grow: 1;
    }

    .level-benefits li {
      margin-bottom: 14px;
      font-size: 0.96rem;
      color: #444;
      display: flex;
      align-items: flex-start;
      gap: 12px;
    }

    .level-benefits li i {
      color: var(--gold);
      font-size: 1.1rem;
      margin-top: 3px;
      flex-shrink: 0;
    }

    .level-btn {
      display: block;
      text-align: center;
      padding: 15px 30px;
      background: var(--forest);
      color: var(--cream);
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      border: 1px solid var(--forest);
    }

    .level-btn:hover {
      background: transparent;
      color: var(--forest);
    }

    .level-card.featured .level-btn {
      background: var(--gold);
      color: var(--forest-deep);
      border-color: var(--gold);
    }

    .level-card.featured .level-btn:hover {
      background: transparent;
      color: var(--gold);
    }

    /* Impact Matters */
    .impact-bg {
      background: linear-gradient(rgba(13, 31, 22, 0.92), rgba(13, 31, 22, 0.95)), url('<?php echo $baseLink("img/pillar_impact.png"); ?>') no-repeat center center / cover;
      color: var(--cream);
    }

    .impact-bg h2 {
      color: var(--gold);
    }

    .impact-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }

    .impact-card {
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      padding: 30px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .impact-card:hover {
      background: rgba(255, 255, 255, 0.08);
      border-color: var(--gold);
      transform: translateY(-5px);
    }

    .impact-card .icon {
      font-size: 2.5rem;
      margin-bottom: 20px;
      display: block;
    }

    .impact-card h3 {
      font-size: 1.4rem;
      color: var(--gold-light);
      margin-bottom: 12px;
    }

    .impact-card p {
      font-size: 0.95rem;
      opacity: 0.85;
      line-height: 1.6;
    }

    .impact-statement {
      text-align: center;
      margin-top: 50px;
      font-family: var(--font-display);
      font-size: 1.8rem;
      font-style: italic;
      color: var(--cream);
    }

    /* Dashboard Preview Simulation */
    .dashboard-preview {
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.04);
      border: 1px solid rgba(27, 58, 43, 0.08);
      max-width: 850px;
      margin: 0 auto;
      overflow: hidden;
    }

    .dash-header {
      background: var(--forest-deep);
      padding: 24px 35px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      border-bottom: 2px solid var(--gold);
    }

    .dash-welcome h4 {
      color: var(--cream);
      font-size: 1.4rem;
    }

    .dash-welcome p {
      color: rgba(246, 242, 233, 0.7);
      font-size: 0.88rem;
    }

    .dash-badge {
      background: var(--gold-glow);
      border: 1px solid var(--gold);
      color: var(--gold-light);
      padding: 6px 16px;
      border-radius: 50px;
      font-size: 0.85rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .dash-body {
      padding: 35px;
    }

    .dash-section-title {
      font-size: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: #888;
      margin-bottom: 20px;
      font-weight: 600;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
    }

    .dash-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }

    @media (max-width: 767px) {
      .dash-grid {
        grid-template-columns: 1fr;
      }
    }

    .dash-panel {
      background: var(--cream-warm);
      border-radius: 10px;
      padding: 24px;
      border: 1px solid rgba(27, 58, 43, 0.03);
    }

    .dash-list {
      list-style: none;
    }

    .dash-list li {
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 0.95rem;
      color: #333;
    }

    .dash-list li i.check {
      color: var(--forest);
    }

    .dash-list li i.icon-stat {
      color: var(--gold);
      width: 20px;
      text-align: center;
    }

    /* Signup Form Card */
    .signup-section {
      background-color: var(--cream);
    }

    .signup-container {
      max-width: 650px;
      margin: 0 auto;
      background: var(--white);
      border-radius: 16px;
      padding: 50px;
      box-shadow: 0 15px 50px rgba(0,0,0,0.03);
      border: 1px solid rgba(27, 58, 43, 0.06);
    }

    @media (max-width: 575px) {
      .signup-container {
        padding: 30px 20px;
      }
    }

    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    @media (max-width: 575px) {
      .form-row {
        flex-direction: column;
        gap: 0;
      }
    }

    .form-group {
      flex: 1;
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 0.85rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-weight: 600;
      margin-bottom: 8px;
      color: var(--forest-deep);
    }

    .form-control {
      width: 100%;
      padding: 14px 16px;
      border: 1px solid #dcd7d2;
      border-radius: 8px;
      font-family: inherit;
      font-size: 0.98rem;
      background: #fafaf9;
      transition: all 0.3s;
      outline: none;
    }

    .form-control:focus {
      border-color: var(--gold);
      background: var(--white);
      box-shadow: 0 0 0 3px rgba(201, 162, 75, 0.12);
    }

    .form-group-checkbox {
      display: flex;
      align-items: flex-start;
      gap: 12px;
      margin-top: 10px;
      margin-bottom: 24px;
    }

    .form-group-checkbox input[type="checkbox"] {
      margin-top: 5px;
      accent-color: var(--forest);
      width: 16px;
      height: 16px;
    }

    .form-group-checkbox label {
      font-size: 0.9rem;
      color: #555;
      line-height: 1.4;
      cursor: pointer;
    }

    .submit-btn {
      width: 100%;
      padding: 16px;
      background: var(--forest);
      color: var(--cream);
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .submit-btn:hover {
      background: var(--forest-deep);
    }

    .submit-btn:disabled {
      background: #c3cbca;
      cursor: not-allowed;
    }

    .submit-spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 0.8s linear infinite;
    }

    /* Modal / Success state */
    .success-panel {
      text-align: center;
      padding: 20px 0;
    }

    .success-panel i {
      font-size: 3.5rem;
      color: var(--forest);
      margin-bottom: 24px;
    }

    .success-panel h3 {
      font-size: 1.8rem;
      margin-bottom: 12px;
    }

    .success-panel p {
      color: #555;
      margin-bottom: 35px;
    }

    /* Animations */
    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    /* Footer */
    footer {
      background: var(--forest-deep);
      padding: 80px 0 40px;
      color: var(--cream);
      font-size: 0.95rem;
      border-top: 1px solid rgba(246, 242, 233, 0.08);
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 50px;
      margin-bottom: 60px;
    }

    @media (max-width: 767px) {
      .footer-grid {
        grid-template-columns: 1fr;
        gap: 30px;
      }
    }

    .footer-brand {
      font-family: var(--font-display);
      font-size: 1.6rem;
      color: var(--cream);
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 18px;
    }

    .footer-brand img {
      height: 40px;
      width: auto;
    }

    .footer-note {
      color: rgba(246, 242, 233, 0.7);
      max-width: 450px;
      line-height: 1.7;
    }

    .footer-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .footer-links a {
      color: var(--cream);
      text-decoration: none;
      opacity: 0.8;
      transition: opacity 0.2s;
    }

    .footer-links a:hover {
      opacity: 1;
      color: var(--gold);
    }

    .footer-bottom {
      border-top: 1px solid rgba(246, 242, 233, 0.08);
      padding-top: 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 15px;
      color: rgba(246, 242, 233, 0.5);
      font-size: 0.88rem;
    }

    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease;
    }

    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
  </style>
</head>
<body>

  <!-- Include Header -->
  <?php include __DIR__ . '/header.php'; ?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="wrap">
      <h1>Virunga Collective Membership</h1>
      <p class="tagline">Travel. Belong. Make an Impact.</p>
      <p class="intro">
        Join a global community of travelers connected by extraordinary experiences, conservation, and positive impact in the Virunga region.
      </p>
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
            <li><i class="fas fa-check"></i> Virunga Ecotours</li>
            <li><i class="fas fa-check"></i> Virunga Homestay</li>
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
  <section style="background-color: #f3eee3;">
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
          <span class="icon">🌱</span>
          <h3>Conservation</h3>
          <p>Funding biodiversity preservation, tree planting, and environmental monitoring in the Virunga region.</p>
        </div>

        <div class="impact-card reveal">
          <span class="icon">👥</span>
          <h3>Local Development</h3>
          <p>Supporting clean water, local cooperative projects, and directly funding community-led initiatives.</p>
        </div>

        <div class="impact-card reveal">
          <span class="icon">🎓</span>
          <h3>Hospitality Education</h3>
          <p>Empowering local youths with world-class hospitality training through the dedicated Virunga Academy.</p>
        </div>

        <div class="impact-card reveal">
          <span class="icon">🏡</span>
          <h3>Local Enterprises</h3>
          <p>Investing in micro-businesses, honey producers, and local crafts to generate sustainable livelihoods.</p>
        </div>
      </div>

      <p class="impact-statement reveal">
        "Your membership is not just about benefits. It is about belonging to a movement."
      </p>
    </div>
  </section>

  <!-- Member Dashboard Simulator Preview -->
  <section>
    <div class="wrap">
      <div class="section-title reveal">
        <h2>Member Portal Preview</h2>
        <p>A look inside your personal member dashboard</p>
      </div>

      <div class="dashboard-preview reveal">
        <div class="dash-header">
          <div class="dash-welcome">
            <h4>Welcome back, Daniel</h4>
            <p>Member since 2024 • ID: VC-88402</p>
          </div>
          <span class="dash-badge">Ambassador Level</span>
        </div>
        <div class="dash-body">
          <div class="dash-grid">
            <div class="dash-panel">
              <h5 class="dash-section-title">Your Journeys</h5>
              <ul class="dash-list">
                <li><i class="fas fa-plane-departure icon-stat"></i> 3 completed experiences</li>
                <li><i class="fas fa-campground icon-stat"></i> Virunga Homestay (2 Stays)</li>
                <li><i class="fas fa-walking icon-stat"></i> Community Trekking (1 Experience)</li>
              </ul>
            </div>
            
            <div class="dash-panel">
              <h5 class="dash-section-title">Your Impact Footprint</h5>
              <ul class="dash-list">
                <li><i class="fas fa-seedling icon-stat" style="color: #2eb8a0;"></i> Conservation initiatives supported</li>
                <li><i class="fas fa-users icon-stat" style="color: #2eb8a0;"></i> Local jobs contributed to</li>
                <li><i class="fas fa-graduation-cap icon-stat" style="color: #2eb8a0;"></i> Local Academy training funded</li>
              </ul>
            </div>
          </div>
          
          <div class="dash-panel" style="margin-top: 30px;">
            <h5 class="dash-section-title">Available Privileges</h5>
            <ul class="dash-list" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
              <li><i class="fas fa-check check"></i> 10% Member rate for stays</li>
              <li><i class="fas fa-check check"></i> Priority booking line</li>
              <li><i class="fas fa-check check"></i> Custom itinerary builder</li>
              <li><i class="fas fa-check check"></i> Exclusive update newsletters</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Signup Form Section -->
  <section class="signup-section" id="join-section">
    <div class="wrap">
      <div class="section-title reveal">
        <h2>Become Part of Virunga Collective</h2>
        <p>Join travelers from around the world who believe tourism can create a positive legacy.</p>
      </div>

      <div class="signup-container reveal" id="signupFormContainer">
        <form id="membershipForm" method="POST">
          <div class="form-row">
            <div class="form-group">
              <label for="firstName">First Name *</label>
              <input type="text" id="firstName" name="firstName" class="form-control" placeholder="First Name" required>
            </div>
            <div class="form-group">
              <label for="lastName">Last Name *</label>
              <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Last Name" required>
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="yourname@domain.com" required>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="phone">Phone / WhatsApp</label>
              <input type="tel" id="phone" name="phone" class="form-control" placeholder="+250 780 000 000">
            </div>
            <div class="form-group">
              <label for="interest">Preferred Level *</label>
              <select id="interest" name="interest" class="form-control" required style="height: 52px;">
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

  <!-- Shared Footer -->
  <footer>
    <div class="wrap">
      <div class="footer-grid">
        <div>
          <p class="footer-brand">
            <img src="<?php echo $baseLink('img/logo.png'); ?>" alt="Virunga Collective Logo">
            Virunga Collective
          </p>
          <p class="footer-note">
            Boutique stays, curated journeys, and community impact in the Virunga
            region of Rwanda. Formerly Virunga Ecotours and Virunga Homestay.
          </p>
        </div>
        <ul class="footer-links">
          <li><a href="<?php echo $baseLink('homestays'); ?>">Stays</a></li>
          <li><a href="<?php echo $baseLink('experiences'); ?>">Journeys</a></li>
          <li><a href="<?php echo $baseLink('ecotours/community'); ?>">Community</a></li>
          <li><a href="<?php echo $baseLink('about'); ?>">Our Story</a></li>
        </ul>
      </div>
      <div class="footer-bottom">
        <span>© 2026 Virunga Collective. All rights reserved.</span>
        <span>Musanze, Rwanda</span>
      </div>
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Scroll reveal observer
      const revealEls = document.querySelectorAll(".reveal");
      if (revealEls.length) {
        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("visible");
              }
            });
          },
          { threshold: 0.15 }
        );
        revealEls.forEach((el) => observer.observe(el));
      }

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
  .recaptcha-modal-btn:hover {
    background: #e4c97a;
    transform: translateY(-2px);
  }
  </style>
</body>
</html>
