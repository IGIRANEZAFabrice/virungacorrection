<?php
header('Content-Type: application/json');
require_once '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$card_id = filter_input(INPUT_POST, 'card_id', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$intro_text = filter_input(INPUT_POST, 'intro_text', FILTER_SANITIZE_STRING);
$main_content = $_POST['main_content'] ?? null; // Array of paragraphs

if (!$card_id || !$title || !$intro_text || !$main_content) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Update card details
    $cardStmt = $pdo->prepare("UPDATE styleguide_cards SET title = ? WHERE card_id = ?");
    $cardStmt->execute([$title, $card_id]);

    // Update content
    $contentStmt = $pdo->prepare("UPDATE styleguide_content SET intro_text = ?, main_content = ? WHERE card_id = ?");
    $contentStmt->execute([$intro_text, json_encode($main_content), $card_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Style guide updated successfully']);

} catch(PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
