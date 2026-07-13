<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$id = (int)($_GET['id'] ?? 0);
$page_id = (int)($_GET['page_id'] ?? 0);
if ($id <= 0 || $page_id <= 0) { header('Location: ../index.php?error=Invalid request'); exit; }

$res = mysqli_query($conn, "SELECT image FROM impact_gallery WHERE id = $id AND page_id = $page_id");
$row = $res ? mysqli_fetch_assoc($res) : null;
$ok = mysqli_query($conn, "DELETE FROM impact_gallery WHERE id = $id AND page_id = $page_id");
if ($ok && $row && $row['image']) {
	$path = '../../../uploads/impact/' . $row['image'];
	if (is_file($path)) { @unlink($path); }
}
header('Location: ../index.php?' . ($ok ? 'success=Gallery item deleted' : 'error=Delete failed'));
exit;


