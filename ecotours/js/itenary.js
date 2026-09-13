document.addEventListener("DOMContentLoaded", function () {
  const cardsContainer = document.getElementById("toursContainer");
  const viewMoreBtn = document.getElementById("viewMoreBtn");
  const cardsToShow = 6;

  if (viewMoreBtn && cardsContainer) {
    viewMoreBtn.addEventListener("click", function () {
      const activeBtn = document.querySelector(".filter-btn.active");
      const activeCategory = activeBtn ? activeBtn.getAttribute("data-category") : "all";
      const cards = cardsContainer.querySelectorAll(".tour-card");
      let visibleCards = 0;

      cards.forEach((card) => {
        if (card.style.display !== "none") {
          if (!card.classList.contains("visible")) {
            if (visibleCards < cardsToShow) {
              card.classList.add("visible");
              visibleCards++;
            }
          }
        }
      });

      // Check if we should hide the view more button
      const remainingCards = Array.from(cards).filter(
        (card) =>
          card.style.display !== "none" && !card.classList.contains("visible")
      ).length;

      if (remainingCards === 0) {
        viewMoreBtn.style.display = "none";
      }
    });
  }
});
