<?php
// Database connection and data fetching
require_once('../admin/config/db_connect.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honeymoon & Family Tours - Virunga Ecotours</title>
    <meta name="description" content="Discover the Virunga Massif through romantic honeymoon and family-friendly tours. Experience community-based tourism with cultural immersion, adventure, and meaningful connections.">
    <meta name="keywords" content="honeymoon tours, family tours, Virunga Massif, community tourism, romantic travel, family adventure, Rwanda, Uganda, Congo">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/images/favicon.ico">

    <!-- External CSS -->
    <link rel="stylesheet" href="../community/assets/css/honeymoon.css">
    <link rel="stylesheet" href="../css/header.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-slider">
            <div class="hero-slide active">
                <img src="../images/honeymoon/hero/IMG-20250911-WA0007.jpg" alt="Romantic Honeymoon Volunteering" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">Honeymoon & Family Tours</h1>
                    <p class="hero-subtitle">Where Love Meets Adventure</p>
                    <p class="hero-description">Discover the breathtaking Virunga Massif through romantic and family-friendly community-based tourism experiences</p>
                </div>
            </div>
            <div class="hero-slide">
                <img src="../images/honeymoon/hero/20230213_120618.jpg" alt="Family Adventure in Virunga" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">Family Adventures</h1>
                    <p class="hero-subtitle">Creating Memories Together</p>
                    <p class="hero-description">Experience cultural immersion and natural wonders perfect for couples and families in the Virunga region</p>
                </div>
            </div>
            <div class="hero-slide">
                <img src="../images/honeymoon/hero/IMG_0164.jpg" alt="Community Impact Honeymoon" class="hero-image">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">Beyond Traditional</h1>
                    <p class="hero-subtitle">A Honeymoon with Heart</p>
                    <p class="hero-description">Discover how your love story can become part of a larger narrative of positive change</p>
                </div>
            </div>
        </div>
        <div class="hero-navigation">
            <button class="hero-nav-btn prev" onclick="changeSlide(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="hero-nav-btn next" onclick="changeSlide(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="hero-indicators">
            <span class="indicator active" onclick="currentSlide(1)"></span>
            <span class="indicator" onclick="currentSlide(2)"></span>
            <span class="indicator" onclick="currentSlide(3)"></span>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="introduction-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>Honeymoon & Family Tours in the Virunga Massif</h2>
                    <p class="intro-lead">A Community-Based Tourism Experience with Virunga Ecotours</p>

                    <p>The Virunga Massif is more than just a destination—it is a living mosaic of towering volcanoes, rich cultures, and communities whose warmth turns every journey into a shared story. Virunga Ecotours invites honeymooners and families to discover this breathtaking region through carefully designed community-based tourism experiences that blend romance, adventure, and cultural immersion.</p>

                    <div class="intro-highlights">
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <h4>For Honeymooners</h4>
                            <p>Celebrate love in an atmosphere of tranquility and awe. The lush volcanic landscapes, serene crater lakes, and private cultural encounters offer intimacy and romance.</p>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4>For Families</h4>
                            <p>Traveling with children and loved ones becomes effortless through programs crafted for all ages with meaningful experiences for everyone.</p>
                        </div>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="fas fa-hands-helping"></i>
                            </div>
                            <h4>For Communities</h4>
                            <p>Every experience contributes directly to local livelihoods. Families and honeymooners empower farmers, artists, and storytellers.</p>
                        </div>
                    </div>
                </div>

                <div class="intro-images">
                    <div class="intro-image-main">
                        <img src="../images/honeymoon/IMG_0218.jpg" alt="Couple Volunteering in Community" class="intro-img">
                        
                    </div>
                    <div class="intro-image-grid">
                        <img src="../images/honeymoon/IMG_0161.jpg" alt="Teaching Children" class="intro-img-small">
                        <img src="../images/honeymoon/IMG_0167.jpg" alt="Conservation Work" class="intro-img-small">
                        <img src="../images/honeymoon/IMG-20250911-WA0074.jpg" alt="Health Outreach" class="intro-img-small">
                        <img src="../images/honeymoon/IMG-20250911-WA0072.jpg" alt="Cultural Exchange" class="intro-img-small">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Choose This Tour?</h2>
                <p>Discover what makes our honeymoon and family tours special</p>
            </div>

            <div class="why-choose-grid">
                <div class="why-choose-card">
                    <div class="why-choose-header">
                        <div class="why-choose-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>For Honeymooners</h3>
                        <div class="why-choose-image">
                            <img src="../images/honeymoon/d4169c51-2071-4e83-bb2b-6c62acbe177a-2.jpg" alt="Romantic Honeymoon" class="why-choose-img">
                        </div>
                    </div>
                    <div class="why-choose-content">
                        <p>Celebrate love in an atmosphere of tranquility and awe. The lush volcanic landscapes, serene crater lakes, and private cultural encounters offer intimacy and romance. Candlelit dinners at local homestays, storytelling evenings, and walks along mist-covered hillsides create memories that last a lifetime.</p>
                    </div>
                </div>

                <div class="why-choose-card">
                    <div class="why-choose-header">
                        <div class="why-choose-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>For Families</h3>
                        <div class="why-choose-image">
                            <img src="../images/honeymoon/IMG-20250911-WA0066.jpg" alt="Family Adventure" class="why-choose-img">
                        </div>
                    </div>
                    <div class="why-choose-content">
                        <p>Traveling with children and loved ones becomes effortless through programs crafted for all ages. While parents may engage in treks or cultural exchanges, children are guided into games, arts, storytelling, and nature discovery, ensuring meaningful experiences for everyone.</p>
                    </div>
                </div>

                <div class="why-choose-card">
                    <div class="why-choose-header">
                        <div class="why-choose-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>For Communities</h3>
                        <div class="why-choose-image">
                            <img src="../images/honeymoon/IMG-20250911-WA0053.jpg" alt="Community Impact" class="why-choose-img">
                        </div>
                    </div>
                    <div class="why-choose-content">
                        <p>Every experience contributes directly to local livelihoods. Families and honeymooners don't just visit—they empower farmers, artists, and storytellers by sharing meals, crafts, and knowledge. This support sustains communities and enriches conservation around the Virunga Massif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Gallery Section -->
    <section class="activities-section">
        <div class="container">
            <div class="section-header">
                <h2>Activities for Honeymooners & Families</h2>
                <p>Experience authentic cultural immersion and adventure together</p>
            </div>

            <div class="activities-grid">
                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/coffe.jpg" alt="Coffee to Cup Experience" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-coffee"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Coffee to Cup Experience</h3>
                        <p>Visit local farms, pick ripe coffee cherries, and join in roasting and tasting sessions. Couples enjoy the sensory journey together, while children explore the fields and learn from farmers.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Farm Visit</span>
                            <span class="feature-tag">Tasting</span>
                            <span class="feature-tag">Learning</span>
                        </div>
                    </div>
                </div>


                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/night.jpg" alt="Village Walks & Storytelling" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-walking"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Village Walks & Storytelling Nights</h3>
                        <p>Explore rural life through guided walks, traditional music, dance, and fireside storytelling—perfect for bonding and cultural exchange.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Culture</span>
                            <span class="feature-tag">Music</span>
                            <span class="feature-tag">Stories</span>
                        </div>
                    </div>
                </div>

                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/arts.jpg" alt="Arts & Games for Families" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Arts & Games for Families</h3>
                        <p>Kids engage in painting, weaving, and traditional games with local children, while parents explore or relax.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Arts</span>
                            <span class="feature-tag">Games</span>
                            <span class="feature-tag">Kids</span>
                        </div>
                    </div>
                </div>

                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/garelly2.jpg" alt="Romantic Adventures" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Romantic Adventures</h3>
                        <p>Private canoe rides on Twin Lakes, scenic picnics on volcanic slopes, or soft treks to hidden viewpoints offer intimacy away from the crowds.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Romance</span>
                            <span class="feature-tag">Nature</span>
                            <span class="feature-tag">Private</span>
                        </div>
                    </div>
                </div>

                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/culture.jpg" alt="Cultural Exchange" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-drum"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Cultural Exchange</h3>
                        <p>Immerse yourselves in local traditions, participate in cultural ceremonies, and learn traditional crafts.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Culture</span>
                            <span class="feature-tag">Traditions</span>
                            <span class="feature-tag">Arts</span>
                        </div>
                    </div>
                </div>


                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../images/honeymoon/agliculture.jpg" alt="Agricultural Support" class="activity-img">
                        <div class="activity-overlay">
                            <div class="activity-icon">
                                <i class="fas fa-seedling"></i>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3>Agricultural Programs</h3>
                        <p>Support sustainable farming initiatives, help with crop cultivation, and learn traditional farming methods.</p>
                        <div class="activity-features">
                            <span class="feature-tag">Farming</span>
                            <span class="feature-tag">Sustainability</span>
                            <span class="feature-tag">Food Security</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expectations Section -->
    <section class="expectations-section">
        <div class="container">
            <div class="section-header">
                <h2>What We Expect from Our Volunteer Couples</h2>
                <p>Understanding your role in creating meaningful community impact</p>
            </div>

            <div class="expectations-table">
                <div class="table-header">
                    <div class="table-cell">Area</div>
                    <div class="table-cell">What You Are Expected To Do</div>
                    <div class="table-cell">Impact on Community</div>
                </div>

                <div class="table-row">
                    <div class="table-cell area-cell">
                        <div class="area-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <span>Project Participation</span>
                    </div>
                    <div class="table-cell">Join structured projects such as teaching, health outreach, or conservation</div>
                    <div class="table-cell">Provides direct support to local development priorities</div>
                </div>

                <div class="table-row">
                    <div class="table-cell area-cell">
                        <div class="area-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <span>Shared Responsibility</span>
                    </div>
                    <div class="table-cell">Work collaboratively as a couple, reinforcing values of unity and teamwork</div>
                    <div class="table-cell">Demonstrates positive partnership models to community youth and groups</div>
                </div>

                <div class="table-row">
                    <div class="table-cell area-cell">
                        <div class="area-icon">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <span>Cultural Exchange</span>
                    </div>
                    <div class="table-cell">Respectfully engage in cultural traditions, homestays, and daily community life</div>
                    <div class="table-cell">Strengthens intercultural understanding and fosters mutual appreciation</div>
                </div>

                <div class="table-row">
                    <div class="table-cell area-cell">
                        <div class="area-icon">
                            <i class="fas fa-monument"></i>
                        </div>
                        <span>Legacy Building</span>
                    </div>
                    <div class="table-cell">Connect honeymoon experiences with long-term community benefits</div>
                    <div class="table-cell">Leaves a meaningful and memorable mark beyond tourism</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Photo Gallery Section -->
    <section class="photo-gallery-section">
        <div class="container">
            <div class="section-header">
                <h2>Moments of Love and Service</h2>
                <p>Capturing the beautiful intersection of romance and community impact</p>
            </div>

            <div class="gallery-masonry">
                <div class="gallery-item large">
                    <img src="../images/honeymoon/garelly1.jpg" alt="Couple teaching children" class="gallery-img">
                    <!-- <div class="gallery-overlay">
                        <h4>Teaching Together</h4>
                        <p>Sharing knowledge and love with local children</p>
                    </div> -->
                </div>

                <div class="gallery-item large">
                    <img src="../images/honeymoon/garelly3.jpg" alt="Health outreach work" class="gallery-img">
                    <!-- <div class="gallery-overlay">
                        <h4>Health Outreach</h4>
                        <p>Supporting community wellness programs</p>
                    </div> -->
                </div>

                <div class="gallery-item large">
                    <img src="../images/honeymoon/garelly4.jpg" alt="Conservation project" class="gallery-img">
                    <!-- <div class="gallery-overlay">
                        <h4>Conservation Work</h4>
                        <p>Protecting the environment together</p>
                    </div> -->
                </div>
                
            </div>
        </div>
    </section>
     <!-- Related Itineraries Section -->
    <?php
    // Fetch itineraries that contain "honeymoon" or "family" in their title
    try {
        $stmt = $pdo->prepare("
            SELECT tour_id, title, short_description, cover_image_path, days_count, category, country
            FROM tours
            WHERE (LOWER(title) LIKE '%honeymoon%' OR LOWER(title) LIKE '%family%')
            AND tour_id IS NOT NULL
            ORDER BY created_at DESC
            LIMIT 6
        ");
        $stmt->execute();
        $related_tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $related_tours = [];
        error_log("Error fetching related tours: " . $e->getMessage());
    }
    ?>

    <?php if (!empty($related_tours)): ?>
    <section class="related-itineraries-section">
        <div class="container">
            <div class="section-header">
                <h2>Recommended Honeymoon & Family Itineraries</h2>
                <p>Discover our carefully crafted tours perfect for couples and families</p>
            </div>

            <div class="itineraries-grid">
                <?php foreach ($related_tours as $tour): ?>
                    <div class="itinerary-card">
                        <div class="itinerary-image">
                            <?php if (!empty($tour['cover_image_path'])): ?>
                                <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                            <?php else: ?>
                                <div class="placeholder-image">
                                    <i class="fas fa-mountain"></i>
                                </div>
                            <?php endif; ?>
                            <div class="itinerary-badge">
                                <span class="badge-country"><?php echo ucfirst(htmlspecialchars($tour['country'])); ?></span>
                                <span class="badge-days"><?php echo htmlspecialchars($tour['days_count']); ?> Days</span>
                            </div>
                        </div>
                        <div class="itinerary-content">
                            <div class="itinerary-category">
                                <i class="fas fa-tag"></i>
                                <span><?php echo htmlspecialchars($tour['category']); ?></span>
                            </div>
                            <h3 class="itinerary-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <p class="itinerary-description">
                                <?php
                                $description = htmlspecialchars($tour['short_description']);
                                echo strlen($description) > 120 ? substr($description, 0, 120) . '...' : $description;
                                ?>
                            </p>
                            <div class="itinerary-actions">
                                <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="view-tour-btn">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                                <a href="build.php?tour_id=<?php echo $tour['tour_id']; ?>" class="book-tour-btn">
                                    <i class="fas fa-calendar-plus"></i>
                                    Book Now
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="view-all-container">
                <a href="itenary.php" class="view-all-btn">
                    <i class="fas fa-th-large"></i>
                    View All Tours
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-header">
                <h2>Love Stories with Impact</h2>
                <p>Hear from couples who chose to make their honeymoon meaningful</p>
            </div>

            <div class="testimonials-grid">
                

                <div class="testimonial-card">
                   
                    <div class="testimonial-content">
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p>"Working together on conservation projects strengthened our partnership in ways we never expected. We learned that our love could be a force for positive change in the world. Rwanda will always be where our marriage truly began."</p>
                        </div>
                        <div class="testimonial-author">
                            <h4>Emma & David Rodriguez</h4>
                            <span>Conservation Volunteers, 2023</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                   
                    <div class="testimonial-content">
                        <div class="testimonial-quote">
                            <i class="fas fa-quote-left"></i>
                            <p>"The health outreach program allowed us to use our medical backgrounds to help others while celebrating our new marriage. Every day brought new challenges and rewards that we faced together as a team."</p>
                        </div>
                        <div class="testimonial-author">
                            <h4>Lisa & James Chen</h4>
                            <span>Health Outreach Volunteers, 2022</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Start Your Love Story with Purpose</h2>
                <p>Join us for a honeymoon experience that will create lasting memories and meaningful impact. Your love story can become part of a larger narrative of positive change in the world.</p>
                <div class="cta-buttons">
                    <a href="contactus.php" class="cta-btn primary">
                        <i class="fas fa-heart"></i>
                        Plan Your Honeymoon
                    </a>
                    <a href="about.php" class="cta-btn secondary">
                        <i class="fas fa-info-circle"></i>
                        Learn More
                    </a>
                </div>
                <div class="cta-features">
                    <div class="cta-feature">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Flexible Dates</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-users"></i>
                        <span>Couples Only</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-certificate"></i>
                        <span>Certified Programs</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-heart"></i>
                        <span>Lifetime Memories</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <?php include 'includes/footer.php'; ?>
    <script src="../js/header.js" defer></script>
    <!-- External JavaScript -->
    <script src="../community/assets/js/honeymoon.js"></script>
</body>
</html>