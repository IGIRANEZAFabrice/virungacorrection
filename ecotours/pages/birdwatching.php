<?php require_once('../admin/config/connection.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Birdwatching in Rwanda - From City Wetlands to Albertine Rift | Virunga Ecotours</title>
    <meta name="description" content="Discover Rwanda's incredible birdlife with over 700 species. From Nyandungu Eco-Park to Nyungwe Forest, experience world-class birdwatching adventures.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/icon.png">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/birdwatching.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include './includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">700+</div>
                        <div class="stat-label">Bird Species</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">27</div>
                        <div class="stat-label">Albertine Endemics</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">5+</div>
                        <div class="stat-label">National Parks</div>
                    </div>
                </div>
                <h1>Birdwatching in Rwanda</h1>
                <p class="hero-subtitle">From City Wetlands to the Albertine Rift</p>
                <div class="hero-buttons">
                    <a href="#tours" class="btn-primary">
                        <i class="fas fa-binoculars"></i>
                        Explore Birding Tours
                    </a>
                    <a href="#hotspots" class="btn-secondary">
                        <i class="fas fa-map-marker-alt"></i>
                        Discover Hotspots
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <div class="scroll-mouse">
                <div class="scroll-wheel"></div>
            </div>
            <span>Scroll to explore</span>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>A Dream Destination for Bird Lovers</h2>
                    <p class="intro-lead">Rwanda is a dream destination for bird lovers, with more than 700 species recorded across its rich mosaic of habitats. From wetlands and lakeshores to ancient rainforests and volcanic slopes, the country's landscapes shelter everything from iconic rarities to colorful everyday species.</p>
                    
                    <p>Whether you are a seasoned ornithologist or a curious traveler, birdwatching here is both rewarding and unforgettable. Rwanda's compact size means you can explore a wide variety of ecosystems in just a few days—going from city wetlands to montane forests and savannahs within hours.</p>
                    
                    <div class="intro-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-dove"></i>
                            </div>
                            <h4>Rich Biodiversity</h4>
                            <p>Over 700 bird species across diverse habitats from wetlands to montane forests.</p>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h4>Endemic Species</h4>
                            <p>27 Albertine Rift endemics including the rare Rwenzori Turaco and Albertine Owlet.</p>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h4>Year-Round Birding</h4>
                            <p>Excellent birding opportunities throughout the year with seasonal migrations.</p>
                        </div>
                    </div>
                </div>
                
                <div class="intro-image">
                    <img src="../images/bird/her2.jpg" alt="Birdwatching in Rwanda" loading="lazy">
                    <div class="image-caption">
                        <i class="fas fa-camera"></i>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Nyandungu Section -->
    <section class="nyandungu-section">
        <div class="container">
            <div class="nyandungu-content">
                <div class="nyandungu-image">
                    <img src="../images/bird/ny.jpg" alt="Nyandungu Urban Wetland Eco-Park" loading="lazy">
                    <div class="species-counter">
                        <div class="counter-number">100+</div>
                        <div class="counter-label">Species</div>
                    </div>
                </div>
                
                <div class="nyandungu-text">
                    <div class="section-badge">
                        <i class="fas fa-city"></i>
                        Urban Birding
                    </div>
                    <h2>Birding Begins in the City</h2>
                    <h3>Nyandungu Urban Wetland Eco-Park</h3>
                    
                    <p>You don't have to leave Kigali to begin your birding journey. The Nyandungu Urban Wetland Eco-Park, a 134-hectare restored wetland, offers a peaceful haven for over 100 bird species right in the capital.</p>
                    
                    <p>Stroll along its wooden walkways and you may spot herons and egrets fishing in the marshes, kingfishers darting over pools, cormorants drying their wings, and dazzling sunbirds feeding in the trees. As Rwanda's first large-scale ecological park in an urban setting, Nyandungu shows how nature and city life can coexist—making it the perfect introduction to Rwanda's birdlife before exploring further afield.</p>
                    
                    <div class="nyandungu-features">
                        <div class="feature-item">
                            <i class="fas fa-walking"></i>
                            <span>Wooden walkways for easy access</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-water"></i>
                            <span>Restored wetland ecosystem</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-leaf"></i>
                            <span>Urban conservation success story</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Birding Hotspots Section -->
    <section class="hotspots-section" id="hotspots">
        <div class="container">
            <div class="section-header">
                <h2>Rwanda's Premier Birding Hotspots</h2>
                <p>Discover the country's most spectacular birdwatching destinations</p>
            </div>
            
            <div class="hotspots-grid">
                <div class="hotspot-card featured">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">World-Class</div>
                        <div class="species-count">300+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/nyu.jpg" alt="Nyungwe Forest National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Nyungwe Forest National Park</h3>
                        <p>A world-class birding site with over 300 species, including 27 Albertine Rift endemics such as the Rwenzori Turaco, Regal Sunbird, and the rare Albertine Owlet. The canopy walkway here offers unique vantage points for spotting birds among the treetops.</p>
                        
                        <div class="endemic-species">
                            <h4>Key Endemics:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Rwenzori Turaco</span>
                                <span class="species-tag">Regal Sunbird</span>
                                <span class="species-tag">Albertine Owlet</span>
                                <span class="species-tag">Dusky Crimsonwing</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-tree"></i>
                                <span>Canopy Walkway</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-hiking"></i>
                                <span>Multiple Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-star"></i>
                                <span>27 Endemics</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Wetland Paradise</div>
                        <div class="species-count">480+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/ak2.jpg" alt="Akagera National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Akagera National Park</h3>
                        <p>Vast savannahs, lakes, and marshes host over 480 species, from fish eagles and papyrus gonoleks to the sought-after Shoebill Stork. Akagera's wetlands also attract migratory species, making it a year-round paradise.</p>
                        
                        <div class="endemic-species">
                            <h4>Highlights:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Shoebill Stork</span>
                                <span class="species-tag">Fish Eagle</span>
                                <span class="species-tag">Papyrus Gonolek</span>
                                <span class="species-tag">Red-faced Barbet</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-water"></i>
                                <span>Lake Birding</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-binoculars"></i>
                                <span>Game Drives</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-calendar"></i>
                                <span>Year-Round</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">High Altitude</div>
                        <div class="species-count">180+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/vi.jpg" alt="Volcanoes National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Volcanoes National Park</h3>
                        <p>While most visitors come for gorillas, birders are rewarded with high-altitude specialists like the Ruwenzori Double-collared Sunbird, Dusky Crimsonwing, and strange-sounding francolins.</p>
                        
                        <div class="endemic-species">
                            <h4>Mountain Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Ruwenzori Sunbird</span>
                                <span class="species-tag">Dusky Crimsonwing</span>
                                <span class="species-tag">Handsome Francolin</span>
                                <span class="species-tag">Rwenzori Batis</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-mountain"></i>
                                <span>Mountain Birding</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-paw"></i>
                                <span>Gorilla Combo</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-cloud"></i>
                                <span>Cloud Forest</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Scenic Shores</div>
                        <div class="species-count">120+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/lak.jpg" alt="Lake Kivu Shores" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Lake Kivu Shores & Wetlands</h3>
                        <p>A scenic backdrop for birdwatching, offering waders, cormorants, weavers, and kingfishers along the shoreline. The peaceful lake environment provides excellent photography opportunities.</p>
                        
                        <div class="endemic-species">
                            <h4>Waterbirds:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Pied Kingfisher</span>
                                <span class="species-tag">African Fish Eagle</span>
                                <span class="species-tag">Yellow Wagtail</span>
                                <span class="species-tag">Wire-tailed Swallow</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-camera"></i>
                                <span>Photography</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-water"></i>
                                <span>Shoreline Walks</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-sun"></i>
                                <span>Scenic Views</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/gi.jpg" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Nyirakugunu</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>MUkungwa</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/gi.jpg" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Mpenge</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/gi.jpg" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Rugezi</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/gi.jpg" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Gishwati-Mukura National Park</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="hotspot-card">
                    <div class="hotspot-header">
                        <div class="hotspot-badge">Hidden Gem</div>
                        <div class="species-count">90+ Species</div>
                    </div>
                    <!-- <div class="hotspot-image">
                        <img src="../images/bird/" alt="Gishwati-Mukura National Park" loading="lazy">
                    </div> -->
                    <div class="hotspot-content">
                        <h3>Buhanga</h3>
                        <p>A smaller but ecologically vital forest that shelters Albertine Rift endemics and forest specialists, ideal for those looking for less-visited birding trails and intimate wildlife experiences.</p>
                        
                        <div class="endemic-species">
                            <h4>Forest Specialists:</h4>
                            <div class="species-tags">
                                <span class="species-tag">Mountain Masked Apalis</span>
                                <span class="species-tag">Grauer's Warbler</span>
                                <span class="species-tag">Yellow-eyed Black Flycatcher</span>
                                <span class="species-tag">Strange Weaver</span>
                            </div>
                        </div>
                        
                        <div class="hotspot-features">
                            <div class="feature">
                                <i class="fas fa-leaf"></i>
                                <span>Forest Trails</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-users"></i>
                                <span>Less Crowded</span>
                            </div>
                            <div class="feature">
                                <i class="fas fa-seedling"></i>
                                <span>Conservation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Birding Tours Section -->
    <section class="tours-section" id="tours">
        <div class="container">
            <div class="section-header">
                <h2>Birdwatching Tours & Packages</h2>
                <p>Join our expert-guided birding adventures across Rwanda's diverse habitats</p>
            </div>
            
            <div class="tours-grid">
                <?php
                // Fetch birdwatching tours from database
                $tours_query = "SELECT * FROM tours WHERE (title LIKE '%bird%' OR title LIKE '%birding%' OR title LIKE '%birdwatching%' OR short_description LIKE '%bird%' OR short_description LIKE '%birding%') ORDER BY created_at DESC LIMIT 6";
                $tours_result = mysqli_query($conn, $tours_query);
                
                if (mysqli_num_rows($tours_result) > 0):
                    $tour_count = 0;
                    while ($tour = mysqli_fetch_assoc($tours_result)):
                        $tour_count++;
                        $hidden_class = $tour_count > 6 ? 'hidden' : '';
                ?>
                    <div class="tour-card <?php echo $hidden_class; ?>">
                        <div class="tour-image">
                            <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($tour['title']); ?>" loading="lazy"
                                 onerror="this.src='../images/birds/default-tour.jpg'">
                            <div class="tour-badge">
                                <i class="fas fa-binoculars"></i>
                                Birding Adventure
                            </div>
                        </div>
                        
                        <div class="tour-content">
                            <h3 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <p class="tour-description"><?php echo htmlspecialchars(substr($tour['short_description'], 0, 120)) . '...'; ?></p>
                            
                            <div class="tour-highlights">
                                <span class="highlight-tag">
                                    <i class="fas fa-binoculars"></i>
                                    Birdwatching
                                </span>
                                <span class="highlight-tag">
                                    <i class="fas fa-star"></i>
                                    Endemic Species
                                </span>
                                <span class="highlight-tag">
                                    <i class="fas fa-camera"></i>
                                    Photography
                                </span>
                            </div>
                            
                            <div class="tour-footer">
                                <div class="tour-price">
                                    <?php if (!empty($tour['price'])): ?>
                                        <span class="price-amount">$<?php echo number_format($tour['price']); ?></span>
                                        <span class="price-unit">per person</span>
                                    <?php else: ?>
                                        <span class="price-amount">Contact for pricing</span>
                                    <?php endif; ?>
                                </div>
                                <div class="tour-duration">
                                    <i class="fas fa-clock"></i>
                                    <?php echo htmlspecialchars($tour['days_count']); ?> days
                                </div>
                            </div>
                            
                            <a href="../itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="tour-btn">
                                <i class="fas fa-arrow-right"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="no-tours-message">
                        <div class="no-tours-icon">
                            <i class="fas fa-binoculars"></i>
                        </div>
                        <h3>Birding Tours Coming Soon</h3>
                        <p>We're currently preparing amazing birdwatching adventure tours. Check back soon or contact us for custom birding arrangements.</p>
                        <a href="../contact.php" class="contact-btn">
                            <i class="fas fa-envelope"></i>
                            Contact Us for Custom Tours
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Why Rwanda Section -->
    <section class="why-rwanda-section">
        <div class="container">
            <div class="why-rwanda-content">
                <div class="why-rwanda-text">
                    <h2>Why Rwanda is a Birding Haven</h2>
                    <p class="section-lead">Rwanda's compact size means you can explore a wide variety of ecosystems in just a few days—going from city wetlands to montane forests and savannahs within hours. This accessibility, combined with rich species diversity, makes Rwanda one of the most efficient and rewarding birding destinations in Africa.</p>
                    
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Compact & Accessible</h4>
                                <p>Explore diverse ecosystems within hours - from wetlands to montane forests and savannahs.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-dna"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Rich Species Diversity</h4>
                                <p>Over 700 species including 27 Albertine Rift endemics found nowhere else on Earth.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Photography Paradise</h4>
                                <p>Stunning landscapes provide perfect backdrops for bird photography and wildlife documentation.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Multi-Activity Destination</h4>
                                <p>Combine birdwatching with gorilla trekking, primate tracking, or cultural tours for a complete experience.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="why-rwanda-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-dove"></i>
                        </div>
                        <div class="stat-number">700+</div>
                        <div class="stat-label">Bird Species Recorded</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-number">27</div>
                        <div class="stat-label">Albertine Rift Endemics</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-tree"></i>
                        </div>
                        <div class="stat-number">5</div>
                        <div class="stat-label">Major Birding Habitats</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Discover Rwanda's Birdlife?</h2>
                <p>Join us for an unforgettable birding adventure through Rwanda's diverse habitats. It's not just about the birds—it's about the stories, sounds, and natural beauty that come alive with every step and every glance through your binoculars.</p>
                
                <div class="cta-buttons">
                    <a href="contactus.php" class="cta-btn primary">
                        <i class="fas fa-binoculars"></i>
                        Book Your Birding Adventure
                    </a>
                </div>
                
                <div class="cta-features">
                    <div class="feature">
                        <i class="fas fa-binoculars"></i>
                        <span>Quality Optics Provided</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-user-friends"></i>
                        <span>Expert Birding Guides</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-book"></i>
                        <span>Field Guides Included</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <span>Conservation Impact</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include "./includes/footer.php" ?>

    <!-- JavaScript -->
    <script src="../js/birdwatching.js"></script>
    <script src="../js/header.js" defer></script>
</body>
</html>
