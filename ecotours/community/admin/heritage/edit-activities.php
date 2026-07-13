<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = '';
$error = '';

// Handle activity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_activity') {
    $activity_id = (int)$_POST['activity_id'];
    $activity_title = mysqli_real_escape_string($conn, $_POST['activity_title']);
    $activity_description = mysqli_real_escape_string($conn, $_POST['activity_description']);

    $update_query = "UPDATE heritage_activities SET 
        activity_title = '$activity_title',
        activity_description = '$activity_description'
        WHERE id = $activity_id";

    if (mysqli_query($conn, $update_query)) {
        $success = 'Activity updated successfully!';
    } else {
        $error = 'Error updating activity: ' . mysqli_error($conn);
    }
}

// Fetch activities
$activities = [];
$aq = mysqli_query($conn, "SELECT id, icon_class, activity_title, activity_description FROM heritage_activities ORDER BY display_order ASC");
if ($aq) {
    while ($r = mysqli_fetch_assoc($aq)) {
        $activities[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Activities</title>
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
		.activity-card { background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1.5rem; border-left: 4px solid #8B7355; }
		.activity-icon { font-size: 2rem; color: #8B7355; margin-bottom: 1rem; }
	</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="container">
		<h1 style="margin-bottom: 2rem;">Edit Activities</h1>
		
		<?php if ($success): ?>
			<div class="alert alert-success">✓ <?php echo htmlspecialchars($success); ?></div>
		<?php endif; ?>
		<?php if ($error): ?>
			<div class="alert alert-error">✗ <?php echo htmlspecialchars($error); ?></div>
		<?php endif; ?>
		
		<?php foreach ($activities as $activity): ?>
		<div class="activity-card">
			<div class="activity-icon">
				<i class="<?php echo htmlspecialchars($activity['icon_class']); ?>"></i>
			</div>
			
			<form method="POST">
				<input type="hidden" name="action" value="update_activity">
				<input type="hidden" name="activity_id" value="<?php echo (int)$activity['id']; ?>">
				
				<div class="form-group">
					<label class="form-label">Activity Title</label>
					<input type="text" name="activity_title" class="form-control" value="<?php echo htmlspecialchars($activity['activity_title']); ?>" required>
				</div>
				
				<div class="form-group">
					<label class="form-label">Activity Description</label>
					<textarea name="activity_description" class="form-control" required><?php echo htmlspecialchars($activity['activity_description']); ?></textarea>
				</div>
				
				<button type="submit" class="btn btn-primary btn-sm">
					<i class="fas fa-save"></i> Save Activity
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

