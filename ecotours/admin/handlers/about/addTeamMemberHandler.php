<?php
/**
 * Add Team Member Handler
 * Handles adding new team members to the About page
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

// Try to use full ImageManager, fallback to SimpleImageManager if GD not available
if (extension_loaded('gd')) {
    require_once '../ImageManager.php';
    $imageManager = new ImageManager();
} else {
    require_once '../SimpleImageManager.php';
    $imageManager = new SimpleImageManager();
}

try {
    // Validate required fields
    $name = trim($_POST['name'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    // Optional social media URLs
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $twitter_url = trim($_POST['twitter_url'] ?? '');
    $instagram_url = trim($_POST['instagram_url'] ?? '');

    // Validation
    if (empty($name)) {
        throw new Exception("Team member name is required.");
    }

    if (strlen($name) > 255) {
        throw new Exception("Name is too long (maximum 255 characters).");
    }

    if (empty($role)) {
        throw new Exception("Team member role is required.");
    }

    if (strlen($role) > 255) {
        throw new Exception("Role is too long (maximum 255 characters).");
    }

    if (empty($bio)) {
        throw new Exception("Team member bio is required.");
    }

    if (strlen($bio) > 1000) {
        throw new Exception("Bio is too long (maximum 1000 characters).");
    }

    if (!$display_order || $display_order < 1) {
        throw new Exception("Display order must be a positive number.");
    }

    // Validate URLs if provided
    if (!empty($linkedin_url) && !filter_var($linkedin_url, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid LinkedIn URL format.");
    }

    if (!empty($twitter_url) && !filter_var($twitter_url, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid Twitter URL format.");
    }

    if (!empty($instagram_url) && !filter_var($instagram_url, FILTER_VALIDATE_URL)) {
        throw new Exception("Invalid Instagram URL format.");
    }

    // Get the active team section ID
    $section_sql = "SELECT section_id FROM about_team_section WHERE is_active = 1 LIMIT 1";
    $section_result = $conn->query($section_sql);

    if ($section_result->num_rows === 0) {
        throw new Exception("No active team section found. Please create a team section first.");
    }

    $section_data = $section_result->fetch_assoc();
    $section_id = $section_data['section_id'];

    // Handle image upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Team member image is required.");
    }

    // Create team directory if it doesn't exist
    $team_dir = '../../images/about/team/';
    if (!is_dir($team_dir)) {
        if (!mkdir($team_dir, 0755, true)) {
            throw new Exception("Failed to create team directory.");
        }
    }

    $upload_result = $imageManager->uploadImage($_FILES['image'], $team_dir);

    if ($upload_result['status'] !== 'success') {
        throw new Exception("Image upload failed: " . $upload_result['message']);
    }

    $image_filename = $upload_result['filename'];

    // Check for duplicate display order
    $order_check_sql = "SELECT member_id FROM about_team_members WHERE section_id = ? AND display_order = ?";
    $order_check_stmt = $conn->prepare($order_check_sql);
    $order_check_stmt->bind_param("ii", $section_id, $display_order);
    $order_check_stmt->execute();
    $order_check_result = $order_check_stmt->get_result();

    if ($order_check_result->num_rows > 0) {
        // Auto-adjust display orders to prevent conflicts
        $adjust_sql = "UPDATE about_team_members SET display_order = display_order + 1 WHERE section_id = ? AND display_order >= ?";
        $adjust_stmt = $conn->prepare($adjust_sql);
        $adjust_stmt->bind_param("ii", $section_id, $display_order);
        $adjust_stmt->execute();
    }

    // Check for duplicate names in the same section
    $name_check_sql = "SELECT member_id FROM about_team_members WHERE section_id = ? AND name = ? AND is_active = 1";
    $name_check_stmt = $conn->prepare($name_check_sql);
    $name_check_stmt->bind_param("is", $section_id, $name);
    $name_check_stmt->execute();
    $name_check_result = $name_check_stmt->get_result();

    if ($name_check_result->num_rows > 0) {
        throw new Exception("A team member with this name already exists.");
    }

    // Start transaction
    $conn->begin_transaction();

    // Insert new team member
    $insert_sql = "INSERT INTO about_team_members (
                    section_id, name, role, bio, image,
                    linkedin_url, twitter_url, instagram_url,
                    display_order, is_active, created_at, updated_at
                   ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isssssssi",
        $section_id, $name, $role, $bio, $image_filename,
        $linkedin_url, $twitter_url, $instagram_url, $display_order
    );

    if (!$insert_stmt->execute()) {
        throw new Exception("Failed to add team member: " . $insert_stmt->error);
    }

    $new_member_id = $conn->insert_id;

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Team member added successfully by admin ID: " . $_SESSION['admin_id'] . " - Member ID: " . $new_member_id . " - Name: " . $name);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Team member "' . $name . '" added successfully!') . '#team-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Delete uploaded image if there was an error after upload
    if (isset($image_filename) && isset($team_dir)) {
        $uploaded_file = $team_dir . $image_filename;
        if (file_exists($uploaded_file)) {
            unlink($uploaded_file);
        }
    }

    // Log error
    error_log("Team member add error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#team-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($order_check_stmt)) $order_check_stmt->close();
    if (isset($adjust_stmt)) $adjust_stmt->close();
    if (isset($name_check_stmt)) $name_check_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();

    // Close database connection
    if (isset($conn)) $conn->close();
}
?>
