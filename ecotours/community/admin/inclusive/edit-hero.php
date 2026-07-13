<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Get page data
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_image, intro_text FROM inclusive_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    mysqli_query($conn, "INSERT INTO inclusive_page (hero_title, hero_subtitle, hero_image, intro_text) VALUES ('Inclusive Community-Based Tourism', 'Empowering Persons with Disabilities in Rural Areas', 'assets/images/inclusive-hero.jpg', '')");
    $page = ['id' => mysqli_insert_id($conn), 'hero_title' => 'Inclusive Community-Based Tourism', 'hero_subtitle' => 'Empowering Persons with Disabilities in Rural Areas', 'hero_image' => 'assets/images/inclusive-hero.jpg', 'intro_text' => ''];
}
$page_id = (int)$page['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title'] ?? '');
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle'] ?? '');
    $intro_text = mysqli_real_escape_string($conn, $_POST['intro_text'] ?? '');
    $hero_image = $page['hero_image'] ?? '';

    // Handle image upload
    if (!empty($_FILES['hero_image']['name'])) {
        $upload_dir = '../../../community/assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = basename($_FILES['hero_image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = 'inclusive-hero-' . time() . '.' . $file_ext;
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

    // Update database
    if (empty($error)) {
        $update_query = "UPDATE inclusive_page SET hero_title = '$hero_title', hero_subtitle = '$hero_subtitle', hero_image = '$hero_image', intro_text = '$intro_text' WHERE id = $page_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: edit-hero.php?success=1");
            exit;
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - Inclusive Page</title>
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-container { max-width: 900px; margin: 0 auto; padding: 2rem; }
        .form-section { background: white; padding: 2rem; border-radius: 8px; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .image-preview { margin: 1rem 0; }
        .image-preview img { max-width: 300px; max-height: 200px; border-radius: 4px; }
        .preview-label { font-size: 0.9rem; color: #666; margin-top: 0.5rem; }
        .btn-group { display: flex; gap: 1rem; }
        .btn { padding: 0.75rem 1.5rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #2a4858; color: white; }
        .btn-primary:hover { background: #1a3a48; }
        .btn-secondary { background: #ddd; color: #333; }
        .btn-secondary:hover { background: #ccc; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #2a4858; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body class="admin-layout">

    
    <div class="admin-content">
        <div class="edit-container">
            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            
            <h1>Edit Hero Section</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Hero section updated successfully!
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-section">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="hero_title">Hero Title</label>
                        <input type="text" id="hero_title" name="hero_title" value="<?php echo htmlspecialchars($page['hero_title'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hero_subtitle">Hero Subtitle</label>
                        <input type="text" id="hero_subtitle" name="hero_subtitle" value="<?php echo htmlspecialchars($page['hero_subtitle'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hero_image">Hero Image</label>
                        <?php if (!empty($page['hero_image'])): ?>
                            <div class="image-preview">
                                <img src="<?php echo htmlspecialchars($page['hero_image']); ?>" alt="Hero Image">
                                <p class="preview-label">Current image</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="hero_image" name="hero_image" accept="image/*">
                        <small>Allowed formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="intro_text">Introduction Text</label>
                        <textarea id="intro_text" name="intro_text"><?php echo htmlspecialchars($page['intro_text'] ?? ''); ?></textarea>
                    </div>

                    <div class="btn-group">
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
</body>
</html>
<?php mysqli_close($conn); ?>

