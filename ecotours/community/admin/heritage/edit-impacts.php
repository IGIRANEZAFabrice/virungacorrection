<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = '';
$error = '';

// Handle impact update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_impact') {
    $impact_id = (int)$_POST['impact_id'];
    $impact_description = mysqli_real_escape_string($conn, $_POST['impact_description']);

    $update_query = "UPDATE heritage_impacts SET 
        impact_description = '$impact_description'
        WHERE id = $impact_id";

    if (mysqli_query($conn, $update_query)) {
        $success = 'Impact updated successfully!';
    } else {
        $error = 'Error updating impact: ' . mysqli_error($conn);
    }
}

// Fetch impacts
$impacts = [];
$iq = mysqli_query($conn, "SELECT id, level_name, icon_class, impact_description FROM heritage_impacts ORDER BY display_order ASC");
if ($iq) {
    while ($r = mysqli_fetch_assoc($iq)) {
        $impacts[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Impact Table</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		.container { max-width: 900px; margin: 0 auto; padding: 2rem; }
		.form-group { margin-bottom: 1.5rem; }
		.form-label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: #333; }
		.form-control { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-size: 1rem; font-family: inherit; }
		textarea.form-control { min-height: 80px; resize: vertical; }
		.btn { display: inline-block; padding: 0.75rem 1.5rem; border-radius: 4px; text-decoration: none; font-weight: 500; cursor: pointer; border: none; }
		.btn-primary { background: #8B7355; color: white; }
		.btn-primary:hover { background: #6B5344; }
		.btn-secondary { background: #ddd; color: #333; }
		.btn-secondary:hover { background: #ccc; }
		.btn-sm { padding: 0.5rem 1rem; font-size: 0.9rem; }
		.alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
		.alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
		.alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
		.impact-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1.5rem; border-left: 4px solid #8B7355; }
		.impact-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
		.impact-icon { font-size: 1.5rem; color: #8B7355; }
		.impact-level { font-weight: 600; color: #333; }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="container">
		<h1 style="margin-bottom: 2rem;">Edit Impact Table</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<?php foreach ($impacts as $impact): ?>
		<div class="impact-card">
			<div class="impact-header">
				<div class="impact-icon">
					<i class="<?php echo htmlspecialchars($impact['icon_class']); ?>"></i>
				</div>
				<div class="impact-level"><?php echo htmlspecialchars($impact['level_name']); ?></div>
			</div>
			
			<form method="POST">
				<input type="hidden" name="action" value="update_impact">
				<input type="hidden" name="impact_id" value="<?php echo (int)$impact['id']; ?>">
				
				<div class="form-group">
					<label class="form-label">Impact Description</label>
					<textarea name="impact_description" class="form-control" required><?php echo htmlspecialchars($impact['impact_description']); ?></textarea>
				</div>
				
				<button type="submit" class="btn btn-primary btn-sm">
					<i class="fas fa-save"></i> Save Impact
				</button>
			</form>
		</div>
		<?php endforeach; ?>
		
		<div style="margin-top: 2rem;">
			<a href="index.php" class="btn btn-secondary">← Back to Dashboard</a>
		</div>
	</div>
</body>
</html>

