<?php
require_once '../admin/config/connection.php';

// Fetch static sections (normalize names to handle variants e.g., "Our Aim" → our_aim)
$static_sections = [
	'introduction' => '',
	'our_aim' => '',
	'our_program' => '',
	'take_action' => ''
];

$static_query = "SELECT section_name, content FROM school_static_sections";
$static_result = mysqli_query($conn, $static_query);
if ($static_result && mysqli_num_rows($static_result) > 0) {
	while ($row = mysqli_fetch_assoc($static_result)) {
		$raw = strtolower(trim((string)($row['section_name'] ?? '')));
		$normalized = preg_replace('/[^a-z0-9]+/i', '_', $raw);
		$normalized = trim($normalized, '_');
		// Map common variants
		$alias = $normalized;
		if ($alias === 'our' || $alias === 'ouraim') { $alias = 'our_aim'; }
		if ($alias === 'our_programs' || $alias === 'program' || $alias === 'programs') { $alias = 'our_program'; }
		if ($alias === 'take' || $alias === 'action' || $alias === 'takeaction') { $alias = 'take_action'; }
		if (array_key_exists($alias, $static_sections)) {
			$static_sections[$alias] = (string)$row['content'];
		}
	}
}

// Fetch dynamic sections
$dynamic_sections = [];
$dynamic_query = "SELECT id, title, description, image, created_at FROM school_dynamic_sections ORDER BY created_at ASC";
$dynamic_result = mysqli_query($conn, $dynamic_query);
if ($dynamic_result && mysqli_num_rows($dynamic_result) > 0) {
	while ($row = mysqli_fetch_assoc($dynamic_result)) {
		$dynamic_sections[] = $row;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Our Schools & Community Programs</title>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="assets/css/community.css" />
	<link rel="stylesheet" href="../css/earthy-theme.css" />
	<link rel="stylesheet" href="assets/css/school.css" />
	
	<!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
</head>
<body>
	<?php include 'includes/header.php'; ?>

	<!-- Hero Section -->
	<section class="hero-section" style="position: relative;">
		<div style="position: absolute; inset: 0;">
			<img src="./uploads/schoolhero.jpg" alt="Our Schools & Community Programs" style="width:100%; height:100%; object-fit:cover;" ondragstart="return false;" oncontextmenu="return false;" />
		</div>
		<div class="container" style="position: relative; z-index: 2;">
			<div class="hero-content">
				<h1 class="hero-title">Our Schools & Community Programs</h1>
				<p class="hero-subtitle">Empowering children, supporting communities, and connecting tourism to education</p>
				<a href="#take-action" class="hero-cta">Get Involved</a>
			</div>
		</div>
	</section>

	<!-- Static Sections -->
	<section class="about-section">
		<div class="container">
			<h2 class="section-title">Introduction</h2>
			<div class="about-content" style="grid-template-columns: 1fr;">
				<div class="about-text" style="color:#1a1a1a; background:transparent;">
					<p><?php $c = trim((string)$static_sections['introduction']); echo $c !== '' ? nl2br($c) : 'Content coming soon.'; ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="about-section">
		<div class="container">
			<h2 class="section-title">Our Aim</h2>
			<div class="about-content" style="grid-template-columns: 1fr;">
				<div class="about-text" style="color:#1a1a1a; background:transparent;">
					<p><?php $c = trim((string)$static_sections['our_aim']); echo $c !== '' ? nl2br($c) : 'Content coming soon.'; ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="about-section">
		<div class="container">
			<h2 class="section-title">Our Program</h2>
			<div class="about-content" style="grid-template-columns: 1fr;">
				<div class="about-text" style="color:#1a1a1a; background:transparent;">
					<p><?php $c = trim((string)$static_sections['our_program']); echo $c !== '' ? nl2br($c) : 'Content coming soon.'; ?></p>
				</div>
			</div>
		</div>
	</section>

	<section class="about-section" id="take-action">
		<div class="container">
			<h2 class="section-title">Take Action</h2>
			<div class="about-content" style="grid-template-columns: 1fr;">
				<div class="about-text" style="color:#1a1a1a; background:transparent;">
					<p><?php $c = trim((string)$static_sections['take_action']); echo $c !== '' ? nl2br($c) : 'Content coming soon.'; ?></p>
					<div style="margin-top: 1rem; display:flex; gap: .75rem; flex-wrap: wrap;">
						<a href="contact.php" class="cta-button">Donate</a>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- Dynamic Sections -->
	<section class="programs-section">
		<div class="container">
			<h2 class="section-title">School & Community Initiatives</h2>
			<div class="programs-intro">
				<p>Stories and initiatives from our education-focused programs</p>
			</div>
			<div>
				<?php if (count($dynamic_sections) === 0): ?>
					<p class="programs-intro">No school initiatives yet. Please check back soon.</p>
				<?php else: ?>
					<?php foreach ($dynamic_sections as $index => $item): ?>
						<?php 
							$stacked = 'flex-direction: column;';
							$desktop = $index % 2 === 0 ? 'row' : 'row-reverse';
						?>
						<div class="dynamic-content-card">
							<?php if ($index % 2 === 0): ?>
								<!-- Image first layout -->
								<div class="dynamic-image">
									<img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" ondragstart="return false;" oncontextmenu="return false;" />
								</div>
								<div class="dynamic-text">
									<h3><?php echo htmlspecialchars($item['title']); ?></h3>
									<p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
								</div>
							<?php else: ?>
								<!-- Text first layout for desktop -->
								<div class="dynamic-text">
									<h3><?php echo htmlspecialchars($item['title']); ?></h3>
									<p><?php echo nl2br(htmlspecialchars($item['description'])); ?></p>
								</div>
								<div class="dynamic-image">
									<img src="uploads/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" ondragstart="return false;" oncontextmenu="return false;" />
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php include 'includes/footer.php'; ?>
	<script src="assets/js/community.js"></script>
	<script src="assets/js/community-header-footer.js"></script>

	<script>
		// School page specific animations and interactions
		document.addEventListener('DOMContentLoaded', function() {
			// Intersection Observer for scroll animations
			const observerOptions = {
				threshold: 0.1,
				rootMargin: '0px 0px -50px 0px'
			};

			const observer = new IntersectionObserver((entries) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						entry.target.style.opacity = '1';
						entry.target.style.transform = 'translateY(0)';
					}
				});
			}, observerOptions);

			// Animate sections on scroll
			const sections = document.querySelectorAll('.about-section, .dynamic-content-card');
			sections.forEach((section, index) => {
				section.style.opacity = '0';
				section.style.transform = 'translateY(30px)';
				section.style.transition = `all 0.8s ease ${index * 0.1}s`;
				observer.observe(section);
			});

			// Smooth scroll for anchor links
			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function (e) {
					e.preventDefault();
					const target = document.querySelector(this.getAttribute('href'));
					if (target) {
						target.scrollIntoView({
							behavior: 'smooth',
							block: 'start'
						});
					}
				});
			});

			// Add loading animation to images
			const images = document.querySelectorAll('.dynamic-image img, .hero-section img');
			images.forEach(img => {
				if (img.complete) {
					img.style.opacity = '1';
				} else {
					img.style.opacity = '0';
					img.style.transition = 'opacity 0.5s ease';
					img.addEventListener('load', function() {
						this.style.opacity = '1';
					});
				}
			});

			// Parallax effect for hero section
			window.addEventListener('scroll', function() {
				const scrolled = window.pageYOffset;
				const heroImg = document.querySelector('.hero-section img');
				if (heroImg) {
					heroImg.style.transform = `translateY(${scrolled * 0.5}px)`;
				}
			});
		});
	</script>
</body>
</html>

<?php mysqli_close($conn); ?>
