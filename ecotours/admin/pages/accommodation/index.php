<?php 
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

require_once('../../config/connection.php');

// Fetch all accommodations with their tier information
$query = "SELECT a.accommodation_id, a.name, a.location, a.price_display, 
                 t.tier_label, a.is_active, a.featured, a.created_at
          FROM accommodations a
          JOIN accommodation_tiers t ON a.tier_id = t.tier_id
          ORDER BY t.sort_order, a.sort_order";
$result = $conn->query($query);
$accommodations = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $accommodations[] = $row;
    }
}
$total_accommodations = count($accommodations);
$active_count = 0;
$featured_count = 0;
foreach ($accommodations as $acc) {
    if ($acc['is_active']) $active_count++;
    if ($acc['featured']) $featured_count++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accommodation Management - Virunga Admin</title>
    <link rel="shortcut icon" href="../../images/icon.png" type="image/x-icon" />
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

            <div class="accommodation-container">
                
                <!-- Page Header Row -->
                <div class="page-header-row">
                    <div class="page-title-wrap">
                        <div class="breadcrumb-trail">
                            <a href="../../index.php">Dashboard</a>
                            <span>/</span>
                            <span>Accommodation</span>
                        </div>
                        <h1 class="page-title">
                            <i class="fas fa-bed" style="color: #206bc4;"></i>
                            Accommodation & Lodges Management
                        </h1>
                        <p class="page-subtitle">
                            Curate safari lodges, boutique hotels, and luxury eco-sanctuaries across Rwanda, Uganda, and the DRC.
                        </p>
                    </div>

                    <div class="page-actions-wrap">
                        <a href="../../../homestay/index.php" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Live Stays
                        </a>
                        <a href="./hero.php" class="btn btn-outline btn-sm">
                            <i class="fas fa-images"></i> Hero Banners
                        </a>
                        <a href="./edit.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Lodge
                        </a>
                    </div>
                </div>

                <!-- Status Alert -->
                <?php if (isset($_GET['status'])): ?>
                    <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                        <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                        <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] === 'success' ? 'Operation completed successfully!' : 'An error occurred.')); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- KPI Metric Row -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $total_accommodations; ?></span>
                            <span class="kpi-badge badge-blue"><i class="fas fa-hotel"></i> Total</span>
                        </div>
                        <span class="kpi-title">Configured Lodges</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $active_count; ?></span>
                            <span class="kpi-badge badge-green"><i class="fas fa-check"></i> Live</span>
                        </div>
                        <span class="kpi-title">Active Listings</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $featured_count; ?></span>
                            <span class="kpi-badge badge-amber"><i class="fas fa-star"></i> Featured</span>
                        </div>
                        <span class="kpi-title">Featured Highlights</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num">3</span>
                            <span class="kpi-badge badge-purple"><i class="fas fa-layer-group"></i> Categories</span>
                        </div>
                        <span class="kpi-title">Luxury Tiers</span>
                    </div>
                </div>

                <!-- Data Table Card -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title"><i class="fas fa-list"></i> All Accommodations & Stays</h3>
                            <p class="card-subtitle">Manage pricing tiers, featured badges, and destination details</p>
                        </div>
                        <a href="./edit.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Add New Stay
                        </a>
                    </div>

                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Lodge / Sanctuary</th>
                                        <th>Location</th>
                                        <th>Tier Category</th>
                                        <th>Price Display</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($accommodations)): ?>
                                        <?php foreach ($accommodations as $acc): 
                                            $tierClass = strtolower(str_replace([' ', '-'], '', $acc['tier_label']));
                                        ?>
                                        <tr>
                                            <td>
                                                <div style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($acc['name']); ?></div>
                                            </td>
                                            <td>
                                                <span style="color: #64748b; font-size: 12.5px;">
                                                    <i class="fas fa-location-dot" style="color: #94a3b8; margin-right: 4px;"></i>
                                                    <?php echo htmlspecialchars($acc['location']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="tier-badge <?php echo $tierClass; ?>">
                                                    <?php echo htmlspecialchars($acc['tier_label']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong style="color: #0f172a;"><?php echo htmlspecialchars($acc['price_display']); ?></strong>
                                            </td>
                                            <td>
                                                <?php if ($acc['featured']): ?>
                                                    <span class="featured-pill yes"><i class="fas fa-star" style="font-size: 10px;"></i> Featured</span>
                                                <?php else: ?>
                                                    <span class="featured-pill no">Standard</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($acc['is_active']): ?>
                                                    <span class="status-pill active"><i class="fas fa-circle" style="font-size: 7px;"></i> Active</span>
                                                <?php else: ?>
                                                    <span class="status-pill inactive"><i class="fas fa-circle" style="font-size: 7px;"></i> Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span style="font-size: 12px; color: #64748b;">
                                                    <?php echo date('M d, Y', strtotime($acc['created_at'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions" style="justify-content: flex-end;">
                                                    <a href="./edit.php?id=<?php echo $acc['accommodation_id']; ?>" class="btn-icon" title="Edit Accommodation">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../../handlers/accommodation_handler.php?action=delete&id=<?php echo $acc['accommodation_id']; ?>" class="btn-icon danger" title="Delete" onclick="return confirm('Are you sure you want to delete this accommodation?');">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                                                <i class="fas fa-hotel" style="font-size: 36px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                                <p style="margin: 0 0 12px;">No accommodations configured yet.</p>
                                                <a href="./edit.php" class="btn btn-primary btn-sm">Add First Accommodation</a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <span style="font-size: 12px; color: #64748b;">
                            <i class="fas fa-info-circle"></i> Accommodations are organized by tier and sort order to provide guests with clear choices.
                        </span>
                        <span style="font-size: 12px; font-weight: 600; color: #334155;">
                            Showing <?php echo count($accommodations); ?> total accommodations
                        </span>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
