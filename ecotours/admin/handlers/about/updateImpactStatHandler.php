<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../pages/login.html');
    exit();
}

require_once('../../config/connection.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Validate required fields
    $required_fields = ['stat_id', 'icon_class', 'stat_count', 'stat_title', 'display_order'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    $stat_id = intval($_POST['stat_id']);
    $icon_class = trim($_POST['icon_class']);
    $stat_count = intval($_POST['stat_count']);
    $stat_title = trim($_POST['stat_title']);
    $display_order = intval($_POST['display_order']);
    
    // Update impact stat in database
    $sql = "UPDATE about_impact_stats SET 
            icon_class = ?, 
            stat_count = ?, 
            stat_title = ?, 
            display_order = ?,
            updated_at = CURRENT_TIMESTAMP 
            WHERE stat_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("sisii", $icon_class, $stat_count, $stat_title, $display_order, $stat_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execution failed: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows were updated. Impact stat may not exist.');
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect with success message
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Impact statistic updated successfully!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("Impact stat update error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
