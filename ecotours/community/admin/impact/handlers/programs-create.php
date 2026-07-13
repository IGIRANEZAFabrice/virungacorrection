<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$features = trim($_POST['features'] ?? '');
if ($page_id <= 0 || $title === '' || $description === '' || !isset($_FILES['image'])) { header('Location: ../index.php?error=Missing fields'); exit; }

$dir = '../../../uploads/impact/'; if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) { header('Location: ../index.php?error=Upload failed'); exit; }
$allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE); $mime = finfo_file($finfo, $_FILES['image']['tmp_name']); finfo_close($finfo);
if (!isset($allowed[$mime])) { header('Location: ../index.php?error=Invalid image'); exit; }
$ext = $allowed[$mime];
$name = 'impact_prog_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
if (!move_uploaded_file($_FILES['image']['tmp_name'], $dir . $name)) { header('Location: ../index.php?error=Save failed'); exit; }

$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);
$feat_safe = mysqli_real_escape_string($conn, $features);
$img_safe = mysqli_real_escape_string($conn, $name);
$ok = mysqli_query($conn, "INSERT INTO teaching_programs (page_id, image, title, description, features) VALUES ($page_id, '$img_safe', '$title_safe', '$desc_safe', '$feat_safe')");
header('Location: ../index.php?' . ($ok ? 'success=Program added' : 'error=Insert failed'));
exit;


