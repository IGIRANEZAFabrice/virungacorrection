<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get stories
$stories_query = "SELECT * FROM philanthropy_stories ORDER BY display_order ASC";
$stories_result = mysqli_query($conn, $stories_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        $id = intval($_POST['id']);
        $story_title = mysqli_real_escape_string($conn, $_POST['story_title']);
        $story_excerpt = mysqli_real_escape_string($conn, $_POST['story_excerpt']);

        // Get current story to get existing image
        $current_query = "SELECT story_image FROM philanthropy_stories WHERE id = $id";
        $current_result = mysqli_query($conn, $current_query);
        $current = mysqli_fetch_assoc($current_result);
        $story_image = $current['story_image'] ?? '';

        // Handle image upload
        if (!empty($_FILES['story_image']['name'])) {
            $upload_dir = '../../../community/assets/images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = basename($_FILES['story_image']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = 'story-' . $id . '-' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($_FILES['story_image']['tmp_name'], $upload_path)) {
                    $story_image = 'assets/images/' . $new_file_name;
                }
            }
        }

        $story_image = mysqli_real_escape_string($conn, $story_image);
        $update_query = "UPDATE philanthropy_stories SET
            story_title = '$story_title',
            story_excerpt = '$story_excerpt',
            story_image = '$story_image'
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            header('Location: edit-stories.php?success=Story updated successfully');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Impact Stories - Philanthropy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .stories-list {
            display: grid;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .story-item {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .story-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .story-image-preview {
            width: 80px;
            height: 80px;
            border-radius: 4px;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 2rem;
        }
        
        .story-title-display {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="content-area">
                <div class="page-header">
                    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
                    <h1>Edit Impact Stories</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="stories-list">
                    <?php while ($story = mysqli_fetch_assoc($stories_result)): ?>
                        <div class="story-item">
                            <div class="story-header">
                                <div class="story-image-preview">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="story-title-display">
                                    <?php echo htmlspecialchars($story['story_title']); ?>
                                </div>
                            </div>

                            <form method="POST" class="edit-form" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $story['id']; ?>">

                                <div class="form-group">
                                    <label for="story_title_<?php echo $story['id']; ?>">Story Title *</label>
                                    <input type="text" id="story_title_<?php echo $story['id']; ?>" name="story_title" required
                                           value="<?php echo htmlspecialchars($story['story_title']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="story_excerpt_<?php echo $story['id']; ?>">Story Excerpt *</label>
                                    <textarea id="story_excerpt_<?php echo $story['id']; ?>" name="story_excerpt" rows="4" required><?php echo htmlspecialchars($story['story_excerpt']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="story_image_<?php echo $story['id']; ?>">Story Image</label>
                                    <?php if (!empty($story['story_image'])): ?>
                                        <div class="image-preview" style="margin-bottom: 1rem;">
                                            <img src="<?php echo htmlspecialchars($story['story_image']); ?>" alt="Story Image" style="max-width: 200px; max-height: 150px; border-radius: 4px;">
                                            <p style="font-size: 0.9rem; color: #666; margin-top: 5px;">Current image</p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" id="story_image_<?php echo $story['id']; ?>" name="story_image" accept="image/*">
                                    <small>Allowed formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

