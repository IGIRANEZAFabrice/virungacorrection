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
      <li><a href="<?php echo htmlspecialchars($baseLink('experiences')); ?>">Journeys</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('homestays')); ?>">Stays</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('ecotours/community')); ?>">Community Impact</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('membership')); ?>">Membership</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('about')); ?>">Story</a></li>
      <li><a href="<?php echo htmlspecialchars($baseLink('contact-us')); ?>">Enquire</a></li>
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

<!-- Floating WhatsApp Concierge Button -->
<a href="https://wa.me/250784513435?text=Hello%20Virunga%20Collective,%20I%20would%20like%20to%20enquire%20about%20a%20stay%20or%20guided%20journey." target="_blank" rel="noopener noreferrer" class="whatsapp-float-btn" title="Chat with Virunga Concierge on WhatsApp">
  <i class="fab fa-whatsapp"></i>
  <span class="wa-tooltip">Direct WhatsApp Concierge</span>
</a>

<style>
.whatsapp-float-btn {
  position: fixed;
  bottom: 25px;
  right: 25px;
  width: 56px;
  height: 56px;
  background: #25d366;
  color: #ffffff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.85rem;
  box-shadow: 0 8px 25px rgba(37, 211, 102, 0.4);
  z-index: 99990;
  transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
  text-decoration: none;
}
.whatsapp-float-btn:hover {
  transform: scale(1.1);
  box-shadow: 0 12px 30px rgba(37, 211, 102, 0.5);
  color: #ffffff;
}
.whatsapp-float-btn .wa-tooltip {
  position: absolute;
  right: 70px;
  background: #122a1f;
  color: #f6f2e9;
  font-size: 0.82rem;
  padding: 6px 14px;
  border-radius: 6px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease, transform 0.3s ease;
  transform: translateX(10px);
  border: 1px solid rgba(201, 162, 75, 0.3);
  font-family: sans-serif;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.whatsapp-float-btn:hover .wa-tooltip {
  opacity: 1;
  transform: translateX(0);
}
@media (max-width: 768px) {
  .whatsapp-float-btn {
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    font-size: 1.6rem;
  }
}
</style>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>