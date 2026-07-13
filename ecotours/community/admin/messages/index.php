<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Handle mark as read/unread
if (isset($_GET['action']) && isset($_GET['id'])) {
    $message_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'mark_read') {
        $update_query = "UPDATE community_messages SET status = 'read' WHERE id = $message_id";
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Message marked as read!";
        }
    } elseif ($action === 'mark_unread') {
        $update_query = "UPDATE community_messages SET status = 'new' WHERE id = $message_id";
        if (mysqli_query($conn, $update_query)) {
            $success_message = "Message marked as unread!";
        }
    } elseif ($action === 'delete') {
        $delete_query = "DELETE FROM community_messages WHERE id = $message_id";
        if (mysqli_query($conn, $delete_query)) {
            $success_message = "Message deleted successfully!";
        } else {
            $error_message = "Error deleting message: " . mysqli_error($conn);
        }
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_messages'])) {
    $selected_messages = $_POST['selected_messages'];
    $action = $_POST['bulk_action'];
    
    if ($action === 'delete') {
        $ids = implode(',', array_map('intval', $selected_messages));
        $bulk_delete_query = "DELETE FROM community_messages WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_delete_query)) {
            $success_message = count($selected_messages) . " messages deleted successfully!";
        }
    } elseif ($action === 'mark_read') {
        $ids = implode(',', array_map('intval', $selected_messages));
        $bulk_update_query = "UPDATE community_messages SET status = 'read' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_messages) . " messages marked as read!";
        }
    } elseif ($action === 'mark_unread') {
        $ids = implode(',', array_map('intval', $selected_messages));
        $bulk_update_query = "UPDATE community_messages SET status = 'new' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_messages) . " messages marked as unread!";
        }
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';
$country_filter = isset($_GET['country']) ? mysqli_real_escape_string($conn, $_GET['country']) : '';
$interest_filter = isset($_GET['interest']) ? mysqli_real_escape_string($conn, $_GET['interest']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 15;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$where_conditions = ["1=1"];

if ($status_filter) {
    $where_conditions[] = "status = '$status_filter'";
}

if ($search_query) {
    $where_conditions[] = "(name LIKE '%$search_query%' OR email LIKE '%$search_query%' OR subject LIKE '%$search_query%' OR message LIKE '%$search_query%')";
}

if ($date_from) {
    $where_conditions[] = "DATE(sent_at) >= '$date_from'";
}

if ($date_to) {
    $where_conditions[] = "DATE(sent_at) <= '$date_to'";
}

if ($country_filter) {
    $where_conditions[] = "country = '$country_filter'";
}

if ($interest_filter) {
    if ($interest_filter === 'volunteer') {
        $where_conditions[] = "volunteer_interest = 1";
    } elseif ($interest_filter === 'donation') {
        $where_conditions[] = "donation_interest = 1";
    }
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_messages WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_messages = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_messages / $per_page);

// Get messages
$messages_query = "SELECT * FROM community_messages WHERE $where_clause ORDER BY sent_at DESC LIMIT $per_page OFFSET $offset";
$messages_result = mysqli_query($conn, $messages_query);

// Get message statistics
$stats_query = "SELECT
    COUNT(*) as total,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_messages,
    COUNT(CASE WHEN status = 'read' THEN 1 END) as read_messages,
    COUNT(CASE WHEN status = 'replied' THEN 1 END) as replied_messages,
    COUNT(CASE WHEN status = 'archived' THEN 1 END) as archived_messages,
    COUNT(CASE WHEN volunteer_interest = 1 THEN 1 END) as volunteer_interested,
    COUNT(CASE WHEN donation_interest = 1 THEN 1 END) as donation_interested
    FROM community_messages";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get countries for filter
$countries_query = "SELECT DISTINCT country FROM community_messages WHERE country IS NOT NULL AND country != '' ORDER BY country";
$countries_result = mysqli_query($conn, $countries_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages Management - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/management.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
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
                        <h1><i class="fas fa-envelope"></i> Messages Management</h1>
                        <p>View and manage contact form submissions</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="export.php" class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Export Messages
                        </a>
                    </div>
                </div>

                <!-- Message Statistics -->
                <div class="stats-grid" style="margin-bottom: 2rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--primary-green);">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['total']); ?></div>
                        <div class="stat-label">Total Messages</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--info-color);">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['new_messages']); ?></div>
                        <div class="stat-label">New Messages</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--success-color);">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['read_messages']); ?></div>
                        <div class="stat-label">Read Messages</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--accent-terracotta);">
                            <i class="fas fa-reply"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['replied_messages']); ?></div>
                        <div class="stat-label">Replied</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--warning-color);">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['volunteer_interested']); ?></div>
                        <div class="stat-label">Volunteer Interest</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--error-color);">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-number"><?php echo number_format($stats['donation_interested']); ?></div>
                        <div class="stat-label">Donation Interest</div>
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

                <!-- Filters -->
                <div class="filters-card">
                    <div class="filters-header">
                        <h3><i class="fas fa-filter"></i> Filters</h3>
                        <button class="filters-toggle" id="filtersToggle">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                    <div class="filters-content" id="filtersContent">
                        <form method="GET" class="filters-form">
                            <div class="filters-grid">
                                <div class="filter-group">
                                    <label for="search">Search</label>
                                    <input type="text" id="search" name="search" 
                                           value="<?php echo htmlspecialchars($search_query); ?>" 
                                           placeholder="Search messages...">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="new" <?php echo $status_filter === 'new' ? 'selected' : ''; ?>>New</option>
                                        <option value="read" <?php echo $status_filter === 'read' ? 'selected' : ''; ?>>Read</option>
                                        <option value="replied" <?php echo $status_filter === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                        <option value="archived" <?php echo $status_filter === 'archived' ? 'selected' : ''; ?>>Archived</option>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="date_from">Date From</label>
                                    <input type="date" id="date_from" name="date_from"
                                           value="<?php echo htmlspecialchars($date_from); ?>">
                                </div>

                                <div class="filter-group">
                                    <label for="date_to">Date To</label>
                                    <input type="date" id="date_to" name="date_to"
                                           value="<?php echo htmlspecialchars($date_to); ?>">
                                </div>

                                <div class="filter-group">
                                    <label for="country">Country</label>
                                    <select id="country" name="country">
                                        <option value="">All Countries</option>
                                        <?php while ($country = mysqli_fetch_assoc($countries_result)): ?>
                                            <option value="<?php echo htmlspecialchars($country['country']); ?>"
                                                    <?php echo $country_filter === $country['country'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($country['country']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="filter-group">
                                    <label for="interest">Interest Type</label>
                                    <select id="interest" name="interest">
                                        <option value="">All Interests</option>
                                        <option value="volunteer" <?php echo $interest_filter === 'volunteer' ? 'selected' : ''; ?>>Volunteer Interest</option>
                                        <option value="donation" <?php echo $interest_filter === 'donation' ? 'selected' : ''; ?>>Donation Interest</option>
                                    </select>
                                </div>
                            </div>

                            <div class="filters-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                    Apply Filters
                                </button>
                                <a href="index.php" class="btn btn-outline">
                                    <i class="fas fa-times"></i>
                                    Clear Filters
                                </a>
                                <a href="export.php?<?php echo http_build_query($_GET); ?>" class="btn btn-success">
                                    <i class="fas fa-download"></i>
                                    Export Results
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Messages Table -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Messages (<?php echo $total_messages; ?>)</h3>
                        </div>
                        <div class="table-actions">
                            <form method="POST" class="bulk-actions-form" id="bulkActionsForm">
                                <select name="bulk_action" id="bulkAction">
                                    <option value="">Bulk Actions</option>
                                    <option value="mark_read">Mark as Read</option>
                                    <option value="mark_unread">Mark as Unread</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-outline btn-sm" id="applyBulkAction" disabled>
                                    Apply
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <?php if (mysqli_num_rows($messages_result) > 0): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Message</th>
                                        <th>Contact Info</th>
                                        <th>Status</th>
                                        <th>Received</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($message = mysqli_fetch_assoc($messages_result)): ?>
                                        <tr class="<?php echo $message['status'] === 'new' ? 'message-unread' : ''; ?>">
                                            <td>
                                                <input type="checkbox" name="selected_messages[]" 
                                                       value="<?php echo $message['id']; ?>" 
                                                       class="message-checkbox">
                                            </td>
                                            <td>
                                                <div class="message-info">
                                                    <div class="message-subject">
                                                        <h4><?php echo htmlspecialchars($message['subject']); ?></h4>
                                                        <?php if ($message['status'] === 'new'): ?>
                                                            <span class="new-indicator">
                                                                <i class="fas fa-circle"></i>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="message-preview">
                                                        <p><?php echo htmlspecialchars(substr($message['message'], 0, 120)); ?>...</p>
                                                    </div>

                                                    <!-- Interest Badges -->
                                                    <div class="interest-badges">
                                                        <?php if ($message['volunteer_interest']): ?>
                                                            <span class="interest-badge volunteer">
                                                                <i class="fas fa-hands-helping"></i>
                                                                Volunteer
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($message['donation_interest']): ?>
                                                            <span class="interest-badge donation">
                                                                <i class="fas fa-heart"></i>
                                                                Donation
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
                                            </td>
                                            <td>
                                                <div class="contact-info">
                                                    <div class="contact-name">
                                                        <i class="fas fa-user"></i>
                                                        <?php echo htmlspecialchars($message['name']); ?>
                                                    </div>
                                                    <div class="contact-email">
                                                        <i class="fas fa-envelope"></i>
                                                        <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>">
                                                            <?php echo htmlspecialchars($message['email']); ?>
                                                        </a>
                                                    </div>
                                                    <?php if ($message['phone']): ?>
                                                        <div class="contact-phone">
                                                            <i class="fas fa-phone"></i>
                                                            <a href="tel:<?php echo htmlspecialchars($message['phone']); ?>">
                                                                <?php echo htmlspecialchars($message['phone']); ?>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $message['status']; ?>">
                                                    <?php echo ucfirst($message['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <div class="date"><?php echo date('M j, Y', strtotime($message['sent_at'])); ?></div>
                                                    <div class="time"><?php echo date('g:i A', strtotime($message['sent_at'])); ?></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="view.php?id=<?php echo $message['id']; ?>" 
                                                       class="btn-action btn-view" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($message['status'] === 'new'): ?>
                                                        <a href="?action=mark_read&id=<?php echo $message['id']; ?>" 
                                                           class="btn-action btn-edit" title="Mark as Read">
                                                            <i class="fas fa-envelope-open"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <a href="?action=mark_unread&id=<?php echo $message['id']; ?>" 
                                                           class="btn-action btn-edit" title="Mark as Unread">
                                                            <i class="fas fa-envelope"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo urlencode($message['subject']); ?>" 
                                                       class="btn-action btn-view" title="Reply">
                                                        <i class="fas fa-reply"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?php echo $message['id']; ?>" 
                                                       class="btn-action btn-delete" title="Delete"
                                                       data-confirm="Are you sure you want to delete this message?">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <h3>No Messages Found</h3>
                                <p>No messages match your current filters. Try adjusting your search criteria.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-wrapper">
                            <nav class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" 
                                       class="pagination-btn prev">
                                        <i class="fas fa-chevron-left"></i>
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <div class="pagination-numbers">
                                    <?php
                                    $start = max(1, $page - 2);
                                    $end = min($total_pages, $page + 2);
                                    
                                    for ($i = $start; $i <= $end; $i++): ?>
                                        <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                           class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    <?php endfor; ?>
                                </div>

                                <?php if ($page < $total_pages): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" 
                                       class="pagination-btn next">
                                        Next
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script src="../assets/js/management.js"></script>
    
    <style>
        .message-unread {
            background-color: rgba(42, 72, 88, 0.02);
            border-left: 3px solid var(--primary-green);
        }
        
        .message-info {
            max-width: 400px;
        }
        
        .message-subject {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .message-subject h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        
        .new-indicator {
            color: var(--primary-green);
            font-size: 0.6rem;
        }
        
        .message-preview p {
            font-size: 0.85rem;
            color: var(--text-medium);
            line-height: 1.4;
            margin: 0;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .contact-name, .contact-email, .contact-phone {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .contact-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .contact-email a, .contact-phone a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .contact-email a:hover, .contact-phone a:hover {
            text-decoration: underline;
        }
        
        .date-info {
            text-align: center;
        }
        
        .date-info .date {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 0.85rem;
        }
        
        .date-info .time {
            color: var(--text-medium);
            font-size: 0.8rem;
        }
        
        .stat-icon.new {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-icon.read {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .interest-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        .interest-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            border-radius: var(--border-radius-sm);
            font-size: 0.7rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .interest-badge.volunteer {
            background-color: var(--warning-color);
            color: white;
        }

        .interest-badge.donation {
            background-color: var(--error-color);
            color: white;
        }

        .interest-badge.country {
            background-color: var(--info-color);
            color: white;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filter-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--text-dark);
        }

        .filter-group input,
        .filter-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--neutral-beige);
            border-radius: var(--border-radius-sm);
            font-size: 0.9rem;
        }

        .filter-group input:focus,
        .filter-group select:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(42, 72, 88, 0.1);
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
            border: 1px solid var(--success-color);
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
