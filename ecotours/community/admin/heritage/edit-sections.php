<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = '';
$error = '';

// Handle section description update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_section') {
    $section_id = mysqli_real_escape_string($conn, $_POST['section_id']);
    $section_description = mysqli_real_escape_string($conn, $_POST['section_description']);
    $benefits_title = mysqli_real_escape_string($conn, $_POST['benefits_title']);

    $update_query = "UPDATE heritage_sections SET 
        section_description = '$section_description',
        benefits_title = '$benefits_title'
        WHERE section_id = '$section_id'";

    if (mysqli_query($conn, $update_query)) {
        $success = 'Section updated successfully!';
    } else {
        $error = 'Error updating section: ' . mysqli_error($conn);
    }
}

// Handle benefit update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_benefit') {
    $benefit_id = (int)$_POST['benefit_id'];
    $benefit_title = mysqli_real_escape_string($conn, $_POST['benefit_title']);
    $benefit_description = mysqli_real_escape_string($conn, $_POST['benefit_description']);

    $update_query = "UPDATE heritage_benefits SET 
        benefit_title = '$benefit_title',
        benefit_description = '$benefit_description'
        WHERE id = $benefit_id";

    if (mysqli_query($conn, $update_query)) {
        $success = 'Benefit updated successfully!';
    } else {
        $error = 'Error updating benefit: ' . mysqli_error($conn);
    }
}

// Fetch sections with benefits
$sections = [];
$sq = mysqli_query($conn, "SELECT id, section_id, section_description, benefits_title FROM heritage_sections ORDER BY display_order ASC");
if ($sq) {
    while ($r = mysqli_fetch_assoc($sq)) {
        $r['benefits'] = [];
        $bq = mysqli_query($conn, "SELECT id, benefit_title, benefit_description FROM heritage_benefits WHERE section_id = '" . mysqli_real_escape_string($conn, $r['section_id']) . "' ORDER BY display_order ASC");
        if ($bq) {
            while ($b = mysqli_fetch_assoc($bq)) {
                $r['benefits'][] = $b;
            }
        }
        $sections[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Sections & Benefits</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 900px; margin: 0 auto; padding: 2rem; }
		.form-group { margin-bottom: 1.5rem; }
		.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
		.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; }
		textarea.form-control { min-height: 100px; resize: vertical; }
		.btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; border: none; }
		.btn-primary { background: #8B7355; color: white; }
		.btn-primary:hover { background: #6B5344; }
		.btn-secondary { background: #ddd; color: #333; }
		.btn-secondary:hover { background: #ccc; }
		.btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; }
		.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
		.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.section-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem; }
		.section-title { font-size: 1.25rem; font-weight: 600; margin-bottom: 1.5rem; color: #333; }
		.benefit-item { background: #f9f9f9; padding: 1.5rem; border-radius: 4px; margin-bottom: 1rem; border-left: 4px solid #8B7355; }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="container">
		<h1 style="margin-bottom: 2rem;">Edit Sections & Benefits</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<?php foreach ($sections as $section): ?>
		<div class="section-card">
			<h2 class="section-title">
				<?php 
				if ($section['section_id'] === 'farm-tourism') {
					echo 'Farm Tourism Section';
				} else {
					echo 'Cultural Tourism Section';
				}
				?>
			</h2>
			
			<form method="POST" style="margin-bottom: 2rem;">
				<input type="hidden" name="action" value="update_section">
				<input type="hidden" name="section_id" value="<?php echo htmlspecialchars($section['section_id']); ?>">
				
				<div class="form-group">
					<label class="form-label">Section Description</label>
					<textarea name="section_description" class="form-control" required><?php echo htmlspecialchars($section['section_description']); ?></textarea>
				</div>
				
				<div class="form-group">
					<label class="form-label">Benefits Title</label>
					<input type="text" name="benefits_title" class="form-control" value="<?php echo htmlspecialchars($section['benefits_title']); ?>" required>
				</div>
				
				<button type="submit" class="btn btn-primary btn-sm">
					<i class="fas fa-save"></i> Save Section
				</button>
			</form>
			
			<h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Benefits</h3>
			<?php foreach ($section['benefits'] as $benefit): ?>
			<div class="benefit-item">
				<form method="POST">
					<input type="hidden" name="action" value="update_benefit">
					<input type="hidden" name="benefit_id" value="<?php echo (int)$benefit['id']; ?>">
					
					<div class="form-group">
						<label class="form-label">Benefit Title</label>
						<input type="text" name="benefit_title" class="form-control" value="<?php echo htmlspecialchars($benefit['benefit_title']); ?>" required>
					</div>
					
					<div class="form-group">
						<label class="form-label">Benefit Description</label>
						<textarea name="benefit_description" class="form-control" required><?php echo htmlspecialchars($benefit['benefit_description']); ?></textarea>
					</div>
					
					<button type="submit" class="btn btn-primary btn-sm">
						<i class="fas fa-save"></i> Save Benefit
					</button>
				</form>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
		
		<div style="margin-top: 2rem;">
			<a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
		</div>
	</div>
</body>
</html>

