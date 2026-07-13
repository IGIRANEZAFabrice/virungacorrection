<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Get activity details
$query = "SELECT * FROM community_activities WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header('Location: index.php');
    exit;
}

$activity = mysqli_fetch_assoc($result);

// Log admin action - only if admin_logs table exists
$admin_id = $_SESSION['community_admin_id'];
$action = "Viewed activity: " . $activity['title'];

// Check if admin_logs table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
if (mysqli_num_rows($table_check) > 0) {
    $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/view.php', NOW())";
    mysqli_query($conn, $log_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Activity - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <style>
        /* Admin container layout fixes */
        .admin-container {
            display: flex;
            position: relative;
            min-height: 100vh;
            width: 100%;
        }
        
        .admin-content {
            flex: 1;
            position: relative;
            z-index: 1;
            margin-left: 250px; /* Match sidebar width */
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }
        
        .admin-main {
            padding: 20px;
            background-color: #f8f9fa;
            min-height: calc(100vh - 60px); /* Adjust for topbar height */
        }
        
        /* Activity specific styles */
        .activity-container {
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            overflow: hidden;
        }
        
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .activity-title {
            margin: 0;
            font-size: 24px;
            color: #333;
            word-break: break-word;
        }
        
        .activity-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .activity-action-btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            white-space: nowrap;
        }
        
        .edit-btn {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .delete-btn {
            background-color: #f8d7da;
            color: #dc3545;
        }
        
        .back-btn {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .activity-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .activity-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        
        .meta-item {
            display: flex;
            flex-direction: column;
        }
        
        .meta-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .meta-value {
            font-size: 16px;
            font-weight: 500;
            color: #333;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-active {
            background-color: #e6f7e6;
            color: #28a745;
        }
        
        .status-inactive {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .activity-content {
            line-height: 1.6;
            color: #333;
            overflow-wrap: break-word;
        }
        
        @media (max-width: 992px) {
            .admin-content {
                margin-left: 0;
                width: 100%;
            }
            
            .admin-main {
                padding: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .activity-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .activity-meta {
                grid-template-columns: 1fr;
            }
            
            .activity-actions {
                flex-wrap: wrap;
                width: 100%;
            }
            
            .activity-action-btn {
                flex: 1;
                text-align: center;
                justify-content: center;
            }
            
            .activity-container {
                padding: 15px;
                margin: 10px 0;
            }
            
            .admin-main {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="admin-main">
                <div class="page-header">
                    <h1>View Activity</h1>
                    <p>Viewing details for activity</p>
                </div>
                
                <div class="activity-container">
                    <div class="activity-header">
                        <h2 class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></h2>
                        <div class="activity-actions">
                            <a href="index.php" class="activity-action-btn back-btn">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <a href="edit.php?id=<?php echo $activity['id']; ?>" class="activity-action-btn edit-btn">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="handlers/delete-handler.php?id=<?php echo $activity['id']; ?>" class="activity-action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this activity?');">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                    
                    <?php if (!empty($activity['image'])): ?>
                        <img src="../../uploads/activities/<?php echo $activity['image']; ?>" alt="<?php echo htmlspecialchars($activity['title']); ?>" class="activity-image">
                    <?php endif; ?>
                    
                    <div class="activity-meta">
                        <div class="meta-item">
                            <span class="meta-label">Status</span>
                            <?php 
                            $status_class = '';
                            switch ($activity['status']) {
                                case 'active':
                                    $status_class = 'status-active';
                                    break;
                                case 'inactive':
                                    $status_class = 'status-inactive';
                                    break;
                                case 'draft':
                                    $status_class = 'status-draft';
                                    break;
                            }
                            ?>
                            <span class="meta-value">
                                <span class="status-badge <?php echo $status_class; ?>">
                                    <?php echo ucfirst($activity['status']); ?>
                                </span>
                            </span>
                        </div>
                        
                        <div class="meta-item">
                            <span class="meta-label">Display Order</span>
                            <span class="meta-value"><?php echo $activity['display_order']; ?></span>
                        </div>
                        
                        <div class="meta-item">
                            <span class="meta-label">Created</span>
                            <span class="meta-value"><?php echo date('M d, Y', strtotime($activity['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <div class="activity-content">
                        <?php echo nl2br(htmlspecialchars($activity['content'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>