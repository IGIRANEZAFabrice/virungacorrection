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
    $required_fields = ['section_id', 'section_title', 'section_intro'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    $section_id = intval($_POST['section_id']);
    $section_title = trim($_POST['section_title']);
    $section_intro = trim($_POST['section_intro']);
    
    // Update team section in database
    $sql = "UPDATE about_team_section SET 
            section_title = ?, 
            section_intro = ?,
            updated_at = CURRENT_TIMESTAMP 
            WHERE section_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ssi", $section_title, $section_intro, $section_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execution failed: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows were updated. Team section may not exist.');
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect with success message
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Team section updated successfully!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("Team section update error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
