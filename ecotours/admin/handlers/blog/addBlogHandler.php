<?php
// Include database connection
require_once '../../config/blog.php';
$pdo = include '../../config/blog.php';

// Function to create a slug from a title
function createSlug($title) {
    // Convert to lowercase and replace spaces with hyphens
    $slug = strtolower(trim($title));
    // Remove special characters
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    // Remove multiple hyphens
    $slug = preg_replace('/-+/', '-', $slug);
    // Remove leading/trailing hyphens
    $slug = trim($slug, '-');
    return $slug;
}

// Function to handle file images
function uploadFile($file, $directory) {
    // Create target directory if it doesn't exist
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }
    
    // Generate unique filename
    $filename = uniqid() . '_' . basename($file['name']);
    $targetPath = $directory . '/' . $filename;
    
    // Move uploaded file to target location
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return $filename;
    }
    
    return false;
}

try {    
    // Start transaction
    $pdo->beginTransaction();
    
    // Process main blog post data
    $title = $_POST['blogTitle'];
    $slug = createSlug($title);
    $author = $_POST['author'];
    $readTime = $_POST['readMin'];
    $category = $_POST['category'];
    $headline = $_POST['bigTitle'];
    $introduction = $_POST['bigDescription'];
    
    // Handle cover image upload
    $coverImage = '';
    if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
        $coverImage = uploadFile($_FILES['coverImage'], "../../images/blog/covers");
        if (!$coverImage) {
            throw new Exception("Failed to upload cover image");
        }
    } else {
        throw new Exception("Cover image is required");
    }
    
    // Insert blog post
    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, slug, cover_image, author, read_time, category, headline, introduction) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $coverImage, $author, $readTime, $category, $headline, $introduction]);
    
    // Get the new post ID
    $postId = $pdo->lastInsertId();
    
    // Process content blocks
    $blockOrder = 1;
    
    // Find all content blocks in the form
    foreach ($_POST as $key => $value) {
        // Process text blocks
        if (strpos($key, 'blockTitle') === 0 && !empty($value)) {
            $blockNumber = substr($key, 10);  // Extract number from blockTitle{N}
            $blockDescription = isset($_POST['blockDescription' . $blockNumber]) ? $_POST['blockDescription' . $blockNumber] : '';
            
            $stmt = $pdo->prepare("INSERT INTO blog_content_blocks (post_id, block_type, block_order, title, content) 
                                   VALUES (?, 'text', ?, ?, ?)");
            $stmt->execute([$postId, $blockOrder, $value, $blockDescription]);
            $blockOrder++;
        }
        
        // Process image blocks
        if (strpos($key, 'blockImageCaption') === 0 && !empty($value)) {
            $blockNumber = substr($key, 17);  // Extract number from blockImageCaption{N}
            
            // Handle image upload
            $blockImage = '';
            if (isset($_FILES['blockImage' . $blockNumber]) && $_FILES['blockImage' . $blockNumber]['error'] == 0) {
                $blockImage = uploadFile($_FILES['blockImage' . $blockNumber], "../../images/blog/content");
                if (!$blockImage) {
                    throw new Exception("Failed to upload block image");
                }
                
                $stmt = $pdo->prepare("INSERT INTO blog_content_blocks (post_id, block_type, block_order, image_path, image_caption) 
                                       VALUES (?, 'image', ?, ?, ?)");
                $stmt->execute([$postId, $blockOrder, $blockImage, $value]);
                $blockOrder++;
            }
        }
        
        // Process quote blocks
        if (strpos($key, 'blockQuote') === 0 && !empty($value) && strpos($key, 'blockQuoteAuthor') === false) {
            $blockNumber = substr($key, 10);  // Extract number from blockQuote{N}
            $blockQuoteAuthor = isset($_POST['blockQuoteAuthor' . $blockNumber]) ? $_POST['blockQuoteAuthor' . $blockNumber] : '';
            
            $stmt = $pdo->prepare("INSERT INTO blog_content_blocks (post_id, block_type, block_order, quote_text, quote_author) 
                                   VALUES (?, 'quote', ?, ?, ?)");
            $stmt->execute([$postId, $blockOrder, $value, $blockQuoteAuthor]);
            $blockOrder++;
        }
        
        // Process list blocks
        if (strpos($key, 'blockListTitle') === 0 && !empty($value)) {
            $blockNumber = substr($key, 14);  // Extract number from blockListTitle{N}
            
            // Get the list items
            $listItems = isset($_POST['listItem' . $blockNumber]) ? $_POST['listItem' . $blockNumber] : [];
            $listContent = '';
            
            if (!empty($listItems) && is_array($listItems)) {
                $listContent = json_encode($listItems);
            }
            
            $stmt = $pdo->prepare("INSERT INTO blog_content_blocks (post_id, block_type, block_order, title, content) 
                                   VALUES (?, 'list', ?, ?, ?)");
            $stmt->execute([$postId, $blockOrder, $value, $listContent]);
            $blockOrder++;
        }
    }
    
    // Process gallery images
    $galleryOrder = 1;
    
    // Loop through potential gallery image images (max 6)
    for ($i = 0; $i < 6; $i++) {
        $fieldName = 'galleryImage' . $i;
        
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] == 0) {
            $galleryImage = uploadFile($_FILES[$fieldName], "../../images/blog/gallery");
            if ($galleryImage) {
                $stmt = $pdo->prepare("INSERT INTO blog_gallery (post_id, image_path, image_order) 
                                       VALUES (?, ?, ?)");
                $stmt->execute([$postId, $galleryImage, $galleryOrder]);
                $galleryOrder++;
            }
        }
    }
    
    // Commit the transaction
    $pdo->commit();
    
    // Redirect with success message
    header("Location: ../../pages/create_blog.php?status=success");
    exit;
    
} catch (Exception $e) {
    // Rollback the transaction on error
    if (isset($pdo)) {
        $pdo->rollBack();
    }
    
    // Redirect with error message
    header("Location: ../../pages/create_blog.php?status=error&message=" . urlencode($e->getMessage()));
    exit;
}
?>