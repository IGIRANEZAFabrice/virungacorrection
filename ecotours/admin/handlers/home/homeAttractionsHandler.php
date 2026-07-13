<?php
require_once('../../config/connection.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get existing slides to preserve unchanged images
        $existingSlides = [];
        $result = $conn->query("SELECT id, image_url FROM home_attractions ORDER BY id ASC");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $existingSlides[$row['id']] = $row['image_url'];
            }
        }

        // Process each slide
        for ($i = 1; $i <= 8; $i++) {
            // Get title from the form
            $title = $_POST['slide'.$i.'-title'] ?? '';
            
            // Handle file upload with the correct index
            $imagePath = $existingSlides[$i] ?? '';
            if (isset($_FILES['slide'.$i.'-img']) && $_FILES['slide'.$i.'-img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../images/home/attractions/';
                $fileName = basename($_FILES['slide'.$i.'-img']['name']);
                $uploadFile = $uploadDir . $fileName;
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Validate image
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($_FILES['slide'.$i.'-img']['tmp_name']);
                
                if (in_array($fileType, $allowedTypes) && 
                    move_uploaded_file($_FILES['slide'.$i.'-img']['tmp_name'], $uploadFile)) {
                    // Use a consistent path format
                    $imagePath = '../../images/home/attractions/' . $fileName;
                }
            }

            // Update or insert into database
            if (isset($existingSlides[$i])) {
                $stmt = $conn->prepare("UPDATE home_attractions SET title = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("ssi", $title, $imagePath, $i);
            } else {
                $stmt = $conn->prepare("INSERT INTO home_attractions (id, title, image_url) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $i, $title, $imagePath);
            }
            $stmt->execute();
            $stmt->close();
        }

        // Redirect back with success message
        header("Location: ../../pages/home/attractions.php?status=success");
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Error updating under hero cards: " . $e->getMessage());
        
        // Redirect back with error message
        header("Location: ../../pages/home/attractions.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request, redirect
    header("Location: ../../pages/home/attractions.php");
    exit();
}
?>