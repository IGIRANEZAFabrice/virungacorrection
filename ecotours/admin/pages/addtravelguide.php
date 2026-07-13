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
    <link rel="stylesheet" href="../css/create_travel.css" />
    <script src="../js/common.js" defer></script>
    <script src="../js/create_travel.js" defer></script>
    <style>
      img {
        max-width: 100%;
        height: auto;
      }
    </style>
  </head>
  <body>
    <div class="admin-container">
      <?php include_once './includes/sidebar.php'; ?>
      <main class="main-content">
        <?php include_once './includes/header.php'; ?>
        <div class="container">
          <div class="blog-form-container" id="blogFormContainer">
            <h2 class="form-header">Create New Style Guide</h2>
            <form id="blogForm" method="post" action="../handlers/travelguide_handler.php" enctype="multipart/form-data">
              <!-- Basic Information -->
              <div class="form-row">
                <div class="form-col">
                  <div class="form-group">
                    <label for="title">Guide Title</label>
                    <input type="text" id="title" name="title" required />
                  </div>
                </div>
                <div class="form-col">
                  <div class="form-group">
                    <label for="coverImage">Cover Image</label>
                    <input type="file" id="coverImage" name="coverImage" accept="image/*" required />
                    <div class="image-preview" id="coverPreview"></div>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="country">Country</label>
                <select id="country" name="country" required>
                  <option value="">Select a country</option>
                  <option value="rwanda">Rwanda</option>
                  <option value="uganda">Uganda</option>
                  <option value="congo">DR Congo</option>
                </select>
              </div>

              <div class="form-group">
                <label for="intro">Introduction</label>
                <textarea id="intro" name="intro" required></textarea>
              </div>

              <!-- Dynamic Content Blocks -->
              <div id="contentBlocks">
                <!-- Content blocks will be added here -->
              </div>

              <div class="button-group">
                <button type="button" class="add-block-btn" data-block-type="text">
                  <i class="fas fa-font"></i> Add Text Block
                </button>
                <button type="button" class="add-block-btn" data-block-type="image">
                  <i class="fas fa-image"></i> Add Image Block
                </button>
              </div>

              <button type="submit" class="submit-btn">Create Guide</button>
            </form>
          </div>
        </div>
      </main>
    </div>

    <!-- Template for text block -->
    <template id="textBlockTemplate">
      <div class="content-block text-block">
        <div class="block-header">
          <span class="block-number"></span>
          <button type="button" class="remove-block">&times;</button>
        </div>
        <div class="form-group">
          <label>Subtitle</label>
          <input type="text" name="subtitle[]" required>
        </div>
        <div class="form-group">
          <label>Content</label>
          <textarea name="content[]" required></textarea>
        </div>
        <input type="hidden" name="block_type[]" value="text">
        <input type="hidden" name="display_no[]" class="display-no">
      </div>
    </template>

    <!-- Template for image block -->
    <template id="imageBlockTemplate">
      <div class="content-block image-block">
        <div class="block-header">
          <span class="block-number"></span>
          <button type="button" class="remove-block">&times;</button>
        </div>
        <div class="form-group">
          <label>Image</label>
          <input type="file" name="block_image[]" accept="image/*" required>
          <div class="image-preview"></div>
        </div>
        <input type="hidden" name="block_type[]" value="image">
        <input type="hidden" name="display_no[]" class="display-no">
      </div>
    </template>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        let blockCount = 0;
        const contentBlocks = document.getElementById('contentBlocks');
        const textTemplate = document.getElementById('textBlockTemplate');
        const imageTemplate = document.getElementById('imageBlockTemplate');

        // Add block handlers
        document.querySelectorAll('.add-block-btn').forEach(button => {
          button.addEventListener('click', function() {
            const blockType = this.dataset.blockType;
            addBlock(blockType);
          });
        });

        function addBlock(type) {
          blockCount++;
          const template = type === 'text' ? textTemplate : imageTemplate;
          const clone = template.content.cloneNode(true);

          // Set block number and display order
          clone.querySelector('.block-number').textContent = `Block ${blockCount}`;
          clone.querySelector('.display-no').value = blockCount;

          // Add remove handler
          clone.querySelector('.remove-block').addEventListener('click', function() {
            this.closest('.content-block').remove();
            reorderBlocks();
          });

          // Add image preview handler for image blocks
          if (type === 'image') {
            const fileInput = clone.querySelector('input[type="file"]');
            const preview = clone.querySelector('.image-preview');

            fileInput.addEventListener('change', function() {
              if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                  preview.innerHTML = `<img src="${e.target.result}" style="max-width: 200px;">`;
                };
                reader.readAsDataURL(this.files[0]);
              }
            });
          }

          contentBlocks.appendChild(clone);
        }

        function reorderBlocks() {
          blockCount = 0;
          document.querySelectorAll('.content-block').forEach(block => {
            blockCount++;
            block.querySelector('.block-number').textContent = `Block ${blockCount}`;
            block.querySelector('.display-no').value = blockCount;
          });
        }
      });
    </script>
  </body>
</html>
