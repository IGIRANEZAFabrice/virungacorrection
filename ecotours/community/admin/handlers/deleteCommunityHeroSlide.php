<?php
session_start();

if (!isset($_SESSION['community_admin_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../../../admin/config/connection.php';

try {
    // Get slide ID
    $slide_id = isset($_POST['slide_id']) ? intval($_POST['slide_id']) : 0;
    
    if ($slide_id === 0) {
        throw new Exception("Invalid slide ID");
    }
    
    // Check if slide exists and get image URL
    $check_query = "SELECT image_url FROM community_hero WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("i", $slide_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Slide not found");
    }
    
    $row = $result->fetch_assoc();
    $image_url = $row['image_url'];
    
    // Delete from database
    $delete_query = "DELETE FROM community_hero WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $slide_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to delete slide: " . $stmt->error);
    }
    
    // Delete image file if it exists
    if (!empty($image_url)) {
        $file_path = '../../../' . $image_url;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    header("Location: ../hero.php?status=success");
    exit();
    
} catch (Exception $e) {
    header("Location: ../hero.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}
?>

