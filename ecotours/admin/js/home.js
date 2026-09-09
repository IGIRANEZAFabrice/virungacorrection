/**
 * Modern Home Management JavaScript
 * Controls carousel preview, pill tab switching, dropzone file previews, and modals.
 */

document.addEventListener("DOMContentLoaded", function () {
  // 1. Live Carousel Preview Switching
  const slides = document.querySelectorAll(".hero-slide");
  const dots = document.querySelectorAll(".dot-indicator");
  const prevBtn = document.getElementById("prevSlideBtn");
  const nextBtn = document.getElementById("nextSlideBtn");
  let currentSlideIndex = 0;
  let autoplayTimer = null;

  function showSlide(index) {
    if (!slides.length) return;
    slides.forEach((s) => s.classList.remove("active"));
    dots.forEach((d) => d.classList.remove("active"));

    currentSlideIndex = (index + slides.length) % slides.length;
    if (slides[currentSlideIndex]) {
      slides[currentSlideIndex].classList.add("active");
    }
    if (dots[currentSlideIndex]) {
      dots[currentSlideIndex].classList.add("active");
    }
  }

  if (prevBtn) {
    prevBtn.addEventListener("click", function (e) {
      e.preventDefault();
      stopAutoplay();
      showSlide(currentSlideIndex - 1);
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener("click", function (e) {
      e.preventDefault();
      stopAutoplay();
      showSlide(currentSlideIndex + 1);
    });
  }

  dots.forEach((dot, idx) => {
    dot.addEventListener("click", function () {
      stopAutoplay();
      showSlide(idx);
    });
  });

  function startAutoplay() {
    if (slides.length > 1) {
      autoplayTimer = setInterval(() => {
        showSlide(currentSlideIndex + 1);
      }, 4500);
    }
  }

  function stopAutoplay() {
    if (autoplayTimer) {
      clearInterval(autoplayTimer);
      autoplayTimer = null;
    }
  }

  startAutoplay();

  // 2. Pill Tabs Switching
  const tabButtons = document.querySelectorAll(".pill-tab-btn");
  const tabPanes = document.querySelectorAll(".tab-pane");

  tabButtons.forEach((btn, idx) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("data-target");

      tabButtons.forEach((b) => b.classList.remove("active"));
      tabPanes.forEach((p) => p.classList.remove("active"));

      this.classList.add("active");
      const activePane = document.getElementById(targetId);
      if (activePane) {
        activePane.classList.add("active");
      }

      // Sync with preview slide if available
      stopAutoplay();
      showSlide(idx);
    });
  });

  // 3. Dropzone Instant Image Preview
  const dropzones = document.querySelectorAll(".dropzone-box");
  dropzones.forEach((zone) => {
    const fileInput = zone.querySelector('input[type="file"]');
    const previewImg = zone.querySelector(".dropzone-thumb-wrap img");
    const previewContainer = zone.querySelector(".dropzone-thumb-wrap");

    if (!fileInput) return;

    fileInput.addEventListener("change", function () {
      const file = this.files && this.files[0];
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (previewImg) {
            previewImg.src = e.target.result;
          } else if (previewContainer) {
            previewContainer.innerHTML = `<img src="${e.target.result}" alt="Uploaded Preview" />`;
          }
        };
        reader.readAsDataURL(file);
      }
    });

    // Drag and Drop styling
    ["dragenter", "dragover"].forEach((eventName) => {
      zone.addEventListener(eventName, (e) => {
        e.preventDefault();
        zone.classList.add("dragover");
      });
    });

    ["dragleave", "drop"].forEach((eventName) => {
      zone.addEventListener(eventName, (e) => {
        e.preventDefault();
        zone.classList.remove("dragover");
      });
    });
  });

  // 4. Modal Helpers
  window.openModal = function (modalId) {
    const m = document.getElementById(modalId);
    if (m) {
      m.classList.add("show");
      document.body.style.overflow = "hidden";
    }
  };

  window.closeModal = function (modalId) {
    const m = document.getElementById(modalId);
    if (m) {
      m.classList.remove("show");
      document.body.style.overflow = "";
    }
  };

  // Close modal when clicking on backdrop
  document.querySelectorAll(".modal-backdrop-custom").forEach((modal) => {
    modal.addEventListener("click", function (e) {
      if (e.target === this) {
        this.classList.remove("show");
        document.body.style.overflow = "";
      }
    });
  });
});

// Delete Slide Handler (used in hero.php)
function deleteHeroSlide(slideId) {
  if (confirm("Are you sure you want to delete this hero slide? This action cannot be undone.")) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "../../handlers/home/deleteHeroSlideHandler.php";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "slide_id";
    input.value = slideId;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
}

// Delete Partner Handler (used in partners.php)
function confirmDeletePartner(partnerId) {
  if (confirm("Are you sure you want to delete this partner?")) {
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "../../handlers/home/deletePartnerHandler.php";

    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "partner_id";
    input.value = partnerId;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
}
