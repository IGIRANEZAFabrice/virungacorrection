<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Get activity details
$query = "SELECT * FROM community_activities WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$activity = mysqli_fetch_assoc($result);

// Log admin action - only if admin_logs table exists
$admin_id = $_SESSION['community_admin_id'];
$action = "Accessed edit form for activity: " . $activity['title'];

// Check if admin_logs table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
if (mysqli_num_rows($table_check) > 0) {
    $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/edit.php', NOW())";
    mysqli_query($conn, $log_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Activity - Community Admin</title>
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
        /* Admin container layout fixes */
        .admin-container {
            display: flex;
            position: relative;
            min-height: 100vh;
            width: 100%;
        }
        
        .admin-content {
            flex: 1;
            position: relative;
            z-index: 1;
            margin-left: 250px; /* Match sidebar width */
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }
        
        .admin-main {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 60px); /* Adjust for topbar height */
        }
        
        /* Form specific styles */
        .form-container {
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow: hidden;
        }
        
        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .form-title {
            margin: 0;
            font-size: 24px;
            color: #333;
            word-break: break-word;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
        }
        
        .form-select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            box-sizing: border-box;
        }
        
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #45a049;
        }
        
        .btn-secondary {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .btn-secondary:hover {
            background-color: #dde2e6;
        }
        
        .image-preview-container {
            margin-top: 10px;
            position: relative;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: block;
        }
        
        .current-image {
            margin-bottom: 10px;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .form-control.error {
            border-color: #dc3545;
        }
        
        @media (max-width: 992px) {
            .admin-content {
                margin-left: 0;
                width: 100%;
            }
            
            .admin-main {
                padding: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .form-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .form-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .form-container {
                padding: 15px;
                margin: 10px 0;
            }
            
            .admin-main {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="admin-main">
                <div class="page-header">
                    <h1>Edit Activity</h1>
                    <p>Update activity information</p>
                </div>
                
                <div class="form-container">
                    <div class="form-header">
                        <h2 class="form-title">Edit Activity</h2>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                    
                    <form action="handlers/edit-handler.php" method="POST" enctype="multipart/form-data" id="edit-activity-form">
                        <input type="hidden" name="id" value="<?php echo $activity['id']; ?>">
                        
                        <div class="form-group">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" id="title" name="title" class="form-control" value="<?php echo htmlspecialchars($activity['title']); ?>" required>
                            <div class="error-message" id="title-error">Please enter a title</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="display_order" class="form-label">Display Order</label>
                            <input type="number" id="display_order" name="display_order" class="form-control" value="<?php echo $activity['display_order']; ?>" min="1">
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="active" <?php echo $activity['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $activity['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="draft" <?php echo $activity['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="image" class="form-label">Image</label>
                            <?php if (!empty($activity['image'])): ?>
                                <div class="current-image">
                                    <p>Current image:</p>
                                    <img src="../../uploads/activities/<?php echo $activity['image']; ?>" alt="Current Image" class="image-preview">
                                </div>
                            <?php endif; ?>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <div class="image-preview-container" id="image-preview-container" style="display: none;">
                                <img id="image-preview" class="image-preview">
                            </div>
                            <div class="error-message" id="image-error">Please select a valid image file</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="content" class="form-label">Content *</label>
                            <textarea id="content" name="content" class="form-control form-textarea" required><?php echo htmlspecialchars($activity['content']); ?></textarea>
                            <div class="error-message" id="content-error">Please enter content</div>
                        </div>
                        
                        <div class="form-actions">
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Activity
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Image preview functionality
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        });
        
        // Form validation
        document.getElementById('edit-activity-form').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate title
            const title = document.getElementById('title');
            const titleError = document.getElementById('title-error');
            
            if (title.value.trim() === '') {
                title.classList.add('error');
                titleError.style.display = 'block';
                isValid = false;
            } else {
                title.classList.remove('error');
                titleError.style.display = 'none';
            }
            
            // Validate content
            const content = document.getElementById('content');
            const contentError = document.getElementById('content-error');
            
            if (content.value.trim() === '') {
                content.classList.add('error');
                contentError.style.display = 'block';
                isValid = false;
            } else {
                content.classList.remove('error');
                contentError.style.display = 'none';
            }
            
            // Validate image if selected
            const image = document.getElementById('image');
            const imageError = document.getElementById('image-error');
            
            if (image.files.length > 0) {
                const file = image.files[0];
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                
                if (!validTypes.includes(file.type)) {
                    image.classList.add('error');
                    imageError.style.display = 'block';
                    imageError.textContent = 'Please select a valid image file (JPEG, PNG, GIF, WEBP)';
                    isValid = false;
                } else if (file.size > 5 * 1024 * 1024) { // 5MB
                    image.classList.add('error');
                    imageError.style.display = 'block';
                    imageError.textContent = 'Image size should be less than 5MB';
                    isValid = false;
                } else {
                    image.classList.remove('error');
                    imageError.style.display = 'none';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>