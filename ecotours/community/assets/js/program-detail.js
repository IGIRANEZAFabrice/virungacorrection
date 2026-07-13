// Program Detail Page JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initializeGallery();
    initializeShareButtons();
    initializeSmoothScrolling();
    initializeAnimations();
    initializeBackToTop();
});

// Gallery Modal Functionality
function initializeGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const modal = document.getElementById('galleryModal');
    const modalImage = document.getElementById('galleryModalImage');
    const modalTitle = document.getElementById('galleryModalTitle');
    const modalCounter = document.getElementById('galleryModalCounter');
    const closeBtn = document.querySelector('.gallery-modal-close');
    const prevBtn = document.getElementById('galleryPrev');
    const nextBtn = document.getElementById('galleryNext');
    
    let currentImageIndex = 0;
    let galleryImages = [];
    
    // Collect all gallery images
    galleryItems.forEach((item, index) => {
        const img = item.querySelector('img');
        galleryImages.push({
            src: img.src,
            alt: img.alt,
            index: index
        });
        
        // Add click event to open modal
        item.addEventListener('click', function() {
            currentImageIndex = index;
            openGalleryModal();
        });
    });
    
    function openGalleryModal() {
        if (galleryImages.length === 0) return;
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        updateModalImage();
        
        // Add fade-in animation
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }
    
    function closeGalleryModal() {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    function updateModalImage() {
        if (galleryImages.length === 0) return;
        
        const currentImage = galleryImages[currentImageIndex];
        modalImage.src = currentImage.src;
        modalImage.alt = currentImage.alt;
        modalCounter.textContent = `${currentImageIndex + 1} of ${galleryImages.length}`;
        
        // Update navigation button states
        prevBtn.style.display = currentImageIndex > 0 ? 'block' : 'none';
        nextBtn.style.display = currentImageIndex < galleryImages.length - 1 ? 'block' : 'none';
    }
    
    function showPreviousImage() {
        if (currentImageIndex > 0) {
            currentImageIndex--;
            updateModalImage();
        }
    }
    
    function showNextImage() {
        if (currentImageIndex < galleryImages.length - 1) {
            currentImageIndex++;
            updateModalImage();
        }
    }
    
    // Event listeners
    if (closeBtn) {
        closeBtn.addEventListener('click', closeGalleryModal);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', showPreviousImage);
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', showNextImage);
    }
    
    // Close modal when clicking outside the image
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeGalleryModal();
        }
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.style.display === 'block') {
            switch(e.key) {
                case 'Escape':
                    closeGalleryModal();
                    break;
                case 'ArrowLeft':
                    showPreviousImage();
                    break;
                case 'ArrowRight':
                    showNextImage();
                    break;
            }
        }
    });
}

// Share Buttons Functionality
function initializeShareButtons() {
    const shareButtons = document.querySelectorAll('.share-btn');
    
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const url = this.href;
            
            // For social media links, open in popup
            if (url.includes('facebook.com') || url.includes('twitter.com') || url.includes('linkedin.com')) {
                e.preventDefault();
                openSharePopup(url);
            }
        });
    });
    
    function openSharePopup(url) {
        const popup = window.open(
            url,
            'share',
            'width=600,height=400,scrollbars=yes,resizable=yes'
        );
        
        // Focus the popup window
        if (popup) {
            popup.focus();
        }
    }
}

// Smooth Scrolling for Anchor Links
function initializeSmoothScrolling() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Scroll Animations
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements for animation
    const animatedElements = document.querySelectorAll(
        '.content-block, .sidebar-block, .testimonial-card, .related-program-card'
    );
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Back to Top Button
function initializeBackToTop() {
    // Create back to top button
    const backToTopBtn = document.createElement('button');
    backToTopBtn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    backToTopBtn.className = 'back-to-top';
    backToTopBtn.setAttribute('aria-label', 'Back to top');
    document.body.appendChild(backToTopBtn);
    
    // Show/hide button based on scroll position
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    });
    
    // Scroll to top when clicked
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// Copy to Clipboard Functionality
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showNotification('Link copied to clipboard!');
        }).catch(function() {
            fallbackCopyToClipboard(text);
        });
    } else {
        fallbackCopyToClipboard(text);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        showNotification('Link copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy text: ', err);
        showNotification('Failed to copy link');
    }
    
    document.body.removeChild(textArea);
}

// Notification System
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Lazy Loading for Images
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            imageObserver.observe(img);
        });
    }
}

// Error Handling for Images
function handleImageErrors() {
    const images = document.querySelectorAll('img');
    
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.src = 'assets/images/placeholder.jpg'; // Fallback image
            this.alt = 'Image not available';
        });
    });
}

// Initialize additional features
document.addEventListener('DOMContentLoaded', function() {
    initializeLazyLoading();
    handleImageErrors();
});
