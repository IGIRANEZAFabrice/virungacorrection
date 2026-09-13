<?php
// Resolve path to database configuration
$db_file = dirname(__DIR__, 2) . '/admin/config/database.php';
if (file_exists($db_file)) {
    require_once $db_file;
} else {
    // Fallback relative path
    require_once __DIR__ . '/../../admin/config/database.php';
}

function getItenaryData($country = 'rwanda', $type = null, $category = null) {
    global $pdo;
    $data = [];
    
    if (!$pdo) {
        return ['tours' => [], 'categories' => []];
    }

    // Enable PDO error display for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sanitize inputs
    $country = strtolower(trim($country));
    $category = $category ? strtolower(trim($category)) : null;
    
    // Build query based on tour type
    $query = "SELECT t.*, 
              COUNT(DISTINCT th.highlight_id) as total_highlights 
              FROM tours t 
              LEFT JOIN tour_highlights th ON t.tour_id = th.tour_id 
              WHERE LOWER(TRIM(t.country)) = :country ";
    
    // Only filter by days_count if type is specified
    if ($type === 'day') {
        $query .= "AND t.days_count = 1 ";
    } elseif ($type === 'multi') {
        $query .= "AND t.days_count > 1 ";
    }
    
    // Add category filter if specified
    if ($category) {
        $query .= "AND LOWER(TRIM(t.category)) = :category ";
    }
    
    $query .= "GROUP BY t.tour_id ORDER BY t.created_at DESC";
    
    try {
        $stmt = $pdo->prepare($query);
        $params = ['country' => $country];
        if ($category) {
            $params['category'] = $category;
        }
        $stmt->execute($params);
        $data['tours'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Fetch distinct categories ONLY for the tours that exist for this country and type
        $categoryQuery = "SELECT DISTINCT category FROM tours WHERE LOWER(TRIM(country)) = :country AND category IS NOT NULL AND TRIM(category) != '' ";
        if ($type === 'day') {
            $categoryQuery .= "AND days_count = 1 ";
        } elseif ($type === 'multi') {
            $categoryQuery .= "AND days_count > 1 ";
        }
        $categoryQuery .= "ORDER BY category";
        $categoryStmt = $pdo->prepare($categoryQuery);
        $categoryStmt->execute(['country' => $country]);
        $allCategoriesForType = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);

        // If there are no tours for this country and type combination at all, categories MUST be empty
        if (empty($allCategoriesForType)) {
            $data['categories'] = [];
        } else {
            $data['categories'] = $allCategoriesForType;
        }
        
        // Debug information
        $data['debug_info'] = "<!-- Debug: Found " . count($data['tours']) . " tours -->";
    } catch(PDOException $e) {
        error_log("Database Error in tours page: " . $e->getMessage());
        $data['tours'] = [];
        $data['categories'] = [];
        $data['error'] = $e->getMessage();
    }
    
    return $data;
}

/**
 * Fetches tours that have pricing in the pricing_tiers table
 */
function getToursWithPricing($limit = 3) {
    global $pdo;
    try {
        $query = "SELECT t.* FROM tours t 
                  INNER JOIN pricing_tiers pt ON t.tour_id = pt.tour_id 
                  GROUP BY t.tour_id 
                  ORDER BY t.created_at DESC 
                  LIMIT :limit";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error in getToursWithPricing: " . $e->getMessage());
        return [];
    }
}

/**
 * Fetches the newest tours that do NOT have pricing in the pricing_tiers table
 */
function getNewestToursWithoutPricing($limit = 9) {
    global $pdo;
    try {
        $query = "SELECT t.* FROM tours t 
                  LEFT JOIN pricing_tiers pt ON t.tour_id = pt.tour_id 
                  WHERE pt.tour_id IS NULL 
                  ORDER BY t.created_at DESC 
                  LIMIT :limit";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error in getNewestToursWithoutPricing: " . $e->getMessage());
        return [];
    }
}

/**
 * Fetches tours by a keyword in the title
 */
function getToursByTitleKeyword($keyword, $limit = 9) {
    global $pdo;
    try {
        $query = "SELECT * FROM tours WHERE title LIKE :keyword ORDER BY created_at DESC LIMIT :limit";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error in getToursByTitleKeyword: " . $e->getMessage());
        return [];
    }
}

// If this file is accessed directly, return the data as JSON
if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
    header('Content-Type: application/json');
    $country = isset($_GET['country']) ? $_GET['country'] : 'rwanda';
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    $category = isset($_GET['category']) ? $_GET['category'] : null;
    echo json_encode(getItenaryData($country, $type, $category));
    exit;
}