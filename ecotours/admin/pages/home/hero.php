<?php 
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

require_once('../../config/connection.php');

// Fetch all hero slides
$slides_query = "SELECT * FROM home_hero ORDER BY id ASC";
$slides_result = $conn->query($slides_query);
$hero_slides = [];
if ($slides_result && $slides_result->num_rows > 0) {
    while ($row = $slides_result->fetch_assoc()) {
        $hero_slides[] = $row;
    }
}
$total_slides = count($hero_slides);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hero Carousel - Virunga Admin</title>
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
                <span>Hero Carousel</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-image" style="color: #206bc4;"></i>
                Hero Carousel Management
              </h1>
              <p class="page-subtitle">
                Manage the full-screen visual hero carousel and messaging on your website homepage.
              </p>
            </div>

            <div class="page-actions-wrap">
              <a href="../../../index.php" target="_blank" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> Live Site
              </a>
              <button type="button" class="btn btn-primary btn-sm" onclick="openModal('addHeroSlideModal')">
                <i class="fas fa-plus"></i> Add New Slide
              </button>
            </div>
          </div>

          <!-- Status Alert Banner -->
          <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
              <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
              <span><?php echo $_GET['status'] === 'success' ? 'Hero carousel updated successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred while saving.'); ?></span>
              <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
          <?php endif; ?>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_slides; ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-images"></i> Active</span>
              </div>
              <span class="kpi-title">Configured Hero Slides</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">1080p</span>
                <span class="kpi-badge badge-green"><i class="fas fa-check"></i> HD Ready</span>
              </div>
              <span class="kpi-title">Recommended Resolution</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">Auto</span>
                <span class="kpi-badge badge-amber"><i class="fas fa-clock"></i> 4.5s</span>
              </div>
              <span class="kpi-title">Transition Speed</span>
            </div>
          </div>

          <!-- Main Grid (Preview & Editor) -->
          <form action="../../handlers/home/updateHomeHero.php" method="POST" enctype="multipart/form-data">
            <div class="home-grid-layout">
              
              <!-- Left Column: Interactive Live Preview Mockup -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-desktop"></i> Live Mockup Preview</h3>
                    <p class="card-subtitle">Real-time simulation of the homepage hero carousel</p>
                  </div>
                  <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 8px;"></i> Live</span>
                </div>
                
                <div class="card-body" style="padding: 1rem;">
                  <div class="preview-mockup-frame">
                    <div class="mockup-header">
                      <div class="mockup-dots">
                        <span></span><span></span><span></span>
                      </div>
                      <div class="mockup-address">virungajourneys.com/home</div>
                      <div><i class="fas fa-lock" style="font-size: 10px; color: #94a3b8;"></i></div>
                    </div>

                    <div class="hero-carousel-preview" id="heroCarouselPreview">
                      <?php if (!empty($hero_slides)): ?>
                        <?php foreach ($hero_slides as $idx => $slide): ?>
                          <div class="hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
                            <img src="<?php echo htmlspecialchars($slide['image_url']); ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>" />
                            <div class="hero-slide-overlay">
                              <h3><?php echo htmlspecialchars($slide['title']); ?></h3>
                              <p><?php echo htmlspecialchars($slide['description']); ?></p>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <div class="hero-slide active" style="display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                          <p>No slides found. Click "Add New Slide" to begin.</p>
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
                        <?php foreach ($hero_slides as $idx => $slide): ?>
                          <span class="dot-indicator <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide-index="<?php echo $idx; ?>"></span>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-info-circle"></i> Carousel rotates automatically every 4.5 seconds.
                  </span>
                </div>
              </div>

              <!-- Right Column: Slide Editor Card -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Slide Content Editor</h3>
                    <p class="card-subtitle">Select a slide below to modify its title, text, and imagery</p>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save All
                  </button>
                </div>

                <div class="card-body">
                  <?php if (!empty($hero_slides)): ?>
                    <!-- Pill Tabs Header -->
                    <div class="pills-tab-header">
                      <?php foreach ($hero_slides as $idx => $slide): ?>
                        <button type="button" class="pill-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-target="slideTab<?php echo $slide['id']; ?>">
                          <i class="fas fa-image"></i> Slide <?php echo $idx + 1; ?>
                        </button>
                      <?php endforeach; ?>
                    </div>

                    <!-- Tab Panes -->
                    <?php foreach ($hero_slides as $idx => $slide): 
                      $slide_num = $idx + 1;
                    ?>
                      <div class="tab-pane <?php echo $idx === 0 ? 'active' : ''; ?>" id="slideTab<?php echo $slide['id']; ?>">
                        <input type="hidden" name="slide_id_<?php echo $slide_num; ?>" value="<?php echo $slide['id']; ?>" />
                        
                        <div class="form-group">
                          <label class="form-label" for="slide<?php echo $slide_num; ?>Title">Slide Heading / Title</label>
                          <input 
                            type="text" 
                            id="slide<?php echo $slide_num; ?>Title" 
                            name="slide<?php echo $slide_num; ?>-title" 
                            class="form-control" 
                            value="<?php echo htmlspecialchars($slide['title']); ?>" 
                            required 
                          />
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="slide<?php echo $slide_num; ?>Desc">Slide Subheadline / Description</label>
                          <textarea 
                            id="slide<?php echo $slide_num; ?>Desc" 
                            name="slide<?php echo $slide_num; ?>-desc" 
                            class="form-control" 
                            rows="3"
                          ><?php echo htmlspecialchars($slide['description']); ?></textarea>
                          <span class="form-hint">Brief subtitle displayed over the hero banner.</span>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Hero Background Image</label>
                          <div class="dropzone-box">
                            <input 
                              type="file" 
                              id="slide<?php echo $slide_num; ?>Img" 
                              name="slide<?php echo $slide_num; ?>-img" 
                              accept="image/jpeg,image/png,image/webp" 
                            />
                            <div class="dropzone-thumb-wrap">
                              <img src="<?php echo htmlspecialchars($slide['image_url']); ?>" alt="Current Hero Banner" />
                            </div>
                            <div class="dropzone-content-prompt">
                              <i class="fas fa-cloud-arrow-up"></i>
                              <span>Click or drag image to replace (1920x1080px JPG/PNG)</span>
                            </div>
                          </div>
                        </div>

                        <?php if ($total_slides > 1): ?>
                          <div style="display: flex; justify-content: flex-end; margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                            <button type="button" class="btn btn-danger-outline btn-sm" onclick="deleteHeroSlide(<?php echo $slide['id']; ?>)">
                              <i class="fas fa-trash-alt"></i> Delete Slide <?php echo $slide_num; ?>
                            </button>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <p style="color: #64748b; text-align: center; padding: 2rem 0;">No hero slides found in database.</p>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-shield-alt"></i> Changes apply immediately to the website hero banner upon saving.
                  </span>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save Changes
                  </button>
                </div>
              </div>
            </div>
          </form>

        </div>
      </main>
    </div>

    <!-- Modern Add Hero Slide Modal -->
    <div id="addHeroSlideModal" class="modal-backdrop-custom">
      <div class="modal-card">
        <div class="modal-header-custom">
          <h3><i class="fas fa-plus-circle" style="color: #206bc4; margin-right: 6px;"></i> Add New Hero Slide</h3>
          <button type="button" class="modal-close-btn" onclick="closeModal('addHeroSlideModal')">&times;</button>
        </div>
        <form id="addHeroSlideForm" action="../../handlers/home/addHeroSlideHandler.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body-custom">
            <div class="form-group">
              <label class="form-label" for="newSlideTitle">Slide Title</label>
              <input type="text" id="newSlideTitle" name="title" class="form-control" required placeholder="e.g. Experience the Living Virunga" />
            </div>

            <div class="form-group">
              <label class="form-label" for="newSlideDescription">Slide Description</label>
              <textarea id="newSlideDescription" name="description" class="form-control" rows="3" required placeholder="e.g. Private encounters with the people, landscapes and traditions..."></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Upload High-Res Slide Image</label>
              <div class="dropzone-box">
                <input type="file" id="newSlideImage" name="image" required accept="image/jpeg,image/png,image/webp" />
                <div class="dropzone-thumb-wrap" style="display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #94a3b8;">
                  <i class="fas fa-image" style="font-size: 32px;"></i>
                </div>
                <div class="dropzone-content-prompt">
                  <i class="fas fa-cloud-arrow-up"></i>
                  <span>Choose 1920x1080px Image (Max 2MB)</span>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer-custom">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addHeroSlideModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Add Slide
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php if (isset($conn) && $conn instanceof mysqli) $conn->close(); ?>
  </body>
</html>
