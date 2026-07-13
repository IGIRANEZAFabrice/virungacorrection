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
    $member_id = (int)$_GET['id'];
    
    // Get member info for image deletion
    $member_query = "SELECT image FROM community_team WHERE id = $member_id";
    $member_result = mysqli_query($conn, $member_query);
    $member = mysqli_fetch_assoc($member_result);
    
    // Delete from database
    $delete_query = "DELETE FROM community_team WHERE id = $member_id";
    if (mysqli_query($conn, $delete_query)) {
        // Delete image file if exists
        if ($member && $member['image'] && file_exists("../../assets/images/team/" . $member['image'])) {
            unlink("../../assets/images/team/" . $member['image']);
        }
        $success_message = "Team member deleted successfully!";
    } else {
        $error_message = "Error deleting team member: " . mysqli_error($conn);
    }
}

// Handle bulk actions
if (isset($_POST['bulk_action']) && isset($_POST['selected_members'])) {
    $selected_members = $_POST['selected_members'];
    $action = $_POST['bulk_action'];
    
    if ($action === 'delete') {
        $ids = implode(',', array_map('intval', $selected_members));
        
        // Get images for deletion
        $images_query = "SELECT image FROM community_team WHERE id IN ($ids) AND image IS NOT NULL";
        $images_result = mysqli_query($conn, $images_query);
        $images_to_delete = [];
        while ($row = mysqli_fetch_assoc($images_result)) {
            if ($row['image']) {
                $images_to_delete[] = $row['image'];
            }
        }
        
        // Delete from database
        $bulk_delete_query = "DELETE FROM community_team WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_delete_query)) {
            // Delete image files
            foreach ($images_to_delete as $image) {
                if (file_exists("../../assets/images/team/" . $image)) {
                    unlink("../../assets/images/team/" . $image);
                }
            }
            $success_message = count($selected_members) . " team members deleted successfully!";
        }
    } elseif ($action === 'activate') {
        $ids = implode(',', array_map('intval', $selected_members));
        $bulk_update_query = "UPDATE community_team SET status = 'active' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_members) . " team members activated successfully!";
        }
    } elseif ($action === 'deactivate') {
        $ids = implode(',', array_map('intval', $selected_members));
        $bulk_update_query = "UPDATE community_team SET status = 'inactive' WHERE id IN ($ids)";
        if (mysqli_query($conn, $bulk_update_query)) {
            $success_message = count($selected_members) . " team members deactivated successfully!";
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
    $where_conditions[] = "(name LIKE '%$search_query%' OR title LIKE '%$search_query%' OR bio LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_team WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_members = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_members / $per_page);

// Get team members
$members_query = "SELECT * FROM community_team WHERE $where_clause ORDER BY order_position ASC, created_at DESC LIMIT $per_page OFFSET $offset";
$members_result = mysqli_query($conn, $members_query);

// Get statistics
$stats_query = "SELECT 
    COUNT(*) as total_members,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_members,
    COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_members
    FROM community_team";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Management - Community Admin</title>
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
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }
        
        .team-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .team-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .team-card-header {
            position: relative;
            padding: 1rem;
            text-align: center;
            background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
        }
        
        .team-member-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            overflow: hidden;
            border: 3px solid white;
            box-shadow: var(--shadow-sm);
        }
        
        .team-member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .team-member-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        
        .team-member-title {
            font-size: 0.9rem;
            color: var(--text-medium);
            margin-bottom: 0.5rem;
        }
        
        .team-card-body {
            padding: 1rem;
        }
        
        .team-member-bio {
            font-size: 0.85rem;
            color: var(--text-medium);
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .team-member-contact {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .contact-item {
            font-size: 0.8rem;
            color: var(--text-medium);
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .social-links {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .social-link {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }
        
        .social-facebook { background-color: #1877f2; color: white; }
        .social-twitter { background-color: #1da1f2; color: white; }
        .social-linkedin { background-color: #0077b5; color: white; }
        .social-instagram { background-color: #e4405f; color: white; }
        
        .social-link:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        .team-card-footer {
            padding: 1rem;
            border-top: 1px solid var(--neutral-beige);
            background-color: var(--neutral-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .order-position {
            font-size: 0.8rem;
            color: var(--text-medium);
            display: flex;
            align-items: center;
            gap: 0.25rem;
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
        
        .card-checkbox {
            position: absolute;
            top: 1rem;
            left: 1rem;
        }
        
        @media (max-width: 768px) {
            .team-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
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
                        <h1><i class="fas fa-users"></i> Team Management</h1>
                        <p>Manage team members, update profiles, and organize team structure</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add Team Member
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
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['total_members']; ?></div>
                        <div class="stat-label">Total Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--success-color);">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['active_members']; ?></div>
                        <div class="stat-label">Active Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="color: var(--warning-color);">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['inactive_members']; ?></div>
                        <div class="stat-label">Inactive Members</div>
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
                                           placeholder="Search team members...">
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

                <!-- Team Members -->
                <div class="table-card">
                    <div class="table-header">
                        <div class="table-title">
                            <h3>Team Members (<?php echo $total_members; ?>)</h3>
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
                        <?php if (mysqli_num_rows($members_result) > 0): ?>
                            <div class="team-grid">
                                <?php while ($member = mysqli_fetch_assoc($members_result)): ?>
                                    <div class="team-card">
                                        <div class="team-card-header">
                                            <input type="checkbox" name="selected_members[]"
                                                   value="<?php echo $member['id']; ?>"
                                                   class="member-checkbox card-checkbox">

                                            <div class="team-member-image">
                                                <img src="../../assets/images/team/<?php echo htmlspecialchars($member['image'] ?? 'default-avatar.jpg'); ?>"
                                                     alt="<?php echo htmlspecialchars($member['name']); ?>">
                                            </div>

                                            <div class="team-member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                                            <div class="team-member-title"><?php echo htmlspecialchars($member['title']); ?></div>

                                            <span class="status-badge status-<?php echo $member['status']; ?>">
                                                <?php echo ucfirst($member['status']); ?>
                                            </span>
                                        </div>

                                        <div class="team-card-body">
                                            <?php if ($member['bio']): ?>
                                                <div class="team-member-bio">
                                                    <?php echo htmlspecialchars($member['bio']); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="team-member-contact">
                                                <?php if ($member['email']): ?>
                                                    <div class="contact-item">
                                                        <i class="fas fa-envelope"></i>
                                                        <?php echo htmlspecialchars($member['email']); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ($member['phone']): ?>
                                                    <div class="contact-item">
                                                        <i class="fas fa-phone"></i>
                                                        <?php echo htmlspecialchars($member['phone']); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="social-links">
                                                <?php if ($member['facebook']): ?>
                                                    <a href="<?php echo htmlspecialchars($member['facebook']); ?>"
                                                       target="_blank" class="social-link social-facebook" title="Facebook">
                                                        <i class="fab fa-facebook-f"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($member['twitter']): ?>
                                                    <a href="<?php echo htmlspecialchars($member['twitter']); ?>"
                                                       target="_blank" class="social-link social-twitter" title="Twitter">
                                                        <i class="fab fa-twitter"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($member['linkedin']): ?>
                                                    <a href="<?php echo htmlspecialchars($member['linkedin']); ?>"
                                                       target="_blank" class="social-link social-linkedin" title="LinkedIn">
                                                        <i class="fab fa-linkedin-in"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($member['instagram']): ?>
                                                    <a href="<?php echo htmlspecialchars($member['instagram']); ?>"
                                                       target="_blank" class="social-link social-instagram" title="Instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="team-card-footer">
                                            <div class="order-position">
                                                <i class="fas fa-sort-numeric-up"></i>
                                                Position: <?php echo $member['order_position']; ?>
                                            </div>

                                            <div class="action-buttons">
                                                <a href="edit.php?id=<?php echo $member['id']; ?>"
                                                   class="btn-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="?action=delete&id=<?php echo $member['id']; ?>"
                                                   class="btn-action btn-delete" title="Delete"
                                                   data-confirm="Are you sure you want to delete this team member?">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>No Team Members Found</h3>
                                <p>No team members match your current filters. Try adjusting your search criteria or add a new team member.</p>
                                <a href="create.php" class="btn btn-primary">
                                    <i class="fas fa-plus"></i>
                                    Add First Team Member
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
            const memberCheckboxes = document.querySelectorAll('.member-checkbox');
            const bulkActionSelect = document.getElementById('bulkAction');
            const applyBulkActionBtn = document.getElementById('applyBulkAction');
            const bulkActionsForm = document.getElementById('bulkActionsForm');

            // Select all functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    memberCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActionButton();
                });
            }

            // Individual checkbox change
            memberCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateBulkActionButton();

                    // Update select all checkbox
                    if (selectAllCheckbox) {
                        const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                        selectAllCheckbox.checked = checkedCount === memberCheckboxes.length;
                        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < memberCheckboxes.length;
                    }
                });
            });

            function updateBulkActionButton() {
                const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                applyBulkActionBtn.disabled = checkedCount === 0 || !bulkActionSelect.value;
            }

            // Bulk action select change
            bulkActionSelect.addEventListener('change', function() {
                updateBulkActionButton();
            });

            // Confirm bulk delete
            bulkActionsForm.addEventListener('submit', function(e) {
                if (bulkActionSelect.value === 'delete') {
                    const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                    if (!confirm(`Are you sure you want to delete ${checkedCount} team member(s)? This action cannot be undone.`)) {
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
