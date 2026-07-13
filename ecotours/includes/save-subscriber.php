<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../admin/config/connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $response = ['success' => false, 'message' => ''];
    
    try {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $sql = "INSERT INTO subscribers (email) VALUES (?)";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("s", $email);
            
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Thank you for subscribing!'];
            } else {
                if ($conn->errno == 1062) { // Duplicate entry
                    $response = ['success' => true, 'message' => 'You are already subscribed!'];
                } else {
                    throw new Exception("Execute failed: " . $stmt->error);
                }
            }
            $stmt->close();
        } else {
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
    
    echo json_encode($response);
    exit;
}
