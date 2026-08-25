<?php
require_once 'handlers/itenary_handler.php';
$newestTours = getNewestToursWithoutPricing(9);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mid-Range Tours around the Virunga Mountains | Virunga Ecotours</title>
    <meta name="description" content="Experience the perfect balance of comfort and value with our mid-range tours. Quality lodges, expert guides, and meaningful cultural encounters in the Virunga Mountains.">
    
    <link rel="canonical" href="https://virungajourneys.com/ecotours/pages/midrange.php" />
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/icon.png?v=1.1">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/midrange.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include "includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Mid-Range Tours around the Virunga Mountains</h1>
                <p class="hero-subtitle">Experience the perfect balance of comfort and value with expert guides and meaningful cultural encounters</p>
                
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="midrange-container">

        <!-- Introduction Section -->
        <section class="intro-section">
            <h2 class="section-title">What "Mid-Range" Means <span class="highlight-text">(and why travelers love it)</span></h2>
            <p>Mid-range tours balance <strong>comfort and value</strong>: quality lodges or guesthouses, private 4x4 transport, expert guiding, and thoughtfully curated activities—without premium-luxury pricing. Guests get reliable amenities (hot showers, Wi-Fi in most places, hearty meals), prime locations close to trailheads, and small-group flexibility that keeps days smooth and unhurried.</p>
        </section>

        <!-- Core Value Proposition -->
        <section class="content-section">
            <div class="value-proposition">
                <h2 class="section-title">Core Value Proposition</h2>
                <ul class="feature-list">
                    <li><strong>Comfort you can count on:</strong> Clean, well-located properties, ensuite rooms, and hospitable service after long trekking days.</li>
                    <li><strong>Smart pricing:</strong> More budget for experiences—gorilla or golden-monkey tracking, canoeing, biking, village visits, crater lake walks.</li>
                    <li><strong>Operational reliability:</strong> Reserved permits, vetted drivers, safe vehicles, and timed departures that match park rules.</li>
                    <li><strong>Meaningful encounters:</strong> Time with local guides, craft cooperatives, farmers, and storytellers framed with context, not spectacle.</li>
                </ul>
            </div>
        </section>

        <!-- Destinations & Experiences -->
        <section class="content-section">
            <h2 class="section-title">Signature Destinations & Experiences</h2>
            
            <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-mountain"></i> Rwanda (Volcanoes NP, Musanze)</h4>
                    <p>Gorilla tracking, golden monkeys, Dian Fossey hike, biking the rolling countryside, coffee and banana-beer tastings, night storytelling.</p>
                </div>
                
                <div class="info-card">
                    <h4><i class="fas fa-tree"></i> Uganda (Mgahinga & Bwindi edges)</h4>
                    <p>Gorilla or golden-monkey tracking, Batwa heritage experiences, scenic canoeing on Lake Mutanda, birding on forest fringes.</p>
                </div>
                
                <div class="info-card">
                    <h4><i class="fas fa-globe-africa"></i> DRC fringe experiences</h4>
                    <p>Virunga-view hikes, markets and art encounters curated with current guidance and safety protocols (when open and appropriate).</p>
                </div>
            </div>
            
            <div class="highlight-box">
                <h3><i class="fas fa-info-circle"></i> Important Note</h3>
                <p>Routes and activities are tailored to current park advisories and traveler preferences. We prioritize safety and sustainability in all our offerings.</p>
            </div>
        </section>

        <!-- Guest Profile -->
        <section class="content-section">
            <h2 class="section-title">Guest Profile <span class="highlight-text">(who this fits best)</span></h2>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><i class="fas fa-users"></i></span>
                    <span class="stat-label">Travelers seeking comfort and cultural depth without luxury markups</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><i class="fas fa-family"></i></span>
                    <span class="stat-label">Families or small friend groups who value flexible timing and private logistics</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><i class="fas fa-camera"></i></span>
                    <span class="stat-label">Photographers and nature lovers who prefer paced activity days</span>
                </div>
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Community Impact Section -->
        <section class="content-section community-impact-section">
            <h2 class="section-title">Community Importance: How Mid-Range Travel Creates Local Value</h2>
            <div class="impact-content">
                <p>Mid-range tours generate <strong>steady, diversified demand</strong>—the kind of travel that most directly sustains everyday livelihoods around the Virunga Mountains.</p>
            </div>
        </section>

        <!-- Newest Tours Section -->
        <section class="content-section newest-tours">
            <h2 class="section-title">Explore Our Newest Adventures</h2>
            <div class="tours-cards" id="midrangeToursContainer">
                <?php if (!empty($newestTours)): ?>
                    <?php foreach ($newestTours as $index => $tour): ?>
                        <div class="tour-card <?php echo $index >= 3 ? 'hidden-card' : ''; ?>">
                            <div class="tour-card-image">
                                <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" />
                                <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($tour['category'])); ?></div>
                                <div class="tour-offer">AVAILABLE</div>
                            </div>
                            <div class="tour-card-content">
                                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                <div class="tour-duration"><?php echo $tour['days_count']; ?> DAY<?php echo $tour['days_count'] > 1 ? 'S' : ''; ?></div>
                                <p><?php echo htmlspecialchars(substr($tour['short_description'], 0, 150)) . '...'; ?></p>
                                <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="read-more-btn">BOOK NOW</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-tours">New adventures coming soon.</p>
                <?php endif; ?>
            </div>
            <?php if (count($newestTours) > 3): ?>
                <div class="view-more-container">
                    <button id="viewMoreMidrangeBtn" class="view-more-btn">View More</button>
                </div>
            <?php endif; ?>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const viewMoreBtn = document.getElementById('viewMoreMidrangeBtn');
                const cards = document.querySelectorAll('#midrangeToursContainer .tour-card');
                let currentlyShown = 3;

                if (viewMoreBtn) {
                    viewMoreBtn.addEventListener('click', function() {
                        let newlyShown = 0;
                        for (let i = currentlyShown; i < cards.length && newlyShown < 3; i++) {
                            cards[i].classList.remove('hidden-card');
                            newlyShown++;
                        }
                        currentlyShown += newlyShown;
                        if (currentlyShown >= cards.length) {
                            viewMoreBtn.style.display = 'none';
                        }
                    });
                }
            });
        </script>

        <div class="info-cards">
                <div class="info-card">
                    <h4><i class="fas fa-briefcase"></i> Direct Employment</h4>
                    <p>Driver-guides, porters, lodge staff, site interpreters, mechanics, cooks, and cleaners benefit from predictable rotations and tips.</p>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-shopping-basket"></i> Local Procurement</h4>
                    <p>Mid-range properties purchase fresh produce, firewood alternatives, beverages, textiles, and maintenance services from nearby vendors, keeping trip expenditures circulating locally.</p>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-graduation-cap"></i> Skills & Enterprise Development</h4>
                    <p>Consistent guest flow underwrites guide training, language practice, safety refreshers, and small business growth (bike hires, craft studios, canoe operations).</p>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-heart"></i> Cultural Continuity with Dignity</h4>
                    <p>Scheduled visits to artists, farmers, storytellers, and musicians are framed with fair compensation, respect for hosts' time, and clear visitor etiquette.</p>
                </div>

                <div class="info-card">
                    <h4><i class="fas fa-coins"></i> Distributed Revenue Effects</h4>
                    <p>Because mid-range groups often dine, shop, and stay across multiple neighborhoods, benefits reach more households than single-site, fly-in/fly-out models.</p>
                </div>
            </div>
        </section>

        <!-- Operating Standards -->
        <section class="content-section">
            <div class="highlight-box">
                <h3 style="color:#ffffff;"><i class="fas fa-shield-alt" ></i> Our Operating Standards (your assurance)</h3>
                <ul class="bullet-list"  style="color:#ffffff;">
                    <li style="color:#ffffff;"><strong style="color:#ffffff;">Safety first:</strong> Daily vehicle checks, route screening, and contingency plans for weather and road changes.</li>
                    <li style="color:#ffffff;"><strong style="color:#ffffff;">Small-group ethics:</strong> Low-impact group sizes; respect for wildlife distance rules and community privacy.</li>
                    <li style="color:#ffffff;"><strong style="color:#ffffff;">Transparent pricing:</strong> Clear inclusions, exclusions, and permit handling—no surprise fees.</li>
                    <li style="color:#ffffff;"><strong style="color:#ffffff;">Punctuality with flexibility:</strong> Timetables that honour park guidelines while leaving room for serendipity.</li>
                </ul>
            </div>
        </section>

        <!-- Impact Measurement -->
        <section class="content-section">
            <h3 class="subsection-title">Measuring Impact <span class="highlight-text">(practical, trackable)</span></h3>
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">%</span>
                    <span class="stat-label">Trip spend with local suppliers and guides</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">#</span>
                    <span class="stat-label">Community-led activities booked per month</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">hrs</span>
                    <span class="stat-label">Training hours completed by guides and hosts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">★</span>
                    <span class="stat-label">Guest feedback on cultural learning and host respect</span>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <h3><i class="fas fa-compass"></i> Why Choose Mid-Range with Virunga Ecotours</h3>
            <p>You'll sleep well, trek confidently, and spend your days immersed—not rushed. Your payments build real careers, keep small enterprises busy year-round, and strengthen the creative and entrepreneurial fabric of communities surrounding the Virunga volcanoes.</p>
            
            <div class="section-divider"></div>
            
            <p><strong>Ready to plan?</strong> Tell us your travel window, preferred activity level (<em>relaxed / moderate / active</em>), and whether you want one or two primate days. We'll tailor a mid-range itinerary that fits your comfort, your time, and the community outcomes you want to support.</p>
        </section>
    </div>

    <!-- Include Footer -->
    <?php include "includes/footer.php" ?>
    
    <!-- JavaScript -->
    <script src="../js/header.js" defer></script>
</body>
</html>