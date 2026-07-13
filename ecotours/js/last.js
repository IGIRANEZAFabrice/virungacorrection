// Mobile grid functionality
document.addEventListener("DOMContentLoaded", function () {
  // Only apply this JavaScript for mobile view
  if (window.innerWidth <= 576) {
    const travelGrid = document.getElementById("travel-grid");
    const prevBtn = document.getElementById("prev-btn");
    const nextBtn = document.getElementById("next-btn");
    const monthIndicator = document.getElementById("month-indicator");
    const monthCards = document.querySelectorAll(".month-card");
    const months = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];
    let currentIndex = 0;

    // Update indicator based on visible card
    function updateIndicator() {
      monthIndicator.textContent = months[currentIndex];
    }

    // Scroll to specific card
    function scrollToCard(index) {
      if (index < 0) index = 0;
      if (index >= monthCards.length) index = monthCards.length - 1;

      currentIndex = index;
      monthCards[index].scrollIntoView({
        behavior: "smooth",
        inline: "center",
      });
      updateIndicator();
    }

    // Next button click handler
    nextBtn.addEventListener("click", function () {
      scrollToCard(currentIndex + 1);
    });

    // Previous button click handler
    prevBtn.addEventListener("click", function () {
      scrollToCard(currentIndex - 1);
    });

    // Listen for scroll end to update indicator
    let timeout;
    travelGrid.addEventListener("scroll", function () {
      clearTimeout(timeout);
      timeout = setTimeout(function () {
        // Find which card is most visible
        const cards = Array.from(monthCards);
        let mostVisibleIndex = 0;
        let maxVisibility = 0;

        cards.forEach((card, index) => {
          const rect = card.getBoundingClientRect();
          const visibleWidth =
            Math.min(rect.right, window.innerWidth) - Math.max(rect.left, 0);
          if (visibleWidth > maxVisibility) {
            maxVisibility = visibleWidth;
            mostVisibleIndex = index;
          }
        });

        currentIndex = mostVisibleIndex;
        updateIndicator();
      }, 150);
    });

    // Initialize
    updateIndicator();
  }
});

