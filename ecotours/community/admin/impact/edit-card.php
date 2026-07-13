<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: index.php?error=Invalid card id'); exit; }

$res = mysqli_query($conn, "SELECT id, page_id, title, description, image FROM impact_cards WHERE id = $id");
$row = $res ? mysqli_fetch_assoc($res) : null;
if (!$row) { header('Location: index.php?error=Card not found'); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Impact Card</title>
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>.admin-container{margin-left:280px}@media(max-width:768px){.admin-container{margin-left:0}}</style>
</head>
<body class="admin-layout">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
	<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-id-card"></i> Edit Impact Card</h1>
			</header>
			<section class="card">
				<div class="card-body">
					<form action="handlers/cards-update.php" method="post" enctype="multipart/form-data" class="form-grid">
						<input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>" />
						<input type="hidden" name="page_id" value="<?php echo (int)$row['page_id']; ?>" />
						<div class="form-group">
							<label>Title</label>
							<input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required />
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea name="description" rows="4" required><?php echo htmlspecialchars($row['description']); ?></textarea>
						</div>
						<div class="form-group">
							<label>Current Image</label>
							<div><img src="../../uploads/impact/<?php echo htmlspecialchars($row['image']); ?>" style="width:220px;height:auto;object-fit:cover;" /></div>
						</div>
						<div class="form-group">
							<label>Replace Image (optional)</label>
							<input type="file" name="image" accept="image/*" />
						</div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Update</button>
						<a href="index.php" class="btn">Cancel</a>
					</form>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

<?php mysqli_close($conn); ?>


