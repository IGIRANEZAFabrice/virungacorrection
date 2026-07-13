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

// Mark message as read if it's new
if ($message['status'] === 'new') {
    $update_query = "UPDATE community_messages SET status = 'read' WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "i", $message_id);
    mysqli_stmt_execute($update_stmt);
    $message['status'] = 'read'; // Update local variable
}

// Handle admin notes update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_notes'])) {
    $admin_notes = trim($_POST['admin_notes']);
    $notes_query = "UPDATE community_messages SET admin_notes = ? WHERE id = ?";
    $notes_stmt = mysqli_prepare($conn, $notes_query);
    mysqli_stmt_bind_param($notes_stmt, "si", $admin_notes, $message_id);
    
    if (mysqli_stmt_execute($notes_stmt)) {
        $success_message = "Admin notes updated successfully!";
        $message['admin_notes'] = $admin_notes;
    } else {
        $error_message = "Failed to update admin notes.";
    }
}

// Handle status update
if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['status'])) {
    $new_status = $_GET['status'];
    $allowed_statuses = ['new', 'read', 'replied', 'archived'];
    
    if (in_array($new_status, $allowed_statuses)) {
        $status_query = "UPDATE community_messages SET status = ? WHERE id = ?";
        $status_stmt = mysqli_prepare($conn, $status_query);
        mysqli_stmt_bind_param($status_stmt, "si", $new_status, $message_id);
        
        if (mysqli_stmt_execute($status_stmt)) {
            $success_message = "Message status updated to " . ucfirst($new_status) . "!";
            $message['status'] = $new_status;
        }
    }
}

// Get previous and next message IDs for navigation
$prev_query = "SELECT id FROM community_messages WHERE id < ? ORDER BY id DESC LIMIT 1";
$prev_stmt = mysqli_prepare($conn, $prev_query);
mysqli_stmt_bind_param($prev_stmt, "i", $message_id);
mysqli_stmt_execute($prev_stmt);
$prev_result = mysqli_stmt_get_result($prev_stmt);
$prev_message = mysqli_fetch_assoc($prev_result);

$next_query = "SELECT id FROM community_messages WHERE id > ? ORDER BY id ASC LIMIT 1";
$next_stmt = mysqli_prepare($conn, $next_query);
mysqli_stmt_bind_param($next_stmt, "i", $message_id);
mysqli_stmt_execute($next_stmt);
$next_result = mysqli_stmt_get_result($next_stmt);
$next_message = mysqli_fetch_assoc($next_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Message - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/management.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">

    <style>
        .message-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 1rem 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            border: 1px solid var(--neutral-beige);
        }

        .nav-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background-color: var(--neutral-light);
            color: var(--text-dark);
            text-decoration: none;
            border-radius: var(--border-radius-sm);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-btn:hover {
            background-color: var(--primary-green);
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .nav-center {
            font-weight: 600;
            color: var(--text-medium);
        }

        .message-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .message-header-card, .message-body-card, .admin-notes-card, .technical-info-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
        }

        .message-header-card {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 2rem;
        }

        .message-subject {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .message-subject h2 {
            font-size: 1.5rem;
            color: var(--text-dark);
            margin: 0;
            font-weight: 600;
        }

        .message-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 2rem;
        }

        .sender-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sender-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--primary-green);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .sender-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .sender-name {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 1.1rem;
        }

        .sender-email a, .sender-phone a {
            color: var(--primary-green);
            text-decoration: none;
            font-size: 0.9rem;
        }

        .sender-email a:hover, .sender-phone a:hover {
            text-decoration: underline;
        }

        .message-timestamp {
            text-align: right;
            color: var(--text-medium);
        }

        .message-timestamp .date {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .message-timestamp .time {
            font-size: 0.85rem;
        }

        .status-actions {
            position: relative;
        }

        .dropdown-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: var(--neutral-light);
            border: 1px solid var(--neutral-beige);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .dropdown-btn:hover {
            background-color: var(--neutral-beige);
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--neutral-beige);
            min-width: 180px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s ease;
            z-index: 1000;
            margin-top: 0.5rem;
        }

        .dropdown-menu.active {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            transition: background-color 0.3s ease;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background-color: var(--neutral-light);
        }

        .dropdown-item.active {
            background-color: var(--primary-green);
            color: white;
        }

        .message-body-card {
            padding: 2rem;
        }

        .message-content h3, .admin-notes-card h3, .technical-info-card h3 {
            font-size: 1.2rem;
            color: var(--text-dark);
            margin: 0 0 1.5rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--neutral-light);
        }

        .message-content h3 i, .admin-notes-card h3 i, .technical-info-card h3 i {
            color: var(--primary-green);
        }

        .message-text {
            background-color: var(--neutral-light);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--primary-green);
            line-height: 1.6;
            color: var(--text-dark);
            font-size: 1rem;
        }

        .additional-info {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--neutral-beige);
        }

        .additional-info h4 {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin: 0 0 1rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-grid, .tech-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item, .tech-item {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-item label, .tech-item label {
            font-weight: 600;
            color: var(--text-medium);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-item span, .tech-item span {
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .interest-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: var(--success-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.85rem;
            font-weight: 500;
        }

        .admin-notes-card, .technical-info-card {
            padding: 2rem;
        }

        .notes-form .form-group {
            margin-bottom: 1rem;
        }

        .notes-form textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--neutral-beige);
            border-radius: var(--border-radius-sm);
            font-family: inherit;
            font-size: 0.95rem;
            line-height: 1.5;
            resize: vertical;
        }

        .notes-form textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(42, 72, 88, 0.1);
        }

        .user-agent {
            font-family: monospace;
            font-size: 0.8rem;
            background-color: var(--neutral-light);
            padding: 0.5rem;
            border-radius: var(--border-radius-sm);
            word-break: break-all;
        }

        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: var(--success-color);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 10000;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.notification-error {
            background: var(--error-color);
        }

        @media (max-width: 768px) {
            .message-navigation {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .message-header-card {
                flex-direction: column;
                gap: 1.5rem;
            }

            .message-meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .info-grid, .tech-grid {
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
                        <h1><i class="fas fa-envelope-open"></i> Message Details</h1>
                        <p>View and manage message from <?php echo htmlspecialchars($message['name']); ?></p>
                    </div>
                    <div class="page-header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Messages
                        </a>
                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-reply"></i>
                            Reply via Email
                        </a>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="message-navigation">
                    <?php if ($prev_message): ?>
                        <a href="view.php?id=<?php echo $prev_message['id']; ?>" class="nav-btn prev">
                            <i class="fas fa-chevron-left"></i>
                            Previous Message
                        </a>
                    <?php endif; ?>
                    
                    <div class="nav-center">
                        <span class="message-counter">Message #<?php echo $message['id']; ?></span>
                    </div>
                    
                    <?php if ($next_message): ?>
                        <a href="view.php?id=<?php echo $next_message['id']; ?>" class="nav-btn next">
                            Next Message
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
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

                <!-- Message Content -->
                <div class="message-container">
                    <!-- Message Header -->
                    <div class="message-header-card">
                        <div class="message-header-content">
                            <div class="message-subject">
                                <h2><?php echo htmlspecialchars($message['subject'] ?: 'No Subject'); ?></h2>
                                <div class="message-status">
                                    <span class="status-badge status-<?php echo $message['status']; ?>">
                                        <?php echo ucfirst($message['status']); ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="message-meta">
                                <div class="sender-info">
                                    <div class="sender-avatar">
                                        <?php echo strtoupper(substr($message['name'], 0, 1)); ?>
                                    </div>
                                    <div class="sender-details">
                                        <div class="sender-name"><?php echo htmlspecialchars($message['name']); ?></div>
                                        <div class="sender-email">
                                            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                                <?php echo htmlspecialchars($message['email']); ?>
                                            </a>
                                        </div>
                                        <?php if ($message['phone']): ?>
                                            <div class="sender-phone">
                                                <a href="tel:<?php echo htmlspecialchars($message['phone']); ?>">
                                                    <?php echo htmlspecialchars($message['phone']); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="message-timestamp">
                                    <div class="date"><?php echo date('F j, Y', strtotime($message['sent_at'])); ?></div>
                                    <div class="time"><?php echo date('g:i A', strtotime($message['sent_at'])); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Actions -->
                        <div class="status-actions">
                            <div class="dropdown">
                                <button class="dropdown-btn" id="statusDropdown">
                                    <i class="fas fa-cog"></i>
                                    Change Status
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <div class="dropdown-menu" id="statusMenu">
                                    <a href="?id=<?php echo $message_id; ?>&action=update_status&status=new" 
                                       class="dropdown-item <?php echo $message['status'] === 'new' ? 'active' : ''; ?>">
                                        <i class="fas fa-envelope"></i>
                                        New
                                    </a>
                                    <a href="?id=<?php echo $message_id; ?>&action=update_status&status=read" 
                                       class="dropdown-item <?php echo $message['status'] === 'read' ? 'active' : ''; ?>">
                                        <i class="fas fa-envelope-open"></i>
                                        Read
                                    </a>
                                    <a href="?id=<?php echo $message_id; ?>&action=update_status&status=replied" 
                                       class="dropdown-item <?php echo $message['status'] === 'replied' ? 'active' : ''; ?>">
                                        <i class="fas fa-reply"></i>
                                        Replied
                                    </a>
                                    <a href="?id=<?php echo $message_id; ?>&action=update_status&status=archived" 
                                       class="dropdown-item <?php echo $message['status'] === 'archived' ? 'active' : ''; ?>">
                                        <i class="fas fa-archive"></i>
                                        Archived
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Body -->
                    <div class="message-body-card">
                        <div class="message-content">
                            <h3><i class="fas fa-comment"></i> Message Content</h3>
                            <div class="message-text">
                                <?php echo nl2br(htmlspecialchars($message['message'])); ?>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <?php if ($message['country'] || $message['program_interest'] || $message['volunteer_interest'] || $message['donation_interest']): ?>
                            <div class="additional-info">
                                <h4><i class="fas fa-info-circle"></i> Additional Information</h4>
                                <div class="info-grid">
                                    <?php if ($message['country']): ?>
                                        <div class="info-item">
                                            <label>Country:</label>
                                            <span><?php echo htmlspecialchars($message['country']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($message['program_interest']): ?>
                                        <div class="info-item">
                                            <label>Program Interest:</label>
                                            <span><?php echo htmlspecialchars($message['program_interest']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($message['volunteer_interest']): ?>
                                        <div class="info-item">
                                            <label>Volunteer Interest:</label>
                                            <span class="interest-badge">
                                                <i class="fas fa-hands-helping"></i>
                                                Yes
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($message['donation_interest']): ?>
                                        <div class="info-item">
                                            <label>Donation Interest:</label>
                                            <span class="interest-badge">
                                                <i class="fas fa-heart"></i>
                                                Yes
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Admin Notes -->
                    <div class="admin-notes-card">
                        <h3><i class="fas fa-sticky-note"></i> Admin Notes</h3>
                        <form method="POST" class="notes-form">
                            <div class="form-group">
                                <textarea name="admin_notes" rows="4" 
                                          placeholder="Add internal notes about this message..."><?php echo htmlspecialchars($message['admin_notes']); ?></textarea>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Save Notes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Technical Information -->
                    <div class="technical-info-card">
                        <h3><i class="fas fa-server"></i> Technical Information</h3>
                        <div class="tech-grid">
                            <div class="tech-item">
                                <label>IP Address:</label>
                                <span><?php echo htmlspecialchars($message['ip_address'] ?: 'Not recorded'); ?></span>
                            </div>
                            <div class="tech-item">
                                <label>User Agent:</label>
                                <span class="user-agent"><?php echo htmlspecialchars($message['user_agent'] ?: 'Not recorded'); ?></span>
                            </div>
                            <div class="tech-item">
                                <label>Received:</label>
                                <span><?php echo date('F j, Y \a\t g:i A', strtotime($message['sent_at'])); ?></span>
                            </div>
                            <?php if ($message['replied_at']): ?>
                                <div class="tech-item">
                                    <label>Last Reply:</label>
                                    <span><?php echo date('F j, Y \a\t g:i A', strtotime($message['replied_at'])); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Status dropdown functionality
            const statusDropdown = document.getElementById('statusDropdown');
            const statusMenu = document.getElementById('statusMenu');
            
            if (statusDropdown && statusMenu) {
                statusDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    statusMenu.classList.toggle('active');
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    statusMenu.classList.remove('active');
                });
            }
            
            // Auto-save notes functionality
            const notesTextarea = document.querySelector('textarea[name="admin_notes"]');
            let saveTimeout;
            
            if (notesTextarea) {
                notesTextarea.addEventListener('input', function() {
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(() => {
                        // Auto-save after 3 seconds of inactivity
                        const form = this.closest('form');
                        const formData = new FormData(form);
                        
                        fetch(window.location.href, {
                            method: 'POST',
                            body: formData
                        }).then(response => {
                            if (response.ok) {
                                showNotification('Notes auto-saved', 'success');
                            }
                        }).catch(error => {
                            console.error('Auto-save failed:', error);
                        });
                    }, 3000);
                });
            }
        });
        
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
                ${message}
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
