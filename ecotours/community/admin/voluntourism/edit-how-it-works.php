<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$how_it_works = null;
$how_it_works_query = "SELECT * FROM voluntourism_how_it_works LIMIT 1";
$how_it_works_result = mysqli_query($conn, $how_it_works_query);
$how_it_works = mysqli_fetch_assoc($how_it_works_result);

$features_query = "SELECT * FROM voluntourism_how_it_works_features ORDER BY display_order";
$features_result = mysqli_query($conn, $features_query);
$features = [];
while ($feature = mysqli_fetch_assoc($features_result)) {
    $features[] = $feature;
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $upload_dir = '../../../images/voluntourism/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $section_title = mysqli_real_escape_string($conn, $_POST['section_title']);
    $section_description = mysqli_real_escape_string($conn, $_POST['section_description']);
    $process_image = $how_it_works['process_image'];
    $overlay_title = mysqli_real_escape_string($conn, $_POST['overlay_title']);
    $overlay_description = mysqli_real_escape_string($conn, $_POST['overlay_description']);

    // Handle process image upload
    if (isset($_FILES['process_image']) && $_FILES['process_image']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES['process_image']['name'], PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            $new_filename = 'process_' . time() . '.' . $file_extension;
            $upload_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['process_image']['tmp_name'], $upload_path)) {
                if ($how_it_works['process_image'] && file_exists($upload_dir . basename($how_it_works['process_image']))) {
                    @unlink($upload_dir . basename($how_it_works['process_image']));
                }
                $process_image = '../images/voluntourism/' . $new_filename;
            } else {
                $error_message = "Failed to upload process image.";
            }
        } else {
            $error_message = "Invalid image format. Please use JPG, PNG, or WebP.";
        }
    }

    $process_image = mysqli_real_escape_string($conn, $process_image);

    if (!$error_message && $how_it_works) {
        $update_query = "UPDATE voluntourism_how_it_works SET
            section_title = '$section_title',
            section_description = '$section_description',
            process_image = '$process_image',
            overlay_title = '$overlay_title',
            overlay_description = '$overlay_description'
            WHERE id = {$how_it_works['id']}";

        if (mysqli_query($conn, $update_query)) {
            // Update features
            for ($i = 1; $i <= 4; $i++) {
                if (isset($_POST["feature_icon_$i"]) && isset($_POST["feature_title_$i"])) {
                    $feature_icon = mysqli_real_escape_string($conn, $_POST["feature_icon_$i"]);
                    $feature_title = mysqli_real_escape_string($conn, $_POST["feature_title_$i"]);
                    $feature_description = mysqli_real_escape_string($conn, $_POST["feature_description_$i"]);

                    if (isset($features[$i-1])) {
                        $feature_update = "UPDATE voluntourism_how_it_works_features SET
                            feature_icon = '$feature_icon',
                            feature_title = '$feature_title',
                            feature_description = '$feature_description'
                            WHERE id = {$features[$i-1]['id']}";
                        mysqli_query($conn, $feature_update);
                    }
                }
            }
            $success_message = "How It Works section updated successfully!";
            $how_it_works_result = mysqli_query($conn, $how_it_works_query);
            $how_it_works = mysqli_fetch_assoc($how_it_works_result);
        } else {
            $error_message = "Error updating section: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit How It Works - Voluntourism</title>
    <meta name="robots" content="noindex, nofollow">
<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">

    <link rel="stylesheet" href="../../../css/earthy-theme.css">
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
                        <h1><i class="fas fa-cogs"></i> Edit How It Works Section</h1>
                        <p>Manage the How It Works section with process image and features</p>
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
                                <h3><i class="fas fa-heading"></i> Section Content</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="section_title">Section Title *</label>
                                    <input type="text" id="section_title" name="section_title" value="<?php echo htmlspecialchars($how_it_works['section_title'] ?? ''); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="section_description">Section Description *</label>
                                    <textarea id="section_description" name="section_description" rows="5" required><?php echo htmlspecialchars($how_it_works['section_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-image"></i> Process Image</h3>
                            </div>
                            <div class="form-section-content" style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                                <?php if ($how_it_works['process_image']):
                                    $preview_path = str_replace('../images/', '../../images/', $how_it_works['process_image']);
                                ?>
                                    <div style="margin-bottom: 15px;">
                                        <img src="<?php echo htmlspecialchars($preview_path); ?>" alt="Process image" style="max-width: 200px; height: auto; border-radius: 8px;">
                                        <p><small>Current image. Upload a new file to replace it.</small></p>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="process_image">Upload Process Image</label>
                                    <input type="file" id="process_image" name="process_image" accept="image/*">
                                    <small>JPG, PNG, or WebP</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-layer-group"></i> Overlay Content</h3>
                            </div>
                            <div class="form-section-content">
                                <div class="form-group">
                                    <label for="overlay_title">Overlay Title *</label>
                                    <input type="text" id="overlay_title" name="overlay_title" value="<?php echo htmlspecialchars($how_it_works['overlay_title'] ?? ''); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="overlay_description">Overlay Description *</label>
                                    <textarea id="overlay_description" name="overlay_description" rows="5" required><?php echo htmlspecialchars($how_it_works['overlay_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-header">
                                <h3><i class="fas fa-star"></i> Features (4 Items)</h3>
                            </div>
                            <div class="form-section-content">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                        <div style="background: #f9f9f9; padding: 15px; border-radius: 8px;">
                                            <h4 style="margin-top: 0;">Feature <?php echo $i; ?></h4>
                                            <div class="form-group">
                                                <label for="feature_icon_<?php echo $i; ?>">Icon Class</label>
                                                <input type="text" id="feature_icon_<?php echo $i; ?>" name="feature_icon_<?php echo $i; ?>" value="<?php echo htmlspecialchars($features[$i-1]['feature_icon'] ?? ''); ?>" placeholder="fas fa-clock">
                                            </div>
                                            <div class="form-group">
                                                <label for="feature_title_<?php echo $i; ?>">Title *</label>
                                                <input type="text" id="feature_title_<?php echo $i; ?>" name="feature_title_<?php echo $i; ?>" value="<?php echo htmlspecialchars($features[$i-1]['feature_title'] ?? ''); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="feature_description_<?php echo $i; ?>">Description *</label>
                                                <textarea id="feature_description_<?php echo $i; ?>" name="feature_description_<?php echo $i; ?>" rows="3" required><?php echo htmlspecialchars($features[$i-1]['feature_description'] ?? ''); ?></textarea>
                                            </div>
                                        </div>
                                    <?php endfor; ?>
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

