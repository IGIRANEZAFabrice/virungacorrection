<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Style Guide</title>
    <style>
        :root {
            --primary-color: #4a6fdc;
            --secondary-color: #f5f7ff;
            --text-color: #333;
            --border-color: #e0e0e0;
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
            background-color: #f8f9fa;
            color: var(--text-color);
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 30px;
        }

        h1, h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .form-section {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: var(--secondary-color);
        }

        .form-section h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(74, 111, 220, 0.2);
        }

        .file-upload {
            position: relative;
            margin-bottom: 20px;
        }

        .file-upload label {
            display: block;
            padding: 15px;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .file-upload label:hover {
            background-color: #3658b8;
        }

        .file-upload input[type="file"] {
            position: absolute;
            width: 0;
            height: 0;
            opacity: 0;
        }

        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: #666;
        }

        .preview-container {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid var(--border-color);
            display: none;
        }

        .paragraph-container {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: white;
        }

        .paragraph-list {
            list-style: none;
            padding: 0;
            margin-bottom: 15px;
        }

        .paragraph-item {
            border: 1px solid var(--border-color);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
            background-color: white;
        }

        .paragraph-content {
            margin-bottom: 10px;
        }

        .paragraph-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #3658b8;
        }

        .btn-secondary {
            background-color: #e0e0e0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #d0d0d0;
        }

        .btn-danger {
            background-color: var(--error-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #388e3c;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
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
            margin: 20px 0;
        }

        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
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
            .container {
                padding: 15px;
            }
            
            .form-section {
                padding: 15px;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Style Guide</h1>
        
        <div id="notification" class="notification"></div>
        
        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Loading style guide...</p>
        </div>
        
        <form id="styleGuideForm" class="form-container">
            <input type="hidden" id="cardId" name="card_id">
            
            <!-- Card Information Section -->
            <div class="form-section">
                <h3>Card Information</h3>
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>
                
                <div class="form-group">
                    <label for="thumbnailImage">Thumbnail Image</label>
                    <div class="current-image">
                        <img id="currentThumbnail" alt="Current Thumbnail" style="max-width: 200px;">
                    </div>
                    <div class="file-upload">
                        <label for="thumbnailImage">Change Thumbnail Image</label>
                        <input type="file" id="thumbnailImage" name="thumbnailImage" accept="image/*">
                        <div class="file-name" id="thumbnailImageName">No file chosen</div>
                    </div>
                    <div class="preview-container">
                        <img id="thumbnailPreview" class="image-preview" alt="Thumbnail Preview">
                    </div>
                </div>
            </div>
            
            <!-- Content Information Section -->
            <div class="form-section">
                <h3>Content Information</h3>
                
                <div class="form-group">
                    <label for="heroImage">Hero Image</label>
                    <div class="current-image">
                        <img id="currentHero" alt="Current Hero Image" style="max-width: 200px;">
                    </div>
                    <div class="file-upload">
                        <label for="heroImage">Change Hero Image</label>
                        <input type="file" id="heroImage" name="heroImage" accept="image/*">
                        <div class="file-name" id="heroImageName">No file chosen</div>
                    </div>
                    <div class="preview-container">
                        <img id="heroPreview" class="image-preview" alt="Hero Image Preview">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="introText">Introduction Text *</label>
                    <textarea id="introText" name="introText" rows="4" required></textarea>
                </div>
                
                <!-- Main Content Paragraphs -->
                <div class="paragraph-container">
                    <h4>Main Content</h4>
                    <ul id="paragraphList" class="paragraph-list"></ul>
                    
                    <div class="form-group">
                        <label for="newParagraph">New Paragraph</label>
                        <textarea id="newParagraph" rows="4"></textarea>
                    </div>
                    
                    <button type="button" id="addParagraph" class="btn btn-secondary">Add Paragraph</button>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="style-guide-cards-display.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardId = new URLSearchParams(window.location.search).get('id');
            if (!cardId) {
                window.location.href = 'style-guide-cards-display.php';
                return;
            }

            // DOM Elements
            const form = document.getElementById('styleGuideForm');
            const loading = document.getElementById('loading');
            const notification = document.getElementById('notification');
            
            // Image preview functionality
            const thumbnailInput = document.getElementById('thumbnailImage');
            const thumbnailPreview = document.getElementById('thumbnailPreview');
            const thumbnailName = document.getElementById('thumbnailImageName');
            
            const heroInput = document.getElementById('heroImage');
            const heroPreview = document.getElementById('heroPreview');
            const heroName = document.getElementById('heroImageName');
            
            // Handle file selection for thumbnail
            thumbnailInput.addEventListener('change', function() {
                handleFileSelection(this, thumbnailPreview, thumbnailName);
            });
            
            // Handle file selection for hero image
            heroInput.addEventListener('change', function() {
                handleFileSelection(this, heroPreview, heroName);
            });
            
            // Function to handle file selection and preview
            function handleFileSelection(input, preview, nameElement) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                    nameElement.textContent = input.files[0].name;
                }
            }

            // Load style guide data
            async function loadStyleGuide() {
                try {
                    loading.style.display = 'block';
                    const response = await fetch(`/ecotours/admin/pages/styleguide/api/get_style_guide_details.php?id=${cardId}`);
                    const data = await response.json();
                    
                    if (!data.success) {
                        throw new Error(data.message);
                    }

                    // Populate form fields
                    document.getElementById('cardId').value = cardId;
                    document.getElementById('title').value = data.data.card.title;
                    document.getElementById('introText').value = data.data.content.intro_text;
                    
                    // Display current images
                    document.getElementById('currentThumbnail').src = data.data.card.thumbnail_image;
                    document.getElementById('currentHero').src = data.data.content.hero_image;
                    
                    // Load paragraphs
                    const mainContent = data.data.content.main_content;
                    if (Array.isArray(mainContent)) {
                        mainContent.forEach(text => {
                            if (text) addParagraph(text);
                        });
                    }
                    
                    loading.style.display = 'none';
                } catch (error) {
                    showNotification(error.message, 'error');
                    loading.style.display = 'none';
                }
            }

            // Handle image uploads
            async function uploadImage(file, type) {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('card_id', cardId);
                formData.append('image_type', type);

                try {
                    const response = await fetch('/ecotours/admin/pages/styleguide/api/update_style_guide_image.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    
                    if (!data.success) {
                        throw new Error(data.message);
                    }
                    
                    return data.data.image_path;
                } catch (error) {
                    throw new Error(`Failed to upload ${type} image: ${error.message}`);
                }
            }

            // Handle form submission
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                loading.style.display = 'block';

                try {
                    // Upload images if new ones are selected
                    if (thumbnailInput.files[0]) {
                        await uploadImage(thumbnailInput.files[0], 'thumbnail');
                    }
                    if (heroInput.files[0]) {
                        await uploadImage(heroInput.files[0], 'hero');
                    }

                    // Update style guide content
                    const formData = new FormData();
                    formData.append('card_id', cardId);
                    formData.append('title', document.getElementById('title').value);
                    formData.append('intro_text', document.getElementById('introText').value);
                    
                    // Collect paragraphs
                    const paragraphs = [];
                    document.querySelectorAll('input[name="paragraph[]"]').forEach(input => {
                        paragraphs.push(input.value);
                    });
                    formData.append('main_content', JSON.stringify(paragraphs));

                    const response = await fetch('/ecotours/admin/pages/styleguide/api/update_style_guide.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (!data.success) {
                        throw new Error(data.message);
                    }

                    showNotification('Style guide updated successfully', 'success');
                    setTimeout(() => {
                        window.location.href = 'style-guide-cards-display.php';
                    }, 1500);
                } catch (error) {
                    showNotification(error.message, 'error');
                } finally {
                    loading.style.display = 'none';
                }
            });

            // Load initial data
            loadStyleGuide();
            
            // Paragraph management code
            const paragraphList = document.getElementById('paragraphList');
            const newParagraphInput = document.getElementById('newParagraph');
            const addParagraphButton = document.getElementById('addParagraph');
            let paragraphCounter = 0;

            addParagraphButton.addEventListener('click', function() {
                const paragraphText = newParagraphInput.value.trim();
                
                if (paragraphText) {
                    addParagraph(paragraphText);
                    newParagraphInput.value = '';
                } else {
                    showNotification('Please enter paragraph text', 'error');
                }
            });

            function addParagraph(text) {
                paragraphCounter++;
                
                const li = document.createElement('li');
                li.className = 'paragraph-item';
                li.dataset.id = paragraphCounter;
                
                li.innerHTML = `
                    <div class="paragraph-content">${text}</div>
                    <div class="paragraph-actions">
                        <button type="button" class="btn btn-secondary btn-edit">Edit</button>
                        <button type="button" class="btn btn-danger btn-delete">Delete</button>
                    </div>
                    <input type="hidden" name="paragraph[]" value="${text}">
                `;
                
                paragraphList.appendChild(li);
                
                const editButton = li.querySelector('.btn-edit');
                const deleteButton = li.querySelector('.btn-delete');
                
                editButton.addEventListener('click', function() {
                    editParagraph(li);
                });
                
                deleteButton.addEventListener('click', function() {
                    deleteParagraph(li);
                });
            }

            function editParagraph(paragraphElement) {
                const content = paragraphElement.querySelector('.paragraph-content');
                const hiddenInput = paragraphElement.querySelector('input[type="hidden"]');
                const currentText = content.textContent;
                
                content.innerHTML = `<textarea rows="4" class="edit-textarea">${currentText}</textarea>`;
                const textarea = content.querySelector('.edit-textarea');
                
                const editButton = paragraphElement.querySelector('.btn-edit');
                editButton.textContent = 'Save';
                editButton.classList.remove('btn-secondary');
                editButton.classList.add('btn-primary');
                
                editButton.removeEventListener('click', function() {
                    editParagraph(paragraphElement);
                });
                
                editButton.addEventListener('click', function() {
                    const newText = textarea.value.trim();
                    
                    if (newText) {
                        content.textContent = newText;
                        hiddenInput.value = newText;
                        
                        editButton.textContent = 'Edit';
                        editButton.classList.remove('btn-primary');
                        editButton.classList.add('btn-secondary');
                        
                        editButton.addEventListener('click', function() {
                            editParagraph(paragraphElement);
                        });
                    } else {
                        showNotification('Paragraph text cannot be empty', 'error');
                    }
                });
                
                textarea.focus();
            }

            function deleteParagraph(paragraphElement) {
                if (confirm('Are you sure you want to delete this paragraph?')) {
                    paragraphElement.remove();
                }
            }

            // Notification function
            function showNotification(message, type) {
                notification.textContent = message;
                notification.className = 'notification';
                notification.classList.add(`notification-${type}`);
                notification.style.display = 'block';
                
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>
</html>
