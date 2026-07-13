<?php
require_once '../../config/connection.php';

// Check if user is logged in
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../login.php');
    exit();
}

// Function to sanitize input
function sanitize($conn, $input) {
    return htmlspecialchars(trim($input));
}

// Function to handle file uploads
function handleImageUpload($file, $target_dir) {
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    
    // Generate unique filename
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check file type
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($file_extension, $allowed_types)) {
        return [
            'success' => false,
            'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.'
        ];
    }
    
    // Check file size (2MB max)
    if ($file['size'] > 2000000) {
        return [
            'success' => false,
            'message' => 'File is too large. Maximum size is 2MB.'
        ];
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return [
            'success' => true,
            'file_path' => $target_file
        ];
    } else {
        return [
            'success' => false,
            'message' => 'Failed to upload file.'
        ];
    }
}

// Get attraction ID
$attraction_id = isset($_POST['attraction_id']) ? intval($_POST['attraction_id']) : 0;

if ($attraction_id <= 0) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'Invalid attraction ID.'
    ];
    header('Location: ../../pages/home/attractions.php');
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Save attraction details
    if (isset($_POST['save_details'])) {
        $description = $_POST['description'] ?? '';
        $location = sanitize($conn, $_POST['location'] ?? '');
        $activities = sanitize($conn, $_POST['activities'] ?? '');
        $external_link = sanitize($conn, $_POST['external_link'] ?? '');
        
        // Check if details already exist
        $check_query = "SELECT id FROM attraction_details WHERE attraction_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $attraction_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            // Update existing record
            $update_query = "UPDATE attraction_details SET 
                            description = ?, 
                            location = ?, 
                            activities = ?, 
                            external_link = ?, 
                            updated_at = CURRENT_TIMESTAMP 
                            WHERE attraction_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $description, $location, $activities, $external_link, $attraction_id);
        } else {
            // Insert new record
            $insert_query = "INSERT INTO attraction_details 
                            (attraction_id, description, location, activities, external_link) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("issss", $attraction_id, $description, $location, $activities, $external_link);
        }
        
        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Attraction details saved successfully.'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Error saving attraction details: ' . $conn->error
            ];
        }
        
        header('Location: ../../pages/home/attraction_details.php?id=' . $attraction_id);
        exit();
    }
    
    // Upload gallery image
    if (isset($_POST['upload_image'])) {
        if (isset($_FILES['gallery_image']) && $_FILES['gallery_image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "../../uploads/attractions/gallery/";
            $upload_result = handleImageUpload($_FILES['gallery_image'], $target_dir);
            
            if ($upload_result['success']) {
                $image_url = $upload_result['file_path'];
                $caption = sanitize($conn, $_POST['image_caption'] ?? '');
                $display_order = intval($_POST['display_order'] ?? 1);
                
                $insert_query = "INSERT INTO attraction_gallery 
                                (attraction_id, image_url, caption, display_order) 
                                VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("issi", $attraction_id, $image_url, $caption, $display_order);
                
                if ($stmt->execute()) {
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => 'Image uploaded successfully.'
                    ];
                } else {
                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Error uploading image: ' . $conn->error
                    ];
                }
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => $upload_result['message']
                ];
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Please select an image to upload.'
            ];
        }
        
        header('Location: ../../pages/home/attraction_details.php?id=' . $attraction_id . '&tab=gallery');
        exit();
    }
    
    // Delete gallery image
    if (isset($_POST['delete_image'])) {
        $image_id = intval($_POST['image_id'] ?? 0);
        
        if ($image_id > 0) {
            // Get image path first
            $select_query = "SELECT image_url FROM attraction_gallery WHERE id = ? AND attraction_id = ?";
            $select_stmt = $conn->prepare($select_query);
            $select_stmt->bind_param("ii", $image_id, $attraction_id);
            $select_stmt->execute();
            $result = $select_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $image = $result->fetch_assoc();
                $image_path = $image['image_url'];
                
                // Delete from database
                $delete_query = "DELETE FROM attraction_gallery WHERE id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param("i", $image_id);
                
                if ($delete_stmt->execute()) {
                    // Delete file if it exists
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                    
                    $_SESSION['message'] = [
                        'type' => 'success',
                        'text' => 'Image deleted successfully.'
                    ];
                } else {
                    $_SESSION['message'] = [
                        'type' => 'error',
                        'text' => 'Error deleting image: ' . $conn->error
                    ];
                }
            } else {
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => 'Image not found.'
                ];
            }
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Invalid image ID.'
            ];
        }
        
        header('Location: ../../pages/home/attraction_details.php?id=' . $attraction_id . '&tab=gallery');
        exit();
    }
}

// If no action was taken, redirect back
header('Location: ../../pages/home/attraction_details.php?id=' . $attraction_id);
exit();
?>