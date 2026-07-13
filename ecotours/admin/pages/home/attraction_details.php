<?php
require_once '../../config/connection.php';

// Compatibility function for get_result() - works with all MySQLi configurations
function getStmtResult($stmt) {
    if (method_exists($stmt, 'get_result')) {
        return $stmt->get_result();
    } else {
        // Fallback for servers without mysqlnd support
        $result = new stdClass();
        $result->data = [];
        $result->num_rows = 0;

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

// Check if user is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../login.php');
    exit();
}

// Get attraction ID
$attraction_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($attraction_id <= 0) {
    header('Location: ./attractions.php');
    exit();
}

// Get active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'details';

// Fetch attraction data
$attraction_query = "SELECT * FROM home_attractions WHERE id = ?";
$attraction_stmt = $conn->prepare($attraction_query);
$attraction_stmt->bind_param("i", $attraction_id);
$attraction_stmt->execute();
$attraction_result = getStmtResult($attraction_stmt);

if ($attraction_result->num_rows === 0) {
    header('Location: ./attractions.php');
    exit();
}

$attraction = $attraction_result->fetch_assoc();

// Fetch attraction details
$details_query = "SELECT * FROM attraction_details WHERE attraction_id = ?";
$details_stmt = $conn->prepare($details_query);
$details_stmt->bind_param("i", $attraction_id);
$details_stmt->execute();
$details_result = getStmtResult($details_stmt);
$details = $details_result->num_rows > 0 ? $details_result->fetch_assoc() : null;

// Fetch gallery images
$gallery_query = "SELECT * FROM attraction_gallery WHERE attraction_id = ? ORDER BY display_order ASC";
$gallery_stmt = $conn->prepare($gallery_query);
$gallery_stmt->bind_param("i", $attraction_id);
$gallery_stmt->execute();
$gallery_result = getStmtResult($gallery_stmt);
$gallery_images = [];

while ($image = $gallery_result->fetch_assoc()) {
    $gallery_images[] = $image;
}

// Page title
$page_title = 'Manage ' . $attraction['title'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Admin Dashboard</title>
    <link rel="shortcut icon" href="../../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/common.css" />
    <link rel="stylesheet" href="../../css/home-attraction-details.css" />
    <script src="../../js/common.js" defer></script>
    <style>
        .form-label {
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 20px 10px;
            border-radius: 5px;
            margin: 0 auto;
            width: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include_once './include/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include_once './include/header.php'; ?>
            
            <div class="content-wrapper">
                <div class="content-header">
                    <h1><i class="fas fa-mountain"></i> <?php echo htmlspecialchars($attraction['title']); ?></h1>
                    <a href="./attractions.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Attractions
                    </a>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?>">
                        <?php 
                        echo $_SESSION['message']['text']; 
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>
                
                <div class="tabs-container">
                    <div class="tabs">
                        <button class="tab-btn <?php echo $active_tab === 'details' ? 'active' : ''; ?>" data-tab="details">
                            <i class="fas fa-info-circle"></i> Details
                        </button>
                        <button class="tab-btn <?php echo $active_tab === 'gallery' ? 'active' : ''; ?>" data-tab="gallery">
                            <i class="fas fa-images"></i> Gallery
                        </button>
                    </div>
                </div>
                
                <!-- Details Tab -->
                <div class="tab-content <?php echo $active_tab === 'details' ? 'active' : ''; ?>" id="details-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2>Attraction Details</h2>
                        </div>
                        <div class="card-body">
                            <form action="../../handlers/home/attractionDetailsHandler.php" method="post">
                                <input type="hidden" name="attraction_id" value="<?php echo $attraction_id; ?>">
                                
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" rows="8" required><?php echo $details ? htmlspecialchars($details['description']) : ''; ?></textarea>
                                    <small>Provide a detailed description of the attraction. Use line breaks for paragraphs.</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" id="location" name="location" value="<?php echo $details ? htmlspecialchars($details['location']) : ''; ?>" required>
                                    <small>Example: "Northwestern Rwanda, bordering the Democratic Republic of Congo and Uganda"</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="activities">Activities</label>
                                    <textarea id="activities" name="activities" rows="4" required><?php echo $details ? htmlspecialchars($details['activities']) : ''; ?></textarea>
                                    <small>Enter activities separated by commas. Example: "Gorilla Trekking,Golden Monkey Tracking,Volcano Hiking"</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="external_link">External Link (Optional)</label>
                                    <input type="url" id="external_link" name="external_link" value="<?php echo $details ? htmlspecialchars($details['external_link']) : ''; ?>">
                                    <small>Official website or additional information source</small>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" name="save_details" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Details
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Tab -->
                <div class="tab-content <?php echo $active_tab === 'gallery' ? 'active' : ''; ?>" id="gallery-tab">
                    <div class="card">
                        <div class="card-header">
                            <h2>Image Gallery</h2>
                        </div>
                        <div class="card-body">
                            <form action="../../handlers/home/attractionDetailsHandler.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="attraction_id" value="<?php echo $attraction_id; ?>">
                                
                                <div class="form-group">
                                    <label for="gallery_image" class="form-label"><i class="fas fa-upload"></i> Upload Image</label>
                                    <input type="file" id="gallery_image" name="gallery_image" accept="image/*" required onchange="previewImage(this)">
                                </div>
                                
                                <div id="image-preview"></div>
                                
                                <div class="form-group">
                                    <label for="image_caption">Caption (Optional)</label>
                                    <input type="text" id="image_caption" name="image_caption">
                                </div>
                                
                                <div class="form-group">
                                    <label for="display_order">Display Order</label>
                                    <input type="number" id="display_order" name="display_order" min="1" value="1">
                                    <small>Lower numbers appear first</small>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" name="upload_image" class="btn btn-primary">
                                        Upload Image
                                    </button>
                                </div>
                            </form>
                            
                            <hr>
                            
                            <h3>Current Gallery Images</h3>
                            <?php if (count($gallery_images) > 0): ?>
                                <div class="gallery-grid">
                                    <?php foreach ($gallery_images as $image): ?>
                                        <div class="gallery-item">
                                            <img src="<?php echo htmlspecialchars($image['image_url']); ?>" alt="<?php echo htmlspecialchars($image['caption']); ?>">
                                            <div class="gallery-caption">
                                                <?php echo !empty($image['caption']) ? htmlspecialchars($image['caption']) : 'No caption'; ?>
                                                <div>Order: <?php echo $image['display_order']; ?></div>
                                            </div>
                                            <div class="gallery-actions">
                                                <form action="../../handlers/home/attractionDetailsHandler.php" method="post" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                                    <input type="hidden" name="attraction_id" value="<?php echo $attraction_id; ?>">
                                                    <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>">
                                                    <button type="submit" name="delete_image" class="delete-btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p>No gallery images have been uploaded yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Update URL without reloading page
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabId);
                window.history.pushState({}, '', url);
                
                // Update active tab
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Show active content
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId + '-tab').classList.add('active');
            });
        });
        
        // Image preview
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '300px';
                    img.style.maxHeight = '200px';
                    img.style.border = '1px solid #ddd';
                    img.style.borderRadius = '4px';
                    img.style.padding = '5px';
                    
                    preview.appendChild(img);
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>



