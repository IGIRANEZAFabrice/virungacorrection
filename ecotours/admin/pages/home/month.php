<?php
// Include database connection
require_once('../../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

// Process form submissions via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $upload_dir = '../../images/destinations/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $image_url = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $file_extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            $image_url = 'images/destinations/' . $file_name;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Error uploading file']);
            exit;
        }
    }

    $month = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : '';
    if (empty($month)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Month is required']);
        exit;
    }

    $destination_id = isset($_POST['destination_id']) ? intval($_POST['destination_id']) : null;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!$destination_id) {
        $sql = "INSERT INTO travel_destinations (month, name, description, image_url) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $month, $name, $description, $image_url);
        
        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Destination added successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error: ' . mysqli_error($conn)];
        }
        mysqli_stmt_close($stmt);
    } else {
        $sql = "UPDATE travel_destinations SET month = ?, name = ?, description = ?" .
                ($image_url ? ", image_url = ?" : "") .
                " WHERE id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        if ($image_url) {
            mysqli_stmt_bind_param($stmt, "ssssi", $month, $name, $description, $image_url, $destination_id);
        } else {
            mysqli_stmt_bind_param($stmt, "sssi", $month, $name, $description, $destination_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $response = ['status' => 'success', 'message' => 'Destination updated successfully!'];
        } else {
            $response = ['status' => 'error', 'message' => 'Error: ' . mysqli_error($conn)];
        }
        mysqli_stmt_close($stmt);
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle delete request via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM travel_destinations WHERE id = $id";
    
    header('Content-Type: application/json');
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['status' => 'success', 'message' => 'Destination deleted successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . mysqli_error($conn)]);
    }
    exit;
}

// Handle get destinations request (for specific month)
if (isset($_GET['action']) && $_GET['action'] === 'get_destinations' && isset($_GET['month'])) {
    $month = mysqli_real_escape_string($conn, $_GET['month']);
    $sql = "SELECT * FROM travel_destinations WHERE month = '$month' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    $destinations = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $destinations[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($destinations);
    exit;
}

// Default view - load all destinations for selected month
$months_list = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
$selected_month = isset($_GET['month']) && in_array($_GET['month'], $months_list) ? $_GET['month'] : 'January';

$sql = "SELECT * FROM travel_destinations WHERE month = '$selected_month' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$destinations_in_month = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $destinations_in_month[] = $row;
    }
}

// Count total destinations across all months
$total_all_destinations = 0;
$count_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM travel_destinations");
if ($count_res) {
    $total_all_destinations = (int)mysqli_fetch_assoc($count_res)['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Travel by Month - Virunga Admin</title>
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
                <span>Travel by Month</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-calendar-alt" style="color: #206bc4;"></i>
                Monthly Travel Recommendations
              </h1>
              <p class="page-subtitle">
                Configure seasonal trip recommendations and best-time-to-visit travel highlights for each month.
              </p>
            </div>

            <div class="page-actions-wrap">
              <a href="../../../index.php" target="_blank" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> View Live Site
              </a>
              <button type="button" class="btn btn-primary btn-sm" onclick="openDestinationModal('add')">
                <i class="fas fa-plus"></i> Add Destination
              </button>
            </div>
          </div>

          <div id="dynamicStatusAlert" style="display: none;"></div>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo htmlspecialchars($selected_month); ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-calendar-check"></i> Selected</span>
              </div>
              <span class="kpi-title">Active Month Filter</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo count($destinations_in_month); ?></span>
                <span class="kpi-badge badge-green"><i class="fas fa-map-pin"></i> Highlights</span>
              </div>
              <span class="kpi-title">Destinations in <?php echo htmlspecialchars($selected_month); ?></span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_all_destinations; ?></span>
                <span class="kpi-badge badge-purple"><i class="fas fa-earth-africa"></i> Annual</span>
              </div>
              <span class="kpi-title">Total Seasonal Destinations</span>
            </div>
          </div>

          <!-- Month Selector Pills Bar -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-calendar-days"></i> Select Month to Manage</h3>
              <span class="badge badge-blue">12 Calendar Months</span>
            </div>
            <div class="card-body" style="padding: 0.75rem 1.25rem;">
              <div class="pills-tab-header" style="margin-bottom: 0;">
                <?php foreach ($months_list as $m): ?>
                  <a 
                    href="?month=<?php echo urlencode($m); ?>" 
                    class="pill-tab-btn <?php echo ($selected_month === $m) ? 'active' : ''; ?>"
                    style="text-decoration: none;"
                  >
                    <i class="far fa-calendar"></i> <?php echo $m; ?>
                  </a>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <!-- Destination Cards Grid -->
          <div class="card">
            <div class="card-header">
              <div>
                <h3 class="card-title">
                  <i class="fas fa-location-dot"></i> Destinations for <?php echo htmlspecialchars($selected_month); ?>
                </h3>
                <p class="card-subtitle">Showing all curated spots recommended for travelers visiting in <?php echo htmlspecialchars($selected_month); ?></p>
              </div>
              <button type="button" class="btn btn-primary btn-sm" onclick="openDestinationModal('add')">
                <i class="fas fa-plus"></i> Add Spot for <?php echo htmlspecialchars($selected_month); ?>
              </button>
            </div>

            <div class="card-body">
              <?php if (!empty($destinations_in_month)): ?>
                <div class="items-cards-grid">
                  <?php foreach ($destinations_in_month as $d): ?>
                    <div class="item-thumb-card">
                      <div class="item-thumb-media">
                        <img 
                          src="../../<?php echo htmlspecialchars($d['image_url']); ?>" 
                          alt="<?php echo htmlspecialchars($d['name']); ?>"
                          onerror="this.src='../../images/costa-rica.jpg';"
                        />
                      </div>
                      <div class="item-thumb-body">
                        <h4 class="item-thumb-title"><?php echo htmlspecialchars($d['name']); ?></h4>
                        <p class="item-thumb-desc"><?php echo htmlspecialchars($d['description']); ?></p>
                      </div>
                      <div class="item-thumb-actions">
                        <span class="badge badge-blue" style="font-size: 11px;"><?php echo htmlspecialchars($d['month']); ?></span>
                        <div style="display: flex; gap: 6px;">
                          <button type="button" class="btn btn-outline btn-sm" onclick="openDestinationModal('edit', <?php echo $d['id']; ?>)">
                            <i class="fas fa-edit"></i> Edit
                          </button>
                          <button type="button" class="btn btn-danger-outline btn-sm" onclick="deleteMonthDestination(<?php echo $d['id']; ?>)">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php else: ?>
                <div style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                  <div style="font-size: 40px; color: #cbd5e1; margin-bottom: 12px;">
                    <i class="fas fa-map-location-dot"></i>
                  </div>
                  <h4 style="font-size: 15px; font-weight: 600; color: #334155; margin: 0 0 6px;">No destinations added for <?php echo htmlspecialchars($selected_month); ?> yet</h4>
                  <p style="font-size: 13px; margin: 0 0 16px;">Curate travel spots, wildlife encounters, or seasonal festival recommendations for this month.</p>
                  <button type="button" class="btn btn-primary btn-sm" onclick="openDestinationModal('add')">
                    <i class="fas fa-plus"></i> Add First Destination for <?php echo htmlspecialchars($selected_month); ?>
                  </button>
                </div>
              <?php endif; ?>
            </div>

            <div class="card-footer">
              <span style="font-size: 12px; color: #64748b;">
                <i class="fas fa-info-circle"></i> Destinations are organized by calendar month and display dynamically when visitors choose their intended travel month.
              </span>
            </div>
          </div>

        </div>
      </main>
    </div>

    <!-- Modern Add/Edit Destination Modal -->
    <div id="monthDestModal" class="modal-backdrop-custom">
      <div class="modal-card">
        <div class="modal-header-custom">
          <h3 id="modalHeadingTitle"><i class="fas fa-location-plus" style="color: #206bc4; margin-right: 6px;"></i> Add New Destination</h3>
          <button type="button" class="modal-close-btn" onclick="closeModal('monthDestModal')">&times;</button>
        </div>
        <form id="monthDestForm" enctype="multipart/form-data">
          <input type="hidden" id="destIdField" name="destination_id" />
          
          <div class="modal-body-custom">
            <div class="form-group">
              <label class="form-label" for="destMonthSelect">Target Month</label>
              <select id="destMonthSelect" name="month" class="form-select" required>
                <?php foreach ($months_list as $m): ?>
                  <option value="<?php echo $m; ?>" <?php echo ($selected_month === $m) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label class="form-label" for="destNameInput">Destination / Highlight Name</label>
              <input type="text" id="destNameInput" name="name" class="form-control" placeholder="e.g. Volcanoes National Park" required />
            </div>

            <div class="form-group">
              <label class="form-label" for="destDescInput">Description / Travel Tip</label>
              <textarea id="destDescInput" name="description" class="form-control" rows="3" placeholder="Explain why this month is the best time to visit..." required></textarea>
            </div>

            <div class="form-group">
              <label class="form-label">Destination Image</label>
              <div class="dropzone-box">
                <input type="file" id="destImageFile" name="image_file" accept="image/*" />
                <div class="dropzone-thumb-wrap" id="modalThumbWrap">
                  <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #94a3b8;">
                    <i class="fas fa-image" style="font-size: 30px;"></i>
                  </div>
                </div>
                <div class="dropzone-content-prompt">
                  <i class="fas fa-cloud-arrow-up"></i>
                  <span>Click or drag image (Max 2MB)</span>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer-custom">
            <button type="button" class="btn btn-outline btn-sm" onclick="closeModal('monthDestModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm" id="saveDestBtn">
              <i class="fas fa-save"></i> Save Destination
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      const selectedCurrentMonth = "<?php echo addslashes($selected_month); ?>";

      function openDestinationModal(mode, id = null) {
        const modal = document.getElementById('monthDestModal');
        const form = document.getElementById('monthDestForm');
        const heading = document.getElementById('modalHeadingTitle');
        const idField = document.getElementById('destIdField');
        const monthSelect = document.getElementById('destMonthSelect');
        const nameInput = document.getElementById('destNameInput');
        const descInput = document.getElementById('destDescInput');
        const thumbWrap = document.getElementById('modalThumbWrap');

        if (mode === 'add') {
          heading.innerHTML = '<i class="fas fa-plus-circle" style="color: #206bc4; margin-right: 6px;"></i> Add New Destination';
          form.reset();
          idField.value = '';
          monthSelect.value = selectedCurrentMonth;
          thumbWrap.innerHTML = '<div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #94a3b8;"><i class="fas fa-image" style="font-size: 30px;"></i></div>';
          openModal('monthDestModal');
        } else if (mode === 'edit' && id) {
          heading.innerHTML = '<i class="fas fa-edit" style="color: #206bc4; margin-right: 6px;"></i> Edit Destination';
          fetch(`?action=get_destinations&month=${encodeURIComponent(selectedCurrentMonth)}`)
            .then(res => res.json())
            .then(destList => {
              const item = destList.find(d => d.id == id);
              if (item) {
                idField.value = item.id;
                monthSelect.value = item.month;
                nameInput.value = item.name;
                descInput.value = item.description;
                if (item.image_url) {
                  thumbWrap.innerHTML = `<img src="../../${item.image_url}" alt="${item.name}" />`;
                }
                openModal('monthDestModal');
              }
            });
        }
      }

      document.getElementById('destImageFile').addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('modalThumbWrap').innerHTML = `<img src="${e.target.result}" alt="Preview" />`;
          };
          reader.readAsDataURL(file);
        }
      });

      document.getElementById('monthDestForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('saveDestBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        const formData = new FormData(this);
        fetch('', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-save"></i> Save Destination';
          if (data.status === 'success') {
            closeModal('monthDestModal');
            window.location.reload();
          } else {
            alert(data.message || 'Error saving destination');
          }
        })
        .catch(err => {
          btn.disabled = false;
          btn.innerHTML = '<i class="fas fa-save"></i> Save Destination';
          alert('Network or server error occurred');
        });
      });

      function deleteMonthDestination(id) {
        if (confirm('Are you sure you want to delete this destination?')) {
          fetch(`?action=delete&id=${id}`)
            .then(res => res.json())
            .then(data => {
              if (data.status === 'success') {
                window.location.reload();
              } else {
                alert(data.message || 'Error deleting destination');
              }
            })
            .catch(err => alert('Network error occurred'));
        }
      }
    </script>
  </body>
</html>