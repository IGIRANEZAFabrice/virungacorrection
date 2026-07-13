document.addEventListener("DOMContentLoaded", function () {
  // Get all action menu toggles
  const actionMenuToggles = document.querySelectorAll(".action-menu-toggle");

  // Function to close all dropdowns
  function closeAllDropdowns() {
    document.querySelectorAll(".action-dropdown").forEach((dropdown) => {
      dropdown.classList.remove("show");
    });
  }

  // Add click event to each toggle button
  actionMenuToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.stopPropagation(); // Prevent event from bubbling up

      // Close all other dropdowns first
      closeAllDropdowns();

      // Toggle the clicked dropdown
      const dropdown = this.nextElementSibling;
      dropdown.classList.toggle("show");
    });
  });

  // Close dropdowns when clicking outside
  document.addEventListener("click", function (e) {
    if (!e.target.closest(".blog-actions")) {
      closeAllDropdowns();
    }
  });

  // Handle action button clicks
  document.querySelectorAll(".action-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const action = this.classList.contains("view")
        ? "view"
        : this.classList.contains("edit")
        ? "edit"
        : "";
      // Use blogId instead of postId for clarity
      const blogId = this.dataset.id; 

      // Handle different actions
      switch (action) {
        case "view":
           // Use blogId in the URL
          window.location.href = `view_blog.php?id=${blogId}`;
          break;
        case "edit":
           // Use blogId in the URL
          window.location.href = `edit_blog.php?id=${blogId}`; 
          break;
      }

      // Close the dropdown after action
      closeAllDropdowns();
    });
  });
});

// Handle delete button click
document.addEventListener("click", function (e) {
  if (e.target.closest(".action-btn.delete")) {
    // Use blogId instead of postId
    const blogId = e.target.closest(".action-btn").dataset.id; 

    if (confirm("Are you sure you want to delete this blog post?")) {
      fetch("../handlers/blog/deleteBlogHandler.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        // Send blog_id in the request body
        body: `blog_id=${blogId}`, 
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Remove the blog card from the DOM
            e.target.closest(".blog-card").remove();
            // Show success message
            alert("Blog post deleted successfully");
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("An error occurred while deleting the blog post");
        });
    }
  }
});
