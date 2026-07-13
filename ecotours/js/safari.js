// Safari Page JavaScript
document.addEventListener("DOMContentLoaded", function () {
  // Initialize safari page functionality
  initializeSafariPage();
});

function initializeSafariPage() {
  // Smooth scroll for hero scroll indicator
  initializeHeroScroll();
  // Initialize view more functionality
  initializeViewMore();
}

// Hero scroll functionality
function initializeHeroScroll() {
  const scrollDown = document.getElementById("scroll-down");
  const heroSection = document.getElementById("hero");

  if (scrollDown && heroSection) {
    scrollDown.addEventListener("click", () => {
      const nextSection = heroSection.nextElementSibling;
      if (nextSection) {
        nextSection.scrollIntoView({ behavior: "smooth" });
      }
    });
  }
}

// View More functionality for safari tours
function initializeViewMore() {
  const viewMoreBtn = document.getElementById("viewMoreBtn");
  const tourCards = document.querySelectorAll(".safari-tour-card");
  const cardsPerLoad = 6;
  let visibleCardCount = 0;

  function showNextCards() {
    const currentlyVisible = document.querySelectorAll(
      ".safari-tour-card.visible"
    ).length;
    let newlyVisibleCount = 0;

    for (let i = currentlyVisible; i < tourCards.length; i++) {
      if (newlyVisibleCount < cardsPerLoad) {
        tourCards[i].style.display = "block";
        tourCards[i].classList.add("visible");
        newlyVisibleCount++;
      }
    }

    visibleCardCount = document.querySelectorAll(
      ".safari-tour-card.visible"
    ).length;

    if (visibleCardCount >= tourCards.length) {
      viewMoreBtn.style.display = "none";
    } else {
      const remaining = tourCards.length - visibleCardCount;
      viewMoreBtn.innerHTML = `<i class="fas fa-plus"></i> Load More Tours (${remaining} remaining)`;
    }
  }

  // Initially show the first batch of cards
  showNextCards();

  if (viewMoreBtn) {
    if (tourCards.length <= cardsPerLoad) {
      viewMoreBtn.style.display = "none";
    } else {
      viewMoreBtn.addEventListener("click", showNextCards);
      const remaining = tourCards.length - visibleCardCount;
      viewMoreBtn.innerHTML = `<i class="fas fa-plus"></i> Load More Tours (${remaining} remaining)`;
    }
  }
}

// Smooth scrolling for internal links
document.addEventListener("click", function (e) {
  if (e.target.matches('a[href^="#"]')) {
    e.preventDefault();
    const targetId = e.target.getAttribute("href").substring(1);
    const targetElement = document.getElementById(targetId);

    if (targetElement) {
      targetElement.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  }
});
