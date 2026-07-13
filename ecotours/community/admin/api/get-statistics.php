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
    // Get dashboard statistics
    $stats_query = "SELECT 
        COUNT(*) as total_programs,
        COUNT(CASE WHEN status = 'active' THEN 1 END) as active_programs,
        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_programs,
        COUNT(CASE WHEN featured = 1 THEN 1 END) as featured_programs,
        SUM(beneficiaries) as total_beneficiaries
        FROM community_programs";
    $stats_result = mysqli_query($conn, $stats_query);
    $stats = mysqli_fetch_assoc($stats_result);

    // Get testimonials count
    $testimonials_query = "SELECT COUNT(*) as total_testimonials FROM community_testimonials WHERE status = 'active'";
    $testimonials_result = mysqli_query($conn, $testimonials_query);
    $testimonials_count = mysqli_fetch_assoc($testimonials_result)['total_testimonials'];

    // Get messages count
    $messages_query = "SELECT 
        COUNT(*) as total_messages,
        COUNT(CASE WHEN status = 'new' THEN 1 END) as new_messages
        FROM community_messages";
    $messages_result = mysqli_query($conn, $messages_query);
    $messages_stats = mysqli_fetch_assoc($messages_result);

    // Get team members count
    $team_query = "SELECT COUNT(*) as total_team FROM community_team WHERE status = 'active'";
    $team_result = mysqli_query($conn, $team_query);
    $team_count = mysqli_fetch_assoc($team_result)['total_team'];

    // Prepare response
    $response = [
        'success' => true,
        'total_programs' => (int)($stats['total_programs'] ?? 0),
        'active_programs' => (int)($stats['active_programs'] ?? 0),
        'completed_programs' => (int)($stats['completed_programs'] ?? 0),
        'featured_programs' => (int)($stats['featured_programs'] ?? 0),
        'total_beneficiaries' => (int)($stats['total_beneficiaries'] ?? 0),
        'total_testimonials' => (int)$testimonials_count,
        'total_messages' => (int)($messages_stats['total_messages'] ?? 0),
        'new_messages' => (int)($messages_stats['new_messages'] ?? 0),
        'total_team' => (int)$team_count,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error occurred'
    ]);
}

// Close database connection
mysqli_close($conn);
?>
