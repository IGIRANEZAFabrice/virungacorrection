<?php
require_once('../admin/config/connection.php');

// Compatibility function for get_result() - works with all MySQLi configurations
function getStmtResult($stmt) {
    if (method_exists($stmt, 'get_result')) {
        return $stmt->get_result();
    } else {
        // Fallback for servers without mysqlnd support
        $result = new stdClass();
        $result->data = [];
        $result->num_rows = 0;
        $result->current_index = 0;

        // Get metadata
        $metadata = $stmt->result_metadata();
        if ($metadata) {
            $fields = [];
            while ($field = $metadata->fetch_field()) {
                $fields[] = $field->name;
            }

            // Bind results
            $values = [];
            $refs = [];
            foreach ($fields as $field) {
                $refs[] = &$values[$field];
            }
            call_user_func_array([$stmt, 'bind_result'], $refs);

            // Fetch all rows
            while ($stmt->fetch()) {
                $row = [];
                foreach ($fields as $field) {
                    $row[$field] = $values[$field];
                }
                $result->data[] = $row;
            }
            $result->num_rows = count($result->data);
        }

        return $result;
    }
}

// Helper function to fetch associative array from custom result object
function fetchAssoc($result) {
    if (is_object($result) && isset($result->data)) {
        // Custom stdClass result object
        if ($result->current_index < count($result->data)) {
            return $result->data[$result->current_index++];
        }
        return null;
    } elseif (is_object($result) && method_exists($result, 'fetch_assoc')) {
        // Real MySQLi result object
        return $result->fetch_assoc();
    }
    return null;
}

// Get attraction ID from URL
$attraction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($attraction_id <= 0) {
    // Redirect to home page if no valid ID
    header('Location: ../index.php');
    exit();
}

// Fetch attraction data
$attraction_query = "SELECT ha.*, ad.description, ad.location, ad.activities, ad.external_link
                    FROM home_attractions ha
                    LEFT JOIN attraction_details ad ON ha.id = ad.attraction_id
                    WHERE ha.id = ?";
$stmt = $conn->prepare($attraction_query);
$stmt->bind_param("i", $attraction_id);
$stmt->execute();
$result = getStmtResult($stmt);

if ($result->num_rows === 0) {
    // Redirect if attraction not found
    header('Location: ../index.php');
    exit();
}

$attraction = fetchAssoc($result);

// Fetch gallery images
$gallery_query = "SELECT * FROM attraction_gallery WHERE attraction_id = ? ORDER BY display_order ASC";
$gallery_stmt = $conn->prepare($gallery_query);
$gallery_stmt->bind_param("i", $attraction_id);
$gallery_stmt->execute();
$gallery_result = getStmtResult($gallery_stmt);
$gallery_images = [];

while ($image = fetchAssoc($gallery_result)) {
    $gallery_images[] = $image;
}

// Clean image path
function cleanImagePath($path) {
    return preg_replace('/^\.\.\/\.\.\//', '', $path);
}

// Format activities as array
$activities = !empty($attraction['activities']) ? explode(',', $attraction['activities']) : [];

// Search for related itineraries/tours based on attraction title
$related_tours = [];
if (!empty($attraction['title'])) {
    // Extract keywords from attraction title (remove common words and get main keywords)
    $title_words = explode(' ', strtolower($attraction['title']));
    $keywords = array_filter($title_words, function($word) {
        $common_words = ['the', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'national', 'park'];
        return strlen($word) > 3 && !in_array($word, $common_words);
    });

    if (!empty($keywords)) {
        // Build search query for tours containing any of the keywords
        $search_conditions = [];
        $search_params = [];

        foreach ($keywords as $keyword) {
            $search_conditions[] = "LOWER(title) LIKE ?";
            $search_params[] = '%' . $keyword . '%';
        }

        if (!empty($search_conditions)) {
            $tours_query = "SELECT tour_id, title, short_description, cover_image_path, category, country, days_count
                           FROM tours
                           WHERE " . implode(' OR ', $search_conditions) . "
                           ORDER BY created_at DESC
                           LIMIT 6";

            try {
                require_once('../admin/config/database.php');
                $tours_stmt = $pdo->prepare($tours_query);
                $tours_stmt->execute($search_params);
                $related_tours = $tours_stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                // If there's an error, just continue without related tours
                error_log("Error fetching related tours: " . $e->getMessage());
            }
        }
    }
}

// Page title
$page_title = $attraction['title'] . ' - Virunga Ecotours';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon">
   <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/attraction.css">
    <script src="../js/header.js" defer></script>
</head>
<body>
    <?php include('./includes/header.php'); ?>

    <!-- Hero Section -->
    <section class="attraction-hero" style="background-image: url('../admin/<?php echo cleanImagePath($attraction['image_url']); ?>');">
        <div class="container">
            <h1><?php echo htmlspecialchars($attraction['title']); ?></h1>
            <?php if (!empty($attraction['location'])): ?>
            <div class="location-badge">
                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($attraction['location']); ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content -->
    <main class="attraction-content">
        <div class="container">
            <!-- About Section -->
            <section class="about-section">
                <h2>About <?php echo htmlspecialchars($attraction['title']); ?></h2>
                <div class="about-content">
                    <?php 
                    if (!empty($attraction['description'])) {
                        // Split description into paragraphs
                        $paragraphs = explode("\n", $attraction['description']);
                        foreach ($paragraphs as $paragraph) {
                            if (trim($paragraph) !== '') {
                                echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                            }
                        }
                    } else {
                        echo '<p>Detailed information about this attraction is coming soon. Please check back later for updates.</p>';
                    }
                    ?>
                </div>
            </section>

            <!-- Activities Section -->
            <?php if (!empty($activities)): ?>
            <section class="activities-section">
                <h2>Activities</h2>
                <div class="activities-grid">
                    <?php foreach ($activities as $activity): ?>
                        <?php if (trim($activity) !== ''): ?>
                        <div class="activity-card">
                            <div class="activity-icon">
                                <?php 
                                // Assign icons based on activity keywords
                                if (stripos($activity, 'gorilla') !== false) {
                                    echo '<i class="fas fa-paw"></i>';
                                } elseif (stripos($activity, 'hik') !== false || stripos($activity, 'trek') !== false) {
                                    echo '<i class="fas fa-hiking"></i>';
                                } elseif (stripos($activity, 'bird') !== false) {
                                    echo '<i class="fas fa-feather-alt"></i>';
                                } elseif (stripos($activity, 'boat') !== false || stripos($activity, 'lake') !== false) {
                                    echo '<i class="fas fa-water"></i>';
                                } elseif (stripos($activity, 'cultur') !== false || stripos($activity, 'village') !== false) {
                                    echo '<i class="fas fa-users"></i>';
                                } elseif (stripos($activity, 'safari') !== false || stripos($activity, 'drive') !== false) {
                                    echo '<i class="fas fa-car"></i>';
                                } else {
                                    echo '<i class="fas fa-leaf"></i>';
                                }
                                ?>
                            </div>
                            <h3><?php echo htmlspecialchars(trim($activity)); ?></h3>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- Related Itineraries Section -->
            <?php if (!empty($related_tours)): ?>
            <section class="related-itineraries-section">
                <h2>Related Itineraries</h2>
                <p class="section-description">Discover our carefully crafted tours that feature <?php echo htmlspecialchars($attraction['title']); ?></p>
                <div class="tours-cards">
                    <?php foreach ($related_tours as $tour): ?>
                        <div class="tour-card">
                            <div class="tour-card-image">
                                <?php if (!empty($tour['cover_image_path'])): ?>
                                    <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>">
                                <?php else: ?>
                                    <div class="placeholder-image">
                                        <i class="fas fa-mountain"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($tour['category'])); ?></div>
                                <div class="tour-offer">AVAILABLE</div>
                            </div>
                            <div class="tour-card-content">
                                <div class="tour-tags">
                                    <span class="tour-tag"><?php echo strtoupper(htmlspecialchars($tour['country'])); ?></span>
                                    <span class="tour-tag"><?php echo htmlspecialchars($tour['days_count']); ?> DAYS</span>
                                </div>
                                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                <?php if (!empty($tour['short_description'])): ?>
                                    <p><?php echo htmlspecialchars(substr($tour['short_description'], 0, 120)) . (strlen($tour['short_description']) > 120 ? '...' : ''); ?></p>
                                <?php endif; ?>
                                <div class="tour-actions">
                                    <a href="../pages/itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="read-more-btn">
                                        READ MORE
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            </section>
            <?php endif; ?>

            <!-- Gallery Section -->
            <section class="gallery-section">
                <h2>Gallery</h2>
                <?php if (count($gallery_images) > 0): ?>
                    <div class="gallery-grid">
                        <?php foreach ($gallery_images as $image): ?>
                            <div class="gallery-item">
                                <img src="../admin/<?php echo cleanImagePath($image['image_url']); ?>" alt="<?php echo htmlspecialchars($image['caption']); ?>">
                                <?php if (!empty($image['caption'])): ?>
                                    <div class="image-caption"><?php echo htmlspecialchars($image['caption']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-images">Gallery images coming soon!</p>
                <?php endif; ?>
            </section>

            <!-- External Link Section -->
            <?php if (!empty($attraction['external_link'])): ?>
                <section class="external-link-section">
                    <h2>Learn More</h2>
                    <p>Visit the official website for more information about <?php echo htmlspecialchars($attraction['title']); ?>:</p>
                    <a href="<?php echo htmlspecialchars($attraction['external_link']); ?>" class="external-link-button" target="_blank" rel="noopener noreferrer">
                        <i class="fas fa-external-link-alt"></i> Official Website
                    </a>
                </section>
            <?php endif; ?>

            <!-- CTA Section -->
            <section class="attraction-cta">
                <div class="cta-content">
                    <h2>Ready to Explore <?php echo htmlspecialchars($attraction['title']); ?>?</h2>
                    <p>Let us help you plan your perfect adventure to this incredible destination.</p>
                    <div class="cta-buttons">
                        <a href="../pages/build.php" class="cta-primary">Plan Your Trip</a>
                        <a href="../pages/contact.php" class="cta-secondary">Contact Us</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <?php include('./includes/footer.php'); ?>

    <script>
        // Gallery lightbox functionality
        document.addEventListener('DOMContentLoaded', function() {
            const galleryItems = document.querySelectorAll('.gallery-item img');
            
            galleryItems.forEach(item => {
                item.addEventListener('click', function() {
                    const lightbox = document.createElement('div');
                    lightbox.className = 'lightbox';
                    
                    const lightboxContent = document.createElement('div');
                    lightboxContent.className = 'lightbox-content';
                    
                    const img = document.createElement('img');
                    img.src = this.src;
                    
                    const caption = document.createElement('div');
                    caption.className = 'lightbox-caption';
                    caption.textContent = this.alt;
                    
                    const closeBtn = document.createElement('span');
                    closeBtn.className = 'lightbox-close';
                    closeBtn.innerHTML = '&times;';
                    
                    lightboxContent.appendChild(img);
                    lightboxContent.appendChild(caption);
                    lightboxContent.appendChild(closeBtn);
                    lightbox.appendChild(lightboxContent);
                    document.body.appendChild(lightbox);
                    
                    closeBtn.addEventListener('click', function() {
                        document.body.removeChild(lightbox);
                    });
                    
                    lightbox.addEventListener('click', function(e) {
                        if (e.target === lightbox) {
                            document.body.removeChild(lightbox);
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>


