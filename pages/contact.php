<?php
require_once __DIR__ . '/../config/recaptcha.php';
$pageTitle = 'Contact Us — Virunga Collective';
$pageDescription = 'Get in touch with Virunga Collective. We reply within 24 hours.';
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
      --forest-deep: #122a1f;
      --gold: #c9a24b;
      --cream: #f6f2e9;
      --charcoal: #1f2620;
      --white: #ffffff;
      --danger: #c62828;
      --font-display: "Cormorant Garamond", serif;
      --font-body: "Jost", sans-serif;
      --ease-out: cubic-bezier(0.22, 0.61, 0.36, 1);
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    html, body {
      height: 100%;
      overflow-x: hidden;
    }
    body {
      font-family: var(--font-body);
      color: var(--charcoal);
      background: var(--cream);
      line-height: 1.6;
    }
    .wrap { max-width: 1280px; margin: 0 auto; padding: 0 6vw; }
    

    
    /* Hero Section */
    .page-hero {
      position: relative;
      padding-top: 180px;
      padding-bottom: 100px;
      background: var(--forest-deep);
      color: var(--cream);
      overflow: hidden;
    }
    .page-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url('<?php echo htmlspecialchars($baseLink('img/contact.JPG')); ?>') center/cover no-repeat;
      opacity: 0.7;
      transform: scale(1.05);
      transition: transform 1s ease;
    }
    .page-hero::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(180deg, rgba(18, 42, 31, 0.3) 0%, rgba(18, 42, 31, 0.7) 100%);
    }
    .page-hero .wrap {
      position: relative;
      z-index: 2;
    }
    .page-hero h1 {
      font-family: var(--font-display);
      font-size: clamp(2.5rem, 6vw, 4rem);
      margin-bottom: 16px;
      font-weight: 400;
    }
    .page-hero p { 
      font-size: 1.15rem; 
      opacity: 0.9; 
      max-width: 600px;
      font-weight: 300;
    }
    
    /* Contact Section */
    .contact-section {
      padding: 120px 6vw;
      max-width: 1280px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1.5fr;
      gap: 100px;
      align-items: start;
      background: var(--cream);
    }
    .contact-left {
      position: sticky;
      top: 100px;
    }
    .contact-help {
      margin-bottom: 60px;
    }
    .contact-help .curated-eyebrow,
    .contact-reach .curated-eyebrow {
      font-size: 0.75rem;
      letter-spacing: 0.25em;
      text-transform: uppercase;
      color: var(--gold);
      margin-bottom: 30px;
      font-weight: 600;
      display: block;
    }
    .contact-help ul.exp-row__list {
      list-style: none;
      padding: 0;
    }
    .contact-help ul.exp-row__list li {
      padding: 16px 0;
      font-family: var(--font-body);
      font-size: 1rem;
      font-weight: 300;
      color: var(--charcoal);
      border-top: 1px solid rgba(0, 0, 0, 0.08);
      display: flex;
      align-items: center;
    }
    .contact-help ul.exp-row__list li:last-child {
      border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }
    .contact-help ul.exp-row__list li::before {
      content: "—";
      margin-right: 15px;
      color: var(--gold);
      font-weight: 700;
    }
    .contact-reach__info {
      font-family: var(--font-body);
      font-size: 1.15rem;
      line-height: 1.8;
      color: var(--charcoal);
      font-weight: 300;
      margin-top: 0;
    }
    .contact-reach__info a {
      color: inherit;
      text-decoration: none;
      transition: color 0.3s ease;
      display: block;
      margin-bottom: 5px;
    }
    .contact-reach__info a:hover {
      color: var(--gold);
    }
    
    /* Form */
    .form-minimal-grid {
      display: flex;
      flex-direction: column;
      gap: 40px;
    }
    .form-minimal-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }
    .form-minimal-group {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .form-minimal-group label {
      font-size: 0.7rem;
      letter-spacing: 0.2em;
      color: #888;
      font-weight: 600;
      text-transform: uppercase;
    }
    .form-minimal-group input,
    .form-minimal-group select,
    .form-minimal-group textarea {
      background: transparent;
      border: none;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      padding: 12px 0;
      font-family: var(--font-body);
      font-size: 1rem;
      color: var(--charcoal);
      font-weight: 300;

      transition: border-color 0.3s ease;
      width: 100%;
    }
    .form-minimal-group select {
      cursor: pointer;
      appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23888' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right center;
    }
    .form-minimal-group input:focus,
    .form-minimal-group select:focus,
    .form-minimal-group textarea:focus {
      outline: none;
      border-bottom-color: var(--gold);
    }
    .form-minimal-group textarea {
      resize: none;
      min-height: 100px;
    }
    .error-msg {
      font-size: 0.75rem;
      color: var(--danger);
      margin-top: 5px;
      display: none;
    }
    .form-minimal-group.has-error input,
    .form-minimal-group.has-error select,
    .form-minimal-group.has-error textarea {
      border-bottom-color: var(--danger);
    }
    .form-minimal-group.has-error .error-msg {
      display: block;
    }
    .form-title {
      font-family: var(--font-display);
      font-size: 2rem;
      margin-bottom: 10px;
      color: var(--charcoal);
      font-weight: 400;
    }
    .form-title em {
      font-style: italic;
      color: var(--gold);
    }
    .form-sub {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 40px;
      font-weight: 300;
    }
    
    /* Checkbox */
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      user-select: none;
    }
    .checkbox-group input[type="checkbox"] {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 1.5px solid rgba(0, 0, 0, 0.2);

      background: transparent;
      cursor: pointer;
      position: relative;
      transition: all 0.3s ease;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .checkbox-group input[type="checkbox"]:checked {
      background: var(--gold);
      border-color: var(--gold);
    }
    .checkbox-group input[type="checkbox"]:checked::after {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      font-size: 10px;
      color: white;
      position: absolute;
    }
    .checkbox-group span {
      font-size: 0.85rem;
      color: #666;
      line-height: 1.4;
      text-transform: none;
      letter-spacing: 0;
    }
    .checkbox-group span a {
      color: var(--gold);
      text-decoration: none;
      font-weight: 500;
    }
    .checkbox-group span a:hover {
      text-decoration: underline;
    }
    
    /* Success */
    .form-success-refined {
      text-align: center;
      padding: 40px 0;
      animation: fadeIn 0.6s ease-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .success-icon-wrap {
      width: 80px;
      height: 80px;
      background: rgba(201, 162, 75, 0.1);
      color: var(--gold);

      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 30px;
      box-shadow: 0 10px 20px rgba(201, 162, 75, 0.1);
    }
    .success-actions {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 40px;
    }
    .btn-success-action {
      width: 100%;
      justify-content: center;
      gap: 12px;
      font-size: 0.8rem;
      padding: 18px 30px;
    }
    .btn-success-action i {
      font-size: 1.1rem;
    }
    .btn-success-action.btn-accent {
      background: var(--gold);
    }
    .btn-success-action.btn-accent:hover {
      background: var(--forest-deep);
    }
    @media (min-width: 600px) {
      .success-actions {
        display: grid;
        grid-template-columns: 1fr;
      }
    }
    
    /* Button */
    .btn-journey {
      display: inline-flex;
      background: var(--forest-deep);
      color: white;
      padding: 20px 48px;
      font-size: 0.85rem;
      letter-spacing: 0.3em;
      text-decoration: none;
      font-weight: 600;
      border: none;
      cursor: pointer;
      transition: all 0.3s var(--ease-out);
      text-transform: uppercase;
      position: relative;
      overflow: hidden;
    }
    .btn-submit-contact {
      width: 100%;
      justify-content: center;
      background: var(--forest-deep);
    }
    .btn-journey:hover {
      background: var(--gold) !important;
      transform: translateY(-3px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .btn-journey:disabled {
      background: #444 !important;
      cursor: not-allowed;
      opacity: 0.8;
      transform: none;
    }
    .btn-loading {
      pointer-events: none;
    }
    
    /* Map Section */
    .map-section {
      padding: 0 6vw 120px;
      max-width: 1280px;
      margin: 0 auto;
    }
    .map-header {
      display: flex;
      align-items: flex-end;
      justify-content: space-between;
      margin-bottom: 40px;
      gap: 24px;
      flex-wrap: wrap;
    }
    .map-header h2 {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3vw, 2.5rem);
      font-weight: 400;
      color: var(--charcoal);
    }
    .map-header h2 em {
      font-style: italic;
      color: var(--gold);
    }
    .directions-link {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: var(--gold);
      font-size: 0.9rem;
      font-weight: 500;
      letter-spacing: 0.06em;
      white-space: nowrap;
      transition: gap 0.3s ease, color 0.3s ease;
    }
    .directions-link:hover {
      gap: 18px;
      color: var(--forest-deep);
    }
    .map-wrapper {

      overflow: hidden;
      border: 1px solid rgba(0, 0, 0, 0.1);
      height: clamp(350px, 60vh, 520px);
      position: relative;
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.08);
    }
    .map-wrapper iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
    
    /* FAQ Section */
    .faq-section {
      background: var(--forest-deep);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding: 120px 6vw;
    }
    .faq-inner {
      max-width: 1280px;
      margin: 0 auto;
    }
    .faq-header {
      text-align: center;
      margin-bottom: 64px;
    }
    .faq-header h2 {
      font-family: var(--font-display);
      font-size: clamp(2rem, 3.5vw, 3rem);
      font-weight: 400;
      color: var(--cream);
    }
    .faq-header h2 em {
      font-style: italic;
      color: var(--gold);
    }
    .faq-header p {
      margin-top: 16px;
      color: rgba(246, 242, 233, 0.7);
      font-weight: 300;
      font-size: 1rem;
    }
    .faq-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(min(100%, 440px), 1fr));
      gap: 24px;
    }
    .faq-item {
      background: var(--forest);
      border: 1px solid rgba(255, 255, 255, 0.1);

      overflow: hidden;
      transition: border-color 0.3s ease;
    }
    .faq-item:hover {
      border-color: rgba(201, 162, 75, 0.3);
    }
    .faq-q {
      width: 100%;
      background: none;
      border: none;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      padding: 32px;
      color: var(--cream);
      font-family: var(--font-body);
      font-size: 1rem;
      font-weight: 400;
      text-align: left;
      cursor: pointer;
      transition: color 0.3s ease;
    }
    .faq-q:hover {
      color: var(--gold);
    }
    .faq-icon {
      width: 26px;
      height: 26px;
      flex-shrink: 0;
      border: 1px solid rgba(255, 255, 255, 0.15);

      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      line-height: 1;
      color: var(--gold);
      transition: background 0.3s ease, border-color 0.3s ease, transform 0.3s ease;
    }
    .faq-item.open .faq-icon {
      background: var(--gold);
      border-color: var(--gold);
      transform: rotate(45deg);
    }
    .faq-a {
      overflow: hidden;
      max-height: 0;
      transition: max-height 0.4s var(--ease-out), padding 0.4s var(--ease-out);
    }
    .faq-item.open .faq-a {
      max-height: 260px;
    }
    .faq-a p {
      padding: 0 32px 32px;
      font-size: 0.95rem;
      font-weight: 300;
      color: rgba(246, 242, 233, 0.8);
      line-height: 1.8;
    }
    
    /* CTA Strip */
    .cta-strip {
      padding: 80px 6vw;
      background: linear-gradient(135deg, var(--forest) 0%, var(--forest-deep) 100%);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 40px;
      max-width: 100%;
    }
    .cta-strip-text h3 {
      font-family: var(--font-display);
      font-size: clamp(1.4rem, 2.5vw, 2rem);
      font-weight: 400;
      color: var(--cream);
    }
    .cta-strip-text h3 em {
      font-style: italic;
      color: var(--gold);
    }
    .cta-strip-text p {
      font-size: 0.95rem;
      color: rgba(246, 242, 233, 0.7);
      margin-top: 8px;
      font-weight: 300;
    }
    .cta-strip-actions {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
    }
    .btn-primary-sm {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      background: var(--gold);
      color: var(--forest-deep);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 600;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 16px 32px;

      transition: background 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
    }
    .btn-primary-sm:hover {
      background: var(--cream);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .btn-ghost-sm {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      background: transparent;
      color: var(--cream);
      text-decoration: none;
      font-size: 0.85rem;
      font-weight: 500;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 16px 32px;

      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: border-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
    }
    .btn-ghost-sm:hover {
      border-color: var(--gold);
      color: var(--gold);
      transform: translateY(-2px);
    }
    
    /* Footer */
    footer { 
      background: var(--forest-deep); 
      color: var(--cream); 
      padding: 80px 6vw 40px;
    }
    .footer-grid { 
      display: flex; 
      justify-content: space-between; 
      flex-wrap: wrap; 
      gap: 40px; 
      margin-bottom: 40px; 
    }
    .footer-brand { 
      font-family: var(--font-display); 
      color: var(--cream); 
      font-size: 1.1rem; 
      margin-bottom: 8px; 
      display: flex; 
      align-items: center; 
      gap: 8px; 
    }
    .footer-brand img { height: 32px; }
    .footer-note { 
      font-size: 0.85rem; 
      opacity: 0.7; 
      max-width: 340px; 
    }
    .footer-links { 
      display: flex; 
      gap: 28px; 
      list-style: none; 
      flex-wrap: wrap; 
    }
    .footer-links a { 
      color: var(--cream); 
      text-decoration: none; 
      opacity: 0.85; 
      transition: opacity 0.3s ease;
    }
    .footer-links a:hover { opacity: 1; }
    .footer-bottom { 
      border-top: 1px solid rgba(255, 255, 255, 0.1); 
      padding-top: 24px; 
      display: flex; 
      justify-content: space-between; 
      flex-wrap: wrap; 
      gap: 12px; 
      font-size: 0.8rem; 
      opacity: 0.65; 
    }
    
    /* Reveal animations */
    .reveal {
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 0.8s ease, transform 0.8s ease;
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .reveal-delay-2 {
      transition-delay: 0.2s;
    }
    
    /* Responsive */
    @media (max-width: 960px) {
      .contact-section {
        grid-template-columns: 1fr;
        gap: 60px;
      }
      .contact-left {
        position: static;
      }
    }
    @media (max-width: 768px) {
      .form-minimal-row {
        grid-template-columns: 1fr;
      }

      .page-hero { padding-top: 140px; padding-bottom: 80px; }
      .contact-section { padding: 80px 24px; }
      .map-section { padding: 0 24px 80px; }
      .faq-section { padding: 80px 24px; }
      .cta-strip { padding: 60px 24px; }
      footer { padding: 60px 24px 30px; }
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/header.php'; ?>

  <section class="page-hero">
    <div class="wrap">
      <h1>Let's start a conversation</h1>
      <p>We'd love to hear from you. Whether you're planning a trip, have questions, or want to partner with us — we're here.</p>
    </div>
  </section>

  <section class="contact-section">
    <div class="contact-left reveal">
      <div class="contact-help">
        <p class="curated-eyebrow">WE HELP WITH</p>
        <ul class="exp-row__list">
          <li>Accommodation</li>
          <li>Cultural experiences</li>
          <li>Trekking guidance support</li>
          <li>Local transfers if needed</li>
        </ul>
      </div>
      <div class="contact-reach" style="margin-top: 60px;">
        <p class="curated-eyebrow">REACH US DIRECTLY</p>
        <p class="contact-reach__info">
          <a href="mailto:info@virungacollective.com">info@virungacollective.com</a>
          <a href="tel:+250784513435">+250 784 513 435</a>
        </p>
      </div>
    </div>

    <div class="contact-right">
      <div id="formSuccess" class="form-success-refined" style="display: none;">
        <div class="success-icon-wrap">
          <i class="fa-solid fa-paper-plane"></i>
        </div>
        <h3 class="form-title">Message <em>Sent!</em></h3>
        <p class="form-sub">Your message was sent. We will get back to you as soon as possible.</p>
        
        <div class="success-actions">
          <a href="<?php echo $baseLink('home'); ?>" class="btn-journey btn-success-action">
            <i class="fa-solid fa-house"></i> BACK TO HOME
          </a>
          <a href="<?php echo $baseLink('homestays'); ?>" class="btn-journey btn-success-action btn-accent">
            <i class="fa-solid fa-calendar-check"></i> BOOK OUR STAY
          </a>
          <a href="<?php echo $baseLink('experiences'); ?>" class="btn-journey btn-success-action">
            <i class="fa-solid fa-mountain-sun"></i> VIEW EXPERIENCES
          </a>
        </div>
      </div>

      <div id="contactFormContainer">
        <h3 class="form-title">Send us a <em>message</em></h3>
        <p class="form-sub">We reply within 24 hours.</p>

        <form id="contactForm" novalidate>
          <div class="form-minimal-grid">
            <div class="form-minimal-row">
              <div class="form-minimal-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" placeholder=" " required>
                <span class="error-msg">Please enter your first name</span>
              </div>

              <div class="form-minimal-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" placeholder=" " required>
                <span class="error-msg">Please enter your last name</span>
              </div>
            </div>

            <div class="form-minimal-row">
              <div class="form-minimal-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder=" " required>
                <span class="error-msg">Enter a valid email</span>
              </div>

              <div class="form-minimal-group">
                <label for="phone">Phone (optional)</label>
                <input type="tel" id="phone" name="phone" placeholder=" ">
              </div>
            </div>

            <div class="form-minimal-group">
              <label for="subject">Subject</label>
              <select id="subject" name="subject" required>
                <option value="" disabled selected hidden></option>
                <option>General Inquiry</option>
                <option>Stays / Accommodation</option>
                <option>Journeys / Trekking</option>
                <option>Community Impact</option>
                <option>Partnerships</option>
                <option>Other</option>
              </select>
              <span class="error-msg">Please select a subject</span>
            </div>

            <div class="form-minimal-group">
              <label for="message">Your Message</label>
              <textarea id="message" name="message" rows="4" placeholder=" " required></textarea>
              <span class="error-msg">Please write a message</span>
            </div>

            <div class="form-minimal-group" style="border:none; margin-bottom: 24px;">
              <div class="g-recaptcha" data-sitekey="<?php echo RECAPTCHA_SITE_KEY; ?>"></div>
            </div>

            <div class="form-minimal-actions">
              <button type="submit" class="btn-journey btn-submit-contact">
                SEND MESSAGE
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <div class="map-section">
    <div class="map-header reveal">
      <div>
        <p class="section-label" style="font-size: 0.75rem; letter-spacing: 0.25em; text-transform: uppercase; color: var(--gold); margin-bottom: 8px;">
          Find Us
        </p>
        <h2>Our <em>location</em></h2>
      </div>
      <a
        href="https://maps.google.com/?q=Musanze,Rwanda"
        target="_blank"
        class="directions-link"
        rel="noreferrer"
      >
        Get Directions <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <div class="map-wrapper reveal reveal-delay-2" id="location">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.4582870349604!2d29.63036257356573!3d-1.4960571358884254!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca74680751f5d%3A0x8181ab39eecaf265!2sVirunga%20Homestay%20Experience%20-%20Where%20Virunga%20Becomes%20Personal.!5e0!3m2!1sen!2srw!4v1778424750665!5m2!1sen!2srw" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>

  <section class="faq-section">
    <div class="faq-inner">
      <div class="faq-header reveal">
        <p class="section-label" style="font-size: 0.75rem; letter-spacing: 0.25em; text-transform: uppercase; color: var(--gold); margin-bottom: 8px;">
          Quick Answers
        </p>
        <h2>Frequently asked <em>questions</em></h2>
        <p>Can't find your answer? Just send us a message above.</p>
      </div>

      <div class="faq-grid">
        <div class="faq-item reveal reveal-delay-1">
          <button class="faq-q">
            What's the best way to book a room?
            <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
          </button>
          <div class="faq-a">
            <p>
              The fastest way is to use our online booking form or send us a message via WhatsApp. You can also email us directly.
            </p>
          </div>
        </div>
        <div class="faq-item reveal reveal-delay-2">
          <button class="faq-q">
            Do you offer airport transfers?
            <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
          </button>
          <div class="faq-a">
            <p>
              Yes, we can arrange airport transfers from Kigali International Airport to Musanze. Please mention this in your booking request.
            </p>
          </div>
        </div>
        <div class="faq-item reveal reveal-delay-1">
          <button class="faq-q">
            Can you help organize gorilla trekking?
            <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
          </button>
          <div class="faq-a">
            <p>
              Absolutely! We have partnerships with local guides and can help you arrange gorilla trekking permits and experiences.
            </p>
          </div>
        </div>
        <div class="faq-item reveal reveal-delay-2">
          <button class="faq-q">
            What payment methods do you accept?
            <span class="faq-icon"><i class="fa-solid fa-plus"></i></span>
          </button>
          <div class="faq-a">
            <p>
              We accept bank transfers, major credit cards, and mobile money payments like M-Pesa.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="cta-strip">
    <div class="cta-strip-text reveal">
      <h3>Ready for your <em>next getaway?</em></h3>
      <p>Explore our rooms and packages - the perfect escape awaits.</p>
    </div>
    <div class="cta-strip-actions reveal reveal-delay-2">
      <a href="<?php echo $baseLink('homestays'); ?>" class="btn-primary-sm">View Rooms <i class="fas fa-arrow-right"></i></a>
      <a href="<?php echo $baseLink('about-us'); ?>" class="btn-ghost-sm">Our Story</a>
    </div>
  </div>

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
          <li><a href="<?php echo $baseLink('home'); ?>#story">Our Story</a></li>
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
      // Scroll reveal
      const revealEls = document.querySelectorAll(".reveal");
      if (revealEls.length) {
        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add("visible");
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.12, rootMargin: "0px 0px -40px 0px" },
        );
        revealEls.forEach((el) => observer.observe(el));
      }

      // Form validation + success swap
      const form = document.getElementById("contactForm");
      const formSuccess = document.getElementById("formSuccess");

      const validate = () => {
        let ok = true;
        const required = [
          { id: "fname", check: (v) => v.trim().length > 1 },
          { id: "lname", check: (v) => v.trim().length > 1 },
          { id: "email", check: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
          { id: "subject", check: (v) => v !== "" },
          { id: "message", check: (v) => v.trim().length > 5 },
        ];
        required.forEach(({ id, check }) => {
          const el = document.getElementById(id);
          if (!el) return;
          
          const grp = el.closest(".form-minimal-group");
          
          if (!check(el.value)) {
            grp?.classList.add("has-error");
            ok = false;
          } else {
            grp?.classList.remove("has-error");
          }
        });

        if (!ok) {
            const firstError = document.querySelector(".has-error");
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        return ok;
      };

      if (form) {
        ["fname", "lname", "email", "subject", "message"].forEach((id) => {
          const el = document.getElementById(id);
          if (!el) return;
          
          el.addEventListener("change", validate);
          el.addEventListener("blur", validate);
          
          if (el.type !== "checkbox") {
              el.addEventListener("input", () => {
                const grp = el.closest(".form-minimal-group");
                grp?.classList.remove("has-error");
              });
          }
        });

        form.addEventListener("submit", (e) => {
          e.preventDefault();
          if (!validate()) return;

          const btn = form.querySelector(".btn-journey");
          if (btn instanceof HTMLButtonElement) {
            btn.classList.add("btn-loading");
            btn.disabled = true;
            btn.innerHTML = 'SENDING... <i class="fa-solid fa-circle-notch fa-spin"></i>';
          }

          const formData = new FormData(form);
          const name = (formData.get('fname') || '') + ' ' + (formData.get('lname') || '');
          formData.set('name', name.trim());
          formData.set('source', 'Contact Form: ' + (formData.get('subject') || 'General'));

          const apiPath = '<?php echo $baseLink('api/send-contact.php'); ?>';

          fetch(apiPath, {
              method: 'POST',
              body: formData
          }).then(r => r.json()).then(data => {
              if(data.status === 'success') {
                  const formContainer = document.getElementById("contactFormContainer");
                  if (formContainer) formContainer.style.display = "none";
                  
                  if (formSuccess) formSuccess.style.display = "block";
              } else {
                  alert("Error: " + data.message);
                  if (btn instanceof HTMLButtonElement) {
                      btn.classList.remove("btn-loading");
                      btn.disabled = false;
                      btn.innerHTML = 'SEND MESSAGE';
                  }
              }
          }).catch(err => {
              console.error("Submission error:", err);
              alert("Network error. Please try again.");
              if (btn instanceof HTMLButtonElement) {
                  btn.classList.remove("btn-loading");
                  btn.disabled = false;
                  btn.innerHTML = 'SEND MESSAGE';
              }
          });
        });
      }

      // FAQ accordion
      document.querySelectorAll(".faq-q").forEach((btn) => {
        btn.addEventListener("click", () => {
          const item = btn.closest(".faq-item");
          const isOpen = item?.classList.contains("open");
          document.querySelectorAll(".faq-item.open").forEach((i) => i.classList.remove("open"));
          if (!isOpen) item?.classList.add("open");
        });
      });
    });
  </script>
</body>
</html>
