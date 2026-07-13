<?php
// Prevent any output before JSON
ob_start();

$host = 'localhost';
$dbname = 'dmxewbmy_ecodatabaseb';
$username = 'dmxewbmy_homestay';
$password = 'Igiraneza@11823';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Clear any output
    ob_clean();
    
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}
