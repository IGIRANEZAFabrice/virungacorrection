// Admin Panel JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initializeDashboard();
    initializeAnimations();
    initializeTooltips();
    initializeConfirmDialogs();
    initializeAutoRefresh();
});

// Dashboard Initialization
function initializeDashboard() {
    // Add loading states to action cards
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!this.classList.contains('loading')) {
                this.classList.add('loading');
                // Remove loading state after navigation
                setTimeout(() => {
                    this.classList.remove('loading');
                }, 2000);
            }
        });
    });

    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(-4px) scale(1)';
        });
    });

    // Initialize real-time updates for statistics
    updateStatistics();
    
    // Auto-refresh statistics every 5 minutes
    setInterval(updateStatistics, 300000);
}

// Animation System
function initializeAnimations() {
    // Intersection Observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Add appropriate animation class based on element type
                if (element.classList.contains('stat-card')) {
                    element.classList.add('scale-in');
                } else if (element.classList.contains('content-card')) {
                    element.classList.add('fade-in-up');
                } else if (element.classList.contains('action-card')) {
                    element.classList.add('slide-in-left');
                }
                
                observer.unobserve(element);
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animatedElements = document.querySelectorAll(
        '.stat-card, .content-card, .action-card, .welcome-section'
    );
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });

    // Stagger animation for stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
}

// Tooltip System
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

function showTooltip(e) {
    const element = e.target;
    const tooltipText = element.getAttribute('data-tooltip');
    
    if (!tooltipText) return;
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = tooltipText;
    
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();
    
    tooltip.style.left = `${rect.left + (rect.width - tooltipRect.width) / 2}px`;
    tooltip.style.top = `${rect.top - tooltipRect.height - 8}px`;
    
    setTimeout(() => {
        tooltip.classList.add('visible');
    }, 10);
    
    element._tooltip = tooltip;
}

function hideTooltip(e) {
    const element = e.target;
    const tooltip = element._tooltip;
    
    if (tooltip) {
        tooltip.classList.remove('visible');
        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, 200);
        delete element._tooltip;
    }
}

// Confirmation Dialogs
function initializeConfirmDialogs() {
    const deleteButtons = document.querySelectorAll('[data-confirm]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = this.getAttribute('data-confirm') || 'Are you sure?';
            const action = this.getAttribute('href') || this.getAttribute('data-action');
            
            showConfirmDialog(message, action);
        });
    });
}

function showConfirmDialog(message, action) {
    const dialog = document.createElement('div');
    dialog.className = 'confirm-dialog-overlay';
    dialog.innerHTML = `
        <div class="confirm-dialog">
            <div class="confirm-dialog-header">
                <h3>Confirm Action</h3>
            </div>
            <div class="confirm-dialog-body">
                <p>${message}</p>
            </div>
            <div class="confirm-dialog-footer">
                <button class="btn btn-outline cancel-btn">Cancel</button>
                <button class="btn btn-primary confirm-btn">Confirm</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(dialog);
    
    // Add event listeners
    const cancelBtn = dialog.querySelector('.cancel-btn');
    const confirmBtn = dialog.querySelector('.confirm-btn');
    
    cancelBtn.addEventListener('click', () => {
        closeConfirmDialog(dialog);
    });
    
    confirmBtn.addEventListener('click', () => {
        if (action) {
            window.location.href = action;
        }
        closeConfirmDialog(dialog);
    });
    
    // Close on overlay click
    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
            closeConfirmDialog(dialog);
        }
    });
    
    // Show dialog
    setTimeout(() => {
        dialog.classList.add('active');
    }, 10);
}

function closeConfirmDialog(dialog) {
    dialog.classList.remove('active');
    setTimeout(() => {
        if (dialog.parentNode) {
            dialog.parentNode.removeChild(dialog);
        }
    }, 300);
}

// Auto-refresh functionality
function initializeAutoRefresh() {
    // Check for new messages every 2 minutes
    setInterval(checkNewMessages, 120000);
    
    // Update last activity timestamp
    updateLastActivity();
    setInterval(updateLastActivity, 60000);
}

function checkNewMessages() {
    fetch('api/check-messages.php')
        .then(response => response.json())
        .then(data => {
            if (data.newMessages > 0) {
                updateNotificationBadge(data.newMessages);
                showNotification(`You have ${data.newMessages} new message(s)`, 'info');
            }
        })
        .catch(error => {
            console.error('Error checking messages:', error);
        });
}

function updateNotificationBadge(count) {
    const badges = document.querySelectorAll('.notification-badge, .nav-badge');
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
}

function updateLastActivity() {
    fetch('api/update-activity.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    }).catch(error => {
        console.error('Error updating activity:', error);
    });
}

// Statistics Update
function updateStatistics() {
    fetch('api/get-statistics.php')
        .then(response => response.json())
        .then(data => {
            updateStatCard('total_programs', data.total_programs);
            updateStatCard('total_beneficiaries', data.total_beneficiaries);
            updateStatCard('total_messages', data.total_messages);
            updateStatCard('total_testimonials', data.total_testimonials);
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
}

function updateStatCard(type, value) {
    const statNumber = document.querySelector(`.stat-${type} .stat-number`);
    if (statNumber) {
        animateNumber(statNumber, parseInt(statNumber.textContent.replace(/,/g, '')), value);
    }
}

function animateNumber(element, start, end) {
    const duration = 1000;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current.toLocaleString();
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

// Notification System
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `admin-notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${getNotificationIcon(type)}"></i>
            <span>${message}</span>
        </div>
        <button class="notification-close">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        hideNotification(notification);
    }, 5000);
    
    // Close button
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        hideNotification(notification);
    });
}

function hideNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

function getNotificationIcon(type) {
    const icons = {
        'success': 'check-circle',
        'error': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}

// Utility Functions
function formatNumber(num) {
    return num.toLocaleString();
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Export functions for use in other scripts
window.AdminDashboard = {
    showNotification,
    showConfirmDialog,
    updateStatistics,
    formatNumber,
    formatDate,
    formatTime
};
