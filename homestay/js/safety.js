document.addEventListener("DOMContentLoaded", () => {
  /* Scroll reveal logic for Safety page */
  const reveals = document.querySelectorAll('.safety-page .reveal');
  
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

  /* Ensure the header knows we are on a dark page if needed */
  const mainNav = document.getElementById('mainNav');
  if (mainNav) {
    // Standard header logic might already handle this, 
    // but we can add specific behavior if needed.
  }
});
