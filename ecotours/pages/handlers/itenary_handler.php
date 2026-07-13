<?php
// Check if we're on the production server or local environment
$handler_base_path = __DIR__;

// If we're on the production server, adjust the path
if (strpos(__FILE__, '/home2/dmxewbmy/public_html/website_58827336/') !== false) {
    // Create a path that works on the production server
    require_once '/home2/dmxewbmy/public_html/website_58827336/admin/config/database.php';
} else {
    // Use the local path
    require_once dirname(dirname(__FILE__)) . '/../admin/config/database.php';
}

function getItenaryData($country = 'rwanda', $type = null, $category = null) {
    global $pdo;
    $data = [];
    
    // Enable PDO error display for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sanitize inputs
    $country = strtolower($country);
    $category = $category ? strtolower($category) : null;
    
    // Test query to verify database connection
    try {
        $testQuery = "SELECT COUNT(*) FROM tours";
        $testStmt = $pdo->prepare($testQuery);
        $testStmt->execute();
        $testCount = $testStmt->fetchColumn();
        $data['debug_total'] = "<!-- Debug: Total tours in database: $testCount -->";
    } catch(PDOException $e) {
        $data['debug_total'] = "<!-- Database Test Error: " . htmlspecialchars($e->getMessage()) . " -->";
        error_log("Database Test Error: " . $e->getMessage());
    }
    
    // Build query based on tour type
    $query = "SELECT t.*, 
              COUNT(DISTINCT th.highlight_id) as total_highlights 
              FROM tours t 
              LEFT JOIN tour_highlights th ON t.tour_id = th.tour_id 
              WHERE t.country = :country ";
    
    // Only filter by days_count if type is specified
    if ($type === 'day') {
        $query .= "AND t.days_count = 1 ";
    } elseif ($type === 'multi') {
        $query .= "AND t.days_count > 1 ";
    }
    // If type is not specified, show all tours for the country
    
    // Add category filter if specified
    if ($category) {
        $query .= "AND LOWER(t.category) = :category ";
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
        
        // Also fetch all distinct categories for the filter buttons
        $categoryQuery = "SELECT DISTINCT category FROM tours WHERE category IS NOT NULL ORDER BY category";
        $categoryStmt = $pdo->query($categoryQuery);
        $data['categories'] = $categoryStmt->fetchAll(PDO::FETCH_COLUMN);
        
        // Debug information
        $data['debug_info'] = "<!-- Debug: Found " . count($data['tours']) . " tours -->";
        
        if (count($data['tours']) == 0) {
            // Check if the query actually returns data
            $checkQuery = "SELECT COUNT(*) FROM tours WHERE country = :country";
            $checkStmt = $pdo->prepare($checkQuery);
            $checkStmt->execute(['country' => $country]);
            $tourCount = $checkStmt->fetchColumn();
            $data['debug_info'] .= "<!-- Debug: Total tours in database for this country: $tourCount -->";
        }
    } catch(PDOException $e) {
        // Improved error handling
        $data['debug_info'] = "<!-- Database Error: " . htmlspecialchars($e->getMessage()) . " -->";
        // Log to a file instead of dying
        error_log("Database Error in tours page: " . $e->getMessage());
        $data['tours'] = []; // Set empty array so the page doesn't crash
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