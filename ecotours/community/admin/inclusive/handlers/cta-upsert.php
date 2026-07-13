<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$text = trim($_POST['text'] ?? '');
$btn_text = trim($_POST['button_text'] ?? 'Get Involved Today');
$btn_link = trim($_POST['button_link'] ?? '');
if ($page_id <= 0 || $title === '' || $text === '') { header('Location: ../index.php?error=Missing fields'); exit; }

$title_safe = mysqli_real_escape_string($conn, $title);
$text_safe = mysqli_real_escape_string($conn, $text);
$bt_safe = mysqli_real_escape_string($conn, $btn_text);
$bl_safe = mysqli_real_escape_string($conn, $btn_link);
$ok = mysqli_query($conn, "INSERT INTO inclusive_cta (page_id, title, text, button_text, button_link) VALUES ($page_id, '$title_safe', '$text_safe', '$bt_safe', '$bl_safe')");
header('Location: ../index.php?' . ($ok ? 'success=CTA saved' : 'error=Save failed'));
exit;


