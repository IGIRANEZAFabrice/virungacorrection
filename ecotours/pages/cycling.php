<?php require_once('../admin/config/connection.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cycling in Rwanda - Land of a Thousand Hills | Virunga Ecotours</title>
    <meta name="description" content="Discover Rwanda's breathtaking cycling routes through the Land of a Thousand Hills. From Congo Nile Trail to volcanic foothills, experience unforgettable cycling adventures.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/icon.png">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/cycling.css">
    
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
                
                <h1>Cycling in Rwanda</h1>
                <p class="hero-subtitle">Pedaling Through the Land of a Thousand Hills</p>
                <div class="hero-buttons">
                    <a href="#tours" class="btn-primary">
                        <i class="fas fa-bicycle"></i>
                        Explore Cycling Tours
                    </a>
                    <a href="#routes" class="btn-secondary">
                        <i class="fas fa-route"></i>
                        Discover Routes
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
                    <h2>A Paradise for Cyclists</h2>
                    <p class="intro-lead">Rwanda is not only a land of breathtaking landscapes and rich culture—it is also a paradise for cyclists. Known as the Land of a Thousand Hills, the country's rolling terrain, winding roads, and scenic trails make it one of Africa's most rewarding cycling destinations.</p>
                    
                    <p>Whether you are an avid rider seeking endurance challenges or a leisure traveler wanting to connect with nature and culture at a slower pace, cycling offers an unforgettable way to explore Rwanda.</p>
                    
                    <div class="intro-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <h4>Diverse Terrain</h4>
                            <p>From rolling hills to volcanic slopes, every route offers unique challenges and stunning views.</p>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>Cultural Immersion</h4>
                            <p>Connect with local communities, visit villages, and experience authentic Rwandan hospitality.</p>
                        </div>
                        
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-road"></i>
                            </div>
                            <h4>Modern Infrastructure</h4>
                            <p>Well-paved roads and maintained trails ensure safe and enjoyable cycling experiences.</p>
                        </div>
                    </div>
                </div>
                
                <div class="intro-image">
                    <img src="../images/cycling/herdown.jpg" alt="Cycling through Rwanda's hills" loading="lazy">
                    <div class="image-caption">
                        <i class="fas fa-camera"></i>
                        Cyclists enjoying the scenic Congo Nile Trail
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experience Section -->
    <section class="experience-section">
        <div class="container">
            <div class="section-header">
                <h2>The Cycling Experience</h2>
                <p>Picture yourself pedaling through Rwanda's most spectacular landscapes</p>
            </div>
            
            <div class="experience-grid">
                <div class="experience-card">
                    <div class="experience-image">
                        <img src="../images/cycling/vi.jpg" alt="Cycling through tea plantations" loading="lazy">
                        <div class="experience-overlay">
                            <i class="fas fa-leaf"></i>
                        </div>
                    </div>
                    <div class="experience-content">
                        <h3>Tea Plantation Trails</h3>
                        <p>Pedal past lush tea plantations where the air is fresh and the views are endless. Stop to learn about tea cultivation and enjoy a cup of the finest Rwandan tea.</p>
                    </div>
                </div>
                
                <div class="experience-card">
                    <div class="experience-image">
                        <img src="../images/cycling/kiv.jpg" alt="Cycling along Lake Kivu" loading="lazy">
                        <div class="experience-overlay">
                            <i class="fas fa-water"></i>
                        </div>
                    </div>
                    <div class="experience-content">
                        <h3>Lake Kivu Shores</h3>
                        <p>Cruise along the tranquil shores of Lake Kivu, one of Africa's Great Lakes. The gentle lakeside routes offer stunning water views and peaceful cycling.</p>
                    </div>
                </div>
                
                <div class="experience-card">
                    <div class="experience-image">
                        <img src="../images/cycling/vir.jpg" alt="Cycling volcanic foothills" loading="lazy">
                        <div class="experience-overlay">
                            <i class="fas fa-mountain"></i>
                        </div>
                    </div>
                    <div class="experience-content">
                        <h3>Volcanic Foothills</h3>
                        <p>Challenge yourself on the slopes of the Virunga Volcanoes. These routes offer dramatic landscapes and the chance to spot wildlife.</p>
                    </div>
                </div>
                
                <div class="experience-card">
                    <div class="experience-image">
                        <img src="../images/cycling/IMG-20250807-WA0123.jpg" alt="Cycling through villages" loading="lazy">
                        <div class="experience-overlay">
                            <i class="fas fa-home"></i>
                        </div>
                    </div>
                    <div class="experience-content">
                        <h3>Village Encounters</h3>
                        <p>Experience the daily rhythm of Rwandan life as you cycle through vibrant villages where children wave with joy and communities welcome you warmly.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Routes Section -->
    <section class="routes-section" id="routes">
        <div class="container">
            <div class="section-header">
                <h2>Iconic Cycling Routes</h2>
                <p>Discover Rwanda's most spectacular cycling trails and adventures</p>
            </div>
            
            <div class="routes-grid">
                <div class="route-card featured">
                    <div class="route-header">
                        <div class="route-badge">Most Popular</div>
                        <div class="route-difficulty">Moderate</div>
                    </div>
                    <div class="route-image">
                        <img src="../images/cycling/nile.jpg" alt="Congo Nile Trail" loading="lazy">
                    </div>
                    <div class="route-content">
                        <h3>Congo Nile Trail</h3>
                        <p>Rwanda's most famous cycling route stretching 227km from Rubavu to Rusizi. Experience diverse landscapes, cultural sites, and stunning lake views.</p>
                        
                        <div class="route-stats">
                            <div class="stat">
                                <i class="fas fa-route"></i>
                                <span>227 km</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>3-5 days</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-chart-line"></i>
                                <span>Moderate</span>
                            </div>
                        </div>
                        
                        <div class="route-highlights">
                            <span class="highlight-tag">Lake Views</span>
                            <span class="highlight-tag">Cultural Sites</span>
                            <span class="highlight-tag">Tea Plantations</span>
                        </div>
                    </div>
                </div>
                
                <div class="route-card">
                    <div class="route-header">
                        <div class="route-difficulty">Challenging</div>
                    </div>
                    <div class="route-image">
                        <img src="../images/cycling/mo.jpg" alt="Virunga Volcanoes Route" loading="lazy">
                    </div>
                    <div class="route-content">
                        <h3>Virunga Volcanoes Circuit</h3>
                        <p>Challenge yourself on the slopes of Rwanda's volcanic mountains. This route offers dramatic elevation changes and spectacular mountain views.</p>
                        
                        <div class="route-stats">
                            <div class="stat">
                                <i class="fas fa-route"></i>
                                <span>85 km</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>2 days</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-chart-line"></i>
                                <span>Challenging</span>
                            </div>
                        </div>
                        
                        <div class="route-highlights">
                            <span class="highlight-tag">Mountain Views</span>
                            <span class="highlight-tag">Wildlife</span>
                            <span class="highlight-tag">Adventure</span>
                        </div>
                    </div>
                </div>
                
                <div class="route-card">
                    <div class="route-header">
                        <div class="route-difficulty">Easy</div>
                    </div>
                    <div class="route-image">
                        <img src="../images/cycling/cou.jpg" alt="Countryside Route" loading="lazy">
                    </div>
                    <div class="route-content">
                        <h3>Countryside Discovery</h3>
                        <p>Gentle rides through Rwanda's beautiful countryside, perfect for families and leisure cyclists. Experience rural life and stunning landscapes.</p>
                        
                        <div class="route-stats">
                            <div class="stat">
                                <i class="fas fa-route"></i>
                                <span>45 km</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-clock"></i>
                                <span>1 day</span>
                            </div>
                            <div class="stat">
                                <i class="fas fa-chart-line"></i>
                                <span>Easy</span>
                            </div>
                        </div>
                        
                        <div class="route-highlights">
                            <span class="highlight-tag">Family Friendly</span>
                            <span class="highlight-tag">Cultural</span>
                            <span class="highlight-tag">Scenic</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cycling Tours Section -->
    <section class="tours-section" id="tours">
        <div class="container">
            <div class="section-header">
                <h2>Cycling Tours & Packages</h2>
                <p>Join our expertly guided cycling adventures across Rwanda</p>
            </div>
            
            <div class="tours-grid">
                <?php
                // Fetch cycling tours from database
                $tours_query = "SELECT * FROM tours WHERE (title LIKE '%cycling%' OR title LIKE '%bike%' OR title LIKE '%bicycle%' OR short_description LIKE '%cycling%' OR short_description LIKE '%bike%') ORDER BY created_at DESC LIMIT 6";
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
                                 onerror="this.src='../images/cycling/default-tour.jpg'">
                            <div class="tour-badge">
                                <i class="fas fa-bicycle"></i>
                                Cycling Adventure
                            </div>
                        </div>
                        
                        <div class="tour-content">
                            <h3 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <p class="tour-description"><?php echo htmlspecialchars(substr($tour['short_description'], 0, 120)) . '...'; ?></p>
                            
                            <div class="tour-highlights">
                                <span class="highlight-tag">
                                    <i class="fas fa-bicycle"></i>
                                    Cycling
                                </span>
                                <span class="highlight-tag">
                                    <i class="fas fa-mountain"></i>
                                    Scenic Routes
                                </span>
                                <span class="highlight-tag">
                                    <i class="fas fa-users"></i>
                                    Cultural Experience
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
                            <i class="fas fa-bicycle"></i>
                        </div>
                        <h3>Cycling Tours Coming Soon</h3>
                        <p>We're currently preparing amazing cycling adventure tours. Check back soon or contact us for custom cycling arrangements.</p>
                        <a href="../contact.php" class="contact-btn">
                            <i class="fas fa-envelope"></i>
                            Contact Us for Custom Tours
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            
        </div>
    </section>

    <!-- Why Choose Cycling Section -->
    <section class="why-cycling-section">
        <div class="container">
            <div class="why-cycling-content">
                <div class="why-cycling-text">
                    <h2>Why Choose Cycling in Rwanda?</h2>
                    <p class="section-lead">Cycling here is more than just sport; it's an immersive journey that allows travelers to slow down, breathe in the fresh highland air, and appreciate the country's geography and culture from the ground up.</p>
                    
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Immersive Experience</h4>
                                <p>Connect with nature and culture at a slower pace, experiencing the daily rhythm of Rwandan life.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Hidden Gems</h4>
                                <p>Unlock hidden gems that vehicles pass by too quickly. Pause for local meals, visit homestays, and join cultural activities.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>World-Class Destination</h4>
                                <p>Rwanda hosts the Tour du Rwanda, one of Africa's most prestigious races, showcasing its status as a global cycling hotspot.</p>
                            </div>
                        </div>
                        
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <div class="benefit-content">
                                <h4>Eco-Friendly Adventure</h4>
                                <p>Explore Rwanda's natural beauty while minimizing your environmental impact through sustainable cycling tourism.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="why-cycling-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-road"></i>
                        </div>
                        <div class="stat-number">2,000+</div>
                        <div class="stat-label">Kilometers of Cycling Routes</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-mountain"></i>
                        </div>
                        <div class="stat-number">1,000</div>
                        <div class="stat-label">Hills to Explore</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Villages to Visit</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Pedal Through Paradise?</h2>
                <p>Join us for an unforgettable cycling adventure through Rwanda's stunning landscapes and vibrant communities. Every route tells a story of nature, culture, and discovery.</p>
                
                <div class="cta-buttons">
                    <a href="contactus.php" class="cta-btn primary">
                        <i class="fas fa-bicycle"></i>
                        Book Your Cycling Adventure
                    </a>
                   
                </div>
                
                <div class="cta-features">
                    <div class="feature">
                        <i class="fas fa-bicycle"></i>
                        <span>Quality Bikes Provided</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-user-friends"></i>
                        <span>Expert Local Guides</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>Safety Equipment Included</span>
                    </div>
                    <div class="feature">
                        <i class="fas fa-heart"></i>
                        <span>Community Impact</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include "./includes/footer.php" ?>

    <!-- JavaScript -->
    <script src="../js/cycling.js"></script>
    <script src="../js/header.js" defer></script>
</body>
</html>
