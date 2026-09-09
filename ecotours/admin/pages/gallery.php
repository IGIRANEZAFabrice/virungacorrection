<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

require_once('../config/connection.php');

$message = '';
$error = '';

// Handle Delete Operation
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First, get the image path
    $select_query = "SELECT image_path FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($select_query);
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        $stmt->close();
        
        // Delete the database record
        $delete_query = "DELETE FROM gallery_items WHERE id = ?";
        $del_stmt = $conn->prepare($delete_query);
        if ($del_stmt) {
            $del_stmt->bind_param("i", $id);
            if ($del_stmt->execute()) {
                if ($image && !empty($image['image_path'])) {
                    $file_path = dirname(__FILE__) . '/../../' . $image['image_path'];
                    if (file_exists($file_path)) {
                        @unlink($file_path);
                    }
                }
                $message = "Gallery photo deleted successfully!";
            } else {
                $error = "Error deleting gallery photo: " . $conn->error;
            }
            $del_stmt->close();
        }
    }
}

// Fetch all gallery items
$query = "SELECT * FROM gallery_items ORDER BY display_order ASC, id DESC";
$result = $conn->query($query);
$items = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}
$total_items = count($items);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery Management - Virunga Admin</title>
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <script src="../js/common.js" defer></script>
    <style>
        .gallery-management-container {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            max-width: 1440px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }

        .page-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title-wrap {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .breadcrumb-trail {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 2px;
        }

        .breadcrumb-trail a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-trail a:hover {
            color: #206bc4;
        }

        .page-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-subtitle {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }

        .page-actions-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* KPI Stat Cards Row */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 1rem;
            width: 100%;
        }

        .kpi-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.15rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .kpi-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kpi-num {
            font-size: 1.65rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.1;
        }

        .kpi-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .kpi-title {
            font-size: 12.5px;
            font-weight: 500;
            color: #64748b;
        }

        .badge-blue { background: #e0f2fe; color: #0369a1; }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-purple { background: #f3e8ff; color: #7e22ce; }

        /* Card Container */
        .card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .card-header {
            padding: 1rem 1.25rem;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-subtitle {
            font-size: 12px;
            color: #64748b;
            margin: 2px 0 0;
        }

        .card-footer {
            padding: 0.85rem 1.25rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* Modern Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .custom-table th {
            background: #f8fafc;
            padding: 10px 14px;
            font-size: 11.5px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 1px solid #e2e8f0;
        }

        .custom-table td {
            padding: 12px 14px;
            font-size: 13px;
            color: #1e293b;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .custom-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .gallery-thumb {
            width: 72px;
            height: 52px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .table-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-icon {
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .btn-icon.danger {
            color: #ef4444;
            border-color: #fca5a5;
        }

        .btn-icon.danger:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            font-size: 13px;
            padding: 7px 14px;
            text-decoration: none;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-primary {
            background-color: #206bc4;
            color: #ffffff !important;
            border-color: #206bc4;
        }

        .btn-primary:hover {
            background-color: #1a569d;
            border-color: #1a569d;
        }

        .btn-outline {
            background-color: #ffffff;
            color: #334155 !important;
            border-color: #cbd5e1;
        }

        .btn-outline:hover {
            background-color: #f8fafc;
            color: #0f172a !important;
            border-color: #94a3b8;
        }

        .alert {
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Include sidebar template -->
        <?php include_once './includes/sidebar.php'; ?>

        <main class="main-content">
            <!-- Top Header -->
            <?php include_once './includes/header.php'; ?>

            <div class="gallery-management-container">
                
                <!-- Page Header Row -->
                <div class="page-header-row">
                    <div class="page-title-wrap">
                        <div class="breadcrumb-trail">
                            <a href="../index.php">Dashboard</a>
                            <span>/</span>
                            <span>Gallery Showcase</span>
                        </div>
                        <h1 class="page-title">
                            <i class="fas fa-images" style="color: #206bc4;"></i>
                            Photo Gallery Management
                        </h1>
                        <p class="page-subtitle">
                            Curate high-resolution destination photos, wildlife encounters, and cultural moments across Virunga.
                        </p>
                    </div>

                    <div class="page-actions-wrap">
                        <a href="../../gallery.php" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Live Gallery
                        </a>
                        <a href="add_gallery_item.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Image
                        </a>
                    </div>
                </div>

                <!-- Status Alerts -->
                <?php if ($message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo htmlspecialchars($message); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();" style="background:none; border:none; margin-left:auto; cursor:pointer;">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span><?php echo htmlspecialchars($error); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();" style="background:none; border:none; margin-left:auto; cursor:pointer;">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- KPI Metric Row -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $total_items; ?></span>
                            <span class="kpi-badge badge-blue"><i class="fas fa-camera"></i> Photos</span>
                        </div>
                        <span class="kpi-title">Total Visual Assets</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num">100%</span>
                            <span class="kpi-badge badge-green"><i class="fas fa-circle-check"></i> High Res</span>
                        </div>
                        <span class="kpi-title">Optimized Image Stream</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num">Active</span>
                            <span class="kpi-badge badge-purple"><i class="fas fa-sliders"></i> Sequence</span>
                        </div>
                        <span class="kpi-title">Custom Ordering Enabled</span>
                    </div>
                </div>

                <!-- Gallery Table Card -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title"><i class="fas fa-photo-film"></i> Gallery Photos (<?php echo $total_items; ?>)</h3>
                            <p class="card-subtitle">Manage captions, descriptions, and sort order</p>
                        </div>
                        <a href="add_gallery_item.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Upload Photo
                        </a>
                    </div>

                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th style="width: 90px;">Photo</th>
                                        <th>Title / Caption</th>
                                        <th>Description</th>
                                        <th style="width: 100px;">Sort Order</th>
                                        <th style="text-align: right; width: 130px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($items)): ?>
                                        <?php foreach ($items as $row): 
                                            $thumbPath = '../../' . $row['image_path'];
                                        ?>
                                            <tr>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($thumbPath); ?>" alt="<?php echo htmlspecialchars($row['alt_text'] ?? $row['title']); ?>" class="gallery-thumb" onerror="this.src='../images/costa-rica.jpg';" />
                                                </td>
                                                <td>
                                                    <strong style="color: #0f172a;"><?php echo htmlspecialchars($row['title']); ?></strong>
                                                    <?php if (!empty($row['alt_text'])): ?>
                                                        <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                                                            Alt: <?php echo htmlspecialchars($row['alt_text']); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span style="color: #475569; font-size: 12.5px;">
                                                        <?php echo htmlspecialchars(mb_strimwidth($row['description'] ?? '', 0, 80, '...')); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-blue" style="font-size: 11.5px;">
                                                        #<?php echo (int)$row['display_order']; ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="table-actions" style="justify-content: flex-end;">
                                                        <a href="view-gallery-item.php?id=<?php echo $row['id']; ?>" class="btn-icon" title="View Full Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="edit-gallery-item.php?id=<?php echo $row['id']; ?>" class="btn-icon" title="Edit Photo">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <a href="gallery.php?delete=<?php echo $row['id']; ?>" class="btn-icon danger" title="Delete Photo" onclick="return confirm('Are you sure you want to delete this gallery photo?');">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                                                <i class="fas fa-images" style="font-size: 36px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                                <p style="margin: 0 0 12px;">No gallery items uploaded yet.</p>
                                                <a href="add_gallery_item.php" class="btn btn-primary btn-sm">Upload First Photo</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <span style="font-size: 12px; color: #64748b;">
                            <i class="fas fa-info-circle"></i> Gallery assets are dynamically rendered in the main guest showcase slider and destination pages.
                        </span>
                        <span style="font-size: 12px; font-weight: 600; color: #334155;">
                            Showing <?php echo $total_items; ?> media items
                        </span>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>