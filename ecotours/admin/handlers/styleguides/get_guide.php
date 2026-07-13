<?php
header('Content-Type: application/json');
require_once('../../config/database.php');

try {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        throw new Exception('Guide ID is required');
    }
    
    $guideId = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM styleguide_cards WHERE card_id = ?");
    $stmt->execute([$guideId]);
    $guide = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$guide) {
        throw new Exception('Style guide not found');
    }
    
    echo json_encode([
        'success' => true,
        'guide' => $guide
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
