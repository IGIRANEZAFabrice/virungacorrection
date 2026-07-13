<?php
require_once '../config/database.php';

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$date = isset($_POST['date']) ? trim($_POST['date']) : '';
$tour_id = isset($_POST['tour_id']) ? (int)$_POST['tour_id'] : 0;
$ip_address = $_SERVER['REMOTE_ADDR'];

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($date) || $tour_id <= 0) {
    header('Location: ../../pages/itenaryopen.php?id='.$tour_id.'&error=missing_fields');
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../../pages/itenaryopen.php?id='.$tour_id.'&error=invalid_email');
    exit();
}

try {
    // Insert booking into database
    $stmt = $pdo->prepare("
        INSERT INTO tour_bookings 
        (tour_id, full_name, email, phone, travel_date, ip_address, status)
        VALUES (:tour_id, :name, :email, :phone, :date, :ip, 'pending')
    ");
    
    $stmt->execute([
        ':tour_id' => $tour_id,
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':date' => $date,
        ':ip' => $ip_address
    ]);

    // Get tour details for email
    $tourStmt = $pdo->prepare("SELECT title FROM tours WHERE tour_id = :tour_id");
    $tourStmt->execute([':tour_id' => $tour_id]);
    $tour = $tourStmt->fetch(PDO::FETCH_ASSOC);

    // Send confirmation email (pseudo-code - implement your email sending logic)
    $to = $email;
    $subject = "Booking Confirmation: " . $tour['title'];
    $message = "Dear $name,\n\nThank you for booking the tour: " . $tour['title'] . ".\n";
    $message .= "Your travel date: $date\n";
    $message .= "We'll contact you shortly to confirm your booking.\n\n";
    $message .= "Best regards,\nVirunga Ecotours Team";
    
    // mail($to, $subject, $message); // Uncomment and configure your mail server

    // Redirect with success
    header('Location: ../../pages/itenaryopen.php?id='.$tour_id.'&success=1');
    exit();

} catch(PDOException $e) {
    error_log("Booking Error: " . $e->getMessage());
    header('Location: ../../pages/itenaryopen.php?id='.$tour_id.'&error=database');
    exit();
}
?>