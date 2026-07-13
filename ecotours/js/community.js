        // Simple JavaScript for scroll animation
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation class to elements when they come into view
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.program-card, .welcome-title, .testimonial-title, .features-title');
                
                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const screenPosition = window.innerHeight / 1.3;
                    
                    if(elementPosition < screenPosition) {
                        element.style.opacity = '1';
                        element.style.transform = 'translateY(0)';
                    }
                });
            };
            
            // Initial styles for animation
            const elementsToAnimate = document.querySelectorAll('.program-card, .welcome-title, .testimonial-title, .features-title');
            elementsToAnimate.forEach(element => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
            
            // Run once on load
            animateOnScroll();
            
            // Run on scroll
            window.addEventListener('scroll', animateOnScroll)
        });