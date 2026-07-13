<?php
require_once('../config/connection.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: gallery_dashboard.php");
    exit();
}

$id = $_GET['id'];
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
    <title>View Gallery Item</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/common.css">
    <script src="../js/common.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gallery-image {
            max-width: 100%;
            max-height: 400px;
            object-fit: contain;
        }
        .details-label {
            font-weight: bold;
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
                        <h4>View Gallery Item</h4>
                        <a href="gallery.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <img src="../../<?php echo $item['image_path']; ?>" alt="<?php echo $item['alt_text']; ?>" class="gallery-image border">
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <td class="details-label">ID</td>
                                        <td><?php echo $item['id']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Title</td>
                                        <td><?php echo $item['title']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Description</td>
                                        <td><?php echo $item['description']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Image Path</td>
                                        <td><?php echo $item['image_path']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Alt Text</td>
                                        <td><?php echo $item['alt_text']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Display Order</td>
                                        <td><?php echo $item['display_order']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Created At</td>
                                        <td><?php echo $item['created_at']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="details-label">Updated At</td>
                                        <td><?php echo $item['updated_at']; ?></td>
                                    </tr>
                                </table>
                                <div class="mt-3">
                                    <a href="edit-gallery-item.php?id=<?php echo $item['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $item['id']; ?>)" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
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
                window.location.href = "gallery_dashboard.php?delete=" + id;
            }
        }
    </script>
</body>
</html>
