// Style Guides Management JavaScript

let currentGuideId = null;
let contentSectionCounter = 0;

// DOM Elements
const guideModal = document.getElementById('guideModal');
const contentModal = document.getElementById('contentModal');
const guideForm = document.getElementById('guideForm');
const contentForm = document.getElementById('contentForm');

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupFileUploads();
    setupFormHandlers();
});

// Modal Functions
function openAddModal() {
    currentGuideId = null;
    document.getElementById('modalTitle').textContent = 'Add New Style Guide';
    document.getElementById('guideId').value = '';
    guideForm.reset();
    resetFilePreview('thumbnailPreview');
    guideModal.classList.add('active');
}

function editGuide(id) {
    currentGuideId = id;
    document.getElementById('modalTitle').textContent = 'Edit Style Guide';
    document.getElementById('guideId').value = id;
    
    // Fetch guide data and populate form
    fetch(`../handlers/styleguides/get_guide.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('guideTitle').value = data.guide.title;
                if (data.guide.thumbnail_image) {
                    showImagePreview('thumbnailPreview', `../images/style-guide/${data.guide.thumbnail_image}`);
                }
                guideModal.classList.add('active');
            } else {
                showNotification('Error loading guide data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading guide data', 'error');
        });
}

function manageContent(id) {
    currentGuideId = id;
    document.getElementById('contentModalTitle').textContent = 'Manage Content';
    document.getElementById('contentGuideId').value = id;
    
    // Fetch content data and populate form
    fetch(`../handlers/styleguides/get_content.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const content = data.content;
                document.getElementById('introText').value = content.intro_text || '';
                
                if (content.hero_image) {
                    showImagePreview('heroPreview', `../images/style-guide/${content.hero_image}`);
                }
                
                // Load main content sections
                loadContentSections(content.main_content);
                contentModal.classList.add('active');
            } else {
                // No content exists yet, show empty form
                contentForm.reset();
                resetFilePreview('heroPreview');
                clearContentSections();
                contentModal.classList.add('active');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading content data', 'error');
        });
}

function deleteGuide(id) {
    if (confirm('Are you sure you want to delete this style guide? This action cannot be undone.')) {
        fetch('../handlers/styleguides/delete_guide.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Style guide deleted successfully', 'success');
                location.reload();
            } else {
                showNotification(data.message || 'Error deleting guide', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error deleting guide', 'error');
        });
    }
}

function closeModal() {
    guideModal.classList.remove('active');
    guideForm.reset();
    resetFilePreview('thumbnailPreview');
}

function closeContentModal() {
    contentModal.classList.remove('active');
    contentForm.reset();
    resetFilePreview('heroPreview');
    clearContentSections();
}

// File Upload Functions
function setupFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewId = this.id === 'guideThumbnail' ? 'thumbnailPreview' : 'heroPreview';
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(previewId, e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    });
}

function showImagePreview(previewId, src) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = `<img src="${src}" alt="Preview">`;
}

function resetFilePreview(previewId) {
    const preview = document.getElementById(previewId);
    preview.innerHTML = `
        <i class="fas fa-image"></i>
        <p>Click to upload ${previewId.includes('thumbnail') ? 'thumbnail' : 'hero image'}</p>
    `;
}

// Content Editor Functions
function addSection() {
    contentSectionCounter++;
    const sectionsContainer = document.getElementById('contentSections');
    const sectionHtml = `
        <div class="content-section" data-section="${contentSectionCounter}">
            <div class="section-header">
                <span class="section-type">Text Section</span>
                <button type="button" class="section-remove" onclick="removeSection(${contentSectionCounter})">Remove</button>
            </div>
            <div class="form-group">
                <label>Section Title</label>
                <input type="text" name="section_title_${contentSectionCounter}" placeholder="Enter section title">
            </div>
            <div class="form-group">
                <label>Section Content</label>
                <textarea name="section_content_${contentSectionCounter}" rows="4" placeholder="Enter section content..."></textarea>
            </div>
        </div>
    `;
    sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
}

function addList() {
    contentSectionCounter++;
    const sectionsContainer = document.getElementById('contentSections');
    const sectionHtml = `
        <div class="content-section" data-section="${contentSectionCounter}">
            <div class="section-header">
                <span class="section-type">List Section</span>
                <button type="button" class="section-remove" onclick="removeSection(${contentSectionCounter})">Remove</button>
            </div>
            <div class="form-group">
                <label>List Title</label>
                <input type="text" name="list_title_${contentSectionCounter}" placeholder="Enter list title">
            </div>
            <div class="form-group">
                <label>List Items (one per line)</label>
                <textarea name="list_items_${contentSectionCounter}" rows="6" placeholder="Enter list items, one per line..."></textarea>
            </div>
        </div>
    `;
    sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
}

function removeSection(sectionId) {
    const section = document.querySelector(`[data-section="${sectionId}"]`);
    if (section) {
        section.remove();
    }
}

function clearContentSections() {
    document.getElementById('contentSections').innerHTML = '';
    contentSectionCounter = 0;
}

function loadContentSections(mainContent) {
    clearContentSections();
    
    if (!mainContent) return;
    
    try {
        const content = typeof mainContent === 'string' ? JSON.parse(mainContent) : mainContent;
        
        if (Array.isArray(content)) {
            content.forEach(section => {
                if (section.type === 'text') {
                    addTextSection(section.title, section.content);
                } else if (section.type === 'list') {
                    addListSection(section.title, section.items);
                }
            });
        }
    } catch (error) {
        console.error('Error loading content sections:', error);
    }
}

function addTextSection(title, content) {
    contentSectionCounter++;
    const sectionsContainer = document.getElementById('contentSections');
    const sectionHtml = `
        <div class="content-section" data-section="${contentSectionCounter}">
            <div class="section-header">
                <span class="section-type">Text Section</span>
                <button type="button" class="section-remove" onclick="removeSection(${contentSectionCounter})">Remove</button>
            </div>
            <div class="form-group">
                <label>Section Title</label>
                <input type="text" name="section_title_${contentSectionCounter}" value="${title || ''}" placeholder="Enter section title">
            </div>
            <div class="form-group">
                <label>Section Content</label>
                <textarea name="section_content_${contentSectionCounter}" rows="4" placeholder="Enter section content...">${content || ''}</textarea>
            </div>
        </div>
    `;
    sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
}

function addListSection(title, items) {
    contentSectionCounter++;
    const sectionsContainer = document.getElementById('contentSections');
    const itemsText = Array.isArray(items) ? items.join('\n') : items || '';
    const sectionHtml = `
        <div class="content-section" data-section="${contentSectionCounter}">
            <div class="section-header">
                <span class="section-type">List Section</span>
                <button type="button" class="section-remove" onclick="removeSection(${contentSectionCounter})">Remove</button>
            </div>
            <div class="form-group">
                <label>List Title</label>
                <input type="text" name="list_title_${contentSectionCounter}" value="${title || ''}" placeholder="Enter list title">
            </div>
            <div class="form-group">
                <label>List Items (one per line)</label>
                <textarea name="list_items_${contentSectionCounter}" rows="6" placeholder="Enter list items, one per line...">${itemsText}</textarea>
            </div>
        </div>
    `;
    sectionsContainer.insertAdjacentHTML('beforeend', sectionHtml);
}

// Form Handlers
function setupFormHandlers() {
    // Guide form handler
    guideForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const url = currentGuideId ? '../handlers/styleguides/update_guide.php' : '../handlers/styleguides/create_guide.php';

        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Style guide saved successfully', 'success');
                closeModal();
                location.reload();
            } else {
                showNotification(data.message || 'Error saving guide', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving guide', 'error');
        });
    });

    // Content form handler
    contentForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Collect content sections
        const sections = [];
        const contentSections = document.querySelectorAll('.content-section');

        contentSections.forEach(section => {
            const sectionId = section.dataset.section;
            const sectionType = section.querySelector('.section-type').textContent.toLowerCase().includes('list') ? 'list' : 'text';

            if (sectionType === 'text') {
                const title = section.querySelector(`input[name="section_title_${sectionId}"]`)?.value || '';
                const content = section.querySelector(`textarea[name="section_content_${sectionId}"]`)?.value || '';

                if (title || content) {
                    sections.push({
                        type: 'text',
                        title: title,
                        content: content
                    });
                }
            } else if (sectionType === 'list') {
                const title = section.querySelector(`input[name="list_title_${sectionId}"]`)?.value || '';
                const itemsText = section.querySelector(`textarea[name="list_items_${sectionId}"]`)?.value || '';
                const items = itemsText.split('\n').filter(item => item.trim());

                if (title || items.length > 0) {
                    sections.push({
                        type: 'list',
                        title: title,
                        items: items
                    });
                }
            }
        });

        formData.append('main_content', JSON.stringify(sections));

        fetch('../handlers/styleguides/save_content.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message || 'Content saved successfully', 'success');
                closeContentModal();
                location.reload();
            } else {
                showNotification(data.message || 'Error saving content', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error saving content', 'error');
        });
    });
}

// Utility Functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? 'var(--primary-green)' : type === 'error' ? 'var(--accent-terracotta)' : 'var(--primary-brown)'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        z-index: 2000;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: slideInRight 0.3s ease-out;
        max-width: 400px;
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    if (e.target === guideModal) {
        closeModal();
    }
    if (e.target === contentModal) {
        closeContentModal();
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);
