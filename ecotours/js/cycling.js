// Cycling Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cycling page functionality
    initializeCyclingPage();
});

function initializeCyclingPage() {
    // Initialize scroll animations
    initializeScrollAnimations();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
    
    // Initialize hero animations
    initializeHeroAnimations();
    
    // Initialize route card interactions
    initializeRouteCards();
}

// Hero Animations
function initializeHeroAnimations() {
    const heroStats = document.querySelectorAll('.stat-number');
    
    // Animate stats on load
    setTimeout(() => {
        heroStats.forEach((stat, index) => {
            const finalValue = parseInt(stat.textContent);
            animateCounter(stat, 0, finalValue, 2000, index * 200);
        });
    }, 500);
}

function animateCounter(element, start, end, duration, delay) {
    setTimeout(() => {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }
            
            // Format numbers with + for 1000+ and 365
            if (end >= 1000) {
                element.textContent = Math.floor(current) + '+';
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }, delay);
}

// Route Cards Interactions
function initializeRouteCards() {
    const routeCards = document.querySelectorAll('.route-card');
    
    routeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Add subtle animation to route stats
            const stats = this.querySelectorAll('.route-stats .stat');
            stats.forEach((stat, index) => {
                setTimeout(() => {
                    stat.style.transform = 'scale(1.05)';
                    stat.style.transition = 'transform 0.3s ease';
                }, index * 100);
            });
        });
        
        card.addEventListener('mouseleave', function() {
            const stats = this.querySelectorAll('.route-stats .stat');
            stats.forEach(stat => {
                stat.style.transform = 'scale(1)';
            });
        });
    });
}

// Scroll Animations
function initializeScrollAnimations() {
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const handleIntersect = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    
                    // Special handling for different elements
                    if (entry.target.classList.contains('experience-card')) {
                        animateExperienceCard(entry.target);
                    } else if (entry.target.classList.contains('stat-card')) {
                        animateStatCard(entry.target);
                    } else if (entry.target.classList.contains('benefit-item')) {
                        animateBenefitItem(entry.target);
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        };

        const observer = new IntersectionObserver(handleIntersect, observerOptions);

        // Observe elements for animation
        const animateElements = document.querySelectorAll(`
            .intro-text, .intro-image, .experience-card, .route-card,
            .tour-card, .stat-card, .benefit-item, .section-header
        `);

        animateElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });

        // Add CSS for animate-in class
        const style = document.createElement('style');
        style.textContent = `
            .animate-in {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        `;
        document.head.appendChild(style);
    }
}

function animateExperienceCard(card) {
    const overlay = card.querySelector('.experience-overlay');
    if (overlay) {
        setTimeout(() => {
            overlay.style.transform = 'scale(1.1) rotate(5deg)';
            overlay.style.transition = 'transform 0.5s ease';
            
            setTimeout(() => {
                overlay.style.transform = 'scale(1) rotate(0deg)';
            }, 500);
        }, 300);
    }
}

function animateStatCard(card) {
    const icon = card.querySelector('.stat-icon');
    const number = card.querySelector('.stat-number');
    
    if (icon) {
        setTimeout(() => {
            icon.style.transform = 'scale(1.2) rotate(360deg)';
            icon.style.transition = 'transform 0.8s ease';
            
            setTimeout(() => {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }, 800);
        }, 200);
    }
    
    if (number) {
        const finalValue = parseInt(number.textContent.replace(/[^0-9]/g, ''));
        setTimeout(() => {
            animateCounter(number, 0, finalValue, 1500, 0);
        }, 400);
    }
}

function animateBenefitItem(item) {
    const icon = item.querySelector('.benefit-icon');
    if (icon) {
        setTimeout(() => {
            icon.style.transform = 'scale(1.1)';
            icon.style.transition = 'transform 0.3s ease';
            
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 300);
        }, 200);
    }
}

// Smooth Scrolling
function initializeSmoothScrolling() {
    const scrollLinks = document.querySelectorAll('a[href^="#"]');
    
    scrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = 80; // Adjust based on your header height
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Parallax Effect for Hero Section
function initializeParallax() {
    const hero = document.querySelector('.hero-section');
    
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            hero.style.transform = `translateY(${rate}px)`;
        });
    }
}

// Tour Card Hover Effects
function initializeTourCards() {
    const tourCards = document.querySelectorAll('.tour-card');
    
    tourCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            const highlights = this.querySelectorAll('.highlight-tag');
            highlights.forEach((tag, index) => {
                setTimeout(() => {
                    tag.style.transform = 'scale(1.05)';
                    tag.style.transition = 'transform 0.2s ease';
                }, index * 50);
            });
        });
        
        card.addEventListener('mouseleave', function() {
            const highlights = this.querySelectorAll('.highlight-tag');
            highlights.forEach(tag => {
                tag.style.transform = 'scale(1)';
            });
        });
    });
}

// Initialize additional features when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeParallax();
    initializeTourCards();
    
    // Add loading animation to images
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
            this.style.transition = 'opacity 0.3s ease';
        });
        
        // Set initial opacity
        img.style.opacity = '0';
    });
});

// Scroll indicator functionality
function initializeScrollIndicator() {
    const scrollIndicator = document.querySelector('.scroll-indicator');
    
    if (scrollIndicator) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            if (scrolled > 100) {
                scrollIndicator.style.opacity = '0';
            } else {
                scrollIndicator.style.opacity = '1';
            }
        });
        
        scrollIndicator.addEventListener('click', () => {
            const introSection = document.querySelector('.intro-section');
            if (introSection) {
                introSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
}

// Initialize scroll indicator
document.addEventListener('DOMContentLoaded', function() {
    initializeScrollIndicator();
});
