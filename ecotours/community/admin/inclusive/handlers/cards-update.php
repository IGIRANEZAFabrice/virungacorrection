<?php
session_start();
require_once '../../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../../index.php'); exit; }

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$page_id = isset($_POST['page_id']) ? (int)$_POST['page_id'] : 0;
$number = isset($_POST['number']) ? (int)$_POST['number'] : 0;
$image = trim($_POST['image'] ?? '');
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($id <= 0 || $page_id <= 0 || $number <= 0 || $image === '' || $title === '' || $description === '') {
    header('Location: ../index.php?error=' . urlencode('Invalid input for card update')); exit;
}

$stmt = mysqli_prepare($conn, "UPDATE approach_cards SET number=?, image=?, title=?, description=? WHERE id=? AND page_id=?");
mysqli_stmt_bind_param($stmt, 'isssii', $number, $image, $title, $description, $id, $page_id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if ($ok) {
    header('Location: ../index.php?success=' . urlencode('Card updated successfully'));
} else {
    header('Location: ../index.php?error=' . urlencode('Failed to update card'));
}
exit;

