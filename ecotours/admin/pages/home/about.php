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
$aboutData = $result ? $result->fetch_assoc() : [
    'title' => 'Pedal toward new horizons!',
    'slide_description' => 'Discover the thrill of cycling through breathtaking landscapes and scenic trails across Africa\'s most beautiful communities.',
    'youtube_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ'
];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About Us Section - Virunga Admin</title>
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
                <span>About Us</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-info-circle" style="color: #206bc4;"></i>
                About Us Section Management
              </h1>
              <p class="page-subtitle">
                Manage the brand story headline, mission narrative, and featured YouTube video displayed on the homepage.
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
              <span><?php echo $_GET['status'] === 'success' ? 'About Us section saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred while saving.'); ?></span>
              <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
          <?php endif; ?>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">Active</span>
                <span class="kpi-badge badge-green"><i class="fas fa-check-circle"></i> Live</span>
              </div>
              <span class="kpi-title">Section Status</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">YouTube</span>
                <span class="kpi-badge badge-rose"><i class="fab fa-youtube"></i> HD Embed</span>
              </div>
              <span class="kpi-title">Video Host</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">16:9</span>
                <span class="kpi-badge badge-blue"><i class="fas fa-tv"></i> Responsive</span>
              </div>
              <span class="kpi-title">Video Aspect Ratio</span>
            </div>
          </div>

          <!-- Main Grid (Preview & Editor) -->
          <form action="../../handlers/home/homeAboutHandler.php" method="POST">
            <div class="home-grid-layout">
              
              <!-- Left Column: Video Mockup Preview -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-desktop"></i> Video Player Preview</h3>
                    <p class="card-subtitle">Real-time simulation of the About video player</p>
                  </div>
                  <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 8px;"></i> Live</span>
                </div>

                <div class="card-body" style="padding: 1rem;">
                  <div class="preview-mockup-frame">
                    <div class="mockup-header">
                      <div class="mockup-dots">
                        <span></span><span></span><span></span>
                      </div>
                      <div class="mockup-address">virungajourneys.com#about</div>
                      <div><i class="fas fa-lock" style="font-size: 10px; color: #94a3b8;"></i></div>
                    </div>

                    <div style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; background: #000;">
                      <iframe 
                        src="<?php echo htmlspecialchars($aboutData['youtube_url']); ?>" 
                        title="About Video Preview" 
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                      ></iframe>
                    </div>
                  </div>

                  <div style="margin-top: 1rem; padding: 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; margin: 0 0 4px;"><?php echo htmlspecialchars($aboutData['title']); ?></h4>
                    <p style="font-size: 12.5px; color: #64748b; margin: 0; line-height: 1.5;"><?php echo htmlspecialchars($aboutData['slide_description']); ?></p>
                  </div>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-info-circle"></i> Embed player scales responsively on mobile, tablet, and desktop screens.
                  </span>
                </div>
              </div>

              <!-- Right Column: Content Editor Card -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Story & Media Content</h3>
                    <p class="card-subtitle">Customize the title, descriptive narrative, and video link</p>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save Changes
                  </button>
                </div>

                <div class="card-body">
                  <div class="form-group">
                    <label class="form-label" for="aboutTitle">Headline / Title</label>
                    <input 
                      type="text" 
                      id="aboutTitle" 
                      name="slide_title" 
                      class="form-control" 
                      value="<?php echo htmlspecialchars($aboutData['title'] ?? 'Pedal toward new horizons!'); ?>" 
                      required 
                    />
                    <span class="form-hint">Inspiring header for the story section.</span>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="aboutDesc">Mission Narrative / Description</label>
                    <textarea 
                      id="aboutDesc" 
                      name="slide_description" 
                      class="form-control" 
                      rows="4" 
                      required
                    ><?php echo htmlspecialchars($aboutData['slide_description'] ?? ''); ?></textarea>
                    <span class="form-hint">A comprehensive paragraph articulating the company's ethos, community values, and journeys.</span>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="aboutVideo">YouTube Embed URL</label>
                    <input 
                      type="url" 
                      id="aboutVideo" 
                      name="youtube_url" 
                      class="form-control" 
                      placeholder="https://www.youtube.com/embed/VIDEO_ID" 
                      value="<?php echo htmlspecialchars($aboutData['youtube_url'] ?? ''); ?>" 
                      required 
                    />
                    <span class="form-hint">
                      <i class="fab fa-youtube" style="color: #ef4444;"></i>
                      Must be an embed URL: <code>https://www.youtube.com/embed/YOUR_VIDEO_ID</code>
                    </span>
                  </div>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-shield-alt"></i> Changes reflect immediately upon saving.
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

    <?php if (isset($conn) && $conn instanceof mysqli) $conn->close(); ?>
  </body>
</html>
