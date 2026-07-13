<?php
session_start();

if (!isset($_SESSION['community_admin_id'])) {
    header("Location: ../index.php");
    exit();
}

require_once '../../../admin/config/connection.php';

$success = true;
$message = '';

try {
    // Get all slides from the database
    $query = "SELECT id FROM community_hero ORDER BY id ASC";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $slide_count = 1;
        while ($row = $result->fetch_assoc()) {
            $slide_id = $row['id'];
            
            // Get form data
            $title = isset($_POST["slide{$slide_count}-title"]) ? $_POST["slide{$slide_count}-title"] : '';
            $description = isset($_POST["slide{$slide_count}-desc"]) ? $_POST["slide{$slide_count}-desc"] : '';
            
            // Handle file upload
            $image_url = null;
            if (isset($_FILES["slide{$slide_count}-img"]) && $_FILES["slide{$slide_count}-img"]['size'] > 0) {
                $file = $_FILES["slide{$slide_count}-img"];
                $upload_dir = '../../../community/hero/';
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                // Generate unique filename
                $filename = time() . '_' . basename($file['name']);
                $filepath = $upload_dir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $image_url = 'community/hero/' . $filename;
                } else {
                    throw new Exception("Failed to upload image for slide {$slide_count}");
                }
            }
            
            // Update database
            if ($image_url) {
                $update_query = "UPDATE community_hero SET title = ?, description = ?, image_url = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssi", $title, $description, $image_url, $slide_id);
            } else {
                $update_query = "UPDATE community_hero SET title = ?, description = ? WHERE id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssi", $title, $description, $slide_id);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update slide {$slide_count}: " . $stmt->error);
            }
            
            $slide_count++;
        }
    }
    
    header("Location: ../hero.php?status=success");
    exit();
    
} catch (Exception $e) {
    header("Location: ../hero.php?status=error&message=" . urlencode($e->getMessage()));
    exit();
}
?>

