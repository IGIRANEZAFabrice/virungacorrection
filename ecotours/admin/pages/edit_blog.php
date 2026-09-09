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

// Process POST form submissions directly on edit_blog.php to bypass Hostinger/WAF handler blocks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../handlers/blog/updateBlogHandler.php';
    exit();
}

require_once __DIR__ . '/../config/connection.php';

// Get blog post ID from URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['blog_id']) ? intval($_POST['blog_id']) : 0);

if ($post_id <= 0) {
    header('Location: blogs.php?error=invalid_id');
    exit();
}

// Fetch blog post details along with category name
$post_sql = "SELECT bp.*, bc.category_name
             FROM blog_posts bp
             JOIN blog_categories bc ON bp.category_id = bc.category_id
             WHERE bp.blog_id = ?";
$post_stmt = $conn->prepare($post_sql);
$post_stmt->bind_param("i", $post_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

if ($post_result->num_rows === 0) {
  // Blog post not found
  header('Location: blogs.php?error=not_found');
  exit();
}

$post = $post_result->fetch_assoc();
$post_stmt->close();

// Fetch all categories for the dropdown
$categories_sql = "SELECT category_id, category_name, category_slug FROM blog_categories ORDER BY category_name ASC"; // Added category_slug
$categories_result = $conn->query($categories_sql);
$categories = [];
if ($categories_result->num_rows > 0) {
    while ($row = $categories_result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch content blocks for this post
$blocks_sql = "SELECT * FROM blog_content_blocks WHERE blog_id = ? ORDER BY block_order ASC";
$blocks_stmt = $conn->prepare($blocks_sql);
$blocks_stmt->bind_param("i", $post_id);
$blocks_stmt->execute();
$blocks_result = $blocks_stmt->get_result();

$content_blocks = [];
while ($block = $blocks_result->fetch_assoc()) {
    $block_id = $block['block_id'];
    $block_type = $block['block_type'];
    $block_details = null;

    // Fetch specific block details based on type
    switch ($block_type) {
        case 'text':
            $detail_sql = "SELECT * FROM blog_text_blocks WHERE block_id = ?";
            break;
        case 'image':
            $detail_sql = "SELECT * FROM blog_image_blocks WHERE block_id = ?";
            break;
        case 'quote':
            $detail_sql = "SELECT * FROM blog_quote_blocks WHERE block_id = ?";
            break;
        case 'list':
            $detail_sql = "SELECT * FROM blog_list_blocks WHERE block_id = ?";
            break;
        default:
            $detail_sql = null;
    }

    if ($detail_sql) {
        $detail_stmt = $conn->prepare($detail_sql);
        $detail_stmt->bind_param("i", $block_id);
        $detail_stmt->execute();
        $detail_result = $detail_stmt->get_result();
        if ($detail_result->num_rows > 0) {
            $block_details = $detail_result->fetch_assoc();
            // If it's a list, fetch list items
            if ($block_type === 'list' && $block_details) {
                $list_items_sql = "SELECT * FROM blog_list_items WHERE list_block_id = ? ORDER BY item_order ASC";
                $list_items_stmt = $conn->prepare($list_items_sql);
                $list_items_stmt->bind_param("i", $block_details['list_block_id']);
                $list_items_stmt->execute();
                $list_items_result = $list_items_stmt->get_result();
                $block_details['items'] = [];
                while ($item = $list_items_result->fetch_assoc()) {
                    $block_details['items'][] = $item;
                }
                $list_items_stmt->close();
            }
        }
        $detail_stmt->close();
    }

    // Combine base block info with detailed info
    $block['details'] = $block_details;
    $content_blocks[] = $block;
}
$blocks_stmt->close();


// Fetch gallery images for this post
$gallery_sql = "SELECT * FROM blog_gallery_images WHERE blog_id = ? ORDER BY image_order ASC"; // Corrected table name
$gallery_stmt = $conn->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $post_id);
$gallery_stmt->execute();
$gallery_result = $gallery_stmt->get_result();

$gallery_images = [];
while ($image = $gallery_result->fetch_assoc()) {
  $gallery_images[] = $image;
}
$gallery_stmt->close();

// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Blog Post - Virunga Ecotours</title>
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
    <link rel="stylesheet" href="../css/create_blog.css" />
    <script src="../js/common.js" defer></script>
    <script src="../js/edit_blog.js" defer></script>
  </head>
  <body class="edit-blog-page">
    <div class="admin-container">
      <!-- Sidebar Navigation -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>

        <div class="container">
          <!-- Page Header Row -->
          <div class="page-header-row edit-page-header">
            <div class="page-header-info">
              <h1><i class="fas fa-edit"></i> Edit Blog Post</h1>
              <p>Editing: <strong><?php echo htmlspecialchars(stripslashes($post['title'])); ?></strong></p>
            </div>
            <div class="page-header-actions">
              <a href="view_blog.php?id=<?php echo $post_id; ?>" class="btn-secondary-outline" target="_blank">
                <i class="fas fa-eye"></i> View Post
              </a>
              <a href="blogs.php" class="btn-secondary-outline">
                <i class="fas fa-arrow-left"></i> Back to Blog List
              </a>
            </div>
          </div>

          <?php if (isset($_GET['status'])): ?>
            <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
              <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
              <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] === 'success' ? 'Changes saved successfully!' : 'An error occurred')); ?></span>
            </div>
          <?php endif; ?>

          <form id="blogForm" method="post" action="edit_blog.php?id=<?php echo $post_id; ?>" enctype="multipart/form-data" class="blog-form-container">
            <!-- Hidden Post ID -->
            <input type="hidden" name="blog_id" value="<?php echo $post_id; ?>">

            <!-- SECTION 1: Post Essentials & Cover Image -->
            <div class="card-section edit-hero-section">
              <div class="card-section-header">
                <h3 class="card-section-title"><i class="fas fa-info-circle"></i> Post Essentials & Cover Image</h3>
              </div>

              <div class="edit-hero-grid">
                <!-- Left Column: Details -->
                <div class="edit-details-panel">
                  <p class="card-section-subtitle">Update the story details, reading time, category, and featured hero image.</p>
                  <div class="form-group">
                    <label for="blogTitle">Blog Title <span class="required-badge">*</span></label>
                    <input
                      type="text"
                      id="blogTitle"
                      name="blogTitle"
                      placeholder="Enter blog title"
                      value="<?php echo htmlspecialchars(stripslashes($post['title'])); ?>"
                      required
                    />
                  </div>

                  <div class="form-row compact-form-row">
                    <div class="form-group">
                      <label for="author">Author Name <span class="required-badge">*</span></label>
                      <input
                        type="text"
                        id="author"
                        name="author"
                        placeholder="Enter author name"
                        value="<?php echo htmlspecialchars(stripslashes($post['author'])); ?>"
                        required
                      />
                    </div>
                    <div class="form-group">
                      <label for="readMin">Read Time (minutes) <span class="required-badge">*</span></label>
                      <input
                        type="number"
                        id="readMin"
                        name="readMin"
                        min="1"
                        max="120"
                        placeholder="Estimated read time"
                        value="<?php echo htmlspecialchars($post['read_minutes']); ?>"
                        required
                      />
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="category">Category <span class="required-badge">*</span></label>
                    <select id="category" name="category" required>
                      <option value="">Select a category</option>
                      <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['category_slug']); ?>" <?php echo ($post['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                          <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <!-- Right Column: Cover Image Upload & Live Preview -->
                <div class="edit-cover-panel">
                  <div class="cover-upload-container">
                    <label>Hero Featured Image <span class="help-hint">(Shown at the top of the article)</span></label>
                    
                    <!-- Drag & Drop Zone -->
                    <div class="cover-dropzone" id="coverDropzone" style="<?php echo !empty($post['cover_image']) ? 'display: none;' : ''; ?>">
                      <input
                        type="file"
                        id="coverImage"
                        name="coverImage"
                        accept="image/jpeg,image/png,image/webp,image/gif"
                      />
                      <i class="fas fa-cloud-upload-alt cover-dropzone-icon"></i>
                      <div class="cover-dropzone-title">Click or drag a hero image</div>
                      <div class="cover-dropzone-hint">Supports JPG, PNG, WEBP up to 10MB</div>
                    </div>

                    <!-- Live Image Preview Card -->
                    <div class="cover-preview-card <?php echo !empty($post['cover_image']) ? 'active' : ''; ?>" id="coverPreviewCard">
                      <img
                        id="coverPreviewImg"
                        src="<?php echo !empty($post['cover_image']) ? '../images/blog/covers/' . htmlspecialchars($post['cover_image']) : ''; ?>"
                        alt="Cover Image Preview"
                      />
                      <div class="cover-preview-overlay">
                        <div class="cover-preview-info">
                          <i class="fas fa-image" style="color: #4ade80;"></i>
                          <span id="coverFileName"><?php echo htmlspecialchars($post['cover_image'] ?? 'Current Cover Image'); ?></span>
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
                    <!-- Hidden input to keep track of existing image -->
                    <input type="hidden" id="existingCoverImage" name="existing_cover_image" value="<?php echo htmlspecialchars($post['cover_image'] ?? ''); ?>">
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
                  placeholder="Enter main headline"
                  value="<?php echo htmlspecialchars(stripslashes($post['main_headline'])); ?>"
                  required
                />
              </div>

              <div class="form-group">
                <label for="bigDescription">Introduction Content <span class="required-badge">*</span></label>
                <textarea
                  id="bigDescription"
                  name="bigDescription"
                  placeholder="Write an introduction for your blog post"
                  required
                ><?php echo htmlspecialchars(stripslashes($post['introduction'])); ?></textarea>
              </div>
            </div>

            <!-- SECTION 3: Dynamic Content Blocks -->
            <div class="card-section">
              <div class="content-blocks-header">
                <div>
                  <h3 class="card-section-title"><i class="fas fa-layer-group"></i> Article Content Blocks</h3>
                  <p class="card-section-subtitle" style="margin-top: 4px; margin-bottom: 0;">
                    Manage paragraphs, captioned photos, quotes, and lists. Use reorder buttons to organize.
                  </p>
                </div>
              </div>

              <!-- Content Blocks Container -->
              <div class="content-blocks" id="contentBlocks">
                <?php
                $blockCounter = 1;
                foreach ($content_blocks as $index => $block):
                  $blockType = $block['block_type'];
                  $blockDetails = $block['details'];

                  $typeIcon = 'fa-font';
                  $typeName = 'Text Block';
                  if ($blockType === 'image') { $typeIcon = 'fa-image'; $typeName = 'Image Block'; }
                  if ($blockType === 'quote') { $typeIcon = 'fa-quote-left'; $typeName = 'Quote Block'; }
                  if ($blockType === 'list') { $typeIcon = 'fa-list-ul'; $typeName = 'List Block'; }
                ?>
                <div class="content-block" data-block-type="<?php echo $blockType; ?>" data-block-id="<?php echo $blockCounter; ?>">
                  <div class="block-header">
                    <span class="block-badge">
                      <i class="fas <?php echo $typeIcon; ?>"></i> <span class="block-title-text"><?php echo $typeName . ' ' . $blockCounter; ?></span>
                    </span>
                    <div class="block-header-controls">
                      <button type="button" class="btn-block-action move-up-block" title="Move Up"><i class="fas fa-arrow-up"></i></button>
                      <button type="button" class="btn-block-action move-down-block" title="Move Down"><i class="fas fa-arrow-down"></i></button>
                      <button type="button" class="btn-block-action remove-block" title="Delete Block"><i class="fas fa-trash-alt"></i></button>
                    </div>
                  </div>

                  <!-- Hidden inputs for backend update matching -->
                  <input type="hidden" name="block_id[]" value="<?php echo $block['block_id']; ?>">
                  <input type="hidden" name="block_type[]" value="<?php echo $blockType; ?>">
                  <input type="hidden" name="block_order[]" value="<?php echo $index + 1; ?>">

                  <?php if ($blockType === 'text'): ?>
                    <div class="form-group">
                      <label for="blockTitle<?php echo $blockCounter; ?>">Section Subheading <span class="help-hint">(Optional)</span></label>
                      <input
                        type="text"
                        id="blockTitle<?php echo $blockCounter; ?>"
                        name="blockTitle[]"
                        placeholder="Enter section subheading"
                        value="<?php echo htmlspecialchars(stripslashes($blockDetails['section_title'] ?? '')); ?>"
                      />
                    </div>
                    <div class="form-group">
                      <label for="blockContent<?php echo $blockCounter; ?>">Section Paragraph Content <span class="required-badge">*</span></label>
                      <textarea
                        id="blockContent<?php echo $blockCounter; ?>"
                        name="blockContent[]"
                        placeholder="Write your content here"
                      ><?php echo htmlspecialchars(stripslashes($blockDetails['content'] ?? '')); ?></textarea>
                    </div>

                  <?php elseif ($blockType === 'image'): ?>
                    <div class="form-row" style="margin-bottom: 12px;">
                      <div class="form-group" style="flex: 2;">
                        <label for="blockImageCaption<?php echo $blockCounter; ?>">Image Caption <span class="help-hint">(Optional)</span></label>
                        <input
                          type="text"
                          id="blockImageCaption<?php echo $blockCounter; ?>"
                          name="blockImageCaption[]"
                          placeholder="Enter image caption"
                          value="<?php echo htmlspecialchars(stripslashes($blockDetails['caption'] ?? '')); ?>"
                        />
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Photo <span class="help-hint">(Click to replace)</span></label>
                      <div class="block-image-upload-wrapper">
                        <div class="block-image-dropzone">
                          <input
                            type="file"
                            id="blockImage<?php echo $blockCounter; ?>"
                            name="blockImage[]"
                            accept="image/*"
                            class="block-image-input"
                            data-block-id="<?php echo $blockCounter; ?>"
                          />
                          <i class="fas fa-image"></i>
                          <span>Click or drop new photo to replace</span>
                        </div>
                        
                        <!-- CONSTRAINED IMAGE PREVIEW CONTAINER -->
                        <div class="image-preview" id="imagePreview<?php echo $blockCounter; ?>">
                          <?php if (!empty($blockDetails['image_path'])): ?>
                            <div class="block-preview-box">
                              <div class="block-preview-img-container">
                                <img src="../images/blog/content/<?php echo htmlspecialchars($blockDetails['image_path']); ?>" alt="Image Block Preview" />
                              </div>
                              <div class="block-preview-footer">
                                <span><i class="fas fa-check-circle" style="color:#2e7d32;"></i> <?php echo htmlspecialchars($blockDetails['image_path']); ?></span>
                              </div>
                            </div>
                          <?php endif; ?>
                        </div>
                        <input type="hidden" name="existing_block_image[]" value="<?php echo htmlspecialchars($blockDetails['image_path'] ?? ''); ?>">
                      </div>
                    </div>

                  <?php elseif ($blockType === 'quote'): ?>
                    <div class="form-group">
                      <label for="blockQuote<?php echo $blockCounter; ?>">Quote Text <span class="required-badge">*</span></label>
                      <textarea
                        id="blockQuote<?php echo $blockCounter; ?>"
                        name="blockQuote[]"
                        placeholder="Enter the quote"
                        style="min-height: 80px;"
                      ><?php echo htmlspecialchars(stripslashes($blockDetails['quote_text'] ?? '')); ?></textarea>
                    </div>
                    <div class="form-row" style="margin-bottom: 0;">
                      <div class="form-group" style="flex: 2;">
                        <label for="blockQuoteAuthor<?php echo $blockCounter; ?>">Quote Author / Attribution <span class="help-hint">(Optional)</span></label>
                        <input
                          type="text"
                          id="blockQuoteAuthor<?php echo $blockCounter; ?>"
                          name="blockQuoteAuthor[]"
                          placeholder="Enter the author of the quote"
                          value="<?php echo htmlspecialchars(stripslashes($blockDetails['attribution'] ?? '')); ?>"
                        />
                      </div>
                    </div>

                  <?php elseif ($blockType === 'list'): ?>
                    <div class="form-group">
                      <label for="blockListTitle<?php echo $blockCounter; ?>">List Title <span class="help-hint">(Optional)</span></label>
                      <input
                        type="text"
                        id="blockListTitle<?php echo $blockCounter; ?>"
                        name="blockListTitle[]"
                        placeholder="Enter list title"
                      value="<?php echo htmlspecialchars(stripslashes($blockDetails['list_title'] ?? '')); ?>"
                      />
                    </div>
                    <div class="form-group">
                      <label>List Items <span class="required-badge">*</span></label>
                      <div class="list-items-container">
                        <?php 
                        $itemsList = $blockDetails['items'] ?? [];
                        if (empty($itemsList)) {
                          $itemsList = [['item_text' => '']];
                        }
                        foreach ($itemsList as $itemIndex => $item): 
                        ?>
                          <div class="list-item-input-group">
                            <input
                              type="text"
                              name="listItems[<?php echo $blockCounter - 1; ?>][]"
                              placeholder="Enter list item"
                              value="<?php echo htmlspecialchars(stripslashes($item['item_text'] ?? '')); ?>"
                            />
                            <button type="button" class="btn-remove-list-item" title="Remove Item">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <button type="button" class="btn-add-list-item">
                        <i class="fas fa-plus"></i> Add List Item
                      </button>
                    </div>
                  <?php endif; ?>
                </div>
                <?php
                  $blockCounter++;
                endforeach;
                ?>
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

            <!-- SECTION 4: Gallery Section -->
            <div class="card-section">
              <div class="card-section-header">
                <h3 class="card-section-title"><i class="fas fa-images"></i> Photo Gallery</h3>
              </div>
              <p class="card-section-subtitle">Add up to 6 images to be displayed in the blog post gallery.</p>

              <div class="gallery-grid" id="galleryContainer">
                <?php
                for ($i = 0; $i < 6; $i++):
                  $galleryImage = isset($gallery_images[$i]) ? $gallery_images[$i] : null;
                ?>
                <div class="gallery-item <?php echo $galleryImage ? 'has-image' : ''; ?>" data-slot="<?php echo $i+1; ?>">
                  <div class="gallery-placeholder">
                    <i class="fas fa-camera"></i>
                    <span>Photo <?php echo $i+1; ?></span>
                  </div>
                  <input
                    type="file"
                    class="gallery-upload"
                    name="galleryUpload<?php echo $i+1; ?>"
                    accept="image/*"
                  />
                  <?php if ($galleryImage): ?>
                    <img
                      src="../images/blog/gallery/<?php echo htmlspecialchars($galleryImage['image_path']); ?>"
                      class="gallery-preview"
                      alt="Gallery Photo <?php echo $i+1; ?>"
                    />
                    <button type="button" class="remove-gallery-image" title="Remove Photo" onclick="removeGalleryImage(<?php echo $galleryImage['gallery_image_id']; ?>, this)">
                      <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="existing_gallery_image<?php echo $i+1; ?>" value="<?php echo htmlspecialchars($galleryImage['image_path']); ?>">
                    <input type="hidden" name="gallery_image_id<?php echo $i+1; ?>" value="<?php echo $galleryImage['gallery_image_id']; ?>">
                  <?php else: ?>
                    <img src="" class="gallery-preview" alt="Gallery Photo <?php echo $i+1; ?>" />
                    <button type="button" class="remove-gallery-image" title="Remove Photo">
                      <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="existing_gallery_image<?php echo $i+1; ?>" value="">
                    <input type="hidden" name="gallery_image_id<?php echo $i+1; ?>" value="0">
                  <?php endif; ?>
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
                <i class="fas fa-save"></i> Update Blog Post
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>

    <!-- Toast container -->
    <div id="toast-container"></div>
  </body>
</html>
