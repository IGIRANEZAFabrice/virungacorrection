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

// Get philanthropy content counts
$hero_query = "SELECT COUNT(*) as count FROM philanthropy_hero";
$hero_result = mysqli_query($conn, $hero_query);
$hero_count = mysqli_fetch_assoc($hero_result)['count'];

$approach_query = "SELECT COUNT(*) as count FROM philanthropy_approach";
$approach_result = mysqli_query($conn, $approach_query);
$approach_count = mysqli_fetch_assoc($approach_result)['count'];

$regenerative_query = "SELECT COUNT(*) as count FROM philanthropy_regenerative";
$regenerative_result = mysqli_query($conn, $regenerative_query);
$regenerative_count = mysqli_fetch_assoc($regenerative_result)['count'];

$focus_query = "SELECT COUNT(*) as count FROM philanthropy_focus_areas";
$focus_result = mysqli_query($conn, $focus_query);
$focus_count = mysqli_fetch_assoc($focus_result)['count'];

$engagement_query = "SELECT COUNT(*) as count FROM philanthropy_engagement";
$engagement_result = mysqli_query($conn, $engagement_query);
$engagement_count = mysqli_fetch_assoc($engagement_result)['count'];

$stories_query = "SELECT COUNT(*) as count FROM philanthropy_stories";
$stories_result = mysqli_query($conn, $stories_query);
$stories_count = mysqli_fetch_assoc($stories_result)['count'];

$partnerships_query = "SELECT COUNT(*) as count FROM philanthropy_partnerships";
$partnerships_result = mysqli_query($conn, $partnerships_query);
$partnerships_count = mysqli_fetch_assoc($partnerships_result)['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Philanthropy Management - Community Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .philanthropy-management {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .philanthropy-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .philanthropy-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .card-icon {
            font-size: 2.5rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }
        
        .philanthropy-card h3 {
            margin: 0 0 0.5rem 0;
            color: var(--text-dark);
        }
        
        .philanthropy-card p {
            color: var(--text-medium);
            margin: 0 0 1.5rem 0;
            font-size: 0.9rem;
        }
        
        .card-count {
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
            background-color: var(--primary-green-dark);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-color-dark);
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 2rem;
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
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="content-area">
                <div class="page-header">
                    <h1>Philanthropy & Community Impact</h1>
                    <p>Manage all content for the philanthropy page</p>
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

                <div class="philanthropy-management">
                    <!-- Hero Section -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <h3>Hero Section</h3>
                        <p>Edit hero title, subtitle, description, and stats</p>
                        <div class="card-count"><?php echo $hero_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-hero.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Approach Cards -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-coins"></i>
                        </div>
                        <h3>Our Approach</h3>
                        <p>Manage CBT approach cards and descriptions</p>
                        <div class="card-count"><?php echo $approach_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-approach.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Regenerative Tourism -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>Regenerative Tourism</h3>
                        <p>Manage regenerative tourism principles</p>
                        <div class="card-count"><?php echo $regenerative_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-regenerative.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Focus Areas -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h3>Focus Areas</h3>
                        <p>Manage key focus areas and impact items</p>
                        <div class="card-count"><?php echo $focus_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-focus-areas.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Visitor Engagement -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Visitor Engagement</h3>
                        <p>Manage visitor engagement activities</p>
                        <div class="card-count"><?php echo $engagement_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-engagement.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Impact Stories -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Impact Stories</h3>
                        <p>Manage community impact stories</p>
                        <div class="card-count"><?php echo $stories_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-stories.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <!-- Partnerships -->
                    <div class="philanthropy-card">
                        <div class="card-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3>Partnerships</h3>
                        <p>Manage partnership opportunities</p>
                        <div class="card-count"><?php echo $partnerships_count; ?></div>
                        <div class="btn-group">
                            <a href="edit-partnerships.php" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

