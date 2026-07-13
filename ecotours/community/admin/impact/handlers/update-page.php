<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$desc = trim($_POST['section_description'] ?? '');
if ($page_id <= 0) { header('Location: ../index.php?error=Invalid page'); exit; }

$desc_safe = mysqli_real_escape_string($conn, $desc);
$ok = mysqli_query($conn, "UPDATE impact_page SET section_description = '$desc_safe' WHERE id = $page_id");
if ($ok) { header('Location: ../index.php?success=Page updated'); } else { header('Location: ../index.php?error=Update failed'); }
exit;


