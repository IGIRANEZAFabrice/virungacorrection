<?php
session_start();
header('Content-Type: application/json');

require_once '../config/database.php';

function sendResponse($success, $message, $data = null) {
    echo json_encode([
        'success' => $success,
        'message' => $message,
        'data' => $data
    ]);
    exit;
}

try {
    // Check if user is logged in
    if (!isset($_SESSION['admin_id'])) {
        sendResponse(false, 'No active session found');
    }

    // Log the logout activity
    $admin_id = $_SESSION['admin_id'];
    $stmt = $pdo->prepare("INSERT INTO admin_activity_logs (admin_id, action, description, ip_address) VALUES (?, 'logout', 'User logged out', ?)");
    $stmt->execute([$admin_id, $_SERVER['REMOTE_ADDR']]);

    // Destroy the session
    session_unset();
    session_destroy();
    
    // Clear session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    sendResponse(true, 'Logout successful');
    
} catch (PDOException $e) {
    sendResponse(false, 'Database error occurred');
} catch (Exception $e) {
    sendResponse(false, 'An unexpected error occurred');
}