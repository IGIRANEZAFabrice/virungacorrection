<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Guide Cards</title>
    <style>
        :root {
            --primary-green: #2a4858;
            --primary-brown: #8b7355;
            --accent-sage: #2a4858ac;
            --accent-terracotta: #967259;
            --accent-light-brown: #a68c69;
            --neutral-cream: #f2e8dc;
            --neutral-beige: #d8c3a5;
            --neutral-light: #f6f4f0;
            --neutral-dark: #3a3026;
            --text-dark: #3a3026;
            --text-medium: #5d4e41;
            --text-light: #f6f4f0;
            --success-color: #4caf50;
            --error-color: #f44336;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--neutral-light);
            color: var(--text-dark);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--neutral-beige);
        }

        h1, h2 {
            color: var(--primary-green);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background-color: var(--primary-green);
            color: var(--text-light);
        }

        .btn-primary:hover {
            background-color: var(--accent-sage);
        }

        .search-filter {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-bar {
            flex-grow: 1;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            font-size: 16px;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 2px rgba(42, 72, 88, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-medium);
        }

        .search-icon svg {
            width: 20px;
            height: 20px;
        }

        .sort-select {
            padding: 12px;
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            font-size: 16px;
            background-color: var(--neutral-light);
        }

        .sort-select:focus {
            outline: none;
            border-color: var(--primary-green);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .card {
            background-color: var(--neutral-light);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            height: 200px;
            width: 100%;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-title {
            font-size: 1.4rem;
            margin-bottom: 8px;
            color: var(--text-dark);
        }

        .card-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            color: var(--text-medium);
            font-size: 0.9rem;
        }

        .card-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }

        .btn-edit {
            background-color: var(--accent-terracotta);
            color: var(--text-light);
        }

        .btn-edit:hover {
            background-color: var(--primary-brown);
        }

        .btn-delete {
            background-color: var(--error-color);
            color: var(--text-light);
        }

        .btn-delete:hover {
            background-color: #d32f2f;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 40px;
            gap: 8px;
        }

        .pagination button {
            padding: 8px 15px;
            border: 1px solid var(--neutral-beige);
            background-color: var(--neutral-light);
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pagination button:hover {
            background-color: var(--neutral-cream);
        }

        .pagination button.active {
            background-color: var(--primary-green);
            color: var(--text-light);
            border-color: var(--primary-green);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal-content {
            background-color: var(--neutral-light);
            margin: 10% auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: var(--text-medium);
        }

        .modal-title {
            margin-bottom: 20px;
            color: var(--error-color);
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
        }

        .no-cards {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background-color: var(--neutral-light);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }

        .no-cards h3 {
            margin-bottom: 15px;
            color: var(--text-dark);
        }

        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-weight: 600;
            display: none;
        }

        .notification-success {
            background-color: #e8f5e9;
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .notification-error {
            background-color: #ffebee;
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .loading {
            display: none;
            text-align: center;
            margin: 40px 0;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-green);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 768px) {
            .search-filter {
                flex-direction: column;
                align-items: stretch;
            }
            
            .cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
            
            .card-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-sm {
                text-align: center;
            }
            
            header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Style Guide Cards</h1>
            <a href="style-guide-upload.html" class="btn btn-primary">Add New Style Guide</a>
        </header>

        <div id="notification" class="notification"></div>

        <div class="search-filter">
            <div class="search-bar">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Search style guides...">
            </div>
            <select class="sort-select" id="sortSelect">
                <option value="newest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="title_asc">Title (A-Z)</option>
                <option value="title_desc">Title (Z-A)</option>
            </select>
        </div>

        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Loading style guides...</p>
        </div>

        <div class="cards-grid" id="cardsGrid">
            <!-- Cards will be dynamically loaded here -->
        </div>

        <div class="pagination" id="pagination">
            <!-- Pagination buttons will be dynamically added here -->
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeModal">&times;</span>
            <h2 class="modal-title">Confirm Deletion</h2>
            <p>Are you sure you want to delete this style guide? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn btn-secondary" id="cancelDelete">Cancel</button>
                <button class="btn btn-delete" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const cardsGrid = document.getElementById('cardsGrid');
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sortSelect');
            const paginationContainer = document.getElementById('pagination');
            const deleteModal = document.getElementById('deleteModal');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelDeleteBtn = document.getElementById('cancelDelete');
            const confirmDeleteBtn = document.getElementById('confirmDelete');
            const notification = document.getElementById('notification');
            const loading = document.getElementById('loading');
            
            // Variables
            let styleGuides = [];
            let currentPage = 1;
            let cardsPerPage = 9;
            let deletingCardId = null;
            
            // Fetch style guides from API
            function fetchStyleGuides() {
                loading.style.display = 'block';
                cardsGrid.innerHTML = '';
                
                fetch('/ecotours/admin/pages/styleguide/api/get_style_guides.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            styleGuides = data.data;
                            loading.style.display = 'none';
                            sortStyleGuides();
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        loading.style.display = 'none';
                        showNotification('Error loading style guides: ' + error.message, 'error');
                    });
            }
            
            // Filter style guides based on search input
            function filterStyleGuides() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                
                if (!searchTerm) {
                    return styleGuides;
                }
                
                return styleGuides.filter(guide => 
                    guide.title.toLowerCase().includes(searchTerm)
                );
            }
            
            // Sort style guides based on selected option
            function sortStyleGuides() {
                const sortOption = sortSelect.value;
                const filteredGuides = filterStyleGuides();
                
                switch(sortOption) {
                    case 'newest':
                        filteredGuides.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        break;
                    case 'oldest':
                        filteredGuides.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
                        break;
                    case 'title_asc':
                        filteredGuides.sort((a, b) => a.title.localeCompare(b.title));
                        break;
                    case 'title_desc':
                        filteredGuides.sort((a, b) => b.title.localeCompare(a.title));
                        break;
                }
                
                renderCards(filteredGuides);
                renderPagination(filteredGuides);
            }
            
            // Render cards to the grid
            function renderCards(guides) {
                cardsGrid.innerHTML = '';
                
                if (guides.length === 0) {
                    cardsGrid.innerHTML = `
                        <div class="no-cards">
                            <h3>No style guides found</h3>
                            <p>Try adjusting your search criteria or add a new style guide.</p>
                        </div>
                    `;
                    return;
                }
                
                // Calculate pagination
                const startIndex = (currentPage - 1) * cardsPerPage;
                const endIndex = Math.min(startIndex + cardsPerPage, guides.length);
                const paginatedGuides = guides.slice(startIndex, endIndex);
                
                // Create card elements
                paginatedGuides.forEach(guide => {
                    const date = new Date(guide.created_at);
                    const formattedDate = date.toLocaleDateString('en-US', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric'
                    });
                    
                    const card = document.createElement('div');
                    card.className = 'card';
                    card.dataset.id = guide.card_id;
                    
                    card.innerHTML = `
                        <img src="${guide.thumbnail_image}" alt="${guide.title}" class="card-image">
                        <div class="card-content">
                            <h2 class="card-title">${guide.title}</h2>
                            <div class="card-meta">
                                <span>Created: ${formattedDate}</span>
                            </div>
                            <div class="card-actions">
                                <a href="style-guide-edit-form.php?id=${guide.card_id}" class="btn btn-sm btn-edit">Edit</a>
                                <button class="btn btn-sm btn-delete" data-id="${guide.card_id}">Delete</button>
                            </div>
                        </div>
                    `;
                    
                    cardsGrid.appendChild(card);
                    
                    // Add delete event listener
                    const deleteBtn = card.querySelector('.btn-delete');
                    deleteBtn.addEventListener('click', function() {
                        openDeleteModal(guide.card_id);
                    });
                });
            }
            
            // Render pagination controls
            function renderPagination(guides) {
                paginationContainer.innerHTML = '';
                
                if (guides.length <= cardsPerPage) {
                    return;
                }
                
                const totalPages = Math.ceil(guides.length / cardsPerPage);
                
                // Add previous button
                if (currentPage > 1) {
                    const prevBtn = document.createElement('button');
                    prevBtn.innerHTML = '&laquo;';
                    prevBtn.addEventListener('click', () => {
                        currentPage--;
                        sortStyleGuides();
                    });
                    paginationContainer.appendChild(prevBtn);
                }
                
                // Add page buttons
                for (let i = 1; i <= totalPages; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.textContent = i;
                    
                    if (i === currentPage) {
                        pageBtn.classList.add('active');
                    }
                    
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        sortStyleGuides();
                    });
                    
                    paginationContainer.appendChild(pageBtn);
                }
                
                // Add next button
                if (currentPage < totalPages) {
                    const nextBtn = document.createElement('button');
                    nextBtn.innerHTML = '&raquo;';
                    nextBtn.addEventListener('click', () => {
                        currentPage++;
                        sortStyleGuides();
                    });
                    paginationContainer.appendChild(nextBtn);
                }
            }
            
            // Open delete confirmation modal
            function openDeleteModal(cardId) {
                deletingCardId = cardId;
                deleteModal.style.display = 'block';
            }
            
            // Close delete confirmation modal
            function closeDeleteModal() {
                deleteModal.style.display = 'none';
                deletingCardId = null;
            }
            
            // Delete style guide using API
            function deleteStyleGuide(cardId) {
                loading.style.display = 'block';
                
                const formData = new FormData();
                formData.append('card_id', cardId);

                fetch('/ecotours/admin/pages/styleguide/api/delete_style_guide.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    loading.style.display = 'none';
                    if (data.success) {
                        showNotification(data.message, 'success');
                        fetchStyleGuides(); // Refresh the list
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    loading.style.display = 'none';
                    showNotification('Error deleting style guide: ' + error.message, 'error');
                });
            }
            
            // Function to show notification
            function showNotification(message, type) {
                notification.textContent = message;
                notification.className = 'notification';
                notification.classList.add(`notification-${type}`);
                notification.style.display = 'block';
                
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 5000);
            }
            
            // Event Listeners
            searchInput.addEventListener('input', () => {
                currentPage = 1;
                sortStyleGuides();
            });
            
            sortSelect.addEventListener('change', () => {
                currentPage = 1;
                sortStyleGuides();
            });
            
            // Modal event listeners
            closeModalBtn.addEventListener('click', closeDeleteModal);
            cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            confirmDeleteBtn.addEventListener('click', () => {
                if (deletingCardId) {
                    deleteStyleGuide(deletingCardId);
                    closeDeleteModal();
                }
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            });
            
            // Initial load
            fetchStyleGuides();
        });
    </script>
</body>
</html>
