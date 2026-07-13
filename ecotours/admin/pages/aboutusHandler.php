<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
header('Content-Type: application/json');

require_once '../config/connection.php';

// Helper function for time ago
function time_elapsed_string($datetime) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  if ($diff->d == 0) {
    if ($diff->h == 0) {
      if ($diff->i == 0) {
        return "just now";
      } else {
        return $diff->i . " minute" . ($diff->i > 1 ? "s" : "") . " ago";
      }
    } else {
      return $diff->h . " hour" . ($diff->h > 1 ? "s" : "") . " ago";
    }
  } else if ($diff->d <= 7) {
    return $diff->d . " day" . ($diff->d > 1 ? "s" : "") . " ago";
  } else if ($diff->d <= 30) {
    $weeks = floor($diff->d / 7);
    return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
  } else {
    return $ago->format('M j, Y');
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  // Fetch all sections
  $query = "SELECT * FROM about_sections ORDER BY display_order";
  $result = $conn->query($query);
  $sections = [];
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $row['last_updated_human'] = time_elapsed_string($row['last_updated']);
      $sections[] = $row;
    }
  }
  echo json_encode(['success' => true, 'sections' => $sections]);
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $response = ['success' => false, 'message' => 'Unknown error'];

  switch ($action) {
    case 'edit_section':
      $section_id = $_POST['section_id'] ?? 0;
      $section_title = $_POST['section_title'] ?? '';
      $content = $_POST['section_content'] ?? '';
      $image_path = $_POST['current_image_path'] ?? '';

      $section_title = htmlspecialchars(trim($section_title));
      $content = trim($content);

      // Handle image upload
      if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] == 0) {
        $upload_dir = '../../images/about/';
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        // Delete old image if exists
        if (!empty($image_path) && file_exists('../../' . ltrim($image_path, '/'))) {
          unlink('../../' . ltrim($image_path, '/'));
        }
        $file_name = time() . '_' . basename($_FILES['section_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['section_image']['tmp_name'], $target_file)) {
          $image_path = '/images/about/' . $file_name;
        }
      }

      if (empty($section_id) || empty($section_title)) {
        $response['message'] = "Missing required fields";
        echo json_encode($response); exit();
      }

      $stmt = $conn->prepare("UPDATE about_sections SET section_title = ?, content = ?, image_path = ?, is_updated = TRUE, last_updated = NOW() WHERE id = ?");
      $stmt->bind_param("sssi", $section_title, $content, $image_path, $section_id);

      if ($stmt->execute()) {
        $response = ['success' => true, 'message' => "Section updated successfully!"];
      } else {
        $response['message'] = "Error updating section: " . $conn->error;
      }
      $stmt->close();
      break;

    case 'add_section':
      $section_name = strtolower(str_replace(' ', '-', $_POST['section_name'] ?? ''));
      $section_title = $_POST['section_title'] ?? '';
      $content = $_POST['section_content'] ?? '';
      $icon_class = $_POST['icon_class'] ?? 'fas fa-paragraph';
      $image_path = '';

      if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] == 0) {
        $upload_dir = '../../images/about/';
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES['section_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (move_uploaded_file($_FILES['section_image']['tmp_name'], $target_file)) {
          $image_path = '/images/about/' . $file_name;
        }
      }

      $result = $conn->query("SELECT MAX(display_order) as max_order FROM about_sections");
      $row = $result->fetch_assoc();
      $display_order = ($row['max_order'] ?? 0) + 1;

      $stmt = $conn->prepare("INSERT INTO about_sections (section_name, section_title, content, image_path, icon_class, display_order, is_new) VALUES (?, ?, ?, ?, ?, ?, TRUE)");
      $stmt->bind_param("sssssi", $section_name, $section_title, $content, $image_path, $icon_class, $display_order);

      if ($stmt->execute()) {
        $response = ['success' => true, 'message' => "New section added successfully!"];
      } else {
        $response['message'] = "Error adding section: " . $conn->error;
      }
      $stmt->close();
      break;

    case 'delete_section':
      $section_id = $_POST['section_id'] ?? 0;
      $stmt = $conn->prepare("DELETE FROM about_sections WHERE id = ?");
      $stmt->bind_param("i", $section_id);

      if ($stmt->execute()) {
        $response = ['success' => true, 'message' => "Section deleted successfully!"];
      } else {
        $response['message'] = "Error deleting section: " . $conn->error;
      }
      $stmt->close();
      break;
  }

  echo json_encode($response);
  exit();
}
