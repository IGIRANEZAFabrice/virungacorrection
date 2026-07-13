<?php
require_once('../config/connection.php');

// Start session to get admin ID
session_start();
$admin_id = $_SESSION['admin_id'] ?? null;

// Check if admin is logged in
if (!$admin_id) {
    header('Location: ../pages/login.html');
    exit;
}

// Process password change form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate inputs
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['profile_errors'] = 'All password fields are required';
        header('Location: ../pages/profile.php#security');
        exit;
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['profile_errors'] = 'New passwords do not match';
        header('Location: ../pages/profile.php#security');
        exit;
    }

    // Get current password hash from database
    $sql = "SELECT password FROM admins WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    // Verify current password
    if (!password_verify($current_password, $admin['password'])) {
        $_SESSION['profile_errors'] = 'Current password is incorrect';
        header('Location: ../pages/profile');
        exit;
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password in database
    $update_sql = "UPDATE admins SET password = ? WHERE admin_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $hashed_password, $admin_id);
    
    if ($update_stmt->execute()) {
        $_SESSION['profile_success'] = 'Password updated successfully';
        header('Location: ../pages/profile.php');
    } else {
        $_SESSION['profile_errors'] = 'Failed to update password';
        header('Location: ../pages/profile.php');
    }
    
    $update_stmt->close();
    exit;
}

// Redirect if accessed directly
header('Location: ../pages/profile.php');
exit;
?>