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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        sendResponse(false, 'Invalid request method');
    }

    $data = json_decode(file_get_contents('php://input'), true);
    $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = $data['password'] ?? '';

    if (!$email || !$password) {
        sendResponse(false, 'Please provide both email and password');
    }

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        sendResponse(false, 'Invalid credentials');
    }

    // Check account status
    if ($admin['account_status'] !== 'active') {
        sendResponse(false, 'Account is ' . $admin['account_status']);
    }

    // Check login attempts
    if ($admin['login_attempts'] >= 5) {
        // Update account status to suspended
        $stmt = $pdo->prepare("UPDATE admins SET account_status = 'suspended' WHERE admin_id = ?");
        $stmt->execute([$admin['admin_id']]);
        sendResponse(false, 'Account suspended due to multiple failed attempts');
    }

    if (!password_verify($password, $admin['password'])) {
        // Increment login attempts
        $stmt = $pdo->prepare("UPDATE admins SET login_attempts = login_attempts + 1 WHERE admin_id = ?");
        $stmt->execute([$admin['admin_id']]);
        sendResponse(false, 'Invalid credentials');
    }

    // Reset login attempts and update last login
    $stmt = $pdo->prepare("UPDATE admins SET login_attempts = 0, last_login = NOW() WHERE admin_id = ?");
    $stmt->execute([$admin['admin_id']]);

    // Log activity
    $stmt = $pdo->prepare("INSERT INTO admin_activity_logs (admin_id, action, description, ip_address) VALUES (?, 'login', 'Successful login', ?)");
    $stmt->execute([$admin['admin_id'], $_SERVER['REMOTE_ADDR']]);

    // Set session variables
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['admin_image'] = $admin['profile_image'];

    sendResponse(true, 'Login successful', [
        'redirect' => '../index.php',
        'name' => $_SESSION['admin_name']
    ]);

} catch (PDOException $e) {
    sendResponse(false, 'Database error occurred');
} catch (Exception $e) {
    sendResponse(false, 'An unexpected error occurred');
}
