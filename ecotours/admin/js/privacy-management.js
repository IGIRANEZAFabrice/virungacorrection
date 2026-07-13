// Privacy Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializePrivacyManagement();
});

function initializePrivacyManagement() {
    // Initialize any required functionality
    console.log('Privacy Management initialized');
}

// Toggle Privacy Policy Editor
function togglePolicyEditor() {
    const editor = document.getElementById('policy-editor');
    const isVisible = editor.style.display !== 'none';
    
    if (isVisible) {
        editor.style.display = 'none';
    } else {
        editor.style.display = 'block';
        // Focus on the textarea
        const textarea = editor.querySelector('textarea');
        if (textarea) {
            textarea.focus();
        }
    }
}

// Filter Privacy Requests
function filterRequests() {
    const statusFilter = document.getElementById('status-filter').value;
    const tableRows = document.querySelectorAll('.requests-table tbody tr');
    
    tableRows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        
        if (statusFilter === '' || rowStatus === statusFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// View Request Details
function viewRequest(requestId) {
    // Fetch request details via AJAX
    fetch(`../handlers/privacy_handler.php?action=get_request&id=${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayRequestDetails(data.request);
                openModal('request-modal');
            } else {
                alert('Error loading request details: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading request details');
        });
}

// Display Request Details in Modal
function displayRequestDetails(request) {
    const detailsContainer = document.getElementById('request-details');
    
    const statusBadgeClass = getStatusBadgeClass(request.status);
    const requestTypeClass = getRequestTypeClass(request.request_type);
    
    detailsContainer.innerHTML = `
        <div class="request-detail-grid">
            <div class="detail-item">
                <label>Request ID:</label>
                <span>${request.id}</span>
            </div>
            <div class="detail-item">
                <label>Type:</label>
                <span class="request-type ${requestTypeClass}">
                    ${formatRequestType(request.request_type)}
                </span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span class="status-badge ${statusBadgeClass}">
                    ${formatStatus(request.status)}
                </span>
            </div>
            <div class="detail-item">
                <label>Email:</label>
                <span>${request.email}</span>
            </div>
            <div class="detail-item">
                <label>Subject:</label>
                <span>${request.subject}</span>
            </div>
            <div class="detail-item full-width">
                <label>Message:</label>
                <div class="message-content">${request.message}</div>
            </div>
            <div class="detail-item">
                <label>Created:</label>
                <span>${formatDate(request.created_at)}</span>
            </div>
            <div class="detail-item">
                <label>Updated:</label>
                <span>${formatDate(request.updated_at)}</span>
            </div>
            ${request.admin_response ? `
                <div class="detail-item full-width">
                    <label>Admin Response:</label>
                    <div class="response-content">${request.admin_response}</div>
                </div>
            ` : ''}
        </div>
        
        <div class="modal-actions">
            <button class="btn btn-warning" onclick="updateRequestStatus(${request.id})">
                <i class="fas fa-edit"></i> Update Status
            </button>
            <button class="btn btn-danger" onclick="confirmDeleteRequest(${request.id})">
                <i class="fas fa-trash"></i> Delete Request
            </button>
        </div>
    `;
}

// Update Request Status
function updateRequestStatus(requestId) {
    // Close any open modals first
    closeModal('request-modal');
    
    // Set the request ID in the status form
    document.getElementById('status-request-id').value = requestId;
    
    // Fetch current request data to populate form
    fetch(`../handlers/privacy_handler.php?action=get_request&id=${requestId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const request = data.request;
                document.getElementById('status').value = request.status;
                document.getElementById('response').value = request.admin_response || '';
                openModal('status-modal');
            } else {
                alert('Error loading request data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading request data');
        });
}

// Delete Request
function deleteRequest(requestId) {
    if (confirm('Are you sure you want to delete this privacy request? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete_request';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'request_id';
        idInput.value = requestId;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Confirm Delete Request (from modal)
function confirmDeleteRequest(requestId) {
    closeModal('request-modal');
    deleteRequest(requestId);
}

// Modal Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target === modal) {
            closeModal(modal.id);
        }
    });
}

// Utility Functions
function getStatusBadgeClass(status) {
    const statusClasses = {
        'pending': 'pending',
        'in_progress': 'in_progress',
        'completed': 'completed',
        'rejected': 'rejected'
    };
    return statusClasses[status] || 'pending';
}

function getRequestTypeClass(type) {
    const typeClasses = {
        'data_access': 'data_access',
        'data_deletion': 'data_deletion',
        'data_portability': 'data_portability',
        'data_correction': 'data_correction'
    };
    return typeClasses[type] || 'data_access';
}

function formatRequestType(type) {
    const typeNames = {
        'data_access': 'Data Access',
        'data_deletion': 'Data Deletion',
        'data_portability': 'Data Portability',
        'data_correction': 'Data Correction'
    };
    return typeNames[type] || type.replace('_', ' ').toUpperCase();
}

function formatStatus(status) {
    const statusNames = {
        'pending': 'Pending',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'rejected': 'Rejected'
    };
    return statusNames[status] || status.toUpperCase();
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

// Export Data Function
function exportPrivacyData() {
    const statusFilter = document.getElementById('status-filter').value;
    const url = `../handlers/privacy_handler.php?action=export&status=${statusFilter}`;
    
    // Create a temporary link to download the file
    const link = document.createElement('a');
    link.href = url;
    link.download = `privacy_requests_${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Search Functionality
function searchRequests() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase();
    const tableRows = document.querySelectorAll('.requests-table tbody tr');
    
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Auto-save functionality for policy editor
let autoSaveTimeout;
function setupAutoSave() {
    const policyTextarea = document.getElementById('policy_content');
    if (policyTextarea) {
        policyTextarea.addEventListener('input', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Auto-save draft (you can implement this feature)
                console.log('Auto-saving draft...');
            }, 5000); // Save after 5 seconds of inactivity
        });
    }
}

// Initialize auto-save when page loads
document.addEventListener('DOMContentLoaded', setupAutoSave);
