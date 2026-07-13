<?php 
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

require_once('../../config/connection.php');

// Fetch all hero images (simplified - only image_url)
$query = "SELECT hero_id, image_url, created_at FROM accommodation_hero_images ORDER BY created_at DESC";
$result = $conn->query($query);
$hero_images = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $hero_images[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hero Images Management - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../../images/logos/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../../css/common.css" />
    <link rel="stylesheet" href="../../css/accommodation.css" />
    <script src="../../js/common.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <!-- Include sidebar template -->
        <?php include_once './include/sidebar.php'; ?>

        <main class="main-content">
            <!-- Top Header -->
            <?php include_once './include/header.php'; ?>

            <div class="content-panels">
                <div class="panel active" id="hero-panel">
                    <div class="panel-header">
                        <h1>Accommodation Hero Images</h1>
                        <div class="panel-actions">
                            <button class="action-button add-btn" onclick="openAddHeroModal()">
                                <i class="fas fa-plus"></i> Add Hero Image
                            </button>
                            <a href="./index.php" class="action-button back-btn">
                                <i class="fas fa-arrow-left"></i> Back to Accommodations
                            </a>
                        </div>
                        <?php if (isset($_GET['status'])): ?>
                            <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                                <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <span><?php echo htmlspecialchars($_GET['message'] ?? 'Operation completed'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="hero-images-list">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image Preview</th>
                                        <th>Image URL</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $count = 1; foreach ($hero_images as $hero): ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td>
                                            <div class="image-preview">
                                                <img src="<?php echo htmlspecialchars($hero['image_url']); ?>" alt="Hero Image" style="max-width: 100px; max-height: 60px; object-fit: cover;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%2260%22%3E%3Crect fill=%22%23ddd%22 width=%22100%22 height=%2260%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23999%22 font-size=%2212%22%3ENo Image%3C/text%3E%3C/svg%3E'">
                                            </div>
                                        </td>
                                        <td><small><?php echo htmlspecialchars($hero['image_url']); ?></small></td>
                                        <td><?php echo date('M d, Y', strtotime($hero['created_at'])); ?></td>
                                        <td class="actions">
                                            <a href="../../handlers/accommodation_handler.php?action=delete_hero&id=<?php echo $hero['hero_id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Hero Modal -->
    <div id="heroModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeHeroModal()">&times;</span>
            <h2>Add Hero Image</h2>
            <form id="heroForm" action="../../handlers/accommodation_handler.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create_hero">

                <div class="form-group">
                    <label for="image_file">Upload Image *</label>
                    <input type="file" name="image_file" id="image_file" accept="image/*" required>
                    <small>Supported formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Upload Image</button>
                    <button type="button" class="btn-cancel" onclick="closeHeroModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddHeroModal() {
            document.getElementById('heroForm').reset();
            document.getElementById('heroModal').style.display = 'block';
        }

        function closeHeroModal() {
            document.getElementById('heroModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('heroModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

