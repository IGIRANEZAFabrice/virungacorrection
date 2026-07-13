<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (empty($_POST['guide_id']) || empty($_POST['title'])) {
        throw new Exception('Guide ID and title are required');
    }
    
    $guideId = intval($_POST['guide_id']);
    $title = trim($_POST['title']);
    $thumbnailImage = null;
    
    // Check if guide exists
    $stmt = $pdo->prepare("SELECT thumbnail_image FROM styleguide_cards WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $existingGuide = $stmt->fetch();
    
    if (!$existingGuide) {
        throw new Exception('Style guide not found');
    }
    
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
        
        // Delete old thumbnail if it exists
        if ($existingGuide['thumbnail_image'] && file_exists($uploadDir . $existingGuide['thumbnail_image'])) {
            unlink($uploadDir . $existingGuide['thumbnail_image']);
        }
    }
    
    // Update database
    if ($thumbnailImage) {
        $stmt = $pdo->prepare("UPDATE styleguide_cards SET title = ?, thumbnail_image = ? WHERE card_id = ?");
        $result = $stmt->execute([$title, $thumbnailImage, $guideId]);
    } else {
        $stmt = $pdo->prepare("UPDATE styleguide_cards SET title = ? WHERE card_id = ?");
        $result = $stmt->execute([$title, $guideId]);
    }
    
    if (!$result) {
        throw new Exception('Failed to update style guide');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Style guide updated successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
