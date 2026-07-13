<?php
/**
 * Add Gallery Item Handler
 * Handles adding new gallery items to the About page gallery section
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
    $title = trim($_POST['title'] ?? '');
    $alt_text = trim($_POST['alt_text'] ?? '');
    $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    // Validation
    if (empty($title)) {
        throw new Exception("Gallery item title is required.");
    }

    if (strlen($title) > 255) {
        throw new Exception("Title is too long (maximum 255 characters).");
    }

    if (empty($alt_text)) {
        throw new Exception("Alt text is required for accessibility.");
    }

    if (strlen($alt_text) > 255) {
        throw new Exception("Alt text is too long (maximum 255 characters).");
    }

    if (!$display_order || $display_order < 1) {
        throw new Exception("Display order must be a positive number.");
    }

    // Additional validation for title quality
    if (str_word_count($title) < 2) {
        throw new Exception("Gallery title should contain at least 2 words for better description.");
    }

    if (str_word_count($title) > 8) {
        throw new Exception("Gallery title should be concise (8 words or less).");
    }

    // Validate alt text quality
    if (str_word_count($alt_text) < 3) {
        throw new Exception("Alt text should contain at least 3 words for proper accessibility.");
    }

    // Get the active gallery section ID
    $section_sql = "SELECT section_id FROM about_gallery_section WHERE is_active = 1 LIMIT 1";
    $section_result = $conn->query($section_sql);
    
    if ($section_result->num_rows === 0) {
        throw new Exception("No active gallery section found. Please create a gallery section first.");
    }
    
    $section_data = $section_result->fetch_assoc();
    $section_id = $section_data['section_id'];

    // Handle image upload
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Gallery item image is required.");
    }

    // Create gallery directory if it doesn't exist
    $gallery_dir = '../../images/about/gallery/';
    if (!is_dir($gallery_dir)) {
        if (!mkdir($gallery_dir, 0755, true)) {
            throw new Exception("Failed to create gallery directory.");
        }
    }

    // Validate image for gallery section
    $validation_result = $imageManager->validateForSection($_FILES['image'], 'gallery');
    if ($validation_result !== true) {
        throw new Exception("Image validation failed: " . $validation_result);
    }

    $upload_result = $imageManager->uploadImage($_FILES['image'], $gallery_dir);
    
    if ($upload_result['status'] !== 'success') {
        throw new Exception("Image upload failed: " . $upload_result['message']);
    }

    $image_filename = $upload_result['filename'];

    // Check for duplicate display order
    $order_check_sql = "SELECT gallery_id FROM about_gallery WHERE section_id = ? AND display_order = ?";
    $order_check_stmt = $conn->prepare($order_check_sql);
    $order_check_stmt->bind_param("ii", $section_id, $display_order);
    $order_check_stmt->execute();
    $order_check_result = $order_check_stmt->get_result();

    if ($order_check_result->num_rows > 0) {
        // Auto-adjust display orders to prevent conflicts
        $adjust_sql = "UPDATE about_gallery SET display_order = display_order + 1 WHERE section_id = ? AND display_order >= ?";
        $adjust_stmt = $conn->prepare($adjust_sql);
        $adjust_stmt->bind_param("ii", $section_id, $display_order);
        $adjust_stmt->execute();
    }

    // Check for duplicate titles in the same section
    $title_check_sql = "SELECT gallery_id FROM about_gallery WHERE section_id = ? AND title = ? AND is_active = 1";
    $title_check_stmt = $conn->prepare($title_check_sql);
    $title_check_stmt->bind_param("is", $section_id, $title);
    $title_check_stmt->execute();
    $title_check_result = $title_check_stmt->get_result();

    if ($title_check_result->num_rows > 0) {
        throw new Exception("A gallery item with this title already exists.");
    }

    // Additional validation: Check for HTML injection attempts
    $dangerous_tags = ['<script', '<iframe', '<object', '<embed', '<form'];
    foreach ($dangerous_tags as $tag) {
        if (stripos($title, $tag) !== false || stripos($alt_text, $tag) !== false) {
            throw new Exception("Invalid content detected. HTML tags are not allowed.");
        }
    }

    // Sanitize input (remove any remaining HTML tags for security)
    $title = strip_tags($title);
    $alt_text = strip_tags($alt_text);

    // Check gallery item limit (prevent too many items)
    $count_sql = "SELECT COUNT(*) as item_count FROM about_gallery WHERE section_id = ? AND is_active = 1";
    $count_stmt = $conn->prepare($count_sql);
    $count_stmt->bind_param("i", $section_id);
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $count_data = $count_result->fetch_assoc();

    $max_gallery_items = 12; // Reasonable limit for gallery display
    if ($count_data['item_count'] >= $max_gallery_items) {
        // Delete uploaded image since we're rejecting the item
        $imageManager->deleteImage($gallery_dir . $image_filename);
        throw new Exception("Gallery is full. Maximum {$max_gallery_items} items allowed. Please remove some items first.");
    }

    // Start transaction
    $conn->begin_transaction();

    // Insert new gallery item
    $insert_sql = "INSERT INTO about_gallery (
                    section_id, title, alt_text, image, 
                    display_order, is_active, created_at, updated_at
                   ) VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isssi", $section_id, $title, $alt_text, $image_filename, $display_order);
    
    if (!$insert_stmt->execute()) {
        throw new Exception("Failed to add gallery item: " . $insert_stmt->error);
    }

    $new_gallery_id = $conn->insert_id;

    // Create thumbnail if ImageManager supports it
    if (method_exists($imageManager, 'createThumbnail')) {
        $thumb_dir = $gallery_dir . 'thumbs/';
        if (!is_dir($thumb_dir)) {
            mkdir($thumb_dir, 0755, true);
        }
        
        $thumb_filename = 'thumb_' . $image_filename;
        $thumb_result = $imageManager->createThumbnail(
            $gallery_dir . $image_filename,
            $thumb_dir . $thumb_filename,
            300, 300
        );
        
        if ($thumb_result['status'] === 'success') {
            // Update gallery item with thumbnail filename
            $thumb_update_sql = "UPDATE about_gallery SET image = ? WHERE gallery_id = ?";
            $thumb_update_stmt = $conn->prepare($thumb_update_sql);
            $thumb_update_stmt->bind_param("si", $thumb_filename, $new_gallery_id);
            $thumb_update_stmt->execute();
        }
    }

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Gallery item added successfully by admin ID: " . $_SESSION['admin_id'] . " - Gallery ID: " . $new_gallery_id . " - Title: " . $title);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Gallery item "' . $title . '" added successfully!') . '#gallery-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Delete uploaded image if there was an error after upload
    if (isset($image_filename) && isset($gallery_dir)) {
        $uploaded_file = $gallery_dir . $image_filename;
        if (file_exists($uploaded_file)) {
            unlink($uploaded_file);
        }
    }

    // Log error
    error_log("Gallery item add error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#gallery-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($order_check_stmt)) $order_check_stmt->close();
    if (isset($adjust_stmt)) $adjust_stmt->close();
    if (isset($title_check_stmt)) $title_check_stmt->close();
    if (isset($count_stmt)) $count_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
    if (isset($thumb_update_stmt)) $thumb_update_stmt->close();
    
    // Close database connection
    if (isset($conn)) $conn->close();
}

/**
 * Additional utility functions for gallery management
 */

/**
 * Validate gallery item content quality
 */
function validateGalleryContent($title, $alt_text) {
    $issues = [];
    
    // Title validation
    if (str_word_count($title) < 2) {
        $issues[] = "Title should contain at least 2 words";
    }
    
    if (strlen($title) < 10) {
        $issues[] = "Title should be more descriptive (at least 10 characters)";
    }
    
    // Alt text validation
    if (str_word_count($alt_text) < 3) {
        $issues[] = "Alt text should contain at least 3 words for accessibility";
    }
    
    if (strlen($alt_text) < 15) {
        $issues[] = "Alt text should be more descriptive (at least 15 characters)";
    }
    
    // Check for generic terms
    $generic_terms = ['image', 'picture', 'photo', 'gallery item'];
    foreach ($generic_terms as $term) {
        if (stripos($title, $term) !== false) {
            $issues[] = "Title should be more specific than '{$term}'";
            break;
        }
    }
    
    return $issues;
}

/**
 * Get suggested gallery categories
 */
function getGalleryCategories() {
    return [
        'Destinations' => [
            'Amazon Rainforest Expedition',
            'Himalayan Sustainable Trek',
            'Serengeti Wildlife Safari',
            'Great Barrier Reef Conservation'
        ],
        'Activities' => [
            'Wildlife Photography Workshop',
            'Community Cultural Exchange',
            'Reforestation Volunteer Project',
            'Marine Conservation Diving'
        ],
        'Conservation' => [
            'Tree Planting Initiative',
            'Wildlife Rehabilitation Center',
            'Coral Reef Restoration',
            'Clean Energy Project'
        ],
        'Community' => [
            'Local Artisan Workshop',
            'Traditional Cooking Class',
            'Village School Visit',
            'Sustainable Farming Tour'
        ]
    ];
}

/**
 * Generate SEO-friendly alt text suggestions
 */
function generateAltTextSuggestions($title) {
    $suggestions = [];
    
    // Basic descriptive format
    $suggestions[] = "EcoTours " . $title . " experience";
    $suggestions[] = "Sustainable tourism " . strtolower($title);
    $suggestions[] = "Eco-friendly " . strtolower($title) . " adventure";
    
    return $suggestions;
}
?>
