<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.html');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tours Management - Virunga Ecotours</title>
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
    <script src="../js/create_blog.js" defer></script>
    <style>
      img {
        max-width: 100%;
        height: auto;
      }
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
            <h2 class="form-header">Create New Blog Post</h2>
            <?php if (isset($_GET['status'])): ?>
              <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                <span><?php echo $_GET['status'] === 'success' ? 'Changes saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred'); ?></span>
              </div>
            <?php endif; ?>

            <form id="blogForm" method="post" action="../handlers/blog/addRichBlogHandler.php" enctype="multipart/form-data">
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
                      required
                    />
                    <div class="image-preview-container" id="coverImagePreview">
                      <img id="coverImagePreviewImg" style="display: none; max-width: 200px; margin-top: 10px;"/>
                    </div>
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
                      required
                    />
                  </div>
                </div>
                <div class="form-col">
                  <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" name="category" required>
                      <option value="">Select a category</option>
                      <option value="lifestyle">Lifestyle</option>
                      <option value="travel">Travel</option>
                      <option value="food">Food</option>
                      <option value="technology">Technology</option>
                      <option value="health">Health</option>
                      <option value="design">Design</option>
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
                ></textarea>
              </div>

              <!-- Dynamic Content Blocks -->
              <h3 style="margin-top: 30px; color: var(--primary-green)">
                Content Blocks
              </h3>

              <div class="content-blocks" id="contentBlocks">
                <!-- Content blocks will be added dynamically -->
              </div>

              <div class="add-block-buttons">
                <button
                  type="button"
                  class="add-block-btn"
                  data-block-type="text"
                >
                  <i class="fas fa-font"></i> Add Text Block
                </button>
                <button
                  type="button"
                  class="add-block-btn"
                  data-block-type="image"
                >
                  <i class="fas fa-image"></i> Add Image Block
                </button>
              </div>

              <!-- Gallery Section -->
              <h3 style="margin-top: 30px; color: var(--primary-green)">
                Gallery Images
              </h3>
              <p style="margin-bottom: 20px; color: var(--text-medium)">
                Add up to 6 images for your blog gallery
              </p>

              <div class="gallery-container" id="galleryContainer">
                <!-- Gallery placeholders -->
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage1" class="gallery-upload" />
                </div>
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage2" class="gallery-upload" />
                </div>
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage3" class="gallery-upload" />
                </div>
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage4" class="gallery-upload" />
                </div>
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage5" class="gallery-upload" />
                </div>
                <div class="gallery-item">
                  <div class="gallery-placeholder">
                    <i class="fas fa-image fa-2x"></i>
                    <img class="gallery-preview" style="display: none; max-width: 100%; height: 100%; object-fit: cover;"/>
                  </div>
                  <input type="file" accept="image/*" name="galleryImage6" class="gallery-upload" />
                </div>
              </div>

              <!-- Submit buttons -->
              <div class="submit-row">
                <button type="submit" class="submit-btn">
                  Publish Blog Post
                </button>
              </div>


            </form>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
