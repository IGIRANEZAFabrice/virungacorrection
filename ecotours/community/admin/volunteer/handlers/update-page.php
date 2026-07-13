<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$desc = trim($_POST['page_description'] ?? '');
if ($page_id <= 0) { header('Location: ../index.php?error=Invalid page'); exit; }
$desc_safe = mysqli_real_escape_string($conn, $desc);
$ok = mysqli_query($conn, "UPDATE volunteer_page SET page_description='$desc_safe' WHERE id=$page_id");
header('Location: ../index.php?' . ($ok ? 'success=Page updated' : 'error=Update failed'));
exit;


