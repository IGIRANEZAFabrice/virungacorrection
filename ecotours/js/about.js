document.addEventListener("DOMContentLoaded", function () {
  const parallaxSections = document.querySelectorAll(".sticky-section");
  let ticking = false;

  function updateParallax() {
    parallaxSections.forEach((section) => {
      const image = section.querySelector(".sticky-image img");
      const rect = section.getBoundingClientRect();
      const windowHeight = window.innerHeight;

      if (rect.top < windowHeight && rect.bottom > 0) {
        const scrollPercent =
          (windowHeight - rect.top) / (windowHeight + rect.height);
        const moveDistance = 200; // Increased from 150 to 200 for even faster movement
        const movement = moveDistance * scrollPercent;

        // Even faster transition
        image.style.transform = `translate3d(0, ${-movement}px, 0)`;
        image.style.transition =
          "transform 0.02s cubic-bezier(0.33, 1, 0.68, 1)";
      }
    });
    ticking = false;
  }

  // Throttle scroll events
  window.addEventListener(
    "scroll",
    () => {
      if (!ticking) {
        requestAnimationFrame(() => {
          updateParallax();
        });
        ticking = true;
      }
    },
    { passive: true }
  );

  // Initial update
  updateParallax();
});
