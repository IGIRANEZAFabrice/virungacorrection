<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM gala_activities WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        $success = 'Activity deleted successfully!';
    } else {
        $error = 'Error deleting activity: ' . mysqli_error($conn);
    }
}

// Handle add new activity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = mysqli_real_escape_string($conn, $_POST['activity_title']);
    $description = mysqli_real_escape_string($conn, $_POST['activity_description']);
    $image = mysqli_real_escape_string($conn, $_POST['activity_image']);
    
    // Get max order
    $order_query = "SELECT MAX(display_order) as max_order FROM gala_activities";
    $order_result = mysqli_query($conn, $order_query);
    $order_data = mysqli_fetch_assoc($order_result);
    $order = ($order_data['max_order'] ?? 0) + 1;
    
    $insert_query = "INSERT INTO gala_activities (activity_title, activity_description, activity_image, display_order) 
                     VALUES ('$title', '$description', '$image', $order)";
    
    if (mysqli_query($conn, $insert_query)) {
        $success = 'Activity added successfully!';
    } else {
        $error = 'Error adding activity: ' . mysqli_error($conn);
    }
}

// Handle update activity
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['activity_id']);
    $title = mysqli_real_escape_string($conn, $_POST['activity_title']);
    $description = mysqli_real_escape_string($conn, $_POST['activity_description']);
    $image = mysqli_real_escape_string($conn, $_POST['activity_image']);
    
    $update_query = "UPDATE gala_activities SET 
                     activity_title = '$title',
                     activity_description = '$description',
                     activity_image = '$image'
                     WHERE id = $id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = 'Activity updated successfully!';
    } else {
        $error = 'Error updating activity: ' . mysqli_error($conn);
    }
}

// Get all activities
$activities_query = "SELECT * FROM gala_activities ORDER BY display_order ASC";
$activities_result = mysqli_query($conn, $activities_query);
$activities = [];
while ($row = mysqli_fetch_assoc($activities_result)) {
    $activities[] = $row;
}

$edit_id = $_GET['edit'] ?? null;
$edit_activity = null;
if ($edit_id) {
    foreach ($activities as $activity) {
        if ($activity['id'] == $edit_id) {
            $edit_activity = $activity;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Activities - Gala Dinner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.95rem;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(45, 122, 62, 0.1);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2d7a3e;
        }
        
        .btn-secondary {
            background-color: var(--neutral-light);
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .activities-list {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .activity-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--neutral-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-dark);
        }
        
        .activity-info p {
            margin: 0;
            color: var(--text-medium);
            font-size: 0.9rem;
        }
        
        .activity-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <?php include '../includes/topbar.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <a href="index.php" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Gala Management
                    </a>
                    <h1><?php echo $edit_activity ? 'Edit Activity' : 'Add New Activity'; ?></h1>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="form-container">
                    <input type="hidden" name="action" value="<?php echo $edit_activity ? 'update' : 'add'; ?>">
                    <?php if ($edit_activity): ?>
                        <input type="hidden" name="activity_id" value="<?php echo $edit_activity['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="activity_title">Activity Title</label>
                        <input type="text" id="activity_title" name="activity_title" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['activity_title']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="activity_description">Activity Description</label>
                        <textarea id="activity_description" name="activity_description" required><?php echo $edit_activity ? htmlspecialchars($edit_activity['activity_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="activity_image">Image Path</label>
                        <input type="text" id="activity_image" name="activity_image" placeholder="../images/gala/1.jpg" value="<?php echo $edit_activity ? htmlspecialchars($edit_activity['activity_image']) : ''; ?>">
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $edit_activity ? 'Update Activity' : 'Add Activity'; ?>
                        </button>
                        <?php if ($edit_activity): ?>
                            <a href="edit-activities.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
                
                <h2>Activities List</h2>
                <div class="activities-list">
                    <?php if (empty($activities)): ?>
                        <div style="padding: 2rem; text-align: center; color: var(--text-medium);">
                            No activities yet. Add one to get started!
                        </div>
                    <?php else: ?>
                        <?php foreach ($activities as $activity): ?>
                            <div class="activity-item">
                                <div class="activity-info">
                                    <h4><?php echo htmlspecialchars($activity['activity_title']); ?></h4>
                                    <p><?php echo htmlspecialchars(substr($activity['activity_description'], 0, 100)) . '...'; ?></p>
                                </div>
                                <div class="activity-actions">
                                    <a href="?edit=<?php echo $activity['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete=<?php echo $activity['id']; ?>" class="btn btn-danger" style="padding: 0.5rem 1rem;" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

