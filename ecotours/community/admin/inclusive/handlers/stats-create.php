<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$page_id = (int)($_POST['page_id'] ?? 0);
$num = trim($_POST['stat_number'] ?? '');
$label = trim($_POST['stat_label'] ?? '');
if ($page_id <= 0 || $num === '' || $label === '') { header('Location: ../index.php?error=Missing fields'); exit; }

$num_safe = mysqli_real_escape_string($conn, $num);
$label_safe = mysqli_real_escape_string($conn, $label);
$ok = mysqli_query($conn, "INSERT INTO inclusive_stats (page_id, stat_number, stat_label) VALUES ($page_id, '$num_safe', '$label_safe')");
header('Location: ../index.php?' . ($ok ? 'success=Stat added' : 'error=Insert failed'));
exit;


