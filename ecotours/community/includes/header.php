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
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : '../../index.php'; ?>" class="top-link main-site" style="font-weight: 700; color: #c9a24b;">
                            <i class="fas fa-globe"></i>
                            Virunga Collective
                        </a>
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('homestays')) : '../../homestays'; ?>" class="top-link main-site">
                            <i class="fas fa-bed"></i>
                            Stays
                        </a>
                        <div class="lang-dropdown notranslate" translate="no" style="position: relative;">
                            <button class="lang-btn" id="langBtnCom" aria-label="Select Language" style="background: none; border: none; cursor: pointer; font-size: 0.85rem; color: #fff; font-weight: bold; display: flex; align-items: center; gap: 4px;">
                                <span class="flag-icon" id="currentFlagCom">🇬🇧</span> <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
                            </button>
                            <ul class="lang-menu" id="langMenuCom" style="position: absolute; top: 100%; right: 0; margin-top: 6px; background: #1b3a2b; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; list-style: none; padding: 6px 0; min-width: 165px; max-height: 320px; overflow-y: auto; display: none; z-index: 10010; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                <li><a href="#" onclick="changeLanguage('en'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇬🇧</span> English (EN)</a></li>
                                <li><a href="#" onclick="changeLanguage('fr'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇫🇷</span> Français (FR)</a></li>
                                <li><a href="#" onclick="changeLanguage('es'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇪🇸</span> Español (ES)</a></li>
                                <li><a href="#" onclick="changeLanguage('pt'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇵🇹</span> Português (PT)</a></li>
                                <li><a href="#" onclick="changeLanguage('zh-CN'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇨🇳</span> 中文 (ZH)</a></li>
                                <li><a href="#" onclick="changeLanguage('ja'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇯🇵</span> 日本語 (JA)</a></li>
                                <li><a href="#" onclick="changeLanguage('it'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇮🇹</span> Italian (IT)</a></li>
                                <li><a href="#" onclick="changeLanguage('nl'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇳🇱</span> Nederlands (NL)</a></li>
                                <li><a href="#" onclick="changeLanguage('sv'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇸🇪</span> Svenska (SV)</a></li>
                                <li><a href="#" onclick="changeLanguage('no'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇳🇴</span> Norsk (NO)</a></li>
                                <li><a href="#" onclick="changeLanguage('da'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇩🇰</span> Dansk (DA)</a></li>
                                <li><a href="#" onclick="changeLanguage('ar'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇸🇦</span> العربية (AR)</a></li>
                                <li><a href="#" onclick="changeLanguage('ko'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇰🇷</span> 한국어 (KO)</a></li>
                                <li><a href="#" onclick="changeLanguage('hi'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇮🇳</span> हिन्दी (HI)</a></li>
                                <li><a href="#" onclick="changeLanguage('ru'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇷🇺</span> Русский (RU)</a></li>
                                <li><a href="#" onclick="changeLanguage('pl'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇵🇱</span> Polski (PL)</a></li>
                                <li><a href="#" onclick="changeLanguage('tr'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇹🇷</span> Türkçe (TR)</a></li>
                                <li><a href="#" onclick="changeLanguage('iw'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇮🇱</span> עברית (HE)</a></li>
                                <li><a href="#" onclick="changeLanguage('cs'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇨🇿</span> Čeština (CS)</a></li>
                                <li><a href="#" onclick="changeLanguage('fi'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇫🇮</span> Suomi (FI)</a></li>
                                <li><a href="#" onclick="changeLanguage('ro'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇷🇴</span> Română (RO)</a></li>
                                <li><a href="#" onclick="changeLanguage('id'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇮🇩</span> Bahasa Indonesia (ID)</a></li>
                                <li><a href="#" onclick="changeLanguage('ms'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇲🇾</span> Bahasa Melayu (MS)</a></li>
                                <li><a href="#" onclick="changeLanguage('sw'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇰🇪</span> Kiswahili (SW)</a></li>
                                <li><a href="#" onclick="changeLanguage('th'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇹🇭</span> ไทย (TH)</a></li>
                                <li><a href="#" onclick="changeLanguage('vi'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇻🇳</span> Tiếng Việt (VI)</a></li>
                                <li><a href="#" onclick="changeLanguage('uk'); return false;" style="display: flex; align-items: center; gap: 8px; padding: 6px 14px; color: #fff; text-decoration: none; font-size: 0.85rem;"><span class="flag-icon">🇺🇦</span> Українська (UK)</a></li>
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
                        <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : '../../index.php'; ?>" class="logo-link" title="Virunga Collective - Main Homepage">
                            <img src="assets/images/logos/logo.jpg" alt="Virunga Collective" class="logo-img">
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

  function hideGoogleTranslateBanner() {
    const banners = document.querySelectorAll('.goog-te-banner-frame, iframe.goog-te-banner-frame, .goog-te-banner, iframe.skiptranslate');
    banners.forEach(b => {
      b.style.setProperty('display', 'none', 'important');
      b.style.setProperty('visibility', 'hidden', 'important');
      b.style.setProperty('height', '0px', 'important');
      b.style.setProperty('opacity', '0', 'important');
      b.style.setProperty('top', '-9999px', 'important');
    });
    if (document.body.style.top !== '0px') {
      document.body.style.setProperty('top', '0px', 'important');
    }
    if (document.documentElement.style.paddingTop !== '0px') {
      document.documentElement.style.setProperty('padding-top', '0px', 'important');
    }
  }
  setInterval(hideGoogleTranslateBanner, 50);

  function changeLanguage(langCode) {
    const val = (langCode && langCode !== 'en') ? '/en/' + langCode : '/en/en';
    const host = window.location.hostname;
    
    document.cookie = 'googtrans=' + val + '; path=/;';
    document.cookie = 'googtrans=' + val + '; path=/; domain=' + host;
    
    const parts = host.split('.');
    if (parts.length >= 2 && !/^\d+\.\d+\.\d+\.\d+$/.test(host) && host !== 'localhost') {
      const rootDomain = parts.slice(-2).join('.');
      document.cookie = 'googtrans=' + val + '; path=/; domain=.' + rootDomain;
    }

    try {
      localStorage.setItem('user_preferred_lang', langCode || 'en');
    } catch(e){}

    window.location.reload();
  }

  document.addEventListener("DOMContentLoaded", () => {
    function getCookie(name) {
      const value = `; ${document.cookie}`;
      const parts = value.split(`; ${name}=`);
      if (parts.length === 2) {
        const val = parts.pop().split(';').shift();
        return val ? decodeURIComponent(val) : null;
      }
      return null;
    }

    const transCookie = getCookie('googtrans');
    let currentLang = 'en';
    
    if (transCookie && transCookie.indexOf('/en/') !== -1) {
      const parts = transCookie.split('/');
      currentLang = parts[parts.length - 1] || 'en';
    } else {
      let savedLang = null;
      try { savedLang = localStorage.getItem('user_preferred_lang'); } catch(e){}
      
      const autoAttempted = sessionStorage.getItem('lang_auto_attempted');
      
      if (savedLang && savedLang !== 'en' && !autoAttempted) {
        sessionStorage.setItem('lang_auto_attempted', '1');
        changeLanguage(savedLang);
        return;
      } else if (!savedLang && !autoAttempted) {
        sessionStorage.setItem('lang_auto_attempted', '1');
        const rawLang = (navigator.language || navigator.userLanguage || '').toLowerCase();
        const langMap = {
          'fr': 'fr', 'de': 'de', 'es': 'es', 'it': 'it', 'nl': 'nl',
          'ja': 'ja', 'pt': 'pt'
        };
        let detected = rawLang.startsWith('zh') ? 'zh-CN' : langMap[rawLang.substring(0, 2)];
        if (detected && detected !== 'en') {
          changeLanguage(detected);
          return;
        }
      }
    }

    const flagMap = {
      'en': '🇬🇧', 'fr': '🇫🇷', 'de': '🇩🇪', 'es': '🇪🇸', 'it': '🇮🇹',
      'nl': '🇳🇱', 'zh-CN': '🇨🇳', 'zh': '🇨🇳', 'ja': '🇯🇵', 'pt': '🇵🇹', 'ko': '🇰🇷'
    };

    const activeFlag = document.getElementById("currentFlagCom");
    if (activeFlag) {
      activeFlag.innerText = flagMap[currentLang] || '🇬🇧';
    }

    const langBtnCom = document.getElementById("langBtnCom");
    const langMenuCom = document.getElementById("langMenuCom");

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
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
