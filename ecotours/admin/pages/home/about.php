<?php
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

// Fetch about section data
$sql = "SELECT * FROM home_about LIMIT 1";
$result = $conn->query($sql);
$aboutData = $result->fetch_assoc();
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
            action="../../handlers/home/homeAboutHandler.php"
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
                <!-- About Section -->
                <div class="home-section active" id="hero-section">
                  <div class="section-header">
                    <h2>
                      <i class="fas fa-info-circle"></i> About Us Section
                      Management
                    </h2>
                    <p class="section-desc">
                      Manage the About Us section on your homepage.
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
                          <div class="hero-slides">
                            <div class="hero-slide active">
                              <div class="video-container">
                                <iframe src="<?php echo htmlspecialchars($aboutData['youtube_url']); ?>" title="Aimecol" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="content-editor">
                      <div class="tabs-container">
                        <div class="tabs-header">
                          <button class="tab-btn active" data-target="slide1">
                            Slide 1
                          </button>
                        </div>

                        <div class="tab-content active" id="slide1">
                          <div class="editor-form">
                            <div class="form-group">
                              <label for="slide1-title">Slide Title</label>
                              <input
                                type="text"
                                id="slide1-title"
                                name="slide_title"
                                value="<?php echo htmlspecialchars($aboutData['title'] ?? 'Pedal toward new horizons!'); ?>"
                              />
                            </div>
                            <div class="form-group">
                              <label for="slide1-desc">Slide Description</label>
                              <textarea id="slide1-desc" name="slide_description" rows="3"><?php echo htmlspecialchars($aboutData['slide_description'] ?? "Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa's most beautiful communities."); ?></textarea>
                            </div>
                            <div class="form-group">
                              <label for="slide1-video">YouTube Embed URL</label>
                              <input
                                type="text"
                                id="slide1-video"
                                name="youtube_url"
                                placeholder="https://www.youtube.com/embed/VIDEO_ID"
                                value="<?php echo htmlspecialchars($aboutData['youtube_url']); ?>"
                              />
                              <p class="input-help">
                                Paste the YouTube embed URL (e.g. https://www.youtube.com/embed/VIDEO_ID)
                              </p>
                            </div>
                          </div>
                        </div>
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
<?php
// Close connection at the very end
$conn->close();
?>
