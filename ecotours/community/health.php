<?php
require_once '../admin/config/connection.php';

$page_title = "Health Tourism Programs - Virunga Ecotours";
$page_description = "Discover how Virunga Ecotours integrates health tourism into community-based activities, connecting wellness experiences with local health initiatives and community development.";

// Get healthcare programs count
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE category = 'Healthcare' AND status IN ('active', 'completed')";
$count_result = mysqli_query($conn, $count_query);
$total_healthcare_programs = mysqli_fetch_assoc($count_result)['total'];

// Get healthcare programs
$programs_query = "SELECT * FROM community_programs WHERE category = 'Healthcare' AND status IN ('active', 'completed') ORDER BY featured DESC, created_at DESC";
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
    <link rel="stylesheet" href="assets/css/health.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="health-page">
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="assets/images/IMG_9320.jpg" alt="Health Tourism Programs" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Health Tourism</span>
                </nav>
                <h1>Health Tourism Programs</h1>
                <p>Wellness through community health engagement that connects visitors with local health initiatives while promoting traditional wellness practices and community well-being.</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>Integration of Health Tourism into Community-Based Activities</h2>
                    <p>Health tourism, in the context of Virunga Ecotours, is not limited to medical treatment but extends to well-being, preventive health practices, and community health engagement. The approach is designed to link visitors' travel experiences with opportunities to contribute to and learn from local health initiatives. This integration strengthens both the visitor experience and the host communities' health outcomes.</p>
                </div>

                <div class="intro-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>Community Health Engagement</h3>
                        <p>Grassroots health initiatives and rural clinic support programs</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-spa"></i>
                        </div>
                        <h3>Preventive & Wellness Tourism</h3>
                        <p>Traditional medicine, nature therapy, and wellness activities</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h3>Philanthropic Health Support</h3>
                        <p>Volunteer expertise and resource contribution to health systems</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Health Awareness Exchange</h3>
                        <p>Cultural exchange on nutrition, hygiene, and wellness practices</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Health Dimensions Section -->
    <section class="dimensions-section">
        <div class="container">
            <h2 class="section-title">Four Dimensions of Health Tourism</h2>
            <p class="section-description">Our health tourism model integrates wellness experiences with meaningful community health contributions across four key dimensions.</p>

            <div class="dimensions-grid">
                <div class="dimension-card">
                    <div class="dimension-number">1</div>
                    <div class="dimension-content">
                        <h3>Community Health Engagement</h3>
                        <p>Visitors are introduced to grassroots health initiatives such as rural clinics, maternal health programs, or water sanitation projects. Through guided visits, they learn about local challenges and innovations in public health. Some tours allow active participation, like supporting awareness campaigns or assisting in educational health sessions.</p>
                        <ul class="dimension-benefits">
                            <li>Rural clinic visits and support</li>
                            <li>Maternal health program engagement</li>
                            <li>Water sanitation project participation</li>
                            <li>Public health awareness campaigns</li>
                        </ul>
                    </div>
                </div>

                <div class="dimension-card">
                    <div class="dimension-number">2</div>
                    <div class="dimension-content">
                        <h3>Preventive and Wellness Tourism</h3>
                        <p>Virunga Ecotours connects travelers to wellness-based activities, such as traditional herbal medicine, stress-relief practices, and nature-based therapies (forest walks, hot springs where available). These are framed as both cultural encounters and health-enhancing experiences.</p>
                        <ul class="dimension-benefits">
                            <li>Traditional herbal medicine learning</li>
                            <li>Nature-based therapy sessions</li>
                            <li>Stress-relief practices</li>
                            <li>Cultural wellness encounters</li>
                        </ul>
                    </div>
                </div>

                <div class="dimension-card">
                    <div class="dimension-number">3</div>
                    <div class="dimension-content">
                        <h3>Philanthropy and Volunteer Health Support</h3>
                        <p>Health tourism is linked with voluntourism: travelers can contribute resources (medications, supplies) or time (sharing expertise if they are health professionals) to local initiatives. This creates a philanthropic channel that directly impacts community health resilience.</p>
                        <ul class="dimension-benefits">
                            <li>Medical supply donations</li>
                            <li>Professional expertise sharing</li>
                            <li>Health facility support</li>
                            <li>Community health resilience building</li>
                        </ul>
                    </div>
                </div>

                <div class="dimension-card">
                    <div class="dimension-number">4</div>
                    <div class="dimension-content">
                        <h3>Health Awareness through Cultural Exchange</h3>
                        <p>Activities such as school visits and community discussions integrate health topics like nutrition, hygiene, and reproductive health. Tourists learn about traditional approaches while also sharing cross-cultural perspectives on well-being.</p>
                        <ul class="dimension-benefits">
                            <li>School health education programs</li>
                            <li>Community wellness discussions</li>
                            <li>Cross-cultural health perspectives</li>
                            <li>Traditional wellness knowledge sharing</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integration Table Section -->
    <section class="integration-table-section">
        <div class="container">
            <h2 class="section-title">Integration of Health Tourism in Community-Based Activities</h2>
            <p class="section-description">A comprehensive overview of how health tourism activities are integrated with community development initiatives.</p>

            <div class="table-container">
                <table class="integration-table">
                    <thead>
                        <tr>
                            <th>Activity Type</th>
                            <th>Description</th>
                            <th>Health Tourism Dimension</th>
                            <th>Benefit for Travelers</th>
                            <th>Benefit for Community</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-clinic-medical"></i>
                                    <strong>Community Clinic Visits</strong>
                                </div>
                            </td>
                            <td>Guided visits to rural health centers, discussions with local health workers</td>
                            <td>Public health education & awareness</td>
                            <td>Understanding rural health systems & challenges</td>
                            <td>Visibility and support for local health facilities</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-leaf"></i>
                                    <strong>Herbal Medicine Demonstration</strong>
                                </div>
                            </td>
                            <td>Learning from traditional healers about plant-based remedies</td>
                            <td>Preventive & cultural health practices</td>
                            <td>Immersion in local healing traditions</td>
                            <td>Recognition and preservation of indigenous knowledge</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-tint"></i>
                                    <strong>Water & Sanitation Projects</strong>
                                </div>
                            </td>
                            <td>Participation in clean water initiatives or hygiene campaigns</td>
                            <td>Preventive health & wellness</td>
                            <td>Hands-on contribution to health-related projects</td>
                            <td>Improved sanitation & disease prevention</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-school"></i>
                                    <strong>School Health Programs</strong>
                                </div>
                            </td>
                            <td>Engaging with schools on nutrition, hygiene, or reproductive health awareness</td>
                            <td>Health education</td>
                            <td>Learning cultural perspectives on health issues</td>
                            <td>Improved health knowledge for children and youth</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-walking"></i>
                                    <strong>Wellness Walks & Nature Therapy</strong>
                                </div>
                            </td>
                            <td>Forest walks, relaxation activities, exposure to fresh air and volcanic scenery</td>
                            <td>Stress relief, wellness, eco-therapy</td>
                            <td>Personal health and mental well-being</td>
                            <td>Promotion of natural assets as wellness resources</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-heart"></i>
                                    <strong>Philanthropic Health Support</strong>
                                </div>
                            </td>
                            <td>Donating supplies or volunteering skills in community health programs</td>
                            <td>Philanthropy linked to health tourism</td>
                            <td>Fulfillment through contribution to health systems</td>
                            <td>Strengthening local healthcare capacity</td>
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
                <h2 class="section-title">Healthcare Programs</h2>
                <p class="section-description">Explore our comprehensive healthcare programs that integrate wellness tourism with community health development.</p>
            </div>

            <div class="programs-grid">
                <?php if (mysqli_num_rows($programs_result) > 0): ?>
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <div class="program-card" data-category="<?php echo htmlspecialchars($program['category']); ?>">
                            <div class="program-image">
                                <img src="assets/images/programs/<?php echo htmlspecialchars($program['image'] ?: 'default-healthcare.jpg'); ?>"
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
                                        <i class="fas fa-stethoscope"></i>
                                        <?php echo htmlspecialchars($program['category']); ?>
                                    </div>
                                </div>

                                <p class="program-description"><?php echo htmlspecialchars($program['short_description']); ?></p>

                                <div class="program-details">
                                    

                                    <?php if ($program['beneficiaries']): ?>
                                    <div class="program-detail">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($program['beneficiaries']); ?> Beneficiaries</span>
                                    </div>
                                    <?php endif; ?>

                                    
                                </div>

                                <div class="program-impact">
                                    <h4>Health Focus:</h4>
                                    <div class="impact-tags">
                                        <span class="impact-tag">
                                            <i class="fas fa-heartbeat"></i>
                                            Community Health
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-spa"></i>
                                            Wellness Tourism
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-hands-helping"></i>
                                            Health Support
                                        </span>
                                    </div>
                                </div>

                                <div class="program-actions">
                                    <a href="program-detail.php?id=<?php echo $program['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        Learn More
                                    </a>
                                    <a href="../pages/contactus.php?program=<?php echo urlencode($program['title']); ?>" class="btn btn-secondary">
                                        <i class="fas fa-stethoscope"></i>
                                        Join Program
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-programs">
                        <div class="no-programs-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <h3>No Programs Found</h3>
                        <p>We're currently developing new healthcare programs. Check back soon!</p>
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
                    <h2>Join Our Health Tourism Initiative</h2>
                    <p>Experience transformative wellness tourism that creates lasting health improvements for communities in the Virunga region. Your participation directly supports healthcare infrastructure and promotes traditional wellness practices.</p>
                    <div class="cta-stats">
                        <div class="cta-stat">
                            <span class="stat-number">25</span>
                            <span class="stat-label">Health Facilities</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">5000+</span>
                            <span class="stat-label">Lives Impacted</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">90%</span>
                            <span class="stat-label">Health Improvement</span>
                        </div>
                    </div>
                </div>
                <div class="cta-actions">
                    <a href="../pages/contactus.php" class="btn btn-primary">
                        <i class="fas fa-stethoscope"></i>
                        Support Health Programs
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