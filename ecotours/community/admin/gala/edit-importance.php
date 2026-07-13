<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delete_query = "DELETE FROM gala_importance WHERE id = $id";
    if (mysqli_query($conn, $delete_query)) {
        $success = 'Importance card deleted successfully!';
    } else {
        $error = 'Error deleting card: ' . mysqli_error($conn);
    }
}

// Handle add new importance card
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = mysqli_real_escape_string($conn, $_POST['importance_title']);
    $icon = mysqli_real_escape_string($conn, $_POST['importance_icon']);
    $description = mysqli_real_escape_string($conn, $_POST['importance_description']);
    
    // Get max order
    $order_query = "SELECT MAX(display_order) as max_order FROM gala_importance";
    $order_result = mysqli_query($conn, $order_query);
    $order_data = mysqli_fetch_assoc($order_result);
    $order = ($order_data['max_order'] ?? 0) + 1;
    
    $insert_query = "INSERT INTO gala_importance (importance_title, importance_icon, importance_description, display_order) 
                     VALUES ('$title', '$icon', '$description', $order)";
    
    if (mysqli_query($conn, $insert_query)) {
        $success = 'Importance card added successfully!';
    } else {
        $error = 'Error adding card: ' . mysqli_error($conn);
    }
}

// Handle update importance card
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['importance_id']);
    $title = mysqli_real_escape_string($conn, $_POST['importance_title']);
    $icon = mysqli_real_escape_string($conn, $_POST['importance_icon']);
    $description = mysqli_real_escape_string($conn, $_POST['importance_description']);
    
    $update_query = "UPDATE gala_importance SET 
                     importance_title = '$title',
                     importance_icon = '$icon',
                     importance_description = '$description'
                     WHERE id = $id";
    
    if (mysqli_query($conn, $update_query)) {
        $success = 'Importance card updated successfully!';
    } else {
        $error = 'Error updating card: ' . mysqli_error($conn);
    }
}

// Get all importance cards
$importance_query = "SELECT * FROM gala_importance ORDER BY display_order ASC";
$importance_result = mysqli_query($conn, $importance_query);
$importance_cards = [];
while ($row = mysqli_fetch_assoc($importance_result)) {
    $importance_cards[] = $row;
}

$edit_id = $_GET['edit'] ?? null;
$edit_card = null;
if ($edit_id) {
    foreach ($importance_cards as $card) {
        if ($card['id'] == $edit_id) {
            $edit_card = $card;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Importance Cards - Gala Dinner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--neutral-beige);
            border-radius: 4px;
            font-family: inherit;
            font-size: 0.95rem;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(45, 122, 62, 0.1);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary-green);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2d7a3e;
        }
        
        .btn-secondary {
            background-color: var(--neutral-light);
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .cards-list {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-item {
            padding: 1.5rem;
            border-bottom: 1px solid var(--neutral-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .card-item:last-child {
            border-bottom: none;
        }
        
        .card-info {
            flex: 1;
        }
        
        .card-info h4 {
            margin: 0 0 0.5rem 0;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .card-info p {
            margin: 0;
            color: var(--text-medium);
            font-size: 0.9rem;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .icon-preview {
            font-size: 1.5rem;
            color: var(--primary-green);
        }
    </style>
</head>
<body>
    <?php include '../includes/topbar.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <div class="content-wrapper">
                <div class="page-header">
                    <a href="index.php" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to Gala Management
                    </a>
                    <h1><?php echo $edit_card ? 'Edit Importance Card' : 'Add New Importance Card'; ?></h1>
                </div>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" class="form-container">
                    <input type="hidden" name="action" value="<?php echo $edit_card ? 'update' : 'add'; ?>">
                    <?php if ($edit_card): ?>
                        <input type="hidden" name="importance_id" value="<?php echo $edit_card['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="importance_title">Card Title</label>
                        <input type="text" id="importance_title" name="importance_title" value="<?php echo $edit_card ? htmlspecialchars($edit_card['importance_title']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="importance_icon">Font Awesome Icon Class</label>
                        <input type="text" id="importance_icon" name="importance_icon" placeholder="fas fa-heart" value="<?php echo $edit_card ? htmlspecialchars($edit_card['importance_icon']) : ''; ?>" required>
                        <small style="color: var(--text-medium);">Example: fas fa-heart, fas fa-scroll, fas fa-coins, fas fa-globe, fas fa-handshake</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="importance_description">Card Description</label>
                        <textarea id="importance_description" name="importance_description" required><?php echo $edit_card ? htmlspecialchars($edit_card['importance_description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> <?php echo $edit_card ? 'Update Card' : 'Add Card'; ?>
                        </button>
                        <?php if ($edit_card): ?>
                            <a href="edit-importance.php" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
                
                <h2>Importance Cards List</h2>
                <div class="cards-list">
                    <?php if (empty($importance_cards)): ?>
                        <div style="padding: 2rem; text-align: center; color: var(--text-medium);">
                            No importance cards yet. Add one to get started!
                        </div>
                    <?php else: ?>
                        <?php foreach ($importance_cards as $card): ?>
                            <div class="card-item">
                                <div class="card-info">
                                    <h4>
                                        <i class="<?php echo htmlspecialchars($card['importance_icon']); ?> icon-preview"></i>
                                        <?php echo htmlspecialchars($card['importance_title']); ?>
                                    </h4>
                                    <p><?php echo htmlspecialchars($card['importance_description']); ?></p>
                                </div>
                                <div class="card-actions">
                                    <a href="?edit=<?php echo $card['id']; ?>" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?delete=<?php echo $card['id']; ?>" class="btn btn-danger" style="padding: 0.5rem 1rem;" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

