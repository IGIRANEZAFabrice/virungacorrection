<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$hero = null;
$hero_query = "SELECT * FROM voluntourism_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $upload_dir = '../../../images/voluntourism/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title']);
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle']);
    $hero_description = mysqli_real_escape_string($conn, $_POST['hero_description']);
    $intro_title = mysqli_real_escape_string($conn, $_POST['intro_title']);
    $intro_description = mysqli_real_escape_string($conn, $_POST['intro_description']);

    $hero_image = $hero['hero_image'];
    $intro_image = $hero['intro_image'];

    // Handle hero image upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'hero_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $upload_path)) {
                if ($hero['hero_image'] && file_exists($upload_dir . basename($hero['hero_image']))) {
                    @unlink($upload_dir . basename($hero['hero_image']));
                }
                $hero_image = '../images/voluntourism/' . $new_filename;
            } else {
                $error_message = "Failed to upload hero image.";
            }
        } else {
            $error_message = "Invalid image format. Please use JPG, PNG, or WebP.";
        }
    }

    // Handle intro image upload
    if (isset($_FILES['intro_image']) && $_FILES['intro_image']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES['intro_image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'intro_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['intro_image']['tmp_name'], $upload_path)) {
                if ($hero['intro_image'] && file_exists($upload_dir . basename($hero['intro_image']))) {
                    @unlink($upload_dir . basename($hero['intro_image']));
                }
                $intro_image = '../images/voluntourism/' . $new_filename;
            } else {
                $error_message = "Failed to upload intro image.";
            }
        } else {
            $error_message = "Invalid image format. Please use JPG, PNG, or WebP.";
        }
    }
    
    if (!$error_message && $hero) {
        $update_query = "UPDATE voluntourism_hero SET 
            hero_title = '$hero_title',
            hero_subtitle = '$hero_subtitle',
            hero_description = '$hero_description',
            intro_title = '$intro_title',
            intro_description = '$intro_description',
            hero_image = '$hero_image',
            intro_image = '$intro_image'
            WHERE id = {$hero['id']}";
        
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Hero section updated successfully!";
            $hero_result = mysqli_query($conn, $hero_query);
            $hero = mysqli_fetch_assoc($hero_result);
        } else {
            $error_message = "Error updating hero section: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - Voluntourism</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">

    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">

    <style>
        body { background-color: var(--neutral-light); }
        .admin-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .content-area { flex: 1; padding: 30px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { color: var(--primary-green); font-size: 28px; margin-bottom: 5px; }
        .page-header p { color: var(--text-medium); font-size: 14px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .admin-form { background: white; padding: 30px; border-radius: 8px; box-shadow: var(--shadow-md); }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .form-section { background: var(--neutral-light); padding: 20px; border-radius: 8px; }
        .form-section-header { margin-bottom: 20px; border-bottom: 2px solid var(--primary-green); padding-bottom: 10px; }
        .form-section-header h3 { color: var(--primary-green); font-size: 18px; margin: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--neutral-beige); border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-green); box-shadow: 0 0 5px rgba(1, 105, 5, 0.3); }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group small { color: var(--text-medium); font-size: 12px; }
        .form-actions { display: flex; gap: 15px; justify-content: center; margin-top: 30px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-primary { background-color: var(--primary-green); color: white; }
        .btn-primary:hover { background-color: var(--accent-sage); }
        .btn-secondary { background-color: var(--neutral-beige); color: var(--text-dark); }
        .btn-secondary:hover { background-color: var(--accent-light-brown); }
        .current-image { margin-bottom: 15px; }
        .current-image img { max-width: 200px; height: auto; border-radius: 8px; box-shadow: var(--shadow-md); }
        .image-section { background: var(--neutral-cream); padding: 20px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include '../includes/sidebar.php'; ?>
        <div class="main-content">
            <?php include '../includes/topbar.php'; ?>
            <div class="content-area">
                <div class="page-header">
                    <div class="page-header-content">
                        <h1><i class="fas fa-image"></i> Edit Hero Section</h1>
                        <p>Manage hero title, subtitle, images, and introduction content</p>
                    </div>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-grid">
                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-heading"></i> Hero Content</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="hero_title">Hero Title *</label>
                                    <input type="text" id="hero_title" name="hero_title" required value="<?php echo htmlspecialchars($hero['hero_title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="hero_subtitle">Hero Subtitle *</label>
                                    <input type="text" id="hero_subtitle" name="hero_subtitle" required value="<?php echo htmlspecialchars($hero['hero_subtitle'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="hero_description">Hero Description *</label>
                                    <textarea id="hero_description" name="hero_description" rows="5" required><?php echo htmlspecialchars($hero['hero_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-image"></i> Hero Image</h3>
                            </div>
                            <div class="form-section-content image-section">
                                <?php if ($hero['hero_image']):
                                    $preview_path = str_replace('../images/', '../../../images/', $hero['hero_image']);
                                ?>
                                    <div class="current-image">
                                        <img src="<?php echo htmlspecialchars($preview_path); ?>" alt="Hero image">
                                        <p><small>Current image. Upload a new file to replace it.</small></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="hero_image">Upload Hero Image</label>
                                    <input type="file" id="hero_image" name="hero_image" accept="image/*">
                                    <small>JPG, PNG, or WebP (recommended: 1920x1080px)</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-align-left"></i> Introduction Content</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="intro_title">Introduction Title *</label>
                                    <input type="text" id="intro_title" name="intro_title" required value="<?php echo htmlspecialchars($hero['intro_title'] ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="intro_description">Introduction Description *</label>
                                    <textarea id="intro_description" name="intro_description" rows="5" required><?php echo htmlspecialchars($hero['intro_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-image"></i> Introduction Image</h3>
                            </div>
                            <div class="form-section-content image-section">
                                <?php if ($hero['intro_image']):
                                    $preview_path = str_replace('../images/', '../../../images/', $hero['intro_image']);
                                ?>
                                    <div class="current-image">
                                        <img src="<?php echo htmlspecialchars($preview_path); ?>" alt="Intro image">
                                        <p><small>Current image. Upload a new file to replace it.</small></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="intro_image">Upload Introduction Image</label>
                                    <input type="file" id="intro_image" name="intro_image" accept="image/*">
                                    <small>JPG, PNG, or WebP</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

