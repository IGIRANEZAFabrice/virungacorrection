<?php
// Privacy Request Handler for Frontend Submissions
// This file handles privacy requests submitted from the public-facing form

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (!$data) {
        throw new Exception('Invalid JSON data received');
    }
    
    $action = $data['action'] ?? '';
    
    if ($action === 'submit_request') {
        handlePrivacyRequestSubmission($data);
    } else {
        throw new Exception('Invalid action specified');
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function handlePrivacyRequestSubmission($data) {
    global $pdo;
    
    // Extract and validate data
    $requestType = trim($data['request_type'] ?? '');
    $email = trim($data['email'] ?? '');
    $subject = trim($data['subject'] ?? '');
    $message = trim($data['message'] ?? '');
    $identityVerification = $data['identity_verification'] ?? '0';
    $privacyPolicyAgreement = $data['privacy_policy_agreement'] ?? '0';
    
    // Validation
    $errors = [];
    
    if (empty($requestType)) {
        $errors[] = 'Request type is required';
    } elseif (!in_array($requestType, ['data_access', 'data_deletion', 'data_correction', 'data_portability'])) {
        $errors[] = 'Invalid request type';
    }
    
    if (empty($email)) {
        $errors[] = 'Email address is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address format';
    }
    
    if (empty($subject)) {
        $errors[] = 'Subject is required';
    } elseif (strlen($subject) > 500) {
        $errors[] = 'Subject must be 500 characters or less';
    }
    
    if (empty($message)) {
        $errors[] = 'Message is required';
    } elseif (strlen($message) > 5000) {
        $errors[] = 'Message must be 5000 characters or less';
    }
    
    if ($identityVerification !== '1') {
        $errors[] = 'Identity verification confirmation is required';
    }
    
    if ($privacyPolicyAgreement !== '1') {
        $errors[] = 'Privacy policy agreement is required';
    }
    
    // Check for rate limiting (prevent spam)
    $rateLimitCheck = checkRateLimit($email);
    if (!$rateLimitCheck['allowed']) {
        $errors[] = $rateLimitCheck['message'];
    }
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode('; ', $errors),
            'errors' => $errors
        ]);
        return;
    }
    
    try {
        // Check if tables exist, create them if they don't
        ensureTablesExist();
        
        // Insert the privacy request
        $stmt = $pdo->prepare("
            INSERT INTO privacy_requests (request_type, email, subject, message, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([$requestType, $email, $subject, $message]);
        $requestId = $pdo->lastInsertId();
        
        // Log the submission
        logPrivacyRequest($requestId, $requestType, $email);
        
        // Send confirmation email (if email functionality is available)
        $emailSent = sendConfirmationEmail($email, $requestType, $requestId);
        
        // Send notification to admin
        sendAdminNotification($requestType, $email, $requestId);
        
        echo json_encode([
            'success' => true,
            'message' => 'Your privacy request has been submitted successfully. We will respond within 30 days.',
            'request_id' => $requestId,
            'email_sent' => $emailSent
        ]);
        
    } catch (PDOException $e) {
        error_log("Privacy request database error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'A database error occurred. Please try again later.'
        ]);
    }
}

function checkRateLimit($email) {
    global $pdo;
    
    try {
        // Check if user has submitted more than 5 requests in the last 24 hours
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM privacy_requests 
            WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)
        ");
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        
        if ($count >= 5) {
            return [
                'allowed' => false,
                'message' => 'You have reached the maximum number of requests allowed per day. Please try again tomorrow.'
            ];
        }
        
        // Check if user has submitted a request in the last hour
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM privacy_requests 
            WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        $stmt->execute([$email]);
        $recentCount = $stmt->fetchColumn();
        
        if ($recentCount >= 1) {
            return [
                'allowed' => false,
                'message' => 'Please wait at least one hour between privacy requests.'
            ];
        }
        
        return ['allowed' => true];
        
    } catch (PDOException $e) {
        // If we can't check rate limit, allow the request but log the error
        error_log("Rate limit check error: " . $e->getMessage());
        return ['allowed' => true];
    }
}

function ensureTablesExist() {
    global $pdo;
    
    // Check if privacy_requests table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_requests'");
    if ($stmt->rowCount() === 0) {
        // Create the table
        $pdo->exec("
            CREATE TABLE privacy_requests (
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
    }
}

function logPrivacyRequest($requestId, $requestType, $email) {
    // Log to file for audit purposes
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'request_id' => $requestId,
        'request_type' => $requestType,
        'email' => $email,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    $logFile = '../logs/privacy_requests.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}

function sendConfirmationEmail($email, $requestType, $requestId) {
    // This is a placeholder for email functionality
    // You would implement actual email sending here using PHPMailer or similar
    
    $subject = "Privacy Request Confirmation - Virunga Ecotours";
    $requestTypeFormatted = ucfirst(str_replace('_', ' ', $requestType));
    
    $message = "
Dear Customer,

Thank you for submitting your privacy request to Virunga Ecotours.

Request Details:
- Request ID: {$requestId}
- Request Type: {$requestTypeFormatted}
- Submitted: " . date('F j, Y g:i A') . "

We have received your request and will process it in accordance with applicable privacy laws. You can expect a response within 30 days.

If you have any questions about your request, please contact our Data Protection Officer at privacy@virungaecotours.com and reference your Request ID: {$requestId}.

Best regards,
Virunga Ecotours Privacy Team

---
This is an automated message. Please do not reply to this email.
    ";
    
    // Uncomment and configure the following lines if you have email functionality set up:
    /*
    $headers = "From: privacy@virungaecotours.com\r\n";
    $headers .= "Reply-To: privacy@virungaecotours.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    return mail($email, $subject, $message, $headers);
    */
    
    // For now, just return true to indicate the email would be sent
    return true;
}

function sendAdminNotification($requestType, $email, $requestId) {
    // Send notification to admin about new privacy request
    $adminEmail = 'admin@virungaecotours.com'; // Configure this
    $subject = "New Privacy Request Submitted - ID: {$requestId}";
    $requestTypeFormatted = ucfirst(str_replace('_', ' ', $requestType));
    
    $message = "
A new privacy request has been submitted:

Request ID: {$requestId}
Request Type: {$requestTypeFormatted}
Email: {$email}
Submitted: " . date('F j, Y g:i A') . "

Please log into the admin panel to review and process this request.

Admin Panel: " . (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}/admin/pages/privacy_management.php

Best regards,
Virunga Ecotours System
    ";
    
    // Uncomment if you have email functionality:
    /*
    $headers = "From: system@virungaecotours.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    mail($adminEmail, $subject, $message, $headers);
    */
}
?>
