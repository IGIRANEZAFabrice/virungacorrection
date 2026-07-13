<?php
header('Content-Type: application/json');
require_once '../config/connection.php';

$card_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$card_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid style guide ID']);
    exit;
}

try {
    // Get card details
    $cardQuery = "SELECT * FROM styleguide_cards WHERE card_id = ?";
    $cardStmt = $pdo->prepare($cardQuery);
    $cardStmt->execute([$card_id]);
    $card = $cardStmt->fetch();

    if (!$card) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Style guide not found']);
        exit;
    }

    // Get content details
    $contentQuery = "SELECT * FROM styleguide_content WHERE card_id = ?";
    $contentStmt = $pdo->prepare($contentQuery);
    $contentStmt->execute([$card_id]);
    $content = $contentStmt->fetch();

    // Transform image paths
    $card['thumbnail_image'] = '/ecotours/admin/images/style-guide/' . $card['thumbnail_image'];
    if ($content) {
        $content['hero_image'] = '/ecotours/admin/images/style-guide/' . $content['hero_image'];
        
        // Parse main_content from JSON if it exists
        if ($content['main_content']) {
            $content['main_content'] = json_decode($content['main_content'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // If JSON parsing fails, try to extract paragraphs from HTML
                preg_match_all('/<p>(.*?)<\/p>/s', $content['main_content'], $matches);
                $content['main_content'] = $matches[1] ?? [];
            }
        } else {
            $content['main_content'] = [];
        }
    }

    echo json_encode([
        'success' => true,
        'data' => [
            'card' => $card,
            'content' => $content
        ]
    ]);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
