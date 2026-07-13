<?php
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

// Fetch all destinations from database
$query = "SELECT * FROM home_destinations ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$destinations = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $destinations[] = $row;
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
            action="../../handlers/home/homeDestinationHandler.php"
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
                <!-- Destinations Section -->
                <div class="home-section active" id="hero-section">
                  <div class="section-header">
                    <h2>
                      <i class="fas fa-map-marker-alt"></i> Destinations
                      Management
                    </h2>
                    <p class="section-desc">
                      Manage the featured destinations on your homepage.
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
                              <?php foreach($destinations as $index => $destination): ?>
                                <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>"></span>
                              <?php endforeach; ?>
                            </div>
                            <span class="carousel-arrow next">
                              <i class="fas fa-chevron-right"></i>
                            </span>
                          </div>
                          <div class="hero-slides">
                            <?php foreach($destinations as $index => $destination): ?>
                            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                              <img
                                src="<?php echo htmlspecialchars($destination['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($destination['country']); ?>"
                              />
                              <div class="hero-overlay">
                                <h2><?php echo htmlspecialchars($destination['country']); ?></h2>
                                <p><?php echo htmlspecialchars($destination['description']); ?></p>
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
                          <?php foreach($destinations as $index => $destination): ?>
                          <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-target="slide<?php echo $index + 1; ?>">
                            Slide <?php echo $index + 1; ?>
                          </button>
                          <?php endforeach; ?>
                        </div>

                        <?php foreach($destinations as $index => $destination): ?>
                        <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="slide<?php echo $index + 1; ?>">
                          <div class="editor-form">
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-title">Slide Title</label>
                              <input
                                type="text"
                                id="slide<?php echo $index + 1; ?>-title"
                                name="slide<?php echo $index + 1;?>-title"
                                value="<?php echo htmlspecialchars($destination['country']); ?>"
                              />
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-desc">Slide Description</label>
                              <textarea id="slide<?php echo $index + 1; ?>-desc" name="slide<?php echo $index + 1; ?>-desc" rows="3"><?php echo htmlspecialchars($destination['description']); ?></textarea>
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-img">Slide Image</label>
                              <div class="file-upload">
                                <input type="file" id="slide<?php echo $index + 1; ?>-img" name="slide<?php echo $index + 1; ?>-img" />
                                <div class="file-preview">
                                  <img
                                    src="<?php echo $destination['image_url']; ?>"
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
