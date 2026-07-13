<?php
require_once '../admin/config/connection.php';

// Fetch activities from the database
$activities_query = "SELECT * FROM community_activities WHERE status = 'active' AND is_active = 1 ORDER BY display_order ASC";
$activities_result = mysqli_query($conn, $activities_query);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    <body>
<?php include 'includes/header.php'; ?>

<!-- Page Header Section -->
<section class="page-header">
    <div class="page-header-background">
        <img src="../images/hero/Culture.jpg" alt="Community Activities" loading="lazy">
        <div class="page-header-overlay"></div>
    </div>
    <div class="container">
        <div class="page-header-content">
            <h1 style="color: #ffffff;">Beyond The Park</h1>
            <p style="color: #ffffff;">Authentic experiences that directly benefit local communities</p>
        </div>
    </div>
</section>

<!-- Introduction Section -->
<section class="intro-section">
    <div class="intro-container">
        <h2 class="intro-title">Beyond the Park Experience</h2>
        <p class="intro-subtitle">Discover diverse activities that extend your adventure beyond wildlife viewing</p>
        
        <div class="intro-description">
            <p>While the national park offers incredible wildlife experiences, the Virunga region has much more to explore. These carefully curated day activities provide authentic local experiences that anyone can enjoy, regardless of fitness level or budget. Each activity is designed to be accessible, affordable, and completed within a single day.</p>
            
            <p>From cultural workshops to scenic adventures, these experiences offer a perfect complement to your park visit. They're ideal for rest days, weather delays, or simply when you want to discover the rich heritage and natural beauty that exists beyond the park boundaries. Best of all, every activity directly supports local families and initiatives.</p>
        </div>
        
        <div class="intro-benefits">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="benefit-title">One-Day Adventures</h3>
                <p class="benefit-description">All activities are designed to fit comfortably within a single day, making them perfect for flexible scheduling and spontaneous exploration.</p>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3 class="benefit-title">Accessible & Affordable</h3>
                <p class="benefit-description">These experiences are priced to be accessible to all travelers, offering great value while ensuring fair compensation for local guides and participants.</p>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="benefit-title">Meaningful Impact</h3>
                <p class="benefit-description">Every activity creates positive economic opportunities for local families and contributes to sustainable livelihoods in the region.</p>
            </div>
        </div>
    </div>
</section>

<!-- Beyond the Park Experience Section -->
<section class="beyond-park-section">
    <div class="container">
        <div class="beyond-park-content">
            <div class="beyond-park-header">
                <h2 class="beyond-park-title">Beyond the Park Experience Around the Virunga Massif</h2>
            </div>

            <div class="beyond-park-description">
                <p>The "Beyond the Park Experience" was created to extend the meaning of travel in the Virunga Massif, offering guests a deeper connection with the people and landscapes that surround the national parks. Rather than limiting exploration to protected areas, this program opens the doors of nearby communities, showcasing their traditions, skills, and everyday realities. Designed by Virunga Ecotours through community-based tourism, it responds to the need for experiences that are enriching, inclusive, and sustainable in both cultural and social terms. Five central reasons guide the foundation of this initiative.</p>
            </div>

            <div class="beyond-park-reasons">
                <div class="reason-item">
                    <div class="reason-number">1</div>
                    <p>It was developed to provide opportunities for travelers who may not access gorilla trekking due to age or financial limitations, ensuring they can still enjoy meaningful encounters in the region.</p>
                </div>

                <div class="reason-item">
                    <div class="reason-number">2</div>
                    <p>It emphasizes cultural immersion, allowing guests to engage with storytelling, music, and ancestral practices that shape local identity.</p>
                </div>

                <div class="reason-item">
                    <div class="reason-number">3</div>
                    <p>It encourages shared benefits between visitors and residents, creating direct economic opportunities that strengthen community livelihoods.</p>
                </div>

                <div class="reason-item">
                    <div class="reason-number">4</div>
                    <p>It fosters intergenerational learning by connecting guests with youth and elders who serve as guardians of heritage and knowledge.</p>
                </div>

                <div class="reason-item">
                    <div class="reason-number">5</div>
                    <p>It enriches the overall journey, offering activities that balance the thrill of nature with the warmth of human connection.</p>
                </div>
            </div>

            <div class="activities-overview">
                <h3 class="activities-overview-title">Activities</h3>
                <p>The "Beyond the Park Experience" includes a wide variety of interactive activities tailored to travelers of all ages and interests. Guests may take part in village walks, traditional cooking sessions, banana beer brewing, pottery making, or basket weaving alongside skilled artisans. Storytelling evenings bring to life myths and memories of the Virunga highlands, while dance and drumming performances immerse participants in the rhythm of local culture. Visitors may also join guided agricultural experiences—such as harvesting, planting, or preparing meals from farm-fresh produce—or explore scenic lakeside settings through boat rides and birdwatching. Each activity highlights the hospitality of the communities, creating genuine bonds that extend far beyond a typical park visit.</p>
            </div>
        </div>
    </div>
</section>

<!-- Activities Section -->
<section class="activities-section">
    <div class="container">
        <div class="section-header">
            <h2>Explore Our Activities</h2>
            <p>Immerse yourself in authentic experiences that make a difference</p>
        </div>
        
        <div class="activities-grid">
            <?php if (mysqli_num_rows($activities_result) > 0): ?>
                <?php while ($activity = mysqli_fetch_assoc($activities_result)): ?>
                    <div class="activity-card">
                        <div class="activity-image">
                            <img src="../community/uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($activity['title']); ?>" loading="lazy">
                            <div class="activity-overlay">
                                <div class="activity-duration">
                                    <i class="fas fa-clock"></i>
                                    <?php echo htmlspecialchars($activity['duration']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="activity-content">
                            <h3 class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></h3>
                            <p class="activity-description">
                                <?php 
                                // Display a summary of the content
                                $summary = substr(strip_tags($activity['content']), 0, 150);
                                echo htmlspecialchars($summary) . (strlen(strip_tags($activity['content'])) > 150 ? '...' : '');
                                ?>
                            </p>
                            <div class="activity-meta">
                                <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="activity-link">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-activities">
                    <i class="fas fa-info-circle"></i>
                    <p>No activities available at the moment. Please check back soon!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
  
<script>
    // Simple animation for benefits
    document.addEventListener('DOMContentLoaded', function() {
        const benefitItems = document.querySelectorAll('.benefit-item');
        
        benefitItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 200 * index);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
</body>
<?php
// Close database connection
mysqli_close($conn);
?>