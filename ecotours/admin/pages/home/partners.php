<?php
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

$partners = [];
$query = "SELECT * FROM home_partners ORDER BY id ASC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $partners[] = $row;
    }
}
$total_partners = count($partners);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Partners Management - Virunga Admin</title>
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
                <span>Partners</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-handshake" style="color: #206bc4;"></i>
                Partners & Affiliates Management
              </h1>
              <p class="page-subtitle">
                Manage global travel networks, conservation alliances, and eco-tourism partner logos displayed on the homepage.
              </p>
            </div>

            <div class="page-actions-wrap">
              <a href="../../../index.php" target="_blank" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> View Live Site
              </a>
              <button type="button" class="btn btn-primary btn-sm" onclick="openModal('addPartnerModal')">
                <i class="fas fa-plus"></i> Add New Partner
              </button>
            </div>
          </div>

          <!-- Status Alert Banner -->
          <?php if (isset($_GET['status'])): ?>
            <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
              <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
              <span><?php echo $_GET['status'] === 'success' ? 'Partner changes saved successfully!' : htmlspecialchars($_GET['message'] ?? 'An error occurred while saving.'); ?></span>
              <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
            </div>
          <?php endif; ?>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_partners; ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-handshake-angle"></i> Affiliates</span>
              </div>
              <span class="kpi-title">Active Partner Logos</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">PNG / SVG</span>
                <span class="kpi-badge badge-green"><i class="fas fa-check"></i> Transparent</span>
              </div>
              <span class="kpi-title">Recommended Logo Format</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num">Carousel</span>
                <span class="kpi-badge badge-amber"><i class="fas fa-infinity"></i> Continuous</span>
              </div>
              <span class="kpi-title">Display Layout</span>
            </div>
          </div>

          <!-- Main Grid -->
          <form action="../../handlers/home/homePartnersHandler.php" method="POST" enctype="multipart/form-data">
            <div class="home-grid-layout">
              
              <!-- Left Column: Partners Overview Grid & Mockup Preview -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-desktop"></i> Partners Showcase</h3>
                    <p class="card-subtitle">Current partner logos and destination links</p>
                  </div>
                  <span class="badge badge-green"><i class="fas fa-circle" style="font-size: 8px;"></i> Live</span>
                </div>

                <div class="card-body">
                  <?php if (!empty($partners)): ?>
                    <div class="items-cards-grid" style="grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));">
                      <?php foreach ($partners as $idx => $partner): ?>
                        <div class="item-thumb-card" style="align-items: center; text-align: center;">
                          <div style="height: 90px; width: 100%; display: flex; align-items: center; justify-content: center; padding: 12px; background: #ffffff; box-sizing: border-box;">
                            <img 
                              src="<?php echo htmlspecialchars($partner['logo_url']); ?>" 
                              alt="Partner Logo" 
                              style="max-height: 100%; max-width: 100%; object-fit: contain;" 
                              onerror="this.src='../../images/icon.png';"
                            />
                          </div>
                          <div class="item-thumb-body" style="padding: 8px 12px; width: 100%; box-sizing: border-box;">
                            <a href="<?php echo htmlspecialchars($partner['web_url']); ?>" target="_blank" style="font-size: 11.5px; color: #206bc4; text-decoration: none; word-break: break-all; display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                              <i class="fas fa-external-link-alt"></i> <?php echo htmlspecialchars($partner['web_url'] ?: 'No website link'); ?>
                            </a>
                          </div>
                          <div class="item-thumb-actions" style="width: 100%; box-sizing: border-box;">
                            <span class="badge badge-blue" style="font-size: 10px;">#<?php echo $idx + 1; ?></span>
                            <button type="button" class="btn btn-danger-outline btn-sm" style="padding: 3px 8px; font-size: 11px;" onclick="confirmDeletePartner(<?php echo $partner['id']; ?>)">
                              <i class="fas fa-trash-alt"></i> Delete
                            </button>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <div style="text-align: center; padding: 2.5rem 1rem; color: #64748b;">
                      <i class="fas fa-handshake-slash" style="font-size: 36px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                      <p>No partners configured yet. Click "Add New Partner" to create one.</p>
                    </div>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-info-circle"></i> High-resolution logos with transparent backgrounds give the best visual impact.
                  </span>
                </div>
              </div>

              <!-- Right Column: Partners Tabbed Editor -->
              <div class="card">
                <div class="card-header">
                  <div>
                    <h3 class="card-title"><i class="fas fa-sliders"></i> Edit Partner Details</h3>
                    <p class="card-subtitle">Update website links and replace logos</p>
                  </div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Save All
                  </button>
                </div>

                <div class="card-body">
                  <?php if (!empty($partners)): ?>
                    <!-- Pill Tabs Header -->
                    <div class="pills-tab-header">
                      <?php foreach ($partners as $idx => $partner): ?>
                        <button type="button" class="pill-tab-btn <?php echo $idx === 0 ? 'active' : ''; ?>" data-target="partnerTab<?php echo $idx + 1; ?>">
                          <i class="fas fa-handshake"></i> Partner <?php echo $idx + 1; ?>
                        </button>
                      <?php endforeach; ?>
                    </div>

                    <!-- Tab Panes -->
                    <?php foreach ($partners as $idx => $partner): 
                      $slide_num = $idx + 1;
                    ?>
                      <div class="tab-pane <?php echo $idx === 0 ? 'active' : ''; ?>" id="partnerTab<?php echo $slide_num; ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; background: #f8fafc; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0;">
                          <span style="font-weight: 600; font-size: 13px; color: #0f172a;">Partner Record #<?php echo $slide_num; ?></span>
                          <button type="button" class="btn btn-danger-outline btn-sm" onclick="confirmDeletePartner(<?php echo $partner['id']; ?>)">
                            <i class="fas fa-trash-alt"></i> Delete Partner
                          </button>
                        </div>

                        <div class="form-group">
                          <label class="form-label" for="partner<?php echo $slide_num; ?>Url">Partner Website URL</label>
                          <input 
                            type="url" 
                            id="partner<?php echo $slide_num; ?>Url" 
                            name="slide<?php echo $slide_num; ?>-title" 
                            class="form-control" 
                            placeholder="https://example.com" 
                            value="<?php echo htmlspecialchars($partner['web_url']); ?>" 
                          />
                          <span class="form-hint">Destination link opened when visitor clicks the partner logo.</span>
                        </div>

                        <div class="form-group">
                          <label class="form-label">Partner Logo</label>
                          <div class="dropzone-box">
                            <input 
                              type="file" 
                              id="partner<?php echo $slide_num; ?>Img" 
                              name="slide<?php echo $slide_num; ?>-img" 
                              accept="image/png,image/jpeg,image/svg+xml,image/webp" 
                            />
                            <div class="dropzone-thumb-wrap" style="background: #ffffff; display: flex; align-items: center; justify-content: center; padding: 10px; box-sizing: border-box;">
                              <img src="<?php echo htmlspecialchars($partner['logo_url']); ?>" alt="Current Partner Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;" />
                            </div>
                            <div class="dropzone-content-prompt">
                              <i class="fas fa-cloud-arrow-up"></i>
                              <span>Click or drag to replace logo (PNG/SVG recommended)</span>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <p style="color: #64748b; text-align: center; padding: 2rem 0;">No partners added yet.</p>
                  <?php endif; ?>
                </div>

                <div class="card-footer">
                  <span style="font-size: 12px; color: #64748b;">
                    <i class="fas fa-shield-alt"></i> Changes apply immediately upon saving.
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

    <!-- Modern Add Partner Modal -->
    <div id="addPartnerModal" class="modal-backdrop-custom">
      <div class="modal-card">
        <div class="modal-header-custom">
          <h3><i class="fas fa-plus-circle" style="color: #206bc4; margin-right: 6px;"></i> Add New Partner</h3>
          <button type="button" class="modal-close-btn" onclick="closeModal('addPartnerModal')">&times;</button>
        </div>
        <form id="addPartnerForm" action="../../handlers/home/addPartnerHandler.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body-custom">
            <div class="form-group">
              <label class="form-label" for="newPartnerWebsite">Website URL</label>
              <input type="url" id="newPartnerWebsite" name="website" class="form-control" required placeholder="https://partner-organization.org" />
            </div>

            <div class="form-group">
              <label class="form-label">Upload Partner Logo</label>
              <div class="dropzone-box">
                <input type="file" id="newPartnerLogo" name="logo" required accept="image/png,image/jpeg,image/svg+xml,image/webp" />
                <div class="dropzone-thumb-wrap" style="display: flex; align-items: center; justify-content: center; background: #ffffff; color: #94a3b8;">
                  <i class="fas fa-image" style="font-size: 32px;"></i>
                </div>
                <div class="dropzone-content-prompt">
                  <i class="fas fa-cloud-arrow-up"></i>
                  <span>Choose Transparent PNG or SVG (Max 2MB)</span>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer-custom">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('addPartnerModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Add Partner
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php if (isset($conn) && $conn instanceof mysqli) $conn->close(); ?>
  </body>
</html>
