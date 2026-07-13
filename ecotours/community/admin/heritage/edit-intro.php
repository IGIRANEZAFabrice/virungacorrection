<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = '';
$error = '';

// Get page data
$page = null;
$pq = mysqli_query($conn, "SELECT id, intro_image FROM heritage_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    header('Location: index.php?error=Page not found');
    exit;
}
$page_id = (int)$page['id'];

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['intro_image']['name'])) {
    $upload_dir = '../../../community/assets/images/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $file_name = basename($_FILES['intro_image']['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = 'heritage-intro-' . time() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['intro_image']['tmp_name'], $upload_path)) {
            $intro_image = 'assets/images/' . $new_file_name;
            $update_query = "UPDATE heritage_page SET intro_image = '" . mysqli_real_escape_string($conn, $intro_image) . "' WHERE id = $page_id";
            if (mysqli_query($conn, $update_query)) {
                $success = 'Intro image updated successfully!';
                $page['intro_image'] = $intro_image;
            } else {
                $error = 'Error updating database: ' . mysqli_error($conn);
            }
        } else {
            $error = 'Error uploading image';
        }
    } else {
        $error = 'Invalid file type. Allowed: jpg, jpeg, png, gif, webp';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Intro Image</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 600px; margin: 0 auto; padding: 2rem; }
		.form-group { margin-bottom: 1.5rem; }
		.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
		.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; }
		.btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; border: none; }
		.btn-primary { background: #8B7355; color: white; }
		.btn-primary:hover { background: #6B5344; }
		.btn-secondary { background: #ddd; color: #333; }
		.btn-secondary:hover { background: #ccc; }
		.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
		.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.image-preview { margin-bottom: 1rem; }
		.image-preview img { max-width: 100%; height: auto; border-radius: 4px; }
		.image-preview-placeholder { width: 100%; height: 300px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999; }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="container">
		<h1 style="margin-bottom: 2rem;">Edit Intro Image</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
			<h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Current Intro Image</h2>
			<div class="image-preview">
				<?php if (!empty($page['intro_image'])): ?>
					<img src="<?php echo htmlspecialchars($page['intro_image']); ?>" alt="Intro Image">
				<?php else: ?>
					<div class="image-preview-placeholder">No image uploaded</div>
				<?php endif; ?>
			</div>
			
			<form method="POST" enctype="multipart/form-data">
				<div class="form-group">
					<label class="form-label">Upload New Intro Image</label>
					<input type="file" name="intro_image" class="form-control" accept="image/*" required>
					<small style="color: #666;">Allowed formats: JPG, PNG, GIF, WebP</small>
				</div>
				
				<div style="display: flex; gap: 1rem;">
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save"></i> Save Image
					</button>
					<a href="index.php" class="btn btn-secondary">← Back</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>

