<?php
session_start();
require_once '../admin/config/connection.php';

$page_title = "Voluntourism & Community Engagement - Virunga Ecotours";
$page_description = "Join our volunteer travel programs that combine meaningful community service with authentic cultural experiences. Contribute your skills while experiencing transformative community-based tourism in the Virunga Massif.";

// Fetch hero and intro data
$hero_query = "SELECT * FROM voluntourism_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

// Fetch highlights
$highlights_query = "SELECT * FROM voluntourism_highlights ORDER BY display_order";
$highlights_result = mysqli_query($conn, $highlights_query);

// Fetch activities
$activities_query = "SELECT * FROM voluntourism_activities ORDER BY display_order";
$activities_result = mysqli_query($conn, $activities_query);

// Fetch programs
$programs_query = "SELECT * FROM voluntourism_programs ORDER BY display_order";
$programs_result = mysqli_query($conn, $programs_query);

// Fetch table rows
$table_rows_query = "SELECT * FROM voluntourism_table_rows ORDER BY display_order";
$table_rows_result = mysqli_query($conn, $table_rows_query);

// Fetch FAQ
$faq_query = "SELECT * FROM voluntourism_faq ORDER BY display_order";
$faq_result = mysqli_query($conn, $faq_query);

// Fetch How It Works section
$how_it_works_query = "SELECT * FROM voluntourism_how_it_works LIMIT 1";
$how_it_works_result = mysqli_query($conn, $how_it_works_query);
$how_it_works = mysqli_fetch_assoc($how_it_works_result);

// Fetch How It Works features
$how_it_works_features_query = "SELECT * FROM voluntourism_how_it_works_features ORDER BY display_order";
$how_it_works_features_result = mysqli_query($conn, $how_it_works_features_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/logos/icon.png">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/voluntourism.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="voluntourism-page">
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <img src="<?php echo htmlspecialchars($hero['hero_image'] ?? '../images/voluntourism/hero.jpg'); ?>" alt="Volunteers working with community members" loading="lazy">
        </div>

        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo htmlspecialchars($hero['hero_title'] ?? 'Voluntourism & Community Engagement'); ?></h1>
                <p class="hero-subtitle"><?php echo htmlspecialchars($hero['hero_subtitle'] ?? 'Transformative Travel Through Meaningful Service'); ?></p>
                <p class="hero-description"><?php echo htmlspecialchars($hero['hero_description'] ?? 'Experience community-based tourism while contributing your skills to meaningful projects.'); ?></p>

            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="introduction-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2 class="section-title"><?php echo htmlspecialchars($hero['intro_title'] ?? 'Voluntourism at Virunga Ecotours'); ?></h2>
                    <p class="intro-description"><?php echo htmlspecialchars($hero['intro_description'] ?? 'Voluntourism at Virunga Ecotours is a distinctive form of community-based tourism...'); ?></p>

                    <div class="intro-image">
                        <img src="<?php echo htmlspecialchars($hero['intro_image'] ?? '../images/voluntourism/HO2A3457.jpg'); ?>" alt="Volunteers working with local community members" loading="lazy">

                    </div>
                </div>

                <div class="intro-highlights">
                    <?php while ($highlight = mysqli_fetch_assoc($highlights_result)): ?>
                        <div class="highlight-item">
                            <div class="highlight-icon">
                                <i class="<?php echo htmlspecialchars($highlight['highlight_icon']); ?>"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($highlight['highlight_title']); ?></h3>
                            <p><?php echo htmlspecialchars($highlight['highlight_description']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works-section">
        <div class="container">
            <h2 class="section-title"><?php echo htmlspecialchars($how_it_works['section_title'] ?? 'How It Works'); ?></h2>
            <div class="how-it-works-content">
                <div class="how-it-works-text">
                    <p><?php echo htmlspecialchars($how_it_works['section_description']); ?></p>

                    <div class="process-image">
                        <img src="<?php echo htmlspecialchars($how_it_works['process_image']); ?>" alt="Voluntourism process and planning" loading="lazy">
                        <div class="image-overlay">
                            <div class="overlay-text">
                                <h4><?php echo htmlspecialchars($how_it_works['overlay_title']); ?></h4>
                                <p><?php echo htmlspecialchars($how_it_works['overlay_description']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="how-it-works-features">
                    <?php
                    // Reset the how it works features result pointer
                    mysqli_data_seek($how_it_works_features_result, 0);
                    while ($feature = mysqli_fetch_assoc($how_it_works_features_result)):
                    ?>
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="<?php echo htmlspecialchars($feature['feature_icon']); ?>"></i>
                            </div>
                            <h3><?php echo htmlspecialchars($feature['feature_title']); ?></h3>
                            <p><?php echo htmlspecialchars($feature['feature_description']); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Examples of Activities Section -->
    <section class="activities-section">
        <div class="container">
            <h2 class="section-title">Examples of Voluntourism Activities</h2>
            <p class="section-description">To enrich both visitors and communities, Virunga Ecotours integrates a variety of community-based educational, cultural, and environmental initiatives:</p>

            <div class="activities-grid">
                <?php
                // Reset the activities result pointer
                mysqli_data_seek($activities_result, 0);
                while ($activity = mysqli_fetch_assoc($activities_result)):
                ?>
                    <div class="activity-card">
                        <div class="activity-image">
                            <img src="<?php echo htmlspecialchars($activity['activity_image']); ?>" alt="<?php echo htmlspecialchars($activity['activity_title']); ?>" loading="lazy">
                            <div class="activity-icon">
                                <i class="<?php echo htmlspecialchars($activity['activity_icon']); ?>"></i>
                            </div>
                        </div>
                        <div class="activity-content">
                            <h3><?php echo htmlspecialchars($activity['activity_title']); ?></h3>
                            <p><?php echo htmlspecialchars($activity['activity_description']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Value of Experience Section -->
    <section class="value-section">
        <div class="container">
            <h2 class="section-title">Value of the Experience</h2>
            <p class="section-description">Voluntourism provides <em>dual benefits</em> that create meaningful impact for both visitors and communities:</p>

            <div class="value-grid">
                <div class="value-card visitors">
                    <div class="value-header">
                        <div class="value-icon">
                            <i class="fas fa-user-friends"></i>
                        </div>
                        <h3>For Visitors</h3>
                    </div>
                    <div class="value-content">
                        <p>It adds purpose, meaning, and cultural understanding to their travels, transforming leisure into a transformative journey.</p>
                        <ul class="value-benefits">
                            <li>Purpose-driven travel experiences</li>
                            <li>Deep cultural understanding</li>
                            <li>Meaningful personal connections</li>
                            <li>Transformative journey of growth</li>
                        </ul>
                    </div>
                </div>

                <div class="value-card communities">
                    <div class="value-header">
                        <div class="value-icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3>For Communities</h3>
                    </div>
                    <div class="value-content">
                        <p>It fosters capacity building, intercultural dialogue, and practical improvements in education, livelihoods, and environmental stewardship.</p>
                        <ul class="value-benefits">
                            <li>Capacity building and skills development</li>
                            <li>Intercultural dialogue and exchange</li>
                            <li>Educational improvements and support</li>
                            <li>Environmental stewardship enhancement</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="value-conclusion">
                <div class="conclusion-content">
                    <div class="conclusion-text">
                        <p>Through this balanced approach, voluntourism at Virunga Ecotours becomes a <em>bridge between leisure and social responsibility</em>, allowing travelers to enjoy the Virunga Massif while contributing directly to the well-being of its people.</p>
                    </div>
                    <div class="conclusion-image">
                        <img src="../images/voluntourism/HO2A3360.jpg" alt="Community impact through voluntourism" loading="lazy">
                        <div class="image-caption">Building lasting connections between visitors and communities</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Voluntourism Activities Table Section -->
    <section class="activities-table-section">
        <div class="container">
            <h2 class="section-title">Voluntourism Activities and Their Community Impact</h2>
            <p class="section-description">A comprehensive overview of how voluntourism activities create mutual benefits for visitors and communities across different focus areas.</p>

            <div class="table-container">
                <table class="activities-table">
                    <thead>
                        <tr>
                            <th>Activity Category</th>
                            <th>Examples of Visitor Involvement</th>
                            <th>Benefits for Visitors</th>
                            <th>Benefits for Communities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Reset the table rows result pointer
                        mysqli_data_seek($table_rows_result, 0);
                        while ($row = mysqli_fetch_assoc($table_rows_result)):
                        ?>
                            <tr>
                                <td class="category-cell">
                                    <div class="category-header">
                                        <i class="<?php echo htmlspecialchars($row['category_icon']); ?>"></i>
                                        <strong><?php echo htmlspecialchars($row['category_name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($row['visitor_involvement']); ?></td>
                                <td><?php echo htmlspecialchars($row['visitor_benefits']); ?></td>
                                <td><?php echo htmlspecialchars($row['community_benefits']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Volunteer Programs Section -->
    <section id="programs" class="programs-section">
        <div class="container">
            <h2 class="section-title">Volunteer Programs</h2>
            <p class="section-description">Choose from our range of community-led volunteer programs, each designed to address real community needs while providing meaningful experiences for volunteers.</p>

            <div class="programs-grid">
                <?php
                // Reset the programs result pointer
                mysqli_data_seek($programs_result, 0);
                while ($program = mysqli_fetch_assoc($programs_result)):
                ?>
                    <div class="program-card">
                        <div class="program-image">
                            <img src="<?php echo htmlspecialchars($program['program_image']); ?>" alt="<?php echo htmlspecialchars($program['program_title']); ?>" />
                            <div class="program-category"><?php echo htmlspecialchars($program['program_category']); ?></div>
                        </div>
                        <div class="program-content">
                            <h3><?php echo htmlspecialchars($program['program_title']); ?></h3>
                            <p><?php echo htmlspecialchars($program['program_description']); ?></p>
                            <div class="program-details">
                                <span class="duration"><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($program['duration']); ?></span>
                                <span class="skills"><i class="fas fa-user-graduate"></i> <?php echo htmlspecialchars($program['skills_required']); ?></span>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Volunteer Experience Section -->
    <section class="experience-section">
        <div class="container">
            <h2 class="section-title">What to Expect as a Volunteer</h2>
            <p class="section-description">Our volunteer experiences are designed to be transformative for both volunteers and communities, creating lasting positive change through authentic cultural exchange.</p>

            <div class="experience-timeline">
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-plane-arrival"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Arrival & Orientation</h3>
                        <p>Community welcome, cultural orientation, project briefing, and homestay placement. Learn about local customs, traditions, and community expectations.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Homestay Integration</h3>
                        <p>Live with local families, participate in daily life, learn traditional skills, and support equitable income distribution. Experience authentic community living.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Project Participation</h3>
                        <p>Hands-on participation in community-led projects. Contribute your skills while learning from local expertise and traditional knowledge systems.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Cultural Immersion</h3>
                        <p>Participate in farm-to-table activities, learn traditional recipes, engage in storytelling, music, dance, and cooking experiences led by community members.</p>
                    </div>
                </div>

                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Impact Assessment</h3>
                        <p>Participate in measuring project impact, provide feedback, and contribute to program improvement. See the direct results of your contributions.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Training & Preparation Section -->
    <section class="training-section">
        <div class="container">
            <div class="training-content">
                <div class="training-text">
                    <h2 class="section-title">Training & Preparation</h2>
                    <p>We provide comprehensive training to ensure volunteers are well-prepared for meaningful community engagement and cultural exchange.</p>

                    <div class="training-modules">
                        <div class="module-item">
                            <i class="fas fa-globe"></i>
                            <div>
                                <h4>Cultural Sensitivity Training</h4>
                                <p>Learn about local customs, traditions, and appropriate behavior in community settings</p>
                            </div>
                        </div>

                        <div class="module-item">
                            <i class="fas fa-language"></i>
                            <div>
                                <h4>Language Preparation</h4>
                                <p>Basic language skills in local languages and French to enhance communication</p>
                            </div>
                        </div>

                        <div class="module-item">
                            <i class="fas fa-tools"></i>
                            <div>
                                <h4>Project-Specific Skills</h4>
                                <p>Technical training relevant to your chosen volunteer program and community needs</p>
                            </div>
                        </div>

                        <div class="module-item">
                            <i class="fas fa-heart"></i>
                            <div>
                                <h4>Ethical Guidelines</h4>
                                <p>Comprehensive training on responsible voluntourism and avoiding cultural exploitation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="training-image">
                    <img src="assets/images/volunteer-training.jpg" alt="Volunteer training session" />
                </div>
            </div>
        </div>
    </section>

    <!-- Impact & Outcomes Section -->
    <section class="impact-section">
        <div class="container">
            <h2 class="section-title">Measuring Impact & Outcomes</h2>
            <p class="section-description">We track both community impact and volunteer transformation to ensure our programs create lasting positive change for all participants.</p>

            <div class="impact-grid">
                <div class="impact-card">
                    <div class="impact-stats">
                        <div class="stat-number">89%</div>
                        <div class="stat-label">Volunteers report life-changing experience</div>
                    </div>
                    <p>Through immersive, respectful experiences, volunteers develop environmental awareness and cultural appreciation that persist long-term.</p>
                </div>

                <div class="impact-card">
                    <div class="impact-stats">
                        <div class="stat-number">94%</div>
                        <div class="stat-label">Communities rate programs as beneficial</div>
                    </div>
                    <p>Community feedback shows high satisfaction with volunteer contributions and cultural exchange experiences.</p>
                </div>

                <div class="impact-card">
                    <div class="impact-stats">
                        <div class="stat-number">76%</div>
                        <div class="stat-label">Volunteers continue supporting communities</div>
                    </div>
                    <p>Long-term engagement continues through ongoing donations, advocacy, and return visits to communities.</p>
                </div>
            </div>

            <div class="measurement-methods">
                <h3>How We Measure Impact</h3>
                <div class="methods-grid">
                    <div class="method-item">
                        <i class="fas fa-poll"></i>
                        <h4>Community Surveys</h4>
                        <p>Regular surveys and participatory assessments with community members</p>
                    </div>
                    <div class="method-item">
                        <i class="fas fa-chart-bar"></i>
                        <h4>Project Metrics</h4>
                        <p>Quantitative measurement of project outcomes and community benefits</p>
                    </div>
                    <div class="method-item">
                        <i class="fas fa-comments"></i>
                        <h4>Feedback Integration</h4>
                        <p>Volunteer and community feedback incorporated into program improvement</p>
                    </div>
                    <div class="method-item">
                        <i class="fas fa-search"></i>
                        <h4>Third-Party Evaluation</h4>
                        <p>Independent assessments ensure transparency and accountability</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">Frequently Asked Questions</h2>

            <div class="faq-grid">
                <?php
                // Reset the FAQ result pointer
                mysqli_data_seek($faq_result, 0);
                while ($faq = mysqli_fetch_assoc($faq_result)):
                ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php echo htmlspecialchars($faq['question']); ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p><?php echo htmlspecialchars($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-content">
                <h2>Ready to Begin Your Volunteer Journey?</h2>
                <p>Contact our voluntourism team to learn more about our programs, application process, and how you can contribute to meaningful community development while experiencing transformative travel.</p>
                <div class="contact-info">
                    <div class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <div>
                            <h4>Email Us</h4>
                            <p>info@virungaecotours.com</p>
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
        // Voluntourism page specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ accordion functionality
            document.querySelectorAll('.faq-question').forEach(question => {
                question.addEventListener('click', function() {
                    const faqItem = this.parentElement;
                    const answer = faqItem.querySelector('.faq-answer');
                    const icon = this.querySelector('i');

                    // Toggle active state
                    faqItem.classList.toggle('active');

                    // Toggle answer visibility
                    if (faqItem.classList.contains('active')) {
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                        icon.style.transform = 'rotate(180deg)';
                    } else {
                        answer.style.maxHeight = '0';
                        icon.style.transform = 'rotate(0deg)';
                    }
                });
            });

            // Animate elements on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all animatable elements
            document.querySelectorAll('.program-card, .principle-item, .ethics-card, .timeline-item, .impact-card, .step-item').forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(30px)';
                item.style.transition = `all 0.6s ease ${index * 0.1}s`;
                observer.observe(item);
            });

            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>