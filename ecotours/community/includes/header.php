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
                    <div class="top-bar-links" style="display: flex; align-items: center; gap: 12px;">
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : '../index.php'; ?>" class="top-link main-site">
                            <i class="fas fa-home"></i>
                            Main Website
                        </a>
                        <div class="lang-dropdown notranslate" translate="no" style="position: relative;">
                            <button class="lang-btn" id="langBtnCom" aria-label="Select Language" style="background: none; border: none; cursor: pointer; font-size: 0.85rem; color: #fff; font-weight: bold; display: flex; align-items: center; gap: 4px;">
                                <span class="flag-icon" id="currentFlagCom">🇬🇧</span> <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                            </button>
                            <ul class="lang-menu" id="langMenuCom" style="position: absolute; top: 100%; right: 0; margin-top: 6px; background: #1b3a2b; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; list-style: none; padding: 6px 0; min-width: 150px; max-height: 280px; overflow-y: auto; display: none; z-index: 10010; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                <li><a href="#" onclick="changeLanguage('en'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇬🇧</span> English (EN)</a></li>
                                <li><a href="#" onclick="changeLanguage('fr'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇫🇷</span> Français (FR)</a></li>
                                <li><a href="#" onclick="changeLanguage('de'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇩🇪</span> Deutsch (DE)</a></li>
                                <li><a href="#" onclick="changeLanguage('es'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇪🇸</span> Español (ES)</a></li>
                                <li><a href="#" onclick="changeLanguage('it'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇮🇹</span> Italiano (IT)</a></li>
                                <li><a href="#" onclick="changeLanguage('nl'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇳🇱</span> Nederlands (NL)</a></li>
                                <li><a href="#" onclick="changeLanguage('zh-CN'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇨🇳</span> 中文 (ZH)</a></li>
                                <li><a href="#" onclick="changeLanguage('ja'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇯🇵</span> 日本語 (JA)</a></li>
                                <li><a href="#" onclick="changeLanguage('pt'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇵🇹</span> Português (PT)</a></li>
                            </ul>
                        </div>
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

<!-- Google Translate Widget Container (Hidden) -->
<div id="google_translate_element" style="display:none;"></div>
<script type="text/javascript">
  function googleTranslateElementInit() {
    new google.translate.TranslateElement({
      pageLanguage: 'en',
      includedLanguages: 'en,fr,de,es,it,nl,zh-CN,ja,pt',
      autoDisplay: false
    }, 'google_translate_element');
  }

  function changeLanguage(langCode) {
    if (langCode === 'en') {
      document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + window.location.hostname;
      document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + window.location.hostname;
      try { localStorage.setItem('user_preferred_lang', 'en'); } catch(e){}
    } else {
      document.cookie = "googtrans=/en/" + langCode + "; path=/;";
      document.cookie = "googtrans=/en/" + langCode + "; path=/; domain=" + window.location.hostname;
      document.cookie = "googtrans=/en/" + langCode + "; path=/; domain=." + window.location.hostname;
      try { localStorage.setItem('user_preferred_lang', langCode); } catch(e){}
    }
    window.location.reload();
  }

  // Hide Google Translate toolbar and reset layout shift
  const hideGoogleTranslateBar = () => {
    const banner = document.querySelector(".goog-te-banner-frame") || document.querySelector("iframe.goog-te-banner-frame") || document.getElementById(":span.global");
    if (banner) {
      banner.style.display = "none";
      banner.style.visibility = "hidden";
    }
    document.body.style.top = "0px";
    document.body.style.position = "static";
    document.documentElement.style.paddingTop = "0px";
    
    const skipClasses = document.getElementsByClassName("skiptranslate");
    for (let i = 0; i < skipClasses.length; i++) {
      if (skipClasses[i].tagName === 'IFRAME') {
        skipClasses[i].style.display = "none";
        skipClasses[i].style.visibility = "hidden";
      }
    }
  };

  window.addEventListener("load", () => {
    hideGoogleTranslateBar();
    setInterval(hideGoogleTranslateBar, 150);
  });

  // Manage UI state and auto-detection
  document.addEventListener("DOMContentLoaded", () => {
    const currentFlagCom = document.getElementById("currentFlagCom");
    const langBtnCom = document.getElementById("langBtnCom");
    const langMenuCom = document.getElementById("langMenuCom");

    function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) return parts.pop().split(';').shift();
    }

    const transCookie = getCookie('googtrans');
    let savedLang = null;
    try { savedLang = localStorage.getItem('user_preferred_lang'); } catch(e){}

    let currentLang = 'en';
    if (transCookie) {
      const parts = transCookie.split('/');
      currentLang = parts[parts.length - 1] || 'en';
    } else if (savedLang) {
      currentLang = savedLang;
      if (savedLang !== 'en') {
        changeLanguage(savedLang);
        return;
      }
    } else {
      const rawLang = (navigator.language || navigator.userLanguage || '').toLowerCase();
      const langMap = {
        'fr': 'fr', 'de': 'de', 'es': 'es', 'it': 'it', 'nl': 'nl',
        'ja': 'ja', 'pt': 'pt'
      };
      
      let detected = null;
      if (rawLang.startsWith('zh')) {
        detected = 'zh-CN';
      } else {
        const prefix = rawLang.substring(0, 2);
        if (langMap[prefix]) {
          detected = langMap[prefix];
        }
      }
      
      if (detected && detected !== 'en') {
        changeLanguage(detected);
        return;
      }
    }

    const flagMap = {
      'en': '🇬🇧', 'fr': '🇫🇷', 'de': '🇩🇪', 'es': '🇪🇸', 'it': '🇮🇹',
      'nl': '🇳🇱', 'zh-CN': '🇨🇳', 'zh': '🇨🇳', 'ja': '🇯🇵', 'pt': '🇵🇹'
    };
    if (currentFlagCom) {
      currentFlagCom.innerText = flagMap[currentLang] || '🇬🇧';
    }

    if (langBtnCom && langMenuCom) {
      langBtnCom.addEventListener("click", (e) => {
        e.stopPropagation();
        langMenuCom.style.display = (langMenuCom.style.display === 'block') ? 'none' : 'block';
      });

      document.addEventListener("click", () => {
        langMenuCom.style.display = 'none';
      });
    }
  });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
