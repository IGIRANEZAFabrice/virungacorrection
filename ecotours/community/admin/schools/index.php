<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../index.php');
	exit;
}

$success_message = $_GET['success'] ?? '';
$error_message = $_GET['error'] ?? '';

// Load static sections
$static_sections = [
	'introduction' => '',
	'our_aim' => '',
	'our_program' => '',
	'take_action' => ''
];

$static_query = "SELECT section_name, content FROM school_static_sections WHERE section_name IN ('introduction','our_aim','our_program','take_action')";
$static_result = mysqli_query($conn, $static_query);
if ($static_result && mysqli_num_rows($static_result) > 0) {
	while ($row = mysqli_fetch_assoc($static_result)) {
		$name = strtolower(trim($row['section_name']));
		if (isset($static_sections[$name])) {
			$static_sections[$name] = $row['content'];
		}
	}
}

// Load dynamic sections
$dynamic_sections = [];
$dyn_query = "SELECT id, title, description, image, created_at FROM school_dynamic_sections ORDER BY created_at ASC";
$dyn_result = mysqli_query($conn, $dyn_query);
if ($dyn_result && mysqli_num_rows($dyn_result) > 0) {
	while ($row = mysqli_fetch_assoc($dyn_result)) {
		$dynamic_sections[] = $row;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Manage Schools Page</title>
	 <!-- CSS Files -->
	 <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">
	<link rel="stylesheet" href="../assets/css/school-admin.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="admin-layout">
<?php include '../includes/sidebar.php'; ?>
	
	<div class="admin-container">
	<?php include '../includes/topbar.php'; ?>
		<main class="admin-main">
			<header class="page-header">
				<h1><i class="fas fa-school"></i> Schools & Community Programs</h1>
				<p>Manage static and dynamic sections shown on the schools page.</p>
			</header>

			<?php if (!empty($success_message)): ?>
				<div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?></div>
			<?php endif; ?>
			<?php if (!empty($error_message)): ?>
				<div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?></div>
			<?php endif; ?>

			<section class="card">
				<div class="card-header">
					<h2><i class="fas fa-edit"></i> Static Sections</h2>
				</div>
				<div class="card-body">
					<form action="handlers/update-static.php" method="post" class="form-grid">
						<div class="form-group">
							<label for="introduction">Introduction</label>
							<textarea id="introduction" name="introduction" rows="5" required><?php echo htmlspecialchars($static_sections['introduction']); ?></textarea>
						</div>
						<div class="form-group">
							<label for="our_aim">Our Aim</label>
							<textarea id="our_aim" name="our_aim" rows="5" required><?php echo htmlspecialchars($static_sections['our_aim']); ?></textarea>
						</div>
						<div class="form-group">
							<label for="our_program">Our Program</label>
							<textarea id="our_program" name="our_program" rows="5" required><?php echo htmlspecialchars($static_sections['our_program']); ?></textarea>
						</div>
						<div class="form-group">
							<label for="take_action">Take Action</label>
							<textarea id="take_action" name="take_action" rows="5" required><?php echo htmlspecialchars($static_sections['take_action']); ?></textarea>
						</div>
						<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
					</form>
				</div>
			</section>

			<section class="card">
				<div class="card-header">
					<h2><i class="fas fa-layer-group"></i> Dynamic Sections</h2>
					<button id="toggleAddSectionBtn" class="btn btn-secondary">
						<i class="fas fa-plus"></i> Add New Section
					</button>
				</div>
				<div class="card-body">
					<div id="addSectionForm" class="add-section-form hidden">
						<form action="handlers/create-dynamic.php" method="post" enctype="multipart/form-data" class="form-grid" style="margin-bottom:2rem;">
							<div class="form-group">
								<label for="title">Title</label>
								<input type="text" id="title" name="title" required placeholder="Enter section title" />
							</div>
							<div class="form-group">
								<label for="description">Description</label>
								<textarea id="description" name="description" rows="4" required placeholder="Enter section description"></textarea>
							</div>
							<div class="form-group">
								<label for="image">Image</label>
								<input type="file" id="image" name="image" accept="image/*" required />
								<small class="form-help">Upload an image for this section (JPG, PNG, GIF)</small>
							</div>
							<div class="form-actions">
								<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Section</button>
								<button type="button" id="cancelAddBtn" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</button>
							</div>
						</form>
					</div>

					<div class="sections-container">
						<?php if (count($dynamic_sections) === 0): ?>
							<div class="empty-state">
								<i class="fas fa-layer-group"></i>
								<h3>No Dynamic Sections</h3>
								<p>Click "Add New Section" to create your first dynamic section.</p>
							</div>
						<?php else: ?>
							<div class="sections-grid">
								<?php foreach ($dynamic_sections as $i => $row): ?>
									<div class="section-card">
										<div class="section-image">
											<img src="../../uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" />
											<div class="section-number"><?php echo $i + 1; ?></div>
										</div>
										<div class="section-content">
											<h4 class="section-title"><?php echo htmlspecialchars($row['title']); ?></h4>
											<p class="section-description"><?php echo htmlspecialchars(substr($row['description'], 0, 120)) . (strlen($row['description']) > 120 ? '...' : ''); ?></p>
											<div class="section-meta">
												<span class="section-date">
													<i class="fas fa-calendar"></i>
													<?php echo htmlspecialchars(date('M d, Y', strtotime($row['created_at']))); ?>
												</span>
											</div>
											<div class="section-actions">
												<a class="btn btn-small btn-primary" href="edit.php?id=<?php echo $row['id']; ?>">
													<i class="fas fa-edit"></i> Edit
												</a>
												<form action="handlers/delete-dynamic.php" method="post" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this section?');">
													<input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>" />
													<button type="submit" class="btn btn-small btn-danger">
														<i class="fas fa-trash"></i> Delete
													</button>
												</form>
											</div>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</section>
		</main>
	</div>

	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const toggleBtn = document.getElementById('toggleAddSectionBtn');
			const addForm = document.getElementById('addSectionForm');
			const cancelBtn = document.getElementById('cancelAddBtn');

			// Toggle add section form
			if (toggleBtn && addForm) {
				toggleBtn.addEventListener('click', function() {
					if (addForm.classList.contains('hidden')) {
						addForm.classList.remove('hidden');
						addForm.style.maxHeight = addForm.scrollHeight + 'px';
						toggleBtn.innerHTML = '<i class="fas fa-minus"></i> Hide Form';
						toggleBtn.classList.add('active');
					} else {
						addForm.classList.add('hidden');
						addForm.style.maxHeight = '0';
						toggleBtn.innerHTML = '<i class="fas fa-plus"></i> Add New Section';
						toggleBtn.classList.remove('active');
					}
				});
			}

			// Cancel button
			if (cancelBtn && addForm && toggleBtn) {
				cancelBtn.addEventListener('click', function() {
					addForm.classList.add('hidden');
					addForm.style.maxHeight = '0';
					toggleBtn.innerHTML = '<i class="fas fa-plus"></i> Add New Section';
					toggleBtn.classList.remove('active');

					// Reset form
					const form = addForm.querySelector('form');
					if (form) {
						form.reset();
					}
				});
			}

			// Add smooth animations to section cards
			const sectionCards = document.querySelectorAll('.section-card');
			sectionCards.forEach((card, index) => {
				card.style.animationDelay = `${index * 0.1}s`;
			});
		});
	</script>
</body>
</html>

<?php mysqli_close($conn); ?>


