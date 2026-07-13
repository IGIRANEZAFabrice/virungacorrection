<?php
session_start();
require_once '../../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../../index.php');
    exit;
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Get form data
$id = mysqli_real_escape_string($conn, $_POST['id']);
$title = mysqli_real_escape_string($conn, $_POST['title']);
$display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
$status = mysqli_real_escape_string($conn, $_POST['status']);
$content = mysqli_real_escape_string($conn, $_POST['content']);

// Validate required fields
if (empty($title) || empty($content)) {
    $_SESSION['error'] = "Title and content are required fields.";
    header("Location: ../edit.php?id=$id");
    exit;
}

// Check if activity exists
$check_query = "SELECT * FROM community_activities WHERE id = '$id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    $_SESSION['error'] = "Activity not found.";
    header('Location: ../index.php');
    exit;
}

$activity = mysqli_fetch_assoc($check_result);
$current_image = $activity['image'];

// Handle image upload if provided
$image = $current_image; // Default to current image

if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $file = $_FILES['image'];
    
    // Validate file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error'] = "Invalid file type. Please upload a JPEG, PNG, GIF, or WEBP image.";
        header("Location: ../edit.php?id=$id");
        exit;
    }
    
    // Validate file size (5MB max)
    if ($file['size'] > 5 * 1024 * 1024) {
        $_SESSION['error'] = "File size too large. Maximum size is 5MB.";
        header("Location: ../edit.php?id=$id");
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = '../../../uploads/activities/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'activity_' . time() . '_' . uniqid() . '.' . $file_ext;
    $target_path = $upload_dir . $filename;
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        $image = $filename;
        
        // Delete old image if it exists and is different
        if (!empty($current_image) && $current_image !== $filename && file_exists($upload_dir . $current_image)) {
            unlink($upload_dir . $current_image);
        }
    } else {
        $_SESSION['error'] = "Failed to upload image. Please try again.";
        header("Location: ../edit.php?id=$id");
        exit;
    }
}

// Update activity in database
$update_query = "UPDATE community_activities SET 
                title = '$title', 
                content = '$content', 
                image = '$image', 
                display_order = $display_order, 
                status = '$status', 
                updated_at = NOW() 
                WHERE id = '$id'";

if (mysqli_query($conn, $update_query)) {
    // Log admin action - only if admin_logs table exists
    $admin_id = $_SESSION['community_admin_id'];
    $action = "Updated activity: $title (ID: $id)";
    
    // Check if admin_logs table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
    if (mysqli_num_rows($table_check) > 0) {
        $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/handlers/edit-handler.php', NOW())";
        mysqli_query($conn, $log_query);
    }
    
    $_SESSION['success'] = "Activity updated successfully.";
    header('Location: ../index.php');
    exit;
} else {
    $_SESSION['error'] = "Failed to update activity: " . mysqli_error($conn);
    header("Location: ../edit.php?id=$id");
    exit;
}
?>