<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../pages/login.html');
    exit();
}

include '../../config/connection.php';

// Function to create a slug from a title
function createSlug($string) {
    $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    $string = strtolower(trim($string));
    $string = preg_replace('/\s+/', '-', $string);
    return $string;
}

// Function to upload image and return path
function uploadImage($file, $targetDir) {
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = time() . '_' . basename($file['name']);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    
    // Allow certain file formats
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif', 'webp');
    if (!in_array(strtolower($fileType), $allowedTypes)) {
        return false;
    }
    
    // Upload file
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return $fileName;
    }
    
    return false;
}

// Initialize response data
$response = [
    'status' => 'error',
    'message' => 'An unknown error occurred'
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $conn->begin_transaction();
        
        // Basic blog information
        $title = $_POST['blogTitle'];
        $author = $_POST['author'];
        $readMin = intval($_POST['readMin']);
        $category = $_POST['category'];
        $bigTitle = $_POST['bigTitle'];
        $bigDescription = $_POST['bigDescription'];
        $adminId = $_SESSION['admin_id'];
        $slug = createSlug($title) . '-' . uniqid();
        
        // Upload cover image
        $coverImagePath = uploadImage($_FILES['coverImage'], '../../images/blog/covers/');
        if (!$coverImagePath) {
            throw new Exception("Invalid cover image format. Please use JPG, JPEG, PNG or GIF.");
        }
        
        // Get category ID from slug
        $categoryQuery = "SELECT category_id FROM blog_categories WHERE category_slug = ?";
        $stmt = $conn->prepare($categoryQuery);
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $stmt->bind_result($categoryId);
        if (!$stmt->fetch()) {
            throw new Exception("Invalid category selected.");
        }
        $stmt->close();
        
        // Insert blog post
        $blogQuery = "INSERT INTO blog_posts (title, slug, author, read_minutes, category_id, cover_image, 
                      main_headline, introduction, status, created_by) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'published', ?)";
        $stmt = $conn->prepare($blogQuery);
        $stmt->bind_param("sssissssi", $title, $slug, $author, $readMin, $categoryId, 
                         $coverImagePath, $bigTitle, $bigDescription, $adminId);
        $stmt->execute();
        $blogId = $conn->insert_id;
        
        // Process content blocks
        $blockOrder = 1;
        
        // --- MODIFIED BLOCK DETECTION ---
        // Detect all potential block IDs from POST and FILES data
        $blockIds = [];
        $blockPattern = '/^block(Title|Description|Image|Quote|ListItems|ImageCaption|ImageAlignment|QuoteAttribution|QuoteStyle|ListTitle|ListType)(\d+)$/';
        
        // Check POST data
        foreach ($_POST as $key => $value) {
            if (preg_match($blockPattern, $key, $matches)) {
                $blockIds[$matches[2]] = true; // Use keys to store unique IDs
            }
            // Specifically check for list items array
            if (preg_match('/^blockListItems(\d+)$/', $key, $matches)) {
                 $blockIds[$matches[1]] = true;
            }
        }
        
        // Check FILES data for image blocks
        foreach ($_FILES as $key => $value) {
            if (preg_match('/^blockImage(\d+)$/', $key, $matches)) {
                if ($value['size'] > 0) { // Ensure a file was actually uploaded
                    $blockIds[$matches[1]] = true;
                }
            }
        }
        
        // Get unique block IDs and sort them numerically to maintain order
        $uniqueBlockIds = array_keys($blockIds);
        sort($uniqueBlockIds, SORT_NUMERIC);
        // --- END OF MODIFIED BLOCK DETECTION ---

        // Process each detected content block
        foreach ($uniqueBlockIds as $blockId) { // Use the new $uniqueBlockIds array
            $blockType = '';
            
            // Determine block type (using the existing logic, which is fine)
            // Check for text block first (most specific fields)
            if (isset($_POST['blockTitle' . $blockId]) || isset($_POST['blockDescription' . $blockId])) {
                 // Prioritize text if both title/desc and other fields exist for the same ID
                 // Check if description is non-empty, as title might be optional
                 if (!empty($_POST['blockDescription' . $blockId])) {
                    $blockType = 'text';
                 }
            } 
            
            // Check for image block if not text
            if (empty($blockType) && isset($_FILES['blockImage' . $blockId]) && $_FILES['blockImage' . $blockId]['size'] > 0) {
                $blockType = 'image';
            } 
            
            // Check for quote block if not text or image
            if (empty($blockType) && isset($_POST['blockQuote' . $blockId])) {
                 // Ensure quote text is not empty
                 if (!empty($_POST['blockQuote' . $blockId])) {
                    $blockType = 'quote';
                 }
            } 
            
            // Check for list block if not text, image, or quote
            if (empty($blockType) && isset($_POST['blockListItems' . $blockId])) {
                 // Ensure list items array is not empty
                 if (!empty($_POST['blockListItems' . $blockId]) && is_array($_POST['blockListItems' . $blockId])) {
                     // Check if at least one item is non-empty
                     $hasContent = false;
                     foreach($_POST['blockListItems' . $blockId] as $item) {
                         if (!empty(trim($item))) {
                             $hasContent = true;
                             break;
                         }
                     }
                     if ($hasContent) {
                        $blockType = 'list';
                     }
                 }
            }
            
            if (!empty($blockType)) {
                // Insert into content blocks table
                $blockQuery = "INSERT INTO blog_content_blocks (blog_id, block_type, block_order) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($blockQuery);
                $stmt->bind_param("isi", $blogId, $blockType, $blockOrder);
                $stmt->execute();
                $contentBlockId = $conn->insert_id;
                
                // Process specific block type
                switch ($blockType) {
                    case 'text':
                        $sectionTitle = isset($_POST['blockTitle' . $blockId]) ? 
                            $_POST['blockTitle' . $blockId] : '';
                        // Ensure description exists and is not empty before accessing
                        $content = isset($_POST['blockDescription' . $blockId]) ? 
                            $_POST['blockDescription' . $blockId] : '';
                        
                        // Only insert if content is not empty
                        if (!empty($content)) {
                            $textQuery = "INSERT INTO blog_text_blocks (block_id, section_title, content) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($textQuery);
                            $stmt->bind_param("iss", $contentBlockId, $sectionTitle, $content);
                            $stmt->execute();
                        } else {
                             // If content is empty, maybe rollback the content_block insert or skip?
                             // For now, let's assume an empty description means the block shouldn't be saved.
                             // We need to delete the previously inserted content block row.
                             $deleteEmptyBlock = $conn->prepare("DELETE FROM blog_content_blocks WHERE block_id = ?");
                             $deleteEmptyBlock->bind_param("i", $contentBlockId);
                             $deleteEmptyBlock->execute();
                             $contentBlockId = null; // Reset block ID
                        }
                        break;
                        
                    case 'image':
                        $imagePath = uploadImage($_FILES['blockImage' . $blockId], '../../images/blog/content/');
                        if (!$imagePath) {
                            // Rollback the content_block insert if image upload fails
                            $deleteEmptyBlock = $conn->prepare("DELETE FROM blog_content_blocks WHERE block_id = ?");
                            $deleteEmptyBlock->bind_param("i", $contentBlockId);
                            $deleteEmptyBlock->execute();
                            $contentBlockId = null; 
                            throw new Exception("Invalid or failed image upload in content block " . $blockId . ".");
                        }
                        
                        $caption = isset($_POST['blockImageCaption' . $blockId]) ? 
                            $_POST['blockImageCaption' . $blockId] : '';
                        $alignment = isset($_POST['blockImageAlignment' . $blockId]) ? 
                            $_POST['blockImageAlignment' . $blockId] : 'center';
                        
                        $imageQuery = "INSERT INTO blog_image_blocks (block_id, image_path, caption, alignment) 
                                       VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($imageQuery);
                        $stmt->bind_param("isss", $contentBlockId, $imagePath, $caption, $alignment);
                        $stmt->execute();
                        break;
                        
                    case 'quote':
                         // Ensure quote text exists and is not empty
                        $quoteText = isset($_POST['blockQuote' . $blockId]) ? 
                            $_POST['blockQuote' . $blockId] : '';

                        if (!empty($quoteText)) {
                            $attribution = isset($_POST['blockQuoteAttribution' . $blockId]) ? 
                                $_POST['blockQuoteAttribution' . $blockId] : '';
                            $style = isset($_POST['blockQuoteStyle' . $blockId]) ? 
                                $_POST['blockQuoteStyle' . $blockId] : 'standard';
                            
                            $quoteQuery = "INSERT INTO blog_quote_blocks (block_id, quote_text, attribution, style) 
                                           VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($quoteQuery);
                            $stmt->bind_param("isss", $contentBlockId, $quoteText, $attribution, $style);
                            $stmt->execute();
                        } else {
                            // Rollback content_block insert if quote text is empty
                            $deleteEmptyBlock = $conn->prepare("DELETE FROM blog_content_blocks WHERE block_id = ?");
                            $deleteEmptyBlock->bind_param("i", $contentBlockId);
                            $deleteEmptyBlock->execute();
                            $contentBlockId = null; 
                        }
                        break;
                        
                    case 'list':
                        // Ensure list items exist and is an array
                        $listItems = isset($_POST['blockListItems' . $blockId]) && is_array($_POST['blockListItems' . $blockId]) ? 
                            $_POST['blockListItems' . $blockId] : [];
                        
                        // Filter out empty items
                        $filteredListItems = array_filter($listItems, function($item) {
                            return !empty(trim($item));
                        });

                        if (!empty($filteredListItems)) {
                            // Remove real_escape_string
                            $listTitle = isset($_POST['blockListTitle' . $blockId]) ? 
                                $_POST['blockListTitle' . $blockId] : ''; 
                            $listType = isset($_POST['blockListType' . $blockId]) ? 
                                $_POST['blockListType' . $blockId] : 'bullet'; 
                            
                            $listQuery = "INSERT INTO blog_list_blocks (block_id, list_title, list_type) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($listQuery);
                            // Pass raw variables to bind_param
                            $stmt->bind_param("iss", $contentBlockId, $listTitle, $listType); 
                            $stmt->execute();
                            $listBlockId = $conn->insert_id;
                            
                            // Insert list items
                            $itemOrder = 1;
                            $itemQuery = "INSERT INTO blog_list_items (list_block_id, item_text, item_order) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($itemQuery);
                            
                            foreach ($filteredListItems as $item) {
                                // Remove real_escape_string
                                $itemText = $item; 
                                // Pass raw variable to bind_param
                                $stmt->bind_param("isi", $listBlockId, $itemText, $itemOrder); 
                                $stmt->execute();
                                $itemOrder++;
                            }
                        } else {
                             // Rollback content_block insert if list is empty
                            $deleteEmptyBlock = $conn->prepare("DELETE FROM blog_content_blocks WHERE block_id = ?");
                            $deleteEmptyBlock->bind_param("i", $contentBlockId);
                            $deleteEmptyBlock->execute();
                            $contentBlockId = null; 
                        }
                        break;
                }

                // Only increment blockOrder if a block was successfully inserted and not rolled back
                if ($contentBlockId !== null) {
                    $blockOrder++;
                }
            }
        } // End foreach ($uniqueBlockIds as $blockId)

        // Process gallery images (existing logic seems okay)
        $galleryOrder = 1;
        for ($i = 0; $i < 6; $i++) {
            $galleryInputName = "galleryImage" . ($i + 1);
            
            if (isset($_FILES[$galleryInputName]) && $_FILES[$galleryInputName]['size'] > 0) {
                $galleryPath = uploadImage($_FILES[$galleryInputName], '../../images/blog/gallery/');
                if (!$galleryPath) {
                    throw new Exception("Invalid gallery image format.");
                }
                
                $galleryQuery = "INSERT INTO blog_gallery_images (blog_id, image_path, image_order) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($galleryQuery);
                $stmt->bind_param("isi", $blogId, $galleryPath, $i);
                $stmt->execute();
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        $response['status'] = 'success';
        $response['message'] = 'Blog post created successfully!';
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        $response['message'] = $e->getMessage();
        // Log the detailed error
        error_log("Error creating blog post: " . $e->getMessage() . "\nStack trace: " . $e->getTraceAsString());

    } finally {
        // Close statement if it was prepared
        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        // Close connection
        $conn->close();
    }
    
    // Redirect back to the form page with status message
    $redirectUrl = '../../pages/blogs.php?status=' . $response['status'];
    if ($response['status'] === 'error') {
        $redirectUrl .= '&message=' . urlencode($response['message']);
    }
    header('Location: ' . $redirectUrl);
    exit();
    
} else {
    // Handle invalid request method
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}
?>