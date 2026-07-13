<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Guide Upload Form</title>
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
            width: 100%;
            /* margin: 5px auto; */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 30px;
        }

        h1,
        h2 {
            color: var(--primary-green);
            margin-bottom: 20px;
            text-align: center;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .form-section {
            border: 1px solid var(--neutral-beige);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: var(--neutral-cream);
        }

        .form-section h3 {
            margin-bottom: 15px;
            color: var(--primary-green);
            border-bottom: 1px solid var(--neutral-beige);
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
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 2px rgba(42, 72, 88, 0.2);
        }

        .file-upload {
            position: relative;
            margin-bottom: 20px;
        }

        .file-upload label {
            display: block;
            padding: 15px;
            background-color: var(--primary-green);
            color: var(--text-light);
            text-align: center;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .file-upload label:hover {
            background-color: var(--accent-sage);
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
            color: var(--text-medium);
        }

        .preview-container {
            margin-top: 10px;
            text-align: center;
        }

        .image-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 4px;
            border: 1px solid var(--neutral-beige);
            display: none;
        }

        .paragraph-container {
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: var(--neutral-light);
        }

        .paragraph-list {
            list-style: none;
            padding: 0;
            margin-bottom: 15px;
        }

        .paragraph-item {
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 10px;
            position: relative;
            background-color: var(--neutral-light);
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
            background-color: var(--primary-green);
            color: var(--text-light);
        }

        .btn-primary:hover {
            background-color: var(--accent-sage);
        }

        .btn-secondary {
            background-color: var(--accent-terracotta);
            color: var(--text-light);
        }

        .btn-secondary:hover {
            background-color: var(--primary-brown);
        }

        .btn-danger {
            background-color: var(--error-color);
            color: var(--text-light);
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .btn-success {
            background-color: var(--success-color);
            color: var(--text-light);
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
            border: 4px solid var(--neutral-cream);
            border-top: 4px solid var(--primary-green);
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
        <h1>Style Guide Upload Form</h1>

        <div id="notification" class="notification"></div>

        <div class="loading" id="loading">
            <div class="loading-spinner"></div>
            <p>Processing your submission...</p>
        </div>

        <form id="styleGuideForm" class="form-container">
            <!-- Card Information Section -->
            <div class="form-section">
                <h3>Card Information</h3>
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="form-group">
                    <label for="thumbnailImage">Thumbnail Image *</label>
                    <div class="file-upload">
                        <label for="thumbnailImage">Choose Thumbnail Image</label>
                        <input type="file" id="thumbnailImage" name="thumbnailImage" accept="image/*" required>
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
                    <label for="heroImage">Hero Image *</label>
                    <div class="file-upload">
                        <label for="heroImage">Choose Hero Image</label>
                        <input type="file" id="heroImage" name="heroImage" accept="image/*" required>
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
                    <p>Add paragraphs to build your main content</p>

                    <ul id="paragraphList" class="paragraph-list">
                        <!-- Paragraphs will be added here dynamically -->
                    </ul>

                    <div class="form-group">
                        <label for="newParagraph">New Paragraph</label>
                        <textarea id="newParagraph" rows="4"></textarea>
                    </div>

                    <button type="button" id="addParagraph" class="btn btn-secondary">Add Paragraph</button>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" id="resetForm" class="btn btn-secondary">Reset Form</button>
                <button type="submit" class="btn btn-success">Submit Style Guide</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // DOM Elements
            const form = document.getElementById('styleGuideForm');
            const paragraphList = document.getElementById('paragraphList');
            const newParagraphInput = document.getElementById('newParagraph');
            const addParagraphButton = document.getElementById('addParagraph');
            const resetFormButton = document.getElementById('resetForm');
            const notification = document.getElementById('notification');
            const loading = document.getElementById('loading');

            // Image preview functionality
            const thumbnailInput = document.getElementById('thumbnailImage');
            const thumbnailPreview = document.getElementById('thumbnailPreview');
            const thumbnailName = document.getElementById('thumbnailImageName');

            const heroInput = document.getElementById('heroImage');
            const heroPreview = document.getElementById('heroPreview');
            const heroName = document.getElementById('heroImageName');

            let paragraphCounter = 0;

            // Handle file selection for thumbnail
            thumbnailInput.addEventListener('change', function () {
                handleFileSelection(this, thumbnailPreview, thumbnailName);
            });

            // Handle file selection for hero image
            heroInput.addEventListener('change', function () {
                handleFileSelection(this, heroPreview, heroName);
            });

            // Function to handle file selection and preview
            function handleFileSelection(input, preview, nameElement) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };

                    reader.readAsDataURL(input.files[0]);
                    nameElement.textContent = input.files[0].name;
                }
            }

            // Add paragraph functionality
            addParagraphButton.addEventListener('click', function () {
                const paragraphText = newParagraphInput.value.trim();

                if (paragraphText) {
                    addParagraph(paragraphText);
                    newParagraphInput.value = '';
                } else {
                    showNotification('Please enter paragraph text', 'error');
                }
            });

            // Function to add a paragraph to the list
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

                // Add event listeners for the new paragraph's buttons
                const editButton = li.querySelector('.btn-edit');
                const deleteButton = li.querySelector('.btn-delete');

                editButton.addEventListener('click', function () {
                    editParagraph(li);
                });

                deleteButton.addEventListener('click', function () {
                    deleteParagraph(li);
                });
            }

            // Function to edit a paragraph
            function editParagraph(paragraphElement) {
                const content = paragraphElement.querySelector('.paragraph-content');
                const hiddenInput = paragraphElement.querySelector('input[type="hidden"]');
                const currentText = content.textContent;

                // Replace the content with a textarea
                content.innerHTML = `<textarea rows="4" class="edit-textarea">${currentText}</textarea>`;
                const textarea = content.querySelector('.edit-textarea');

                // Change the Edit button to Save
                const editButton = paragraphElement.querySelector('.btn-edit');
                editButton.textContent = 'Save';
                editButton.classList.remove('btn-secondary');
                editButton.classList.add('btn-primary');

                // Change the event listener for the button
                editButton.removeEventListener('click', function () {
                    editParagraph(paragraphElement);
                });

                editButton.addEventListener('click', function () {
                    const newText = textarea.value.trim();

                    if (newText) {
                        content.textContent = newText;
                        hiddenInput.value = newText;

                        // Reset the button
                        editButton.textContent = 'Edit';
                        editButton.classList.remove('btn-primary');
                        editButton.classList.add('btn-secondary');

                        // Add the original event listener back
                        editButton.addEventListener('click', function () {
                            editParagraph(paragraphElement);
                        });
                    } else {
                        showNotification('Paragraph text cannot be empty', 'error');
                    }
                });

                // Focus the textarea
                textarea.focus();
            }

            // Function to delete a paragraph
            function deleteParagraph(paragraphElement) {
                if (confirm('Are you sure you want to delete this paragraph?')) {
                    paragraphElement.remove();
                }
            }

            // Form submission
            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                if (!validateForm()) {
                    return;
                }

                loading.style.display = 'block';

                try {
                    const formData = new FormData(form);
                    
                    // Add paragraphs as array
                    const paragraphs = [];
                    document.querySelectorAll('input[name="paragraph[]"]').forEach(function (input) {
                        paragraphs.push(input.value);
                    });
                    formData.append('paragraphs', JSON.stringify(paragraphs));

                    const response = await fetch('api/upload.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification(result.message, 'success');
                        form.reset();
                        paragraphList.innerHTML = '';
                        thumbnailPreview.style.display = 'none';
                        heroPreview.style.display = 'none';
                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    showNotification(error.message, 'error');
                } finally {
                    loading.style.display = 'none';
                }
            });

            // Reset form functionality
            resetFormButton.addEventListener('click', function () {
                if (confirm('Are you sure you want to reset the form? All entered data will be lost.')) {
                    form.reset();
                    paragraphList.innerHTML = '';
                    thumbnailPreview.style.display = 'none';
                    thumbnailName.textContent = 'No file chosen';
                    heroPreview.style.display = 'none';
                    heroName.textContent = 'No file chosen';
                    paragraphCounter = 0;
                }
            });

            // Function to validate the form
            function validateForm() {
                // Check if title is provided
                if (!document.getElementById('title').value.trim()) {
                    showNotification('Please enter a title', 'error');
                    return false;
                }

                // Check if thumbnail image is provided
                if (!thumbnailInput.files || !thumbnailInput.files[0]) {
                    showNotification('Please select a thumbnail image', 'error');
                    return false;
                }

                // Check if hero image is provided
                if (!heroInput.files || !heroInput.files[0]) {
                    showNotification('Please select a hero image', 'error');
                    return false;
                }

                // Check if intro text is provided
                if (!document.getElementById('introText').value.trim()) {
                    showNotification('Please enter introduction text', 'error');
                    return false;
                }

                // Check if at least one paragraph is added
                if (paragraphList.children.length === 0) {
                    showNotification('Please add at least one paragraph to the main content', 'error');
                    return false;
                }

                return true;
            }

            // Function to show notification
            function showNotification(message, type) {
                notification.textContent = message;
                notification.className = 'notification';
                notification.classList.add(`notification-${type}`);
                notification.style.display = 'block';

                setTimeout(function () {
                    notification.style.display = 'none';
                }, 5000);
            }
        });
    </script>
</body>

</html>