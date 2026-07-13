<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Ensure there is a page row
$page = null;
$pq = mysqli_query($conn, "SELECT id, section_description FROM impact_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
	$page = mysqli_fetch_assoc($pq);
} else {
	mysqli_query($conn, "INSERT INTO impact_page (section_description) VALUES ('')");
	$pid = mysqli_insert_id($conn);
	$page = ['id' => $pid, 'section_description' => ''];
}
$page_id = (int)$page['id'];

// Load gallery, cards, programs
$gallery = [];
$gq = mysqli_query($conn, "SELECT id, image, title, description FROM impact_gallery WHERE page_id = $page_id ORDER BY id ASC");
if ($gq) { while ($r = mysqli_fetch_assoc($gq)) { $gallery[] = $r; } }

$cards = [];
$cq = mysqli_query($conn, "SELECT id, image, title, description FROM impact_cards WHERE page_id = $page_id ORDER BY id ASC");
if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }

$programs = [];
$tq = mysqli_query($conn, "SELECT id, image, title, description, features FROM teaching_programs WHERE page_id = $page_id ORDER BY id ASC");
if ($tq) { while ($r = mysqli_fetch_assoc($tq)) { $programs[] = $r; } }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Impact Page</title>

	 <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-layout">

	<?php include '../includes/sidebar.php'; ?>
	<div class="admin-container">
	<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-heart"></i> Impact Page</h1>
				<p>Edit section description, gallery, impact cards, and teaching programs.</p>
			</header>

			<?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-align-left"></i> Section Description</h2></div>
				<div class="card-body">
					<form action="handlers/update-page.php" method="post" class="form-grid">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group">
							<label for="section_description">Description</label>
							<textarea id="section_description" name="section_description" rows="4" required><?php echo htmlspecialchars($page['section_description']); ?></textarea>
						</div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save</button>
					</form>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-images"></i> Gallery</h2></div>
				<div class="card-body">
					<form action="handlers/gallery-create.php" method="post" enctype="multipart/form-data" class="form-grid" style="margin-bottom:1.5rem;">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Title</label><input type="text" name="title" required /></div>
						<div class="form-group"><label>Description</label><textarea name="description" rows="3"></textarea></div>
						<div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*" required /></div>
						<button class="btn" type="submit"><i class="fas fa-plus"></i> Add</button>
					</form>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($gallery)===0): ?><tr><td colspan="5">No items.</td></tr><?php endif; ?>
								<?php foreach ($gallery as $i=>$g): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><img src="../../uploads/impact/<?php echo htmlspecialchars($g['image']); ?>" style="width:100px;height:70px;object-fit:cover;" /></td>
									<td><?php echo htmlspecialchars($g['title']); ?></td>
									<td style="max-width:360px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">&nbsp;<?php echo htmlspecialchars($g['description']); ?></td>
									<td>
										<a class="btn btn-small" href="edit-gallery.php?id=<?php echo (int)$g['id']; ?>"><i class="fas fa-pen"></i> Edit</a>
										<a class="btn btn-small btn-danger" href="handlers/gallery-delete.php?id=<?php echo (int)$g['id']; ?>&page_id=<?php echo $page_id; ?>" onclick="return confirm('Delete this image?');"><i class="fas fa-trash"></i> Delete</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-id-card"></i> Impact Cards</h2></div>
				<div class="card-body">
					<form action="handlers/cards-create.php" method="post" enctype="multipart/form-data" class="form-grid" style="margin-bottom:1.5rem;">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Title</label><input type="text" name="title" required /></div>
						<div class="form-group"><label>Description</label><textarea name="description" rows="3" required></textarea></div>
						<div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*" required /></div>
						<button class="btn" type="submit"><i class="fas fa-plus"></i> Add</button>
					</form>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($cards)===0): ?><tr><td colspan="5">No cards.</td></tr><?php endif; ?>
								<?php foreach ($cards as $i=>$c): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><img src="../../uploads/impact/<?php echo htmlspecialchars($c['image']); ?>" style="width:100px;height:70px;object-fit:cover;" /></td>
									<td><?php echo htmlspecialchars($c['title']); ?></td>
									<td style="max-width:360px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">&nbsp;<?php echo htmlspecialchars($c['description']); ?></td>
									<td>
										<a class="btn btn-small" href="edit-card.php?id=<?php echo (int)$c['id']; ?>"><i class="fas fa-pen"></i> Edit</a>
										<a class="btn btn-small btn-danger" href="handlers/cards-delete.php?id=<?php echo (int)$c['id']; ?>&page_id=<?php echo $page_id; ?>" onclick="return confirm('Delete this card?');"><i class="fas fa-trash"></i> Delete</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-graduation-cap"></i> Teaching Programs</h2></div>
				<div class="card-body">
					<form action="handlers/programs-create.php" method="post" enctype="multipart/form-data" class="form-grid" style="margin-bottom:1.5rem;">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Title</label><input type="text" name="title" required /></div>
						<div class="form-group"><label>Description</label><textarea name="description" rows="3" required></textarea></div>
						<div class="form-group"><label>Image</label><input type="file" name="image" accept="image/*" required /></div>
						<div class="form-group"><label>Features (one per line)</label><textarea name="features" rows="4"></textarea></div>
						<button class="btn" type="submit"><i class="fas fa-plus"></i> Add</button>
					</form>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($programs)===0): ?><tr><td colspan="5">No programs.</td></tr><?php endif; ?>
								<?php foreach ($programs as $i=>$p): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><img src="../../uploads/impact/<?php echo htmlspecialchars($p['image']); ?>" style="width:100px;height:70px;object-fit:cover;" /></td>
									<td><?php echo htmlspecialchars($p['title']); ?></td>
									<td style="max-width:360px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">&nbsp;<?php echo htmlspecialchars($p['description']); ?></td>
									<td>
										<a class="btn btn-small" href="edit-program.php?id=<?php echo (int)$p['id']; ?>"><i class="fas fa-pen"></i> Edit</a>
										<a class="btn btn-small btn-danger" href="handlers/programs-delete.php?id=<?php echo (int)$p['id']; ?>&page_id=<?php echo $page_id; ?>" onclick="return confirm('Delete this program?');"><i class="fas fa-trash"></i> Delete</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

<?php mysqli_close($conn); ?>


