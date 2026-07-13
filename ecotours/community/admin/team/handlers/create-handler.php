<?php
session_start();
require_once '../../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../../index.php');
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../create.php');
    exit;
}

try {
    // Validate required fields
    $name = trim($_POST['name'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $order_position = filter_input(INPUT_POST, 'order_position', FILTER_VALIDATE_INT);
    $status = $_POST['status'] ?? 'active';
    
    // Social media URLs
    $facebook = trim($_POST['facebook'] ?? '');
    $twitter = trim($_POST['twitter'] ?? '');
    $linkedin = trim($_POST['linkedin'] ?? '');
    $instagram = trim($_POST['instagram'] ?? '');
    
    // Validation
    if (empty($name)) {
        throw new Exception("Team member name is required.");
    }
    
    if (strlen($name) > 100) {
        throw new Exception("Name is too long (maximum 100 characters).");
    }
    
    if (empty($title)) {
        throw new Exception("Job title is required.");
    }
    
    if (strlen($title) > 150) {
        throw new Exception("Job title is too long (maximum 150 characters).");
    }
    
    if ($bio && strlen($bio) > 1000) {
        throw new Exception("Biography is too long (maximum 1000 characters).");
    }
    
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email address format.");
    }
    
    if ($email && strlen($email) > 100) {
        throw new Exception("Email address is too long (maximum 100 characters).");
    }
    
    if ($phone && strlen($phone) > 20) {
        throw new Exception("Phone number is too long (maximum 20 characters).");
    }
    
    if (!$order_position || $order_position < 1) {
        $order_position = 1;
    }
    
    if (!in_array($status, ['active', 'inactive'])) {
        $status = 'active';
    }
    
    // Validate social media URLs
    if ($facebook && !filter_var($facebook, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid Facebook URL format.");
    }
    
    if ($twitter && !filter_var($twitter, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid Twitter URL format.");
    }
    
    if ($linkedin && !filter_var($linkedin, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid LinkedIn URL format.");
    }
    
    if ($instagram && !filter_var($instagram, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid Instagram URL format.");
    }
    
    // Handle image upload
    $image_filename = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../../assets/images/team/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                throw new Exception("Failed to create upload directory.");
            }
        }
        
        $file = $_FILES['image'];
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        // Validate file type
        if (!in_array($file_extension, $allowed_extensions)) {
            throw new Exception("Invalid image format. Allowed formats: " . implode(', ', $allowed_extensions));
        }
        
        // Validate file size (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            throw new Exception("Image size must be less than 2MB.");
        }
        
        // Generate unique filename
        $image_filename = uniqid('team_') . '.' . $file_extension;
        $upload_path = $upload_dir . $image_filename;
        
        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception("Failed to upload image.");
        }
        
        // Validate that it's actually an image
        $image_info = getimagesize($upload_path);
        if ($image_info === false) {
            unlink($upload_path);
            throw new Exception("Uploaded file is not a valid image.");
        }
    }
    
    // Check for duplicate names
    $name_check_query = "SELECT id FROM community_team WHERE name = ? AND status = 'active'";
    $name_check_stmt = mysqli_prepare($conn, $name_check_query);
    mysqli_stmt_bind_param($name_check_stmt, "s", $name);
    mysqli_stmt_execute($name_check_stmt);
    $name_check_result = mysqli_stmt_get_result($name_check_stmt);
    
    if (mysqli_num_rows($name_check_result) > 0) {
        if ($image_filename && file_exists($upload_path)) {
            unlink($upload_path);
        }
        throw new Exception("A team member with this name already exists.");
    }
    
    // Check for duplicate order position and adjust if necessary
    $order_check_query = "SELECT id FROM community_team WHERE order_position = ?";
    $order_check_stmt = mysqli_prepare($conn, $order_check_query);
    mysqli_stmt_bind_param($order_check_stmt, "i", $order_position);
    mysqli_stmt_execute($order_check_stmt);
    $order_check_result = mysqli_stmt_get_result($order_check_stmt);
    
    if (mysqli_num_rows($order_check_result) > 0) {
        // Auto-adjust order positions to prevent conflicts
        $adjust_query = "UPDATE community_team SET order_position = order_position + 1 WHERE order_position >= ?";
        $adjust_stmt = mysqli_prepare($conn, $adjust_query);
        mysqli_stmt_bind_param($adjust_stmt, "i", $order_position);
        mysqli_stmt_execute($adjust_stmt);
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    // Insert new team member
    $insert_query = "INSERT INTO community_team (
        name, title, bio, image, email, phone,
        facebook, twitter, linkedin, instagram,
        order_position, status, created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "ssssssssssss",
        $name, $title, $bio, $image_filename, $email, $phone,
        $facebook, $twitter, $linkedin, $instagram, $order_position, $status
    );
    
    if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception("Failed to add team member: " . mysqli_error($conn));
    }
    
    $new_member_id = mysqli_insert_id($conn);
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Log the action
    error_log("Team member added successfully by admin ID: " . $_SESSION['community_admin_id'] . " - Member ID: " . $new_member_id . " - Name: " . $name);
    
    // Success redirect
    header('Location: ../index.php?status=success&message=' . urlencode('Team member "' . $name . '" added successfully!'));
    exit;
    
} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    
    // Delete uploaded image if there was an error after upload
    if (isset($image_filename) && isset($upload_path) && file_exists($upload_path)) {
        unlink($upload_path);
    }
    
    // Log error
    error_log("Team member add error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['community_admin_id'] ?? 'unknown'));
    
    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../create.php?error=' . urlencode($error_message));
    exit;
}

// Close database connection
mysqli_close($conn);
?>
