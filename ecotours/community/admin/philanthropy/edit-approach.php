<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get approach cards
$approach_query = "SELECT * FROM philanthropy_approach ORDER BY display_order ASC";
$approach_result = mysqli_query($conn, $approach_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update') {
        $id = intval($_POST['id']);
        $card_title = mysqli_real_escape_string($conn, $_POST['card_title']);
        $card_description = mysqli_real_escape_string($conn, $_POST['card_description']);
        
        $update_query = "UPDATE philanthropy_approach SET 
            card_title = '$card_title',
            card_description = '$card_description'
            WHERE id = $id";
        
        if (mysqli_query($conn, $update_query)) {
            header('Location: edit-approach.php?success=Card updated successfully');
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
    <title>Edit Approach Cards - Philanthropy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .cards-list {
            display: grid;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .card-item {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .card-icon-display {
            font-size: 2rem;
            color: var(--primary-green);
        }
        
        .card-title-display {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text-dark);
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
                    <h1>Edit Our Approach Cards</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="cards-list">
                    <?php while ($card = mysqli_fetch_assoc($approach_result)): ?>
                        <div class="card-item">
                            <div class="card-header">
                                <div class="card-icon-display">
                                    <i class="<?php echo htmlspecialchars($card['card_icon']); ?>"></i>
                                </div>
                                <div class="card-title-display">
                                    <?php echo htmlspecialchars($card['card_title']); ?>
                                </div>
                            </div>

                            <form method="POST" class="edit-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $card['id']; ?>">

                                <div class="form-group">
                                    <label for="card_title_<?php echo $card['id']; ?>">Card Title *</label>
                                    <input type="text" id="card_title_<?php echo $card['id']; ?>" name="card_title" required 
                                           value="<?php echo htmlspecialchars($card['card_title']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="card_description_<?php echo $card['id']; ?>">Card Description *</label>
                                    <textarea id="card_description_<?php echo $card['id']; ?>" name="card_description" rows="4" required><?php echo htmlspecialchars($card['card_description']); ?></textarea>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

