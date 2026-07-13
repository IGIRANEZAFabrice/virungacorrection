<?php
require_once('../../config/connection.php');

session_start();

// if (!isset($_SESSION['admin_id'])) {
//     header("Location: ../../pages/login.html");
//     exit();
// }

if (isset($_GET['id'])) {
    $partnerId = mysqli_real_escape_string($conn, $_GET['id']);
    
    // First get the logo URL to delete the file
    $query = "SELECT logo_url FROM home_partners WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $partnerId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        // Delete the image file
        $logoPath = $_SERVER['DOCUMENT_ROOT'] . str_replace('/ecotours', '', $row['logo_url']);
        if (file_exists($logoPath)) {
            unlink($logoPath);
        }
    }

    // Delete the partner from database
    $query = "DELETE FROM home_partners WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $partnerId);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: ../../pages/home/partners.php?status=success&message=Partner deleted successfully");
    } else {
        header("Location: ../../pages/home/partners.php?status=error&message=Failed to delete partner");
    }
} else {
    header("Location: ../../pages/home/partners.php?status=error&message=Invalid partner ID");
}

exit();
