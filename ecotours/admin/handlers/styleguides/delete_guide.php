<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['id'])) {
        throw new Exception('Guide ID is required');
    }
    
    $guideId = intval($input['id']);
    
    // Get guide info for file cleanup
    $stmt = $pdo->prepare("SELECT thumbnail_image FROM styleguide_cards WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $guide = $stmt->fetch();
    
    if (!$guide) {
        throw new Exception('Style guide not found');
    }
    
    // Get content info for file cleanup
    $stmt = $pdo->prepare("SELECT hero_image FROM styleguide_content WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $content = $stmt->fetch();
    
    $pdo->beginTransaction();
    
    try {
        // Delete content first (foreign key constraint)
        $stmt = $pdo->prepare("DELETE FROM styleguide_content WHERE card_id = ?");
        $stmt->execute([$guideId]);
        
        // Delete the guide
        $stmt = $pdo->prepare("DELETE FROM styleguide_cards WHERE card_id = ?");
        $result = $stmt->execute([$guideId]);
        
        if (!$result) {
            throw new Exception('Failed to delete style guide');
        }
        
        $pdo->commit();
        
        // Clean up files
        $uploadDir = '../../images/style-guide/';
        
        if ($guide['thumbnail_image'] && file_exists($uploadDir . $guide['thumbnail_image'])) {
            unlink($uploadDir . $guide['thumbnail_image']);
        }
        
        if ($content && $content['hero_image'] && file_exists($uploadDir . $content['hero_image'])) {
            unlink($uploadDir . $content['hero_image']);
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Style guide deleted successfully'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
