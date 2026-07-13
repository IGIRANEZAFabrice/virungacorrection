// Community Hero Management JavaScript

function initHeroCarousel() {
  const carousel = document.querySelector(".hero-carousel");
  if (!carousel) {
    console.warn("Hero carousel not found in the DOM");
    return;
  }

  console.log("Initializing community hero carousel");

  const slides = carousel.querySelectorAll(".hero-slide");
  const indicators = carousel.querySelectorAll(".indicator");
  const prevBtn = carousel.querySelector(".carousel-arrow.prev");
  const nextBtn = carousel.querySelector(".carousel-arrow.next");

  let currentSlide = 0;
  let slideInterval;

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
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function () {
      nextSlide();
    });
  }

  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", function () {
      showSlide(index);
    });
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

// Tab switching functionality
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

// Initialize hero carousel if it exists on the page
if (document.querySelector(".home-section.active#hero-section")) {
  initHeroCarousel();
}

// Handle file uploads
const fileInputs = document.querySelectorAll('input[type="file"]');
fileInputs.forEach((input) => {
  input.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const filePreview = this.closest(".file-upload").querySelector(".file-preview");
      const reader = new FileReader();

      reader.onload = function (e) {
        filePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
      };

      reader.readAsDataURL(file);
    }
  });
});

// Modal functions for adding new hero slides
function openAddHeroSlideModal() {
  const modal = document.getElementById('addHeroSlideModal');
  modal.style.display = 'flex';

  // Reset form
  document.getElementById('addHeroSlideForm').reset();
  document.getElementById('newSlideImagePreview').innerHTML = '<p>No image selected</p>';
}

function closeAddHeroSlideModal() {
  const modal = document.getElementById('addHeroSlideModal');
  modal.style.display = 'none';
}

// Handle new slide image preview
document.addEventListener('DOMContentLoaded', function() {
  const newSlideImageInput = document.getElementById('newSlideImage');
  if (newSlideImageInput) {
    newSlideImageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      const preview = document.getElementById('newSlideImagePreview');

      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; height: auto;">`;
        };
        reader.readAsDataURL(file);
      } else {
        preview.innerHTML = '<p>No image selected</p>';
      }
    });
  }
});

// Function to delete a hero slide
function deleteHeroSlide(slideId) {
  if (confirm('Are you sure you want to delete this slide? This action cannot be undone.')) {
    // Create a form to submit the delete request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'handlers/deleteCommunityHeroSlide.php';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'slide_id';
    input.value = slideId;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
  const modal = document.getElementById('addHeroSlideModal');
  if (event.target === modal) {
    closeAddHeroSlideModal();
  }
});

