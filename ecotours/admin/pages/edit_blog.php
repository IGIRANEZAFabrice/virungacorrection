<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.html');
  exit();
}

require_once('../config/connection.php');

// Get blog post ID from URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    <style>
      img {
        max-width: 100%;
        height: auto;
      }

      .content-block {
        border: 1px solid #ddd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 5px;
        background-color: #f9f9f9;
      }
      .remove-block {
        float: right;
        color: red;
        cursor: pointer;
      }

      /* --- Gallery Styles --- */
      .gallery-section {
          margin-top: 20px;
          padding-top: 20px;
          border-top: 1px solid #eee;
      }

      .gallery-grid {
          display: grid;
          /* Creates columns that are at least 120px wide, filling the container */
          grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
          gap: 15px; /* Space between grid items */
          margin-top: 10px;
          margin-bottom: 20px; /* Space before the file input */
      }

      .gallery-item {
          position: relative; /* Needed for absolute positioning of the remove button */
          border: 1px solid #ddd;
          border-radius: 5px; /* Slightly rounded corners */
          overflow: hidden; /* Ensures image respects the border radius */
          background-color: #f9f9f9; /* Light background */
          box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* Subtle shadow */
          transition: transform 0.2s ease-in-out; /* Smooth hover effect */
          cursor: pointer;
      }

      .gallery-item:hover {
          transform: translateY(-2px); /* Slight lift on hover */
          box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Enhanced shadow on hover */
      }

      .gallery-item img {
          display: block; /* Remove extra space below image */
          width: 100%;
          height: auto; /* Fixed height for uniform look */
          object-fit: cover; /* Scales the image while preserving aspect ratio, cropping if necessary */
          aspect-ratio: 1 / 1; /* Makes the image container square */
      }

      .gallery-placeholder {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          height: 200px;
          color: #999;
          font-size: 14px;
      }

      .gallery-placeholder i {
          font-size: 2em;
          margin-bottom: 10px;
      }

      .gallery-upload {
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          opacity: 0;
          cursor: pointer;
          z-index: 5;
      }

      .gallery-item .remove-gallery-image {
          position: absolute;
          top: 8px;
          right: 8px;
          background-color: rgba(220, 53, 69, 0.9); /* Semi-transparent red background */
          color: white;
          border: none;
          border-radius: 50%; /* Circular button */
          width: 26px; /* Size of the button */
          height: 26px;
          font-size: 12px; /* Size of the 'x' icon */
          line-height: 26px; /* Vertically center the icon */
          text-align: center; /* Horizontally center the icon */
          cursor: pointer;
          opacity: 0.8; /* Slightly visible by default */
          transition: opacity 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
          z-index: 10; /* Ensure it's above the image */
      }

      .gallery-item:hover .remove-gallery-image {
          opacity: 1; /* Show on hover */
          transform: scale(1.1);
      }

      .gallery-item .remove-gallery-image:hover {
          background-color: rgba(200, 33, 49, 1); /* Darker red on hover */
          transform: scale(1.2);
      }

      /* Styles for the preview area of newly added gallery images */
      #galleryImagePreview img {
          max-width: 100px;
          max-height: 100px;
          margin: 5px;
          border: 1px solid #eee;
          border-radius: 3px;
      }
      /* --- End Gallery Styles --- */

    </style>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>

        <div class="container">
          <div class="blog-form-container" id="blogFormContainer">
            <h2 class="form-header">Edit Blog Post: <?php echo htmlspecialchars(stripslashes($post['title'])); ?></h2>
            <?php if (isset($_GET['status'])): ?>
              <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] === 'success' ? 'Changes saved successfully!' : 'An error occurred')); ?></span>
              </div>
            <?php endif; ?>

            <form id="blogForm" method="post" action="../handlers/blog/updateBlogHandler.php" enctype="multipart/form-data">
              <!-- Add hidden input for blog ID -->
              <input type="hidden" name="blog_id" value="<?php echo $post_id; ?>">

              <!-- Basic Information Section -->
              <div class="form-row">
                <div class="form-col">
                  <div class="form-group">
                    <label for="blogTitle">Blog Title</label>
                    <input
                      type="text"
                      id="blogTitle"
                      name="blogTitle"
                      placeholder="Enter blog title"
                      value="<?php echo htmlspecialchars(stripslashes($post['title'])); ?>"
                      required
                    />
                  </div>
                </div>
                <div class="form-col">
                  <div class="form-group">
                    <label for="coverImage">Cover Image</label>
                    <input
                      type="file"
                      id="coverImage"
                      name="coverImage"
                      accept="image/*"
                    />
                    <div class="image-preview-container" id="coverImagePreview">
                      <?php if (!empty($post['cover_image'])): ?>
                        <img id="coverImagePreviewImg" src="../images/blog/covers/<?php echo htmlspecialchars($post['cover_image']); ?>" style="display: block; max-width: 200px; margin-top: 10px;"/>
                      <?php else: ?>
                        <img id="coverImagePreviewImg" style="display: none; max-width: 200px; margin-top: 10px;"/>
                      <?php endif; ?>
                    </div>
                    <!-- Add hidden input to keep track of existing image -->
                    <input type="hidden" name="existing_cover_image" value="<?php echo htmlspecialchars($post['cover_image'] ?? ''); ?>">
                  </div>
                </div>
              </div>

              <div class="form-row">
                <div class="form-col">
                  <div class="form-group">
                    <label for="author">Author</label>
                    <input
                      type="text"
                      id="author"
                      name="author"
                      placeholder="Enter author name"
                      value="<?php echo htmlspecialchars(stripslashes($post['author'])); ?>"
                      required
                    />
                  </div>
                </div>
                <div class="form-col">
                  <div class="form-group">
                    <label for="readMin">Read Time (minutes)</label>
                    <input
                      type="number"
                      id="readMin"
                      name="readMin"
                      min="1"
                      placeholder="Estimated read time"
                      value="<?php echo htmlspecialchars($post['read_minutes']); ?>"
                      required
                    />
                  </div>
                </div>
                <div class="form-col">
                  <div class="form-group">
                    <label for="category">Category</label>
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
              </div>

              <!-- Introduction Section -->
              <div class="form-group">
                <label for="bigTitle">Main Headline</label>
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
                <label for="bigDescription">Introduction</label>
                <textarea
                  id="bigDescription"
                  name="bigDescription"
                  placeholder="Write an introduction for your blog post"
                  required
                ><?php echo htmlspecialchars(stripslashes($post['introduction'])); ?></textarea>
              </div>

              <!-- Dynamic Content Blocks -->
              <h3 style="margin-top: 30px; color: var(--primary-green)">
                Content Blocks
              </h3>

              <div class="content-blocks" id="contentBlocks">
                <!-- Content blocks will be loaded dynamically -->
                <?php
                $blockCounter = 1;
                foreach ($content_blocks as $index => $block):
                  $blockType = $block['block_type'];
                  $blockDetails = $block['details'];
                ?>
                <div class="content-block" data-block-type="<?php echo $blockType; ?>">
                  <div class="block-header">
                    <span class="block-title">Content Block <?php echo $blockCounter; ?></span>
                    <button type="button" class="remove-block">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>

                  <!-- Hidden input to store block ID for update -->
                  <input type="hidden" name="block_id[]" value="<?php echo $block['block_id']; ?>">
                  <input type="hidden" name="block_type[]" value="<?php echo $blockType; ?>">
                  <input type="hidden" name="block_order[]" value="<?php echo $index + 1; ?>">

                  <?php if ($blockType === 'text'): ?>
                    <div class="form-group">
                      <label for="blockTitle<?php echo $blockCounter; ?>">Section Title</label>
                      <input
                        type="text"
                        id="blockTitle<?php echo $blockCounter; ?>"
                        name="blockTitle[]"
                        placeholder="Enter section title"
                        value="<?php echo htmlspecialchars(stripslashes($blockDetails['section_title'])); ?>"
                      />
                    </div>
                    <div class="form-group">
                      <label for="blockContent<?php echo $blockCounter; ?>">Content</label>
                      <textarea
                        id="blockContent<?php echo $blockCounter; ?>"
                        name="blockContent[]"
                        placeholder="Write your content here"
                      ><?php echo htmlspecialchars(stripslashes($blockDetails['content'])); ?></textarea>
                    </div>
                  <?php elseif ($blockType === 'image'): ?>
                    <div class="form-group">
                      <label for="blockImageCaption<?php echo $blockCounter; ?>">Image Caption</label>
                      <input
                        type="text"
                        id="blockImageCaption<?php echo $blockCounter; ?>"
                        name="blockImageCaption[]"
                        placeholder="Enter image caption"
                        value="<?php echo htmlspecialchars(stripslashes($blockDetails['caption'])); ?>"
                      />
                    </div>
                    <div class="form-group">
                      <label for="blockImage<?php echo $blockCounter; ?>">Image</label>
                      <input
                        type="file"
                        id="blockImage<?php echo $blockCounter; ?>"
                        name="blockImage[]"
                        accept="image/*"
                        class="block-image-input"
                        data-block-id="<?php echo $blockCounter; ?>"
                      />
                      <div class="image-preview" id="imagePreview<?php echo $blockCounter; ?>">
                        <?php if (!empty($blockDetails['image_path'])): ?>
                          <img src="../images/blog/content/<?php echo htmlspecialchars($blockDetails['image_path']); ?>" style="max-width: 80%; margin-top: 20px; border-radius: 10px; display: block;" class="preview-image">
                        <?php endif; ?>
                      </div>
                      <input type="hidden" name="existing_block_image[]" value="<?php echo htmlspecialchars($blockDetails['image_path'] ?? ''); ?>">
                    </div>
                  <?php elseif ($blockType === 'quote'): ?>
                    <div class="form-group">
                      <label for="blockQuote<?php echo $blockCounter; ?>">Quote Text</label>
                      <textarea
                        id="blockQuote<?php echo $blockCounter; ?>"
                        name="blockQuote[]"
                        placeholder="Enter the quote"
                      ><?php echo htmlspecialchars(stripslashes($blockDetails['quote_text'])); ?></textarea>
                    </div>
                    <div class="form-group">
                      <label for="blockQuoteAuthor<?php echo $blockCounter; ?>">Quote Author</label>
                      <input
                        type="text"
                        id="blockQuoteAuthor<?php echo $blockCounter; ?>"
                        name="blockQuoteAuthor[]"
                        placeholder="Enter the author of the quote"
                        value="<?php echo htmlspecialchars(stripslashes($blockDetails['attribution'])); ?>"
                      />
                    </div>
                  <?php elseif ($blockType === 'list'): ?>
                    <div class="form-group">
                      <label for="blockListTitle<?php echo $blockCounter; ?>">List Title</label>
                      <input
                        type="text"
                        id="blockListTitle<?php echo $blockCounter; ?>"
                        name="blockListTitle[]"
                        placeholder="Enter list title"
                        value="<?php echo htmlspecialchars(stripslashes($blockDetails['title'])); ?>"
                      />
                    </div>
                    <div class="list-items-container">
                      <?php foreach ($blockDetails['items'] as $itemIndex => $item): ?>
                        <div class="list-item">
                          <div class="form-group">
                            <label>List Item <?php echo $itemIndex + 1; ?></label>
                            <div class="list-item-input-group">
                              <input
                                type="text"
                                name="listItems[<?php echo $blockCounter - 1; ?>][]"
                                placeholder="Enter list item"
                                value="<?php echo htmlspecialchars(stripslashes($item['item_text'])); ?>"
                              />
                              <button type="button" class="remove-list-item">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                    <button type="button" class="add-list-item-btn">
                      <i class="fas fa-plus"></i> Add List Item
                    </button>
                  <?php endif; ?>
                </div>
                <?php
                  $blockCounter++;
                endforeach;
                ?>
              </div>

              <!-- Add Block Buttons -->
              <div class="add-block-buttons">
                <button type="button" class="add-block-btn" data-block-type="text">
                  <i class="fas fa-paragraph"></i> Add Text Block
                </button>
                <button type="button" class="add-block-btn" data-block-type="image">
                  <i class="fas fa-image"></i> Add Image Block
                </button>
              </div>

              <!-- Gallery Section -->
              <h3 style="margin-top: 30px; color: var(--primary-green)">
                Gallery Images
              </h3>
              <p>Add up to 6 images to be displayed in the blog post gallery.</p>

              <div class="gallery-container" id="galleryContainer">
                <?php
                // Display existing gallery images
                for ($i = 0; $i < 6; $i++):
                  $galleryImage = isset($gallery_images[$i]) ? $gallery_images[$i] : null;
                ?>
                <div class="gallery-item" data-slot="<?php echo $i+1; ?>">
                  <div class="gallery-placeholder" <?php echo $galleryImage ? 'style="display: none;"' : ''; ?>>
                    <i class="fas fa-image"></i>
                    <span>Click to add image</span>
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
                      style="display: block;"
                    />
                    <button type="button" class="remove-gallery-image" onclick="removeGalleryImage(<?php echo $galleryImage['gallery_image_id']; ?>, this)">
                      <i class="fas fa-times"></i>
                    </button>
                    <input type="hidden" name="existing_gallery_image<?php echo $i+1; ?>" value="<?php echo htmlspecialchars($galleryImage['image_path']); ?>">
                    <input type="hidden" name="gallery_image_id<?php echo $i+1; ?>" value="<?php echo $galleryImage['gallery_image_id']; ?>">
                  <?php else: ?>
                    <img src="" class="gallery-preview" style="display: none;" />
                    <input type="hidden" name="existing_gallery_image<?php echo $i+1; ?>" value="">
                    <input type="hidden" name="gallery_image_id<?php echo $i+1; ?>" value="0">
                  <?php endif; ?>
                </div>
                <?php endfor; ?>
              </div>

              <!-- Hidden container for gallery inputs -->
              <div id="galleryInputsContainer" style="display: none;"></div>

              <!-- Submit Button -->
              <div class="form-actions">
                <button type="submit" class="submit-btn">
                  <i class="fas fa-save"></i> Update Blog Post
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>