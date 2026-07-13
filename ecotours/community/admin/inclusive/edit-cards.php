<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Get page data
$page = null;
$pq = mysqli_query($conn, "SELECT id FROM inclusive_page ORDER BY id ASC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    mysqli_query($conn, "INSERT INTO inclusive_page (hero_title, hero_subtitle) VALUES ('Inclusive Community-Based Tourism', 'Empowering Persons with Disabilities in Rural Areas')");
    $page = ['id' => mysqli_insert_id($conn)];
}
$page_id = (int)$page['id'];

// Load cards
$cards = [];
$cq = mysqli_query($conn, "SELECT id, number, image, title, description FROM approach_cards WHERE page_id = $page_id ORDER BY number ASC");
if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = (int)($_POST['card_id'] ?? 0);
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
    $card_image = '';

    // Get current image
    $current_query = "SELECT image FROM approach_cards WHERE id = $card_id AND page_id = $page_id";
    $current_result = mysqli_query($conn, $current_query);
    if ($current_result && mysqli_num_rows($current_result) > 0) {
        $current = mysqli_fetch_assoc($current_result);
        $card_image = $current['image'] ?? '';
    }

    // Handle image upload
    if (!empty($_FILES['card_image']['name'])) {
        $upload_dir = '../../../community/assets/images/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_name = basename($_FILES['card_image']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = 'inclusive-card-' . $card_id . '-' . time() . '.' . $file_ext;
            $upload_path = $upload_dir . $new_file_name;

            if (move_uploaded_file($_FILES['card_image']['tmp_name'], $upload_path)) {
                $card_image = 'assets/images/' . $new_file_name;
            } else {
                $error = "Error uploading image";
            }
        } else {
            $error = "Invalid file type. Allowed: jpg, jpeg, png, gif, webp";
        }
    }

    // Update database
    if (empty($error)) {
        $update_query = "UPDATE approach_cards SET title = '$title', description = '$description', image = '$card_image' WHERE id = $card_id AND page_id = $page_id";
        if (mysqli_query($conn, $update_query)) {
            header("Location: edit-cards.php?success=1");
            exit;
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Approach Cards - Inclusive Page</title>
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .edit-container { max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 2rem; }
        .card-editor { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .card-number { font-size: 0.9rem; color: #666; margin-bottom: 1rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; box-sizing: border-box; }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .image-preview { margin: 1rem 0; }
        .image-preview img { max-width: 100%; max-height: 150px; border-radius: 4px; }
        .preview-label { font-size: 0.9rem; color: #666; margin-top: 0.5rem; }
        .btn-group { display: flex; gap: 0.5rem; }
        .btn { padding: 0.5rem 1rem; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 0.9rem; }
        .btn-primary { background: #2a4858; color: white; }
        .btn-primary:hover { background: #1a3a48; }
        .btn-secondary { background: #ddd; color: #333; }
        .btn-secondary:hover { background: #ccc; }
        .alert { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .back-link { display: inline-block; margin-bottom: 1rem; color: #2a4858; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body class="admin-layout">
    
    
    <div class="admin-content">
        <div class="edit-container">
            <a href="index.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            
            <h1>Edit Approach Cards</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Card updated successfully!
                </div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <div class="cards-grid">
                <?php foreach ($cards as $card): ?>
                <div class="card-editor">
                    <div class="card-number">Card #<?php echo (int)$card['number']; ?></div>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="card_id" value="<?php echo (int)$card['id']; ?>">
                        
                        <div class="form-group">
                            <label for="title_<?php echo (int)$card['id']; ?>">Title</label>
                            <input type="text" id="title_<?php echo (int)$card['id']; ?>" name="title" value="<?php echo htmlspecialchars($card['title'] ?? ''); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description_<?php echo (int)$card['id']; ?>">Description</label>
                            <textarea id="description_<?php echo (int)$card['id']; ?>" name="description" required><?php echo htmlspecialchars($card['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="card_image_<?php echo (int)$card['id']; ?>">Card Image</label>
                            <?php if (!empty($card['image'])): ?>
                                <div class="image-preview">
                                    <img src="<?php echo htmlspecialchars($card['image']); ?>" alt="Card Image">
                                    <p class="preview-label">Current image</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="card_image_<?php echo (int)$card['id']; ?>" name="card_image" accept="image/*">
                            <small>Allowed formats: JPG, PNG, GIF, WebP</small>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>

