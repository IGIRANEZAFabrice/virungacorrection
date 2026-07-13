document.addEventListener("DOMContentLoaded", () => {
  // Rooms/Why reveal on scroll
  const revealEls = [
    { id: "roomsLabel", cls: "in" },
    { id: "roomsHeading", cls: "in" },
    { id: "roomsActions", cls: "in" },
    { id: "whyHeading", cls: "in" },
  ];

  const revObs = new IntersectionObserver(
    (entries) => entries.forEach((e) => e.isIntersecting && e.target.classList.add("in")),
    { threshold: 0.25 },
  );
  revealEls.forEach(({ id }) => {
    const el = document.getElementById(id);
    if (el) revObs.observe(el);
  });
  document.querySelectorAll("[data-reveal]").forEach((el) => revObs.observe(el));

  const roomCards = document.querySelectorAll(".room-card");
  const roomObs = new IntersectionObserver(
    (entries) => entries.forEach((e) => e.isIntersecting && e.target.classList.add("in")),
    { threshold: 0.15 },
  );
  roomCards.forEach((c) => roomObs.observe(c));

  // Metric counters in about card
  const counters = document.querySelectorAll("[data-count]");
  const counterObs = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return;
        const el = e.target;
        const target = parseFloat(el.dataset.target || "0");
        const suffix = el.dataset.suffix || "";
        const duration = 1200;
        const startTime = performance.now();
        const start = 0;
        const step = (now) => {
          const p = Math.min((now - startTime) / duration, 1);
          const val = Math.floor(start + (target - start) * p);
          el.textContent = `${val}${suffix}`;
          if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
        obs.unobserve(el);
      });
    },
    { threshold: 0.5 },
  );
  counters.forEach((c) => counterObs.observe(c));

  // ══ GUEST REVIEWS: Auto-Scrolling ══
  const reviewsGrid = document.querySelector(".guest-reviews__grid");
  let isScrolling = false;

  if (reviewsGrid) {
    // Auto-scroll logic
    let scrollInterval = setInterval(() => {
      if (isScrolling) return;
      
      const card = reviewsGrid.querySelector(".guest-review-card");
      if (!card) return;
      const cardWidth = card.offsetWidth + 24;
      const maxScroll = reviewsGrid.scrollWidth - reviewsGrid.offsetWidth;
      
      if (reviewsGrid.scrollLeft >= maxScroll - 10) {
        reviewsGrid.scrollTo({ left: 0, behavior: "smooth" });
      } else {
        reviewsGrid.scrollBy({ left: cardWidth, behavior: "smooth" });
      }
    }, 4000);

    // Pause on interaction
    reviewsGrid.addEventListener("mouseenter", () => isScrolling = true);
    reviewsGrid.addEventListener("mouseleave", () => isScrolling = false);
    reviewsGrid.addEventListener("touchstart", () => isScrolling = true);
    reviewsGrid.addEventListener("touchend", () => isScrolling = false);
  }

  // ══ A DAY AT VIRUNGA: Scroll-Driven Slides ══
  const daySection = document.getElementById("daySection");
  const daySlides = document.querySelectorAll(".day-slide");
  const dayDots = document.querySelectorAll(".day-dot");
  const dayCurText = document.querySelector(".day-cur");

  // Preload images for "A Day at Virunga"
  if (daySection) {
    const imagesToPreload = [
      "./img/day/1.jpeg",
      "./img/day/2.jpeg",
      "./img/day/333.jpeg",
      "./img/day/4.jpeg",
      "./img/day/5.jpeg",
      "./img/day/6.jpeg",
      "./img/day/7.jpeg"
    ];
    
    imagesToPreload.forEach(src => {
      const img = new Image();
      img.src = src;
    });
  }

  if (daySection && daySlides.length > 0) {
    const updateDayScroll = () => {
      const rect = daySection.getBoundingClientRect();
      const sectionHeight = rect.height;
      const viewportHeight = window.innerHeight;
      
      // Calculate progress (0 to 1) based on how much of the section has scrolled past the top
      let progress = -rect.top / (sectionHeight - viewportHeight);
      progress = Math.max(0, Math.min(1, progress));

      // Determine active index
      const activeIdx = Math.min(daySlides.length - 1, Math.floor(progress * daySlides.length));

      // Toggle active classes
      daySlides.forEach((slide, i) => {
        slide.classList.toggle("active", i === activeIdx);
      });
      dayDots.forEach((dot, i) => {
        dot.classList.toggle("active", i === activeIdx);
      });

      // Update counter
      if (dayCurText) {
        dayCurText.textContent = (activeIdx + 1).toString().padStart(2, "0");
      }
    };

    window.addEventListener("scroll", updateDayScroll, { passive: true });
    updateDayScroll(); // Initial check
  }
});

// Helper for the "View Testimonials" button in the day section
function scrollToTestimonials() {
  const target = document.getElementById("testimonials");
  if (target) {
    target.scrollIntoView({ behavior: "smooth" });
  }
}
