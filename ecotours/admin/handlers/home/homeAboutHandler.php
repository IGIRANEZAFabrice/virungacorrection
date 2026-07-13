<?php
require_once('../../config/connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $title = $_POST['slide_title'] ?? '';
        $description = $_POST['slide_description'] ?? '';
        $youtubeUrl = $_POST['youtube_url'] ?? '';

        // Basic validation
        if (empty($title) || empty($description) || empty($youtubeUrl)) {
            throw new Exception("All fields are required");
        }

        // Validate YouTube URL format
        if (!filter_var($youtubeUrl, FILTER_VALIDATE_URL) || 
            !preg_match('/youtube\.com\/embed\/[a-zA-Z0-9_-]+/', $youtubeUrl)) {
            throw new Exception("Please provide a valid YouTube embed URL");
        }

        // Prepare SQL statement
        $sql = "UPDATE home_about SET 
                title = ?, 
                slide_description = ?, 
                youtube_url = ? 
                WHERE id = 1";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Database error: " . $conn->error);
        }

        // Bind parameters and execute
        $stmt->bind_param("sss", $title, $description, $youtubeUrl);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            throw new Exception("No changes were made to the about section");
        }

        // Success - redirect back with success message
        header("Location: ../../pages/home/about.php?status=success&message=Changes+saved+successfully!");
        exit();

    } catch (Exception $e) {
        // Error - redirect back with error message
        header("Location: ../../pages/home/about.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Not a POST request - redirect to about page
    header("Location: ../../pages/home/about.php");
    exit();
}
?>