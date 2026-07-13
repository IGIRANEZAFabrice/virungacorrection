<?php
session_start();
require_once '../../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../../index.php');
	exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
if ($id <= 0 || $title === '' || $description === '') {
	header('Location: ../index.php?error=Invalid input');
	exit;
}

$image_clause = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
	$upload_dir = '../../../uploads/';
	if (!is_dir($upload_dir)) { @mkdir($upload_dir, 0755, true); }

	$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime = finfo_file($finfo, $_FILES['image']['tmp_name']);
	finfo_close($finfo);
	if (!isset($allowed[$mime])) {
		header('Location: ../index.php?error=Invalid image type');
		exit;
	}
	$ext = $allowed[$mime];
	$basename = 'school_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
	$target = $upload_dir . $basename;
	if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
		header('Location: ../index.php?error=Failed to save image');
		exit;
	}
	$image_clause = ", image = '" . mysqli_real_escape_string($conn, $basename) . "'";
}

$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);

$update = "UPDATE school_dynamic_sections SET title = '$title_safe', description = '$desc_safe' $image_clause WHERE id = $id";
$ok = mysqli_query($conn, $update);

if ($ok) {
	header('Location: ../index.php?success=Section updated');
} else {
	header('Location: ../index.php?error=Update failed');
}
exit;


