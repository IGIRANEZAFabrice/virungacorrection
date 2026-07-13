<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

require_once('../config/connection.php');

// Enable error logging for debugging
$log_file = '../../../logs/upload_debug.log';
$log_dir = dirname($log_file);
if (!is_dir($log_dir)) {
    @mkdir($log_dir, 0755, true);
}

function log_upload_debug($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    $log_message = "[$timestamp] $message\n";
    @file_put_contents($log_file, $log_message, FILE_APPEND);
}

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Log the action
log_upload_debug("Action: $action");

// Handle accommodation CRUD operations
if ($action === 'create' || $action === 'update') {
    $tier_id = intval($_POST['tier_id']);
    $name = $_POST['name'];
    $location = $_POST['location'];
    $price_min = isset($_POST['price_min']) && $_POST['price_min'] !== '' ? floatval($_POST['price_min']) : null;
    $price_max = isset($_POST['price_max']) && $_POST['price_max'] !== '' ? floatval($_POST['price_max']) : null;
    $price_display = $_POST['price_display'];
    $short_description = $_POST['short_description'] ?? '';
    $full_description = $_POST['full_description'] ?? '';
    $accommodation_type = $_POST['accommodation_type'] ?? '';
    $includes = $_POST['includes'] ?? '';
    $guest_capacity = isset($_POST['guest_capacity']) && $_POST['guest_capacity'] !== '' ? intval($_POST['guest_capacity']) : null;
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $featured = isset($_POST['featured']) ? 1 : 0;

    if ($action === 'create') {
        $query = "INSERT INTO accommodations (tier_id, name, location, price_min, price_max, price_display, 
                  short_description, full_description, accommodation_type, includes, guest_capacity, 
                  phone, email, is_active, featured) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issddsssssissi", $tier_id, $name, $location, $price_min, $price_max, $price_display,
                         $short_description, $full_description, $accommodation_type, $includes, $guest_capacity,
                         $phone, $email, $is_active, $featured);
    } else {
        $accommodation_id = intval($_POST['accommodation_id']);
        $query = "UPDATE accommodations SET tier_id=?, name=?, location=?, price_min=?, price_max=?, price_display=?,
                  short_description=?, full_description=?, accommodation_type=?, includes=?, guest_capacity=?,
                  phone=?, email=?, is_active=?, featured=? WHERE accommodation_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issddsssssissi", $tier_id, $name, $location, $price_min, $price_max, $price_display,
                         $short_description, $full_description, $accommodation_type, $includes, $guest_capacity,
                         $phone, $email, $is_active, $featured, $accommodation_id);
    }

    if ($stmt->execute()) {
        header("Location: ../pages/accommodation/index.php?status=success&message=" . urlencode($action === 'create' ? 'Accommodation created successfully' : 'Accommodation updated successfully'));
    } else {
        header("Location: ../pages/accommodation/index.php?status=error&message=" . urlencode('Error: ' . $stmt->error));
    }
    $stmt->close();
}

// Handle accommodation deletion
elseif ($action === 'delete') {
    $accommodation_id = intval($_GET['id']);
    $query = "DELETE FROM accommodations WHERE accommodation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $accommodation_id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/accommodation/index.php?status=success&message=" . urlencode('Accommodation deleted successfully'));
    } else {
        header("Location: ../pages/accommodation/index.php?status=error&message=" . urlencode('Error deleting accommodation'));
    }
    $stmt->close();
}

// Handle hero image operations
elseif ($action === 'create_hero' || $action === 'update_hero') {
    log_upload_debug("=== HERO IMAGE UPLOAD START ===");
    log_upload_debug("Action: $action");

    // Simplified: only image_url is stored in database
    // Hero content (heading, subheading, etc.) is hardcoded in frontend
    $image_url = null;

    // Handle file upload
    if (isset($_FILES['image_file'])) {
        log_upload_debug("File upload detected");
        log_upload_debug("File error code: " . $_FILES['image_file']['error']);
        log_upload_debug("File name: " . $_FILES['image_file']['name']);
        log_upload_debug("File size: " . $_FILES['image_file']['size']);
        log_upload_debug("File type: " . $_FILES['image_file']['type']);
    } else {
        log_upload_debug("No file upload detected");
    }

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        log_upload_debug("File upload validation passed");

        $upload_dir = '../../../images/accommodation/';
        log_upload_debug("Upload directory: $upload_dir");
        log_upload_debug("Upload directory absolute: " . realpath(dirname(__FILE__)) . '/' . $upload_dir);

        // Create directory if it doesn't exist
        if (!is_dir($upload_dir)) {
            log_upload_debug("Directory does not exist, creating...");
            if (@mkdir($upload_dir, 0755, true)) {
                log_upload_debug("Directory created successfully");
                // Set proper permissions after creation
                @chmod($upload_dir, 0755);
                log_upload_debug("Directory permissions set to 0755");
            } else {
                log_upload_debug("ERROR: Failed to create directory");
                log_upload_debug("Attempting alternative directory creation method...");
                // Try alternative method
                if (!file_exists($upload_dir)) {
                    $parts = explode('/', trim($upload_dir, '/'));
                    $path = '';
                    foreach ($parts as $part) {
                        $path .= $part . '/';
                        if (!is_dir($path)) {
                            if (!@mkdir($path, 0755)) {
                                log_upload_debug("ERROR: Failed to create path: $path");
                            } else {
                                log_upload_debug("Created path: $path");
                                @chmod($path, 0755);
                            }
                        }
                    }
                }
            }
        } else {
            log_upload_debug("Directory already exists");
            log_upload_debug("Directory is writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
            // Ensure proper permissions
            @chmod($upload_dir, 0755);
        }

        // Final check - verify directory exists and is writable
        if (!is_dir($upload_dir)) {
            log_upload_debug("CRITICAL ERROR: Directory still does not exist after creation attempts");
            header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Failed to create upload directory. Please contact administrator.'));
            exit();
        }

        if (!is_writable($upload_dir)) {
            log_upload_debug("CRITICAL ERROR: Directory is not writable");
            header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Upload directory is not writable. Please contact administrator.'));
            exit();
        }

        log_upload_debug("Directory check passed - directory exists and is writable");

        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_name = $_FILES['image_file']['name'];
        $file_size = $_FILES['image_file']['size'];

        log_upload_debug("File tmp: $file_tmp");
        log_upload_debug("File tmp exists: " . (file_exists($file_tmp) ? 'YES' : 'NO'));

        // Validate file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        // Get file type - try finfo first, then fallback to extension
        $file_type = null;
        if (function_exists('finfo_file')) {
            log_upload_debug("Using finfo_file for MIME detection");
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_type = finfo_file($finfo, $file_tmp);
            finfo_close($finfo);
            log_upload_debug("Detected MIME type: $file_type");
        } elseif (function_exists('mime_content_type')) {
            log_upload_debug("Using mime_content_type for MIME detection");
            $file_type = mime_content_type($file_tmp);
            log_upload_debug("Detected MIME type: $file_type");
        } else {
            log_upload_debug("WARNING: No MIME detection function available");
        }

        // Validate file type
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        log_upload_debug("File extension: $file_ext");

        if (!in_array($file_ext, $allowed_extensions)) {
            log_upload_debug("ERROR: Invalid file extension: $file_ext");
            header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Invalid file extension. Only JPG, PNG, GIF, and WebP are allowed.'));
            exit();
        }

        if ($file_type && !in_array($file_type, $allowed_types)) {
            log_upload_debug("ERROR: Invalid MIME type: $file_type");
            header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.'));
            exit();
        }

        log_upload_debug("File size: $file_size bytes (max: $max_size)");
        if ($file_size > $max_size) {
            log_upload_debug("ERROR: File size exceeds limit");
            header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('File size exceeds 5MB limit.'));
            exit();
        }

        log_upload_debug("File validation passed");

        // Generate unique filename
        $new_filename = 'hero_' . time() . '_' . uniqid() . '.' . $file_ext;
        $upload_path = $upload_dir . $new_filename;

        log_upload_debug("Generated filename: $new_filename");
        log_upload_debug("Upload path: $upload_path");
        log_upload_debug("Absolute upload path: " . realpath(dirname(__FILE__)) . '/' . $upload_path);

        // Move uploaded file
        log_upload_debug("Attempting to move uploaded file...");
        log_upload_debug("Source: $file_tmp");
        log_upload_debug("Destination: $upload_path");

        if (move_uploaded_file($file_tmp, $upload_path)) {
            log_upload_debug("File moved successfully");
            log_upload_debug("File exists at destination: " . (file_exists($upload_path) ? 'YES' : 'NO'));
            log_upload_debug("File size at destination: " . filesize($upload_path) . ' bytes');

            // Set proper permissions on the uploaded file
            @chmod($upload_path, 0644);
            log_upload_debug("File permissions set to 0644");

            $image_url = '/images/accommodation/' . $new_filename;
            log_upload_debug("Image URL set to: $image_url");
            log_upload_debug("SUCCESS: File uploaded and saved to database");
        } else {
            log_upload_debug("ERROR: move_uploaded_file failed");
            log_upload_debug("File tmp still exists: " . (file_exists($file_tmp) ? 'YES' : 'NO'));
            log_upload_debug("Upload directory writable: " . (is_writable($upload_dir) ? 'YES' : 'NO'));
            log_upload_debug("Upload directory permissions: " . substr(sprintf('%o', fileperms($upload_dir)), -4));

            // Try alternative copy method if move_uploaded_file fails
            log_upload_debug("Attempting alternative copy method...");
            if (copy($file_tmp, $upload_path)) {
                log_upload_debug("File copied successfully using copy()");
                @unlink($file_tmp);
                @chmod($upload_path, 0644);
                $image_url = '/images/accommodation/' . $new_filename;
                log_upload_debug("Image URL set to: $image_url");
                log_upload_debug("SUCCESS: File uploaded via copy method");
            } else {
                log_upload_debug("ERROR: Both move_uploaded_file and copy failed");
                header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Failed to upload file. Please check server permissions.'));
                exit();
            }
        }
    } elseif ($action === 'create_hero') {
        // File is required for create
        log_upload_debug("ERROR: No file uploaded for create_hero action");
        header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Please select an image file.'));
        exit();
    } elseif ($action === 'update_hero') {
        // For update, if no new file, keep existing image_url
        log_upload_debug("Update hero - retrieving existing image URL");
        $hero_id = intval($_POST['hero_id']);
        $query = "SELECT image_url FROM accommodation_hero_images WHERE hero_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $hero_id);
        $stmt->execute();
        $stmt->bind_result($image_url);
        $stmt->fetch();
        $stmt->close();
        log_upload_debug("Existing image URL: $image_url");
    }

    if ($action === 'create_hero') {
        log_upload_debug("Preparing INSERT query");
        log_upload_debug("Image URL: $image_url");

        $query = "INSERT INTO accommodation_hero_images (image_url) VALUES (?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            log_upload_debug("ERROR: Failed to prepare INSERT statement: " . $conn->error);
        }
        $stmt->bind_param("s", $image_url);
    } else {
        log_upload_debug("Preparing UPDATE query");
        $hero_id = intval($_POST['hero_id']);
        log_upload_debug("Hero ID: $hero_id");
        log_upload_debug("Image URL: $image_url");

        $query = "UPDATE accommodation_hero_images SET image_url=? WHERE hero_id=?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            log_upload_debug("ERROR: Failed to prepare UPDATE statement: " . $conn->error);
        }
        $stmt->bind_param("si", $image_url, $hero_id);
    }

    log_upload_debug("Executing database query...");
    if ($stmt->execute()) {
        log_upload_debug("Query executed successfully");
        log_upload_debug("Rows affected: " . $stmt->affected_rows);
        log_upload_debug("=== HERO IMAGE UPLOAD COMPLETE ===");
        header("Location: ../pages/accommodation/hero.php?status=success&message=" . urlencode($action === 'create_hero' ? 'Hero image added successfully' : 'Hero image updated successfully'));
    } else {
        log_upload_debug("ERROR: Query execution failed: " . $stmt->error);
        log_upload_debug("=== HERO IMAGE UPLOAD FAILED ===");
        header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Error: ' . $stmt->error));
    }
    $stmt->close();
}

// Handle hero image deletion
elseif ($action === 'delete_hero') {
    $hero_id = intval($_GET['id']);

    // Get image path before deleting from database
    $query = "SELECT image_url FROM accommodation_hero_images WHERE hero_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hero_id);
    $stmt->execute();
    $stmt->bind_result($image_url_to_delete);
    $stmt->fetch();
    $stmt->close();

    $row = array('image_url' => $image_url_to_delete);

    // Delete from database
    $query = "DELETE FROM accommodation_hero_images WHERE hero_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $hero_id);

    if ($stmt->execute()) {
        // Delete file from server if it exists
        if ($row && $row['image_url']) {
            $file_path = '../..' . $row['image_url'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        header("Location: ../pages/accommodation/hero.php?status=success&message=" . urlencode('Hero image deleted successfully'));
    } else {
        header("Location: ../pages/accommodation/hero.php?status=error&message=" . urlencode('Error deleting hero image'));
    }
    $stmt->close();
}

$conn->close();
?>