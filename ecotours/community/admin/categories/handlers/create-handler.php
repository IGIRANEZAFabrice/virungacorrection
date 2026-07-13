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
    $description = trim($_POST['description'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $color = trim($_POST['color'] ?? '');
    $status = $_POST['status'] ?? 'active';
    
    // Validation
    if (empty($name)) {
        throw new Exception("Category name is required.");
    }
    
    if (strlen($name) > 100) {
        throw new Exception("Category name is too long (maximum 100 characters).");
    }
    
    if ($description && strlen($description) > 500) {
        throw new Exception("Description is too long (maximum 500 characters).");
    }
    
    if ($icon && strlen($icon) > 100) {
        throw new Exception("Icon class is too long (maximum 100 characters).");
    }
    
    if ($color && !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
        throw new Exception("Invalid color format. Please use hex format (#RRGGBB).");
    }
    
    if (!in_array($status, ['active', 'inactive'])) {
        $status = 'active';
    }
    
    // Set defaults if not provided
    if (empty($icon)) {
        $icon = 'fas fa-tag';
    }
    
    if (empty($color)) {
        $color = '#2a4858';
    }
    
    // Check for duplicate names
    $name_check_query = "SELECT id FROM community_categories WHERE name = ?";
    $name_check_stmt = mysqli_prepare($conn, $name_check_query);
    mysqli_stmt_bind_param($name_check_stmt, "s", $name);
    mysqli_stmt_execute($name_check_stmt);
    $name_check_result = mysqli_stmt_get_result($name_check_stmt);
    
    if (mysqli_num_rows($name_check_result) > 0) {
        throw new Exception("A category with this name already exists.");
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    // Insert new category
    $insert_query = "INSERT INTO community_categories (
        name, description, icon, color, status, created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
    
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "sssss",
        $name, $description, $icon, $color, $status
    );
    
    if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception("Failed to create category: " . mysqli_error($conn));
    }
    
    $new_category_id = mysqli_insert_id($conn);
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Log the action
    error_log("Category created successfully by admin ID: " . $_SESSION['community_admin_id'] . " - Category ID: " . $new_category_id . " - Name: " . $name);
    
    // Success redirect
    header('Location: ../index.php?status=success&message=' . urlencode('Category "' . $name . '" created successfully!'));
    exit;
    
} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    
    // Log error
    error_log("Category creation error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['community_admin_id'] ?? 'unknown'));
    
    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../create.php?error=' . urlencode($error_message));
    exit;
}

// Close database connection
mysqli_close($conn);
?>
