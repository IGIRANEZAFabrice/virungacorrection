<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Ensure page row
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_image, intro_title, intro_image FROM heritage_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) { 
    $page = mysqli_fetch_assoc($pq); 
} else {
    mysqli_query($conn, "INSERT INTO heritage_page (hero_title, hero_subtitle, hero_description, hero_image, intro_title, intro_lead, intro_text, intro_image, intro_caption) VALUES ('From Fields and Culture to Futures', 'Tourism as a Catalyst for Community Development', 'Discover how farm and cultural tourism transform communities...', 'assets/images/heritage-hero.jpg', 'Tourism as a Catalyst for Transformation', 'In the heart of the Virunga region...', 'Through farm and cultural tourism...', 'assets/images/heritage-intro.jpg', 'Local communities engaging visitors')");
    $page = ['id' => mysqli_insert_id($conn), 'hero_title' => 'From Fields and Culture to Futures', 'hero_subtitle' => 'Tourism as a Catalyst for Community Development', 'hero_image' => 'assets/images/heritage-hero.jpg', 'intro_title' => 'Tourism as a Catalyst for Transformation', 'intro_image' => 'assets/images/heritage-intro.jpg'];
}
$page_id = (int)$page['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Heritage Page</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.admin-container { max-width: 1200px; margin: 0 auto; padding: 2rem; }
		.section-card { background: white; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
		.section-title { font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem; color: #333; }
		.btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; border: none; }
		.btn-primary { background: #8B7355; color: white; }
		.btn-primary:hover { background: #6B5344; }
		.btn-secondary { background: #ddd; color: #333; }
		.btn-secondary:hover { background: #ccc; }
		.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
		.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
		@media (max-width: 768px) { .grid-2 { grid-template-columns: 1fr; } }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
		<h1 style="margin-bottom: 2rem;">Heritage Page Management</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<!-- Hero & Intro Section -->
		<div class="section-card">
			<h2 class="section-title">Hero & Introduction Section</h2>
			<p style="color: #666; margin-bottom: 1.5rem;">Edit hero image and introduction image</p>
			<div class="grid-2">
				<div>
					<h3 style="margin-bottom: 1rem;">Hero Image</h3>
					<?php if (!empty($page['hero_image'])): ?>
						<img src="<?php echo htmlspecialchars($page['hero_image']); ?>" alt="Hero" style="max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 1rem;">
					<?php else: ?>
						<div style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 4px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #999;">No image</div>
					<?php endif; ?>
					<a href="edit-hero.php" class="btn btn-primary" style="width: 100%; text-align: center;">
						<i class="fas fa-image"></i> Edit Hero Image
					</a>
				</div>
				<div>
					<h3 style="margin-bottom: 1rem;">Intro Image</h3>
					<?php if (!empty($page['intro_image'])): ?>
						<img src="<?php echo htmlspecialchars($page['intro_image']); ?>" alt="Intro" style="max-width: 100%; height: auto; border-radius: 4px; margin-bottom: 1rem;">
					<?php else: ?>
						<div style="width: 100%; height: 200px; background: #f0f0f0; border-radius: 4px; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; color: #999;">No image</div>
					<?php endif; ?>
					<a href="edit-intro.php" class="btn btn-primary" style="width: 100%; text-align: center;">
						<i class="fas fa-image"></i> Edit Intro Image
					</a>
				</div>
			</div>
		</div>
		
		<!-- Content Sections -->
		<div class="section-card">
			<h2 class="section-title">Content Sections</h2>
			<p style="color: #666; margin-bottom: 1.5rem;">Edit Farm Tourism and Cultural Tourism sections</p>
			<a href="edit-sections.php" class="btn btn-primary">
				<i class="fas fa-edit"></i> Edit Sections & Benefits
			</a>
		</div>
		
		<!-- Activities -->
		<div class="section-card">
			<h2 class="section-title">Activities</h2>
			<p style="color: #666; margin-bottom: 1.5rem;">Manage activities that support community income generation</p>
			<a href="edit-activities.php" class="btn btn-primary">
				<i class="fas fa-tasks"></i> Edit Activities
			</a>
		</div>
		
		<!-- Impact Table -->
		<div class="section-card">
			<h2 class="section-title">Impact Table</h2>
			<p style="color: #666; margin-bottom: 1.5rem;">Manage the impact table rows</p>
			<a href="edit-impacts.php" class="btn btn-primary">
				<i class="fas fa-table"></i> Edit Impact Table
			</a>
		</div>
		
		<!-- Hero & Intro Text -->
		<div class="section-card">
			<h2 class="section-title">Hero & Intro Text Content</h2>
			<p style="color: #666; margin-bottom: 1.5rem;">Edit hero title, subtitle, description and intro text</p>
			<a href="edit-text.php" class="btn btn-primary">
				<i class="fas fa-pen"></i> Edit Text Content
			</a>
		</div>
		
		<div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #ddd;">
			<a href="../index.php" class="btn btn-secondary">← Back to Admin</a>
		</div>
	</div>
</body>
</html>

