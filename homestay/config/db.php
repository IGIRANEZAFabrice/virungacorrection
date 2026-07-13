<?php
$db_host = 'localhost';
$db_user = 'u703364579_fab';
$db_pass = 'Fab@11823';
$db_name = 'u703364579_homestay';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure proper charset
$conn->set_charset("utf8mb4");
?>
