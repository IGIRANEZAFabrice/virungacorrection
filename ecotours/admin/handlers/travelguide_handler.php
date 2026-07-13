<?php

require_once '../config/connection.php';

// Create PDO connection
try {
    $host = 'localhost';
    $dbname = 'dmxewbmy_ecodatabase';
    $username = 'dmxewbmy_homestay';
    $password = 'Igiraneza@11823';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle main guide information
    $title = $_POST['title'] ?? '';
    $country = $_POST['country'] ?? '';
    $intro = $_POST['intro'] ?? '';
    
    // Handle cover image
    $coverImage = '';
    if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
        $coverImage = uploadImage($_FILES['coverImage'], '../../uploads/guides/');
    }

    try {
        $pdo->beginTransaction();

        // Debug image uploads
        error_log("Processing image uploads: " . print_r($_FILES, true));

        // Insert main guide information
        $stmt = $pdo->prepare("INSERT INTO styleguide (title, country, intro, coverimg) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $country, $intro, $coverImage]);
        $guideId = $pdo->lastInsertId();

        // Initialize arrays with default empty arrays if not set
        $blockTypes = isset($_POST['block_type']) ? (array)$_POST['block_type'] : [];
        $subtitles = isset($_POST['subtitle']) ? (array)$_POST['subtitle'] : [];
        $contents = isset($_POST['content']) ? (array)$_POST['content'] : [];
        $displayOrders = isset($_POST['display_no']) ? (array)$_POST['display_no'] : [];

        // Only process blocks if there are any
        if (!empty($blockTypes)) {
            // Handle multiple blocks
            for ($i = 0; $i < count($blockTypes); $i++) {
                $blockType = $blockTypes[$i];
                $displayOrder = $displayOrders[$i] ?? ($i + 1);
                
                if ($blockType === 'text') {
                    // Handle text block
                    $subtitle = $subtitles[$i] ?? '';
                    $content = $contents[$i] ?? '';
                    
                    $stmt = $pdo->prepare("INSERT INTO styleguide_content (guide_id, subtitle, content, display_no) 
                                         VALUES (?, ?, ?, ?)");
                    $stmt->execute([$guideId, $subtitle, $content, $displayOrder]);
                } 
                elseif ($blockType === 'image' && isset($_FILES['block_image'])) {
                    // Verify image file exists for this block
                    if (isset($_FILES['block_image']['name'][$i]) && 
                        $_FILES['block_image']['error'][$i] === UPLOAD_ERR_OK) {
                        
                        $imageFile = array(
                            'name' => $_FILES['block_image']['name'][$i],
                            'type' => $_FILES['block_image']['type'][$i],
                            'tmp_name' => $_FILES['block_image']['tmp_name'][$i],
                            'error' => $_FILES['block_image']['error'][$i],
                            'size' => $_FILES['block_image']['size'][$i]
                        );

                        try {
                            $imagePath = uploadImage($imageFile, '../../uploads/guides/blocks/');
                            error_log("Image uploaded successfully: " . $imagePath);
                            
                            $stmt = $pdo->prepare("INSERT INTO styleguide_images (guide_id, imgpath, display_no) 
                                                 VALUES (?, ?, ?)");
                            $stmt->execute([$guideId, $imagePath, $displayOrder]);
                        } catch (Exception $e) {
                            error_log("Image upload failed: " . $e->getMessage());
                            throw $e;
                        }
                    } else {
                        error_log("No valid image found for block " . $i);
                    }
                }
            }
        }

        $pdo->commit();
        header("Location: ../pages/travelguide.php?success=1");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error in transaction: " . $e->getMessage());
        header("Location: ../pages/addtravelguide.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

function uploadImage($file, $targetDirectory) {
    if (!file_exists($targetDirectory)) {
        mkdir($targetDirectory, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($file['name']);
    $targetPath = $targetDirectory . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return str_replace('../../', '', $targetPath);
    }
    throw new Exception("Failed to upload image.");
}
?>
