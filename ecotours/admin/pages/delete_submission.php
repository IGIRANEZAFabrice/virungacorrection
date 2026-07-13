<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config/db_connect.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM build_submissions WHERE id = ?");
        $result = $stmt->execute([$id]);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid ID']);
}
