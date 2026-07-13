<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Log admin action - only if admin_logs table exists
$admin_id = $_SESSION['community_admin_id'];
$action = "Viewed activities list";

// Check if admin_logs table exists
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
if (mysqli_num_rows($table_check) > 0) {
    $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/index.php', NOW())";
    mysqli_query($conn, $log_query);
}

// Get all activities
$query = "SELECT * FROM community_activities ORDER BY display_order ASC, created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activities - Community Admin</title>
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
        .activities-container {
            margin: 20px 0;
        }
        
        .activities-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .activities-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .activities-table th, 
        .activities-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .activities-table th {
            background-color: #f5f5f5;
            font-weight: 600;
        }
        
        .activities-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
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
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .view-btn {
            background-color: #e3f2fd;
            color: #0d6efd;
        }
        
        .edit-btn {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .delete-btn {
            background-color: #f8d7da;
            color: #dc3545;
        }
        
        .add-btn {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .activities-table {
                display: block;
                overflow-x: auto;
            }
            
            .activities-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 5px;
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
                    <h1>Activities</h1>
                    <p>Manage community activities</p>
                </div>
                
                <div class="activities-container">
                    <div class="activities-header">
                        <h2>All Activities</h2>
                        <a href="create.php" class="add-btn">
                            <i class="fas fa-plus"></i> Add New Activity
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="activities-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Display Order</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($row['image'])): ?>
                                                    <img src="../../uploads/activities/<?php echo $row['image']; ?>" alt="<?php echo $row['title']; ?>" class="thumbnail">
                                                <?php else: ?>
                                                    <div class="no-image">No Image</div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                                            <td>
                                                <?php 
                                                $status_class = '';
                                                switch ($row['status']) {
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
                                                <span class="status-badge <?php echo $status_class; ?>">
                                                    <?php echo ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $row['display_order']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="action-btn view-btn">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="action-btn edit-btn">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="handlers/delete-handler.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this activity?');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center;">No activities found. <a href="create.php">Create one</a>.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/admin.js"></script>
</body>
</html>