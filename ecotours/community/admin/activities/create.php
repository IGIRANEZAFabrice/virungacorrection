<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Log admin action - only if admin_logs table exists
$admin_id = $_SESSION['community_admin_id'];
$action = "Accessed activity creation form";

// Check if admin_logs table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
if (mysqli_num_rows($table_check) > 0) {
    $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/create.php', NOW())";
    mysqli_query($conn, $log_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Activity - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <style>
        /* Main layout structure */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }
        
        .admin-content {
            flex: 1;
            position: relative;
            z-index: 1;
            min-width: 0; /* Prevent content from overflowing */
            margin-left: 250px; /* Match sidebar width */
        }
        
        .content-wrapper {
            padding: 1.5rem;
            background-color: #f8f9fa;
            min-height: calc(100vh - 60px); /* Adjust for topbar height */
        }
        
        /* Form layout and styling */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .form-full-width {
            grid-column: span 2;
        }
        
        .image-preview {
            max-width: 100%;
            height: auto;
            margin-top: 1rem;
            border-radius: 8px;
            display: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.15s ease-in-out;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .required {
            color: #dc3545;
        }
        
        .btn {
            display: inline-block;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: all 0.15s ease-in-out;
        }
        
        .btn-primary {
            color: #fff;
            background-color: #4CAF50;
            border-color: #4CAF50;
        }
        
        .btn-primary:hover {
            background-color: #3e8e41;
            border-color: #3e8e41;
        }
        
        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        .form-actions {
            margin-top: 2rem;
            text-align: right;
        }
        
        /* Card styling */
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }
        
        .content-header h1 {
            margin: 0;
            font-size: 1.75rem;
            color: #333;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .admin-content {
                margin-left: 0;
                width: 100%;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-full-width {
                grid-column: span 1;
            }
            
            .form-actions {
                text-align: center;
            }
            
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .content-header .actions {
                width: 100%;
                display: flex;
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 576px) {
            .content-wrapper {
                padding: 0.75rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Include Sidebar -->
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <!-- Include Topbar -->
            <?php include '../includes/topbar.php'; ?>
            
            <div class="content-wrapper">
                <div class="content-header">
                    <h1>Add New Activity</h1>
                    <div class="actions">
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Activities
                        </a>
                    </div>
                </div>
                
                <div class="content-body">
                    <div class="card">
                        <div class="card-body">
                            <form id="activityForm" action="handlers/create-handler.php" method="POST" enctype="multipart/form-data" novalidate>
                                <div class="form-grid">
                                    <!-- Title -->
                                    <div class="form-group">
                                        <label for="title">Title <span class="required">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control" required>
                                        <div id="titleError" class="error-message"></div>
                                    </div>
                                    
                                    <!-- Display Order -->
                                    <div class="form-group">
                                        <label for="display_order">Display Order</label>
                                        <input type="number" id="display_order" name="display_order" class="form-control" min="1" value="1">
                                        <small class="form-text">Lower numbers appear first</small>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="form-group form-full-width">
                                        <label for="status">Status <span class="required">*</span></label>
                                        <select id="status" name="status" class="form-control" required>
                                            <option value="active" selected>Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Image Upload -->
                                    <div class="form-group form-full-width">
                                        <label for="image">Image <span class="required">*</span></label>
                                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                                        <small class="form-text">Recommended size: 1200x800 pixels, max 2MB</small>
                                        <div id="imageError" class="error-message"></div>
                                        <img id="imagePreview" class="image-preview" src="#" alt="Image Preview">
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="form-group form-full-width">
                                        <label for="content">Content <span class="required">*</span></label>
                                        <textarea id="content" name="content" class="form-control" rows="10" required></textarea>
                                        <div id="contentError" class="error-message"></div>
                                    </div>
                                    
                                    <div class="form-group form-full-width form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Save Activity
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const imagePreview = document.getElementById('imagePreview');
                    imagePreview.src = event.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Form validation
        document.getElementById('activityForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate title
            const title = document.getElementById('title');
            const titleError = document.getElementById('titleError');
            if (!title.value.trim()) {
                titleError.textContent = 'Title is required';
                isValid = false;
            } else {
                titleError.textContent = '';
            }
            
            // Validate image
            const image = document.getElementById('image');
            const imageError = document.getElementById('imageError');
            if (!image.files || image.files.length === 0) {
                imageError.textContent = 'Image is required';
                isValid = false;
            } else {
                imageError.textContent = '';
            }
            
            // Validate content
            const content = document.getElementById('content');
            const contentError = document.getElementById('contentError');
            if (!content.value.trim()) {
                contentError.textContent = 'Content is required';
                isValid = false;
            } else {
                contentError.textContent = '';
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
    <script>
        // Initialize custom editor when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new CustomEditor('.custom-editor-field');
        });
        
        // Image preview
        document.getElementById('cover_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });
        
        // Form validation
        document.getElementById('activityForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Reset error messages
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(element => {
                element.textContent = '';
            });
            
            // Validate title
            const title = document.getElementById('title').value.trim();
            if (title === '') {
                document.getElementById('titleError').textContent = 'Title is required';
                isValid = false;
            } else if (title.length < 5) {
                document.getElementById('titleError').textContent = 'Title must be at least 5 characters';
                isValid = false;
            }
            
            // Validate location
            const location = document.getElementById('location').value.trim();
            if (location === '') {
                document.getElementById('locationError').textContent = 'Location is required';
                isValid = false;
            }
            
            // Validate duration
            const duration = document.getElementById('duration').value.trim();
            if (duration === '') {
                document.getElementById('durationError').textContent = 'Duration is required';
                isValid = false;
            }
            
            // Validate price
            const price = document.getElementById('price').value;
            if (price === '') {
                document.getElementById('priceError').textContent = 'Price is required';
                isValid = false;
            } else if (isNaN(price) || parseFloat(price) < 0) {
                document.getElementById('priceError').textContent = 'Price must be a positive number';
                isValid = false;
            }
            
            // Validate cover image
            const coverImage = document.getElementById('cover_image').files[0];
            if (!coverImage) {
                document.getElementById('coverImageError').textContent = 'Cover image is required';
                isValid = false;
            } else {
                // Check file size (max 2MB)
                if (coverImage.size > 2 * 1024 * 1024) {
                    document.getElementById('coverImageError').textContent = 'Image size must be less than 2MB';
                    isValid = false;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
                if (!validTypes.includes(coverImage.type)) {
                    document.getElementById('coverImageError').textContent = 'Only JPG, PNG and WebP images are allowed';
                    isValid = false;
                }
            }
            
            // Validate summary
            const summary = document.getElementById('summary').value.trim();
            if (summary === '') {
                document.getElementById('summaryError').textContent = 'Summary is required';
                isValid = false;
            } else if (summary.length < 50) {
                document.getElementById('summaryError').textContent = 'Summary must be at least 50 characters';
                isValid = false;
            }
            
            // Validate content (TinyMCE)
            const content = tinymce.get('content').getContent().trim();
            if (content === '') {
                document.getElementById('contentError').textContent = 'Content is required';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                // Scroll to the first error
                const firstError = document.querySelector('.error-message:not(:empty)');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Toggle sidebar on mobile
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>