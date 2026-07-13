<?php
session_start();
require_once '../../../admin/config/connection.php';

if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

$programs = [];
$programs_query = "SELECT * FROM voluntourism_programs ORDER BY display_order";
$programs_result = mysqli_query($conn, $programs_query);
while ($row = mysqli_fetch_assoc($programs_result)) {
    $programs[] = $row;
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
    $upload_dir = '../../../images/voluntourism/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    for ($i = 1; $i <= 4; $i++) {
        if (isset($_POST["program_title_$i"])) {
            $title = mysqli_real_escape_string($conn, $_POST["program_title_$i"]);
            $category = mysqli_real_escape_string($conn, $_POST["program_category_$i"]);
            $description = mysqli_real_escape_string($conn, $_POST["program_description_$i"]);
            $duration = mysqli_real_escape_string($conn, $_POST["program_duration_$i"]);
            $skills = mysqli_real_escape_string($conn, $_POST["program_skills_$i"]);
            $image = $programs[$i-1]['program_image'] ?? '';

            // Handle image upload
            if (isset($_FILES["program_image_$i"]) && $_FILES["program_image_$i"]['error'] === UPLOAD_ERR_OK) {
                $file_extension = strtolower(pathinfo($_FILES["program_image_$i"]['name'], PATHINFO_EXTENSION));
                if (in_array($file_extension, $allowed_extensions)) {
                    $new_filename = 'program_' . $i . '_' . time() . '.' . $file_extension;
                    $upload_path = $upload_dir . $new_filename;
                    if (move_uploaded_file($_FILES["program_image_$i"]['tmp_name'], $upload_path)) {
                        if ($image && file_exists($upload_dir . basename($image))) {
                            @unlink($upload_dir . basename($image));
                        }
                        $image = '../images/voluntourism/' . $new_filename;
                    } else {
                        $error_message = "Failed to upload image for program $i.";
                        $success = false;
                    }
                } else {
                    $error_message = "Invalid image format for program $i. Please use JPG, PNG, or WebP.";
                    $success = false;
                }
            }

            $image = mysqli_real_escape_string($conn, $image);

            if (isset($programs[$i-1])) {
                $id = $programs[$i-1]['id'];
                $query = "UPDATE voluntourism_programs SET
                    program_title = '$title',
                    program_category = '$category',
                    program_description = '$description',
                    duration = '$duration',
                    skills_required = '$skills',
                    program_image = '$image'
                    WHERE id = $id";
            } else {
                $query = "INSERT INTO voluntourism_programs
                    (program_title, program_category, program_description, program_image, duration, skills_required, display_order)
                    VALUES ('$title', '$category', '$description', '$image', '$duration', '$skills', $i)";
            }

            if (!mysqli_query($conn, $query)) {
                $success = false;
                $error_message = "Error updating program $i: " . mysqli_error($conn);
            }
        }
    }

    if ($success && !$error_message) {
        $success_message = "All programs updated successfully!";
        $programs_result = mysqli_query($conn, $programs_query);
        $programs = [];
        while ($row = mysqli_fetch_assoc($programs_result)) {
            $programs[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Programs - Voluntourism</title>
    <meta name="robots" content="noindex, nofollow">
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
            box-shadow: var(--shadow-md);
        }
        .program-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        .program-card h3 {
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
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #999;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #777;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include '../includes/sidebar.php'; ?>
        <div class="main-content">
            <?php include '../includes/topbar.php'; ?>
            <div class="content-area">
                <div class="page-header">
                    <div class="page-header-content">
                        <h1><i class="fas fa-briefcase"></i> Edit Programs</h1>
                        <p>Manage 4 volunteer programs with images</p>
                    </div>
                </div>

                <?php if ($success_message): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <div class="alert alert-error"><i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="admin-form">
                    <div class="form-grid">
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <div class="form-section">
                                <div class="form-section-header">
                                    <h3><i class="fas fa-briefcase"></i> Program <?php echo $i; ?></h3>
                                </div>
                                <div class="form-section-content">
                                    <div class="form-group">
                                        <label for="program_title_<?php echo $i; ?>">Title *</label>
                                        <input type="text" id="program_title_<?php echo $i; ?>" name="program_title_<?php echo $i; ?>"
                                            value="<?php echo htmlspecialchars($programs[$i-1]['program_title'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="program_category_<?php echo $i; ?>">Category *</label>
                                        <input type="text" id="program_category_<?php echo $i; ?>" name="program_category_<?php echo $i; ?>"
                                            value="<?php echo htmlspecialchars($programs[$i-1]['program_category'] ?? ''); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="program_description_<?php echo $i; ?>">Description *</label>
                                        <textarea id="program_description_<?php echo $i; ?>" name="program_description_<?php echo $i; ?>" rows="4" required><?php echo htmlspecialchars($programs[$i-1]['program_description'] ?? ''); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="program_duration_<?php echo $i; ?>">Duration</label>
                                        <input type="text" id="program_duration_<?php echo $i; ?>" name="program_duration_<?php echo $i; ?>"
                                            value="<?php echo htmlspecialchars($programs[$i-1]['duration'] ?? ''); ?>" placeholder="e.g., 2-12 weeks">
                                    </div>
                                    <div class="form-group">
                                        <label for="program_skills_<?php echo $i; ?>">Skills Required</label>
                                        <input type="text" id="program_skills_<?php echo $i; ?>" name="program_skills_<?php echo $i; ?>"
                                            value="<?php echo htmlspecialchars($programs[$i-1]['skills_required'] ?? ''); ?>" placeholder="e.g., Teaching, mentoring">
                                    </div>

                                    <?php if (!empty($programs[$i-1]['program_image'])):
                                        $preview_path = str_replace('../images/', '../../images/', $programs[$i-1]['program_image']);
                                    ?>
                                        <div class="current-image">
                                            <img src="<?php echo htmlspecialchars($preview_path); ?>" alt="Program <?php echo $i; ?>">
                                            <p><small>Current image. Upload a new file to replace it.</small></p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label for="program_image_<?php echo $i; ?>">Upload Image</label>
                                        <input type="file" id="program_image_<?php echo $i; ?>" name="program_image_<?php echo $i; ?>" accept="image/*">
                                        <small>JPG, PNG, or WebP</small>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Programs</button>
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

