// Birdwatching Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize birdwatching page functionality
    initializeBirdwatchingPage();
});

function initializeBirdwatchingPage() {
    // Initialize scroll animations
    initializeScrollAnimations();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
    
    // Initialize hero animations
    initializeHeroAnimations();
    
    // Initialize hotspot card interactions
    initializeHotspotCards();
    
    // Initialize scroll indicator
    initializeScrollIndicator();
}

// Hero Animations
function initializeHeroAnimations() {
    const heroStats = document.querySelectorAll('.stat-number');
    
    // Animate stats on load
    setTimeout(() => {
        heroStats.forEach((stat, index) => {
            const finalValue = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
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
            
            // Format numbers with + for 700+
            if (end >= 700) {
                element.textContent = Math.floor(current) + '+';
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }, delay);
}

// Hotspot Cards Interactions
function initializeHotspotCards() {
    const hotspotCards = document.querySelectorAll('.hotspot-card');
    
    hotspotCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Add subtle animation to species tags
            const tags = this.querySelectorAll('.species-tag');
            tags.forEach((tag, index) => {
                setTimeout(() => {
                    tag.style.transform = 'scale(1.05)';
                    tag.style.transition = 'transform 0.3s ease';
                }, index * 50);
            });
            
            // Animate features
            const features = this.querySelectorAll('.hotspot-features .feature');
            features.forEach((feature, index) => {
                setTimeout(() => {
                    feature.style.transform = 'translateY(-3px)';
                    feature.style.transition = 'transform 0.3s ease';
                }, index * 100);
            });
        });
        
        card.addEventListener('mouseleave', function() {
            const tags = this.querySelectorAll('.species-tag');
            tags.forEach(tag => {
                tag.style.transform = 'scale(1)';
            });
            
            const features = this.querySelectorAll('.hotspot-features .feature');
            features.forEach(feature => {
                feature.style.transform = 'translateY(0)';
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
                    if (entry.target.classList.contains('hotspot-card')) {
                        animateHotspotCard(entry.target);
                    } else if (entry.target.classList.contains('stat-card')) {
                        animateStatCard(entry.target);
                    } else if (entry.target.classList.contains('species-counter')) {
                        animateSpeciesCounter(entry.target);
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
            .intro-text, .intro-image, .nyandungu-text, .nyandungu-image,
            .hotspot-card, .tour-card, .stat-card, .benefit-item, 
            .section-header, .species-counter
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

function animateHotspotCard(card) {
    const badge = card.querySelector('.hotspot-badge');
    const speciesCount = card.querySelector('.species-count');
    
    if (badge) {
        setTimeout(() => {
            badge.style.transform = 'scale(1.1)';
            badge.style.transition = 'transform 0.5s ease';
            
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 500);
        }, 200);
    }
    
    if (speciesCount) {
        setTimeout(() => {
            speciesCount.style.transform = 'scale(1.1)';
            speciesCount.style.transition = 'transform 0.5s ease';
            
            setTimeout(() => {
                speciesCount.style.transform = 'scale(1)';
            }, 500);
        }, 400);
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

function animateSpeciesCounter(counter) {
    const number = counter.querySelector('.counter-number');
    if (number) {
        const finalValue = parseInt(number.textContent.replace(/[^0-9]/g, ''));
        setTimeout(() => {
            animateCounter(number, 0, finalValue, 1500, 0);
        }, 300);
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

// Parallax Effect for Hero Section
function initializeParallax() {
    const hero = document.querySelector('.hero-section');
    
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.3;
            
            hero.style.transform = `translateY(${rate}px)`;
        });
    }
}

// Species Counter Animation
function initializeSpeciesCounters() {
    const counters = document.querySelectorAll('.species-counter');
    
    counters.forEach(counter => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const numberElement = entry.target.querySelector('.counter-number');
                    if (numberElement) {
                        const finalValue = parseInt(numberElement.textContent.replace(/[^0-9]/g, ''));
                        animateCounter(numberElement, 0, finalValue, 2000, 0);
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(counter);
    });
}

// Initialize additional features when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeParallax();
    initializeTourCards();
    initializeSpeciesCounters();
    
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
    
    // Add bird sound effects (optional)
    addBirdSoundEffects();
});

// Optional: Add subtle bird sound effects
function addBirdSoundEffects() {
    const hotspotCards = document.querySelectorAll('.hotspot-card');
    
    hotspotCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // You can add subtle bird chirp sounds here
            // playBirdSound();
        });
    });
}

// Utility function for bird sounds (placeholder)
function playBirdSound() {
    // Implementation for playing bird sounds
    // This would require audio files and proper audio handling
    console.log('Bird sound effect triggered');
}
