<?php
require_once '../admin/config/connection.php';

// Fetch team members
$team_query = "SELECT * FROM community_team WHERE status = 'active' ORDER BY order_position ASC, created_at ASC";
$team_result = mysqli_query($conn, $team_query);

// Fetch some statistics
$stats_query = "SELECT 
    COUNT(*) as total_programs,
    SUM(beneficiaries) as total_beneficiaries,
    COUNT(DISTINCT country) as countries_served,
    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_programs
    FROM community_programs WHERE status IN ('active', 'completed')";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Virunga Ecotours Community</title>
    <meta name="description" content="Learn about Virunga Ecotours' community programs, our mission, vision, and the dedicated team working to empower communities across Rwanda, DRC Congo, and Uganda.">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="assets/css/intro.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    <style>
        .member-info {
            padding: 2rem 1rem;
        }

        .team-member {
            border-radius: var(--border-radius-md);
        }

        .approach-item {
            border-radius: var(--border-radius-md);
            box-shadow: 0 5px 16px rgba(0, 0, 0, 0.08);
        }

        .mission-card,
        .vision-card,
        .values-card {
            background: white;
            border-radius: var(--border-radius-md);
            padding: 2rem 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(42, 72, 88, 0.1);
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="../images/hero/women4.jpg" alt="About Virunga Ecotours Community" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">About Us</span>
                </nav>
                <h1>About Our Community Programs</h1>
                <p>Discover the story behind our mission to empower communities and preserve the natural heritage of the Virunga Massif region.</p>
            </div>
        </div>
    </section>

    <!-- Intro Text Section -->
    <section class="intro-text-section">
        <div class="container">
            <p>The Virunga Community Programs was born from a simple idea: tourism should not only showcase the beauty of the Virunga Massif but also improve the lives of the people who call it home. Supported by Virunga Ecotours, the initiative ensures that every journey contributes to meaningful change, transforming challenges into opportunities and turning travel into lasting impact.</p>
            <p>Across the region, families face real struggles: poverty that limits livelihoods, youth without opportunities, schools lacking resources, and communities without adequate healthcare or clean water. At the same time, women continue to confront barriers, cultural traditions risk being forgotten, and living alongside wildlife often leads to daily conflict. These challenges are significant, but they are not without solutions. Through skills training, education support, health and water projects, women’s empowerment, cultural preservation, and conservation awareness, the initiative delivers programs that are practical, people-centered, and designed for lasting results.</p>
            <p>What makes this work different is its foundation: it is fueled by the spirit of tourism and strengthened by those who believe in meaningful impact. Each visit with Virunga Ecotours creates a ripple effect that reaches classrooms, households, and villages. Every partnership, every shared skill, every contribution helps build a future where communities thrive alongside nature.</p>
            <p>This is more than a program, it is an open door for anyone who wants to see their time, resources, or knowledge create visible change. By walking alongside this vision, you become part of a collective effort to lift families out of poverty, give children a chance at education, empower women, protect culture, and safeguard the Virunga landscape for generations to come. Together, we can turn today’s struggles into tomorrow’s hope and build a stronger, more vibrant Virunga.</p>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mission-vision-section">
        <div class="container">
            <div class="mission-vision-grid">
                <div class="mission-card">
                    <div class="card-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Our Mission</h3>
                    <p>To empower local communities through sustainable development programs that promote conservation, education, healthcare, and economic opportunities while preserving the unique biodiversity of the Virunga Massif region.</p>
                </div>
                
                <div class="vision-card">
                    <div class="card-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Our Vision</h3>
                    <p>A thriving Virunga region where communities and wildlife coexist in harmony, supported by sustainable tourism, conservation efforts, and community-driven development initiatives.</p>
                </div>
                
                <div class="values-card">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Our Values</h3>
                    <ul>
                        <li><i class="fas fa-check"></i> Community Empowerment</li>
                        <li><i class="fas fa-check"></i> Environmental Conservation</li>
                        <li><i class="fas fa-check"></i> Sustainable Development</li>
                        <li><i class="fas fa-check"></i> Cultural Respect</li>
                        <li><i class="fas fa-check"></i> Transparency & Accountability</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="story-section">
        <div class="container">
            <div class="story-content">
                <div class="story-text">
                    <h2>Our Journey</h2>
                    <div class="story-timeline">
                        <div class="timeline-item">
                            <div class="timeline-year">2017</div>
                            <div class="timeline-content">
                            <h4>Vision Born</h4>
                            <p>In 2017, the seeds of Virunga Ecotours were planted with a bold vision to create a bridge between tourism, conservation, and community empowerment in the Virunga Massif.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-year">2019</div>
                            <div class="timeline-content">
                            <h4>Building Partnerships & Skills</h4>
                            <p> By 2019, the company had evolved into a professional operator engaging directly with local communities and authorities across Rwanda, Uganda, and DR Congo.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-year">2021</div>
                            <div class="timeline-content">
                            <h4>Expanding Community Impact</h4>
                            <p>In 2021, our vision grew beyond tourism. We launched new community programs tackling menstrual hygiene for girls, basic healthcare access, and women’s leadership in conservation.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-year">2023</div>
                            <div class="timeline-content">
                            <h4>Growing Impact</h4>
                            <p>Today, our programs have reached over <?php echo number_format($stats['total_beneficiaries'] ?? 0); ?> beneficiaries across <?php echo $stats['countries_served'] ?? 3; ?> countries, with <?php echo $stats['completed_programs'] ?? 0; ?> successfully completed projects.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="story-image">
                    <img src="../images/hero/how.jpg" alt="Our community work journey" loading="lazy">
                    <div class="story-stats">
                        <div class="stat">
                            <div class="stat-number"><?php echo $stats['total_programs'] ?? 0; ?></div>
                            <div class="stat-label">Active Programs</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number"><?php echo number_format($stats['total_beneficiaries'] ?? 0); ?></div>
                            <div class="stat-label">Lives Impacted</div>
                        </div>
                        <div class="stat">
                            <div class="stat-number"><?php echo $stats['countries_served'] ?? 0; ?></div>
                            <div class="stat-label">Countries</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Approach Section -->
    <section class="approach-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Approach</h2>
                <p>We believe in community-driven development that respects local culture and promotes long-term sustainability.</p>
            </div>
            
            <div class="approach-grid">
                <div class="approach-item">
                    <div class="approach-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Community-Centered</h3>
                    <p>All our programs are developed in partnership with local communities, ensuring they address real needs and are culturally appropriate.</p>
                </div>
                
                <div class="approach-item">
                    <div class="approach-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Environmentally Sustainable</h3>
                    <p>Every initiative considers environmental impact and promotes conservation practices that protect the Virunga ecosystem.</p>
                </div>
                
                <div class="approach-item">
                    <div class="approach-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Collaborative Partnerships</h3>
                    <p>We work with local organizations, government agencies, and international partners to maximize our collective impact.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2>Meet Our Team</h2>
                <p>Dedicated professionals working tirelessly to create positive change in the Virunga region.</p>
            </div>
            
            <div class="team-grid">
                <?php if (mysqli_num_rows($team_result) > 0): ?>
                    <?php while ($member = mysqli_fetch_assoc($team_result)): ?>
                        <!--<div class="team-member">-->
                        <!--    <div class="member-image">-->
                        <!--        <img src="assets/images/team/<?php echo htmlspecialchars($member['image'] ?? 'default-avatar.jpg'); ?>" -->
                        <!--             alt="<?php echo htmlspecialchars($member['name']); ?>" loading="lazy">-->
                        <!--        <div class="member-overlay">-->
                        <!--            <div class="social-links">-->
                        <!--                <?php if ($member['facebook']): ?>-->
                        <!--                    <a href="<?php echo htmlspecialchars($member['facebook']); ?>" target="_blank" rel="noopener">-->
                        <!--                        <i class="fab fa-facebook-f"></i>-->
                        <!--                    </a>-->
                        <!--                <?php endif; ?>-->
                        <!--                <?php if ($member['twitter']): ?>-->
                        <!--                    <a href="<?php echo htmlspecialchars($member['twitter']); ?>" target="_blank" rel="noopener">-->
                        <!--                        <i class="fab fa-twitter"></i>-->
                        <!--                    </a>-->
                        <!--                <?php endif; ?>-->
                        <!--                <?php if ($member['linkedin']): ?>-->
                        <!--                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>" target="_blank" rel="noopener">-->
                        <!--                        <i class="fab fa-linkedin-in"></i>-->
                        <!--                    </a>-->
                        <!--                <?php endif; ?>-->
                        <!--                <?php if ($member['instagram']): ?>-->
                        <!--                    <a href="<?php echo htmlspecialchars($member['instagram']); ?>" target="_blank" rel="noopener">-->
                        <!--                        <i class="fab fa-instagram"></i>-->
                        <!--                    </a>-->
                        <!--                <?php endif; ?>-->
                        <!--                <?php if ($member['email']): ?>-->
                        <!--                    <a href="mailto:<?php echo htmlspecialchars($member['email']); ?>">-->
                        <!--                        <i class="fas fa-envelope"></i>-->
                        <!--                    </a>-->
                        <!--                <?php endif; ?>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--    <div class="member-info">-->
                        <!--        <h3><?php echo htmlspecialchars($member['name']); ?></h3>-->
                        <!--        <p class="member-title"><?php echo htmlspecialchars($member['title']); ?></p>-->
                        <!--        <?php if ($member['bio']): ?>-->
                        <!--            <p class="member-bio"><?php echo htmlspecialchars($member['bio']); ?></p>-->
                        <!--        <?php endif; ?>-->
                        <!--    </div>-->
                        <!--</div>-->
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-team-members">
                        <i class="fas fa-users"></i>
                        <p>Team information will be available soon.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Impact Map Section -->
    <section class="impact-map-section">
        <div class="container">
            <div class="section-header">
                <h2>Our Impact Across the Region</h2>
                <p>Explore our comprehensive community programs across the Virunga Massif region spanning Rwanda, DRC Congo, and Uganda.</p>
            </div>

            <!-- Regional Statistics -->
            <div class="regional-stats">
                <div class="stats-grid">
                    <div class="stat-card rwanda">
                        <div class="stat-flag">🇷🇼</div>
                        <h4>Rwanda</h4>
                        <div class="stat-number"><?php echo $stats['total_programs'] ? ceil($stats['total_programs'] * 0.4) : 6; ?></div>
                        <div class="stat-label">Active Programs</div>
                        <p>Focus: Conservation & Education</p>
                    </div>
                    <div class="stat-card uganda">
                        <div class="stat-flag">🇺🇬</div>
                        <h4>Uganda</h4>
                        <div class="stat-number"><?php echo $stats['total_programs'] ? ceil($stats['total_programs'] * 0.3) : 4; ?></div>
                        <div class="stat-label">Active Programs</div>
                        <p>Focus: Women & Youth Empowerment</p>
                    </div>
                    <div class="stat-card drc">
                        <div class="stat-flag">🇨🇩</div>
                        <h4>DRC Congo</h4>
                        <div class="stat-number"><?php echo $stats['total_programs'] ? ceil($stats['total_programs'] * 0.3) : 5; ?></div>
                        <div class="stat-label">Active Programs</div>
                        <p>Focus: Healthcare & Agriculture</p>
                    </div>
                    <div class="stat-card total">
                        <div class="stat-flag">🌍</div>
                        <h4>Total Impact</h4>
                        <div class="stat-number"><?php echo number_format($stats['total_beneficiaries'] ?? 2500); ?></div>
                        <div class="stat-label">Lives Impacted</div>
                        <p>Across 3 Countries</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Join Our Mission</h2>
                <p>Be part of the positive change in the Virunga region. Whether through volunteering, partnerships, or donations, your support makes a real difference in people's lives.</p>
                <div class="cta-buttons">
                   
                    <a href="programs.php" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        View Our Programs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/about.js"></script>
    <!-- <script src="assets/js/community.js"></script> -->
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
