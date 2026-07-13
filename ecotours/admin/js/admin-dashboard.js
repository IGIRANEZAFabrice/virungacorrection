document.addEventListener("DOMContentLoaded", function () {
  // Sidebar Toggle
  const sidebar = document.querySelector(".sidebar");
  const sidebarToggle = document.getElementById("sidebarToggle");
  const mainContent = document.querySelector(".main-content");

  if (sidebarToggle) {
    sidebarToggle.addEventListener("click", function () {
      sidebar.classList.toggle("collapsed");
      sidebarToggle.style.color = sidebar.classList.contains("collapsed")
        ? "#2a4858"
        : "#fff";
    });
  }

  // Navigation Panel Switching (Main Panels)
  const navItems = document.querySelectorAll(".nav-item");
  const panels = document.querySelectorAll(".panel");

  // Initially, make the dashboard active
  // if (document.getElementById("dashboard-panel")) {
  //   document.getElementById("dashboard-panel").classList.add("active");
  // }

  navItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      // Don't process submenu clicks here
      if (e.target.closest(".submenu")) {
        return;
      }

      const panel = this.getAttribute("data-panel");

      // Do nothing for submenu toggle if it's the home section
      if (panel === "home-schema" && this.contains(e.target)) {
        // Just toggle the submenu visibility
        this.classList.toggle("active");
        return;
      }

      // For regular nav items, proceed with panel switching
      navItems.forEach((navItem) => {
        if (navItem !== this || panel !== "home-schema") {
          navItem.classList.remove("active");
        }
      });

      // Add active class to clicked item (except for home which has special handling)
      if (panel !== "home-schema") {
        this.classList.add("active");

        // Get the panel to show
        const panelId = panel + "-panel";

        // Hide all panels
        panels.forEach((p) => {
          p.classList.remove("active");
        });

        // Show the target panel
        const targetPanel = document.getElementById(panelId);
        if (targetPanel) {
          targetPanel.classList.add("active");
        }
      }
    });
  });

  // Home Schema & Submenu Specific Functionality
  const homeNavItem = document.querySelector(
    '.nav-item[data-panel="home-schema"]'
  );
  const submenuItems = document.querySelectorAll(".submenu li");
  const homeSections = document.querySelectorAll(".home-section");
  const homeSchemaPanel = document.getElementById("home-schema-panel");

  // Toggle submenu visibility when clicking on Home nav item
  if (homeNavItem) {
    homeNavItem.addEventListener("click", function (e) {
      // Only toggle if we're clicking on the main item or its direct children (not submenu)
      if (!e.target.closest(".submenu")) {
        e.preventDefault();
        e.stopPropagation();

        // Toggle the active class to show/hide the submenu
        this.classList.toggle("active");

        // If activating the menu, also show the home panel
        if (this.classList.contains("active")) {
          // Hide all panels
          panels.forEach((panel) => {
            panel.classList.remove("active");
          });

          // Make home panel active
          if (homeSchemaPanel) {
            homeSchemaPanel.classList.add("active");

            // If no section is active, activate the first one
            const activeSection = document.querySelector(
              ".home-section.active"
            );
            if (!activeSection && homeSections.length > 0) {
              homeSections[0].classList.add("active");

              // Also mark the first submenu item as active
              if (submenuItems.length > 0) {
                submenuItems[0].classList.add("active");
              }
            }
          }
        }
      }
    });
  }

  // Add this function to handle section ID mapping
  function getSectionId(dataSection) {
    // Handle any section ID mismatches
    const mappings = {
      "home-cards": "cards",
    };

    // Return the mapped section ID or the original with -section appended
    const sectionName = mappings[dataSection] || dataSection;
    return sectionName + "-section";
  }

  // Update the submenu click handler to use this function
  submenuItems.forEach((item) => {
    item.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      // Get the section to show using our mapping function
      const dataSection = this.getAttribute("data-section");
      const sectionId = getSectionId(dataSection);

      // Set active states
      submenuItems.forEach((subItem) => subItem.classList.remove("active"));
      this.classList.add("active");

      // Ensure the home nav item stays active when clicking submenu items
      navItems.forEach((navItem) => {
        if (navItem !== homeNavItem) {
          navItem.classList.remove("active");
        }
      });
      homeNavItem.classList.add("active");

      // Show the home schema panel
      panels.forEach((panel) => panel.classList.remove("active"));
      if (homeSchemaPanel) {
        homeSchemaPanel.classList.add("active");
      }

      // Hide all sections then show the selected one
      homeSections.forEach((section) => section.classList.remove("active"));
      const targetSection = document.getElementById(sectionId);
      if (targetSection) {
        targetSection.classList.add("active");
        console.log("Activated section:", sectionId);

        // Initialize carousel if needed
        if (sectionId === "hero-section") {
          setTimeout(initHeroCarousel, 100);
        }
      } else {
        console.error("Could not find section:", sectionId);
        console.log(
          "Available sections:",
          Array.from(homeSections)
            .map((s) => s.id)
            .join(", ")
        );
      }
    });
  });

  // Click handler for main Home nav item to show first section
  homeNavItem.querySelector("a").addEventListener("click", function (e) {
    e.preventDefault();

    // Only proceed if we're clicking the main link, not a submenu
    if (!e.target.closest(".submenu")) {
      console.log("Main home nav clicked");

      // Remove active class from all nav items except home
      navItems.forEach((navItem) => {
        if (navItem !== homeNavItem) {
          navItem.classList.remove("active");
        }
      });

      // Make sure home is active to show submenu
      homeNavItem.classList.add("active");

      // Hide all panels
      panels.forEach((panel) => {
        panel.classList.remove("active");
      });

      // Show home schema panel
      if (homeSchemaPanel) {
        homeSchemaPanel.classList.add("active");

        // Activate the first section by default
        if (homeSections.length > 0) {
          // Clear active state from all sections
          homeSections.forEach((section) => {
            section.classList.remove("active");
          });

          // Set first section as active
          homeSections[0].classList.add("active");

          // Set first submenu item as active
          if (submenuItems.length > 0) {
            submenuItems.forEach((item) => {
              item.classList.remove("active");
            });
            submenuItems[0].classList.add("active");
          }

          // Initialize hero carousel if first section is hero
          if (homeSections[0].id === "hero-section") {
            setTimeout(initHeroCarousel, 100);
          }
        }
      }
    }
  });

  // Hero Carousel Functionality
  function initHeroCarousel() {
    const carousel = document.querySelector(".hero-carousel");
    if (!carousel) {
      console.warn("Hero carousel not found in the DOM");
      return;
    }

    console.log("Initializing hero carousel");

    const slides = carousel.querySelectorAll(".hero-slide");
    const indicators = carousel.querySelectorAll(".indicator");
    const prevBtn = carousel.querySelector(".carousel-arrow.prev");
    const nextBtn = carousel.querySelector(".carousel-arrow.next");

    let currentSlide = 0;
    let slideInterval;

    // Initialize autoplay
    const autoplayCheckbox = document.getElementById("carousel-autoplay");
    const intervalInput = document.getElementById("carousel-interval");

    const startAutoplay = function () {
      if (autoplayCheckbox && autoplayCheckbox.checked) {
        const interval =
          (intervalInput ? parseInt(intervalInput.value) : 5) * 1000;
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, interval);
      }
    };

    const stopAutoplay = function () {
      clearInterval(slideInterval);
    };

    // Show slide by index
    const showSlide = function (index) {
      slides.forEach((slide) => slide.classList.remove("active"));
      indicators.forEach((indicator) => indicator.classList.remove("active"));

      currentSlide = (index + slides.length) % slides.length;
      slides[currentSlide].classList.add("active");

      if (indicators[currentSlide]) {
        indicators[currentSlide].classList.add("active");
      }
    };

    // Next slide function
    const nextSlide = function () {
      showSlide(currentSlide + 1);
    };

    // Previous slide function
    const prevSlide = function () {
      showSlide(currentSlide - 1);
    };

    // Event listeners
    if (prevBtn) {
      prevBtn.addEventListener("click", function () {
        prevSlide();
        stopAutoplay();
        startAutoplay();
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener("click", function () {
        nextSlide();
        stopAutoplay();
        startAutoplay();
      });
    }

    indicators.forEach((indicator, index) => {
      indicator.addEventListener("click", function () {
        showSlide(index);
        stopAutoplay();
        startAutoplay();
      });
    });

    // Handle autoplay toggle
    if (autoplayCheckbox) {
      autoplayCheckbox.addEventListener("change", function () {
        if (this.checked) {
          startAutoplay();
        } else {
          stopAutoplay();
        }
      });
    }

    // Handle interval change
    if (intervalInput) {
      intervalInput.addEventListener("change", function () {
        if (autoplayCheckbox && autoplayCheckbox.checked) {
          stopAutoplay();
          startAutoplay();
        }
      });
    }

    // Transition effect change
    const transitionSelect = document.getElementById("carousel-transition");
    const speedInput = document.getElementById("carousel-speed");

    if (transitionSelect) {
      transitionSelect.addEventListener("change", function () {
        const effect = this.value;
        slides.forEach((slide) => {
          const speed = speedInput ? speedInput.value : 500;
          slide.style.transition = `${
            effect === "fade" ? "opacity" : "transform"
          } ${speed}ms ease`;
        });
      });
    }

    // Speed change
    if (speedInput) {
      speedInput.addEventListener("change", function () {
        const speed = this.value;
        slides.forEach((slide) => {
          slide.style.transitionDuration = `${speed}ms`;
        });
      });
    }

    // Start autoplay on init if enabled
    startAutoplay();

    // Stop autoplay when user interacts with carousel settings
    const carouselSettings = document.querySelectorAll(
      ".hero-carousel-settings input, .hero-carousel-settings select"
    );
    carouselSettings.forEach((setting) => {
      setting.addEventListener("focus", stopAutoplay);
    });

    // Preview carousel tab switching
    const heroTabButtons = document.querySelectorAll(
      '.tab-btn[data-target^="slide"]'
    );
    heroTabButtons.forEach((button, index) => {
      button.addEventListener("click", function () {
        showSlide(index);
      });
    });
  }

  // Initialize hero carousel if it exists on the page and we're on the hero section
  if (document.querySelector(".home-section.active#hero-section")) {
    initHeroCarousel();
  }

  // Table Search Feature
  const searchFilters = document.querySelectorAll(".search-filter input");
  searchFilters.forEach((filter) => {
    filter.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase();
      const tableRows = this.closest(".panel").querySelectorAll("tbody tr");

      tableRows.forEach((row) => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });
  });

  // Filters Change Event
  const filterSelects = document.querySelectorAll(".filters select");
  filterSelects.forEach((select) => {
    select.addEventListener("change", function () {
      // Add filtering logic here
      console.log("Filter changed:", this.value);
    });
  });

  // Mobile Responsive Handling
  function handleResponsive() {
    if (window.innerWidth <= 992) {
      sidebar.classList.add("collapsed");
      mainContent.style.marginLeft = "80px";

      // Create overlay for mobile if it doesn't exist
      if (!document.querySelector(".overlay")) {
        const overlay = document.createElement("div");
        overlay.className = "overlay";
        document.body.appendChild(overlay);

        overlay.addEventListener("click", function () {
          sidebar.classList.remove("expanded");
          this.classList.remove("active");
        });
      }

      sidebarToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        sidebar.classList.toggle("expanded");
        document.querySelector(".overlay").classList.toggle("active");
      });
    } else {
      const overlay = document.querySelector(".overlay");
      if (overlay) {
        overlay.remove();
      }
    }
  }

  // Initial call and window resize event
  handleResponsive();
  window.addEventListener("resize", handleResponsive);


  // Initialize tooltips for action buttons
  const actionButtons = document.querySelectorAll("[title]");
  actionButtons.forEach((button) => {
    const title = button.getAttribute("title");
    button.addEventListener("mouseenter", function (e) {
      const tooltip = document.createElement("div");
      tooltip.className = "tooltip";
      tooltip.textContent = title;

      document.body.appendChild(tooltip);

      const rect = button.getBoundingClientRect();
      tooltip.style.top = `${rect.top - tooltip.offsetHeight - 5}px`;
      tooltip.style.left = `${
        rect.left + rect.width / 2 - tooltip.offsetWidth / 2
      }px`;

      button.addEventListener("mouseleave", function () {
        tooltip.remove();
      });
    });
  });

  // Handle tab switching in card editor
  const tabButtons = document.querySelectorAll(".tab-btn:not(.add-tab)");
  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const targetId = this.getAttribute("data-target");
      const tabsContainer = this.closest(".tabs-container");

      // Update active button
      tabsContainer.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("active");
      });
      this.classList.add("active");

      // Update active content
      tabsContainer.querySelectorAll(".tab-content").forEach((content) => {
        content.classList.remove("active");
      });
      tabsContainer.querySelector(`#${targetId}`).classList.add("active");
    });
  });

  // Handle icon selection
  const iconSelects = document.querySelectorAll('select[id$="-icon"]');
  iconSelects.forEach((select) => {
    select.addEventListener("change", function () {
      const iconClass = this.value;
      const iconPreview = this.nextElementSibling;

      // Update icon preview
      iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
    });
  });

  // Handle file uploads
  const fileInputs = document.querySelectorAll('input[type="file"]');
  fileInputs.forEach((input) => {
    input.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const filePreview =
          this.closest(".file-upload").querySelector(".file-preview");
        const reader = new FileReader();

        reader.onload = function (e) {
          filePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };

        reader.readAsDataURL(file);
      }
    });
  });

  // Handle color picker
  const colorInputs = document.querySelectorAll('input[type="color"]');
  colorInputs.forEach((input) => {
    input.addEventListener("input", function () {
      const textInput = this.nextElementSibling;
      textInput.value = this.value;
    });

    // Also handle text input changes
    const textInput = input.nextElementSibling;
    textInput.addEventListener("input", function () {
      input.value = this.value;
    });
  });

  // Preview toggle functionality
  const previewToggles = document.querySelectorAll(".preview-toggle");
  previewToggles.forEach((toggle) => {
    toggle.addEventListener("click", function () {
      const previewContainer =
        this.closest(".content-preview").querySelector(".preview-container");
      previewContainer.classList.toggle("fullscreen");

      if (previewContainer.classList.contains("fullscreen")) {
        this.innerHTML = '<i class="fas fa-compress"></i> Exit Preview';
      } else {
        this.innerHTML = '<i class="fas fa-eye"></i> Preview';
      }
    });
  });

  // Add Card Button Functionality
  const addCardBtn = document.querySelector(".add-tab");
  if (addCardBtn) {
    let cardCount = 3; // Starting with 3 cards

    addCardBtn.addEventListener("click", function () {
      cardCount++;
      const tabsHeader = this.closest(".tabs-header");
      const tabsContainer = this.closest(".tabs-container");

      // Create new tab button
      const newTabBtn = document.createElement("button");
      newTabBtn.className = "tab-btn";
      newTabBtn.setAttribute("data-target", `card${cardCount}`);
      newTabBtn.textContent = `Card ${cardCount}`;

      // Insert before add button
      tabsHeader.insertBefore(newTabBtn, this);

      // Create new tab content
      const newTabContent = document.createElement("div");
      newTabContent.className = "tab-content";
      newTabContent.id = `card${cardCount}`;
      newTabContent.innerHTML = `
                <div class="editor-form">
                    <div class="form-group">
                        <label for="card${cardCount}-title">Card Title</label>
                        <input type="text" id="card${cardCount}-title" value="New Feature">
                    </div>
                    <div class="form-group">
                        <label for="card${cardCount}-desc">Card Description</label>
                        <textarea id="card${cardCount}-desc" rows="2">Description for this feature</textarea>
                    </div>
                    <div class="form-group">
                        <label for="card${cardCount}-icon">Card Icon</label>
                        <select id="card${cardCount}-icon">
                            <option value="fas fa-star">Star</option>
                            <option value="fas fa-heart">Heart</option>
                            <option value="fas fa-thumbs-up">Thumbs Up</option>
                            <option value="fas fa-shield-alt">Shield</option>
                            <option value="fas fa-check-circle">Check Circle</option>
                        </select>
                        <div class="icon-preview"><i class="fas fa-star"></i></div>
                    </div>
                    <div class="form-actions">
                        <button class="action-button secondary delete-card"><i class="fas fa-trash"></i> Delete Card</button>
                    </div>
                </div>
            `;

      // Add new content to container
      tabsContainer.appendChild(newTabContent);

      // Activate the new tab
      tabsHeader.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("active");
      });
      newTabBtn.classList.add("active");

      tabsContainer.querySelectorAll(".tab-content").forEach((content) => {
        content.classList.remove("active");
      });
      newTabContent.classList.add("active");

      // Add event listener to the new tab button
      newTabBtn.addEventListener("click", function () {
        const targetId = this.getAttribute("data-target");

        // Update active button
        tabsContainer.querySelectorAll(".tab-btn").forEach((btn) => {
          btn.classList.remove("active");
        });
        this.classList.add("active");

        // Update active content
        tabsContainer.querySelectorAll(".tab-content").forEach((content) => {
          content.classList.remove("active");
        });
        tabsContainer.querySelector(`#${targetId}`).classList.add("active");
      });

      // Add event listener to the new icon select
      const newIconSelect = newTabContent.querySelector(
        `#card${cardCount}-icon`
      );
      newIconSelect.addEventListener("change", function () {
        const iconClass = this.value;
        const iconPreview = this.nextElementSibling;

        // Update icon preview
        iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
      });

      // Add event listener to delete button
      const deleteBtn = newTabContent.querySelector(".delete-card");
      deleteBtn.addEventListener("click", function () {
        if (confirm("Are you sure you want to delete this card?")) {
          // Remove tab button and content
          newTabBtn.remove();
          newTabContent.remove();

          // Activate the first tab
          const firstTabBtn = tabsContainer.querySelector(
            ".tab-btn:not(.add-tab)"
          );
          if (firstTabBtn) {
            firstTabBtn.click();
          }
        }
      });
    });
  }

  // Delete Card Button Functionality
  const deleteCardBtns = document.querySelectorAll(".delete-card");
  deleteCardBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const tabContent = this.closest(".tab-content");
      const tabId = tabContent.id;
      const tabBtn = document.querySelector(`.tab-btn[data-target="${tabId}"]`);

      if (confirm("Are you sure you want to delete this card?")) {
        // Remove tab button and content
        tabBtn.remove();
        tabContent.remove();

        // Activate the first tab
        const firstTabBtn = document.querySelector(".tab-btn:not(.add-tab)");
        if (firstTabBtn) {
          firstTabBtn.click();
        }
      }
    });
  });

  // Add custom class for fullscreen preview
  const styleElement = document.createElement("style");
  styleElement.textContent = `
        .preview-container.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-container.fullscreen > * {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
        }
    `;
  document.head.appendChild(styleElement);
});

// Hero Carousel Tab Switching
document.addEventListener('DOMContentLoaded', function() {
  const heroTabButtons = document.querySelectorAll('.tabs-container .tab-btn');
  
  heroTabButtons.forEach(button => {
    button.addEventListener('click', function() {
      // Get target slide content
      const targetId = this.getAttribute('data-target');
      const tabsContainer = this.closest('.tabs-container');
      
      // Remove active class from all buttons
      tabsContainer.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
      });
      
      // Add active class to clicked button
      this.classList.add('active');
      
      // Hide all tab contents
      tabsContainer.querySelectorAll('.tab-content').forEach(content => {
        content.style.display = 'none';
      });
      
      // Show target content
      const targetContent = tabsContainer.querySelector(`#${targetId}`);
      if (targetContent) {
        targetContent.style.display = 'block';
      }
    });
  });
});
