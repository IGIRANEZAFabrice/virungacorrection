<?php
require_once('../../config/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $website = filter_var($_POST['website'], FILTER_SANITIZE_URL);
        
        // Handle file upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $upload_dir = '../../images/home/partners/';
            if (!file_exists($upload_dir)) {
                mkdir( $upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_path = $upload_dir . $file_name;
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $target_path)) {
                $logo_path = '../../images/home/partners/' . $file_name;
                
                // Insert into database
                $query = "INSERT INTO home_partners (web_url, logo_url) VALUES (?, ?)";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "ss", $website, $logo_path);
                
                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../../pages/home/partners.php?status=success");
                    exit();
                } else {
                    throw new Exception("Failed to save partner data");
                }
            } else {
                throw new Exception("Failed to upload file");
            }
        } else {
            throw new Exception("No file uploaded or file upload error");
        }
    } catch (Exception $e) {
        header("Location: ../../pages/home/partners.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../../pages/home/partners.php");
    exit();
}
