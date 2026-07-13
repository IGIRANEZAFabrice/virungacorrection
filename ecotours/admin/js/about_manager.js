// About Page Manager JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeTabs();
    initializeImagePreviews();
    initializeFormValidation();
});

// Tab Management
function initializeTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab + '-tab').classList.add('active');
            
            // Store active tab in localStorage
            localStorage.setItem('activeAboutTab', targetTab);
        });
    });

    // Restore active tab from localStorage
    const activeTab = localStorage.getItem('activeAboutTab') || 'hero';
    const activeButton = document.querySelector(`[data-tab="${activeTab}"]`);
    const activeContent = document.getElementById(activeTab + '-tab');
    
    if (activeButton && activeContent) {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        activeButton.classList.add('active');
        activeContent.classList.add('active');
    }
}

// Image Preview Functionality
function initializeImagePreviews() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleImagePreview(this);
        });
    });
}

function handleImagePreview(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const currentImageDiv = input.parentElement.querySelector('.current-image');
        
        reader.onload = function(e) {
            // Create preview if it doesn't exist
            let preview = input.parentElement.querySelector('.image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.className = 'image-preview';
                preview.style.marginTop = '10px';
                input.parentElement.appendChild(preview);
            }
            
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" style="max-width: 200px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <p style="margin: 8px 0 0 0; font-size: 12px; color: #666;">New image preview</p>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Form Validation
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showToast('Please fill in all required fields correctly.', 'error');
            }
        });
    });
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            field.style.borderColor = '#e0e0e0';
        }
    });
    
    return isValid;
}

// Delete Functions
function deleteStat(statId) {
    if (confirm('Are you sure you want to delete this statistic? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../handlers/about/deleteImpactStatHandler.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'stat_id';
        input.value = statId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteTeamMember(memberId) {
    if (confirm('Are you sure you want to delete this team member? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../handlers/about/deleteTeamMemberHandler.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'member_id';
        input.value = memberId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteValue(valueId) {
    if (confirm('Are you sure you want to delete this value? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../handlers/about/deleteValueHandler.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'value_id';
        input.value = valueId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteGalleryItem(galleryId) {
    if (confirm('Are you sure you want to delete this gallery item? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../handlers/about/deleteGalleryItemHandler.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'gallery_id';
        input.value = galleryId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}

// Add New Item Functions
function showAddStatForm() {
    const modal = createModal('Add New Statistic', `
        <form action="../handlers/about/addImpactStatHandler.php" method="POST">
            <div class="form-group">
                <label for="new_stat_icon">Icon Class</label>
                <input type="text" id="new_stat_icon" name="icon_class" placeholder="e.g., fas fa-users" required>
                <small>Use Font Awesome icon classes</small>
            </div>
            <div class="form-group">
                <label for="new_stat_count">Count</label>
                <input type="number" id="new_stat_count" name="stat_count" placeholder="e.g., 1500" required>
            </div>
            <div class="form-group">
                <label for="new_stat_title">Title</label>
                <input type="text" id="new_stat_title" name="stat_title" placeholder="e.g., Happy Travelers" required>
            </div>
            <div class="form-group">
                <label for="new_stat_order">Display Order</label>
                <input type="number" id="new_stat_order" name="display_order" value="1" required>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Statistic</button>
            </div>
        </form>
    `);
    
    document.body.appendChild(modal);
}

function showAddTeamMemberForm() {
    const modal = createModal('Add New Team Member', `
        <form action="../handlers/about/addTeamMemberHandler.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new_member_name">Name</label>
                <input type="text" id="new_member_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="new_member_role">Role</label>
                <input type="text" id="new_member_role" name="role" required>
            </div>
            <div class="form-group">
                <label for="new_member_bio">Bio</label>
                <textarea id="new_member_bio" name="bio" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="new_member_image">Image</label>
                <input type="file" id="new_member_image" name="image" accept="image/*" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="new_member_linkedin">LinkedIn URL</label>
                    <input type="url" id="new_member_linkedin" name="linkedin_url">
                </div>
                <div class="form-group">
                    <label for="new_member_twitter">Twitter URL</label>
                    <input type="url" id="new_member_twitter" name="twitter_url">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="new_member_instagram">Instagram URL</label>
                    <input type="url" id="new_member_instagram" name="instagram_url">
                </div>
                <div class="form-group">
                    <label for="new_member_order">Display Order</label>
                    <input type="number" id="new_member_order" name="display_order" value="1" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Team Member</button>
            </div>
        </form>
    `);
    
    document.body.appendChild(modal);
}

function showAddValueForm() {
    const modal = createModal('Add New Value', `
        <form action="../handlers/about/addValueHandler.php" method="POST">
            <div class="form-group">
                <label for="new_value_icon">Icon Class</label>
                <input type="text" id="new_value_icon" name="icon_class" placeholder="e.g., fas fa-leaf" required>
                <small>Use Font Awesome icon classes</small>
            </div>
            <div class="form-group">
                <label for="new_value_title">Title</label>
                <input type="text" id="new_value_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="new_value_description">Description</label>
                <textarea id="new_value_description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="new_value_order">Display Order</label>
                <input type="number" id="new_value_order" name="display_order" value="1" required>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Value</button>
            </div>
        </form>
    `);
    
    document.body.appendChild(modal);
}

function showAddGalleryItemForm() {
    const modal = createModal('Add New Gallery Item', `
        <form action="../handlers/about/addGalleryItemHandler.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="new_gallery_title">Title</label>
                <input type="text" id="new_gallery_title" name="title" required>
            </div>
            <div class="form-group">
                <label for="new_gallery_alt">Alt Text</label>
                <input type="text" id="new_gallery_alt" name="alt_text" required>
            </div>
            <div class="form-group">
                <label for="new_gallery_image">Image</label>
                <input type="file" id="new_gallery_image" name="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <label for="new_gallery_order">Display Order</label>
                <input type="number" id="new_gallery_order" name="display_order" value="1" required>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Gallery Item</button>
            </div>
        </form>
    `);
    
    document.body.appendChild(modal);
}

// Modal Functions
function createModal(title, content) {
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="modal-content">
            <div class="modal-header">
                <h3>${title}</h3>
                <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                ${content}
            </div>
        </div>
    `;
    
    // Add modal styles
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    `;
    
    const modalContent = modal.querySelector('.modal-content');
    modalContent.style.cssText = `
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    `;
    
    const modalHeader = modal.querySelector('.modal-header');
    modalHeader.style.cssText = `
        padding: 20px 30px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #f8f9fa;
    `;
    
    const modalBody = modal.querySelector('.modal-body');
    modalBody.style.cssText = `
        padding: 30px;
    `;
    
    const closeButton = modal.querySelector('.modal-close');
    closeButton.style.cssText = `
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    `;
    
    return modal;
}

function closeModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        modal.remove();
    }
}

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 1001;
        animation: slideIn 0.3s ease-out;
    `;
    
    if (type === 'success') {
        toast.style.backgroundColor = '#28a745';
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545';
    } else {
        toast.style.backgroundColor = '#17a2b8';
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
