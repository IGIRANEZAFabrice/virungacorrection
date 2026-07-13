<?php
require_once '../admin/config/connection.php';

$page_title = "Women's Empowerment Programs - Virunga Ecotours";
$page_description = "Discover how Virunga Ecotours integrates women's empowerment into community-based tourism through capacity building, economic inclusion, and cultural visibility programs.";

// Get empowerment programs count
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE category = 'Empowerment' AND status IN ('active', 'completed')";
$count_result = mysqli_query($conn, $count_query);
$total_empowerment_programs = mysqli_fetch_assoc($count_result)['total'];

// Get empowerment programs
$programs_query = "SELECT * FROM community_programs WHERE category = 'Empowerment' AND status IN ('active', 'completed') ORDER BY featured DESC, created_at DESC";
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
    <link rel="stylesheet" href="assets/css/women.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="women-page">
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="assets/images/IMG_9320.jpg" alt="Women's Empowerment Programs" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Women's Empowerment</span>
                </nav>
                <h1>Women's Empowerment Programs</h1>
                <p>Advancing gender equity through community-based tourism that empowers women economically, socially, and culturally across the Virunga region.</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>Women's Empowerment in Community-Based Tourism</h2>
                    <p>Virunga Ecotours has strategically integrated women's empowerment into its tourism model as both a social and economic development goal. Women in the Virunga Massif historically faced limited access to income, decision-making, and professional opportunities. By embedding empowerment initiatives into community-based tourism, the company not only advances gender equity but also enriches the visitor experience through authentic engagement with local culture.</p>
                </div>

                <div class="intro-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Capacity Building</h3>
                        <p>Training in hospitality, guiding, handicrafts, and small business management</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Economic Inclusion</h3>
                        <p>Direct income through homestays, cooperatives, and cultural experiences</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3>Cultural Visibility</h3>
                        <p>Recognition through dance, storytelling, and culinary demonstrations</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Social Transformation</h3>
                        <p>Challenging stereotypes and creating visible leadership opportunities</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Impact Section -->
    <section class="impact-section">
        <div class="container">
            <h2 class="section-title">Four Pillars of Women's Empowerment</h2>

            <div class="impact-grid">
                <div class="impact-card">
                    <div class="impact-header">
                       
                        <h3>Capacity Building and Skills Training</h3>
                    </div>
                    <div class="impact-content">
                        <p>Women are offered training in hospitality, guiding, handicrafts, and small business management. These programs enhance professional competence, giving women the tools to participate in tourism value chains. For example, local women's cooperatives produce handicrafts and food products marketed directly to visitors.</p>
                        <ul class="impact-benefits">
                            <li>Hospitality and guiding training</li>
                            <li>Handicraft production skills</li>
                            <li>Small business management</li>
                            <li>Professional competence development</li>
                        </ul>
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-header">
                        
                        <h3>Economic Inclusion</h3>
                    </div>
                    <div class="impact-content">
                        <p>Virunga Ecotours allocates specific roles for women within homestays, cooperatives, and cultural experiences. This ensures that female participants gain direct income from tourism activities. Income diversification reduces household vulnerability and strengthens women's decision-making power.</p>
                        <ul class="impact-benefits">
                            <li>Direct income from tourism</li>
                            <li>Homestay management roles</li>
                            <li>Cooperative leadership</li>
                            <li>Financial independence</li>
                        </ul>
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-header">
                       
                        <h3>Cultural Visibility</h3>
                    </div>
                    <div class="impact-content">
                        <p>Through curated cultural encounters—such as dance, storytelling, and culinary demonstrations—women's knowledge and traditions are given visibility and recognition. This not only validates women's cultural contributions but also positions them as cultural ambassadors.</p>
                        <ul class="impact-benefits">
                            <li>Dance and music performances</li>
                            <li>Traditional storytelling</li>
                            <li>Culinary demonstrations</li>
                            <li>Cultural ambassador roles</li>
                        </ul>
                    </div>
                </div>

                <div class="impact-card">
                    <div class="impact-header">
                       
                        <h3>Social Transformation</h3>
                    </div>
                    <div class="impact-content">
                        <p>The presence of empowered women in tourism reshapes local social structures by challenging stereotypes. Tourism becomes a platform where women transition from invisible contributors to visible leaders in both economic and cultural domains.</p>
                        <ul class="impact-benefits">
                            <li>Leadership development</li>
                            <li>Stereotype challenging</li>
                            <li>Community governance participation</li>
                            <li>Visible economic roles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integration Table Section -->
    <section class="integration-table-section">
        <div class="container">
            <h2 class="section-title">Integration of Women's Empowerment in Tourism Activities</h2>
            <p class="section-description">A comprehensive overview of how women's empowerment is integrated across different dimensions of our tourism activities.</p>

            <div class="table-container">
                <table class="integration-table">
                    <thead>
                        <tr>
                            <th>Dimension</th>
                            <th>Integration in Tourism Activities</th>
                            <th>Impact on Women</th>
                            <th>Impact on Tourism Sector</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="dimension-cell">
                                <div class="dimension-header">
                                    <i class="fas fa-tools"></i>
                                    <strong>Capacity Building</strong>
                                </div>
                            </td>
                            <td>Training in hospitality, guiding, and handicrafts</td>
                            <td>Improved skills, professional identity</td>
                            <td>Higher service quality and authenticity</td>
                        </tr>
                        <tr>
                            <td class="dimension-cell">
                                <div class="dimension-header">
                                    <i class="fas fa-chart-line"></i>
                                    <strong>Economic Inclusion</strong>
                                </div>
                            </td>
                            <td>Employment in homestays, cooperatives, and market access</td>
                            <td>Increased income, financial independence</td>
                            <td>Diversified tourism economy</td>
                        </tr>
                        <tr>
                            <td class="dimension-cell">
                                <div class="dimension-header">
                                    <i class="fas fa-theater-masks"></i>
                                    <strong>Cultural Visibility</strong>
                                </div>
                            </td>
                            <td>Dance, music, storytelling, and culinary activities led by women</td>
                            <td>Recognition of cultural role, pride, social status</td>
                            <td>Enriched tourist experience, deeper cultural immersion</td>
                        </tr>
                        <tr>
                            <td class="dimension-cell">
                                <div class="dimension-header">
                                    <i class="fas fa-balance-scale"></i>
                                    <strong>Social Transformation</strong>
                                </div>
                            </td>
                            <td>Women in decision-making, leadership of cooperatives and associations</td>
                            <td>Greater voice in community governance</td>
                            <td>Inclusive and sustainable tourism governance</td>
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
                <h2 class="section-title">Women's Empowerment Programs</h2>
                <p class="section-description">Explore our comprehensive programs designed to empower women through tourism and community development.</p>
            </div>

            <div class="programs-grid">
                <?php if (mysqli_num_rows($programs_result) > 0): ?>
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <div class="program-card" data-category="<?php echo htmlspecialchars($program['category']); ?>">
                            <div class="program-image">
                                <img src="assets/images/programs/<?php echo htmlspecialchars($program['image'] ?: 'default-empowerment.jpg'); ?>"
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
                                        <i class="fas fa-female"></i>
                                        <?php echo htmlspecialchars($program['category']); ?>
                                    </div>
                                </div>

                                <p class="program-description"><?php echo htmlspecialchars($program['short_description']); ?></p>

                                <div class="program-details">
                                            

                                    <?php if ($program['beneficiaries']): ?>
                                    <div class="program-detail">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($program['beneficiaries']); ?> Women Beneficiaries</span>
                                    </div>
                                    <?php endif; ?>

                                    
                                </div>

                                <div class="program-impact">
                                    <h4>Key Impact Areas:</h4>
                                    <div class="impact-tags">
                                        <span class="impact-tag">
                                            <i class="fas fa-graduation-cap"></i>
                                            Skills Training
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-coins"></i>
                                            Economic Empowerment
                                        </span>
                                        <span class="impact-tag">
                                            <i class="fas fa-users"></i>
                                            Leadership
                                        </span>
                                    </div>
                                </div>

                                <div class="program-actions">
                                    <a href="program-detail.php?id=<?php echo $program['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i>
                                        Learn More
                                    </a>
                                    <a href="../pages/contactus.php?program=<?php echo urlencode($program['title']); ?>" class="btn btn-secondary">
                                        <i class="fas fa-envelope"></i>
                                        Get Involved
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-programs">
                        <div class="no-programs-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No Programs Found</h3>
                        <p>We're currently developing new women's empowerment programs. Check back soon!</p>
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
                    <h2>Join Our Women's Empowerment Initiative</h2>
                    <p>Be part of transformative tourism that creates lasting change for women in the Virunga region. Your participation directly supports women's economic independence and cultural recognition.</p>
                    <div class="cta-stats">
                        <div class="cta-stat">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Women Empowered</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">15</span>
                            <span class="stat-label">Active Cooperatives</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">85%</span>
                            <span class="stat-label">Income Increase</span>
                        </div>
                    </div>
                </div>
                <div class="cta-actions">
                    <a href="../pages/contactus.php" class="btn btn-primary">
                        <i class="fas fa-envelope"></i>
                        Get Involved
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