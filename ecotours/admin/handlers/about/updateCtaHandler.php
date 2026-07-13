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
    $required_fields = ['cta_id', 'section_title', 'section_description', 'button_text', 'button_link'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    $cta_id = intval($_POST['cta_id']);
    $section_title = trim($_POST['section_title']);
    $section_description = trim($_POST['section_description']);
    $button_text = trim($_POST['button_text']);
    $button_link = trim($_POST['button_link']);
    $facebook_url = trim($_POST['facebook_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $pinterest_url = trim($_POST['pinterest_url'] ?? '');
    $existing_background_image = $_POST['existing_background_image'] ?? '';
    
    $background_image = $existing_background_image; // Default to existing image
    
    // Handle background image upload
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadDir = '../../images/about/';
        $uploadResult = uploadImage($_FILES['background_image'], $uploadDir);
        
        if ($uploadResult['status'] === 'success') {
            $background_image = $uploadResult['filename'];
            
            // Delete old image if it exists and is different
            if (!empty($existing_background_image) && $existing_background_image !== $background_image) {
                deleteImageFile($uploadDir . $existing_background_image);
            }
        } else {
            throw new Exception('Background image upload failed: ' . $uploadResult['message']);
        }
    }
    
    // Update CTA section in database
    $sql = "UPDATE about_cta SET 
            section_title = ?, 
            section_description = ?, 
            button_text = ?, 
            button_link = ?, 
            background_image = ?, 
            facebook_url = ?, 
            instagram_url = ?, 
            twitter_url = ?, 
            youtube_url = ?, 
            pinterest_url = ?,
            updated_at = CURRENT_TIMESTAMP 
            WHERE cta_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ssssssssssi", $section_title, $section_description, $button_text, $button_link, $background_image, $facebook_url, $instagram_url, $twitter_url, $youtube_url, $pinterest_url, $cta_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execution failed: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows were updated. CTA section may not exist.');
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect with success message
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Call to Action section updated successfully!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("CTA update error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
