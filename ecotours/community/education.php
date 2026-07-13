<?php
require_once '../admin/config/connection.php';

$page_title = "Educational Tourism Programs - Virunga Ecotours";
$page_description = "Discover how Virunga Ecotours integrates education into community-based tourism, creating transformative learning experiences that empower both visitors and local communities.";

// Get education programs count
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE category = 'Education' AND status IN ('active', 'completed')";
$count_result = mysqli_query($conn, $count_query);
$total_education_programs = mysqli_fetch_assoc($count_result)['total'];

// Get education programs
$programs_query = "SELECT * FROM community_programs WHERE category = 'Education' AND status IN ('active', 'completed') ORDER BY featured DESC, created_at DESC";
$programs_result = mysqli_query($conn, $programs_query);

// Get education programs
$programs_query = "SELECT * FROM community_programs WHERE category = 'Education' AND status IN ('active', 'completed') ORDER BY featured DESC, created_at DESC";
$programs_result = mysqli_query($conn, $programs_query);
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
    <link rel="stylesheet" href="assets/css/education.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="education-page">
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="assets/images/IMG_9320.jpg" alt="Educational Tourism Programs" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Education</span>
                </nav>
                <h1>Educational Tourism Programs</h1>
                <p>Learning that transforms communities through educational exchanges, cultural preservation, and capacity building that benefits both visitors and local communities.</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>How Virunga Ecotours Integrates Education in Community-Based Tourism</h2>
                    <p>Virunga Ecotours recognizes that tourism should not only entertain but also <em>educate and empower</em>. That is why education is deeply woven into its community-based tourism model. The programs create a bridge between visitors and local residents, ensuring that knowledge flows in both directions. Tourists learn about conservation, cultural heritage, and community life, while locals gain access to skills, resources, and opportunities.</p>
                </div>
                
                <div class="intro-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Environmental Education</h3>
                        <p>Conservation learning through guided forest treks and wildlife encounters</p>
                    </div>
                    
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <h3>School Engagement</h3>
                        <p>Direct interaction with students and educational system support</p>
                    </div>
                    
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h3>Cultural Learning</h3>
                        <p>Hands-on cultural activities and traditional knowledge preservation</p>
                    </div>
                    
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Skills Exchange</h3>
                        <p>Reciprocal learning between visitors and community members</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Focus Areas Section -->
    <section class="focus-areas-section">
        <div class="container">
            <h2 class="section-title">Five Educational Focus Areas</h2>
            <p class="section-description">Our educational tourism model creates transformative learning experiences across five key areas that benefit both visitors and communities.</p>

            <div class="focus-areas-grid">
                <div class="focus-area-card">
                   
                    <div class="focus-area-content">
                        <h3>Environmental and Conservation Education</h3>
                        <p>Guided tours in forests, wetlands, and volcanic landscapes are designed to be highly educational. Visitors learn how ecosystems function, why species like the mountain gorilla are endangered, and how communities play an active role in conservation. This transforms every trek, hike, or birdwatching walk into a living classroom, inspiring responsible tourism.</p>
                        <ul class="focus-area-benefits">
                            <li>Ecosystem function understanding</li>
                            <li>Species conservation awareness</li>
                            <li>Community conservation roles</li>
                            <li>Responsible tourism practices</li>
                        </ul>
                    </div>
                </div>

                <div class="focus-area-card">
                   
                    <div class="focus-area-content">
                        <h3>Community and School Engagement</h3>
                        <p>Education tours often include visits to schools, where tourists interact with students and teachers. These visits give guests insight into local education systems while also providing opportunities for contributions—such as donating books or offering mentorship. For students, the encounters bring global perspectives directly into their classrooms, enriching their worldview.</p>
                        <ul class="focus-area-benefits">
                            <li>School system insights</li>
                            <li>Student-tourist interactions</li>
                            <li>Educational resource contributions</li>
                            <li>Global perspective sharing</li>
                        </ul>
                    </div>
                </div>

                <div class="focus-area-card">
                    
                    <div class="focus-area-content">
                        <h3>Cultural Learning Experiences</h3>
                        <p>Cultural activities—such as cooking traditional dishes, weaving, drumming, or storytelling—are intentionally structured as <em>educational exchanges</em>. Tourists are not only spectators but active learners, guided by local knowledge-holders. This helps preserve intangible cultural heritage while giving communities pride and recognition for their traditions.</p>
                        <ul class="focus-area-benefits">
                            <li>Traditional skill learning</li>
                            <li>Cultural heritage preservation</li>
                            <li>Active participation experiences</li>
                            <li>Community pride building</li>
                        </ul>
                    </div>
                </div>

                <div class="focus-area-card">
                   
                    <div class="focus-area-content">
                        <h3>Skills Development and Volunteer Education</h3>
                        <p>Some programs allow tourists to share their own expertise in areas like language, teaching, or healthcare. At the same time, community members are trained in tourism, guiding, hospitality, and environmental management. This reciprocal exchange builds stronger local capacity while giving visitors meaningful, purposeful experiences.</p>
                        <ul class="focus-area-benefits">
                            <li>Expertise sharing opportunities</li>
                            <li>Tourism skills training</li>
                            <li>Capacity building programs</li>
                            <li>Reciprocal learning exchanges</li>
                        </ul>
                    </div>
                </div>

                <div class="focus-area-card">
                    
                    <div class="focus-area-content">
                        <h3>Holistic Community Impact</h3>
                        <p>By embedding education into every stage of tourism, Virunga Ecotours ensures that visits uplift communities, strengthen local identity, and create long-term benefits. Education tours, therefore, are not an "add-on" but a <em>core strategy</em> to make tourism more inclusive, impactful, and transformative.</p>
                        <ul class="focus-area-benefits">
                            <li>Community upliftment</li>
                            <li>Local identity strengthening</li>
                            <li>Long-term benefit creation</li>
                            <li>Inclusive tourism development</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Summary Table Section -->
    <section class="summary-table-section">
        <div class="container">
            <h2 class="section-title">Community-Based Education in Virunga Ecotours</h2>
            <p class="section-description">A comprehensive overview of how educational activities create mutual benefits for visitors and communities across different focus areas.</p>

            <div class="table-container">
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>Community Focus Area</th>
                            <th>Educational Activity</th>
                            <th>Impact on Visitors</th>
                            <th>Impact on Communities</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-leaf"></i>
                                    <strong>Environmental Awareness</strong>
                                </div>
                            </td>
                            <td>Guided forest treks, gorilla interpretation, birdwatching tours</td>
                            <td>Learn about biodiversity and conservation challenges</td>
                            <td>Builds recognition of local role in conservation</td>
                        </tr>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-school"></i>
                                    <strong>Schools & Youth</strong>
                                </div>
                            </td>
                            <td>School visits, cultural exchanges, joint activities with students</td>
                            <td>Understand education systems and youth aspirations</td>
                            <td>Students gain exposure to global knowledge & support</td>
                        </tr>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-palette"></i>
                                    <strong>Cultural Heritage</strong>
                                </div>
                            </td>
                            <td>Hands-on activities: cooking, weaving, drumming, storytelling</td>
                            <td>Gain practical cultural knowledge through participation</td>
                            <td>Preserves traditions and creates income opportunities</td>
                        </tr>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-tools"></i>
                                    <strong>Skills & Capacity Building</strong>
                                </div>
                            </td>
                            <td>Training in guiding, eco-tourism management, farming workshops</td>
                            <td>Learn practical local crafts and skills</td>
                            <td>Builds capacity and empowers youth and women groups</td>
                        </tr>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-handshake"></i>
                                    <strong>Volunteer Exchange</strong>
                                </div>
                            </td>
                            <td>Visitors share expertise in teaching, languages, healthcare, or business</td>
                            <td>Meaningful engagement and knowledge exchange</td>
                            <td>Strengthens local resources and builds partnerships</td>
                        </tr>
                        <tr>
                            <td class="focus-cell">
                                <div class="focus-header">
                                    <i class="fas fa-gift"></i>
                                    <strong>Philanthropy in Education</strong>
                                </div>
                            </td>
                            <td>Tourists contribute resources (books, materials, scholarships) during visits</td>
                            <td>Deeper connection and purpose in their travel</td>
                            <td>Communities gain educational resources and support</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Educational Programs</h2>
                <p class="section-description">Explore our comprehensive educational programs that create transformative learning experiences for both visitors and communities.</p>
            </div>

            <div class="programs-grid">
                <?php if (mysqli_num_rows($programs_result) > 0): ?>
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <div class="program-card" data-category="<?php echo htmlspecialchars($program['category']); ?>">
                            <div class="program-image">
                                <img src="assets/images/programs/<?php echo htmlspecialchars($program['image'] ?: 'default-education.jpg'); ?>"
                                     alt="<?php echo htmlspecialchars($program['title']); ?>"
                                     loading="lazy">
                                <div class="program-overlay">
                                    <div class="program-badges">
                                        <span class="program-country">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars(ucfirst($program['country'])); ?>
                                        </span>
                                        <?php if ($program['featured']): ?>
                                            <span class="program-featured">
                                                <i class="fas fa-star"></i> Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="program-status status-<?php echo $program['status']; ?>">
                                        <i class="fas fa-<?php echo $program['status'] == 'active' ? 'play-circle' : ($program['status'] == 'completed' ? 'check-circle' : 'clock'); ?>"></i>
                                        <?php echo htmlspecialchars(ucfirst($program['status'])); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="program-content">
                                <div class="program-header">
                                    <h3 class="program-title"><?php echo htmlspecialchars($program['title']); ?></h3>
                                    <div class="program-category">
                                        <i class="fas fa-graduation-cap"></i>
                                        <?php echo htmlspecialchars($program['category']); ?>
                                    </div>
                                </div>

                                <p class="program-description"><?php echo htmlspecialchars($program['short_description']); ?></p>

                                <div class="program-details">
                                    

                                    <?php if ($program['beneficiaries']): ?>
                                    <div class="program-detail">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($program['beneficiaries']); ?> Students Reached</span>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($program['budget']): ?>
                                    <div class="program-detail">
                                        <i class="fas fa-dollar-sign"></i>
                                        <span>Budget: $<?php echo number_format($program['budget']); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="program-impact">
                                    <h4>Educational Focus:</h4>
                                    <div class="impact-tags">
                                        <span class="impact-tag">
                                            <i class="fas fa-book"></i>
                                            Learning Support
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-users"></i>
                                            Community Education
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-lightbulb"></i>
                                            Knowledge Exchange
                                        </span>
                                    </div>
                                </div>

                                <div class="program-actions">
                                    <a href="program-detail.php?id=<?php echo $program['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        Learn More
                                    </a>
                                    <a href="../pages/contactus.php?program=<?php echo urlencode($program['title']); ?>" class="btn btn-secondary">
                                        <i class="fas fa-graduation-cap"></i>
                                        Join Program
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-programs">
                        <div class="no-programs-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>No Programs Found</h3>
                        <p>We're currently developing new educational programs. Check back soon!</p>
                        <a href="programs.php" class="btn btn-primary">View All Programs</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Join Our Educational Tourism Initiative</h2>
                    <p>Experience transformative educational tourism that creates lasting learning opportunities for communities in the Virunga region. Your participation directly supports educational infrastructure and promotes knowledge exchange between cultures.</p>
                    <div class="cta-stats">
                        <div class="cta-stat">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Schools Supported</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">2000+</span>
                            <span class="stat-label">Students Reached</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Learning Impact</span>
                        </div>
                    </div>
                </div>
                <div class="cta-actions">
                    <a href="../pages/contactus.php" class="btn btn-primary">
                        <i class="fas fa-graduation-cap"></i>
                        Support Education
                    </a>
                    <a href="programs.php" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        View All Programs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>


</body>
</html>
