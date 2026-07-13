<?php
// Include and get database connection
// require_once '../../config/database.php';
$pdo = include '../../config/blog.php';

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get blog ID from request (changed variable name for clarity)
    // Fix: Use 'blog_id' to match the key sent from JavaScript
    $blogId = $_POST['blog_id']; 

    // First delete related content blocks (Uses ON DELETE CASCADE, but explicit delete is safer)
    // Note: blog_content_blocks references blog_posts(blog_id)
    $deleteBlocks = $pdo->prepare("DELETE FROM blog_content_blocks WHERE blog_id = ?");
    $deleteBlocks->execute([$blogId]);

    // Delete gallery images (Updated table name and column name)
    // Note: blog_gallery_images references blog_posts(blog_id)
    $deleteGallery = $pdo->prepare("DELETE FROM blog_gallery_images WHERE blog_id = ?");
    $deleteGallery->execute([$blogId]);

    // Delete comments (Updated column name)
    // Note: blog_comments references blog_posts(blog_id)
    $deleteComments = $pdo->prepare("DELETE FROM blog_comments WHERE blog_id = ?");
    $deleteComments->execute([$blogId]);

    // Finally delete the blog post itself (Updated column name)
    $deletePost = $pdo->prepare("DELETE FROM blog_posts WHERE blog_id = ?");
    $deletePost->execute([$blogId]);

    // Commit the transaction
    $pdo->commit();

    // Return success response
    echo json_encode(['status' => 'success', 'message' => 'Blog post deleted successfully']);

} catch (Exception $e) {
    // Rollback the transaction on error
    if (isset($pdo) && $pdo->inTransaction()) { // Check if in transaction before rollback
        $pdo->rollBack();
    }

    // Return error response
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete blog post: ' . $e->getMessage()]); // Improved error message
}
?>