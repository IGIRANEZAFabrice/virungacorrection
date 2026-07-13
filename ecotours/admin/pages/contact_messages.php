<?php
require_once '../config/connection.php';

// Check admin login
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

// Get all contact submissions
$query = "SELECT * FROM contact_submissions ORDER BY submission_date DESC";
$result = $conn->query($query);
$submissions = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $submissions[] = $row;
    }
}

// Handle message status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mark_as_read'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $conn->query("UPDATE contact_submissions SET is_read = 1 WHERE id = $id");
    } elseif (isset($_POST['mark_as_responded'])) {
        $id = $conn->real_escape_string($_POST['id']);
        $notes = $conn->real_escape_string($_POST['response_notes']);
        $conn->query("UPDATE contact_submissions SET is_responded = 1, response_notes = '$notes', response_date = NOW() WHERE id = $id");
    }
    
    header('Location: contact_messages.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Panel</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/blog.css">
    <link rel="stylesheet" href="../css/common.css" />
    <script src="../js/common.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <?php include_once './includes/sidebar.php'; ?>
        <main class="main-content">
        <!-- Top Header -->
            <?php include_once './includes/header.php'; ?>

            <div class="container">
                <h2 class="section-title">Contact Us Messages</h2>
                
                <div class="messages-container">
                    <?php foreach ($submissions as $submission): ?>
                    <div class="message-card <?php echo $submission['is_read'] ? 'read' : 'unread'; ?>">
                        <div class="message-header">
                            <h3><?php echo htmlspecialchars($submission['subject']); ?></h3>
                            <span class="message-date">
                                <?php echo date('M j, Y g:i a', strtotime($submission['submission_date'])); ?>
                            </span>
                        </div>
                        
                        <div class="message-sender">
                            From: <?php echo htmlspecialchars($submission['first_name'] . ' ' . $submission['last_name']); ?>
                            <span class="sender-email"><?php echo htmlspecialchars($submission['email']); ?></span>
                        </div>
                        
                        <div class="message-content">
                            <?php echo nl2br(htmlspecialchars($submission['message'])); ?>
                        </div>
                        
                        <div class="message-actions">
                            <?php if (!$submission['is_read']): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <button type="submit" name="mark_as_read" class="btn btn-sm">
                                    <i class="fas fa-check"></i> Mark as Read
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <?php if (!$submission['is_responded']): ?>
                            <form method="POST" class="inline-form">
                                <input type="hidden" name="id" value="<?php echo $submission['id']; ?>">
                                <textarea name="response_notes" placeholder="Response notes..." required></textarea>
                                <button type="submit" name="mark_as_responded" class="btn btn-sm btn-primary">
                                    <i class="fas fa-reply"></i> Mark as Responded
                                </button>
                            </form>
                            <?php else: ?>
                            <div class="response-info">
                                <strong>Responded on:</strong> 
                                <?php echo date('M j, Y g:i a', strtotime($submission['response_date'])); ?>
                                <p><?php echo nl2br(htmlspecialchars($submission['response_notes'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>