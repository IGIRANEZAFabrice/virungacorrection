<?php
require_once('../config/connection.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

$admin_id = $_SESSION['admin_id'] ?? 1; // Default to admin ID 1 for demo

// Fetch admin data
$sql = "SELECT first_name, last_name, email, phone, profile_image FROM admins WHERE admin_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($first_name, $last_name, $email, $phone, $profile_image);
if ($stmt->fetch()) {
  $admin = [
    'first_name' => $first_name,
    'last_name' => $last_name,
    'email' => $email,
    'phone' => $phone,
    'profile_image' => $profile_image
  ];
} else {
  $admin = null;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Virunga Ecotours</title>
    <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/profile.css" />
    <script src="../js/common.js" defer></script>
    <script src="../js/profile.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include "./includes/sidebar.php";?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include "./includes/header.php"; ?>

        <div class="content-panels">
          <!-- Profile content -->
          <div class="panel active" id="profile-panel">
            <div class="profile-container">
              <div class="profile-header">
                <div class="cover-photo">
                  <div class="profile-avatar">
                    <img src="<?php echo htmlspecialchars($admin['profile_image'] ?? 'costa-rica.jpg'); ?>" alt="Admin Profile" />
                  </div>
                </div>
                <div class="profile-info">
                  <h1><?php echo htmlspecialchars(($admin['first_name'] ?? '') . ' ' . ($admin['last_name'] ?? '')); ?></h1>
                  <p>Senior Administrator</p>
                </div>
              </div>

              <div class="profile-content">
                <!-- Display notifications -->
                <?php if (isset($_SESSION['profile_success'])): ?>
                  <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?php echo htmlspecialchars($_SESSION['profile_success']); ?>
                  </div>
                  <?php unset($_SESSION['profile_success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['profile_errors']) && is_array($_SESSION['profile_errors'])): ?>
                  <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul>
                      <?php foreach ($_SESSION['profile_errors'] as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                  <?php unset($_SESSION['profile_errors']); ?>
                <?php endif; ?>

                <div class="profile-nav">
                  <button class="active" data-tab="personal">
                    <i class="fas fa-user"></i> Personal Info
                  </button>
                  <button data-tab="security">
                    <i class="fas fa-shield-alt"></i> Security
                  </button>
                </div>

                <div class="profile-sections">
                  <section class="profile-section active" id="personal">
                    <form class="profile-form" method="POST" action="../handlers/profileHandler.php" enctype="multipart/form-data">
                      <div class="form-grid">
                        <div class="form-group image-upload-group">
                          <label><i class="fas fa-image"></i> Profile Image</label>
                          <div class="image-upload-container">
                            <div class="image-preview">
                              <img id="profileImagePreview" src="<?php echo htmlspecialchars($admin['profile_image'] ?? 'costa-rica.jpg'); ?>" alt="Profile Preview" />
                            </div>
                            <input type="file" id="profileImageInput" name="profile_image" accept="image/*" style="display: none;" />
                            <div class="upload-options">
                              <button type="button" class="upload-btn" onclick="document.getElementById('profileImageInput').click()">
                                <i class="fas fa-upload"></i> Upload
                              </button>
                              <span class="drag-text">or drag & drop</span>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label><i class="fas fa-user"></i> First Name</label>
                          <input type="text" name="first_name" value="<?php echo htmlspecialchars($admin['first_name'] ?? ''); ?>" required />
                        </div>
                        <div class="form-group">
                          <label><i class="fas fa-user"></i> Last Name</label>
                          <input type="text" name="last_name" value="<?php echo htmlspecialchars($admin['last_name'] ?? ''); ?>" required />
                        </div>
                        <div class="form-group">
                          <label><i class="fas fa-envelope"></i> Email</label>
                          <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email'] ?? ''); ?>" required />
                        </div>
                        <div class="form-group">
                          <label><i class="fas fa-phone"></i> Phone</label>
                          <input type="tel" name="phone" value="<?php echo htmlspecialchars($admin['phone'] ?? ''); ?>" />
                        </div>
                      </div>
                      <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Save Changes
                      </button>
                    </form>
                  </section>

                  <section class="profile-section" id="security">
                    <form class="profile-form" method="POST" action="../handlers/passwordHandler.php">
                      <div class="form-group">
                        <label><i class="fas fa-lock"></i> Current Password</label>
                        <input type="password" name="current_password" required />
                      </div>
                      <div class="form-group">
                        <label><i class="fas fa-key"></i> New Password</label>
                        <input type="password" name="new_password" required />
                      </div>
                      <div class="form-group">
                        <label><i class="fas fa-check"></i> Confirm Password</label>
                        <input type="password" name="confirm_password" required />
                      </div>
                      <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i> Update Password
                      </button>
                    </form>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
