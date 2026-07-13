let isExpanding = false;

function handleScroll() {
  const middleSection = document.querySelector(".middle-section");
  const maskText = document.querySelector(".mask-text");
  const backgroundReveal = document.querySelector(".background-reveal");

  const rect = middleSection.getBoundingClientRect();
  const windowHeight = window.innerHeight;
  const sectionCenter = rect.top + rect.height / 2;
  // Move the viewport center up by 30% of window height
  const offset = windowHeight * 0.3;
  const viewportCenter = windowHeight / 2 - offset;

  // Widen the range: effect starts when section center is within 90% of viewport height from center
  const effectRange = windowHeight * 0.9;
  let progress =
    1 - Math.abs(sectionCenter - viewportCenter) / (effectRange / 2);
  progress = Math.max(0, Math.min(1, progress));

  if (progress > 0) {
    // Animate for a wider range
    isExpanding = true;
    middleSection.classList.add("expanding");

    // Dramatic zoom, peak at offset center
    const textScale = 1 + progress * 18; // Scale from 1 to 19
    const textOpacity = Math.max(0, 1 - progress * 2); // Fade out text
    const bgOpacity = Math.min(1, progress * 2.5); // Fade in background
    const bgScale = 1.5 - progress * 0.5; // Scale from 1.5 to 1

    maskText.style.transform = `scale(${textScale})`;
    maskText.style.opacity = textOpacity;
    backgroundReveal.style.opacity = bgOpacity;
    backgroundReveal.style.transform = `scale(${bgScale})`;

    if (progress >= 1) {
      maskText.style.opacity = "0";
      backgroundReveal.style.opacity = "1";
      backgroundReveal.style.transform = "scale(1)";
    }
  } else {
    // Reset when not near center
    isExpanding = false;
    middleSection.classList.remove("expanding");
    maskText.style.transform = "scale(1)";
    maskText.style.opacity = "1";
    backgroundReveal.style.opacity = "0";
    backgroundReveal.style.transform = "scale(1.5)";
  }
}

// Optimized scroll handling
let ticking = false;

window.addEventListener("scroll", () => {
  if (!ticking) {
    requestAnimationFrame(() => {
      handleScroll();
      ticking = false;
    });
    ticking = true;
  }
});

// Initial call
handleScroll();
