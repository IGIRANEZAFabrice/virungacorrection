<?php
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $uploadDir = '../../images/style-guide/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle image uploads
    $thumbnailImage = handleImageUpload('thumbnailImage', $uploadDir);
    $heroImage = handleImageUpload('heroImage', $uploadDir);

    $db = getDBConnection();
    
    // Start transaction
    $db->beginTransaction();

    // Insert into styleguide_cards
    $stmt = $db->prepare("INSERT INTO styleguide_cards (title, thumbnail_image) VALUES (?, ?)");
    $stmt->execute([$_POST['title'], $thumbnailImage]);
    $cardId = $db->lastInsertId();

    // Insert into styleguide_content
    $stmt = $db->prepare("INSERT INTO styleguide_content (card_id, hero_image, intro_text, main_content) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $cardId,
        $heroImage,
        $_POST['introText'],
        json_encode($_POST['paragraphs'])
    ]);

    $db->commit();
    
    echo json_encode(['success' => true, 'message' => 'Style guide uploaded successfully']);

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function handleImageUpload($fieldName, $uploadDir) {
    if (!isset($_FILES[$fieldName])) {
        throw new Exception("Missing $fieldName file");
    }

    $file = $_FILES[$fieldName];
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new Exception("Failed to upload $fieldName");
    }

    return $fileName;
}
