<?php
session_start();
require_once '../../admin/config/connection.php';

// Check if admin is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ./index.php');
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['community_admin_id'];
$query = "SELECT * FROM community_admins WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $admin_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin = mysqli_fetch_assoc($result);

// Handle profile update
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $full_name = trim($_POST['full_name']);
    
    // Handle password change if requested
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    $update_password = false;
    if (!empty($current_password) && !empty($new_password)) {
        // Verify current password
        if (!password_verify($current_password, $admin['password'])) {
            $error = "Current password is incorrect";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } else {
            $update_password = true;
        }
    }
    
    if (empty($error)) {
        // Start transaction
        mysqli_begin_transaction($conn);
        try {
            // Update basic info
            $update_query = "UPDATE community_admins SET username = ?, email = ?, full_name = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $full_name, $admin_id);
            mysqli_stmt_execute($stmt);
            
            // Update password if requested
            if ($update_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $password_query = "UPDATE community_admins SET password = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $password_query);
                mysqli_stmt_bind_param($stmt, "si", $hashed_password, $admin_id);
                mysqli_stmt_execute($stmt);
            }
            
            // Handle profile image upload
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
                $file_type = $_FILES['profile_image']['type'];
                
                if (in_array($file_type, $allowed_types)) {
                    $file_name = uniqid('admin_') . '_' . basename($_FILES['profile_image']['name']);
                    $upload_path = '../uploads/profile/' . $file_name;
                    
                    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $image_query = "UPDATE community_admins SET profile_image = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $image_query);
                        mysqli_stmt_bind_param($stmt, "si", $file_name, $admin_id);
                        mysqli_stmt_execute($stmt);
                    }
                }
            }
            
            mysqli_commit($conn);
            $message = "Profile updated successfully!";
            
            // Refresh admin data
            $result = mysqli_query($conn, "SELECT id, username, email, full_name, profile_image, role, status, last_login FROM community_admins WHERE id = $admin_id");
            $admin = mysqli_fetch_assoc($result);
            
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "An error occurred while updating your profile";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Community Dashboard</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="assets/css/community-profile.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
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
                    <a href="index.php" target="_blank" class="nav-link">
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
            </script>            <div class="community-profile-container">
                <div class="community-profile-header">
                    <h2>My Profile</h2>
                    <p>Manage your account settings and preferences</p>
                </div>                <?php if (!empty($message)): ?>
                    <div class="community-profile-alert community-profile-alert-success">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)): ?>
                    <div class="community-profile-alert community-profile-alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?><form action="" method="POST" enctype="multipart/form-data" class="community-profile-form">
                    <div class="community-profile-grid">
                        <div class="community-profile-image-section">
                            <div class="current-image">
                                <img src="<?php echo !empty($admin['profile_image']) ? '../uploads/profile/' . htmlspecialchars($admin['profile_image']) : '../assets/images/default-avatar.png'; ?>" 
                                     alt="Profile Image" id="profile-preview">
                            </div>
                            <div class="image-upload">
                                <label for="profile_image" class="btn btn-outline">
                                    <i class="fas fa-camera"></i> Change Profile Picture
                                </label>
                                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="hidden">
                            </div>
                            <p class="image-hint">Recommended: Square image, max 2MB</p>
                        </div>
                        <div class="community-profile-details">
                            <div class="community-profile-form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                            </div>

                            <div class="community-profile-form-group">
                                <label for="full_name">Full Name</label>
                                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($admin['full_name']); ?>" required>
                            </div>

                            <div class="community-profile-form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                            </div>

                            <div class="community-profile-form-group">
                                <label>Role</label>
                                <input type="text" value="<?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $admin['role']))); ?>" readonly>
                            </div>

                            <div class="community-profile-form-group">
                                <label>Status</label>
                                <input type="text" value="<?php echo htmlspecialchars(ucwords($admin['status'])); ?>" readonly>
                            </div>

                            <div class="community-profile-form-group">
                                <label>Last Login</label>
                                <input type="text" value="<?php echo $admin['last_login'] ? date('F j, Y g:i A', strtotime($admin['last_login'])) : 'Never'; ?>" readonly>
                            </div>
                        </div>
                    </div>                    <div class="community-profile-password-section">
                        <h3>Change Password</h3>
                        <p>Leave blank if you don't want to change your password</p>

                        <div class="community-profile-password-grid">
                            <div class="community-profile-form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password">
                            </div>

                            <div class="community-profile-form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password">
                            </div>

                            <div class="community-profile-form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                    </div>                    <div class="community-profile-form-actions">
                        <button type="submit" class="community-profile-btn community-profile-btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>                        <button type="reset" class="community-profile-btn community-profile-btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Profile image preview
        document.getElementById('profile_image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Form reset confirmation
        document.querySelector('.profile-form').addEventListener('reset', function(e) {
            if (!confirm('Are you sure you want to reset all changes?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
