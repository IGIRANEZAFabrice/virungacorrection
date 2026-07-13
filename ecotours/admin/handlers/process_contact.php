<?php
require_once '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : null;
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $ipAddress = $_SERVER['REMOTE_ADDR'];

    // Set your email address here
    $to_email = 'aimecol314@gmail.com';
    $from_email = $email;

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO contact_messages 
                           (first_name, last_name, email, phone, subject, message, ip_address) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $firstName, $lastName, $email, $phone, $subject, $message, $ipAddress);
    
    if ($stmt->execute()) {
        // Prepare email content
        $email_subject = "New Contact Form Submission: $subject";
        $email_body = "You have received a new message from the contact form.\n\n".
                      "Name: $firstName $lastName\n".
                      "Email: $email\n".
                      "Phone: ".($phone ? $phone : 'Not provided')."\n".
                      "Subject: $subject\n".
                      "Message:\n$message\n\n".
                      "IP Address: $ipAddress\n".
                      "Submission Date: ".date('Y-m-d H:i:s');
        
        $headers = "From: $from_email" . "\r\n" .
                   "Reply-To: $email" . "\r\n" .
                   "X-Mailer: PHP/" . phpversion();

        // Send email
        $mail_sent = mail($to_email, $email_subject, $email_body, $headers);
        
        // Success - redirect or show success message
        header('Location: ../../pages/contactus.html?status=success');
        exit();
    } else {
        // Error handling
        header('Location: ../../pages/contactus.html?status=error');
        exit();
    }
} else {
    // Not a POST request
    header('Location: ../../pages/contactus.html');
    exit();
}
?>