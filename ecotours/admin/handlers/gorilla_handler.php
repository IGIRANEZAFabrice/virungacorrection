<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

require_once('../config/connection.php');

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

// Handle gorilla family CRUD operations
if ($action === 'create' || $action === 'update') {
    $family_name = $_POST['family_name'] ?? '';
    $country = $_POST['country'] ?? '';
    $region = $_POST['region'] ?? '';
    $silverback_name = $_POST['silverback_name'] ?? '';
    $family_size = $_POST['family_size'] ?? '';
    $description = $_POST['description'] ?? '';
    $characteristics = $_POST['characteristics'] ?? '';
    $history = $_POST['history'] ?? '';
    $special_features = $_POST['special_features'] ?? '';
    $image_url = $_POST['image_url'] ?? '';
    $is_habituated = isset($_POST['is_habituated']) ? 1 : 0;
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $sort_order = intval($_POST['sort_order'] ?? 0);

    if ($action === 'create') {
        $query = "INSERT INTO gorilla_families (family_name, country, region, silverback_name, family_size, description, characteristics, history, special_features, image_url, is_habituated, is_active, sort_order) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssssii", $family_name, $country, $region, $silverback_name, $family_size, $description, $characteristics, $history, $special_features, $image_url, $is_habituated, $is_active, $sort_order);
    } else {
        $family_id = intval($_POST['family_id']);
        $query = "UPDATE gorilla_families SET family_name=?, country=?, region=?, silverback_name=?, family_size=?, description=?, characteristics=?, history=?, special_features=?, image_url=?, is_habituated=?, is_active=?, sort_order=? WHERE family_id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssssssssiii", $family_name, $country, $region, $silverback_name, $family_size, $description, $characteristics, $history, $special_features, $image_url, $is_habituated, $is_active, $sort_order, $family_id);
    }

    if ($stmt->execute()) {
        header("Location: ../pages/gorilla/index.php?status=success&message=" . urlencode($action === 'create' ? 'Gorilla family created successfully' : 'Gorilla family updated successfully'));
    } else {
        header("Location: ../pages/gorilla/index.php?status=error&message=" . urlencode('Error: ' . $stmt->error));
    }
    $stmt->close();
}

// Handle gorilla family deletion
elseif ($action === 'delete') {
    $family_id = intval($_GET['id']);
    $query = "DELETE FROM gorilla_families WHERE family_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $family_id);
    
    if ($stmt->execute()) {
        header("Location: ../pages/gorilla/index.php?status=success&message=" . urlencode('Gorilla family deleted successfully'));
    } else {
        header("Location: ../pages/gorilla/index.php?status=error&message=" . urlencode('Error deleting gorilla family'));
    }
    $stmt->close();
}

$conn->close();
?>

