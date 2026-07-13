<?php
session_start();
require_once '../../../admin/config/connection.php';
if (!isset($_SESSION['community_admin_id'])) { header('Location: ../index.php'); exit; }

$type = $_GET['type'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$page_id = isset($_GET['page_id']) ? (int)$_GET['page_id'] : 0;

if (!in_array($type, ['card','stat'], true) || $id <= 0 || $page_id <= 0) {
    header('Location: index.php?error=' . urlencode('Invalid edit request')); exit;
}

$item = null;
if ($type === 'card') {
    $q = mysqli_query($conn, "SELECT id, number, image, title, description FROM approach_cards WHERE id = $id AND page_id = $page_id LIMIT 1");
    if ($q && mysqli_num_rows($q) === 1) { $item = mysqli_fetch_assoc($q); }
} else {
    $q = mysqli_query($conn, "SELECT id, stat_number, stat_label FROM inclusive_stats WHERE id = $id AND page_id = $page_id LIMIT 1");
    if ($q && mysqli_num_rows($q) === 1) { $item = mysqli_fetch_assoc($q); }
}

if (!$item) { header('Location: index.php?error=' . urlencode('Item not found')); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?php echo $type === 'card' ? 'Approach Card' : 'Statistic'; ?></title>
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
</head>
<body class="admin-layout">
   
    <div class="admin-container">

        <main class="admin-main">
            <header class="page-header">
                <h1><?php echo $type === 'card' ? 'Edit Approach Card' : 'Edit Statistic'; ?></h1>
            </header>

            <section class="card">
                <div class="card-body">
                    <?php if ($type === 'card'): ?>
                    <form action="handlers/cards-update.php" method="post" class="form-grid">
                        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>" />
                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                        <div class="form-group"><label>Number</label><input type="number" name="number" min="1" step="1" value="<?php echo (int)$item['number']; ?>" required /></div>
                        <div class="form-group"><label>Image URL</label><input type="text" name="image" value="<?php echo htmlspecialchars($item['image']); ?>" required /></div>
                        <div class="form-group"><label>Title</label><input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required /></div>
                        <div class="form-group"><label>Description</label><textarea name="description" rows="4" required><?php echo htmlspecialchars($item['description']); ?></textarea></div>
                        <button class="btn btn-primary" type="submit">Save</button>
                        <a class="btn" href="index.php">Cancel</a>
                    </form>
                    <?php else: ?>
                    <form action="handlers/stats-update.php" method="post" class="form-grid">
                        <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>" />
                        <input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
                        <div class="form-group"><label>Number</label><input type="text" name="stat_number" value="<?php echo htmlspecialchars($item['stat_number']); ?>" required /></div>
                        <div class="form-group"><label>Label</label><input type="text" name="stat_label" value="<?php echo htmlspecialchars($item['stat_label']); ?>" required /></div>
                        <button class="btn btn-primary" type="submit">Save</button>
                        <a class="btn" href="index.php">Cancel</a>
                    </form>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
<?php mysqli_close($conn); ?>


