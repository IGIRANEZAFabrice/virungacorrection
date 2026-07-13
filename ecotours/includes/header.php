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
  <div class="top-nav">
    <a href="./pages/activity.php">Beyond The Park Experience</a>
    <a href="./pages/gallery.php">Our Gallery</a>
    <a href="./pages/faq-page.php">Faqs</a>
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
