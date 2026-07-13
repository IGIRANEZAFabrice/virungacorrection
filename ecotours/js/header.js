document.addEventListener("DOMContentLoaded", function () {
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

  // Toggle nested mobile dropdowns (e.g., Interests -> sub-links)
  const mobileNestedItems = document.querySelectorAll(".mobile-nested-item");
  if (mobileNestedItems && mobileNestedItems.length) {
    mobileNestedItems.forEach((nestedItem) => {
      const nestedTitle = nestedItem.querySelector(".mobile-nested-title");
      const nestedDropdown = nestedItem.querySelector(".mobile-nested-dropdown");

      if (nestedTitle && nestedDropdown) {
        nestedTitle.addEventListener("click", function (e) {
          // prevent parent dropdown closing immediately on tap
          e.stopPropagation();

          // Close sibling nested dropdowns within the same group
          const siblings = nestedItem.parentElement.querySelectorAll(".mobile-nested-item");
          siblings.forEach((sib) => {
            if (sib !== nestedItem) {
              const sibTitle = sib.querySelector(".mobile-nested-title");
              const sibDropdown = sib.querySelector(".mobile-nested-dropdown");
              sibDropdown && sibDropdown.classList.remove("active");
              sibTitle && sibTitle.classList.remove("active");
            }
          });

          // Toggle current nested dropdown and rotate chevron
          nestedDropdown.classList.toggle("active");
          nestedTitle.classList.toggle("active");
        });
      }
    });
  }
});
