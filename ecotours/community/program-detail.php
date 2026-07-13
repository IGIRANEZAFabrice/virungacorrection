<?php
require_once '../admin/config/connection.php';

// Get program slug from URL
$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

if (empty($slug)) {
    header('Location: programs.php');
    exit();
}

// Fetch program details
$program_query = "SELECT * FROM community_programs WHERE slug = '$slug' AND status IN ('active', 'completed')";
$program_result = mysqli_query($conn, $program_query);

if (mysqli_num_rows($program_result) === 0) {
    header('Location: programs.php');
    exit();
}

$program = mysqli_fetch_assoc($program_result);

// Parse gallery images if they exist
$gallery_images = [];
if (!empty($program['gallery'])) {
    $gallery_data = json_decode($program['gallery'], true);
    if (is_array($gallery_data)) {
        $gallery_images = $gallery_data;
    }
}

// Fetch related programs (same category or country, excluding current)
$related_query = "SELECT * FROM community_programs 
                  WHERE (category = '{$program['category']}' OR country = '{$program['country']}') 
                  AND slug != '$slug' 
                  AND status IN ('active', 'completed') 
                  ORDER BY featured DESC, created_at DESC 
                  LIMIT 3";
$related_result = mysqli_query($conn, $related_query);

// Fetch testimonials for this program
$testimonials_query = "SELECT * FROM community_testimonials 
                       WHERE program_id = {$program['id']} 
                       AND status = 'active' 
                       ORDER BY created_at DESC 
                       LIMIT 3";
$testimonials_result = mysqli_query($conn, $testimonials_query);

// Set page meta information
$page_title = !empty($program['meta_title']) ? $program['meta_title'] : $program['title'] . ' - Virunga Ecotours Community';
$page_description = !empty($program['meta_description']) ? $program['meta_description'] : $program['short_description'];
$page_image = 'assets/images/programs/' . $program['image'];
$page_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($program['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:image" content="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/community/<?php echo $page_image; ?>">
    <meta property="og:url" content="<?php echo $page_url; ?>">
    <meta property="og:type" content="article">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($program['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/community/<?php echo $page_image; ?>">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/programs.css">
    <link rel="stylesheet" href="assets/css/program-detail.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/logos/logo.png">
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Project",
        "name": "<?php echo htmlspecialchars($program['title']); ?>",
        "description": "<?php echo htmlspecialchars($program['short_description']); ?>",
        "image": "<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/community/<?php echo $page_image; ?>",
        "url": "<?php echo $page_url; ?>",
        "location": {
            "@type": "Place",
            "name": "<?php echo htmlspecialchars($program['location'] ?: ucfirst($program['country'])); ?>",
            "addressCountry": "<?php echo strtoupper($program['country'] === 'congo' ? 'CD' : ($program['country'] === 'rwanda' ? 'RW' : 'UG')); ?>"
        },
        "startDate": "<?php echo $program['date_started']; ?>",
        <?php if ($program['date_ended']): ?>
        "endDate": "<?php echo $program['date_ended']; ?>",
        <?php endif; ?>
        "organizer": {
            "@type": "Organization",
            "name": "Virunga Ecotours",
            "url": "https://virungaecotours.com"
        }
    }
    </script>
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Program Hero Section -->
    <section class="program-hero">
        <div class="program-hero-background">
            <img src="<?php echo $page_image; ?>" alt="<?php echo htmlspecialchars($program['title']); ?>" loading="eager">
            <div class="program-hero-overlay"></div>
        </div>
        <div class="container">
            <div class="program-hero-content">
                
                
                <div class="program-hero-info">
                    
                    
                    <h1><?php echo htmlspecialchars($program['title']); ?></h1>
                    <p class="program-hero-description"><?php echo htmlspecialchars($program['short_description']); ?></p>
                    
                    <div class="program-hero-stats">
                        <?php if ($program['location']): ?>
                            <div class="hero-stat">
                                <i class="fas fa-map-pin"></i>
                                <div class="stat-info">
                                    <span class="stat-text"><?php echo htmlspecialchars($program['location']); ?></span>
                                    <span class="stat-label">Location</span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="hero-stat">
                            <i class="fas fa-calendar"></i>
                            <div class="stat-info">
                                <span class="stat-text"><?php echo date('M Y', strtotime($program['date_started'])); ?></span>
                                <span class="stat-label">Started</span>
                            </div>
                        </div>
                        <div class="hero-stat">
                            <i class="fas fa-circle"></i>
                            <div class="stat-info">
                                <span class="stat-text status-<?php echo $program['status']; ?>"><?php echo ucfirst($program['status']); ?></span>
                                <span class="stat-label">Status</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Content Section -->
    <section class="program-content-section">
        <div class="container">
            <div class="program-content-grid">
                <!-- Main Content -->
                <div class="program-main-content">
                    <!-- Program Description -->
                    <div class="content-block">
                        <h2>About This Program</h2>
                        <div class="program-description">
                            <?php echo nl2br(htmlspecialchars($program['description'])); ?>
                        </div>
                    </div>

                    <!-- Program Impact -->
                    <?php if (!empty($program['impact_summary'])): ?>
                        <div class="content-block">
                            <h2>Program Impact</h2>
                            <div class="impact-content">
                                <?php echo nl2br(htmlspecialchars($program['impact_summary'])); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Gallery Section -->
                    <?php if (!empty($gallery_images)): ?>
                        <div class="content-block">
                            <h2>Program Gallery</h2>
                            <div class="program-gallery">
                                <?php foreach ($gallery_images as $index => $image): ?>
                                    <div class="gallery-item" data-index="<?php echo $index; ?>">
                                        <img src="assets/images/programs/gallery/<?php echo htmlspecialchars($image); ?>" 
                                             alt="<?php echo htmlspecialchars($program['title']); ?> - Image <?php echo $index + 1; ?>" 
                                             loading="lazy">
                                        <div class="gallery-overlay">
                                            <i class="fas fa-expand"></i>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="program-sidebar">
                    <!-- Quick Info -->
                    <div class="sidebar-block">
                        <h3>Program Details</h3>
                        <div class="program-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Country</span>
                                    <span class="detail-value"><?php echo ucfirst(htmlspecialchars($program['country'])); ?></span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-tag"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Category</span>
                                    <span class="detail-value"><?php echo htmlspecialchars($program['category']); ?></span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-calendar-start"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Start Date</span>
                                    <span class="detail-value"><?php echo date('F j, Y', strtotime($program['date_started'])); ?></span>
                                </div>
                            </div>
                            <?php if ($program['date_ended']): ?>
                                <div class="detail-item">
                                    <i class="fas fa-calendar-check"></i>
                                    <div class="detail-content">
                                        <span class="detail-label">End Date</span>
                                        <span class="detail-value"><?php echo date('F j, Y', strtotime($program['date_ended'])); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <div class="detail-content">
                                    <span class="detail-label">Beneficiaries</span>
                                    <span class="detail-value"><?php echo number_format($program['beneficiaries']); ?> people</span>
                                </div>
                            </div>
                            <?php if ($program['budget'] && $program['budget'] > 0): ?>
                                <div class="detail-item">
                                    <i class="fas fa-dollar-sign"></i>
                                    <div class="detail-content">
                                        <span class="detail-label">Budget</span>
                                        <span class="detail-value">$<?php echo number_format($program['budget'], 2); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="sidebar-block">
                        <h3>Get Involved</h3>
                        <div class="action-buttons">
                            <a href="contact.php?action=volunteer&program=<?php echo urlencode($program['slug']); ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-hands-helping"></i>
                                Volunteer for This Program
                            </a>
                            <a href="contact.php?action=donate&program=<?php echo urlencode($program['slug']); ?>" class="btn btn-outline btn-block">
                                <i class="fas fa-heart"></i>
                                Support This Program
                            </a>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="sidebar-block">
                        <h3>Share This Program</h3>
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($page_url); ?>" 
                               target="_blank" rel="noopener" class="share-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($page_url); ?>&text=<?php echo urlencode($program['title']); ?>" 
                               target="_blank" rel="noopener" class="share-btn twitter">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($page_url); ?>" 
                               target="_blank" rel="noopener" class="share-btn linkedin">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                            <a href="mailto:?subject=<?php echo urlencode($program['title']); ?>&body=<?php echo urlencode('Check out this community program: ' . $page_url); ?>" 
                               class="share-btn email">
                                <i class="fas fa-envelope"></i>
                                <span>Email</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <?php if (mysqli_num_rows($testimonials_result) > 0): ?>
        <section class="program-testimonials-section">
            <div class="container">
                <div class="section-header">
                    <h2>What People Say</h2>
                    <p>Hear from those who have been impacted by this program</p>
                </div>
                <div class="testimonials-grid">
                    <?php while ($testimonial = mysqli_fetch_assoc($testimonials_result)): ?>
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="testimonial-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'active' : ''; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="testimonial-message">"<?php echo htmlspecialchars($testimonial['message']); ?>"</p>
                            </div>
                            <div class="testimonial-author">
                                <?php if ($testimonial['testimonial_image']): ?>
                                    <img src="assets/images/testimonials/<?php echo htmlspecialchars($testimonial['testimonial_image']); ?>"
                                         alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="author-image">
                                <?php else: ?>
                                    <div class="author-image-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="author-info">
                                    <h4 class="author-name"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                    <?php if ($testimonial['role']): ?>
                                        <p class="author-role"><?php echo htmlspecialchars($testimonial['role']); ?></p>
                                    <?php endif; ?>
                                    <?php if ($testimonial['location']): ?>
                                        <p class="author-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo htmlspecialchars($testimonial['location']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Related Programs Section -->
    <?php if (mysqli_num_rows($related_result) > 0): ?>
        <section class="related-programs-section">
            <div class="container">
                <div class="section-header">
                    <h2>Related Programs</h2>
                    <p>Explore other programs in <?php echo htmlspecialchars($program['category']); ?> or <?php echo ucfirst(htmlspecialchars($program['country'])); ?></p>
                </div>
                <div class="related-programs-grid">
                    <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                        <div class="related-program-card">
                            <div class="related-program-image">
                                <img src="assets/images/programs/<?php echo htmlspecialchars($related['image']); ?>"
                                     alt="<?php echo htmlspecialchars($related['title']); ?>" loading="lazy">
                                <div class="related-program-overlay">
                                    <div class="program-badges">
                                        <span class="program-country">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo ucfirst(htmlspecialchars($related['country'])); ?>
                                        </span>
                                        <?php if ($related['featured']): ?>
                                            <span class="program-featured">
                                                <i class="fas fa-star"></i>
                                                Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="related-program-content">
                                <div class="program-meta">
                                    <span class="program-category">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($related['category']); ?>
                                    </span>
                                    <span class="program-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('M Y', strtotime($related['date_started'])); ?>
                                    </span>
                                </div>
                                <h3><?php echo htmlspecialchars($related['title']); ?></h3>
                                <p><?php echo htmlspecialchars($related['short_description']); ?></p>
                                <div class="program-stats">
                                    <div class="stat">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($related['beneficiaries']); ?> beneficiaries</span>
                                    </div>
                                </div>
                                <a href="program-detail.php?slug=<?php echo htmlspecialchars($related['slug']); ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i>
                                    Learn More
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Call to Action Section -->
    <section class="program-cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Ready to Make a Difference?</h2>
                    <p>Join us in creating positive change in the Virunga region. Whether through volunteering, donations, or spreading awareness, every contribution matters.</p>
                </div>
                <div class="cta-actions">
                    <a href="contact.php?action=volunteer&program=<?php echo urlencode($program['slug']); ?>" class="btn btn-primary btn-large">
                        <i class="fas fa-hands-helping"></i>
                        Volunteer Now
                    </a>
                    <a href="programs.php" class="btn btn-outline btn-large">
                        <i class="fas fa-arrow-left"></i>
                        View All Programs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="gallery-modal">
        <div class="gallery-modal-content">
            <span class="gallery-modal-close">&times;</span>
            <div class="gallery-modal-image-container">
                <img id="galleryModalImage" src="" alt="">
                <button class="gallery-nav-btn gallery-prev" id="galleryPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="gallery-nav-btn gallery-next" id="galleryNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="gallery-modal-info">
                <h3 id="galleryModalTitle"><?php echo htmlspecialchars($program['title']); ?></h3>
                <p id="galleryModalCounter"></p>
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/community.js"></script>
    <script src="assets/js/program-detail.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
