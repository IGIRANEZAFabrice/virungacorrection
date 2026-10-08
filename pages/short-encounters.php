<?php require_once __DIR__ . '/../config/branding.php'; ?>
<!doctype html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Short Encounters | Virunga Journeys</title><meta name="description" content="Discover hands-on craft, artist studios, coffee and traditional brewing in Musanze."><link rel="canonical" href="https://virungajourneys.com/short-encounters"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"><link rel="preconnect" href="https://fonts.googleapis.com"><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&amp;family=Jost:wght@400;500;600&amp;display=swap"><style>
:root { --forest:#1b3a2b; --forest-deep:#122a1f; --cream:#f6f2e9; --gold:#c9a24b; --gold-light:#dfba6b; --font-display:"Cormorant Garamond",Georgia,serif; --font-body:"Jost",sans-serif; }
header { background:#122a1f; } body { padding-top:100px; } h1,h2,h3 { font-family:var(--font-display); } .dest-card:focus-visible { outline:3px solid var(--gold); outline-offset:4px; }

body { margin:0; font-family:var(--font-body); background:#f6f2e9; color:#1b3a2b; } * { box-sizing:border-box; } a { color:inherit; } .wrap { max-width:1200px; margin:auto; padding:0 24px; } .sec-rwanda { padding:70px 0; } .sec-header { text-align:center; margin-bottom:36px; } .sec-title { font-size:clamp(2rem,4vw,3rem); } .sec-subtitle { line-height:1.7; } .destinations-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:24px; } .dest-card { position:relative; min-height:320px; overflow:hidden; } .dest-bg { position:absolute; width:100%; height:100%; object-fit:cover; } .dest-overlay { position:absolute; inset:0; background:linear-gradient(transparent,#122a1fdd); } .dest-content { position:absolute; bottom:24px; left:24px; right:24px; color:#fff; } .dest-title { font-size:1.8rem; margin-bottom:10px; } .btn { display:inline-block; padding:15px 24px; background:#1b3a2b; color:#fff; text-decoration:none; } @media(max-width:800px) { .destinations-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } } @media(max-width:520px) { .destinations-grid { grid-template-columns:1fr; } }

</style></head>
<body><?php include __DIR__ . '/header.php'; ?>
<section class="sec-rwanda"><div class="wrap"><div class="sec-header"><h1 class="sec-title">SHORT ENCOUNTERS</h1><p class="sec-subtitle">Passing through Musanze? Discover the Virunga in 90 minutes to half a day.</p></div>
<div class="destinations-grid"><a class="dest-card" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>"><img class="dest-bg" src="<?php echo htmlspecialchars($baseLink('img/intro.jpeg')); ?>" alt="" loading="lazy" decoding="async"><div class="dest-overlay"></div>
<div class="dest-content"><h2 class="dest-title">Short Maker’s Table</h2><p>2–3 Hours · Hands-on Craft</p></div>
</a>
<a class="dest-card" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>"><img class="dest-bg" src="<?php echo htmlspecialchars($baseLink('img/abouthero.jpeg')); ?>" alt="" loading="lazy" decoding="async"><div class="dest-overlay"></div>
<div class="dest-content"><h2 class="dest-title">Short Living Canvas</h2><p>2 Hours · Artist Studio</p></div>
</a>
<a class="dest-card" href="<?php echo htmlspecialchars($baseLink('coffee')); ?>"><img class="dest-bg" src="<?php echo htmlspecialchars($baseLink('homestay/img/activities/1778346998_coffee.jpeg')); ?>" alt="" loading="lazy" decoding="async"><div class="dest-overlay"></div>
<div class="dest-content"><h2 class="dest-title">Virunga Coffee & Conversation</h2><p>90 Min · Roasting & Cupping</p></div>
</a>
<a class="dest-card" href="<?php echo htmlspecialchars($baseLink('experiences')); ?>"><img class="dest-bg" src="<?php echo htmlspecialchars($baseLink('img/bg.jpg')); ?>" alt="" loading="lazy" decoding="async"><div class="dest-overlay"></div>
<div class="dest-content"><h2 class="dest-title">Short Brewing Table</h2><p>2 Hours · Traditional Brewing</p></div>
</a>
</div>
</div></section><?php include __DIR__ . '/footer.php'; ?></body></html>