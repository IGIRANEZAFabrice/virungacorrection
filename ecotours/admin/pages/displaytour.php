<?php
require_once '../config/connection.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tour_id'])) {
    $tour_id = mysqli_real_escape_string($conn, $_POST['tour_id']);
    
    // Delete the tour
    $query = "DELETE FROM tours WHERE tour_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $tour_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
        exit;
    }
}

// Fetch tours from database
$query = "SELECT * FROM tours ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
$tours = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tours Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/displaytour.css">
    <link rel="stylesheet" href="../css/common.css">
     <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
</head>
<body>
 <?php include_once './includes/sidebar.php'; ?>
   
     

      

    <main class="main-content">
        <!-- Include header template -->
        <?php include_once './includes/header.php'; ?>
    <div class="add-tour-button">
        <a href="addtour.php" class="add-btn">
            <i class="fas fa-plus"></i>
            Add New Tour
        </a>
    </div>
        
        <!-- Tours Grid -->
        <div class="tours-grid" id="tours-grid">
            <?php foreach($tours as $tour): ?>
                <div class="tour-card" data-tour-id="<?php echo htmlspecialchars($tour['tour_id']); ?>">
                    <div class="tour-image">
                        <img src="../../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($tour['title']); ?>"
                            >
                    </div>
                    <div class="tour-content">
                        <h3 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                        <div class="tour-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($tour['country']); ?>
                        </div>
                        <p class="tour-description"><?php echo htmlspecialchars($tour['short_description']); ?></p>
                        <div class="tour-footer">
                            <div class="tour-meta">
                                <span><i class="fas fa-calendar"></i> <?php echo htmlspecialchars($tour['days_count']); ?> days</span>
                                <span class="tour-category"><?php echo htmlspecialchars($tour['category']); ?></span>
                            </div>
                            <div class="tour-actions">
                                <button class="edit-btn" onclick="editTour(<?php echo $tour['tour_id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="delete-btn" onclick="deleteTour(<?php echo $tour['tour_id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    </div>
    </main>

</body>
 <script src="../js/common.js" defer></script>
 <script>
    function deleteTour(tourId) {
        if (confirm('Are you sure you want to delete this tour?')) {
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'tour_id=' + tourId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the tour card from the DOM
                    const tourCard = document.querySelector(`.tour-card[data-tour-id="${tourId}"]`);
                    if (tourCard) {
                        tourCard.remove();
                    }
                    alert('Tour deleted successfully!');
                } else {
                    alert('Failed to delete tour: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
    }
</script>
</html>
