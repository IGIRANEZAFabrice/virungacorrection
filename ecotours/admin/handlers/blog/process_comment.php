<?php
require_once '../../config/connection.php';

// Validate required fields
if (!isset($_POST['blog_id'], $_POST['name'], $_POST['email'], $_POST['content'])) { // Changed post_id to blog_id
    header("Location: ../../../pages/blog.php?error=missing_fields");
    exit();
}

// Sanitize input
$blog_id = filter_var($_POST['blog_id'], FILTER_SANITIZE_NUMBER_INT); // Changed post_id to blog_id
$name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../../../pages/blogopen.php?id=$blog_id&error=invalid_email"); // Changed post_id to blog_id
    exit();
}

// Validate content length
if (strlen($content) < 10 || strlen($content) > 1000) {
    header("Location: ../../../pages/blogopen.php?id=$blog_id&error=invalid_content_length"); // Changed post_id to blog_id
    exit();
}

// Check if post exists
$post_check = $conn->prepare("SELECT blog_id FROM blog_posts WHERE blog_id = ?"); // Changed post_id to blog_id
$post_check->bind_param("i", $blog_id); // Changed post_id to blog_id
$post_check->execute();
$post_check->store_result();

if ($post_check->num_rows === 0) {
    header("Location: ../../../pages/blog.php?error=invalid_post");
    exit();
}
$post_check->close(); // Close the statement

// Insert comment into database
$insert_query = "INSERT INTO blog_comments 
                (blog_id, commenter_name, commenter_email, comment_text, is_approved) 
                VALUES (?, ?, ?, ?, ?)"; // Updated column names
$stmt = $conn->prepare($insert_query);

// For now, set is_approved to false (moderation required)
$is_approved = 0;

$stmt->bind_param("isssi", $blog_id, $name, $email, $content, $is_approved); // Changed post_id to blog_id

if ($stmt->execute()) {
    // Success - redirect back to blog post with success message
    header("Location: ../../../pages/blogopen.php?id=$blog_id&success=comment_submitted"); // Changed post_id to blog_id
} else {
    // Error - redirect back with error message
    // Log the error for debugging: error_log("Comment insertion failed: " . $stmt->error);
    header("Location: ../../../pages/blogopen.php?id=$blog_id&error=comment_failed"); // Changed post_id to blog_id
}

$stmt->close();
$conn->close();
exit();