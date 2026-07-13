<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../index.php');
	exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
	header('Location: index.php?error=Invalid section');
	exit;
}

$query = "SELECT id, title, description, image FROM school_dynamic_sections WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = $result ? mysqli_fetch_assoc($result) : null;
if (!$row) {
	header('Location: index.php?error=Section not found');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Section</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-layout">
<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
	<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-pen"></i> Edit Dynamic Section</h1>
			</header>

			<section class="card">
				<div class="card-body">
					<form action="handlers/update-dynamic.php" method="post" enctype="multipart/form-data" class="form-grid">
						<input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>" />
						<div class="form-group">
							<label for="title">Title</label>
							<input type="text" id="title" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required />
						</div>
						<div class="form-group">
							<label for="description">Description</label>
							<textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($row['description']); ?></textarea>
						</div>
						<div class="form-group">
							<label>Current Image</label>
							<div>
								<img src="../../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="" style="width:200px; height:auto; object-fit:cover;" />
							</div>
						</div>
						<div class="form-group">
							<label for="image">Replace Image (optional)</label>
							<input type="file" id="image" name="image" accept="image/*" />
						</div>
						<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
						<a href="index.php" class="btn">Cancel</a>
					</form>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

<?php mysqli_close($conn); ?>


