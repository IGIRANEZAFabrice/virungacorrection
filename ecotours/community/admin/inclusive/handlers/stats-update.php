<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$page_id = isset($_POST['page_id']) ? (int)$_POST['page_id'] : 0;
$stat_number = trim($_POST['stat_number'] ?? '');
$stat_label = trim($_POST['stat_label'] ?? '');

if ($id <= 0 || $page_id <= 0 || $stat_number === '' || $stat_label === '') {
    header('Location: ../index.php?error=' . urlencode('Invalid input for stat update')); exit;
}

$stmt = mysqli_prepare($conn, "UPDATE inclusive_stats SET stat_number=?, stat_label=? WHERE id=? AND page_id=?");
mysqli_stmt_bind_param($stmt, 'ssii', $stat_number, $stat_label, $id, $page_id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    header('Location: ../index.php?success=' . urlencode('Stat updated successfully'));
} else {
    header('Location: ../index.php?error=' . urlencode('Failed to update stat'));
}
exit;

