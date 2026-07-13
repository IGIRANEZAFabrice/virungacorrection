<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    
    // Handle image upload
    $image_filename = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/images/programs/';
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($file_extension, $allowed_extensions)) {
            $image_filename = uniqid() . '_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $image_filename;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $error_message = "Failed to upload image.";
            }
        } else {
            $error_message = "Invalid image format. Please use JPG, PNG, or WebP.";
        }
    }
    
    // Handle gallery upload
    $gallery_images = [];
    if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
        $gallery_dir = '../../assets/images/programs/gallery/';
        
        // Create gallery directory if it doesn't exist
        if (!is_dir($gallery_dir)) {
            mkdir($gallery_dir, 0755, true);
        }
        
        for ($i = 0; $i < count($_FILES['gallery']['name']); $i++) {
            if ($_FILES['gallery']['error'][$i] === UPLOAD_ERR_OK) {
                $file_extension = strtolower(pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION));
                if (in_array($file_extension, $allowed_extensions)) {
                    $gallery_filename = uniqid() . '_' . time() . '_' . $i . '.' . $file_extension;
                    $gallery_path = $gallery_dir . $gallery_filename;
                    
                    if (move_uploaded_file($_FILES['gallery']['tmp_name'][$i], $gallery_path)) {
                        $gallery_images[] = $gallery_filename;
                    }
                }
            }
        }
    }
    
    $gallery_json = !empty($gallery_images) ? json_encode($gallery_images) : null;
    
    if (!isset($error_message)) {
        // Insert program into database
        $insert_query = "INSERT INTO community_programs (
            title, slug, short_description, description, country, location, category, 
            status, date_started, date_ended, beneficiaries, budget, impact_summary, 
            image, gallery, featured, meta_title, meta_description, created_at
        ) VALUES (
            '$title', '$slug', '$short_description', '$description', '$country', '$location', '$category',
            '$status', '$date_started', " . ($date_ended ? "'$date_ended'" : "NULL") . ", $beneficiaries, 
            " . ($budget ? $budget : "NULL") . ", '$impact_summary', '$image_filename', 
            " . ($gallery_json ? "'$gallery_json'" : "NULL") . ", $featured, '$meta_title', '$meta_description', NOW()
        )";
        
        if (mysqli_query($conn, $insert_query)) {
            $success_message = "Program created successfully!";
            // Redirect to programs list
            header('Location: index.php?success=' . urlencode($success_message));
            exit;
        } else {
            $error_message = "Error creating program: " . mysqli_error($conn);
        }
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
    <title>Create Program - Community Admin</title>
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
                        <h1><i class="fas fa-plus"></i> Create New Program</h1>
                        <p>Add a new community program to showcase your impact</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Programs
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
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
                <form method="POST" enctype="multipart/form-data" class="admin-form">
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
                                           placeholder="Enter program title">
                                </div>

                                <div class="form-group">
                                    <label for="slug">URL Slug *</label>
                                    <input type="text" id="slug" name="slug" required 
                                           placeholder="program-url-slug">
                                    <small>Used in the program URL. Use lowercase letters, numbers, and hyphens only.</small>
                                </div>

                                <div class="form-group">
                                    <label for="short_description">Short Description *</label>
                                    <textarea id="short_description" name="short_description" rows="3" required 
                                              placeholder="Brief description for program cards and previews"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="description">Full Description *</label>
                                    <textarea id="description" name="description" rows="8" required 
                                              placeholder="Detailed description of the program"></textarea>
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
                                            <option value="rwanda">Rwanda</option>
                                            <option value="uganda">Uganda</option>
                                            <option value="congo">Congo</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="location">Specific Location</label>
                                        <input type="text" id="location" name="location" 
                                               placeholder="City, region, or specific area">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="category">Category *</label>
                                    <select id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="Conservation">Conservation</option>
                                        <option value="Education">Education</option>
                                        <option value="Healthcare">Healthcare</option>
                                        <option value="Economic Development">Economic Development</option>
                                        <option value="Infrastructure">Infrastructure</option>
                                        <option value="Women Empowerment">Women Empowerment</option>
                                        <option value="Youth Development">Youth Development</option>
                                        <option value="Environmental Protection">Environmental Protection</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                            <option value="<?php echo htmlspecialchars($category['category']); ?>">
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
                                            <option value="active">Active</option>
                                            <option value="completed">Completed</option>
                                            <option value="planned">Planned</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="beneficiaries">Beneficiaries *</label>
                                        <input type="number" id="beneficiaries" name="beneficiaries" required min="0" 
                                               placeholder="Number of people benefiting">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="date_started">Start Date *</label>
                                        <input type="date" id="date_started" name="date_started" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="date_ended">End Date</label>
                                        <input type="date" id="date_ended" name="date_ended">
                                        <small>Leave empty for ongoing programs</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="budget">Budget (USD)</label>
                                    <input type="number" id="budget" name="budget" step="0.01" min="0" 
                                           placeholder="Program budget in USD">
                                </div>

                                <div class="form-group">
                                    <label for="impact_summary">Impact Summary</label>
                                    <textarea id="impact_summary" name="impact_summary" rows="4" 
                                              placeholder="Describe the impact and outcomes of this program"></textarea>
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
                                    <label for="image">Main Image *</label>
                                    <input type="file" id="image" name="image" accept="image/*" required>
                                    <small>Upload a high-quality image that represents the program (JPG, PNG, WebP)</small>
                                </div>

                                <div class="form-group">
                                    <label for="gallery">Gallery Images</label>
                                    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple>
                                    <small>Upload additional images for the program gallery (optional)</small>
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
                                           placeholder="SEO title for search engines">
                                    <small>Leave empty to use program title</small>
                                </div>

                                <div class="form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea id="meta_description" name="meta_description" rows="3" 
                                              placeholder="SEO description for search engines"></textarea>
                                    <small>Leave empty to use short description</small>
                                </div>

                                <div class="form-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="featured" name="featured" value="1">
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
                            Create Program
                        </button>
                        <a href="index.php" class="btn btn-outline btn-large">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
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

        // Auto-fill meta title from title
        document.getElementById('title').addEventListener('input', function() {
            const metaTitleField = document.getElementById('meta_title');
            if (!metaTitleField.value) {
                metaTitleField.value = this.value + ' - Virunga Ecotours Community';
            }
        });

        // Auto-fill meta description from short description
        document.getElementById('short_description').addEventListener('input', function() {
            const metaDescField = document.getElementById('meta_description');
            if (!metaDescField.value) {
                metaDescField.value = this.value;
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
