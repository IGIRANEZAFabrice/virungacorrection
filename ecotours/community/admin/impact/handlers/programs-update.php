<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$id = (int)($_POST['id'] ?? 0);
$page_id = (int)($_POST['page_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$features = trim($_POST['features'] ?? '');
if ($id <= 0 || $page_id <= 0 || $title === '' || $description === '') { header('Location: ../index.php?error=Invalid input'); exit; }

$image_clause = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $dir = '../../../uploads/impact/'; if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE); $mime = finfo_file($finfo, $_FILES['image']['tmp_name']); finfo_close($finfo);
    if (!isset($allowed[$mime])) { header('Location: ../index.php?error=Invalid image type'); exit; }
    $ext = $allowed[$mime];
    $name = 'impact_prog_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $dir . $name)) { header('Location: ../index.php?error=Failed to save image'); exit; }
    $image_clause = ", image = '" . mysqli_real_escape_string($conn, $name) . "'";
}

$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);
$feat_safe = mysqli_real_escape_string($conn, $features);
$ok = mysqli_query($conn, "UPDATE teaching_programs SET title='$title_safe', description='$desc_safe', features='$feat_safe' $image_clause WHERE id=$id AND page_id=$page_id");
header('Location: ../index.php?' . ($ok ? 'success=Program updated' : 'error=Update failed'));
exit;


