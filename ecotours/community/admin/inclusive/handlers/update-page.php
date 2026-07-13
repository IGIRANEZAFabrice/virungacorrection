<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$title = trim($_POST['hero_title'] ?? '');
$subtitle = trim($_POST['hero_subtitle'] ?? '');
$intro = trim($_POST['intro_text'] ?? '');
if ($page_id <= 0 || $title === '') { header('Location: ../index.php?error=Invalid input'); exit; }

$title_safe = mysqli_real_escape_string($conn, $title);
$subtitle_safe = mysqli_real_escape_string($conn, $subtitle);
$intro_safe = mysqli_real_escape_string($conn, $intro);
$ok = mysqli_query($conn, "UPDATE inclusive_page SET hero_title='$title_safe', hero_subtitle='$subtitle_safe', intro_text='$intro_safe' WHERE id=$page_id");
header('Location: ../index.php?' . ($ok ? 'success=Page updated' : 'error=Update failed'));
exit;


