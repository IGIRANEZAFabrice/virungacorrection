// Management Pages JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeManagement();
});

function initializeManagement() {
    initializeFilters();
    initializeBulkActions();
    initializeConfirmations();
    initializeTableFeatures();
    initializeFormValidation();
}

// Filters functionality
function initializeFilters() {
    const filtersToggle = document.getElementById('filtersToggle');
    const filtersContent = document.getElementById('filtersContent');
    
    if (filtersToggle && filtersContent) {
        filtersToggle.addEventListener('click', function() {
            const isVisible = filtersContent.style.display !== 'none';
            filtersContent.style.display = isVisible ? 'none' : 'block';
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = isVisible ? 'fas fa-chevron-down' : 'fas fa-chevron-up';
            }
        });
        
        // Initially hide filters on mobile
        if (window.innerWidth <= 768) {
            filtersContent.style.display = 'none';
        }
    }
}

// Bulk actions functionality
function initializeBulkActions() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const itemCheckboxes = document.querySelectorAll('.program-checkbox, .testimonial-checkbox, .team-checkbox, .message-checkbox');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkButton = document.getElementById('applyBulkAction');
    const bulkForm = document.getElementById('bulkActionsForm');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActionButton();
        });
    }
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllState();
            updateBulkActionButton();
        });
    });
    
    if (bulkActionSelect) {
        bulkActionSelect.addEventListener('change', updateBulkActionButton);
    }
    
    if (bulkForm) {
        bulkForm.addEventListener('submit', function(e) {
            const selectedItems = document.querySelectorAll('input[name*="selected_"]:checked');
            const action = bulkActionSelect.value;
            
            if (selectedItems.length === 0) {
                e.preventDefault();
                showNotification('Please select at least one item.', 'warning');
                return;
            }
            
            if (!action) {
                e.preventDefault();
                showNotification('Please select an action.', 'warning');
                return;
            }
            
            // Confirm destructive actions
            if (action === 'delete') {
                const confirmMessage = `Are you sure you want to delete ${selectedItems.length} item(s)? This action cannot be undone.`;
                if (!confirm(confirmMessage)) {
                    e.preventDefault();
                    return;
                }
            }
        });
    }
    
    function updateSelectAllState() {
        if (selectAllCheckbox) {
            const checkedCount = document.querySelectorAll('input[name*="selected_"]:checked').length;
            const totalCount = itemCheckboxes.length;
            
            selectAllCheckbox.checked = checkedCount === totalCount && totalCount > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < totalCount;
        }
    }
    
    function updateBulkActionButton() {
        if (applyBulkButton) {
            const selectedCount = document.querySelectorAll('input[name*="selected_"]:checked').length;
            const hasAction = bulkActionSelect && bulkActionSelect.value;
            
            applyBulkButton.disabled = selectedCount === 0 || !hasAction;
            
            if (selectedCount > 0) {
                applyBulkButton.textContent = `Apply (${selectedCount})`;
            } else {
                applyBulkButton.textContent = 'Apply';
            }
        }
    }
}

// Confirmation dialogs
function initializeConfirmations() {
    const confirmLinks = document.querySelectorAll('[data-confirm]');
    
    confirmLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm');
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
}

// Table features
function initializeTableFeatures() {
    // Row hover effects
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(42, 72, 88, 0.05)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
    
    // Action button tooltips
    const actionButtons = document.querySelectorAll('.btn-action');
    actionButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (title) {
                showTooltip(this, title);
            }
        });
        
        button.addEventListener('mouseleave', function() {
            hideTooltip();
        });
    });
}

// Form validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        // Real-time validation
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
        
        // Form submission validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            inputs.forEach(input => {
                if (!validateField(input)) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showNotification('Please fix the errors in the form.', 'error');
                
                // Focus first invalid field
                const firstError = form.querySelector('.has-error input, .has-error select, .has-error textarea');
                if (firstError) {
                    firstError.focus();
                }
            } else {
                // Show loading state
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.classList.add('loading');
                    submitButton.disabled = true;
                }
            }
        });
    });
}

function validateField(field) {
    const formGroup = field.closest('.form-group');
    if (!formGroup) return true;
    
    clearFieldError(field);
    
    // Required field validation
    if (field.hasAttribute('required') && !field.value.trim()) {
        showFieldError(formGroup, 'This field is required.');
        return false;
    }
    
    // Email validation
    if (field.type === 'email' && field.value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(field.value)) {
            showFieldError(formGroup, 'Please enter a valid email address.');
            return false;
        }
    }
    
    // URL validation
    if (field.type === 'url' && field.value) {
        try {
            new URL(field.value);
        } catch {
            showFieldError(formGroup, 'Please enter a valid URL.');
            return false;
        }
    }
    
    // Number validation
    if (field.type === 'number' && field.value) {
        const min = field.getAttribute('min');
        const max = field.getAttribute('max');
        const value = parseFloat(field.value);
        
        if (min && value < parseFloat(min)) {
            showFieldError(formGroup, `Value must be at least ${min}.`);
            return false;
        }
        
        if (max && value > parseFloat(max)) {
            showFieldError(formGroup, `Value must be no more than ${max}.`);
            return false;
        }
    }
    
    // File validation
    if (field.type === 'file' && field.files.length > 0) {
        const allowedTypes = field.getAttribute('accept');
        if (allowedTypes) {
            const file = field.files[0];
            const fileType = file.type;
            
            if (allowedTypes.includes('image/*') && !fileType.startsWith('image/')) {
                showFieldError(formGroup, 'Please select an image file.');
                return false;
            }
        }
        
        // File size validation (5MB limit)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (field.files[0].size > maxSize) {
            showFieldError(formGroup, 'File size must be less than 5MB.');
            return false;
        }
    }
    
    formGroup.classList.add('has-success');
    return true;
}

function showFieldError(formGroup, message) {
    formGroup.classList.add('has-error');
    formGroup.classList.remove('has-success');
    
    let errorElement = formGroup.querySelector('.error-message');
    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        formGroup.appendChild(errorElement);
    }
    
    errorElement.textContent = message;
    errorElement.style.display = 'block';
}

function clearFieldError(field) {
    const formGroup = field.closest('.form-group');
    if (formGroup) {
        formGroup.classList.remove('has-error', 'has-success');
        const errorElement = formGroup.querySelector('.error-message');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }
}

// Tooltip functionality
let currentTooltip = null;

function showTooltip(element, text) {
    hideTooltip();
    
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    document.body.appendChild(tooltip);
    
    const rect = element.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();
    
    tooltip.style.left = `${rect.left + (rect.width - tooltipRect.width) / 2}px`;
    tooltip.style.top = `${rect.top - tooltipRect.height - 8}px`;
    
    setTimeout(() => {
        tooltip.classList.add('visible');
    }, 10);
    
    currentTooltip = tooltip;
}

function hideTooltip() {
    if (currentTooltip) {
        currentTooltip.classList.remove('visible');
        setTimeout(() => {
            if (currentTooltip && currentTooltip.parentNode) {
                currentTooltip.parentNode.removeChild(currentTooltip);
            }
            currentTooltip = null;
        }, 200);
    }
}

// Notification system
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

// Image preview functionality
function initializeImagePreview() {
    const fileInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            showImagePreview(this);
        });
    });
}

function showImagePreview(input) {
    const files = input.files;
    if (!files.length) return;
    
    // Remove existing preview
    const existingPreview = input.parentNode.querySelector('.image-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    const preview = document.createElement('div');
    preview.className = 'image-preview';
    
    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'image-preview-item';
                previewItem.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="image-preview-remove" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                preview.appendChild(previewItem);
                
                // Remove button functionality
                const removeBtn = previewItem.querySelector('.image-preview-remove');
                removeBtn.addEventListener('click', function() {
                    previewItem.remove();
                    // Note: Removing files from input is not straightforward
                    // This is mainly for visual feedback
                });
            };
            reader.readAsDataURL(file);
        }
    });
    
    input.parentNode.appendChild(preview);
}

// Initialize image preview when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeImagePreview();
});

// Export functions for use in other scripts
window.ManagementJS = {
    showNotification,
    validateField,
    showImagePreview,
    showTooltip,
    hideTooltip
};
