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
                'impact' => 'Impact Page',
                'schools' => 'Schools & Community Programs',
                'volunteer' => 'Volunteer Management',
                'inclusive' => 'Inclusive Programs',
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
                        <a href="../messages/" class="view-all">View All</a>
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
                    <a href="../profile.php" class="dropdown-item">
                        <i class="fas fa-user"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="../logout.php" class="dropdown-item logout">
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
