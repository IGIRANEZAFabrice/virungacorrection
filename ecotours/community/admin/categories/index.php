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
    $category_id = (int)$_GET['id'];
    
    // Check if category is being used by any programs
    $usage_check_query = "SELECT COUNT(*) as program_count FROM community_programs WHERE category = (SELECT name FROM community_categories WHERE id = ?)";
    $usage_check_stmt = mysqli_prepare($conn, $usage_check_query);
    mysqli_stmt_bind_param($usage_check_stmt, "i", $category_id);
    mysqli_stmt_execute($usage_check_stmt);
    $usage_result = mysqli_stmt_get_result($usage_check_stmt);
    $usage_count = mysqli_fetch_assoc($usage_result)['program_count'];
    
    if ($usage_count > 0) {
        $error_message = "Cannot delete category. It is currently being used by $usage_count program(s).";
    } else {
        // Delete category
        $delete_query = "DELETE FROM community_categories WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $category_id);
        
        if (mysqli_stmt_execute($delete_stmt)) {
            $success_message = "Category deleted successfully!";
        } else {
            $error_message = "Error deleting category: " . mysqli_error($conn);
        }
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_categories'])) {
    $selected_categories = $_POST['selected_categories'];
    $action = $_POST['bulk_action'];
    
    if ($action === 'activate') {
        $ids = implode(',', array_map('intval', $selected_categories));
        $bulk_update_query = "UPDATE community_categories SET status = 'active' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_categories) . " categories activated successfully!";
        }
    } elseif ($action === 'deactivate') {
        $ids = implode(',', array_map('intval', $selected_categories));
        $bulk_update_query = "UPDATE community_categories SET status = 'inactive' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_categories) . " categories deactivated successfully!";
        }
    } elseif ($action === 'delete') {
        // Check if any selected categories are being used
        $ids = implode(',', array_map('intval', $selected_categories));
        $usage_check_query = "SELECT c.name, COUNT(p.id) as program_count 
                             FROM community_categories c 
                             LEFT JOIN community_programs p ON c.name = p.category 
                             WHERE c.id IN ($ids) 
                             GROUP BY c.id, c.name 
                             HAVING program_count > 0";
        $usage_result = mysqli_query($conn, $usage_check_query);
        
        if (mysqli_num_rows($usage_result) > 0) {
            $used_categories = [];
            while ($row = mysqli_fetch_assoc($usage_result)) {
                $used_categories[] = $row['name'] . " ({$row['program_count']} programs)";
            }
            $error_message = "Cannot delete the following categories as they are being used: " . implode(', ', $used_categories);
        } else {
            $bulk_delete_query = "DELETE FROM community_categories WHERE id IN ($ids)";
            if (mysqli_query($conn, $bulk_delete_query)) {
                $success_message = count($selected_categories) . " categories deleted successfully!";
            }
        }
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 12;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$where_conditions = ["1=1"];

if ($status_filter) {
    $where_conditions[] = "status = '$status_filter'";
}

if ($search_query) {
    $where_conditions[] = "(name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_categories WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_categories = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_categories / $per_page);

// Get categories with program counts
$categories_query = "SELECT c.*, 
    COUNT(p.id) as program_count,
    COUNT(CASE WHEN p.status = 'active' THEN 1 END) as active_programs
    FROM community_categories c 
    LEFT JOIN community_programs p ON c.name = p.category 
    WHERE $where_clause 
    GROUP BY c.id 
    ORDER BY c.created_at DESC 
    LIMIT $per_page OFFSET $offset";
$categories_result = mysqli_query($conn, $categories_query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_categories,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_categories,
    COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_categories
    FROM community_categories";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get category usage statistics
$usage_stats_query = "SELECT 
    COUNT(DISTINCT p.category) as used_categories,
    COUNT(p.id) as total_programs
    FROM community_programs p 
    INNER JOIN community_categories c ON p.category = c.name";
$usage_stats_result = mysqli_query($conn, $usage_stats_query);
$usage_stats = mysqli_fetch_assoc($usage_stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - Community Admin</title>
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
    
    <style>
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .category-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .category-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .category-card-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
            border-bottom: 1px solid var(--neutral-beige);
            position: relative;
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }
        
        .category-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }
        
        .category-description {
            font-size: 0.9rem;
            color: var(--text-medium);
            line-height: 1.5;
        }
        
        .category-card-body {
            padding: 1.5rem;
        }
        
        .category-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .stat-item {
            text-align: center;
            padding: 0.75rem;
            background-color: var(--neutral-light);
            border-radius: var(--border-radius-sm);
        }
        
        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-medium);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .category-card-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--neutral-beige);
            background-color: var(--neutral-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-checkbox {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
        
        .color-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: var(--shadow-sm);
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            text-align: center;
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text-medium);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        @media (max-width: 768px) {
            .categories-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
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
                        <h1><i class="fas fa-tags"></i> Category Management</h1>
                        <p>Manage program categories, organize content, and track usage</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Category
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

                <!-- Statistics -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--primary-green);">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['total_categories']; ?></div>
                        <div class="stat-label">Total Categories</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--success-color);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['active_categories']; ?></div>
                        <div class="stat-label">Active Categories</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--warning-color);">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['inactive_categories']; ?></div>
                        <div class="stat-label">Inactive Categories</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--info-color);">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-number"><?php echo $usage_stats['used_categories'] ?: 0; ?></div>
                        <div class="stat-label">Categories in Use</div>
                    </div>
                </div>

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
                                           placeholder="Search categories...">
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

                <!-- Categories -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Categories (<?php echo $total_categories; ?>)</h3>
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
                        <?php if (mysqli_num_rows($categories_result) > 0): ?>
                            <div class="categories-grid">
                                <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                    <div class="category-card">
                                        <div class="category-card-header">
                                            <input type="checkbox" name="selected_categories[]"
                                                   value="<?php echo $category['id']; ?>"
                                                   class="category-checkbox card-checkbox">

                                            <div class="category-icon" style="background-color: <?php echo htmlspecialchars($category['color']); ?>;">
                                                <i class="<?php echo htmlspecialchars($category['icon'] ?: 'fas fa-tag'); ?>"></i>
                                            </div>

                                            <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                                            <div class="category-description">
                                                <?php echo htmlspecialchars($category['description'] ?: 'No description provided'); ?>
                                            </div>

                                            <span class="status-badge status-<?php echo $category['status']; ?>">
                                                <?php echo ucfirst($category['status']); ?>
                                            </span>
                                        </div>

                                        <div class="category-card-body">
                                            <div class="category-stats">
                                                <div class="stat-item">
                                                    <div class="stat-number"><?php echo $category['program_count']; ?></div>
                                                    <div class="stat-label">Total Programs</div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-number"><?php echo $category['active_programs']; ?></div>
                                                    <div class="stat-label">Active Programs</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="category-card-footer">
                                            <div class="color-indicator" style="background-color: <?php echo htmlspecialchars($category['color']); ?>;"
                                                 title="Category Color"></div>

                                            <div class="action-buttons">
                                                <a href="edit.php?id=<?php echo $category['id']; ?>"
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($category['program_count'] == 0): ?>
                                                    <a href="?action=delete&id=<?php echo $category['id']; ?>"
                                                       class="btn-action btn-delete" title="Delete"
                                                       data-confirm="Are you sure you want to delete this category?">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <span class="btn-action btn-disabled" title="Cannot delete - category is in use">
                                                        <i class="fas fa-lock"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h3>No Categories Found</h3>
                                <p>No categories match your current filters. Try adjusting your search criteria or add a new category.</p>
                                <a href="create.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add First Category
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle bulk actions
            const selectAllCheckbox = document.getElementById('selectAll');
            const categoryCheckboxes = document.querySelectorAll('.category-checkbox');
            const bulkActionSelect = document.getElementById('bulkAction');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            const bulkActionsForm = document.getElementById('bulkActionsForm');

            // Select all functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    categoryCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActionButton();
                });
            }

            // Individual checkbox change
            categoryCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkActionButton();

                    // Update select all checkbox
                    if (selectAllCheckbox) {
                        const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                        selectAllCheckbox.checked = checkedCount === categoryCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < categoryCheckboxes.length;
                    }
                });
            });

            function updateBulkActionButton() {
                const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                applyBulkActionBtn.disabled = checkedCount === 0 || !bulkActionSelect.value;
            }

            // Bulk action select change
            bulkActionSelect.addEventListener('change', function() {
                updateBulkActionButton();
            });

            // Confirm bulk delete
            bulkActionsForm.addEventListener('submit', function(e) {
                if (bulkActionSelect.value === 'delete') {
                    const checkedCount = document.querySelectorAll('.category-checkbox:checked').length;
                    if (!confirm(`Are you sure you want to delete ${checkedCount} category(ies)? This action cannot be undone and will fail if any category is in use.`)) {
                        e.preventDefault();
                    }
                }
            });

            // Confirm individual delete
            document.querySelectorAll('[data-confirm]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm(this.dataset.confirm)) {
                        e.preventDefault();
                    }
                });
            });

            // Filters toggle
            const filtersToggle = document.getElementById('filtersToggle');
            const filtersContent = document.getElementById('filtersContent');

            if (filtersToggle && filtersContent) {
                filtersToggle.addEventListener('click', function() {
                    filtersContent.style.display = filtersContent.style.display === 'none' ? 'block' : 'none';
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                });
            }
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
