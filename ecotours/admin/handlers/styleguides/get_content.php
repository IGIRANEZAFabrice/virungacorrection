<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('Guide ID is required');
    }
    
    $guideId = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM styleguide_content WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $content = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$content) {
        // No content exists yet, return empty structure
        echo json_encode([
            'success' => true,
            'content' => [
                'card_id' => $guideId,
                'hero_image' => null,
                'intro_text' => '',
                'main_content' => null
            ]
        ]);
    } else {
        // Decode main_content if it's JSON
        if ($content['main_content']) {
            $content['main_content'] = json_decode($content['main_content'], true);
        }
        
        echo json_encode([
            'success' => true,
            'content' => $content
        ]);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
