<?php

// Check if connection exists
if (!isset($conn) || !($conn instanceof mysqli)) {
    require_once('../../config/connection.php');
}

// Fetch admin data if not already available
if (isset($_SESSION['admin_id'])) {
  $admin_id = $_SESSION['admin_id'];
  $sql = "SELECT first_name, last_name, profile_image FROM admins WHERE admin_id = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $admin_id);
  $stmt->execute();

  // Use bind_result instead of get_result for better compatibility
  $first_name = '';
  $last_name = '';
  $profile_image = '';
  $stmt->bind_result($first_name, $last_name, $profile_image);

  if ($stmt->fetch()) {
    $admin = array(
      'first_name' => $first_name,
      'last_name' => $last_name,
      'profile_image' => $profile_image
    );
  } else {
    $admin = null;
  }
  $stmt->close();
}
?>

<header class="top-header">
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
  </button>
  <div class="user-menu">
    <div class="user-profile">
      <img src="<?php 
        // Determine the correct path to the profile image
        if (isset($admin) && !empty($admin['profile_image'])) {
          // Extract just the filename from the path
          $img_path = basename($admin['profile_image']);
          echo "../../images/profile/" . $img_path;
        } else {
          echo "../../images/costa-rica.jpg";
        }
      ?>" alt="Admin" />
      <span><?php echo isset($admin) ? htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']) : 'Admin User'; ?></span>
      <i class="fas fa-chevron-down"></i>
      <!-- Add dropdown menu -->
      <div class="user-dropdown">
        <a href="../profile.php" class="dropdown-item">
          <i class="fas fa-user"></i>
          <span>My Profile</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="../logout.html" class="dropdown-item text-danger">
          <i class="fas fa-sign-out-alt"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </div>
</header>