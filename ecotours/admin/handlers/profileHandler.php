<?php
session_start();
require_once '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = $_SESSION['admin_id'];
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $bio = trim($_POST['bio']);

    // Validate inputs
    $errors = [];
    if (empty($first_name)) $errors[] = 'First name is required';
    if (empty($last_name)) $errors[] = 'Last name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';

    if (!empty($errors)) {
        $_SESSION['profile_errors'] = $errors;
        header('Location: ../pages/profile.php');
        exit();
    }

    // Handle profile image upload
    $profile_image = '';
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = '../images/profile/';
        $file_name = basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . uniqid() . '_' . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is actual image
        $check = getimagesize($_FILES['profile_image']['tmp_name']);
        if ($check === false) {
            $_SESSION['profile_errors'] = ['File is not an image.'];
            header('Location: ../pages/profile.php');
            exit();
        }
        
        // Check file size (max 2MB)
        if ($_FILES['profile_image']['size'] > 2000000) {
            $_SESSION['profile_errors'] = ['Image size must be less than 2MB.'];
            header('Location: ../pages/profile.php');
            exit();
        }
        
        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            $_SESSION['profile_errors'] = ['Only JPG, JPEG, PNG & GIF files are allowed.'];
            header('Location: ../pages/profile.php');
            exit();
        }
        
        // Upload file
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $profile_image = $target_file;
            
            // Remove old profile image if exists
            $sql = "SELECT profile_image FROM admins WHERE admin_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if (!empty($row['profile_image']) && file_exists($row['profile_image'])) {
                unlink($row['profile_image']);
            }
        }
    }

    try {
        // Update admin profile
        $sql = "UPDATE admins SET ";
        $sql .= "first_name = ?, last_name = ?, email = ?, phone = ?";
        if (!empty($profile_image)) {
            $sql .= ", profile_image = ?";
        }
        $sql .= " WHERE admin_id = ?";
        
        $stmt = $conn->prepare($sql);
        
        if (!empty($profile_image)) {
            $stmt->bind_param('sssssi', $first_name, $last_name, $email, $phone, $profile_image, $admin_id);
        } else {
            $stmt->bind_param('ssssi', $first_name, $last_name, $email, $phone, $admin_id);
        }
        
        if ($stmt->execute()) {
            $_SESSION['profile_success'] = 'Profile updated successfully';
        } else {
            $_SESSION['profile_errors'] = ['Failed to update profile. Please try again.'];
        }
        
        header('Location: ../pages/profile.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['profile_errors'] = ['An error occurred: ' . $e->getMessage()];
        header('Location: ../pages/profile.php');
        exit();
    }
} else {
    $_SESSION['profile_errors'] = ['Invalid request method.'];
    header('Location:../pages/profile.php');
    exit();
}

header('Location: ../pages/profile.php');
exit();
?>