document.addEventListener("DOMContentLoaded", function () {
  const cardsContainer = document.getElementById("toursContainer");
  const viewMoreBtn = document.getElementById("viewMoreBtn");
  const cardsToShow = 6;

  viewMoreBtn.addEventListener("click", function () {
    const activeCategory = document
      .querySelector(".filter-btn.active")
      .getAttribute("data-category");
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
});
