// Scroll animations
function initScrollAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    // Observe all elements with scroll-animate class
    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });
}

// Smooth scrolling for anchor links
function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Parallax effect for the parallax section
let _parallaxScrollHandler = null;
function initParallaxEffect() {
    const parallaxSection = document.querySelector('.parallax-section');
    // Remove previous handler to avoid duplicates on resize
    if (_parallaxScrollHandler) {
        window.removeEventListener('scroll', _parallaxScrollHandler);
        _parallaxScrollHandler = null;
    }
    if (!parallaxSection) return;

    _parallaxScrollHandler = function updateParallax() {
        const scrolled = window.pageYOffset;
        const sectionTop = parallaxSection.offsetTop;
        const sectionHeight = parallaxSection.offsetHeight;
        const windowHeight = window.innerHeight;
        
        // Only apply parallax when section is in view and limit movement
        if (scrolled + windowHeight > sectionTop && scrolled < sectionTop + sectionHeight) {
            const parallaxElement = parallaxSection.querySelector('.parallax-content');
            
            // Calculate relative position within the section
            const sectionProgress = Math.max(0, Math.min(1, (scrolled + windowHeight - sectionTop) / (sectionHeight + windowHeight)));
            const maxMovement = 50; // Limit maximum movement to 50px
            const rate = (sectionProgress - 0.5) * maxMovement;
            
            if (parallaxElement) {
                parallaxElement.style.transform = `translateY(${rate}px) translateZ(0)`;
            }
        } else {
            // Reset position when section is not in view
            const parallaxElement = parallaxSection.querySelector('.parallax-content');
            if (parallaxElement) {
                parallaxElement.style.transform = `translateY(0px) translateZ(0)`;
            }
        }
    };

    // Only apply parallax on desktop to avoid performance issues on mobile
    if (window.innerWidth > 1024) {
        window.addEventListener('scroll', _parallaxScrollHandler, { passive: true });
        _parallaxScrollHandler();
    }
}

// Counter animation for statistics
function initCounterAnimation() {
    const counters = document.querySelectorAll('.stat-number');
    
    const animateCounter = (counter) => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60fps
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            
            // Format number with commas
            counter.textContent = Math.floor(current).toLocaleString();
        }, 16);
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateCounter(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

// Add hover effects to cards
function initCardHoverEffects() {
    const cards = document.querySelectorAll('.program-card, .activity-card, .testimonial-card');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Initialize all functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initSmoothScrolling();
    initParallaxEffect();
    initCounterAnimation();
    initCardHoverEffects();
    initPartnersSlider();
});

// Handle window resize for parallax
window.addEventListener('resize', function() {
    // Reinitialize parallax on resize
    initParallaxEffect();
});

// Add loading animation
window.addEventListener('load', function() {
    document.body.classList.add('loaded');
});

// Partners slider logic
let _partnersSliderInitialized = false;
function initPartnersSlider() {
    const container = document.getElementById('sliderContainer');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');
    if (!container || _partnersSliderInitialized) return;
    _partnersSliderInitialized = true;

    // Compute item width for scroll increments
    const getStep = () => {
        const item = container.querySelector('.slider-item');
        if (!item) return 200;
        const style = window.getComputedStyle(item);
        const marginRight = parseFloat(style.marginRight || '0');
        return item.getBoundingClientRect().width + marginRight;
    };

    // Button controls
    const scrollByAmount = (amount) => {
        container.scrollBy({ left: amount, behavior: 'smooth' });
    };

    if (prevBtn) {
        prevBtn.addEventListener('click', () => scrollByAmount(-getStep() * 2));
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', () => scrollByAmount(getStep() * 2));
    }

    // Autoplay with pause on hover
    let autoplayTimer;
    const startAutoplay = () => {
        stopAutoplay();
        autoplayTimer = setInterval(() => {
            // If near end, loop back
            if (container.scrollLeft + container.clientWidth + 5 >= container.scrollWidth) {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                scrollByAmount(getStep());
            }
        }, 3000);
    };
    const stopAutoplay = () => { if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; } };

    container.addEventListener('mouseenter', stopAutoplay);
    container.addEventListener('mouseleave', startAutoplay);
    startAutoplay();

    // Pause when tab is hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoplay();
        } else {
            startAutoplay();
        }
    });

    // Keyboard accessibility
    container.setAttribute('tabindex', '0');
    container.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            scrollByAmount(getStep());
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            scrollByAmount(-getStep());
        }
    });
}