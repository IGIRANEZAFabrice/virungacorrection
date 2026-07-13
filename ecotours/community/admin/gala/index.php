<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Get gala hero content
$hero_query = "SELECT * FROM gala_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

// Get activities count
$activities_query = "SELECT COUNT(*) as count FROM gala_activities";
$activities_result = mysqli_query($conn, $activities_query);
$activities_count = mysqli_fetch_assoc($activities_result)['count'];

// Get importance cards count
$importance_query = "SELECT COUNT(*) as count FROM gala_importance";
$importance_result = mysqli_query($conn, $importance_query);
$importance_count = mysqli_fetch_assoc($importance_result)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gala Dinner Management - Community Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/management.css">
    <style>
        .gala-management {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .gala-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .gala-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .gala-card-icon {
            font-size: 2.5rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }
        
        .gala-card h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-dark);
        }
        
        .gala-card p {
            color: var(--text-medium);
            margin: 0 0 1.5rem 0;
            font-size: 0.9rem;
        }
        
        .gala-card-count {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }
        
        .btn-group {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
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
                    <h1>Gala Dinner Management</h1>
                    <p>Manage the Gala Local Dinner & Culture Experience page content</p>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <div class="gala-management">
                    <!-- Hero Section Card -->
                    <div class="gala-card">
                        <div class="gala-card-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3>Hero Section</h3>
                        <p>Edit the hero title, subtitle, description, and introduction text</p>
                        <div class="btn-group">
                            <a href="edit-hero.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    
                    <!-- Activities Card -->
                    <div class="gala-card">
                        <div class="gala-card-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h3>Afternoon Activities</h3>
                        <p>Manage the afternoon cultural activities</p>
                        <div class="gala-card-count"><?php echo $activities_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-activities.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Manage
                            </a>
                        </div>
                    </div>
                    
                    <!-- Importance Cards -->
                    <div class="gala-card">
                        <div class="gala-card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3>Why This Matters</h3>
                        <p>Manage the importance and impact cards</p>
                        <div class="gala-card-count"><?php echo $importance_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-importance.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Manage
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

