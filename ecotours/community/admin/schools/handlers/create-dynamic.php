<?php
session_start();
require_once '../../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../../index.php');
	exit;
}

// Validate inputs
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($title === '' || $description === '' || !isset($_FILES['image'])) {
	header('Location: ../index.php?error=Please fill all fields');
	exit;
}

// Handle image upload
$upload_dir = '../../../uploads/'; // relative to this script => community/uploads
if (!is_dir($upload_dir)) {
	@mkdir($upload_dir, 0755, true);
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
	header('Location: ../index.php?error=Image upload failed');
	exit;
}

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);
if (!isset($allowed[$mime])) {
	header('Location: ../index.php?error=Invalid image type');
	exit;
}

$ext = $allowed[$mime];
$basename = 'school_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$target = $upload_dir . $basename;

if (!move_uploaded_file($file['tmp_name'], $target)) {
	header('Location: ../index.php?error=Failed to save image');
	exit;
}

$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);
$image_safe = mysqli_real_escape_string($conn, $basename);

$insert = "INSERT INTO school_dynamic_sections (title, description, image, created_at) VALUES ('$title_safe', '$desc_safe', '$image_safe', NOW())";
$ok = mysqli_query($conn, $insert);

if ($ok) {
	header('Location: ../index.php?success=Section added');
} else {
	@unlink($target);
	header('Location: ../index.php?error=Failed to insert');
}
exit;


