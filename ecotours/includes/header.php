<!-- Top Bar -->
<div class="top-bar">
  <div class="trustpilot">
    <a href="mailto:info@virungaecotours.com" rel="noopener noreferrer"
      >info@virungaecotours.com</a
    >
    <span class="separator">|</span>
    <a
      href="https://www.tripadvisor.com/Attraction_Review-g317075-d21346700-Reviews-VIRUNGA_ECOTOURS-Ruhengeri_Musanze_District_Northern_Province.html"
      target="_blank"
      rel="noopener noreferrer"
      class="tripadvisor-link"
    >
      <img
        src="./images/tripadvisor/logosmall.png"
        alt="TripAdvisor"
        class="tripadvisor-mobile"
      />
      <img
        src="./images/tripadvisor/logo.png"
        alt="TripAdvisor"
        class="tripadvisor-desktop"
      />
    </a>
  </div>
  <div class="top-nav" style="display: flex; align-items: center; gap: 15px;">
    <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : '../index.php'; ?>" style="font-weight: 700; color: #c9a24b;"><i class="fas fa-globe" style="margin-right: 4px;"></i> Virunga Collective</a>
    <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('homestays')) : '../homestays'; ?>">Stays</a>
    <a href="./pages/activity.php">Beyond The Park Experience</a>
    <a href="./pages/gallery.php">Our Gallery</a>
    <a href="./pages/faq-page.php">Faqs</a>
    <div class="lang-dropdown notranslate" translate="no" style="position: relative; margin-left: 15px;">
      <button class="lang-btn" id="langBtnEco" aria-label="Select Language" style="background: none; border: none; cursor: pointer; font-size: 0.85rem; color: var(--text-medium); font-weight: bold; display: flex; align-items: center; gap: 4px;">
        <span class="flag-icon" id="currentFlagEco">🇬🇧</span> <span id="currentLangTextEco" style="font-size: 0.85rem; font-weight: bold; text-transform: uppercase;">EN</span> <i class="fas fa-chevron-down" style="font-size: 0.7rem;"></i>
      </button>
      <ul class="lang-menu" id="langMenuEco" style="position: absolute; top: 100%; right: 0; margin-top: 6px; background: #1f3123; border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; list-style: none; padding: 6px 0; min-width: 165px; max-height: 320px; overflow-y: auto; display: none; z-index: 10010; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
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

<!-- Header -->
<div class="header">
  <div class="logo">
    <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('home')) : './index.php'; ?>">
      <img src="./images/logos/logo.png" alt="Virunga Ecotours Logo" />
    </a>
  </div>

  <!-- Mobile Menu Button -->
  <div class="mobile-menu-toggle">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <div class="contact-info">
    <div class="nav-phone-number">
      <a href="tel:0784513435" rel="noopener noreferrer">+(250) 784 513 435</a
      ><br /><span>OUR OFFICE IS OPEN 9:00AM - 6:00PM</span>
    </div>
    <div class="closed">OR</div>
    <a href="./pages/build.php" rel="noopener noreferrer"
      ><button class="quote-btn">Explore Experiences</button></a
    >
  </div>
</div>

<!-- Mobile Side Buttons -->
<div class="mobile-side-buttons">
  <div class="mobile-side-btn">
    <a href="https://wa.me/250784513435" target="_blank">
      <i class="fa-brands fa-whatsapp"></i>
      <span>Whatsapp</span>
    </a>
  </div>
  <div class="mobile-side-btn">
    <a href="tel:+250784513435">
      <i class="fas fa-phone"></i>
      <span>Calls</span>
    </a>
  </div>
  <div class="mobile-side-btn">
    <a href="mailto:info@virungaecotours.com">
      <i class="fas fa-envelope"></i>
      <span>Mail</span>
    </a>
  </div>
</div>

<!-- Main Navigation -->
<div class="main-nav">
  <div class="nav-item">
    <span>EXPERIENCES</span> <i class="fas fa-chevron-down"></i>
    <div class="dropdown">
      <div class="dropdown-container">
        <div class="destination-group">
          <div class="nav-item-with-nested">
            <a href="./pages/gorilla.php" class="has-sub">Wildlife Encounters</a>
            <div class="nested-dropdown">
              <div class="nested-dropdown-container">
                <a href="./pages/gorilla.php">Gorilla</a>
                <a href="./pages/birdwatching.php">Birdwatching</a>
                <a href="./pages/safari.php">Safari</a>
                <a href="./pages/coffee.php">Coffee Tours </a>
              </div>
            </div>
          </div>
          <div class="nav-item-with-nested">
            <a href="./pages/museums.php" class="has-sub">Cultural Discovery</a>
            <div class="nested-dropdown">
              <div class="nested-dropdown-container">
                <a href="./pages/museums.php">Museum</a>
                <a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('ecotours/community')) : './community/index.php'; ?>">Community</a>
              </div>
            </div>
          </div>
          <div class="nav-item-with-nested">
            <a href="./pages/cycling.php" class="has-sub">Adventure Collection</a>
            <div class="nested-dropdown">
              <div class="nested-dropdown-container">
                <a href="./pages/cycling.php">Cycling</a>
                
                <a href="./pages/trek.php">Trek</a>
              </div>
            </div>
          </div>
          <div class="nav-item-with-nested">
            <a href="./pages/Carhire.php" class="has-sub">VIRUNGA TRAVEL DESIGN & LOGISTICS</a>
            <div class="nested-dropdown">
              <div class="nested-dropdown-container">
                <a href="./pages/carhire.php">Car Hire</a>
              </div>
            </div>
          </div>
          <a href="./pages/highend.php">LUXURY VIRUNGA IMMERSIVE JOURNEYS</a>
          <a href="./pages/budget.php">ESSENTIAL VIRUNGA EXPLORER JOURNEYS</a>
          <a href="./pages/midrange.php">SIGNATURE VIRUNGA JOURNEYS</a>
          <a href="./pages/evacuation.php">VIRUNGA JOURNEY ASSURANCE & SAFETY SUPPORT</a>
        </div>
      </div>
    </div>
  </div>
  <div class="nav-item">
    <span>Rwanda</span> <i class="fas fa-chevron-down"></i>
    <div class="dropdown">
      <div class="dropdown-container">
        <div class="destination-group">
          <a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a>
          <a href="./pages/itenary.php?country=rwanda&type=day"
            >IMMERSIVE DAY EXPERIENCES</a
          >
          <a href="./pages/itenary.php?country=rwanda&type=multi"
            >MULTI-DAY IMMERSIVE JOURNEYS</a
          >
          <a href="./pages/itenary.php?country=rwanda"
            >VIRUNGA JOURNEY STYLES</a
          >
          <a href="./pages/styleguide.php?country=rwanda"
            >VIRUNGA DESTINATION GUIDE</a
          >
        </div>
      </div>
    </div>
  </div>

  <div class="nav-item">
    <span>Uganda</span> <i class="fas fa-chevron-down"></i>
    <div class="dropdown">
      <div class="dropdown-container">
        <div class="destination-group">
          <a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a>
          <a href="./pages/itenary.php?country=uganda&type=day"
            >IMMERSIVE DAY EXPERIENCES</a
          >
          <a href="./pages/itenary.php?country=uganda&type=multi"
            >MULTI-DAY IMMERSIVE JOURNEYS</a
          >
          <a href="./pages/itenary.php?country=uganda"
            >VIRUNGA JOURNEY STYLES</a
          >
          <a href="./pages/styleguide.php?country=uganda"
            >VIRUNGA DESTINATION GUIDE</a
          >
        </div>
      </div>
    </div>
  </div>

  <div class="nav-item">
    <span>DRCongo</span> <i class="fas fa-chevron-down"></i>
    <div class="dropdown">
      <div class="dropdown-container">
        <div class="destination-group">
          <a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a>
          <a href="./pages/itenary.php?country=congo&type=day"
            >IMMERSIVE DAY EXPERIENCES</a
          >
          <a href="./pages/itenary.php?country=congo&type=multi"
            >MULTI-DAY IMMERSIVE JOURNEYS</a
          >
          <a href="./pages/styleguide.php?country=congo"
            >VIRUNGA DESTINATION GUIDE</a
          >
        </div>
      </div>
    </div>
  </div>

  <div class="nav-item">
    <span><a href="./pages/blog.php">Blog</a></span>
  </div>
  <div class="nav-item">
    <span><a href="./pages/about.php">About Us</a></span>
  </div>

  <div class="nav-item">
    <span><a href="./pages/contactus.php">Contact us</a></span>
  </div>
  

  <div class="search-btn">
    <a
      href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('ecotours/community')) : './community/index.php'; ?>"
      target="_blank"
      rel="noopener noreferrer"
      style="
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
      "
    >
      Community Impact
    </a>
  </div>
</div>

<!-- Mobile Navigation Menu -->
<div class="mobile-nav">
  <div class="mobile-nav-header">
    <div class="mobile-close"><i class="fa-solid fa-xmark"></i></div>
  </div>
  <div class="mobile-nav-content">
    <!-- Top Nav Links -->
    <div class="search-btn">
      <a
        href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('ecotours/community')) : './community/index.php'; ?>"
        target="_blank"
        rel="noopener noreferrer"
        style="
          display: flex;
          align-items: center;
          justify-content: center;
          gap: 0.5rem;
        "
      >
        Community Impact
      </a>
    </div>
    <div class="mobile-top-links">
      <a href="./pages/blog.php">Blog</a>
      <a href="./pages/gallery.php">Our Gallery</a>
      <a href="./pages/faq-page.php">Faqs</a>
      <a href="./pages/about.php">About us</a>
      <a href="./pages/contactus.php">Contact us</a>
      <a
        href="https://www.tripadvisor.com/Attraction_Review-g317075-d21346700-Reviews-VIRUNGA_ECOTOURS-Ruhengeri_Musanze_District_Northern_Province.html"
        target="_blank"
        rel="noopener noreferrer"
        class="mobile-tripadvisor-link"
      >
        <img src="./images/tripadvisor/logosmall.png" alt="TripAdvisor" />
        TripAdvisor
      </a>
    </div>

    <!-- Main Nav Items -->
    <div class="mobile-main-links">
      <div class="mobile-nav-item">
        <div class="mobile-nav-title">
          <span>Rwanda</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="mobile-dropdown">
          <div class="mobile-links-list">
            <ul>
              <li><a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a></li>
              <li>
                <a href="./pages/itenary.php?country=rwanda&type=day"
                  > IMMERSIVE DAY EXPERIENCES</a
                >
              </li>
              <li>
                <a href="./pages/itenary.php?country=rwanda&type=multi"
                  >MULTI-DAY IMMERSIVE JOURNEYS</a
                >
              </li>
              <li>
                <a href="./pages/itenary.php?country=rwanda"
                  >VIRUNGA JOURNEY STYLES</a
                >
              </li>
              <li>
                <a href="./pages/styleguide.php?country=rwanda"
                  >VIRUNGA DESTINATION GUIDE</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="mobile-nav-item">
        <div class="mobile-nav-title">
          <span>Uganda</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="mobile-dropdown">
          <div class="mobile-links-list">
            <ul>
              <li><a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a></li>
              <li>
                <a href="./pages/itenary.php?country=uganda&type=day"
                  >IMMERSIVE DAY EXPERIENCES</a
                >
              </li>
              <li>
                <a href="./pages/itenary.php?country=uganda&type=multi"
                  >MULTI-DAY IMMERSIVE JOURNEYS</a
                >
              </li>
              <li>
                <a href="./pages/itenary.php?country=uganda"
                  >VIRUNGA JOURNEY STYLES</a
                >
              </li>
              <li>
                <a href="./pages/styleguide.php?country=uganda"
                  >VIRUNGA DESTINATION GUIDE</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="mobile-nav-item">
        <div class="mobile-nav-title">
          <span>DRCongo</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="mobile-dropdown">
          <div class="mobile-links-list">
            <ul>
              <li>
                <a href="./pages/build.php">PLAN YOUR VIRUNGA JOURNEY</a>
              </li>
              <li>
                <a href="./pages/itenary.php?country=congo&type=day"
                  >IMMERSIVE DAY EXPERIENCES</a
                >
              </li>
              <li>
                <a href="./pages/itenary.php?country=congo&type=multi"
                  >VIRUNGA JOURNEY STYLES</a
                >
              </li>
              <li>
                <a href="./pages/styleguide.php?country=congo"
                  >VIRUNGA DESTINATION GUIDE</a
                >
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="mobile-nav-item">
        <div class="mobile-nav-title">
          <span>EXPERIENCES</span>
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="mobile-dropdown">
          <div class="mobile-links-list">
            <ul>
              <li class="mobile-nested-item">
                <div class="mobile-nested-title">
                  <span>Wildlife Encounters</span>
                  <i class="fas fa-chevron-right"></i>
                </div>
                <div class="mobile-nested-dropdown">
                  <ul>
                    <li><a href="./pages/gorilla.php">Gorilla</a></li>
                    <li><a href="./pages/birdwatching.php">Birdwatching</a></li>
                    <li><a href="./pages/safari.php">Safari</a></li>
                  </ul>
                </div>
              </li>
              <li class="mobile-nested-item">
                <div class="mobile-nested-title">
                  <span>Cultural Discovery</span>
                  <i class="fas fa-chevron-right"></i>
                </div>
                <div class="mobile-nested-dropdown">
                  <ul>
                    <li><a href="./pages/museums.php">Museum</a></li>
                    <li><a href="<?php echo isset($baseLink) ? htmlspecialchars($baseLink('ecotours/community')) : './community/index.php'; ?>">Community</a></li>
                  </ul>
                </div>
              </li>
              <li class="mobile-nested-item">
                <div class="mobile-nested-title">
                  <span>Adventure Collection</span>
                  <i class="fas fa-chevron-right"></i>
                </div>
                <div class="mobile-nested-dropdown">
                  <ul>
                    <li><a href="./pages/cycling.php">Cycling</a></li>
                  
                    <li><a href="./pages/trek.php">Trek</a></li>
                  </ul>
                </div>
              </li>
              <li>
                <a href="./pages/highend.php">LUXURY VIRUNGA IMMERSIVE JOURNEYS</a>
              </li>
              <li>
                <a href="./pages/budget.php">ESSENTIAL VIRUNGA EXPLORER JOURNEYS</a>
              </li>
              <li>
                <a href="./pages/midrange.php">SIGNATURE VIRUNGA JOURNEYS</a>
              </li>
              <li>
                <a href="./pages/insurance.php">VIRUNGA JOURNEY ASSURANCE & SAFETY SUPPORT</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Mobile Overlay -->
<div class="mobile-overlay"></div>

<!-- Side Buttons -->
<div class="side-buttons">
  <div class="side-btn">
    <a
      href="https://wa.me/250784513435"
      target="_blank"
      rel="noopener noreferrer"
    >
      <i class="fa-brands fa-whatsapp"></i> </a
    ><span>Whatsapp</span>
  </div>
  <div class="side-btn">
    <a href="tel:+250784513435">
      <i class="fas fa-phone"></i> </a
    ><span>Calls</span>
  </div>
  <div class="side-btn">
    <a href="mailto:info@virungaecotours.com">
      <i class="fas fa-envelope"></i> </a
    ><span>Mail</span>
  </div>
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
      'en': '🇬🇧', 'fr': '🇫🇷', 'es': '🇪🇸', 'pt': '🇵🇹', 'zh-CN': '🇨🇳', 'zh': '🇨🇳',
      'ja': '🇯🇵', 'it': '🇮🇹', 'nl': '🇳🇱', 'sv': '🇸🇪', 'no': '🇳🇴', 'da': '🇩🇰',
      'ar': '🇸🇦', 'ko': '🇰🇷', 'hi': '🇮🇳', 'ru': '🇷🇺', 'pl': '🇵🇱', 'tr': '🇹🇷',
      'iw': '🇮🇱', 'he': '🇮🇱', 'cs': '🇨🇿', 'fi': '🇫🇮', 'ro': '🇷🇴', 'id': '🇮🇩',
      'ms': '🇲🇾', 'sw': '🇰🇪', 'th': '🇹🇭', 'vi': '🇻🇳', 'uk': '🇺🇦', 'de': '🇩🇪'
    };

    const activeFlag = document.getElementById("currentFlagEco");
    const currentLangTextEco = document.getElementById("currentLangTextEco");
    if (activeFlag) {
      activeFlag.innerText = flagMap[currentLang] || '🇬🇧';
    }
    if (currentLangTextEco) {
      currentLangTextEco.innerText = (currentLang === 'zh-CN' || currentLang === 'zh') ? 'ZH' : currentLang.toUpperCase().split('-')[0];
    }

    // Highlight the active language element in the dropdown list
    const langMenuEco = document.getElementById("langMenuEco");
    if (langMenuEco) {
      const links = langMenuEco.querySelectorAll("a");
      links.forEach(link => {
        const onClickAttr = link.getAttribute("onclick") || "";
        const targetLang = (currentLang === 'zh' || currentLang === 'zh-CN') ? 'zh-CN' : currentLang;
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

    const langBtnEco = document.getElementById("langBtnEco");
    const langMenuEco = document.getElementById("langMenuEco");

    if (langBtnEco && langMenuEco) {
      langBtnEco.addEventListener("click", (e) => {
        e.stopPropagation();
        langMenuEco.style.display = (langMenuEco.style.display === 'block') ? 'none' : 'block';
      });

      document.addEventListener("click", () => {
        langMenuEco.style.display = 'none';
      });
    }
  });
</script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
