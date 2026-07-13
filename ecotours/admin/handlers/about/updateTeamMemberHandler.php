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
    $required_fields = ['member_id', 'name', 'role', 'bio', 'display_order'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    $member_id = intval($_POST['member_id']);
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $bio = trim($_POST['bio']);
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');
    $display_order = intval($_POST['display_order']);
    $existing_image = $_POST['existing_image'] ?? '';
    
    $image = $existing_image; // Default to existing image
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $uploadDir = '../../images/about/team/';
        $uploadResult = uploadImage($_FILES['image'], $uploadDir);
        
        if ($uploadResult['status'] === 'success') {
            $image = $uploadResult['filename'];
            
            // Delete old image if it exists and is different
            if (!empty($existing_image) && $existing_image !== $image) {
                deleteImageFile($uploadDir . $existing_image);
            }
        } else {
            throw new Exception('Image upload failed: ' . $uploadResult['message']);
        }
    }
    
    // Update team member in database
    $sql = "UPDATE about_team_members SET 
            name = ?, 
            role = ?, 
            bio = ?, 
            image = ?, 
            linkedin_url = ?, 
            twitter_url = ?, 
            instagram_url = ?, 
            display_order = ?,
            updated_at = CURRENT_TIMESTAMP 
            WHERE member_id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Database prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("sssssssii", $name, $role, $bio, $image, $linkedin_url, $twitter_url, $instagram_url, $display_order, $member_id);
    
    if (!$stmt->execute()) {
        throw new Exception('Database execution failed: ' . $stmt->error);
    }
    
    if ($stmt->affected_rows === 0) {
        throw new Exception('No rows were updated. Team member may not exist.');
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect with success message
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Team member updated successfully!'));
    exit();
    
} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("Team member update error: " . $e->getMessage());
    
    // Redirect with error message
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($e->getMessage()));
    exit();
}
?>
