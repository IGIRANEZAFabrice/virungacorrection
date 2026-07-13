<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session only once
session_start();

// if (!isset($_SESSION['admin_id'])) {
//   header("Location: login.html");
//   exit();
// }

// Fixed path to connection.php
require_once '../config/connection.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  // Handle different actions
  switch ($action) {
    case 'edit_section':
      $section_id = $_POST['section_id'] ?? 0;
      $section_title = $_POST['section_title'] ?? '';
      $content = $_POST['section_content'] ?? '';
      $image_path = $_POST['current_image_path'] ?? '';

      // Sanitize inputs
      $section_title = htmlspecialchars(trim($section_title));
      $content = trim($content); // Don't htmlspecialchars here to preserve formatting

      // Update image path if a new image was uploaded
      if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] == 0) {
        // Change upload directory
        $upload_dir = '../../images/about/';
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        
        // Delete old image if exists
        if (!empty($image_path) && file_exists('../../' . ltrim($image_path, '/'))) {
          unlink('../../' . ltrim($image_path, '/'));
        }
        
        $file_name = time() . '_' . basename($_FILES['section_image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['section_image']['tmp_name'], $target_file)) {
          // Store path in DB without ../../
          $image_path = '/images/about/' . $file_name;
        }
      }

      // Add error handling
      if (empty($section_id) || empty($section_title)) {
        $_SESSION['error_message'] = "Missing required fields";
        break;
      }

      $stmt = $conn->prepare("UPDATE about_sections SET section_title = ?, content = ?, image_path = ?, is_updated = TRUE, last_updated = NOW() WHERE id = ?");
      $stmt->bind_param("sssi", $section_title, $content, $image_path, $section_id);
      
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Section updated successfully!";
      } else {
        $_SESSION['error_message'] = "Error updating section: " . $conn->error;
      }
      
      $stmt->close();
      break;

    case 'add_section':
      $section_name = strtolower(str_replace(' ', '-', $_POST['section_name'] ?? ''));
      $section_title = $_POST['section_title'] ?? '';
      $content = $_POST['section_content'] ?? '';
      $icon_class = $_POST['icon_class'] ?? 'fas fa-paragraph';
      $image_path = '';

      // Handle image upload
      if (isset($_FILES['section_image']) && $_FILES['section_image']['error'] == 0) {
        // Change upload directory
        $upload_dir = '../../images/about/';
        if (!file_exists($upload_dir)) {
          mkdir($upload_dir, 0777, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['section_image']['name']);
        $target_file = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['section_image']['tmp_name'], $target_file)) {
          // Store path in DB without ../../
          $image_path = '/images/about/' . $file_name;
        }
      }

      // Get the highest display order
      $result = $conn->query("SELECT MAX(display_order) as max_order FROM about_sections");
      $row = $result->fetch_assoc();
      $display_order = ($row['max_order'] ?? 0) + 1;

      $stmt = $conn->prepare("INSERT INTO about_sections (section_name, section_title, content, image_path, icon_class, display_order, is_new) VALUES (?, ?, ?, ?, ?, ?, TRUE)");
      $stmt->bind_param("sssssi", $section_name, $section_title, $content, $image_path, $icon_class, $display_order);
      
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "New section added successfully!";
      } else {
        $_SESSION['error_message'] = "Error adding section: " . $conn->error;
      }
      
      $stmt->close();
      break;

    case 'delete_section':
      $section_id = $_POST['section_id'] ?? 0;
      $stmt = $conn->prepare("DELETE FROM about_sections WHERE id = ?");
      $stmt->bind_param("i", $section_id);
      
      if ($stmt->execute()) {
        $_SESSION['success_message'] = "Section deleted successfully!";
      } else {
        $_SESSION['error_message'] = "Error deleting section: " . $conn->error;
      }
      
      $stmt->close();
      break;
  }

  // Redirect to refresh page
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Get section data from database
$query = "SELECT * FROM about_sections ORDER BY display_order";
$result = $conn->query($query);
$sections = [];
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $sections[] = $row;
  }
}

// Helper function to format time ago
function time_elapsed_string($datetime) {
  $now = new DateTime;
  $ago = new DateTime($datetime);
  $diff = $now->diff($ago);

  if ($diff->d == 0) {
    if ($diff->h == 0) {
      if ($diff->i == 0) {
        return "just now";
      } else {
        return $diff->i . " minute" . ($diff->i > 1 ? "s" : "") . " ago";
      }
    } else {
      return $diff->h . " hour" . ($diff->h > 1 ? "s" : "") . " ago";
    }
  } else if ($diff->d <= 7) {
    return $diff->d . " day" . ($diff->d > 1 ? "s" : "") . " ago";
  } else if ($diff->d <= 30) {
    $weeks = floor($diff->d / 7);
    return $weeks . " week" . ($weeks > 1 ? "s" : "") . " ago";
  } else {
    return $ago->format('M j, Y');
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us Dashboard - Virunga Ecotours</title>
     <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/aboutus.css" />
    <link rel="stylesheet" href="../css/common.css" />

    <script src="../js/common.js" defer></script>
    <script src="../js/about.js" defer></script>
  </head>
  <body>
    <!-- Include sidebar template -->
    <?php include_once './includes/sidebar.php'; ?>

    <main class="main-content">
      <!-- Include header template -->
      <?php include_once './includes/header.php'; ?>
      <div class="dashboard-container">
        <div class="dashboard-header">
          <h1 class="dashboard-title">About Us Page Editor</h1>
          <div class="dashboard-actions">
            <button class="btn btn-success" id="addNewCardBtn">
              <i class="fas fa-plus"></i> Add New Card
            </button>
            <a href="../../pages/about.php" class="btn btn-secondary" target="_blank">
              <i class="fas fa-eye"></i> Preview Page
            </a>
          </div>
        </div>

        <div id="alertContainer"></div>

        <div class="content-sections" id="sectionsContainer">
          <?php
            if (!empty($sections)):
              foreach ($sections as $section):
          ?>
            <div class="section-card <?php echo $section['is_new'] ? 'new-section' : ($section['is_updated'] ? 'updated-section' : ''); ?>">
              <div class="section-card-header">
                <h2 class="section-title">
                  <i class="<?php echo htmlspecialchars($section['icon_class']); ?>"></i> <?php echo htmlspecialchars($section['section_title']); ?>
                </h2>
                <div class="section-actions">
                  <i
                    class="fas fa-edit action-icon edit-section"
                    data-section='<?php echo htmlspecialchars(json_encode($section), ENT_QUOTES, 'UTF-8'); ?>'
                  ></i>
                  <i
                    class="fas fa-trash action-icon delete-section"
                    data-section-id="<?php echo $section['id']; ?>"
                  ></i>
                </div>
              </div>
              <?php if (!empty($section['image_path'])): ?>
              <div class="section-media">
                <img src="../../<?php echo ltrim(htmlspecialchars($section['image_path']), '/'); ?>" 
                     alt="<?php echo htmlspecialchars($section['section_title']); ?>" />
              </div>
              <?php endif; ?>
              <div class="section-preview">
                <?php
                  $content = $section['content'];
                  echo htmlspecialchars(mb_substr($content, 0, 200)) . (mb_strlen($content) > 200 ? '...' : '');
                ?>
              </div>
              <div class="section-stats">
                <span class="stat"
                  ><i class="fas fa-clock"></i> Last edited: 
                  <?php
                    echo time_elapsed_string($section['last_updated']);
                  ?>
                </span>
                <span class="stat"><i class="fas fa-user"></i> <?php echo htmlspecialchars($section['updated_by']); ?></span>
              </div>
            </div>
          <?php
              endforeach;
            endif;
          ?>
        </div>
      </div>

      <!-- Edit Section Modal -->
      <div class="modal-backdrop" id="editModal">
        <div class="modal">
          <i class="fas fa-times modal-close" id="closeModal"></i>
          <h2 class="modal-title">
            Edit Section: <span id="sectionName"></span>
          </h2>

          <form id="editForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_section">
            <input type="hidden" name="section_id" id="sectionId">
            <input type="hidden" name="current_image_path" id="currentImagePath">

            <div class="form-group">
              <label class="form-label">Section Title</label>
              <input type="text" class="form-control" id="sectionTitle" name="section_title" required />
            </div>

            <div class="form-group">
              <label class="form-label">Section Content</label>
              <textarea class="form-control" id="sectionContent" name="section_content" required></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Section Image</label>
              <div class="image-uploader" id="imageUploader">
                <i class="fas fa-cloud-upload-alt"
                   style="font-size: 40px; color: #6c757d; margin-bottom: 15px"></i>
                <p>Drag and drop an image here, or click to select a file</p>
                <p style="font-size: 12px; color: #6c757d; margin-top: 10px">
                  Supported formats: JPG, PNG, WEBP (Max 5MB)
                </p>
                <input type="file" name="section_image" id="sectionImage" 
                       accept="image/jpeg,image/png,image/webp" style="display: none;">
              </div>
              <div id="imagePreview" style="display: none; margin-top: 10px;">
                <img src="" alt="Image Preview" style="max-width: 100%; max-height: 200px;">
                <button type="button" class="btn btn-danger btn-sm" id="removeImage" 
                        style="margin-top: 10px;">
                  <i class="fas fa-trash"></i> Remove Image
                </button>
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="btn btn-secondary" id="cancelEdit">
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Add New Section Modal -->
      <div class="modal-backdrop" id="addModal">
        <div class="modal">
          <i class="fas fa-times modal-close" id="closeAddModal"></i>
          <h2 class="modal-title">Add New Section</h2>

          <form id="addForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_section">
            
            <div class="form-group">
              <label class="form-label">Section Name</label>
              <input type="text" class="form-control" id="newSectionName" name="section_name" required />
              <small class="form-text text-muted">This will be used as an identifier (e.g., "our-mission")</small>
            </div>

            <div class="form-group">
              <label class="form-label">Section Title</label>
              <input type="text" class="form-control" id="newSectionTitle" name="section_title" required />
            </div>

            <div class="form-group">
              <label class="form-label">Icon Class</label>
              <select class="form-control" id="iconClass" name="icon_class">
                <option value="fas fa-paragraph">Paragraph</option>
                <option value="fas fa-image">Image</option>
                <option value="fas fa-star">Star</option>
                <option value="fas fa-lightbulb">Lightbulb</option>
                <option value="fas fa-history">History</option>
                <option value="fas fa-users">Users</option>
                <option value="fas fa-heart">Heart</option>
                <option value="fas fa-tasks">Tasks</option>
                <option value="fas fa-leaf">Leaf</option>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label">Section Content</label>
              <textarea class="form-control" id="newSectionContent" name="section_content" required></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Section Image</label>
              <div class="image-uploader" id="newImageUploader">
                <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: #6c757d; margin-bottom: 15px"></i>
                <p>Drag and drop an image here, or click to select a file</p>
                <p style="font-size: 12px; color: #6c757d; margin-top: 10px">
                  Supported formats: JPG, PNG, WEBP (Max 5MB)
                </p>
                <input type="file" name="section_image" id="newSectionImage" accept="image/jpeg,image/png,image/webp" style="display: none;">
              </div>
              <div id="newImagePreview" style="display: none; margin-top: 10px;">
                <img src="" alt="Image Preview" style="max-width: 100%; max-height: 200px;">
              </div>
            </div>

            <div class="form-actions">
              <button type="button" class="btn btn-secondary" id="cancelAdd">
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">Add Section</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <div class="modal-backdrop" id="deleteModal">
        <div class="modal">
          <i class="fas fa-times modal-close" id="closeDeleteModal"></i>
          <h2 class="modal-title">Delete Section</h2>
          
          <div class="modal-content">
            <p>Are you sure you want to delete this section? This action cannot be undone.</p>
          </div>

          <form method="POST" id="deleteForm">
            <input type="hidden" name="action" value="delete_section">
            <input type="hidden" name="section_id" id="deleteSectionId">
            <div class="form-actions">
              <button type="button" class="btn btn-secondary" id="cancelDelete">
                Cancel
              </button>
              <button type="submit" class="btn btn-danger">Delete</button>
            </div>
          </form>
        </div>
      </div>
    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Keep other handlers like delete, preview, etc.
        // Delete section
        document.querySelectorAll('.delete-section').forEach(function(button) {
          button.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section-id');
            document.getElementById('deleteSectionId').value = sectionId;
            document.getElementById('deleteModal').style.display = 'flex';
          });
        });

        // Add new section button
        document.getElementById('addNewCardBtn').addEventListener('click', function() {
          document.getElementById('addModal').style.display = 'flex';
        });

        // Preview button
        document.getElementById('previewBtn').addEventListener('click', function() {
          window.open('../about-us.php', '_blank');
        });

        // Close modals
        document.getElementById('closeAddModal').addEventListener('click', function() {
          document.getElementById('addModal').style.display = 'none';
        });

        document.getElementById('closeDeleteModal').addEventListener('click', function() {
          document.getElementById('deleteModal').style.display = 'none';
        });

        // Cancel buttons
        document.getElementById('cancelAdd').addEventListener('click', function() {
          document.getElementById('addModal').style.display = 'none';
        });

        document.getElementById('cancelDelete').addEventListener('click', function() {
          document.getElementById('deleteModal').style.display = 'none';
        });

        // Image uploader for add form
        document.getElementById('newImageUploader').addEventListener('click', function() {
          document.getElementById('newSectionImage').click();
        });

        document.getElementById('newSectionImage').addEventListener('change', function(e) {
          if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('newImagePreview').style.display = 'block';
              document.getElementById('newImagePreview').querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
          }
        });

        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
          setTimeout(function() {
            alert.style.opacity = '0';
            setTimeout(function() {
              alert.style.display = 'none';
            }, 500);
          }, 5000);
        });

        // Image upload handling
        document.getElementById('imageUploader').addEventListener('click', function(e) {
          if (e.target.id !== 'removeImage') {
            document.getElementById('sectionImage').click();
          }
        });

        document.getElementById('sectionImage').addEventListener('change', function(e) {
          if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
              const imagePreview = document.getElementById('imagePreview');
              imagePreview.style.display = 'block';
              imagePreview.querySelector('img').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
          }
        });

        // Add remove image functionality
        document.getElementById('removeImage')?.addEventListener('click', function(e) {
          e.stopPropagation();
          document.getElementById('sectionImage').value = '';
          document.getElementById('currentImagePath').value = '';
          document.getElementById('imagePreview').style.display = 'none';
        });
      });
    </script>
  </body>
</html>