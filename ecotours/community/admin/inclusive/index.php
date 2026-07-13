<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Ensure page row
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_image, intro_text FROM inclusive_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) { $page = mysqli_fetch_assoc($pq); } else {
    mysqli_query($conn, "INSERT INTO inclusive_page (hero_title, hero_subtitle, hero_image, intro_text) VALUES ('Inclusive Community-Based Tourism', 'Empowering Persons with Disabilities in Rural Areas', 'assets/images/inclusive-hero.jpg', '')");
    $page = ['id' => mysqli_insert_id($conn), 'hero_title' => 'Inclusive Community-Based Tourism', 'hero_subtitle' => 'Empowering Persons with Disabilities in Rural Areas', 'hero_image' => 'assets/images/inclusive-hero.jpg', 'intro_text' => ''];
}
$page_id = (int)$page['id'];

// Load cards
$cards = [];
$cq = mysqli_query($conn, "SELECT id, number, image, title, description FROM approach_cards WHERE page_id = $page_id ORDER BY number ASC, id ASC");
if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }

// Load stats
$stats = [];
$sq = mysqli_query($conn, "SELECT id, stat_number, stat_label FROM inclusive_stats WHERE page_id = $page_id ORDER BY id ASC");
if ($sq) { while ($r = mysqli_fetch_assoc($sq)) { $stats[] = $r; } }

// Load CTA
$cta = null;
$ctaq = mysqli_query($conn, "SELECT id, title, text, button_text, button_link FROM inclusive_cta WHERE page_id = $page_id ORDER BY id DESC LIMIT 1");
if ($ctaq && mysqli_num_rows($ctaq) > 0) { $cta = mysqli_fetch_assoc($ctaq); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Inclusive Page</title>
	<!-- CSS Files -->
	<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">
	<link rel="stylesheet" href="../assets/css/inclusive-admin.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="admin-layout inclusive-admin">
	<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
	<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-universal-access"></i> Inclusive Page</h1>
				<p>Manage hero, intro, approach cards, stats, and CTA.</p>
			</header>

			<?php if ($success): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?></div><?php endif; ?>
			<?php if ($error): ?><div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?></div><?php endif; ?>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-header"></i> Hero & Intro</h2></div>
				<div class="card-body">
					<div style="margin-bottom: 1.5rem;">
						<a href="edit-hero.php" class="btn btn-primary"><i class="fas fa-image"></i> Edit Hero Section & Image</a>
					</div>
					<form action="handlers/update-page.php" method="post" class="form-grid">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Hero Title</label><input type="text" name="hero_title" value="<?php echo htmlspecialchars($page['hero_title']); ?>" required placeholder="Enter hero title" /></div>
						<div class="form-group"><label>Hero Subtitle</label><input type="text" name="hero_subtitle" value="<?php echo htmlspecialchars($page['hero_subtitle']); ?>" placeholder="Enter hero subtitle" /></div>
						<div class="form-group"><label>Intro Text</label><textarea name="intro_text" rows="5" placeholder="Enter introduction text for the page"><?php echo htmlspecialchars($page['intro_text']); ?></textarea></div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Hero & Intro</button>
					</form>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-shapes"></i> Approach Cards</h2></div>
				<div class="card-body">
					<div style="margin-bottom: 1.5rem;">
						<a href="edit-cards.php" class="btn btn-primary"><i class="fas fa-images"></i> Edit Card Images</a>
					</div>
					<div class="card-creation-form">
						<form action="handlers/cards-create.php" method="post" class="form-grid" style="margin-bottom:1.5rem;">
							<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
							<div class="form-group"><label>Number</label><input type="number" name="number" min="1" step="1" required placeholder="1" /></div>
							<div class="form-group"><label>Image URL</label><input type="text" name="image" required placeholder="https://example.com/image.jpg" /></div>
							<div class="form-group"><label>Title</label><input type="text" name="title" required placeholder="Card title" /></div>
							<div class="form-group"><label>Description</label><textarea name="description" rows="3" required placeholder="Enter card description"></textarea></div>
							<button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Add Approach Card</button>
						</form>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Number</th><th>Image</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($cards)===0): ?><tr><td colspan="6">No cards.</td></tr><?php endif; ?>
								<?php foreach ($cards as $i=>$c): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><?php echo (int)$c['number']; ?></td>
									<td><small><?php echo htmlspecialchars($c['image']); ?></small></td>
									<td><?php echo htmlspecialchars($c['title']); ?></td>
									<td style="max-width:420px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">&nbsp;<?php echo htmlspecialchars($c['description']); ?></td>
									<td>
										<a class="btn btn-small" href="edit.php?type=card&id=<?php echo (int)$c['id']; ?>&page_id=<?php echo $page_id; ?>"><i class="fas fa-edit"></i> Edit</a>
										<a class="btn btn-small" href="handlers/cards-delete.php?id=<?php echo (int)$c['id']; ?>&page_id=<?php echo $page_id; ?>" onclick="return confirm('Delete this card?');"><i class="fas fa-trash"></i> Delete</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-chart-bar"></i> Statistics</h2></div>
				<div class="card-body">
					<div class="card-creation-form">
						<form action="handlers/stats-create.php" method="post" class="form-grid" style="margin-bottom:1.5rem;">
							<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
							<div class="form-group"><label>Number</label><input type="text" name="stat_number" required placeholder="100+" /></div>
							<div class="form-group"><label>Label</label><input type="text" name="stat_label" required placeholder="People Empowered" /></div>
							<button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Add Statistic</button>
						</form>
					</div>
					<div class="table-responsive">
						<table class="table">
							<thead><tr><th>#</th><th>Number</th><th>Label</th><th>Actions</th></tr></thead>
							<tbody>
								<?php if (count($stats)===0): ?><tr><td colspan="4">No stats.</td></tr><?php endif; ?>
								<?php foreach ($stats as $i=>$s): ?>
								<tr>
									<td><?php echo $i+1; ?></td>
									<td><?php echo htmlspecialchars($s['stat_number']); ?></td>
									<td><?php echo htmlspecialchars($s['stat_label']); ?></td>
									<td>
										<a class="btn btn-small" href="edit.php?type=stat&id=<?php echo (int)$s['id']; ?>&page_id=<?php echo $page_id; ?>"><i class="fas fa-edit"></i> Edit</a>
										<a class="btn btn-small" href="handlers/stats-delete.php?id=<?php echo (int)$s['id']; ?>&page_id=<?php echo $page_id; ?>" onclick="return confirm('Delete this stat?');"><i class="fas fa-trash"></i> Delete</a>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</section>

			<section class="card">
				<div class="card-header"><h2><i class="fas fa-bullhorn"></i> Call to Action</h2></div>
				<div class="card-body">
					<form action="handlers/cta-upsert.php" method="post" class="form-grid">
						<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
						<div class="form-group"><label>Title</label><input type="text" name="title" value="<?php echo htmlspecialchars($cta['title'] ?? ''); ?>" required placeholder="Join Our Mission" /></div>
						<div class="form-group"><label>Text</label><textarea name="text" rows="3" required placeholder="Enter call-to-action description"><?php echo htmlspecialchars($cta['text'] ?? ''); ?></textarea></div>
						<div class="form-group"><label>Button Text</label><input type="text" name="button_text" value="<?php echo htmlspecialchars($cta['button_text'] ?? 'Get Involved Today'); ?>" placeholder="Get Involved Today" /></div>
						<div class="form-group"><label>Button Link</label><input type="url" name="button_link" value="<?php echo htmlspecialchars($cta['button_link'] ?? ''); ?>" placeholder="https://example.com/contact" /></div>
						<button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Save Call to Action</button>
					</form>
				</div>
			</section>
		</main>
	</div>
</body>
</html>

<?php mysqli_close($conn); ?>


