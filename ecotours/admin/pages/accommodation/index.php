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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accommodation Management - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../../images/logos/icon.png" type="image/x-icon" />
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

            <div class="content-panels">
                <div class="panel active" id="accommodation-panel">
                    <div class="panel-header">
                        <h1>Accommodation Management</h1>
                        <div class="panel-actions">
                            <a href="./edit.php" class="action-button add-btn">
                                <i class="fas fa-plus"></i> Add New Accommodation
                            </a>
                            <a href="./hero.php" class="action-button hero-btn">
                                <i class="fas fa-image"></i> Manage Hero Images
                            </a>
                        </div>
                        <?php if (isset($_GET['status'])): ?>
                            <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                                <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                                <span><?php echo htmlspecialchars($_GET['message'] ?? 'Operation completed'); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="accommodation-list">
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Tier</th>
                                        <th>Price</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($accommodations as $acc): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($acc['name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($acc['location']); ?></td>
                                        <td><span class="tier-badge <?php echo strtolower(str_replace(' ', '-', $acc['tier_label'])); ?>"><?php echo htmlspecialchars($acc['tier_label']); ?></span></td>
                                        <td><?php echo htmlspecialchars($acc['price_display']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $acc['featured'] ? 'featured' : 'not-featured'; ?>">
                                                <?php echo $acc['featured'] ? 'Yes' : 'No'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status <?php echo $acc['is_active'] ? 'active' : 'inactive'; ?>">
                                                <?php echo $acc['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($acc['created_at'])); ?></td>
                                        <td class="actions">
                                            <a href="./edit.php?id=<?php echo $acc['accommodation_id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="../../handlers/accommodation_handler.php?action=delete&id=<?php echo $acc['accommodation_id']; ?>" class="btn-delete" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

