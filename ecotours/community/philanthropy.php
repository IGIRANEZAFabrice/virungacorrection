<?php
require_once '../admin/config/connection.php';

$page_title = "Philanthropy & Community Impact - Virunga Ecotours";
$page_description = "Discover how Virunga Ecotours creates lasting positive impact through strategic philanthropy, community partnerships, and sustainable development initiatives in the Virunga Massif region.";

// Get hero content
$hero_query = "SELECT * FROM philanthropy_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

// Get approach cards
$approach_query = "SELECT * FROM philanthropy_approach ORDER BY display_order ASC";
$approach_result = mysqli_query($conn, $approach_query);

// Get regenerative cards
$regenerative_query = "SELECT * FROM philanthropy_regenerative ORDER BY display_order ASC";
$regenerative_result = mysqli_query($conn, $regenerative_query);

// Get focus areas
$focus_query = "SELECT * FROM philanthropy_focus_areas ORDER BY display_order ASC";
$focus_result = mysqli_query($conn, $focus_query);

// Get engagement activities
$engagement_query = "SELECT * FROM philanthropy_engagement ORDER BY display_order ASC";
$engagement_result = mysqli_query($conn, $engagement_query);

// Get stories
$stories_query = "SELECT * FROM philanthropy_stories ORDER BY display_order ASC";
$stories_result = mysqli_query($conn, $stories_query);

// Get partnerships
$partnerships_query = "SELECT * FROM philanthropy_partnerships ORDER BY display_order ASC";
$partnerships_result = mysqli_query($conn, $partnerships_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/philanthropy.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="philanthropy-page">
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <?php if (!empty($hero['hero_image'])): ?>
            <img src="<?php echo htmlspecialchars($hero['hero_image']); ?>" alt="Hero" class="hero-image">
        <?php else: ?>
            <div class="hero-image" style="background: linear-gradient(135deg, #2a4858 0%, #1a3a48 100%); height: 500px;"></div>
        <?php endif; ?>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title"><?php echo htmlspecialchars($hero['hero_title'] ?? 'Philanthropy & Community Impact'); ?></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($hero['hero_subtitle'] ?? 'Creating Lasting Change Through Strategic Partnerships'); ?></p>
            <p class="hero-description"><?php echo htmlspecialchars($hero['hero_description'] ?? ''); ?></p>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number"><?php echo htmlspecialchars($hero['stat1_number'] ?? '500+'); ?></span>
                    <span class="stat-label"><?php echo htmlspecialchars($hero['stat1_label'] ?? 'Students Supported'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo htmlspecialchars($hero['stat2_number'] ?? '15'); ?></span>
                    <span class="stat-label"><?php echo htmlspecialchars($hero['stat2_label'] ?? 'Community Projects'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo htmlspecialchars($hero['stat3_number'] ?? '1000+'); ?></span>
                    <span class="stat-label"><?php echo htmlspecialchars($hero['stat3_label'] ?? 'Lives Impacted'); ?></span>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Approach Section -->
    <section class="approach-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Our Community-Based Tourism Approach</h2>
                <p class="section-description">Community-based tourism is led, managed, and owned by local communities, ensuring economic, social, and cultural benefits remain local while preserving heritage and nature. We implement CBT through partnerships with homestays, Red Rocks Initiatives, indigenous guides, and artisan cooperatives.</p>
            </div>

            <div class="approach-grid">
                <?php while ($card = mysqli_fetch_assoc($approach_result)): ?>
                    <div class="approach-card">
                        <div class="approach-icon">
                            <i class="<?php echo htmlspecialchars($card['card_icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($card['card_title']); ?></h3>
                        <p><?php echo htmlspecialchars($card['card_description']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Regenerative Tourism Principles -->
    <section class="regenerative-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Regenerative Tourism Principles</h2>
                <p class="section-description">While sustainable tourism minimizes harm, regenerative tourism actively restores ecosystems, strengthens cultural integrity, and improves livelihoods. We measure impact through social assessments, employment metrics, visitor feedback, and ecological monitoring.</p>
            </div>

            <div class="regenerative-grid">
                <?php while ($card = mysqli_fetch_assoc($regenerative_result)): ?>
                    <div class="regenerative-card">
                        <div class="regenerative-icon">
                            <i class="<?php echo htmlspecialchars($card['card_icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($card['card_title']); ?></h3>
                        <p><?php echo htmlspecialchars($card['card_description']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Key Focus Areas -->
    <section class="focus-areas-section">
        <div class="container">
            <h2 class="section-title">Our Key Focus Areas</h2>

            <?php
            $reverse = false;
            while ($focus = mysqli_fetch_assoc($focus_result)):
                $reverse = !$reverse;
            ?>
            <div class="focus-area">
                <div class="focus-content <?php echo $reverse ? 'reverse' : ''; ?>">
                    <div class="focus-text">
                        <h3><i class="<?php echo htmlspecialchars($focus['focus_icon']); ?>"></i> <?php echo htmlspecialchars($focus['focus_title']); ?></h3>
                        <p><?php echo htmlspecialchars($focus['focus_description']); ?></p>
                        <ul class="impact-list">
                            <?php
                            $items_query = "SELECT * FROM philanthropy_focus_items WHERE focus_id = '" . mysqli_real_escape_string($conn, $focus['focus_id']) . "' ORDER BY display_order ASC";
                            $items_result = mysqli_query($conn, $items_query);
                            while ($item = mysqli_fetch_assoc($items_result)):
                            ?>
                            <li><strong><?php echo htmlspecialchars($item['item_title']); ?>:</strong> <?php echo htmlspecialchars($item['item_description']); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <div class="focus-image">
                        <?php if (!empty($focus['focus_image'])): ?>
                            <img src="<?php echo htmlspecialchars($focus['focus_image']); ?>" alt="<?php echo htmlspecialchars($focus['focus_title']); ?>" />
                        <?php else: ?>
                            <div class="image-placeholder">No image available</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Visitor Engagement & Experience -->
    <section class="visitor-engagement-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Visitor Engagement & Transformative Experiences</h2>
                <p class="section-description">We create meaningful travel experiences that benefit both visitors and communities through authentic cultural exchange and hands-on participation in local life.</p>
            </div>

            <div class="engagement-grid">
                <?php while ($activity = mysqli_fetch_assoc($engagement_result)): ?>
                    <div class="engagement-item">
                        <div class="engagement-content">
                            <h3><i class="<?php echo htmlspecialchars($activity['engagement_icon']); ?>"></i> <?php echo htmlspecialchars($activity['engagement_title']); ?></h3>
                            <p><?php echo htmlspecialchars($activity['engagement_description']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="transformative-impact">
                <div class="impact-text">
                    <h3>Creating Transformative Travel</h3>
                    <p>Through immersive, respectful experiences, visitors develop environmental awareness and cultural appreciation that persist long-term. Regenerative tourism influences visitor behavior beyond the trip, fostering environmental responsibility, cultural empathy, and conscious consumption in daily life.</p>
                    <div class="impact-stats">
                        <div class="impact-stat">
                            <span class="stat-number">85%</span>
                            <span class="stat-label">Visitors report lasting behavior change</span>
                        </div>
                        <div class="impact-stat">
                            <span class="stat-number">92%</span>
                            <span class="stat-label">Would recommend to others</span>
                        </div>
                        <div class="impact-stat">
                            <span class="stat-number">78%</span>
                            <span class="stat-label">Continue supporting communities post-visit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stories Section -->
    <section class="impact-stories-section">
        <div class="container">
            <h2 class="section-title">Stories of Impact</h2>
            <p class="section-description">Real stories from the communities we serve, showcasing the transformative power of strategic philanthropy and community partnership.</p>
            
            <div class="stories-grid">
                <?php while ($story = mysqli_fetch_assoc($stories_result)): ?>
                    <div class="story-card">
                        <div class="story-image">
                            <img src="<?php echo htmlspecialchars($story['story_image']); ?>" alt="<?php echo htmlspecialchars($story['story_title']); ?>" />
                        </div>
                        <div class="story-content">
                            <h3><?php echo htmlspecialchars($story['story_title']); ?></h3>
                            <p class="story-excerpt"><?php echo htmlspecialchars($story['story_excerpt']); ?></p>
                            <a href="<?php echo htmlspecialchars($story['story_link']); ?>" class="read-more">Read Full Story <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Partnership Opportunities Section -->
    <section class="partnership-section">
        <div class="container">
            <h2 class="section-title">Partnership Opportunities</h2>
            <p class="section-description">Join us in creating lasting positive change. We offer various ways for individuals, organizations, and businesses to partner with us in our philanthropic mission.</p>

            <div class="partnership-grid">
                <?php while ($partnership = mysqli_fetch_assoc($partnerships_result)): ?>
                    <div class="partnership-card">
                        <div class="partnership-icon">
                            <i class="<?php echo htmlspecialchars($partnership['partnership_icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($partnership['partnership_title']); ?></h3>
                        <p><?php echo htmlspecialchars($partnership['partnership_description']); ?></p>
                        <a href="<?php echo htmlspecialchars($partnership['partnership_link']); ?>" class="partnership-btn">Learn More</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Transparency & Accountability Section -->
    <section class="transparency-section">
        <div class="container">
            <div class="transparency-content">
                <div class="transparency-text">
                    <h2 class="section-title">Measuring & Communicating Impact</h2>
                    <p>We measure social impact through surveys, participatory assessments, employment data, and community feedback. Ecological regeneration metrics include tree growth, biodiversity indicators, and reduced environmental degradation tracked alongside social indicators.</p>

                    <div class="transparency-features">
                        <div class="feature-item">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h4>Social Impact Assessment</h4>
                                <p>Surveys, participatory assessments, employment data, and community feedback to measure social outcomes</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <i class="fas fa-leaf"></i>
                            <div>
                                <h4>Ecological Monitoring</h4>
                                <p>Tree growth, biodiversity indicators, and reduced environmental degradation tracked alongside social indicators</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <i class="fas fa-comments"></i>
                            <div>
                                <h4>Visitor Feedback Integration</h4>
                                <p>Surveys, interviews, and community discussions inform experience improvement and decision-making</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <i class="fas fa-file-contract"></i>
                            <div>
                                <h4>Transparency Commitment</h4>
                                <p>Annual reports, third-party audits, community consultations, and clear communication of outcomes</p>
                            </div>
                        </div>
                    </div>

                    <div class="transparency-actions">
                        <a href="#reports" class="cta-button primary">View Annual Reports</a>
                        <a href="#impact" class="cta-button secondary">See Impact Data</a>
                    </div>
                </div>

                <div class="transparency-stats">
                    <div class="stat-card">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Program Funding</div>
                        <div class="stat-description">Of donations go directly to programs</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Transparency</div>
                        <div class="stat-description">All financial records are public</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-number">4.8/5</div>
                        <div class="stat-label">Community Rating</div>
                        <div class="stat-description">Average satisfaction score</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Get Involved Section -->
    <section class="get-involved-section">
        <div class="container">
            <div class="get-involved-content">
                <h2 class="section-title">Get Involved Today</h2>
                <p class="section-description">Ready to make a difference? There are many ways to support our philanthropic mission and create lasting positive change in the communities we serve.</p>

                <div class="involvement-options">
                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3>Make a Donation</h3>
                        <p>Support our programs with a one-time gift or monthly contribution</p>
                        <a href="#donate" class="option-btn">Donate Now</a>
                    </div>

                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <h3>Volunteer Travel</h3>
                        <p>Join our volunteer travel programs and contribute your skills directly</p>
                        <a href="#volunteer" class="option-btn">Learn More</a>
                    </div>

                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h3>Spread the Word</h3>
                        <p>Share our mission with your network and help us reach more supporters</p>
                        <a href="#share" class="option-btn">Share Now</a>
                    </div>

                    <div class="option-card">
                        <div class="option-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Corporate Partnership</h3>
                        <p>Partner with us for meaningful corporate social responsibility initiatives</p>
                        <a href="#corporate" class="option-btn">Partner</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-philanthropy-section">
        <div class="container">
            <div class="contact-content">
                <h2>Ready to Create Impact Together?</h2>
                <p>Contact our philanthropy team to discuss partnership opportunities, volunteer programs, or learn more about our community impact initiatives.</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email Us</h4>
                            <p>info@virungajourneys.com</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-phone"></i>
                        <div>
                            <h4>Call Us</h4>
                            <p>+250 784 513 435</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <div>
                            <h4>Visit Us</h4>
                            <p>Musanze, Rwanda</p>
                        </div>
                    </div>
                </div>
                <a href="../pages/contact.php" class="contact-btn">Get In Touch</a>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/community.js"></script>
    <script src="assets/js/community-header-footer.js"></script>

    <script>
        // Philanthropy page specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stats on scroll
            const observerOptions = {
                threshold: 0.5,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateStats(entry.target);
                    }
                });
            }, observerOptions);

            // Observe stat sections
            document.querySelectorAll('.hero-stats, .transparency-stats').forEach(section => {
                observer.observe(section);
            });

            // Animate cards on scroll
            const cardObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, { threshold: 0.1 });

            // Observe all cards
            document.querySelectorAll('.approach-card, .story-card, .partnership-card, .option-card').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = `all 0.6s ease ${index * 0.1}s`;
                cardObserver.observe(card);
            });

            // Stats animation function
            function animateStats(container) {
                const statNumbers = container.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const finalValue = stat.textContent;
                    const isPercentage = finalValue.includes('%');
                    const isRating = finalValue.includes('/');
                    const numericValue = parseFloat(finalValue.replace(/[^\d.]/g, ''));

                    let currentValue = 0;
                    const increment = numericValue / 50;

                    const timer = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= numericValue) {
                            currentValue = numericValue;
                            clearInterval(timer);
                        }

                        if (isPercentage) {
                            stat.textContent = Math.floor(currentValue) + '%';
                        } else if (isRating) {
                            stat.textContent = currentValue.toFixed(1) + '/5';
                        } else {
                            stat.textContent = Math.floor(currentValue) + (finalValue.includes('+') ? '+' : '');
                        }
                    }, 20);
                });
            }
        });
    </script>
</body>
</html>
