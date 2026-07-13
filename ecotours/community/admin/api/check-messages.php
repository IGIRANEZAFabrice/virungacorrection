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
    // Get new messages count
    $query = "SELECT COUNT(*) as new_messages FROM community_messages WHERE status = 'new'";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);

    // Get recent messages for notification preview
    $recent_query = "SELECT id, name, email, subject, sent_at 
                     FROM community_messages 
                     WHERE status = 'new' 
                     ORDER BY sent_at DESC 
                     LIMIT 3";
    $recent_result = mysqli_query($conn, $recent_query);
    
    $recent_messages = [];
    while ($message = mysqli_fetch_assoc($recent_result)) {
        $recent_messages[] = [
            'id' => $message['id'],
            'name' => $message['name'],
            'email' => $message['email'],
            'subject' => $message['subject'],
            'sent_at' => $message['sent_at'],
            'time_ago' => timeAgo($message['sent_at'])
        ];
    }

    $response = [
        'success' => true,
        'newMessages' => (int)$data['new_messages'],
        'recentMessages' => $recent_messages,
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

// Helper function to calculate time ago
function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) {
        return 'Just now';
    } elseif ($time < 3600) {
        $minutes = floor($time / 60);
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } elseif ($time < 86400) {
        $hours = floor($time / 3600);
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($time < 2592000) {
        $days = floor($time / 86400);
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } else {
        return date('M j, Y', strtotime($datetime));
    }
}

// Close database connection
mysqli_close($conn);
?>
