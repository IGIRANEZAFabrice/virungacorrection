<?php
session_start();
require_once '../../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../../index.php');
    exit;
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Activity ID is required";
    header('Location: ../index.php');
    exit;
}

$activity_id = intval($_GET['id']);

// Get activity details first to get the image filename
$get_activity = "SELECT image FROM community_activities WHERE id = ?";
$stmt = mysqli_prepare($conn, $get_activity);
mysqli_stmt_bind_param($stmt, "i", $activity_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "Activity not found";
    header('Location: ../index.php');
    exit;
}

$activity = mysqli_fetch_assoc($result);
$image_filename = $activity['image'];

// Delete the activity from database
$delete_query = "DELETE FROM community_activities WHERE id = ?";
$stmt = mysqli_prepare($conn, $delete_query);
mysqli_stmt_bind_param($stmt, "i", $activity_id);

if (mysqli_stmt_execute($stmt)) {
    // Delete the image file if it exists
    if (!empty($image_filename)) {
        $image_path = "../../../uploads/activities/" . $image_filename;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    // Log admin action - only if admin_logs table exists
    $admin_id = $_SESSION['community_admin_id'];
    $action = "Deleted activity ID: $activity_id";
    
    $table_check = mysqli_query($conn, "SHOW TABLES LIKE 'admin_logs'");
    if (mysqli_num_rows($table_check) > 0) {
        $log_query = "INSERT INTO admin_logs (admin_id, action, page, timestamp) VALUES ('$admin_id', '$action', 'activities/delete-handler.php', NOW())";
        mysqli_query($conn, $log_query);
    }
    
    $_SESSION['success'] = "Activity deleted successfully";
} else {
    $_SESSION['error'] = "Failed to delete activity: " . mysqli_error($conn);
}

mysqli_stmt_close($stmt);
header('Location: ../index.php');
exit;
?>
