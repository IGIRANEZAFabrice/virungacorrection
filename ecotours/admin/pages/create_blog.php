<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
    || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

if (!isset($_SESSION['admin_id'])) {
  if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Admin session expired. Please log in again.']);
    exit();
  }
  header('Location: login.html');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../handlers/blog/addRichBlogHandler.php';
    exit();
}

require_once __DIR__ . '/../config/connection.php';

// Fetch categories from DB
$categories_sql = "SELECT category_id, category_name, category_slug FROM blog_categories ORDER BY category_name ASC";
$categories_result = $conn->query($categories_sql);
$categories = [];
if ($categories_result && $categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}
if (empty($categories)) {
    // Fallback default categories if table is empty
    $categories = [
        ['category_slug' => 'lifestyle', 'category_name' => 'Lifestyle'],
        ['category_slug' => 'travel', 'category_name' => 'Travel & Tours'],
        ['category_slug' => 'wildlife', 'category_name' => 'Wildlife & Gorillas'],
        ['category_slug' => 'conservation', 'category_name' => 'Conservation & Nature'],
        ['category_slug' => 'community', 'category_name' => 'Community & Culture'],
        ['category_slug' => 'food', 'category_name' => 'Food & Hospitality'],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create New Blog Post - Virunga Ecotours</title>
    <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/create_blog.css?v=20260902-rich-modal-save" />
    <script src="../js/common.js" defer></script>
    <script src="../js/create_blog.js?v=20260902-rich-modal-save" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <!-- Sidebar Navigation -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>

        <div class="container">
          <!-- Page Header Row -->
          <div class="page-header-row">
            <div class="page-header-info">
              <h1><i class="fas fa-feather-alt"></i> Create New Blog Post</h1>
              <p>Compose an engaging eco-tourism article with rich media, quotes, and photo galleries.</p>
            </div>
            <a href="blogs.php" class="btn-secondary-outline">
              <i class="fas fa-arrow-left"></i> Back to Blog List
            </a>
          </div>

          <?php if (isset($_GET['status'])): ?>
            <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
              <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
              <span><?php echo $_GET['status'] === 'success' ? 'Changes saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred'); ?></span>
            </div>
          <?php endif; ?>

          <form id="blogForm" method="post" action="create_blog.php" enctype="multipart/form-data" class="blog-form-container">
            
            <!-- SECTION 1: Post Essentials & Cover Image -->
            <div class="card-section">
              <div class="card-section-header">
                <h3 class="card-section-title"><i class="fas fa-info-circle"></i> Post Essentials & Cover Image</h3>
              </div>
              <p class="card-section-subtitle">Provide key details and select an eye-catching featured cover image.</p>

              <div class="form-row">
                <!-- Left Column: Details -->
                <div class="form-col" style="flex: 1.2;">
                  <div class="form-group">
                    <label for="blogTitle">Blog Title <span class="required-badge">*</span></label>
                    <input
                      type="text"
                      id="blogTitle"
                      name="blogTitle"
                      placeholder="e.g., What Nobody Tells You Before Gorilla Trekking"
                      required
                    />
                  </div>

                  <div class="form-row" style="margin-bottom: 0;">
                    <div class="form-group" style="flex: 1;">
                      <label for="author">Author Name <span class="required-badge">*</span></label>
                      <input
                        type="text"
                        id="author"
                        name="author"
                        placeholder="e.g., Virunga Team or Author Name"
                        value="<?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Virunga Team'); ?>"
                        required
                      />
                    </div>
                    <div class="form-group" style="flex: 1;">
                      <label for="readMin">Read Time (minutes) <span class="required-badge">*</span></label>
                      <input
                        type="number"
                        id="readMin"
                        name="readMin"
                        min="1"
                        max="120"
                        value="5"
                        placeholder="5"
                        required
                      />
                    </div>
                  </div>

                  <div class="form-group" style="margin-top: 15px;">
                    <label for="category">Category <span class="required-badge">*</span></label>
                    <select id="category" name="category" required>
                      <option value="">Select a category</option>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['category_slug']); ?>">
                          <?php echo htmlspecialchars($cat['category_name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Right Column: Cover Image Upload & Live Preview -->
                <div class="form-col" style="flex: 1;">
                  <div class="cover-upload-container">
                    <label>Cover Featured Image <span class="required-badge">*</span> <span class="help-hint">(Recommended: 1200x630px)</span></label>
                    
                    <!-- Drag & Drop Zone -->
                    <div class="cover-dropzone" id="coverDropzone">
                      <input
                        type="file"
                        id="coverImage"
                        name="coverImage"
                        accept="image/*"
                        required
                      />
                      <i class="fas fa-cloud-upload-alt cover-dropzone-icon"></i>
                      <div class="cover-dropzone-title">Click or drag & drop cover image</div>
                      <div class="cover-dropzone-hint">Supports JPG, PNG, WEBP up to 10MB</div>
                    </div>

                    <!-- Live Image Preview Card -->
                    <div class="cover-preview-card" id="coverPreviewCard">
                      <img id="coverPreviewImg" src="" alt="Cover Image Preview" />
                      <div class="cover-preview-overlay">
                        <div class="cover-preview-info">
                          <i class="fas fa-check-circle" style="color: #4ade80;"></i>
                          <span id="coverFileName">Cover Image Selected</span>
                        </div>
                        <div class="cover-preview-actions">
                          <button type="button" class="btn-preview-action" id="btnChangeCover">
                            <i class="fas fa-sync-alt"></i> Change
                          </button>
                          <button type="button" class="btn-preview-action remove-btn" id="btnRemoveCover">
                            <i class="fas fa-trash-alt"></i> Remove
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SECTION 2: Lead Headline & Introduction -->
            <div class="card-section">
              <div class="card-section-header">
                <h3 class="card-section-title"><i class="fas fa-paragraph"></i> Lead Headline & Introduction</h3>
              </div>
              <p class="card-section-subtitle">Introduce the core subject and capture your readers' attention right away.</p>

              <div class="form-group">
                <label for="bigTitle">Main Headline <span class="required-badge">*</span></label>
                <input
                  type="text"
                  id="bigTitle"
                  name="bigTitle"
                  placeholder="e.g., An Unforgettable Journey Into The Heart of the Virunga Mountains"
                  required
                />
              </div>

              <div class="form-group">
                <label for="bigDescription">Introduction Content <span class="required-badge">*</span></label>
                <textarea
                  id="bigDescription"
                  name="bigDescription"
                  placeholder="Write an engaging introduction that sets the stage for this blog post..."
                  required
                ></textarea>
              </div>
            </div>

            <!-- SECTION 3: Dynamic Content Blocks -->
            <div class="card-section">
              <div class="content-blocks-header">
                <div>
                  <h3 class="card-section-title"><i class="fas fa-layer-group"></i> Article Content Blocks</h3>
                  <p class="card-section-subtitle" style="margin-top: 4px; margin-bottom: 0;">
                    Add paragraphs, captioned photos, pull quotes, or lists in any order.
                  </p>
                </div>
              </div>

              <!-- Dynamic Blocks Container -->
              <div class="content-blocks" id="contentBlocks">
                <!-- Dynamically generated block items will render here -->
              </div>

              <!-- Add Block Buttons Card -->
              <div class="add-block-buttons-card">
                <div class="add-block-prompt">Insert New Content Section:</div>
                <div class="add-block-buttons">
                  <button type="button" class="add-block-btn" data-block-type="text">
                    <i class="fas fa-font"></i> Add Text Block
                  </button>
                  <button type="button" class="add-block-btn" data-block-type="image">
                    <i class="fas fa-image"></i> Add Image Block
                  </button>
                  <button type="button" class="add-block-btn" data-block-type="quote">
                    <i class="fas fa-quote-left"></i> Add Quote Block
                  </button>
                  <button type="button" class="add-block-btn" data-block-type="list">
                    <i class="fas fa-list-ul"></i> Add List Block
                  </button>
                </div>
              </div>
            </div>

            <!-- SECTION 4: Gallery Images -->
            <div class="card-section">
              <div class="card-section-header">
                <h3 class="card-section-title"><i class="fas fa-images"></i> Photo Gallery</h3>
              </div>
              <p class="card-section-subtitle">Add up to 6 gallery photos that readers can browse at the bottom of the article.</p>

              <div class="gallery-grid" id="galleryGrid">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                  <div class="gallery-item" data-slot="<?php echo $i; ?>">
                    <div class="gallery-placeholder">
                      <i class="fas fa-camera"></i>
                      <span>Photo <?php echo $i; ?></span>
                    </div>
                    <input
                      type="file"
                      accept="image/*"
                      name="galleryImage<?php echo $i; ?>"
                      class="gallery-upload"
                      data-slot="<?php echo $i; ?>"
                    />
                    <img class="gallery-preview" alt="Gallery Photo <?php echo $i; ?>" />
                    <button type="button" class="remove-gallery-image" title="Remove Photo">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                <?php endfor; ?>
              </div>
            </div>

            <!-- SECTION 5: Submit Actions Bar -->
            <div class="form-actions-bar">
              <a href="blogs.php" class="btn-secondary-outline">
                <i class="fas fa-times"></i> Cancel
              </a>
              <button type="submit" class="submit-btn" id="submitBlogBtn">
                <i class="fas fa-paper-plane"></i> Publish Blog Post
              </button>
            </div>

          </form>
        </div>
      </main>
    </div>

    <!-- Container for dynamic toast notifications -->
    <div id="toast-container"></div>
  </body>
</html>

