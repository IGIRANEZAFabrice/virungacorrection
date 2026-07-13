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
    header('Location: ../create.php');
    exit;
}

// Get form data
$title = mysqli_real_escape_string($conn, $_POST['title']);
$content = mysqli_real_escape_string($conn, $_POST['content']);
$display_order = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 1;
$status = mysqli_real_escape_string($conn, $_POST['status']);

// Validate required fields
if (empty($title) || empty($content)) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header('Location: ../create.php');
    exit;
}

// Handle image upload
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Validate file type and size
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        $_SESSION['error'] = "Invalid file type. Please upload a JPG, PNG, GIF, or WEBP image.";
        header('Location: ../create.php');
        exit;
    }
    
    if ($_FILES['image']['size'] > $max_size) {
        $_SESSION['error'] = "File size exceeds the maximum limit of 2MB.";
        header('Location: ../create.php');
        exit;
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = '../../../uploads/activities/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $filename = 'activity_' . time() . '_' . uniqid() . '.' . $file_extension;
    $target_file = $upload_dir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $image_path =  $filename;
    } else {
        $_SESSION['error'] = "Failed to upload image. Please try again.";
        header('Location: ../create.php');
        exit;
    }
} else {
    $_SESSION['error'] = "Image is required.";
    header('Location: ../create.php');
    exit;
}

// Insert into database
$admin_id = $_SESSION['community_admin_id'];
$created_at = date('Y-m-d H:i:s');

$query = "INSERT INTO community_activities (title, content, image, display_order, status, created_at) 
          VALUES ('$title', '$content', '$image_path', $display_order, '$status', '$created_at')";

if (mysqli_query($conn, $query)) {
    $_SESSION['success'] = "Activity created successfully.";
    
    // Log admin action if admin_logs table exists
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
    if (mysqli_num_rows($table_check) > 0) {
        $action = "Created new activity: $title";
        $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/handlers/create-handler.php', NOW())";
        mysqli_query($conn, $log_query);
    }
    
    header('Location: ../index.php');
    exit;
} else {
    $_SESSION['error'] = "Error creating activity: " . mysqli_error($conn);
    header('Location: ../create.php');
    exit;
}
?>