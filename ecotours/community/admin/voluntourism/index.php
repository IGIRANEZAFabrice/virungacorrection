<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $table = isset($_GET['table']) ? $_GET['table'] : '';
    
    if (in_array($table, ['voluntourism_hero', 'voluntourism_highlights', 'voluntourism_activities', 'voluntourism_programs', 'voluntourism_table_rows', 'voluntourism_faq'])) {
        $delete_query = "DELETE FROM $table WHERE id = $id";
        if (mysqli_query($conn, $delete_query)) {
            $success_message = "Record deleted successfully!";
        } else {
            $error_message = "Error deleting record: " . mysqli_error($conn);
        }
    }
}

// Get all voluntourism data
$hero_query = "SELECT * FROM voluntourism_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

$highlights_query = "SELECT * FROM voluntourism_highlights ORDER BY display_order";
$highlights_result = mysqli_query($conn, $highlights_query);

$activities_query = "SELECT * FROM voluntourism_activities ORDER BY display_order";
$activities_result = mysqli_query($conn, $activities_query);

$programs_query = "SELECT * FROM voluntourism_programs ORDER BY display_order";
$programs_result = mysqli_query($conn, $programs_query);

$table_rows_query = "SELECT * FROM voluntourism_table_rows ORDER BY display_order";
$table_rows_result = mysqli_query($conn, $table_rows_query);

$faq_query = "SELECT * FROM voluntourism_faq ORDER BY display_order";
$faq_result = mysqli_query($conn, $faq_query);

// Get How It Works data
$how_it_works_query = "SELECT * FROM voluntourism_how_it_works LIMIT 1";
$how_it_works_result = mysqli_query($conn, $how_it_works_query);
$how_it_works = mysqli_fetch_assoc($how_it_works_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voluntourism Management - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/management.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">

    <style>
        body { background-color: var(--neutral-light); }
        .admin-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .content-area { flex: 1; padding: 30px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { color: var(--primary-green); font-size: 28px; margin-bottom: 5px; }
        .page-header p { color: var(--text-medium); font-size: 14px; }
        .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 30px; }
        .dashboard-card { background: white; border-radius: 8px; padding: 20px; box-shadow: var(--shadow-md); transition: all 0.3s ease; }
        .dashboard-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .card-header h3 { color: var(--primary-green); font-size: 18px; margin: 0; }
        .card-icon { font-size: 24px; color: var(--primary-green); }
        .card-content { color: var(--text-medium); font-size: 14px; line-height: 1.6; }
        .card-actions { display: flex; gap: 10px; margin-top: 15px; }
        .btn { padding: 10px 16px; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.3s ease; }
        .btn-primary { background-color: var(--primary-green); color: white; }
        .btn-primary:hover { background-color: var(--accent-sage); }
        .btn-secondary { background-color: var(--neutral-beige); color: var(--text-dark); }
        .btn-secondary:hover { background-color: var(--accent-light-brown); }
        .success-message { background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
    </style>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .page-header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 5px solid #667eea;
        }

        .section-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
        }

        .section-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-header h3 i {
            color: #667eea;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 87, 108, 0.4);
        }

        .btn-danger {
            background-color: #f44336;
            color: white;
        }

        .btn-danger:hover {
            background-color: #da190b;
            transform: translateY(-2px);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px;
            text-align: left;
            font-weight: 600;
            border: none;
        }

        .data-table td {
            padding: 14px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .data-table tr:hover {
            background-color: #f8f9ff;
        }

        .alert {
            padding: 16px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left-color: #f44336;
        }
    </style>
</head>
<body class="admin-layout">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/topbar.php'; ?>
        
        <main class="admin-main">
            <header class="page-header">
                <h1><i class="fas fa-hands-helping"></i> Voluntourism Management</h1>
                <p>Manage all voluntourism page content and data</p>
            </header>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Hero Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-image"></i> Hero & Introduction</h3>
                    <div class="btn-group">
                        <a href="edit-hero.php" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <?php if ($hero): ?>
                    <table class="data-table">
                        <tr>
                            <td><strong>Hero Title:</strong> <?php echo htmlspecialchars($hero['hero_title']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Hero Subtitle:</strong> <?php echo htmlspecialchars($hero['hero_subtitle']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Intro Title:</strong> <?php echo htmlspecialchars($hero['intro_title']); ?></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Highlights Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-star"></i> Highlights (4 Cards)</h3>
                    <div class="btn-group">
                        <a href="edit-highlights.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add/Edit
                        </a>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($highlight = mysqli_fetch_assoc($highlights_result)): ?>
                            <tr>
                                <td><?php echo $highlight['display_order']; ?></td>
                                <td><?php echo htmlspecialchars($highlight['highlight_title']); ?></td>
                                <td><?php echo substr(htmlspecialchars($highlight['highlight_description']), 0, 50) . '...'; ?></td>
                                <td>
                                    <a href="edit-highlights.php?id=<?php echo $highlight['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Activities Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-tasks"></i> Activities (5 Cards)</h3>
                    <div class="btn-group">
                        <a href="edit-activities.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add/Edit
                        </a>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($activity = mysqli_fetch_assoc($activities_result)): ?>
                            <tr>
                                <td><?php echo $activity['display_order']; ?></td>
                                <td><?php echo htmlspecialchars($activity['activity_title']); ?></td>
                                <td><?php echo substr(htmlspecialchars($activity['activity_description']), 0, 50) . '...'; ?></td>
                                <td>
                                    <a href="edit-activities.php?id=<?php echo $activity['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Programs Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-project-diagram"></i> Programs (4 Programs)</h3>
                    <div class="btn-group">
                        <a href="edit-programs.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add/Edit
                        </a>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                            <tr>
                                <td><?php echo $program['display_order']; ?></td>
                                <td><?php echo htmlspecialchars($program['program_title']); ?></td>
                                <td><?php echo htmlspecialchars($program['program_category']); ?></td>
                                <td><?php echo htmlspecialchars($program['duration']); ?></td>
                                <td>
                                    <a href="edit-programs.php?id=<?php echo $program['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Table Rows Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-table"></i> Comparison Table Rows (5 Rows)</h3>
                    <div class="btn-group">
                        <a href="edit-table-rows.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add/Edit
                        </a>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($table_rows_result)): ?>
                            <tr>
                                <td><?php echo $row['display_order']; ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td>
                                    <a href="edit-table-rows.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- How It Works Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-cogs"></i> How It Works</h3>
                    <div class="btn-group">
                        <a href="edit-how-it-works.php" class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <?php if ($how_it_works): ?>
                    <table class="data-table">
                        <tr>
                            <td><strong>Section Title:</strong> <?php echo htmlspecialchars($how_it_works['section_title']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Process Image:</strong> <?php echo htmlspecialchars($how_it_works['process_image']); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Overlay Title:</strong> <?php echo htmlspecialchars($how_it_works['overlay_title']); ?></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>

            <!-- FAQ Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-question-circle"></i> FAQ (6 Questions)</h3>
                    <div class="btn-group">
                        <a href="edit-faq.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add/Edit
                        </a>
                    </div>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Question</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($faq = mysqli_fetch_assoc($faq_result)): ?>
                            <tr>
                                <td><?php echo $faq['display_order']; ?></td>
                                <td><?php echo htmlspecialchars(substr($faq['question'], 0, 60)) . '...'; ?></td>
                                <td>
                                    <a href="edit-faq.php?id=<?php echo $faq['id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 12px;">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

