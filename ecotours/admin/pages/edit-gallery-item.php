<?php
require_once('../config/connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gallery_dashboard.php");
    exit();
}

$id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $alt_text = $_POST['alt_text'];
    $display_order = $_POST['display_order'];
    $image_path = $_POST['current_image_path']; // Default to current path
    
    // Handle image upload if a new file is provided
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = "../images/hero/";
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        
        if (!in_array($file_extension, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($_FILES["image"]["size"] > 5000000) { // 5MB max
            $error = "File is too large. Maximum size is 5MB.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $error = "Error uploading file.";
            }
        }
    }
    
    if (!isset($error)) {
        // Update the database
        $update_query = "UPDATE gallery_items SET title = ?, description = ?, image_path = ?, alt_text = ?, display_order = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssiii", $title, $description, $image_path, $alt_text, $display_order, $id);
        
        if ($stmt->execute()) {
            $message = "Gallery item updated successfully!";
        } else {
            $error = "Error updating gallery item: " . $conn->error;
        }
        $stmt->close();
    }
}

// Fetch the gallery item data
$query = "SELECT * FROM gallery_items WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: gallery_dashboard.php");
    exit();
}

$item = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Gallery Item</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/common.css">
    <script src="../js/common.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .preview-image {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include_once './includes/sidebar.php'; ?>

    <main class="main-content">
        <!-- Include header template -->
        <?php include_once './includes/header.php'; ?>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Edit Gallery Item</h4>
                        <a href="gallery.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if(isset($message)): ?>
                            <div class="alert alert-success"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo $item['title']; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo $item['description']; ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="alt_text" class="form-label">Alt Text</label>
                                        <input type="text" class="form-control" id="alt_text" name="alt_text" value="<?php echo $item['alt_text']; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="display_order" class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo $item['display_order']; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Current Image</label>
                                        <div>
                                            <img src="../../<?php echo $item['image_path']; ?>" alt="<?php echo $item['alt_text']; ?>" class="preview-image border">
                                        </div>
                                        <input type="hidden" name="current_image_path" value="<?php echo $item['image_path']; ?>">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload New Image (Optional)</label>
                                        <input type="file" class="form-control" id="image" name="image">
                                        <div class="form-text">Leave blank to keep current image. Only JPG, JPEG, PNG, and GIF files are allowed (max 5MB).</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Gallery Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
