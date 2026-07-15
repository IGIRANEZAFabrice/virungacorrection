<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'u703364579_eco');
define('DB_USER', 'u703364579_eco');
define('DB_PASS', 'Fab@11823');

// PDO options for better security and performance
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
    PDO::ATTR_PERSISTENT         => true
];

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);

    // Test connection
    $pdo->query('SELECT 1');
} catch (PDOException $e) {
    // Log error details securely
    error_log("Connection failed: " . $e->getMessage());
    
    // Show generic error message
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again later.'
    ]));
}

// Make connection available globally
return $pdo;
?>