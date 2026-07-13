<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$highlights = [];
$highlights_query = "SELECT * FROM voluntourism_highlights ORDER BY display_order";
$highlights_result = mysqli_query($conn, $highlights_query);
while ($row = mysqli_fetch_assoc($highlights_result)) {
    $highlights[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    
    for ($i = 1; $i <= 4; $i++) {
        if (isset($_POST["highlight_title_$i"])) {
            $title = mysqli_real_escape_string($conn, $_POST["highlight_title_$i"]);
            $description = mysqli_real_escape_string($conn, $_POST["highlight_description_$i"]);
            $icon = mysqli_real_escape_string($conn, $_POST["highlight_icon_$i"]);
            
            if (isset($highlights[$i-1])) {
                $id = $highlights[$i-1]['id'];
                $query = "UPDATE voluntourism_highlights SET 
                    highlight_title = '$title',
                    highlight_description = '$description',
                    highlight_icon = '$icon'
                    WHERE id = $id";
            } else {
                $query = "INSERT INTO voluntourism_highlights 
                    (highlight_icon, highlight_title, highlight_description, display_order)
                    VALUES ('$icon', '$title', '$description', $i)";
            }
            
            if (!mysqli_query($conn, $query)) {
                $success = false;
            }
        }
    }
    
    if ($success) {
        header('Location: index.php?success=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Highlights - Voluntourism</title>
<link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
	<link rel="stylesheet" href="../assets/css/forms.css">
	<link rel="stylesheet" href="../assets/css/management.css">
	<link rel="stylesheet" href="../assets/css/layout-fixes.css">

    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">

    <style>
        body { background-color: var(--neutral-light); }
        .admin-layout { display: flex; min-height: 100vh; }
        .main-content { flex: 1; display: flex; flex-direction: column; }
        .content-area { flex: 1; padding: 30px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { color: var(--primary-green); font-size: 28px; margin-bottom: 5px; }
        .page-header p { color: var(--text-medium); font-size: 14px; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .admin-form { background: white; padding: 30px; border-radius: 8px; box-shadow: var(--shadow-md); }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
        .form-section { background: var(--neutral-light); padding: 20px; border-radius: 8px; }
        .form-section-header { margin-bottom: 20px; border-bottom: 2px solid var(--primary-green); padding-bottom: 10px; }
        .form-section-header h3 { color: var(--primary-green); font-size: 18px; margin: 0; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: var(--text-dark); }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid var(--neutral-beige); border-radius: 6px; font-size: 14px; }
        .form-group input:focus, .form-group textarea:focus { outline: none; border-color: var(--primary-green); box-shadow: 0 0 5px rgba(1, 105, 5, 0.3); }
        .form-group textarea { resize: vertical; min-height: 100px; }
        .form-group small { color: var(--text-medium); font-size: 12px; }
        .form-actions { display: flex; gap: 15px; justify-content: center; margin-top: 30px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; }
        .btn-primary { background-color: var(--primary-green); color: white; }
        .btn-primary:hover { background-color: var(--accent-sage); }
        .btn-secondary { background-color: var(--neutral-beige); color: var(--text-dark); }
        .btn-secondary:hover { background-color: var(--accent-light-brown); }
        .form-container {
            background: white;
            border-radius: 8px;
            padding: 30px;
            max-width: 900px;
            margin: 20px auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .highlight-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .highlight-card h3 {
            margin-top: 0;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
    </style>
</head>
<body class="admin-layout">
    <?php include '../includes/sidebar.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/topbar.php'; ?>
        
        <main class="admin-main">
            <header class="page-header">
                <h1><i class="fas fa-star"></i> Edit Highlights (4 Cards)</h1>
                <p>Update the introduction highlight cards</p>
            </header>

            <div class="form-container">
                <form method="POST">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                        <div class="highlight-card">
                            <h3>Highlight <?php echo $i; ?></h3>
                            
                            <div class="form-group">
                                <label for="highlight_icon_<?php echo $i; ?>">Icon Class (FontAwesome)</label>
                                <input type="text" id="highlight_icon_<?php echo $i; ?>" name="highlight_icon_<?php echo $i; ?>" 
                                    value="<?php echo htmlspecialchars($highlights[$i-1]['highlight_icon'] ?? 'fas fa-star'); ?>" 
                                    placeholder="e.g., fas fa-hands-helping">
                            </div>

                            <div class="form-group">
                                <label for="highlight_title_<?php echo $i; ?>">Title</label>
                                <input type="text" id="highlight_title_<?php echo $i; ?>" name="highlight_title_<?php echo $i; ?>" 
                                    value="<?php echo htmlspecialchars($highlights[$i-1]['highlight_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="highlight_description_<?php echo $i; ?>">Description</label>
                                <textarea id="highlight_description_<?php echo $i; ?>" name="highlight_description_<?php echo $i; ?>" required><?php echo htmlspecialchars($highlights[$i-1]['highlight_description'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    <?php endfor; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save All Highlights
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

