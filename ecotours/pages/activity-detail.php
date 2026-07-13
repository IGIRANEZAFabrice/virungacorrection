<?php
require_once '../admin/config/connection.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: activity.php');
    exit;
}

$activity_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch the specific activity
$activity_query = "SELECT * FROM community_activities WHERE id = '$activity_id' AND status = 'active' AND is_active = 1";
$activity_result = mysqli_query($conn, $activity_query);

// Check if activity exists
if (mysqli_num_rows($activity_result) == 0) {
    header('Location: activity.php');
    exit;
}

$activity = mysqli_fetch_assoc($activity_result);

// Fetch related activities (excluding current one)
$related_query = "SELECT id, title, image, duration FROM community_activities 
                 WHERE id != '$activity_id' AND status = 'active' AND is_active = 1 
                 ORDER BY display_order ASC LIMIT 3";
$related_result = mysqli_query($conn, $related_query);

// Function to format activity content with proper line breaks and structure
function formatActivityContent($content) {
    // Convert line breaks to HTML
    $content = nl2br($content);
    
    // Convert bullet points (•) to proper HTML lists
    $content = preg_replace('/•\s*(.+?)(?=\n|•|$)/s', '<li>$1</li>', $content);
    
    // Wrap consecutive list items in <ul> tags
    $content = preg_replace('/(<li>.*?<\/li>)+/s', '<ul class="activity-bullet-list">$0</ul>', $content);
    
    // Add spacing around paragraphs
    $content = preg_replace('/\n\n+/', '</p><p>', $content);
    
    // Wrap content in paragraphs if not already wrapped
    if (!preg_match('/<p>/', $content)) {
        $content = '<p>' . $content . '</p>';
    }
    
    // Clean up any empty paragraphs
    $content = str_replace('<p></p>', '', $content);
    
    return $content;
}
?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="../community/assets/css/impact.css">
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../community/assets/css/about.css">
    <link rel="stylesheet" href="../community/assets/css/activity.css">
    <script src="../js/header.js"></script>
    <link rel="stylesheet" href="../community/assets/css/community.css">
<?php include 'includes/header.php'; ?>

<!-- Activity Hero Section -->
<section class="activity-hero">
    <div class="activity-hero-background">
        <img src="../community/uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>" 
             alt="<?php echo htmlspecialchars($activity['title']); ?>" loading="lazy">
        <div class="activity-hero-overlay"></div>
    </div>
    <div class="container">
        <div class="activity-hero-content">
            <nav class="breadcrumb">
                <a href="index.php">Community</a>
                <span class="separator"><i class="fas fa-chevron-right"></i></span>
                <a href="activity.php">Activities</a>
                <span class="separator"><i class="fas fa-chevron-right"></i></span>
                <span class="current"><?php echo htmlspecialchars($activity['title']); ?></span>
            </nav>
            <h1><?php echo htmlspecialchars($activity['title']); ?></h1>
            
            <div class="activity-meta-badges">
                <?php if (!empty($activity['duration'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-clock"></i>
                    <span><?php echo htmlspecialchars($activity['duration']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($activity['location'])): ?>
                <div class="meta-badge">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo htmlspecialchars($activity['location']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Activity Content Section -->
<section class="activity-content-section">
    <div class="container">
        <div class="activity-content-wrapper">
            <div class="activity-main-content">
                <div class="activity-description">
                    <?php 
                    // Format the content with proper line breaks and bullet points
                    $formatted_content = formatActivityContent($activity['content']);
                    echo $formatted_content;
                    ?>
                </div>
            </div>
            
            <div class="activity-sidebar">
                <div class="booking-card">
                    <h3>Book This Activity</h3>
                    
                    
                    
                    <a href="contact.php?action=book&activity=<?php echo urlencode($activity['title']); ?>" class="book-button">
                        <i class="fas fa-calendar-check"></i>
                        Book Now
                    </a>
                    
                    <div class="booking-note">
                        <i class="fas fa-info-circle"></i>
                        <p>For group bookings or custom dates, please <a href="contact.php">contact us</a> directly.</p>
                    </div>
                </div>
                
                <?php if (!empty($activity['requirements'])): ?>
                <div class="requirements-card">
                    <h3>Requirements</h3>
                    <div class="requirements-content">
                        <?php echo $activity['requirements']; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="share-card">
                    <h3>Share This Activity</h3>
                    <div class="social-share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-button facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode('Check out this amazing community activity: ' . $activity['title']); ?>" target="_blank" class="share-button twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode('Check out this amazing community activity: ' . $activity['title'] . ' - https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-button whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode('Check out this community activity: ' . $activity['title']); ?>&body=<?php echo urlencode('I thought you might be interested in this community activity: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-button email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Activities Section -->
<?php if (mysqli_num_rows($related_result) > 0): ?>
<section class="related-activities-section">
    <div class="container">
        <div class="section-header">
            <h2>Related Activities</h2>
            <p>Discover more community experiences</p>
        </div>
        
        <div class="related-activities-grid">
            <?php while ($related = mysqli_fetch_assoc($related_result)): ?>
                <div class="activity-card">
                    <div class="activity-image">
                        <img src="../community/uploads/activities/<?php echo htmlspecialchars($related['image']); ?>" 
                             alt="<?php echo htmlspecialchars($related['title']); ?>" loading="lazy">
                        <div class="activity-overlay">
                            <div class="activity-duration">
                                <i class="fas fa-clock"></i>
                                <?php echo htmlspecialchars($related['duration']); ?>
                            </div>
                        </div>
                    </div>
                    <div class="activity-content">
                        <h3 class="activity-title" style="color:#ffffff;"><?php echo htmlspecialchars($related['title']); ?></h3>
                        <div class="activity-meta">
                           
                            <a href="activity-detail.php?id=<?php echo $related['id']; ?>" class="activity-link">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <div class="view-all-activities">
            <a href="activity.php" class="view-all-button">
                <i class="fas fa-th-large"></i>
                View All Activities
            </a>
        </div>
    </div>
</section>
<?php endif; ?>


<style>
    /* Activity Detail Page Styles */
    .activity-hero {
        position: relative;
        height: 60vh;
        min-height: 400px;
        max-height: 600px;
        color: #fff;
        margin-bottom: 50px;
    }
    
    .activity-hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
    
    .activity-hero-background img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transform: scale(1.02);
        transition: transform 0.3s ease;
    }
    
    .activity-hero:hover .activity-hero-background img {
        transform: scale(1.05);
    }
    
    .activity-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
    }
    
    .activity-hero-content {
        position: relative;
        padding-top: 200px;
        max-width: 800px;
    }
    
    .breadcrumb {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }
    
    .breadcrumb a {
        color: #fff;
        text-decoration: none;
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    .breadcrumb a:hover {
        opacity: 1;
        text-decoration: underline;
    }
    
    .breadcrumb .separator {
        margin: 0 10px;
        font-size: 0.7rem;
        opacity: 0.7;
    }
    
    .breadcrumb .current {
        opacity: 0.7;
    }
    
    .activity-hero-content h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        color: #fff;
    }
    
    .activity-meta-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 20px;
    }
    
    .meta-badge {
        display: inline-flex;
        align-items: center;
        background-color: rgba(255,255,255,0.2);
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 0.9rem;
    }
    
    .meta-badge i {
        margin-right: 8px;
    }
    

    /* Content Section Styles */
    .activity-content-section {
        padding: 0 0 80px;
    }
    
    .activity-content-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
    }
    
    .activity-main-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .activity-description {
        padding: 30px;
        /* Override the line-clamp from community.css to show full content */
        display: block !important;
        -webkit-line-clamp: unset !important;
        -webkit-box-orient: unset !important;
        overflow: visible !important;
        max-height: none !important;
        height: auto !important;
    }
    
    .activity-description p {
        margin-bottom: 20px;
        line-height: 1.8;
        color: #333;
    }
    
    .activity-description ul {
        margin: 20px 0;
        padding-left: 20px;
    }
    
    .activity-description li {
        margin-bottom: 12px;
        line-height: 1.6;
        color: #555;
    }
    
    .activity-bullet-list {
        list-style: none;
        padding-left: 0;
        margin: 25px 0;
    }
    
    .activity-bullet-list li {
        position: relative;
        padding-left: 25px;
        margin-bottom: 15px;
        line-height: 1.7;
        color: #444;
    }
    
    .activity-bullet-list li:before {
        content: '•';
        position: absolute;
        left: 0;
        color: #8D6E63;
        font-size: 1.2rem;
        font-weight: bold;
        line-height: 1.4;
    }
    
    .activity-description h3,
    .activity-description h4 {
        color: #3E2723;
        margin: 25px 0 15px 0;
        font-weight: 600;
    }
    
    .activity-description h3 {
        font-size: 1.4rem;
    }
    
    .activity-description h4 {
        font-size: 1.2rem;
    }
    
    .activity-description img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .activity-highlights,
    .activity-includes,
    .activity-impact {
        padding: 30px;
        border-top: 1px solid #eee;
    }
    
    .activity-highlights h3,
    .activity-includes h3,
    .activity-impact h3 {
        font-size: 1.5rem;
        color: #3E2723;
        margin-bottom: 20px;
        position: relative;
        padding-bottom: 10px;
    }
    
    .activity-highlights h3:after,
    .activity-includes h3:after,
    .activity-impact h3:after {
        content: '';
        position: absolute;
        width: 50px;
        height: 3px;
        background-color: #8D6E63;
        bottom: 0;
        left: 0;
    }
    
    .highlights-content ul,
    .includes-content ul {
        padding-left: 20px;
    }
    
    .highlights-content li,
    .includes-content li {
        margin-bottom: 10px;
        line-height: 1.6;
    }
    
    .impact-list {
        list-style: none;
        padding: 0;
        margin: 20px 0 0;
    }
    
    .impact-list li {
        display: flex;
        align-items: flex-start;
        margin-bottom: 15px;
    }
    
    .impact-list li i {
        color: #8D6E63;
        font-size: 1.2rem;
        margin-right: 15px;
        margin-top: 3px;
    }
    
    /* Sidebar Styles */
    .activity-sidebar > div {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }
    
    .booking-card {
        padding: 30px;
    }
    
    .booking-card h3 {
        font-size: 1.5rem;
        color: #3E2723;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .book-button {
        display: block;
        background-color: #8D6E63;
        color: #fff;
        text-align: center;
        padding: 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .book-button:hover {
        background-color: #6D4C41;
        transform: translateY(-3px);
    }
    
    .book-button i {
        margin-right: 8px;
    }
    
    .booking-note {
        display: flex;
        align-items: flex-start;
        font-size: 0.9rem;
        color: #666;
        background-color: #f9f7f4;
        padding: 15px;
        border-radius: 8px;
    }
    
    .booking-note i {
        color: #8D6E63;
        margin-right: 10px;
        margin-top: 3px;
    }
    
    .booking-note a {
        color: #8D6E63;
        text-decoration: none;
        font-weight: 600;
    }
    
    .booking-note a:hover {
        text-decoration: underline;
    }
    
    .requirements-card,
    .share-card {
        padding: 30px;
    }
    
    .requirements-card h3,
    .share-card h3 {
        font-size: 1.3rem;
        color: #3E2723;
        margin-bottom: 20px;
        text-align: center;
    }
    
    .requirements-content ul {
        padding-left: 20px;
    }
    
    .requirements-content li {
        margin-bottom: 10px;
        line-height: 1.6;
    }
    
    .social-share-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    
    .share-button {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .share-button:hover {
        transform: translateY(-3px);
    }
    
    .share-button.facebook {
        background-color: #3b5998;
    }
    
    .share-button.twitter {
        background-color: #1da1f2;
    }
    
    .share-button.whatsapp {
        background-color: #25d366;
    }
    
    .share-button.email {
        background-color: #8D6E63;
    }
    
    /* Related Activities Section */
    .related-activities-section {
        padding: 80px 0;
        background-color: #f9f7f4;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .section-header h2 {
        font-size: 2.2rem;
        color: #3E2723;
        margin-bottom: 10px;
    }
    
    .section-header p {
        font-size: 1.1rem;
        color: #666;
    }
    
    .related-activities-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }
    
    .activity-card {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .activity-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.12);
    }
    
    .activity-image {
        height: 200px;
        overflow: hidden;
        position: relative;
    }
    
    .activity-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .activity-card:hover .activity-image img {
        transform: scale(1.05);
    }
    
    .activity-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
    }
    
    .activity-duration {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: rgba(141, 110, 99, 0.9);
        color: #fff;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .activity-content {
        padding: 20px;
    }
    
    .activity-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #3E2723;
        margin-bottom: 15px;
    }
    
    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    

    .activity-link {
        display: inline-block;
        padding: 8px 15px;
        background-color: #8D6E63;
        color: #fff;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }
    
    .activity-link:hover {
        background-color: #6D4C41;
    }
    
    .view-all-activities {
        text-align: center;
        margin-top: 40px;
    }
    
    .view-all-button {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        background-color: transparent;
        color: #8D6E63;
        border: 2px solid #8D6E63;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .view-all-button:hover {
        background-color: #8D6E63;
        color: #fff;
    }
    
    .view-all-button i {
        margin-right: 8px;
    }
    
    /* CTA Section Styles */
    .cta-section {
        padding: 80px 0;
        background-color: #5D4037;
        color: #fff;
        text-align: center;
    }
    
    .cta-content {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .cta-content h2 {
        font-size: 2.2rem;
        margin-bottom: 20px;
    }
    
    .cta-content p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 25px;
        border-radius: 4px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn i {
        margin-right: 8px;
    }
    
    .btn-primary {
        background-color: #8D6E63;
        color: #fff;
    }
    
    .btn-primary:hover {
        background-color: #A1887F;
        transform: translateY(-3px);
    }
    
    .btn-secondary {
        background-color: transparent;
        color: #fff;
        border: 2px solid #fff;
    }
    
    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateY(-3px);
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .activity-content-wrapper {
            grid-template-columns: 1fr;
        }
        
        .related-activities-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .activity-hero-content h1 {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .activity-hero {
            height: 50vh;
        }
        
        .activity-hero-content {
            padding-top: 150px;
        }
        
        .activity-hero-content h1 {
            font-size: 2rem;
        }
        
        .meta-badge {
            padding: 6px 12px;
            font-size: 0.8rem;
        }
        
        .activity-description,
        .activity-highlights,
        .activity-includes,
        .activity-impact {
            padding: 20px;
        }
        
        .related-activities-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .cta-content h2 {
            font-size: 1.8rem;
        }
        
        .cta-content p {
            font-size: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .activity-hero {
            height: 40vh;
            min-height: 300px;
        }
        
        .activity-hero-content {
            padding-top: 120px;
        }
        
        .activity-hero-content h1 {
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        
        .activity-meta-badges {
            gap: 10px;
        }
        
        .meta-badge {
            padding: 5px 10px;
            font-size: 0.75rem;
        }
        
        .activity-content-section,
        .related-activities-section,
        .cta-section {
            padding: 50px 0;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<?php include 'includes/footer.php'; ?>

<?php
// Close database connection
mysqli_close($conn);
?>