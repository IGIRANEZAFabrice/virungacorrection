<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Ensure a page row exists
$page = null;
$pq = mysqli_query($conn, "SELECT id, page_description FROM volunteer_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    mysqli_query($conn, "INSERT INTO volunteer_page (page_description) VALUES ('')");
    $page = ['id' => mysqli_insert_id($conn), 'page_description' => ''];
}
$page_id = (int)$page['id'];

// Load cards
$cards = [];
$cq = mysqli_query($conn, "SELECT id, icon, title, description FROM involved_cards WHERE page_id = $page_id ORDER BY id ASC");
if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }

// Load program (latest)
$prog = null;
$rq = mysqli_query($conn, "SELECT id, section_title, section_description, content, highlights, shop_link, shop_note FROM empowerment_programs WHERE page_id = $page_id ORDER BY id DESC LIMIT 1");
if ($rq && mysqli_num_rows($rq) > 0) { $prog = mysqli_fetch_assoc($rq); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Volunteer Page</title>
		<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">
	<link rel="stylesheet" href="../assets/css/inclusive-admin.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="../assets/css/volunteer.css">
	<link rel="stylesheet" href="../assets/css/volunteer-admin.css">
	
<body class="admin-layout volunteer-page volunteer-admin">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
		<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-hands-helping"></i> Volunteer Page</h1>
				<p>Edit introduction, involvement cards, and empowerment section.</p>
			</header>

			<?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-align-left"></i> Introduction</h2></div>
				<div class="card-body">
					<form action="handlers/update-page.php" method="post" class="form-grid">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Description</label><textarea name="page_description" rows="4" required placeholder="Enter the volunteer page introduction text..."><?php echo htmlspecialchars($page['page_description']); ?></textarea></div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save</button>
					</form>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-people-carry"></i> Get Involved Cards</h2></div>
				<div class="card-body">
					<div class="card-creation-form">
						<form action="handlers/cards-create.php" method="post" class="form-grid-3">
							<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
							<div class="form-group"><label>Icon (emoji or short text)</label><input type="text" name="icon" maxlength="10" required placeholder="🤝" /></div>
							<div class="form-group"><label>Title</label><input type="text" name="title" required placeholder="Get Involved" /></div>
							<div class="form-group"><label>Description</label><textarea name="description" rows="3" required placeholder="Describe how people can get involved..."></textarea></div>
							<button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Add Card</button>
						</form>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Icon</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($cards)===0): ?><tr><td colspan="5">No cards.</td></tr><?php endif; ?>
								<?php foreach ($cards as $i=>$c): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><?php echo htmlspecialchars($c['icon']); ?></td>
									<td><?php echo htmlspecialchars($c['title']); ?></td>
									<td style="max-width:420px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">&nbsp;<?php echo htmlspecialchars($c['description']); ?></td>
									<td>
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
				<div class="card-header"><h2><i class="fas fa-fist-raised"></i> Empowerment Section</h2></div>
				<div class="card-body">
					<form action="handlers/programs-upsert.php" method="post" class="form-grid">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Section Title</label><input type="text" name="section_title" value="<?php echo htmlspecialchars($prog['section_title'] ?? ''); ?>" required placeholder="Women Empowerment Program" /></div>
						<div class="form-group"><label>Section Description</label><textarea name="section_description" rows="3" required placeholder="Brief description of the empowerment section..."><?php echo htmlspecialchars($prog['section_description'] ?? ''); ?></textarea></div>
						<div class="form-group"><label>Content</label><textarea name="content" rows="6" required placeholder="Detailed content about the empowerment programs..."><?php echo htmlspecialchars($prog['content'] ?? ''); ?></textarea></div>
						<div class="form-group"><label>Highlights (comma or newline separated)</label><textarea name="highlights" rows="4" placeholder="• Skill development workshops&#10;• Leadership training&#10;• Economic opportunities"><?php echo htmlspecialchars($prog['highlights'] ?? ''); ?></textarea></div>
						<div class="form-group"><label>Shop Link</label><input type="url" name="shop_link" value="<?php echo htmlspecialchars($prog['shop_link'] ?? ''); ?>" placeholder="https://shop.example.com" /></div>
						<div class="form-group"><label>Shop Note</label><textarea name="shop_note" rows="3" placeholder="Additional information about the shop or products..."><?php echo htmlspecialchars($prog['shop_note'] ?? ''); ?></textarea></div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Empowerment Section</button>
					</form>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

<?php mysqli_close($conn); ?>


