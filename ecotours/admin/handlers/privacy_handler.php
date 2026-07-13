<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'get_request':
            handleGetRequest();
            break;
            
        case 'export':
            handleExport();
            break;
            
        case 'create_tables':
            handleCreateTables();
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function handleGetRequest() {
    global $pdo;
    
    $requestId = $_GET['id'] ?? 0;
    
    if (!$requestId) {
        echo json_encode(['success' => false, 'message' => 'Request ID is required']);
        return;
    }
    
    $stmt = $pdo->prepare("SELECT * FROM privacy_requests WHERE id = ?");
    $stmt->execute([$requestId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$request) {
        echo json_encode(['success' => false, 'message' => 'Request not found']);
        return;
    }
    
    echo json_encode(['success' => true, 'request' => $request]);
}

function handleExport() {
    global $pdo;
    
    $status = $_GET['status'] ?? '';
    
    // Build query based on status filter
    $query = "SELECT * FROM privacy_requests";
    $params = [];
    
    if ($status) {
        $query .= " WHERE status = ?";
        $params[] = $status;
    }
    
    $query .= " ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="privacy_requests_' . date('Y-m-d') . '.csv"');
    
    // Create CSV output
    $output = fopen('php://output', 'w');
    
    // Add CSV headers
    fputcsv($output, [
        'ID',
        'Request Type',
        'Email',
        'Subject',
        'Message',
        'Status',
        'Admin Response',
        'Created At',
        'Updated At'
    ]);
    
    // Add data rows
    foreach ($requests as $request) {
        fputcsv($output, [
            $request['id'],
            $request['request_type'],
            $request['email'],
            $request['subject'],
            $request['message'],
            $request['status'],
            $request['admin_response'],
            $request['created_at'],
            $request['updated_at']
        ]);
    }
    
    fclose($output);
}

function handleCreateTables() {
    global $pdo;
    
    try {
        // Create privacy_policy table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS privacy_policy (
                id INT PRIMARY KEY AUTO_INCREMENT,
                content LONGTEXT NOT NULL,
                last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        // Create privacy_requests table
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS privacy_requests (
                id INT PRIMARY KEY AUTO_INCREMENT,
                request_type ENUM('data_access', 'data_deletion', 'data_portability', 'data_correction') NOT NULL,
                email VARCHAR(255) NOT NULL,
                subject VARCHAR(500) NOT NULL,
                message TEXT NOT NULL,
                status ENUM('pending', 'in_progress', 'completed', 'rejected') DEFAULT 'pending',
                admin_response TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_status (status),
                INDEX idx_request_type (request_type),
                INDEX idx_created_at (created_at)
            )
        ");
        
        // Insert default privacy policy if none exists
        $stmt = $pdo->query("SELECT COUNT(*) FROM privacy_policy");
        if ($stmt->fetchColumn() == 0) {
            $defaultPolicy = "
# Privacy Policy

## Introduction
At Virunga Ecotours, we are committed to protecting your personal information and being transparent about how we use it.

## Information We Collect
We collect information you provide directly to us, such as when you create an account, make a booking, or contact us.

## How We Use Your Information
We use the information we collect to provide, maintain, and improve our services.

## Information Sharing
We do not sell, trade, or otherwise transfer your personal information to third parties without your consent.

## Data Security
We implement appropriate security measures to protect your personal information.

## Your Rights
You have the right to access, update, or delete your personal information.

## Contact Us
If you have any questions about this Privacy Policy, please contact us at privacy@virungaecotours.com.
            ";
            
            $stmt = $pdo->prepare("INSERT INTO privacy_policy (content) VALUES (?)");
            $stmt->execute([$defaultPolicy]);
        }
        
        // Insert sample privacy requests for demonstration
        $stmt = $pdo->query("SELECT COUNT(*) FROM privacy_requests");
        if ($stmt->fetchColumn() == 0) {
            $sampleRequests = [
                [
                    'data_access',
                    'john.doe@example.com',
                    'Request for Personal Data Access',
                    'I would like to request access to all personal data you have collected about me.',
                    'pending'
                ],
                [
                    'data_deletion',
                    'jane.smith@example.com',
                    'Request for Data Deletion',
                    'Please delete all my personal information from your systems.',
                    'in_progress'
                ],
                [
                    'data_correction',
                    'bob.wilson@example.com',
                    'Correction of Personal Information',
                    'I need to update my contact information in your records.',
                    'completed'
                ]
            ];
            
            $stmt = $pdo->prepare("
                INSERT INTO privacy_requests (request_type, email, subject, message, status) 
                VALUES (?, ?, ?, ?, ?)
            ");
            
            foreach ($sampleRequests as $request) {
                $stmt->execute($request);
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Tables created successfully']);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Function to handle privacy request submissions from the frontend
function handlePrivacyRequestSubmission() {
    global $pdo;
    
    $requestType = $_POST['request_type'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate input
    if (empty($requestType) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
        return;
    }
    
    $validTypes = ['data_access', 'data_deletion', 'data_portability', 'data_correction'];
    if (!in_array($requestType, $validTypes)) {
        echo json_encode(['success' => false, 'message' => 'Invalid request type']);
        return;
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO privacy_requests (request_type, email, subject, message) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$requestType, $email, $subject, $message]);
        
        // Send confirmation email (you can implement this)
        // sendPrivacyRequestConfirmation($email, $requestType);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Privacy request submitted successfully. We will respond within 30 days.',
            'request_id' => $pdo->lastInsertId()
        ]);
        
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Function to send confirmation email (placeholder)
function sendPrivacyRequestConfirmation($email, $requestType) {
    // Implement email sending logic here
    // You can use PHPMailer or similar library
    
    $subject = "Privacy Request Confirmation - Virunga Ecotours";
    $message = "
        Dear Customer,
        
        We have received your privacy request for: " . str_replace('_', ' ', $requestType) . "
        
        We will process your request and respond within 30 days as required by applicable privacy laws.
        
        If you have any questions, please contact us at privacy@virungaecotours.com
        
        Best regards,
        Virunga Ecotours Privacy Team
    ";
    
    // mail($email, $subject, $message);
}
?>
