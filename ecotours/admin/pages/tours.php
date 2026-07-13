<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}
// Get messages from session
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : null;
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : null;

// Clear the messages from session
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// Update the delete handling section at the top of the file
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tour'])) {
    try {
        $tour_id = $_POST['tour_id'];
        $pdo->beginTransaction();

        // Delete from child tables first due to foreign key constraints
        $tables = [
            'tour_highlights',
            'tour_days',
            'tour_included',
            'tour_excluded',
            'tour_to_bring',
            'pricing_tiers',
            'pricing_notes'
        ];

        foreach ($tables as $table) {
            $stmt = $pdo->prepare("DELETE FROM $table WHERE tour_id = ?");
            $stmt->execute([$tour_id]);
        }

        // Get image paths before deleting tour record
        $stmt = $pdo->prepare("SELECT cover_image_path FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch();

        // Delete the main tour record
        $stmt = $pdo->prepare("DELETE FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);

        // Delete associated files if they exist
        if ($tour && $tour['cover_image_path']) {
            $file_path = '../../' . $tour['cover_image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $pdo->commit();
        $success_message = "Tour deleted successfully!";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error_message = "Error deleting tour: " . $e->getMessage();
    }
}

// Update the fetch handler section
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_tour'])) {
    try {
        $tour_id = $_GET['id'];
        // First get the main tour data
        $stmt = $pdo->prepare("SELECT * FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tour) {
            throw new Exception("Tour not found");
        }

        // Fetch tour days
        $stmt = $pdo->prepare("SELECT day_number, day_title, day_description FROM tour_days WHERE tour_id = ? ORDER BY day_number ASC");
        $stmt->execute([$tour_id]);
        $tour['days'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch tour highlights
        $stmt = $pdo->prepare("SELECT image_path, display_order FROM tour_highlights WHERE tour_id = ? ORDER BY display_order");
        $stmt->execute([$tour_id]);
        $tour['highlights'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch included items
        $stmt = $pdo->prepare("SELECT item_description FROM tour_included WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['included'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch excluded items
        $stmt = $pdo->prepare("SELECT item_description FROM tour_excluded WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['excluded'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch to bring items
        $stmt = $pdo->prepare("SELECT item_description FROM tour_to_bring WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['to_bring'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch pricing tiers
        $stmt = $pdo->prepare("SELECT group_size, price_per_person FROM pricing_tiers WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['pricing_tiers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch pricing notes
        $stmt = $pdo->prepare("SELECT note FROM pricing_notes WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['pricing_notes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'data' => $tour]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Update the tour creation/update handler
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo->beginTransaction();
        $is_update = isset($_POST['update_tour']);
        $tour_id = $is_update ? $_POST['tour_id'] : null;
        
        // Handle cover image upload
        $cover_image_path = null;
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] === 0) {
            $upload_dir = '../../images/tours/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
            $file_name = uniqid() . '.' . pathinfo($_FILES['coverImage']['name'], PATHINFO_EXTENSION);
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $upload_dir . $file_name)) {
                $cover_image_path = 'images/tours/' . $file_name;
            }
        }

        if ($is_update) {
            $sql = "UPDATE tours SET title = ?, category = ?, country = ?, days_count = ?, short_description = ?, why_attend = ?";
            $params = [$_POST['tourTitle'], $_POST['tourCategory'], $_POST['tourCountry'], $_POST['tourDays'], $_POST['tourDesc'], $_POST['whyAttend']];
            if ($cover_image_path) {
                $sql .= ", cover_image_path = ?";
                $params[] = $cover_image_path;
            }
            $sql .= " WHERE tour_id = ?";
            $params[] = $tour_id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $pdo->prepare("INSERT INTO tours (title, category, country, days_count, cover_image_path, short_description, why_attend) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_POST['tourTitle'], $_POST['tourCategory'], $_POST['tourCountry'], $_POST['tourDays'], $cover_image_path, $_POST['tourDesc'], $_POST['whyAttend']]);
            $tour_id = $pdo->lastInsertId();
        }

        // Process highlight images
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_FILES["highlight$i"]) && $_FILES["highlight$i"]['error'] === 0) {
                $upload_dir = '../../images/tours/highlights/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
                $file_name = uniqid() . '.' . pathinfo($_FILES["highlight$i"]['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES["highlight$i"]['tmp_name'], $upload_dir . $file_name)) {
                    $img_path = 'images/tours/highlights/' . $file_name;
                    
                    if ($is_update) {
                        // Check if highlight exists for this order
                        $checkStmt = $pdo->prepare("SELECT highlight_id FROM tour_highlights WHERE tour_id = ? AND display_order = ?");
                        $checkStmt->execute([$tour_id, $i]);
                        $highlight = $checkStmt->fetch();
                        
                        if ($highlight) {
                            $stmt = $pdo->prepare("UPDATE tour_highlights SET image_path = ? WHERE highlight_id = ?");
                            $stmt->execute([$img_path, $highlight['highlight_id']]);
                        } else {
                            $stmt = $pdo->prepare("INSERT INTO tour_highlights (tour_id, image_path, display_order) VALUES (?, ?, ?)");
                            $stmt->execute([$tour_id, $img_path, $i]);
                        }
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO tour_highlights (tour_id, image_path, display_order) VALUES (?, ?, ?)");
                        $stmt->execute([$tour_id, $img_path, $i]);
                    }
                }
            }
        }

        // Helper for child tables
        $updateChildTable = function($pdo, $tour_id, $tableName, $dataJson, $insertSql, $paramsMapFunc) {
            $stmt = $pdo->prepare("DELETE FROM $tableName WHERE tour_id = ?");
            $stmt->execute([$tour_id]);
            $items = json_decode($dataJson, true);
            if ($items) {
                $stmt = $pdo->prepare($insertSql);
                foreach ($items as $item) {
                    $params = $paramsMapFunc($tour_id, $item);
                    if ($params) $stmt->execute($params);
                }
            }
        };

        $updateChildTable($pdo, $tour_id, 'tour_days', $_POST['activities'] ?? '[]', 
            "INSERT INTO tour_days (tour_id, day_number, day_title, day_description) VALUES (?, ?, ?, ?)",
            fn($tid, $i) => !empty($i['title']) ? [$tid, $i['day_number'], $i['title'], $i['description']] : null);

        $updateChildTable($pdo, $tour_id, 'tour_included', $_POST['includedItems'] ?? '[]', 
            "INSERT INTO tour_included (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null);

        $updateChildTable($pdo, $tour_id, 'tour_excluded', $_POST['excludedItems'] ?? '[]', 
            "INSERT INTO tour_excluded (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null);

        $updateChildTable($pdo, $tour_id, 'tour_to_bring', $_POST['toBringItems'] ?? '[]', 
            "INSERT INTO tour_to_bring (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null);

        $updateChildTable($pdo, $tour_id, 'pricing_tiers', $_POST['pricingTiers'] ?? '[]', 
            "INSERT INTO pricing_tiers (tour_id, group_size, price_per_person) VALUES (?, ?, ?)",
            fn($tid, $i) => !empty($i['group_size']) ? [$tid, $i['group_size'], $i['price_per_person']] : null);

        $updateChildTable($pdo, $tour_id, 'pricing_notes', $_POST['pricingNotes'] ?? '[]', 
            "INSERT INTO pricing_notes (tour_id, note) VALUES (?, ?)",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null);

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => "Tour " . ($is_update ? "updated" : "saved") . " successfully!"]);
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tours Management - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/tours.css" />
    <script src="../js/common.js" defer></script>
    <script src="../js/tours.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <?php include_once './includes/sidebar.php'; ?>
      <main class="main-content">
        <?php include_once './includes/header.php'; ?>
        <div class="content-panels">
          <div class="panel active" id="tours-panel">
            <div class="container">
              <div class="tours-header">
                <button class="add-tour-btn" data-state="add"><span class="btn-text">Add New Tour</span></button>
                <div class="view-switcher">
                  <button class="view-btn active" id="listViewBtn" title="List View"><i class="fas fa-list"></i></button>
                  <button class="view-btn" id="cardViewBtn" title="Card View"><i class="fas fa-th-large"></i></button>
                </div>
              </div>

              <div class="add-tour-form" id="addTourForm">
                <h2 class="form-title"><i class="fas fa-plus-circle"></i> Add New Tour</h2>
                <form id="tourForm" method="POST" enctype="multipart/form-data">
                  <div class="form-row">
                    <div class="form-col">
                      <div class="form-group">
                        <label for="tourTitle"><i class="fas fa-heading"></i> Tour Title</label>
                        <input type="text" id="tourTitle" name="tourTitle" required />
                      </div>
                    </div>
                    <div class="form-col">
                      <div class="form-group">
                        <label for="tourCountry"><i class="fas fa-globe"></i> Country</label>
                        <select id="tourCountry" name="tourCountry" required>
                          <option value="">Select Country</option>
                          <option value="all">All</option>
                          <option value="rwanda">Rwanda</option>
                          <option value="uganda">Uganda</option>
                          <option value="congo">DR Congo</option>
                          <option value="burundi">Burundi</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-col">
                      <div class="form-group">
                        <label for="tourCategory"><i class="fas fa-tag"></i> Category</label>
                        <div class="category-input-container">
                          <select id="tourCategory" name="tourCategory" required>
                            <option value="">Select a category</option>
                            <option value="Adventure">Adventure</option>
                            <option value="Cultural">Cultural</option>
                            <option value="City Tours">City Tours</option>
                            <option value="Comunity Based Tourism">Comunity Based Tourism</option>
                            <option value="Family Friendly">Family Friendly</option>
                            <option value="Food & Culinary">Food & Culinary</option>
                            <option value="Gastronomy">Gastronomy</option>
                            <option value="Highlights">Highlights</option>
                            <option value="Nature">Nature</option>
                            <option value="Off the beaten Path">Off the beaten Path</option>
                            <option value="Historical">Historical</option>
                            <option value="Spiritual">Spiritual</option>
                            <option value="add_new">+ Add New Category</option>
                          </select>
                          <div id="newCategoryContainer" class="new-category-container" style="display: none;">
                            <input type="text" id="newCategoryInput" placeholder="Enter new category name" maxlength="50">
                            <div class="category-actions">
                              <button type="button" id="confirmNewCategory" class="btn-confirm"><i class="fas fa-check"></i></button>
                              <button type="button" id="cancelNewCategory" class="btn-cancel"><i class="fas fa-times"></i></button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-col">
                      <div class="form-group">
                        <label for="tourDays"><i class="fas fa-calendar-day"></i> Number of Days</label>
                        <input type="number" id="tourDays" name="tourDays" min="1" placeholder="e.g. 5" required />
                      </div>
                    </div>
                    <div class="form-col">
                      <div class="form-group image-upload">
                        <label for="coverImage"><i class="fas fa-image"></i> Cover Image</label>
                        <div class="image-preview" id="coverPreview">
                          <i class="fas fa-cloud-upload-alt"></i><span>Click to upload cover image</span>
                        </div>
                        <input type="file" id="coverImage" name="coverImage" accept="image/*" style="display: none;" />
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tourDesc"><i class="fas fa-align-left"></i> Short Description</label>
                    <textarea id="tourDesc" name="tourDesc" placeholder="Enter a brief description of the tour" required></textarea>
                  </div>

                  <h3 class="section-title"><i class="fas fa-images"></i> Highlight Images</h3>
                  <div class="highlight-images">
                    <?php for($i=1; $i<=4; $i++): ?>
                    <div class="form-group image-upload">
                      <div class="image-preview highlight-image" id="highlight<?php echo $i; ?>Preview">
                        <i class="fas fa-camera"></i><span>Highlight <?php echo $i; ?></span>
                      </div>
                      <input type="file" id="highlight<?php echo $i; ?>" name="highlight<?php echo $i; ?>" accept="image/*" style="display: none;" />
                    </div>
                    <?php endfor; ?>
                  </div>

                  <h3 class="section-title"><i class="fas fa-route"></i> Tour Itinerary</h3>
                  <div id="daysContainer">
                    <div class="day-container">
                      <div class="day-header"><h4 class="day-title"><i class="fas fa-map-marker-alt"></i> Activity 1</h4></div>
                      <div class="form-group">
                        <label><i class="fas fa-heading"></i> Activity Title</label>
                        <input type="text" class="activity-title" placeholder="Enter title" required>
                      </div>
                      <div class="form-group">
                        <label><i class="fas fa-align-left"></i> Activity Description</label>
                        <textarea class="activity-desc" placeholder="Describe the activity" required></textarea>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn add-btn" id="addActivityBtn"><i class="fas fa-plus"></i> Add More Activities</button>

                  <h3 class="section-title"><i class="fas fa-check-circle"></i> What's Included</h3>
                  <div class="list-container" id="includedList">
                    <div class="list-item"><input type="text" placeholder="Enter item"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>
                  </div>
                  <button type="button" class="btn add-btn" id="addIncludedBtn"><i class="fas fa-plus"></i> Add More</button>

                  <h3 class="section-title"><i class="fas fa-times-circle"></i> What's Excluded</h3>
                  <div class="list-container" id="excludedList">
                    <div class="list-item"><input type="text" placeholder="Enter item"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>
                  </div>
                  <button type="button" class="btn add-btn" id="addExcludedBtn"><i class="fas fa-plus"></i> Add More</button>

                  <h3 class="section-title"><i class="fas fa-suitcase"></i> What to Bring</h3>
                  <div class="list-container" id="bringList">
                    <div class="list-item"><input type="text" placeholder="Enter item"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>
                  </div>
                  <button type="button" class="btn add-btn" id="addBringBtn"><i class="fas fa-plus"></i> Add More</button>

                  <h3 class="section-title"><i class="fas fa-star"></i> Why Attend</h3>
                  <textarea id="whyAttend" name="whyAttend" placeholder="Enter reasons why people should attend this tour"></textarea>

                  <h3 class="section-title"><i class="fas fa-money-bill-wave"></i> Pricing Tiers</h3>
                  <div class="list-container" id="pricingTiersList">
                    <div class="list-item pricing-tier">
                      <input type="text" placeholder="Group Size (e.g. 1-2 people)" class="tier-group">
                      <input type="number" step="0.01" placeholder="Price" class="tier-price">
                      <button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                  <button type="button" class="btn add-btn" id="addPricingBtn"><i class="fas fa-plus"></i> Add More Tiers</button>

                  <h3 class="section-title"><i class="fas fa-sticky-note"></i> Pricing Notes</h3>
                  <div class="list-container" id="pricingNotesList">
                    <div class="list-item"><input type="text" placeholder="Enter pricing note" class="pricing-note"><button type="button" class="btn remove-btn"><i class="fas fa-trash"></i></button></div>
                  </div>
                  <button type="button" class="btn add-btn" id="addPricingNoteBtn"><i class="fas fa-plus"></i> Add More Notes</button>

                  <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Create Tour</button>
                </form>
              </div>

              <div class="table-section" id="tableSection">
                <?php
                $query = "SELECT t.tour_id, t.title, t.category, t.country, t.days_count, t.cover_image_path, t.short_description,
                          COUNT(DISTINCT td.day_id) as total_days, COUNT(DISTINCT th.highlight_id) as total_highlights
                          FROM tours t LEFT JOIN tour_days td ON t.tour_id = td.tour_id LEFT JOIN tour_highlights th ON t.tour_id = th.tour_id
                          GROUP BY t.tour_id ORDER BY t.created_at DESC";
                try {
                    $stmt = $pdo->query($query); $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <div class="tours-display list-view" id="toursDisplay">
                        <div class="table-container">
                            <table class="tours-table">
                                <thead>
                                    <tr><th>Image</th><th><i class="fas fa-heading"></i> Title</th><th><i class="fas fa-tag"></i> Category</th><th><i class="fas fa-globe"></i> Country</th><th><i class="fas fa-calendar-alt"></i> Activities</th><th><i class="fas fa-images"></i> Highlights</th><th style="text-align: right;"><i class="fas fa-cog"></i> Actions</th></tr>
                                 </thead>
                                 <tbody>
                                     <?php foreach ($tours as $row) { ?>
                                         <tr data-id="<?php echo $row['tour_id']; ?>">
                                             <td class="table-img"><img src="../../<?php echo htmlspecialchars($row['cover_image_path'] ?: 'images/default-tour.jpg'); ?>" alt=""></td>
                                             <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                             <td><span class="category-badge"><?php echo htmlspecialchars($row['category']); ?></span></td>
                                             <td><span class="country-badge"><?php echo htmlspecialchars($row['country']); ?></span></td>
                                             <td><?php echo $row['total_days']; ?> Days</td>
                                             <td><?php echo $row['total_highlights']; ?> Images</td>
                                            <td class="actions" style="justify-content: flex-end;">
                                                <button class="edit-btn" onclick="editTour(<?php echo $row['tour_id']; ?>)" title="Edit Tour"><i class="fas fa-edit"></i></button>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                    <input type="hidden" name="delete_tour" value="1"><input type="hidden" name="tour_id" value="<?php echo $row['tour_id']; ?>">
                                                    <button type="submit" class="delete-btn" title="Delete Tour"><i class="fas fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="cards-container">
                            <?php foreach ($tours as $row) { ?>
                                <div class="tour-card" data-id="<?php echo $row['tour_id']; ?>">
                                    <div class="card-img">
                                        <img src="../../<?php echo htmlspecialchars($row['cover_image_path'] ?: 'images/default-tour.jpg'); ?>" alt="">
                                        <div class="card-badges"><span class="card-category"><?php echo htmlspecialchars($row['category']); ?></span><span class="card-country"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($row['country']); ?></span></div>
                                    </div>
                                    <div class="card-content">
                                        <h3 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                                        <p class="card-desc"><?php echo htmlspecialchars(substr($row['short_description'], 0, 80)) . '...'; ?></p>
                                        <div class="card-meta"><span><i class="fas fa-calendar-alt"></i> <?php echo $row['total_days']; ?> Days</span><span><i class="fas fa-images"></i> <?php echo $row['total_highlights']; ?> Highlights</span></div>
                                        <div class="card-actions">
                                            <button class="edit-btn" onclick="editTour(<?php echo $row['tour_id']; ?>)"><i class="fas fa-edit"></i> Edit</button>
                                            <form method="POST" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="delete_tour" value="1"><input type="hidden" name="tour_id" value="<?php echo $row['tour_id']; ?>">
                                                <button type="submit" class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                } catch(PDOException $e) { echo "<p class='error-message'>Error: " . $e->getMessage() . "</p>"; }
                ?>
                <div class="pagination">
                    <?php
                    $total = $pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn();
                    $totalPages = ceil($total / 10); $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($totalPages > 1) {
                        if ($currentPage > 1) echo '<button class="page-btn" onclick="changePage('.($currentPage-1).')"><i class="fas fa-chevron-left"></i></button>';
                        for ($i = 1; $i <= $totalPages; $i++) echo '<button class="page-btn '.($i==$currentPage?'active':'').'" onclick="changePage('.$i.')">'.$i.'</button>';
                        if ($currentPage < $totalPages) echo '<button class="page-btn" onclick="changePage('.($currentPage+1).')"><i class="fas fa-chevron-right"></i></button>';
                    }
                    ?>
                </div>
              </div>

              <script>
              const addTourForm = document.getElementById("addTourForm");
              const tableSection = document.getElementById("tableSection");
              const addTourBtn = document.querySelector(".add-tour-btn");
              const btnText = addTourBtn.querySelector(".btn-text");

              function toggleFormAndTable() {
                addTourForm.classList.toggle("active");
                tableSection.classList.toggle("hidden");
                if (addTourBtn.dataset.state === "add") {
                  addTourBtn.dataset.state = "close";
                  btnText.textContent = "Close Form";
                } else {
                  addTourBtn.dataset.state = "add";
                  btnText.textContent = "Add New Tour";
                  if (typeof resetFormToAddMode === 'function') resetFormToAddMode();
                }
              }
              if (addTourBtn) addTourBtn.addEventListener("click", toggleFormAndTable);
              function changePage(page) { window.location.href = `?page=${page}`; }
              </script>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
