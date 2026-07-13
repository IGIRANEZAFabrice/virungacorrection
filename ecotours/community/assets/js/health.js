/**
 * Health Tourism Page JavaScript
 * Handles loading and displaying healthcare programs
 */

// Load healthcare programs from database
async function loadHealthcarePrograms() {
    const programsGrid = document.getElementById('programs-grid');
    const loadingState = document.getElementById('loading-state');
    const noPrograms = document.getElementById('no-programs');
    
    try {
        // Show loading state
        loadingState.style.display = 'block';
        programsGrid.style.display = 'none';
        noPrograms.style.display = 'none';
        
        // Fetch healthcare programs
        const response = await fetch('api/get-healthcare-programs.php');
        const data = await response.json();
        
        // Hide loading state
        loadingState.style.display = 'none';
        
        if (data.success && data.programs && data.programs.length > 0) {
            // Display programs
            programsGrid.innerHTML = '';
            data.programs.forEach(program => {
                programsGrid.appendChild(createProgramCard(program));
            });
            programsGrid.style.display = 'grid';
            
            // Update programs count in header
            const programsCount = document.querySelector('.stat-number');
            if (programsCount) {
                programsCount.textContent = data.programs.length;
            }
        } else {
            // Show no programs message
            noPrograms.style.display = 'block';
        }
        
    } catch (error) {
        console.error('Error loading healthcare programs:', error);
        loadingState.style.display = 'none';
        noPrograms.style.display = 'block';
    }
}

// Create program card element
function createProgramCard(program) {
    const card = document.createElement('div');
    card.className = 'program-card';
    card.setAttribute('data-category', program.category);
    
    // Format dates
    const startDate = new Date(program.start_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    const endDate = program.end_date ? new Date(program.end_date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    }) : 'Ongoing';
    
    // Determine status color and icon
    const statusConfig = {
        'active': { color: '#28a745', icon: 'fa-play-circle' },
        'completed': { color: '#6c757d', icon: 'fa-check-circle' },
        'upcoming': { color: '#ffc107', icon: 'fa-clock' }
    };
    
    const status = statusConfig[program.status] || statusConfig['active'];
    
    card.innerHTML = `
        <div class="program-image">
            <img src="assets/images/programs/${program.image}" 
                 alt="${escapeHtml(program.title)}" 
                 loading="lazy"
                 onerror="this.src='assets/images/programs/default-healthcare.jpg'">
            <div class="program-overlay">
                <div class="program-badges">
                    <span class="program-country">
                        <i class="fas fa-map-marker-alt"></i>
                        ${capitalizeFirst(program.country)}
                    </span>
                    ${program.featured ? '<span class="program-featured"><i class="fas fa-star"></i> Featured</span>' : ''}
                </div>
                <div class="program-status" style="background-color: ${status.color}">
                    <i class="fas ${status.icon}"></i>
                    ${capitalizeFirst(program.status)}
                </div>
            </div>
        </div>
        
        <div class="program-content">
            <div class="program-header">
                <h3 class="program-title">${escapeHtml(program.title)}</h3>
                <div class="program-category">
                    <i class="fas fa-stethoscope"></i>
                    ${escapeHtml(program.category)}
                </div>
            </div>
            
            <p class="program-description">${escapeHtml(program.short_description)}</p>
            
            <div class="program-details">
                <div class="program-detail">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Duration: ${startDate} - ${endDate}</span>
                </div>
                
                ${program.beneficiaries ? `
                <div class="program-detail">
                    <i class="fas fa-users"></i>
                    <span>${program.beneficiaries} Beneficiaries</span>
                </div>
                ` : ''}
                
                ${program.budget ? `
                <div class="program-detail">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Budget: $${formatNumber(program.budget)}</span>
                </div>
                ` : ''}
            </div>
            
            <div class="program-impact">
                <h4>Health Focus:</h4>
                <div class="impact-tags">
                    <span class="impact-tag">
                        <i class="fas fa-heartbeat"></i>
                        Community Health
                    </span>
                    <span class="impact-tag">
                        <i class="fas fa-spa"></i>
                        Wellness Tourism
                    </span>
                    <span class="impact-tag">
                        <i class="fas fa-hands-helping"></i>
                        Health Support
                    </span>
                </div>
            </div>
            
            <div class="program-actions">
                <a href="program-detail.php?id=${program.id}" class="btn btn-primary">
                    <i class="fas fa-eye"></i>
                    Learn More
                </a>
                <a href="../pages/contactus.php?program=${encodeURIComponent(program.title)}" class="btn btn-secondary">
                    <i class="fas fa-stethoscope"></i>
                    Join Program
                </a>
            </div>
        </div>
    `;
    
    return card;
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

// Smooth scroll for internal links
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to all internal links
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
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
    
    // Animate stats on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateStats(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe stat elements
    const statElements = document.querySelectorAll('.stat-number, .cta-stat .stat-number');
    statElements.forEach(el => observer.observe(el));
    
    // Animate dimension cards on scroll
    const dimensionCards = document.querySelectorAll('.dimension-card');
    dimensionCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'all 0.6s ease';
        
        observer.observe(card);
        card.addEventListener('animationend', () => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    });
});

// Animate statistics numbers
function animateStats(element) {
    const finalValue = element.textContent.replace(/[^\d]/g, '');
    if (!finalValue) return;
    
    const duration = 2000;
    const increment = Math.ceil(finalValue / (duration / 16));
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= finalValue) {
            current = finalValue;
            clearInterval(timer);
        }
        
        // Preserve any non-numeric characters
        const originalText = element.textContent;
        const suffix = originalText.replace(/^\d+/, '');
        element.textContent = current + suffix;
    }, 16);
}

// Add loading animation for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    images.forEach(img => {
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.addEventListener('error', function() {
            this.style.opacity = '0.5';
            console.warn('Failed to load image:', this.src);
        });
    });
    
    // Add intersection observer for dimension cards animation
    const dimensionObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }, index * 200);
                dimensionObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    
    const dimensionCards = document.querySelectorAll('.dimension-card');
    dimensionCards.forEach(card => {
        dimensionObserver.observe(card);
    });
    
    // Add hover effects for table rows
    const tableRows = document.querySelectorAll('.integration-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.02)';
            this.style.transition = 'all 0.3s ease';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});

// Export functions for external use
window.Health = {
    loadHealthcarePrograms,
    createProgramCard
};
