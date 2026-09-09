<?php
  if (!isset($baseLink) || !is_callable($baseLink)) {
    $basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
    $basePath = $basePath === '/' ? '' : $basePath;
    $baseLink = function (string $target = '') use ($basePath): string {
      $target = ltrim($target, '/');
      return ($basePath === '' ? '' : $basePath) . '/' . $target;
    };
  }

  $policySections = [
    [
      'title' => 'Your Cancellation Terms',
      'body' => [
        'The cancellation terms applicable to your journey will be confirmed in writing before your booking is finalized.',
        'Because every journey may include different experiences, accommodation, permits, transportation and independent service providers, cancellation conditions can vary.',
        'Your booking confirmation will therefore specify the applicable cancellation deadlines, charges and refund conditions.',
      ],
    ],
    [
      'title' => 'Cancellation by the Guest',
      'body' => [
        'If you need to cancel your journey, please notify Virunga Collective in writing as soon as possible.',
        'Any refund will depend on the date we receive your cancellation, the services included in your booking, payments already made to secure those services and the cancellation policies of relevant independent providers.',
        'Where a service provider has applied a non-refundable charge, that amount may not be recoverable.',
      ],
    ],
    [
      'title' => 'Gorilla Trekking & Limited-Availability Services',
      'body' => [
        'Gorilla trekking permits and certain other limited-availability services are subject to the applicable terms and conditions of the relevant authorities or providers.',
        'Once such services have been confirmed or paid for, their cancellation or refund conditions may differ from the general terms of your journey.',
        'We will communicate these conditions clearly before you commit to the booking.',
      ],
    ],
    [
      'title' => 'Changes Instead of Cancellation',
      'body' => [
        'If your plans change, we encourage you to contact us before cancelling.',
        'Where possible, we may be able to change your travel dates, modify your experience, adjust accommodation or redesign part of your journey.',
        'Changes are subject to availability and any additional costs or provider conditions that may apply.',
      ],
    ],
    [
      'title' => 'Cancellation by Virunga Collective',
      'body' => [
        'In exceptional circumstances, Virunga Collective may need to cancel or significantly alter an experience or journey.',
        'This may occur because of circumstances beyond reasonable control, including severe weather, unsafe conditions, government or park restrictions, natural events or other circumstances affecting the safe operation of the journey.',
        'Where this occurs, we will communicate with you as soon as reasonably possible and work toward an appropriate alternative or applicable refund.',
      ],
    ],
    [
      'title' => 'Changes Caused by Independent Providers',
      'body' => [
        'Some elements of your journey may be provided by independent accommodation, transport, activity or other service providers.',
        'If an independent provider changes or cancels a service, we will assist with communication and, where reasonably possible, help arrange an alternative.',
        'Any refund or compensation relating to that service will be subject to the provider\'s applicable terms.',
      ],
    ],
    [
      'title' => 'No-Show & Early Departure',
      'body' => [
        'If you do not arrive for a confirmed service or choose to leave your journey early, refunds are not guaranteed.',
        'Any refund will depend on the specific service and the applicable cancellation conditions.',
      ],
    ],
    [
      'title' => 'Travel Insurance',
      'body' => [
        'We strongly recommend comprehensive travel insurance covering cancellation, interruption, medical expenses and other relevant travel risks.',
        'Travel insurance can provide protection where costs cannot be recovered under the applicable cancellation terms.',
      ],
    ],
    [
      'title' => 'Refund Processing',
      'body' => [
        'Where a refund is due, we will confirm the applicable amount and process it according to the payment method and conditions associated with the booking.',
        'Third-party refund processing may take additional time where funds must first be returned by an independent provider.',
      ],
    ],
    [
      'title' => 'Before You Book',
      'body' => [
        'We want you to understand the cancellation conditions before committing to your journey.',
        'Your booking proposal and confirmation will contain the specific cancellation terms that apply to your arrangements.',
        'If anything is unclear, please ask us before confirming your booking.',
      ],
    ],
  ];
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cancellation Policy | Virunga Collective</title>
    <meta name="description" content="Virunga Collective cancellation policy for private journeys, Signature Journeys, gorilla trekking permits, independent providers, refunds and travel changes." />
    <meta name="keywords" content="Virunga Collective cancellation policy, Rwanda journey cancellation, gorilla permit cancellation, travel refund policy">
    <meta name="author" content="Virunga Collective">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://virungajourneys.com/cancellation-policy" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://virungajourneys.com/cancellation-policy">
    <meta property="og:title" content="Cancellation Policy | Virunga Collective">
    <meta property="og:description" content="Clear cancellation guidance for Virunga Collective journeys and experiences.">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:site_name" content="Virunga Collective">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Cancellation Policy | Virunga Collective">
    <meta name="twitter:description" content="Clear cancellation guidance for Virunga Collective journeys and experiences.">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <script type="application/ld+json">
      <?php echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Cancellation Policy | Virunga Collective',
        'url' => 'https://virungajourneys.com/cancellation-policy',
        'dateModified' => '2026-09-01',
        'publisher' => [
          '@type' => 'Organization',
          'name' => 'Virunga Collective',
          'url' => 'https://virungajourneys.com',
        ],
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
    </script>
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($baseLink('img/icon.png')); ?>" />
    <link rel="manifest" href="<?php echo htmlspecialchars($baseLink('manifest.json')); ?>" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <style>
      :root {
        --forest: #1b3a2b;
        --forest-deep: #0e2118;
        --gold: #b6924c;
        --cream: #f7f4ec;
        --cream-warm: #fbf9f3;
        --ink: #202620;
        --ink-soft: rgba(32, 38, 32, 0.72);
        --line: rgba(27, 58, 43, 0.12);
        --max-w: 1120px;
        --font-display: "Cormorant Garamond", Georgia, serif;
        --font-body: "Jost", sans-serif;
      }
      * { box-sizing: border-box; margin: 0; padding: 0; }
      html { scroll-behavior: smooth; }
      body { background: var(--cream); color: var(--ink); font-family: var(--font-body); line-height: 1.75; }
      a { color: inherit; text-decoration: none; }
      img { display: block; max-width: 100%; }
      .policy-wrap { max-width: var(--max-w); margin: 0 auto; padding: 0 24px; }
      .policy-hero {
        position: relative;
        min-height: 62vh;
        display: flex;
        align-items: flex-end;
        padding: 170px 0 76px;
        color: var(--cream);
        background: var(--forest-deep);
        overflow: hidden;
      }
      .policy-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(14, 33, 24, 0.5), rgba(14, 33, 24, 0.94)), url('<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>') center/cover no-repeat;
      }
      .policy-hero-content { position: relative; max-width: 780px; }
      .policy-kicker { color: var(--gold); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.18em; margin-bottom: 14px; text-transform: uppercase; }
      .policy-hero h1 { font-family: var(--font-display); font-size: clamp(2.5rem, 7vw, 5.6rem); font-weight: 600; line-height: 0.98; margin-bottom: 18px; }
      .policy-updated { color: rgba(247, 244, 236, 0.7); font-size: 0.92rem; margin-bottom: 20px; }
      .policy-lead { color: rgba(247, 244, 236, 0.84); font-size: clamp(1rem, 2vw, 1.16rem); max-width: 62ch; }
      .policy-layout { display: grid; grid-template-columns: 250px 1fr; gap: 52px; padding: 82px 0; }
      .policy-nav { position: sticky; top: 120px; align-self: start; display: grid; gap: 7px; }
      .policy-nav a { border-left: 2px solid var(--line); color: var(--ink-soft); font-size: 0.92rem; padding: 8px 0 8px 14px; transition: color 0.2s ease, border-color 0.2s ease; }
      .policy-nav a:hover { border-color: var(--gold); color: var(--forest); }
      .policy-content { display: grid; gap: 18px; }
      .policy-card { background: var(--cream-warm); border: 1px solid var(--line); border-radius: 8px; padding: 30px; }
      .policy-card h2 { color: var(--forest-deep); font-family: var(--font-display); font-size: clamp(1.65rem, 3vw, 2.2rem); font-weight: 600; line-height: 1.15; margin-bottom: 14px; }
      .policy-number { color: var(--gold); display: block; font-family: var(--font-body); font-size: 0.8rem; font-weight: 700; letter-spacing: 0.14em; margin-bottom: 8px; text-transform: uppercase; }
      .policy-card p { color: var(--ink-soft); margin-top: 12px; max-width: 76ch; }
      .policy-note { border-left: 3px solid var(--gold); color: var(--ink-soft); font-size: 1.02rem; padding-left: 18px; }
      .policy-cta { background: var(--forest-deep); color: var(--cream); padding: 76px 0; text-align: center; }
      .policy-cta h2 { font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.25rem); font-weight: 600; line-height: 1; margin-bottom: 16px; }
      .policy-cta p { color: rgba(247, 244, 236, 0.78); margin: 0 auto 28px; max-width: 570px; }
      .policy-btn { display: inline-flex; align-items: center; gap: 10px; background: var(--gold); border: 1px solid var(--gold); border-radius: 4px; color: var(--forest-deep); font-size: 0.86rem; font-weight: 700; letter-spacing: 0.08em; padding: 13px 20px; text-transform: uppercase; }
      footer { background: #0b1b13; color: var(--cream); padding: 54px 0 26px; }
      .policy-footer-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start; }
      .policy-footer-brand { display: flex; align-items: center; gap: 12px; font-family: var(--font-display); font-size: 1.45rem; margin-bottom: 14px; }
      .policy-footer-brand img { height: 54px; width: auto; }
      .policy-footer-note { color: rgba(247, 244, 236, 0.7); max-width: 420px; }
      .policy-footer-links { display: flex; flex-wrap: wrap; gap: 14px 24px; justify-content: flex-end; list-style: none; }
      .policy-footer-links a { color: rgba(247, 244, 236, 0.78); font-size: 0.92rem; }
      .policy-footer-links a:hover { color: var(--gold); }
      .policy-footer-bottom { border-top: 1px solid rgba(247, 244, 236, 0.1); color: rgba(247, 244, 236, 0.58); display: flex; justify-content: space-between; gap: 20px; margin-top: 34px; padding-top: 20px; font-size: 0.82rem; }
      @media (max-width: 860px) {
        .policy-layout { grid-template-columns: 1fr; gap: 28px; padding: 56px 0; }
        .policy-nav { position: static; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .policy-card { padding: 24px 20px; }
        .policy-footer-grid, .policy-footer-bottom { grid-template-columns: 1fr; flex-direction: column; }
        .policy-footer-links { justify-content: flex-start; }
      }
      @media (max-width: 560px) {
        .policy-hero { min-height: 56vh; padding: 140px 0 52px; }
        .policy-nav { display: none; }
      }
    </style>
  </head>
  <body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
      <section class="policy-hero">
        <div class="policy-wrap policy-hero-content">
          <div class="policy-kicker">Cancellation Policy</div>
          <h1>Clear and Fair Travel Terms</h1>
          <p class="policy-updated">Last updated: September 2026</p>
          <p class="policy-lead">We understand that travel plans can change. Our cancellation policy is designed to be clear and fair while reflecting the arrangements required to prepare your journey.</p>
        </div>
      </section>

      <section class="policy-wrap policy-layout">
        <nav class="policy-nav" aria-label="Cancellation policy sections">
          <?php foreach ($policySections as $index => $section): ?>
            <a href="#policy-<?php echo $index + 1; ?>"><?php echo htmlspecialchars($section['title']); ?></a>
          <?php endforeach; ?>
        </nav>

        <div class="policy-content">
          <p class="policy-note">Specific cancellation terms are confirmed in writing for each booking, especially where permits, accommodation, transport or independent providers are involved.</p>
          <?php foreach ($policySections as $index => $section): ?>
            <section class="policy-card" id="policy-<?php echo $index + 1; ?>">
              <span class="policy-number"><?php echo sprintf('%02d', $index + 1); ?></span>
              <h2><?php echo htmlspecialchars($section['title']); ?></h2>
              <?php foreach ($section['body'] as $paragraph): ?>
                <p><?php echo htmlspecialchars($paragraph); ?></p>
              <?php endforeach; ?>
            </section>
          <?php endforeach; ?>
        </div>
      </section>

      <?php
        $cta_id = 'planner';
        $cta_eyebrow = 'VIRUNGA COLLECTIVE';
        $cta_title = 'PLAN YOUR JOURNEY';
        $cta_lead = 'Plans change. If you need to modify or cancel your journey, contact us as soon as possible and we will guide you through the available options.';
        $cta_primary_text = 'PLAN YOUR JOURNEY';
        $cta_primary_url = 'https://wa.me/250784513435?text=' . urlencode('Hello Virunga Collective, I need help with a booking change or cancellation.');
        $cta_secondary_text = 'DISCOVER VIRUNGA HOUSE';
        $cta_secondary_url = $baseLink('homestays');
        include __DIR__ . '/cta.php';
      ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
  </body>
</html>
