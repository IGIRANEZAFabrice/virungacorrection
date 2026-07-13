<?php
require_once('../../config/connection.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate required fields
        if (empty($_POST['title']) || empty($_POST['description'])) {
            throw new Exception("Title and description are required");
        }

        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $imagePath = '';

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../images/home/hero/';
            
            // Create directory if it doesn't exist
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $uploadFile = $uploadDir . $fileName;
            
            // Validate image
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);
            $fileSize = $_FILES['image']['size'];
            $maxFileSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception("Invalid file type. Please upload a JPEG, PNG, or GIF image.");
            }
            
            if ($fileSize > $maxFileSize) {
                throw new Exception("File size too large. Maximum size is 2MB.");
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                $imagePath = '../../images/home/hero/' . $fileName;
            } else {
                throw new Exception("Failed to upload image");
            }
        } else {
            throw new Exception("Image is required");
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO home_hero (title, description, image_url) VALUES (?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Database prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $title, $description, $imagePath);
        
        if (!$stmt->execute()) {
            throw new Exception("Database execution failed: " . $stmt->error);
        }
        
        $stmt->close();
        $conn->close();

        // Redirect back with success message
        header("Location: ../../pages/home/hero.php?status=success&message=" . urlencode("New hero slide added successfully!"));
        exit();
        
    } catch (Exception $e) {
        // Clean up uploaded file if database insert failed
        if (!empty($imagePath) && file_exists($uploadFile)) {
            unlink($uploadFile);
        }
        
        // Redirect back with error message
        header("Location: ../../pages/home/hero.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect if not POST request
    header("Location: ../../pages/home/hero.php");
    exit();
}
?>
