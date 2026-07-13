<?php
/**
 * Update Values Section Handler
 * Handles updating the values section header content for the About page
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
    $section_id = filter_input(INPUT_POST, 'section_id', FILTER_VALIDATE_INT);
    $section_title = trim($_POST['section_title'] ?? '');
    $section_intro = trim($_POST['section_intro'] ?? '');

    // Validation
    if (!$section_id) {
        throw new Exception("Invalid section ID.");
    }

    if (empty($section_title)) {
        throw new Exception("Section title is required.");
    }

    if (strlen($section_title) > 255) {
        throw new Exception("Section title is too long (maximum 255 characters).");
    }

    if (empty($section_intro)) {
        throw new Exception("Section introduction is required.");
    }

    if (strlen($section_intro) > 1000) {
        throw new Exception("Section introduction is too long (maximum 1000 characters).");
    }

    // Check if section exists
    $check_sql = "SELECT section_id FROM about_values_section WHERE section_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $section_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        throw new Exception("Values section not found.");
    }

    // Additional validation: Check for HTML injection attempts
    $dangerous_tags = ['<script', '<iframe', '<object', '<embed', '<form'];
    foreach ($dangerous_tags as $tag) {
        if (stripos($section_title, $tag) !== false || stripos($section_intro, $tag) !== false) {
            throw new Exception("Invalid content detected. HTML tags are not allowed.");
        }
    }

    // Sanitize input (remove any remaining HTML tags for security)
    $section_title = strip_tags($section_title);
    $section_intro = strip_tags($section_intro);

    // Start transaction
    $conn->begin_transaction();

    // Update values section
    $update_sql = "UPDATE about_values_section SET 
                   section_title = ?, 
                   section_intro = ?, 
                   updated_at = CURRENT_TIMESTAMP 
                   WHERE section_id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $section_title, $section_intro, $section_id);
    
    if (!$update_stmt->execute()) {
        throw new Exception("Failed to update values section: " . $update_stmt->error);
    }

    // Verify the update was successful
    if ($update_stmt->affected_rows === 0) {
        // Check if the data is actually the same (no real change)
        $verify_sql = "SELECT section_title, section_intro FROM about_values_section WHERE section_id = ?";
        $verify_stmt = $conn->prepare($verify_sql);
        $verify_stmt->bind_param("i", $section_id);
        $verify_stmt->execute();
        $verify_result = $verify_stmt->get_result();
        $current_data = $verify_result->fetch_assoc();
        
        if ($current_data['section_title'] === $section_title && $current_data['section_intro'] === $section_intro) {
            // Data is the same, this is okay
            $message = "Values section is already up to date.";
        } else {
            throw new Exception("Failed to update values section - no rows affected.");
        }
    } else {
        $message = "Values section updated successfully!";
    }

    // Commit transaction
    $conn->commit();

    // Log the action
    error_log("Values section updated successfully by admin ID: " . $_SESSION['admin_id'] . " - Section ID: " . $section_id);

    // Success redirect
    header('Location: ../../pages/about_page_manager.php?status=success&message=' . urlencode($message) . '#values-tab');
    exit();

} catch (Exception $e) {
    // Rollback transaction if it was started
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }

    // Log error
    error_log("Values section update error: " . $e->getMessage() . " - Admin ID: " . ($_SESSION['admin_id'] ?? 'unknown') . " - Section ID: " . ($section_id ?? 'unknown'));

    // Error redirect
    $error_message = $e->getMessage();
    header('Location: ../../pages/about_page_manager.php?status=error&message=' . urlencode($error_message) . '#values-tab');
    exit();

} finally {
    // Close prepared statements
    if (isset($check_stmt)) $check_stmt->close();
    if (isset($update_stmt)) $update_stmt->close();
    if (isset($verify_stmt)) $verify_stmt->close();
    
    // Close database connection
    if (isset($conn)) $conn->close();
}

/**
 * Additional utility functions for values section management
 */

/**
 * Validate section content for common issues
 */
function validateSectionContent($title, $intro) {
    $errors = [];
    
    // Check for minimum content requirements
    if (str_word_count($title) < 2) {
        $errors[] = "Section title should contain at least 2 words.";
    }
    
    if (str_word_count($intro) < 10) {
        $errors[] = "Section introduction should contain at least 10 words.";
    }
    
    // Check for excessive capitalization
    if (strtoupper($title) === $title && strlen($title) > 10) {
        $errors[] = "Section title should not be in all caps.";
    }
    
    // Check for proper sentence structure in intro
    if (!preg_match('/[.!?]$/', trim($intro))) {
        $errors[] = "Section introduction should end with proper punctuation.";
    }
    
    return $errors;
}

/**
 * Log section changes for audit trail
 */
function logSectionChange($section_id, $old_data, $new_data, $admin_id) {
    $changes = [];
    
    if ($old_data['section_title'] !== $new_data['section_title']) {
        $changes[] = "Title: '{$old_data['section_title']}' → '{$new_data['section_title']}'";
    }
    
    if ($old_data['section_intro'] !== $new_data['section_intro']) {
        $changes[] = "Intro: Changed";
    }
    
    if (!empty($changes)) {
        $log_message = "Values section {$section_id} updated by admin {$admin_id}: " . implode(', ', $changes);
        error_log($log_message);
    }
}
?>
