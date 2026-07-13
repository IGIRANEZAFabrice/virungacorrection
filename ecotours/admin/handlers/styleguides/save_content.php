<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    if (empty($_POST['guide_id'])) {
        throw new Exception('Guide ID is required');
    }
    
    $guideId = intval($_POST['guide_id']);
    $introText = trim($_POST['intro_text'] ?? '');
    $mainContent = $_POST['main_content'] ?? '';
    $heroImage = null;
    
    // Verify guide exists
    $stmt = $pdo->prepare("SELECT card_id FROM styleguide_cards WHERE card_id = ?");
    $stmt->execute([$guideId]);
    if (!$stmt->fetch()) {
        throw new Exception('Style guide not found');
    }
    
    // Handle hero image upload
    if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../images/style-guide/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileExtension = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP files are allowed.');
        }
        
        // Generate unique filename
        $heroImage = uniqid('hero_') . '.' . $fileExtension;
        $uploadPath = $uploadDir . $heroImage;
        
        if (!move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadPath)) {
            throw new Exception('Failed to upload hero image');
        }
    }
    
    // Check if content already exists
    $stmt = $pdo->prepare("SELECT content_id, hero_image FROM styleguide_content WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $existingContent = $stmt->fetch();
    
    if ($existingContent) {
        // Update existing content
        if ($heroImage) {
            // Delete old hero image if it exists
            if ($existingContent['hero_image'] && file_exists($uploadDir . $existingContent['hero_image'])) {
                unlink($uploadDir . $existingContent['hero_image']);
            }
            
            $stmt = $pdo->prepare("UPDATE styleguide_content SET hero_image = ?, intro_text = ?, main_content = ?, updated_at = NOW() WHERE card_id = ?");
            $result = $stmt->execute([$heroImage, $introText, $mainContent, $guideId]);
        } else {
            $stmt = $pdo->prepare("UPDATE styleguide_content SET intro_text = ?, main_content = ?, updated_at = NOW() WHERE card_id = ?");
            $result = $stmt->execute([$introText, $mainContent, $guideId]);
        }
    } else {
        // Insert new content
        $stmt = $pdo->prepare("INSERT INTO styleguide_content (card_id, hero_image, intro_text, main_content, updated_at) VALUES (?, ?, ?, ?, NOW())");
        $result = $stmt->execute([$guideId, $heroImage, $introText, $mainContent]);
    }
    
    if (!$result) {
        throw new Exception('Failed to save content');
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Content saved successfully'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
