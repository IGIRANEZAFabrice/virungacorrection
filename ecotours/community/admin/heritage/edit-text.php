<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = '';
$error = '';

// Get page data
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_description, intro_title, intro_lead, intro_text FROM heritage_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    header('Location: index.php?error=Page not found');
    exit;
}
$page_id = (int)$page['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title'] ?? '');
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle'] ?? '');
    $hero_description = mysqli_real_escape_string($conn, $_POST['hero_description'] ?? '');
    $intro_title = mysqli_real_escape_string($conn, $_POST['intro_title'] ?? '');
    $intro_lead = mysqli_real_escape_string($conn, $_POST['intro_lead'] ?? '');
    $intro_text = mysqli_real_escape_string($conn, $_POST['intro_text'] ?? '');

    $update_query = "UPDATE heritage_page SET 
        hero_title = '$hero_title',
        hero_subtitle = '$hero_subtitle',
        hero_description = '$hero_description',
        intro_title = '$intro_title',
        intro_lead = '$intro_lead',
        intro_text = '$intro_text'
        WHERE id = $page_id";

    if (mysqli_query($conn, $update_query)) {
        $success = 'Text content updated successfully!';
        $page['hero_title'] = $_POST['hero_title'];
        $page['hero_subtitle'] = $_POST['hero_subtitle'];
        $page['hero_description'] = $_POST['hero_description'];
        $page['intro_title'] = $_POST['intro_title'];
        $page['intro_lead'] = $_POST['intro_lead'];
        $page['intro_text'] = $_POST['intro_text'];
    } else {
        $error = 'Error updating database: ' . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Text Content</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 800px; margin: 0 auto; padding: 2rem; }
		.form-group { margin-bottom: 1.5rem; }
		.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
		.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; }
		textarea.form-control { min-height: 120px; resize: vertical; }
		.btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; border: none; }
		.btn-primary { background: #8B7355; color: white; }
		.btn-primary:hover { background: #6B5344; }
		.btn-secondary { background: #ddd; color: #333; }
		.btn-secondary:hover { background: #ccc; }
		.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
		.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.section-divider { margin: 2rem 0; padding: 2rem 0; border-top: 2px solid #eee; }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="container">
		<h1 style="margin-bottom: 2rem;">Edit Text Content</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<div style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
			<form method="POST">
				<h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Hero Section</h2>
				
				<div class="form-group">
					<label class="form-label">Hero Title</label>
					<input type="text" name="hero_title" class="form-control" value="<?php echo htmlspecialchars($page['hero_title']); ?>" required>
				</div>
				
				<div class="form-group">
					<label class="form-label">Hero Subtitle</label>
					<input type="text" name="hero_subtitle" class="form-control" value="<?php echo htmlspecialchars($page['hero_subtitle']); ?>" required>
				</div>
				
				<div class="form-group">
					<label class="form-label">Hero Description</label>
					<textarea name="hero_description" class="form-control" required><?php echo htmlspecialchars($page['hero_description']); ?></textarea>
				</div>
				
				<div class="section-divider"></div>
				
				<h2 style="margin-bottom: 1.5rem; font-size: 1.25rem;">Introduction Section</h2>
				
				<div class="form-group">
					<label class="form-label">Intro Title</label>
					<input type="text" name="intro_title" class="form-control" value="<?php echo htmlspecialchars($page['intro_title']); ?>" required>
				</div>
				
				<div class="form-group">
					<label class="form-label">Intro Lead (First Paragraph)</label>
					<textarea name="intro_lead" class="form-control" required><?php echo htmlspecialchars($page['intro_lead']); ?></textarea>
				</div>
				
				<div class="form-group">
					<label class="form-label">Intro Text (Second Paragraph)</label>
					<textarea name="intro_text" class="form-control" required><?php echo htmlspecialchars($page['intro_text']); ?></textarea>
				</div>
				
				<div style="display: flex; gap: 1rem; margin-top: 2rem;">
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save"></i> Save Changes
					</button>
					<a href="index.php" class="btn btn-secondary">← Back</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>

