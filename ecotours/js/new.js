document.addEventListener("DOMContentLoaded", function () {
  // Blog carousel functionality
  const blogCarousel = document.querySelector(".blog-carousel");
  const blogSlides = document.querySelectorAll(".blog-slide");
  const blogDots = document.querySelectorAll(".blog-dot");
  const prevBlogBtn = document.querySelector(".blog-prev");
  const nextBlogBtn = document.querySelector(".blog-next");

  let currentBlogSlide = 0;
  let blogSlideInterval;
  const blogIntervalTime = 6000; // Time between auto slides (6 seconds)

  // Initialize the blog carousel
  function initBlogCarousel() {
    // Start auto sliding
    startBlogSlideInterval();

    // Add event listeners for controls
    if (prevBlogBtn) prevBlogBtn.addEventListener("click", prevBlogSlide);
    if (nextBlogBtn) nextBlogBtn.addEventListener("click", nextBlogSlide);

    // Add event listeners for indicators
    blogDots.forEach((dot, index) => {
      dot.addEventListener("click", function () {
        goToBlogSlide(index);
      });
    });

    // Pause auto sliding on hover
    if (blogCarousel) {
      blogCarousel.addEventListener("mouseenter", stopBlogSlideInterval);
      blogCarousel.addEventListener("mouseleave", startBlogSlideInterval);

      // Add touch swipe functionality
      let touchStartX = 0;
      let touchEndX = 0;

      blogCarousel.addEventListener(
        "touchstart",
        function (e) {
          touchStartX = e.changedTouches[0].screenX;
          stopBlogSlideInterval();
        },
        { passive: true }
      );

      blogCarousel.addEventListener(
        "touchend",
        function (e) {
          touchEndX = e.changedTouches[0].screenX;
          handleBlogSwipe();
          startBlogSlideInterval();
        },
        { passive: true }
      );
    }

    function handleBlogSwipe() {
      const swipeThreshold = 50;
      if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe left - next slide
        nextBlogSlide();
      } else if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe right - previous slide
        prevBlogSlide();
      }
    }
  }

  // Go to specific blog slide
  function goToBlogSlide(slideIndex) {
    if (!blogSlides.length) return;

    if (slideIndex < 0) {
      slideIndex = blogSlides.length - 1;
    } else if (slideIndex >= blogSlides.length) {
      slideIndex = 0;
    }

    // Remove active class from current slide and dot
    blogSlides[currentBlogSlide].classList.remove("active");
    if (blogDots[currentBlogSlide])
      blogDots[currentBlogSlide].classList.remove("active");

    // Add active class to new slide and dot
    blogSlides[slideIndex].classList.add("active");
    if (blogDots[slideIndex]) blogDots[slideIndex].classList.add("active");

    // Update current slide index
    currentBlogSlide = slideIndex;

    // Reset the auto slide interval
    if (blogSlideInterval) {
      stopBlogSlideInterval();
      startBlogSlideInterval();
    }
  }

  // Go to previous blog slide
  function prevBlogSlide() {
    goToBlogSlide(currentBlogSlide - 1);
  }

  // Go to next blog slide
  function nextBlogSlide() {
    goToBlogSlide(currentBlogSlide + 1);
  }

  // Start auto sliding for blog
  function startBlogSlideInterval() {
    blogSlideInterval = setInterval(nextBlogSlide, blogIntervalTime);
  }

  // Stop auto sliding for blog
  function stopBlogSlideInterval() {
    clearInterval(blogSlideInterval);
  }

  // Initialize the blog carousel if elements exist
  if (blogSlides.length > 0) {
    initBlogCarousel();
  }

  // Blog card hover effects
  const blogCards = document.querySelectorAll(".blog-card");

  blogCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.classList.add("hover");
    });

    card.addEventListener("mouseleave", function () {
      this.classList.remove("hover");
    });
  });

  // Animate blog elements when they come into view
  const observerOptions = {
    root: null,
    rootMargin: "0px",
    threshold: 0.1,
  };

  const blogObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("animate");
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);

  // Observe all blog section elements that should animate on scroll
  const animateElements = document.querySelectorAll(
    ".blog-section h2, .blog-card, .blog-dots, .blog-navigation"
  );

  animateElements.forEach((element) => {
    blogObserver.observe(element);
  });

  // Why Travel Section Animations
  const whyTravelSection = document.querySelector(".why-travel-section");
  const ctaSection = document.querySelector(".cta-section");

  if (whyTravelSection) {
    // Create observer for feature boxes with animation on scroll
    const featureBoxes = document.querySelectorAll(".feature-box");

    // Observer for feature boxes
    const featureObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            // Add a class instead of relying solely on the CSS animation
            entry.target.classList.add("animated");
            // Unobserve after animation is triggered
            featureObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.2,
        rootMargin: "0px 0px -100px 0px",
      }
    );

    // Observe each feature box
    featureBoxes.forEach((box) => {
      featureObserver.observe(box);

      // Add hover interaction
      box.addEventListener("mouseenter", function () {
        this.classList.add("hover");
      });

      box.addEventListener("mouseleave", function () {
        this.classList.remove("hover");
      });
    });
  }

  if (ctaSection) {
    // Create observer for CTA boxes
    const ctaBoxes = document.querySelectorAll(".cta-box");

    // Observer for CTA boxes
    const ctaObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animated");
            ctaObserver.unobserve(entry.target);
          }
        });
      },
      {
        threshold: 0.2,
        rootMargin: "0px 0px -100px 0px",
      }
    );

    // Observe each CTA box
    ctaBoxes.forEach((box) => {
      ctaObserver.observe(box);
    });

    // Add button hover effects
    const ctaButtons = document.querySelectorAll(".cta-button");
    ctaButtons.forEach((button) => {
      button.addEventListener("mouseenter", function () {
        this.classList.add("hover");
      });

      button.addEventListener("mouseleave", function () {
        this.classList.remove("hover");
      });
    });
  }

  // Initialize GSAP animations
  if (typeof gsap !== 'undefined' && gsap.registerPlugin) {
    gsap.registerPlugin(ScrollTrigger);

    // Animation for section elements
    function initAnimations() {
      // Section heading animation
      gsap.to(".fade-in-up", {
        scrollTrigger: {
          trigger: ".about-section, .middle-about-section",
          start: "top 80%",
        },
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: "power2.out",
      });

      // Left side animation
      gsap.to(".fade-in-left", {
        scrollTrigger: {
          trigger: ".video-flex-container",
          start: "top 75%",
        },
        opacity: 1,
        x: 0,
        duration: 0.8,
        ease: "power2.out",
        delay: 0.2,
      });

      // Right side animation
      gsap.to(".fade-in-right", {
        scrollTrigger: {
          trigger: ".video-flex-container",
          start: "top 75%",
        },
        opacity: 1,
        x: 0,
        duration: 0.8,
        ease: "power2.out",
        delay: 0.4,
      });
    }

    // Initialize animations
    initAnimations();
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const sliderContainer = document.getElementById("sliderContainer");
  const prevButton = document.querySelector(".slider-prev");
  const nextButton = document.querySelector(".slider-next");
  const sliderItems = document.querySelectorAll(".slider-item");

  let currentIndex = 0;
  const itemWidth = sliderItems[0].offsetWidth;
  const itemsToShow =
    window.innerWidth > 768 ? 3 : window.innerWidth > 480 ? 2 : 1;
  const maxIndex = sliderItems.length - itemsToShow;

  // Clone the first few items and append them to the end for infinite loop
  for (let i = 0; i < itemsToShow; i++) {
    const clone = sliderItems[i].cloneNode(true);
    sliderContainer.appendChild(clone);
  }

  function updateSlider() {
    const translateValue = -currentIndex * itemWidth;
    sliderContainer.style.transform = `translateX(${translateValue}px)`;
  }

  prevButton.addEventListener("click", function () {
    if (currentIndex > 0) {
      currentIndex--;
    } else {
      currentIndex = maxIndex;
      sliderContainer.style.transition = "none";
      updateSlider();
      setTimeout(() => {
        sliderContainer.style.transition = "transform 0.5s ease";
      }, 10);
    }
    updateSlider();
  });

  nextButton.addEventListener("click", function () {
    if (currentIndex < maxIndex) {
      currentIndex++;
    } else {
      currentIndex = 0;
      sliderContainer.style.transition = "none";
      updateSlider();
      setTimeout(() => {
        sliderContainer.style.transition = "transform 0.5s ease";
      }, 10);
    }
    updateSlider();
  });

  // Auto slide
  setInterval(function () {
    nextButton.click();
  }, 5000);

  // Handle responsive changes
  window.addEventListener("resize", function () {
    const newItemsToShow =
      window.innerWidth > 768 ? 3 : window.innerWidth > 480 ? 2 : 1;
    if (newItemsToShow !== itemsToShow) {
      location.reload();
    }
  });

  document.addEventListener("DOMContentLoaded", function () {
    // Only apply this JavaScript for mobile view
    if (window.innerWidth <= 576) {
      const travelGrid = document.getElementById("travel-grid");
      const prevBtn = document.getElementById("prev-btn");
      const nextBtn = document.getElementById("next-btn");
      const monthIndicator = document.getElementById("month-indicator");
      const monthCards = document.querySelectorAll(".month-card");
      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      let currentIndex = 0;

      // Update indicator based on visible card
      function updateIndicator() {
        monthIndicator.textContent = months[currentIndex];
      }

      // Scroll to specific card
      function scrollToCard(index) {
        if (index < 0) index = 0;
        if (index >= monthCards.length) index = monthCards.length - 1;

        currentIndex = index;
        monthCards[index].scrollIntoView({
          behavior: "smooth",
          inline: "center",
        });
        updateIndicator();
      }

      // Next button click handler
      nextBtn.addEventListener("click", function () {
        scrollToCard(currentIndex + 1);
      });

      // Previous button click handler
      prevBtn.addEventListener("click", function () {
        scrollToCard(currentIndex - 1);
      });

      // Listen for scroll end to update indicator
      let timeout;
      travelGrid.addEventListener("scroll", function () {
        clearTimeout(timeout);
        timeout = setTimeout(function () {
          // Find which card is most visible
          const cards = Array.from(monthCards);
          let mostVisibleIndex = 0;
          let maxVisibility = 0;

          cards.forEach((card, index) => {
            const rect = card.getBoundingClientRect();
            const visibleWidth =
              Math.min(rect.right, window.innerWidth) - Math.max(rect.left, 0);
            if (visibleWidth > maxVisibility) {
              maxVisibility = visibleWidth;
              mostVisibleIndex = index;
            }
          });

          currentIndex = mostVisibleIndex;
          updateIndicator();
        }, 150);
      });

      // Initialize
      updateIndicator();
    }
  });
});

