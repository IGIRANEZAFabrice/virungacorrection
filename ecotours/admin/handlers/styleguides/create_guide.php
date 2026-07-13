<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (empty($_POST['title'])) {
        throw new Exception('Title is required');
    }
    
    $title = trim($_POST['title']);
    $thumbnailImage = null;
    
    // Handle file upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/style-guide/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP files are allowed.');
        }
        
        // Generate unique filename
        $thumbnailImage = uniqid('thumb_') . '.' . $fileExtension;
        $uploadPath = $uploadDir . $thumbnailImage;
        
        if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload thumbnail image');
        }
    }
    
    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO styleguide_cards (title, thumbnail_image, created_at) VALUES (?, ?, NOW())");
    $result = $stmt->execute([$title, $thumbnailImage]);
    
    if (!$result) {
        throw new Exception('Failed to create style guide');
    }
    
    $cardId = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'message' => 'Style guide created successfully',
        'card_id' => $cardId
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
