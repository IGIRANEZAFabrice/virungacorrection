<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'u703364579_eco');
define('DB_PASS', 'Fab@11823');
define('DB_NAME', 'u703364579_eco');

function getDBConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
