<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php $resolvedAssetBase = !empty($assetBase) ? $assetBase : './'; ?>
    <base href="<?php echo htmlspecialchars($resolvedAssetBase, ENT_QUOTES); ?>">
    <title><?php 
      $displayTitle = isset($pageTitle) ? $pageTitle : 'Virunga Homestay';
      if (isset($slug) && $slug !== 'home') {
        echo $displayTitle . ' | Virunga Homestay - Musanze Rwanda';
      } else {
        echo 'Virunga Homestay | Best Accommodation in Musanze, Rwanda';
      }
    ?></title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Experience authentic Rwandan hospitality at Virunga Homestay in Musanze. Perfect for gorilla trekking, volcano hikes, and cultural immersion. Book your stay today!'; ?>">
    <meta name="keywords" content="<?php echo isset($pageKeywords) ? $pageKeywords : 'homestay Musanze, Virunga accommodation, Rwanda gorilla trekking stay, Volcanoes National Park lodging, authentic Rwanda travel'; ?>">
    <?php
      $canonicalSlug = (isset($slug) && $slug !== 'home') ? $slug : '';
      $canonicalUrl = 'https://virungahomestay.com/' . $canonicalSlug;
    ?>
    <link rel="canonical" href="<?php echo $canonicalUrl; ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $canonicalUrl; ?>">
    <meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle : 'Virunga Homestay'; ?>">
    <meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : 'Experience the best stay at Virunga Homestay. Your perfect sanctuary in the heart of nature near Virunga volcanoes.'; ?>">
    <meta property="og:image" content="<?php echo 'https://virungahomestay.com/img/hero/room.jpg'; ?>">

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
      <a href="<?php echo $baseLink('homestays'); ?>" class="logo-wrap">
        <img
          src="./img/logo/logo.png"
          class="logo-full"
          alt="Virunga Homestay"
        />
        <img
          src="./img/logo/logo-small.png"
          class="logo-sm"
          alt="Virunga Homestay"
        />
      </a>

      <ul class="nav-links">
        <li><a href="<?php echo $baseLink('homestays'); ?>">Home</a></li>
        <li><a href="<?php echo $baseLink('rooms'); ?>">Stay</a></li>
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
