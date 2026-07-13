<?php
/**
 * Add Value Handler
 * Handles adding new company values to the About page values section
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
    $icon_class = trim($_POST['icon_class'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $display_order = filter_input(INPUT_POST, 'display_order', FILTER_VALIDATE_INT);

    // Validation
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

    // Get the active values section ID
    $section_sql = "SELECT section_id FROM about_values_section WHERE is_active = 1 LIMIT 1";
    $section_result = $conn->query($section_sql);
    
    if ($section_result->num_rows === 0) {
        throw new Exception("No active values section found. Please create a values section first.");
    }
    
    $section_data = $section_result->fetch_assoc();
    $section_id = $section_data['section_id'];

    // Check for duplicate display order
    $order_check_sql = "SELECT value_id FROM about_values WHERE section_id = ? AND display_order = ?";
    $order_check_stmt = $conn->prepare($order_check_sql);
    $order_check_stmt->bind_param("ii", $section_id, $display_order);
    $order_check_stmt->execute();
    $order_check_result = $order_check_stmt->get_result();

    if ($order_check_result->num_rows > 0) {
        // Auto-adjust display orders to prevent conflicts
        $adjust_sql = "UPDATE about_values SET display_order = display_order + 1 WHERE section_id = ? AND display_order >= ?";
        $adjust_stmt = $conn->prepare($adjust_sql);
        $adjust_stmt->bind_param("ii", $section_id, $display_order);
        $adjust_stmt->execute();
    }

    // Check for duplicate titles in the same section
    $title_check_sql = "SELECT value_id FROM about_values WHERE section_id = ? AND title = ? AND is_active = 1";
    $title_check_stmt = $conn->prepare($title_check_sql);
    $title_check_stmt->bind_param("is", $section_id, $title);
    $title_check_stmt->execute();
    $title_check_result = $title_check_stmt->get_result();

    if ($title_check_result->num_rows > 0) {
        throw new Exception("A value with this title already exists in this section.");
    }

    // Additional validation: Check for HTML injection attempts
    $dangerous_tags = ['<script', '<iframe', '<object', '<embed', '<form'];
    foreach ($dangerous_tags as $tag) {
        if (stripos($title, $tag) !== false || stripos($description, $tag) !== false) {
            throw new Exception("Invalid content detected. HTML tags are not allowed.");
        }
    }

    // Sanitize input (remove any remaining HTML tags for security)
    $title = strip_tags($title);
    $description = strip_tags($description);
    $icon_class = strip_tags($icon_class);

    // Validate common FontAwesome icons
    $common_icons = [
        'fas fa-leaf', 'fas fa-handshake', 'fas fa-book-open', 'fas fa-heart',
        'fas fa-certificate', 'fas fa-globe-americas', 'fas fa-users', 'fas fa-seedling',
        'fas fa-recycle', 'fas fa-tree', 'fas fa-mountain', 'fas fa-water',
        'fas fa-sun', 'fas fa-shield-alt', 'fas fa-award', 'fas fa-star',
        'fas fa-thumbs-up', 'fas fa-check-circle', 'fas fa-lightbulb', 'fas fa-compass'
    ];

    // Log if using uncommon icon (for admin awareness)
    if (!in_array($icon_class, $common_icons)) {
        error_log("Uncommon icon used: " . $icon_class . " by admin ID: " . $_SESSION['admin_id']);
    }

    // Start transaction
    $conn->begin_transaction();

    // Insert new value
    $insert_sql = "INSERT INTO about_values (
                    section_id, icon_class, title, description, 
                    display_order, is_active, created_at, updated_at
                   ) VALUES (?, ?, ?, ?, ?, 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
    
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isssi", $section_id, $icon_class, $title, $description, $display_order);
    
    if (!$insert_stmt->execute()) {
        throw new Exception("Failed to add value: " . $insert_stmt->error);
    }

    $new_value_id = $conn->insert_id;

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Value added successfully by admin ID: " . $_SESSION['admin_id'] . " - Value ID: " . $new_value_id . " - Title: " . $title);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode('Value "' . $title . '" added successfully!') . '#values-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Log error
    error_log("Value add error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#values-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($order_check_stmt)) $order_check_stmt->close();
    if (isset($adjust_stmt)) $adjust_stmt->close();
    if (isset($title_check_stmt)) $title_check_stmt->close();
    if (isset($insert_stmt)) $insert_stmt->close();
    
    // Close database connection
    if (isset($conn)) $conn->close();
}

/**
 * Additional utility functions for value management
 */

/**
 * Validate FontAwesome icon class
 */
function validateIconClass($icon_class) {
    // Check basic format
    if (!preg_match('/^(fas|far|fab|fal|fad)\s+fa-[\w-]+$/', $icon_class)) {
        return "Invalid icon format. Use format like 'fas fa-icon-name'";
    }
    
    // Check for potentially problematic characters
    if (preg_match('/[<>"\']/', $icon_class)) {
        return "Icon class contains invalid characters";
    }
    
    return true;
}

/**
 * Get suggested icons for values
 */
function getSuggestedIcons() {
    return [
        'Environmental' => [
            'fas fa-leaf' => 'Leaf (Nature)',
            'fas fa-seedling' => 'Seedling (Growth)',
            'fas fa-tree' => 'Tree (Forest)',
            'fas fa-recycle' => 'Recycle (Sustainability)',
            'fas fa-globe-americas' => 'Globe (Global)',
            'fas fa-water' => 'Water (Conservation)'
        ],
        'Business' => [
            'fas fa-handshake' => 'Handshake (Partnership)',
            'fas fa-users' => 'Users (Community)',
            'fas fa-certificate' => 'Certificate (Quality)',
            'fas fa-award' => 'Award (Excellence)',
            'fas fa-star' => 'Star (Premium)',
            'fas fa-shield-alt' => 'Shield (Trust)'
        ],
        'Education' => [
            'fas fa-book-open' => 'Book (Learning)',
            'fas fa-lightbulb' => 'Lightbulb (Ideas)',
            'fas fa-compass' => 'Compass (Guidance)',
            'fas fa-graduation-cap' => 'Graduation (Education)'
        ],
        'Emotion' => [
            'fas fa-heart' => 'Heart (Love/Care)',
            'fas fa-thumbs-up' => 'Thumbs Up (Approval)',
            'fas fa-smile' => 'Smile (Happiness)',
            'fas fa-check-circle' => 'Check Circle (Success)'
        ]
    ];
}

/**
 * Validate value content for quality
 */
function validateValueContent($title, $description) {
    $issues = [];
    
    // Check title quality
    if (str_word_count($title) < 2) {
        $issues[] = "Title should contain at least 2 words";
    }
    
    if (str_word_count($title) > 6) {
        $issues[] = "Title should be concise (6 words or less)";
    }
    
    // Check description quality
    if (str_word_count($description) < 15) {
        $issues[] = "Description should contain at least 15 words";
    }
    
    if (!preg_match('/[.!?]$/', trim($description))) {
        $issues[] = "Description should end with proper punctuation";
    }
    
    // Check for excessive capitalization
    if (strtoupper($title) === $title && strlen($title) > 5) {
        $issues[] = "Title should not be in all caps";
    }
    
    return $issues;
}
?>
