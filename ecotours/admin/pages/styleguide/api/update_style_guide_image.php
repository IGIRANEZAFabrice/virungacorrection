<?php
header('Content-Type: application/json');
require_once '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$card_id = filter_input(INPUT_POST, 'card_id', FILTER_VALIDATE_INT);
$image_type = filter_input(INPUT_POST, 'image_type', FILTER_SANITIZE_STRING);

if (!$card_id || !$image_type || !isset($_FILES['image'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $file = $_FILES['image'];
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . '/ecotours/admin/images/style-guide/' . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception('Failed to upload image');
    }

    $pdo->beginTransaction();

    if ($image_type === 'thumbnail') {
        $stmt = $pdo->prepare("UPDATE styleguide_cards SET thumbnail_image = ? WHERE card_id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE styleguide_content SET hero_image = ? WHERE card_id = ?");
    }

    $stmt->execute([$fileName, $card_id]);
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Image updated successfully',
        'data' => ['image_path' => '/ecotours/admin/images/style-guide/' . $fileName]
    ]);

} catch(Exception $e) {
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update image']);
}
