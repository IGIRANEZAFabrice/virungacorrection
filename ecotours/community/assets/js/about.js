/**
 * About Page JavaScript
 * Interactive Map Functionality for Virunga Ecotours Community About Page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize interactive map functionality
    initializeInteractiveMap();
    
    // Initialize Mermaid diagrams
    initializeMermaidDiagrams();
    
    // Initialize scroll animations
    initializeScrollAnimations();
    
    // Initialize enhanced tooltips
    initializeEnhancedTooltips();
});

/**
 * Initialize Interactive Map Tab Functionality
 */
function initializeInteractiveMap() {
    const mapTabs = document.querySelectorAll('.map-tab');
    const mapContents = document.querySelectorAll('.map-content');
    
    if (mapTabs.length === 0) return;
    
    mapTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetMap = this.getAttribute('data-map');
            
            // Remove active class from all tabs and contents
            mapTabs.forEach(t => t.classList.remove('active'));
            mapContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding map content
            const targetContent = document.getElementById(targetMap + '-map');
            if (targetContent) {
                targetContent.classList.add('active');
                
                // Reinitialize Mermaid for the active content
                const mermaidElement = targetContent.querySelector('.mermaid');
                if (mermaidElement && window.mermaid) {
                    // Clear previous render
                    mermaidElement.removeAttribute('data-processed');
                    mermaidElement.classList.remove('mermaid-processed');

                    // Re-render Mermaid diagram
                    setTimeout(() => {
                        try {
                            window.mermaid.init(undefined, mermaidElement);
                        } catch (error) {
                            console.error('Error re-rendering Mermaid diagram:', error);
                            // Try alternative rendering method
                            const diagramText = mermaidElement.textContent;
                            mermaid.render(`mermaid-tab-${Date.now()}`, diagramText, (svgCode) => {
                                mermaidElement.innerHTML = svgCode;
                            });
                        }
                    }, 100);
                }
            }
            
            // Add smooth transition effect
            mapContents.forEach(content => {
                if (content.classList.contains('active')) {
                    content.style.opacity = '0';
                    content.style.transform = 'translateY(20px)';
                    
                    setTimeout(() => {
                        content.style.transition = 'all 0.5s ease';
                        content.style.opacity = '1';
                        content.style.transform = 'translateY(0)';
                    }, 50);
                }
            });
        });
    });
}

/**
 * Initialize Mermaid Diagrams
 */
function initializeMermaidDiagrams() {
    // Check if Mermaid is available
    if (typeof mermaid === 'undefined') {
        // Load Mermaid dynamically if not available
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/mermaid@10.6.1/dist/mermaid.min.js';
        script.onload = function() {
            configureMermaid();
        };
        document.head.appendChild(script);
    } else {
        configureMermaid();
    }
}

/**
 * Configure Mermaid Settings
 */
function configureMermaid() {
    if (window.mermaid) {
        mermaid.initialize({
            startOnLoad: true,
            theme: 'default',
            themeVariables: {
                primaryColor: '#2a4858',
                primaryTextColor: '#ffffff',
                primaryBorderColor: '#2a4858',
                lineColor: '#8b7355',
                secondaryColor: '#f2e8dc',
                tertiaryColor: '#d8c3a5',
                background: '#f6f4f0',
                mainBkg: '#ffffff',
                secondBkg: '#f2e8dc',
                tertiaryBkg: '#d8c3a5'
            },
            flowchart: {
                useMaxWidth: true,
                htmlLabels: true,
                curve: 'basis'
            },
            securityLevel: 'loose',
            maxTextSize: 90000
        });

        // Initialize all mermaid diagrams
        try {
            mermaid.init(undefined, document.querySelectorAll('.mermaid'));
        } catch (error) {
            console.error('Mermaid initialization error:', error);
            // Fallback: try to render each diagram individually
            document.querySelectorAll('.mermaid').forEach((element, index) => {
                try {
                    const diagramText = element.textContent.trim();
                    if (diagramText) {
                        mermaid.render(`mermaid-${index}`, diagramText, (svgCode) => {
                            element.innerHTML = svgCode;
                        });
                    }
                } catch (e) {
                    console.error(`Error rendering diagram ${index}:`, e);
                    element.innerHTML = `
                        <div class="mermaid-error">
                            <i class="fas fa-exclamation-triangle"></i>
                            Unable to render interactive map. Please refresh the page or try a different browser.
                        </div>
                    `;
                }
            });
        }
    }
}

/**
 * Initialize Scroll Animations
 */
function initializeScrollAnimations() {
    // Create intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animateElements = document.querySelectorAll(
        '.stat-card, .timeline-item, .approach-item, .team-member, .marker'
    );
    
    animateElements.forEach(el => {
        observer.observe(el);
    });
}

/**
 * Initialize Enhanced Tooltips
 */
function initializeEnhancedTooltips() {
    const markers = document.querySelectorAll('.marker');
    
    markers.forEach(marker => {
        const tooltip = marker.querySelector('.marker-tooltip');
        if (!tooltip) return;
        
        marker.addEventListener('mouseenter', function() {
            // Position tooltip to avoid viewport edges
            const rect = marker.getBoundingClientRect();
            const tooltipRect = tooltip.getBoundingClientRect();
            
            // Adjust tooltip position if it goes off screen
            if (rect.left + tooltipRect.width > window.innerWidth) {
                tooltip.style.left = 'auto';
                tooltip.style.right = '0';
                tooltip.style.transform = 'translateX(0)';
            }
            
            if (rect.top - tooltipRect.height < 0) {
                tooltip.style.bottom = 'auto';
                tooltip.style.top = '120%';
            }
            
            // Add entrance animation
            tooltip.style.animation = 'tooltipFadeIn 0.3s ease';
        });
        
        marker.addEventListener('mouseleave', function() {
            // Reset tooltip position
            tooltip.style.left = '50%';
            tooltip.style.right = 'auto';
            tooltip.style.transform = 'translateX(-50%)';
            tooltip.style.bottom = '120%';
            tooltip.style.top = 'auto';
        });
    });
}

/**
 * Statistics Counter Animation
 */
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    
    counters.forEach(counter => {
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
    });
}

/**
 * Map Tab Keyboard Navigation
 */
function initializeKeyboardNavigation() {
    const mapTabs = document.querySelectorAll('.map-tab');
    
    mapTabs.forEach((tab, index) => {
        tab.setAttribute('tabindex', '0');
        tab.setAttribute('role', 'tab');
        
        tab.addEventListener('keydown', function(e) {
            let targetIndex;
            
            switch(e.key) {
                case 'ArrowLeft':
                    targetIndex = index > 0 ? index - 1 : mapTabs.length - 1;
                    break;
                case 'ArrowRight':
                    targetIndex = index < mapTabs.length - 1 ? index + 1 : 0;
                    break;
                case 'Enter':
                case ' ':
                    e.preventDefault();
                    tab.click();
                    return;
                default:
                    return;
            }
            
            if (targetIndex !== undefined) {
                e.preventDefault();
                mapTabs[targetIndex].focus();
                mapTabs[targetIndex].click();
            }
        });
    });
}

/**
 * Responsive Map Handling
 */
function handleResponsiveMap() {
    const mapContainer = document.querySelector('.interactive-map-container');
    if (!mapContainer) return;
    
    function adjustMapForMobile() {
        const isMobile = window.innerWidth <= 768;
        const mermaidContainers = document.querySelectorAll('.mermaid-container');
        
        mermaidContainers.forEach(container => {
            if (isMobile) {
                container.style.overflowX = 'auto';
                container.style.padding = '0.5rem';
            } else {
                container.style.overflowX = 'visible';
                container.style.padding = '1rem';
            }
        });
    }
    
    // Initial adjustment
    adjustMapForMobile();
    
    // Adjust on window resize
    window.addEventListener('resize', adjustMapForMobile);
}

/**
 * Initialize all functionality when page loads
 */
function initializeAboutPage() {
    initializeInteractiveMap();
    initializeMermaidDiagrams();
    initializeScrollAnimations();
    initializeEnhancedTooltips();
    initializeKeyboardNavigation();
    handleResponsiveMap();
    
    // Animate counters when they come into view
    const statsSection = document.querySelector('.regional-stats');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        statsObserver.observe(statsSection);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAboutPage);
} else {
    initializeAboutPage();
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes tooltipFadeIn {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.6s ease forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
