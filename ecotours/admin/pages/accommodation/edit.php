<?php 
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: ../login.html");
  exit();
}

require_once('../../config/connection.php');

$accommodation = null;
$tiers = [];

// Fetch accommodation tiers
$tier_query = "SELECT tier_id, tier_label FROM accommodation_tiers ORDER BY sort_order";
$tier_result = $conn->query($tier_query);
if ($tier_result) {
    while ($row = $tier_result->fetch_assoc()) {
        $tiers[] = $row;
    }
}

// If editing, fetch the accommodation data
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM accommodations WHERE accommodation_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accommodation = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $accommodation ? 'Edit' : 'Add'; ?> Accommodation - Virunga Ecotours</title>
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
                <div class="panel active" id="accommodation-edit-panel">
                    <div class="panel-header">
                        <h1><?php echo $accommodation ? 'Edit Accommodation' : 'Add New Accommodation'; ?></h1>
                    </div>

                    <form action="../../handlers/accommodation_handler.php" method="POST" class="accommodation-form">
                        <input type="hidden" name="action" value="<?php echo $accommodation ? 'update' : 'create'; ?>">
                        <?php if ($accommodation): ?>
                            <input type="hidden" name="accommodation_id" value="<?php echo $accommodation['accommodation_id']; ?>">
                        <?php endif; ?>

                        <div class="form-section">
                            <h2>Basic Information</h2>
                            
                            <div class="form-group">
                                <label for="tier_id">Accommodation Tier *</label>
                                <select name="tier_id" id="tier_id" required>
                                    <option value="">Select Tier</option>
                                    <?php foreach ($tiers as $tier): ?>
                                        <option value="<?php echo $tier['tier_id']; ?>" <?php echo ($accommodation && $accommodation['tier_id'] == $tier['tier_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tier['tier_label']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="name">Accommodation Name *</label>
                                <input type="text" name="name" id="name" required value="<?php echo $accommodation ? htmlspecialchars($accommodation['name']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="location">Location *</label>
                                <input type="text" name="location" id="location" required value="<?php echo $accommodation ? htmlspecialchars($accommodation['location']) : ''; ?>">
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="price_min">Price Min (USD)</label>
                                    <input type="number" name="price_min" id="price_min" step="0.01" value="<?php echo $accommodation ? $accommodation['price_min'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="price_max">Price Max (USD)</label>
                                    <input type="number" name="price_max" id="price_max" step="0.01" value="<?php echo $accommodation ? $accommodation['price_max'] : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price_display">Price Display Text *</label>
                                <input type="text" name="price_display" id="price_display" required value="<?php echo $accommodation ? htmlspecialchars($accommodation['price_display']) : ''; ?>" placeholder="e.g., $20–90 / night">
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Description</h2>
                            
                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea name="short_description" id="short_description" rows="3"><?php echo $accommodation ? htmlspecialchars($accommodation['short_description']) : ''; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="full_description">Full Description</label>
                                <textarea name="full_description" id="full_description" rows="6"><?php echo $accommodation ? htmlspecialchars($accommodation['full_description']) : ''; ?></textarea>
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Additional Details</h2>
                            
                            <div class="form-group">
                                <label for="accommodation_type">Accommodation Type</label>
                                <input type="text" name="accommodation_type" id="accommodation_type" value="<?php echo $accommodation ? htmlspecialchars($accommodation['accommodation_type']) : ''; ?>" placeholder="e.g., Homestay, Lodge, Hotel">
                            </div>

                            <div class="form-group">
                                <label for="includes">What's Included</label>
                                <textarea name="includes" id="includes" rows="3"><?php echo $accommodation ? htmlspecialchars($accommodation['includes']) : ''; ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="guest_capacity">Guest Capacity</label>
                                    <input type="number" name="guest_capacity" id="guest_capacity" value="<?php echo $accommodation ? $accommodation['guest_capacity'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="tel" name="phone" id="phone" value="<?php echo $accommodation ? htmlspecialchars($accommodation['phone']) : ''; ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo $accommodation ? htmlspecialchars($accommodation['email']) : ''; ?>">
                            </div>
                        </div>

                        <div class="form-section">
                            <h2>Status</h2>
                            
                            <div class="form-group checkbox">
                                <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo ($accommodation && $accommodation['is_active']) ? 'checked' : ''; ?>>
                                <label for="is_active">Active</label>
                            </div>

                            <div class="form-group checkbox">
                                <input type="checkbox" name="featured" id="featured" value="1" <?php echo ($accommodation && $accommodation['featured']) ? 'checked' : ''; ?>>
                                <label for="featured">Featured</label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> <?php echo $accommodation ? 'Update' : 'Create'; ?> Accommodation
                            </button>
                            <a href="./index.php" class="btn-cancel">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>

