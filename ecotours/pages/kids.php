<?php
require_once '../admin/config/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids & Teens Activities - Virunga Ecotours</title>
    <meta name="description" content="Inclusive activities for children and teenagers in Musanze - safe, engaging, and educational experiences while parents explore the parks">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../community/assets/css/kids.css">
    
    <script src="../js/header.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <img src="../images/kids/hero/IMG-20250814-WA0043.jpg" alt="Kids Activities in Musanze" loading="lazy">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Inclusive Activities for Kids and Teens</h1>
                <p class="hero-subtitle">Safe, engaging, and educational experiences while parents explore the parks</p>
                <a href="#activities" class="hero-cta">
                    <i class="fas fa-child"></i>
                    Explore Activities
                </a>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <h2 class="section-title">Bridging the Gap for Young Explorers</h2>
                <p>Virunga Ecotours bridges the gap created by age restrictions in Volcanoes National Park by offering safe, engaging, and educational opportunities for children under 13 and 15 years old. While parents explore the gorillas and golden monkeys, kids embark on tailored programs that spark creativity, build cultural awareness, and inspire curiosity about nature.</p>
                
                <p>These experiences ensure that family travel is inclusive, meaningful, and enriching for every member. Children are not only entertained but also guided through structured activities that combine fun with learning—ranging from games and arts to conservation-inspired discovery. Parents enjoy their park adventures with peace of mind, knowing their children are equally immersed in purposeful exploration.</p>
            </div>
        </div>
    </section>

    <!-- Activities Overview Section -->
    <section id="activities" class="activities-overview-section">
        <div class="container">
            <div class="section-header">
                <h2>Kids' and Teens' Activities Overview</h2>
                <p>Structured activities that combine fun with meaningful learning experiences</p>
            </div>
            
            <div class="activities-table-container">
                <div class="activities-table">
                    <div class="table-header">
                        <div class="header-cell">Activity</div>
                        <div class="header-cell">What Kids Do</div>
                        <div class="header-cell">What They Gain</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <span>Storytelling & Games</span>
                        </div>
                        <div class="activity-description">Listen to traditional tales, play cooperative games, and join group activities.</div>
                        <div class="activity-benefits">Builds imagination, teamwork, and a sense of belonging.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <span>Arts & Painting</span>
                        </div>
                        <div class="activity-description">Create drawings inspired by wildlife, landscapes, and Rwandan culture.</div>
                        <div class="activity-benefits">Encourages creativity, self-expression, and cultural connection.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-music"></i>
                            </div>
                            <span>Dance & Music</span>
                        </div>
                        <div class="activity-description">Participate in traditional dances, drumming, and singing sessions.</div>
                        <div class="activity-benefits">Develops confidence, rhythm, and appreciation of local culture.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <span>Conservation Learning</span>
                        </div>
                        <div class="activity-description">Explore nature trails, birdwatching, and simple eco-activities.</div>
                        <div class="activity-benefits">Fosters environmental awareness and responsibility.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <span>Success Stories Sharing</span>
                        </div>
                        <div class="activity-description">Hear inspiring local stories about conservation heroes.</div>
                        <div class="activity-benefits">Inspires values of leadership, resilience, and care for nature.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-tree"></i>
                            </div>
                            <span>Buhanga Eco Park Visits</span>
                        </div>
                        <div class="activity-description">Guided discovery walks in a sacred forest with myths and legends.</div>
                        <div class="activity-benefits">Connects children to heritage, storytelling, and nature's mysteries.</div>
                    </div>
                    
                    <div class="table-row">
                        <div class="activity-name">
                            <div class="activity-icon">
                                <i class="fas fa-water"></i>
                            </div>
                            <span>Wetland Exploration</span>
                        </div>
                        <div class="activity-description">Visit Mpenge conservation wetland and learn about saltwater and birds.</div>
                        <div class="activity-benefits">Builds scientific curiosity and observational skills.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section">
        <div class="container">
            <div class="section-header">
                <h2>Activity Gallery</h2>
                <p>Capturing moments of joy, learning, and discovery</p>
            </div>
            
            <div class="gallery-grid">
                <div class="gallery-item">
                    <img src="../images/kids/HO2A2927.jpg" alt="Children listening to traditional stories" loading="lazy">
                    <div class="gallery-overlay">
                        
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="../images/kids/IMG-20250814-WA0040.jpg" alt="Kids creating art inspired by nature" loading="lazy">
                    <div class="gallery-overlay">
                        
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="../images/kids/HO2A2063.jpg" alt="Children learning traditional dances" loading="lazy">
                    <div class="gallery-overlay">
                      
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="../images/kids/HO2A1092.jpg" alt="Kids exploring nature trails" loading="lazy">
                    <div class="gallery-overlay">
                        
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="../images/kids/WhatsApp Image 2025-09-10 at 09.25.43_63dc6adf.jpg" alt="Children learning about conservation" loading="lazy">
                    <div class="gallery-overlay">
                        
                        
                    </div>
                </div>
                
                <div class="gallery-item">
                    <img src="../images/kids/IMG-20250814-WA0091.jpg" alt="Kids exploring Buhanga Eco Park" loading="lazy">
                    <div class="gallery-overlay">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Age-Based Programs Section -->
    <section class="age-programs-section">
        <div class="container">
            <div class="section-header">
                <h2>All Things to Do in Musanze by Age Group</h2>
                <p>Tailored experiences that respect age restrictions while maximizing engagement</p>
            </div>

            <div class="age-programs-grid">
                <div class="age-group-card">
                    <div class="age-header">
                        <h3>Young Kids (Under 13)</h3>
                        <span class="age-badge">Ages 5-12</span>
                    </div>
                    <div class="age-activities">
                        <div class="activity-item">
                            <i class="fas fa-book-open"></i>
                            <div>
                                <h4>Interactive Storytelling</h4>
                                <p>Moral stories and Rwandan folktales</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-palette"></i>
                            <div>
                                <h4>Nature Art</h4>
                                <p>Drawing animals and landscapes</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-music"></i>
                            <div>
                                <h4>Traditional Dance</h4>
                                <p>Fun movement and cultural play</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-seedling"></i>
                            <div>
                                <h4>Simple Conservation</h4>
                                <p>Easy nature walks and bird spotting</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="age-group-card">
                    <div class="age-header">
                        <h3>Teenagers (13-19)</h3>
                        <span class="age-badge">Ages 13+</span>
                    </div>
                    <div class="age-activities">
                        <div class="activity-item">
                            <i class="fas fa-mountain"></i>
                            <div>
                                <h4>Golden monkeys</h4>
                                <p>Available from age 13 - wildlife encounters</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-mountain"></i>
                            <div>
                                <h4>Gorilla Trekking</h4>
                                <p>Available from age 15 - wildlife encounters</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-microscope"></i>
                            <div>
                                <h4>Conservation Science</h4>
                                <p>In-depth ecological discussions</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-camera"></i>
                            <div>
                                <h4>Photography Workshops</h4>
                                <p>Capturing nature and culture</p>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-hiking"></i>
                            <div>
                                <h4>Advanced Hiking</h4>
                                <p>Challenging trails and exploration</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Visits Section -->
    <section class="educational-visits-section">
        <div class="container">
            <div class="section-header">
                <h2>Educational Visits & Experiences</h2>
                <p>Learning opportunities that inspire and educate</p>
            </div>

            <div class="visits-grid">
                <div class="visit-card">
                    <div class="visit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Ellen DeGeneres Campus</h3>
                    <p>Interactive exhibits and conservation education programs about gorillas and their habitats through scientific demonstrations.</p>
                    <ul class="visit-features">
                        <li>Interactive exhibits for all ages</li>
                        <li>Conservation education programs</li>
                        <li>Scientific demonstrations</li>
                        <li>Research insights for teenagers</li>
                    </ul>
                </div>

                <div class="visit-card">
                    <div class="visit-icon">
                        <i class="fas fa-tree"></i>
                    </div>
                    <h3>Buhanga Eco Park</h3>
                    <p>Culturally sacred forest providing exploration of biodiversity and ancient Rwandan heritage linked to coronation rituals.</p>
                    <ul class="visit-features">
                        <li>Sacred forest exploration</li>
                        <li>Ancient Rwandan heritage</li>
                        <li>Biodiversity discovery</li>
                        <li>Cultural interpretation</li>
                    </ul>
                </div>

                <div class="visit-card">
                    <div class="visit-icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <h3>Mpenge Conservation Wetland</h3>
                    <p>Unique saltwater wetland offering lessons on hydrology, ecosystem interdependence, and diverse bird habitats.</p>
                    <ul class="visit-features">
                        <li>Saltwater wetland ecosystem</li>
                        <li>Hydrology education</li>
                        <li>Bird habitat observation</li>
                        <li>Ecosystem interdependence</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Itineraries and Activities Section -->
    <section class="kids-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Kid-Friendly Tours & Activities</h2>
                <p>Specially curated experiences perfect for young explorers and their families</p>
            </div>

            <div class="tours-activities-grid">
                <?php
                // Fetch activities with "kids" or "young" in title or content
                $activities_query = "SELECT * FROM community_activities WHERE (title LIKE '%kids%' OR title LIKE '%safari%' OR title LIKE '%DeGeneres%' OR title LIKE '%buhanga%'  OR title LIKE '%ellen%' OR title LIKE '%young%' OR title LIKE '%mpenge%' OR title LIKE '%culture%' OR title LIKE '%bird%' OR title LIKE '%community%' OR title LIKE '%local%' OR title LIKE '%homestay%' OR title LIKE '%nyungwe%' OR title LIKE '%akagera%' OR title LIKE '%museum%') AND title NOT LIKE '%umuganda%' AND title NOT LIKE '%bee%' ORDER BY display_order ASC";
                $activities_result = mysqli_query($conn, $activities_query);
                
                // Fetch itineraries with "kids" or "young" (assuming tours table exists)
                $tours_query = "SELECT * FROM tours WHERE  (title LIKE '%kids%' OR title LIKE '%young%' OR title LIKE '%relig%' OR title LIKE '%safari%' OR title LIKE '%buhanga%' OR title LIKE '%mpenge%' OR title LIKE '%bird%' OR title LIKE '%DeGeneres%' OR title LIKE '%kivu%' OR title LIKE '%culture%' OR title LIKE '%ellen%' OR title LIKE '%community%' OR title LIKE '%local%' OR title LIKE '%homestay%' OR title LIKE '%nyungwe%' OR title LIKE '%akagera%' OR title LIKE '%museum%') AND title NOT LIKE '%umuganda%' AND title NOT LIKE '%bee%' AND title NOT LIKE '%coffe%' AND title NOT LIKE '%gorilla%' AND title NOT LIKE '%chimpanzee%' AND title NOT LIKE '%volcano%' ORDER BY created_at DESC";
                $tours_result = mysqli_query($conn, $tours_query);
            
               
                
                $has_content = false;
                
                // Display activities if found
                if (mysqli_num_rows($activities_result) > 0):
                    $has_content = true;
                    while ($activity = mysqli_fetch_assoc($activities_result)):
                ?>
                    <div class="tour-activity-card activity-card">
                        <div class="card-image">
                            <img src="../community/uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($activity['title']); ?>" loading="lazy">
                            <div class="card-badge activity-badge">
                                <i class="fas fa-star"></i>
                                Activity
                            </div>
                            <div class="card-duration">
                                <i class="fas fa-clock"></i>
                                <?php echo htmlspecialchars($activity['duration']); ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($activity['title']); ?></h3>
                            <p class="card-description">
                                <?php 
                                $summary = substr(strip_tags($activity['content']), 0, 120);
                                echo htmlspecialchars($summary) . (strlen(strip_tags($activity['content'])) > 120 ? '...' : '');
                                ?>
                            </p>
                            <div class="card-features">
                                <span class="feature-tag">
                                    <i class="fas fa-child"></i>
                                    Kid-Friendly
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-users"></i>
                                    Family Fun
                                </span>
                            </div>
                            <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="card-btn">
                                <i class="fas fa-arrow-right"></i>
                                Learn More
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                endif;
                
                // Display tours if found
                if (mysqli_num_rows($tours_result) > 0):
                    $has_content = true;
                    while ($tour = mysqli_fetch_assoc($tours_result)):
                ?>
                    <div class="tour-activity-card tour-card">
                        <div class="card-image">
                            <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($tour['title']); ?>" loading="lazy"
                                 onerror="this.src='../images/kids/default-tour.jpg'">
                            <div class="card-badge tour-badge">
                                <i class="fas fa-route"></i>
                                Tour
                            </div>
                            <div class="card-duration">
                                <i class="fas fa-calendar"></i>
                                <?php echo $tour['days_count']; ?> Day<?php echo $tour['days_count'] > 1 ? 's' : ''; ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <p class="card-description">
                                <?php 
                                $summary = substr(strip_tags($tour['short_description']), 0, 120);
                                echo htmlspecialchars($summary) . (strlen(strip_tags($tour['short_description'])) > 120 ? '...' : '');
                                ?>
                            </p>
                            <div class="card-features">
                                <span class="feature-tag">
                                    <i class="fas fa-child"></i>
                                    Kids Welcome
                                </span>
                                <span class="feature-tag">
                                    <i class="fas fa-users"></i>
                                    Family Tour
                                </span>
                            </div>
                            <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="card-btn">
                                <i class="fas fa-arrow-right"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                endif;
                
                // If no activities or tours found, display message
                if (!$has_content):
                ?>
                    <div class="no-content-message">
                        <div class="no-content-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>No Kids Activities or Tours Available</h3>
                        <p>We currently don't have any activities or tours specifically designed for kids or young people. Please check back soon or contact us for custom family experiences.</p>
                        <a href="contactus.php" class="contact-btn">
                            <i class="fas fa-envelope"></i>
                            Contact Us
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- View More Button -->
            <div class="view-more-container">
                <a href="activity.php" class="view-more-btn">
                    <i class="fas fa-plus"></i>
                    View All Activities
                </a>
                <a href="itenary.php" class="view-more-btn">
                    <i class="fas fa-map"></i>
                    View All Tours
                </a>
            </div>
        </div>
    </section>
     
    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Create Unforgettable Family Memories</h2>
                <p>While parents fulfill their dream of seeing gorillas, children embark on their own adventures, returning with stories just as inspiring.</p>
                <div class="cta-buttons">
                    <a href="../pages/contactus.php" class="cta-btn primary">
                        <i class="fas fa-calendar-alt"></i>
                        Book Family Experience
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
