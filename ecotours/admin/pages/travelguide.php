<?php
require_once '../config/connection.php';

// Delete travel guide if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $guide_id = $_GET['delete'];
    
    $conn->begin_transaction();
    
    try {
        // Delete related records first (due to foreign key constraints)
        $conn->query("DELETE FROM styleguide_content WHERE guide_id = $guide_id");
        $conn->query("DELETE FROM styleguide_images WHERE guide_id = $guide_id");
        
        // Delete the main guide record
        $result = $conn->query("DELETE FROM styleguide WHERE guide_id = $guide_id");
        
        if ($result) {
            $conn->commit();
            $success_message = "Travel guide deleted successfully!";
        } else {
            throw new Exception("Failed to delete travel guide");
        }
    } catch (Exception $e) {
        $conn->rollback();
        $error_message = "Error: " . $e->getMessage();
    }
}

// Fetch all travel guides
$sql = "SELECT guide_id, country, title, coverimg FROM styleguide ORDER BY country, title";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Guides Management</title>
    <link rel="stylesheet" href="../css/common.css" />
    <script src="../js/common.js" defer></script>
  <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/travelguide.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
<?php include_once './includes/header.php'; ?>
    
    <div class="container-fluid">
        <?php include_once './includes/sidebar.php'; ?>
        <div class="row">
        
            
            <main>
                <div class="page-header">
                    <h1 class="page-title">Travel Guides Management</h1>
                    <a href="addtravelguide.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Travel Guide
                    </a>
                </div>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success alert-dismissible">
                        <?php echo $success_message; ?>
                        <button type="button" class="close" onclick="this.parentElement.style.display='none';">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <?php echo $error_message; ?>
                        <button type="button" class="close" onclick="this.parentElement.style.display='none';">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <div class="grid">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col">
                                <div class="card guide-card h-100">
                                    <div class="guide-img-container">
                                        <img src="../../<?php echo htmlspecialchars($row['coverimg']); ?>" 
                                             alt="<?php echo htmlspecialchars($row['title']); ?>">
                                        <span class="country-badge">
                                            <?php echo htmlspecialchars($row['country']); ?>
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <div class="btn-group">
                                            <a href="viewtravelguide.php?id=<?php echo $row['guide_id']; ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="edittravelguide.php?id=<?php echo $row['guide_id']; ?>" class="btn btn-outline-secondary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $row['guide_id']; ?>)" class="btn btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col" style="flex: 0 0 100%; max-width: 100%;">
                            <div class="alert alert-info">
                                No travel guides found. <a href="addtravelguide.php" class="alert-link">Add your first travel guide</a>.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this travel guide? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function confirmDelete(guideId) {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.href = 'travelguide.php?delete=' + guideId;
            
            const modal = document.getElementById('deleteModal');
            modal.classList.add('show');
            modal.style.display = 'block';
        }
        
        function closeModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>