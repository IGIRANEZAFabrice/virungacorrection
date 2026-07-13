<?php
require_once('../config/connection.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $alt_text = $_POST['alt_text'];
    $display_order = $_POST['display_order'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $target_dir = dirname(__FILE__) . "/../../images/hero/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        $relative_path = "images/hero/" . $new_filename;  // Store relative path in database
        
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        
        if (!in_array($file_extension, $allowed_types)) {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($_FILES["image"]["size"] > 5000000) { // 5MB max
            $error = "File is too large. Maximum size is 5MB.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Insert into database using relative path
                $insert_query = "INSERT INTO gallery_items (title, description, image_path, alt_text, display_order) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("ssssi", $title, $description, $relative_path, $alt_text, $display_order);
                
                if ($stmt->execute()) {
                    $message = "Gallery item added successfully!";
                    // Clear form data after successful submission
                    $title = $description = $alt_text = "";
                    $display_order = 0;
                } else {
                    $error = "Error adding gallery item: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Error uploading file.";
            }
        }
    } else {
        $error = "Please select an image to upload.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Gallery Item</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #imagePreview {
            max-width: 100%;
            max-height: 200px;
            display: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Add New Gallery Item</h4>
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
                                        <input type="text" class="form-control" id="title" name="title" value="<?php echo isset($title) ? $title : ''; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required><?php echo isset($description) ? $description : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="alt_text" class="form-label">Alt Text</label>
                                        <input type="text" class="form-control" id="alt_text" name="alt_text" value="<?php echo isset($alt_text) ? $alt_text : ''; ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="display_order" class="form-label">Display Order</label>
                                        <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo isset($display_order) ? $display_order : '0'; ?>" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)" required>
                                        <div class="form-text">Only JPG, JPEG, PNG, and GIF files are allowed (max 5MB).</div>
                                        <img id="imagePreview" class="border" alt="Image Preview">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Gallery Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewImage(input) {
            var preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
</body>
</html>
