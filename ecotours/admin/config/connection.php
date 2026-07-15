<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'u703364579_eco';
$db_pass = 'Fab@11823';
$db_name = 'u703364579_eco';


// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>