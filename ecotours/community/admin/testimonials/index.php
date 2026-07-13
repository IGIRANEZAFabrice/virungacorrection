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
    $testimonial_id = (int)$_GET['id'];
    $delete_query = "DELETE FROM community_testimonials WHERE id = $testimonial_id";
    if (mysqli_query($conn, $delete_query)) {
        $success_message = "Testimonial deleted successfully!";
    } else {
        $error_message = "Error deleting testimonial: " . mysqli_error($conn);
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_testimonials'])) {
    $selected_testimonials = $_POST['selected_testimonials'];
    $action = $_POST['bulk_action'];
    
    if ($action === 'delete') {
        $ids = implode(',', array_map('intval', $selected_testimonials));
        $bulk_delete_query = "DELETE FROM community_testimonials WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_delete_query)) {
            $success_message = count($selected_testimonials) . " testimonials deleted successfully!";
        }
    } elseif ($action === 'activate') {
        $ids = implode(',', array_map('intval', $selected_testimonials));
        $bulk_update_query = "UPDATE community_testimonials SET status = 'active' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_testimonials) . " testimonials activated successfully!";
        }
    } elseif ($action === 'deactivate') {
        $ids = implode(',', array_map('intval', $selected_testimonials));
        $bulk_update_query = "UPDATE community_testimonials SET status = 'inactive' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_testimonials) . " testimonials deactivated successfully!";
        }
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$program_filter = isset($_GET['program']) ? (int)$_GET['program'] : 0;
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$where_conditions = ["1=1"];

if ($status_filter) {
    $where_conditions[] = "t.status = '$status_filter'";
}

if ($program_filter) {
    $where_conditions[] = "t.program_id = $program_filter";
}

if ($search_query) {
    $where_conditions[] = "(t.name LIKE '%$search_query%' OR t.message LIKE '%$search_query%' OR t.organization LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_testimonials t WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_testimonials = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_testimonials / $per_page);

// Get testimonials with program information
$testimonials_query = "SELECT t.*, p.title as program_title 
                       FROM community_testimonials t 
                       LEFT JOIN community_programs p ON t.program_id = p.id 
                       WHERE $where_clause 
                       ORDER BY t.created_at DESC 
                       LIMIT $per_page OFFSET $offset";
$testimonials_result = mysqli_query($conn, $testimonials_query);

// Get programs for filter
$programs_query = "SELECT id, title FROM community_programs ORDER BY title";
$programs_result = mysqli_query($conn, $programs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testimonials Management - Community Admin</title>
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
                        <h1><i class="fas fa-quote-left"></i> Testimonials Management</h1>
                        <p>Manage community testimonials and feedback</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add New Testimonial
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
                                           placeholder="Search testimonials...">
                                </div>
                                
                                <div class="filter-group">
                                    <label for="program">Program</label>
                                    <select id="program" name="program">
                                        <option value="">All Programs</option>
                                        <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                                            <option value="<?php echo $program['id']; ?>" 
                                                    <?php echo $program_filter === $program['id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($program['title']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                
                                <div class="filter-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="">All Statuses</option>
                                        <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo $status_filter === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
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

                <!-- Testimonials Table -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Testimonials (<?php echo $total_testimonials; ?>)</h3>
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
                        <?php if (mysqli_num_rows($testimonials_result) > 0): ?>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>Testimonial</th>
                                        <th>Program</th>
                                        <th>Rating</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($testimonial = mysqli_fetch_assoc($testimonials_result)): ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="selected_testimonials[]" 
                                                       value="<?php echo $testimonial['id']; ?>" 
                                                       class="testimonial-checkbox">
                                            </td>
                                            <td>
                                                <div class="testimonial-info">
                                                    <div class="testimonial-author">
                                                        <?php if ($testimonial['image']): ?>
                                                            <div class="author-image">
                                                                <img src="../../assets/images/testimonials/<?php echo htmlspecialchars($testimonial['image']); ?>" 
                                                                     alt="<?php echo htmlspecialchars($testimonial['name']); ?>">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="author-placeholder">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="author-details">
                                                            <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                                            <?php if ($testimonial['role']): ?>
                                                                <p class="author-role"><?php echo htmlspecialchars($testimonial['role']); ?></p>
                                                            <?php endif; ?>
                                                            <?php if ($testimonial['organization']): ?>
                                                                <p class="author-org"><?php echo htmlspecialchars($testimonial['organization']); ?></p>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="testimonial-message">
                                                        <p><?php echo htmlspecialchars(substr($testimonial['message'], 0, 150)); ?>...</p>
                                                        <?php if ($testimonial['featured']): ?>
                                                            <span class="featured-badge">
                                                                <i class="fas fa-star"></i>
                                                                Featured
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if ($testimonial['program_title']): ?>
                                                    <span class="program-link">
                                                        <?php echo htmlspecialchars($testimonial['program_title']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="no-program">General</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="rating-display">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'active' : ''; ?>"></i>
                                                    <?php endfor; ?>
                                                    <span class="rating-number">(<?php echo $testimonial['rating']; ?>)</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $testimonial['status']; ?>">
                                                    <?php echo ucfirst($testimonial['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="date-info">
                                                    <?php echo date('M j, Y', strtotime($testimonial['created_at'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="edit.php?id=<?php echo $testimonial['id']; ?>" 
                                                       class="btn-action btn-edit" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?php echo $testimonial['id']; ?>" 
                                                       class="btn-action btn-delete" title="Delete"
                                                       data-confirm="Are you sure you want to delete this testimonial?">
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
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                <h3>No Testimonials Found</h3>
                                <p>No testimonials match your current filters. Try adjusting your search criteria or create a new testimonial.</p>
                                <a href="create.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Create First Testimonial
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
    
    <style>
        .testimonial-info {
            max-width: 500px;
        }
        
        .testimonial-author {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        .author-image, .author-placeholder {
            width: 50px;
            height: 50px;
            border-radius: var(--border-radius-round);
            overflow: hidden;
            flex-shrink: 0;
        }
        
        .author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .author-placeholder {
            background: var(--neutral-beige);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-medium);
            font-size: 1.2rem;
        }
        
        .author-details h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .author-role, .author-org {
            font-size: 0.8rem;
            color: var(--text-medium);
            margin: 0;
        }
        
        .testimonial-message p {
            font-size: 0.9rem;
            color: var(--text-medium);
            line-height: 1.4;
            margin-bottom: 0.5rem;
        }
        
        .rating-display {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .rating-display .fa-star {
            color: var(--neutral-beige);
            font-size: 0.9rem;
        }
        
        .rating-display .fa-star.active {
            color: #ffc107;
        }
        
        .rating-number {
            font-size: 0.8rem;
            color: var(--text-medium);
            margin-left: 0.5rem;
        }
        
        .program-link {
            color: var(--primary-green);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .no-program {
            color: var(--text-medium);
            font-style: italic;
            font-size: 0.9rem;
        }
    </style>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
