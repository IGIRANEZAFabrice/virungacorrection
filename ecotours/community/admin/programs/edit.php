<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Handle gallery image deletion
if (isset($_POST['delete_gallery_image'])) {
    $image_to_delete = $_POST['image_name'];
    $program_id = (int)$_POST['program_id'];
    
    // Fetch current gallery
    $gallery_query = "SELECT gallery FROM community_programs WHERE id = $program_id";
    $gallery_result = mysqli_query($conn, $gallery_query);
    $gallery_data = mysqli_fetch_assoc($gallery_result);
    
    if ($gallery_data && $gallery_data['gallery']) {
        $gallery_images = json_decode($gallery_data['gallery'], true);
        
        // Remove the image from the array
        $gallery_images = array_filter($gallery_images, function($img) use ($image_to_delete) {
            return $img !== $image_to_delete;
        });
        
        // Update database with new gallery array
        $gallery_json = !empty($gallery_images) ? json_encode(array_values($gallery_images)) : null;
        $update_query = "UPDATE community_programs SET gallery = " . 
                       ($gallery_json ? "'$gallery_json'" : "NULL") . 
                       " WHERE id = $program_id";
        
        if (mysqli_query($conn, $update_query)) {
            // Delete the actual file
            $file_path = '../../assets/images/programs/gallery/' . $image_to_delete;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            header('Location: edit.php?id=' . $program_id . '&gallery_deleted=1');
            exit;
        }
    }
}

// Get program ID
$program_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$program_id) {
    header('Location: index.php');
    exit;
}

// Fetch program data
$program_query = "SELECT * FROM community_programs WHERE id = $program_id";
$program_result = mysqli_query($conn, $program_query);

if (mysqli_num_rows($program_result) === 0) {
    header('Location: index.php');
    exit;
}

$program = mysqli_fetch_assoc($program_result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Define allowed file extensions
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    
    // Debug information
    error_log('POST data received: ' . print_r($_POST, true));
    error_log('FILES data received: ' . print_r($_FILES, true));

    $errors = array();
    
    // Validate required fields
    $required_fields = ['title', 'slug', 'short_description', 'description', 'country', 'category', 'status', 'date_started', 'beneficiaries'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
        }
    }
    
    // Debug validation errors
    if (!empty($errors)) {
        error_log('Validation errors: ' . print_r($errors, true));
    }

    // Initialize image filename
    $image_filename = $program['image']; // Keep existing image by default

    // Handle main image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        try {
            $upload_dir = '../../assets/images/programs/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            
            if (in_array($file_extension, $allowed_extensions)) {
                $new_image_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $upload_path = $upload_dir . $new_image_filename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    // Delete old image if it exists and is different from the new one
                    if ($program['image'] && file_exists($upload_dir . $program['image']) && $program['image'] !== $new_image_filename) {
                        unlink($upload_dir . $program['image']);
                    }
                    $image_filename = $new_image_filename;
                } else {
                    error_log("Failed to move uploaded file to: $upload_path");
                    $errors[] = "Failed to upload image file.";
                }
            } else {
                $errors[] = "Invalid image format. Please use JPG, PNG, or WebP.";
            }
        } catch (Exception $e) {
            error_log("Error handling image upload: " . $e->getMessage());
            $errors[] = "Error processing image upload.";
        }
    }

    // Only proceed if there are no validation errors
    if (empty($errors)) {
        try {
            $title = mysqli_real_escape_string($conn, $_POST['title']);
            $slug = mysqli_real_escape_string($conn, $_POST['slug']);
            $short_description = mysqli_real_escape_string($conn, $_POST['short_description']);
            $description = mysqli_real_escape_string($conn, $_POST['description']);
            $country = mysqli_real_escape_string($conn, $_POST['country']);
            $location = mysqli_real_escape_string($conn, $_POST['location']);
            $category = mysqli_real_escape_string($conn, $_POST['category']);
            $status = mysqli_real_escape_string($conn, $_POST['status']);
            $date_started = mysqli_real_escape_string($conn, $_POST['date_started']);
            $date_ended = !empty($_POST['date_ended']) ? mysqli_real_escape_string($conn, $_POST['date_ended']) : null;
            $beneficiaries = (int)$_POST['beneficiaries'];
            $budget = !empty($_POST['budget']) ? (float)$_POST['budget'] : null;
            $impact_summary = mysqli_real_escape_string($conn, $_POST['impact_summary']);
            $featured = isset($_POST['featured']) ? 1 : 0;
            $meta_title = mysqli_real_escape_string($conn, $_POST['meta_title']);
            $meta_description = mysqli_real_escape_string($conn, $_POST['meta_description']);
            
            // Image filename is already handled above
            
            // Handle gallery upload
            $gallery_images = [];
            if (!empty($program['gallery'])) {
                $gallery_images = json_decode($program['gallery'], true) ?: [];
            }
            
            if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                $gallery_dir = '../../assets/images/programs/gallery/';
                
                // Create gallery directory if it doesn't exist
                if (!is_dir($gallery_dir)) {
                    if (!mkdir($gallery_dir, 0755, true)) {
                        error_log("Failed to create gallery directory: $gallery_dir");
                        $errors[] = "Failed to create gallery directory.";
                    }
                }
                
                if (empty($errors)) {
                    for ($i = 0; $i < count($_FILES['gallery']['name']); $i++) {
                        if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                            $file_extension = strtolower(pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION));
                            
                            if (!in_array($file_extension, $allowed_extensions)) {
                                error_log("Invalid file extension for gallery image: $file_extension");
                                $errors[] = "Invalid file type for gallery image " . ($i + 1) . ". Allowed types: " . implode(', ', $allowed_extensions);
                                continue;
                            }
                            
                            $gallery_filename = uniqid() . '_' . time() . '_' . $i . '.' . $file_extension;
                            $gallery_path = $gallery_dir . $gallery_filename;
                            
                            if (!move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gallery_path)) {
                                error_log("Failed to move gallery image to: $gallery_path");
                                $errors[] = "Failed to upload gallery image " . ($i + 1);
                                continue;
                            }
                            
                            $gallery_images[] = $gallery_filename;
                        } else {
                            error_log("Gallery upload error for image $i: " . $_FILES['gallery']['error'][$i]);
                            $errors[] = "Error uploading gallery image " . ($i + 1);
                        }
                    }
                }
            }
            
            $gallery_json = !empty($gallery_images) ? json_encode($gallery_images) : null;
            
            if (!isset($error_message)) {
                // Update program in database
                $update_query = "UPDATE community_programs SET 
                    title = ?,
                    slug = ?,
                    short_description = ?,
                    description = ?,
                    country = ?,
                    location = ?,
                    category = ?,
                    status = ?,
                    date_started = ?,
                    date_ended = ?,
                    beneficiaries = ?,
                    budget = ?,
                    impact_summary = ?,
                    meta_title = ?,
                    meta_description = ?,
                    featured = ?,
                    updated_at = NOW()";

                // Add image to update if a new one was uploaded
                if (isset($image_filename)) {
                    $update_query .= ", image = ?";
                }

                // Add gallery to update if new images were uploaded
                if (isset($gallery_json)) {
                    $update_query .= ", gallery = ?";
                }

                $update_query .= " WHERE id = ?";

                try {
                    $stmt = mysqli_prepare($conn, $update_query);
                    if ($stmt === false) {
                        throw new Exception('Failed to prepare statement: ' . mysqli_error($conn));
                    }

                    // Create references for bind_param
                    $refs = array();
                    
                    // Start with the type string
                    $types = 'ssssssssssissssi'; // Base types for required fields
                    $refs[] = &$types;
                    
                    // Create references for all parameters
                    $refs[] = &$title;
                    $refs[] = &$slug;
                    $refs[] = &$short_description;
                    $refs[] = &$description;
                    $refs[] = &$country;
                    $refs[] = &$location;
                    $refs[] = &$category;
                    $refs[] = &$status;
                    $refs[] = &$date_started;
                    $refs[] = &$date_ended;
                    $refs[] = &$beneficiaries;
                    $refs[] = &$budget;
                    $refs[] = &$impact_summary;
                    $refs[] = &$meta_title;
                    $refs[] = &$meta_description;
                    $refs[] = &$featured;

                    // Add image parameter if needed
                    if (isset($image_filename)) {
                        $types .= 's';
                        $refs[] = &$image_filename;
                    }

                    // Add gallery parameter if needed
                    if (isset($gallery_json)) {
                        $types .= 's';
                        $refs[] = &$gallery_json;
                    }

                    // Add program ID
                    $types .= 'i';
                    $refs[] = &$program_id;

                    // Call bind_param with references
                    call_user_func_array(array($stmt, 'bind_param'), $refs);

                    // Execute the statement
                    if (!mysqli_stmt_execute($stmt)) {
                        throw new Exception('Failed to execute statement: ' . mysqli_stmt_error($stmt));
                    }

                    $success_message = "Program updated successfully!";
                    
                    // Refresh program data
                    $program_result = mysqli_query($conn, "SELECT * FROM community_programs WHERE id = $program_id");
                    $program = mysqli_fetch_assoc($program_result);
                    
                } catch (Exception $e) {
                    error_log("Database error: " . $e->getMessage());
                    $errors[] = "Failed to update program: " . $e->getMessage();
                } finally {
                    if (isset($stmt)) {
                        mysqli_stmt_close($stmt);
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error processing form data: " . $e->getMessage());
            $errors[] = "An error occurred while processing your request.";
        }
    }
}

// Parse gallery images
$gallery_images = [];
if (!empty($program['gallery'])) {
    $gallery_data = json_decode($program['gallery'], true);
    if (is_array($gallery_data)) {
        $gallery_images = $gallery_data;
    }
}

// Get categories for dropdown
$categories_query = "SELECT DISTINCT category FROM community_programs ORDER BY category";
$categories_result = mysqli_query($conn, $categories_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Program - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <style>
    /* Custom Styles for Admin */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f7fa;
        margin: 0;
        padding: 0;
    }
    
    .admin-layout {
        display: flex;
        flex-direction: row;
        min-height: 100vh;
    }
    
    .main-content {
        flex: 1;
        padding: 2rem;
        background-color: #fff;
        border-left: 1px solid #e1e4e8;
        border-right: 1px solid #e1e4e8;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .page-header-content h1 {
        font-size: 1.75rem;
        margin: 0;
        color: #333;
    }
    
    .page-header-content p {
        margin: 0;
        color: #666;
    }
    
    .page-header-actions a {
        margin-left: 1rem;
    }
    
    .alert {
        padding: 1rem;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Gallery Styles */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .gallery-item {
        position: relative;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-item-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-item-overlay {
        opacity: 1;
    }
    
    .delete-gallery-form {
        margin: 0;
    }
    
    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }
    
    .btn-danger:hover {
        background-color: #c82333;
    }
    
    .btn-small {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <?php include '../includes/topbar.php'; ?>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-content">
                        <h1><i class="fas fa-edit"></i> Edit Program</h1>
                        <p>Update program information and settings</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="../../program-detail.php?slug=<?php echo htmlspecialchars($program['slug']); ?>" 
                           target="_blank" class="btn btn-outline">
                            <i class="fas fa-eye"></i>
                            View Program
                        </a>
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Programs
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['gallery_deleted']) && $_GET['gallery_deleted'] === '1'): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Gallery image deleted successfully!
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST"  enctype="multipart/form-data" class="admin-form" id="destinationForm">
                    <div class="form-grid">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-info-circle"></i> Basic Information</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="title">Program Title *</label>
                                    <input type="text" id="title" name="title" required 
                                           value="<?php echo htmlspecialchars($program['title']); ?>"
                                           placeholder="Enter program title">
                                </div>

                                <div class="form-group">
                                    <label for="slug">URL Slug *</label>
                                    <input type="text" id="slug" name="slug" required 
                                           value="<?php echo htmlspecialchars($program['slug']); ?>"
                                           placeholder="program-url-slug">
                                    <small>Used in the program URL. Use lowercase letters, numbers, and hyphens only.</small>
                                </div>

                                <div class="form-group">
                                    <label for="short_description">Short Description *</label>
                                    <textarea id="short_description" name="short_description" rows="3" required 
                                              placeholder="Brief description for program cards and previews"><?php echo htmlspecialchars($program['short_description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="description">Full Description *</label>
                                    <textarea id="description" name="description" rows="8" required 
                                              placeholder="Detailed description of the program"><?php echo htmlspecialchars($program['description']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Location & Category -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-map-marker-alt"></i> Location & Category</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="country">Country *</label>
                                        <select id="country" name="country" required>
                                            <option value="">Select Country</option>
                                            <option value="rwanda" <?php echo $program['country'] === 'rwanda' ? 'selected' : ''; ?>>Rwanda</option>
                                            <option value="uganda" <?php echo $program['country'] === 'uganda' ? 'selected' : ''; ?>>Uganda</option>
                                            <option value="congo" <?php echo $program['country'] === 'congo' ? 'selected' : ''; ?>>Congo</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Specific Location</label>
                                        <input type="text" id="location" name="location" 
                                               value="<?php echo htmlspecialchars($program['location']); ?>"
                                               placeholder="City, region, or specific area">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Conservation" <?php echo $program['category'] === 'Conservation' ? 'selected' : ''; ?>>Conservation</option>
                                        <option value="Education" <?php echo $program['category'] === 'Education' ? 'selected' : ''; ?>>Education</option>
                                        <option value="Healthcare" <?php echo $program['category'] === 'Healthcare' ? 'selected' : ''; ?>>Healthcare</option>
                                        <option value="Economic Development" <?php echo $program['category'] === 'Economic Development' ? 'selected' : ''; ?>>Economic Development</option>
                                        <option value="Infrastructure" <?php echo $program['category'] === 'Infrastructure' ? 'selected' : ''; ?>>Infrastructure</option>
                                        <option value="Women Empowerment" <?php echo $program['category'] === 'Women Empowerment' ? 'selected' : ''; ?>>Women Empowerment</option>
                                        <option value="Youth Development" <?php echo $program['category'] === 'Youth Development' ? 'selected' : ''; ?>>Youth Development</option>
                                        <option value="Environmental Protection" <?php echo $program['category'] === 'Environmental Protection' ? 'selected' : ''; ?>>Environmental Protection</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                            <option value="<?php echo htmlspecialchars($category['category']); ?>"
                                                    <?php echo $program['category'] === $category['category'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Program Details -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-calendar-alt"></i> Program Details</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="status">Status *</label>
                                        <select id="status" name="status" required>
                                            <option value="active" <?php echo $program['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                            <option value="completed" <?php echo $program['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="planned" <?php echo $program['status'] === 'planned' ? 'selected' : ''; ?>>Planned</option>
                                            <option value="cancelled" <?php echo $program['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="beneficiaries">Beneficiaries *</label>
                                        <input type="number" id="beneficiaries" name="beneficiaries" required min="0" 
                                               value="<?php echo $program['beneficiaries']; ?>"
                                               placeholder="Number of people benefiting">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="date_started">Start Date *</label>
                                        <input type="date" id="date_started" name="date_started" required
                                               value="<?php echo $program['date_started']; ?>">
                                    </div>

                                    <div class="form-group">
                                        <label for="date_ended">End Date</label>
                                        <input type="date" id="date_ended" name="date_ended"
                                               value="<?php echo $program['date_ended']; ?>">
                                        <small>Leave empty for ongoing programs</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="budget">Budget (USD)</label>
                                    <input type="number" id="budget" name="budget" step="0.01" min="0" 
                                           value="<?php echo $program['budget']; ?>"
                                           placeholder="Program budget in USD">
                                </div>

                                <div class="form-group">
                                    <label for="impact_summary">Impact Summary</label>
                                    <textarea id="impact_summary" name="impact_summary" rows="4" 
                                              placeholder="Describe the impact and outcomes of this program"><?php echo htmlspecialchars($program['impact_summary']); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Media -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-images"></i> Media</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="image">Main Image</label>
                                    <?php if ($program['image']): ?>
                                        <div class="current-image">
                                            <img src="../../assets/images/programs/<?php echo htmlspecialchars($program['image']); ?>" 
                                                 alt="Current image" style="max-width: 200px; height: auto; margin-bottom: 1rem; border-radius: 8px;">
                                            <p><small>Current image. Upload a new file to replace it.</small></p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" id="image" name="image" accept="image/*">
                                    <small>Upload a high-quality image that represents the program (JPG, PNG, WebP)</small>
                                </div>

                                <div class="form-group">
                                    <label for="gallery">Gallery Images</label>
                                    <?php if (!empty($gallery_images)): ?>
                                        <div class="current-gallery">
                                            <p><strong>Current gallery images:</strong></p>
                                            <div class="gallery-grid">
                                                <?php foreach ($gallery_images as $gallery_image): ?>
                                                    <div class="gallery-item">
                                                        <img src="../../assets/images/programs/gallery/<?php echo htmlspecialchars($gallery_image); ?>" 
                                                             alt="Gallery image">
                                                        <div class="gallery-item-overlay">
                                                            <form method="POST" class="delete-gallery-form" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                                                <input type="hidden" name="delete_gallery_image" value="1">
                                                                <input type="hidden" name="image_name" value="<?php echo htmlspecialchars($gallery_image); ?>">
                                                                <input type="hidden" name="program_id" value="<?php echo $program_id; ?>">
                                                                <button type="submit" class="btn btn-danger btn-small">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <p><small>Upload new images to add to the gallery.</small></p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                                    <small>Upload additional images for the program gallery (optional). Supported formats: JPG, PNG, WebP</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO & Settings -->
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-cog"></i> SEO & Settings</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" id="meta_title" name="meta_title" 
                                           value="<?php echo htmlspecialchars($program['meta_title']); ?>"
                                           placeholder="SEO title for search engines">
                                    <small>Leave empty to use program title</small>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea id="meta_description" name="meta_description" rows="3" 
                                              placeholder="SEO description for search engines"><?php echo htmlspecialchars($program['meta_description']); ?></textarea>
                                    <small>Leave empty to use short description</small>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="featured" name="featured" value="1" 
                                               <?php echo $program['featured'] ? 'checked' : ''; ?>>
                                        <label for="featured">
                                            <i class="fas fa-star"></i>
                                            Featured Program
                                        </label>
                                        <small>Featured programs appear prominently on the website</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-save"></i>
                            Update Program
                        </button>
                        <a href="index.php" class="btn btn-outline btn-large">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>

                    <!-- Validation Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
        // Form handling
        const programForm = document.getElementById('destinationForm');
        
        programForm.addEventListener('submit', function(e) {
            // Show loading state on submit button
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            submitButton.disabled = true;

            // Add loading overlay
            const overlay = document.createElement('div');
            overlay.className = 'loading-overlay';
            overlay.innerHTML = '<div class="loading-spinner"></div>';
            document.body.appendChild(overlay);

            // Form will submit normally
            // The loading state will be cleared on page reload
        });

        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function() {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            document.getElementById('slug').value = slug;
        });
    </script>

    <style>
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
