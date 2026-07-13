<?php
session_start();
require_once '../../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
	header('Location: ../../index.php');
	exit;
}

function save_section($conn, $name, $content) {
	$name_safe = mysqli_real_escape_string($conn, strtolower(trim($name)));
	$content_safe = mysqli_real_escape_string($conn, trim($content));
	// Upsert
	$query = "INSERT INTO school_static_sections (section_name, content) VALUES ('$name_safe', '$content_safe')
		ON DUPLICATE KEY UPDATE content = VALUES(content)";
	return mysqli_query($conn, $query);
}

$required = ['introduction','our_aim','our_program','take_action'];
foreach ($required as $field) {
	if (!isset($_POST[$field])) {
		header('Location: ../index.php?error=Missing fields');
		exit;
	}
}

$ok = true;
foreach ($required as $field) {
	$ok = $ok && save_section($conn, $field, $_POST[$field]);
}

if ($ok) {
	header('Location: ../index.php?success=Static sections updated');
} else {
	header('Location: ../index.php?error=Failed to update');
}
exit;


