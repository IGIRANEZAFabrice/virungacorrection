<?php
header('Content-Type: application/json');
require_once('../config/connection.php');

$guide_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$guide_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid guide ID']);
    exit;
}

try {
    // Get both card and content details
    $query = "SELECT c.title, c.thumbnail_image, sc.hero_image, sc.intro_text, sc.main_content 
              FROM styleguide_cards c 
              LEFT JOIN styleguide_content sc ON c.card_id = sc.card_id 
              WHERE c.card_id = ?";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute([$guide_id]);
    $guide = $stmt->fetch();

    if ($guide) {
        // Transform image paths
        $guide['hero_image'] = '/ecotours/admin/images/style-guide/' . $guide['hero_image'];
        $guide['thumbnail_image'] = '/ecotours/admin/images/style-guide/' . $guide['thumbnail_image'];
        echo json_encode(['success' => true, 'data' => $guide]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Guide not found']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}