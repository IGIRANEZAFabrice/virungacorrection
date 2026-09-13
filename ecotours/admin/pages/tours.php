<?php
require_once '../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// Flash messages
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Handle AJAX Fetch Single Tour Details (for Quick View and Edit modal)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['fetch_tour'])) {
    header('Content-Type: application/json');
    try {
        $tour_id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tour) {
            echo json_encode(['success' => false, 'message' => 'Tour not found']);
            exit;
        }

        // Fetch tour days
        $stmt = $pdo->prepare("SELECT day_id, day_number, day_title, day_description FROM tour_days WHERE tour_id = ? ORDER BY day_number ASC");
        $stmt->execute([$tour_id]);
        $tour['days'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch tour highlights
        $stmt = $pdo->prepare("SELECT highlight_id, image_path, display_order FROM tour_highlights WHERE tour_id = ? ORDER BY display_order ASC");
        $stmt->execute([$tour_id]);
        $tour['highlights'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch included items
        $stmt = $pdo->prepare("SELECT included_id, item_description FROM tour_included WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['included'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch excluded items
        $stmt = $pdo->prepare("SELECT excluded_id, item_description FROM tour_excluded WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['excluded'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch to bring items
        $stmt = $pdo->prepare("SELECT to_bring_id, item_description FROM tour_to_bring WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour['to_bring'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch pricing tiers
        $stmt = $pdo->prepare("SELECT id, group_size, price_per_person FROM pricing_tiers WHERE tour_id = ? ORDER BY id ASC");
        $stmt->execute([$tour_id]);
        $tour['pricing_tiers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch pricing notes
        $stmt = $pdo->prepare("SELECT id, note FROM pricing_notes WHERE tour_id = ? ORDER BY id ASC");
        $stmt->execute([$tour_id]);
        $tour['pricing_notes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $tour]);
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Handle Delete Tour (POST via AJAX or standard form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['delete_tour']) || (isset($_POST['action']) && $_POST['action'] === 'delete'))) {
    $is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    try {
        $tour_id = (int)($_POST['tour_id'] ?? 0);
        if ($tour_id <= 0) throw new Exception("Invalid Tour ID.");

        $pdo->beginTransaction();

        $childTables = [
            'tour_highlights',
            'tour_days',
            'tour_included',
            'tour_excluded',
            'tour_to_bring',
            'pricing_tiers',
            'pricing_notes'
        ];

        foreach ($childTables as $table) {
            $stmt = $pdo->prepare("DELETE FROM $table WHERE tour_id = ?");
            $stmt->execute([$tour_id]);
        }

        // Get image paths before deleting record
        $stmt = $pdo->prepare("SELECT cover_image_path FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);
        $tour = $stmt->fetch();

        // Delete the main tour record
        $stmt = $pdo->prepare("DELETE FROM tours WHERE tour_id = ?");
        $stmt->execute([$tour_id]);

        // Delete associated cover image file if exists
        if ($tour && !empty($tour['cover_image_path'])) {
            $file_path = '../../' . $tour['cover_image_path'];
            if (file_exists($file_path) && is_file($file_path)) {
                @unlink($file_path);
            }
        }

        $pdo->commit();

        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Tour deleted successfully!']);
            exit;
        }

        $_SESSION['success_message'] = "Tour deleted successfully!";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error deleting tour: ' . $e->getMessage()]);
            exit;
        }
        $_SESSION['error_message'] = "Error deleting tour: " . $e->getMessage();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Handle Tour Create & Update (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST" && (isset($_POST['save_tour']) || isset($_POST['update_tour']) || isset($_POST['tourTitle']))) {
    header('Content-Type: application/json');
    try {
        $pdo->beginTransaction();
        $is_update = !empty($_POST['tour_id']);
        $tour_id = $is_update ? (int)$_POST['tour_id'] : null;

        $tourTitle = trim($_POST['tourTitle'] ?? '');
        $tourCategory = trim($_POST['tourCategory'] ?? '');
        $tourCountry = trim($_POST['tourCountry'] ?? '');
        $tourDays = max(1, (int)($_POST['tourDays'] ?? 1));
        $tourDesc = trim($_POST['tourDesc'] ?? '');
        $whyAttend = trim($_POST['whyAttend'] ?? '');

        if (empty($tourTitle)) throw new Exception("Tour title is required.");
        if (empty($tourCategory)) throw new Exception("Category is required.");
        if (empty($tourCountry)) throw new Exception("Country is required.");

        // Handle cover image upload
        $cover_image_path = null;
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../../images/tours/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
            $ext = strtolower(pathinfo($_FILES['coverImage']['name'], PATHINFO_EXTENSION));
            $file_name = uniqid('tour_') . '.' . $ext;
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $upload_dir . $file_name)) {
                $cover_image_path = 'images/tours/' . $file_name;
            }
        }

        if ($is_update) {
            $sql = "UPDATE tours SET title = ?, category = ?, country = ?, days_count = ?, short_description = ?, why_attend = ?";
            $params = [$tourTitle, $tourCategory, $tourCountry, $tourDays, $tourDesc, $whyAttend];
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
            $stmt->execute([$tourTitle, $tourCategory, $tourCountry, $tourDays, $cover_image_path, $tourDesc, $whyAttend]);
            $tour_id = (int)$pdo->lastInsertId();
        }

        // Process highlight images (1 to 4)
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_FILES["highlight$i"]) && $_FILES["highlight$i"]['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../../images/tours/highlights/';
                if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
                $ext = strtolower(pathinfo($_FILES["highlight$i"]['name'], PATHINFO_EXTENSION));
                $file_name = uniqid('hl_' . $i . '_') . '.' . $ext;
                if (move_uploaded_file($_FILES["highlight$i"]['tmp_name'], $upload_dir . $file_name)) {
                    $img_path = 'images/tours/highlights/' . $file_name;

                    if ($is_update) {
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

        // Child tables update
        $updateChildTable = function(PDO $pdo, int $tour_id, string $tableName, $dataJson, string $insertSql, callable $paramsMapFunc) {
            $stmt = $pdo->prepare("DELETE FROM $tableName WHERE tour_id = ?");
            $stmt->execute([$tour_id]);

            $items = is_string($dataJson) ? json_decode($dataJson, true) : (is_array($dataJson) ? $dataJson : []);
            if (!empty($items) && is_array($items)) {
                $stmt = $pdo->prepare($insertSql);
                foreach ($items as $item) {
                    $params = $paramsMapFunc($tour_id, $item);
                    if ($params !== null) {
                        $stmt->execute($params);
                    }
                }
            }
        };

        // Itinerary
        $updateChildTable($pdo, $tour_id, 'tour_days', $_POST['activities'] ?? '[]',
            "INSERT INTO tour_days (tour_id, day_number, day_title, day_description) VALUES (?, ?, ?, ?)",
            fn($tid, $i) => !empty($i['title']) ? [$tid, $i['day_number'] ?? 1, $i['title'], $i['description'] ?? ''] : null
        );

        // Inclusions
        $updateChildTable($pdo, $tour_id, 'tour_included', $_POST['includedItems'] ?? '[]',
            "INSERT INTO tour_included (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => (is_string($i) && trim($i) !== '') ? [$tid, trim($i)] : (!empty($i['item_description']) ? [$tid, trim($i['item_description'])] : null)
        );

        // Exclusions
        $updateChildTable($pdo, $tour_id, 'tour_excluded', $_POST['excludedItems'] ?? '[]',
            "INSERT INTO tour_excluded (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => (is_string($i) && trim($i) !== '') ? [$tid, trim($i)] : (!empty($i['item_description']) ? [$tid, trim($i['item_description'])] : null)
        );

        // What to bring
        $updateChildTable($pdo, $tour_id, 'tour_to_bring', $_POST['toBringItems'] ?? '[]',
            "INSERT INTO tour_to_bring (tour_id, item_description) VALUES (?, ?)",
            fn($tid, $i) => (is_string($i) && trim($i) !== '') ? [$tid, trim($i)] : (!empty($i['item_description']) ? [$tid, trim($i['item_description'])] : null)
        );

        // Pricing Tiers
        $updateChildTable($pdo, $tour_id, 'pricing_tiers', $_POST['pricingTiers'] ?? '[]',
            "INSERT INTO pricing_tiers (tour_id, group_size, price_per_person) VALUES (?, ?, ?)",
            fn($tid, $i) => (!empty($i['group_size']) && isset($i['price_per_person'])) ? [$tid, $i['group_size'], (float)$i['price_per_person']] : null
        );

        // Pricing Notes
        $updateChildTable($pdo, $tour_id, 'pricing_notes', $_POST['pricingNotes'] ?? '[]',
            "INSERT INTO pricing_notes (tour_id, note) VALUES (?, ?)",
            fn($tid, $i) => (is_string($i) && trim($i) !== '') ? [$tid, trim($i)] : (!empty($i['note']) ? [$tid, trim($i['note'])] : null)
        );

        $pdo->commit();
        echo json_encode([
            'success' => true,
            'message' => "Tour " . ($is_update ? "updated" : "created") . " successfully!",
            'tour_id' => $tour_id
        ]);
        exit;
    } catch (Exception $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => "Error: " . $e->getMessage()]);
        exit;
    }
}

// -------------------------------------------------------------
// Read & Infinite Scroll Parameters
// -------------------------------------------------------------
$page = max(1, isset($_GET['page']) ? (int)$_GET['page'] : 1);
$per_page = isset($_GET['per_page']) ? min(100, max(4, (int)$_GET['per_page'])) : 12; // Default 12 per batch for smooth infinite scrolling
$search = trim($_GET['search'] ?? '');
$category_filter = trim($_GET['category'] ?? '');
$country_filter = trim($_GET['country'] ?? '');
$sort = trim($_GET['sort'] ?? 'newest');

// Fetch distinct categories and countries for filter dropdowns
$categoriesList = $pdo->query("SELECT DISTINCT category FROM tours WHERE category IS NOT NULL AND category != '' ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
$countriesList = $pdo->query("SELECT DISTINCT country FROM tours WHERE country IS NOT NULL AND country != '' ORDER BY country ASC")->fetchAll(PDO::FETCH_COLUMN);

// Fetch Overall KPI Dashboard Stats
$stats = [
    'total_tours' => (int)$pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn(),
    'rwanda_tours' => (int)$pdo->query("SELECT COUNT(*) FROM tours WHERE LOWER(country) = 'rwanda'")->fetchColumn(),
    'other_tours' => (int)$pdo->query("SELECT COUNT(*) FROM tours WHERE LOWER(country) != 'rwanda'")->fetchColumn(),
    'total_categories' => count($categoriesList),
    'total_activities' => (int)$pdo->query("SELECT COUNT(*) FROM tour_days")->fetchColumn()
];

// Build Where Clause for query
$whereClauses = [];
$queryParams = [];

if ($search !== '') {
    $whereClauses[] = "(t.title LIKE :search OR t.short_description LIKE :search OR t.category LIKE :search OR t.country LIKE :search)";
    $queryParams[':search'] = '%' . $search . '%';
}

if ($category_filter !== '' && $category_filter !== 'all') {
    $whereClauses[] = "t.category = :category";
    $queryParams[':category'] = $category_filter;
}

if ($country_filter !== '' && $country_filter !== 'all') {
    $whereClauses[] = "LOWER(t.country) = LOWER(:country)";
    $queryParams[':country'] = $country_filter;
}

$whereSql = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

// Count Total Filtered Records
$countSql = "SELECT COUNT(*) FROM tours t $whereSql";
$countStmt = $pdo->prepare($countSql);
foreach ($queryParams as $key => $val) {
    $countStmt->bindValue($key, $val);
}
$countStmt->execute();
$total_records = (int)$countStmt->fetchColumn();

$total_pages = max(1, (int)ceil($total_records / $per_page));
if ($page > $total_pages && $total_records > 0) {
    $page = $total_pages;
}
$offset = ($page - 1) * $per_page;

// Determine Sort Order
$orderBy = match ($sort) {
    'oldest' => 't.created_at ASC, t.tour_id ASC',
    'title_asc' => 't.title ASC',
    'title_desc' => 't.title DESC',
    'days_desc' => 't.days_count DESC, t.title ASC',
    'days_asc' => 't.days_count ASC, t.title ASC',
    default => 't.created_at DESC, t.tour_id DESC'
};

// Fetch Paginated Tours
$toursQuery = "SELECT t.tour_id, t.title, t.category, t.country, t.days_count, t.cover_image_path, t.short_description, t.why_attend, t.created_at,
              COUNT(DISTINCT td.day_id) as total_days, COUNT(DISTINCT th.highlight_id) as total_highlights
              FROM tours t
              LEFT JOIN tour_days td ON t.tour_id = td.tour_id
              LEFT JOIN tour_highlights th ON t.tour_id = th.tour_id
              $whereSql
              GROUP BY t.tour_id
              ORDER BY $orderBy
              LIMIT :limit OFFSET :offset";

$toursStmt = $pdo->prepare($toursQuery);
foreach ($queryParams as $key => $val) {
    $toursStmt->bindValue($key, $val);
}
$toursStmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$toursStmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$toursStmt->execute();
$tours = $toursStmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * Render single tour Table Row (List View)
 */
function renderTourRow(array $row): string {
    $coverImg = !empty($row['cover_image_path']) ? '../../' . htmlspecialchars($row['cover_image_path']) : '../../images/default-tour.jpg';
    $tourId = (int)$row['tour_id'];
    $title = htmlspecialchars($row['title']);
    $titleEscaped = addslashes($title);
    $category = htmlspecialchars($row['category']);
    $country = htmlspecialchars(ucfirst($row['country']));
    $days = (int)$row['days_count'];
    $totalDays = (int)($row['total_days'] ?? 0);
    $totalHighlights = (int)($row['total_highlights'] ?? 0);

    return '
    <tr class="tour-row" data-id="' . $tourId . '">
      <td>
        <div class="thumb-cell" onclick="openPreviewModal(' . $tourId . ')">
          <img src="' . $coverImg . '" alt="' . $title . '" onerror="this.src=\'../../images/default-tour.jpg\';" />
        </div>
      </td>
      <td>
        <div class="tour-text-cell">
          <strong class="tour-title-link" onclick="openPreviewModal(' . $tourId . ')">
            ' . $title . '
          </strong>
          <span class="category-tag">' . $category . '</span>
        </div>
      </td>
      <td>
        <span class="country-badge-clean">
          ' . $country . '
        </span>
      </td>
      <td>
        <span class="duration-text"><i class="fas fa-clock"></i> ' . $days . ' Days</span>
      </td>
      <td>
        <span class="meta-count">' . $totalDays . ' Acts • ' . $totalHighlights . ' Photos</span>
      </td>
      <td style="text-align: right;">
        <div class="action-btn-row">
          <button type="button" class="action-icon-btn btn-view" onclick="openPreviewModal(' . $tourId . ')" title="View Details">
            <i class="fas fa-eye"></i>
          </button>
          <button type="button" class="action-icon-btn btn-edit" onclick="editTour(' . $tourId . ')" title="Edit Tour">
            <i class="fas fa-pen"></i>
          </button>
          <button type="button" class="action-icon-btn btn-delete" onclick="promptDeleteTour(' . $tourId . ', \'' . $titleEscaped . '\')" title="Delete Tour">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>';
}

/**
 * Render single tour Card (Grid View)
 */
function renderTourCard(array $row): string {
    $coverImg = !empty($row['cover_image_path']) ? '../../' . htmlspecialchars($row['cover_image_path']) : '../../images/default-tour.jpg';
    $tourId = (int)$row['tour_id'];
    $title = htmlspecialchars($row['title']);
    $titleEscaped = addslashes($title);
    $category = htmlspecialchars($row['category']);
    $country = htmlspecialchars(ucfirst($row['country']));
    $days = (int)$row['days_count'];
    $desc = htmlspecialchars(mb_strimwidth($row['short_description'] ?? '', 0, 95, '...'));

    return '
    <div class="tour-clean-card" data-id="' . $tourId . '">
      <div class="card-cover-media" onclick="openPreviewModal(' . $tourId . ')">
        <img src="' . $coverImg . '" alt="' . $title . '" onerror="this.src=\'../../images/default-tour.jpg\';" />
        <span class="card-country-pill">' . $country . '</span>
        <span class="card-days-pill">' . $days . ' Days</span>
      </div>

      <div class="card-body-inner">
        <span class="card-cat">' . $category . '</span>
        <h4 class="card-tour-heading" onclick="openPreviewModal(' . $tourId . ')">
          ' . $title . '
        </h4>
        <p class="card-desc-snippet">
          ' . $desc . '
        </p>

        <div class="card-footer-btns">
          <button type="button" class="btn btn-sm btn-outline btn-flex" onclick="openPreviewModal(' . $tourId . ')">
            <i class="fas fa-eye"></i> View
          </button>
          <button type="button" class="btn btn-sm btn-primary btn-flex" onclick="editTour(' . $tourId . ')">
            <i class="fas fa-edit"></i> Edit
          </button>
          <button type="button" class="btn btn-sm btn-outline btn-trash" onclick="promptDeleteTour(' . $tourId . ', \'' . $titleEscaped . '\')">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </div>
    </div>';
}

// Handle AJAX Infinite Scroll Request
if (isset($_GET['ajax_tours'])) {
    header('Content-Type: application/json');
    $rows_html = '';
    $cards_html = '';
    foreach ($tours as $row) {
        $rows_html .= renderTourRow($row);
        $cards_html .= renderTourCard($row);
    }
    echo json_encode([
        'success' => true,
        'page' => $page,
        'per_page' => $per_page,
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'has_more' => ($page < $total_pages),
        'count' => count($tours),
        'rows_html' => $rows_html,
        'cards_html' => $cards_html
    ]);
    exit;
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
    <link rel="stylesheet" href="../css/common.css?v=<?php echo time(); ?>" />
    <link rel="stylesheet" href="../css/tours.css?v=<?php echo time(); ?>" />
    <script src="../js/common.js?v=<?php echo time(); ?>" defer></script>
    <script src="../js/tours.js?v=<?php echo time(); ?>" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <?php include_once './includes/sidebar.php'; ?>
      
      <main class="main-content">
        <?php include_once './includes/header.php'; ?>

        <div class="tabler-tours-wrapper">
          
          <!-- Clean Page Header -->
          <div class="tours-page-header">
            <div class="header-titles">
              <h2 class="page-main-heading">Tours & Experiences</h2>
              <div class="page-meta-row">
                <span class="meta-item"><i class="fas fa-layer-group"></i> <?php echo $stats['total_tours']; ?> Packages</span>
                <span class="sep">•</span>
                <span class="meta-item"><i class="fas fa-tag"></i> <?php echo $stats['total_categories']; ?> Categories</span>
              </div>
            </div>
            
            <div class="header-actions-group">
              <div class="view-switch-pills">
                <button type="button" class="btn-pill-view active" id="cardViewBtn" title="Grid View">
                  <i class="fas fa-grip"></i> Grid
                </button>
                <button type="button" class="btn-pill-view" id="listViewBtn" title="List View">
                  <i class="fas fa-table-list"></i> List
                </button>
              </div>

              <button type="button" class="btn btn-sm btn-primary" id="openAddTourBtn">
                <i class="fas fa-plus"></i> Add New Tour
              </button>
            </div>
          </div>

          <!-- 4 Tabler-Style Metric KPI Cards -->
          <div class="tours-kpi-row">
            <div class="kpi-mini-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $stats['total_tours']; ?></span>
                <span class="kpi-badge badge-green"><i class="fas fa-arrow-up"></i> Live</span>
              </div>
              <span class="kpi-title">Total Tour Packages</span>
            </div>

            <div class="kpi-mini-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $stats['rwanda_tours']; ?></span>
                <span class="kpi-badge badge-blue">Rwanda</span>
              </div>
              <span class="kpi-title">Volcanoes & Primates</span>
            </div>

            <div class="kpi-mini-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $stats['other_tours']; ?></span>
                <span class="kpi-badge badge-amber">Regional</span>
              </div>
              <span class="kpi-title">Cross-Border Trips</span>
            </div>

            <div class="kpi-mini-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $stats['total_categories']; ?></span>
                <span class="kpi-badge badge-indigo">Styles</span>
              </div>
              <span class="kpi-title">Active Categories</span>
            </div>
          </div>

          <!-- Clean Filter Toolbar -->
          <div class="tours-filter-bar">
            <form method="GET" action="tours.php" class="filter-controls-row" id="filterForm">
              <div class="filter-search-input">
                <i class="fas fa-search"></i>
                <input 
                  type="text" 
                  name="search" 
                  id="tourSearchInput"
                  value="<?php echo htmlspecialchars($search); ?>" 
                  placeholder="Search tours..."
                  autocomplete="off"
                />
                <?php if (!empty($search)): ?>
                  <a href="tours.php" class="clear-search" title="Clear"><i class="fas fa-times"></i></a>
                <?php endif; ?>
              </div>

              <div class="filter-select-group">
                <select name="country" onchange="this.form.submit()" class="clean-select">
                  <option value="all">All Countries</option>
                  <option value="rwanda" <?php echo strtolower($country_filter) === 'rwanda' ? 'selected' : ''; ?>>Rwanda</option>
                  <option value="uganda" <?php echo strtolower($country_filter) === 'uganda' ? 'selected' : ''; ?>>Uganda</option>
                  <option value="congo" <?php echo strtolower($country_filter) === 'congo' || strtolower($country_filter) === 'dr congo' ? 'selected' : ''; ?>>DR Congo</option>
                  <option value="burundi" <?php echo strtolower($country_filter) === 'burundi' ? 'selected' : ''; ?>>Burundi</option>
                </select>

                <select name="category" onchange="this.form.submit()" class="clean-select">
                  <option value="all">All Categories</option>
                  <?php foreach ($categoriesList as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $category_filter === $cat ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($cat); ?>
                    </option>
                  <?php endforeach; ?>
                </select>

                <select name="sort" onchange="this.form.submit()" class="clean-select">
                  <option value="newest" <?php echo $sort === 'newest' ? 'selected' : ''; ?>>Newest First</option>
                  <option value="oldest" <?php echo $sort === 'oldest' ? 'selected' : ''; ?>>Oldest First</option>
                  <option value="title_asc" <?php echo $sort === 'title_asc' ? 'selected' : ''; ?>>Title (A-Z)</option>
                  <option value="title_desc" <?php echo $sort === 'title_desc' ? 'selected' : ''; ?>>Title (Z-A)</option>
                  <option value="days_desc" <?php echo $sort === 'days_desc' ? 'selected' : ''; ?>>Duration (Long)</option>
                  <option value="days_asc" <?php echo $sort === 'days_asc' ? 'selected' : ''; ?>>Duration (Short)</option>
                </select>

                <?php if (!empty($search) || (!empty($category_filter) && $category_filter !== 'all') || (!empty($country_filter) && $country_filter !== 'all') || $sort !== 'newest'): ?>
                  <a href="tours.php" class="btn-reset-link" title="Reset filters">
                    <i class="fas fa-rotate-left"></i> Reset
                  </a>
                <?php endif; ?>
              </div>
            </form>
          </div>

          <!-- Main Content: Data Table / Cards -->
          <div class="tours-content-box">
            <?php if (empty($tours)): ?>
              <div class="empty-state-clean">
                <i class="fas fa-compass"></i>
                <h4>No tour packages found</h4>
                <p>No results match your selected filters. Try resetting filters or add a new tour.</p>
                <div class="empty-btns">
                  <a href="tours.php" class="btn btn-sm btn-outline">Reset Filters</a>
                  <button type="button" class="btn btn-sm btn-primary" onclick="openAddTourModal()">+ Add New Tour</button>
                </div>
              </div>
            <?php else: ?>
              
              <div class="tours-display card-view" id="toursDisplay">
                
                <!-- Table View (List) -->
                <div class="table-container">
                  <table class="tabler-data-table">
                    <thead>
                      <tr>
                        <th style="width: 50px;">Photo</th>
                        <th>Tour Title & Category</th>
                        <th>Destination</th>
                        <th>Duration</th>
                        <th>Activities</th>
                        <th style="text-align: right; width: 110px;">Actions</th>
                      </tr>
                    </thead>
                    <tbody id="toursTableBody">
                      <?php foreach ($tours as $row) { echo renderTourRow($row); } ?>
                    </tbody>
                  </table>
                </div>

                <!-- Grid View (Cards) -->
                <div class="cards-container" id="toursCardsContainer">
                  <?php foreach ($tours as $row) { echo renderTourCard($row); } ?>
                </div>

              </div>

              <!-- Infinite Scroll Sentinel & Status Loader -->
              <div id="infiniteScrollSentinel" class="infinite-scroll-sentinel" data-page="1" data-total-pages="<?php echo $total_pages; ?>" data-total-records="<?php echo $total_records; ?>" data-has-more="<?php echo ($total_pages > 1) ? '1' : '0'; ?>">
                <div id="infiniteScrollLoader" class="infinite-loader" style="display: none;">
                  <i class="fas fa-circle-notch fa-spin"></i> Loading more tour packages...
                </div>
                <div id="infiniteScrollEnd" class="infinite-end-msg" style="<?php echo ($total_pages <= 1 && !empty($tours)) ? 'display: flex;' : 'display: none;'; ?>">
                  <span id="infiniteScrollEndText">All <?php echo $total_records; ?> tour packages loaded</span>
                </div>
              </div>

            <?php endif; ?>
          </div>

        </div>
      </main>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 1: ADD & EDIT TOUR (Multi-Step & Tabs Modal)           -->
    <!-- ============================================================ -->
    <div class="modal-backdrop" id="tourFormModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="dialog" aria-modal="true" aria-labelledby="modalFormTitle">
        <div class="modal-content">
          
          <div class="modal-header">
            <h4 class="modal-title" id="modalFormTitle">Add New Tour Package</h4>
            <button type="button" class="modal-close-btn" onclick="closeTourModal()" aria-label="Close modal">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <!-- Stepper Tab Navigation -->
          <div class="stepper-nav" id="formStepper">
            <button type="button" class="step-btn active" data-tab="tab-basics">
              <span class="step-num">1</span>
              <span class="step-label">Basics</span>
            </button>
            <button type="button" class="step-btn" data-tab="tab-itinerary">
              <span class="step-num">2</span>
              <span class="step-label">Itinerary</span>
            </button>
            <button type="button" class="step-btn" data-tab="tab-media">
              <span class="step-num">3</span>
              <span class="step-label">Media</span>
            </button>
            <button type="button" class="step-btn" data-tab="tab-inclusions">
              <span class="step-num">4</span>
              <span class="step-label">Inclusions</span>
            </button>
            <button type="button" class="step-btn" data-tab="tab-pricing">
              <span class="step-num">5</span>
              <span class="step-label">Pricing</span>
            </button>
          </div>

          <!-- Modal Body with Form -->
          <form id="tourForm" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="tour_id" id="formTourId" value="" />
            <input type="hidden" name="save_tour" value="1" />

            <div class="modal-body-scroll">
              
              <!-- STEP 1: BASICS -->
              <div class="tab-pane active" id="tab-basics">
                <div class="form-grid-2">
                  <div class="form-group full-span">
                    <label for="tourTitle" class="form-label required">Tour Title</label>
                    <input type="text" id="tourTitle" name="tourTitle" placeholder="e.g. 5-Day Living Virunga & Gorilla Immersion" required />
                  </div>

                  <div class="form-group">
                    <label for="tourCountry" class="form-label required">Destination Country</label>
                    <select id="tourCountry" name="tourCountry" required>
                      <option value="">-- Select Country --</option>
                      <option value="rwanda">Rwanda</option>
                      <option value="uganda">Uganda</option>
                      <option value="congo">DR Congo</option>
                      <option value="burundi">Burundi</option>
                      <option value="all">Multi-Country / Regional</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="tourCategory" class="form-label required">Category</label>
                    <div class="category-select-wrapper">
                      <select id="tourCategory" name="tourCategory" required>
                        <option value="">-- Select Category --</option>
                        <option value="Signature Journeys">Signature Journeys</option>
                        <option value="Private Experiences">Private Experiences</option>
                        <option value="Wildlife & The Volcanoes">Wildlife & The Volcanoes</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Cultural">Cultural</option>
                        <option value="City Tours">City Tours</option>
                        <option value="Community Based Tourism">Community Based Tourism</option>
                        <option value="Family Friendly">Family Friendly</option>
                        <option value="Food & Culinary">Food & Culinary</option>
                        <option value="Nature">Nature</option>
                        <option value="Off the beaten Path">Off the beaten Path</option>
                        <option value="Historical">Historical</option>
                        <option value="Spiritual">Spiritual</option>
                        <option value="add_new">+ Add Custom Category...</option>
                      </select>
                      
                      <div id="newCategoryContainer" class="new-category-inline" style="display: none;">
                        <input type="text" id="newCategoryInput" placeholder="Enter new category name..." maxlength="60" />
                        <div class="inline-btn-group">
                          <button type="button" id="confirmNewCategory" class="btn btn-sm btn-primary" title="Add"><i class="fas fa-check"></i></button>
                          <button type="button" id="cancelNewCategory" class="btn btn-sm btn-outline" title="Cancel"><i class="fas fa-times"></i></button>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="tourDays" class="form-label required">Total Duration (Days)</label>
                    <input type="number" id="tourDays" name="tourDays" min="1" max="90" placeholder="e.g. 5" required />
                  </div>
                </div>

                <div class="form-group full-span">
                  <label for="tourDesc" class="form-label required">Short Summary / Teaser</label>
                  <textarea id="tourDesc" name="tourDesc" rows="3" placeholder="Briefly describe what makes this experience extraordinary..." required></textarea>
                </div>

                <div class="form-group full-span">
                  <label for="whyAttend" class="form-label">Why Attend Overview</label>
                  <textarea id="whyAttend" name="whyAttend" rows="2" placeholder="Key highlights why travelers choose this expedition..."></textarea>
                </div>
              </div>

              <!-- STEP 2: ITINERARY -->
              <div class="tab-pane" id="tab-itinerary">
                <div class="tab-header-flex">
                  <h5>Day-by-Day Activities</h5>
                  <button type="button" class="btn btn-sm btn-outline" id="addActivityBtn">
                    <i class="fas fa-plus"></i> Add Activity
                  </button>
                </div>

                <div id="daysContainer" class="itinerary-builder-list">
                  <div class="day-card-item">
                    <div class="day-card-header">
                      <span class="day-num-badge">Activity 1</span>
                      <button type="button" class="btn-remove-day" title="Remove"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Title</label>
                      <input type="text" class="activity-title" placeholder="e.g. Arrival in Kigali & Scenic Transfer" required />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Description</label>
                      <textarea class="activity-desc" rows="2" placeholder="Describe the day's schedule..." required></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- STEP 3: MEDIA -->
              <div class="tab-pane" id="tab-media">
                <div class="media-upload-section">
                  <label class="form-label required">Cover Photo</label>
                  <div class="main-cover-dropzone" id="coverDropzone">
                    <div class="dropzone-preview" id="coverPreview">
                      <div class="dropzone-empty-prompt">
                        <i class="fas fa-cloud-arrow-up"></i>
                        <h5>Click or Drag Cover Image</h5>
                        <p>1920x1080px recommended</p>
                      </div>
                    </div>
                    <input type="file" id="coverImage" name="coverImage" accept="image/*" style="display: none;" />
                  </div>
                </div>

                <div class="highlights-upload-section">
                  <label class="form-label">Highlight Photos (Up to 4)</label>
                  <div class="highlights-grid">
                    <?php for ($i = 1; $i <= 4; $i++): ?>
                      <div class="highlight-dropzone" id="highlight<?php echo $i; ?>Dropzone">
                        <div class="dropzone-preview highlight-preview" id="highlight<?php echo $i; ?>Preview">
                          <i class="fas fa-image"></i>
                          <span>Highlight <?php echo $i; ?></span>
                        </div>
                        <input type="file" id="highlight<?php echo $i; ?>" name="highlight<?php echo $i; ?>" accept="image/*" style="display: none;" />
                      </div>
                    <?php endfor; ?>
                  </div>
                </div>
              </div>

              <!-- STEP 4: INCLUSIONS & EXCLUSIONS -->
              <div class="tab-pane" id="tab-inclusions">
                <div class="inclusions-grid-3">
                  <div class="inclusion-builder-card">
                    <div class="card-head">
                      <strong class="text-success"><i class="fas fa-check-circle"></i> Included</strong>
                    </div>
                    <div class="dynamic-items-list" id="includedList">
                      <div class="dynamic-item-row">
                        <input type="text" placeholder="e.g. Park Permit" />
                        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
                      </div>
                    </div>
                    <button type="button" class="btn-add-item" id="addIncludedBtn">+ Add Item</button>
                  </div>

                  <div class="inclusion-builder-card">
                    <div class="card-head">
                      <strong class="text-danger"><i class="fas fa-times-circle"></i> Excluded</strong>
                    </div>
                    <div class="dynamic-items-list" id="excludedList">
                      <div class="dynamic-item-row">
                        <input type="text" placeholder="e.g. Flights" />
                        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
                      </div>
                    </div>
                    <button type="button" class="btn-add-item" id="addExcludedBtn">+ Add Item</button>
                  </div>

                  <div class="inclusion-builder-card">
                    <div class="card-head">
                      <strong class="text-amber"><i class="fas fa-suitcase"></i> What to Bring</strong>
                    </div>
                    <div class="dynamic-items-list" id="bringList">
                      <div class="dynamic-item-row">
                        <input type="text" placeholder="e.g. Hiking Boots" />
                        <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
                      </div>
                    </div>
                    <button type="button" class="btn-add-item" id="addBringBtn">+ Add Item</button>
                  </div>
                </div>
              </div>

              <!-- STEP 5: PRICING -->
              <div class="tab-pane" id="tab-pricing">
                <div class="pricing-card">
                  <div class="pricing-card-header">
                    <strong><i class="fas fa-tags text-primary"></i> Pricing Tiers (USD / person)</strong>
                    <button type="button" class="btn-add-item" style="width: auto; padding: 4px 12px;" id="addPricingBtn">
                      <i class="fas fa-plus"></i> Add Tier
                    </button>
                  </div>
                  <p style="font-size: 11.5px; color: #64748b; margin: 0 0 8px;">Optional for custom/bespoke journeys. Leave blank if quoted on request.</p>
                  <div class="pricing-tier-list" id="pricingTiersList">
                    <div class="pricing-tier-row">
                      <div class="tier-col-group">
                        <label>Group Size</label>
                        <input type="text" class="tier-group" placeholder="e.g. 1 Person (Solo)" value="1 Person" />
                      </div>
                      <div class="tier-col-price">
                        <label>Price (USD)</label>
                        <div class="input-dollar">
                          <span>$</span>
                          <input type="number" step="0.01" class="tier-price" placeholder="e.g. 1500.00" />
                        </div>
                      </div>
                      <button type="button" class="btn-remove-tier" title="Remove"><i class="fas fa-trash"></i></button>
                    </div>
                  </div>
                </div>

                <div class="pricing-card" style="margin-top: 14px;">
                  <div class="pricing-card-header">
                    <strong><i class="fas fa-file-contract text-primary"></i> Pricing Notes & Terms</strong>
                    <button type="button" class="btn-add-item" style="width: auto; padding: 4px 12px;" id="addPricingNoteBtn">
                      <i class="fas fa-plus"></i> Add Note
                    </button>
                  </div>
                  <div class="pricing-notes-list" id="pricingNotesList">
                    <div class="pricing-note-row">
                      <input type="text" class="pricing-note" placeholder="e.g. Rates subject to permit availability." />
                      <button type="button" class="btn-remove-row"><i class="fas fa-times"></i></button>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <!-- Modal Sticky Footer -->
            <div class="modal-footer">
              <button type="button" class="btn-modal btn-modal-secondary" id="btnPrevStep" style="display: none;">
                <i class="fas fa-arrow-left"></i> Previous
              </button>
              <div class="modal-footer-right">
                <button type="button" class="btn-modal btn-modal-ghost" onclick="closeTourModal()">Cancel</button>
                <button type="button" class="btn-modal btn-modal-primary" id="btnNextStep">
                  Next Step <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn-modal btn-modal-success" id="btnSubmitForm" style="display: none;">
                  <i class="fas fa-check-circle"></i> <span id="submitBtnLabel">Save Package</span>
                </button>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 2: QUICK PREVIEW / VIEW TOUR MODAL                     -->
    <!-- ============================================================ -->
    <div class="modal-backdrop" id="quickViewModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="dialog" aria-modal="true" aria-labelledby="previewModalTitle">
        <div class="modal-content">
          
          <div class="quick-view-hero" id="qvHero">
            <div class="hero-overlay"></div>
            <button type="button" class="modal-close-btn hero-close" onclick="closeQuickViewModal()" aria-label="Close">
              <i class="fas fa-times"></i>
            </button>
            <div class="hero-content">
              <div class="hero-badges">
                <span class="badge" id="qvCategoryBadge">Category</span>
                <span class="badge" id="qvCountryBadge">Country</span>
                <span class="badge" id="qvDurationBadge">0 Days</span>
              </div>
              <h3 class="hero-title" id="qvTitle">Tour Title</h3>
            </div>
          </div>

          <div class="quick-view-tabs-nav">
            <button type="button" class="qv-tab-btn active" data-qv="qv-overview">Overview</button>
            <button type="button" class="qv-tab-btn" data-qv="qv-itinerary">Itinerary</button>
            <button type="button" class="qv-tab-btn" data-qv="qv-gallery">Highlights</button>
            <button type="button" class="qv-tab-btn" data-qv="qv-inclusions">Inclusions</button>
            <button type="button" class="qv-tab-btn" data-qv="qv-pricing">Pricing</button>
          </div>

          <div class="modal-body-scroll qv-body" id="qvBody">
            <div class="qv-tab-pane active" id="qv-overview">
              <p id="qvSummary" class="qv-lead-text"></p>
              <div id="qvWhyAttendWrap">
                <h5 style="margin-bottom: 6px;">Why Attend</h5>
                <div id="qvWhyAttend" class="qv-highlight-box"></div>
              </div>
            </div>

            <div class="qv-tab-pane" id="qv-itinerary">
              <div class="qv-itinerary-timeline" id="qvItineraryTimeline"></div>
            </div>

            <div class="qv-tab-pane" id="qv-gallery">
              <div class="qv-gallery-grid" id="qvGalleryGrid"></div>
            </div>

            <div class="qv-tab-pane" id="qv-inclusions">
              <div class="qv-inclusions-row">
                <div class="qv-inc-col">
                  <h5><i class="fas fa-check-circle text-success"></i> Included</h5>
                  <ul id="qvIncludedList" class="qv-bullet-list inc-list"></ul>
                </div>
                <div class="qv-inc-col">
                  <h5><i class="fas fa-times-circle text-danger"></i> Excluded</h5>
                  <ul id="qvExcludedList" class="qv-bullet-list exc-list"></ul>
                </div>
                <div class="qv-inc-col">
                  <h5><i class="fas fa-suitcase text-amber"></i> To Bring</h5>
                  <ul id="qvBringList" class="qv-bullet-list bring-list"></ul>
                </div>
              </div>
            </div>

            <div class="qv-tab-pane" id="qv-pricing">
              <div class="qv-tiers-cards" id="qvTiersCards"></div>
              <div id="qvNotesWrap" style="margin-top: 14px;">
                <h6>Important Notes</h6>
                <ul id="qvNotesList" class="qv-notes-list"></ul>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-outline" onclick="closeQuickViewModal()">Close</button>
            <button type="button" class="btn btn-sm btn-primary" id="qvEditBtn"><i class="fas fa-edit"></i> Edit Tour</button>
          </div>

        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 3: DELETE CONFIRMATION MODAL                           -->
    <!-- ============================================================ -->
    <div class="modal-backdrop" id="deleteModal" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle">
        <div class="modal-content" style="padding: 20px; text-align: center;">
          <h4 id="deleteModalTitle" style="margin-bottom: 8px;">Delete Tour Package</h4>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
            Are you sure you want to delete <strong id="deleteTourName">this tour</strong>?
          </p>

          <form id="deleteForm" method="POST">
            <input type="hidden" name="delete_tour" value="1" />
            <input type="hidden" name="tour_id" id="deleteTourId" value="" />
            <div style="display: flex; justify-content: center; gap: 8px;">
              <button type="button" class="btn btn-sm btn-outline" onclick="closeDeleteModal()">Cancel</button>
              <button type="submit" class="btn btn-sm" style="background: #fa5252; color: #fff;" id="confirmDeleteBtn">
                <i class="fas fa-trash"></i> Yes, Delete
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- ============================================================ -->
    <!-- MODAL 4: IMAGE LIGHTBOX                                      -->
    <!-- ============================================================ -->
    <div class="image-lightbox-modal" id="lightboxModal" onclick="closeLightbox()">
      <button type="button" class="lightbox-close"><i class="fas fa-times"></i></button>
      <img id="lightboxImg" src="" alt="Full Preview" onclick="event.stopPropagation()" />
      <div class="lightbox-caption" id="lightboxCaption"></div>
    </div>

    <!-- Toast Notification Container -->
    <div class="toast-container" id="toastContainer" aria-live="polite"></div>

    <?php if (!empty($success_message)): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          if (typeof showToast === 'function') {
            showToast('<?php echo addslashes($success_message); ?>', 'success');
          }
        });
      </script>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
      <script>
        document.addEventListener("DOMContentLoaded", function() {
          if (typeof showToast === 'function') {
            showToast('<?php echo addslashes($error_message); ?>', 'error');
          }
        });
      </script>
    <?php endif; ?>

  </body>
</html>
