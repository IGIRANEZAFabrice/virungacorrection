<?php
// Prevent any output before JSON
ob_start();

header('Content-Type: application/json');
error_reporting(0);

require_once '../config/connection.php';

try {
    // Clear any previous output
    ob_clean();
    
    $query = "SELECT card_id, title, thumbnail_image, created_at FROM styleguide_cards ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $styleGuides = $stmt->fetchAll();

    // Transform thumbnail_image paths
    foreach ($styleGuides as &$guide) {
        if ($guide['thumbnail_image']) {
            $guide['thumbnail_image'] = '/ecotours/admin/images/style-guide/' . $guide['thumbnail_image'];
        } else {
            $guide['thumbnail_image'] = '/ecotours/admin/images/style-guide/default-thumbnail.jpg';
        }
    }

    echo json_encode(['success' => true, 'data' => $styleGuides]);
} catch(PDOException $e) {
    // Clear any output
    ob_clean();
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Failed to fetch style guides'
    ]);
}
?>
