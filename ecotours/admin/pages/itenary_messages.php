<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fix the path to properly include db_connect.php
require_once('../config/db_connect.php');

// Check admin login
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

// Get all tour bookings
$query = "SELECT b.*, t.title as tour_title 
          FROM tour_bookings b
          JOIN tours t ON b.tour_id = t.tour_id
          ORDER BY b.created_at DESC";
$stmt = $pdo->query($query);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];
        $notes = $_POST['admin_notes'];
        
        $update_query = "UPDATE tour_bookings 
                        SET status = :status, 
                            admin_notes = :notes,
                            updated_at = NOW()
                        WHERE booking_id = :booking_id";
        
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([
            ':status' => $status,
            ':notes' => $notes,
            ':booking_id' => $booking_id
        ]);
        
        header('Location: itenary_messages.php');
        exit();
    } elseif (isset($_POST['delete_booking'])) {
        $booking_id = $_POST['booking_id'];
        
        $delete_query = "DELETE FROM tour_bookings WHERE booking_id = :booking_id";
        $stmt = $pdo->prepare($delete_query);
        $stmt->execute([':booking_id' => $booking_id]);
        
        header('Location: itenary_messages.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itinerary Messages - Admin Panel</title>
    <link rel="stylesheet" href="../css/itenaryMessage.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/common.css" />
    <script src="../js/common.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include_once './includes/sidebar.php'; ?>
        <main class="main-content">
            <?php include_once './includes/header.php'; ?>

            <div class="container">
                <h2 class="section-title">Tour Booking Messages</h2>
                
                <div class="bookings-container">
                    <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card status-<?php echo $booking['status']; ?>">
                        <div class="booking-header">
                            <h3><?php echo htmlspecialchars($booking['tour_title']); ?></h3>
                            <span class="booking-date">
                                <?php echo date('M j, Y g:i a', strtotime($booking['created_at'])); ?>
                            </span>
                            <span class="booking-status"><?php echo ucfirst($booking['status']); ?></span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="detail-row">
                                <span class="detail-label">Traveler:</span>
                                <span><?php echo htmlspecialchars($booking['full_name']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Email:</span>
                                <span><?php echo htmlspecialchars($booking['email']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Phone:</span>
                                <span><?php echo htmlspecialchars($booking['phone']); ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Travel Date:</span>
                                <span><?php echo date('M j, Y', strtotime($booking['travel_date'])); ?></span>
                            </div>
                            <?php if (!empty($booking['message'])): ?>
                            <div class="detail-row">
                                <span class="detail-label">Message:</span>
                                <p><?php echo nl2br(htmlspecialchars($booking['message'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <form method="POST" class="status-form" onsubmit="return confirmDelete(this)">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                            
                            <div class="form-group">
                                <label>Status:</label>
                                <select name="status" required>
                                    <option value="pending" <?php echo $booking['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="confirmed" <?php echo $booking['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="cancelled" <?php echo $booking['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Admin Notes:</label>
                                <textarea name="admin_notes"><?php echo htmlspecialchars($booking['admin_notes'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="button-group">
                                <button type="submit" name="update_status" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Status
                                </button>
                                <button type="submit" name="delete_booking" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
    <script>
    function confirmDelete(form) {
        if (form.delete_booking) {
            if (!confirm('Are you sure you want to delete this booking? This action cannot be undone.')) {
                return false;
            }
        }
        return true;
    }
    </script>
</body>
</html>