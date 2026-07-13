<?php
require_once('../config/connection.php');

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // First, get the image path
    $select_query = "SELECT image_path FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($select_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();
    $stmt->close();
    
    // Delete the database record
    $delete_query = "DELETE FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // If database deletion was successful, delete the image file
        if ($image && isset($image['image_path'])) {
            $file_path = dirname(__FILE__) . '/../../' . $image['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        $message = "Gallery item deleted successfully!";
    } else {
        $error = "Error deleting gallery item: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all gallery items
$query = "SELECT * FROM gallery_items ORDER BY display_order ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Dashboard</title>
     <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/common.css">
    <script src="../js/common.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .thumbnail {
            width: 100px;
            height: 75px;
            object-fit: cover;
        }
        .actions {
            width: 150px;
        }
        .message {
            margin-top: 15px;
        }
        a{
            text-decoration: none;
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
                        <h4>Gallery Management</h4>
                        <a href="add_gallery_item.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Image
                        </a>
                    </div>
                    <div class="card-body">
                        <?php if(isset($message)): ?>
                            <div class="alert alert-success message"><?php echo $message; ?></div>
                        <?php endif; ?>
                        
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger message"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td>
                                                    <img src="../../<?php echo $row['image_path']; ?>" alt="<?php echo $row['alt_text']; ?>" class="thumbnail">
                                                </td>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo substr($row['description'], 0, 50) . (strlen($row['description']) > 50 ? '...' : ''); ?></td>
                                                <td><?php echo $row['display_order']; ?></td>
                                                <td class="actions">
                                                    <a href="view-gallery-item.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit-gallery-item.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No gallery items found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this gallery item?")) {
                window.location.href = "gallery.php?delete=" + id;  // Changed from gallery_dashboard.php to gallery.php
            }
        }
    </script>
</body>
</html>