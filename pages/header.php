<style>
  /* ---------- Nav ---------- */
  header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 50;
    padding: 10px 0;
    transition:
      background 0.35s ease,
      padding 0.35s ease;
  }
  header.scrolled {
    background: rgba(18, 42, 31, 0.94);
    padding: 5px 0;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }
  header.scrolled .brand img {
    background-color: white;
    padding: 8px;
  }
  nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
  }
  .nav-shell {
    display: flex;
    align-items: center;
    gap: 18px;
  }
  .brand {
    font-family: var(--font-display);
    font-size: 1.3rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    color: var(--cream);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .brand .logo-full {
    display: block;
    height: 90px;
    width: auto;
    transition: all 0.35s ease;
  }
  .brand .logo-icon {
    display: none;
    height: 60px;
    width: auto;
    transition: all 0.35s ease;
  }
  header.scrolled .brand .logo-full {
    height: 60px;
  }
  .nav-links {
    display: flex;
    gap: 32px;
    list-style: none;
  }
  .nav-links a {
    position: relative;
    color: var(--cream);
    text-decoration: none;
    font-size: 0.92rem;
    letter-spacing: 0.03em;
    font-weight: 500;
    opacity: 0.88;
    transition: opacity 0.2s;
  }
  .nav-links a::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 1px;
    background: var(--gold);
    transition: width 0.25s ease;
  }
  .nav-links a:hover {
    opacity: 1;
  }
  .nav-links a:hover::after {
    width: 100%;
  }
  .nav-toggle {
    display: none;
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
  }
  .nav-toggle span {
    position: absolute;
    left: 50%;
    display: block;
    width: 18px;
    height: 2px;
    background: var(--cream);
    transform: translateX(-50%);
    transition:
      top 0.25s ease,
      transform 0.25s ease,
      opacity 0.2s ease;
  }
  .nav-close {
    display: none;
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1020;
    width: 42px;
    height: 42px;
    border: 1px solid rgba(246, 242, 233, 0.24);
    background: rgba(246, 242, 233, 0.08);
    color: var(--cream);
    font-size: 2rem;
    line-height: 1;
    cursor: pointer;
  }
  .nav-close.open {
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* ---------- Responsive for Nav ---------- */
  @media (max-width: 767px) {
    header {
      padding: 12px 0;
    }
    header.scrolled {
      padding: 8px 0;
    }
    .nav-shell {
      background: rgba(18, 42, 31, 0.94);
      padding: 10px 14px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      border-radius: 0;
    }
    .brand .logo-full {
      display: none;
    }
    .brand .logo-icon {
      display: block;
      height: 50px;
    }
    header.scrolled .brand .logo-icon {
      height: 40px;
      padding: 4px;
    }
    .nav-links {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 100vw;
      background: var(--forest-deep);
      flex-direction: column;
      padding: 96px 24px 32px;
      transform: translateX(100%);
      transition: transform 0.3s ease;
      gap: 26px;
      z-index: 1010;
      display: flex;
    }
    .nav-links.open {
      transform: translateX(0);
    }
    .nav-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      background: rgba(246,242,233,0.08);
      margin-left: 6px;
    }
    .nav-close.open {
      display: inline-flex;
    }
  }

  /* Language Selector styling */
  .lang-dropdown {
    position: relative;
    display: inline-block;
  }
  .lang-btn {
    background: none;
    border: none;
    color: var(--cream);
    font-size: 0.92rem;
    letter-spacing: 0.03em;
    font-weight: 500;
    opacity: 0.88;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 4px;
    transition: opacity 0.2s, color 0.2s;
    outline: none;
  }
  .lang-btn:hover {
    opacity: 1;
    color: var(--gold);
  }
  .lang-menu {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 12px;
    background: var(--forest-deep);
    border: 1px solid rgba(201, 162, 75, 0.3);
    border-radius: 8px;
    list-style: none;
    padding: 8px 0;
    min-width: 150px;
    max-height: 320px;
    overflow-y: auto;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    display: none;
    z-index: 10010;
  }
  .lang-menu.open {
    display: block;
    animation: langFadeInDown 0.2s ease;
  }
  .lang-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    color: var(--cream);
    text-decoration: none;
    font-size: 0.88rem;
    transition: background 0.2s, color 0.2s;
  }
  .lang-menu li a:hover {
    background: rgba(201, 162, 75, 0.15);
    color: var(--gold-light);
  }
  .flag-icon {
    font-size: 1.1rem;
    line-height: 1;
  }
  
  @keyframes langFadeInDown {
    from {
      opacity: 0;
      transform: translateY(-8px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Hide Google Translate Bar & Popups */
  .goog-te-banner-frame, 
  .goog-te-banner-frame.skiptranslate,
  .goog-te-balloon-frame,
  .goog-te-menu-value,
  #google_translate_element {
    display: none !important;
  }
  body {
    top: 0px !important;
  }
  font {
    background-color: transparent !important;
    box-shadow: none !important;
  }

  /* Responsive styling for Mobile Nav */
  @media (max-width: 767px) {
    .lang-dropdown {
      margin-top: 10px;
      width: 100%;
    }
    .lang-btn {
      width: 100%;
      justify-content: space-between;
      padding: 8px 0;
    }
    .lang-menu {
      position: static;
      box-shadow: none;
      border: none;
      background: rgba(255,255,255,0.03);
      margin-top: 6px;
      width: 100%;
      display: none;
    }
    .lang-menu.open {
      display: block;
    }
  }
</style>

<header id="siteHeader">
  <nav class="wrap">
    <div class="nav-shell">
      <a href="<?php echo htmlspecialchars($baseLink('')); ?>" class="brand">
        <img src="<?php echo htmlspecialchars($baseLink('img/logo.png')); ?>" alt="Virunga Collective Logo" class="logo-full">
        <img src="<?php echo htmlspecialchars($baseLink('img/icon.png')); ?>" alt="Virunga Collective Icon" class="logo-icon">
      </a>
      <button
        class="nav-toggle"
        id="navToggle"
        aria-label="Open menu"
        aria-controls="navLinks"
        aria-expanded="false"
      >
        <span style="top: 12px;"></span><span style="top: 18px;"></span><span style="top: 24px;"></span>
      </button>
    </div>
    <ul class="nav-links" id="navLinks">
      <li><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">Journeys</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Stays</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">Community Impact</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('membership')); ?>">Membership</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('about-us')); ?>">Story</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('contact-us')); ?>">Enquire</a></li>
      <li class="lang-dropdown notranslate" translate="no">
        <button class="lang-btn" id="langBtn" aria-label="Select Language">
          <span class="flag-icon" id="currentFlag">🇬🇧</span> <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
        </button>
        <ul class="lang-menu" id="langMenu">
          <li><a href="#" onclick="changeLanguage('en'); return false;"><span class="flag-icon">🇬🇧</span> English (EN)</a></li>
          <li><a href="#" onclick="changeLanguage('fr'); return false;"><span class="flag-icon">🇫🇷</span> Français (FR)</a></li>
          <li><a href="#" onclick="changeLanguage('de'); return false;"><span class="flag-icon">🇩🇪</span> Deutsch (DE)</a></li>
          <li><a href="#" onclick="changeLanguage('es'); return false;"><span class="flag-icon">🇪🇸</span> Español (ES)</a></li>
          <li><a href="#" onclick="changeLanguage('it'); return false;"><span class="flag-icon">🇮🇹</span> Italiano (IT)</a></li>
          <li><a href="#" onclick="changeLanguage('nl'); return false;"><span class="flag-icon">🇳🇱</span> Nederlands (NL)</a></li>
          <li><a href="#" onclick="changeLanguage('zh-CN'); return false;"><span class="flag-icon">🇨🇳</span> 中文 (ZH)</a></li>
          <li><a href="#" onclick="changeLanguage('ja'); return false;"><span class="flag-icon">🇯🇵</span> 日本語 (JA)</a></li>
          <li><a href="#" onclick="changeLanguage('pt'); return false;"><span class="flag-icon">🇵🇹</span> Português (PT)</a></li>
        </ul>
      </li>
    </ul>
    <button
      class="nav-close"
      id="navClose"
      aria-label="Close menu"
      aria-controls="navLinks"
    >
      &times;
    </button>
  </nav>
</header>

<script>
  // Header scroll effect
  (function() {
    function handleHeaderScroll() {
      const header = document.getElementById('siteHeader');
      if (header) {
        const scrolled = (window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0) > 15;
        if (scrolled) {
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
        }
      }
    }
    window.addEventListener('scroll', handleHeaderScroll, { passive: true });
    window.addEventListener('DOMContentLoaded', handleHeaderScroll);
    handleHeaderScroll();
  })();

  // Mobile nav toggle
  const navToggle = document.getElementById('navToggle');
  const navClose = document.getElementById('navClose');
  const navLinks = document.getElementById('navLinks');

  if (navToggle && navClose && navLinks) {
    function setMenuState(isOpen) {
      navLinks.classList.toggle('open', isOpen);
      navClose.classList.toggle('open', isOpen);
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    navToggle.addEventListener('click', () => {
      setMenuState(!navLinks.classList.contains('open'));
    });
    navClose.addEventListener('click', () => {
      setMenuState(false);
    });
    navLinks.querySelectorAll('a').forEach((link) => {
      link.addEventListener('click', () => {
        setMenuState(false);
      });
    });
  }
</script>

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
    
    // Also remove the native Google Translate iframe wrapper class if present
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
    // Run periodically to prevent late loads from shifting the page
    setInterval(hideGoogleTranslateBar, 150);
  });

  // Manage UI state and auto-detection
  document.addEventListener("DOMContentLoaded", () => {
    const currentFlag = document.getElementById("currentFlag");
    const langBtn = document.getElementById("langBtn");
    const langMenu = document.getElementById("langMenu");

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
      // Auto-detect search engine / browser language on first visit
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

    // Update Flag
    const flagMap = {
      'en': '🇬🇧', 'fr': '🇫🇷', 'de': '🇩🇪', 'es': '🇪🇸', 'it': '🇮🇹',
      'nl': '🇳🇱', 'zh-CN': '🇨🇳', 'zh': '🇨🇳', 'ja': '🇯🇵', 'pt': '🇵🇹'
    };
    if (currentFlag) {
      currentFlag.innerText = flagMap[currentLang] || '🇬🇧';
    }

    // Toggle Dropdown menu
    if (langBtn && langMenu) {
      langBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        langMenu.classList.toggle("open");
      });

      document.addEventListener("click", () => {
        langMenu.classList.remove("open");
      });
    }
  });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>