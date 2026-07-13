<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $resolvedAssetBase = !empty($assetBase) ? $assetBase : './'; ?>
    <base href="<?php echo htmlspecialchars($resolvedAssetBase, ENT_QUOTES); ?>">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Virunga Ecotours">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Virunga Ecotours Community Programs">
    <meta property="og:locale" content="en_US">
    <meta property="og:image" content="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST']; ?>/clone/ecotours/images/logos/logo.png">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@virunga_ecotours">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/logos/logo.png">
    <link rel="apple-touch-icon" href="../images/logos/logo.png">
</head>
<body>
    <!-- Skip to Content (Accessibility) -->
    <a href="#main-content" class="skip-to-content">Skip to main content</a>
    
    <!-- Community Header -->
    <header class="community-header" id="communityHeader">
        <!-- Top Bar -->
        <div class="community-top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <div class="header-contact-info">
                        <a href="mailto:community@virungaecotours.com" class="header-contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@virungaecotours.com</span>
                        </a>
                        <a href="tel:+250784513435" class="header-contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+(250) 784 513 435</span>
                        </a>
                    </div>
                    <div class="top-bar-links">
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : '../index.php'; ?>" class="top-link main-site">
                            <i class="fas fa-home"></i>
                            Main Website
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="community-main-header">
            <div class="container">
                <div class="header-content">
                    <!-- Logo -->
                    <div class="community-logo">
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('community')) : 'index.php'; ?>" class="logo-link">
                            <img src="assets/images/logos/logo.jpg" alt="logo" class="logo-img">
                        </a>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle mobile menu">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>

                    <!-- Navigation -->
                    <nav class="community-nav" id="communityNav">
                        <ul class="nav-list">
                            <li class="nav-item">
                                <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('community')) : 'index.php'; ?>" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                                    
                                    <span>Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''; ?>">
                                    
                                    <span>About Us</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="impact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'impact.php' ? 'active' : ''; ?>">
                                    <span>Community Impact</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="activity.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'activity.php' ? 'active' : ''; ?>">
                                    <span>Activities</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="programs.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'programs.php' || basename($_SERVER['PHP_SELF']) === 'program-detail.php' ? 'active' : ''; ?>">
                                    
                                    <span>Programs</span>
                                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-content">
                                        <div class="dropdown-section">
                                            <h4>By Country</h4>
                                            <a href="programs.php?country=rwanda" class="dropdown-link">
                                                <i class="fas fa-map-marker-alt"></i>
                                                Rwanda Programs
                                            </a>
                                            <a href="programs.php?country=uganda" class="dropdown-link">
                                                <i class="fas fa-map-marker-alt"></i>
                                                Uganda Programs
                                            </a>
                                            <a href="programs.php?country=congo" class="dropdown-link">
                                                <i class="fas fa-map-marker-alt"></i>
                                                DRC Congo Programs
                                            </a>
                                        </div>
                                        <div class="dropdown-section">
                                            <h4>By Category</h4>
                                            <a href="programs.php?category=Education" class="dropdown-link">
                                                <i class="fas fa-graduation-cap"></i>
                                                Education
                                            </a>
                                            <a href="programs.php?category=Health" class="dropdown-link">
                                                <i class="fas fa-heartbeat"></i>
                                                Health
                                            </a>
                                            <a href="programs.php?category=Conservation" class="dropdown-link">
                                                <i class="fas fa-leaf"></i>
                                                Conservation
                                            </a>
                                            <a href="programs.php?category=Women Empowerment" class="dropdown-link">
                                                <i class="fas fa-female"></i>
                                                Women Empowerment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            
                           
                            <li class="nav-item">
                                <a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : ''; ?>">
                                    
                                    <span>Contact Us</span>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Header Actions -->
                    <div class="header-actions">
                        <a href="volunteer.php?action=volunteer" class="action-btn volunteer-btn">
                            <i class="fas fa-hands-helping"></i>
                            <span>Volunteer</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Overlay -->
        <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
        
        <!-- Mobile Navigation Menu -->
        <div class="mobile-nav-menu" id="mobileNavMenu">
            <div class="mobile-nav-header">
                <div class="mobile-logo">
                    <img src="../images/logos/logo.png" alt="Virunga Ecotours">
                    <span>Community Programs</span>
                </div>
                <button class="mobile-nav-close" id="mobileNavClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mobile-nav-content">
                <ul class="mobile-nav-list">
                    <li class="mobile-nav-item">
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('community')) : 'index.php'; ?>" class="mobile-nav-link">
                            <i class="fas fa-home"></i>
                            Home
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="about.php" class="mobile-nav-link">
                            <i class="fas fa-users"></i>
                            About Us
                        </a>
                    </li>
                    <li class="mobile-nav-item has-submenu">
                        <a href="programs.php" class="mobile-nav-link">
                            <i class="fas fa-project-diagram"></i>
                            Programs
                            <i class="fas fa-chevron-down submenu-toggle"></i>
                        </a>
                        <ul class="mobile-submenu">
                            <li><a href="programs.php?country=rwanda">Rwanda Programs</a></li>
                            <li><a href="programs.php?country=uganda">Uganda Programs</a></li>
                            <li><a href="programs.php?country=congo">DRC Congo Programs</a></li>
                            <li><a href="programs.php?category=Education">Education</a></li>
                            <li><a href="programs.php?category=Health">Health</a></li>
                            <li><a href="programs.php?category=Conservation">Conservation</a></li>
                        </ul>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="impact.php" class="mobile-nav-link">
                            <i class="fas fa-envelope"></i>
                           Community Impact
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="activity.php" class="mobile-nav-link">
                            <i class="fas fa-envelope"></i>
                            Activities
                        </a>
                    </li>
                    <li class="mobile-nav-item">
                        <a href="contact.php" class="mobile-nav-link">
                            <i class="fas fa-envelope"></i>
                            Contact Us
                        </a>
                    </li>
                </ul>
                
                <div class="mobile-nav-actions">
                    <a href="volunteer.php?action=volunteer" class="mobile-action-btn volunteer">
                        <i class="fas fa-hands-helping"></i>
                        Volunteer With Us
                    </a>
                    <a href="contact.php?action=donate" class="mobile-action-btn donate">
                        <i class="fas fa-heart"></i>
                        Support Our Cause
                    </a>
                </div>
                
                <div class="mobile-nav-footer">
                    <div class="mobile-contact-info">
                        <a href="tel:+250784513435">
                            <i class="fas fa-phone"></i>
                            +(250) 784 513 435
                        </a>
                        <a href="mailto:community@virungaecotours.com">
                            <i class="fas fa-envelope"></i>
                            info@virungaecotours.com
                        </a>
                    </div>
                    
                    <div class="mobile-social-links">
                        <a href="https://www.facebook.com/VirungaPrograms" target="_blank" rel="noopener">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.instagram.com/virunga_ecotours" target="_blank" rel="noopener">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/virunga-ecotours-863a221b1" target="_blank" rel="noopener">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.youtube.com/@virungaecotours8285" target="_blank" rel="noopener">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Wrapper -->
    <main id="main-content" class="main-content">
        <!-- Page content will be inserted here -->
