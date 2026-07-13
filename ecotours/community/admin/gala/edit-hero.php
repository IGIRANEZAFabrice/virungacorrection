<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get hero content
$hero_query = "SELECT * FROM gala_hero LIMIT 1";
$hero_result = mysqli_query($conn, $hero_query);
$hero = mysqli_fetch_assoc($hero_result);

// If no hero exists, create one
if (!$hero) {
    $insert_query = "INSERT INTO gala_hero (hero_title, hero_subtitle, hero_description, intro_text, activities_title, activities_intro) 
                     VALUES ('Gala Local Dinner & Culture Experience', 'in Musanze', '', '', 'Afternoon Cultural Activities', '')";
    mysqli_query($conn, $insert_query);
    $hero_result = mysqli_query($conn, $hero_query);
    $hero = mysqli_fetch_assoc($hero_result);
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero_title']);
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero_subtitle']);
    $hero_description = mysqli_real_escape_string($conn, $_POST['hero_description']);
    $intro_text = mysqli_real_escape_string($conn, $_POST['intro_text']);
    $activities_title = mysqli_real_escape_string($conn, $_POST['activities_title']);
    $activities_intro = mysqli_real_escape_string($conn, $_POST['activities_intro']);
    $dinner_title = mysqli_real_escape_string($conn, $_POST['dinner_title']);
    $dinner_text = mysqli_real_escape_string($conn, $_POST['dinner_text']);
    $final_title = mysqli_real_escape_string($conn, $_POST['final_title']);
    $final_text = mysqli_real_escape_string($conn, $_POST['final_text']);
    
    $update_query = "UPDATE gala_hero SET 
                     hero_title = '$hero_title',
                     hero_subtitle = '$hero_subtitle',
                     hero_description = '$hero_description',
                     intro_text = '$intro_text',
                     activities_title = '$activities_title',
                     activities_intro = '$activities_intro',
                     dinner_title = '$dinner_title',
                     dinner_text = '$dinner_text',
                     final_title = '$final_title',
                     final_text = '$final_text'
                     WHERE id = " . $hero['id'];
    
    if (mysqli_query($conn, $update_query)) {
        $success = 'Hero section updated successfully!';
        // Refresh hero data
        $hero_result = mysqli_query($conn, $hero_query);
        $hero = mysqli_fetch_assoc($hero_result);
    } else {
        $error = 'Error updating hero section: ' . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - Gala Dinner</title>
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
            max-width: 900px;
            margin: 2rem 0;
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
            min-height: 120px;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(45, 122, 62, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
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
                    <h1>Edit Hero Section</h1>
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
                    <div class="form-row">
                        <div class="form-group">
                            <label for="hero_title">Hero Title</label>
                            <input type="text" id="hero_title" name="hero_title" value="<?php echo htmlspecialchars($hero['hero_title']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hero_subtitle">Hero Subtitle</label>
                            <input type="text" id="hero_subtitle" name="hero_subtitle" value="<?php echo htmlspecialchars($hero['hero_subtitle']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="hero_description">Hero Description</label>
                        <textarea id="hero_description" name="hero_description" required><?php echo htmlspecialchars($hero['hero_description']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="intro_text">Introduction Text</label>
                        <textarea id="intro_text" name="intro_text" required><?php echo htmlspecialchars($hero['intro_text']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="activities_title">Activities Section Title</label>
                            <input type="text" id="activities_title" name="activities_title" value="<?php echo htmlspecialchars($hero['activities_title']); ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="activities_intro">Activities Introduction</label>
                        <textarea id="activities_intro" name="activities_intro" required><?php echo htmlspecialchars($hero['activities_intro']); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="dinner_title">Dinner Section Title</label>
                            <input type="text" id="dinner_title" name="dinner_title" value="<?php echo htmlspecialchars($hero['dinner_title']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dinner_text">Dinner Section Text</label>
                        <textarea id="dinner_text" name="dinner_text"><?php echo htmlspecialchars($hero['dinner_text'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="final_title">Final Section Title</label>
                            <input type="text" id="final_title" name="final_title" value="<?php echo htmlspecialchars($hero['final_title']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="final_text">Final Section Text</label>
                        <textarea id="final_text" name="final_text"><?php echo htmlspecialchars($hero['final_text'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

