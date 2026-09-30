<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

// Verify admin authentication
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Admin session expired. Please log in again.']);
    exit();
}

require_once __DIR__ . '/../../config/connection.php';

// Accept blog_id from POST or JSON body
$blogId = isset($_POST['blog_id']) ? (int)$_POST['blog_id'] : 0;
if ($blogId <= 0) {
    $rawInput = file_get_contents('php://input');
    $jsonData = json_decode($rawInput, true);
    if (!empty($jsonData['blog_id'])) {
        $blogId = (int)$jsonData['blog_id'];
    }
}

if ($blogId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing blog ID.']);
    exit();
}

try {
    // 1. Fetch cover image
    $coverStmt = $conn->prepare("SELECT cover_image FROM blog_posts WHERE blog_id = ?");
    $coverStmt->bind_param("i", $blogId);
    $coverStmt->execute();
    $coverResult = $coverStmt->get_result();
    
    if ($coverResult->num_rows === 0) {
        $coverStmt->close();
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Blog post not found.']);
        exit();
    }
    
    $postRow = $coverResult->fetch_assoc();
    $coverImage = $postRow['cover_image'] ?? '';
    $coverStmt->close();

    // 2. Fetch gallery images
    $galleryImages = [];
    $galleryStmt = $conn->prepare("SELECT image_path FROM blog_gallery_images WHERE blog_id = ?");
    $galleryStmt->bind_param("i", $blogId);
    $galleryStmt->execute();
    $galleryResult = $galleryStmt->get_result();
    while ($gRow = $galleryResult->fetch_assoc()) {
        if (!empty($gRow['image_path'])) {
            $galleryImages[] = $gRow['image_path'];
        }
    }
    $galleryStmt->close();

    // 3. Fetch content block images
    $contentImages = [];
    $contentStmt = $conn->prepare("SELECT bib.image_path 
                                   FROM blog_image_blocks bib 
                                   JOIN blog_content_blocks bcb ON bib.block_id = bcb.block_id 
                                   WHERE bcb.blog_id = ?");
    $contentStmt->bind_param("i", $blogId);
    $contentStmt->execute();
    $contentResult = $contentStmt->get_result();
    while ($cRow = $contentResult->fetch_assoc()) {
        if (!empty($cRow['image_path'])) {
            $contentImages[] = $cRow['image_path'];
        }
    }
    $contentStmt->close();

    // 4. Delete the post from database (CASCADE deletes related blocks, gallery, comments)
    $delStmt = $conn->prepare("DELETE FROM blog_posts WHERE blog_id = ?");
    $delStmt->bind_param("i", $blogId);
    $deleted = $delStmt->execute();
    $delStmt->close();

    if (!$deleted) {
        throw new Exception("Database failed to delete blog post.");
    }

    // 5. Delete physical image files from disk
    $coversDir = __DIR__ . '/../../images/blog/covers/';
    if (!empty($coverImage) && file_exists($coversDir . $coverImage)) {
        @unlink($coversDir . $coverImage);
    }

    $galleryDir = __DIR__ . '/../../images/blog/gallery/';
    foreach ($galleryImages as $img) {
        if (!empty($img) && file_exists($galleryDir . $img)) {
            @unlink($galleryDir . $img);
        }
    }

    $contentDir = __DIR__ . '/../../images/blog/content/';
    foreach ($contentImages as $img) {
        if (!empty($img) && file_exists($contentDir . $img)) {
            @unlink($contentDir . $img);
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Blog post and associated media deleted successfully.'
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error deleting blog post: ' . $e->getMessage()
    ]);
    exit();
}
