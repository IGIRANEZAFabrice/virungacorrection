<?php
require_once '../config/connection.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

// Handle actions
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'toggle_status':
                if (isset($_POST['subscriber_id'])) {
                    $subscriber_id = intval($_POST['subscriber_id']);
                    $toggle_query = "UPDATE subscribers SET status = 1 - status WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $toggle_query);
                    mysqli_stmt_bind_param($stmt, "i", $subscriber_id);

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Subscriber status updated successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Error updating subscriber status.";
                        $message_type = "error";
                    }
                }
                break;

            case 'delete_subscriber':
                if (isset($_POST['subscriber_id'])) {
                    $subscriber_id = intval($_POST['subscriber_id']);
                    $delete_query = "DELETE FROM subscribers WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $delete_query);
                    mysqli_stmt_bind_param($stmt, "i", $subscriber_id);

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Subscriber deleted successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Error deleting subscriber.";
                        $message_type = "error";
                    }
                }
                break;

            case 'bulk_action':
                if (isset($_POST['bulk_action']) && isset($_POST['selected_subscribers'])) {
                    $bulk_action = $_POST['bulk_action'];
                    $selected_ids = $_POST['selected_subscribers'];

                    if (!empty($selected_ids)) {
                        $ids_placeholder = str_repeat('?,', count($selected_ids) - 1) . '?';

                        if ($bulk_action === 'delete') {
                            $bulk_query = "DELETE FROM subscribers WHERE id IN ($ids_placeholder)";
                            $stmt = mysqli_prepare($conn, $bulk_query);
                            mysqli_stmt_bind_param($stmt, str_repeat('i', count($selected_ids)), ...$selected_ids);

                            if (mysqli_stmt_execute($stmt)) {
                                $count = mysqli_stmt_affected_rows($stmt);
                                $message = "$count subscriber(s) deleted successfully!";
                                $message_type = "success";
                            } else {
                                $message = "Error deleting subscribers.";
                                $message_type = "error";
                            }
                        } elseif ($bulk_action === 'activate') {
                            $bulk_query = "UPDATE subscribers SET status = 1 WHERE id IN ($ids_placeholder)";
                            $stmt = mysqli_prepare($conn, $bulk_query);
                            mysqli_stmt_bind_param($stmt, str_repeat('i', count($selected_ids)), ...$selected_ids);

                            if (mysqli_stmt_execute($stmt)) {
                                $count = mysqli_stmt_affected_rows($stmt);
                                $message = "$count subscriber(s) activated successfully!";
                                $message_type = "success";
                            } else {
                                $message = "Error activating subscribers.";
                                $message_type = "error";
                            }
                        } elseif ($bulk_action === 'deactivate') {
                            $bulk_query = "UPDATE subscribers SET status = 0 WHERE id IN ($ids_placeholder)";
                            $stmt = mysqli_prepare($conn, $bulk_query);
                            mysqli_stmt_bind_param($stmt, str_repeat('i', count($selected_ids)), ...$selected_ids);

                            if (mysqli_stmt_execute($stmt)) {
                                $count = mysqli_stmt_affected_rows($stmt);
                                $message = "$count subscriber(s) deactivated successfully!";
                                $message_type = "success";
                            } else {
                                $message = "Error deactivating subscribers.";
                                $message_type = "error";
                            }
                        }
                    }
                }
                break;
        }
    }
}

// Handle search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = $search ? "WHERE email LIKE '%$search%'" : '';

// Pagination
$per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $per_page;

// Get total records for pagination
$total_query = "SELECT COUNT(*) as count FROM subscribers $where_clause";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $per_page);

// Get subscribers
$query = "SELECT * FROM subscribers $where_clause ORDER BY created_at DESC LIMIT $start_from, $per_page";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriber Management - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/subscribers.css">
    <script src="../js/common.js" defer></script>
        <style>
            /* Action Buttons Internal Styles */
            .action-buttons {
                display: flex;
                gap: 8px;
                justify-content: center;
                align-items: center;
            }
    
            .action-btn {
                background: none;
                border: none;
                padding: 8px;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.3s ease;
                font-size: 0.9rem;
                width: 32px;
                height: 32px;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
    
            .action-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }
    
            .toggle-btn {
                color: #2a4858;
                background-color: rgba(42, 72, 88, 0.1);
            }
    
            .toggle-btn:hover {
                background-color: rgba(42, 72, 88, 0.2);
                color: #1a3240;
            }
    
            .delete-btn {
                color: #c62828;
                background-color: rgba(198, 40, 40, 0.1);
            }
    
            .delete-btn:hover {
                background-color: rgba(198, 40, 40, 0.2);
                color: #a71e1e;
            }
    
            /* Bulk Actions Styles */
            .bulk-actions {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                padding: 1rem;
                background: linear-gradient(135deg, #f6f4f0 0%, #f2e8dc 100%);
                border-radius: 8px;
                border: 1px solid #d8c3a5;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }
    
            .bulk-select {
                display: flex;
                align-items: center;
                gap: 8px;
                font-weight: 600;
                color: #3a3026;
            }
    
            .bulk-controls {
                display: flex;
                align-items: center;
                gap: 12px;
            }
    
            .bulk-action-select {
                padding: 10px 14px;
                border: 2px solid #d8c3a5;
                border-radius: 6px;
                background: white;
                font-size: 0.9rem;
                color: #3a3026;
                font-weight: 500;
                transition: all 0.3s ease;
                min-width: 160px;
            }
    
            .bulk-action-select:focus {
                outline: none;
                border-color: #2a4858;
                box-shadow: 0 0 0 3px rgba(42, 72, 88, 0.1);
            }
    
            .bulk-apply-btn {
                background: linear-gradient(135deg, #2a4858 0%, #1a3240 100%);
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                min-width: 100px;
                box-shadow: 0 2px 4px rgba(42, 72, 88, 0.3);
            }
    
            .bulk-apply-btn:hover:not(:disabled) {
                background: linear-gradient(135deg, #1a3240 0%, #0f1e28 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(42, 72, 88, 0.4);
            }
    
            .bulk-apply-btn:disabled {
                background: #ccc;
                cursor: not-allowed;
                transform: none;
                box-shadow: none;
                opacity: 0.6;
            }
    
            /* Alert Messages */
            .alert {
                padding: 14px 18px;
                border-radius: 8px;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 12px;
                transition: all 0.3s ease;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
    
            .alert i {
                font-size: 1.1rem;
            }
    
            .alert-success {
                background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                color: #1b5e20;
                border-left: 4px solid #4caf50;
            }
    
            .alert-error {
                background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                color: #b71c1c;
                border-left: 4px solid #f44336;
            }
    
            /* Modal Styles */
            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(2px);
                animation: fadeIn 0.3s ease;
            }
    
            .modal-content {
                background: white;
                margin: 10% auto;
                padding: 0;
                border-radius: 12px;
                width: 90%;
                max-width: 500px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                animation: slideIn 0.3s ease;
                overflow: hidden;
            }
    
            .modal-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.5rem;
                background: linear-gradient(135deg, #2a4858 0%, #1a3240 100%);
                color: white;
            }
    
            .modal-header h3 {
                margin: 0;
                font-size: 1.2rem;
                font-weight: 600;
            }
    
            .close-modal {
                color: rgba(255, 255, 255, 0.8);
                font-size: 24px;
                font-weight: bold;
                cursor: pointer;
                line-height: 1;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.3s ease;
            }
    
            .close-modal:hover {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
            }
    
            .modal-body {
                padding: 2rem 1.5rem;
                font-size: 1rem;
                line-height: 1.5;
                color: #3a3026;
            }
    
            .modal-footer {
                display: flex;
                justify-content: flex-end;
                gap: 12px;
                padding: 1.5rem;
                background-color: #f8f9fa;
                border-top: 1px solid #e9ecef;
            }
    
            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                font-size: 0.9rem;
                transition: all 0.3s ease;
                min-width: 100px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
    
            .btn-secondary {
                background: #6c757d;
                color: white;
                box-shadow: 0 2px 4px rgba(108, 117, 125, 0.3);
            }
    
            .btn-secondary:hover {
                background: #5a6268;
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(108, 117, 125, 0.4);
            }
    
            .btn-danger {
                background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
                color: white;
                box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
            }
    
            .btn-danger:hover {
                background: linear-gradient(135deg, #c82333 0%, #a71e2a 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 8px rgba(220, 53, 69, 0.4);
            }
    
            /* Checkbox Styles */
            .select-all-checkbox,
            .subscriber-checkbox {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: #2a4858;
                transform: scale(1.1);
            }
    
            .select-all-checkbox:hover,
            .subscriber-checkbox:hover {
                transform: scale(1.2);
            }
    
            /* Table Enhancements */
            table th:first-child,
            table td:first-child {
                text-align: center;
                width: 50px;
            }
    
            table th:last-child,
            table td:last-child {
                text-align: center;
                width: 120px;
            }
    
            /* Status Badge Enhancements */
            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                display: inline-block;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            }
    
            .status-badge.active {
                background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
                color: #1b5e20;
                border: 1px solid #4caf50;
            }
    
            .status-badge.inactive {
                background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
                color: #b71c1c;
                border: 1px solid #f44336;
            }
    
            /* Animations */
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
    
            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateY(-50px) scale(0.9);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }
    
            /* Hover Effects for Table Rows */
            tbody tr {
                transition: all 0.3s ease;
            }
    
            tbody tr:hover {
                background-color: rgba(42, 72, 88, 0.05) !important;
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
    
            /* Loading States */
            .btn:active {
                transform: translateY(0);
                box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            }
    
            /* Responsive Enhancements */
            @media (max-width: 768px) {
                .bulk-actions {
                    flex-direction: column;
                    gap: 1rem;
                    align-items: stretch;
                }
    
                .bulk-controls {
                    justify-content: center;
                    flex-wrap: wrap;
                }
    
                .action-buttons {
                    flex-direction: row;
                    gap: 6px;
                }
    
                .action-btn {
                    width: 28px;
                    height: 28px;
                    font-size: 0.8rem;
                }
    
                .modal-content {
                    margin: 5% auto;
                    width: 95%;
                }
    
                .modal-footer {
                    flex-direction: column;
                    gap: 10px;
                }
    
                .btn {
                    width: 100%;
                    margin: 0;
                }
    
                .bulk-action-select {
                    min-width: auto;
                    width: 100%;
                }
            }
    
            @media (max-width: 480px) {
                .action-buttons {
                    flex-direction: column;
                    gap: 4px;
                }
    
                .modal-body {
                    padding: 1.5rem 1rem;
                }
    
                .modal-header,
                .modal-footer {
                    padding: 1rem;
                }
            }
    
            /* Tooltip Styles */
            .action-btn[title]:hover::after {
                content: attr(title);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 4px 8px;
                border-radius: 4px;
                font-size: 0.75rem;
                white-space: nowrap;
                z-index: 1000;
                margin-bottom: 5px;
            }
    
            .action-btn[title]:hover::before {
                content: '';
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 4px solid transparent;
                border-top-color: rgba(0, 0, 0, 0.8);
                z-index: 1000;
                margin-bottom: 1px;
            }
    
            /* Focus States for Accessibility */
            .action-btn:focus,
            .bulk-apply-btn:focus,
            .btn:focus {
                outline: 2px solid #2a4858;
                outline-offset: 2px;
            }
    
            .select-all-checkbox:focus,
            .subscriber-checkbox:focus {
                outline: 2px solid #2a4858;
                outline-offset: 1px;
            }
        </style>
</head>
<body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Include header template -->
        <?php include_once './includes/header.php'; ?>
        <div class="newcont">
            <div class="container">
                <header>
                    <h1>Subscriber Management</h1>
                    <button class="download-btn" id="downloadBtn">
                        <i class="fas fa-download"></i> Download Subscribers
                    </button>
                </header>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $message_type; ?>" id="alertMessage">
                        <i class="fas fa-<?php echo $message_type === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <div class="search-container">
                    <form method="GET" action="">
                        <input type="text" name="search" class="search-input"
                            placeholder="Search subscribers..."
                            value="<?php echo htmlspecialchars($search); ?>">
                    </form>
                </div>

                <form method="POST" id="bulkActionForm">

                    <table id="subscribersTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Subscription Date</th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM subscribers $where_clause ORDER BY created_at DESC LIMIT $start_from, $per_page";
                            $result = mysqli_query($conn, $query);
                            if (mysqli_num_rows($result) > 0) {
                                $count = $start_from + 1; // Start count from the current page's offset + 1
                                while($row = mysqli_fetch_assoc($result)) {
                                    // Handle boolean status (1 = active, 0 = inactive)
                                    $status_class = $row['status'] == 1 ? 'active' : 'inactive';
                                    $status_text = $row['status'] == 1 ? 'Active' : 'Inactive';
                                    $toggle_text = $row['status'] == 1 ? 'Deactivate' : 'Activate';
                                    $toggle_icon = $row['status'] == 1 ? 'fa-toggle-off' : 'fa-toggle-on';

                                    echo "<tr>
                                        <td>". $count++ ."</td>
                                        <td>" . htmlspecialchars($row['email']) . "</td>
                                        <td>" . date('M d, Y', strtotime($row['created_at'])) . "</td>
                                        <td><span class='status-badge {$status_class}'>{$status_text}</span></td>
                                        <td class='action-buttons'>
                                            <button type='button' class='action-btn toggle-btn'
                                                onclick='toggleStatus({$row['id']})'
                                                title='{$toggle_text}'>
                                                <i class='fas {$toggle_icon}'></i>
                                            </button>
                                            <button type='button' class='action-btn delete-btn'
                                                onclick='deleteSubscriber({$row['id']}, \"" . htmlspecialchars($row['email']) . "\")'
                                                title='Delete Subscriber'>
                                                <i class='fas fa-trash'></i>
                                            </button>
                                        </td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>No subscribers found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </form>

                <div class="pagination">
                    <?php if($total_pages > 1): ?>
                        <?php if($page > 1): ?>
                            <a href="?page=<?php echo ($page-1); ?>&search=<?php echo urlencode($search); ?>" class="pagination-btn">Previous</a>
                        <?php endif; ?>

                        <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
                            class="pagination-btn <?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>

                        <?php if($page < $total_pages): ?>
                            <a href="?page=<?php echo ($page+1); ?>&search=<?php echo urlencode($search); ?>" class="pagination-btn">Next</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Confirm Action</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Are you sure you want to perform this action?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBtn">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // Auto-hide alert messages
        document.addEventListener('DOMContentLoaded', function() {
            const alertMessage = document.getElementById('alertMessage');
            if (alertMessage) {
                setTimeout(() => {
                    alertMessage.style.opacity = '0';
                    setTimeout(() => {
                        alertMessage.style.display = 'none';
                    }, 300);
                }, 5000);
            }
        });

        // Individual action functions
        function toggleStatus(subscriberId) {
            showConfirmModal(
                'Toggle Status',
                'Are you sure you want to change this subscriber\'s status?',
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="toggle_status">
                        <input type="hidden" name="subscriber_id" value="${subscriberId}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        function deleteSubscriber(subscriberId, email) {
            showConfirmModal(
                'Delete Subscriber',
                `Are you sure you want to delete subscriber "${email}"? This action cannot be undone.`,
                () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="action" value="delete_subscriber">
                        <input type="hidden" name="subscriber_id" value="${subscriberId}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }

        // Modal functions
        function showConfirmModal(title, message, onConfirm) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('confirmModal').style.display = 'block';

            document.getElementById('confirmBtn').onclick = () => {
                closeModal();
                onConfirm();
            };
        }

        function closeModal() {
            document.getElementById('confirmModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('confirmModal');
            if (event.target === modal) {
                closeModal();
            }
        });

        // Download subscribers functionality
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // Create CSV content
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "ID,Email,Status,Subscription Date\n";

            // Get table data
            const table = document.getElementById('subscribersTable');
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length > 1 && !cells[1].textContent.includes('No subscribers found')) {
                    const rowData = [];
                    // Skip checkbox column (index 0), get ID (1), Email (2), Date (3), Status (4)
                    rowData.push('"' + cells[1].textContent.trim() + '"'); // ID
                    rowData.push('"' + cells[2].textContent.trim() + '"'); // Email
                    rowData.push('"' + cells[4].querySelector('.status-badge').textContent.trim() + '"'); // Status
                    rowData.push('"' + cells[3].textContent.trim() + '"'); // Date
                    csvContent += rowData.join(",") + "\n";
                }
            });

            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "subscribers_" + new Date().toISOString().split('T')[0] + ".csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    </script>
</body>
</html>
