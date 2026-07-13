<?php
// Database connection
require_once '../admin/config/connection.php';

// Start session for admin check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fetch page data
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_description, hero_image, intro_title, intro_lead, intro_text, intro_image, intro_caption FROM heritage_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    mysqli_query($conn, "INSERT INTO heritage_page (hero_title, hero_subtitle, hero_description, hero_image, intro_title, intro_lead, intro_text, intro_image, intro_caption) VALUES ('From Fields and Culture to Futures', 'Tourism as a Catalyst for Community Development', 'Discover how farm and cultural tourism transform communities, creating sustainable income while preserving heritage and supporting conservation efforts across the Virunga region.', 'assets/images/heritage-hero.jpg', 'Tourism as a Catalyst for Transformation', 'In the heart of the Virunga region, tourism serves as more than just an economic activity—it becomes a powerful force for community empowerment, cultural preservation, and sustainable development.', 'Through farm and cultural tourism, local communities transform their traditional practices into engaging visitor experiences, creating multiple income streams while maintaining their authentic way of life. This approach ensures that tourism benefits reach every level of society, from individual farmers and artists to entire nations.', 'assets/images/heritage-intro.jpg', 'Local communities engaging visitors in traditional farming and cultural practices')");
    $page = ['id' => mysqli_insert_id($conn), 'hero_title' => 'From Fields and Culture to Futures', 'hero_subtitle' => 'Tourism as a Catalyst for Community Development', 'hero_description' => 'Discover how farm and cultural tourism transform communities, creating sustainable income while preserving heritage and supporting conservation efforts across the Virunga region.', 'hero_image' => 'assets/images/heritage-hero.jpg', 'intro_title' => 'Tourism as a Catalyst for Transformation', 'intro_lead' => 'In the heart of the Virunga region, tourism serves as more than just an economic activity—it becomes a powerful force for community empowerment, cultural preservation, and sustainable development.', 'intro_text' => 'Through farm and cultural tourism, local communities transform their traditional practices into engaging visitor experiences, creating multiple income streams while maintaining their authentic way of life. This approach ensures that tourism benefits reach every level of society, from individual farmers and artists to entire nations.', 'intro_image' => 'assets/images/heritage-intro.jpg', 'intro_caption' => 'Local communities engaging visitors in traditional farming and cultural practices'];
}
$page_id = (int)$page['id'];

// Fetch sections
$sections = [];
$sq = mysqli_query($conn, "SELECT id, section_id, section_description, benefits_title FROM heritage_sections ORDER BY display_order ASC");
if ($sq) {
    while ($r = mysqli_fetch_assoc($sq)) {
        $r['benefits'] = [];
        $bq = mysqli_query($conn, "SELECT id, benefit_title, benefit_description FROM heritage_benefits WHERE section_id = '" . mysqli_real_escape_string($conn, $r['section_id']) . "' ORDER BY display_order ASC");
        if ($bq) {
            while ($b = mysqli_fetch_assoc($bq)) {
                $r['benefits'][] = $b;
            }
        }
        $sections[] = $r;
    }
}

// Fetch activities
$activities = [];
$aq = mysqli_query($conn, "SELECT id, icon_class, activity_title, activity_description FROM heritage_activities ORDER BY display_order ASC");
if ($aq) {
    while ($r = mysqli_fetch_assoc($aq)) {
        $activities[] = $r;
    }
}

// Fetch impacts
$impacts = [];
$iq = mysqli_query($conn, "SELECT id, level_name, icon_class, impact_description FROM heritage_impacts ORDER BY display_order ASC");
if ($iq) {
    while ($r = mysqli_fetch_assoc($iq)) {
        $impacts[] = $r;
    }
}

// Set page meta
$page_title = 'Heritage & Community Tourism | Virunga Ecotours';
$page_description = 'Discover farm and cultural tourism that transforms communities while preserving heritage and supporting conservation.';
$page_keywords = 'heritage tourism, farm tourism, cultural tourism, community development, Virunga';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Virunga Ecotours</title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <meta name="keywords" content="<?php echo $page_keywords; ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo $page_title; ?> | Virunga Ecotours">
    <meta property="og:description" content="<?php echo $page_description; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:image" content="../assets/images/heritage-og-image.jpg">
     <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="assets/css/heritage.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
            <?php if (!empty($page['hero_image'])): ?>
                <img src="<?php echo htmlspecialchars($page['hero_image']); ?>" alt="Farm and Cultural Heritage" class="hero-bg-img">
            <?php else: ?>
                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #8B7355 0%, #5C4033 100%);"></div>
            <?php endif; ?>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo htmlspecialchars($page['hero_title'] ?? 'From Fields and Culture to Futures'); ?></h1>
                <p class="hero-subtitle"><?php echo htmlspecialchars($page['hero_subtitle'] ?? 'Tourism as a Catalyst for Community Development'); ?></p>
                <p class="hero-description"><?php echo htmlspecialchars($page['hero_description'] ?? ''); ?></p>
                <div class="hero-buttons">
                    <a href="#farm-tourism" class="hero-btn primary">
                        <i class="fas fa-seedling"></i>
                        Explore Farm Tourism
                    </a>
                    <a href="#cultural-tourism" class="hero-btn secondary">
                        <i class="fas fa-music"></i>
                        Discover Cultural Heritage
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="introduction-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2><?php echo htmlspecialchars($page['intro_title'] ?? 'Tourism as a Catalyst for Transformation'); ?></h2>
                    <p class="intro-lead"><?php echo htmlspecialchars($page['intro_lead'] ?? ''); ?></p>
                    <p><?php echo htmlspecialchars($page['intro_text'] ?? ''); ?></p>
                </div>
                <div class="intro-image">
                    <?php if (!empty($page['intro_image'])): ?>
                        <img src="<?php echo htmlspecialchars($page['intro_image']); ?>" alt="Community tourism activities" class="intro-img">
                    <?php else: ?>
                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #e0e0e0 0%, #f5f5f5 100%);"></div>
                    <?php endif; ?>
                    <div class="image-caption">
                        <p><?php echo htmlspecialchars($page['intro_caption'] ?? 'Local communities engaging visitors in traditional farming and cultural practices'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Farm Tourism Section -->
    <section class="farm-tourism-section" id="farm-tourism">
        <div class="container">
            <div class="section-header">
                <h2>Farmer through Farm Tourism</h2>
                <p>Transforming agriculture into platforms for visitor experiences and community development</p>
            </div>

            <div class="farm-content">
                <div class="farm-description">
                    <?php
                    $farm_section = null;
                    foreach ($sections as $sec) {
                        if ($sec['section_id'] === 'farm-tourism') {
                            $farm_section = $sec;
                            break;
                        }
                    }
                    ?>
                    <p><?php echo htmlspecialchars($farm_section['section_description'] ?? ''); ?></p>
                </div>

                <div class="farm-benefits">
                    <h3><?php echo htmlspecialchars($farm_section['benefits_title'] ?? 'Income Generation Benefits'); ?></h3>
                    <div class="benefits-grid">
                        <?php foreach ($farm_section['benefits'] as $benefit): ?>
                        <div class="benefit-card">
                            <div class="benefit-content">
                                <h4><?php echo htmlspecialchars($benefit['benefit_title']); ?></h4>
                                <p><?php echo htmlspecialchars($benefit['benefit_description']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cultural Tourism Section -->
    <section class="cultural-tourism-section" id="cultural-tourism">
        <div class="container">
            <div class="section-header">
                <h2>Cultural Artist through Cultural Tourism</h2>
                <p>Transforming cultural heritage into engaging visitor experiences</p>
            </div>

            <div class="cultural-content">
                <div class="cultural-description">
                    <?php
                    $cultural_section = null;
                    foreach ($sections as $sec) {
                        if ($sec['section_id'] === 'cultural-tourism') {
                            $cultural_section = $sec;
                            break;
                        }
                    }
                    ?>
                    <p><?php echo htmlspecialchars($cultural_section['section_description'] ?? ''); ?></p>
                </div>

                <div class="cultural-benefits">
                    <h3><?php echo htmlspecialchars($cultural_section['benefits_title'] ?? 'Cultural Tourism Impact'); ?></h3>
                    <div class="benefits-grid">
                        <?php foreach ($cultural_section['benefits'] as $benefit): ?>
                        <div class="benefit-card">
                            <div class="benefit-content">
                                <h4><?php echo htmlspecialchars($benefit['benefit_title']); ?></h4>
                                <p><?php echo htmlspecialchars($benefit['benefit_description']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities Section -->
    <section class="activities-section">
        <div class="container">
            <div class="section-header">
                <h2>Activities Supporting Community Income Generation</h2>
                <p>Diverse activities that create direct and indirect income streams for communities</p>
            </div>

            <div class="activities-content">
                <div class="activities-intro">
                    <p>Both farm and cultural tourism involve diverse activities that create direct and indirect income streams. These activities do more than create revenue—they empower rural communities, conserve culture and nature, support states economically, and enhance national identity globally.</p>
                </div>

                <div class="activities-grid">
                    <?php foreach ($activities as $activity): ?>
                    <div class="activity-card">
                        <div class="activity-icon">
                            <i class="<?php echo htmlspecialchars($activity['icon_class']); ?>"></i>
                        </div>
                        <div class="activity-content">
                            <h4><?php echo htmlspecialchars($activity['activity_title']); ?></h4>
                            <p><?php echo htmlspecialchars($activity['activity_description']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Table Section -->
    <section class="impact-table-section">
        <div class="container">
            <div class="section-header">
                <h2>Role of Income Generation from Farm and Cultural Tourism</h2>
                <p>Understanding the multi-level impact of tourism-based income generation</p>
            </div>

            <div class="table-container">
                <div class="impact-table">
                    <div class="table-header">
                        <div class="table-cell header-cell">Level</div>
                        <div class="table-cell header-cell">Role of Income Generation</div>
                    </div>

                    <?php foreach ($impacts as $impact): ?>
                    <div class="table-row">
                        <div class="table-cell level-cell">
                            <div class="level-icon">
                                <i class="<?php echo htmlspecialchars($impact['icon_class']); ?>"></i>
                            </div>
                            <span class="level-name"><?php echo htmlspecialchars($impact['level_name']); ?></span>
                        </div>
                        <div class="table-cell content-cell">
                            <?php echo htmlspecialchars($impact['impact_description']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section - STATIC -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Experience the Transformation</h2>
                <p>Join us in supporting community-based tourism that creates lasting positive impact while preserving the rich heritage and natural beauty of the Virunga region.</p>
                <div class="cta-buttons">
                    <a href="contact.php" class="cta-btn primary">
                        <i class="fas fa-envelope"></i>
                        Plan Your Visit
                    </a>
                    <a href="about.php" class="cta-btn secondary">
                        <i class="fas fa-info-circle"></i>
                        Learn More
                    </a>
                </div>
                <div class="cta-features">
                    <div class="cta-feature">
                        <i class="fas fa-seedling"></i>
                        <span>Sustainable Tourism</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-heart"></i>
                        <span>Community Impact</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-shield-alt"></i>
                        <span>Heritage Preservation</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fas fa-star"></i>
                        <span>Authentic Experiences</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- External JavaScript -->
    <script src="assets/js/heritage.js"></script>
</body>
</html>
