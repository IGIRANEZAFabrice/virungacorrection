<?php
require_once('../../config/connection.php');

session_start();

// if (!isset($_SESSION['admin_id'])) {
//   header("Location: ../login.html");
//   exit();
// }

$partners = [];
$query = "SELECT * FROM home_partners";
$result = mysqli_query($conn, $query);
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $partners[] = $row;
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
            action="../../handlers/home/homePartnersHandler.php"
            method="post"
            enctype="multipart/form-data"
          >
            <div class="panel active" id="home-schema-panel">
              <div class="panel-header">
                <h1>Home Page Management</h1>
                <div class="panel-actions">
                  <button class="action-button" type="submit">
                    <i class="fas fa-save"></i> Save Changes
                  </button>
                  <button type="button" class="action-button add-partner-btn" onclick="openAddPartnerModal()">
                    <i class="fas fa-plus"></i> Add New Partner
                  </button>
                </div>
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
                      <i class="fas fa-landmark"></i> Partners Management
                    </h2>
                    <p class="section-desc">
                      Manage the partners section on the home page.
                    </p>
                  </div>

                  <div class="section-content">
                    <div class="content-preview">
                      <div class="preview-header">
                        <h3>Current Partners</h3>
                        
                      </div>
                      <div class="preview-container">
                        <div class="hero-carousel">
                          <div class="carousel-navigation">
                            <span class="carousel-arrow prev">
                              <i class="fas fa-chevron-left"></i>
                            </span>
                            <div class="carousel-indicators">
                              <?php foreach($partners as $index => $partner): ?>
                              <span class="indicator <?php echo $index === 0 ? 'active' : ''; ?>"></span>
                              <?php endforeach; ?>
                            </div>
                            <span class="carousel-arrow next">
                              <i class="fas fa-chevron-right"></i>
                            </span>
                          </div>
                          <div class="hero-slides">
                            <?php foreach($partners as $index => $partner): ?>
                            <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                              <img
                                src="<?php echo htmlspecialchars($partner['logo_url']); ?>"
                                alt="<?php echo htmlspecialchars($partner['web_url']); ?>"
                              />
                              <div class="hero-overlay">
                                <h2><?php echo htmlspecialchars($partner['web_url']); ?></h2>
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
                          <?php foreach($partners as $index => $partner): ?>
                          <div class="tab-container">
                            <button class="tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-target="slide<?php echo $index + 1; ?>">
                              Paterner <?php echo $index + 1; ?>
                            </button>
                          </div>
                          <?php endforeach; ?>
                        </div>

                        <?php foreach($partners as $index => $partner): ?>
                        <div class="tab-content <?php echo $index === 0 ? 'active' : ''; ?>" id="slide<?php echo $index + 1; ?>">
                          <div class="editor-form">
                            <div class="form-group-header">
                              <h3>Partner Details</h3>
                              <button type="button" class="delete-btn" onclick="confirmDeletePartner(<?php echo $partner['id']; ?>)">
                                <i class="fas fa-trash"></i>
                                <span class="tooltip">Delete Partner</span>
                              </button>
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-title">website link</label>
                              <input
                                type="text"
                                id="slide<?php echo $index + 1; ?>-title"
                                name="slide<?php echo $index + 1; ?>-title"
                                value="<?php echo htmlspecialchars($partner['web_url']); ?>"
                              />
                            </div>
                            <div class="form-group">
                              <label for="slide<?php echo $index + 1; ?>-img">Company logo</label>
                              <div class="file-upload">
                                <input type="file" id="slide<?php echo $index + 1; ?>-img" name="slide<?php echo $index + 1; ?>-img" />
                                <div class="file-preview">
                                  <img
                                    src="<?php echo htmlspecialchars($partner['logo_url']); ?>"
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

          <!-- Add Partner Modal -->
          <div id="addPartnerModal" class="modal">
            <div class="modal-content">
              <span class="close" onclick="closeAddPartnerModal()">&times;</span>
              <h2>Add New Partner</h2>
              <form id="addPartnerForm" action="../../handlers/home/addPartnerHandler.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="partnerWebsite">Website URL</label>
                  <input type="url" id="partnerWebsite" name="website" required placeholder="https://example.com">
                </div>
                <div class="form-group">
                  <label for="partnerLogo">Company Logo</label>
                  <div class="file-upload">
                    <input type="file" id="partnerLogo" name="logo" required accept="image/*">
                    <div class="file-preview" id="logoPreview">
                      <p>No image selected</p>
                    </div>
                    <button type="button" class="file-button" onclick="document.getElementById('partnerLogo').click()">
                      <i class="fas fa-upload"></i> Choose File
                    </button>
                  </div>
                  <p class="input-help">Recommended size: 200x100px, max file size: 1MB</p>
                </div>
                <div class="form-actions">
                  <button type="submit" class="action-button">
                    <i class="fas fa-save"></i> Save Partner
                  </button>
                </div>
              </form>
            </div>
          </div>

          <style>
            .panel-actions {
              display: flex;
              gap: 10px;
            }
            
            .modal {
              display: none;
              position: fixed;
              z-index: 1000;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              background-color: rgba(0,0,0,0.5);
              overflow-y: scroll;
            }

            .modal-content {
              background-color: #fefefe;
              margin: 15% auto;
              padding: 20px;
              border: 1px solid #888;
              width: 80%;
              max-width: 500px;
              border-radius: 8px;
              position: relative;
              overflow-y: scroll;
            }

            .close {
              position: absolute;
              right: 15px;
              top: 10px;
              font-size: 28px;
              cursor: pointer;
            }

            .form-actions {
              margin-top: 20px;
              text-align: right;
            }

            #logoPreview img {
              max-width: 200px;
              max-height: 100px;
              object-fit: contain;
            }

            .tab-container {
              display: flex;
              align-items: center;
              gap: 5px;
            }

            .form-group-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 20px;
              padding-bottom: 10px;
              border-bottom: 1px solid #eee;
            }

            .form-group-header h3 {
              margin: 0;
              color: #333;
            }

            .delete-btn {
              background: transparent;
              color: #dc3545;
              border: none;
              padding: 8px;
              cursor: pointer;
              border-radius: 50%;
              width: 40px;
              height: 40px;
              display: flex;
              align-items: center;
              justify-content: center;
              position: relative;
              transition: all 0.3s ease;
            }

            .delete-btn:hover {
              background: rgba(220, 53, 69, 0.1);
              transform: scale(1.05);
            }

            .delete-btn .tooltip {
              position: absolute;
              background: #333;
              color: white;
              padding: 5px 10px;
              border-radius: 4px;
              font-size: 12px;
              bottom: -30px;
              white-space: nowrap;
              visibility: hidden;
              opacity: 0;
              transition: all 0.3s ease;
            }

            .delete-btn:hover .tooltip {
              visibility: visible;
              opacity: 1;
            }
          </style>

          <script>
            function openAddPartnerModal() {
              document.getElementById('addPartnerModal').style.display = 'block';
            }

            function closeAddPartnerModal() {
              document.getElementById('addPartnerModal').style.display = 'none';
            }

            // Close modal when clicking outside
            window.onclick = function(event) {
              if (event.target == document.getElementById('addPartnerModal')) {
                closeAddPartnerModal();
              }
            }

            // Preview uploaded logo
            document.getElementById('partnerLogo').addEventListener('change', function(e) {
              const file = e.target.files[0];
              if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                  document.getElementById('logoPreview').innerHTML = `
                    <img src="${e.target.result}" alt="Logo preview">
                  `;
                }
                reader.readAsDataURL(file);
              }
            });

            function confirmDeletePartner(partnerId) {
              if (confirm('Are you sure you want to delete this partner?')) {
                window.location.href = `../../handlers/home/deletePartnerHandler.php?id=${partnerId}`;
              }
            }
          </script>
        </div>
      </main>
    </div>
  </body>
</html>
