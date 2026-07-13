<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$page_id = (int)($_GET['page_id'] ?? 0);
if ($id <= 0 || $page_id <= 0) { header('Location: ../index.php?error=Invalid request'); exit; }
$ok = mysqli_query($conn, "DELETE FROM inclusive_stats WHERE id=$id AND page_id=$page_id");
header('Location: ../index.php?' . ($ok ? 'success=Stat deleted' : 'error=Delete failed'));
exit;


