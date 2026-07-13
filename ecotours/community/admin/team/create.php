<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get the next order position
$order_query = "SELECT MAX(order_position) as max_order FROM community_team";
$order_result = mysqli_query($conn, $order_query);
$max_order = mysqli_fetch_assoc($order_result)['max_order'];
$next_order = $max_order ? $max_order + 1 : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Team Member - Community Admin</title>
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
        .form-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
        }

        .form-header {
            padding: 2rem;
            background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
            border-bottom: 1px solid var(--neutral-beige);
        }

        .form-header h3 {
            font-size: 1.3rem;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-header h3 i {
            color: var(--primary-green);
        }

        .form-header p {
            color: var(--text-medium);
            margin: 0;
            font-size: 0.95rem;
        }

        .team-form {
            padding: 2rem;
        }

        .form-section {
            margin-bottom: 2.5rem;
        }

        .form-section:last-of-type {
            margin-bottom: 0;
        }

        .form-section h4 {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin: 0 0 1.5rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--neutral-light);
        }

        .form-section h4 i {
            color: var(--primary-green);
        }

        .section-description {
            color: var(--text-medium);
            font-size: 0.9rem;
            margin: -1rem 0 1.5rem 0;
        }

        .image-upload-container {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .image-preview {
            width: 120px;
            height: 120px;
            border-radius: var(--border-radius-md);
            overflow: hidden;
            border: 2px solid var(--neutral-beige);
            background-color: var(--neutral-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            text-align: center;
            color: var(--text-medium);
        }

        .image-placeholder i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .image-placeholder p {
            font-size: 0.8rem;
            margin: 0;
        }

        .image-upload-controls {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .file-input {
            display: none;
        }

        .image-requirements {
            margin-top: 1rem;
        }

        .image-requirements small {
            color: var(--text-medium);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .image-upload-container {
                flex-direction: column;
                gap: 1rem;
            }

            .image-upload-controls {
                width: 100%;
            }
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
                        <h1><i class="fas fa-user-plus"></i> Add Team Member</h1>
                        <p>Add a new team member to your community team</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Team
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h3><i class="fas fa-user"></i> Team Member Information</h3>
                        <p>Fill in the details for the new team member</p>
                    </div>
                    
                    <form action="handlers/create-handler.php" method="POST" enctype="multipart/form-data" class="team-form" id="teamForm">
                        <div class="form-grid">
                            <!-- Basic Information -->
                            <div class="form-section">
                                <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                                
                                <div class="form-group">
                                    <label for="name" class="required">Full Name</label>
                                    <input type="text" id="name" name="name" required 
                                           placeholder="Enter full name">
                                </div>
                                
                                <div class="form-group">
                                    <label for="title" class="required">Job Title</label>
                                    <input type="text" id="title" name="title" required 
                                           placeholder="e.g., Community Programs Director">
                                </div>
                                
                                <div class="form-group">
                                    <label for="bio">Biography</label>
                                    <textarea id="bio" name="bio" rows="4" 
                                              placeholder="Brief biography or description of the team member"></textarea>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="order_position">Display Order</label>
                                        <input type="number" id="order_position" name="order_position" 
                                               value="<?php echo $next_order; ?>" min="1">
                                        <small>Lower numbers appear first</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select id="status" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="form-section">
                                <h4><i class="fas fa-address-book"></i> Contact Information</h4>
                                
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" 
                                           placeholder="email@example.com">
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" 
                                           placeholder="+250 XXX XXX XXX">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="form-section">
                            <h4><i class="fas fa-image"></i> Profile Image</h4>
                            
                            <div class="image-upload-container">
                                <div class="image-preview" id="imagePreview">
                                    <div class="image-placeholder">
                                        <i class="fas fa-user"></i>
                                        <p>No image selected</p>
                                    </div>
                                </div>
                                
                                <div class="image-upload-controls">
                                    <input type="file" id="image" name="image" accept="image/*" class="file-input">
                                    <label for="image" class="btn btn-outline">
                                        <i class="fas fa-upload"></i>
                                        Choose Image
                                    </label>
                                    <button type="button" class="btn btn-outline" id="removeImage" style="display: none;">
                                        <i class="fas fa-times"></i>
                                        Remove
                                    </button>
                                </div>
                                
                                <div class="image-requirements">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Recommended: Square image, minimum 300x300px, maximum 2MB
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media Links -->
                        <div class="form-section">
                            <h4><i class="fas fa-share-alt"></i> Social Media Links</h4>
                            <p class="section-description">Optional social media profiles</p>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="facebook">
                                        <i class="fab fa-facebook-f"></i>
                                        Facebook URL
                                    </label>
                                    <input type="url" id="facebook" name="facebook" 
                                           placeholder="https://facebook.com/username">
                                </div>
                                
                                <div class="form-group">
                                    <label for="twitter">
                                        <i class="fab fa-twitter"></i>
                                        Twitter URL
                                    </label>
                                    <input type="url" id="twitter" name="twitter" 
                                           placeholder="https://twitter.com/username">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="linkedin">
                                        <i class="fab fa-linkedin-in"></i>
                                        LinkedIn URL
                                    </label>
                                    <input type="url" id="linkedin" name="linkedin" 
                                           placeholder="https://linkedin.com/in/username">
                                </div>
                                
                                <div class="form-group">
                                    <label for="instagram">
                                        <i class="fab fa-instagram"></i>
                                        Instagram URL
                                    </label>
                                    <input type="url" id="instagram" name="instagram" 
                                           placeholder="https://instagram.com/username">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Add Team Member
                            </button>
                            <a href="index.php" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const removeImageBtn = document.getElementById('removeImage');
            const teamForm = document.getElementById('teamForm');

            // Image preview functionality
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file.');
                        this.value = '';
                        return;
                    }
                    
                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Image size must be less than 2MB.');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                        removeImageBtn.style.display = 'inline-flex';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove image functionality
            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.innerHTML = `
                    <div class="image-placeholder">
                        <i class="fas fa-user"></i>
                        <p>No image selected</p>
                    </div>
                `;
                this.style.display = 'none';
            });

            // Form validation
            teamForm.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value.trim();
                const title = document.getElementById('title').value.trim();
                
                if (!name) {
                    e.preventDefault();
                    alert('Please enter the team member\'s name.');
                    document.getElementById('name').focus();
                    return;
                }
                
                if (!title) {
                    e.preventDefault();
                    alert('Please enter the team member\'s job title.');
                    document.getElementById('title').focus();
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
                submitBtn.disabled = true;
                
                // Re-enable button after 10 seconds (in case of server issues)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
