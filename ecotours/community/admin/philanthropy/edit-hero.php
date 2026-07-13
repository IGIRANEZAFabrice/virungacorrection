<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get hero content
$hero_query = "SELECT * FROM philanthropy_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title']);
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle']);
    $hero_description = mysqli_real_escape_string($conn, $_POST['hero_description']);
    $stat1_number = mysqli_real_escape_string($conn, $_POST['stat1_number']);
    $stat1_label = mysqli_real_escape_string($conn, $_POST['stat1_label']);
    $stat2_number = mysqli_real_escape_string($conn, $_POST['stat2_number']);
    $stat2_label = mysqli_real_escape_string($conn, $_POST['stat2_label']);
    $stat3_number = mysqli_real_escape_string($conn, $_POST['stat3_number']);
    $stat3_label = mysqli_real_escape_string($conn, $_POST['stat3_label']);

    // Handle image upload
    $hero_image = $hero['hero_image'] ?? '';
    if (!empty($_FILES['hero_image']['name'])) {
        $upload_dir = '../../../community/assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = basename($_FILES['hero_image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = 'hero-' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_path)) {
                $hero_image = 'assets/images/' . $new_file_name;
            } else {
                $error = "Error uploading image";
            }
        } else {
            $error = "Invalid file type. Allowed: jpg, jpeg, png, gif, webp";
        }
    }

    if (!isset($error)) {
        if ($hero) {
            // Update existing
            $update_query = "UPDATE philanthropy_hero SET
                hero_title = '$hero_title',
                hero_subtitle = '$hero_subtitle',
                hero_description = '$hero_description',
                hero_image = '$hero_image',
                stat1_number = '$stat1_number',
                stat1_label = '$stat1_label',
                stat2_number = '$stat2_number',
                stat2_label = '$stat2_label',
                stat3_number = '$stat3_number',
                stat3_label = '$stat3_label'
                WHERE id = " . $hero['id'];

            if (mysqli_query($conn, $update_query)) {
                header('Location: index.php?success=Hero section updated successfully');
                exit;
            } else {
                $error = "Error updating hero: " . mysqli_error($conn);
            }
        } else {
            // Insert new
            $insert_query = "INSERT INTO philanthropy_hero
                (hero_title, hero_subtitle, hero_description, hero_image, stat1_number, stat1_label, stat2_number, stat2_label, stat3_number, stat3_label)
                VALUES ('$hero_title', '$hero_subtitle', '$hero_description', '$hero_image', '$stat1_number', '$stat1_label', '$stat2_number', '$stat2_label', '$stat3_number', '$stat3_label')";

            if (mysqli_query($conn, $insert_query)) {
                header('Location: index.php?success=Hero section created successfully');
                exit;
            } else {
                $error = "Error creating hero: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - Philanthropy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
</head>

<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="content-area">
                <div class="page-header">
                    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
                    <h1>Edit Hero Section</h1>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" class="edit-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="hero_title">Hero Title *</label>
                            <input type="text" id="hero_title" name="hero_title" required
                                   value="<?php echo htmlspecialchars($hero['hero_title'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="hero_subtitle">Hero Subtitle *</label>
                            <input type="text" id="hero_subtitle" name="hero_subtitle" required
                                   value="<?php echo htmlspecialchars($hero['hero_subtitle'] ?? ''); ?>">
                        </div>

                        <div class="form-group">
                            <label for="hero_description">Hero Description *</label>
                            <textarea id="hero_description" name="hero_description" rows="5" required><?php echo htmlspecialchars($hero['hero_description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="hero_image">Hero Image</label>
                            <?php if (!empty($hero['hero_image'])): ?>
                                <div class="image-preview">
                                    <img src="<?php echo htmlspecialchars($hero['hero_image']); ?>" alt="Hero Image" style="max-width: 200px; max-height: 150px;">
                                    <p style="font-size: 0.9rem; color: #666; margin-top: 5px;">Current image</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="hero_image" name="hero_image" accept="image/*">
                            <small>Allowed formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                        </div>

                        <div class="form-section">
                            <h3>Statistics</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="stat1_number">Stat 1 Number *</label>
                                    <input type="text" id="stat1_number" name="stat1_number" required 
                                           value="<?php echo htmlspecialchars($hero['stat1_number'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="stat1_label">Stat 1 Label *</label>
                                    <input type="text" id="stat1_label" name="stat1_label" required 
                                           value="<?php echo htmlspecialchars($hero['stat1_label'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="stat2_number">Stat 2 Number *</label>
                                    <input type="text" id="stat2_number" name="stat2_number" required 
                                           value="<?php echo htmlspecialchars($hero['stat2_number'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="stat2_label">Stat 2 Label *</label>
                                    <input type="text" id="stat2_label" name="stat2_label" required 
                                           value="<?php echo htmlspecialchars($hero['stat2_label'] ?? ''); ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="stat3_number">Stat 3 Number *</label>
                                    <input type="text" id="stat3_number" name="stat3_number" required 
                                           value="<?php echo htmlspecialchars($hero['stat3_number'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="stat3_label">Stat 3 Label *</label>
                                    <input type="text" id="stat3_label" name="stat3_label" required 
                                           value="<?php echo htmlspecialchars($hero['stat3_label'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="index.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

