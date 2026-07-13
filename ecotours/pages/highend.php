<?php
require_once 'handlers/itenary_handler.php';
$featuredTours = getToursByTitleKeyword('Luxury', 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>High-End Luxury Tours in the Virunga Mountains | Virunga Ecotours</title>
    <meta name="description" content="Experience the ultimate in luxury travel with our exclusive high-end tours. Private guides, luxury lodges, and meaningful cultural immersion in the Virunga Mountains.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/icon.png">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/highend.css">
    
    <!-- Premium Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include "includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section" style="background-image: url('../images/hero/1.jpg');">
        <div class="hero-content">
            <h1>High-End Luxury Tours in the Virunga Mountains</h1>
        </div>
    </section>

    <!-- Luxury Content Container -->
    <div class="content">
        
        <!-- Luxury Introduction -->
        <p>Virunga Ecotours specializes in crafting <strong class="highlight-text">high-end luxury tours</strong> for discerning travelers who wish to explore the breathtaking Virunga Mountains in the most <em>exclusive and comfortable way</em>. These tours are designed for visitors who seek not only to encounter the extraordinary beauty of mountain gorillas, golden monkeys, and volcanic landscapes, but also to do so with the <strong>finest level of service, privacy, and cultural immersion</strong>.</p>

        <div class="section-divider"></div>

        <!-- Luxury Experience Section -->
        <div class="luxury-feature">
            <h3>Why Choose a Luxury Tour in the Virunga Mountains?</h3>
            <p>Luxury tours with Virunga Ecotours go <strong>beyond standard travel</strong>. Guests enjoy private transportation in top-class vehicles, handpicked luxury lodges with panoramic views, and personalized experiences led by expert guides. The tours are tailored to provide seamless comfort, from exclusive gorilla trekking permits to curated culinary experiences and private cultural performances. Every detail is designed to give visitors time and space to absorb the magic of the Virungas without compromise.</p>
        </div>

        <!-- Premium Services Showcase -->
        <section class="luxury-services-section">
            <h2 class="section-title">Exclusive Luxury Experiences</h2>
            
            <div class="luxury-services">
            <div class="service-grid">
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-helicopter"></i>
                    </div>
                    <h4>Private Helicopter Transfers</h4>
                    <p>Skip the roads and arrive in style with exclusive helicopter access to remote locations.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-concierge-bell"></i>
                    </div>
                    <h4>Personal Butler Service</h4>
                    <p>Dedicated personal service ensuring every need is anticipated and fulfilled.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Private Chef Experiences</h4>
                    <p>Gourmet dining with world-class chefs using local ingredients and traditional techniques.</p>
                </div>
                
                <div class="service-item">
                    <div class="service-icon">
                        <i class="fas fa-spa"></i>
                    </div>
                    <h4>Exclusive Wellness</h4>
                    <p>Private spa treatments and wellness experiences in stunning natural settings.</p>
                </div>
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Community Impact with Luxury Touch -->
        <section class="community-impact-section">
            <h2>The Importance of Luxury in Community-Based Tourism</h2>
            <div class="impact-content">
                <p>What sets Virunga Ecotours apart is its <strong class="highlight-text">strong foundation in community-based tourism</strong>. Even while offering luxury, the tours are deeply rooted in supporting local communities around the Virunga Mountains. A portion of the revenue directly benefits local projects ranging from education and cultural preservation to small-scale enterprises. By choosing a luxury package, visitors not only indulge in world-class experiences but also <em>uplift communities</em> through:</p>
            </div>
        </section>

        <!-- Featured Luxury Tours with Pricing -->
        <section class="featured-tours">
            <h2 class="section-title">Explore Our Premium Tours</h2>
            <div class="tours-cards" id="luxuryToursContainer">
                <?php if (!empty($featuredTours)): ?>
                    <?php foreach ($featuredTours as $index => $tour): ?>
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
                    <p class="no-tours">Premium tours coming soon.</p>
                <?php endif; ?>
            </div>
            <?php if (count($featuredTours) > 3): ?>
                <div class="view-more-container">
                    <button id="viewMoreLuxuryBtn" class="view-more-btn">View More</button>
                </div>
            <?php endif; ?>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const viewMoreBtn = document.getElementById('viewMoreLuxuryBtn');
                const cards = document.querySelectorAll('#luxuryToursContainer .tour-card');
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

        <!-- Premium List Styling -->
        <ul class="luxury-list">
            <li><strong>Employment and training:</strong> Local guides, drivers, and hospitality teams are given opportunities to showcase their skills at the highest level of service.</li>
            <li><strong>Cultural enrichment:</strong> Guests are introduced to traditional Rwandan and Congolese crafts, music, and cuisine in ways that preserve heritage while creating pride and sustainable income.</li>
            <li><strong>Sustainable development:</strong> Revenue supports initiatives like anti-poaching patrols, reforestation, and women- and youth-led projects around the Virungas.</li>
        </ul>

        <!-- Luxury Impact Statistics -->
        <div class="impact-stats">
            <div class="stat-luxury">
                <span class="stat-number">95%</span>
                <span class="stat-label">Revenue to Local Communities</span>
            </div>
            <div class="stat-luxury">
                <span class="stat-number">50+</span>
                <span class="stat-label">Local Families Employed</span>
            </div>
            <div class="stat-luxury">
                <span class="stat-number">100%</span>
                <span class="stat-label">Sustainable Practices</span>
            </div>
        </div>

        <!-- Premium Call to Action -->
        <div class="luxury-cta">
            <h3>A Journey with Meaning</h3>
            <p>Virunga Ecotours believes that <strong>true luxury is more than comfort</strong>—it is about connection and impact. Travelers leave with memories of gorillas, volcanoes, and rare birds, but also with the satisfaction of knowing their journey directly contributes to the well-being of the people who call the Virunga Mountains home.</p>
            
            <div class="luxury-contact">
                <p><strong>Ready for the ultimate luxury experience?</strong></p>
                <p>Contact our luxury travel specialists to design your bespoke Virunga adventure.</p>
            </div>
        </div>

        <!-- Luxury Testimonial Section -->
        <div class="luxury-testimonial">
            <blockquote>
                <p>"This wasn't just a trip it was a transformative experience. The level of service, attention to detail, and meaningful connections with local communities exceeded every expectation. Virunga Ecotours has redefined what luxury travel means to us."</p>
                <cite>Distinguished Guest, 2024</cite>
            </blockquote>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include "includes/footer.php" ?>
    
    <!-- JavaScript -->
    <script src="../js/header.js" defer></script>
</body>
</html>