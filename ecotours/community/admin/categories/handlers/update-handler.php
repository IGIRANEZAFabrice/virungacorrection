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
    header('Location: ../index.php');
    exit;
}

try {
    // Get category ID
    $category_id = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    if (!$category_id) {
        throw new Exception("Invalid category ID.");
    }
    
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
    
    // Check if category exists
    $category_check_query = "SELECT id, name FROM community_categories WHERE id = ?";
    $category_check_stmt = mysqli_prepare($conn, $category_check_query);
    mysqli_stmt_bind_param($category_check_stmt, "i", $category_id);
    mysqli_stmt_execute($category_check_stmt);
    $category_check_result = mysqli_stmt_get_result($category_check_stmt);
    
    if (mysqli_num_rows($category_check_result) === 0) {
        throw new Exception("Category not found.");
    }
    
    $existing_category = mysqli_fetch_assoc($category_check_result);
    $old_name = $existing_category['name'];
    
    // Check for duplicate names (excluding current category)
    $name_check_query = "SELECT id FROM community_categories WHERE name = ? AND id != ?";
    $name_check_stmt = mysqli_prepare($conn, $name_check_query);
    mysqli_stmt_bind_param($name_check_stmt, "si", $name, $category_id);
    mysqli_stmt_execute($name_check_stmt);
    $name_check_result = mysqli_stmt_get_result($name_check_stmt);
    
    if (mysqli_num_rows($name_check_result) > 0) {
        throw new Exception("A category with this name already exists.");
    }
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    // Update category
    $update_query = "UPDATE community_categories SET 
        name = ?, description = ?, icon = ?, color = ?, status = ?, updated_at = NOW()
        WHERE id = ?";
    
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "sssssi",
        $name, $description, $icon, $color, $status, $category_id
    );
    
    if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception("Failed to update category: " . mysqli_error($conn));
    }
    
    if (mysqli_stmt_affected_rows($update_stmt) === 0) {
        throw new Exception("No changes were made to the category.");
    }
    
    // If name changed, update programs that use this category
    if ($old_name !== $name) {
        $programs_update_query = "UPDATE community_programs SET category = ? WHERE category = ?";
        $programs_update_stmt = mysqli_prepare($conn, $programs_update_query);
        mysqli_stmt_bind_param($programs_update_stmt, "ss", $name, $old_name);
        mysqli_stmt_execute($programs_update_stmt);
        
        $affected_programs = mysqli_stmt_affected_rows($programs_update_stmt);
        if ($affected_programs > 0) {
            error_log("Category name change updated $affected_programs programs from '$old_name' to '$name'");
        }
    }
    
    // Commit transaction
    mysqli_commit($conn);
    
    // Log the action
    error_log("Category updated successfully by admin ID: " . $_SESSION['community_admin_id'] . " - Category ID: " . $category_id . " - Name: " . $name);
    
    // Success redirect
    header('Location: ../index.php?status=success&message=' . urlencode('Category "' . $name . '" updated successfully!'));
    exit;
    
} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn)) {
        mysqli_rollback($conn);
    }
    
    // Log error
    error_log("Category update error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['community_admin_id'] ?? 'unknown'));
    
    // Error redirect
    $error_message = $e->getMessage();
    $redirect_id = isset($category_id) ? $category_id : '';
    header('Location: ../edit.php?id=' . $redirect_id . '&error=' . urlencode($error_message));
    exit;
}

// Close database connection
mysqli_close($conn);
?>
