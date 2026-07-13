<?php 

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

require_once '../config/connection.php';

// Fetch current content
$query = "SELECT * FROM young_explorers LIMIT 1";
$result = mysqli_query($conn, $query);
$content = mysqli_fetch_assoc($result);

// If no content exists, create default
if (!$content) {
    $insert_query = "INSERT INTO young_explorers (section_description, image_url) VALUES ('', '')";
    mysqli_query($conn, $insert_query);
    $result = mysqli_query($conn, $query);
    $content = mysqli_fetch_assoc($result);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section_description = mysqli_real_escape_string($conn, $_POST['section_description']);
    $image_url = $content['image_url'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../uploads/young_explorers/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = time() . '_' . basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image_url = 'uploads/young_explorers/' . $file_name;
        }
    }

    if (empty($section_description)) {
        $error = 'Description is required!';
    } else {
        $update_query = "UPDATE young_explorers SET
            section_description = '$section_description',
            image_url = '$image_url'
            WHERE id = " . $content['id'];

        if (mysqli_query($conn, $update_query)) {
            $message = 'Content updated successfully!';
            // Refresh content
            $result = mysqli_query($conn, $query);
            $content = mysqli_fetch_assoc($result);
        } else {
            $error = 'Error updating content: ' . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Young Explorers - Virunga Ecotours Admin</title>
    <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/home.css" />
    <script src="../js/common.js" defer></script>
    <style>
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        .form-group textarea {
            width: 100%;
            min-height: 200px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }
        .form-group input[type="file"] {
            padding: 8px;
        }
        .image-preview {
            margin-top: 15px;
            max-width: 300px;
        }
        .image-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        .message-receiver {
            padding: 12px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .message-receiver.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message-receiver.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .btn-save {
            background-color: #016905;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
        }
        .btn-save:hover {
            background-color: #014d04;
        }
    </style>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>
      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>

        <div class="content-panels">
          <form method="post" enctype="multipart/form-data">
            <div class="panel active">
              <div class="panel-header">
                <h1>Young Explorers Section</h1>
                <div class="panel-actions">
                  <button class="action-button" type="submit">
                    <i class="fas fa-save"></i> Save Changes
                  </button>
                </div>
                <?php if (isset($message)): ?>
                  <div class="message-receiver success">
                    <i class="fas fa-check-circle"></i>
                    <span><?php echo $message; ?></span>
                  </div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                  <div class="message-receiver error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo $error; ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <div class="home-sections">
                <div class="home-section active">
                  <div class="section-header">
                    <h2>
                      <i class="fas fa-child"></i> Young Explorers Content
                    </h2>
                    <p class="section-desc">
                      Edit the Young Explorers section that appears on the homepage.
                    </p>
                  </div>

                  <div class="section-content">
                    <div class="form-group">
                      <label for="section_description">Section Description</label>
                      <textarea 
                        id="section_description" 
                        name="section_description" 
                        required
                      ><?php echo htmlspecialchars($content['section_description']); ?></textarea>
                      <small style="color: #666;">This is the main text that appears in the Young Explorers section</small>
                    </div>

                    <div class="form-group">
                      <label for="image">Section Image</label>
                      <input 
                        type="file" 
                        id="image" 
                        name="image" 
                        accept="image/*"
                      />
                      <small style="color: #666;">Supported formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                      
                      <?php if ($content['image_url']): ?>
                      <div class="image-preview">
                        <p style="margin: 10px 0 5px 0; font-weight: 600;">Current Image:</p>
                        <img src="../../<?php echo htmlspecialchars($content['image_url']); ?>" 
                             alt="Young Explorers Section Image">
                      </div>
                      <?php endif; ?>
                    </div>

                    <div class="form-group">
                      <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Save Changes
                      </button>
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

