<?php
  if (!isset($baseLink) || !is_callable($baseLink)) {
    $basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
    $basePath = $basePath === '/' ? '' : $basePath;
    $baseLink = function (string $target = '') use ($basePath): string {
      $target = ltrim($target, '/');
      return ($basePath === '' ? '' : $basePath) . '/' . $target;
    };
  }

  $faqGroups = [
    [
      'title' => 'About Virunga Collective',
      'items' => [
        ['What is Virunga Collective?', 'Virunga Collective is a destination experience brand rooted in the Virunga region of northern Rwanda. We create and curate meaningful experiences that connect travelers with wildlife, landscapes, people, culture, food and stories.'],
        ['Where are your experiences based?', 'Our experiences are primarily rooted in Musanze, Volcanoes National Park and the surrounding volcanic landscapes, forests, lakes, communities and cultural places.'],
        ['Are you a traditional tour operator?', 'Virunga Collective is experience-led rather than built around standardized tours. We create and curate distinctive experiences and can coordinate the practical elements of a journey where required, including accommodation, transport, permits and selected services.'],
      ],
    ],
    [
      'title' => 'Experiences',
      'items' => [
        ['What can I experience beyond gorilla trekking?', 'The Virunga offers much more than gorillas. Depending on your interests, experiences can include volcanic landscapes, mountain trails, Buhanga Ecopark, the Twin Lakes, coffee, food, crafts, cooking, pottery, painting, local markets and meaningful encounters with people and place.'],
        ['Do I have to go gorilla trekking?', 'No. Gorilla trekking is one of the region\'s defining experiences, but it is not essential to every Virunga journey.'],
        ['What are Signature Journeys?', 'Signature Journeys are carefully curated multi-day experiences designed around the character of the Virunga. They bring together several elements of the destination while leaving room for personalization.'],
        ['Can experiences be private?', 'Yes. Many experiences can be arranged privately for individuals, couples, families and small groups, subject to availability.'],
        ['Can I create my own experience?', 'Absolutely. If you know what draws you to the Virunga, we can shape a journey around it. If you are unsure, simply tell us what interests you, such as wildlife, mountains, food, coffee, culture, people, photography or simply slowing down, and we will help shape the experience.'],
      ],
    ],
    [
      'title' => 'Gorilla Trekking',
      'items' => [
        ['How do I arrange gorilla trekking?', 'We can help coordinate the practical arrangements required for your gorilla trekking experience, including the relevant permit arrangements, transport and planning.'],
        ['Is gorilla trekking physically demanding?', 'Gorilla trekking takes place in mountainous forest terrain, and conditions can vary considerably. Hiking time and difficulty depend on the gorilla group, terrain and weather.'],
        ['What should I bring for gorilla trekking?', 'Comfortable hiking clothing, sturdy footwear, rain protection, water, a small daypack and other personal essentials are recommended. We will provide more detailed guidance before your journey.'],
        ['Can gorilla trekking be combined with other experiences?', 'Yes. Gorilla trekking can form part of a broader Virunga journey incorporating landscapes, food, coffee, crafts, culture and other experiences.'],
      ],
    ],
    [
      'title' => 'Stay',
      'items' => [
        ['Where can I stay?', 'We offer two accommodation pathways: Virunga House, our own hospitality offering in Musanze, and selected independently operated accommodation partners chosen for their location, style and comfort.'],
        ['What is Virunga House?', 'Virunga House is Virunga Collective\'s own hospitality offering in Musanze. It provides a personal base from which to experience the region and can be combined with selected experiences throughout the Virunga.'],
        ['Which accommodation partners do you work with?', 'Our selected accommodation network includes Virunga Hotel, a comfortable mid-range option in Musanze, and Virunga Inn Resort & Spa, a resort-style option near Volcanoes National Park.'],
        ['Are partner properties owned by Virunga Collective?', 'No. Partner properties are independently operated. We select them according to the needs of each journey rather than presenting them as part of our own accommodation.'],
        ['Can I choose where I stay?', 'Yes, subject to availability. We can recommend the most suitable option according to your journey, preferred level of comfort, location and budget.'],
        ['Can I combine Virunga House with partner accommodation?', 'Yes. Depending on your journey, combining Virunga House with another accommodation option can create a varied and well-balanced stay.'],
      ],
    ],
    [
      'title' => 'Responsible Travel',
      'items' => [
        ['What does responsible travel mean to Virunga Collective?', 'For us, responsible travel means approaching the Virunga with respect for its landscapes, wildlife, people and culture. It means creating meaningful encounters while encouraging thoughtful, conscious travel.'],
        ['How do local people participate in your experiences?', 'Our experiences can bring travelers together with local makers, farmers, cooks, artists, guides and other community members whose knowledge, skills and stories form part of the character of the region.'],
        ['How do your experiences support local makers?', 'Experiences involving crafts, food, coffee and creative traditions create opportunities for local people to share their skills, work and knowledge directly with travelers.'],
        ['Does every booking directly fund conservation?', 'No. We do not claim that every booking directly funds conservation. Conservation is carried out by dedicated authorities and organizations. Our role is to create responsible experiences that encourage respect, understanding and meaningful engagement with the Virunga.'],
        ['What is Virunga Impact?', 'Virunga Impact represents the wider community and conservation dimension of the Virunga Collective vision. It encompasses initiatives, collaborations and long-term efforts connected to creating positive value through travel.'],
      ],
    ],
    [
      'title' => 'Planning Your Journey',
      'items' => [
        ['When is the best time to visit the Virunga?', 'The Virunga can be experienced throughout the year. Weather, trail conditions, wildlife activities and personal interests can influence the ideal timing for your journey.'],
        ['How do I get to Musanze?', 'Musanze is accessible by road from Kigali, and private transport can be arranged according to your journey.'],
        ['Can you arrange airport transfers?', 'Yes. Airport transfers and other private transport can be coordinated upon request.'],
        ['Do I need permits for park activities?', 'Some activities, including gorilla trekking, require official permits. We will advise you on the permits relevant to your chosen experiences.'],
        ['How far in advance should I book?', 'We recommend booking as early as possible, particularly for gorilla trekking and journeys involving limited availability. Last-minute arrangements may sometimes be possible, depending on availability.'],
      ],
    ],
    [
      'title' => 'Booking & Pricing',
      'items' => [
        ['How much do your experiences cost?', 'Our experiences are Price on Request. Cost depends on factors such as duration, group size, accommodation, transport, permits and the level of customization required.'],
        ['Why don\'t you publish fixed prices?', 'Many of our experiences are private and customizable. Rather than placing every traveler into the same package, we prefer to build the journey around what you want to discover.'],
        ['How do I book?', 'Tell us your preferred dates, number of travelers, interests and any specific experiences you have in mind. We will respond with a suitable proposal and the practical details required to confirm your journey.'],
      ],
    ],
    [
      'title' => 'Before You Travel',
      'items' => [
        ['What should I pack?', 'Packing depends on your experiences and the season. Comfortable clothing, suitable walking shoes, rain protection and personal essentials are generally recommended.'],
        ['Do I need travel insurance?', 'Yes. Comprehensive travel insurance is strongly recommended, particularly for journeys involving outdoor activities.'],
        ['What happens if the weather changes?', 'Mountain weather can change quickly. Experience plans may occasionally need to adapt according to weather, trail conditions, park guidance and safety considerations.'],
        ['Do I need to be very fit?', 'Not every experience requires a high level of fitness. We offer a range of experiences, from gentle cultural and culinary encounters to more demanding mountain activities. We will help you choose appropriately.'],
        ['Are there rules I need to follow in protected areas?', 'Yes. Park regulations and wildlife guidelines must always be respected. We will provide the relevant guidance before activities involving protected areas.'],
      ],
    ],
    [
      'title' => 'Custom Journeys',
      'items' => [
        ['I do not know exactly what I want. Can you help?', 'Yes. You do not need to arrive with a complete itinerary. Tell us what interests you, and we can recommend experiences and accommodation that fit your time, pace and priorities.'],
        ['Can you design a journey around my interests?', 'Yes. Journeys can be shaped around wildlife, mountains, food, coffee, culture, crafts, photography, conservation, people or simply a slower way of discovering the Virunga.'],
        ['Do you work with couples, families and small groups?', 'Yes. Private journeys can be designed for individuals, couples, families and small groups, subject to availability.'],
      ],
    ],
  ];

  $schemaItems = [];
  foreach ($faqGroups as $group) {
    foreach ($group['items'] as $item) {
      $schemaItems[] = [
        '@type' => 'Question',
        'name' => $item[0],
        'acceptedAnswer' => [
          '@type' => 'Answer',
          'text' => $item[1],
        ],
      ];
    }
  }
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Frequently Asked Questions | Virunga Collective</title>
    <meta name="description" content="Frequently asked questions about Virunga Collective, Signature Journeys, private experiences, gorilla trekking, Virunga House, accommodation partners and responsible travel." />
    <meta name="keywords" content="Virunga Collective FAQ, Rwanda experiences questions, gorilla trekking Rwanda, Virunga House, Signature Journeys, Private Experiences">
    <meta name="author" content="Virunga Collective">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://virungajourneys.com/faq" />
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://virungajourneys.com/faq">
    <meta property="og:title" content="Frequently Asked Questions | Virunga Collective">
    <meta property="og:description" content="Answers about experiences, stays, gorilla trekking, responsible travel and planning a journey in the Virunga.">
    <meta property="og:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <meta property="og:site_name" content="Virunga Collective">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Frequently Asked Questions | Virunga Collective">
    <meta name="twitter:description" content="Answers about experiences, stays, gorilla trekking, responsible travel and planning a journey in the Virunga.">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($baseLink('img/about.jpeg')); ?>">
    <script type="application/ld+json">
      <?php echo json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $schemaItems,
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
      body { background: var(--cream); color: var(--ink); font-family: var(--font-body); line-height: 1.7; }
      a { color: inherit; text-decoration: none; }
      img { display: block; max-width: 100%; }
      .faq-wrap { max-width: var(--max-w); margin: 0 auto; padding: 0 24px; }
      .faq-hero {
        position: relative;
        min-height: 64vh;
        display: flex;
        align-items: flex-end;
        padding: 170px 0 76px;
        color: var(--cream);
        background: var(--forest-deep);
        overflow: hidden;
      }
      .faq-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(14, 33, 24, 0.48), rgba(14, 33, 24, 0.92)), url('<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>') center/cover no-repeat;
      }
      .faq-hero-content { position: relative; max-width: 760px; }
      .faq-kicker { color: var(--gold); font-size: 0.78rem; font-weight: 600; letter-spacing: 0.18em; margin-bottom: 14px; text-transform: uppercase; }
      .faq-hero h1 { font-family: var(--font-display); font-size: clamp(2.6rem, 7vw, 5.8rem); font-weight: 600; line-height: 0.95; margin-bottom: 22px; }
      .faq-lead { color: rgba(247, 244, 236, 0.84); font-size: clamp(1rem, 2vw, 1.18rem); max-width: 58ch; }
      .faq-layout { display: grid; grid-template-columns: 250px 1fr; gap: 52px; padding: 82px 0; }
      .faq-nav { position: sticky; top: 120px; align-self: start; display: grid; gap: 7px; }
      .faq-nav a { border-left: 2px solid var(--line); color: var(--ink-soft); font-size: 0.93rem; padding: 8px 0 8px 14px; transition: color 0.2s ease, border-color 0.2s ease; }
      .faq-nav a:hover { border-color: var(--gold); color: var(--forest); }
      .faq-groups { display: grid; gap: 36px; }
      .faq-group { background: var(--cream-warm); border: 1px solid var(--line); border-radius: 8px; padding: 30px; }
      .faq-group h2 { color: var(--forest-deep); font-family: var(--font-display); font-size: clamp(1.8rem, 3vw, 2.35rem); font-weight: 600; line-height: 1.1; margin-bottom: 18px; }
      .faq-item { border-top: 1px solid var(--line); }
      .faq-item:first-of-type { border-top: 0; }
      .faq-item summary { cursor: pointer; display: flex; align-items: center; justify-content: space-between; gap: 18px; list-style: none; padding: 18px 0; color: var(--forest-deep); font-weight: 600; }
      .faq-item summary::-webkit-details-marker { display: none; }
      .faq-item summary::after { content: "+"; width: 28px; height: 28px; border: 1px solid var(--line); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; flex: 0 0 auto; color: var(--gold); font-size: 1.15rem; }
      .faq-item[open] summary::after { content: "-"; }
      .faq-item p { color: var(--ink-soft); max-width: 74ch; padding: 0 42px 20px 0; }
      .faq-cta { background: var(--forest-deep); color: var(--cream); padding: 76px 0; text-align: center; }
      .faq-cta h2 { font-family: var(--font-display); font-size: clamp(2rem, 4vw, 3.3rem); font-weight: 600; line-height: 1; margin-bottom: 16px; }
      .faq-cta p { color: rgba(247, 244, 236, 0.78); margin: 0 auto 28px; max-width: 560px; }
      .faq-btn { display: inline-flex; align-items: center; gap: 10px; background: var(--gold); border: 1px solid var(--gold); border-radius: 4px; color: var(--forest-deep); font-size: 0.86rem; font-weight: 700; letter-spacing: 0.08em; padding: 13px 20px; text-transform: uppercase; }
      footer { background: #0b1b13; color: var(--cream); padding: 54px 0 26px; }
      .faq-footer-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 40px; align-items: start; }
      .faq-footer-brand { display: flex; align-items: center; gap: 12px; font-family: var(--font-display); font-size: 1.45rem; margin-bottom: 14px; }
      .faq-footer-brand img { height: 54px; width: auto; }
      .faq-footer-note { color: rgba(247, 244, 236, 0.7); max-width: 420px; }
      .faq-footer-links { display: flex; flex-wrap: wrap; gap: 14px 24px; justify-content: flex-end; list-style: none; }
      .faq-footer-links a { color: rgba(247, 244, 236, 0.78); font-size: 0.92rem; }
      .faq-footer-links a:hover { color: var(--gold); }
      .faq-footer-bottom { border-top: 1px solid rgba(247, 244, 236, 0.1); color: rgba(247, 244, 236, 0.58); display: flex; justify-content: space-between; gap: 20px; margin-top: 34px; padding-top: 20px; font-size: 0.82rem; }
      @media (max-width: 860px) {
        .faq-layout { grid-template-columns: 1fr; gap: 28px; padding: 56px 0; }
        .faq-nav { position: static; grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .faq-group { padding: 24px 20px; }
        .faq-footer-grid, .faq-footer-bottom { grid-template-columns: 1fr; flex-direction: column; }
        .faq-footer-links { justify-content: flex-start; }
      }
      @media (max-width: 560px) {
        .faq-hero { min-height: 56vh; padding: 140px 0 52px; }
        .faq-nav { display: none; }
        .faq-item p { padding-right: 0; }
      }
    </style>
  </head>
  <body>
    <?php include __DIR__ . '/header.php'; ?>

    <main>
      <section class="faq-hero">
        <div class="faq-wrap faq-hero-content">
          <div class="faq-kicker">Frequently Asked Questions</div>
          <h1>Before You Begin</h1>
          <p class="faq-lead">Clear answers about Virunga Collective, our experiences, places to stay, responsible travel and planning a journey in northern Rwanda.</p>
        </div>
      </section>

      <section class="faq-wrap faq-layout">
        <nav class="faq-nav" aria-label="FAQ sections">
          <?php foreach ($faqGroups as $index => $group): ?>
            <a href="#faq-<?php echo $index; ?>"><?php echo htmlspecialchars($group['title']); ?></a>
          <?php endforeach; ?>
        </nav>

        <div class="faq-groups">
          <?php foreach ($faqGroups as $index => $group): ?>
            <section class="faq-group" id="faq-<?php echo $index; ?>">
              <h2><?php echo htmlspecialchars($group['title']); ?></h2>
              <?php foreach ($group['items'] as $item): ?>
                <details class="faq-item">
                  <summary><?php echo htmlspecialchars($item[0]); ?></summary>
                  <p><?php echo htmlspecialchars($item[1]); ?></p>
                </details>
              <?php endforeach; ?>
            </section>
          <?php endforeach; ?>
        </div>
      </section>

      <?php
        $cta_id = 'planner';
        $cta_eyebrow = 'VIRUNGA COLLECTIVE FAQ';
        $cta_title = 'PLAN YOUR JOURNEY';
        $cta_lead = 'Your journey does not have to begin with a fixed itinerary. Tell us what you would like to discover, and we will help you find your way into the Virunga.';
        $cta_primary_text = 'PLAN YOUR JOURNEY';
        $cta_primary_url = 'https://wa.me/250784513435?text=' . urlencode('Hello Virunga Collective, I have a question about planning my journey.');
        $cta_secondary_text = 'DISCOVER VIRUNGA HOUSE';
        $cta_secondary_url = $baseLink('homestays');
        include __DIR__ . '/cta.php';
      ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
  </body>
</html>
