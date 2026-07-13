<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$errors = [];
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';
    $organization = $_POST['organization'] ?? '';
    $location = $_POST['location'] ?? '';
    $message = $_POST['message'] ?? '';
    $rating = (int)($_POST['rating'] ?? 0);
    $program_id = (int)($_POST['program_id'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'inactive';
    $featured = isset($_POST['featured']) ? 1 : 0;

    // Validation
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($message)) $errors[] = 'Testimonial message is required';
    if ($rating < 1 || $rating > 5) $errors[] = 'Rating must be between 1 and 5';

    // Handle file upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = 'Only JPG, PNG, and GIF images are allowed';
        } elseif ($_FILES['image']['size'] > $max_size) {
            $errors[] = 'Image size must be less than 2MB';
        } else {
            $upload_dir = '../../assets/images/testimonials/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('testimonial_', true) . '.' . $extension;
            $destination = $upload_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = $filename;
            } else {
                $errors[] = 'Failed to upload image';
            }
        }
    }

    // If no errors, insert into database
    if (empty($errors)) {
        $query = "INSERT INTO community_testimonials 
                  (name, role, organization, location, message, rating, program_id, status, featured, image) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssiissi', 
            $name, $role, $organization, $location, $message, 
            $rating, $program_id, $status, $featured, $image_path);
        
        if (mysqli_stmt_execute($stmt)) {
            $success_message = 'Testimonial created successfully!';
            // Clear form
            $name = $role = $organization = $location = $message = '';
            $rating = 0;
            $program_id = 0;
            $status = 'inactive';
            $featured = 0;
        } else {
            $errors[] = 'Error creating testimonial: ' . mysqli_error($conn);
        }
    }
}

// Get programs for dropdown
$programs_query = "SELECT id, title FROM community_programs ORDER BY title";
$programs_result = mysqli_query($conn, $programs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Testimonial - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
      /* Testimonial Form Styles */
      .form-card {
        background: white;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        padding: 2rem;
        margin-bottom: 2rem;
      }

      .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
      }

      .form-section {
        background: var(--neutral-light);
        padding: 1.5rem;
        border-radius: var(--border-radius-md);
        border: 1px solid var(--neutral-beige);
      }

      .form-section h3 {
        color: var(--primary-green);
        margin-bottom: 1rem;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .image-upload-container {
        margin-top: 1rem;
      }

      input {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border: 1px solid var(--neutral-beige);
        border-radius: var(--border-radius-sm);
        background: var(--neutral-cream);
        color: var(--text-dark);
        font-size: 1rem;
        width: 100%;
      }

      textarea {
        height: 100px;
        resize: vertical;
        padding: 0.5rem;
        border: 1px solid var(--neutral-beige);
        border-radius: var(--border-radius-sm);
        background: var(--neutral-cream);
        color: var(--text-dark);
        font-size: 1rem;
        width: 100%;
      }

      select {
        padding: 0.5rem;
        border: 1px solid var(--neutral-beige);
        border-radius: var(--border-radius-sm);
        background: var(--neutral-cream);
        color: var(--text-dark);
        font-size: 1rem;
        width: 100%;
      }

      .image-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: var(--neutral-cream);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        border: 2px dashed var(--neutral-beige);
      }

      .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .image-preview-default {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--text-medium);
      }

      .image-preview-default i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
      }

      .rating-input {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
      }

      .rating-input input[type="radio"] {
        display: none;
      }

      .rating-input label {
        cursor: pointer;
        color: var(--neutral-beige);
        font-size: 1.5rem;
        transition: color 0.2s;
      }

      .rating-input input[type="radio"]:checked ~ label,
      .rating-input input[type="radio"]:hover ~ label {
        color: var(--accent-terracotta);
      }

      .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
      }

      @media (max-width: 768px) {
        .form-grid {
          grid-template-columns: 1fr;
        }

        .form-actions {
          flex-direction: column;
        }
      }

      .image-upload-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
      }

      .image-upload-container input[type="file"] {
        display: none;
      }

      .image-upload-container label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--accent-terracotta);
        color: white;
        border-radius: var(--border-radius-sm);
        transition: background 0.2s;
      }

      /* .image-upload-container label:hover {
        background: var(--accent-terracotta-dark);
      } */

      .image-upload-container i {
        font-size: 1.2rem;
      }
    </style>
    <link rel="stylesheet" href="../assets/css/management.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
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
                        <h1><i class="fas fa-quote-left"></i> Add New Testimonial</h1>
                        <p>Create a new community testimonial</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to List
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Testimonial Form -->
                <div class="form-card">
                    <form method="POST" enctype="multipart/form-data" id="testimonialForm">
                        <div class="form-grid">
                            <!-- Personal Information -->
                            <div class="form-section">
                                <h3><i class="fas fa-user"></i> Personal Information</h3>
                                
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" id="name" name="name" 
                                           value="<?php echo htmlspecialchars($name ?? ''); ?>" 
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="role">Role/Position</label>
                                    <input type="text" id="role" name="role" 
                                           value="<?php echo htmlspecialchars($role ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="organization">Organization</label>
                                    <input type="text" id="organization" name="organization" 
                                           value="<?php echo htmlspecialchars($organization ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" id="location" name="location" 
                                           value="<?php echo htmlspecialchars($location ?? ''); ?>">
                                </div>
                            </div>
                            
                            <!-- Testimonial Content -->
                            <div class="form-section">
                                <h3><i class="fas fa-comment"></i> Testimonial Content</h3>
                                
                                <div class="form-group">
                                    <label for="message">Testimonial Message *</label>
                                    <textarea id="message" name="message" rows="5" required><?php 
                                        echo htmlspecialchars($message ?? ''); 
                                    ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="rating">Rating (1-5) *</label>
                                    <div class="rating-input">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <input type="radio" id="rating<?php echo $i; ?>" name="rating" 
                                                   value="<?php echo $i; ?>" 
                                                   <?php echo ($rating ?? 0) == $i ? 'checked' : ''; ?>>
                                            <label for="rating<?php echo $i; ?>">
                                                <i class="fas fa-star"></i>
                                            </label>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="program_id">Related Program</label>
                                    <select id="program_id" name="program_id">
                                        <option value="0">No Specific Program</option>
                                        <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                                            <option value="<?php echo $program['id']; ?>"
                                                <?php echo ($program_id ?? 0) == $program['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($program['title']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Media & Settings -->
                            <div class="form-section">
                                <h3><i class="fas fa-cog"></i> Settings</h3>
                                
                                <div class="form-group">
                                    <label for="image">Profile Image</label>
                                    <div class="image-upload-container">
                                        <div class="image-preview" id="imagePreview">
                                            <div class="image-preview-default">
                                                <i class="fas fa-user"></i>
                                                <span>No image selected</span>
                                            </div>
                                        </div>
                                        <input type="file" id="image" name="image" accept="image/*" 
                                               class="image-upload-input">
                                        <label for="image" class="btn btn-outline image-upload-label">
                                            <i class="fas fa-upload"></i> Choose Image
                                        </label>
                                        <small class="form-text">Max size: 2MB (JPEG, PNG, GIF)</small>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active" <?php echo ($status ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($status ?? '') == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <div class="checkbox-group">
                                        <input type="checkbox" id="featured" name="featured" 
                                               value="1" <?php echo ($featured ?? 0) ? 'checked' : ''; ?>>
                                        <label for="featured">Featured Testimonial</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Save Testimonial
                            </button>
                            <button type="reset" class="btn btn-outline">
                                <i class="fas fa-undo"></i>
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/form-validation.js"></script>
    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Form validation
        document.getElementById('testimonialForm').addEventListener('submit', function(e) {
            let valid = true;
            
            // Validate name
            if (!document.getElementById('name').value.trim()) {
                alert('Please enter a name');
                valid = false;
            }
            
            // Validate message
            if (!document.getElementById('message').value.trim()) {
                alert('Please enter a testimonial message');
                valid = false;
            }
            
            // Validate rating
            if (!document.querySelector('input[name="rating"]:checked')) {
                alert('Please select a rating');
                valid = false;
            }
            
            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
<?php
// Close database connection
mysqli_close($conn);
?>