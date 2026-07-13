<?php
// Newsletter Subscription Handler
header('Content-Type: application/json');
require_once '../../admin/config/connection.php';

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and validate email
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    if (empty($email)) {
        $response['message'] = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
    } else {
        // Sanitize email
        $email = mysqli_real_escape_string($conn, $email);
        
        // Check if email already exists
        $check_query = "SELECT id FROM newsletter_subscribers WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $response['message'] = 'You are already subscribed to our newsletter.';
        } else {
            // Create table if it doesn't exist
            $create_table_query = "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                status ENUM('active', 'inactive') DEFAULT 'active',
                source VARCHAR(100) DEFAULT 'community_website',
                ip_address VARCHAR(45),
                user_agent TEXT,
                subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email),
                INDEX idx_status (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            
            mysqli_query($conn, $create_table_query);
            
            // Get client information
            $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
            $user_agent = mysqli_real_escape_string($conn, $_SERVER['HTTP_USER_AGENT'] ?? '');
            
            // Insert new subscriber
            $insert_query = "INSERT INTO newsletter_subscribers (email, ip_address, user_agent) 
                           VALUES ('$email', '$ip_address', '$user_agent')";
            
            if (mysqli_query($conn, $insert_query)) {
                $response['success'] = true;
                $response['message'] = 'Thank you for subscribing! You will receive updates about our community programs.';
                
                // Optional: Send welcome email (implement your email service here)
                // sendWelcomeEmail($email);
                
                // Log subscription for analytics
                error_log("Newsletter subscription: $email from " . ($_SERVER['HTTP_REFERER'] ?? 'direct'));
                
            } else {
                $response['message'] = 'Sorry, there was an error processing your subscription. Please try again.';
                error_log("Newsletter subscription error: " . mysqli_error($conn));
            }
        }
    }
} else {
    $response['message'] = 'Invalid request method.';
}

// Close database connection
mysqli_close($conn);

// Return JSON response
echo json_encode($response);

// Optional: Function to send welcome email
function sendWelcomeEmail($email) {
    // Implement your email service here (PHPMailer, SendGrid, etc.)
    // Example structure:
    /*
    $subject = "Welcome to Virunga Ecotours Community Updates";
    $message = "
    <html>
    <head>
        <title>Welcome to Our Community</title>
    </head>
    <body>
        <h2>Welcome to Virunga Ecotours Community Programs!</h2>
        <p>Thank you for subscribing to our newsletter. You'll receive updates about:</p>
        <ul>
            <li>New community programs and initiatives</li>
            <li>Success stories from the field</li>
            <li>Volunteer opportunities</li>
            <li>Ways to support our mission</li>
        </ul>
        <p>Stay connected with our work in the Virunga Massif region!</p>
        <p>Best regards,<br>The Virunga Ecotours Community Team</p>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: community@virungaecotours.com" . "\r\n";
    
    mail($email, $subject, $message, $headers);
    */
}
?>
