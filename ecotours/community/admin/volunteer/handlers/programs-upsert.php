<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$title = trim($_POST['section_title'] ?? '');
$desc = trim($_POST['section_description'] ?? '');
$content = trim($_POST['content'] ?? '');
$highlights = trim($_POST['highlights'] ?? '');
$shop_link = trim($_POST['shop_link'] ?? '');
$shop_note = trim($_POST['shop_note'] ?? '');
if ($page_id <= 0 || $title === '' || $desc === '' || $content === '') { header('Location: ../index.php?error=Missing fields'); exit; }

$title_safe = mysqli_real_escape_string($conn, $title);
$desc_safe = mysqli_real_escape_string($conn, $desc);
$content_safe = mysqli_real_escape_string($conn, $content);
$high_safe = mysqli_real_escape_string($conn, $highlights);
$link_safe = mysqli_real_escape_string($conn, $shop_link);
$note_safe = mysqli_real_escape_string($conn, $shop_note);

// Upsert: insert new row; you can choose to always insert, keeping history
$ok = mysqli_query($conn, "INSERT INTO empowerment_programs (page_id, section_title, section_description, content, highlights, shop_link, shop_note) VALUES ($page_id, '$title_safe', '$desc_safe', '$content_safe', '$high_safe', '$link_safe', '$note_safe')");
header('Location: ../index.php?' . ($ok ? 'success=Empowerment section saved' : 'error=Save failed'));
exit;


