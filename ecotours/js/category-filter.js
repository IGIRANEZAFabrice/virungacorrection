document.addEventListener("DOMContentLoaded", function () {
  const filterContainer = document.querySelector(".filter-container");
  const filterButtons = document.querySelectorAll(".filter-btn");
  const tourCards = document.querySelectorAll(".tour-card");
  const toursContainer = document.getElementById("toursContainer");
  const cardsToShow = 6;
  let currentlyShown = 6;

  // 1. If there are no tour cards rendered, completely remove the filter bar
  if (!tourCards || tourCards.length === 0) {
    if (filterContainer) {
      filterContainer.style.display = "none";
    }
    return;
  }

  // 2. Discover all unique categories present on actual rendered tour cards
  const availableCategories = new Set();
  tourCards.forEach((card) => {
    const cardBadge = card.querySelector(".tour-badge");
    if (cardBadge) {
      const catText = cardBadge.textContent.trim().toLowerCase();
      if (catText) {
        availableCategories.add(catText);
      }
    }
  });

  // 3. Hide any filter button that has 0 tours on the page
  let visibleButtonsCount = 0;
  filterButtons.forEach((btn) => {
    const cat = (btn.getAttribute("data-category") || "").toLowerCase();
    if (cat === "all") {
      btn.style.display = "";
      visibleButtonsCount++;
    } else if (availableCategories.has(cat)) {
      btn.style.display = "";
      visibleButtonsCount++;
    } else {
      btn.style.display = "none";
    }
  });

  // If only 1 category exists or no subcategories, optionally keep or adjust
  if (visibleButtonsCount <= 1 && filterContainer) {
    filterContainer.style.display = "none";
  }

  // 4. Helper function to show/hide cards based on category
  function updateCardVisibility(selectedCategory) {
    let visibleCount = 0;
    const catTarget = (selectedCategory || "all").toLowerCase();

    // Remove any previous no-category notice
    const existingNotice = document.getElementById("noCatToursNotice");
    if (existingNotice) {
      existingNotice.remove();
    }

    tourCards.forEach((card) => {
      const cardBadge = card.querySelector(".tour-badge");
      const cardCategory = cardBadge ? cardBadge.textContent.trim().toLowerCase() : "";

      // Reset display
      card.style.display = "";

      const matchesCategory = (catTarget === "all" || cardCategory === catTarget);

      if (matchesCategory) {
        if (visibleCount < currentlyShown) {
          card.classList.add("visible");
        } else {
          card.classList.remove("visible");
        }
        visibleCount++;
      } else {
        card.style.display = "none";
        card.classList.remove("visible");
      }
    });

    // If 0 cards matched this category
    if (visibleCount === 0 && toursContainer) {
      const notice = document.createElement("p");
      notice.id = "noCatToursNotice";
      notice.className = "no-tours";
      notice.textContent = "No tours available in this category.";
      toursContainer.appendChild(notice);
    }

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
      currentlyShown = cardsToShow;
      updateCardVisibility(selectedCategory);
    });
  });

  // Handle initial active state
  const activeButton = document.querySelector(".filter-btn.active");
  if (activeButton) {
    updateCardVisibility(activeButton.getAttribute("data-category"));
  } else {
    updateCardVisibility("all");
  }
});
