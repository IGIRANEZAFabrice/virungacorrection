<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get message ID
$message_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$message_id) {
    header('Location: index.php?error=' . urlencode('Invalid message ID.'));
    exit;
}

// Get message data
$message_query = "SELECT * FROM community_messages WHERE id = ?";
$message_stmt = mysqli_prepare($conn, $message_query);
mysqli_stmt_bind_param($message_stmt, "i", $message_id);
mysqli_stmt_execute($message_stmt);
$message_result = mysqli_stmt_get_result($message_stmt);

if (mysqli_num_rows($message_result) === 0) {
    header('Location: index.php?error=' . urlencode('Message not found.'));
    exit;
}

$message = mysqli_fetch_assoc($message_result);

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply_subject = trim($_POST['reply_subject'] ?? '');
    $reply_message = trim($_POST['reply_message'] ?? '');
    $send_copy = isset($_POST['send_copy']);
    
    if (empty($reply_subject) || empty($reply_message)) {
        $error_message = "Subject and message are required.";
    } else {
        // Here you would integrate with your email system
        // For now, we'll just update the message status and log the reply
        
        $update_query = "UPDATE community_messages SET 
            status = 'replied', 
            replied_at = NOW(),
            admin_notes = CONCAT(COALESCE(admin_notes, ''), '\n\n--- REPLY SENT ---\nSubject: ', ?, '\nMessage: ', ?, '\nSent by: Admin ID ', ?, '\nSent at: ', NOW())
            WHERE id = ?";
        
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "ssii", $reply_subject, $reply_message, $_SESSION['community_admin_id'], $message_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            // Log the reply
            error_log("Reply sent to message ID: $message_id by admin ID: " . $_SESSION['community_admin_id']);
            
            $success_message = "Reply sent successfully! The message has been marked as replied.";
            
            // Redirect to view page after successful reply
            header('Location: view.php?id=' . $message_id . '&reply_sent=1');
            exit;
        } else {
            $error_message = "Failed to update message status.";
        }
    }
}

// Generate default reply subject
$default_subject = 'Re: ' . ($message['subject'] ?: 'Your message to Virunga Ecotours');

// Generate default reply message template
$default_message = "Dear " . $message['name'] . ",\n\n";
$default_message .= "Thank you for contacting Virunga Ecotours. We have received your message";
if ($message['subject']) {
    $default_message .= " regarding \"" . $message['subject'] . "\"";
}
$default_message .= ".\n\n";

if ($message['volunteer_interest']) {
    $default_message .= "We're excited to hear about your interest in volunteering with us! ";
}

if ($message['donation_interest']) {
    $default_message .= "We appreciate your interest in supporting our conservation efforts through donations. ";
}

$default_message .= "\n\nBest regards,\nVirunga Ecotours Team\n\n";
$default_message .= "---\nOriginal Message:\n";
$default_message .= "From: " . $message['name'] . " <" . $message['email'] . ">\n";
$default_message .= "Date: " . date('F j, Y \a\t g:i A', strtotime($message['sent_at'])) . "\n";
if ($message['subject']) {
    $default_message .= "Subject: " . $message['subject'] . "\n";
}
$default_message .= "\n" . $message['message'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Message - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <style>
        .reply-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .original-message-card, .reply-form-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
        }
        
        .card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
            border-bottom: 1px solid var(--neutral-beige);
        }
        
        .card-header h3 {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-header h3 i {
            color: var(--primary-green);
        }
        
        .card-content {
            padding: 1.5rem;
        }
        
        .original-message {
            background-color: var(--neutral-light);
            padding: 1rem;
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--primary-green);
            margin-bottom: 1rem;
        }
        
        .message-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        
        .meta-label {
            font-weight: 600;
            color: var(--text-medium);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
        
        .meta-value {
            color: var(--text-dark);
        }
        
        .reply-form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .form-group label {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input,
        .form-group textarea {
            padding: 0.75rem;
            border: 1px solid var(--neutral-beige);
            border-radius: var(--border-radius-sm);
            font-family: inherit;
            font-size: 0.95rem;
        }
        
        .form-group textarea {
            min-height: 300px;
            resize: vertical;
            line-height: 1.5;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(42, 72, 88, 0.1);
        }
        
        .form-options {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background-color: var(--neutral-light);
            border-radius: var(--border-radius-sm);
        }
        
        .form-options input[type="checkbox"] {
            margin: 0;
        }
        
        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            padding-top: 1rem;
            border-top: 1px solid var(--neutral-beige);
        }
        
        .template-buttons {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .template-btn {
            padding: 0.5rem 1rem;
            background-color: var(--neutral-light);
            border: 1px solid var(--neutral-beige);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        
        .template-btn:hover {
            background-color: var(--primary-green);
            color: white;
            border-color: var(--primary-green);
        }
        
        @media (max-width: 1024px) {
            .reply-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <?php include '../includes/topbar.php'; ?>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-content">
                        <h1><i class="fas fa-reply"></i> Reply to Message</h1>
                        <p>Compose a reply to <?php echo htmlspecialchars($message['name']); ?></p>
                    </div>
                    <div class="page-header-actions">
                        <a href="view.php?id=<?php echo $message_id; ?>" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Message
                        </a>
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-list"></i>
                            All Messages
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <!-- Reply Container -->
                <div class="reply-container">
                    <!-- Original Message -->
                    <div class="original-message-card">
                        <div class="card-header">
                            <h3><i class="fas fa-envelope"></i> Original Message</h3>
                        </div>
                        <div class="card-content">
                            <div class="message-meta">
                                <div class="meta-item">
                                    <div class="meta-label">From</div>
                                    <div class="meta-value"><?php echo htmlspecialchars($message['name']); ?></div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Email</div>
                                    <div class="meta-value"><?php echo htmlspecialchars($message['email']); ?></div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Date</div>
                                    <div class="meta-value"><?php echo date('F j, Y \a\t g:i A', strtotime($message['sent_at'])); ?></div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Status</div>
                                    <div class="meta-value">
                                        <span class="status-badge status-<?php echo $message['status']; ?>">
                                            <?php echo ucfirst($message['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($message['subject']): ?>
                                <div class="meta-item" style="margin-bottom: 1rem;">
                                    <div class="meta-label">Subject</div>
                                    <div class="meta-value"><?php echo htmlspecialchars($message['subject']); ?></div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="original-message">
                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                            </div>
                            
                            <!-- Additional Info -->
                            <?php if ($message['volunteer_interest'] || $message['donation_interest'] || $message['country']): ?>
                                <div class="additional-info" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--neutral-beige);">
                                    <div class="meta-label" style="margin-bottom: 0.5rem;">Additional Information</div>
                                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                        <?php if ($message['volunteer_interest']): ?>
                                            <span class="interest-badge volunteer">
                                                <i class="fas fa-hands-helping"></i>
                                                Volunteer Interest
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($message['donation_interest']): ?>
                                            <span class="interest-badge donation">
                                                <i class="fas fa-heart"></i>
                                                Donation Interest
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($message['country']): ?>
                                            <span class="interest-badge country">
                                                <i class="fas fa-globe"></i>
                                                <?php echo htmlspecialchars($message['country']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reply Form -->
                    <div class="reply-form-card">
                        <div class="card-header">
                            <h3><i class="fas fa-pen"></i> Compose Reply</h3>
                        </div>
                        <div class="card-content">
                            <form method="POST" class="reply-form" id="replyForm">
                                <div class="template-buttons">
                                    <button type="button" class="template-btn" onclick="loadTemplate('default')">
                                        Default Template
                                    </button>
                                    <button type="button" class="template-btn" onclick="loadTemplate('volunteer')">
                                        Volunteer Template
                                    </button>
                                    <button type="button" class="template-btn" onclick="loadTemplate('donation')">
                                        Donation Template
                                    </button>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reply_subject">Subject</label>
                                    <input type="text" id="reply_subject" name="reply_subject" 
                                           value="<?php echo htmlspecialchars($default_subject); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reply_message">Message</label>
                                    <textarea id="reply_message" name="reply_message" required><?php echo htmlspecialchars($default_message); ?></textarea>
                                </div>
                                
                                <div class="form-options">
                                    <input type="checkbox" id="send_copy" name="send_copy" checked>
                                    <label for="send_copy">Send a copy to my email</label>
                                </div>
                                
                                <div class="form-actions">
                                    <a href="view.php?id=<?php echo $message_id; ?>" class="btn btn-outline">
                                        <i class="fas fa-times"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane"></i>
                                        Send Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
        // Template data
        const templates = {
            default: {
                subject: 'Re: <?php echo addslashes($message['subject'] ?: 'Your message to Virunga Ecotours'); ?>',
                message: `<?php echo addslashes($default_message); ?>`
            },
            volunteer: {
                subject: 'Re: Volunteer Opportunities with Virunga Ecotours',
                message: `Dear <?php echo addslashes($message['name']); ?>,

Thank you for your interest in volunteering with Virunga Ecotours! We're excited to hear from someone who shares our passion for conservation and community development.

Our volunteer programs offer unique opportunities to:
- Support local conservation efforts
- Work directly with communities
- Gain hands-on experience in sustainable tourism
- Make a meaningful impact in Rwanda

We would love to discuss how you can get involved. Please let us know:
1. Your areas of interest
2. Your availability
3. Any relevant skills or experience

We'll be in touch soon with more details about our current volunteer opportunities.

Best regards,
Virunga Ecotours Team

---
Original Message:
From: <?php echo addslashes($message['name']); ?> <<?php echo addslashes($message['email']); ?>>
Date: <?php echo addslashes(date('F j, Y \a\t g:i A', strtotime($message['sent_at']))); ?>

<?php echo addslashes($message['message']); ?>`
            },
            donation: {
                subject: 'Re: Supporting Virunga Ecotours Conservation Efforts',
                message: `Dear <?php echo addslashes($message['name']); ?>,

Thank you for your interest in supporting our conservation efforts through donations. Your generosity helps us continue our vital work protecting Rwanda's natural heritage and supporting local communities.

Your donation can help us:
- Protect endangered species and their habitats
- Support community-based conservation programs
- Provide education and training opportunities
- Develop sustainable tourism initiatives

We have several ways you can contribute:
- One-time donations
- Monthly giving programs
- Sponsorship opportunities
- In-kind donations

I'll send you detailed information about our donation programs and how your contribution will make a direct impact.

Thank you for considering supporting our mission.

Best regards,
Virunga Ecotours Team

---
Original Message:
From: <?php echo addslashes($message['name']); ?> <<?php echo addslashes($message['email']); ?>>
Date: <?php echo addslashes(date('F j, Y \a\t g:i A', strtotime($message['sent_at']))); ?>

<?php echo addslashes($message['message']); ?>`
            }
        };

        function loadTemplate(templateName) {
            const template = templates[templateName];
            if (template) {
                document.getElementById('reply_subject').value = template.subject;
                document.getElementById('reply_message').value = template.message;
            }
        }

        // Form validation
        document.getElementById('replyForm').addEventListener('submit', function(e) {
            const subject = document.getElementById('reply_subject').value.trim();
            const message = document.getElementById('reply_message').value.trim();
            
            if (!subject || !message) {
                e.preventDefault();
                alert('Please fill in both subject and message fields.');
                return;
            }
            
            if (!confirm('Are you sure you want to send this reply?')) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
