<?php require_once __DIR__ . '/../config/branding.php'; ?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Destinations | Virunga Journeys</title><meta name="description" content="Explore Rwanda's volcanoes, forests, lakes and wildlife with Virunga Journeys."><link rel="canonical" href="https://virungajourneys.com/destinations"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@400;500;600&amp;display=swap"><style>
:root { --forest:#1b3a2b; --forest-deep:#122a1f; --cream:#f6f2e9; --gold:#c9a24b; --gold-light:#dfba6b; --font-display:"Cormorant Garamond",Georgia,serif; --font-body:"Jost",sans-serif; }
header { background:#122a1f; } body { padding-top:100px; } h1,h2,h3 { font-family:var(--font-display); } .dest-card:focus-visible { outline:3px solid var(--gold); outline-offset:4px; }

body { margin:0; font-family:var(--font-body); background:#f6f2e9; color:#1b3a2b; } * { box-sizing:border-box; } a { color:inherit; } .wrap { max-width:1200px; margin:auto; padding:0 24px; } .sec-rwanda { padding:70px 0; } .sec-header { text-align:center; margin-bottom:36px; } .sec-title { font-size:clamp(2rem,4vw,3rem); } .sec-subtitle { line-height:1.7; } .destinations-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:24px; } .dest-card { position:relative; min-height:320px; overflow:hidden; } .dest-bg { position:absolute; width:100%; height:100%; object-fit:cover; } .dest-overlay { position:absolute; inset:0; background:linear-gradient(transparent,#122a1fdd); } .dest-content { position:absolute; bottom:24px; left:24px; right:24px; color:#fff; } .dest-title { font-size:1.8rem; margin-bottom:10px; } .btn { display:inline-block; padding:15px 24px; background:#1b3a2b; color:#fff; text-decoration:none; } @media(max-width:800px) { .destinations-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } } @media(max-width:520px) { .destinations-grid { grid-template-columns:1fr; } }

</style></head>
<body><?php include __DIR__ . '/header.php'; ?><section class="sec-rwanda" id="rwanda">
      <div class="wrap">
        <div class="sec-header center reveal">
          <h1 class="sec-title">BEYOND THE VIRUNGA</h1>
          <p class="sec-subtitle">
            Discover the forests, wildlife and landscapes of Rwanda, with journeys shaped by local knowledge.
          </p>
        </div>

        <div class="destinations-grid">
          <!-- 1: Volcanoes -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.04s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/virunga.jpg')); ?>" alt="Volcanoes National Park" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">The Volcanoes</h3>
              <div class="dest-meta">Gorillas • Golden Monkeys • Volcanoes</div>
            </div>
          </div>

          <!-- 2: Nyungwe -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.11s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/nyungwe.jpg')); ?>" alt="Nyungwe Rainforest" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Nyungwe</h3>
              <div class="dest-meta">Chimpanzees • Forest • Canopy</div>
            </div>
          </div>

          <!-- 3: Akagera -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.18s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/akagera.jpg')); ?>" alt="Akagera Savanna" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Akagera</h3>
              <div class="dest-meta">Wildlife • Big Five • Lake</div>
            </div>
          </div>

          <!-- 4: Gishwati -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.25s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/gishwati.jpg')); ?>" alt="Gishwati Forest" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Gishwati</h3>
              <div class="dest-meta">Forest • Nature • Birdlife</div>
            </div>
          </div>

          <!-- 5: Nyandungu -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.32s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/nyandungu.jpeg')); ?>" alt="Nyandungu Eco-Park" class="dest-bg" loading="lazy" decoding="async" />
            <div class="dest-overlay"></div>
            <div class="dest-content">
              <h3 class="dest-title">Nyandungu</h3>
              <div class="dest-meta">Wetland • Nature • Birdlife</div>
            </div>
          </div>

          <!-- 6: Buhanga -->
          <div class="dest-card reveal-card" style="--reveal-delay: 0.39s;">
            <img src="<?php echo htmlspecialchars($baseLink('img/home/buhanga.jpg')); ?>" alt="Buhanga Eco-Park" class="dest-bg" loading="lazy" decoding="async" />
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
    </section><?php include __DIR__ . '/footer.php'; ?></body></html>