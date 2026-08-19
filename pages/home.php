<?php
  require_once __DIR__ . '/../config/localization.php';
  $loc = get_localization_data();
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($loc['code']); ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Primary Meta Tags (Localized & Custom per Language) -->
    <title><?php echo htmlspecialchars($loc['seo_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($loc['seo_description']); ?>" />
    <meta name="keywords" content="<?php echo htmlspecialchars($loc['seo_keywords']); ?>">
    <meta name="author" content="Virunga Collective">
    <meta name="robots" content="index, follow">

    <!-- Language-Specific Currency & Travel Context Metadata -->
    <meta name="currency" content="<?php echo htmlspecialchars($loc['currency_code']); ?>">
    <meta name="travel-info" content="<?php echo htmlspecialchars($loc['travel_info']); ?>">
    <meta name="cultural-notes" content="<?php echo htmlspecialchars($loc['cultural_notes']); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://virungacollective.com/">
    <meta property="og:title" content="<?php echo htmlspecialchars($loc['seo_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($loc['seo_description']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Virunga Collective">
    <meta property="og:locale" content="<?php echo htmlspecialchars($loc['code']); ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://virungacollective.com/">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($loc['seo_title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($loc['seo_description']); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta name="twitter:site" content="@virungacollective">
    <meta name="twitter:creator" content="@virungacollective">
    
    <!-- Canonical & 27-Language Hreflang Alternates -->
    <link rel="canonical" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="x-default" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="en" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="fr" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="de" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="es" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="it" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="nl" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="pt" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="zh" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ja" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="sv" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="no" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="da" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ar" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ko" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="hi" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ru" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="pl" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="tr" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="he" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="cs" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="fi" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ro" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="id" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="ms" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="sw" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="th" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="vi" href="https://virungacollective.com/" />
    <link rel="alternate" hreflang="uk" href="https://virungacollective.com/" />

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "TravelAgency",
          "@id": "https://virungacollective.com/#organization",
          "name": "Virunga Collective",
          "alternateName": "Virunga Conservation Hospitality Collective",
          "url": "https://virungacollective.com",
          "logo": "https://virungacollective.com/img/icon.png",
          "image": "https://virungacollective.com/img/about.jpeg",
          "description": "Rwanda's premier destination ecosystem—connecting luxury homestays, bespoke gorilla trekking safaris, volcanic coffee, and community impact in the Virunga Massif.",
          "telephone": "+250784513435",
          "email": "hello@virungacollective.com",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Musanze",
            "addressRegion": "Northern Province",
            "addressCountry": "RW"
          },
          "geo": {
            "@type": "GeoCoordinates",
            "latitude": -1.4961,
            "longitude": 29.6299
          },
          "knowsAbout": [
            "Luxury Rwanda Safaris",
            "Gorilla Trekking Rwanda",
            "Volcanoes National Park",
            "Bespoke Virunga Journeys",
            "Regenerative Tourism Rwanda",
            "Virunga Coffee"
          ],
          "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Virunga Collective Ecosystem",
            "itemListElement": [
              {
                "@type": "OfferCatalog",
                "name": "Virunga Ecotours",
                "description": "Bespoke Gorilla Trekking & Wildlife Expeditions"
              },
              {
                "@type": "OfferCatalog",
                "name": "Virunga House & Homestay",
                "description": "Boutique Luxury Hospitality in Musanze"
              },
              {
                "@type": "OfferCatalog",
                "name": "Virunga Signatures",
                "description": "Exclusive Curated Experiences & Private Safaris"
              },
              {
                "@type": "OfferCatalog",
                "name": "Virunga Community Impact",
                "description": "Conservation, People & Local Livelihoods"
              },
              {
                "@type": "OfferCatalog",
                "name": "Virunga Academy",
                "description": "Leadership, Skills & Capacity Building"
              },
              {
                "@type": "OfferCatalog",
                "name": "Virunga Coffee",
                "description": "Volcanic Coffee Experience Farm to Cup"
              }
            ]
          },
          "sameAs": [
            "https://www.tripadvisor.com/Hotel_Review-g317075-d20326735-Reviews-Virunga_Homestay_Live_the_Virunga_Experience-Ruhengeri_Musanze_District_Northern_Prov.html",
            "https://instagram.com/virungacollective",
            "https://facebook.com/virungacollective"
          ],
          "priceRange": "$$$"
        },
        {
          "@type": "WebSite",
          "@id": "https://virungacollective.com/#website",
          "url": "https://virungacollective.com",
          "name": "Virunga Collective",
          "publisher": {
            "@id": "https://virungacollective.com/#organization"
          }
        }
      ]
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
      :root {
        --forest: #1b3a2b;
        --forest-deep: #122a1f;
        --gold: #c9a24b;
        --cream: #f6f2e9;
        --charcoal: #1f2620;
        --sage: #6e8270;
        --max-w: 1180px;
        --font-display: "Cormorant Garamond", serif;
        --font-body: "Jost", sans-serif;
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
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
        overflow-x: hidden;
      }
      body.loading {
        overflow: hidden;
      }
      img,
      video {
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
        padding: 0 24px;
      }

      /* ---------- Skeleton Loader ---------- */
      #skeleton {
        position: fixed;
        inset: 0;
        z-index: 999;
        display: flex;
        flex-direction: column;
        transition:
          opacity 0.5s ease,
          visibility 0.5s ease;
        background: var(--cream);
      }
      #skeleton.hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
      }
      .sk-nav {
        height: 78px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 24px;
        max-width: var(--max-w);
        width: 100%;
        margin: 0 auto;
        position: relative;
        z-index: 10;
      }
      .sk-pill {
        background: linear-gradient(
          90deg,
          #e9e4d6 25%,
          #f2eee2 37%,
          #e9e4d6 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      .sk-logo {
        width: 150px;
        height: 22px;
      }
      .sk-links {
        display: flex;
        gap: 18px;
      }
      .sk-links span {
        width: 60px;
        height: 14px;
        display: block;
      }
      .sk-hero {
        flex: 1;
        position: relative;
        background: linear-gradient(135deg, var(--sage), var(--forest));
      }
      .sk-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          180deg,
          rgba(18, 42, 31, 0.4) 0%,
          rgba(18, 42, 31, 0.05) 30%,
          rgba(18, 42, 31, 0.15) 55%,
          rgba(18, 42, 31, 0.85) 100%
        );
      }
      .sk-hero-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 2;
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        padding: 135px 48px 0;
      }
      .sk-coords {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }
      .sk-coords span {
        width: 120px;
        height: 12px;
        opacity: 0.7;
        background: linear-gradient(
          90deg,
          rgba(246, 242, 233, 0.2) 25%,
          rgba(246, 242, 233, 0.3) 37%,
          rgba(246, 242, 233, 0.2) 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      .sk-hero-content {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        padding: 0 48px 70px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
      }
      .sk-hero-text {
        max-width: 620px;
        width: 100%;
      }
      .sk-eyebrow {
        display: flex;
        gap: 8px;
        margin-bottom: 18px;
      }
      .sk-eyebrow span {
        height: 14px;
        background: linear-gradient(
          90deg,
          rgba(201, 162, 75, 0.3) 25%,
          rgba(201, 162, 75, 0.4) 37%,
          rgba(201, 162, 75, 0.3) 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      .sk-eyebrow span:nth-child(1) { width: 70px; }
      .sk-eyebrow span:nth-child(2) { width: 55px; }
      .sk-eyebrow span:nth-child(3) { width: 40px; }
      .sk-heading {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 22px;
      }
      .sk-heading span {
        height: 42px;
        background: linear-gradient(
          90deg,
          rgba(246, 242, 233, 0.25) 25%,
          rgba(246, 242, 233, 0.35) 37%,
          rgba(246, 242, 233, 0.25) 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      .sk-heading span:nth-child(1) { width: 300px; }
      .sk-heading span:nth-child(2) { width: 420px; }
      .sk-subtitle {
        height: 20px;
        width: 480px;
        background: linear-gradient(
          90deg,
          rgba(246, 242, 233, 0.2) 25%,
          rgba(246, 242, 233, 0.3) 37%,
          rgba(246, 242, 233, 0.2) 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      .sk-hero-cta {
        flex-shrink: 0;
      }
      .sk-btn {
        width: 160px;
        height: 54px;
        background: linear-gradient(
          90deg,
          rgba(201, 162, 75, 0.4) 25%,
          rgba(201, 162, 75, 0.5) 37%,
          rgba(201, 162, 75, 0.4) 63%
        );
        background-size: 400% 100%;
        animation: shimmer 1.4s ease-in-out infinite;
      }
      @keyframes shimmer {
        0% {
          background-position: 100% 0;
        }
        100% {
          background-position: 0 0;
        }
      }
      @media (max-width: 860px) {
        .sk-hero-top {
          padding: 28px 24px 0;
        }
        .sk-hero-content {
          padding: 0 24px 48px;
          flex-direction: column;
          align-items: flex-start;
          gap: 26px;
        }
        .sk-hero-cta {
          width: 100%;
        }
        .sk-btn {
          width: 100%;
        }
      }
      @media (max-width: 720px) {
        .sk-links {
          display: none;
        }
        .sk-hero-top {
          padding: 72px 20px 0;
        }
        .sk-hero-content {
          padding: 0 20px 40px;
        }
        .sk-heading span {
          height: 34px;
        }
        .sk-heading span:nth-child(1) { width: 240px; }
        .sk-heading span:nth-child(2) { width: 340px; }
        .sk-subtitle {
          width: 100%;
        }
      }
      @media (max-width: 480px) {
        .sk-nav {
          padding: 0 16px;
        }
        .sk-hero-top {
          padding: 18px 16px 0;
        }
        .sk-hero-content {
          padding: 0 16px 32px;
        }
        .sk-coords {
          display: none;
        }
        .sk-heading span {
          height: 28px;
        }
        .sk-heading span:nth-child(1) { width: 180px; }
        .sk-heading span:nth-child(2) { width: 260px; }
      }



      /* ---------- Hero ---------- */
      .hero {
        position: relative;
        height: 100vh;
        height: 100dvh;
        max-height: 100vh;
        max-height: 100dvh;
        overflow: hidden;
        color: var(--cream);
      }
      .hero-bg-poster {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        z-index: 0;
        opacity: 1;
        visibility: visible;
        transition: opacity 0.8s ease-in-out, visibility 0.8s ease-in-out;
        will-change: opacity;
      }
      .hero-bg-poster.is-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
      }
      .hero video {
        position: absolute;
        top: 50%;
        left: 50%;
        min-width: 100%;
        min-height: 100%;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: translate(-50%, -50%);
        z-index: 0;
        opacity: 0;
        transition: opacity 0.8s ease-in-out;
      }
      .hero video.is-playing {
        opacity: 1;
      }
      @keyframes heroZoom {
        from {
          transform: translate(-50%, -50%) scale(1.12);
        }
        to {
          transform: translate(-50%, -50%) scale(1);
        }
      }
      .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          180deg,
          rgba(18, 42, 31, 0.4) 0%,
          rgba(18, 42, 31, 0.05) 30%,
          rgba(18, 42, 31, 0.15) 55%,
          rgba(18, 42, 31, 0.85) 100%
        );
        z-index: 1;
      }
      .hero-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 3;
        display: flex;
        justify-content: flex-end;
        align-items: flex-start;
        padding: 135px 48px 0;
      }
      .hero-coords {
        font-size: 0.72rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: rgba(246, 242, 233, 0.75);
        text-align: right;
        line-height: 1.7;
      }
      .hero-content {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        padding: 0 48px 45px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 40px;
      }
      .hero-text {
        max-width: 620px;
        text-align: left;
      }
      .hero-text .eyebrow {
        justify-content: flex-start;
      }
      .hero-cta-side {
        flex-shrink: 0;
        text-align: right;
        padding-bottom: 6px;
      }
      .word-mask {
        overflow: hidden;
        display: block;
        width: 100%;
        max-width: 100%;
      }
      .word-mask-inline {
        overflow: hidden;
        display: inline-block;
        max-width: 100%;
      }
      .word-mask span {
        will-change: transform;
      }
      [data-hero-word] {
        transform: translateY(115%);
        opacity: 0;
        transition:
          transform 1s cubic-bezier(0.16, 1, 0.3, 1),
          opacity 0.9s ease;
        overflow-wrap: break-word;
        word-break: break-word;
      }
      .word-mask [data-hero-word] {
        display: block;
        width: 100%;
        max-width: 100%;
      }
      .word-mask-inline [data-hero-word] {
        display: inline-block;
      }
      [data-hero-word].in {
        transform: translateY(0);
        opacity: 1;
      }
      .hero-text {
        max-width: 620px;
        width: 100%;
        text-align: left;
        overflow: hidden;
      }
      .hero-text .eyebrow {
        flex-wrap: wrap;
      }
      .hero h1 {
        max-width: 100%;
        overflow: hidden;
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 4.2vw, 3rem);
        font-weight: 600;
        line-height: 1.15;
        margin-bottom: 18px;
      }
      .eyebrow {
        font-size: 0.78rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 600;
        margin-bottom: 18px;
        display: flex;
      }
      .scroll-cue {
        position: absolute;
        bottom: 28px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        color: var(--cream);
        opacity: 0.6;
        display: none;
      }

      /* ---------- Buttons ---------- */
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 15px 34px;
        border: 1px solid var(--gold);
        color: var(--cream);
        background: transparent;
        text-decoration: none;
        font-size: 0.92rem;
        letter-spacing: 0.05em;
        font-weight: 500;
        transition:
          background 0.35s cubic-bezier(0.16, 1, 0.3, 1),
          color 0.35s ease,
          transform 0.35s cubic-bezier(0.16, 1, 0.3, 1),
          box-shadow 0.35s ease;
        cursor: pointer;
        gap: 10px;
        position: relative;
        overflow: hidden;
      }
      /* Shimmer sweep on hover */
      .btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
          90deg,
          transparent 0%,
          rgba(255, 255, 255, 0.15) 50%,
          transparent 100%
        );
        transition: left 0.5s ease;
        pointer-events: none;
      }
      .btn:hover::after {
        left: 100%;
      }
      .btn:hover {
        background: var(--gold);
        color: var(--forest-deep);
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(201, 162, 75, 0.3);
      }
      /* Animate the arrow icon inside buttons */
      .btn i,
      .btn .fa-arrow-right,
      .btn .fa-arrow-down,
      .btn .fa-envelope,
      .btn .fa-comments {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .btn:hover .fa-arrow-right {
        transform: translateX(5px);
      }
      .btn:hover .fa-arrow-down {
        animation: bounceDown 0.6s ease infinite;
      }
      .btn:hover .fa-envelope {
        animation: wiggle 0.5s ease;
      }
      .btn:hover .fa-comments {
        animation: wiggle 0.5s ease;
      }
      @keyframes bounceDown {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(4px); }
      }
      @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-8deg); }
        75% { transform: rotate(8deg); }
      }
      .btn-solid {
        background: var(--gold);
        color: var(--forest-deep);
        border: 1px solid var(--gold);
      }
      .btn-solid:hover {
        background: transparent;
        color: var(--cream);
        box-shadow: 0 8px 25px rgba(201, 162, 75, 0.25);
      }
      /* Pillar link arrow animation */
      .pillar-link {
        transition: color 0.3s ease, gap 0.3s ease;
      }
      .pillar-link i {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .pillar-link:hover {
        color: var(--gold) !important;
      }
      .pillar-link:hover i {
        transform: translateX(6px);
      }
      
      /* ---------- Footer ---------- */
      footer {
        background: var(--forest);
        color: var(--cream);
        padding: 60px 0 30px;
      }

      /* ---------- Ridge divider ---------- */
      .ridge {
        width: 100%;
        display: block;
        line-height: 0;
      }
      .ridge svg {
        width: 100%;
        height: 64px;
        display: block;
      }

      /* ---------- Modern Card Sections ---------- */
      .section {
        padding: 100px 0;
        background: var(--cream);
      }
      .section-dark {
        background: var(--forest-deep);
        color: var(--cream);
      }

      /* ---------- CTA Section ---------- */
      .cta {
        padding: 160px 0;
        position: relative;
        overflow: hidden;
        background-attachment: fixed;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
      }
      .cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(27, 58, 43, 0.45) 0%, rgba(18, 42, 31, 0.55) 100%);
        z-index: 1;
      }
      .cta .wrap {
        position: relative;
        z-index: 2;
      }
      .cta h2 {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4.5vw, 3.2rem);
        line-height: 1.2;
        margin-bottom: 48px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
      }
      .cta .btn {
        padding: 18px 48px;
        font-size: 1rem;
        letter-spacing: 0.08em;
      }
      .cta .btn:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(201, 162, 75, 0.3);
      }
      .section-header {
        text-align: center;
        margin-bottom: 60px;
      }
      .section-header h2 {
        font-family: var(--font-display);
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 16px;
      }
      .section-dark .section-header h2 {
        color: var(--cream);
      }
      .section-header .eyebrow {
        justify-content: center;
        color: var(--sage);
      }
      .section-dark .section-header .eyebrow {
        color: var(--gold);
      }
      .card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
      }
      .card {
        background: white;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s ease;
        position: relative;
        overflow: hidden;
      }
      .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--gold), #e4c97a);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 48px rgba(0, 0, 0, 0.14);
      }
      .card:hover::before {
        transform: scaleX(1);
      }
      .card-icon {
        font-size: 2.5rem;
        margin-bottom: 20px;
      }
      .card h3 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 16px;
      }
      .card p {
        font-size: 1.05rem;
        line-height: 1.7;
        color: var(--charcoal);
        opacity: 0.9;
      }
      .section-dark .card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(246, 242, 233, 0.1);
      }
      .section-dark .card h3 {
        color: var(--cream);
      }
      .section-dark .card p {
        color: rgba(246, 242, 233, 0.9);
      }

      /* ---------- Story Section ---------- */
      .story-section {
        background: var(--forest-deep);
        color: var(--cream);
        padding: 100px 0;
      }
      .story-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
      }
      .story-image {
        width: 100%;
        aspect-ratio: 4/5;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      }
      .story-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .story-content h2 {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        font-weight: 600;
        margin-bottom: 24px;
      }
      .story-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: rgba(246, 242, 233, 0.85);
        margin-bottom: 24px;
      }
      .story-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 40px;
      }
      .stat-item {
        text-align: center;
      }
      .stat-num {
        font-family: var(--font-display);
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--gold);
        margin-bottom: 8px;
      }
      .stat-label {
        font-size: 0.9rem;
        color: rgba(246, 242, 233, 0.7);
      }

      /* ---------- Reveal on scroll ---------- */
      .reveal {
        opacity: 0;
        transform: translateY(48px) scale(0.97);
        transition:
          opacity 0.9s cubic-bezier(0.16, 1, 0.3, 1),
          transform 0.9s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal.in-view {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
      .reveal-left {
        opacity: 0;
        transform: translateX(-50px);
        transition:
          opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
          transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-left.in-view {
        opacity: 1;
        transform: translateX(0);
      }
      .reveal-right {
        opacity: 0;
        transform: translateX(50px);
        transition:
          opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
          transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-right.in-view {
        opacity: 1;
        transform: translateX(0);
      }
      .reveal-scale {
        opacity: 0;
        transform: scale(0.88);
        transition:
          opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
          transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-scale.in-view {
        opacity: 1;
        transform: scale(1);
      }
      /* Stat counter animation */
      .stat-num {
        transition: opacity 0.5s ease;
      }

      /* ---------- Responsive ---------- */
      @media (max-width: 1024px) {
        .story-grid {
          gap: 40px;
        }
      }
      @media (max-width: 860px) {
        .hero-top {
          padding: 115px 24px 0;
        }
        .hero-content {
          padding: 0 24px 32px;
          flex-direction: column;
          align-items: flex-start;
          gap: 26px;
        }
        .hero-cta-side {
          text-align: left;
          padding-bottom: 0;
        }
        .story-grid {
          grid-template-columns: 1fr;
          gap: 36px;
        }
        .story-image {
          order: -1;
          aspect-ratio: 16/9;
        }
        .section {
          padding: 72px 0;
        }
      }
      @media (max-width: 720px) {
        .hero {
          height: 100vh;
          height: 100dvh;
          max-height: 100vh;
          max-height: 100dvh;
        }
        .hero-top {
          padding: 105px 20px 0;
        }
        .hero-coords {
          font-size: 0.64rem;
          letter-spacing: 0.14em;
        }
        .hero-content {
          padding: 0 20px 30px;
          flex-direction: column;
          align-items: flex-start;
          gap: 18px;
        }
        .hero-cta-side {
          text-align: left;
          padding-bottom: 0;
          width: 100%;
        }
        .hero-cta-side .btn {
          width: 100%;
          text-align: center;
        }
        .section {
          padding: 64px 0;
        }
      }
      @media (max-width: 480px) {
        .hero-top {
          padding: 95px 16px 0;
        }
        .hero-content {
          padding: 0 16px 24px;
        }
        .hero h1 {
          font-size: clamp(1.5rem, 8vw, 2rem);
          line-height: 1.18;
        }
        .hero-coords {
          display: none;
        }
        .btn {
          padding: 13px 26px;
          font-size: 0.86rem;
        }
        .section {
          padding: 56px 0;
        }
        .card-grid {
          grid-template-columns: 1fr;
        }
        .card {
          padding: 30px;
        }
        .cta {
          padding: 100px 0;
          background-attachment: scroll; /* Disable parallax on mobile for better performance */
        }
      }
      @media (prefers-reduced-motion: reduce) {
        html {
          scroll-behavior: auto;
        }
        .reveal,
        .reveal-left,
        .reveal-right,
        .reveal-scale {
          opacity: 1 !important;
          transform: none !important;
          transition: none !important;
        }
        [data-hero-word] {
          opacity: 1 !important;
          transform: none !important;
          transition: none !important;
        }
        .hero video {
          animation: none !important;
        }
        * {
          transition-duration: 0.01ms !important;
          animation-duration: 0.01ms !important;
        }
      }
      :focus-visible {
        outline: 2px solid var(--gold);
        outline-offset: 3px;
      }

      /* ---------- SEVEN PILLARS SECTION (SCROLL-DRIVEN) ---------- */
      .pillars {
        background: white;
        padding: 120px 0;
      }
      .pillars__intro {
        max-width: 680px;
        margin: 0 auto;
        padding: 0 24px 80px;
        text-align: center;
      }
      .pillars__eyebrow {
        font-size: 0.72rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--gold);
        margin: 0 0 16px;
        font-weight: 600;
      }
      .pillars__heading {
        font-family: var(--font-display);
        font-weight: 600;
        font-size: clamp(2rem, 4vw, 3rem);
        line-height: 1.2;
        color: var(--forest-deep);
        margin: 0 0 18px;
      }
      .pillars__sub {
        font-size: 1.1rem;
        line-height: 1.7;
        color: var(--charcoal);
        opacity: 0.8;
        margin: 0;
      }
      .pillars__scroll {
        display: flex;
        max-width: var(--max-w);
        margin: 0 auto;
        padding: 0 24px;
        gap: 80px;
      }
      .pillars__rail {
        width: 280px;
        flex-shrink: 0;
      }
      .pillars__rail-inner {
        position: sticky;
        top: 120px;
        display: flex;
        gap: 24px;
      }
      .pillars__rail-line {
        position: relative;
        width: 2px;
        background: rgba(27, 58, 43, 0.1);
        border-radius: 2px;
        flex-shrink: 0;
        height: auto;
      }
      .pillars__rail-fill {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 14.28%; /* 1/7 default */
        background: linear-gradient(180deg, var(--gold), #e4c878);
        border-radius: 2px;
        transition: height 400ms cubic-bezier(.16,1,.3,1);
      }
      .pillars__rail-list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 28px;
      }
      .pillars__rail-item {
        display: flex;
        align-items: center;
        gap: 16px;
      }
      .pillars__rail-index {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 50%;
        border: 1.5px solid rgba(27, 58, 43, 0.15);
        font-family: var(--font-display);
        font-size: 0.85rem;
        color: var(--sage);
        background: transparent;
        transition: all 250ms ease;
      }
      .pillars__rail-label {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--sage);
        transition: color 250ms ease;
      }
      .pillars__rail-item.is-active .pillars__rail-index {
        background: var(--gold);
        border-color: var(--gold);
        color: var(--forest-deep);
        font-weight: 600;
        transform: scale(1.08);
      }
      .pillars__rail-item.is-active .pillars__rail-label {
        color: var(--forest-deep);
        font-weight: 600;
      }

      .pillars__panels {
        flex: 1;
        min-width: 0;
      }
      .pillars__panel {
        display: flex;
        align-items: center;
        gap: 50px;
        min-height: 65vh;
        border-top: 1px solid rgba(27, 58, 43, 0.08);
        padding: 60px 0;
      }
      .pillars__panel:last-child {
        border-bottom: 1px solid rgba(27, 58, 43, 0.08);
      }
      .pillars__media {
        position: relative;
        width: 320px;
        height: 320px;
        flex-shrink: 0;
        overflow: hidden;
        border-radius: 4px;
        box-shadow: 0 10px 30px rgba(27, 58, 43, 0.1);
      }
      .pillars__media-image {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transform: scale(1.02);
        transition: transform 0.5s ease;
      }
      .pillars__panel:hover .pillars__media-image {
        transform: scale(1.06);
      }
      .pillars__media-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        padding: 24px;
        background: linear-gradient(180deg, rgba(18, 42, 31, 0.85) 0%, rgba(18, 42, 31, 0) 100%);
      }
      .pillars__media-count {
        font-family: var(--font-display);
        font-size: 0.75rem;
        letter-spacing: 2px;
        color: var(--gold);
        margin: 0 0 6px;
        text-transform: uppercase;
      }
      .pillars__media-title {
        font-family: var(--font-display);
        font-weight: 600;
        font-size: 1.4rem;
        line-height: 1.25;
        color: var(--cream);
        margin: 0;
      }
      .pillars__copy {
        flex: 1;
        min-width: 0;
      }
      .pillars__copy-desc {
        font-size: 1.05rem;
        line-height: 1.75;
        color: var(--charcoal);
        margin: 0 0 24px;
        max-width: 48ch;
      }
      .pillars__tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        margin: 0;
        padding: 0;
      }
      .pillars__tags li {
        font-size: 0.82rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        color: var(--gold);
        background: rgba(201, 162, 75, 0.06);
        border: 1px solid rgba(201, 162, 75, 0.35);
        padding: 6px 14px;
        border-radius: 20px;
      }

      /* ---------- ECOSYSTEM SECTION ---------- */
      .ecosystem-section {
        background: var(--cream);
        padding: 120px 0;
        border-top: 1px solid rgba(27, 58, 43, 0.08);
      }
      .ecosystem-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-top: 60px;
      }
      .ecosystem-card {
        background: white;
        padding: 40px;
        border: 1px solid rgba(27, 58, 43, 0.06);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
      }
      .ecosystem-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--gold);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
      }
      .ecosystem-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(27, 58, 43, 0.1);
      }
      .ecosystem-card:hover::before {
        transform: scaleX(1);
      }
      .ecosystem-card h3 {
        font-family: var(--font-display);
        font-size: 1.55rem;
        color: var(--forest-deep);
        margin-bottom: 14px;
        font-weight: 600;
      }
      .ecosystem-card p {
        font-size: 1rem;
        color: var(--charcoal);
        opacity: 0.85;
        line-height: 1.6;
        margin-bottom: 24px;
        flex-grow: 1;
      }
      .ecosystem-link {
        color: var(--gold);
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: color 0.3s ease;
      }
      .ecosystem-link:hover {
        color: var(--forest);
      }

      /* ---------- PARTNERS SECTION (B2B) ---------- */
      .partners-section {
        background: var(--forest-deep);
        color: var(--cream);
        padding: 120px 0;
      }
      .partners-grid {
        display: grid;
        grid-template-columns: 1.15fr 0.85fr;
        gap: 80px;
        align-items: center;
      }
      .partners-content h2 {
        font-family: var(--font-display);
        font-size: clamp(2rem, 3.5vw, 2.8rem);
        line-height: 1.2;
        margin-bottom: 24px;
        color: var(--cream);
        font-weight: 600;
      }
      .partners-content p {
        font-size: 1.1rem;
        line-height: 1.75;
        color: rgba(246, 242, 233, 0.8);
        margin-bottom: 30px;
      }
      .partners-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 30px;
        list-style: none;
        margin-top: 30px;
      }
      .partners-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 1.02rem;
        line-height: 1.5;
        color: rgba(246, 242, 233, 0.85);
      }
      .partners-list li i {
        color: var(--gold);
        margin-top: 4px;
      }
      .partners-image {
        position: relative;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
        aspect-ratio: 4/5;
      }
      .partners-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
      }

      /* ---------- PROOF OF IMPACT SECTION ---------- */
      .impact-section {
        background: var(--cream);
        padding: 120px 0;
        border-bottom: 1px solid rgba(27, 58, 43, 0.08);
      }
      .impact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 24px;
        text-align: center;
        margin-top: 40px;
      }
      .impact-item {
        padding: 15px 5px;
        position: relative;
      }
      .impact-num {
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.2vw, 2.6rem);
        font-weight: 600;
        color: var(--forest-deep);
        margin-bottom: 8px;
        line-height: 1;
      }
      .impact-label {
        font-size: 0.76rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: var(--sage);
        font-weight: 600;
        line-height: 1.4;
      }

      /* ---------- TESTIMONIALS SECTION ---------- */
      .testimonials-section {
        background: white;
        padding: 120px 0;
      }
      .testimonials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 40px;
        margin-top: 60px;
      }
      .testimonial-card {
        background: var(--cream);
        padding: 45px 40px;
        border-radius: 4px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-left: 4px solid var(--gold);
        position: relative;
        transition: transform 0.4s ease;
      }
      .testimonial-card:hover {
        transform: translateY(-5px);
      }
      .testimonial-quote {
        font-size: 1.1rem;
        line-height: 1.75;
        color: var(--charcoal);
        opacity: 0.9;
        font-style: italic;
        margin-bottom: 35px;
        position: relative;
        z-index: 2;
      }
      .testimonial-quote::before {
        content: '“';
        font-family: var(--font-display);
        font-size: 5rem;
        color: rgba(201, 162, 75, 0.12);
        position: absolute;
        top: -45px;
        left: -15px;
        line-height: 1;
        z-index: -1;
      }
      .testimonial-author {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }
      .testimonial-name {
        font-family: var(--font-display);
        font-weight: 600;
        font-size: 1.25rem;
        color: var(--forest-deep);
      }
      .testimonial-role {
        font-size: 0.85rem;
        color: var(--sage);
        font-weight: 600;
        letter-spacing: 0.5px;
      }

      /* ---------- RESPONSIVE BREAKPOINTS ---------- */
      @media (max-width: 1024px) {
        .pillars__scroll { gap: 40px; }
        .pillars__rail { width: 240px; }
        .pillars__media { width: 260px; height: 260px; }
      }

      @media (max-width: 860px) {
        .pillars { padding: 80px 0; }
        .pillars__scroll { padding: 0 24px; }
        .pillars__rail { display: none; }
        .pillars__panel {
          flex-direction: column;
          align-items: flex-start;
          min-height: 0;
          padding: 40px 0;
          gap: 25px;
        }
        .pillars__media { width: 100%; height: 280px; }
        .ecosystem-section, .partners-section, .impact-section, .testimonials-section {
          padding: 80px 0;
        }
      }

      @media (prefers-reduced-motion: reduce) {
        .pillars__rail-fill, .pillars__rail-index, .pillars__rail-label {
          transition: none !important;
        }
      }
    </style>
  </head>
  <body class="loading">
    <!-- Skeleton loader -->
    <div id="skeleton" aria-hidden="true">
      <div class="sk-nav">
        <div class="sk-pill sk-logo"></div>
        <div class="sk-links">
          <span class="sk-pill"></span><span class="sk-pill"></span><span class="sk-pill"></span><span class="sk-pill"></span>
        </div>
      </div>
      <div class="sk-hero">
        <div class="sk-hero-top">
          <div class="sk-coords">
            <span></span>
            <span></span>
          </div>
        </div>
        <div class="sk-hero-content">
          <div class="sk-hero-text">
            <div class="sk-eyebrow">
              <span></span>
              <span></span>
              <span></span>
            </div>
            <div class="sk-heading">
              <span></span>
              <span></span>
            </div>
            <div class="sk-subtitle"></div>
          </div>
          <div class="sk-hero-cta">
            <div class="sk-btn"></div>
          </div>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/header.php'; ?>

    <!-- Hero Section - Clean with video background & instant poster fallback -->
    <section class="hero">
      <div class="hero-bg-poster" style="background-image: url('<?php echo htmlspecialchars($baseLink('img/hero-poster.webp')); ?>');"></div>
      <video autoplay muted loop playsinline poster="<?php echo htmlspecialchars($baseLink('img/hero-poster.webp')); ?>">
        <source src="<?php echo htmlspecialchars($baseLink('img/hero-web-light.mp4')); ?>" type="video/mp4" media="(max-width: 768px)" />
        <source src="<?php echo htmlspecialchars($baseLink('img/hero-web.mp4')); ?>" type="video/mp4" />
      </video>

      <div class="hero-top">
        <div class="hero-coords">
          01.4990° S, 29.6333° E<br />
          Virunga Region, Rwanda
        </div>
      </div>

      <div class="hero-content">
        <div class="hero-text" style="max-width: 800px;">
          <h1 id="heroHeading">
            <span class="word-mask" style="display: block;">
              <span data-hero-word>Experience the Virunga Mountains</span>
            </span>
            <span class="word-mask" style="display: block;">
              <span data-hero-word>Through the People Who Call Them Home</span>
            </span>
          </h1>
          <p class="hero-subheadline reveal" style="font-size: clamp(0.95rem, 1.8vw, 1.1rem); color: var(--cream); opacity: 0.95; max-width: 650px; margin-top: 15px; line-height: 1.6; --reveal-delay: 0.15s;">
            Extraordinary journeys. Authentic communities. Lasting conservation impact.
          </p>
          <div class="hero-cta-group reveal" style="display: flex; gap: 16px; flex-wrap: wrap; margin-top: 20px; --reveal-delay: 0.3s;">
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="btn btn-solid">
              Explore Experiences <i class="fas fa-arrow-right"></i>
            </a>
            <a href="<?php echo htmlspecialchars($baseLink('contact-us')); ?>" class="btn" style="border-color: var(--cream);">
              Plan Your Journey
            </a>
          </div>
        </div>
      </div>

      <div class="scroll-cue"><span></span>Scroll</div>
    </section>

    <div class="ridge" aria-hidden="true">
      <svg
        viewBox="0 0 1200 64"
        preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M0,64 L0,40 L120,10 L220,46 L340,4 L460,38 L600,14 L740,48 L860,8 L980,42 L1100,18 L1200,40 L1200,64 Z"
          fill="#F6F2E9"
        />
      </svg>
    </div>

    <!-- Why Choose Virunga Collective — Seven Pillars Scroll-driven section -->
    <section class="pillars" aria-labelledby="pillars-heading" id="why-choose-us">
      <div class="pillars__intro reveal">
        <p class="pillars__eyebrow">Why Choose Virunga Collective</p>
        <h2 class="pillars__heading" id="pillars-heading">The Seven Pillars That Make Us Different</h2>
        <p class="pillars__sub">What sets us apart and defines our commitment to you, the community, and the landscape.</p>
      </div>

      <div class="pillars__scroll">
        <!-- Left: Sticky Navigation Rail (Visible on Desktop) -->
        <div class="pillars__rail" aria-hidden="true">
          <div class="pillars__rail-inner">
            <div class="pillars__rail-line">
              <div class="pillars__rail-fill"></div>
            </div>
            <ol class="pillars__rail-list">
              <li class="pillars__rail-item is-active" id="rail-item-0">
                <span class="pillars__rail-index">01</span>
                <span class="pillars__rail-label">Authentic Experiences</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-1">
                <span class="pillars__rail-index">02</span>
                <span class="pillars__rail-label">Trusted Local Expertise</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-2">
                <span class="pillars__rail-index">03</span>
                <span class="pillars__rail-label">Community-Led Tourism</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-3">
                <span class="pillars__rail-index">04</span>
                <span class="pillars__rail-label">Conservation in Action</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-4">
                <span class="pillars__rail-index">05</span>
                <span class="pillars__rail-label">Excellence in Service</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-5">
                <span class="pillars__rail-index">06</span>
                <span class="pillars__rail-label">Learning Through Travel</span>
              </li>
              <li class="pillars__rail-item" id="rail-item-6">
                <span class="pillars__rail-index">07</span>
                <span class="pillars__rail-label">Positive Lasting Impact</span>
              </li>
            </ol>
          </div>
        </div>

        <!-- Right: Scrolling panels -->
        <div class="pillars__panels">
          <!-- Pillar 1 -->
          <article class="pillars__panel reveal" id="panel-0" data-index="0">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_authentic.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 01 of 07</p>
                <h3 class="pillars__media-title">Authentic Experiences</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                We go beyond sightseeing to create meaningful journeys that immerse you in the landscapes, wildlife, cultures, and stories of the Virunga Mountains.
              </p>
              <ul class="pillars__tags">
                <li>Exclusive experiences</li>
                <li>Small groups</li>
                <li>Authentic encounters</li>
                <li>Personal memories</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 2 -->
          <article class="pillars__panel reveal" id="panel-1" data-index="1">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_expertise.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 02 of 07</p>
                <h3 class="pillars__media-title">Trusted Local Expertise</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                Our experienced local team delivers safe, seamless, and professionally guided adventures with unmatched knowledge of the region.
              </p>
              <ul class="pillars__tags">
                <li>Professional guides</li>
                <li>Reliable logistics</li>
                <li>Personalized planning</li>
                <li>Peace of mind</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 3 -->
          <article class="pillars__panel reveal" id="panel-2" data-index="2">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_community.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 03 of 07</p>
                <h3 class="pillars__media-title">Community-Led Tourism</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                Every experience directly supports local families, creates jobs, and strengthens community livelihoods.
              </p>
              <ul class="pillars__tags">
                <li>Stay locally</li>
                <li>Meet local people</li>
                <li>Support local businesses</li>
                <li>Create meaningful impact</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 4 -->
          <article class="pillars__panel reveal" id="panel-3" data-index="3">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_conservation.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 04 of 07</p>
                <h3 class="pillars__media-title">Conservation in Action</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                Every journey contributes to protecting the unique biodiversity of the Virunga landscape for future generations.
              </p>
              <ul class="pillars__tags">
                <li>Responsible travel</li>
                <li>Wildlife protection</li>
                <li>Environmental stewardship</li>
                <li>Sustainable tourism</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 5 -->
          <article class="pillars__panel reveal" id="panel-4" data-index="4">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_service.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 05 of 07</p>
                <h3 class="pillars__media-title">Excellence in Service</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                International standards combined with genuine African hospitality ensure every guest receives exceptional care from arrival to departure.
              </p>
              <ul class="pillars__tags">
                <li>Professional service</li>
                <li>Comfort</li>
                <li>Safety</li>
                <li>Attention to detail</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 6 -->
          <article class="pillars__panel reveal" id="panel-5" data-index="5">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_learning.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 06 of 07</p>
                <h3 class="pillars__media-title">Learning Through Travel</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                Our experiences inspire curiosity, cultural understanding, and a deeper appreciation of nature and conservation.
              </p>
              <ul class="pillars__tags">
                <li>Learn from experts</li>
                <li>Discover local traditions</li>
                <li>Understand conservation</li>
                <li>Travel with purpose</li>
              </ul>
            </div>
          </article>

          <!-- Pillar 7 -->
          <article class="pillars__panel reveal" id="panel-6" data-index="6">
            <div class="pillars__media">
              <div class="pillars__media-image" style="background-image:url('<?php echo htmlspecialchars($baseLink('img/pillar_impact.png')); ?>')"></div>
              <div class="pillars__media-overlay">
                <p class="pillars__media-count">Pillar 07 of 07</p>
                <h3 class="pillars__media-title">Positive Lasting Impact</h3>
              </div>
            </div>
            <div class="pillars__copy">
              <p class="pillars__copy-desc">
                Your journey creates benefits that continue long after your visit for communities, conservation, and future generations.
              </p>
              <ul class="pillars__tags">
                <li>Real social impact</li>
                <li>Responsible tourism</li>
                <li>Ethical travel</li>
                <li>A meaningful legacy</li>
              </ul>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- The Virunga Collective Ecosystem -->
    <section class="ecosystem-section" id="ecosystem">
      <div class="wrap">
        <div class="section-header reveal" style="text-align: center; max-width: 750px; margin: 0 auto 50px;">
          <p class="pillars__eyebrow">The Virunga Collective Ecosystem</p>
          <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 2.8rem); color: var(--forest-deep); font-weight: 600; margin-bottom: 20px;">
            One Brand. One Mission. One Extraordinary Destination.
          </h2>
          <p style="font-size: 1.1rem; color: var(--charcoal); opacity: 0.8; line-height: 1.6;">
            Every experience, stay, and project is part of a unified regenerative travel ecosystem designed to protect nature and support communities.
          </p>
        </div>

        <div class="ecosystem-grid">
          <!-- Card 1: Ecotours -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0s">
            <div>
              <h3><i class="fa-solid fa-leaf" style="color: var(--gold); margin-right: 8px;"></i> Virunga Ecotours</h3>
              <p>Discover unforgettable wildlife, nature, and cultural adventures guided by local experts.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="ecosystem-link">
              Explore Journeys <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- Card 2: Homestays -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0.15s">
            <div>
              <h3><i class="fa-solid fa-house-chimney" style="color: var(--gold); margin-right: 8px;"></i> Virunga Homestay</h3>
              <p>Stay with local families and experience authentic community life with genuine Rwandan hospitality.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>" class="ecosystem-link">
              Book a Stay <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- Card 3: Signature Experiences -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0.3s">
            <div>
              <h3><i class="fa-solid fa-star" style="color: var(--gold); margin-right: 8px;"></i> Virunga Signatures</h3>
              <p>Exclusive, handcrafted adventures designed for deep connection and available only through our collective.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="ecosystem-link">
              View Signatures <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- Card 4: Community Impact -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0s">
            <div>
              <h3><i class="fa-solid fa-handshake" style="color: var(--gold); margin-right: 8px;"></i> Virunga Community Impact</h3>
              <p>Creating opportunities that strengthen local livelihoods, fund education, and empower communities.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>" class="ecosystem-link">
              See Our Impact <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- Card 5: Academy -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0.15s">
            <div>
              <h3><i class="fa-solid fa-graduation-cap" style="color: var(--gold); margin-right: 8px;"></i> Virunga Academy</h3>
              <p>Developing the next generation of local tourism, hospitality, and conservation professionals through hands-on training.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/training.php')); ?>" class="ecosystem-link">
              Explore Academy <i class="fas fa-arrow-right"></i>
            </a>
          </div>

          <!-- Card 6: Coffee -->
          <div class="ecosystem-card reveal" style="--reveal-delay: 0.3s">
            <div>
              <h3><i class="fa-solid fa-mug-hot" style="color: var(--gold); margin-right: 8px;"></i> Virunga Coffee</h3>
              <p>Coffee cultivated in the fertile volcanic soils of the Virunga region and crafted with exceptional care.</p>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('coffee')); ?>" class="ecosystem-link">
              Discover Coffee <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Virunga Academy - Shaping the Future of Tourism -->
    <section class="partners-section" id="partners">
      <div class="wrap partners-grid">
        <div class="partners-content reveal-left">
          <p class="pillars__eyebrow" style="color: var(--gold);">Empowering Local Communities</p>
          <h2>Virunga Academy: Nurturing the Future Leaders of Ecotourism</h2>
          <p>
            Through the Virunga Academy, we provide professional training in guiding, hospitality, conservation, and leadership, empowering community members with sustainable career skills.
          </p>
          <ul class="partners-list">
            <li><i class="fas fa-graduation-cap"></i> Professional guide certification</li>
            <li><i class="fas fa-first-aid"></i> Wilderness first aid & safety</li>
            <li><i class="fas fa-concierge-bell"></i> Hospitality & homestay excellence</li>
            <li><i class="fas fa-hiking"></i> Ethical porter training programs</li>
            <li><i class="fas fa-language"></i> Storytelling & cultural heritage</li>
            <li><i class="fas fa-leaf"></i> Sustainable conservation practices</li>
            <li><i class="fas fa-handshake"></i> Hands-on internships & mentorship</li>
            <li><i class="fas fa-users"></i> Livelihood development & empowerment</li>
          </ul>
        </div>
        <div class="partners-image reveal-right" style="--reveal-delay: 0.2s">
          <img src="<?php echo htmlspecialchars($baseLink('img/training.png')); ?>" alt="Virunga Academy professional local guides in training">
        </div>
      </div>
    </section>

    <!-- Proof of Impact -->
    <section class="impact-section" id="impact">
      <div class="wrap">
        <div class="section-header reveal" style="text-align: center; max-width: 600px; margin: 0 auto 40px;">
          <p class="pillars__eyebrow">Proof of Impact</p>
          <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 2.8rem); color: var(--forest-deep); font-weight: 600;">
            Measurable Results, Real Change
          </h2>
          <p style="font-size: 1.1rem; color: var(--charcoal); opacity: 0.8; margin-top: 10px;">
            We don't just make promises. Here is the direct, measurable impact of our collective efforts in the Virunga region.
          </p>
        </div>

        <div class="impact-grid story-stats"> <!-- Reuses the story-stats class to trigger the existing animated counter script -->
          <div class="impact-item reveal" style="--reveal-delay: 0s">
            <div class="impact-num" data-count="350" data-suffix="+">0</div>
            <div class="impact-label">Families Benefiting</div>
          </div>
          <div class="impact-item reveal" style="--reveal-delay: 0.1s">
            <div class="impact-num" data-count="8" data-suffix="">0</div>
            <div class="impact-label">Conservation Projects</div>
          </div>
          <div class="impact-item reveal" style="--reveal-delay: 0.2s">
            <div class="impact-num" data-count="98" data-suffix="%">0</div>
            <div class="impact-label">Guest Satisfaction</div>
          </div>
          <div class="impact-item reveal" style="--reveal-delay: 0.3s">
            <div class="impact-num" data-count="92" data-suffix="%">0</div>
            <div class="impact-label">Repeat Partner Rate</div>
          </div>
          <div class="impact-item reveal" style="--reveal-delay: 0.4s">
            <div class="impact-num" data-count="10" data-suffix="+">0</div>
            <div class="impact-label">Years of Experience</div>
          </div>
        </div>
      </div>
    </section>

    <?php if (false): ?>
    <!-- Guest Testimonials -->
    <section class="testimonials-section" id="testimonials">
      <div class="wrap">
        <div class="section-header reveal" style="text-align: center; max-width: 600px; margin: 0 auto 50px;">
          <p class="pillars__eyebrow">Guest Testimonials</p>
          <h2 style="font-family: var(--font-display); font-size: clamp(2rem, 3.5vw, 2.8rem); color: var(--forest-deep); font-weight: 600;">
            Real Stories From Our Partners & Guests
          </h2>
        </div>

        <div class="testimonials-grid">
          <!-- Testimonial 1 -->
          <div class="testimonial-card reveal" style="--reveal-delay: 0s">
            <p class="testimonial-quote">
              The level of professionalism and local integration Virunga Collective offers is unparalleled. Our tour groups had life-changing experiences while knowing our bookings directly supported local community projects.
            </p>
            <div class="testimonial-author">
              <span class="testimonial-name">Sarah Jenkins</span>
              <span class="testimonial-role">Director, EcoVenture Travel (Tour Operator)</span>
            </div>
          </div>

          <!-- Testimonial 2 -->
          <div class="testimonial-card reveal" style="--reveal-delay: 0.15s">
            <p class="testimonial-quote">
              Staying at the Virunga Homestay and exploring Volcanoes National Park with local guides was the highlight of our trip. The hospitality was genuine, and the guides had generational knowledge of the gorillas.
            </p>
            <div class="testimonial-author">
              <span class="testimonial-name">David & Maria Weber</span>
              <span class="testimonial-role">Travelers from Germany</span>
            </div>
          </div>

          <!-- Testimonial 3 -->
          <div class="testimonial-card reveal" style="--reveal-delay: 0.3s">
            <p class="testimonial-quote">
              Virunga Academy is developing outstanding local leaders. Our partnership has created excellent hospitality internships and porter training programs that strengthen local livelihoods in Musanze.
            </p>
            <div class="testimonial-author">
              <span class="testimonial-name">Jean-Claude Nsengiyumva</span>
              <span class="testimonial-role">Director of Conservation Education, Local NGO</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- Call to Action -->
    <section class="cta section-dark" id="cta" style="background-image: url('<?php echo htmlspecialchars($baseLink('img/cta.jpeg')); ?>');">
      <div class="wrap" style="text-align: center;">
        <div class="reveal-scale">
          <p class="eyebrow" style="justify-content: center; color: var(--gold);">Ready to Experience the Virunga Mountains Differently?</p>
          <h2 style="font-family: var(--font-display); font-size: clamp(1.8rem, 3vw, 2.5rem); margin-bottom: 20px; font-weight: 500;">
            Whether you’re an independent traveler, travel agency, tour operator, school, conservation organization, or corporate partner, we’re ready to help you create meaningful journeys with lasting impact.
          </h2>
          <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="btn btn-solid">
              Explore Experiences <i class="fas fa-compass"></i>
            </a>
            <a href="<?php echo htmlspecialchars($baseLink('contact-us')); ?>" class="btn" style="border-color: var(--cream);">
              Partner With Us <i class="fas fa-handshake"></i>
            </a>
            <a href="mailto:info@virungacollective.com" class="btn" style="border-color: var(--cream);">
              Contact Our Team <i class="fas fa-envelope"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <footer>
      <div class="wrap">
        <div class="footer-grid" style="display: flex; justify-content: space-between; flex-wrap: wrap; gap: 24px; margin-bottom: 28px;">
          <div>
            <p class="footer-brand" style="font-family: var(--font-display); color: var(--cream); font-size: 1.1rem; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
              <img src="<?php echo htmlspecialchars($baseLink('img/logo.png')); ?>" alt="Virunga Collective Logo" style="height: 32px;">
              Virunga Collective
            </p>
            <p class="footer-note" style="font-size: 0.8rem; opacity: 0.7; max-width: 340px;">
              Travel that Protects Nature. Empowers Communities. Creates Meaningful Experiences. Explore, Stay, Empower.
            </p>
          </div>
          <ul class="footer-links" style="display: flex; gap: 28px; list-style: none; flex-wrap: wrap;">
            <li><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">Explore</a></li>
            <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Stay</a></li>
            <li><a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">Empower</a></li>
            <li><a href="#story">Our Mission</a></li>
          </ul>
        </div>
        <div class="footer-bottom" style="border-top: 1px solid rgba(246,242,233,0.12); padding-top: 20px; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; font-size: 0.78rem; opacity: 0.65;">
          <span>© 2026 Virunga Collective. All rights reserved.</span>
          <span>Musanze, Rwanda</span>
        </div>
      </div>
    </footer>

    <script>
      // Skeleton loader: hide once page is ready, then play hero entrance
      function revealHero() {
        const words = document.querySelectorAll("[data-hero-word]");
        words.forEach((w, i) => {
          setTimeout(() => w.classList.add("in"), i * 90);
        });
      }
      window.addEventListener("load", () => {
        setTimeout(() => {
          document.getElementById("skeleton").classList.add("hide");
          document.body.classList.remove("loading");
          revealHero();
        }, 500);
      });
      setTimeout(() => {
        if (document.body.classList.contains("loading")) {
          document.getElementById("skeleton").classList.add("hide");
          document.body.classList.remove("loading");
          revealHero();
        }
      }, 3500);



      // Staggered reveals for modern cards
      const STAGGER_STEP = 0.15;
      function setupStaggerGroup(groupSelector, itemSelector) {
        document.querySelectorAll(groupSelector).forEach((group) => {
          const items = Array.from(group.querySelectorAll(itemSelector));
          items.forEach((el, i) => {
            el.style.setProperty("--reveal-delay", i * STAGGER_STEP + "s");
          });
        });
      }
      setupStaggerGroup(".card-grid", ".card");
      setupStaggerGroup(".story-grid", ".reveal");

      // Plain single reveals (section heads)
      document
        .querySelectorAll(".section-header.reveal")
        .forEach((el) => {
          el.style.setProperty("--reveal-delay", "0s");
        });

      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("in-view");
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.08, rootMargin: "0px 0px -40px 0px" }
      );
      document
        .querySelectorAll(".reveal, .reveal-left, .reveal-right, .reveal-scale")
        .forEach((el) => observer.observe(el));

      // Animated stat counters
      function animateCounters() {
        document.querySelectorAll('[data-count]').forEach((el) => {
          const target = parseInt(el.getAttribute('data-count'), 10);
          const suffix = el.getAttribute('data-suffix') || '';
          const duration = 1200;
          const start = performance.now();
          function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(eased * target);
            el.textContent = current + suffix;
            if (progress < 1) requestAnimationFrame(step);
          }
          requestAnimationFrame(step);
        });
      }
      // Trigger counter animation when stats section enters view
      const statsObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              animateCounters();
              statsObserver.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.3 }
      );
      const statsSection = document.querySelector('.story-stats');
      if (statsSection) statsObserver.observe(statsSection);

      // ---------- Additional Stagger Animations ----------
      setupStaggerGroup(".ecosystem-grid", ".ecosystem-card");
      setupStaggerGroup(".testimonials-grid", ".testimonial-card");

      // ---------- Brand Pillars Sticky Rail Observer ----------
      const totalPillars = 7;
      const railList = document.querySelector(".pillars__rail-list");
      const railFill = document.querySelector(".pillars__rail-fill");

      function setActivePillar(index) {
        if (!railList || !railFill) return;
        railList.querySelectorAll(".pillars__rail-item").forEach((el, i) => {
          el.classList.toggle("is-active", i === index);
        });
        const pct = ((index + 1) / totalPillars) * 100;
        railFill.style.height = pct + "%";
      }

      if ("IntersectionObserver" in window) {
        const panelObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              const index = Number(entry.target.getAttribute("data-index"));
              setActivePillar(index);
            }
          });
        }, { rootMargin: "-45% 0px -45% 0px", threshold: 0 });

        document.querySelectorAll(".pillars__panel").forEach((panel) => {
          panelObserver.observe(panel);
        });
      }
      setActivePillar(0);
    </script>

    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "TravelAgency",
          "name": "Virunga Collective",
          "url": "https://virungacollective.com",
          "description": "Boutique stays, curated journeys, and community impact experiences in Rwanda, Uganda, and DR Congo.",
          "areaServed": [
            "Rwanda",
            "Uganda",
            "Democratic Republic of the Congo"
          ],
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Musanze",
            "addressCountry": "RW"
          }
        },
        {
          "@type": "LodgingBusiness",
          "name": "Virunga Homestay",
          "url": "https://virungacollective.com/homestay",
          "description": "Boutique stay near Volcanoes National Park with authentic Rwandan hospitality.",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Musanze",
            "addressCountry": "RW"
          }
        }
      ]
    }
    </script>
    <style>
      .member-popup {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 340px;
        background: rgba(13, 31, 22, 0.96);
        border: 1px solid rgba(201, 162, 75, 0.4);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        color: #f6f2e9;
        z-index: 10000;
        backdrop-filter: blur(10px);
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.6s ease;
        font-family: "Jost", sans-serif;
      }
      .member-popup.show {
        transform: translateX(0);
        opacity: 1;
      }
      .member-popup__close {
        position: absolute;
        top: 12px;
        right: 16px;
        background: none;
        border: none;
        color: rgba(246, 242, 233, 0.6);
        font-size: 1.5rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s ease;
      }
      .member-popup__close:hover {
        color: #c9a24b;
      }
      .member-popup__badge {
        display: inline-block;
        background: rgba(201, 162, 75, 0.15);
        border: 1px solid #c9a24b;
        color: #e4c97a;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 3px 10px;
        border-radius: 50px;
        margin-bottom: 12px;
        font-weight: 600;
      }
      .member-popup h3 {
        font-family: "Cormorant Garamond", serif;
        font-size: 1.45rem;
        color: #c9a24b;
        margin-bottom: 8px;
        font-weight: 500;
        line-height: 1.25;
      }
      .member-popup__text {
        font-size: 0.92rem;
        line-height: 1.5;
        color: rgba(246, 242, 233, 0.85);
        margin-bottom: 18px;
      }
      .member-popup__btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #c9a24b;
        color: #0d1f16;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 10px 18px;
        border-radius: 6px;
        transition: all 0.25s ease;
        border: 1px solid #c9a24b;
      }
      .member-popup__btn:hover {
        background: transparent;
        color: #c9a24b;
        transform: translateY(-2px);
      }
    </style>

    <!-- Membership Advert Popup -->
    <div id="membershipPopup" class="member-popup">
      <button id="closeMemberPopup" class="member-popup__close" aria-label="Close Ad">&times;</button>
      <div class="member-popup__content">
        <div class="member-popup__badge">MEMBER CLUB</div>
        <h3>Virunga Collective Membership</h3>
        <p class="member-popup__text">Travel. Belong. Make an Impact. You can become a member! Click to learn how.</p>
        <a href="<?php echo htmlspecialchars($baseLink('membership')); ?>" class="member-popup__btn">Learn More <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const heroVideo = document.querySelector('.hero video');
      const heroPoster = document.querySelector('.hero-bg-poster');

      if (heroVideo && heroPoster) {
        let revealed = false;
        const revealVideo = () => {
          if (revealed) return;
          revealed = true;
          heroVideo.classList.add('is-playing');
          heroPoster.classList.add('is-hidden');
        };

        if (heroVideo.readyState >= 2 && !heroVideo.paused) {
          revealVideo();
        }

        heroVideo.addEventListener('loadeddata', revealVideo, { once: true });
        heroVideo.addEventListener('canplay', revealVideo, { once: true });
        heroVideo.addEventListener('playing', revealVideo, { once: true });
        heroVideo.addEventListener('timeupdate', () => {
          if (heroVideo.currentTime >= 0) {
            revealVideo();
          }
        });

        const playPromise = heroVideo.play();
        if (playPromise !== undefined) {
          playPromise.then(() => {
            if (heroVideo.currentTime >= 0 || heroVideo.readyState >= 2) {
              revealVideo();
            }
          }).catch(() => {});
        }
      }

      const popup = document.getElementById("membershipPopup");
      const closeBtn = document.getElementById("closeMemberPopup");
      const ecosystemSection = document.getElementById("ecosystem");
      
      let hasTriggered = false;
      let autoCloseTimeout;

      if (popup && closeBtn && ecosystemSection) {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting && !hasTriggered) {
              hasTriggered = true;
              
              // Slide in the popup after a brief delay
              setTimeout(() => {
                popup.classList.add("show");
                
                // Auto close in 30 seconds
                autoCloseTimeout = setTimeout(() => {
                  popup.classList.remove("show");
                }, 30000);
              }, 800);
              
              // Once triggered, stop observing
              observer.unobserve(ecosystemSection);
            }
          });
        }, { threshold: 0.1 });

        observer.observe(ecosystemSection);

        // Close on button click
        closeBtn.addEventListener("click", () => {
          clearTimeout(autoCloseTimeout);
          popup.classList.remove("show");
        });
      }
    });
    </script>
  </body>
</html>
