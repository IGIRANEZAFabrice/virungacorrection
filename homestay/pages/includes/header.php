<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php $resolvedAssetBase = !empty($assetBase) ? $assetBase : './'; ?>
    <base href="<?php echo htmlspecialchars($resolvedAssetBase, ENT_QUOTES); ?>">
    <title><?php 
      if (isset($pageTitle) && !empty($pageTitle)) {
        echo htmlspecialchars($pageTitle);
      } else {
        echo 'Virunga House & Luxury Homestay Musanze | Virunga Collective';
      }
    ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Experience boutique luxury hospitality in Musanze at Virunga House. Authentic Rwandan warmth, volcano views, and bespoke immersion near Volcanoes National Park.'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? htmlspecialchars($pageKeywords) : 'Virunga Homestay, luxury homestay Musanze, Virunga House, Volcanoes National Park accommodation, boutique stay Rwanda'; ?>">
    <?php
      $canonicalSlug = (isset($slug) && $slug !== 'home') ? $slug : 'homestays';
      $canonicalUrl = 'https://virungacollective.com/' . $canonicalSlug;
    ?>
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Virunga House & Luxury Homestay'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? htmlspecialchars($pageDescription) : 'Experience boutique luxury hospitality in Musanze at Virunga House.'; ?>">
    <meta property="og:image" content="https://virungacollective.com/img/about.jpeg">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'Virunga Homestay'; ?>">
    <meta property="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Experience authentic Rwandan hospitality at Virunga Homestay in Musanze. Perfect for gorilla trekking, volcano hikes, and cultural immersion. Book your stay today!'; ?>">
    <meta property="twitter:image" content="<?php echo 'https://virungahomestay.com/img/hero/room.jpg'; ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="img/logo/logo-small.png">
    <link rel="shortcut icon" href="img/logo/logo-small.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1b3a2b">

    <!-- Webmaster Tools Verification -->
    <meta name="google-site-verification" content="lJq7E1iB-kVBSsouRewk9b9SRn0d2aBHCe3D7C96HPo" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Jost:wght@300;400;500;600&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="./css/theme.css" />
    <link rel="stylesheet" href="./css/main.css" />
    <?php if (!empty($pageCss) && is_array($pageCss)) foreach ($pageCss as $css): ?>
      <link rel="stylesheet" href="./css/<?php echo $css; ?>" />
    <?php endforeach; ?>

    <style>
      /* Language Selector styling */
      .lang-dropdown {
        position: relative;
        display: inline-block;
      }
      .lang-btn {
        background: none;
        border: none;
        color: #fdfaf7;
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
        color: #c8711a;
      }
      .lang-menu {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 12px;
        background: #150f0b;
        border: 1px solid rgba(200, 113, 26, 0.3);
        border-radius: 8px;
        list-style: none;
        padding: 8px 0;
        min-width: 150px;
        max-height: 320px;
        overflow-y: auto;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
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
        color: #fdfaf7;
        text-decoration: none;
        font-size: 0.88rem;
        transition: background 0.2s, color 0.2s;
      }
      .lang-menu li a:hover {
        background: rgba(200, 113, 26, 0.15);
        color: #e49e54;
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
    </style>

    <!-- JSON-LD Schema Markup -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LodgingBusiness",
      "name": "Virunga Homestay",
      "image": "https://virungahomestay.com/img/logo/logo.png",
      "@id": "https://virungahomestay.com",
      "url": "https://virungahomestay.com",
      "telephone": "+250784513435",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Musanze",
        "addressLocality": "Musanze",
        "addressRegion": "Northern Province",
        "addressCountry": "RW"
      },
      "geo": {
        "@type": "GeoCoordinates",
        "latitude": -1.5000,
        "longitude": 29.6333
      },
      "url": "https://virungahomestay.com",
      "priceRange": "$$",
      "description": "Premium homestay experience at the foot of the Virunga Volcanoes in Musanze, Rwanda.",
      "amenityFeature": [
        {
          "@type": "LocationFeatureSpecification",
          "name": "Free WiFi",
          "value": true
        },
        {
          "@type": "LocationFeatureSpecification",
          "name": "Breakfast Included",
          "value": true
        },
        {
          "@type": "LocationFeatureSpecification",
          "name": "Private Bathroom",
          "value": true
        },
        {
          "@type": "LocationFeatureSpecification",
          "name": "Hot Shower",
          "value": true
        }
      ]
    }
    </script>
  </head>
  <body>
    <!-- --- NAV -------------------------------------------------- -->
    <nav id="mainNav">
      <a href="<?php echo $baseLink('home'); ?>" class="logo-wrap" title="Virunga Collective - Main Homepage">
        <img
          src="./img/logo/logo.png"
          class="logo-full"
          alt="Virunga Collective"
        />
        <img
          src="./img/logo/logo-small.png"
          class="logo-sm"
          alt="Virunga Collective"
        />
      </a>

      <ul class="nav-links">
        <li><a href="<?php echo $baseLink('home'); ?>"><i class="fas fa-globe" style="font-size: 0.8rem; margin-right: 4px; color: var(--color-primary);"></i> Collective Home</a></li>
        <li><a href="<?php echo $baseLink('homestays'); ?>">Home</a></li>
        <li><a href="<?php echo $baseLink('rooms'); ?>">Our Rooms</a></li>
        <li><a href="<?php echo $baseLink('activity'); ?>">Experiences</a></li>
        <li><a href="<?php echo $baseLink('impact'); ?>">Impact</a></li>
        <!-- -- Services dropdown -- -->
        <li class="has-dropdown">
          <a href="" tabindex="0">
            Services
            <span class="chevron" aria-hidden="true"></span>
          </a>
          <div class="dropdown" role="menu">
            <a href="<?php echo $baseLink('shop'); ?>" class="dropdown-item" role="menuitem"> Shop </a>
            <a href="<?php echo $baseLink('carrent'); ?>" class="dropdown-item" role="menuitem">
              Car Rent
            </a>
          </div>
        </li>
        <li><a href="<?php echo $baseLink('about-us'); ?>">Story</a></li>
        <li><a href="<?php echo $baseLink('safety'); ?>">Safety</a></li>
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
        <li class="cta-link"><a href="<?php echo $baseLink('contact'); ?>">Book Stay</a></li>
      </ul>

      <button
        class="hamburger"
        id="hamburger"
        aria-label="Toggle menu"
        aria-expanded="false"
      >
        <span></span><span></span><span></span>
      </button>
    </nav>

    <script>
      (function() {
        function checkNavScroll() {
          const nav = document.getElementById("mainNav");
          if (!nav) return;
          const isScrolled = (window.scrollY || window.pageYOffset || document.documentElement.scrollTop || 0) > 15;
          if (isScrolled) {
            nav.classList.add("scrolled");
          } else {
            nav.classList.remove("scrolled");
          }
        }
        window.addEventListener("scroll", checkNavScroll, { passive: true });
        window.addEventListener("DOMContentLoaded", checkNavScroll);
        checkNavScroll();
      })();
    </script>

    <!-- -- mobile drawer -- -->
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
      <ul>
        <li><a href="<?php echo $baseLink('homestays'); ?>">Home</a></li>
        <li><a href="<?php echo $baseLink('impact'); ?>">Impact</a></li>
        <li><a href="<?php echo $baseLink('about-us'); ?>">Story</a></li>
        <li><a href="<?php echo $baseLink('rooms'); ?>">Rooms</a></li>
        <li><a href="<?php echo $baseLink('safety'); ?>">Safety</a></li>

        <!-- Services accordion -->
        <li style="border-bottom: 1px solid var(--color-border-dark)">
          <button
            class="mob-services-toggle"
            id="mobServicesBtn"
            aria-expanded="false"
          >
            Services
            <span class="mob-chevron" aria-hidden="true"></span>
          </button>
          <div class="mob-services-panel" id="mobServicesPanel">
            <a href="<?php echo $baseLink('shop'); ?>">Shop</a>
            <a href="<?php echo $baseLink('carrent'); ?>">Car Rent</a>
            <a href="<?php echo $baseLink('activity'); ?>">Community Activities</a>
          </div>
        </li>

        <li><a href="<?php echo $baseLink('blog'); ?>">Blogs</a></li>
        <li><a href="<?php echo $baseLink('rules'); ?>">House Rules</a></li>
        <li><a href="<?php echo $baseLink('contact'); ?>">Contact Us</a></li>
      </ul>
    </div>

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
  });
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
