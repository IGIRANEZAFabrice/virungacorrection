<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get focus areas
$focus_query = "SELECT * FROM philanthropy_focus_areas ORDER BY display_order ASC";
$focus_result = mysqli_query($conn, $focus_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        $id = intval($_POST['id']);
        $focus_title = mysqli_real_escape_string($conn, $_POST['focus_title']);
        $focus_description = mysqli_real_escape_string($conn, $_POST['focus_description']);

        // Get current focus area to get existing image
        $current_query = "SELECT focus_image FROM philanthropy_focus_areas WHERE id = $id";
        $current_result = mysqli_query($conn, $current_query);
        $current = mysqli_fetch_assoc($current_result);
        $focus_image = $current['focus_image'] ?? '';

        // Handle image upload
        if (!empty($_FILES['focus_image']['name'])) {
            $upload_dir = '../../../community/assets/images/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = basename($_FILES['focus_image']['name']);
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_ext, $allowed_ext)) {
                $new_file_name = 'focus-' . $id . '-' . time() . '.' . $file_ext;
                $upload_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($_FILES['focus_image']['tmp_name'], $upload_path)) {
                    $focus_image = 'assets/images/' . $new_file_name;
                }
            }
        }

        $focus_image = mysqli_real_escape_string($conn, $focus_image);
        $update_query = "UPDATE philanthropy_focus_areas SET
            focus_title = '$focus_title',
            focus_description = '$focus_description',
            focus_image = '$focus_image'
            WHERE id = $id";

        if (mysqli_query($conn, $update_query)) {
            header('Location: edit-focus-areas.php?success=Focus area updated successfully');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Focus Areas - Philanthropy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .focus-areas-list {
            display: grid;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .focus-item {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .focus-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .focus-icon-display {
            font-size: 2rem;
            color: var(--primary-green);
        }
        
        .focus-title-display {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .items-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }
        
        .items-section h4 {
            margin-bottom: 1rem;
            color: var(--text-dark);
        }
        
        .items-list {
            display: grid;
            gap: 1rem;
        }
        
        .item-row {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .item-text {
            flex: 1;
        }
        
        .item-title {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .item-description {
            font-size: 0.9rem;
            color: var(--text-medium);
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="admin-content">
            <?php include '../includes/topbar.php'; ?>
            
            <div class="content-area">
                <div class="page-header">
                    <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
                    <h1>Edit Focus Areas</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="focus-areas-list">
                    <?php while ($focus = mysqli_fetch_assoc($focus_result)): ?>
                        <div class="focus-item">
                            <div class="focus-header">
                                <div class="focus-icon-display">
                                    <i class="<?php echo htmlspecialchars($focus['focus_icon']); ?>"></i>
                                </div>
                                <div class="focus-title-display">
                                    <?php echo htmlspecialchars($focus['focus_title']); ?>
                                </div>
                            </div>

                            <form method="POST" class="edit-form" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $focus['id']; ?>">

                                <div class="form-group">
                                    <label for="focus_title_<?php echo $focus['id']; ?>">Focus Title *</label>
                                    <input type="text" id="focus_title_<?php echo $focus['id']; ?>" name="focus_title" required
                                           value="<?php echo htmlspecialchars($focus['focus_title']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="focus_description_<?php echo $focus['id']; ?>">Focus Description *</label>
                                    <textarea id="focus_description_<?php echo $focus['id']; ?>" name="focus_description" rows="4" required><?php echo htmlspecialchars($focus['focus_description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="focus_image_<?php echo $focus['id']; ?>">Focus Image</label>
                                    <?php if (!empty($focus['focus_image'])): ?>
                                        <div class="image-preview" style="margin-bottom: 1rem;">
                                            <img src="<?php echo htmlspecialchars($focus['focus_image']); ?>" alt="Focus Image" style="max-width: 200px; max-height: 150px; border-radius: 4px;">
                                            <p style="font-size: 0.9rem; color: #666; margin-top: 5px;">Current image</p>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" id="focus_image_<?php echo $focus['id']; ?>" name="focus_image" accept="image/*">
                                    <small>Allowed formats: JPG, PNG, GIF, WebP (Max 5MB)</small>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>

                            <!-- Items for this focus area -->
                            <div class="items-section">
                                <h4>Impact Items</h4>
                                <div class="items-list">
                                    <?php
                                    $items_query = "SELECT * FROM philanthropy_focus_items WHERE focus_id = '" . $focus['focus_id'] . "' ORDER BY display_order ASC";
                                    $items_result = mysqli_query($conn, $items_query);
                                    while ($item = mysqli_fetch_assoc($items_result)):
                                    ?>
                                        <div class="item-row">
                                            <div class="item-text">
                                                <div class="item-title"><?php echo htmlspecialchars($item['item_title']); ?></div>
                                                <div class="item-description"><?php echo htmlspecialchars($item['item_description']); ?></div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

