// Gorilla Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize gorilla page functionality
    initializeGorillaPage();
});

function initializeGorillaPage() {
    // Initialize country tabs
    initializeCountryTabs();
    
    // Initialize load more functionality
    initializeLoadMore();
    
    // Initialize scroll animations
    initializeScrollAnimations();
    
    // Initialize smooth scrolling
    initializeSmoothScrolling();
}

// Country Tabs Functionality
function initializeCountryTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const countryContents = document.querySelectorAll('.country-content');
    
    if (!tabButtons.length || !countryContents.length) return;
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetCountry = this.dataset.country;
            
            // Update active button
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding content with animation
            showCountryContent(targetCountry, countryContents);
        });
    });
}

function showCountryContent(targetCountry, contents) {
    // Hide all contents first
    contents.forEach(content => {
        content.classList.remove('active');
        content.style.opacity = '0';
        content.style.transform = 'translateY(20px)';
    });
    
    // Show target content with animation
    setTimeout(() => {
        const targetContent = document.getElementById(targetCountry);
        if (targetContent) {
            targetContent.classList.add('active');
            targetContent.style.opacity = '1';
            targetContent.style.transform = 'translateY(0)';
        }
    }, 150);
}

// Load More Tours Functionality
function initializeLoadMore() {
    const loadMoreBtn = document.getElementById('loadMoreTours');
    if (!loadMoreBtn) return;
    
    loadMoreBtn.addEventListener('click', function() {
        loadMoreTours();
    });
}

function loadMoreTours() {
    const loadMoreBtn = document.getElementById('loadMoreTours');
    const hiddenTours = document.querySelectorAll('.tour-card.hidden');
    
    if (hiddenTours.length === 0) {
        loadMoreBtn.style.display = 'none';
        return;
    }
    
    // Show loading state
    loadMoreBtn.classList.add('loading');
    loadMoreBtn.innerHTML = '<i class="fas fa-spinner"></i> Loading...';
    
    // Simulate loading delay for better UX
    setTimeout(() => {
        let toursToShow = 6; // Show 6 more tours
        let shownCount = 0;
        
        hiddenTours.forEach((tour, index) => {
            if (shownCount < toursToShow) {
                tour.classList.remove('hidden');
                
                // Add animation
                setTimeout(() => {
                    tour.style.opacity = '0';
                    tour.style.transform = 'translateY(20px)';
                    tour.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    
                    setTimeout(() => {
                        tour.style.opacity = '1';
                        tour.style.transform = 'translateY(0)';
                    }, 50);
                }, index * 100);
                
                shownCount++;
            }
        });
        
        // Update button state
        loadMoreBtn.classList.remove('loading');
        
        const remainingHidden = document.querySelectorAll('.tour-card.hidden');
        if (remainingHidden.length === 0) {
            loadMoreBtn.innerHTML = '<i class="fas fa-check"></i> All Tours Loaded';
            setTimeout(() => {
                loadMoreBtn.style.display = 'none';
            }, 2000);
        } else {
            loadMoreBtn.innerHTML = `<i class="fas fa-plus"></i> Load More Tours (${remainingHidden.length} remaining)`;
        }
    }, 800);
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
                    const element = entry.target;
                    
                    // Add animation class based on element type
                    if (element.classList.contains('timeline-item')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateX(0)';
                    } else if (element.classList.contains('family-card')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    } else if (element.classList.contains('habitat-card')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    } else if (element.classList.contains('benefit-item')) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateX(0)';
                    } else {
                        element.classList.add('animate-in');
                    }
                    
                    observer.unobserve(element);
                }
            });
        };

        const observer = new IntersectionObserver(handleIntersect, observerOptions);

        // Observe elements for animation
        const elementsToObserve = document.querySelectorAll('.timeline-item, .family-card, .habitat-card, .benefit-item, .intro-content, .section-header, .stat-item');
        elementsToObserve.forEach(element => {
            // Set initial state for animated elements
            if (element.classList.contains('timeline-item')) {
                element.style.opacity = '0';
                element.style.transform = 'translateX(-30px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            } else if (element.classList.contains('family-card') || element.classList.contains('habitat-card')) {
                element.style.opacity = '0';
                element.style.transform = 'translateY(30px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            } else if (element.classList.contains('benefit-item')) {
                element.style.opacity = '0';
                element.style.transform = 'translateX(-20px)';
                element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            }
            
            observer.observe(element);
        });
    }
}

// Smooth Scrolling
function initializeSmoothScrolling() {
    // Smooth scroll for internal links
    document.addEventListener('click', function(e) {
        if (e.target.matches('a[href^="#"]')) {
            e.preventDefault();
            const targetId = e.target.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
}

// Counter Animation for Stats
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
        const increment = target / 100;
        let current = 0;
        
        const updateCounter = () => {
            if (current < target) {
                current += increment;
                counter.textContent = Math.ceil(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString();
            }
        };
        
        updateCounter();
    });
}

// Initialize counter animation when stats come into view
function initializeCounterAnimation() {
    const statsSection = document.querySelector('.hero-stats');
    if (!statsSection) return;
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(statsSection);
}

// Enhanced Family Card Interactions
function enhanceFamilyCards() {
    const familyCards = document.querySelectorAll('.family-card');
    
    familyCards.forEach(card => {
        // Add click handler for potential expansion
        card.addEventListener('click', function(e) {
            // Prevent default if clicking on interactive elements
            if (e.target.matches('a, button')) return;
            
            // Add subtle click feedback
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
        
        // Add keyboard navigation support
        card.setAttribute('tabindex', '0');
        card.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Handle window resize
window.addEventListener('resize', debounce(function() {
    // Recalculate any responsive elements if needed
    const activeContent = document.querySelector('.country-content.active');
    if (activeContent) {
        activeContent.style.opacity = '1';
        activeContent.style.transform = 'translateY(0)';
    }
}, 250));

// Initialize enhanced interactions when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    enhanceFamilyCards();
    initializeCounterAnimation();
});

// Export functions for potential external use
window.GorillaPage = {
    showCountryContent,
    initializeCountryTabs,
    animateCounters
};
