<?php
  require_once __DIR__ . '/../config/localization.php';
  $loc = get_localization_data();

  // Database queries for dynamic homepage content
  $featured_blogs = [];
  $signature_tours = [];

  if (!function_exists('get_tour_image_url')) {
    function get_tour_image_url($cover_path, $default_rel_path, $baseLink) {
      if (!empty($cover_path)) {
        if (preg_match('#^https?://#', $cover_path)) {
          return $cover_path;
        }
        $clean = ltrim($cover_path, '/');
        if (strpos($clean, 'ecotours/') === 0 || strpos($clean, 'homestay/') === 0 || strpos($clean, 'img/') === 0) {
          return $baseLink($clean);
        }
        return $baseLink('ecotours/' . $clean);
      }
      return $baseLink($default_rel_path);
    }
  }

  if (!function_exists('limit_words')) {
    function limit_words($text, $limit = 25, $end = '...') {
      $clean = trim(strip_tags((string)$text));
      if ($clean === '') return '';
      $words = preg_split('/\s+/u', $clean);
      if (count($words) <= $limit) {
        return $clean;
      }
      return implode(' ', array_slice($words, 0, $limit)) . $end;
    }
  }

  // Select the three defining journeys explicitly; retain their database IDs.
  try {
    require_once __DIR__ . '/../ecotours/admin/config/connection.php';
    $blogs = $conn->query("SELECT bp.blog_id, bp.title, bp.introduction, bp.cover_image, bp.author, bp.read_minutes, bp.published_at, bp.created_at, bc.category_name FROM blog_posts bp JOIN blog_categories bc ON bp.category_id = bc.category_id WHERE bp.status = 'published' ORDER BY bp.published_at DESC, bp.created_at DESC LIMIT 3");
    if ($blogs) $featured_blogs = $blogs->fetch_all(MYSQLI_ASSOC);
    $signature_names = ['THE LIVING VIRUNGA JOURNEY', 'THE VIRUNGA WAY', 'THE FOREST & THE PEOPLE'];
    $signature_rows = $conn->query("SELECT tour_id, title, cover_image_path, short_description FROM tours WHERE TRIM(LOWER(category)) = 'signature journeys' ORDER BY created_at DESC");
    $available_signatures = $signature_rows ? $signature_rows->fetch_all(MYSQLI_ASSOC) : [];
    foreach ($signature_names as $index => $name) {
      foreach ($available_signatures as $tour) {
        $normalized_title = strtoupper(trim(preg_replace('/^\d+\s*[^a-zA-Z]+\s*/u', '', $tour['title'])));
        if ($normalized_title === $name) {
          $signature_tours[] = array_merge($tour, ['badge' => sprintf('%02d', $index + 1), 'pillar' => '']);
          break;
        }
      }
    }
  } catch (Throwable $e) {
    error_log('Homepage content unavailable: ' . $e->getMessage());
  }
?>
<!doctype html>
<html lang="<?php echo htmlspecialchars($loc['code']); ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Primary Meta Tags -->
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
    <meta property="og:url" content="https://virungajourneys.com/">
    <meta property="og:title" content="<?php echo htmlspecialchars($loc['seo_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($loc['seo_description']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Virunga Collective">
    <meta property="og:locale" content="<?php echo htmlspecialchars($loc['code']); ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://virungajourneys.com/">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($loc['seo_title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($loc['seo_description']); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    
    <!-- Canonical & Alternates -->
    <link rel="canonical" href="https://virungajourneys.com/" />
    <link rel="alternate" hreflang="x-default" href="https://virungajourneys.com/" />
    <link rel="alternate" hreflang="en" href="https://virungajourneys.com/" />
    <link rel="alternate" hreflang="fr" href="https://virungajourneys.com/" />
    <link rel="alternate" hreflang="de" href="https://virungajourneys.com/" />
    <link rel="alternate" hreflang="es" href="https://virungajourneys.com/" />

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@graph": [
        {
          "@type": "TravelAgency",
          "@id": "https://virungajourneys.com/#organization",
          "name": "Virunga Collective",
          "alternateName": "The Virunga Journey",
          "url": "https://virungajourneys.com",
          "logo": "https://virungajourneys.com/img/icon.png",
          "image": "https://virungajourneys.com/img/about.jpeg",
          "description": "The Virunga is more than a destination to visit. Experience it through the people, stories, landscapes and living traditions that make this place extraordinary.",
          "telephone": "+250784513435",
          "email": "info@virungajourneys.com",
          "address": {
            "@type": "PostalAddress",
            "addressLocality": "Musanze",
            "addressRegion": "Northern Province",
            "addressCountry": "RW"
          }
        },
        {
          "@type": "WebSite",
          "@id": "https://virungajourneys.com/#website",
          "url": "https://virungajourneys.com",
          "name": "Virunga Collective",
          "publisher": {
            "@id": "https://virungajourneys.com/#organization"
          }
        }
      ]
    }
    </script>

    <!-- Favicon & Fonts -->
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($baseLink('img/icon.png')); ?>" />
    <link rel="manifest" href="<?php echo htmlspecialchars($baseLink('manifest.json')); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" />
    <link rel="dns-prefetch" href="https://cdnjs.cloudflare.com" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=Jost:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <style>
      :root {
        --forest: #1b3a2b;
        --forest-deep: #122a1f;
        --forest-darker: #0b1912;
        --gold: #c9a24b;
        --gold-light: #dfba6b;
        --cream: #f6f2e9;
        --charcoal: #1f2620;
        --sage: #6e8270;
        --sand: #e6decb;
        --sand-light: #f0ebd8;
        --max-w: 1200px;
        --font-display: "Cormorant Garamond", Georgia, serif;
        --font-body: "Jost", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
        max-width: 100vw;
        position: relative;
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
        transition: opacity 0.5s ease, visibility 0.5s ease;
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
        background: linear-gradient(90deg, #e9e4d6 25%, #f2eee2 37%, #e9e4d6 63%);
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
      @keyframes shimmer {
        0% { background-position: 100% 0; }
        100% { background-position: -100% 0; }
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
      }
      .hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          180deg,
          rgba(18, 42, 31, 0.45) 0%,
          rgba(18, 42, 31, 0.1) 30%,
          rgba(18, 42, 31, 0.2) 55%,
          rgba(18, 42, 31, 0.88) 100%
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
        max-width: 820px;
        width: 100%;
        text-align: left;
        overflow: hidden;
      }
      .word-mask-inline {
        overflow: hidden;
        display: inline-block;
        vertical-align: top;
        margin-right: 0.22em;
      }
      [data-hero-word] {
        display: inline-block;
        transform: translateY(125%);
        opacity: 0;
        transition: transform 0.85s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.75s ease;
        will-change: transform, opacity;
      }
      [data-hero-word].in {
        transform: translateY(0);
        opacity: 1;
      }
      .hero h1 {
        max-width: 100%;
        overflow: hidden;
        font-family: var(--font-display);
        font-size: clamp(1.8rem, 3.6vw, 2.75rem);
        font-weight: 600;
        line-height: 1.16;
        margin-bottom: 14px;
        letter-spacing: 0.01em;
        text-transform: uppercase;
      }
      .eyebrow {
        font-size: 0.74rem;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      /* ---------- Buttons ---------- */
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 13px 30px;
        border: 1px solid var(--gold);
        color: var(--cream);
        background: transparent;
        text-decoration: none;
        font-size: 0.84rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
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
        border-radius: 0;
      }
      img,
      .idea-media,
      .sig-card,
      .sig-media,
      .pathway-card,
      .discovery-card,
      .discovery-media,
      .dest-card,
      .short-card,
      .people-card,
      .journal-card,
      .journal-card-image,
      .stay-gallery img {
        border-radius: 0 !important;
      }
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
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(201, 162, 75, 0.3);
      }
      .btn i,
      .btn .fa-arrow-right {
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .btn:hover .fa-arrow-right {
        transform: translateX(5px);
      }
      .btn-solid {
        background: var(--gold);
        color: var(--forest-deep);
        border: 1px solid var(--gold);
        font-weight: 600;
      }
      .btn-solid:hover {
        background: var(--gold-light);
        color: var(--forest-deep);
        box-shadow: 0 8px 25px rgba(201, 162, 75, 0.35);
      }
      .btn-forest {
        background: var(--forest);
        color: var(--cream);
        border: 1px solid var(--forest);
      }
      .btn-forest:hover {
        background: var(--forest-deep);
        color: #ffffff;
        border-color: var(--forest-deep);
      }
      .btn-outline-forest {
        background: transparent;
        color: var(--forest);
        border: 1px solid var(--forest);
      }
      .btn-outline-forest:hover {
        background: var(--forest);
        color: var(--cream);
      }
      .link-arrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: var(--font-body);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--gold);
        transition: color 0.25s, transform 0.25s;
      }
      .link-arrow i {
        transition: transform 0.25s ease;
      }
      .link-arrow:hover {
        color: var(--forest);
        transform: translateX(4px);
      }
      .link-arrow:hover i {
        transform: translateX(4px);
      }
      .link-arrow-light {
        color: var(--gold-light);
      }
      .link-arrow-light:hover {
        color: #ffffff;
      }

      /* ---------- Un-overwritable Smooth Reveal Animation System ---------- */
      .reveal,
      .reveal-card {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-left {
        opacity: 0;
        transform: translateX(-40px);
        transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-right {
        opacity: 0;
        transform: translateX(40px);
        transition: opacity 0.85s cubic-bezier(0.16, 1, 0.3, 1), transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .reveal-scale {
        opacity: 0;
        transform: scale(0.92) translateY(20px);
        transition: opacity 0.85s ease, transform 0.85s cubic-bezier(0.16, 1, 0.3, 1);
        transition-delay: var(--reveal-delay, 0s);
        will-change: opacity, transform;
      }
      .in-view.reveal,
      .in-view.reveal-left,
      .in-view.reveal-right,
      .in-view.reveal-scale,
      .in-view.reveal-card {
        opacity: 1;
        transform: translate(0, 0) scale(1);
      }

      /* Hover states apply cleanly once in view */
      .in-view.sig-card:hover,
      .in-view.discovery-card:hover,
      .in-view.dest-card:hover,
      .in-view.pathway-card:hover,
      .in-view.short-card:hover,
      .in-view.journal-card:hover,
      .in-view.people-card:hover {
        transform: translateY(-5px);
      }

      /* ---------- Typography & Section Helpers ---------- */
      .sec-title {
        font-family: var(--font-display);
        font-size: clamp(1.65rem, 2.7vw, 2.25rem);
        font-weight: 500;
        color: var(--forest);
        line-height: 1.2;
        letter-spacing: -0.01em;
      }
      .sec-title-light {
        color: var(--cream);
      }
      .sec-subtitle {
        font-size: clamp(0.94rem, 1.25vw, 1.06rem);
        color: var(--charcoal);
        margin-top: 8px;
        line-height: 1.6;
        max-width: 650px;
        opacity: 0.88;
      }
      .sec-subtitle-light {
        color: rgba(246, 242, 233, 0.85);
      }
      .sec-header {
        margin-bottom: 42px;
      }
      .sec-header.center {
        text-align: center;
      }
      .sec-header.center .sec-subtitle {
        margin-left: auto;
        margin-right: auto;
      }
      .sec-header.center .eyebrow {
        justify-content: center;
      }

      /* ---------- 02: THE IDEA BEHIND VIRUNGA COLLECTIVE ---------- */
      .sec-idea {
        padding: 95px 0;
        background: var(--cream);
      }
      .idea-grid {
        display: grid;
        grid-template-columns: 1fr 1.15fr;
        gap: 52px;
        align-items: center;
      }
      .idea-media {
        position: relative;
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(18, 42, 31, 0.12);
      }
      .idea-media img {
        width: 100%;
        height: 480px;
        object-fit: cover;
      }
      .idea-floating-badge {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(18, 42, 31, 0.88);
        color: var(--cream);
        padding: 8px 18px;
        border-radius: 0;
        font-family: var(--font-display);
        font-size: 0.95rem;
        font-style: italic;
        border: 1px solid rgba(246, 242, 233, 0.15);
        backdrop-filter: blur(8px);
      }
      .idea-content h2 {
        font-family: var(--font-display);
        font-size: clamp(1.6rem, 2.5vw, 2.15rem);
        font-weight: 500;
        color: var(--forest);
        line-height: 1.22;
        margin-bottom: 16px;
      }
      .idea-quote {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-style: italic;
        color: var(--gold);
        line-height: 1.45;
        margin-bottom: 18px;
        padding-left: 16px;
        border-left: 2px solid var(--gold);
      }
      .idea-desc {
        font-size: 0.96rem;
        color: var(--charcoal);
        line-height: 1.7;
        margin-bottom: 26px;
        opacity: 0.9;
      }

      /* ---------- 03: SIGNATURE EXPERIENCES ---------- */
      .sec-signatures {
        padding: 95px 0;
        background: #ffffff;
        border-top: 1px solid rgba(27, 58, 43, 0.08);
      }
      .signatures-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 28px;
        align-items: stretch;
      }
      .sig-card {
        background: var(--cream);
        border: 1px solid rgba(27, 58, 43, 0.08);
        border-radius: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
      }
      .sig-card:hover {
        box-shadow: 0 14px 32px rgba(18, 42, 31, 0.09);
      }
      .sig-media {
        height: 220px;
        min-height: 220px;
        max-height: 220px;
        position: relative;
        overflow: hidden;
      }
      .sig-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
      }
      .sig-card:hover .sig-media img {
        transform: scale(1.05);
      }
      .sig-num-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(18, 42, 31, 0.88);
        color: var(--gold-light);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        padding: 4px 10px;
        border-radius: 0;
        backdrop-filter: blur(6px);
      }
      .sig-pillar-tag {
        position: absolute;
        bottom: 14px;
        right: 14px;
        background: var(--gold);
        color: var(--forest-deep);
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: 0;
      }
      .sig-body {
        padding: 26px 22px;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        justify-content: space-between;
      }
      .sig-title {
        font-family: var(--font-display);
        font-size: 1.32rem;
        font-weight: 600;
        color: var(--forest);
        margin-bottom: 10px;
        line-height: 1.25;
        min-height: 2.5em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      .sig-desc {
        font-size: 0.9rem;
        color: var(--charcoal);
        line-height: 1.6;
        margin-bottom: 18px;
        opacity: 0.86;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 6.4em;
        max-height: 6.4em;
      }

      /* ---------- 04: AFTER THE GORILLAS (Modern editorial cards) ---------- */
      .sec-after-gorillas {
        position: relative;
        padding: 120px 0;
        background: linear-gradient(135deg, #173b2b, #0d2218);
        color: var(--cream);
        overflow: hidden;
      }
      .sec-after-gorillas::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
          180deg,
          rgba(11, 25, 18, 0.88) 0%,
          rgba(14, 34, 24, 0.82) 40%,
          rgba(11, 25, 18, 0.86) 75%,
          rgba(8, 20, 14, 0.94) 100%
        );
        z-index: 1;
      }
      .sec-after-gorillas .wrap {
        position: relative;
        z-index: 2;
      }
      .pathways-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 18px;
        margin-top: 42px;
      }
      .pathway-card {
        background: rgba(18, 42, 31, 0.78);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(201, 162, 75, 0.35);
        padding: 28px 18px;
        border-radius: 0;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
      }
      .pathway-card:hover {
        background: rgba(201, 162, 75, 0.25);
        border-color: var(--gold);
        box-shadow: 0 14px 32px rgba(0, 0, 0, 0.4);
      }
      .pathway-pillar {
        font-size: 0.72rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--gold-light);
        font-weight: 700;
        margin-bottom: 10px;
      }
      .pathway-name {
        font-family: var(--font-display);
        font-size: 1.18rem;
        color: #ffffff;
        line-height: 1.3;
        margin-bottom: 14px;
      }

      /* ---------- 05: PRIVATE DISCOVERIES ---------- */
      .sec-discoveries {
        padding: 95px 0;
        background: var(--cream);
      }
      .discoveries-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        align-items: stretch;
      }
      .discovery-card {
        background: #ffffff;
        border-radius: 0;
        border: 1px solid rgba(27, 58, 43, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      }
      .discovery-card:hover {
        box-shadow: 0 12px 30px rgba(18, 42, 31, 0.08);
      }
      .discovery-media {
        height: 200px;
        min-height: 200px;
        max-height: 200px;
        position: relative;
        overflow: hidden;
      }
      .discovery-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
      }
      .discovery-card:hover .discovery-media img {
        transform: scale(1.05);
      }
      .discovery-body {
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        justify-content: space-between;
      }
      .discovery-num {
        font-size: 0.72rem;
        letter-spacing: 0.14em;
        color: var(--gold);
        font-weight: 700;
        margin-bottom: 5px;
        text-transform: uppercase;
      }
      .discovery-title {
        font-family: var(--font-display);
        font-size: 1.28rem;
        font-weight: 600;
        color: var(--forest);
        margin-bottom: 8px;
        line-height: 1.25;
        min-height: 2.5em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      .discovery-tags {
        font-size: 0.74rem;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--sage);
        font-weight: 600;
        margin-bottom: 10px;
      }
      .discovery-desc {
        font-size: 0.88rem;
        color: var(--charcoal);
        line-height: 1.55;
        margin-bottom: 16px;
        opacity: 0.88;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 6.2em;
        max-height: 6.2em;
      }

      /* ---------- 06: EXPERIENCE RWANDA ---------- */
      .sec-rwanda {
        padding: 95px 0;
        background: #ffffff;
      }
      .destinations-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
      }
      .dest-card {
        position: relative;
        height: 260px;
        border-radius: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
        color: var(--cream);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      }
      .dest-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
      }
      .dest-card:hover .dest-bg {
        transform: scale(1.08);
      }
      .dest-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(8, 20, 14, 0.92) 0%, rgba(8, 20, 14, 0.25) 60%, transparent 100%);
      }
      .dest-content {
        position: relative;
        z-index: 2;
      }
      .dest-title {
        font-family: var(--font-display);
        font-size: 1.35rem;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 4px;
      }
      .dest-meta {
        font-size: 0.78rem;
        color: var(--gold-light);
        letter-spacing: 0.04em;
      }

      /* ---------- 07: SHORT VIRUNGA ENCOUNTERS ---------- */
      .sec-short {
        padding: 90px 0;
        background: var(--cream);
      }
      .short-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
      }
      .short-card {
        background: #ffffff;
        border: 1px solid rgba(27, 58, 43, 0.08);
        border-radius: 0;
        padding: 24px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
      }
      .short-card:hover {
        border-color: var(--gold);
        box-shadow: 0 10px 24px rgba(18, 42, 31, 0.08);
      }
      .short-icon {
        font-size: 1.35rem;
        color: var(--gold);
        margin-bottom: 12px;
      }
      .short-title {
        font-family: var(--font-display);
        font-size: 1.2rem;
        color: var(--forest);
        margin-bottom: 6px;
        line-height: 1.25;
      }
      .short-time {
        font-size: 0.74rem;
        color: var(--sage);
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 14px;
      }

      /* ---------- 08: VIRUNGA HOUSE ---------- */
      .sec-stay {
        padding: 95px 0;
        background: #ffffff;
      }
      .stay-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 48px;
        align-items: center;
      }
      .stay-gallery {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 14px;
      }
      .stay-gallery img {
        border-radius: 0;
        object-fit: cover;
        width: 100%;
      }
      .stay-gallery img.tall {
        height: 420px;
      }
      .stay-gallery img.short {
        height: 203px;
      }
      .stay-feature-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 20px 0 28px;
      }
      .stay-pill {
        font-size: 0.76rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 5px 12px;
        background: var(--cream);
        color: var(--forest);
        border: 1px solid rgba(27, 58, 43, 0.1);
        border-radius: 0;
        font-weight: 600;
      }

      /* ---------- 09: OUR APPROACH ---------- */
      .sec-approach {
        padding: 95px 0;
        background: var(--forest-deep);
        color: var(--cream);
      }
      .approach-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-top: 40px;
      }
      .approach-card {
        border-top: 2px solid var(--gold);
        padding-top: 20px;
      }
      .approach-title {
        font-family: var(--font-body);
        font-size: 0.8rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--gold-light);
        font-weight: 700;
        margin-bottom: 6px;
      }
      .approach-desc {
        font-family: var(--font-display);
        font-size: 1.15rem;
        font-style: italic;
        color: rgba(246, 242, 233, 0.9);
        line-height: 1.35;
      }

      /* ---------- 10: THE PEOPLE OF THE VIRUNGA ---------- */
      .sec-people {
        padding: 95px 0;
        background: var(--cream);
        overflow: hidden;
      }
      .people-carousel-wrap {
        position: relative;
        margin-top: 36px;
      }
      .people-track {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        padding: 10px 4px 20px;
      }
      .people-track::-webkit-scrollbar {
        display: none;
      }
      .people-card {
        flex: 0 0 calc(33.333% - 14px);
        min-width: 280px;
        max-width: 360px;
        scroll-snap-align: center;
        background: #ffffff;
        border-radius: 0;
        padding: 26px 20px 20px;
        border: 1px solid rgba(27, 58, 43, 0.08);
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: left;
        position: relative;
        overflow: hidden;
      }
      .people-card.active,
      .people-card:hover {
        border-color: rgba(201, 162, 75, 0.4);
        box-shadow: 0 12px 28px rgba(27, 58, 43, 0.08);
      }
      .people-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
      }
      .people-avatar-icon {
        width: 44px;
        height: 44px;
        border-radius: 0;
        background: var(--cream);
        color: var(--gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
      }
      .people-role-pill {
        font-size: 0.7rem;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--forest);
        background: var(--cream);
        padding: 3px 10px;
        border-radius: 0;
        font-weight: 600;
      }
      .people-name {
        font-family: var(--font-display);
        font-size: 1.35rem;
        color: var(--forest);
        margin-bottom: 4px;
        font-weight: 600;
      }
      .people-origin {
        font-size: 0.78rem;
        color: var(--sage);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
      }
      .people-story {
        font-size: 0.88rem;
        color: var(--charcoal);
        line-height: 1.55;
        font-style: italic;
        opacity: 0.9;
        margin-bottom: 16px;
      }
      .people-badge-tag {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 1px solid rgba(27, 58, 43, 0.06);
        font-size: 0.78rem;
        color: var(--gold);
        font-weight: 600;
      }
      .people-nav-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
      }
      .people-dots {
        display: flex;
        gap: 8px;
        align-items: center;
      }
      .people-dot {
        width: 24px;
        height: 3px;
        border-radius: 0;
        background: rgba(27, 58, 43, 0.15);
        transition: all 0.35s ease;
        cursor: pointer;
      }
      .people-dot.active {
        width: 44px;
        background: var(--gold);
      }
      .people-arrows {
        display: flex;
        gap: 8px;
      }
      .people-arrow-btn {
        width: 38px;
        height: 38px;
        border-radius: 0;
        border: 1px solid rgba(27, 58, 43, 0.15);
        background: #ffffff;
        color: var(--forest);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.25s ease;
      }
      .people-arrow-btn:hover {
        background: var(--gold);
        color: var(--forest-deep);
        border-color: var(--gold);
      }

      /* ---------- 11: THE VIRUNGA JOURNAL ---------- */
      .sec-journal {
        padding: 95px 0;
        background: #ffffff;
      }
      .journal-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        align-items: stretch;
      }
      .journal-card {
        background: var(--cream);
        border-radius: 0;
        border: 1px solid rgba(27, 58, 43, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      }
      .journal-card:hover {
        box-shadow: 0 12px 30px rgba(18, 42, 31, 0.08);
      }
      .journal-card-image {
        height: 190px;
        min-height: 190px;
        max-height: 190px;
        overflow: hidden;
      }
      .journal-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
      }
      .journal-card:hover .journal-card-image img {
        transform: scale(1.05);
      }
      .journal-card-body {
        padding: 22px 18px;
        display: flex;
        flex-direction: column;
        flex: 1 1 auto;
        justify-content: space-between;
      }
      .journal-tag {
        font-size: 0.7rem;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--gold);
        font-weight: 700;
        margin-bottom: 6px;
      }
      .journal-title {
        font-family: var(--font-display);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--forest);
        line-height: 1.3;
        margin-bottom: 10px;
        min-height: 2.6em;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      .journal-desc {
        font-size: 0.88rem;
        color: var(--charcoal);
        line-height: 1.55;
        margin-bottom: 16px;
        opacity: 0.86;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        min-height: 4.65em;
        max-height: 4.65em;
      }

      /* ---------- 12: FINAL CTA ---------- */
      .sec-final {
        position: relative;
        padding: 110px 0;
        background: #08140e url('<?php echo htmlspecialchars($baseLink('img/bg.jpg')); ?>') no-repeat center center;
        background-size: cover;
        color: var(--cream);
        text-align: center;
        overflow: hidden;
      }
      .final-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, rgba(18, 42, 31, 0.85) 0%, rgba(8, 20, 14, 0.96) 100%);
        z-index: 1;
      }
      .final-content {
        position: relative;
        z-index: 2;
        max-width: 780px;
        margin: 0 auto;
        padding: 0 20px;
      }
      .final-title {
        font-family: var(--font-display);
        font-size: clamp(1.9rem, 3.6vw, 2.7rem);
        font-weight: 500;
        color: #ffffff;
        line-height: 1.18;
        margin-bottom: 16px;
        letter-spacing: 0.01em;
      }
      .final-lead {
        font-size: clamp(0.95rem, 1.4vw, 1.1rem);
        color: rgba(246, 242, 233, 0.9);
        line-height: 1.6;
        margin-bottom: 30px;
        font-weight: 300;
      }

      /* ---------- FOOTER ---------- */
      .site-footer {
        background: #08140e;
        color: var(--cream);
        padding: 70px 0 35px;
        border-top: 1px solid rgba(201, 162, 75, 0.2);
      }
      .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1fr;
        gap: 40px;
        margin-bottom: 50px;
      }
      .footer-bottom {
        padding-top: 24px;
        border-top: 1px solid rgba(246, 242, 233, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 0.8rem;
        color: rgba(246, 242, 233, 0.5);
      }

      /* ---------- RESPONSIVE BREAKPOINTS ---------- */
      @media (max-width: 1024px) {
        .signatures-grid,
        .discoveries-grid,
        .destinations-grid,
        .journal-grid {
          grid-template-columns: repeat(2, 1fr);
        }
        .pathways-grid {
          grid-template-columns: repeat(3, 1fr);
        }
        .approach-grid {
          grid-template-columns: repeat(3, 1fr);
        }
        .short-grid {
          grid-template-columns: repeat(2, 1fr);
        }
        .people-card {
          flex: 0 0 calc(50% - 10px);
        }
        .footer-grid {
          grid-template-columns: repeat(2, 1fr);
          gap: 32px;
        }
      }

      @media (max-width: 900px) {
        .idea-grid,
        .stay-grid {
          grid-template-columns: 1fr;
          gap: 32px;
        }
        .stay-gallery {
          grid-template-columns: 1fr;
        }
        .stay-gallery img.tall,
        .stay-gallery img.short {
          height: 250px;
        }
        .hero-top {
          padding: 100px 20px 0;
        }
        .hero-content {
          padding: 0 20px 30px;
        }
      }

      @media (max-width: 680px) {
        .wrap {
          padding: 0 16px;
        }
        .sec-idea,
        .sec-signatures,
        .sec-after-gorillas,
        .sec-discoveries,
        .sec-rwanda,
        .sec-short,
        .sec-stay,
        .sec-approach,
        .sec-people,
        .sec-journal {
          padding: 60px 0;
        }
        .sec-final {
          padding: 75px 0;
        }
        .signatures-grid,
        .pathways-grid,
        .discoveries-grid,
        .destinations-grid,
        .short-grid,
        .approach-grid,
        .journal-grid,
        .footer-grid {
          grid-template-columns: 1fr;
          gap: 18px;
        }
        .people-card {
          flex: 0 0 85%;
        }
        .hero-cta-group {
          flex-direction: column;
          width: 100%;
          max-width: 320px;
          gap: 10px;
        }
        .btn {
          padding: 11px 18px;
          font-size: 0.78rem;
          letter-spacing: 0.06em;
          min-height: auto;
        }
        .hero-cta-group .btn {
          width: 100%;
        }
        .footer-bottom {
          flex-direction: column;
          text-align: center;
          gap: 10px;
        }
      }

      @media (max-width: 360px) {
        .btn {
          padding: 9px 12px;
          font-size: 0.72rem;
          letter-spacing: 0.04em;
          gap: 6px;
        }
        .hero-cta-group {
          max-width: 100%;
        }
      }

      /* Consolidated editorial homepage */
      .sec-after-gorillas { padding:64px 0; }
      .pathways-grid { grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-top:24px; }
      .pathway-modern { position:relative; min-height:140px; padding:20px; gap:12px; text-align:left; justify-content:space-between; border-radius:16px; background:#ffffff06; border-color:#ffffff20; box-shadow:none; backdrop-filter:none; }
      .pathway-modern::after { content:'\2197'; align-self:flex-end; display:grid; place-items:center; width:28px; height:28px; border-radius:50%; background:#ffffff0d; color:var(--gold-light); font-size:1.05rem; }
      .pathway-modern:hover { background:#ffffff0d; border-color:var(--gold); box-shadow:0 12px 28px #00000015; }
      .pathway-modern .pathway-name { font-size:1.25rem; margin:0; }
      .pathway-modern .pathway-pillar { font-size:.65rem; margin-bottom:8px; }
      .pathway-modern:focus-visible { outline:3px solid var(--gold-light); outline-offset:4px; }
      .section-action { text-align:center; margin-top:28px; }
      .short-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
      .sec-short,.sec-approach { padding:64px 0; }
      .short-card { padding:26px; }
      .stay-brand { font:500 1.5rem var(--font-display); color:var(--gold); margin:16px 0 12px; }
      .stay-intro { max-width:440px; margin-bottom:24px; }
      .approach-principles { display:flex; justify-content:center; flex-wrap:wrap; list-style:none; gap:12px 28px; margin:24px 0; color:var(--gold-light); letter-spacing:.08em; font-size:.86rem; }
      .approach-links { display:flex; justify-content:center; flex-wrap:wrap; gap:24px; margin-top:26px; }
      @media(max-width:900px) { .pathways-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } }
      @media(max-width:600px) { .short-grid { grid-template-columns:1fr; } .pathway-modern { min-height:130px; padding:16px; } .pathway-name { font-size:1.1rem; } }
      @media(prefers-reduced-motion:reduce) { html { scroll-behavior:auto; } *,*::before,*::after { animation:none!important; transition:none!important; } .reveal,.reveal-left,.reveal-right,.reveal-card,.reveal-scale,[data-hero-word],.word-mask-inline { opacity:1!important; transform:none!important; } }

      /* Short encounters: compact editorial cards */
      .sec-short { padding:clamp(48px,6vw,80px) 0; background:var(--cream); }
      .short-heading { display:flex; align-items:flex-end; justify-content:space-between; gap:36px; margin-bottom:32px; }
      .short-heading h2 { font:500 clamp(2.2rem,3.8vw,3.3rem)/1.08 var(--font-display); color:var(--forest); }
      .short-heading h2 em { font-weight:400; color:var(--sage); }
      .short-heading p { max-width:320px; font-size:.98rem; line-height:1.7; color:var(--sage); margin-bottom:4px; }
      .sec-short .short-grid { gap:24px; }
      .sec-short .short-modern-card { padding:0; border-radius:16px; overflow:hidden; border:1px solid #1b3a2b14; background:#fffdf8; box-shadow:0 4px 18px #122a1f05; transition:box-shadow .2s ease,border-color .2s ease; }
      .sec-short .short-modern-card:hover { transform:none; border-color:#c9a24b80; box-shadow:0 12px 28px #122a1f12; }
      .short-modern-card:focus-visible { outline:3px solid var(--gold); outline-offset:5px; }
      .short-card-top { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:24px 24px 0; }
      .short-duration { padding:7px 12px; border-radius:30px; background:#e9ede5; color:var(--forest); font-size:.75rem; font-weight:600; }
      .short-number { color:var(--gold); font:500 2rem var(--font-display); }
      .short-body { min-height:220px; padding:24px; display:flex; flex-direction:column; flex:1; }
      .short-kind { font-size:.68rem; text-transform:uppercase; letter-spacing:.12em; color:var(--sage); margin-bottom:10px; }
      .sec-short .short-title { font-size:1.65rem; font-weight:500; margin-bottom:22px; }
      .short-card-link { display:flex; justify-content:space-between; align-items:center; gap:12px; border-top:1px solid #1b3a2b18; padding-top:16px; margin-top:auto; font-size:.85rem; color:var(--forest); }
      .short-arrow { display:grid; place-items:center; width:34px; height:34px; border-radius:50%; background:#e9ede5; font-size:1.2rem; }
      .short-modern-card:hover .short-arrow { background:var(--forest); color:var(--cream); }
      .short-footer { display:flex; align-items:center; justify-content:space-between; gap:24px; border-top:1px solid #1b3a2b20; padding-top:24px; margin-top:32px; }
      .short-footer > span { font:italic 1.2rem var(--font-display); color:var(--sage); }
      @media(max-width:800px) { .short-heading { align-items:flex-start; flex-direction:column; gap:18px; } .short-heading p { max-width:520px; } .sec-short .short-grid { gap:16px; } .short-body { padding:18px; } .sec-short .short-title { font-size:1.35rem; } }
      @media(max-width:600px) { .short-footer { align-items:flex-start; flex-direction:column; gap:16px; } .short-footer .link-arrow { font-size:.72rem; } .short-card-top { padding:18px 18px 0; } }
    </style>
  </head>
  <body class="loading">
    <!-- Skeleton loader placeholder while page loads -->
    <div id="skeleton">
      <div class="sk-nav">
        <div class="sk-pill sk-logo"></div>
        <div class="sk-links">
          <div class="sk-pill sk-links"><span></span><span></span><span></span></div>
        </div>
      </div>
      <div class="sk-hero">
        <div class="sk-hero-top">
          <div class="sk-coords">
            <span class="sk-pill"></span>
            <span class="sk-pill"></span>
          </div>
        </div>
        <div class="sk-hero-content">
          <div class="sk-hero-text">
            <div class="sk-eyebrow">
              <span class="sk-pill"></span>
              <span class="sk-pill"></span>
            </div>
            <div class="sk-heading">
              <span class="sk-pill"></span>
              <span class="sk-pill"></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/header.php'; ?>

    <!-- ====================================================
         01 — HERO SECTION (Word-by-word Slide-Up Animation)
    ==================================================== -->
    <section class="hero" id="top">
      <video autoplay muted loop playsinline preload="auto">
        <source src="<?php echo htmlspecialchars($baseLink('img/hero.mp4')); ?>" type="video/mp4" />
      </video>

      <div class="hero-top">
        <div class="hero-coords">
          01.4990° S, 29.6333° E<br />
          Virunga Region, Rwanda
        </div>
      </div>

      <div class="hero-content">
        <div class="hero-text">
          <div class="eyebrow" style="margin-bottom: 14px;">
            <span>VIRUNGA COLLECTIVE</span>
          </div>
          <h1 id="heroHeading">
            <span class="word-mask-inline"><span data-hero-word>Beyond</span></span>
            <span class="word-mask-inline"><span data-hero-word>the</span></span><br />
            <span class="word-mask-inline"><span data-hero-word>Expected</span></span>
          </h1>
          <p class="hero-subheadline reveal" style="font-size: clamp(0.95rem, 1.6vw, 1.12rem); color: var(--cream); opacity: 0.95; max-width: 680px; margin-top: 12px; line-height: 1.6; --reveal-delay: 0.35s;">
            The Virunga is more than a destination to visit. Experience it through the people, stories, landscapes and living traditions that make this place extraordinary.
          </p>
          <div class="hero-cta-group reveal" style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 22px; --reveal-delay: 0.48s;">
            <a href="#signatures" class="btn btn-solid">
              EXPLORE EXPERIENCES <i class="fas fa-arrow-right"></i>
            </a>
            <a href="#planner" class="btn" style="border-color: var(--cream);">
              PLAN YOUR JOURNEY
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         02 — THE IDEA BEHIND VIRUNGA COLLECTIVE
    ==================================================== -->
    <section class="sec-idea" id="about">
      <div class="wrap">
        <div class="idea-grid">
          <div class="idea-media reveal-left">
            <img src="<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>" alt="Virunga Highlands Landscape and People" loading="lazy" decoding="async" />
            <div class="idea-floating-badge">
              Rooted in the living Virunga
            </div>
          </div>

          <div class="idea-content reveal-right" style="--reveal-delay: 0.15s;">
            <span class="eyebrow">Beyond the expected</span>
            <h2>The Virunga is more than a destination to visit.</h2>
            <div class="idea-quote">
              It is a living landscape of people, stories, creativity, conservation and traditions.
            </div>
            <p class="idea-desc">
              Virunga Collective creates meaningful ways to experience this place inviting travellers to go beyond observing, to participate, connect and leave with something of the Virunga.
            </p>
            <a href="<?php echo htmlspecialchars($baseLink('about')); ?>" class="link-arrow">
              Discover Our Story <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         03 — OUR THREE SIGNATURES (Slide-In Grid)
    ==================================================== -->
    <?php if (!empty($signature_tours)): ?>
    <section class="sec-signatures" id="signatures">
      <div class="wrap">
        <div class="sec-header center reveal">
          <h2 class="sec-title">THE VIRUNGA SIGNATURES</h2>
          <p class="sec-subtitle">
            Three defining ways to experience the Virunga.
          </p>
        </div>

        <div class="signatures-grid">
          <?php 
            $sig_idx = 0;
            foreach ($signature_tours as $sig): 
              $sig_delay = number_format(0.05 + ($sig_idx * 0.11), 2);
              $sig_idx++;
              $sig_img = get_tour_image_url($sig['cover_image_path'] ?? '', 'homestay/img/hero/1776268009_Mgahinga.jpg', $baseLink);
              $sig_open_url = $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$sig['tour_id']);
              $sig_desc = !empty($sig['short_description']) ? limit_words($sig['short_description'], 25) : '';
          ?>
          <div class="sig-card reveal-card" style="--reveal-delay: <?php echo $sig_delay; ?>s;">
            <div class="sig-media">
              <a href="<?php echo htmlspecialchars($sig_open_url); ?>" tabindex="-1" aria-hidden="true">
                <img src="<?php echo htmlspecialchars($sig_img); ?>" alt="<?php echo htmlspecialchars($sig['title']); ?>" loading="lazy" decoding="async" />
              </a>
              <div class="sig-num-badge"><?php echo htmlspecialchars($sig['badge'] ?? sprintf('%02d', $sig_idx)); ?></div>
              <?php if (!empty($sig['pillar'])): ?>
                <div class="sig-pillar-tag"><?php echo htmlspecialchars($sig['pillar']); ?></div>
              <?php endif; ?>
            </div>
            <div class="sig-body">
              <div>
                <h3 class="sig-title">
                  <a href="<?php echo htmlspecialchars($sig_open_url); ?>" style="color: inherit; text-decoration: none;">
                    <?php echo htmlspecialchars($sig['title']); ?>
                  </a>
                </h3>
                <p class="sig-desc">
                  <?php echo htmlspecialchars($sig_desc); ?>
                </p>
              </div>
              <a href="<?php echo htmlspecialchars($sig_open_url); ?>" class="link-arrow">VIEW EXPERIENCE <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="btn btn-forest">
            VIEW ALL EXPERIENCES <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ====================================================
         04 — AFTER THE GORILLAS (Modern editorial cards)
    ==================================================== -->
    <section class="sec-after-gorillas" id="after-gorillas">
      <div class="wrap">
        <div class="sec-header center">
          <h2 class="sec-title sec-title-light">THE GORILLAS ARE ONLY THE BEGINNING</h2>
          <p class="sec-subtitle sec-subtitle-light">Come for the gorillas. Stay to discover the people, landscapes, creativity and living traditions of the Virunga.</p>
        </div>
        <div class="pathways-grid">
          <a class="pathway-card pathway-modern" href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">
            <div><div class="pathway-pillar">MEET</div><h3 class="pathway-name">People &amp; Communities</h3></div>
          </a>
          <a class="pathway-card pathway-modern" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">
            <div><div class="pathway-pillar">CREATE</div><h3 class="pathway-name">Hands-on Experiences</h3></div>
          </a>
          <a class="pathway-card pathway-modern" href="<?php echo htmlspecialchars($baseLink('coffee')); ?>">
            <div><div class="pathway-pillar">TASTE</div><h3 class="pathway-name">Food &amp; Coffee</h3></div>
          </a>
          <a class="pathway-card pathway-modern" href="<?php echo htmlspecialchars($baseLink('journeys')); ?>">
            <div><div class="pathway-pillar">EXPLORE</div><h3 class="pathway-name">Mountains &amp; Nature</h3></div>
          </a>
        </div>
        <div class="section-action"><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="btn btn-solid">DISCOVER MORE</a></div>
      </div>
    </section>

    <!-- ====================================================
         05 — PRIVATE DISCOVERIES (Slide-In Grid)
    ==================================================== -->
    

    <!-- ====================================================
         06 — EXPERIENCE RWANDA (Slide-In Grid)
    ==================================================== -->
    

    <!-- ====================================================
         07 — SHORT EXPERIENCES
    ==================================================== -->
    <section class="sec-short" id="short-encounters" aria-labelledby="short-heading">
      <div class="wrap">
        <div class="short-heading">
          <div>
            <span class="eyebrow">Short Encounters</span>
            <h2 id="short-heading">Short on time.<br><em>Not short on meaning.</em></h2>
          </div>
          <p>Passing through Musanze? Discover the Virunga in 90 minutes to half a day.</p>
        </div>
        <div class="short-grid">
          <a class="short-card short-modern-card" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">
            <div class="short-card-top">
              <span class="short-duration">2–3 hours</span>
              <span class="short-number" aria-hidden="true">01</span>
            </div>
            <div class="short-body">
              <span class="short-kind">Hands-on craft</span>
              <h3 class="short-title">Short Maker’s Table</h3>
              <span class="short-card-link">View experience <span class="short-arrow" aria-hidden="true">&#8599;</span></span>
            </div>
          </a>
          <a class="short-card short-modern-card" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">
            <div class="short-card-top">
              <span class="short-duration">2 hours</span>
              <span class="short-number" aria-hidden="true">02</span>
            </div>
            <div class="short-body">
              <span class="short-kind">Artist studio</span>
              <h3 class="short-title">Short Living Canvas</h3>
              <span class="short-card-link">View experience <span class="short-arrow" aria-hidden="true">&#8599;</span></span>
            </div>
          </a>
          <a class="short-card short-modern-card" href="<?php echo htmlspecialchars($baseLink('coffee')); ?>">
            <div class="short-card-top">
              <span class="short-duration">90 minutes</span>
              <span class="short-number" aria-hidden="true">03</span>
            </div>
            <div class="short-body">
              <span class="short-kind">Roasting & cupping</span>
              <h3 class="short-title">Virunga Coffee &amp; Conversation</h3>
              <span class="short-card-link">View experience <span class="short-arrow" aria-hidden="true">&#8599;</span></span>
            </div>
          </a>
        </div>
        <div class="short-footer">
          <span>A little time. A lasting connection.</span>
          <a href="<?php echo htmlspecialchars($baseLink('short-encounters')); ?>" class="link-arrow">DISCOVER SHORT ENCOUNTERS <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         08 — VIRUNGA HOUSE
    ==================================================== -->
    <section class="sec-stay" id="stay">
      <div class="wrap">
        <div class="stay-grid">
          <div class="stay-gallery reveal-left">
            <img src="<?php echo htmlspecialchars($baseLink('img/HO2A1241.jpg')); ?>" alt="Virunga House Sanctuary Room" class="tall" loading="lazy" decoding="async" />
            <div style="display: flex; flex-direction: column; gap: 14px;">
              <img src="<?php echo htmlspecialchars($baseLink('img/room.jpeg')); ?>" alt="Virunga House Suite" class="short" loading="lazy" decoding="async" />
              <img src="<?php echo htmlspecialchars($baseLink('img/room2.jpeg')); ?>" alt="Virunga House Bedroom" class="short" loading="lazy" decoding="async" />
            </div>
          </div>

          <div class="reveal-right" style="--reveal-delay: 0.15s;">
            <span class="eyebrow">The Virunga Experience Stay</span>
            <h2 class="sec-title">STAY CLOSE TO THE STORY</h2>
            <h3 class="stay-brand">VIRUNGA HOUSE</h3>
            <p class="stay-intro">A locally rooted boutique stay where staying becomes part of discovering the Virunga.</p>
            <a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>" class="btn btn-forest">DISCOVER VIRUNGA HOUSE</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         09 — OUR APPROACH
    ==================================================== -->
    <section class="sec-approach" id="approach">
      <div class="wrap">
        <div class="sec-header center">
          <h2 class="sec-title sec-title-light">OUR APPROACH</h2>
          <ul class="approach-principles" aria-label="Our five principles">
            <li>PRIVATE</li><li>PARTICIPATORY</li><li>PERSONAL</li><li>ROOTED</li><li>CONSCIOUS</li>
          </ul>
          <p class="sec-subtitle sec-subtitle-light">We design journeys around people, place and genuine connection&mdash;not checklists.</p>
          <div class="approach-links"><a href="<?php echo htmlspecialchars($baseLink('about#the-virunga-way')); ?>" class="link-arrow link-arrow-light">OUR APPROACH</a><a href="<?php echo htmlspecialchars($baseLink('about#people')); ?>" class="link-arrow link-arrow-light">MEET OUR PEOPLE</a></div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         10 — THE PEOPLE BEHIND THE JOURNEY (Restored Team Carousel)
    ==================================================== -->
    

    <!-- ====================================================
         11 — THE VIRUNGA JOURNAL
    ==================================================== -->
    <?php if (!empty($featured_blogs)): ?>
    <section class="sec-journal" id="journal">
      <div class="wrap">
        <div class="sec-header center reveal">
          <span class="eyebrow">Editorial & Field Notes</span>
          <h2 class="sec-title">THE VIRUNGA JOURNAL</h2>
          <p class="sec-subtitle">
            Stories, field notes and perspectives from the volcanic highlands.
          </p>
        </div>

        <div class="journal-grid">
          <?php foreach ($featured_blogs as $idx => $post): ?>
            <?php
              $delay = number_format(0.05 + ($idx * 0.12), 2);
              $cover = !empty($post['cover_image']) ? $post['cover_image'] : '';
              $catName = !empty($post['category_name']) ? $post['category_name'] : 'The Journal';
              $intro = substr(strip_tags($post['introduction'] ?? ''), 0, 130);
              if (strlen(strip_tags($post['introduction'] ?? '')) > 130) {
                $intro .= '...';
              }
              $openUrl = $baseLink('ecotours/pages/blogopen.php?id=' . (int)$post['blog_id']);
            ?>
            <div class="journal-card reveal-card" style="--reveal-delay: <?php echo $delay; ?>s;">
              <?php if ($cover): ?>
                <div class="journal-card-image">
                  <img src="<?php echo htmlspecialchars($baseLink('ecotours/admin/images/blog/covers/' . $cover)); ?>" alt="<?php echo htmlspecialchars(stripslashes($post['title'])); ?>" loading="lazy" decoding="async" />
                </div>
              <?php endif; ?>
              <div class="journal-card-body">
                <div>
                  <div class="journal-tag"><?php echo htmlspecialchars(strtoupper(stripslashes($catName))); ?></div>
                  <h4 class="journal-title"><?php echo htmlspecialchars(stripslashes($post['title'])); ?></h4>
                  <p class="journal-desc"><?php echo htmlspecialchars(stripslashes($intro)); ?></p>
                </div>
                <div>
                  <a href="<?php echo htmlspecialchars($openUrl); ?>" class="link-arrow">Read Story <i class="fas fa-arrow-right"></i></a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 36px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/blog.php')); ?>" class="btn btn-outline-forest">
            EXPLORE THE JOURNAL <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ====================================================
         12 — FINAL CTA (Modular Component)
    ==================================================== -->
    <?php
      $cta_id = 'planner';
      $cta_eyebrow = 'VIRUNGA COLLECTIVE';
      $cta_title = 'READY TO EXPERIENCE THE VIRUNGA?';
      $cta_lead = 'Tell us what you\'re curious about, how much time you have, and what you want to discover. We\'ll help you find the right way into the Virunga.';
      $cta_primary_text = 'PLAN YOUR JOURNEY';
      $cta_primary_url = 'https://wa.me/250784513435?text=' . urlencode('Hello Virunga Collective, I would like to plan my journey.');
      $cta_secondary_text = 'DISCOVER VIRUNGA HOUSE';
      $cta_secondary_url = $baseLink('homestays');
      include __DIR__ . '/cta.php';
    ?>

    <!-- ====================================================
         FOOTER
    ==================================================== -->
        <?php include __DIR__ . '/footer.php'; ?>

    <!-- Animations & Interactivity Scripts -->
    <script>
      // Sequential word-by-word reveal in the Hero title
      function revealHero() {
        const words = document.querySelectorAll("[data-hero-word]");
        words.forEach((w, i) => {
          setTimeout(() => {
            w.classList.add("in");
          }, 60 + (i * 130));
        });
      }

      window.addEventListener("load", () => {
        setTimeout(() => {
          const sk = document.getElementById("skeleton");
          if (sk) sk.classList.add("hide");
          document.body.classList.remove("loading");
          revealHero();
        }, 250);
      });

      setTimeout(() => {
        if (document.body.classList.contains("loading")) {
          const sk = document.getElementById("skeleton");
          if (sk) sk.classList.add("hide");
          document.body.classList.remove("loading");
          revealHero();
        }
      }, 2500);

      // Staggered reveals for cards across all sections
      const STAGGER_STEP = 0.12;
      function setupStaggerGroup(groupSelector, itemSelector) {
        document.querySelectorAll(groupSelector).forEach((group) => {
          const items = Array.from(group.querySelectorAll(itemSelector));
          items.forEach((el, i) => {
            el.style.setProperty("--reveal-delay", i * STAGGER_STEP + "s");
          });
        });
      }

      setupStaggerGroup(".signatures-grid", ".sig-card");
      setupStaggerGroup(".pathways-grid", ".pathway-card");
      setupStaggerGroup(".discoveries-grid", ".discovery-card");
      setupStaggerGroup(".destinations-grid", ".dest-card");
      setupStaggerGroup(".short-grid", ".short-card");
      setupStaggerGroup(".approach-grid", ".approach-card");
      setupStaggerGroup(".journal-grid", ".journal-card");

      // Auto-Scrolling Spotlight Carousel for People of Virunga
      // Intersection Observer for scroll-triggered slide-in animations
      function initScrollReveal() {
        const revealElements = document.querySelectorAll(
          '.reveal, .reveal-left, .reveal-right, .reveal-scale, .reveal-card'
        );
        if (!revealElements.length) return;

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

        revealElements.forEach((el) => observer.observe(el));
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
          initScrollReveal();
        });
      } else {
        initScrollReveal();
      }
    </script>
  </body>
</html>
