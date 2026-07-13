document.addEventListener("DOMContentLoaded", () => {
  const filterButtons = document.querySelectorAll(
    ".filter-categories .filter-btn"
  );
  const holidayCards = document.querySelectorAll(
    ".holiday-cards .holiday-card"
  );
  const showMoreBtn = document.querySelector(".show-more-btn");
  const CARDS_TO_SHOW = 3;
  let currentlyShowing = CARDS_TO_SHOW;

  // Initially hide cards beyond the first three
  holidayCards.forEach((card, index) => {
    if (index >= CARDS_TO_SHOW) {
      card.style.display = "none";
    }
  });

  // Filter functionality
  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Reset cards counter
      currentlyShowing = CARDS_TO_SHOW;

      // Remove active class from all buttons
      filterButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      const selectedCategory = this.getAttribute("data-category").toLowerCase();
      let visibleCount = 0;

      holidayCards.forEach((card, index) => {
        const cardCategory = card.getAttribute("data-category").toLowerCase();

        if (selectedCategory === "all" || selectedCategory === cardCategory) {
          if (visibleCount < CARDS_TO_SHOW) {
            card.style.display = "";
            card.style.opacity = "0";
            setTimeout(() => {
              card.style.opacity = "1";
            }, 50);
          } else {
            card.style.display = "none";
          }
          visibleCount++;
        } else {
          card.style.display = "none";
        }
      });

      // Show/hide "Show More" button based on filtered results
      const totalFilteredCards = Array.from(holidayCards).filter((card) => {
        const cardCategory = card.getAttribute("data-category").toLowerCase();
        return selectedCategory === "all" || selectedCategory === cardCategory;
      }).length;

      showMoreBtn.style.display =
        totalFilteredCards > CARDS_TO_SHOW ? "" : "none";
    });
  });

  // Show More functionality
  showMoreBtn.addEventListener("click", () => {
    const selectedCategory = document
      .querySelector(".filter-btn.active")
      .getAttribute("data-category")
      .toLowerCase();
    let visibleCount = 0;
    let shownInThisBatch = 0;

    holidayCards.forEach((card) => {
      const cardCategory = card.getAttribute("data-category").toLowerCase();

      if (selectedCategory === "all" || selectedCategory === cardCategory) {
        visibleCount++;
        if (
          visibleCount > currentlyShowing &&
          shownInThisBatch < CARDS_TO_SHOW
        ) {
          card.style.display = "";
          card.style.opacity = "0";
          setTimeout(() => {
            card.style.opacity = "1";
          }, 50);
          shownInThisBatch++;
        }
      }
    });

    currentlyShowing += CARDS_TO_SHOW;

    // Hide "Show More" button if all cards are shown
    const totalFilteredCards = Array.from(holidayCards).filter((card) => {
      const cardCategory = card.getAttribute("data-category").toLowerCase();
      return selectedCategory === "all" || selectedCategory === cardCategory;
    }).length;

    if (currentlyShowing >= totalFilteredCards) {
      showMoreBtn.style.display = "none";
    }
  });
});
