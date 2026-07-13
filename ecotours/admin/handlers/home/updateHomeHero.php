<?php
require_once('../../config/connection.php');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get existing slides to preserve unchanged images
        $existingSlides = [];
        $result = $conn->query("SELECT id, image_url FROM home_hero ORDER BY id ASC");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $existingSlides[$row['id']] = $row['image_url'];
            }
        }

        // Process each slide
        for ($i = 1; $i <= 3; $i++) {
            $title = $_POST['slide'.$i.'-title'] ?? '';
            $description = $_POST['slide'.$i.'-desc'] ?? '';
            
            // Handle file upload
            $imagePath = $existingSlides[$i] ?? '';
            if (isset($_FILES['slide'.$i.'-img']) && $_FILES['slide'.$i.'-img']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../../images/home/hero/';
                $fileName = basename($_FILES['slide'.$i.'-img']['name']);
                $uploadFile = $uploadDir . $fileName;
                
                // Validate image
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $fileType = mime_content_type($_FILES['slide'.$i.'-img']['tmp_name']);
                
                if (in_array($fileType, $allowedTypes) && 
                    move_uploaded_file($_FILES['slide'.$i.'-img']['tmp_name'], $uploadFile)) {
                    $imagePath = '../../images/home/hero/' . $fileName;
                }
            }

            // Update or insert into database
            if (isset($existingSlides[$i])) {
                $stmt = $conn->prepare("UPDATE home_hero SET title = ?, description = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $description, $imagePath, $i);
            } else {
                $stmt = $conn->prepare("INSERT INTO home_hero (id, title, description, image_url) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $i, $title, $description, $imagePath);
            }
            $stmt->execute();
        }

        // Redirect back with success message
        header("Location: ../../pages/home/hero.php?status=success");
        exit();
    } catch (Exception $e) {
        // Redirect back with error message
        header("Location: ../../pages/home/hero.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request, redirect
    header("Location: ../../pages/home/hero.php");
    exit();
}
?>