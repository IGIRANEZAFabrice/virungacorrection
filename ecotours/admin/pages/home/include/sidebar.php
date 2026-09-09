<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="logo">
      <img src="../../images/icon.png" alt="Virunga Logo" />
    </div>
    <h2>Virunga Admin</h2>
  </div>
  <nav class="sidebar-nav">
    <ul>
      <li class="nav-item">
        <a href="../../index.php">
          <i class="fa-solid fa-chart-line"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="nav-item dropdown active open">
        <a href="#" class="dropdown-toggle">
          <i class="fas fa-house"></i>
          <span>Home</span>
          <i class="fas fa-chevron-down"></i>
        </a>
        <ul class="submenu" style="display: block;">
          <li class="<?php echo ($currentPage === 'hero.php') ? 'active' : ''; ?>">
            <a href="./hero.php"><i class="fas fa-image"></i> <span>Hero</span></a>
          </li>
          <li class="<?php echo ($currentPage === 'destinations.php') ? 'active' : ''; ?>">
            <a href="./destinations.php"><i class="fas fa-map-marker-alt"></i> <span>Destinations</span></a>
          </li>
          <li class="<?php echo ($currentPage === 'attractions.php' || $currentPage === 'attraction_details.php') ? 'active' : ''; ?>">
            <a href="./attractions.php"><i class="fas fa-landmark"></i> <span>Attractions</span></a>
          </li>
          <li class="<?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>">
            <a href="./about.php"><i class="fas fa-info-circle"></i> <span>About Us</span></a>
          </li>
          <li class="<?php echo ($currentPage === 'month.php') ? 'active' : ''; ?>">
            <a href="./month.php"><i class="fas fa-calendar-alt"></i> <span>Month</span></a>
          </li>
          <li class="<?php echo ($currentPage === 'partners.php') ? 'active' : ''; ?>">
            <a href="./partners.php"><i class="fas fa-handshake"></i> <span>Partners</span></a>
          </li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="../tours.php"><i class="fas fa-compass"></i> <span>Tours</span></a>
      </li>
      <li class="nav-item">
        <a href="../about_page_manager.php"><i class="fas fa-users"></i> <span>About Us</span></a>
      </li>
      <li class="nav-item">
        <a href="../accommodation/index.php"><i class="fas fa-bed"></i> <span>Accommodation</span></a>
      </li>
      <li class="nav-item">
        <a href="../contact_messages.php"><i class="fas fa-envelope"></i> <span>Contact Messages</span></a>
      </li>
      <li class="nav-item">
        <a href="../itenary_messages.php"><i class="fas fa-calendar-check"></i> <span>Tour Bookings</span></a>
      </li>
      <li class="nav-item dropdown">
        <a href="#" class="dropdown-toggle">
          <i class="fas fa-newspaper"></i>
          <span>Blogs</span>
          <i class="fas fa-chevron-down"></i>
        </a>
        <ul class="submenu">
          <li><a href="../blogs.php"><i class="fas fa-book"></i> <span>All Blogs</span></a></li>
          <li><a href="../blog_comments.php"><i class="fas fa-comments"></i> <span>Blog Comments</span></a></li>
        </ul>
      </li>
      <li class="nav-item">
        <a href="../faqs.php"><i class="fas fa-circle-question"></i> <span>FAQs</span></a>
      </li>
      <li class="nav-item">
        <a href="../subscribers.php"><i class="fas fa-users"></i> <span>Subscribers</span></a>
      </li>
      <li class="nav-item">
        <a href="../buildmessage.php"><i class="fas fa-map-marked-alt"></i> <span>Custom Trips</span></a>
      </li>
      <li class="nav-item">
        <a href="../profile.php"><i class="fas fa-user-gear"></i> <span>Settings</span></a>
      </li>
      <li class="nav-item">
        <a href="../gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a>
      </li>
      <li class="nav-item">
        <a href="../styleguides.php"><i class="fas fa-map"></i> <span>Style Guides</span></a>
      </li>
    </ul>
  </nav>
  <div class="sidebar-footer">
    <a href="../../../index.php" target="_blank" class="view-site">
      <i class="fas fa-globe"></i> <span>View Website</span>
    </a>
    <a href="../logout.html" class="logout">
      <i class="fas fa-arrow-right-from-bracket"></i> <span>Logout</span>
    </a>
  </div>
</aside>