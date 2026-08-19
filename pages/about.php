<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Primary Meta Tags -->
    <title>About Us | Virunga Collective — A Living Travel Ecosystem</title>
    <meta
      name="description"
      content="Discover the story behind Virunga Collective. Learn about our regenerative travel philosophy uniting Virunga Ecotours, Virunga Homestay, and Virunga Community Impact in Rwanda's Virunga region."
    />
    <meta name="keywords" content="about Virunga Collective, Virunga Collective story, regenerative travel Rwanda, Virunga ecotours about, Virunga homestay about, community impact tourism, Rwanda sustainable travel, Virunga region travel">
    <meta name="author" content="Virunga Collective">
    <meta name="robots" content="index, follow">
    
    <!-- Canonical & Alternate Links -->
    <link rel="canonical" href="https://virungacollective.com/about" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://virungacollective.com/about">
    <meta property="og:title" content="About Virunga Collective | Luxury Conservation Travel Ecosystem">
    <meta property="og:description" content="Discover the story behind Virunga Collective: connecting luxury homestays, gorilla trekking expeditions, volcanic coffee, and community conservation in Rwanda.">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Virunga Collective">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://virungacollective.com/about">
    <meta name="twitter:title" content="About Virunga Collective | Luxury Travel Ecosystem">
    <meta name="twitter:description" content="Learn about Virunga Collective's 100-year legacy commitment uniting luxury hospitality, gorilla safaris, volcanic coffee, and community stewardship.">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta name="twitter:site" content="@virungacollective">
    <meta name="twitter:creator" content="@virungacollective">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "AboutPage",
      "name": "About Virunga Collective",
      "url": "https://virungacollective.com/about",
      "description": "Discover the brand story, 100-year vision, and conservation hospitality ecosystem of Virunga Collective in Rwanda.",
      "mainEntity": {
        "@type": "Organization",
        "name": "Virunga Collective",
        "url": "https://virungacollective.com",
        "logo": "https://virungacollective.com/img/icon.png",
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
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Jost:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />

    <style>
      /* =========================================
         DESIGN SYSTEM
         ========================================= */
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
        --sage: #6e8270;
        --sage-light: #8fa891;
        --max-w: 1200px;
        --font-display: "Cormorant Garamond", serif;
        --font-body: "Jost", sans-serif;
        --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
        --ease-smooth: cubic-bezier(0.25, 0.46, 0.45, 0.94);
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      html {
        scroll-behavior: smooth;
      }

      body {
        font-family: var(--font-body);
        color: var(--charcoal);
        background: var(--cream);
        line-height: 1.8;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        overflow-x: hidden;
      }

      img, video {
        max-width: 100%;
        display: block;
      }

      a {
        color: inherit;
        text-decoration: none;
      }

      .wrap {
        max-width: var(--max-w);
        margin: 0 auto;
        padding: 0 28px;
      }

      /* =========================================
         HERO SECTION
         ========================================= */
      .about-hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: var(--forest-deep);
      }

      .about-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url('<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>') center/cover no-repeat;
        opacity: 0.7;
        transform: scale(1.1);
        transition: transform 0.8s ease;
      }

      .about-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
          radial-gradient(ellipse at 30% 80%, rgba(201, 162, 75, 0.05) 0%, transparent 60%),
          radial-gradient(ellipse at 70% 20%, rgba(27, 58, 43, 0.15) 0%, transparent 70%),
          linear-gradient(180deg, rgba(13, 31, 22, 0.25) 0%, rgba(13, 31, 22, 0.5) 100%);
      }

      .hero-particles {
        position: absolute;
        inset: 0;
        overflow: hidden;
        z-index: 1;
      }

      .hero-particle {
        position: absolute;
        width: 2px;
        height: 2px;
        background: var(--gold);
        opacity: 0;
        animation: particleFloat 6s infinite ease-in-out;
      }

      @keyframes particleFloat {
        0%, 100% { opacity: 0; transform: translateY(0) scale(0); }
        20% { opacity: 0.6; transform: translateY(-20px) scale(1); }
        80% { opacity: 0.3; transform: translateY(-100px) scale(0.5); }
      }

      .hero-inner {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--cream);
        padding: 140px 28px 100px;
        max-width: 880px;
        margin: 0 auto;
      }

      .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 10px 28px;
        border: 1px solid rgba(201, 162, 75, 0.3);
        font-size: 0.78rem;
        letter-spacing: 0.28em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 600;
        margin-bottom: 36px;
        backdrop-filter: blur(10px);
        background: rgba(201, 162, 75, 0.06);
      }

      .hero-badge::before,
      .hero-badge::after {
        content: '';
        width: 20px;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--gold));
      }

      .hero-badge::after {
        background: linear-gradient(90deg, var(--gold), transparent);
      }

      .about-hero h1 {
        font-family: var(--font-display);
        font-size: clamp(3rem, 6vw, 5rem);
        font-weight: 600;
        line-height: 1.1;
        margin-bottom: 28px;
        background: linear-gradient(135deg, var(--cream) 0%, var(--gold-light) 50%, var(--cream) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: shimmer 4s ease-in-out infinite;
      }

      @keyframes shimmer {
        0%, 100% { background-position: 0% center; }
        50% { background-position: 200% center; }
      }

      .hero-subtitle {
        font-size: clamp(1.1rem, 2vw, 1.35rem);
        opacity: 0.85;
        max-width: 620px;
        margin: 0 auto 48px;
        font-weight: 300;
        line-height: 1.7;
      }

      .hero-scroll-indicator {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 3;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        color: rgba(246, 242, 233, 0.4);
        font-size: 0.72rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        animation: scrollBounce 2s ease-in-out infinite;
      }

      .hero-scroll-indicator .scroll-line {
        width: 1px;
        height: 40px;
        background: linear-gradient(180deg, var(--gold), transparent);
        animation: scrollPulse 2s ease-in-out infinite;
      }

      @keyframes scrollBounce {
        0%, 100% { transform: translateX(-50%) translateY(0); }
        50% { transform: translateX(-50%) translateY(8px); }
      }

      @keyframes scrollPulse {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.8; }
      }

      /* =========================================
         COMMON SECTION STYLES
         ========================================= */
      .about-section {
        position: relative;
        padding: 120px 0;
        overflow: hidden;
      }

      .about-section--light {
        background: var(--cream-warm);
      }

      .about-section--dark {
        background: var(--forest-deep);
        color: var(--cream);
      }

      .about-section--accent {
        background: linear-gradient(135deg, var(--forest-mid) 0%, var(--forest-deep) 100%);
        color: var(--cream);
      }

      /* Decorative gradient line between sections */
      .section-accent-line {
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, 
          transparent 0%, 
          var(--gold) 20%, 
          var(--gold-light) 50%, 
          var(--gold) 80%, 
          transparent 100%
        );
        opacity: 0.5;
      }

      .section-label {
        display: inline-block;
        font-size: 0.72rem;
        letter-spacing: 0.25em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 600;
        margin-bottom: 16px;
        position: relative;
        padding-left: 36px;
      }

      .section-label::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 24px;
        height: 1px;
        background: var(--gold);
      }

      .section-title {
        font-family: var(--font-display);
        font-size: clamp(2.2rem, 4.5vw, 3.4rem);
        font-weight: 600;
        margin-bottom: 32px;
        line-height: 1.15;
      }

      .about-section--light .section-title,
      .about-section--plain .section-title {
        color: var(--forest-deep);
      }

      .about-section--dark .section-title,
      .about-section--accent .section-title {
        color: var(--cream);
      }

      .section-text {
        font-size: 1.12rem;
        line-height: 1.85;
        margin-bottom: 24px;
        max-width: 720px;
        color: rgba(31, 38, 32, 0.82);
      }

      .about-section--dark .section-text,
      .about-section--accent .section-text {
        color: rgba(246, 242, 233, 0.82);
      }

      .section-quote {
        font-family: var(--font-display);
        font-size: clamp(1.4rem, 2.5vw, 1.8rem);
        font-weight: 600;
        line-height: 1.5;
        margin: 40px 0;
        padding-left: 28px;
        border-left: 3px solid var(--gold);
        color: var(--forest-deep);
      }

      .about-section--dark .section-quote,
      .about-section--accent .section-quote {
        color: var(--cream);
        border-left-color: var(--gold-light);
      }

      /* =========================================
         INTRODUCTION SECTION
         ========================================= */
      .intro-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
      }

      .intro-visual {
        position: relative;

        overflow: hidden;
        aspect-ratio: 4/5;
      }

      .intro-visual img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s var(--ease-out-expo);
      }

      .intro-visual:hover img {
        transform: scale(1.05);
      }

      .intro-visual::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 50%, rgba(13, 31, 22, 0.4) 100%);
        pointer-events: none;
      }

      .intro-visual-badge {
        position: absolute;
        bottom: 24px;
        left: 24px;
        z-index: 2;
        background: rgba(13, 31, 22, 0.7);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(201, 162, 75, 0.2);
        padding: 16px 24px;

        color: var(--cream);
      }

      .intro-visual-badge span {
        display: block;
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--gold);
      }

      .intro-visual-badge small {
        font-size: 0.78rem;
        opacity: 0.7;
        letter-spacing: 0.05em;
      }

      .not-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 24px 0 32px;
      }

      .not-list-item {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 1.05rem;
        padding: 12px 20px;
        background: rgba(27, 58, 43, 0.04);

        border-left: 3px solid transparent;
        transition: all 0.3s ease;
      }

      .not-list-item:hover {
        border-left-color: var(--gold);
        background: rgba(27, 58, 43, 0.07);
        transform: translateX(6px);
      }

      .not-list-item .x-mark {
        color: var(--sage);
        font-size: 0.85rem;
        opacity: 0.5;
      }

      /* =========================================
         PHILOSOPHY SECTION
         ========================================= */
      .philosophy-content {
        max-width: 800px;
        margin: 0 auto;
      }

      .philosophy-values {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        margin: 48px 0;
        border: 1px solid rgba(201, 162, 75, 0.15);

        overflow: hidden;
      }

      .philosophy-value {
        padding: 36px 28px;
        text-align: center;
        border-right: 1px solid rgba(201, 162, 75, 0.1);
        background: rgba(255, 255, 255, 0.03);
        transition: all 0.4s ease;
        position: relative;
      }

      .philosophy-value:last-child {
        border-right: none;
      }

      .philosophy-value:hover {
        background: rgba(201, 162, 75, 0.06);
      }

      .philosophy-value::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--gold);
        transition: width 0.4s var(--ease-out-expo);
      }

      .philosophy-value:hover::before {
        width: 60%;
      }

      .philosophy-value i {
        font-size: 1.6rem;
        color: var(--gold);
        margin-bottom: 16px;
        display: block;
      }

      .philosophy-value p {
        font-size: 0.95rem;
        line-height: 1.7;
        color: rgba(246, 242, 233, 0.75);
      }

      .philosophy-cta-quote {
        text-align: center;
        font-family: var(--font-display);
        font-size: clamp(1.5rem, 3vw, 2.2rem);
        font-weight: 600;
        color: var(--cream);
        line-height: 1.5;
        margin-top: 20px;
      }

      .philosophy-cta-quote .accent {
        color: var(--gold-light);
        font-style: italic;
      }

      /* =========================================
         PILLARS / COLLECTIVE SECTION
         ========================================= */
      .pillars-intro {
        text-align: center;
        max-width: 640px;
        margin: 0 auto 64px;
      }

      .pillars-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
      }

      .pillar-card {
        position: relative;
        background: white;
        padding: 44px 32px 36px;
        border: 1px solid rgba(27, 58, 43, 0.06);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.04);
        transition: all 0.5s var(--ease-out-expo);
        overflow: hidden;
      }

      .pillar-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s var(--ease-out-expo);
      }

      .pillar-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border-color: rgba(201, 162, 75, 0.15);
      }

      .pillar-card:hover::before {
        transform: scaleX(1);
      }

      .pillar-icon-wrap {
        width: 64px;
        height: 64px;

        background: linear-gradient(135deg, var(--gold-glow), rgba(201, 162, 75, 0.08));
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        transition: transform 0.4s ease;
      }

      .pillar-card:hover .pillar-icon-wrap {
        transform: scale(1.1) rotate(-3deg);
      }

      .pillar-icon-wrap i {
        font-size: 1.5rem;
        color: var(--gold);
      }

      .pillar-card h3 {
        font-family: var(--font-display);
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 8px;
      }

      .pillar-card .pillar-sub {
        font-size: 0.82rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 600;
        margin-bottom: 18px;
        display: block;
      }

      .pillar-card p {
        font-size: 1rem;
        line-height: 1.75;
        color: rgba(31, 38, 32, 0.72);
        margin-bottom: 14px;
      }

      .pillar-card .pillar-quote {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--forest-deep);
        line-height: 1.5;
        padding-top: 18px;
        margin-top: auto;
        border-top: 1px solid rgba(27, 58, 43, 0.08);
      }

      /* =========================================
         DIFFERENTIATORS SECTION
         ========================================= */
      .diff-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: start;
      }

      .diff-content {
        padding-top: 20px;
      }

      .diff-list {
        list-style: none;
        margin: 36px 0;
      }

      .diff-list li {
        display: grid;
        grid-template-columns: auto 1fr auto 1fr;
        align-items: center;
        gap: 16px;
        padding: 20px 0;
        border-bottom: 1px solid rgba(246, 242, 233, 0.08);
        font-size: 1.05rem;
        transition: all 0.3s ease;
      }

      .diff-list li:hover {
        padding-left: 12px;
      }

      .diff-list li:last-child {
        border-bottom: none;
      }

      .diff-label {
        color: rgba(246, 242, 233, 0.45);
        font-weight: 500;
      }

      .diff-arrow {
        color: var(--gold);
        font-size: 1.2rem;
      }

      .diff-value {
        color: var(--gold-light);
        font-weight: 600;
      }

      .diff-visual {
        position: relative;
      }

      .diff-stat-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
      }

      .diff-stat-card {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(201, 162, 75, 0.12);

        padding: 28px 24px;
        text-align: center;
        backdrop-filter: blur(8px);
        transition: all 0.4s ease;
      }

      .diff-stat-card:hover {
        background: rgba(201, 162, 75, 0.06);
        transform: translateY(-4px);
        border-color: rgba(201, 162, 75, 0.25);
      }

      .diff-stat-card .stat-icon {
        font-size: 1.8rem;
        color: var(--gold);
        margin-bottom: 12px;
      }

      .diff-stat-card h4 {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 6px;
      }

      .diff-stat-card p {
        font-size: 0.85rem;
        color: rgba(246, 242, 233, 0.6);
        line-height: 1.5;
      }

      /* =========================================
         APPROACH SECTION
         ========================================= */
      .approach-principles {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        margin-top: 60px;
        counter-reset: principle;
      }

      .approach-principle {
        padding: 48px 36px;
        text-align: center;
        position: relative;
        counter-increment: principle;
        transition: all 0.4s ease;
      }

      .approach-principle:not(:last-child)::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: linear-gradient(180deg, transparent, rgba(27, 58, 43, 0.12), transparent);
      }

      .approach-principle:hover {
        background: rgba(27, 58, 43, 0.03);
      }

      .approach-number {
        font-family: var(--font-display);
        font-size: 3.5rem;
        font-weight: 700;
        color: var(--gold);
        line-height: 1;
        margin-bottom: 20px;
        opacity: 0.35;
        transition: opacity 0.4s ease;
      }

      .approach-principle:hover .approach-number {
        opacity: 0.7;
      }

      .approach-principle h4 {
        font-family: var(--font-display);
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 14px;
      }

      .approach-principle p {
        font-size: 1rem;
        line-height: 1.75;
        color: rgba(31, 38, 32, 0.68);
        max-width: 300px;
        margin: 0 auto;
      }

      /* =========================================
         EXPERIENCE SECTION
         ========================================= */
      .experience-content {
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
      }

      .experience-layers {
        display: flex;
        flex-direction: column;
        gap: 0;
        margin: 48px auto;
        max-width: 600px;
      }

      .experience-layer {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 22px 28px;
        border-left: 2px solid rgba(201, 162, 75, 0.2);
        transition: all 0.4s ease;
        position: relative;
      }

      .experience-layer::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 50%;
        transform: translateY(-50%);
        width: 10px;
        height: 10px;
        background: var(--forest-deep);
        border: 2px solid var(--gold);
        transition: all 0.3s ease;
      }

      .experience-layer:hover {
        border-left-color: var(--gold);
        padding-left: 36px;
      }

      .experience-layer:hover::before {
        background: var(--gold);
        box-shadow: 0 0 12px rgba(201, 162, 75, 0.4);
      }

      .experience-layer .layer-from {
        color: rgba(246, 242, 233, 0.5);
        font-size: 1.05rem;
        min-width: 200px;
        text-align: right;
      }

      .experience-layer .layer-arrow {
        color: var(--gold);
        font-size: 0.9rem;
      }

      .experience-layer .layer-to {
        color: var(--gold-light);
        font-weight: 500;
        font-size: 1.05rem;
      }

      /* =========================================
         PROMISE SECTION
         ========================================= */
      .promise-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 80px;
        align-items: center;
      }

      .promise-content .big-quote {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        line-height: 1.2;
        color: var(--forest-deep);
        margin-bottom: 32px;
      }

      .promise-content .big-quote .gold {
        color: var(--gold);
      }

      .promise-visual {
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .promise-card {
        padding: 32px;

        background: rgba(27, 58, 43, 0.03);
        border: 1px solid rgba(27, 58, 43, 0.06);
        transition: all 0.4s ease;
      }

      .promise-card:hover {
        background: rgba(27, 58, 43, 0.06);
        border-color: rgba(201, 162, 75, 0.15);
        transform: translateX(8px);
      }

      .promise-card p {
        font-size: 1.05rem;
        line-height: 1.75;
        color: rgba(31, 38, 32, 0.75);
      }

      /* =========================================
         CLOSING / BRAND SIGN-OFF
         ========================================= */
      .closing-section {
        text-align: center;
        padding: 140px 28px;
        background:
          radial-gradient(ellipse at 50% 100%, rgba(201, 162, 75, 0.08) 0%, transparent 60%),
          var(--forest-deep);
      }

      .closing-quote {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4.5vw, 3.2rem);
        font-weight: 600;
        color: var(--cream);
        line-height: 1.3;
        margin-bottom: 28px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
      }

      .closing-body {
        font-size: 1.15rem;
        color: rgba(246, 242, 233, 0.7);
        max-width: 680px;
        margin: 0 auto 56px;
        line-height: 1.8;
      }

      .closing-divider {
        width: 60px;
        height: 1px;
        background: var(--gold);
        margin: 48px auto;
        opacity: 0.5;
      }

      .closing-brand {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 600;
        color: var(--cream);
        margin-bottom: 12px;
      }

      .closing-pillars {
        font-size: 1rem;
        color: var(--gold);
        letter-spacing: 0.15em;
        margin-bottom: 14px;
      }

      .closing-tagline {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: rgba(246, 242, 233, 0.6);
        font-style: italic;
      }

      /* =========================================
         FOOTER
         ========================================= */
      footer {
        background: var(--forest-deep);
        color: rgba(246, 242, 233, 0.7);
        padding: 48px 0 28px;
        font-size: 0.86rem;
        border-top: 1px solid rgba(246, 242, 233, 0.06);
      }

      .footer-grid {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 24px;
        margin-bottom: 28px;
      }

      .footer-brand {
        font-family: var(--font-display);
        color: var(--cream);
        font-size: 1.1rem;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .footer-brand img {
        height: 32px;
        width: auto;
      }

      .footer-note {
        font-size: 0.8rem;
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
        transition: color 0.2s ease;
      }

      .footer-links a:hover {
        color: var(--gold);
      }

      .footer-bottom {
        border-top: 1px solid rgba(246, 242, 233, 0.12);
        padding-top: 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        font-size: 0.78rem;
        opacity: 0.65;
      }

      /* =========================================
         REVEAL ANIMATIONS
         ========================================= */
      .reveal {
        opacity: 0;
        transform: translateY(40px);
        transition:
          opacity 1s var(--ease-out-expo),
          transform 1s var(--ease-out-expo);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }

      .reveal.in-view {
        opacity: 1;
        transform: translateY(0);
      }

      .reveal-left {
        opacity: 0;
        transform: translateX(-40px);
        transition:
          opacity 1s var(--ease-out-expo),
          transform 1s var(--ease-out-expo);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }

      .reveal-left.in-view {
        opacity: 1;
        transform: translateX(0);
      }

      .reveal-right {
        opacity: 0;
        transform: translateX(40px);
        transition:
          opacity 1s var(--ease-out-expo),
          transform 1s var(--ease-out-expo);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }

      .reveal-right.in-view {
        opacity: 1;
        transform: translateX(0);
      }

      .reveal-scale {
        opacity: 0;
        transform: scale(0.92);
        transition:
          opacity 1s var(--ease-out-expo),
          transform 1s var(--ease-out-expo);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }

      .reveal-scale.in-view {
        opacity: 1;
        transform: scale(1);
      }

      /* =========================================
         RESPONSIVE
         ========================================= */
      @media (max-width: 1024px) {
        .intro-layout {
          grid-template-columns: 1fr;
          gap: 48px;
        }

        .intro-visual {
          max-height: 400px;
          aspect-ratio: 16/9;
        }

        .pillars-grid {
          grid-template-columns: 1fr;
          gap: 24px;
        }

        .diff-layout {
          grid-template-columns: 1fr;
          gap: 48px;
        }

        .promise-layout {
          grid-template-columns: 1fr;
          gap: 48px;
        }
      }

      @media (max-width: 768px) {
        .about-section {
          padding: 80px 0;
        }

        .hero-inner {
          padding: 120px 20px 80px;
        }

        .philosophy-values {
          grid-template-columns: 1fr;
          gap: 0;
        }

        .philosophy-value {
          border-right: none;
          border-bottom: 1px solid rgba(201, 162, 75, 0.1);
        }

        .philosophy-value:last-child {
          border-bottom: none;
        }

        .approach-principles {
          grid-template-columns: 1fr;
        }

        .approach-principle:not(:last-child)::after {
          right: 20%;
          top: auto;
          bottom: 0;
          height: 1px;
          width: 60%;
          background: linear-gradient(90deg, transparent, rgba(27, 58, 43, 0.12), transparent);
        }

        .experience-layer {
          flex-direction: column;
          align-items: flex-start;
          gap: 6px;
          text-align: left;
        }

        .experience-layer .layer-from {
          text-align: left;
          min-width: auto;
        }

        .diff-list li {
          grid-template-columns: 1fr auto 1fr;
          gap: 12px;
        }

        .diff-list li .diff-label {
          grid-column: 1 / -1;
        }

        .diff-stat-grid {
          grid-template-columns: 1fr 1fr;
          gap: 12px;
        }

        .closing-section {
          padding: 100px 20px;
        }
      }

      @media (max-width: 480px) {
        .wrap {
          padding: 0 18px;
        }

        .about-section {
          padding: 64px 0;
        }

        .hero-badge {
          font-size: 0.7rem;
          padding: 8px 18px;
        }

        .pillar-card {
          padding: 32px 24px 28px;
        }

        .diff-stat-grid {
          grid-template-columns: 1fr;
        }

        .diff-list li {
          grid-template-columns: 1fr;
          gap: 4px;
          padding: 14px 0;
        }

        .diff-list li .diff-arrow {
          display: none;
        }

        .experience-layers {
          margin: 32px auto;
        }
      }
    </style>
  </head>
  <body>
    <?php include __DIR__ . '/header.php'; ?>

    <!-- ========== HERO ========== -->
    <section class="about-hero" id="aboutHero">
      <div class="hero-particles" id="heroParticles"></div>
      <div class="hero-inner">
        <div class="hero-badge reveal" style="--reveal-delay: 0.2s">
          Explore · Stay · Empower
        </div>
        <h1 class="reveal" style="--reveal-delay: 0.4s">About Virunga Collective</h1>
        <p class="hero-subtitle reveal" style="--reveal-delay: 0.6s">
          Travel that Protects Nature. Empowers Communities. Creates Meaningful Experiences.
        </p>
      </div>
      <div class="hero-scroll-indicator">
        <span>Scroll</span>
        <div class="scroll-line"></div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== INTRODUCTION ========== -->
    <section class="about-section about-section--light" id="aboutIntro">
      <div class="wrap">
        <div class="intro-layout">
          <div class="intro-visual reveal-left">
            <img src="<?php echo htmlspecialchars($baseLink('img/intro.jpeg')); ?>" alt="Virunga landscape — volcanic peaks and lush forest canopy" loading="lazy" />
            <div class="intro-visual-badge">
              <span>Virunga</span>
              <small>A Living Travel Ecosystem</small>
            </div>
          </div>
          <div class="reveal-right">
            <span class="section-label">Our Story</span>
            <h2 class="section-title">Introduction</h2>
            <p class="section-text">
              Virunga Collective was born from a simple but powerful belief:
            </p>
            <div class="section-quote">
              That travel should not exist outside of people and place — but within them.
            </div>
            <p class="section-text">
              In the shadow of the Virunga volcanoes, where landscapes are ancient and communities are deeply connected to the land, we saw the opportunity to create something different.
            </p>
            <div class="not-list">
              <div class="not-list-item">
                <span class="x-mark"><i class="fas fa-times"></i></span>
                Not a tourism company.
              </div>
              <div class="not-list-item">
                <span class="x-mark"><i class="fas fa-times"></i></span>
                Not a lodge collection.
              </div>
              <div class="not-list-item">
                <span class="x-mark"><i class="fas fa-times"></i></span>
                Not a safari operator.
              </div>
            </div>
            <p class="section-text">
              But a living travel ecosystem — where every journey contributes to a shared future between guests and the communities who call Virunga home.
            </p>
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== PHILOSOPHY ========== -->
    <section class="about-section about-section--dark" id="aboutPhilosophy">
      <div class="wrap">
        <div class="philosophy-content">
          <span class="section-label reveal">Guiding Beliefs</span>
          <h2 class="section-title reveal" style="--reveal-delay: 0.1s">Our Philosophy</h2>
          <h3 class="reveal" style="font-family: var(--font-display); font-size: clamp(1.3rem, 2.5vw, 1.7rem); font-weight: 500; color: var(--gold-light); margin-bottom: 28px; --reveal-delay: 0.15s;">
            Travel, redefined through connection.
          </h3>
          <p class="section-text reveal" style="--reveal-delay: 0.2s">
            For too long, luxury travel has been defined by separation — between visitor and host, comfort and culture, experience and impact.
          </p>
          <p class="section-text reveal" style="--reveal-delay: 0.25s">
            Virunga Collective exists to dissolve that distance.
          </p>
          <p class="section-text reveal" style="--reveal-delay: 0.3s">
            We believe true luxury is not found in isolation, but in meaningful connection:
          </p>

          <div class="philosophy-values reveal-scale" style="--reveal-delay: 0.35s">
            <div class="philosophy-value">
              <i class="fas fa-mountain"></i>
              <p>Connection to landscapes that shape life itself</p>
            </div>
            <div class="philosophy-value">
              <i class="fas fa-people-group"></i>
              <p>Connection to communities who welcome you into their world</p>
            </div>
            <div class="philosophy-value">
              <i class="fas fa-seedling"></i>
              <p>Connection to experiences that leave a lasting imprint beyond memory</p>
            </div>
          </div>

          <div class="philosophy-cta-quote reveal" style="--reveal-delay: 0.4s">
            Here, travel is not consumption.<br>
            It is <span class="accent">participation</span>.
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== THE COLLECTIVE / PILLARS ========== -->
    <section class="about-section about-section--light" id="aboutPillars">
      <div class="wrap">
        <div class="pillars-intro reveal">
          <span class="section-label">Three Pillars</span>
          <h2 class="section-title">The Collective</h2>
          <p class="section-text" style="margin: 0 auto;">
            Virunga Collective is the master brand that brings together three interconnected pillars:
          </p>
        </div>

        <div class="pillars-grid">
          <!-- Explore -->
          <div class="pillar-card reveal" style="--reveal-delay: 0.1s">
            <div class="pillar-icon-wrap">
              <i class="fas fa-compass"></i>
            </div>
            <span class="pillar-sub">Explore</span>
            <h3>Virunga Ecotours</h3>
            <p>Guided exploration across the Virunga landscape.</p>
            <p>
              Virunga Ecotours offers immersive journeys through volcanoes, wildlife corridors, and cultural landscapes, led by local experts who carry generational knowledge of the region.
            </p>
            <p>
              Each journey is designed to reveal Virunga not as a destination, but as a living, breathing system.
            </p>
          </div>

          <!-- Stay -->
          <div class="pillar-card reveal" style="--reveal-delay: 0.25s">
            <div class="pillar-icon-wrap">
              <i class="fas fa-home-lg-alt"></i>
            </div>
            <span class="pillar-sub">Stay</span>
            <h3>Virunga Homestay</h3>
            <p>Authentic hospitality within local communities.</p>
            <p>
              Virunga Homestay connects guests directly with families across the Virunga region, offering carefully curated stays rooted in real life, shared experiences, and cultural exchange.
            </p>
            <div class="pillar-quote">
              These are not accommodations built for performance.<br>
              They are homes built for connection.
            </div>
          </div>

          <!-- Empower -->
          <div class="pillar-card reveal" style="--reveal-delay: 0.4s">
            <div class="pillar-icon-wrap">
              <i class="fas fa-heart"></i>
            </div>
            <span class="pillar-sub">Empower</span>
            <h3>Virunga Community Impact</h3>
            <p>A measurable model of shared prosperity.</p>
            <p>
              Virunga Community Impact ensures that every journey contributes directly to community development, conservation, and local livelihoods.
            </p>
            <p>
              Impact is not a promise here — it is a process that is tracked, shared, and experienced.
            </p>
            <div class="pillar-quote">
              Guests do not only hear about change.<br>
              They witness it.
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== WHAT MAKES US DIFFERENT ========== -->
    <section class="about-section about-section--accent" id="aboutDiff">
      <div class="wrap">
        <div class="diff-layout">
          <div class="diff-content">
            <span class="section-label reveal">Our Edge</span>
            <h2 class="section-title reveal" style="--reveal-delay: 0.1s">What Makes Us Different</h2>
            <h3 class="reveal" style="font-family: var(--font-display); font-size: clamp(1.2rem, 2vw, 1.5rem); font-weight: 500; color: var(--gold-light); margin-bottom: 20px; --reveal-delay: 0.15s;">
              A shift in the meaning of travel.
            </h3>
            <p class="section-text reveal" style="--reveal-delay: 0.2s">
              Most tourism models separate experience from impact.
            </p>
            <div class="section-quote reveal" style="--reveal-delay: 0.25s">
              Virunga Collective unifies them.
            </div>

            <ul class="diff-list reveal" style="--reveal-delay: 0.3s">
              <li>
                <span class="diff-label">Destinations</span>
                <span class="diff-arrow"><i class="fas fa-arrow-right"></i></span>
                <span class="diff-value">Ecosystems</span>
              </li>
              <li>
                <span class="diff-label">Services</span>
                <span class="diff-arrow"><i class="fas fa-arrow-right"></i></span>
                <span class="diff-value">Participation</span>
              </li>
              <li>
                <span class="diff-label">Experiences</span>
                <span class="diff-arrow"><i class="fas fa-arrow-right"></i></span>
                <span class="diff-value">Narratives</span>
              </li>
              <li>
                <span class="diff-label">Impact statements</span>
                <span class="diff-arrow"><i class="fas fa-arrow-right"></i></span>
                <span class="diff-value">Visible transformation</span>
              </li>
            </ul>

            <div class="philosophy-cta-quote reveal" style="text-align: left; font-size: clamp(1.2rem, 2vw, 1.6rem); --reveal-delay: 0.35s">
              This is not an evolution of tourism.<br>
              It is a <span class="accent">redefinition</span>.
            </div>
          </div>

          <div class="diff-visual reveal-right" style="--reveal-delay: 0.3s">
            <div class="diff-stat-grid">
              <div class="diff-stat-card">
                <div class="stat-icon"><i class="fas fa-globe-africa"></i></div>
                <h4>Ecosystems</h4>
                <p>Not destinations</p>
              </div>
              <div class="diff-stat-card">
                <div class="stat-icon"><i class="fas fa-handshake-angle"></i></div>
                <h4>Participation</h4>
                <p>Not services</p>
              </div>
              <div class="diff-stat-card">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <h4>Narratives</h4>
                <p>Not experiences</p>
              </div>
              <div class="diff-stat-card">
                <div class="stat-icon"><i class="fas fa-eye"></i></div>
                <h4>Transformation</h4>
                <p>Not impact statements</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== APPROACH ========== -->
    <section class="about-section about-section--light" id="aboutApproach">
      <div class="wrap">
        <div style="text-align: center; max-width: 640px; margin: 0 auto;">
          <span class="section-label reveal" style="padding-left: 0;">
            <span style="display: none;"></span>How We Work
          </span>
          <h2 class="section-title reveal" style="--reveal-delay: 0.1s">Our Approach</h2>
          <h3 class="reveal" style="font-family: var(--font-display); font-size: clamp(1.2rem, 2vw, 1.5rem); font-weight: 500; color: var(--gold); margin-bottom: 16px; --reveal-delay: 0.15s;">
            Designed from within the Virunga landscape.
          </h3>
          <p class="section-text reveal" style="margin: 0 auto 0; --reveal-delay: 0.2s">
            Everything we create is shaped by three principles:
          </p>
        </div>

        <div class="approach-principles">
          <div class="approach-principle reveal" style="--reveal-delay: 0.15s">
            <div class="approach-number">01</div>
            <h4>Local Ownership</h4>
            <p>
              Communities are not beneficiaries of tourism — they are active architects of it.
            </p>
          </div>
          <div class="approach-principle reveal" style="--reveal-delay: 0.3s">
            <div class="approach-number">02</div>
            <h4>Authentic Immersion</h4>
            <p>
              Experiences are not staged. They are lived, respectfully and meaningfully.
            </p>
          </div>
          <div class="approach-principle reveal" style="--reveal-delay: 0.45s">
            <div class="approach-number">03</div>
            <h4>Visible Impact</h4>
            <p>
              Every journey contributes to measurable outcomes within the region.
            </p>
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== THE EXPERIENCE WE CREATE ========== -->
    <section class="about-section about-section--dark" id="aboutExperience">
      <div class="wrap">
        <div class="experience-content">
          <span class="section-label reveal">The Journey</span>
          <h2 class="section-title reveal" style="--reveal-delay: 0.1s">The Experience We Create</h2>
          <p class="section-text reveal" style="margin: 0 auto 16px; --reveal-delay: 0.15s">
            A journey with Virunga Collective is not linear.
          </p>
          <p class="section-text reveal" style="margin: 0 auto; --reveal-delay: 0.2s">
            It moves through layers:
          </p>

          <div class="experience-layers reveal-scale" style="--reveal-delay: 0.3s">
            <div class="experience-layer">
              <span class="layer-from">Volcanic landscapes</span>
              <span class="layer-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
              <span class="layer-to">Village pathways</span>
            </div>
            <div class="experience-layer">
              <span class="layer-from">Guided exploration</span>
              <span class="layer-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
              <span class="layer-to">Shared meals</span>
            </div>
            <div class="experience-layer">
              <span class="layer-from">Observation</span>
              <span class="layer-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
              <span class="layer-to">Understanding</span>
            </div>
          </div>

          <p class="section-text reveal" style="margin: 0 auto; --reveal-delay: 0.4s">
            Guests leave not only with photographs, but with perspective — a deeper understanding of place, people, and purpose.
          </p>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== OUR PROMISE ========== -->
    <section class="about-section about-section--light" id="aboutPromise">
      <div class="wrap">
        <div class="promise-layout">
          <div class="promise-content reveal-left">
            <span class="section-label">Our Commitment</span>
            <h2 class="section-title">Our Promise</h2>
            <div class="big-quote">
              We do not promise detachment from the world.<br>
              We promise <span class="gold">reconnection</span> with it.
            </div>
          </div>

          <div class="promise-visual reveal-right" style="--reveal-delay: 0.2s">
            <div class="promise-card">
              <p>
                Every journey with Virunga Collective is designed to leave a trace — not only on the landscape, but on the traveler.
              </p>
            </div>
            <div class="promise-card">
              <p>
                A reminder that travel, when done with intention, can be transformative for both guest and host.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="section-accent-line"></div>

    <!-- ========== CLOSING STATEMENT & BRAND SIGN-OFF ========== -->
    <section class="closing-section" id="aboutClosing">
      <div class="wrap">
        <div class="closing-quote reveal">
          Virunga is not a place to be seen.<br><br>
          It is a place to be understood.
        </div>
        <p class="closing-body reveal" style="--reveal-delay: 0.15s">
          And Virunga Collective exists to ensure that when you come here, you do not simply pass through the landscape — you become part of its continuing story.
        </p>

        <div class="closing-divider reveal" style="--reveal-delay: 0.25s"></div>

        <h2 class="closing-brand reveal" style="--reveal-delay: 0.3s">Virunga Collective</h2>
        <p class="closing-pillars reveal" style="--reveal-delay: 0.35s">Ecotours · Homestays · Community Impact</p>
        <p class="closing-tagline reveal" style="--reveal-delay: 0.4s">
          A regenerative travel ecosystem rooted in the Virunga landscape.
        </p>
      </div>
    </section>

    <!-- ========== FOOTER ========== -->
    <footer>
      <div class="wrap">
        <div class="footer-grid">
          <div>
            <p class="footer-brand">
              <img src="<?php echo htmlspecialchars($baseLink('img/logo.png')); ?>" alt="Virunga Collective Logo">
              Virunga Collective
            </p>
            <p class="footer-note">
              Boutique stays, curated journeys, and community impact in the Virunga region of Rwanda. Formerly Virunga Ecotours and Virunga Homestay.
            </p>
          </div>
          <ul class="footer-links">
            <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Stays</a></li>
            <li><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">Journeys</a></li>
            <li><a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">Community</a></li>
            <li><a href="<?php echo htmlspecialchars($baseLink('home')); ?>#story">Our Story</a></li>
          </ul>
        </div>
        <div class="wrap footer-bottom">
          <span>© 2026 Virunga Collective. All rights reserved.</span>
          <span>Musanze, Rwanda</span>
        </div>
      </div>
    </footer>

    <script>
      /* ========================================
         PARTICLES
         ======================================== */
      (function initParticles() {
        const container = document.getElementById('heroParticles');
        if (!container) return;
        const count = 30;
        for (let i = 0; i < count; i++) {
          const p = document.createElement('div');
          p.className = 'hero-particle';
          p.style.left = Math.random() * 100 + '%';
          p.style.top = Math.random() * 100 + '%';
          p.style.animationDelay = Math.random() * 6 + 's';
          p.style.animationDuration = (4 + Math.random() * 4) + 's';
          p.style.width = p.style.height = (1 + Math.random() * 2) + 'px';
          container.appendChild(p);
        }
      })();

      /* ========================================
         PARALLAX on hero background
         ======================================== */
      (function initParallax() {
        const hero = document.querySelector('.about-hero');
        if (!hero) return;
        let ticking = false;
        window.addEventListener('scroll', () => {
          if (!ticking) {
            requestAnimationFrame(() => {
              const scrolled = window.scrollY;
              const bg = hero.querySelector(':before');
              hero.style.setProperty('--parallax-y', (scrolled * 0.3) + 'px');
              ticking = false;
            });
            ticking = true;
          }
        });
      })();

      /* ========================================
         REVEAL ANIMATIONS (Intersection Observer)
         ======================================== */
      (function initReveal() {
        const revealSelectors = '.reveal, .reveal-left, .reveal-right, .reveal-scale';
        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
        );
        document.querySelectorAll(revealSelectors).forEach((el) => observer.observe(el));
      })();

      /* ========================================
         SMOOTH scroll for indicator
         ======================================== */
      (function initScrollIndicator() {
        const indicator = document.querySelector('.hero-scroll-indicator');
        if (!indicator) return;
        indicator.addEventListener('click', () => {
          const target = document.getElementById('aboutIntro');
          if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
          }
        });
        indicator.style.cursor = 'pointer';
      })();

      /* ========================================
         HIDE scroll indicator on scroll
         ======================================== */
      (function hideScrollOnScroll() {
        const indicator = document.querySelector('.hero-scroll-indicator');
        if (!indicator) return;
        let hidden = false;
        window.addEventListener('scroll', () => {
          if (!hidden && window.scrollY > 150) {
            indicator.style.opacity = '0';
            indicator.style.transition = 'opacity 0.5s ease';
            hidden = true;
          } else if (hidden && window.scrollY <= 150) {
            indicator.style.opacity = '1';
            hidden = false;
          }
        });
      })();
    </script>
  </body>
</html>
