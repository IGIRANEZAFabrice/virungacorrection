document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.querySelector(".sidebar");
  const sidebarToggle = document.querySelector("#sidebarToggle");
  const dropdowns = document.querySelectorAll(".nav-item.dropdown > a");
  const userProfile = document.querySelector(".user-profile");
  const userProfileDropdown = document.querySelector(".user-dropdown");

  userProfile.addEventListener("click", () => {
    userProfile.classList.toggle("active");
  });

  // Create overlay element
  const overlay = document.createElement("div");
  overlay.className = "overlay";
  document.body.appendChild(overlay);

  // Toggle sidebar
  sidebarToggle.addEventListener("click", () => {
    if (window.innerWidth <= 992) {
      // Mobile behavior
      sidebar.classList.toggle("expanded");
      overlay.classList.toggle("active");
    } else {
      // Desktop behavior
      sidebar.classList.toggle("collapsed");
    }
  });

  // Close sidebar when clicking overlay
  overlay.addEventListener("click", () => {
    sidebar.classList.remove("expanded");
    overlay.classList.remove("active");
  });

  // Handle dropdowns
  dropdowns.forEach((dropdown) => {
    dropdown.addEventListener("click", function (e) {
      e.preventDefault();
      const parent = this.parentElement;

      // Close other open dropdowns
      dropdowns.forEach((other) => {
        if (other !== dropdown) {
          other.parentElement.classList.remove("active");
        }
      });

      parent.classList.toggle("active");

      overlay.addEventListener("click", () => {
        parent.classList.remove("active");
      });
    });
  });

  // Close sidebar on mobile when clicking menu items
  const menuItems = document.querySelectorAll(
    ".nav-item a:not(.dropdown-toggle)"
  );

  menuItems.forEach((item) => {
    item.addEventListener("click", () => {
      if (window.innerWidth <= 992) {
        sidebar.classList.remove("expanded");
        overlay.classList.remove("active");
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
});
