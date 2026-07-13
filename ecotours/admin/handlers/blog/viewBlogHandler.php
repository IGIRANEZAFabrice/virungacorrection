<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once '../../config/connection.php';

// Compatibility function for get_result() - works with all MySQLi configurations
function getStmtResult($stmt) {
    if (method_exists($stmt, 'get_result')) {
        return $stmt->get_result();
    } else {
        // Fallback for servers without mysqlnd support
        $result = new stdClass();
        $result->data = [];
        $result->num_rows = 0;

        // Get metadata
        $metadata = $stmt->result_metadata();
        if ($metadata) {
            $fields = [];
            while ($field = $metadata->fetch_field()) {
                $fields[] = $field->name;
            }

            // Bind results
            $values = [];
            $refs = [];
            foreach ($fields as $field) {
                $refs[] = &$values[$field];
            }
            call_user_func_array([$stmt, 'bind_result'], $refs);

            // Fetch all rows
            while ($stmt->fetch()) {
                $row = [];
                foreach ($fields as $field) {
                    $row[$field] = $values[$field];
                }
                $result->data[] = $row;
            }
            $result->num_rows = count($result->data);
        }

        return $result;
    }
}

// Wrapper class for result object
class CompatibleResult {
    public $data = [];
    public $num_rows = 0;
    private $position = 0;

    public function fetch_assoc() {
        if ($this->position < count($this->data)) {
            return $this->data[$this->position++];
        }
        return null;
    }
}

// Function to get all blogs with pagination
function getAllBlogs($page = 1, $limit = 10) {
    global $conn;

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Get total count for pagination
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM blogs");
    $countStmt->execute();
    $totalResult = getStmtResult($countStmt);
    $totalRow = $totalResult->fetch_assoc();
    $totalBlogs = $totalRow['total'];

    // Get blogs with pagination
    $stmt = $conn->prepare("SELECT * FROM blogs ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $result = getStmtResult($stmt);

    $blogs = [];
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }

    return [
        'blogs' => $blogs,
        'total' => $totalBlogs,
        'pages' => ceil($totalBlogs / $limit),
        'current_page' => $page
    ];
}

// Function to get a single blog by ID
function getBlogById($blogId) {
    global $conn;

    // Get blog details
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $blogId);
    $stmt->execute();
    $result = getStmtResult($stmt);

    if ($result->num_rows === 0) {
        return null;
    }

    $blog = $result->fetch_assoc();

    // Get content blocks
    $blockStmt = $conn->prepare("SELECT * FROM blog_content_blocks WHERE blog_id = ? ORDER BY block_order ASC");
    $blockStmt->bind_param("i", $blogId);
    $blockStmt->execute();
    $blockResult = getStmtResult($blockStmt);

    $blog['content_blocks'] = [];
    while ($block = $blockResult->fetch_assoc()) {
        $blog['content_blocks'][] = $block;
    }

    // Get gallery images
    $galleryStmt = $conn->prepare("SELECT * FROM blog_gallery_images WHERE blog_id = ? ORDER BY display_order ASC");
    $galleryStmt->bind_param("i", $blogId);
    $galleryStmt->execute();
    $galleryResult = getStmtResult($galleryStmt);

    $blog['gallery_images'] = [];
    while ($image = $galleryResult->fetch_assoc()) {
        $blog['gallery_images'][] = $image;
    }

    return $blog;
}

// Function to get blogs by category
function getBlogsByCategory($category, $page = 1, $limit = 10) {
    global $conn;

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Get total count for pagination
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM blogs WHERE category = ?");
    $countStmt->bind_param("s", $category);
    $countStmt->execute();
    $totalResult = getStmtResult($countStmt);
    $totalRow = $totalResult->fetch_assoc();
    $totalBlogs = $totalRow['total'];

    // Get blogs with pagination
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE category = ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sii", $category, $limit, $offset);
    $stmt->execute();
    $result = getStmtResult($stmt);
    
    $blogs = [];
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
    
    return [
        'blogs' => $blogs,
        'total' => $totalBlogs,
        'pages' => ceil($totalBlogs / $limit),
        'current_page' => $page
    ];
}

// Function to search blogs
function searchBlogs($query, $page = 1, $limit = 10) {
    global $conn;

    // Prepare search query
    $searchQuery = "%$query%";

    // Calculate offset
    $offset = ($page - 1) * $limit;

    // Get total count for pagination
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM blogs WHERE blog_title LIKE ? OR big_title LIKE ? OR big_description LIKE ?");
    $countStmt->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
    $countStmt->execute();
    $totalResult = getStmtResult($countStmt);
    $totalRow = $totalResult->fetch_assoc();
    $totalBlogs = $totalRow['total'];

    // Get blogs with pagination
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_title LIKE ? OR big_title LIKE ? OR big_description LIKE ? ORDER BY created_at DESC LIMIT ? OFFSET ?");
    $stmt->bind_param("sssii", $searchQuery, $searchQuery, $searchQuery, $limit, $offset);
    $stmt->execute();
    $result = getStmtResult($stmt);
    
    $blogs = [];
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
    
    return [
        'blogs' => $blogs,
        'total' => $totalBlogs,
        'pages' => ceil($totalBlogs / $limit),
        'current_page' => $page
    ];
}

// Function to increment view count
function incrementBlogViews($blogId, $ipAddress, $userAgent) {
    global $conn;
    
    // Check if this IP has already viewed this blog recently (within 24 hours)
    $stmt = $conn->prepare("SELECT * FROM blog_views WHERE blog_id = ? AND ip_address = ? AND viewed_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)");
    $stmt->bind_param("is", $blogId, $ipAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If no recent view from this IP, increment view count and log the view
    if ($result->num_rows === 0) {
        // Update blog views count
        $updateStmt = $conn->prepare("UPDATE blogs SET views = views + 1 WHERE blog_id = ?");
        $updateStmt->bind_param("i", $blogId);
        $updateStmt->execute();
        
        // Log the view
        $logStmt = $conn->prepare("INSERT INTO blog_views (blog_id, ip_address, user_agent) VALUES (?, ?, ?)");
        $logStmt->bind_param("iss", $blogId, $ipAddress, $userAgent);
        $logStmt->execute();
        
        return true;
    }
    
    return false;
}

// Handle API requests
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $response = ['success' => false];
    
    switch ($action) {
        case 'get_all':
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $response = getAllBlogs($page, $limit);
            $response['success'] = true;
            break;
            
        case 'get_by_id':
            if (isset($_GET['id'])) {
                $blog = getBlogById((int)$_GET['id']);
                if ($blog) {
                    $response['blog'] = $blog;
                    $response['success'] = true;
                } else {
                    $response['message'] = 'Blog not found';
                }
            } else {
                $response['message'] = 'Blog ID is required';
            }
            break;
            
        case 'get_by_category':
            if (isset($_GET['category'])) {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $response = getBlogsByCategory($_GET['category'], $page, $limit);
                $response['success'] = true;
            } else {
                $response['message'] = 'Category is required';
            }
            break;
            
        case 'search':
            if (isset($_GET['query'])) {
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
                $response = searchBlogs($_GET['query'], $page, $limit);
                $response['success'] = true;
            } else {
                $response['message'] = 'Search query is required';
            }
            break;
            
        case 'increment_views':
            if (isset($_GET['id'])) {
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $result = incrementBlogViews((int)$_GET['id'], $ipAddress, $userAgent);
                $response['success'] = true;
                $response['incremented'] = $result;
            } else {
                $response['message'] = 'Blog ID is required';
            }
            break;
            
        default:
            $response['message'] = 'Invalid action';
    }
    
    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>