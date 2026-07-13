<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_policy':
                $policy_content = $_POST['policy_content'];
                $last_updated = date('Y-m-d H:i:s');
                
                // Update privacy policy in database
                $stmt = $pdo->prepare("UPDATE privacy_policy SET content = ?, last_updated = ? WHERE id = 1");
                $stmt->execute([$policy_content, $last_updated]);
                
                $success_message = "Privacy policy updated successfully!";
                break;
                
            case 'delete_request':
                $request_id = $_POST['request_id'];
                $stmt = $pdo->prepare("DELETE FROM privacy_requests WHERE id = ?");
                $stmt->execute([$request_id]);
                
                $success_message = "Privacy request deleted successfully!";
                break;
                
            case 'update_request_status':
                $request_id = $_POST['request_id'];
                $status = $_POST['status'];
                $response = $_POST['response'] ?? '';
                
                $stmt = $pdo->prepare("UPDATE privacy_requests SET status = ?, admin_response = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$status, $response, $request_id]);
                
                $success_message = "Request status updated successfully!";
                break;
        }
    }
}

// Check if tables exist and initialize variables
$privacy_policy = null;
$privacy_requests = [];
$stats = [
    'total_requests' => 0,
    'pending_requests' => 0,
    'completed_requests' => 0,
    'data_subjects' => 0
];
$tables_exist = false;

try {
    // Check if privacy tables exist
    $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_policy'");
    $privacy_policy_exists = $stmt->rowCount() > 0;

    $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_requests'");
    $privacy_requests_exists = $stmt->rowCount() > 0;

    $tables_exist = $privacy_policy_exists && $privacy_requests_exists;

    if ($tables_exist) {
        // Fetch current privacy policy
        $stmt = $pdo->query("SELECT * FROM privacy_policy WHERE id = 1");
        $privacy_policy = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch privacy requests
        $stmt = $pdo->query("SELECT * FROM privacy_requests ORDER BY created_at DESC");
        $privacy_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get statistics
        $stats = [
            'total_requests' => $pdo->query("SELECT COUNT(*) FROM privacy_requests")->fetchColumn(),
            'pending_requests' => $pdo->query("SELECT COUNT(*) FROM privacy_requests WHERE status = 'pending'")->fetchColumn(),
            'completed_requests' => $pdo->query("SELECT COUNT(*) FROM privacy_requests WHERE status = 'completed'")->fetchColumn(),
            'data_subjects' => $pdo->query("SELECT COUNT(DISTINCT email) FROM privacy_requests")->fetchColumn()
        ];
    }
} catch (PDOException $e) {
    $error_message = "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Management - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/privacy-management.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        .newcont {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include_once './includes/sidebar.php'; ?>
        
        <main class="main-content">
            <!-- Include header template -->
            <?php include_once './includes/header.php'; ?>

            <div class="newcont">

            <div class="content-header">
                <h1><i class="fas fa-shield-alt"></i> Privacy Management</h1>
                <p>Manage privacy policy and data protection requests</p>
            </div>

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

            <?php if (!$tables_exist): ?>
                <div class="alert alert-error">
                    <i class="fas fa-database"></i>
                    Privacy management tables are not set up. Please run the <a href="./privacy_setup.php">setup process</a> first.
                </div>
            <?php endif; ?>

            <?php if ($tables_exist): ?>
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-shield"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['total_requests']; ?></h3>
                        <p>Total Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['pending_requests']; ?></h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['completed_requests']; ?></h3>
                        <p>Completed Requests</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo $stats['data_subjects']; ?></h3>
                        <p>Data Subjects</p>
                    </div>
                </div>
            </div>

            <!-- Privacy Policy Management -->
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-file-contract"></i> Privacy Policy Management</h2>
                    <button class="btn btn-primary" onclick="togglePolicyEditor()">
                        <i class="fas fa-edit"></i> Edit Policy
                    </button>
                </div>
                
                <div class="policy-info">
                    <p><strong>Last Updated:</strong> 
                        <?php echo $privacy_policy ? date('F j, Y g:i A', strtotime($privacy_policy['last_updated'])) : 'Never'; ?>
                    </p>
                </div>

                <div id="policy-editor" class="policy-editor" style="display: none;">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="update_policy">
                        <div class="form-group">
                            <label for="policy_content">Privacy Policy Content:</label>
                            <textarea id="policy_content" name="policy_content" rows="20" class="form-control">
<?php echo htmlspecialchars($privacy_policy['content'] ?? ''); ?>
                            </textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Save Policy
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="togglePolicyEditor()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Privacy Requests Management -->
            <div class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-user-shield"></i> Privacy Requests</h2>
                    <div class="filter-controls">
                        <select id="status-filter" onchange="filterRequests()">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="requests-table-container">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($privacy_requests as $request): ?>
                            <tr data-status="<?php echo $request['status']; ?>">
                                <td><?php echo $request['id']; ?></td>
                                <td>
                                    <span class="request-type <?php echo $request['request_type']; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $request['request_type'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($request['email']); ?></td>
                                <td><?php echo htmlspecialchars(substr($request['subject'], 0, 50)) . '...'; ?></td>
                                <td>
                                    <span class="status-badge <?php echo $request['status']; ?>">
                                        <?php echo ucfirst($request['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($request['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn btn-sm btn-info" onclick="viewRequest(<?php echo $request['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="updateRequestStatus(<?php echo $request['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteRequest(<?php echo $request['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div id="request-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Privacy Request Details</h3>
                <span class="close" onclick="closeModal('request-modal')">&times;</span>
            </div>
            <div class="modal-body" id="request-details">
                <!-- Request details will be loaded here -->
            </div>
        </div>
    </div>

    <div id="status-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Update Request Status</h3>
                <span class="close" onclick="closeModal('status-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <form id="status-form" method="POST" action="">
                    <input type="hidden" name="action" value="update_request_status">
                    <input type="hidden" name="request_id" id="status-request-id">
                    
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="response">Admin Response:</label>
                        <textarea name="response" id="response" rows="4" class="form-control" 
                                  placeholder="Enter your response to the data subject..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="closeModal('status-modal')">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../js/common.js"></script>
    <script src="../js/privacy-management.js"></script>
</body>
</html>
