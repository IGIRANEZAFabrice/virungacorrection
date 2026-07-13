<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$icon = trim($_POST['icon'] ?? '');
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
if ($page_id <= 0 || $icon === '' || $title === '' || $description === '') { header('Location: ../index.php?error=Missing fields'); exit; }

$icon_safe = mysqli_real_escape_string($conn, $icon);
$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);
$ok = mysqli_query($conn, "INSERT INTO involved_cards (page_id, icon, title, description) VALUES ($page_id, '$icon_safe', '$title_safe', '$desc_safe')");
header('Location: ../index.php?' . ($ok ? 'success=Card added' : 'error=Insert failed'));
exit;


