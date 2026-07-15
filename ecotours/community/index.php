<?php
// Reuse the exact database fetching mechanism from community/index.php
require_once '../admin/config/connection.php';

// Fetch community hero slides
$hero_query = "SELECT * FROM community_hero ORDER BY id ASC";
$hero_result = mysqli_query($conn, $hero_query);

// Fetch featured programs
$featured_programs_query = "SELECT * FROM community_programs WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT 3";
$featured_programs_result = mysqli_query($conn, $featured_programs_query);

// Fetch testimonials
$testimonials_query = "SELECT * FROM community_testimonials WHERE featured = 1 AND status = 'active' ORDER BY created_at DESC LIMIT 3";
$testimonials_result = mysqli_query($conn, $testimonials_query);

// Fetch program statistics
$stats_query = "SELECT
    COUNT(*) as total_programs,
    SUM(beneficiaries) as total_beneficiaries,
    COUNT(DISTINCT country) as countries_served
    FROM community_programs WHERE status IN ('active', 'completed')";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Fetch activities (limited to 3)
$activities_query = "SELECT * FROM community_activities WHERE status = 'active' AND is_active = 1 ORDER BY display_order ASC, created_at DESC LIMIT 3";
$activities_result = mysqli_query($conn, $activities_query);

// Fetch partners (home_partners)
$partners = [];
$partners_query = "SELECT web_url, logo_url FROM home_partners ORDER BY id ASC";
$partners_result = mysqli_query($conn, $partners_query);
if ($partners_result && mysqli_num_rows($partners_result) > 0) {
    while ($row = mysqli_fetch_assoc($partners_result)) {
        // Normalize stored path like '../images/...' or '../../images/...' → 'images/...'
        $logo = (string)($row['logo_url'] ?? '');
        $logo = preg_replace('#^(?:\./|\../)+#', '', $logo);
        $logo = ltrim($logo, '/');
        $row['logo_url'] = $logo;
        $partners[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact Organization - Creating Positive Change</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <?php if (isset($hero_result) && mysqli_num_rows($hero_result) > 0): ?>
            <div class="hero-carousel-container">
                <div class="hero-carousel-wrapper">
                    <div class="hero-slides-container">
                        <?php
                        $hero_active = true;
                        mysqli_data_seek($hero_result, 0);
                        while ($hero = mysqli_fetch_assoc($hero_result)):
                        ?>
                            <div class="hero-carousel-slide <?php echo $hero_active ? 'active' : ''; ?>" style="background: linear-gradient(135deg, rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../<?php echo htmlspecialchars($hero['image_url']); ?>') center / cover no-repeat;">
                                <div class="hero-content">
                                    <h1 class="hero-title"><?php echo htmlspecialchars($hero['title']); ?></h1>
                                    <p class="hero-subtitle"><?php echo htmlspecialchars($hero['description']); ?></p>
                                    <a href="#programs" class="hero-cta">
                                        <i class="fas fa-arrow-down"></i>
                                        Explore Our Impact
                                    </a>
                                </div>
                            </div>
                        <?php
                        $hero_active = false;
                        endwhile;
                        ?>
                    </div>

                    <!-- Carousel Controls -->
                    <div class="hero-carousel-controls">
                        <button class="hero-carousel-btn prev" onclick="prevHeroSlide()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <div class="hero-carousel-indicators">
                            <?php
                            mysqli_data_seek($hero_result, 0);
                            $indicator_index = 0;
                            while ($hero = mysqli_fetch_assoc($hero_result)):
                            ?>
                                <span class="hero-indicator <?php echo $indicator_index === 0 ? 'active' : ''; ?>" onclick="goToHeroSlide(<?php echo $indicator_index; ?>)"></span>
                            <?php
                            $indicator_index++;
                            endwhile;
                            ?>
                        </div>
                        <button class="hero-carousel-btn next" onclick="nextHeroSlide()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <video autoplay muted loop playsinline class="hero-video">
                <source src="../images/1.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title">Building Stronger Communities</h1>
                    <p class="hero-subtitle">Empowering local communities across Rwanda, DRC Congo, and Uganda through sustainable development, conservation, and education programs.</p>
                    <a href="#programs" class="hero-cta">
                        <i class="fas fa-arrow-down"></i>
                        Explore Our Impact
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- Statistics Section (moved below hero) -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card scroll-animate">
                    <i class="fas fa-project-diagram stat-icon"></i>
                    <div class="stat-number"><?php echo number_format((int)($stats['total_programs'] ?? 0)); ?></div>
                    <div class="stat-label">Active Programs</div>
                </div>
                <div class="stat-card scroll-animate">
                    <i class="fas fa-users stat-icon"></i>
                    <div class="stat-number">221</div>
                    <div class="stat-label">Lives Impacted</div>
                </div>
                <div class="stat-card scroll-animate">
                    <i class="fas fa-globe-africa stat-icon"></i>
                    <div class="stat-number"><?php echo number_format((int)($stats['countries_served'] ?? 0)); ?></div>
                    <div class="stat-label">Countries Served</div>
                </div>
                <div class="stat-card scroll-animate">
                    <i class="fas fa-calendar-alt stat-icon"></i>
                    <div class="stat-number"><?php
echo date("Y") - 2017;
?></div>
                    <div class="stat-label">Years of Impact</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <h2 class="section-title">Who We Are</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>Virunga Ecotours is committed to creating positive change in the communities surrounding the Virunga Massif region.</p>
                    <p>Our community programs focus on sustainable development, conservation, education, healthcare, empowerment, and economic opportunities. Our goal is to empower local communities and build long-term partnerships that can create lasting positive impact while preserving the natural beauty and wildlife of the Virunga region.</p>
                </div>
                <div class="about-image">
                    <img src="uploads/impact/home_about.jpg" alt="Community Impact" ondragstart="return false;" oncontextmenu="return false;"/>
                </div>
            </div>
            
            <div class="mission-vision">
                <div class="mission-card scroll-animate">
                    <div class="card-title">
                        <i class="fas fa-bullseye"></i>
                        Our Mission
                    </div>
                    <div class="card-text">
                        To empower local communities through sustainable development programs that promote conservation, education, and economic opportunities.
                    </div>
                </div>
                <div class="vision-card scroll-animate">
                    <div class="card-title">
                        <i class="fas fa-eye"></i>
                        Our Vision
                    </div>
                    <div class="card-text">
                        A thriving Virunga region where communities and wildlife coexist, supported by sustainable tourism and conservation efforts.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Community-Based Tourism Section -->
    <section class="cbt-section">
        <div class="container">
            <div class="cbt-header">
                <h2 class="section-title">What is Community-Based Tourism?</h2>
                <div class="cbt-definition">
                    <p>Community-based tourism (CBT) ensures that local communities have full ownership and management of the tourism experience, so that the economic benefits of tourism stay within their community. CBT is designed to give travellers an authentic taste of a local community's heritage, their cultural practices and natural resources, and in this way offers an immersive and rich travel experience.</p>
                </div>
            </div>

            <div class="cbt-benefits">
                <h3 class="benefits-title">Benefits of Community-Based Tourism</h3>
                <div class="benefits-grid">
                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Economic</h4>
                            <p>CBT provides employment opportunities, revenue generation, local procurement of goods and services and limits the funds that leave the community. It also helps diversify the economic activities beyond farming, which reduces risk in years when climate change produces low or no yield.</p>
                        </div>
                    </div>

                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Shared Value</h4>
                            <p>Within a CBT, there is a distribution of benefits to all households. For example, though not all families will host homestays some may act as guides or provide meals. Even those not directly involved benefit from the agreed use of the community fund.</p>
                        </div>
                    </div>

                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Social</h4>
                            <p>CBT provides people with skills-training, opportunities for community infrastructure development (power, roads, sanitation, water) and health benefits (water and waste management education). It also promotes a more equitable community structure and the association with foreign travellers helps raise confidence and pride among the people.</p>
                        </div>
                    </div>

                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Environmental</h4>
                            <p>Conservation of the environment, awareness and wildlife protection are all great benefits of CBT.</p>
                        </div>
                    </div>

                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-female"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Female Empowerment</h4>
                            <p>One of the greatest outcomes is the empowerment of women in the community, as they are often largely responsible for the management and generation of the experience and therefore income.</p>
                        </div>
                    </div>

                    <div class="benefit-card scroll-animate">
                        <div class="benefit-icon">
                            <i class="fas fa-monument"></i>
                        </div>
                        <div class="benefit-content">
                            <h4>Preservation of Culture</h4>
                            <p>Often CBT prevents young people in communities leaving for larger cities, by providing employment opportunities for them locally.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
     <!-- Middle Section - Text Mask -->
    <section class="middle-section">
        <div class="mask-container">
            <div class="background-reveal"></div>
            <div class="mask-text">
                VIRUNGA COMMUNITY PROGRAMS
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section" id="programs">
        <div class="container">
            <h2 class="section-title">Our Impact Programs</h2>
            <div class="programs-intro">
                <p>Discover how we're making a difference across Rwanda, DRC, Congo, and Uganda</p>
            </div>
            
            <div class="programs-grid">
                <?php if (isset($featured_programs_result) && mysqli_num_rows($featured_programs_result) > 0): ?>
                    <?php while ($program = mysqli_fetch_assoc($featured_programs_result)): ?>
                        <div class="program-card scroll-animate">
                            <div class="program-image" style="background: linear-gradient(135deg, rgba(0,0,0,0.15), rgba(0,0,0,0.15)), url('assets/images/programs/<?php echo htmlspecialchars($program['image']); ?>') center / cover no-repeat;">
                                <!-- background image applied via inline style -->
                            </div>
                            <div class="program-content">
                                <div class="program-category">
                                    <?php echo htmlspecialchars($program['category']); ?>
                                </div>
                                <h3 class="program-title"><?php echo htmlspecialchars($program['title']); ?></h3>
                                <p class="program-description"><?php echo htmlspecialchars($program['short_description']); ?></p>
                                <div class="program-stats">
                                    <div class="program-stat">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format((int)$program['beneficiaries']); ?> beneficiaries</span>
                                    </div>
                                    <div class="program-stat">
                                        <i class="fas fa-calendar"></i>
                                        <span><?php echo date('M Y', strtotime($program['date_started'])); ?></span>
                                    </div>
                                </div>
                                <a href="program-detail.php?slug=<?php echo htmlspecialchars($program['slug']); ?>" class="learn-more-btn">
                                    Learn More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="programs-intro">No featured programs available at the moment.</div>
                <?php endif; ?>
            </div>
            
            <div class="text-center" style="margin-top: var(--spacing-xl);">
                <a href="./programs.php" class="cta-button">View All Programs</a>
            </div>
        </div>
    </section>

    <!-- Parallax Section -->
    <section class="parallax-section">
        <div class="parallax-content">
            <div class="container">
                <h2 class="parallax-title">Enriching Communities Through Diverse Activities</h2>
                <p class="parallax-subtitle">Building stronger communities through cultural exchange, education, and sustainable development initiatives that create lasting positive impact.</p>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="activities-section">
        <div class="container">
            <h2 class="section-title">Explore Our Activities</h2>
            <p class="programs-intro">Immerse yourself in authentic experiences that make a difference</p>
            
            <div class="activities-grid">
                <?php if (isset($activities_result) && mysqli_num_rows($activities_result) > 0): ?>
                    <?php while ($activity = mysqli_fetch_assoc($activities_result)): ?>
                        <?php
                            $summary = substr(strip_tags($activity['content']), 0, 150);
                            $hasMore = strlen(strip_tags($activity['content'])) > 150;
                        ?>
                        <div class="activity-card scroll-animate" style="background: linear-gradient(135deg, rgba(0,0,0,0.25), rgba(0,0,0,0.25)), url('uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>') center / cover no-repeat;">
                            <div class="activity-content">
                                <div class="activity-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo htmlspecialchars($activity['title']); ?>
                                </div>
                                <p class="activity-description"><?php echo htmlspecialchars($summary) . ($hasMore ? '...' : ''); ?></p>
                                <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="learn-more-btn">Learn More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="programs-intro">No activities available at the moment. Please check back soon!</div>
                <?php endif; ?>
            </div>
            
            <div class="text-center" style="margin-top: var(--spacing-xl);">
                <a href="./activity.php" class="cta-button">View All Activities</a>
            </div>
        </div>
    </section>
    <section class="imigongo-section">
        <div>
            <div class="imigongo-pattern"></div>
        </div>
    </section>
    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <h2 class="section-title">Voices from Our Communities</h2>
            <p class="programs-intro">Hear from the people whose lives have been touched by our programs</p>
            
            <div class="testimonials-grid">
                <?php if (isset($testimonials_result) && mysqli_num_rows($testimonials_result) > 0): ?>
                    <?php while ($testimonial = mysqli_fetch_assoc($testimonials_result)): ?>
                        <div class="testimonial-card scroll-animate">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <p class="testimonial-text">"<?php echo htmlspecialchars($testimonial['message']); ?>"</p>
                            <div class="testimonial-author">
                                <div class="author-avatar">
                                    <?php 
                                        $initials = '';
                                        $parts = explode(' ', trim($testimonial['name'] ?? ''));
                                        foreach ($parts as $p) { if ($p !== '') { $initials .= strtoupper($p[0]); } }
                                        echo htmlspecialchars(substr($initials, 0, 2));
                                    ?>
                                </div>
                                <div class="author-info">
                                    <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    <p><?php echo htmlspecialchars($testimonial['role']); ?></p>
                                </div>
                            </div>
                            <div class="star-rating">
                                <?php $rating = (int)($testimonial['rating'] ?? 0); for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" style="opacity: <?php echo $i <= $rating ? '1' : '0.3'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="partners-section">
        <div class="partners-slider">
          <button class="slider-control slider-prev">&#10094;</button>
          <div class="slider-container" id="sliderContainer">
            <?php foreach ($partners as $partner): ?>
              <div class="slider-item">
                <?php if (!empty($partner['web_url'])): ?>
                  <a href="<?php echo htmlspecialchars($partner['web_url']); ?>" target="_blank" rel="noopener noreferrer">
                <?php endif; ?>
                  <img src="../admin/<?php echo htmlspecialchars($partner['logo_url']); ?>" alt="Partner Logo" ondragstart="return false;" oncontextmenu="return false;"/>
                <?php if (!empty($partner['web_url'])): ?>
                  </a>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="slider-control slider-next">&#10095;</button>
        </div>
    </section>
     
    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/community.js"></script>
    <script src="assets/js/scroll.js"></script>
    <script src="assets/js/community-header-footer.js"></script>

    <!-- Community Hero Carousel Script -->
    <script>
        let currentHeroSlide = 0;
        const heroSlides = document.querySelectorAll('.hero-carousel-slide');
        const heroIndicators = document.querySelectorAll('.hero-indicator');

        function showHeroSlide(index) {
            if (heroSlides.length === 0) return;

            // Remove active class from all slides and indicators
            heroSlides.forEach(slide => slide.classList.remove('active'));
            heroIndicators.forEach(indicator => indicator.classList.remove('active'));

            // Calculate the correct index
            currentHeroSlide = (index + heroSlides.length) % heroSlides.length;

            // Add active class to current slide and indicator
            heroSlides[currentHeroSlide].classList.add('active');
            if (heroIndicators[currentHeroSlide]) {
                heroIndicators[currentHeroSlide].classList.add('active');
            }
        }

        function nextHeroSlide() {
            showHeroSlide(currentHeroSlide + 1);
        }

        function prevHeroSlide() {
            showHeroSlide(currentHeroSlide - 1);
        }

        function goToHeroSlide(index) {
            showHeroSlide(index);
        }

        // Auto-advance carousel every 5 seconds
        if (heroSlides.length > 1) {
            setInterval(nextHeroSlide, 5000);
        }
    </script>
</body>
</html>