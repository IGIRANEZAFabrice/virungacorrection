<?php
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

$attractions = [];
$query = "SELECT * FROM home_attractions";
$result = mysqli_query($conn, $query);
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $attractions[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Virunga Ecotours</title>
    <link
      rel="shortcut icon"
      href="../../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../../css/common.css" />
    <link rel="stylesheet" href="../../css/home.css" />
    <script src="../../js/common.js" defer></script>
    <script src="../../js/home.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './include/sidebar.php'; ?>
      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './include/header.php'; ?>
        <div class="content-panels">
          <!-- Dashboard specific content -->
          <form
            action="../../handlers/home/homeAttractionsHandler.php"
            method="post"
            enctype="multipart/form-data"
          >
            <div class="panel active" id="home-schema-panel">
              <div class="panel-header">
                <h1>Home Page Management</h1>
                <button class="action-button" type="submit">
                  <i class="fas fa-save"></i> Save Changes
                </button>
                <?php if (isset($_GET['status'])): ?>
                  <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                    <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <span><?php echo $_GET['status'] === 'success' ? 'Changes saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred'); ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Home Schema Sections -->
              <div class="home-sections">
                <!-- Attractions Section -->
                <div class="home-section active" id="hero-section">
                  <div class="section-header">
                    <h2>
                      <i class="fas fa-landmark"></i> Attractions Management
                    </h2>
                    <p class="section-desc">
                      Manage the featured attractions on your homepage.
                    </p>
                  </div>

                  <div class="section-content">
                    <div class="content-preview">
                      <div class="preview-header">
                        <h3>Current Destinations</h3>
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
                              <?php foreach($attractions as $index => $attraction): ?>
                              <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>"></span>
                              <?php endforeach; ?>
                            </div>
                            <span class="carousel-arrow next">
                              <i class="fas fa-chevron-right"></i>
                            </span>
                          </div>
                          <div class="hero-slides">
                            <?php foreach($attractions as $index => $attraction): ?>
                            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                              <img
                                src="<?php echo htmlspecialchars($attraction['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($attraction['title']); ?>"
                              />
                              <div class="hero-overlay">
                                <h2><?php echo htmlspecialchars($attraction['title']); ?></h2>
                                <div class="attraction-actions">
                                  <a href="attraction_details.php?id=<?php echo $attraction['id']; ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Manage Details
                                  </a>
                                </div>
                              </div>
                            </div>
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="content-editor">
                      <div class="tabs-container">
                        <div class="tabs-header">
                          <?php foreach($attractions as $index => $attraction): ?>
                          <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-target="slide<?php echo $index + 1; ?>">
                            Slide <?php echo $index + 1; ?>
                          </button>
                          <?php endforeach; ?>
                        </div>

                        <?php foreach($attractions as $index => $attraction): ?>
                        <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="slide<?php echo $index + 1; ?>">
                          <div class="editor-form">
                            <div class="form-group-header">
                              <h3>Attraction Details</h3>
                              <a href="attraction_details.php?id=<?php echo $attraction['id']; ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-cog"></i> Manage Details & Gallery
                              </a>
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-title">Slide Title</label>
                              <input
                                type="text"
                                id="slide<?php echo $index + 1; ?>-title"
                                name="slide<?php echo $index + 1; ?>-title"
                                value="<?php echo htmlspecialchars($attraction['title']); ?>"
                              />
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-img">Slide Image</label>
                              <div class="file-upload">
                                <input type="file" id="slide<?php echo $index + 1; ?>-img" name="slide<?php echo $index + 1; ?>-img" />
                                <div class="file-preview">
                                  <img
                                    src="<?php echo htmlspecialchars($attraction['image_url']); ?>"
                                    alt="Current Hero Image"
                                  />
                                </div>
                                <button class="file-button">
                                  <i class="fas fa-upload"></i> Choose File
                                </button>
                              </div>
                              <p class="input-help">
                                Recommended size: 1920x1080px, max file size:
                                2MB
                              </p>
                            </div>
                          </div>
                        </div>
                        <?php endforeach; ?>
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
  </body>
</html>
