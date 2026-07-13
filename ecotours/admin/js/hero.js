const homeSections = document.querySelectorAll(".home-section");

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

const tabButtons = document.querySelectorAll(".tab-btn:not(.add-tab)");
tabButtons.forEach((button) => {
  button.addEventListener("click", function (e) {
    e.preventDefault();
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

// Handle message receiver timeout
const messageReceiver = document.querySelector(".message-receiver");
if (messageReceiver) {
  setTimeout(() => {
    messageReceiver.style.animation = "fadeOutSlideUp 0.4s ease-out forwards";
    setTimeout(() => messageReceiver.remove(), 400);
  }, 5000);
}
