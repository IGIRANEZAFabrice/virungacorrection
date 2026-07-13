document.addEventListener("DOMContentLoaded", function () {
  const filterButtons = document.querySelectorAll(".filter-btn");
  const tourCards = document.querySelectorAll(".tour-card");
  const cardsToShow = 6;
  let currentlyShown = 6;

  // Helper function to show/hide cards based on category and current count
  function updateCardVisibility(selectedCategory) {
    let visibleCount = 0;

    tourCards.forEach((card) => {
      const cardBadge = card.querySelector(".tour-badge");
      const cardCategory = cardBadge ? cardBadge.textContent.toLowerCase() : "";

      // Reset display to default first
      card.style.display = "";

      // Check if card matches category
      const matchesCategory =
        selectedCategory === "all" ||
        cardCategory === selectedCategory.toLowerCase();

      if (matchesCategory) {
        if (visibleCount < currentlyShown) {
          card.classList.add("visible");
        } else {
          card.classList.remove("visible");
        }
        visibleCount++;
      } else {
        card.style.display = "none";
      }
    });

    // Update view more button visibility
    const viewMoreBtn = document.getElementById("viewMoreBtn");
    if (viewMoreBtn) {
      viewMoreBtn.style.display = visibleCount > currentlyShown ? "" : "none";
    }

    return visibleCount;
  }

  filterButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault();

      filterButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      const selectedCategory = this.getAttribute("data-category");
      currentlyShown = cardsToShow; // Reset to initial count when changing category
      updateCardVisibility(selectedCategory);
    });
  });

  // Handle the initial state
  const activeButton = document.querySelector(".filter-btn.active");
  if (activeButton) {
    updateCardVisibility(activeButton.getAttribute("data-category"));
  }
});
