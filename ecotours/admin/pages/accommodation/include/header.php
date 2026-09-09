<?php
if (!isset($conn) || !($conn instanceof mysqli)) {
    require_once('../../config/connection.php');
}

// Fetch admin data
$admin_first_name = 'Admin';
$admin_last_name = 'User';
$admin_profile_image = '';

if (isset($_SESSION['admin_id'])) {
    $admin_id = (int)$_SESSION['admin_id'];
    $sql = "SELECT first_name, last_name, profile_image FROM admins WHERE admin_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $admin_id);
        $stmt->execute();
        $stmt->bind_result($f_name, $l_name, $p_img);
        if ($stmt->fetch()) {
            $admin_first_name = $f_name;
            $admin_last_name = $l_name;
            $admin_profile_image = $p_img;
        }
        $stmt->close();
    }
}

// Profile image resolution
$display_avatar = "../../images/costa-rica.jpg";
if (!empty($admin_profile_image)) {
    $display_avatar = "../../images/profile/" . basename($admin_profile_image);
}
?>

<header class="top-header">
  <div class="header-left">
    <button class="sidebar-toggle" id="sidebarToggle" type="button" aria-label="Toggle Sidebar">
      <i class="fas fa-bars"></i>
    </button>
    <div class="header-search">
      <i class="fas fa-search"></i>
      <input type="text" placeholder="Search lodges, accommodations..." />
    </div>
  </div>

  <div class="header-right">
    <a href="../../../index.php" target="_blank" class="header-icon-link" title="View Live Website">
      <i class="fas fa-external-link-alt"></i>
    </a>

    <div class="user-profile" id="userProfileToggle">
      <img src="<?php echo htmlspecialchars($display_avatar); ?>" alt="Admin Profile" />
      <span class="user-name"><?php echo htmlspecialchars($admin_first_name . ' ' . $admin_last_name); ?></span>
      <i class="fas fa-chevron-down"></i>

      <div class="user-dropdown">
        <a href="../profile.php" class="dropdown-item">
          <i class="fas fa-user"></i>
          <span>My Profile</span>
        </a>
        <a href="../profile.php#security" class="dropdown-item">
          <i class="fas fa-shield-alt"></i>
          <span>Security</span>
        </a>
        <div class="dropdown-divider"></div>
        <a href="../logout.html" class="dropdown-item text-danger">
          <i class="fas fa-arrow-right-from-bracket"></i>
          <span>Logout</span>
        </a>
      </div>
    </div>
  </div>
</header>
