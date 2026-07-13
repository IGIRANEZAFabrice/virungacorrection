<?php
session_start();

if (!isset($_SESSION['community_admin_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../../../admin/config/connection.php';

try {
    // Get form data
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    
    if (empty($title) || empty($description)) {
        throw new Exception("Title and description are required");
    }
    
    // Handle file upload
    if (!isset($_FILES['image']) || $_FILES['image']['size'] === 0) {
        throw new Exception("Image file is required");
    }
    
    $file = $_FILES['image'];
    $upload_dir = '../../../community/hero/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed");
    }
    
    // Validate file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new Exception("File size exceeds 2MB limit");
    }
    
    // Generate unique filename
    $filename = time() . '_' . basename($file['name']);
    $filepath = $upload_dir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        throw new Exception("Failed to upload image");
    }
    
    $image_url = 'community/hero/' . $filename;
    
    // Insert into database
    $insert_query = "INSERT INTO community_hero (title, description, image_url) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $title, $description, $image_url);
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to add slide: " . $stmt->error);
    }
    
    header("Location: ../hero.php?status=success");
    exit();
    
} catch (Exception $e) {
    header("Location: ../hero.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}
?>

