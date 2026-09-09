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
    background-color: #122a1f;
    border-radius: 0;
    padding: 6px 12px;
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
  @media (max-width: 900px) {
    header {
      padding: 10px 0;
    }
    header.scrolled {
      padding: 6px 0;
    }
    .nav-shell {
      width: 100%;
      justify-content: space-between;
      background: rgba(18, 42, 31, 0.94);
      padding: 10px 16px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 0;
    }
    .brand .logo-full {
      display: none;
    }
    .brand .logo-icon {
      display: block;
      height: 48px;
    }
    header.scrolled .brand .logo-icon {
      height: 38px;
      padding: 2px;
    }
    .nav-links {
      position: fixed;
      top: 0;
      right: 0;
      left: auto;
      height: 100vh;
      width: min(320px, 85vw);
      background: var(--forest-deep);
      flex-direction: column;
      padding: 85px 24px 32px;
      transform: translateX(100%);
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s ease;
      gap: 22px;
      z-index: 1010;
      display: flex;
      visibility: hidden;
      box-shadow: -8px 0 30px rgba(0,0,0,0.35);
      overflow-y: auto;
    }
    .nav-links.open {
      transform: translateX(0);
      visibility: visible;
    }
    .nav-toggle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 38px;
      height: 38px;
      background: rgba(246,242,233,0.08);
      border-radius: 4px;
      margin-left: 6px;
    }
    .nav-close.open {
      display: inline-flex;
      z-index: 1025;
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

  /* Clean Google Translate Toolbar Suppression */
  .goog-te-banner-frame,
  iframe.goog-te-banner-frame,
  .goog-te-banner,
  #goog-gt-tt,
  #goog-gt-vt,
  .goog-te-balloon-frame {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    height: 0 !important;
  }
  body {
    top: 0px !important;
  }
  iframe.skiptranslate {
    display: none !important;
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
      <li><a href="<?php echo htmlspecialchars($baseLink('#signatures')); ?>">Signatures</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Virunga House</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('about')); ?>">Our Story</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('#journal')); ?>">Journal</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('#planner')); ?>" class="nav-cta-link" style="color: var(--gold-light, #deb862); font-weight: 600; letter-spacing: 0.04em;">PLAN YOUR JOURNEY</a></li>
      <li class="lang-dropdown notranslate" translate="no">
        <button class="lang-btn" id="langBtn" aria-label="Select Language">
          <span class="flag-icon" id="currentFlag">🇬🇧</span> <span id="currentLangText" style="font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">EN</span> <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
        </button>
        <ul class="lang-menu" id="langMenu" style="max-height: 320px; overflow-y: auto;">
          <li><a href="#" onclick="changeLanguage('en'); return false;"><span class="flag-icon">🇬🇧</span> English (EN)</a></li>
          <li><a href="#" onclick="changeLanguage('fr'); return false;"><span class="flag-icon">🇫🇷</span> Français (FR)</a></li>
          <li><a href="#" onclick="changeLanguage('es'); return false;"><span class="flag-icon">🇪🇸</span> Español (ES)</a></li>
          <li><a href="#" onclick="changeLanguage('pt'); return false;"><span class="flag-icon">🇵🇹</span> Português (PT)</a></li>
          <li><a href="#" onclick="changeLanguage('zh-CN'); return false;"><span class="flag-icon">🇨🇳</span> 中文 (ZH)</a></li>
          <li><a href="#" onclick="changeLanguage('ja'); return false;"><span class="flag-icon">🇯🇵</span> 日本語 (JA)</a></li>
          <li><a href="#" onclick="changeLanguage('it'); return false;"><span class="flag-icon">🇮🇹</span> Italian (IT)</a></li>
          <li><a href="#" onclick="changeLanguage('nl'); return false;"><span class="flag-icon">🇳🇱</span> Nederlands (NL)</a></li>
          <li><a href="#" onclick="changeLanguage('sv'); return false;"><span class="flag-icon">🇸🇪</span> Svenska (SV)</a></li>
          <li><a href="#" onclick="changeLanguage('no'); return false;"><span class="flag-icon">🇳🇴</span> Norsk (NO)</a></li>
          <li><a href="#" onclick="changeLanguage('da'); return false;"><span class="flag-icon">🇩🇰</span> Dansk (DA)</a></li>
          <li><a href="#" onclick="changeLanguage('ar'); return false;"><span class="flag-icon">🇸🇦</span> العربية (AR)</a></li>
          <li><a href="#" onclick="changeLanguage('ko'); return false;"><span class="flag-icon">🇰🇷</span> 한국어 (KO)</a></li>
          <li><a href="#" onclick="changeLanguage('hi'); return false;"><span class="flag-icon">🇮🇳</span> हिन्दी (HI)</a></li>
          <li><a href="#" onclick="changeLanguage('ru'); return false;"><span class="flag-icon">🇷🇺</span> Русский (RU)</a></li>
          <li><a href="#" onclick="changeLanguage('pl'); return false;"><span class="flag-icon">🇵🇱</span> Polski (PL)</a></li>
          <li><a href="#" onclick="changeLanguage('tr'); return false;"><span class="flag-icon">🇹🇷</span> Türkçe (TR)</a></li>
          <li><a href="#" onclick="changeLanguage('iw'); return false;"><span class="flag-icon">🇮🇱</span> עברית (HE)</a></li>
          <li><a href="#" onclick="changeLanguage('cs'); return false;"><span class="flag-icon">🇨🇿</span> Čeština (CS)</a></li>
          <li><a href="#" onclick="changeLanguage('fi'); return false;"><span class="flag-icon">🇫🇮</span> Suomi (FI)</a></li>
          <li><a href="#" onclick="changeLanguage('ro'); return false;"><span class="flag-icon">🇷🇴</span> Română (RO)</a></li>
          <li><a href="#" onclick="changeLanguage('id'); return false;"><span class="flag-icon">🇮🇩</span> Bahasa Indonesia (ID)</a></li>
          <li><a href="#" onclick="changeLanguage('ms'); return false;"><span class="flag-icon">🇲🇾</span> Bahasa Melayu (MS)</a></li>
          <li><a href="#" onclick="changeLanguage('sw'); return false;"><span class="flag-icon">🇰🇪</span> Kiswahili (SW)</a></li>
          <li><a href="#" onclick="changeLanguage('th'); return false;"><span class="flag-icon">🇹🇭</span> ไทย (TH)</a></li>
          <li><a href="#" onclick="changeLanguage('vi'); return false;"><span class="flag-icon">🇻🇳</span> Tiếng Việt (VI)</a></li>
          <li><a href="#" onclick="changeLanguage('uk'); return false;"><span class="flag-icon">🇺🇦</span> Українська (UK)</a></li>
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
      includedLanguages: 'en,fr,de,es,pt,zh-CN,ja,it,nl,sv,no,da,ar,ko,hi,ru,pl,tr,iw,cs,fi,ro,id,ms,sw,th,vi,uk',
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
        try {
          return val ? decodeURIComponent(val) : null;
        } catch(e) {
          return val || null;
        }
      }
      return null;
    }

    let currentLang = 'en';
    
    // Failsafe Language Detection (HTML attribute or Cookie or localStorage)
    const htmlLang = document.documentElement.lang || '';
    const transCookie = getCookie('googtrans');
    
    if (htmlLang && htmlLang !== 'en') {
      currentLang = htmlLang;
    } else if (transCookie && transCookie.indexOf('/en/') !== -1) {
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
          'fr': 'fr', 'es': 'es', 'pt': 'pt', 'it': 'it', 'nl': 'nl',
          'sv': 'sv', 'no': 'no', 'da': 'da', 'ar': 'ar', 'ko': 'ko',
          'hi': 'hi', 'ru': 'ru', 'pl': 'pl', 'tr': 'tr', 'he': 'iw',
          'iw': 'iw', 'cs': 'cs', 'fi': 'fi', 'ro': 'ro', 'id': 'id',
          'ms': 'ms', 'sw': 'sw', 'th': 'th', 'vi': 'vi', 'uk': 'uk',
          'ja': 'ja', 'de': 'de'
        };
        let detected = rawLang.startsWith('zh') ? 'zh-CN' : (rawLang.startsWith('ko') ? 'ko' : langMap[rawLang.substring(0, 2)]);
        if (detected && detected !== 'en') {
          changeLanguage(detected);
          return;
        }
      }
    }

    // Normalize language code
    currentLang = currentLang.toLowerCase();
    if (currentLang === 'zh-cn' || currentLang === 'zh-tw') currentLang = 'zh';
    if (currentLang === 'he') currentLang = 'iw';

    const flagMap = {
      'en': '🇬🇧', 'fr': '🇫🇷', 'es': '🇪🇸', 'pt': '🇵🇹', 'zh-CN': '🇨🇳', 'zh': '🇨🇳',
      'ja': '🇯🇵', 'it': '🇮🇹', 'nl': '🇳🇱', 'sv': '🇸🇪', 'no': '🇳🇴', 'da': '🇩🇰',
      'ar': '🇸🇦', 'ko': '🇰🇷', 'hi': '🇮🇳', 'ru': '🇷🇺', 'pl': '🇵🇱', 'tr': '🇹🇷',
      'iw': '🇮🇱', 'he': '🇮🇱', 'cs': '🇨🇿', 'fi': '🇫🇮', 'ro': '🇷🇴', 'id': '🇮🇩',
      'ms': '🇲🇾', 'sw': '🇰🇪', 'th': '🇹🇭', 'vi': '🇻🇳', 'uk': '🇺🇦', 'de': '🇩🇪'
    };

    const currentFlag = document.getElementById("currentFlag");
    const currentLangText = document.getElementById("currentLangText");
    if (currentFlag) {
      currentFlag.innerText = flagMap[currentLang] || '🇬🇧';
    }
    if (currentLangText) {
      currentLangText.innerText = (currentLang === 'zh-CN' || currentLang === 'zh' || currentLang === 'zh-cn') ? 'ZH' : currentLang.toUpperCase().split('-')[0];
    }

    // Highlight the active language element in the dropdown list
    const langMenu = document.getElementById("langMenu");
    if (langMenu) {
      const links = langMenu.querySelectorAll("a");
      links.forEach(link => {
        const onClickAttr = link.getAttribute("onclick") || "";
        const targetLang = (currentLang === 'zh' || currentLang === 'zh-cn' || currentLang === 'zh-tw') ? 'zh-CN' : currentLang;
        if (onClickAttr.includes(`changeLanguage('${targetLang}')`)) {
          link.style.backgroundColor = "rgba(201, 162, 75, 0.2)";
          link.style.color = "#c9a24b";
          link.style.fontWeight = "bold";
          link.classList.add("active");
        } else {
          link.style.backgroundColor = "";
          link.style.color = "";
          link.style.fontWeight = "";
          link.classList.remove("active");
        }
      });
    }

    const langBtn = document.getElementById("langBtn");

    if (langBtn && langMenu) {
      langBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        langMenu.classList.toggle("open");
      });

      document.addEventListener("click", () => {
        langMenu.classList.remove("open");
      });
    }

    // Subdirectory-Safe Service Worker Registration
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        const path = window.location.pathname.startsWith('/virungacorrection') 
          ? '/virungacorrection/sw.js' 
          : '/sw.js';
        navigator.serviceWorker.register(path).catch(() => {});
      });
    }
  });
</script>

<!-- Floating AI Assistant -->
<button type="button" id="ai-chat-btn" aria-label="Open Virunga Collective Assistant" title="Virunga Collective Assistant">
  <i class="fas fa-comments"></i>
</button>

<section id="ai-chat-modal" class="ai-chat-hidden" aria-label="Virunga Collective Assistant">
  <div class="ai-chat-header">
    <div class="ai-chat-title">
      <span class="ai-status-dot"></span>
      <strong>Virunga Collective Assistant</strong>
    </div>
    <button type="button" class="ai-chat-close" aria-label="Close assistant">&times;</button>
  </div>

  <div class="ai-chat-messages" id="ai-messages">
    <div class="ai-msg bot-msg">
      Hello! How can I help you explore Virunga Collective's tours, stays, activities, or shop items today?
    </div>
  </div>

  <form class="ai-chat-input-area" id="ai-chat-form">
    <input type="text" id="ai-user-input" placeholder="Ask about tours, stays, activities..." autocomplete="off" />
    <button type="submit">Send</button>
  </form>
</section>

<style>
#ai-chat-btn {
  position: fixed;
  bottom: 25px;
  right: 25px;
  width: 56px;
  height: 56px;
  background: #1b4332;
  color: #ffffff;
  border: 1px solid rgba(201, 162, 75, 0.45);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.45rem;
  box-shadow: 0 8px 25px rgba(18, 42, 31, 0.38);
  z-index: 99990;
  cursor: pointer;
  transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
}
#ai-chat-btn:hover {
  transform: scale(1.07);
  background: #2e7d32;
  box-shadow: 0 12px 30px rgba(18, 42, 31, 0.48);
}
#ai-chat-modal {
  position: fixed;
  bottom: 95px;
  right: 25px;
  width: 370px;
  max-width: calc(100vw - 32px);
  height: 500px;
  max-height: calc(100vh - 130px);
  background: #ffffff;
  border: 1px solid rgba(18, 42, 31, 0.12);
  border-radius: 12px;
  box-shadow: 0 16px 45px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
  z-index: 99991;
  overflow: hidden;
  font-family: inherit;
}
.ai-chat-hidden {
  display: none !important;
}
.ai-chat-header {
  background: #122a1f;
  color: #f6f2e9;
  padding: 14px 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 12px;
}
.ai-chat-title {
  display: flex;
  align-items: center;
  gap: 8px;
  min-width: 0;
}
.ai-chat-title strong {
  font-size: 0.96rem;
  line-height: 1.2;
}
.ai-status-dot {
  width: 8px;
  height: 8px;
  background: #4caf50;
  border-radius: 50%;
  flex: 0 0 auto;
}
.ai-chat-close {
  background: transparent;
  border: 0;
  color: #ffffff;
  font-size: 1.55rem;
  line-height: 1;
  cursor: pointer;
  padding: 0 2px;
}
.ai-chat-messages .bot-msg {
  background: #ffffff;
  color: #233127;
  align-self: flex-start;
  border: 1px solid rgba(18, 42, 31, 0.08);
  border-bottom-left-radius: 4px;
}
.wa-typing-container {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 0.88rem;
  color: #1b3a2b;
  font-weight: 500;
  padding: 2px 0;
}
.wa-typing-dots {
  display: inline-flex;
  align-items: center;
  gap: 4px;
}
.wa-dot {
  width: 6px;
  height: 6px;
  background-color: #1b3a2b;
  border-radius: 50%;
  display: inline-block;
  animation: waDotBounce 1.4s infinite ease-in-out both;
}
.wa-dot:nth-child(1) { animation-delay: 0s; }
.wa-dot:nth-child(2) { animation-delay: 0.2s; }
.wa-dot:nth-child(3) { animation-delay: 0.4s; }

@keyframes waDotBounce {
  0%, 60%, 100% {
    transform: translateY(0) scale(0.85);
    opacity: 0.35;
  }
  30% {
    transform: translateY(-5px) scale(1.2);
    opacity: 1;
  }
}
.user-msg {
  background: #2e7d32;
  color: #ffffff;
  align-self: flex-end;
  border-bottom-right-radius: 4px;
}
.ai-chat-messages {
  flex: 1;
  padding: 14px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #f8f7f2;
}
.ai-msg {
  max-width: 84%;
  padding: 10px 13px;
  border-radius: 14px;
  font-size: 0.9rem;
  line-height: 1.45;
  overflow-wrap: anywhere;
}
.bot-msg {
  background: #ffffff;
  color: #233127;
  align-self: flex-start;
  border: 1px solid rgba(18, 42, 31, 0.08);
  border-bottom-left-radius: 4px;
}
.user-msg {
  background: #2e7d32;
  color: #ffffff;
  align-self: flex-end;
  border-bottom-right-radius: 4px;
}
.ai-chat-input-area {
  display: flex;
  gap: 8px;
  padding: 10px;
  border-top: 1px solid rgba(18, 42, 31, 0.12);
  background: #ffffff;
}
.ai-chat-input-area input {
  flex: 1;
  min-width: 0;
  border: 1px solid rgba(18, 42, 31, 0.22);
  border-radius: 20px;
  padding: 9px 14px;
  outline: none;
  font-size: 0.9rem;
  font-family: inherit;
}
.ai-chat-input-area input:focus {
  border-color: #2e7d32;
}
.ai-chat-input-area button {
  background: #1b4332;
  color: #ffffff;
  border: 0;
  border-radius: 20px;
  padding: 9px 15px;
  cursor: pointer;
  font-size: 0.9rem;
  font-family: inherit;
}
.ai-chat-input-area button:disabled,
.ai-chat-input-area input:disabled {
  opacity: 0.65;
  cursor: wait;
}
@media (max-width: 768px) {
  #ai-chat-btn {
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    font-size: 1.25rem;
  }
  #ai-chat-modal {
    bottom: 82px;
    right: 16px;
    height: 460px;
  }
}
</style>
<script>
  (() => {
    const endpoint = "<?php echo isset($baseLink) ? htmlspecialchars($baseLink('chat_api.php'), ENT_QUOTES) : 'chat_api.php'; ?>";
    const button = document.getElementById('ai-chat-btn');
    const modal = document.getElementById('ai-chat-modal');
    const close = modal?.querySelector('.ai-chat-close');
    const form = document.getElementById('ai-chat-form');
    const input = document.getElementById('ai-user-input');
    const messages = document.getElementById('ai-messages');

    const chatHistory = [];

    const appendMessage = (content, type, isHTML = false) => {
      const div = document.createElement('div');
      div.className = `ai-msg ${type}-msg`;
      if (isHTML) {
        div.innerHTML = content;
      } else {
        div.textContent = content;
      }
      messages.appendChild(div);
      messages.scrollTop = messages.scrollHeight;
      return div;
    };

    const toggleChat = () => {
      modal.classList.toggle('ai-chat-hidden');
      if (!modal.classList.contains('ai-chat-hidden')) {
        input.focus();
      }
    };

    button?.addEventListener('click', toggleChat);
    close?.addEventListener('click', toggleChat);

    const parseMarkdown = (text) => {
      if (!text) return '';
      let str = text.trim();

      // Clean up stray markdown artifacts like :* or * :
      str = str.replace(/^[:*#\s]+/gm, '');

      str = str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/__(.*?)__/g, '<strong>$1</strong>')
        .replace(/\*([^\*]+)\*/g, '<em>$1</em>')
        .replace(/^[\s]*[-•*]\s+(.*)$/gm, '• $1')
        .replace(/\n/g, '<br>');

      return str;
    };

    form?.addEventListener('submit', async (event) => {
      event.preventDefault();
      const text = input.value.trim();
      if (!text) return;

      appendMessage(text, 'user');
      chatHistory.push({ role: 'user', text: text });

      input.value = '';
      input.disabled = true;
      form.querySelector('button').disabled = true;
      const botMessage = appendMessage('<span class="wa-typing-container">typing <span class="wa-typing-dots"><span class="wa-dot"></span><span class="wa-dot"></span><span class="wa-dot"></span></span></span>', 'bot', true);

      try {
        const response = await fetch(endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ message: text, history: chatHistory.slice(-8) }),
        });
        const data = await response.json();
        const reply = data.response || 'Sorry, I could not process that right now. Please contact us directly through our Contact page.';
        botMessage.innerHTML = parseMarkdown(reply);
        chatHistory.push({ role: 'bot', text: reply });
      } catch (error) {
        botMessage.textContent = 'Sorry, I am having trouble connecting right now. Please try again later or contact us directly through our Contact page.';
      } finally {
        input.disabled = false;
        form.querySelector('button').disabled = false;
        input.focus();
        messages.scrollTop = messages.scrollHeight;
      }
    });
  })();
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
