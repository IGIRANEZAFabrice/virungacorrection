<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$number = (int)($_POST['number'] ?? 0);
$image = trim($_POST['image'] ?? '');
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
if ($page_id <= 0 || $number <= 0 || $image === '' || $title === '' || $description === '') { header('Location: ../index.php?error=Missing fields'); exit; }

$img_safe = mysqli_real_escape_string($conn, $image);
$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $description);
$ok = mysqli_query($conn, "INSERT INTO approach_cards (page_id, number, image, title, description) VALUES ($page_id, $number, '$img_safe', '$title_safe', '$desc_safe')");
header('Location: ../index.php?' . ($ok ? 'success=Card added' : 'error=Insert failed'));
exit;


