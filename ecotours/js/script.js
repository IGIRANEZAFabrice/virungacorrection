document.addEventListener("DOMContentLoaded", function () {
  // Get all region list items
  const regionItems = document.querySelectorAll(".sidebar ul li");
  const destinationGroups = document.querySelectorAll(".destination-group");

  // Set default active region
  let activeRegion = null;

  // Function to update featured content from data attributes
  function updateFeaturedContent(regionElement) {
    const featuredImg = document.getElementById("region-featured-img");
    const featuredTitle = document.getElementById("region-featured-title");
    const featuredDescription = document.getElementById(
      "region-featured-description"
    );

    // Get data from the region element's data attributes
    const image = regionElement.getAttribute("data-featured-image");
    const title = regionElement.getAttribute("data-featured-title");
    const description = regionElement.getAttribute("data-featured-description");

    // Update the content with a fade effect
    featuredImg.style.opacity = "0";
    setTimeout(() => {
      featuredImg.src = image;
      featuredTitle.textContent = title;
      featuredDescription.textContent = description;
      featuredImg.style.opacity = "1";
    }, 300);
  }

  // Add click event listeners to each region item
  regionItems.forEach((item) => {
    item.addEventListener("click", function () {
      const region = this.textContent.trim();

      // Remove active class from all regions
      regionItems.forEach((r) => r.classList.remove("active-region"));

      // Add active class to clicked region
      this.classList.add("active-region");

      // Hide all destination groups
      destinationGroups.forEach((group) => {
        group.style.display = "none";
      });

      // Show the selected region's destinations
      const selectedGroup = document.querySelector(
        `.destination-group[data-region="${region}"]`
      );
      if (selectedGroup) {
        selectedGroup.style.display = "grid";
      }

      // Update the featured content based on the selected region
      updateFeaturedContent(this);
    });
  });

  // Initialize with first region selected
  if (regionItems.length > 0) {
    regionItems[0].click();
  }

  // Add hover effect for destination items (delegated event)
  destinationGroups.forEach((group) => {
    group.addEventListener("mouseover", function (e) {
      const item = e.target.closest(".destination-item");
      if (item) {
        item.classList.add("destination-hover");
      }
    });

    group.addEventListener("mouseout", function (e) {
      const item = e.target.closest(".destination-item");
      if (item) {
        item.classList.remove("destination-hover");
      }
    });
  });

  // Hero Carousel Functionality
  // Get all carousel elements
  const slides = document.querySelectorAll(".carousel-slide");
  const indicators = document.querySelectorAll(".indicator");
  const prevBtn = document.querySelector(".prev-slide");
  const nextBtn = document.querySelector(".next-slide");

  let currentSlide = 0;
  let slideInterval;
  const intervalTime = 5000; // Time between auto slides (5 seconds)

  // Initialize the carousel
  function initCarousel() {
    // Start auto sliding
    startSlideInterval();

    // Add event listeners for controls
    prevBtn.addEventListener("click", prevSlide);
    nextBtn.addEventListener("click", nextSlide);

    // Add event listeners for indicators
    indicators.forEach((indicator) => {
      indicator.addEventListener("click", function () {
        const slideIndex = parseInt(this.getAttribute("data-slide"));
        goToSlide(slideIndex);
      });
    });

    // Pause auto sliding on hover
    const carousel = document.querySelector(".hero-carousel");
    carousel.addEventListener("mouseenter", stopSlideInterval);
    carousel.addEventListener("mouseleave", startSlideInterval);

    // Add touch swipe functionality
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener(
      "touchstart",
      function (e) {
        touchStartX = e.changedTouches[0].screenX;
        stopSlideInterval();
      },
      { passive: true }
    );

    carousel.addEventListener(
      "touchend",
      function (e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
        startSlideInterval();
      },
      { passive: true }
    );

    function handleSwipe() {
      const swipeThreshold = 50;
      if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe left - next slide
        nextSlide();
      } else if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe right - previous slide
        prevSlide();
      }
    }
  }

  // Go to specific slide
  function goToSlide(slideIndex) {
    if (slideIndex < 0) {
      slideIndex = slides.length - 1;
    } else if (slideIndex >= slides.length) {
      slideIndex = 0;
    }

    // Remove active class from current slide and indicator
    slides[currentSlide].classList.remove("active");
    indicators[currentSlide].classList.remove("active");

    // Add active class to new slide and indicator
    slides[slideIndex].classList.add("active");
    indicators[slideIndex].classList.add("active");

    // Update current slide index
    currentSlide = slideIndex;

    // Reset the auto slide interval
    if (slideInterval) {
      stopSlideInterval();
      startSlideInterval();
    }
  }

  // Go to previous slide
  function prevSlide() {
    goToSlide(currentSlide - 1);
  }

  // Go to next slide
  function nextSlide() {
    goToSlide(currentSlide + 1);
  }

  // Start auto sliding
  function startSlideInterval() {
    slideInterval = setInterval(nextSlide, intervalTime);
  }

  // Stop auto sliding
  function stopSlideInterval() {
    clearInterval(slideInterval);
  }

  // Initialize the carousel
  initCarousel();

  // Side buttons scroll effect
  const sideButtons = document.querySelector(".side-buttons");
  let lastScrollTop = 0;
  let scrollThreshold = 100; // Scroll distance before buttons become compact

  window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

    // Add compact class when scrolling down past threshold
    if (scrollTop > scrollThreshold) {
      sideButtons.classList.add("compact");
    } else {
      sideButtons.classList.remove("compact");
    }

    lastScrollTop = scrollTop;
  });

  // Mobile Menu Toggle
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const mobileNav = document.querySelector(".mobile-nav");
  const mobileOverlay = document.querySelector(".mobile-overlay");
  const mobileClose = document.querySelector(".mobile-close");
  const mobileNavItems = document.querySelectorAll(".mobile-nav-item");

  // Open mobile menu
  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener("click", function () {
      mobileNav.classList.add("active");
      mobileOverlay.classList.add("active");
      document.body.style.overflow = "hidden"; // Prevent scrolling when menu is open
    });
  }

  // Close mobile menu
  if (mobileClose) {
    mobileClose.addEventListener("click", function () {
      mobileNav.classList.remove("active");
      mobileOverlay.classList.remove("active");
      document.body.style.overflow = ""; // Restore scrolling
    });
  }

  // Close mobile menu when clicking overlay
  if (mobileOverlay) {
    mobileOverlay.addEventListener("click", function () {
      mobileNav.classList.remove("active");
      mobileOverlay.classList.remove("active");
      document.body.style.overflow = ""; // Restore scrolling
    });
  }

  // Toggle mobile dropdowns
  if (mobileNavItems) {
    mobileNavItems.forEach((item) => {
      const title = item.querySelector(".mobile-nav-title");
      const dropdown = item.querySelector(".mobile-dropdown");

      // Only add click event if the item has a dropdown
      if (title && dropdown) {
        title.addEventListener("click", function () {
          // Close all other dropdowns
          mobileNavItems.forEach((otherItem) => {
            const otherDropdown = otherItem.querySelector(".mobile-dropdown");
            if (otherDropdown && otherItem !== item) {
              otherDropdown.classList.remove("active");
            }
          });

          // Toggle current dropdown
          dropdown.classList.toggle("active");
        });
      }
    });
  }

  // Get all day elements
  const days = document.querySelectorAll(".itinerary-day");

  // Set up each day
  days.forEach((day) => {
    const header = day.querySelector(".day-header");
    const content = day.querySelector(".day-content");
    const toggle = day.querySelector(".day-toggle");

    // Set initial state (day1 is open, others are closed)
    if (day.id === "day1") {
      content.classList.add("active");
      header.classList.add("active");
      toggle.innerHTML = "&#8722;"; // Minus sign
      toggle.style.transform = "rotate(180deg)";
    } else {
      content.classList.remove("active");
      header.classList.remove("active");
      toggle.innerHTML = "&#43;"; // Plus sign
      toggle.style.transform = "rotate(0deg)";
    }

    // Add click event listener
    header.addEventListener("click", function () {
      // Toggle the active classes
      content.classList.toggle("active");
      header.classList.toggle("active");

      // Update the toggle icon and rotation
      if (content.classList.contains("active")) {
        toggle.innerHTML = "&#8722;"; // Minus sign
        toggle.style.transform = "rotate(180deg)";
      } else {
        toggle.innerHTML = "&#43;"; // Plus sign
        toggle.style.transform = "rotate(0deg)";
      }
    });
  });

  // Destination section scroll animation
  function initDestinationAnimation() {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
          }
        });
      },
      {
        threshold: 0.1,
      }
    );

    // Observe the destination image
    const destinationImage = document.querySelector(".destination-image");
    if (destinationImage) {
      observer.observe(destinationImage);
    }

    // Optional: Add hover effect for the image
    destinationImage?.addEventListener("mousemove", (e) => {
      const { left, top, width, height } = e.target.getBoundingClientRect();
      const x = (e.clientX - left) / width;
      const y = (e.clientY - top) / height;

      e.target.style.transform = `perspective(1000px) rotateY(${
        (x - 0.5) * 10
      }deg) rotateX(${(y - 0.5) * -10}deg)`;
    });

    destinationImage?.addEventListener("mouseleave", (e) => {
      e.target.style.transform = "perspective(1000px) rotateY(0) rotateX(0)";
    });
  }

  initDestinationAnimation();
});
