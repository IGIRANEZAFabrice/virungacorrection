document.addEventListener("DOMContentLoaded", function () {
  // Profile tab switching
  const tabButtons = document.querySelectorAll(".profile-nav button");
  const sections = document.querySelectorAll(".profile-section");

  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const tabName = button.getAttribute("data-tab");

      // Update active states
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      sections.forEach((section) => section.classList.remove("active"));

      button.classList.add("active");
      document.getElementById(tabName).classList.add("active");
    });
  });

  // Profile image upload preview
  const profileImageInput = document.getElementById("profileImageInput");
  const profileImagePreview = document.getElementById("profileImagePreview");

  if (profileImageInput && profileImagePreview) {
    profileImageInput.addEventListener("change", function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
          profileImagePreview.src = event.target.result;
        };

        reader.readAsDataURL(file);
      }
    });

    // Handle drag and drop functionality
    const imagePreviewContainer = document.querySelector(".image-preview");

    imagePreviewContainer.addEventListener("dragover", (e) => {
      e.preventDefault();
      imagePreviewContainer.classList.add("dragover");
    });

    imagePreviewContainer.addEventListener("dragleave", () => {
      imagePreviewContainer.classList.remove("dragover");
    });

    imagePreviewContainer.addEventListener("drop", (e) => {
      e.preventDefault();
      imagePreviewContainer.classList.remove("dragover");

      if (e.dataTransfer.files.length) {
        profileImageInput.files = e.dataTransfer.files;
        const event = new Event("change");
        profileImageInput.dispatchEvent(event);
      }
    });
  }
});
