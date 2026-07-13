<?php
// Ensure this is at the very top of the file, before any output or whitespace
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../admin/config/connection.php';

// Ensure we're sending JSON response
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "INSERT INTO subscribers (email) VALUES (?) ON DUPLICATE KEY UPDATE created_at = CURRENT_TIMESTAMP";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("s", $email);
            
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Thank you for subscribing!'];
            } else if ($conn->errno == 1062) { // Duplicate entry
                $stmt->close();
                // Try to update the timestamp for existing email
                $updateStmt = $conn->prepare("UPDATE subscribers SET created_at = CURRENT_TIMESTAMP WHERE email = ?");
                if ($updateStmt) {
                    $updateStmt->bind_param("s", $email);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                $response = ['success' => true, 'message' => 'Thank you for your continued interest!'];
            } else {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            if (isset($stmt)) {
                $stmt->close();
            }
        }
        else {
            $response = ['success' => false, 'message' => 'Please enter a valid email address.'];
        }
    } catch (Exception $e) {
        $timestamp = date('Y-m-d H:i:s');
        error_log("[{$timestamp}] Subscription error: " . $e->getMessage() . 
                  " | IP: " . $_SERVER['REMOTE_ADDR'] . 
                  " | Email: " . $email);
        $response = [
            'success' => false, 
            'message' => 'An error occurred. Please contact support if the problem persists.'
        ];
    }
    
    // Clean any output buffered content
    ob_clean();
    
    // Send the JSON response
    echo json_encode($response);
    exit;
}