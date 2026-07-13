<?php
session_start();
require_once '../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: index.php');
    exit;
}

// Get dashboard statistics
$stats_query = "SELECT 
    COUNT(*) as total_programs,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_programs,
    COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_programs,
    COUNT(CASE WHEN featured = 1 THEN 1 END) as featured_programs,
    SUM(beneficiaries) as total_beneficiaries
    FROM community_programs";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

// Get testimonials count
$testimonials_query = "SELECT COUNT(*) as total_testimonials FROM community_testimonials WHERE status = 'active'";
$testimonials_result = mysqli_query($conn, $testimonials_query);
$testimonials_count = mysqli_fetch_assoc($testimonials_result)['total_testimonials'];

// Get messages count
$messages_query = "SELECT 
    COUNT(*) as total_messages,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_messages
    FROM community_messages";
$messages_result = mysqli_query($conn, $messages_query);
$messages_stats = mysqli_fetch_assoc($messages_result);

// Get team members count
$team_query = "SELECT COUNT(*) as total_team FROM community_team WHERE status = 'active'";
$team_result = mysqli_query($conn, $team_query);
$team_count = mysqli_fetch_assoc($team_result)['total_team'];

// Get recent programs
$recent_programs_query = "SELECT id, title, country, status, created_at, beneficiaries FROM community_programs ORDER BY created_at DESC LIMIT 5";
$recent_programs_result = mysqli_query($conn, $recent_programs_query);

// Get recent messages
$recent_messages_query = "SELECT id, name, email, subject, status, sent_at FROM community_messages ORDER BY sent_at DESC LIMIT 5";
$recent_messages_result = mysqli_query($conn, $recent_messages_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/images/logos/logo.jpg">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo">
                    <img src="../assets/images/logos/logo.jpg" alt="Virunga Ecotours">
                    <h2>Community Admin</h2>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="programs/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/programs/') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-project-diagram"></i>
                        <span>Programs</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="testimonials/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/testimonials/') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-quote-left"></i>
                        <span>Testimonials</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="team/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/team/') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i>
                        <span>Team Members</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="messages/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/messages/') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-envelope"></i>
                        <span>Messages</span>
                        <?php
                        // Get unread messages count
                        if (isset($conn)) {
                            $unread_query = "SELECT COUNT(*) as unread FROM community_messages WHERE status = 'new'";
                            $unread_result = mysqli_query($conn, $unread_query);
                            $unread_count = mysqli_fetch_assoc($unread_result)['unread'];
                            if ($unread_count > 0) {
                                echo '<span class="nav-badge">' . $unread_count . '</span>';
                            }
                        }
                        ?>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="categories/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/categories/') !== false ? 'active' : ''; ?>">
                        <i class="fas fa-tags"></i>
                        <span>Categories</span>
                    </a>
                </div>
                
                <div class="nav-divider"></div>
                
                <div class="nav-item">
                    <a href="../index.php" target="_blank" class="nav-link">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Site</span>
                    </a>
                </div>
                
                <div class="nav-item">
                    <a href="../../index.php" target="_blank" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Main Website</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <style>
        .nav-badge {
            background-color: var(--error-color);
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            margin-left: auto;
            min-width: 18px;
            text-align: center;
            font-weight: 600;
        }
        
        .nav-divider {
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
            margin: 1rem 1.5rem;
        }
        
        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .nav-link span:first-of-type {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }
        </style>


        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">
                        <?php
                        $page_titles = [
                            'dashboard.php' => 'Dashboard',
                            'programs' => 'Programs',
                            'testimonials' => 'Testimonials',
                            'team' => 'Team Members',
                            'messages' => 'Messages',
                            'categories' => 'Categories',
                            'settings' => 'Settings'
                        ];
                        
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $current_dir = basename(dirname($_SERVER['PHP_SELF']));
                        
                        if (isset($page_titles[$current_page])) {
                            echo $page_titles[$current_page];
                        } elseif (isset($page_titles[$current_dir])) {
                            echo $page_titles[$current_dir];
                        } else {
                            echo 'Community Admin';
                        }
                        ?>
                    </h1>
                </div>
                
                <div class="top-bar-right">
                    <div class="user-menu">
                        <!-- Notifications -->
                        <div class="notifications-dropdown">
                            <button class="notification-btn" id="notificationBtn">
                                <i class="fas fa-bell"></i>
                                <?php
                                // Get notification count
                                if (isset($conn)) {
                                    $notification_query = "SELECT COUNT(*) as count FROM community_messages WHERE status = 'new'";
                                    $notification_result = mysqli_query($conn, $notification_query);
                                    $notification_count = mysqli_fetch_assoc($notification_result)['count'];
                                    if ($notification_count > 0) {
                                        echo '<span class="notification-badge">' . $notification_count . '</span>';
                                    }
                                }
                                ?>
                            </button>
                            <div class="notification-dropdown" id="notificationDropdown">
                                <div class="notification-header">
                                    <h4>Notifications</h4>
                                    <a href="messages/" class="view-all">View All</a>
                                </div>
                                <div class="notification-list">
                                    <?php
                                    if (isset($conn)) {
                                        $recent_notifications_query = "SELECT name, email, sent_at FROM community_messages WHERE status = 'new' ORDER BY sent_at DESC LIMIT 5";
                                        $recent_notifications_result = mysqli_query($conn, $recent_notifications_query);
                                        
                                        if (mysqli_num_rows($recent_notifications_result) > 0) {
                                            while ($notification = mysqli_fetch_assoc($recent_notifications_result)) {
                                                echo '<div class="notification-item">';
                                                echo '<div class="notification-content">';
                                                echo '<p><strong>' . htmlspecialchars($notification['name']) . '</strong> sent a message</p>';
                                                echo '<span class="notification-time">' . date('M j, g:i A', strtotime($notification['sent_at'])) . '</span>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="notification-empty">No new notifications</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Info -->
                        <div class="user-info">
                            <div class="user-avatar">
                                <?php echo strtoupper(substr($_SESSION['community_admin_name'], 0, 1)); ?>
                            </div>
                            <div class="user-details">
                                <div class="user-name"><?php echo htmlspecialchars($_SESSION['community_admin_name']); ?></div>
                                <div class="user-role"><?php echo htmlspecialchars($_SESSION['community_admin_role']); ?></div>
                            </div>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="user-dropdown">
                            <button class="user-dropdown-btn" id="userDropdownBtn">
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="user-dropdown-menu" id="userDropdownMenu">
                                <a href="profile.php" class="dropdown-item">
                                    <i class="fas fa-user"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="logout.php" class="dropdown-item logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <style>
                .top-bar {
                    background-color: white;
                    padding: 1rem 2rem;
                    box-shadow: var(--shadow-sm);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    position: sticky;
                    top: 0;
                    z-index: 100;
                    border-bottom: 1px solid var(--neutral-beige);
                    margin-left: 280px; /* keep topbar aligned with fixed sidebar */
                }

                .top-bar-left {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .mobile-menu-toggle {
                    display: none;
                    background: none;
                    border: none;
                    font-size: 1.2rem;
                    color: var(--text-dark);
                    cursor: pointer;
                    padding: 0.5rem;
                    border-radius: var(--border-radius-sm);
                    transition: background-color 0.3s ease;
                }

                .mobile-menu-toggle:hover {
                    background-color: var(--neutral-light);
                }

                .page-title {
                    font-size: 1.5rem;
                    font-weight: 600;
                    color: var(--text-dark);
                    margin: 0;
                }

                .top-bar-right {
                    display: flex;
                    align-items: center;
                }

                .user-menu {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                /* Notifications */
                .notifications-dropdown {
                    position: relative;
                }

                .notification-btn {
                    background: none;
                    border: none;
                    font-size: 1.2rem;
                    color: var(--text-medium);
                    cursor: pointer;
                    padding: 0.5rem;
                    border-radius: var(--border-radius-sm);
                    transition: all 0.3s ease;
                    position: relative;
                }

                .notification-btn:hover {
                    background-color: var(--neutral-light);
                    color: var(--text-dark);
                }

                .notification-badge {
                    position: absolute;
                    top: 0.2rem;
                    right: 0.2rem;
                    background-color: var(--error-color);
                    color: white;
                    font-size: 0.7rem;
                    padding: 0.1rem 0.4rem;
                    border-radius: 10px;
                    min-width: 16px;
                    text-align: center;
                    font-weight: 600;
                }

                .notification-dropdown {
                    position: absolute;
                    top: 100%;
                    right: 0;
                    width: 320px;
                    background: white;
                    border-radius: var(--border-radius-md);
                    box-shadow: var(--shadow-lg);
                    border: 1px solid var(--neutral-beige);
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-10px);
                    transition: all 0.3s ease;
                    z-index: 1000;
                    margin-top: 0.5rem;
                }

                .notification-dropdown.active {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0);
                }

                .notification-header {
                    padding: 1rem;
                    border-bottom: 1px solid var(--neutral-beige);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .notification-header h4 {
                    margin: 0;
                    color: var(--text-dark);
                    font-size: 1rem;
                }

                .view-all {
                    color: var(--primary-green);
                    text-decoration: none;
                    font-size: 0.9rem;
                    font-weight: 500;
                }

                .notification-list {
                    max-height: 300px;
                    overflow-y: auto;
                }

                .notification-item {
                    padding: 1rem;
                    border-bottom: 1px solid var(--neutral-light);
                    transition: background-color 0.3s ease;
                }

                .notification-item:hover {
                    background-color: var(--neutral-light);
                }

                .notification-item:last-child {
                    border-bottom: none;
                }

                .notification-content p {
                    margin: 0 0 0.25rem 0;
                    color: var(--text-dark);
                    font-size: 0.9rem;
                }

                .notification-time {
                    color: var(--text-medium);
                    font-size: 0.8rem;
                }

                .notification-empty {
                    padding: 2rem;
                    text-align: center;
                    color: var(--text-medium);
                    font-size: 0.9rem;
                }

                /* User Info */
                .user-info {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                }

                .user-avatar {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    background-color: var(--primary-green);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 600;
                    font-size: 1rem;
                }

                .user-details {
                    display: flex;
                    flex-direction: column;
                }

                .user-name {
                    font-weight: 600;
                    color: var(--text-dark);
                    font-size: 0.9rem;
                    line-height: 1.2;
                }

                .user-role {
                    font-size: 0.8rem;
                    color: var(--text-medium);
                    text-transform: capitalize;
                    line-height: 1.2;
                }

                /* User Dropdown */
                .user-dropdown {
                    position: relative;
                }

                .user-dropdown-btn {
                    background: none;
                    border: none;
                    color: var(--text-medium);
                    cursor: pointer;
                    padding: 0.5rem;
                    border-radius: var(--border-radius-sm);
                    transition: all 0.3s ease;
                }

                .user-dropdown-btn:hover {
                    background-color: var(--neutral-light);
                    color: var(--text-dark);
                }

                .user-dropdown-menu {
                    position: absolute;
                    top: 100%;
                    right: 0;
                    width: 200px;
                    background: white;
                    border-radius: var(--border-radius-md);
                    box-shadow: var(--shadow-lg);
                    border: 1px solid var(--neutral-beige);
                    opacity: 0;
                    visibility: hidden;
                    transform: translateY(-10px);
                    transition: all 0.3s ease;
                    z-index: 1000;
                    margin-top: 0.5rem;
                }

                .user-dropdown-menu.active {
                    opacity: 1;
                    visibility: visible;
                    transform: translateY(0);
                }

                .dropdown-item {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 0.75rem 1rem;
                    color: var(--text-dark);
                    text-decoration: none;
                    transition: background-color 0.3s ease;
                    font-size: 0.9rem;
                }

                .dropdown-item:hover {
                    background-color: var(--neutral-light);
                }

                .dropdown-item.logout {
                    color: var(--error-color);
                }

                .dropdown-item.logout:hover {
                    background-color: rgba(244, 67, 54, 0.1);
                }

                .dropdown-divider {
                    height: 1px;
                    background-color: var(--neutral-beige);
                    margin: 0.5rem 0;
                }

                /* Mobile Responsive */
                @media (max-width: 768px) {
                    .mobile-menu-toggle {
                        display: block;
                    }
                    
                    .top-bar {
                        padding: 1rem;
                        margin-left: 0; /* sidebar collapses on mobile */
                    }
                    
                    .user-details {
                        display: none;
                    }
                    
                    .notification-dropdown,
                    .user-dropdown-menu {
                        width: 280px;
                    }
                }

                @media (max-width: 480px) {
                    .page-title {
                        font-size: 1.2rem;
                    }
                    
                    .notification-dropdown,
                    .user-dropdown-menu {
                        width: 260px;
                        right: -1rem;
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Mobile menu toggle
                    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
                    const sidebar = document.getElementById('sidebar');
                    const sidebarOverlay = document.getElementById('sidebarOverlay');
                    
                    if (mobileMenuToggle) {
                        mobileMenuToggle.addEventListener('click', function() {
                            sidebar.classList.toggle('active');
                            sidebarOverlay.classList.toggle('active');
                        });
                    }
                    
                    if (sidebarOverlay) {
                        sidebarOverlay.addEventListener('click', function() {
                            sidebar.classList.remove('active');
                            sidebarOverlay.classList.remove('active');
                        });
                    }
                    
                    // Notification dropdown
                    const notificationBtn = document.getElementById('notificationBtn');
                    const notificationDropdown = document.getElementById('notificationDropdown');
                    
                    if (notificationBtn && notificationDropdown) {
                        notificationBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            notificationDropdown.classList.toggle('active');
                            // Close user dropdown if open
                            const userDropdownMenu = document.getElementById('userDropdownMenu');
                            if (userDropdownMenu) {
                                userDropdownMenu.classList.remove('active');
                            }
                        });
                    }
                    
                    // User dropdown
                    const userDropdownBtn = document.getElementById('userDropdownBtn');
                    const userDropdownMenu = document.getElementById('userDropdownMenu');
                    
                    if (userDropdownBtn && userDropdownMenu) {
                        userDropdownBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            userDropdownMenu.classList.toggle('active');
                            // Close notification dropdown if open
                            if (notificationDropdown) {
                                notificationDropdown.classList.remove('active');
                            }
                        });
                    }
                    
                    // Close dropdowns when clicking outside
                    document.addEventListener('click', function() {
                        if (notificationDropdown) {
                            notificationDropdown.classList.remove('active');
                        }
                        if (userDropdownMenu) {
                            userDropdownMenu.classList.remove('active');
                        }
                    });
                });
            </script>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-content">
                        <h1>Welcome back, <?php echo htmlspecialchars($_SESSION['community_admin_name']); ?>!</h1>
                        <p>Here's an overview of your community programs management system.</p>
                    </div>
                    <div class="welcome-actions">
                        <a href="programs/create.php" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            Add New Program
                        </a>
                        <a href="../index.php" target="_blank" class="btn btn-outline">
                            <i class="fas fa-external-link-alt"></i>
                            View Site
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon programs">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $stats['total_programs'] ?? 0; ?></div>
                            <div class="stat-label">Total Programs</div>
                            <div class="stat-detail">
                                <?php echo $stats['active_programs'] ?? 0; ?> active, 
                                <?php echo $stats['completed_programs'] ?? 0; ?> completed
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon beneficiaries">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo number_format($stats['total_beneficiaries'] ?? 0); ?></div>
                            <div class="stat-label">Beneficiaries</div>
                            <div class="stat-detail">Lives impacted by programs</div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon messages">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $messages_stats['total_messages'] ?? 0; ?></div>
                            <div class="stat-label">Messages</div>
                            <div class="stat-detail">
                                <?php echo $messages_stats['new_messages'] ?? 0; ?> new messages
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon testimonials">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number"><?php echo $testimonials_count ?? 0; ?></div>
                            <div class="stat-label">Testimonials</div>
                            <div class="stat-detail"><?php echo $team_count ?? 0; ?> team members</div>
                        </div>
                    </div>
                </div>

                <!-- Content Grid -->
                <div class="content-grid">
                    <!-- Recent Programs -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Recent Programs</h3>
                            <a href="programs/" class="view-all-link">
                                <i class="fas fa-arrow-right"></i>
                                View All
                            </a>
                        </div>
                        <div class="card-content">
                            <?php if (mysqli_num_rows($recent_programs_result) > 0): ?>
                                <div class="list-items">
                                    <?php while ($program = mysqli_fetch_assoc($recent_programs_result)): ?>
                                        <div class="list-item">
                                            <div class="item-info">
                                                <h4><?php echo htmlspecialchars($program['title']); ?></h4>
                                                <div class="item-meta">
                                                    <span class="country">
                                                        <i class="fas fa-map-marker-alt"></i>
                                                        <?php echo ucfirst($program['country']); ?>
                                                    </span>
                                                    <span class="beneficiaries">
                                                        <i class="fas fa-users"></i>
                                                        <?php echo number_format($program['beneficiaries']); ?> beneficiaries
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="item-status">
                                                <span class="status-badge status-<?php echo $program['status']; ?>">
                                                    <?php echo ucfirst($program['status']); ?>
                                                </span>
                                                <div class="item-date">
                                                    <?php echo date('M j, Y', strtotime($program['created_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-project-diagram"></i>
                                    <p>No programs yet. <a href="programs/create.php">Create your first program</a>.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Recent Messages -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Recent Messages</h3>
                            <a href="messages/" class="view-all-link">
                                <i class="fas fa-arrow-right"></i>
                                View All
                            </a>
                        </div>
                        <div class="card-content">
                            <?php if (mysqli_num_rows($recent_messages_result) > 0): ?>
                                <div class="list-items">
                                    <?php while ($message = mysqli_fetch_assoc($recent_messages_result)): ?>
                                        <div class="list-item">
                                            <div class="item-info">
                                                <h4><?php echo htmlspecialchars($message['name']); ?></h4>
                                                <div class="item-meta">
                                                    <span class="email">
                                                        <i class="fas fa-envelope"></i>
                                                        <?php echo htmlspecialchars($message['email']); ?>
                                                    </span>
                                                    <?php if ($message['subject']): ?>
                                                        <span class="subject">
                                                            <?php echo htmlspecialchars($message['subject']); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="item-status">
                                                <span class="status-badge status-<?php echo $message['status']; ?>">
                                                    <?php echo ucfirst($message['status']); ?>
                                                </span>
                                                <div class="item-date">
                                                    <?php echo date('M j, Y', strtotime($message['sent_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-envelope"></i>
                                    <p>No messages yet.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="actions-grid">
                        <a href="programs/create.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="action-content">
                                <h4>Add Program</h4>
                                <p>Create a new community program</p>
                            </div>
                        </a>

                        <a href="testimonials/create.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-quote-left"></i>
                            </div>
                            <div class="action-content">
                                <h4>Add Testimonial</h4>
                                <p>Add a new testimonial</p>
                            </div>
                        </a>

                        <a href="team/create.php" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="action-content">
                                <h4>Add Team Member</h4>
                                <p>Add a new team member</p>
                            </div>
                        </a>

                        <a href="messages/" class="action-card">
                            <div class="action-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <div class="action-content">
                                <h4>Check Messages</h4>
                                <p>Review contact messages</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/admin.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
