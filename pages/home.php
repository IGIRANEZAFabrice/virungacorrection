<?php
  require_once __DIR__ . '/../config/localization.php';
  $loc = get_localization_data();

  // Database queries for dynamic homepage content
  $featured_blogs = [];
  $featured_tours = [];
  $signature_tours = [];
  $discovery_tours = [];

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

  try {
    if (file_exists(__DIR__ . '/../ecotours/admin/config/connection.php')) {
      require_once __DIR__ . '/../ecotours/admin/config/connection.php';
      if (isset($conn) && $conn instanceof mysqli && !$conn->connect_error) {
        // Fetch published blog stories from DB for the Journal section
        $b_query = "SELECT bp.blog_id, bp.title, bp.introduction, bp.cover_image, bp.author, bp.read_minutes, bp.published_at, bp.created_at, bc.category_name 
                    FROM blog_posts bp
                    JOIN blog_categories bc ON bp.category_id = bc.category_id
                    WHERE bp.status = 'published' 
                    ORDER BY bp.published_at DESC, bp.created_at DESC
                    LIMIT 3";
        $b_res = $conn->query($b_query);
        if ($b_res) {
          while ($b_row = $b_res->fetch_assoc()) {
            $featured_blogs[] = $b_row;
          }
        }

        // Fetch latest multi-day Rwanda tours from DB
        $t_query = "SELECT tour_id, title, country, days_count, category, cover_image_path, short_description, created_at 
                    FROM tours 
                    WHERE LOWER(country) = 'rwanda' AND days_count > 1 
                    ORDER BY created_at DESC 
                    LIMIT 3";
        $t_res = $conn->query($t_query);
        if ($t_res) {
          while ($t_row = $t_res->fetch_assoc()) {
            $featured_tours[] = $t_row;
          }
        }

        // Fetch homepage tour cards by category instead of matching exact tour names.
        $sig_meta = [
          ['key' => 'salon', 'badge' => '01', 'pillar' => 'UNDERSTAND'],
          ['key' => 'canvas', 'badge' => '02', 'pillar' => 'CREATE'],
          ['key' => 'table', 'badge' => '03', 'pillar' => 'TASTE']
        ];
        $sig_stmt = $conn->prepare("SELECT tour_id, title, country, days_count, category, cover_image_path, short_description, created_at
                                    FROM tours
                                    WHERE TRIM(LOWER(category)) = 'signature journeys'
                                    ORDER BY created_at DESC
                                    LIMIT 3");
        if ($sig_stmt && $sig_stmt->execute()) {
          $sig_res = $sig_stmt->get_result();
          $sig_idx = 0;
          while ($sig_row = $sig_res->fetch_assoc()) {
            $meta = $sig_meta[$sig_idx] ?? [
              'key' => 'signature_' . ($sig_idx + 1),
              'badge' => sprintf('%02d', $sig_idx + 1),
              'pillar' => ''
            ];
            $signature_tours[$meta['key']] = array_merge($sig_row, [
              'badge' => $meta['badge'],
              'pillar' => $meta['pillar']
            ]);
            $sig_idx++;
          }
          $sig_stmt->close();
        }

        $disc_meta = [
          ['key' => 'brewing', 'badge' => '04 - Experience', 'tags' => 'MAKE - SHARE'],
          ['key' => 'maker', 'badge' => '05 - Experience', 'tags' => 'MAKE - TAKE HOME'],
          ['key' => 'family', 'badge' => '06 - Experience', 'tags' => 'MEET - SHARE']
        ];
        $disc_stmt = $conn->prepare("SELECT tour_id, title, country, days_count, category, cover_image_path, short_description, created_at
                                     FROM tours
                                     WHERE TRIM(LOWER(category)) = 'private experiences'
                                     ORDER BY created_at DESC
                                     LIMIT 3");
        if ($disc_stmt && $disc_stmt->execute()) {
          $disc_res = $disc_stmt->get_result();
          $disc_idx = 0;
          while ($disc_row = $disc_res->fetch_assoc()) {
            $meta = $disc_meta[$disc_idx] ?? [
              'key' => 'discovery_' . ($disc_idx + 1),
              'badge' => sprintf('%02d - Experience', $disc_idx + 4),
              'tags' => ''
            ];
            $discovery_tours[$meta['key']] = array_merge($disc_row, [
              'badge' => $meta['badge'],
              'tags' => $meta['tags']
            ]);
            $disc_idx++;
          }
          $disc_stmt->close();
        }

        // Target titles definition with badges and pillars
        $sig_defs = [
          'salon' => [
            'exact_title' => 'The Virunga Conservation Salon',
            'badge' => '01',
            'pillar' => 'UNDERSTAND',
            'keywords' => ['conservation salon']
          ],
          'canvas' => [
            'exact_title' => 'The Virunga Living Canvas',
            'badge' => '02',
            'pillar' => 'CREATE',
            'keywords' => ['living canvas']
          ],
          'table' => [
            'exact_title' => 'The Virunga Table',
            'badge' => '03',
            'pillar' => 'TASTE',
            'keywords' => ['the virunga table', 'virunga table']
          ]
        ];

        $disc_defs = [
          'brewing' => [
            'exact_title' => 'The Virunga Brewing Table',
            'badge' => '04 — Experience',
            'tags' => 'MAKE • SHARE',
            'keywords' => ['brewing table']
          ],
          'maker' => [
            'exact_title' => 'The Virunga Maker’s Table',
            'badge' => '05 — Experience',
            'tags' => 'MAKE • TAKE HOME',
            'keywords' => ['maker’s table', "maker's table", 'maker table']
          ],
          'family' => [
            'exact_title' => 'The Virunga Family Table',
            'badge' => '06 — Experience',
            'tags' => 'MEET • SHARE',
            'keywords' => ['family table']
          ]
        ];

        // Fetch tours from DB matching target titles
        $all_tours_res = false;
        if ($all_tours_res) {
          $db_tours = [];
          while ($row = $all_tours_res->fetch_assoc()) {
            $db_tours[] = $row;
          }

          // Match Signature tours
          foreach ($sig_defs as $key => $def) {
            foreach ($db_tours as $t) {
              $t_title_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $t['title'])));
              $def_title_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $def['exact_title'])));
              
              $matched = false;
              if ($t_title_clean === $def_title_clean) {
                $matched = true;
              } else {
                foreach ($def['keywords'] as $kw) {
                  $kw_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $kw)));
                  if (strpos($t_title_clean, $kw_clean) !== false) {
                    if ($key === 'table' && (strpos($t_title_clean, 'brewing') !== false || strpos($t_title_clean, 'family') !== false || strpos($t_title_clean, 'maker') !== false)) {
                      continue;
                    }
                    $matched = true;
                    break;
                  }
                }
              }

              if ($matched) {
                $signature_tours[$key] = array_merge($t, [
                  'badge' => $def['badge'],
                  'pillar' => $def['pillar']
                ]);
                break;
              }
            }
          }

          // Match Discovery tours
          foreach ($disc_defs as $key => $def) {
            foreach ($db_tours as $t) {
              $t_title_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $t['title'])));
              $def_title_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $def['exact_title'])));
              
              $matched = false;
              if ($t_title_clean === $def_title_clean) {
                $matched = true;
              } else {
                foreach ($def['keywords'] as $kw) {
                  $kw_clean = mb_strtolower(trim(str_replace(['’', '‘', '`'], "'", $kw)));
                  if (strpos($t_title_clean, $kw_clean) !== false) {
                    $matched = true;
                    break;
                  }
                }
              }

              if ($matched) {
                $discovery_tours[$key] = array_merge($t, [
                  'badge' => $def['badge'],
                  'tags' => $def['tags']
                ]);
                break;
              }
            }
          }
        }
      }
    }
  } catch (Throwable $e) {
    // Graceful fallback if database connection encounters an error
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
          "description": "Private encounters with the people, stories, landscapes and living traditions of the Virunga.",
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
      }
      .sig-card {
        background: var(--cream);
        border: 1px solid rgba(27, 58, 43, 0.08);
        border-radius: 0;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
      }
      .sig-card:hover {
        box-shadow: 0 14px 32px rgba(18, 42, 31, 0.09);
      }
      .sig-media {
        height: 220px;
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
        flex-grow: 1;
        justify-content: space-between;
      }
      .sig-title {
        font-family: var(--font-display);
        font-size: 1.32rem;
        font-weight: 600;
        color: var(--forest);
        margin-bottom: 10px;
        line-height: 1.25;
      }
      .sig-desc {
        font-size: 0.9rem;
        color: var(--charcoal);
        line-height: 1.6;
        margin-bottom: 18px;
        opacity: 0.86;
      }

      /* ---------- 04: AFTER THE GORILLAS (Distinct Background Image & Glass Cards) ---------- */
      .sec-after-gorillas {
        position: relative;
        padding: 120px 0;
        background: #0d2218 url('<?php echo htmlspecialchars($baseLink('homestay/img/activities/1778348756_silvereat.jpeg')); ?>') no-repeat center 35%;
        background-size: cover;
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
      }
      .discovery-card {
        background: #ffffff;
        border-radius: 0;
        border: 1px solid rgba(27, 58, 43, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      }
      .discovery-card:hover {
        box-shadow: 0 12px 30px rgba(18, 42, 31, 0.08);
      }
      .discovery-media {
        height: 200px;
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
        flex-grow: 1;
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
      }
      .journal-card {
        background: var(--cream);
        border-radius: 0;
        border: 1px solid rgba(27, 58, 43, 0.08);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      }
      .journal-card:hover {
        box-shadow: 0 12px 30px rgba(18, 42, 31, 0.08);
      }
      .journal-card-image {
        height: 190px;
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
        flex-grow: 1;
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
      }
      .journal-desc {
        font-size: 0.88rem;
        color: var(--charcoal);
        line-height: 1.55;
        margin-bottom: 16px;
        opacity: 0.86;
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
            <span class="word-mask-inline"><span data-hero-word>Experience</span></span>
            <span class="word-mask-inline"><span data-hero-word>the</span></span>
            <span class="word-mask-inline"><span data-hero-word>Virunga</span></span>
            <br />
            <span class="word-mask-inline"><span data-hero-word>Differently</span></span>
          </h1>
          <p class="hero-subheadline reveal" style="font-size: clamp(0.95rem, 1.6vw, 1.12rem); color: var(--cream); opacity: 0.95; max-width: 680px; margin-top: 12px; line-height: 1.6; --reveal-delay: 0.35s;">
            Private encounters with the people, stories, landscapes and living traditions of the Virunga.
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
              “It is a landscape of people, stories, creativity, conservation and living traditions.”
            </div>
            <p class="idea-desc">
              Virunga Collective creates intimate experiences that invite travellers to do more than observe—to participate, connect and leave with something of the place.
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
            Three experiences that define the way we invite you into the Virunga.
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
              $sig_desc = !empty($sig['short_description']) ? $sig['short_description'] : '';
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
              <a href="<?php echo htmlspecialchars($sig_open_url); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/itenary.php?country=rwanda')); ?>" class="btn btn-forest">
            View All Signature Experiences <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ====================================================
         04 — AFTER THE GORILLAS (With Visible Background Image & Glass Cards)
    ==================================================== -->
    <section class="sec-after-gorillas" id="after-gorillas">
      <div class="wrap">
        <div class="sec-header center reveal">
          <h2 class="sec-title sec-title-light">THE GORILLAS ARE ONLY THE BEGINNING</h2>
          <p class="sec-subtitle sec-subtitle-light">
            After the forest, there is another side of the Virunga waiting to be discovered. Choose your way to continue:
          </p>
        </div>

        <div class="pathways-grid">
          <!-- Understand -->
          <?php 
            $p1_url = isset($signature_tours['salon']) 
              ? $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$signature_tours['salon']['tour_id']) 
              : $baseLink('ecotours/pages/itenary.php?country=rwanda'); 
          ?>
          <div class="pathway-card reveal-card" style="--reveal-delay: 0.05s;">
            <div>
              <div class="pathway-pillar">UNDERSTAND</div>
              <h3 class="pathway-name"><?php echo htmlspecialchars($signature_tours['salon']['title'] ?? 'The Virunga Conservation Salon'); ?></h3>
            </div>
            <a href="<?php echo htmlspecialchars($p1_url); ?>" class="link-arrow link-arrow-light">Discover <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Create -->
          <?php 
            $p2_url = isset($signature_tours['canvas']) 
              ? $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$signature_tours['canvas']['tour_id']) 
              : $baseLink('ecotours/pages/itenary.php?country=rwanda'); 
          ?>
          <div class="pathway-card reveal-card" style="--reveal-delay: 0.12s;">
            <div>
              <div class="pathway-pillar">CREATE</div>
              <h3 class="pathway-name"><?php echo htmlspecialchars($signature_tours['canvas']['title'] ?? 'The Virunga Living Canvas'); ?></h3>
            </div>
            <a href="<?php echo htmlspecialchars($p2_url); ?>" class="link-arrow link-arrow-light">Discover <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Make -->
          <?php 
            $p3_url = isset($discovery_tours['maker']) 
              ? $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$discovery_tours['maker']['tour_id']) 
              : $baseLink('ecotours/pages/itenary.php?country=rwanda'); 
          ?>
          <div class="pathway-card reveal-card" style="--reveal-delay: 0.19s;">
            <div>
              <div class="pathway-pillar">MAKE</div>
              <h3 class="pathway-name"><?php echo htmlspecialchars($discovery_tours['maker']['title'] ?? 'The Virunga Maker’s Table'); ?></h3>
            </div>
            <a href="<?php echo htmlspecialchars($p3_url); ?>" class="link-arrow link-arrow-light">Discover <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Share -->
          <?php 
            $p4_url = isset($discovery_tours['family']) 
              ? $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$discovery_tours['family']['tour_id']) 
              : $baseLink('ecotours/pages/itenary.php?country=rwanda'); 
          ?>
          <div class="pathway-card reveal-card" style="--reveal-delay: 0.26s;">
            <div>
              <div class="pathway-pillar">SHARE</div>
              <h3 class="pathway-name"><?php echo htmlspecialchars($discovery_tours['family']['title'] ?? 'The Virunga Family Table'); ?></h3>
            </div>
            <a href="<?php echo htmlspecialchars($p4_url); ?>" class="link-arrow link-arrow-light">Discover <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Taste -->
          <?php 
            $p5_url = isset($signature_tours['table']) 
              ? $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$signature_tours['table']['tour_id']) 
              : $baseLink('ecotours/pages/coffee.php'); 
          ?>
          <div class="pathway-card reveal-card" style="--reveal-delay: 0.33s;">
            <div>
              <div class="pathway-pillar">TASTE</div>
              <h3 class="pathway-name"><?php echo htmlspecialchars($signature_tours['table']['title'] ?? 'The Virunga Table'); ?></h3>
            </div>
            <a href="<?php echo htmlspecialchars($p5_url); ?>" class="link-arrow link-arrow-light">Discover <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/itenary.php?country=rwanda')); ?>" class="btn btn-solid">
            Discover What Comes After the Trek <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         05 — PRIVATE DISCOVERIES (Slide-In Grid)
    ==================================================== -->
    <?php if (!empty($discovery_tours)): ?>
    <section class="sec-discoveries" id="discoveries">
      <div class="wrap">
        <div class="sec-header center reveal">
          <h2 class="sec-title">PRIVATE DISCOVERIES</h2>
          <p class="sec-subtitle">
            Participate, rather than simply observe. Intimate hands-on encounters with living highland traditions.
          </p>
        </div>

        <div class="discoveries-grid">
          <?php 
            $disc_idx = 0;
            foreach ($discovery_tours as $disc): 
              $disc_delay = number_format(0.05 + ($disc_idx * 0.11), 2);
              $disc_idx++;
              $disc_img = get_tour_image_url($disc['cover_image_path'] ?? '', 'homestay/img/activities/1778346998_coffee.jpeg', $baseLink);
              $disc_open_url = $baseLink('ecotours/pages/itenaryopen.php?id=' . (int)$disc['tour_id']);
              $disc_desc = !empty($disc['short_description']) ? $disc['short_description'] : '';
          ?>
          <div class="discovery-card reveal-card" style="--reveal-delay: <?php echo $disc_delay; ?>s;">
            <div class="discovery-media">
              <a href="<?php echo htmlspecialchars($disc_open_url); ?>" tabindex="-1" aria-hidden="true">
                <img src="<?php echo htmlspecialchars($disc_img); ?>" alt="<?php echo htmlspecialchars($disc['title']); ?>" loading="lazy" decoding="async" />
              </a>
            </div>
            <div class="discovery-body">
              <div>
                <div class="discovery-num"><?php echo htmlspecialchars($disc['badge'] ?? sprintf('%02d — Experience', $disc_idx + 3)); ?></div>
                <h3 class="discovery-title">
                  <a href="<?php echo htmlspecialchars($disc_open_url); ?>" style="color: inherit; text-decoration: none;">
                    <?php echo htmlspecialchars($disc['title']); ?>
                  </a>
                </h3>
                <?php if (!empty($disc['tags'])): ?>
                  <div class="discovery-tags"><?php echo htmlspecialchars($disc['tags']); ?></div>
                <?php endif; ?>
                <p class="discovery-desc">
                  <?php echo htmlspecialchars($disc_desc); ?>
                </p>
              </div>
              <a href="<?php echo htmlspecialchars($disc_open_url); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 36px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/itenary.php?country=rwanda')); ?>" class="btn btn-outline-forest">
            View Private Discoveries <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ====================================================
         06 — EXPERIENCE RWANDA (Slide-In Grid)
    ==================================================== -->
    <section class="sec-rwanda" id="rwanda">
      <div class="wrap">
        <div class="sec-header center reveal">
          <h2 class="sec-title">EXPERIENCE RWANDA</h2>
          <p class="sec-subtitle">
            Beyond the Virunga, discover another side of Rwanda.
          </p>
        </div>

        <div class="destinations-grid">
          <!-- 1: Volcanoes -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.04s;">
            <img src="<?php echo htmlspecialchars($baseLink('homestay/img/hero/1776268009_Mgahinga.jpg')); ?>" alt="Volcanoes National Park" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">The Volcanoes</h3>
              <div class="dest-meta">Gorillas • Golden Monkeys • Volcanoes</div>
            </div>
          </div>

          <!-- 2: Nyungwe -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.11s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>" alt="Nyungwe Rainforest" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Nyungwe</h3>
              <div class="dest-meta">Chimpanzees • Forest • Canopy</div>
            </div>
          </div>

          <!-- 3: Akagera -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.18s;">
            <img src="<?php echo htmlspecialchars($baseLink('homestay/img/activities/1778427637_twinlake.jpeg')); ?>" alt="Akagera Savanna" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Akagera</h3>
              <div class="dest-meta">Wildlife • Big Five • Lake</div>
            </div>
          </div>

          <!-- 4: Gishwati -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.25s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>" alt="Gishwati Forest" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Gishwati</h3>
              <div class="dest-meta">Forest • Nature • Birdlife</div>
            </div>
          </div>

          <!-- 5: Nyandungu -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.32s;">
            <img src="<?php echo htmlspecialchars($baseLink('homestay/img/activities/1778346998_coffee.jpeg')); ?>" alt="Nyandungu Eco-Park" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Nyandungu</h3>
              <div class="dest-meta">Wetland • Nature • Birdlife</div>
            </div>
          </div>

          <!-- 6: Buhanga -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.39s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/cta.jpeg')); ?>" alt="Buhanga Eco-Park" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Buhanga</h3>
              <div class="dest-meta">Forest • Ecology • Heritage</div>
            </div>
          </div>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('ecotours/pages/itenary.php?country=rwanda')); ?>" class="btn btn-forest">
            Explore Rwanda <i class="fas fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- ====================================================
         07 — SHORT EXPERIENCES
    ==================================================== -->
    <section class="sec-short" id="short-encounters">
      <div class="wrap">
        <div class="sec-header center reveal">
          <span class="eyebrow">Short Encounters</span>
          <h2 class="sec-title">SHORT ON TIME. NOT SHORT ON MEANING.</h2>
          <p class="sec-subtitle">
            Passing through Musanze? You do not need another full day to encounter the Virunga differently.
          </p>
        </div>

        <div class="short-grid">
          <!-- Short 1 -->
          <div class="short-card reveal-card" style="--reveal-delay: 0.05s;">
            <div>
              <div class="short-icon"><i class="fas fa-hammer"></i></div>
              <h3 class="short-title">Short Maker’s Table</h3>
              <div class="short-time">2–3 Hours · Hands-on Craft</div>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Short 2 -->
          <div class="short-card reveal-card" style="--reveal-delay: 0.12s;">
            <div>
              <div class="short-icon"><i class="fas fa-palette"></i></div>
              <h3 class="short-title">Short Living Canvas</h3>
              <div class="short-time">2 Hours · Artist Studio</div>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Short 3 -->
          <div class="short-card reveal-card" style="--reveal-delay: 0.19s;">
            <div>
              <div class="short-icon"><i class="fas fa-mug-hot"></i></div>
              <h3 class="short-title">Virunga Coffee & Conversation</h3>
              <div class="short-time">90 Min · Roasting & Cupping</div>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('coffee')); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
          </div>

          <!-- Short 4 -->
          <div class="short-card reveal-card" style="--reveal-delay: 0.26s;">
            <div>
              <div class="short-icon"><i class="fas fa-wine-glass"></i></div>
              <h3 class="short-title">Short Brewing Table</h3>
              <div class="short-time">2 Hours · Traditional Brewing</div>
            </div>
            <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="link-arrow">BEGIN YOUR JOURNEY <i class="fas fa-arrow-right"></i></a>
          </div>
        </div>

        <div style="text-align: center; margin-top: 36px;" class="reveal">
          <a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>" class="btn btn-outline-forest">
            Discover Short Encounters <i class="fas fa-arrow-right"></i>
          </a>
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
            <span class="eyebrow">Sanctuary & Base</span>
            <h2 class="sec-title" style="margin-bottom: 10px;">STAY CLOSE TO THE STORY</h2>
            <div style="font-family: var(--font-display); font-size: 1.22rem; font-style: italic; color: var(--gold); margin-bottom: 14px;">
              Virunga House — Your base for discovering the Virunga through people, place and experience.
            </div>
            <p style="font-size: 0.94rem; color: var(--charcoal); line-height: 1.65; opacity: 0.9;">
              A place to slow down between journeys, share a meal, watch the volcanoes change with the light, and return from the day’s discoveries in complete tranquility.
            </p>

            <div class="stay-feature-list">
              <span class="stay-pill">Accommodation</span>
              <span class="stay-pill">Dining</span>
              <span class="stay-pill">Fire-side evenings</span>
              <span class="stay-pill">Private experiences</span>
              <span class="stay-pill">Retreats / gatherings</span>
            </div>

            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
              <a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>" class="btn btn-forest">
                Discover Virunga House <i class="fas fa-arrow-right"></i>
              </a>
              <a href="<?php echo htmlspecialchars($baseLink('rooms')); ?>" class="btn btn-outline-forest">
                Book Your Stay
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         09 — OUR APPROACH
    ==================================================== -->
    <section class="sec-approach" id="approach">
      <div class="wrap">
        <div class="sec-header center reveal">
          <span class="eyebrow" style="color: var(--gold-light); justify-content: center;">Our Approach</span>
          <h2 class="sec-title sec-title-light">NOT JUST SOMETHING TO SEE. SOMETHING TO SHARE.</h2>
        </div>

        <div class="approach-grid">
          <!-- 1: Private -->
          <div class="approach-card reveal-card" style="--reveal-delay: 0.05s;">
            <div class="approach-title">PRIVATE</div>
            <div class="approach-desc">Designed around you.</div>
          </div>

          <!-- 2: Participatory -->
          <div class="approach-card reveal-card" style="--reveal-delay: 0.12s;">
            <div class="approach-title">PARTICIPATORY</div>
            <div class="approach-desc">You become part of the moment.</div>
          </div>

          <!-- 3: Personal -->
          <div class="approach-card reveal-card" style="--reveal-delay: 0.19s;">
            <div class="approach-title">PERSONAL</div>
            <div class="approach-desc">Every encounter has a human story.</div>
          </div>

          <!-- 4: Rooted -->
          <div class="approach-card reveal-card" style="--reveal-delay: 0.26s;">
            <div class="approach-title">ROOTED</div>
            <div class="approach-desc">Connected to the living Virunga.</div>
          </div>

          <!-- 5: Conscious -->
          <div class="approach-card reveal-card" style="--reveal-delay: 0.33s;">
            <div class="approach-title">CONSCIOUS</div>
            <div class="approach-desc">Tourism that values people and place.</div>
          </div>
        </div>
      </div>
    </section>

    <!-- ====================================================
         10 — THE PEOPLE BEHIND THE JOURNEY (Restored Team Carousel)
    ==================================================== -->
    <section class="sec-people" id="team">
      <div class="wrap">
        <div class="sec-header center reveal">
          <span class="eyebrow" style="justify-content: center;">Our People</span>
          <h2 class="sec-title">THE PEOPLE BEHIND THE JOURNEY</h2>
          <p class="sec-subtitle">
            The passionate storytellers, guides, coffee growers, hearth keepers, and cultural artists who bring your journey to life.
          </p>
        </div>

        <div class="people-carousel-wrap">
          <div class="people-track" id="peopleTrack">
            <!-- 1: Fabrice -->
            <div class="people-card active" data-person="0">
              <div class="people-card-top">
                <div class="people-avatar-icon">
                  <i class="fas fa-compass"></i>
                </div>
                <span class="people-role-pill">Founder & Concierge</span>
              </div>
              <div>
                <h4 class="people-name">Fabrice</h4>
                <div class="people-origin"><i class="fas fa-location-dot"></i> Musanze Base</div>
                <p class="people-story">“10+ years designing private cultural and wilderness journeys across Rwanda, Uganda, and DRC.”</p>
              </div>
              <div class="people-badge-tag">
                <span>Journey Architect</span>
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>

            <!-- 2: Gervais -->
            <div class="people-card" data-person="1">
              <div class="people-card-top">
                <div class="people-avatar-icon">
                  <i class="fas fa-mountain-sun"></i>
                </div>
                <span class="people-role-pill">Master Tracker</span>
              </div>
              <div>
                <h4 class="people-name">Gervais</h4>
                <div class="people-origin"><i class="fas fa-location-dot"></i> Kinigi Headquarters</div>
                <p class="people-story">“Deep generational instinct tracing mountain gorilla families through the bamboo mist.”</p>
              </div>
              <div class="people-badge-tag">
                <span>Wildlife Naturalist</span>
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>

            <!-- 3: Jean-Pierre -->
            <div class="people-card" data-person="2">
              <div class="people-card-top">
                <div class="people-avatar-icon">
                  <i class="fas fa-seedling"></i>
                </div>
                <span class="people-role-pill">The Grower</span>
              </div>
              <div>
                <h4 class="people-name">Jean-Pierre</h4>
                <div class="people-origin"><i class="fas fa-location-dot"></i> Mount Bisoke Slopes</div>
                <p class="people-story">“Cultivates award-winning Bourbon coffee cherries on mineral-rich volcanic high slopes.”</p>
              </div>
              <div class="people-badge-tag">
                <span>Highland Coffee Master</span>
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>

            <!-- 4: Claudine -->
            <div class="people-card" data-person="3">
              <div class="people-card-top">
                <div class="people-avatar-icon">
                  <i class="fas fa-fire-burner"></i>
                </div>
                <span class="people-role-pill">The Hearth</span>
              </div>
              <div>
                <h4 class="people-name">Claudine</h4>
                <div class="people-origin"><i class="fas fa-location-dot"></i> Virunga House Hearth</div>
                <p class="people-story">“Brings the highland harvest alive over open wood fires with fragrant traditional recipes.”</p>
              </div>
              <div class="people-badge-tag">
                <span>Hearth Keeper</span>
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>

            <!-- 5: Innocent -->
            <div class="people-card" data-person="4">
              <div class="people-card-top">
                <div class="people-avatar-icon">
                  <i class="fas fa-palette"></i>
                </div>
                <span class="people-role-pill">The Artist</span>
              </div>
              <div>
                <h4 class="people-name">Innocent</h4>
                <div class="people-origin"><i class="fas fa-location-dot"></i> Musanze Arts Quarter</div>
                <p class="people-story">“Shapes volcanic earth and banana leaf fibers into timeless cultural expressions.”</p>
              </div>
              <div class="people-badge-tag">
                <span>Cultural Sculptor</span>
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>
          </div>

          <!-- Carousel Controls -->
          <div class="people-nav-bar">
            <div class="people-dots">
              <div class="people-dot active" data-dot="0"></div>
              <div class="people-dot" data-dot="1"></div>
              <div class="people-dot" data-dot="2"></div>
              <div class="people-dot" data-dot="3"></div>
              <div class="people-dot" data-dot="4"></div>
            </div>
            <div class="people-arrows">
              <button class="people-arrow-btn" id="peoplePrev" aria-label="Previous Person">
                <i class="fas fa-arrow-left"></i>
              </button>
              <button class="people-arrow-btn" id="peopleNext" aria-label="Next Person">
                <i class="fas fa-arrow-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

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
            Stories, field notes, and cultural perspectives from the volcanic highlands.
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
      $cta_title = 'PLAN YOUR JOURNEY';
      $cta_lead = 'Tell us what you are curious about. We will help you discover the right way to experience the Virunga.';
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
      function initPeopleCarousel() {
        const track = document.getElementById('peopleTrack');
        const cards = document.querySelectorAll('.people-card');
        const dots = document.querySelectorAll('.people-dot');
        const prevBtn = document.getElementById('peoplePrev');
        const nextBtn = document.getElementById('peopleNext');
        if (!track || !cards.length) return;

        let currentIndex = 0;
        let isPaused = false;

        function setPerson(index, shouldScroll = true) {
          currentIndex = (index + cards.length) % cards.length;
          
          cards.forEach((card, i) => {
            card.classList.toggle('active', i === currentIndex);
          });
          
          dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
          });

          if (shouldScroll) {
            const activeCard = cards[currentIndex];
            if (activeCard) {
              const scrollLeft = activeCard.offsetLeft - (track.clientWidth / 2) + (activeCard.clientWidth / 2);
              track.scrollTo({
                left: Math.max(0, scrollLeft),
                behavior: 'smooth'
              });
            }
          }
        }

        cards.forEach((card, index) => {
          card.addEventListener('click', () => {
            setPerson(index, true);
          });
          card.addEventListener('mouseenter', () => {
            isPaused = true;
            setPerson(index, false);
          });
        });

        dots.forEach((dot, index) => {
          dot.addEventListener('click', () => {
            setPerson(index, true);
          });
        });

        if (prevBtn) {
          prevBtn.addEventListener('click', () => {
            setPerson(currentIndex - 1, true);
          });
        }
        if (nextBtn) {
          nextBtn.addEventListener('click', () => {
            setPerson(currentIndex + 1, true);
          });
        }

        const section = document.querySelector('.sec-people');
        if (section) {
          section.addEventListener('mouseenter', () => { isPaused = true; });
          section.addEventListener('mouseleave', () => { isPaused = false; });
        }

        setInterval(() => {
          if (!isPaused) {
            setPerson(currentIndex + 1, true);
          }
        }, 3800);

        setPerson(0, false);
      }

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
          initPeopleCarousel();
          initScrollReveal();
        });
      } else {
        initPeopleCarousel();
        initScrollReveal();
      }
    </script>
  </body>
</html>
