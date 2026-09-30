<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Verify admin authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Admin session expired. Please log in again.']);
    exit();
}

require_once __DIR__ . '/../../config/connection.php';

// Accept parameters from POST or JSON body
$blogId = isset($_POST['blog_id']) ? (int)$_POST['blog_id'] : 0;
$newStatus = trim($_POST['new_status'] ?? $_POST['status'] ?? '');

if ($blogId <= 0 || empty($newStatus)) {
    $rawInput = file_get_contents('php://input');
    $jsonData = json_decode($rawInput, true);
    if (!empty($jsonData['blog_id'])) {
        $blogId = (int)$jsonData['blog_id'];
    }
    if (!empty($jsonData['new_status'])) {
        $newStatus = trim($jsonData['new_status']);
    } elseif (!empty($jsonData['status'])) {
        $newStatus = trim($jsonData['status']);
    }
}

$newStatus = strtolower($newStatus);
$allowedStatuses = ['published', 'draft', 'archived'];

if ($blogId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing blog ID.']);
    exit();
}

if (!in_array($newStatus, $allowedStatuses, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid status value. Allowed: ' . implode(', ', $allowedStatuses)]);
    exit();
}

try {
    // Check if blog post exists
    $checkStmt = $conn->prepare("SELECT blog_id, status FROM blog_posts WHERE blog_id = ?");
    $checkStmt->bind_param("i", $blogId);
    $checkStmt->execute();
    $res = $checkStmt->get_result();
    
    if ($res->num_rows === 0) {
        $checkStmt->close();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Blog post not found.']);
        exit();
    }
    $checkStmt->close();

    // Update status
    $sql = "UPDATE blog_posts 
            SET status = ?, 
                updated_at = NOW(), 
                published_at = CASE 
                    WHEN ? = 'published' AND (published_at IS NULL OR published_at = '0000-00-00 00:00:00') THEN NOW() 
                    ELSE published_at 
                END 
            WHERE blog_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $newStatus, $newStatus, $blogId);
    $updated = $stmt->execute();
    $stmt->close();

    if ($updated) {
        echo json_encode([
            'success' => true,
            'message' => 'Article status successfully changed to \'' . ucfirst($newStatus) . '\'.',
            'status' => $newStatus,
            'blog_id' => $blogId
        ]);
        exit();
    } else {
        throw new Exception("Database failed to update status.");
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error updating article status: ' . $e->getMessage()
    ]);
    exit();
}
