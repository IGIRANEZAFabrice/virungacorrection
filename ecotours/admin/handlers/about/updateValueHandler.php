<?php
/**
 * Update Value Handler
 * Handles updating individual company values for the About page
 */

session_start();

// Security check
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../pages/login.html');
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/about_page_manager.php');
    exit();
}

require_once '../../config/connection.php';

try {
    // Validate required fields
    $value_id = filter_input(INPUT_POST, 'value_id', FILTER_VALIDATE_INT);
    $icon_class = trim($_POST['icon_class'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    // Validation
    if (!$value_id) {
        throw new Exception("Invalid value ID.");
    }

    if (empty($icon_class)) {
        throw new Exception("Icon class is required.");
    }

    if (strlen($icon_class) > 100) {
        throw new Exception("Icon class is too long (maximum 100 characters).");
    }

    // Validate icon class format (should be FontAwesome classes)
    if (!preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/', $icon_class)) {
        throw new Exception("Invalid icon class format. Use FontAwesome format like 'fas fa-leaf'.");
    }

    if (empty($title)) {
        throw new Exception("Value title is required.");
    }

    if (strlen($title) > 255) {
        throw new Exception("Title is too long (maximum 255 characters).");
    }

    if (empty($description)) {
        throw new Exception("Value description is required.");
    }

    if (strlen($description) > 1000) {
        throw new Exception("Description is too long (maximum 1000 characters).");
    }

    if (!$display_order || $display_order < 1) {
        throw new Exception("Display order must be a positive number.");
    }

    // Check if value exists and get section_id
    $check_sql = "SELECT value_id, section_id FROM about_values WHERE value_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $value_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        throw new Exception("Value not found.");
    }

    $value_data = $check_result->fetch_assoc();
    $section_id = $value_data['section_id'];

    // Check for duplicate display order (excluding current value)
    $order_check_sql = "SELECT value_id FROM about_values WHERE section_id = ? AND display_order = ? AND value_id != ?";
    $order_check_stmt = $conn->prepare($order_check_sql);
    $order_check_stmt->bind_param("iii", $section_id, $display_order, $value_id);
    $order_check_stmt->execute();
    $order_check_result = $order_check_stmt->get_result();

    if ($order_check_result->num_rows > 0) {
        // Auto-adjust display orders to prevent conflicts
        $adjust_sql = "UPDATE about_values SET display_order = display_order + 1 WHERE section_id = ? AND display_order >= ? AND value_id != ?";
        $adjust_stmt = $conn->prepare($adjust_sql);
        $adjust_stmt->bind_param("iii", $section_id, $display_order, $value_id);
        $adjust_stmt->execute();
    }

    // Check for duplicate titles in the same section (excluding current value)
    $title_check_sql = "SELECT value_id FROM about_values WHERE section_id = ? AND title = ? AND value_id != ? AND is_active = 1";
    $title_check_stmt = $conn->prepare($title_check_sql);
    $title_check_stmt->bind_param("isi", $section_id, $title, $value_id);
    $title_check_stmt->execute();
    $title_check_result = $title_check_stmt->get_result();

    if ($title_check_result->num_rows > 0) {
        throw new Exception("A value with this title already exists in this section.");
    }

    // Start transaction
    $conn->begin_transaction();

    // Update value
    $update_sql = "UPDATE about_values SET 
                   icon_class = ?, 
                   title = ?, 
                   description = ?, 
                   display_order = ?, 
                   updated_at = CURRENT_TIMESTAMP 
                   WHERE value_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssii", $icon_class, $title, $description, $display_order, $value_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Failed to update value: " . $update_stmt->error);
    }

    // Verify the update was successful
    if ($update_stmt->affected_rows === 0) {
        throw new Exception("No changes were made to the value.");
    }

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Value updated successfully by admin ID: " . $_SESSION['admin_id'] . " - Value ID: " . $value_id . " - Title: " . $title);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Value "' . $title . '" updated successfully!') . '#values-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Log error
    error_log("Value update error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown') . " - Value ID: " . ($value_id ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#values-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($order_check_stmt)) $order_check_stmt->close();
    if (isset($adjust_stmt)) $adjust_stmt->close();
    if (isset($title_check_stmt)) $title_check_stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    
    // Close database connection
    if (isset($conn)) $conn->close();
}
?>
