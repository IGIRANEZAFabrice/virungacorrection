<?php
session_start();
require_once '../../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../../index.php');
	exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
	header('Location: ../index.php?error=Invalid id');
	exit;
}

// Optionally fetch image to delete file
$res = mysqli_query($conn, "SELECT image FROM school_dynamic_sections WHERE id = $id");
$row = $res ? mysqli_fetch_assoc($res) : null;

$ok = mysqli_query($conn, "DELETE FROM school_dynamic_sections WHERE id = $id");
if ($ok) {
	if ($row && !empty($row['image'])) {
		$path = '../../../uploads/' . $row['image'];
		if (is_file($path)) { @unlink($path); }
	}
	header('Location: ../index.php?success=Section deleted');
} else {
	header('Location: ../index.php?error=Delete failed');
}
exit;


