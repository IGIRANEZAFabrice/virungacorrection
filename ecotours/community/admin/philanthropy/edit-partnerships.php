<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get partnerships
$partnerships_query = "SELECT * FROM philanthropy_partnerships ORDER BY display_order ASC";
$partnerships_result = mysqli_query($conn, $partnerships_query);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update') {
        $id = intval($_POST['id']);
        $partnership_title = mysqli_real_escape_string($conn, $_POST['partnership_title']);
        $partnership_description = mysqli_real_escape_string($conn, $_POST['partnership_description']);
        
        $update_query = "UPDATE philanthropy_partnerships SET 
            partnership_title = '$partnership_title',
            partnership_description = '$partnership_description'
            WHERE id = $id";
        
        if (mysqli_query($conn, $update_query)) {
            header('Location: edit-partnerships.php?success=Partnership updated successfully');
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
    <title>Edit Partnerships - Philanthropy</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    <style>
        .partnerships-list {
            display: grid;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .partnership-item {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .partnership-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .partnership-icon-display {
            font-size: 2rem;
            color: var(--primary-green);
        }
        
        .partnership-title-display {
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
                    <h1>Edit Partnership Opportunities</h1>
                </div>

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>

                <div class="partnerships-list">
                    <?php while ($partnership = mysqli_fetch_assoc($partnerships_result)): ?>
                        <div class="partnership-item">
                            <div class="partnership-header">
                                <div class="partnership-icon-display">
                                    <i class="<?php echo htmlspecialchars($partnership['partnership_icon']); ?>"></i>
                                </div>
                                <div class="partnership-title-display">
                                    <?php echo htmlspecialchars($partnership['partnership_title']); ?>
                                </div>
                            </div>

                            <form method="POST" class="edit-form">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $partnership['id']; ?>">

                                <div class="form-group">
                                    <label for="partnership_title_<?php echo $partnership['id']; ?>">Partnership Title *</label>
                                    <input type="text" id="partnership_title_<?php echo $partnership['id']; ?>" name="partnership_title" required 
                                           value="<?php echo htmlspecialchars($partnership['partnership_title']); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="partnership_description_<?php echo $partnership['id']; ?>">Partnership Description *</label>
                                    <textarea id="partnership_description_<?php echo $partnership['id']; ?>" name="partnership_description" rows="4" required><?php echo htmlspecialchars($partnership['partnership_description']); ?></textarea>
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

