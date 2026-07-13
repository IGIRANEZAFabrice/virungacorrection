<?php
session_start();
header('Content-Type: application/json');
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

try {
    $admin_id = $_SESSION['community_admin_id'];
    
    // Update last login time for the current admin
    $update_query = "UPDATE community_admins SET last_login = NOW() WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    $result = mysqli_stmt_execute($stmt);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Activity updated',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    } else {
        throw new Exception('Failed to update activity');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to update activity'
    ]);
}

// Close database connection
mysqli_close($conn);
?>
