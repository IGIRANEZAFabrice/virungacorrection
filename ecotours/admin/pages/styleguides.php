<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Style Guides Management - Virunga Ecotours Admin</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <script src="../js/common.js" defer></script>
    <style>
        /* Style Guides Management Styles */

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1rem 2rem;
    border-bottom: 2px solid var(--neutral-beige);
}

.page-header h1 {
    color: var(--primary-green);
    font-size: 2rem;
    margin: 0;
}

.page-header h1 i {
    margin-right: 0.5rem;
}

/* Guides Grid */
.guides-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1rem;
    padding: 1rem 2rem;
}

.guide-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.guide-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.guide-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.guide-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.guide-card:hover .guide-image img {
    transform: scale(1.05);
}

.guide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(42, 72, 88, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.guide-card:hover .guide-overlay {
    opacity: 1;
}

.guide-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: white;
    color: var(--primary-green);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 1rem;
}

.btn-action:hover {
    background: var(--primary-green);
    color: white;
    transform: scale(1.1);
}

.btn-action.delete:hover {
    background: var(--accent-terracotta);
}

.content-indicator {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    background: var(--primary-green);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
}

.guide-info {
    padding: 1rem;
}

.guide-info h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-dark);
    font-size: 1.1rem;
    line-height: 1.3;
}

.guide-date, .guide-updated {
    margin: 0.25rem 0;
    font-size: 0.85rem;
    color: var(--text-medium);
}

.guide-updated {
    color: var(--primary-green);
    font-weight: 500;
}

/* Empty and Error States */
.empty-state, .error-state {
    text-align: center;
    padding: 3rem;
    color: var(--text-medium);
    grid-column: 1 / -1;
}

.empty-state i, .error-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: var(--neutral-beige);
}

.empty-state h3, .error-state h3 {
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.modal-content.large {
    max-width: 800px;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--neutral-beige);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--neutral-cream);
    border-radius: 12px 12px 0 0;
}

.modal-header h2 {
    margin: 0;
    color: var(--primary-green);
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-medium);
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: var(--accent-terracotta);
    color: white;
}

/* Form Styles */
form {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-dark);
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--neutral-beige);
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-green);
}

.form-group small {
    display: block;
    margin-top: 0.25rem;
    color: var(--text-medium);
    font-size: 0.85rem;
}

/* File Upload */
.file-upload-area {
    border: 2px dashed var(--neutral-beige);
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.file-upload-area:hover {
    border-color: var(--primary-green);
    background: var(--neutral-light);
}

.file-upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-preview {
    color: var(--text-medium);
}

.file-preview i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.file-preview img {
    max-width: 100%;
    max-height: 150px;
    border-radius: 6px;
    margin-bottom: 0.5rem;
}

/* Content Editor */
.content-editor {
    border: 1px solid var(--neutral-beige);
    border-radius: 8px;
    overflow: hidden;
}

.editor-toolbar {
    background: var(--neutral-cream);
    padding: 0.75rem;
    border-bottom: 1px solid var(--neutral-beige);
    display: flex;
    gap: 0.5rem;
}

.btn-tool {
    padding: 0.5rem 1rem;
    background: var(--primary-green);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.3s ease;
}

.btn-tool:hover {
    background: var(--accent-sage);
}

.content-sections {
    padding: 1rem;
    min-height: 200px;
}

.content-section {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid var(--neutral-beige);
    border-radius: 6px;
    position: relative;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.section-type {
    font-size: 0.85rem;
    color: var(--primary-green);
    font-weight: 500;
}

.section-remove {
    background: var(--accent-terracotta);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    cursor: pointer;
    font-size: 0.8rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--neutral-beige);
}

/* Buttons */
.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-green);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-sage);
    transform: translateY(-2px);
}

.btn-secondary {
    background: var(--neutral-beige);
    color: var(--text-dark);
}

.btn-secondary:hover {
    background: var(--accent-terracotta);
    color: white;
}

/* Content Editor */
.content-editor {
    border: 1px solid var(--neutral-beige);
    border-radius: 8px;
    overflow: hidden;
}

.editor-toolbar {
    background: var(--neutral-cream);
    padding: 0.75rem;
    border-bottom: 1px solid var(--neutral-beige);
    display: flex;
    gap: 0.5rem;
}

.btn-tool {
    padding: 0.5rem 1rem;
    background: var(--primary-green);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background 0.3s ease;
}

.btn-tool:hover {
    background: var(--accent-sage);
}

.content-sections {
    padding: 1rem;
    min-height: 200px;
}

.content-section {
    margin-bottom: 1rem;
    padding: 1rem;
    border: 1px solid var(--neutral-beige);
    border-radius: 6px;
    position: relative;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.section-type {
    font-size: 0.85rem;
    color: var(--primary-green);
    font-weight: 500;
}

.section-remove {
    background: var(--accent-terracotta);
    color: white;
    border: none;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
    cursor: pointer;
    font-size: 0.8rem;
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--neutral-beige);
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .guides-grid {
        grid-template-columns: 1fr;
    }

    .modal-content {
        width: 95%;
        margin: 1rem;
    }

    .form-actions {
        flex-direction: column;
    }
}

    </style>
</head>
<body>
    <div class="admin-container">
        <?php include('./includes/sidebar.php'); ?>
        
        <main class="main-content">
            <!-- Include header template -->
            <?php include_once './includes/header.php'; ?>

            <div class="content-wrapper">
                <div class="page-header">
                    <h1><i class="fas fa-book-open"></i> Style Guides Management</h1>
                    <button class="btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Add New Style Guide
                    </button>
                </div>

                <!-- Style Guides Grid -->
                <div class="guides-grid" id="guidesGrid">
                    <?php
                    require_once('../config/database.php');
                    
                    try {
                        $stmt = $pdo->prepare("
                            SELECT sg.card_id, sg.title, sg.thumbnail_image, sg.created_at,
                                   sc.intro_text, sc.updated_at
                            FROM styleguide_cards sg
                            LEFT JOIN styleguide_content sc ON sg.card_id = sc.card_id
                            ORDER BY sg.created_at DESC
                        ");
                        $stmt->execute();
                        $guides = $stmt->fetchAll();

                        if (empty($guides)) {
                            echo '<div class="empty-state">
                                    <i class="fas fa-book-open"></i>
                                    <h3>No Style Guides Yet</h3>
                                    <p>Create your first style guide to get started</p>
                                  </div>';
                        } else {
                            foreach ($guides as $guide) {
                                $thumbnail = $guide['thumbnail_image'] ? '../images/style-guide/' . $guide['thumbnail_image'] : '../images/default-guide.jpg';
                                $hasContent = !empty($guide['intro_text']);
                                
                                echo '
                                <div class="guide-card" data-id="' . $guide['card_id'] . '">
                                    <div class="guide-image">
                                        <img src="' . htmlspecialchars($thumbnail) . '" alt="' . htmlspecialchars($guide['title']) . '">
                                        <div class="guide-overlay">
                                            <div class="guide-actions">
                                                <button class="btn-action edit" onclick="editGuide(' . $guide['card_id'] . ')" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn-action content" onclick="manageContent(' . $guide['card_id'] . ')" title="Manage Content">
                                                    <i class="fas fa-file-text"></i>
                                                </button>
                                                <button class="btn-action delete" onclick="deleteGuide(' . $guide['card_id'] . ')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        ' . ($hasContent ? '<div class="content-indicator"><i class="fas fa-check"></i></div>' : '') . '
                                    </div>
                                    <div class="guide-info">
                                        <h3>' . htmlspecialchars($guide['title']) . '</h3>
                                        <p class="guide-date">Created: ' . date('M j, Y', strtotime($guide['created_at'])) . '</p>
                                        ' . ($guide['updated_at'] ? '<p class="guide-updated">Updated: ' . date('M j, Y', strtotime($guide['updated_at'])) . '</p>' : '') . '
                                    </div>
                                </div>';
                            }
                        }
                    } catch(PDOException $e) {
                        echo '<div class="error-state">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Error Loading Style Guides</h3>
                                <p>Please try again later</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Guide Modal -->
    <div id="guideModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Style Guide</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="guideForm" enctype="multipart/form-data">
                <input type="hidden" id="guideId" name="guide_id">
                
                <div class="form-group">
                    <label for="guideTitle">Title *</label>
                    <input type="text" id="guideTitle" name="title" required maxlength="255">
                </div>

                <div class="form-group">
                    <label for="guideThumbnail">Thumbnail Image</label>
                    <div class="file-upload-area">
                        <input type="file" id="guideThumbnail" name="thumbnail" accept="image/*">
                        <div class="file-preview" id="thumbnailPreview">
                            <i class="fas fa-image"></i>
                            <p>Click to upload thumbnail</p>
                        </div>
                    </div>
                    <small>Recommended size: 400x300px, max 2MB</small>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Guide
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Content Management Modal -->
    <div id="contentModal" class="modal">
        <div class="modal-content large">
            <div class="modal-header">
                <h2 id="contentModalTitle">Manage Content</h2>
                <button class="modal-close" onclick="closeContentModal()">&times;</button>
            </div>
            <form id="contentForm">
                <input type="hidden" id="contentGuideId" name="guide_id">
                
                <div class="form-group">
                    <label for="heroImage">Hero Image</label>
                    <div class="file-upload-area">
                        <input type="file" id="heroImage" name="hero_image" accept="image/*">
                        <div class="file-preview" id="heroPreview">
                            <i class="fas fa-image"></i>
                            <p>Click to upload hero image</p>
                        </div>
                    </div>
                    <small>Recommended size: 1200x600px, max 5MB</small>
                </div>

                <div class="form-group">
                    <label for="introText">Introduction Text</label>
                    <textarea id="introText" name="intro_text" rows="4" placeholder="Brief introduction to the style guide..."></textarea>
                </div>

                <div class="form-group">
                    <label for="mainContent">Main Content</label>
                    <div class="content-editor">
                        <div class="editor-toolbar">
                            <button type="button" onclick="addSection()" class="btn-tool">
                                <i class="fas fa-plus"></i> Add Section
                            </button>
                            <button type="button" onclick="addList()" class="btn-tool">
                                <i class="fas fa-list"></i> Add List
                            </button>
                        </div>
                        <div id="contentSections" class="content-sections">
                            <!-- Dynamic content sections will be added here -->
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeContentModal()">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Content
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="../js/styleguides.js"></script>
</body>
</html>
