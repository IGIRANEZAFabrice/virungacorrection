<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../pages/login.html');
    exit();
}

require_once('../../config/connection.php');

// Image upload function
function uploadImage($file, $uploadDir) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($file['type'], $allowedTypes)) {
        return ['status' => 'error', 'message' => 'Invalid file type. Only JPEG, PNG, GIF, and WebP are allowed.'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['status' => 'error', 'message' => 'File size too large. Maximum 5MB allowed.'];
    }
    
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . uniqid() . '.' . $extension;
    $uploadPath = $uploadDir . $filename;
    
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return ['status' => 'success', 'filename' => $filename];
    } else {
        return ['status' => 'error', 'message' => 'Failed to upload file.'];
    }
}

// Delete image function
function deleteImageFile($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    // Validate required fields
    $required_fields = ['story_id', 'section_title', 'paragraph_1', 'paragraph_2', 'paragraph_3', 'button_text', 'button_link'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    $story_id = intval($_POST['story_id']);
    $section_title = trim($_POST['section_title']);
    $paragraph_1 = trim($_POST['paragraph_1']);
    $paragraph_2 = trim($_POST['paragraph_2']);
    $paragraph_3 = trim($_POST['paragraph_3']);
    $button_text = trim($_POST['button_text']);
    $button_link = trim($_POST['button_link']);
    $existing_story_image = $_POST['existing_story_image'] ?? '';
    
    $story_image = $existing_story_image; // Default to existing image
    
    // Handle story image upload
    if (isset($_FILES['story_image']) && $_FILES['story_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadDir = '../../images/about/';
        $uploadResult = uploadImage($_FILES['story_image'], $uploadDir);
        
        if ($uploadResult['status'] === 'success') {
            $story_image = $uploadResult['filename'];
            
            // Delete old image if it exists and is different
            if (!empty($existing_story_image) && $existing_story_image !== $story_image) {
                deleteImageFile($uploadDir . $existing_story_image);
            }
        } else {
            throw new Exception('Story image upload failed: ' . $uploadResult['message']);
        }
    }
    
    // Update story section in database
    $sql = "UPDATE about_story SET 
            section_title = ?, 
            paragraph_1 = ?, 
            paragraph_2 = ?, 
            paragraph_3 = ?, 
            button_text = ?, 
            button_link = ?, 
            story_image = ?,
            updated_at = CURRENT_TIMESTAMP 
            WHERE story_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("sssssssi", $section_title, $paragraph_1, $paragraph_2, $paragraph_3, $button_text, $button_link, $story_image, $story_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execution failed: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows were updated. Story section may not exist.');
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect with success message
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Story section updated successfully!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("Story update error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
