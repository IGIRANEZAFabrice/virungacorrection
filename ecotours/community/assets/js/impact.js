// Mobile Carousel Functionality
class MobileCarousel {
    constructor() {
        this.currentSlide = 0;
        this.slides = document.querySelectorAll('.carousel-slide');
        this.dots = document.querySelectorAll('.dot');
        this.autoPlayInterval = null;
        this.autoPlayDelay = 5000; // 5 seconds
        
        this.init();
    }
    
    init() {
        if (this.slides.length === 0) return;
        
        // Add click event listeners to dots
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });
        
        // Add touch/swipe support
        this.addTouchSupport();
        
        // Start autoplay
        this.startAutoPlay();
        
        // Pause autoplay on hover
        const carousel = document.querySelector('.mobile-carousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', () => this.stopAutoPlay());
            carousel.addEventListener('mouseleave', () => this.startAutoPlay());
        }
    }
    
    goToSlide(index) {
        // Remove active class from current slide and dot
        this.slides[this.currentSlide].classList.remove('active');
        this.dots[this.currentSlide].classList.remove('active');
        
        // Update current slide index
        this.currentSlide = index;
        
        // Add active class to new slide and dot
        this.slides[this.currentSlide].classList.add('active');
        this.dots[this.currentSlide].classList.add('active');
    }
    
    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.goToSlide(nextIndex);
    }
    
    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.goToSlide(prevIndex);
    }
    
    startAutoPlay() {
        this.stopAutoPlay(); // Clear any existing interval
        this.autoPlayInterval = setInterval(() => {
            this.nextSlide();
        }, this.autoPlayDelay);
    }
    
    stopAutoPlay() {
        if (this.autoPlayInterval) {
            clearInterval(this.autoPlayInterval);
            this.autoPlayInterval = null;
        }
    }
    
    addTouchSupport() {
        const carousel = document.querySelector('.carousel-container');
        if (!carousel) return;
        
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;
        
        carousel.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        carousel.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            
            const deltaX = endX - startX;
            const deltaY = endY - startY;
            
            // Check if horizontal swipe is more significant than vertical
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            }
        });
    }
}

// Intersection Observer for scroll animations
class ScrollAnimations {
    constructor() {
        this.observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        this.init();
    }
    
    init() {
        // Create intersection observer
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, this.observerOptions);
        
        // Observe elements
        this.observeElements();
    }
    
    observeElements() {
        // Add animation classes to elements
        const elementsToAnimate = [
            '.impact-card',
            '.teaching-card',
            '.parallax-item',
            '.section-title',
            '.section-description'
        ];
        
        elementsToAnimate.forEach(selector => {
            const elements = document.querySelectorAll(selector);
            elements.forEach((element, index) => {
                element.classList.add('animate-on-scroll');
                element.style.transitionDelay = `${index * 0.1}s`;
                this.observer.observe(element);
            });
        });
    }
}

// Parallax Effect for Hero Section
class ParallaxEffect {
    constructor() {
        this.heroPattern = document.querySelector('.hero-pattern');
        this.parallaxItems = document.querySelectorAll('.parallax-item');
        
        this.init();
    }
    
    init() {
        if (!this.heroPattern && this.parallaxItems.length === 0) return;
        
        window.addEventListener('scroll', () => this.handleScroll());
    }
    
    handleScroll() {
        const scrolled = window.pageYOffset;
        const rate = scrolled * -0.5;
        
        // Hero pattern parallax
        if (this.heroPattern) {
            this.heroPattern.style.transform = `translateY(${rate}px)`;
        }
        
        // Parallax items
        this.parallaxItems.forEach((item, index) => {
            const rect = item.getBoundingClientRect();
            const speed = 0.1 + (index * 0.05);
            
            if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
                const yPos = -(scrolled - item.offsetTop) * speed;
                const image = item.querySelector('.parallax-image');
                if (image) {
                    image.style.transform = `translateY(${yPos}px)`;
                }
            }
        });
    }
}

// Smooth scroll for anchor links
class SmoothScroll {
    constructor() {
        this.init();
    }
    
    init() {
        // Add smooth scrolling to any anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }
}

// Image lazy loading
class LazyLoading {
    constructor() {
        this.images = document.querySelectorAll('img[data-src]');
        this.init();
    }
    
    init() {
        if ('IntersectionObserver' in window) {
            this.imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.loadImage(entry.target);
                        this.imageObserver.unobserve(entry.target);
                    }
                });
            });
            
            this.images.forEach(img => this.imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            this.images.forEach(img => this.loadImage(img));
        }
    }
    
    loadImage(img) {
        img.src = img.dataset.src;
        img.classList.add('loaded');
    }
}

// Responsive behavior
class ResponsiveHandler {
    constructor() {
        this.mobileBreakpoint = 768;
        this.init();
    }
    
    init() {
        window.addEventListener('resize', () => this.handleResize());
        this.handleResize(); // Initial call
    }
    
    handleResize() {
        const isMobile = window.innerWidth <= this.mobileBreakpoint;
        
        // Toggle carousel visibility based on screen size
        const carousel = document.querySelector('.mobile-carousel');
        const impactCards = document.querySelector('.impact-cards');
        
        if (carousel && impactCards) {
            if (isMobile) {
                carousel.style.display = 'block';
                impactCards.style.display = 'none';
            } else {
                carousel.style.display = 'none';
                impactCards.style.display = 'grid';
            }
        }
    }
}

// Performance optimization
class PerformanceOptimizer {
    constructor() {
        this.init();
    }
    
    init() {
        // Throttle scroll events
        let ticking = false;
        
        const throttledScroll = () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    // Trigger scroll-based animations here
                    ticking = false;
                });
                ticking = true;
            }
        };
        
        window.addEventListener('scroll', throttledScroll);
        
        // Preload critical images
        this.preloadCriticalImages();
    }
    
    preloadCriticalImages() {
        const criticalImages = [
            'uploads/impact/IMG-20250820-WA0080.jpg',
            'uploads/impact/IMG-20250820-WA0098.jpg',
            'uploads/impact/123.jpg'
        ];
        
        criticalImages.forEach(src => {
            const img = new Image();
            img.src = src;
        });
    }
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize all components
    new MobileCarousel();
    new ScrollAnimations();
    new ParallaxEffect();
    new SmoothScroll();
    new LazyLoading();
    new ResponsiveHandler();
    new PerformanceOptimizer();
    
    // Add loading complete class to body
    document.body.classList.add('loaded');
});

// Handle page visibility changes (for autoplay)
document.addEventListener('visibilitychange', () => {
    const carousel = document.querySelector('.mobile-carousel');
    if (carousel) {
        if (document.hidden) {
            // Page is hidden, pause autoplay
            window.carouselInstance?.stopAutoPlay();
        } else {
            // Page is visible, resume autoplay
            window.carouselInstance?.startAutoPlay();
        }
    }
});

