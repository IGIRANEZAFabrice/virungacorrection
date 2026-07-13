<?php
// Include database connection
require_once('../../config/connection.php');

// Process form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create upload directory if it doesn't exist
    $upload_dir = '../../images/destinations/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Handle file upload and get image URL
    $image_url = '';
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === 0) {
        $file_extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $target_path)) {
            $image_url = 'images/destinations/' . $file_name;
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error uploading file'
            ];
            echo json_encode($response);
            exit;
        }
    }

    // Get and validate month
    $month = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : '';
    if (empty($month)) {
        $response = [
            'status' => 'error',
            'message' => 'Month is required'
        ];
        echo json_encode($response);
        exit;
    }

    // Prepare other form data
    $destination_id = isset($_POST['destination_id']) ? intval($_POST['destination_id']) : null;
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Debug output
    error_log("Saving destination with month: " . $month);
    
    if (!$destination_id) {
        // Insert new destination
        $sql = "INSERT INTO travel_destinations (month, name, description, image_url) 
                VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $month, $name, $description, $image_url);
        
        if (mysqli_stmt_execute($stmt)) {
            $response = [
                'status' => 'success',
                'message' => 'Destination added successfully!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error: ' . mysqli_error($conn)
            ];
        }
        mysqli_stmt_close($stmt);
    } else {
        // Update existing destination
        $sql = "UPDATE travel_destinations 
                SET month = ?, name = ?, description = ?" .
                ($image_url ? ", image_url = ?" : "") .
                " WHERE id = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        if ($image_url) {
            mysqli_stmt_bind_param($stmt, "ssssi", $month, $name, $description, $image_url, $destination_id);
        } else {
            mysqli_stmt_bind_param($stmt, "sssi", $month, $name, $description, $destination_id);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            $response = [
                'status' => 'success',
                'message' => 'Destination updated successfully!'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Error: ' . mysqli_error($conn)
            ];
        }
        mysqli_stmt_close($stmt);
    }
    
    // Return JSON response for AJAX requests
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle delete request via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql = "DELETE FROM travel_destinations WHERE id = $id";
    
    if (mysqli_query($conn, $sql)) {
        $response = [
            'status' => 'success',
            'message' => 'Destination deleted successfully!'
        ];
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Error: ' . mysqli_error($conn)
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Handle get destinations request (for specific month)
if (isset($_GET['action']) && $_GET['action'] === 'get_destinations' && isset($_GET['month'])) {
    $month = mysqli_real_escape_string($conn, $_GET['month']);
    
    $sql = "SELECT * FROM travel_destinations WHERE month = '$month' ORDER BY id DESC";
    $result = mysqli_query($conn, $sql);
    
    $destinations = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $destinations[] = $row;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($destinations);
    exit;
}

// Default view - load all destinations for selected month (January by default)
$selected_month = isset($_GET['month']) ? mysqli_real_escape_string($conn, $_GET['month']) : 'January';
$sql = "SELECT * FROM travel_destinations WHERE month = '$selected_month' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Destinations Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="../../../images/logos/icon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link rel="stylesheet" href="../../css/common.css" />
  <link rel="stylesheet" href="../../css/month.css" />
  <script src="../../js/common.js" defer></script>
  <style>
    /* You can add any additional CSS here if needed */
    .status-message {
      display: none;
      padding: 10px;
      margin: 10px 0;
      border-radius: 4px;
    }
    
    .status-message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .status-message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Include sidebar template -->
    <?php include_once './include/sidebar.php'; ?>

    <main class="main-content">
      <!-- Include header template -->
      <?php include_once './include/header.php'; ?>
      
      <div class="dashboard-header">
        <h1 class="dashboard-title">Destination Content Manager</h1>
        <div class="controls">
          <div class="month-selector">
            <select class="month-dropdown" id="monthSelector">
              <option value="January" <?php echo $selected_month === 'January' ? 'selected' : ''; ?>>January</option>
              <option value="February" <?php echo $selected_month === 'February' ? 'selected' : ''; ?>>February</option>
              <option value="March" <?php echo $selected_month === 'March' ? 'selected' : ''; ?>>March</option>
              <option value="April" <?php echo $selected_month === 'April' ? 'selected' : ''; ?>>April</option>
              <option value="May" <?php echo $selected_month === 'May' ? 'selected' : ''; ?>>May</option>
              <option value="June" <?php echo $selected_month === 'June' ? 'selected' : ''; ?>>June</option>
              <option value="July" <?php echo $selected_month === 'July' ? 'selected' : ''; ?>>July</option>
              <option value="August" <?php echo $selected_month === 'August' ? 'selected' : ''; ?>>August</option>
              <option value="September" <?php echo $selected_month === 'September' ? 'selected' : ''; ?>>September</option>
              <option value="October" <?php echo $selected_month === 'October' ? 'selected' : ''; ?>>October</option>
              <option value="November" <?php echo $selected_month === 'November' ? 'selected' : ''; ?>>November</option>
              <option value="December" <?php echo $selected_month === 'December' ? 'selected' : ''; ?>>December</option>
            </select>
          </div>
          <button class="add-destination-btn" id="addDestinationBtn">
            <i class="fas fa-plus"></i> Add Destination
          </button>
        </div>
      </div>
      
      <div id="statusMessage" class="status-message"></div>
      
      <div id="destinationsContainer">
        <?php
        // Check if there are destinations for the selected month
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="destinations-grid">';
            
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="destination-card" data-id="' . $row['id'] . '">
                    <div class="destination-image">
                        <img src="../../' . htmlspecialchars($row['image_url']) . '" alt="' . htmlspecialchars($row['name']) . '">
                        <div class="destination-actions">
                            <div class="action-btn edit-btn" onclick="openModal(\'edit\', ' . $row['id'] . ')">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="action-btn delete-btn" onclick="deleteDestination(' . $row['id'] . ')">
                                <i class="fas fa-trash"></i>
                            </div>
                        </div>
                    </div>
                    <div class="destination-content">
                        <h3 class="destination-title">' . htmlspecialchars($row['name']) . '</h3>
                        <p class="destination-text">' . htmlspecialchars($row['description']) . '</p>
                    </div>
                </div>';
            }
            
            echo '</div>';
        } else {
            // Display empty state
            echo '<div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-globe-americas"></i>
                </div>
                <div class="empty-state-text">
                    No destinations added for ' . $selected_month . ' yet
                </div>
                <button class="add-destination-btn" onclick="openModal(\'add\')">
                    <i class="fas fa-plus"></i> Add Your First Destination
                </button>
            </div>';
        }
        ?>
      </div>
    </main>
  </div>
  
  <!-- Add/Edit Destination Modal -->
  <div style="overflow-y: scroll;" class="modal-overlay" id="destinationModal">
    <div class="modal">
      <div class="modal-header">
        <h2 class="modal-title" id="modalTitle">Add New Destination</h2>
        <button class="modal-close" id="modalClose">&times;</button>
      </div>
      <div class="modal-body">
        <form id="destinationForm" enctype="multipart/form-data">
          <input type="hidden" id="destinationId" name="destination_id">
          
          <div class="form-group">
            <label class="form-label" for="destinationMonth">Month</label>
            <select class="form-select" id="destinationMonth" name="month" required>
              <option value="January">January</option>
              <option value="February">February</option>
              <option value="March">March</option>
              <option value="April">April</option>
              <option value="May">May</option>
              <option value="June">June</option>
              <option value="July">July</option>
              <option value="August">August</option>
              <option value="September">September</option>
              <option value="October">October</option>
              <option value="November">November</option>
              <option value="December">December</option>
            </select>
          </div>
          
          <div class="form-group">
            <label class="form-label" for="destinationName">Destination Name</label>
            <input type="text" class="form-input" id="destinationName" name="name" required>
          </div>
          
          <div class="form-group">
            <label class="form-label" for="destinationDescription">Description</label>
            <textarea class="form-textarea" id="destinationDescription" name="description" required></textarea>
          </div>
          
          <div class="form-group">
            <label class="form-label" for="destinationImage">Image</label>
            <input type="file" class="form-input" id="destinationImage" name="image_file" accept="image/*">
            <div class="image-placeholder" id="imagePlaceholder">
              <i class="fas fa-image"></i> Image Preview
            </div>
            <img id="imagePreview" class="preview-image" alt="Destination preview">
            <input type="hidden" name="image_url" id="imageUrlInput">
          </div>
          
          <div class="form-buttons">
            <button type="button" class="btn-cancel" id="cancelBtn">Cancel</button>
            <button type="submit" class="btn-save">Save Destination</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // DOM Elements
    const monthSelector = document.getElementById('monthSelector');
    const destinationsContainer = document.getElementById('destinationsContainer');
    const addDestinationBtn = document.getElementById('addDestinationBtn');
    const destinationModal = document.getElementById('destinationModal');
    const modalClose = document.getElementById('modalClose');
    const cancelBtn = document.getElementById('cancelBtn');
    const destinationForm = document.getElementById('destinationForm');
    const modalTitle = document.getElementById('modalTitle');
    const destinationIdInput = document.getElementById('destinationId');
    const destinationMonthInput = document.getElementById('destinationMonth');
    const destinationNameInput = document.getElementById('destinationName');
    const destinationDescriptionInput = document.getElementById('destinationDescription');
    const destinationImageInput = document.getElementById('destinationImage');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');
    const imageUrlInput = document.getElementById('imageUrlInput');
    const statusMessage = document.getElementById('statusMessage');
    
    // Event Listeners
    document.addEventListener('DOMContentLoaded', () => {
      monthSelector.addEventListener('change', () => {
        window.location.href = '?month=' + monthSelector.value;
      });
      
      addDestinationBtn.addEventListener('click', () => {
        openModal('add');
      });
      
      modalClose.addEventListener('click', closeModal);
      cancelBtn.addEventListener('click', closeModal);
      
      destinationForm.addEventListener('submit', handleFormSubmit);
      
      destinationImageInput.addEventListener('change', previewImage);
    });
    
    // Functions
    function openModal(mode, id = null) {
      destinationModal.style.display = 'flex';
      
      if (mode === 'add') {
        modalTitle.textContent = 'Add New Destination';
        destinationForm.reset();
        destinationIdInput.value = '';
        destinationMonthInput.value = monthSelector.value;
        imagePreview.style.display = 'none';
        imagePlaceholder.style.display = 'flex';
      } else if (mode === 'edit') {
        modalTitle.textContent = 'Edit Destination';
        
        // Fetch destination data from the server
        fetch(`?action=get_destinations&month=${monthSelector.value}`)
          .then(response => response.json())
          .then(destinations => {
            const destination = destinations.find(dest => dest.id == id);
            if (destination) {
              destinationIdInput.value = destination.id;
              destinationMonthInput.value = destination.month;
              destinationNameInput.value = destination.name;
              destinationDescriptionInput.value = destination.description;
              destinationImageInput.value = destination.image_url;
              
              if (destination.image_url) {
                imagePreview.src = destination.image_url;
                imagePreview.style.display = 'block';
                imagePlaceholder.style.display = 'none';
              }
            }
          });
      }
    }
    
    function closeModal() {
      destinationModal.style.display = 'none';
    }
    
    function handleFormSubmit(e) {
      e.preventDefault();
      
      // Create FormData object
      const formData = new FormData(destinationForm);
      
      // Send form data to server using fetch API
      fetch('', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          showStatus(data.message, 'success');
          closeModal();
          // Reload the page to show updated destinations
          setTimeout(() => {
            window.location.reload();
          }, 1000);
        } else {
          showStatus(data.message, 'error');
        }
      })
      .catch(error => {
        showStatus('An error occurred: ' + error, 'error');
      });
    }
    
    function deleteDestination(id) {
      if (confirm('Are you sure you want to delete this destination?')) {
        // Send delete request to server
        fetch(`?action=delete&id=${id}`)
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              showStatus(data.message, 'success');
              // Reload the page to show updated destinations
              setTimeout(() => {
                window.location.reload();
              }, 1000);
            } else {
              showStatus(data.message, 'error');
            }
          })
          .catch(error => {
            showStatus('An error occurred: ' + error, 'error');
          });
      }
    }
    
    function previewImage(event) {
      const file = event.target.files[0];
      const imagePreview = document.getElementById('imagePreview');
      const imagePlaceholder = document.getElementById('imagePlaceholder');
      const imageUrlInput = document.getElementById('imageUrlInput');
      
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
          imagePlaceholder.style.display = 'none';
          // Store filename in hidden input
          imageUrlInput.value = file.name;
        };
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = 'none';
        imagePlaceholder.style.display = 'flex';
        imageUrlInput.value = '';
      }
    }
    
    function showStatus(message, type) {
      statusMessage.textContent = message;
      statusMessage.className = 'status-message ' + type;
      statusMessage.style.display = 'block';
      
      setTimeout(() => {
        statusMessage.style.display = 'none';
      }, 3000);
    }
  </script>
</body>
</html>