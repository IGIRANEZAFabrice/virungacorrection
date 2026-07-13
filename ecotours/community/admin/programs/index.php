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
    $program_id = (int)$_GET['id'];
    $delete_query = "DELETE FROM community_programs WHERE id = $program_id";
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Program deleted successfully!";
    } else {
        $error_message = "Error deleting program: " . mysqli_error($conn);
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_programs'])) {
    $selected_programs = $_POST['selected_programs'];
    $action = $_POST['bulk_action'];
    
    if ($action === 'delete') {
        $ids = implode(',', array_map('intval', $selected_programs));
        $bulk_delete_query = "DELETE FROM community_programs WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_delete_query)) {
            $success_message = count($selected_programs) . " programs deleted successfully!";
        }
    } elseif ($action === 'activate') {
        $ids = implode(',', array_map('intval', $selected_programs));
        $bulk_update_query = "UPDATE community_programs SET status = 'active' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_programs) . " programs activated successfully!";
        }
    } elseif ($action === 'deactivate') {
        $ids = implode(',', array_map('intval', $selected_programs));
        $bulk_update_query = "UPDATE community_programs SET status = 'planned' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_programs) . " programs deactivated successfully!";
        }
    }
}

// Get filter parameters
$country_filter = isset($_GET['country']) ? mysqli_real_escape_string($conn, $_GET['country']) : '';
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$where_conditions = ["1=1"];

if ($country_filter) {
    $where_conditions[] = "country = '$country_filter'";
}

if ($category_filter) {
    $where_conditions[] = "category = '$category_filter'";
}

if ($status_filter) {
    $where_conditions[] = "status = '$status_filter'";
}

if ($search_query) {
    $where_conditions[] = "(title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR location LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_programs = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_programs / $per_page);

// Get programs
$programs_query = "SELECT * FROM community_programs WHERE $where_clause ORDER BY created_at DESC LIMIT $per_page OFFSET $offset";
$programs_result = mysqli_query($conn, $programs_query);

// Get categories for filter
$categories_query = "SELECT DISTINCT category FROM community_programs ORDER BY category";
$categories_result = mysqli_query($conn, $categories_query);

// Get countries for filter
$countries = ['rwanda', 'uganda', 'congo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programs Management - Community Admin</title>
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
                        <h1><i class="fas fa-project-diagram"></i> Programs Management</h1>
                        <p>Manage community programs, track impact, and monitor progress</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add New Program
                        </a>
                        <a href="export.php" class="btn btn-outline">
                            <i class="fas fa-download"></i>
                            Export Data
                        </a>
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
                                           placeholder="Search programs...">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="country">Country</label>
                                    <select id="country" name="country">
                                        <option value="">All Countries</option>
                                        <?php foreach ($countries as $country): ?>
                                            <option value="<?php echo $country; ?>" 
                                                    <?php echo $country_filter === $country ? 'selected' : ''; ?>>
                                                <?php echo ucfirst($country); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                
                                <div class="filter-group">
                                    <label for="category">Category</label>
                                    <select id="category" name="category">
                                        <option value="">All Categories</option>
                                        <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                            <option value="<?php echo htmlspecialchars($category['category']); ?>" 
                                                    <?php echo $category_filter === $category['category'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['category']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="filter-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                        <option value="planned" <?php echo $status_filter === 'planned' ? 'selected' : ''; ?>>Planned</option>
                                        <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Programs Table -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Programs (<?php echo $total_programs; ?>)</h3>
                        </div>
                        <div class="table-actions">
                            <form method="POST" class="bulk-actions-form" id="bulkActionsForm">
                                <select name="bulk_action" id="bulkAction">
                                    <option value="">Bulk Actions</option>
                                    <option value="activate">Activate</option>
                                    <option value="deactivate">Deactivate</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-outline btn-sm" id="applyBulkAction" disabled>
                                    Apply
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <?php if (mysqli_num_rows($programs_result) > 0): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Program</th>
                                        <th>Country</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Beneficiaries</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_programs[]" 
                                                       value="<?php echo $program['id']; ?>" 
                                                       class="program-checkbox">
                                            </td>
                                            <td>
                                                <div class="program-info">
                                                    <div class="program-image">
                                                        <img src="../../assets/images/programs/<?php echo htmlspecialchars($program['image']); ?>" 
                                                             alt="<?php echo htmlspecialchars($program['title']); ?>">
                                                    </div>
                                                    <div class="program-details">
                                                        <h4><?php echo htmlspecialchars($program['title']); ?></h4>
                                                        <p><?php echo htmlspecialchars(substr($program['short_description'], 0, 100)); ?>...</p>
                                                        <?php if ($program['featured']): ?>
                                                            <span class="featured-badge">
                                                                <i class="fas fa-star"></i>
                                                                Featured
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="country-badge country-<?php echo $program['country']; ?>">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <?php echo ucfirst($program['country']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="category-tag">
                                                    <?php echo htmlspecialchars($program['category']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $program['status']; ?>">
                                                    <?php echo ucfirst($program['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="beneficiaries-count">
                                                    <i class="fas fa-users"></i>
                                                    <?php echo number_format($program['beneficiaries']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="date-info">
                                                    <?php echo date('M j, Y', strtotime($program['created_at'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="../../program-detail.php?slug=<?php echo htmlspecialchars($program['slug']); ?>" 
                                                       target="_blank" class="btn-action btn-view" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $program['id']; ?>" 
                                                       class="btn-action btn-edit" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?php echo $program['id']; ?>" 
                                                       class="btn-action btn-delete" title="Delete"
                                                       data-confirm="Are you sure you want to delete this program?">
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
                                    <i class="fas fa-project-diagram"></i>
                                </div>
                                <h3>No Programs Found</h3>
                                <p>No programs match your current filters. Try adjusting your search criteria or create a new program.</p>
                                <a href="create.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Create First Program
                                </a>
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
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
