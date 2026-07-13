// Community Header & Footer JavaScript
// Handles navigation, mobile menu, and interactive elements

document.addEventListener('DOMContentLoaded', function() {
    initializeHeader();
    initializeFooter();
    initializeMobileNavigation();
    initializeScrollEffects();
    initializeAccessibility();
});

// Header Initialization
function initializeHeader() {
    const header = document.getElementById('communityHeader');
    
    // Sticky header effect
    let lastScrollTop = 0;
    const headerHeight = header.offsetHeight;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add/remove scrolled class for styling
        if (scrollTop > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Hide/show header on scroll (optional)
        if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
            header.classList.add('header-hidden');
        } else {
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Dropdown menu interactions
    initializeDropdowns();
    
    // Search functionality (if search exists)
    initializeHeaderSearch();
}

// Dropdown Menu Functionality
function initializeDropdowns() {
    const dropdownItems = document.querySelectorAll('.nav-item.dropdown');
    
    dropdownItems.forEach(item => {
        const dropdownMenu = item.querySelector('.dropdown-menu');
        let timeoutId;
        
        // Mouse enter
        item.addEventListener('mouseenter', function() {
            clearTimeout(timeoutId);
            dropdownMenu.style.display = 'block';
            setTimeout(() => {
                dropdownMenu.classList.add('active');
            }, 10);
        });
        
        // Mouse leave
        item.addEventListener('mouseleave', function() {
            dropdownMenu.classList.remove('active');
            timeoutId = setTimeout(() => {
                dropdownMenu.style.display = 'none';
            }, 300);
        });
        
        // Keyboard navigation
        const dropdownLink = item.querySelector('.nav-link');
        dropdownLink.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                dropdownMenu.classList.toggle('active');
            }
            
            if (e.key === 'Escape') {
                dropdownMenu.classList.remove('active');
                dropdownLink.focus();
            }
        });
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.nav-item.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
                setTimeout(() => {
                    if (!menu.classList.contains('active')) {
                        menu.style.display = 'none';
                    }
                }, 300);
            });
        }
    });
}

// Header Search Functionality
function initializeHeaderSearch() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchForm = document.querySelector('.header-search-form');
    const searchInput = document.querySelector('.header-search-input');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle && searchForm) {
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchForm.classList.add('active');
            searchInput.focus();
        });
        
        if (searchClose) {
            searchClose.addEventListener('click', function() {
                searchForm.classList.remove('active');
            });
        }
        
        // Close search on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchForm.classList.contains('active')) {
                searchForm.classList.remove('active');
            }
        });
    }
}

// Mobile Navigation
function initializeMobileNavigation() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNavMenu = document.getElementById('mobileNavMenu');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    const mobileNavClose = document.getElementById('mobileNavClose');
    
    // Open mobile menu
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            openMobileMenu();
        });
    }
    
    // Close mobile menu
    if (mobileNavClose) {
        mobileNavClose.addEventListener('click', function() {
            closeMobileMenu();
        });
    }
    
    // Close on overlay click
    if (mobileNavOverlay) {
        mobileNavOverlay.addEventListener('click', function() {
            closeMobileMenu();
        });
    }
    
    // Handle submenu toggles
    const submenuToggles = document.querySelectorAll('.mobile-nav-item.has-submenu .mobile-nav-link');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentItem = this.closest('.mobile-nav-item');
            const isActive = parentItem.classList.contains('active');
            
            // Close all other submenus
            document.querySelectorAll('.mobile-nav-item.has-submenu').forEach(item => {
                if (item !== parentItem) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle current submenu
            parentItem.classList.toggle('active', !isActive);
        });
    });
    
    // Close mobile menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNavMenu && mobileNavMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });
    
    // Close mobile menu on window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            closeMobileMenu();
        }
    });
}

function openMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNavMenu = document.getElementById('mobileNavMenu');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    
    mobileMenuToggle.classList.add('active');
    mobileNavMenu.classList.add('active');
    mobileNavOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Focus management
    const firstFocusableElement = mobileNavMenu.querySelector('a, button');
    if (firstFocusableElement) {
        firstFocusableElement.focus();
    }
}

function closeMobileMenu() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNavMenu = document.getElementById('mobileNavMenu');
    const mobileNavOverlay = document.getElementById('mobileNavOverlay');
    
    mobileMenuToggle.classList.remove('active');
    mobileNavMenu.classList.remove('active');
    mobileNavOverlay.classList.remove('active');
    document.body.style.overflow = '';
    
    // Close all submenus
    document.querySelectorAll('.mobile-nav-item.has-submenu').forEach(item => {
        item.classList.remove('active');
    });
    
    // Return focus to toggle button
    mobileMenuToggle.focus();
}

// Footer Initialization
function initializeFooter() {
    // Newsletter form handling is in the footer HTML
    
    // Social media link tracking
    const socialLinks = document.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('click', function() {
            const platform = this.classList.contains('facebook') ? 'Facebook' :
                           this.classList.contains('instagram') ? 'Instagram' :
                           this.classList.contains('linkedin') ? 'LinkedIn' :
                           this.classList.contains('youtube') ? 'YouTube' :
                           this.classList.contains('whatsapp') ? 'WhatsApp' : 'Unknown';
            
            // Track social media clicks (replace with your analytics)
            if (typeof gtag !== 'undefined') {
                gtag('event', 'social_click', {
                    'platform': platform,
                    'page_location': window.location.href
                });
            }
        });
    });
    
    // Animate footer stats on scroll
    animateFooterStats();
}

// Animate Footer Statistics
function animateFooterStats() {
    const statNumbers = document.querySelectorAll('.footer-stats .stat-number');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                entry.target.classList.add('animated');
                animateNumber(entry.target);
            }
        });
    }, { threshold: 0.5 });
    
    statNumbers.forEach(stat => {
        observer.observe(stat);
    });
}

function animateNumber(element) {
    const finalNumber = element.textContent.replace(/[^\d]/g, '');
    const duration = 2000;
    const increment = finalNumber / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= finalNumber) {
            current = finalNumber;
            clearInterval(timer);
        }
        
        // Format number with + suffix if original had it
        const formattedNumber = Math.floor(current).toLocaleString();
        element.textContent = element.textContent.includes('+') ? 
            formattedNumber + '+' : formattedNumber;
    }, 16);
}

// Scroll Effects
function initializeScrollEffects() {
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerHeight = document.getElementById('communityHeader').offsetHeight;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Parallax effect for newsletter section (optional)
    const newsletterSection = document.querySelector('.newsletter-section');
    if (newsletterSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = scrolled * 0.5;
            newsletterSection.style.transform = `translateY(${parallax}px)`;
        });
    }
}

// Accessibility Enhancements
function initializeAccessibility() {
    // Skip to content functionality
    const skipLink = document.querySelector('.skip-to-content');
    if (skipLink) {
        skipLink.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.focus();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }
    
    // Keyboard navigation for dropdowns
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            // Handle tab navigation through dropdowns
            const activeDropdown = document.querySelector('.dropdown-menu.active');
            if (activeDropdown) {
                const focusableElements = activeDropdown.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])');
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];
                
                if (e.shiftKey && document.activeElement === firstElement) {
                    e.preventDefault();
                    lastElement.focus();
                } else if (!e.shiftKey && document.activeElement === lastElement) {
                    e.preventDefault();
                    firstElement.focus();
                }
            }
        }
    });
    
    // Announce page changes for screen readers
    announcePageChange();
}

function announcePageChange() {
    const pageTitle = document.title;
    const announcement = document.createElement('div');
    announcement.setAttribute('aria-live', 'polite');
    announcement.setAttribute('aria-atomic', 'true');
    announcement.className = 'sr-only';
    announcement.textContent = `Page loaded: ${pageTitle}`;
    document.body.appendChild(announcement);
    
    setTimeout(() => {
        document.body.removeChild(announcement);
    }, 1000);
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

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Performance optimized scroll handler
const optimizedScrollHandler = throttle(function() {
    // Handle scroll-based animations and effects
    const scrollTop = window.pageYOffset;
    
    // Update header state
    const header = document.getElementById('communityHeader');
    if (header) {
        if (scrollTop > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
    
    // Update back to top button
    const backToTopBtn = document.getElementById('backToTop');
    if (backToTopBtn) {
        if (scrollTop > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    }
}, 16);

window.addEventListener('scroll', optimizedScrollHandler);

// Export functions for use in other scripts
window.CommunityHeaderFooter = {
    openMobileMenu,
    closeMobileMenu,
    debounce,
    throttle
};
