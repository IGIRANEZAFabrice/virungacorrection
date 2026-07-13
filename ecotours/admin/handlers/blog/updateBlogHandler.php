<?php
session_start();
// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../pages/login.html');
    exit();
}

include '../../config/connection.php'; // $conn is available from here

// Function to create a slug (remains the same)
function createSlug($string) {
    $string = preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    $string = strtolower(trim($string));
    $string = preg_replace('/\s+/', '-', $string);
    return $string;
}

// Function to upload image (remains the same, ensure paths are correct)
function uploadImage($file, $targetDir) {
    if (!file_exists($targetDir)) {
        if (!mkdir($targetDir, 0777, true)) {
            error_log("Failed to create directory: " . $targetDir);
            return ['status' => 'error', 'message' => 'Failed to create image directory.'];
        }
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['status' => 'no_file']; // Indicate no file was uploaded
        }
        error_log("File upload error code: " . $file['error']);
        return ['status' => 'error', 'message' => 'File upload error: ' . $file['error']];
    }

    if ($file['size'] == 0) {
         return ['status' => 'no_file']; // No actual file content
    }

    // Sanitize filename more robustly
    $baseName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $safeBaseName = preg_replace("/[^a-zA-Z0-9_-]/", "_", $baseName);
    $fileName = time() . '_' . $safeBaseName . '.' . $extension;

    $targetFilePath = $targetDir . $fileName;

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedTypes)) {
        error_log("Invalid file type uploaded: " . $extension . " for file " . $fileName);
        return ['status' => 'error', 'message' => 'Invalid file type. Allowed types: JPG, JPEG, PNG, GIF, WEBP.'];
    }

    if ($file['size'] > 10 * 1024 * 1024) { // Increased to 10 MB
        error_log("File size exceeds limit for " . $fileName);
        return ['status' => 'error', 'message' => 'File size exceeds limit (10MB).'];
    }

    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        return ['status' => 'success', 'filename' => $fileName];
    } else {
        error_log("Failed to move uploaded file: " . $file['name'] . " to " . $targetFilePath . " - Check permissions and path.");
        return ['status' => 'error', 'message' => 'Failed to save uploaded file. Check server permissions.'];
    }
}

// Function to delete an image file
function deleteImageFile($filePath) {
    if (file_exists($filePath) && is_file($filePath)) {
        unlink($filePath);
    }
}


// --- Main Processing Logic ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn->begin_transaction();
    try {
        // --- Basic Blog Information ---
        $blog_id = isset($_POST['blog_id']) ? intval($_POST['blog_id']) : 0;
        if ($blog_id <= 0) {
            throw new Exception("Invalid blog ID provided.");
        }

        // Fetch existing post data to manage image deletion later
        $stmt = $conn->prepare("SELECT cover_image FROM blog_posts WHERE blog_id = ?");
        $stmt->bind_param("i", $blog_id);
        $stmt->execute();
        $existing_post_result = $stmt->get_result();
        if ($existing_post_result->num_rows === 0) {
            throw new Exception("Blog post not found.");
        }
        $existing_post = $existing_post_result->fetch_assoc();
        $old_cover_image = $existing_post['cover_image'];
        $stmt->close();


        $title = $conn->real_escape_string(stripslashes($_POST['blogTitle']));
        $slug = createSlug($title); // Generate slug from title
        $author = $conn->real_escape_string(stripslashes($_POST['author']));
        $readMin = intval($_POST['readMin']);
        $category_slug = $conn->real_escape_string($_POST['category']); // Expecting slug from form
        $main_headline = $conn->real_escape_string(stripslashes($_POST['bigTitle']));
        $introduction = $conn->real_escape_string(stripslashes($_POST['bigDescription']));
        $adminId = $_SESSION['admin_id']; // Assuming admin ID is needed for tracking/logging

        // Get category ID from the submitted slug
        $stmt = $conn->prepare("SELECT category_id FROM blog_categories WHERE category_slug = ?");
        $stmt->bind_param("s", $category_slug);
        $stmt->execute();
        $categoryResult = $stmt->get_result();
        if ($categoryResult->num_rows === 0) {
            throw new Exception("Invalid category selected. Slug not found: " . htmlspecialchars($category_slug));
        }
        $categoryRow = $categoryResult->fetch_assoc();
        $categoryId = $categoryRow['category_id'];
        $stmt->close();

        // --- Handle Cover Image ---
        $coverImagePath = $old_cover_image; // Default to existing image
        $newCoverImageUploaded = false;
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['coverImage']['size'] > 0) {
            $uploadResult = uploadImage($_FILES['coverImage'], '../../images/blog/covers/');
            if ($uploadResult['status'] === 'success') {
                $coverImagePath = $uploadResult['filename'];
                $newCoverImageUploaded = true;
            } elseif ($uploadResult['status'] === 'error') {
                throw new Exception("Cover Image Upload Error: " . $uploadResult['message']);
            }
            // 'no_file' status is ignored here, means keep existing
        }

        // --- Update Blog Post ---
        $sql = "UPDATE blog_posts SET
                    title = ?,
                    slug = ?,
                    author = ?,
                    read_minutes = ?,
                    category_id = ?,
                    cover_image = ?,
                    main_headline = ?,
                    introduction = ?,
                    updated_at = NOW()
                WHERE blog_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed (blog_posts): " . $conn->error);
        }
        $stmt->bind_param("sssiisssi",
            $title, $slug, $author, $readMin, $categoryId,
            $coverImagePath, $main_headline, $introduction, $blog_id
        );
        if (!$stmt->execute()) {
             throw new Exception("Execute failed (blog_posts): " . $stmt->error);
        }
        $stmt->close();

        // Delete old cover image if a new one was uploaded successfully
        if ($newCoverImageUploaded && !empty($old_cover_image) && $old_cover_image !== $coverImagePath) {
            deleteImageFile('../../images/blog/covers/' . $old_cover_image);
        }


        // --- Handle Content Blocks ---

        // 1. Fetch existing block data (IDs and image paths for deletion)
        $existing_blocks_query = "
            SELECT bc.block_id, bc.block_type, bi.image_path
            FROM blog_content_blocks bc
            LEFT JOIN blog_image_blocks bi ON bc.block_id = bi.block_id
            WHERE bc.blog_id = ?
        ";
        $stmt = $conn->prepare($existing_blocks_query);
        $stmt->bind_param("i", $blog_id);
        $stmt->execute();
        $existing_blocks_result = $stmt->get_result();
        $existing_blocks_data = [];
        while ($row = $existing_blocks_result->fetch_assoc()) {
            $existing_blocks_data[$row['block_id']] = $row;
        }
        $stmt->close();
        $processed_block_ids = []; // Keep track of block IDs submitted in the form

        // 2. Process submitted blocks
        $block_types = $_POST['block_type'] ?? [];
        $block_ids_from_form = $_POST['block_id'] ?? []; // Existing block IDs from hidden fields
        $block_orders = $_POST['block_order'] ?? []; // Should match array indices

        // Map submitted data for easier access (assuming arrays are indexed 0, 1, 2...)
        $blockTitles = $_POST['blockTitle'] ?? [];
        $blockContents = $_POST['blockContent'] ?? [];
        $blockImageCaptions = $_POST['blockImageCaption'] ?? [];
        $existingBlockImages = $_POST['existing_block_image'] ?? [];
        $blockQuotes = $_POST['blockQuote'] ?? [];
        $blockQuoteAuthors = $_POST['blockQuoteAuthor'] ?? [];
        $blockListTitles = $_POST['blockListTitle'] ?? [];
        $listItems = $_POST['listItems'] ?? []; // This will be a nested array

        for ($i = 0; $i < count($block_types); $i++) {
            $block_type = $conn->real_escape_string($block_types[$i]);
            $current_block_id = isset($block_ids_from_form[$i]) ? intval($block_ids_from_form[$i]) : 0; // 0 for new blocks
            $block_order = $i + 1; // Use loop index for order

            $content_block_id = 0; // Will hold the ID after insert/update

            // Check if it's an existing block being updated or a new block
            if ($current_block_id > 0 && isset($existing_blocks_data[$current_block_id])) {
                // Update existing block
                $content_block_id = $current_block_id;
                $processed_block_ids[] = $content_block_id; // Mark as processed

                $update_block_sql = "UPDATE blog_content_blocks SET block_type = ?, block_order = ? WHERE block_id = ?";
                $stmt = $conn->prepare($update_block_sql);
                $stmt->bind_param("sii", $block_type, $block_order, $content_block_id);
                $stmt->execute();
                $stmt->close();

                // Delete old block-specific data before inserting updated data
                // (Simpler than checking type change and updating specific tables)
                $conn->query("DELETE FROM blog_text_blocks WHERE block_id = $content_block_id");
                $conn->query("DELETE FROM blog_image_blocks WHERE block_id = $content_block_id");
                $conn->query("DELETE FROM blog_quote_blocks WHERE block_id = $content_block_id");
                // Need to handle list items deletion carefully if updating a list block
                $list_block_id_result = $conn->query("SELECT list_block_id FROM blog_list_blocks WHERE block_id = $content_block_id");
                if($list_block_id_result && $list_block_id_row = $list_block_id_result->fetch_assoc()) {
                    $conn->query("DELETE FROM blog_list_items WHERE list_block_id = " . $list_block_id_row['list_block_id']);
                }
                $conn->query("DELETE FROM blog_list_blocks WHERE block_id = $content_block_id");

            } else {
                // Insert new block
                $insert_block_sql = "INSERT INTO blog_content_blocks (blog_id, block_type, block_order) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_block_sql);
                $stmt->bind_param("isi", $blog_id, $block_type, $block_order);
                $stmt->execute();
                $content_block_id = $conn->insert_id; // Get the new block ID
                $stmt->close();
            }

            // Insert data into the specific block type table
            switch ($block_type) {
                case 'text':
                    $title_val = $conn->real_escape_string(stripslashes($blockTitles[$i] ?? ''));
                    $content_val = $conn->real_escape_string(stripslashes($blockContents[$i] ?? ''));
                    $sql = "INSERT INTO blog_text_blocks (block_id, section_title, content) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iss", $content_block_id, $title_val, $content_val);
                    $stmt->execute();
                    $stmt->close();
                    break;

                case 'image':
                    $caption_val = $conn->real_escape_string(stripslashes($blockImageCaptions[$i] ?? ''));
                    $alignment_val = 'center'; // Default or get from form if added
                    $image_path_val = $existingBlockImages[$i] ?? null; // Existing path from hidden field
                    $old_block_image_path = $existing_blocks_data[$current_block_id]['image_path'] ?? null; // Get old path for deletion check
                    $newBlockImageUploaded = false;

                    // Check for new file upload for this block
                    $file_key = 'blockImage'; // Assuming name="blockImage[]"
                    if (isset($_FILES[$file_key]) && $_FILES[$file_key]['error'][$i] !== UPLOAD_ERR_NO_FILE && $_FILES[$file_key]['size'][$i] > 0) {
                         // Reconstruct the file array for the specific index
                        $block_file = [
                            'name' => $_FILES[$file_key]['name'][$i],
                            'type' => $_FILES[$file_key]['type'][$i],
                            'tmp_name' => $_FILES[$file_key]['tmp_name'][$i],
                            'error' => $_FILES[$file_key]['error'][$i],
                            'size' => $_FILES[$file_key]['size'][$i]
                        ];
                        $uploadResult = uploadImage($block_file, '../../images/blog/content/');
                        if ($uploadResult['status'] === 'success') {
                            $image_path_val = $uploadResult['filename'];
                            $newBlockImageUploaded = true;
                        } elseif ($uploadResult['status'] === 'error') {
                            throw new Exception("Block Image Upload Error (Index $i): " . $uploadResult['message']);
                        }
                    }

                    if (!empty($image_path_val)) {
                        $sql = "INSERT INTO blog_image_blocks (block_id, image_path, caption, alignment) VALUES (?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("isss", $content_block_id, $image_path_val, $caption_val, $alignment_val);
                        $stmt->execute();
                        $stmt->close();

                        // Delete old block image if a new one was uploaded
                        if ($newBlockImageUploaded && !empty($old_block_image_path) && $old_block_image_path !== $image_path_val) {
                             deleteImageFile('../../images/blog/content/' . $old_block_image_path);
                        }
                    } else {
                         error_log("Skipping image block insert for block_id $content_block_id as no image path was determined.");
                    }
                    break;

                case 'quote':
                    $quote_val = $conn->real_escape_string(stripslashes($blockQuotes[$i] ?? ''));
                    $author_val = $conn->real_escape_string(stripslashes($blockQuoteAuthors[$i] ?? ''));
                    $style_val = 'standard'; // Default or get from form if added
                    $sql = "INSERT INTO blog_quote_blocks (block_id, quote_text, attribution, style) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("isss", $content_block_id, $quote_val, $author_val, $style_val);
                    $stmt->execute();
                    $stmt->close();
                    break;

                case 'list':
                    $list_title_val = $conn->real_escape_string(stripslashes($blockListTitles[$i] ?? ''));
                    $list_type_val = 'bullet'; // Default or get from form if added
                    $sql = "INSERT INTO blog_list_blocks (block_id, list_title, list_type) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iss", $content_block_id, $list_title_val, $list_type_val);
                    $stmt->execute();
                    $list_block_id = $conn->insert_id; // Get the ID for the list block itself
                    $stmt->close();

                    // Process list items for this specific block
                    $current_list_items = $listItems[$i] ?? []; // Get items for the current list block index
                    if (is_array($current_list_items)) {
                        $item_order = 1;
                        foreach ($current_list_items as $item_text) {
                            if (!empty(trim($item_text))) { // Avoid inserting empty items
                                $item_text_val = $conn->real_escape_string(stripslashes($item_text));
                                $item_sql = "INSERT INTO blog_list_items (list_block_id, item_text, item_order) VALUES (?, ?, ?)";
                                $item_stmt = $conn->prepare($item_sql);
                                $item_stmt->bind_param("isi", $list_block_id, $item_text_val, $item_order);
                                $item_stmt->execute();
                                $item_stmt->close();
                                $item_order++;
                            }
                        }
                    }
                    break;
            }
        }

        // 3. Delete blocks that were in the DB but not submitted in the form (removed by user)
        $blocks_to_delete = array_diff(array_keys($existing_blocks_data), $processed_block_ids);
        if (!empty($blocks_to_delete)) {
            $delete_ids_str = implode(',', $blocks_to_delete);
            // Delete associated images first
            foreach($blocks_to_delete as $del_id) {
                if ($existing_blocks_data[$del_id]['block_type'] === 'image' && !empty($existing_blocks_data[$del_id]['image_path'])) {
                    deleteImageFile('../../images/blog/content/' . $existing_blocks_data[$del_id]['image_path']);
                }
                 // Add deletion for list items if needed
                if ($existing_blocks_data[$del_id]['block_type'] === 'list') {
                     $list_block_id_result = $conn->query("SELECT list_block_id FROM blog_list_blocks WHERE block_id = $del_id");
                     if($list_block_id_result && $list_block_id_row = $list_block_id_result->fetch_assoc()) {
                         $conn->query("DELETE FROM blog_list_items WHERE list_block_id = " . $list_block_id_row['list_block_id']);
                     }
                }
            }
            // Delete from all block type tables and the main content block table
            $conn->query("DELETE FROM blog_text_blocks WHERE block_id IN ($delete_ids_str)");
            $conn->query("DELETE FROM blog_image_blocks WHERE block_id IN ($delete_ids_str)");
            $conn->query("DELETE FROM blog_quote_blocks WHERE block_id IN ($delete_ids_str)");
            $conn->query("DELETE FROM blog_list_blocks WHERE block_id IN ($delete_ids_str)"); // List items deleted above
            $conn->query("DELETE FROM blog_content_blocks WHERE block_id IN ($delete_ids_str)");
        }


        // --- Handle Gallery Images ---
        $existing_gallery_images = [];
        $stmt = $conn->prepare("SELECT gallery_image_id, image_path FROM blog_gallery_images WHERE blog_id = ? ORDER BY gallery_image_id ASC"); // Assuming order matters or use a specific order column
        $stmt->bind_param("i", $blog_id);
        $stmt->execute();
        $gallery_result = $stmt->get_result();
        while($row = $gallery_result->fetch_assoc()) {
            $existing_gallery_images[$row['gallery_image_id']] = $row['image_path'];
        }
        $stmt->close();

        for ($i = 1; $i <= 6; $i++) { // Assuming max 6 gallery images based on edit form
            $file_input_name = 'galleryUpload' . $i;
            $existing_image_input_name = 'existing_gallery_image' . $i;
            $gallery_id_input_name = 'gallery_image_id' . $i; // Hidden input holding the ID

            $gallery_id = isset($_POST[$gallery_id_input_name]) ? intval($_POST[$gallery_id_input_name]) : 0;
            $existing_path = isset($_POST[$existing_image_input_name]) ? $_POST[$existing_image_input_name] : null;
            $current_path_in_db = $existing_gallery_images[$gallery_id] ?? null; // Path currently in DB for this ID
            $new_path = null;
            $newGalleryImageUploaded = false;

            // Check for new upload
            if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] !== UPLOAD_ERR_NO_FILE && $_FILES[$file_input_name]['size'] > 0) {
                $uploadResult = uploadImage($_FILES[$file_input_name], '../../images/blog/gallery/');
                if ($uploadResult['status'] === 'success') {
                    $new_path = $uploadResult['filename'];
                    $newGalleryImageUploaded = true;
                } elseif ($uploadResult['status'] === 'error') {
                    throw new Exception("Gallery Image Upload Error (Slot $i): " . $uploadResult['message']);
                }
            }

            if ($newGalleryImageUploaded) {
                // New image uploaded
                if ($gallery_id > 0) {
                    // Update existing gallery entry
                    $sql = "UPDATE blog_gallery_images SET image_path = ? WHERE gallery_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("si", $new_path, $gallery_id);
                    $stmt->execute();
                    $stmt->close();
                    // Delete old file if it existed and is different
                    if (!empty($current_path_in_db) && $current_path_in_db !== $new_path) {
                        deleteImageFile('../../images/blog/gallery/' . $current_path_in_db);
                    }
                } else {
                    // Insert new gallery entry
                    $sql = "INSERT INTO blog_gallery_images (blog_id, image_path) VALUES (?, ?)"; // Add order column if needed
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("is", $blog_id, $new_path);
                    $stmt->execute();
                    $stmt->close();
                }
            } elseif (empty($existing_path) && $gallery_id > 0) {
                 // No new image, and existing path input is empty -> User removed the image
                 $sql = "DELETE FROM blog_gallery_images WHERE gallery_id = ?";
                 $stmt = $conn->prepare($sql);
                 $stmt->bind_param("i", $gallery_id);
                 $stmt->execute();
                 $stmt->close();
                 // Delete the file
                 if (!empty($current_path_in_db)) {
                     deleteImageFile('../../images/blog/gallery/' . $current_path_in_db);
                 }
            }
            // If no new image and existing_path is set, do nothing (keep existing)
        }


        // --- Commit Transaction ---
        $conn->commit();

        // --- Success Redirect ---
        header('Location: ../../pages/blogs.php?status=success&message=' . urlencode('Blog post updated successfully!'));
        exit();

    } catch (Exception $e) {
        // --- Rollback and Error Handling ---
        $conn->rollback();
        error_log("Blog Update Error: " . $e->getMessage() . " for blog_id: " . ($blog_id ?? 'unknown')); // Log the error
        // Redirect back to edit page with error message
        $redirect_url = '../../pages/edit_blog.php?id=' . ($blog_id ?? 0) . '&status=error&message=' . urlencode($e->getMessage());
        header('Location: ' . $redirect_url);
        exit();
    }

} else {
    // Invalid request method
    header('Location: ../../pages/blogs.php');
    exit();
}
?>