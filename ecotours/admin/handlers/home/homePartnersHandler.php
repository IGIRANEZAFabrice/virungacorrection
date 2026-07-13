<?php
require_once('../../config/connection.php');

// Check if database connection exists
if (!isset($conn) || $conn->connect_error) {
    header("Location: ../../pages/home/partners.php?status=error&message=" . urlencode("Database connection failed"));
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get all partners from database to determine count
        $query = "SELECT * FROM home_partners ORDER BY id";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            throw new Exception("Failed to retrieve current partners data");
        }
        
        $partners = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $partners[] = $row;
        }
        
        $slideCount = count($partners);
        
        // Process each partner slide
        $partnersData = [];
        for ($i = 1; $i <= $slideCount; $i++) {
            $webUrl = isset($_POST["slide{$i}-title"]) ? trim($_POST["slide{$i}-title"]) : '';
            $partnerId = $partners[$i-1]['id']; // Get actual ID from database
            
            // Get existing logo URL
            $logoUrl = $partners[$i-1]['logo_url'];
            
            // Handle file upload if new image is provided
            if (!empty($_FILES["slide{$i}-img"]['name'])) {
                $file = $_FILES["slide{$i}-img"];
                
                // Validate file
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if (!in_array($file['type'], $allowedTypes)) {
                    throw new Exception("Invalid file type for slide {$i}. Only JPG, PNG, and GIF are allowed.");
                }
                
                if ($file['size'] > $maxSize) {
                    throw new Exception("File for slide {$i} exceeds maximum size of 2MB.");
                }
                
                // Generate unique filename
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'partner_' . uniqid() . '.' . $ext;
                $uploadDir = '../../images/home/partners/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        throw new Exception("Failed to create upload directory");
                    }
                }
                
                $uploadPath = $uploadDir . $filename;
                
                // Move uploaded file
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    throw new Exception("Failed to upload image for slide {$i}.");
                }
                
                // Delete old image file if it exists and is different
                if (!empty($logoUrl) && file_exists($logoUrl) && $logoUrl != $uploadPath) {
                    // Only try to delete if it's not a default image
                    if (strpos($logoUrl, 'default') === false) {
                        @unlink($logoUrl);
                    }
                }
                
                $logoUrl = $uploadDir . $filename;
            }
            
            // Add to partners data array
            $partnersData[] = [
                'id' => $partnerId,
                'web_url' => $webUrl,
                'logo_url' => $logoUrl
            ];
        }
        
        // Update database
        $conn->begin_transaction();
        
        foreach ($partnersData as $partner) {
            $query = "UPDATE home_partners SET web_url = ?, logo_url = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $partner['web_url'], $partner['logo_url'], $partner['id']);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update partner: " . $stmt->error);
            }
        }
        
        $conn->commit();
        
        // Redirect with success message
        header("Location: ../../pages/home/partners.php?status=success");
        exit();
        
    } catch (Exception $e) {
        // Rollback on error
        if (isset($conn) && $conn instanceof mysqli && method_exists($conn, 'rollback')) {
            $conn->rollback();
        }
        
        // Redirect with error message
        header("Location: ../../pages/home/partners.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}

// If not POST request, redirect back
header("Location: ../../pages/home/partners.php");
exit();
?>