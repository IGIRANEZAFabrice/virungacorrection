<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="../../assets/images/logos/logo.jpg" alt="Virunga Ecotours">
            <h2>Community Admin</h2>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-item">
            <a href="../dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../hero/hero.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'hero.php' ? 'active' : ''; ?>">
                <i class="fas fa-image"></i>
                <span>Hero Section</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../programs/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/programs/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-project-diagram"></i>
                <span>Programs</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="../testimonials/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/testimonials/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-quote-left"></i>
                <span>Testimonials</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="../team/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/team/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Team Members</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="../messages/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/messages/') !== false ? 'active' : ''; ?>">
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
            <a href="../categories/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/categories/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-tags"></i>
                <span>Categories</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../impact/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/impact/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-heart"></i>
                <span>Impact</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../schools/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/schools/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-school"></i>
                <span>Schools</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../volunteer/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/volunteer/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-hands-helping"></i>
                <span>Volunteer</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../inclusive/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/inclusive/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-universal-access"></i>
                <span>Inclusive Programs</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../activities" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/inclusive/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-universal-access"></i>
                <span>Activities</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../voluntourism/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/voluntourism/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-hands-helping"></i>
                <span>Voluntourism</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../gala/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/gala/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-utensils"></i>
                <span>Gala Dinner</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../philanthropy/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/philanthropy/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-heart"></i>
                <span>Philanthropy</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="../heritage/" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/heritage/') !== false ? 'active' : ''; ?>">
                <i class="fas fa-landmark"></i>
                <span>Heritage & Culture</span>
            </a>
        </div>

        <div class="nav-divider"></div>
        
        <div class="nav-item">
            <a href="../../index.php" target="_blank" class="nav-link">
                <i class="fas fa-external-link-alt"></i>
                <span>View Site</span>
            </a>
        </div>
        
        <div class="nav-item">
            <a href="../../../index.php" target="_blank" class="nav-link">
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
