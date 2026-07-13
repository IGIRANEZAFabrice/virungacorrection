<?php require_once '../admin/config/connection.php';
// Load main page (first row or create empty defaults)
$page = null;
$page_q = mysqli_query($conn, "SELECT id, section_description FROM impact_page ORDER BY id ASC LIMIT 1");
if ($page_q && mysqli_num_rows($page_q) > 0) {
    $page = mysqli_fetch_assoc($page_q);
} else {
    $page = [ 'id' => null, 'section_description' => '' ];
}

$page_id = $page['id'];

// Load gallery, cards, teaching programs for this page
$gallery = [];
if ($page_id) {
    $gq = mysqli_query($conn, "SELECT id, image, title, description FROM impact_gallery WHERE page_id = " . (int)$page_id . " ORDER BY id ASC");
    if ($gq) { while ($r = mysqli_fetch_assoc($gq)) { $gallery[] = $r; } }
}

$cards = [];
if ($page_id) {
    $cq = mysqli_query($conn, "SELECT id, image, title, description FROM impact_cards WHERE page_id = " . (int)$page_id . " ORDER BY id ASC");
    if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }
}

$programs = [];
if ($page_id) {
    $pq = mysqli_query($conn, "SELECT id, image, title, description, features FROM teaching_programs WHERE page_id = " . (int)$page_id . " ORDER BY id ASC");
    if ($pq) { while ($r = mysqli_fetch_assoc($pq)) { $programs[] = $r; } }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impact</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    <link rel="stylesheet" href="assets/css/impact.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
        <section class="page-header">
            <div class="page-header-background">
                <img src="uploads/impact/hero.jpg" alt="About Virunga Ecotours Community" loading="lazy">
                <div class="page-header-overlay"></div>
            </div>
            <div class="container">
                <div class="page-header-content">
                    <nav class="breadcrumb">
                        <a href="index.php">Community</a>
                        <span class="separator"><i class="fas fa-chevron-right"></i></span>
                        <span class="current">Our Impact</span>
                    </nav>
                    <h1 style="color: #ffffff;">Our Impact on Community</h1>
                    <p style="color: #ffffff;">Empowering communities through traditional crafts and cultural preservation.</p>
                </div>
            </div>
        </section>
        <!-- Hero Section -->
       

        <!-- Impact Section -->
        <section class="impact-section">
            <div class="container">
                <h2 class="section-title">Community Impact</h2>
                <p class="section-description"><?php echo nl2br(htmlspecialchars($page['section_description'] ?? '')); ?></p>
                
                <!-- Image Gallery -->
                <div class="image-gallery">
                    <?php if (count($gallery) === 0): ?>
                        <div class="programs-intro">No gallery items yet.</div>
                    <?php else: ?>
                        <?php foreach ($gallery as $g): ?>
                    <div class="gallery-item">
                            <img src="uploads/impact/<?php echo htmlspecialchars($g['image']); ?>" alt="<?php echo htmlspecialchars($g['title']); ?>" class="gallery-image">
                        <div class="gallery-overlay">
                                <h3><?php echo htmlspecialchars($g['title']); ?></h3>
                                <p><?php echo htmlspecialchars($g['description']); ?></p>
                    </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Impact Cards -->
                <div class="impact-cards">
                    <?php if (count($cards) === 0): ?>
                        <div class="programs-intro">No impact cards yet.</div>
                    <?php else: ?>
                        <?php foreach ($cards as $c): ?>
                    <div class="impact-card">
                            <img src="uploads/impact/<?php echo htmlspecialchars($c['image']); ?>" alt="<?php echo htmlspecialchars($c['title']); ?>" class="card-image">
                        <div class="card-content">
                                <h3><?php echo htmlspecialchars($c['title']); ?></h3>
                                <p><?php echo htmlspecialchars($c['description']); ?></p>
                    </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Mobile Carousel -->
                <div class="mobile-carousel">
                    <div class="carousel-container">
                        <?php $i=0; foreach ($gallery as $g): ?>
                        <div class="carousel-slide <?php echo $i===0 ? 'active' : ''; ?>">
                            <img src="uploads/impact/<?php echo htmlspecialchars($g['image']); ?>" alt="<?php echo htmlspecialchars($g['title']); ?>" class="carousel-image">
                            <div class="carousel-content">
                                <h3><?php echo htmlspecialchars($g['title']); ?></h3>
                                <p><?php echo htmlspecialchars($g['description']); ?></p>
                            </div>
                        </div>
                        <?php $i++; endforeach; ?>
                    </div>
                    <div class="carousel-dots">
                        <?php for ($d = 0; $d < count($gallery); $d++): ?>
                            <span class="dot <?php echo $d===0 ? 'active' : ''; ?>" data-slide="<?php echo $d; ?>"></span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            
        </section>
         <!-- Project Outcomes Section -->
         <section class="outcomes-section" id="project-outcomes">
            <div class="container">
                <h2 class="section-title">Project Outcomes</h2>
                <p class="section-description">We work alongside community members to preserve cultural heritage while expanding economic opportunities. Our programs directly support women, youth and local artisans, and indirectly benefit households, schools, and the wider tourism value chain.</p>
                
                <div class="beneficiaries-grid">
                    <div class="beneficiary-card">
                        <div class="beneficiary-icon"><i class="fas fa-users"></i></div>
                        <h3>Direct Beneficiaries</h3>
                        <p>Women artisans, young trainees, local guides, and educators who participate in our hands-on workshops and training programs.</p>
                    </div>
                    <div class="beneficiary-card">
                        <div class="beneficiary-icon"><i class="fas fa-people-group"></i></div>
                        <h3>Indirect Beneficiaries</h3>
                        <p>Families, community-based organizations, schools, and local businesses strengthened by increased skills, income, and cultural visibility.</p>
                    </div>
                    
                </div>

                <h3 class="subsection-title"><i class="fas fa-hand-holding-heart"></i> <span>What We Do</span></h3>
                 <div class="activities-grid">
                    <div class="activity-card">
                        <div class="activity-icon"><i class="fas fa-feather"></i></div>
                        <h4>Poetry</h4>
                        <p>We nurture creative writing and spoken-word that documents local stories, values, and traditions, helping youth build voice and confidence.</p>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon"><i class="fas fa-scissors"></i></div>
                        <h4>Sewing</h4>
                        <p>Our sewing labs teach practical garment and accessory making, blending traditional techniques with modern design for market-ready products.</p>
                    </div>
                    <div class="activity-card">
                        <div class="activity-icon"><i class="fas fa-cubes"></i></div>
                        <h4>AR</h4>
                        <p>We explore accessible augmented-reality storytelling to interpret culture and crafts, creating engaging learning and visitor experiences.</p>
                    </div>
                </div>

                <ul class="outcomes-highlights">
                    <li><i class="fas fa-check-circle"></i><span>Preserved and showcased cultural knowledge through creative media and craft.</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Strengthened livelihoods with marketable skills and artisan product development.</span></li>
                    <li><i class="fas fa-check-circle"></i><span>Improved youth engagement, confidence, and digital creativity.</span></li>
                </ul>
            </div>
        </section>

        <!-- Parallax Scrolling Section -->
        <section class="parallax-section">
            <div class="parallax-overlay">
                <div class="parallax-content">
                    <h2>Preserving Heritage, Empowering Futures</h2>
                    <p style="color: #ffffff;">Empowering you to give back to communities around the world.</p>
                </div>
            </div>
        </section>

        <!-- Teaching Section -->
        <section class="teaching-section">
            <div class="container">
                <h2 class="section-title">What We Teach</h2>
                <p class="section-description">We provide comprehensive training programs that blend traditional knowledge with modern skills, empowering our community members with valuable expertise.</p>
                
                <div class="teaching-cards">
                    <?php foreach ($programs as $p): ?>
                    <div class="teaching-card">
                        <img src="uploads/impact/<?php echo htmlspecialchars($p['image']); ?>" alt="<?php echo htmlspecialchars($p['title']); ?>" class="teaching-image">
                        <div class="teaching-content">
                            <div class="teaching-icon"><i class="fas fa-book-open"></i></div>
                            <h3><?php echo htmlspecialchars($p['title']); ?></h3>
                            <p><?php echo htmlspecialchars($p['description']); ?></p>
                            <?php 
                                $features = array_filter(array_map('trim', explode("\n", (string)($p['features'] ?? ''))));
                                if (count($features) > 0):
                            ?>
                            <ul class="teaching-features">
                                <?php foreach ($features as $f): ?>
                                    <li><?php echo htmlspecialchars($f); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
       
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/impact.js"></script>
</body>
</html>

<?php mysqli_close($conn); ?>

