function initSafety() {
  const page = document.querySelector('.safety-page');
  if (!page) return;

  const reveals = page.querySelectorAll('.reveal');
  
  // Only apply scroll reveal if IntersectionObserver is supported
  if ('IntersectionObserver' in window) {
    // Add js-active to trigger the hidden styles in CSS
    page.classList.add('js-active');

    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          io.unobserve(e.target);
        }
      });
    }, { 
      threshold: 0.1, 
      rootMargin: '0px 0px -40px 0px' 
    });

    reveals.forEach(el => io.observe(el));
  }
}

// Run immediately if DOM is already parsed, otherwise wait for DOMContentLoaded
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initSafety);
} else {
  initSafety();
}
