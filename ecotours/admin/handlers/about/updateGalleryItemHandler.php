<?php
/**
 * Update Gallery Item Handler
 * Handles updating gallery items for the About page
 */

// Enable error reporting for debugging (comment out in production)
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Log all errors to file instead of displaying them
ini_set('log_errors', 1);
ini_set('error_log', '../../logs/php_errors.log');

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

// Include database connection with error handling
try {
    require_once '../../config/connection.php';

    // Verify database connection
    if (!isset($conn) || $conn->connect_error) {
        throw new Exception("Database connection failed: " . ($conn->connect_error ?? 'Unknown error'));
    }
} catch (Exception $e) {
    error_log("Database connection error in updateGalleryItemHandler.php: " . $e->getMessage());
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode('Database connection failed'));
    exit();
}

// Try to include ImageManager classes with better error handling
$imageManager = null;
try {
    if (extension_loaded('gd') && file_exists('../ImageManager.php')) {
        require_once '../ImageManager.php';
        $imageManager = new ImageManager();
    } elseif (file_exists('../SimpleImageManager.php')) {
        require_once '../SimpleImageManager.php';
        $imageManager = new SimpleImageManager();
    } else {
        throw new Exception("No image manager class found");
    }
} catch (Exception $e) {
    error_log("ImageManager initialization error: " . $e->getMessage());
    // Continue without image manager for now, will handle in upload section
}

// Fallback image upload function
function uploadImageFallback($file, $upload_dir) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    // Validate file
    if (!in_array($file['type'], $allowed_types)) {
        return ['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.'];
    }

    if ($file['size'] > $max_size) {
        return ['status' => 'error', 'message' => 'File too large. Maximum size is 5MB.'];
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'gallery_' . time() . '_' . uniqid() . '.' . strtolower($extension);
    $target_path = $upload_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return ['status' => 'success', 'filename' => $filename];
    } else {
        return ['status' => 'error', 'message' => 'Failed to move uploaded file.'];
    }
}

try {
    // Validate required fields
    $gallery_id = filter_input(INPUT_POST, 'gallery_id', FILTER_VALIDATE_INT);
    $title = trim($_POST['title'] ?? '');
    $alt_text = trim($_POST['alt_text'] ?? '');
    $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);
    $existing_image = $_POST['existing_image'] ?? '';

    // Validation
    if (!$gallery_id) {
        throw new Exception("Invalid gallery item ID.");
    }

    if (empty($title)) {
        throw new Exception("Gallery item title is required.");
    }

    if (empty($alt_text)) {
        throw new Exception("Alt text is required for accessibility.");
    }

    if (!$display_order || $display_order < 1) {
        throw new Exception("Display order must be a positive number.");
    }

    // Check if gallery item exists
    $check_sql = "SELECT gallery_id, section_id FROM about_gallery WHERE gallery_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $gallery_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        throw new Exception("Gallery item not found.");
    }

    $gallery_data = $check_result->fetch_assoc();
    $section_id = $gallery_data['section_id'];

    // Handle image upload
    $image_filename = $existing_image; // Default to existing image

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        // Create gallery directory if it doesn't exist
        $gallery_dir = '../../images/about/gallery/';
        if (!is_dir($gallery_dir)) {
            if (!mkdir($gallery_dir, 0755, true)) {
                throw new Exception("Failed to create gallery directory.");
            }
        }

        // Verify directory is writable
        if (!is_writable($gallery_dir)) {
            throw new Exception("Gallery directory is not writable.");
        }

        try {
            // Use ImageManager if available, otherwise use fallback
            if ($imageManager !== null) {
                $upload_result = $imageManager->uploadImage($_FILES['image'], $gallery_dir);
            } else {
                $upload_result = uploadImageFallback($_FILES['image'], $gallery_dir);
            }

            if ($upload_result['status'] === 'success') {
                // Delete old image if it exists and is different
                if (!empty($existing_image) && $existing_image !== $upload_result['filename']) {
                    $old_image_path = $gallery_dir . $existing_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                $image_filename = $upload_result['filename'];
            } else {
                throw new Exception("Image upload failed: " . $upload_result['message']);
            }
        } catch (Exception $e) {
            throw new Exception("Image upload error: " . $e->getMessage());
        }
    }

    // Validate image filename
    if (empty($image_filename)) {
        throw new Exception("Gallery item image is required.");
    }

    // Check for duplicate display order (excluding current item)
    $order_check_sql = "SELECT gallery_id FROM about_gallery WHERE section_id = ? AND display_order = ? AND gallery_id != ?";
    $order_check_stmt = $conn->prepare($order_check_sql);
    $order_check_stmt->bind_param("iii", $section_id, $display_order, $gallery_id);
    $order_check_stmt->execute();
    $order_check_result = $order_check_stmt->get_result();

    if ($order_check_result->num_rows > 0) {
        // Auto-adjust display orders to prevent conflicts
        $adjust_sql = "UPDATE about_gallery SET display_order = display_order + 1 WHERE section_id = ? AND display_order >= ? AND gallery_id != ?";
        $adjust_stmt = $conn->prepare($adjust_sql);
        $adjust_stmt->bind_param("iii", $section_id, $display_order, $gallery_id);
        $adjust_stmt->execute();
    }

    // Start transaction
    $conn->begin_transaction();

    // Update gallery item
    $update_sql = "UPDATE about_gallery SET
                   title = ?,
                   alt_text = ?,
                   image = ?,
                   display_order = ?,
                   updated_at = CURRENT_TIMESTAMP
                   WHERE gallery_id = ?";

    $update_stmt = $conn->prepare($update_sql);
    if (!$update_stmt) {
        throw new Exception("Failed to prepare update statement: " . $conn->error);
    }

    $update_stmt->bind_param("sssii", $title, $alt_text, $image_filename, $display_order, $gallery_id);

    if (!$update_stmt->execute()) {
        throw new Exception("Failed to update gallery item: " . $update_stmt->error);
    }

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Gallery item updated successfully by admin ID: " . $_SESSION['admin_id'] . " - Gallery ID: " . $gallery_id);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Gallery item updated successfully!') . '#gallery-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Log error
    error_log("Gallery item update error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#gallery-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($order_check_stmt)) $order_check_stmt->close();
    if (isset($adjust_stmt)) $adjust_stmt->close();
    if (isset($update_stmt)) $update_stmt->close();

    // Close database connection
    if (isset($conn)) $conn->close();
}
?>
