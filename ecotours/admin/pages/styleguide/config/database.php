<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'dmxewbmy_homestay');
define('DB_PASS', 'Igiraneza@11823');
define('DB_NAME', 'dmxewbmy_ecodatabaseb');

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
