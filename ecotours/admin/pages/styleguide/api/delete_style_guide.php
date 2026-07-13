<?php
header('Content-Type: application/json');
require_once '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$cardId = filter_input(INPUT_POST, 'card_id', FILTER_VALIDATE_INT);

if (!$cardId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid card ID']);
    exit;
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Delete related content first
    $stmt = $pdo->prepare("DELETE FROM styleguide_content WHERE card_id = ?");
    $stmt->execute([$cardId]);

    // Then delete the card
    $stmt = $pdo->prepare("DELETE FROM styleguide_cards WHERE card_id = ?");
    $stmt->execute([$cardId]);

    // Commit transaction
    $pdo->commit();

    echo json_encode(['success' => true, 'message' => 'Style guide deleted successfully']);
} catch(PDOException $e) {
    // Rollback in case of error
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
