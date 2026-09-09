<?php
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

$attractions = [];
$query = "SELECT * FROM home_attractions ORDER BY id ASC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $attractions[] = $row;
    }
}
$total_attractions = count($attractions);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Attractions Management - Virunga Admin</title>
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
                <span>Attractions</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-landmark" style="color: #206bc4;"></i>
                Attractions Showcase Management
              </h1>
              <p class="page-subtitle">
                Manage featured wildlife, park attractions, and signature highlights displayed on the homepage.
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
              <span><?php echo $_GET['status'] === 'success' ? 'Attractions updated successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred while saving.'); ?></span>
              <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
          <?php endif; ?>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_attractions; ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-mountain"></i> Active</span>
              </div>
              <span class="kpi-title">Featured Highlights</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">100%</span>
                <span class="kpi-badge badge-green"><i class="fas fa-link"></i> Linked</span>
              </div>
              <span class="kpi-title">Detail Pages Connected</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">Grid</span>
                <span class="kpi-badge badge-purple"><i class="fas fa-grip"></i> Responsive</span>
              </div>
              <span class="kpi-title">Homepage Layout</span>
            </div>
          </div>

          <!-- Main Grid -->
          <form action="../../handlers/home/homeAttractionsHandler.php" method="POST" enctype="multipart/form-data">
            <div class="home-grid-layout">
              
              <!-- Left Column: Interactive Mockup Preview -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-desktop"></i> Live Mockup Preview</h3>
                    <p class="card-subtitle">Real-time simulation of attraction cards</p>
                  </div>
                  <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 8px;"></i> Live</span>
                </div>

                <div class="card-body" style="padding: 1rem;">
                  <div class="preview-mockup-frame">
                    <div class="mockup-header">
                      <div class="mockup-dots">
                        <span></span><span></span><span></span>
                      </div>
                      <div class="mockup-address">virungajourneys.com#attractions</div>
                      <div><i class="fas fa-lock" style="font-size: 10px; color: #94a3b8;"></i></div>
                    </div>

                    <div class="hero-carousel-preview" id="attractionsCarouselPreview">
                      <?php if (!empty($attractions)): ?>
                        <?php foreach ($attractions as $idx => $attraction): ?>
                          <div class="hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" alt="<?php echo htmlspecialchars($attraction['title']); ?>" />
                            <div class="hero-slide-overlay">
                              <h3><?php echo htmlspecialchars($attraction['title']); ?></h3>
                              <div style="margin-top: 8px;">
                                <a href="attraction_details.php?id=<?php echo $attraction['id']; ?>" class="btn btn-sm btn-primary" style="font-size: 11px;">
                                  <i class="fas fa-cog"></i> Manage Story & Gallery
                                </a>
                              </div>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="hero-slide active" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                          <p>No attractions configured.</p>
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
                        <?php foreach ($attractions as $idx => $attraction): ?>
                          <span class="dot-indicator <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $idx; ?>"></span>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-info-circle"></i> Each attraction has a dedicated story & photo gallery manager.
                  </span>
                </div>
              </div>

              <!-- Right Column: Attractions Editor Card -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Attraction Content Editor</h3>
                    <p class="card-subtitle">Select an attraction card below to edit</p>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save All
                  </button>
                </div>

                <div class="card-body">
                  <?php if (!empty($attractions)): ?>
                    <!-- Pill Tabs Header -->
                    <div class="pills-tab-header">
                      <?php foreach ($attractions as $idx => $attraction): ?>
                        <button type="button" class="pill-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-target="attrTab<?php echo $idx + 1; ?>">
                          <i class="fas fa-mountain-sun"></i> Card <?php echo $idx + 1; ?>
                        </button>
                      <?php endforeach; ?>
                    </div>

                    <!-- Tab Panes -->
                    <?php foreach ($attractions as $idx => $attraction): 
                      $slide_num = $idx + 1;
                    ?>
                      <div class="tab-pane <?php echo $idx === 0 ? 'active' : ''; ?>" id="attrTab<?php echo $slide_num; ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; background: #f8fafc; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                          <span style="font-weight: 600; font-size: 13px; color: #0f172a;">Attraction Card #<?php echo $slide_num; ?></span>
                          <a href="attraction_details.php?id=<?php echo $attraction['id']; ?>" class="btn btn-sm btn-outline">
                            <i class="fas fa-images"></i> Manage Full Story & Gallery
                          </a>
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="attr<?php echo $slide_num; ?>Title">Attraction Title / Headline</label>
                          <input 
                            type="text" 
                            id="attr<?php echo $slide_num; ?>Title" 
                            name="slide<?php echo $slide_num; ?>-title" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($attraction['title']); ?>" 
                            required 
                          />
                        </div>

                        <div class="form-group">
                          <label class="form-label">Cover Thumbnail Image</label>
                          <div class="dropzone-box">
                            <input 
                              type="file" 
                              id="attr<?php echo $slide_num; ?>Img" 
                              name="slide<?php echo $slide_num; ?>-img" 
                              accept="image/jpeg,image/png,image/webp" 
                            />
                            <div class="dropzone-thumb-wrap">
                              <img src="<?php echo htmlspecialchars($attraction['image_url']); ?>" alt="Current Attraction Image" />
                            </div>
                            <div class="dropzone-content-prompt">
                              <i class="fas fa-cloud-arrow-up"></i>
                              <span>Click or drag image to replace (800x600px JPG/PNG)</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <p style="color: #64748b; text-align: center; padding: 2rem 0;">No attractions configured.</p>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-shield-alt"></i> Changes save instantly to database.
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
