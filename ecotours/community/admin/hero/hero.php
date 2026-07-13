<?php 
session_start();

if (!isset($_SESSION['community_admin_id'])) {
  header("Location: index.php");
  exit();
}

require_once '../../../admin/config/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Community Hero Management - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../../assets/images/logos/logo.jpg" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../../../css/earthy-theme.css" />
    <link rel="stylesheet" href="../assets/css/admin.css" />
    <link rel="stylesheet" href="../assets/css/hero.css" />
    <script src="../assets/js/admin.js" defer></script>
    <script src="../assets/js/hero.js" defer></script>
  </head>
  <body>
    <!-- Sidebar (outside admin-container for fixed positioning) -->
    <?php include_once '../includes/sidebar.php'; ?>

    <div class="admin-container">
      <!-- Top Header -->
      <?php include_once '../includes/topbar.php'; ?>
      <main class="main-content">
        

        <div class="content-panels">
          <!-- Dashboard specific content -->
          <form action="../handlers/updateCommunityHero.php" method="post" enctype="multipart/form-data">
            <div class="panel active" id="community-hero-panel">
              <div class="panel-header">
                <h1>Community Hero Management</h1>
                <div class="panel-actions">
                  <button class="action-button" type="submit">
                    <i class="fas fa-save"></i> Save Changes
                  </button>
                  <button type="button" class="action-button add-hero-slide-btn" onclick="openAddHeroSlideModal()">
                    <i class="fas fa-plus"></i> Add New Hero Slide
                  </button>
                </div>
                <?php if (isset($_GET['status'])): ?>
                  <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                    <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <span><?php echo $_GET['status'] === 'success' ? 'Changes saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred'); ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Hero Section -->
              <div class="home-sections">
                <div class="home-section active" id="hero-section">
                  <div class="section-header">
                    <h2>
                      <i class="fas fa-image"></i> Hero Section Management
                    </h2>
                    <p class="section-desc">
                      Manage the community hero carousel on your homepage.
                    </p>
                  </div>

                  <div class="section-content">
                    <div class="content-preview">
                      <div class="preview-header">
                        <h3>Current Hero Carousel</h3>
                        <span class="preview-toggle">
                          <i class="fas fa-eye"></i> Preview
                        </span>
                      </div>
                      <div class="preview-container">
                        <div class="hero-carousel">
                          <div class="carousel-navigation">
                            <span class="carousel-arrow prev">
                              <i class="fas fa-chevron-left"></i>
                            </span>
                            <div class="carousel-indicators">
                              <?php
                              // Count total slides for indicators
                              $indicator_query = "SELECT COUNT(*) as total FROM community_hero";
                              $indicator_result = $conn->query($indicator_query);
                              $total_slides = $indicator_result->fetch_assoc()['total'];

                              for ($i = 0; $i < $total_slides; $i++) {
                                  echo '<span class="indicator' . ($i === 0 ? ' active' : '') . '"></span>';
                              }
                              ?>
                            </div>
                            <span class="carousel-arrow next">
                              <i class="fas fa-chevron-right"></i>
                            </span>
                          </div>
                          <div class="hero-slides">
                            <?php
                            $query = "SELECT * FROM community_hero ORDER BY id ASC";
                            $result = $conn->query($query);
                            $active = 'active';
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                            ?>
                            <div class="hero-slide <?php echo $active; $active = ''; ?>">
                              <img src="../../../<?php echo $row['image_url']; ?>" alt="Hero Banner" />
                              <div class="hero-overlay">
                                <h2><?php echo $row['title']; ?></h2>
                                <p><?php echo $row['description']; ?></p>
                              </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="content-editor">
                      <div class="tabs-container">
                        <div class="tabs-header">
                          <?php
                          // Generate dynamic tabs based on actual slides
                          $tab_query = "SELECT id FROM community_hero ORDER BY id ASC";
                          $tab_result = $conn->query($tab_query);
                          $tab_count = 1;

                          if ($tab_result->num_rows > 0) {
                              while($tab_row = $tab_result->fetch_assoc()) {
                                  $active_class = $tab_count === 1 ? ' active' : '';
                                  echo '<button class="tab-btn' . $active_class . '" data-target="slide' . $tab_row['id'] . '">';
                                  echo 'Slide ' . $tab_count;
                                  echo '</button>';
                                  $tab_count++;
                              }
                          }
                          ?>
                        </div>

                        <?php
                          $content_query = "SELECT * FROM community_hero ORDER BY id ASC";
                          $content_result = $conn->query($content_query);
                          $slide_count = 1;

                          if ($content_result->num_rows > 0) {
                              while($row = $content_result->fetch_assoc()) {
                          ?>
                          <div class="tab-content <?php echo $slide_count === 1 ? 'active' : ''; ?>" id="slide<?php echo $row['id']; ?>">
                              <div class="editor-form">
                                  <input type="hidden" name="slide_id_<?php echo $slide_count; ?>" value="<?php echo $row['id']; ?>" />
                                  <div class="form-group">
                                      <label for="slide<?php echo $row['id']; ?>-title">Slide Title</label>
                                      <input type="text" id="slide<?php echo $row['id']; ?>-title" name="slide<?php echo $slide_count; ?>-title" value="<?php echo htmlspecialchars($row['title']); ?>" />
                                  </div>
                                  <div class="form-group">
                                      <label for="slide<?php echo $row['id']; ?>-desc">Slide Description</label>
                                      <textarea id="slide<?php echo $row['id']; ?>-desc" name="slide<?php echo $slide_count; ?>-desc" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                                  </div>
                                  <div class="form-group">
                                      <label for="slide<?php echo $row['id']; ?>-img">Slide Image</label>
                                      <div class="file-upload">
                                          <input type="file" id="slide<?php echo $row['id']; ?>-img" name="slide<?php echo $slide_count; ?>-img" />
                                          <div class="file-preview">
                                              <img src="../../<?php echo $row['image_url']; ?>" alt="Current Hero Image" />
                                          </div>
                                          <button class="file-button">
                                              <i class="fas fa-upload"></i> Choose File
                                          </button>
                                      </div>
                                      <p class="input-help">
                                          Recommended size: 1920x1080px, max file size: 2MB
                                      </p>
                                  </div>
                                  <div class="form-group">
                                      <button type="button" class="action-button delete-slide-btn" onclick="deleteHeroSlide(<?php echo $row['id']; ?>)">
                                          <i class="fas fa-trash"></i> Delete Slide
                                      </button>
                                  </div>
                              </div>
                          </div>
                        <?php
                                $slide_count++;
                            }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </main>
    </div>

    <!-- Add Hero Slide Modal -->
    <div id="addHeroSlideModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeAddHeroSlideModal()">&times;</span>
        <h2>Add New Hero Slide</h2>
        <form id="addHeroSlideForm" action="handlers/addCommunityHeroSlide.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label for="newSlideTitle">Slide Title</label>
            <input type="text" id="newSlideTitle" name="title" required placeholder="Enter slide title">
          </div>
          <div class="form-group">
            <label for="newSlideDescription">Slide Description</label>
            <textarea id="newSlideDescription" name="description" rows="3" required placeholder="Enter slide description"></textarea>
          </div>
          <div class="form-group">
            <label for="newSlideImage">Slide Image</label>
            <div class="file-upload">
              <input type="file" id="newSlideImage" name="image" required accept="image/*">
              <div class="file-preview" id="newSlideImagePreview">
                <p>No image selected</p>
              </div>
              <button type="button" class="file-button" onclick="document.getElementById('newSlideImage').click()">
                <i class="fas fa-upload"></i> Choose File
              </button>
            </div>
            <p class="input-help">
              Recommended size: 1920x1080px, max file size: 2MB
            </p>
          </div>
          <div class="form-actions">
            <button type="button" class="action-button secondary" onclick="closeAddHeroSlideModal()">
              <i class="fas fa-times"></i> Cancel
            </button>
            <button type="submit" class="action-button">
              <i class="fas fa-plus"></i> Add Slide
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php $conn->close(); ?>
  </body>
</html>

