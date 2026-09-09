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
$total_destinations = count($destinations);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Destinations Management - Virunga Admin</title>
    <link rel="shortcut icon" href="../../images/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../../css/common.css" />
    <link rel="stylesheet" href="../../css/home.css" />
    <script src="../../js/common.js" defer></script>
    <script src="../../js/home.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <?php include_once './include/sidebar.php'; ?>
      
      <main class="main-content">
        <?php include_once './include/header.php'; ?>

        <div class="home-management-container">
          
          <!-- Page Header Row -->
          <div class="page-header-row">
            <div class="page-title-wrap">
              <div class="breadcrumb-trail">
                <a href="../../index.php">Dashboard</a>
                <span>/</span>
                <a href="#">Home</a>
                <span>/</span>
                <span>Destinations</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-map-marker-alt" style="color: #206bc4;"></i>
                Destinations Management
              </h1>
              <p class="page-subtitle">
                Manage the featured countries and destination highlights showcased on the homepage.
              </p>
            </div>

            <div class="page-actions-wrap">
              <a href="../../../index.php" target="_blank" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> View Live Site
              </a>
            </div>
          </div>

          <!-- Status Alert Banner -->
          <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
              <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
              <span><?php echo $_GET['status'] === 'success' ? 'Destinations updated successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred while saving.'); ?></span>
              <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
          <?php endif; ?>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_destinations; ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-globe-africa"></i> Live</span>
              </div>
              <span class="kpi-title">Featured Destinations</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">16:9</span>
                <span class="kpi-badge badge-green"><i class="fas fa-check"></i> Standard</span>
              </div>
              <span class="kpi-title">Card Aspect Ratio</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">High</span>
                <span class="kpi-badge badge-amber"><i class="fas fa-bolt"></i> Fast CDN</span>
              </div>
              <span class="kpi-title">Homepage Visibility</span>
            </div>
          </div>

          <!-- Main Form & Grid -->
          <form action="../../handlers/home/homeDestinationHandler.php" method="POST" enctype="multipart/form-data">
            <div class="home-grid-layout">
              
              <!-- Left Column: Interactive Mockup Preview -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-desktop"></i> Live Mockup Preview</h3>
                    <p class="card-subtitle">Real-time simulation of destination showcase</p>
                  </div>
                  <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 8px;"></i> Live</span>
                </div>

                <div class="card-body" style="padding: 1rem;">
                  <div class="preview-mockup-frame">
                    <div class="mockup-header">
                      <div class="mockup-dots">
                        <span></span><span></span><span></span>
                      </div>
                      <div class="mockup-address">virungajourneys.com#destinations</div>
                      <div><i class="fas fa-lock" style="font-size: 10px; color: #94a3b8;"></i></div>
                    </div>

                    <div class="hero-carousel-preview" id="destCarouselPreview">
                      <?php if (!empty($destinations)): ?>
                        <?php foreach ($destinations as $idx => $destination): ?>
                          <div class="hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" alt="<?php echo htmlspecialchars($destination['country']); ?>" />
                            <div class="hero-slide-overlay">
                              <h3><?php echo htmlspecialchars($destination['country']); ?></h3>
                              <p><?php echo htmlspecialchars($destination['description']); ?></p>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="hero-slide active" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                          <p>No destinations found.</p>
                        </div>
                      <?php endif; ?>

                      <!-- Navigation Controls -->
                      <div class="carousel-nav-arrows">
                        <button type="button" class="nav-arrow-btn" id="prevSlideBtn" title="Previous Slide">
                          <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="nav-arrow-btn" id="nextSlideBtn" title="Next Slide">
                          <i class="fas fa-chevron-right"></i>
                        </button>
                      </div>

                      <div class="carousel-dots-bar">
                        <?php foreach ($destinations as $idx => $destination): ?>
                          <span class="dot-indicator <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $idx; ?>"></span>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-info-circle"></i> Use the tabs on the right to edit each country's title, text, and imagery.
                  </span>
                </div>
              </div>

              <!-- Right Column: Destination Editor Card -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Destination Editor</h3>
                    <p class="card-subtitle">Select a destination below to customize content</p>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save All Changes
                  </button>
                </div>

                <div class="card-body">
                  <?php if (!empty($destinations)): ?>
                    <!-- Pill Tabs Header -->
                    <div class="pills-tab-header">
                      <?php foreach ($destinations as $idx => $destination): ?>
                        <button type="button" class="pill-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-target="destTab<?php echo $idx + 1; ?>">
                          <i class="fas fa-location-dot"></i> Destination <?php echo $idx + 1; ?>
                        </button>
                      <?php endforeach; ?>
                    </div>

                    <!-- Tab Panes -->
                    <?php foreach ($destinations as $idx => $destination): 
                      $slide_num = $idx + 1;
                    ?>
                      <div class="tab-pane <?php echo $idx === 0 ? 'active' : ''; ?>" id="destTab<?php echo $slide_num; ?>">
                        <div class="form-group">
                          <label class="form-label" for="dest<?php echo $slide_num; ?>Title">Country / Region Name</label>
                          <input 
                            type="text" 
                            id="dest<?php echo $slide_num; ?>Title" 
                            name="slide<?php echo $slide_num; ?>-title" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($destination['country']); ?>" 
                            required 
                          />
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="dest<?php echo $slide_num; ?>Desc">Destination Summary</label>
                          <textarea 
                            id="dest<?php echo $slide_num; ?>Desc" 
                            name="slide<?php echo $slide_num; ?>-desc" 
                            class="form-control" 
                            rows="3"
                          ><?php echo htmlspecialchars($destination['description']); ?></textarea>
                          <span class="form-hint">Highlight key attractions and highlights for this destination.</span>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Destination Cover Image</label>
                          <div class="dropzone-box">
                            <input 
                              type="file" 
                              id="dest<?php echo $slide_num; ?>Img" 
                              name="slide<?php echo $slide_num; ?>-img" 
                              accept="image/jpeg,image/png,image/webp" 
                            />
                            <div class="dropzone-thumb-wrap">
                              <img src="<?php echo htmlspecialchars($destination['image_url']); ?>" alt="Current Destination Image" />
                            </div>
                            <div class="dropzone-content-prompt">
                              <i class="fas fa-cloud-arrow-up"></i>
                              <span>Click or drag image to replace (1920x1080px JPG/PNG)</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <p style="color: #64748b; text-align: center; padding: 2rem 0;">No destinations found.</p>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-shield-alt"></i> Changes save instantly to database and reflect on the homepage.
                  </span>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save All Changes
                  </button>
                </div>
              </div>
            </div>
          </form>

        </div>
      </main>
    </div>

    <?php if (isset($conn) && $conn instanceof mysqli) $conn->close(); ?>
  </body>
</html>
