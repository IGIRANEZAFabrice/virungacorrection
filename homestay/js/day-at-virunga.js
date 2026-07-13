document.addEventListener("DOMContentLoaded", () => {
  const section = document.querySelector(".day-section");
  if (!section) return;

  const slides = section.querySelectorAll(".day-slide");
  const dotsEl = section.querySelector(".day-progress");
  const curtain = section.querySelector(".day-curtain");
  const ending = section.querySelector(".day-ending");
  const curEl = section.querySelector(".day-cur");
  const totEl = section.querySelector(".day-tot");
  const N = slides.length;
  let current = 0;
  let animating = false;
  let isSnapped = false;

  /* Build dots */
  for (let i = 0; i < N; i++) {
    const d = document.createElement("div");
    d.className = "day-dot" + (i === 0 ? " active" : "");
    d.setAttribute("role", "button");
    d.setAttribute("aria-label", "Slide " + (i + 1));
    d.onclick = () => goTo(i);
    dotsEl.appendChild(d);
  }

  function getDots() {
    return dotsEl.querySelectorAll(".day-dot");
  }

  function pad(n) {
    return String(n + 1).padStart(2, "0");
  }

  if (totEl) totEl.textContent = pad(N - 1);

  /* Transition */
  function goTo(idx, showEnd = false) {
    if (animating) return;
    if (idx === current && !showEnd) return;
    animating = true;

    curtain.classList.add("flash");

    setTimeout(() => {
      slides[current].classList.remove("active");

      if (idx < N) {
        slides[idx].classList.add("active");
        current = idx;
        if (curEl) curEl.textContent = pad(current);
        getDots().forEach((d, i) =>
          d.classList.toggle("active", i === current),
        );
      } else {
        ending.classList.add("show");
      }

      curtain.classList.remove("flash");
      setTimeout(() => {
        animating = false;
      }, 150);
    }, 260);
  }

  function next() {
    if (current === N - 1) {
      goTo(N, true);
      return;
    }
    goTo(current + 1);
  }

  function prev() {
    if (current === 0) return;
    goTo(current - 1);
  }

  window.skipDayAll = function() {
    isSnapped = false;
    if (ending) ending.classList.remove("show");
    const storiesSection = document.getElementById("guest-reviews");
    if (storiesSection) {
      storiesSection.scrollIntoView({ behavior: "smooth" });
    }
  };

  window.restartDay = function() {
    ending.classList.remove("show");
    slides.forEach((s, i) => s.classList.toggle("active", i === 0));
    getDots().forEach((d, i) => d.classList.toggle("active", i === 0));
    current = 0;
    if (curEl) curEl.textContent = pad(0);
    isSnapped = true; // Re-snap on restart
  };

  /* ── SMOOTH SCROLL ── */
  let wheelAccum = 0;
  let wheelTimer = null;
  const WHEEL_THRESHOLD = 30;

  let sectionInView = false;
  let lastScrollY = window.scrollY;

  const observer = new IntersectionObserver((entries) => {
    sectionInView = entries[0].isIntersecting;
    
    // Auto-snap when section top is close to viewport top and scrolling DOWN
    if (sectionInView && !isSnapped && window.scrollY > lastScrollY) {
      const rect = section.getBoundingClientRect();
      if (rect.top > 0 && rect.top < 150) {
        snapToSection();
      }
    }
    lastScrollY = window.scrollY;
  }, { threshold: [0, 0.1, 0.5, 0.9] });
  observer.observe(section);

  function snapToSection() {
    if (isSnapped) return;
    isSnapped = true;
    window.scrollTo({
      top: section.offsetTop,
      behavior: 'smooth'
    });
  }

  window.addEventListener(
    "wheel",
    (e) => {
      if (!sectionInView) return;
      
      const rect = section.getBoundingClientRect();
      const isAtTop = Math.abs(rect.top) < 15;

      // RELEASE SNAP if scrolling UP from first slide
      if (isSnapped && current === 0 && e.deltaY < 0) {
        isSnapped = false;
        return; // Allow normal scroll
      }

      // RELEASE SNAP if scrolling DOWN from ending
      if (isSnapped && ending.classList.contains("show") && e.deltaY > 0) {
        isSnapped = false;
        return; // Allow normal scroll
      }

      // If we are at the top and scrolling down, snap it
      if (!isSnapped && isAtTop && e.deltaY > 0 && !ending.classList.contains("show")) {
        snapToSection();
      }

      // If snapped, prevent default and handle slides
      if (isSnapped) {
        e.preventDefault();
        if (animating) return;

        wheelAccum += e.deltaY;
        clearTimeout(wheelTimer);

        if (Math.abs(wheelAccum) >= WHEEL_THRESHOLD) {
          const dir = wheelAccum > 0 ? 1 : -1;
          wheelAccum = 0;
          if (dir > 0) next();
          else prev();
          return;
        }

        wheelTimer = setTimeout(() => {
          wheelAccum = 0;
        }, 300);
      }
    },
    { passive: false }
  );

  /* Keyboard */
  window.addEventListener("keydown", (e) => {
    if (!sectionInView || !isSnapped) return;
    if (ending.classList.contains("show")) return;

    if (e.key === "ArrowDown" || e.key === "ArrowRight") {
      e.preventDefault();
      next();
    }
    if (e.key === "ArrowUp" || e.key === "ArrowLeft") {
      e.preventDefault();
      prev();
    }
    if (e.key === "Escape") skipDayAll();
  });

  /* Touch swipe */
  let touchStartY = 0;
  window.addEventListener(
    "touchstart",
    (e) => {
      touchStartY = e.changedTouches[0].clientY;
    },
    { passive: true }
  );
  window.addEventListener(
    "touchend",
    (e) => {
      if (!sectionInView || !isSnapped) return;
      if (ending.classList.contains("show")) return;

      const dy = touchStartY - e.changedTouches[0].clientY;
      if (Math.abs(dy) > 30) {
        dy > 0 ? next() : prev();
      }
    },
    { passive: true }
  );
});
