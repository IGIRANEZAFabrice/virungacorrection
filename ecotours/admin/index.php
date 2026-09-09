<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: pages/login.html");
    exit();
}

// Include database connection
require_once './config/connection.php';
require_once './config/database.php';

// Fetch admin data
$admin_id = (int)$_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT first_name, last_name, email, profile_image, last_login FROM admins WHERE admin_id = ?");
$stmt->execute([$admin_id]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC) ?: [
    'first_name' => 'Admin',
    'last_name' => 'User',
    'email' => '',
    'profile_image' => '',
    'last_login' => date('Y-m-d H:i:s')
];

// Fetch dashboard stats safely with PDO
$stats = [
    'tours' => (int)$pdo->query("SELECT COUNT(*) FROM tours")->fetchColumn(),
    'custom_trips' => (int)$pdo->query("SELECT COUNT(*) FROM build_submissions")->fetchColumn(),
    'subscribers' => (int)$pdo->query("SELECT COUNT(*) FROM subscribers")->fetchColumn(),
    'blogs' => (int)$pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn(),
    'accommodations' => (int)$pdo->query("SELECT COUNT(*) FROM accommodations")->fetchColumn(),
    'contacts' => (int)$pdo->query("SELECT COUNT(*) FROM contact_submissions")->fetchColumn(),
    'bookings' => (int)$pdo->query("SELECT COUNT(*) FROM tour_bookings")->fetchColumn(),
    'destinations' => (int)$pdo->query("SELECT COUNT(*) FROM destinations")->fetchColumn()
];

// Fetch recent Custom Trip Submissions (Top 5)
$customTripsQuery = "SELECT id, names, email, phone, referral_source, travelers_info, trip_days, group_size, travel_date, budget_notes, created_at 
                     FROM build_submissions 
                     ORDER BY created_at DESC 
                     LIMIT 5";
$recentCustomTrips = $pdo->query($customTripsQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent Tour Bookings (Top 5)
$recentBookingsQuery = "SELECT b.booking_id, b.full_name, b.email, b.phone, b.travel_date, b.status, b.created_at, t.title as tour_title, t.cover_image_path
                        FROM tour_bookings b
                        LEFT JOIN tours t ON b.tour_id = t.tour_id
                        ORDER BY b.created_at DESC
                        LIMIT 5";
$recentBookings = $pdo->query($recentBookingsQuery)->fetchAll(PDO::FETCH_ASSOC);

// Fetch monthly activity data for Chart.js (Current Year)
$currentYear = (int)date('Y');

// 1. Custom trip requests by month
$monthlyTrips = array_fill(1, 12, 0);
$tripsChartQuery = "SELECT MONTH(created_at) as m, COUNT(*) as c 
                    FROM build_submissions 
                    WHERE YEAR(created_at) = $currentYear 
                    GROUP BY MONTH(created_at)";
$tripsChartStmt = $pdo->query($tripsChartQuery);
while ($row = $tripsChartStmt->fetch(PDO::FETCH_ASSOC)) {
    $monthlyTrips[(int)$row['m']] = (int)$row['c'];
}

// 2. Bookings by month
$monthlyBookings = array_fill(1, 12, 0);
$bookingsChartQuery = "SELECT MONTH(created_at) as m, COUNT(*) as c 
                       FROM tour_bookings 
                       WHERE YEAR(created_at) = $currentYear 
                       GROUP BY MONTH(created_at)";
$bookingsChartStmt = $pdo->query($bookingsChartQuery);
while ($row = $bookingsChartStmt->fetch(PDO::FETCH_ASSOC)) {
    $monthlyBookings[(int)$row['m']] = (int)$row['c'];
}

// Content distribution data
$contentDistribution = [
    'Tours' => $stats['tours'],
    'Stories' => $stats['blogs'],
    'Lodges' => $stats['accommodations'],
    'Inquiries' => $stats['custom_trips']
];

// Helper to get initials
function getInitials($name) {
    $words = explode(' ', trim($name ?: 'Guest Traveler'));
    $initials = '';
    foreach (array_slice($words, 0, 2) as $w) {
        $initials .= strtoupper($w[0] ?? '');
    }
    return $initials ?: 'TR';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - Virunga Ecotours</title>
    <link rel="shortcut icon" href="./images/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="./css/common.css" />
    <link rel="stylesheet" href="./css/dashboard.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="./js/common.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      
      <!-- Sleek Modern Sidebar -->
      <aside class="sidebar">
        <div class="sidebar-header">
          <div class="logo">
            <img src="./images/icon.png" alt="Logo" />
          </div>
          <h2>Virunga Admin</h2>
        </div>
        <nav class="sidebar-nav">
          <ul>
            <li class="nav-item active">
              <a href="index.php"><i class="fa-solid fa-chart-line"></i> <span>Dashboard</span></a>
            </li>
            <li class="nav-item dropdown">
              <a href="#" class="dropdown-toggle">
                <i class="fas fa-house"></i>
                <span>Home</span>
                <i class="fas fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                <li><a href="./pages/home/hero.php"><i class="fas fa-image"></i> Hero</a></li>
                <li><a href="./pages/home/destinations.php"><i class="fas fa-map-marker-alt"></i> Destinations</a></li>
                <li><a href="./pages/home/attractions.php"><i class="fas fa-landmark"></i> Attractions</a></li>
                <li><a href="./pages/home/about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
                <li><a href="./pages/home/month.php"><i class="fas fa-calendar-alt"></i> Month</a></li>
                <li><a href="./pages/home/partners.php"><i class="fas fa-handshake"></i> Partners</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="./pages/tours.php"><i class="fas fa-compass"></i> <span>Tours</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/about_page_manager.php"><i class="fas fa-users"></i> <span>About Us</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/accommodation/index.php"><i class="fas fa-bed"></i> <span>Accommodation</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/contact_messages.php"><i class="fas fa-envelope"></i> <span>Contact Messages</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/itenary_messages.php"><i class="fas fa-calendar-check"></i> <span>Tour Bookings</span></a>
            </li>
            <li class="nav-item dropdown">
              <a href="#" class="dropdown-toggle">
                <i class="fas fa-newspaper"></i>
                <span>Blogs</span>
                <i class="fas fa-chevron-down"></i>
              </a>
              <ul class="submenu">
                <li><a href="./pages/blogs.php"><i class="fas fa-book"></i> Blogs</a></li>
                <li><a href="./pages/blog_comments.php"><i class="fas fa-comments"></i> Blog Comments</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="./pages/faqs.php"><i class="fas fa-circle-question"></i> <span>FAQs</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/buildmessage.php"><i class="fas fa-map-marked-alt"></i> <span>Custom Trip</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/subscribers.php"><i class="fas fa-users"></i> <span>Subscribers</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/profile.php"><i class="fas fa-user-gear"></i> <span>Settings</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/gallery.php"><i class="fas fa-images"></i> <span>Gallery</span></a>
            </li>
            <li class="nav-item">
              <a href="./pages/styleguides.php"><i class="fas fa-palette"></i> <span>Style Guides</span></a>
            </li>
          </ul>
        </nav>
        <div class="sidebar-footer">
          <a href="../index.php" class="view-site" target="_blank">
            <i class="fas fa-globe"></i> <span>View Website</span>
          </a>
          <a href="./pages/logout.html" class="logout">
            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
          </a>
        </div>
      </aside>

      <!-- Main Content Area -->
      <main class="main-content">
        
        <!-- Clean Slim Top Header -->
        <header class="top-header">
          <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
              <i class="fas fa-bars"></i>
            </button>
            <div class="header-search">
              <i class="fas fa-search"></i>
              <input type="text" placeholder="Search..." id="headerSearchInput" />
            </div>
          </div>

          <div class="header-right">
            <a href="../index.php" class="header-icon-link" target="_blank" title="View Website">
              <i class="fas fa-external-link-alt"></i>
            </a>
            <a href="./pages/buildmessage.php" class="header-icon-link notification-link" title="Inquiries">
              <i class="fas fa-bell"></i>
              <?php if ($stats['custom_trips'] > 0): ?>
                <span class="notification-badge"><?php echo $stats['custom_trips']; ?></span>
              <?php endif; ?>
            </a>

            <div class="user-profile">
              <img src="<?php 
                if (!empty($admin['profile_image'])) {
                  echo "./images/profile/" . basename($admin['profile_image']);
                } else {
                  echo "./images/costa-rica.jpg";
                }
              ?>" alt="Admin" class="avatar-img" />
              <div class="user-meta">
                <span class="user-name"><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></span>
                <span class="user-role">Administrator</span>
              </div>
              <i class="fas fa-chevron-down dropdown-arrow"></i>

              <!-- User Dropdown Menu -->
              <div class="user-dropdown">
                <div class="dropdown-header">
                  <strong><?php echo htmlspecialchars($admin['first_name'] . ' ' . $admin['last_name']); ?></strong>
                  <span class="email"><?php echo htmlspecialchars($admin['email'] ?: 'admin@virungaecotours.com'); ?></span>
                </div>
                <div class="dropdown-divider"></div>
                <a href="./pages/profile.php" class="dropdown-item">
                  <i class="fas fa-user"></i> My Profile & Settings
                </a>
                <a href="./pages/tours.php" class="dropdown-item">
                  <i class="fas fa-compass"></i> Manage Tours
                </a>
                <a href="../index.php" class="dropdown-item" target="_blank">
                  <i class="fas fa-globe"></i> Visit Website
                </a>
                <div class="dropdown-divider"></div>
                <a href="./pages/logout.html" class="dropdown-item text-danger">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </a>
              </div>
            </div>
          </div>
        </header>

        <!-- Clean Dashboard Body Container -->
        <div class="tabler-dashboard-container">
          
          <!-- Page Header (Clean, concise Tabler style) -->
          <div class="page-header-row">
            <div class="page-title-wrap">
              <h2 class="page-title">Dashboard</h2>
              <span class="page-date"><i class="fas fa-calendar-alt"></i> <?php echo date('F d, Y'); ?></span>
            </div>
            <div class="page-actions-wrap">
              <a href="./pages/tours.php" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New Tour
              </a>
              <a href="./pages/buildmessage.php" class="btn btn-sm btn-outline">
                <i class="fas fa-map-marked-alt"></i> Custom Trips
              </a>
            </div>
          </div>

          <!-- 6 Sleek Metric Cards Row (Tabler Minimalist Style) -->
          <div class="tabler-metric-cards-row">
            
            <a href="./pages/tours.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['tours']; ?></span>
                <span class="metric-badge badge-green"><i class="fas fa-arrow-up"></i> Live</span>
              </div>
              <div class="metric-label">Total Tours</div>
            </a>

            <a href="./pages/buildmessage.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['custom_trips']; ?></span>
                <span class="metric-badge badge-amber"><i class="fas fa-bolt"></i> Inquiries</span>
              </div>
              <div class="metric-label">Custom Requests</div>
            </a>

            <a href="./pages/subscribers.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['subscribers']; ?></span>
                <span class="metric-badge badge-blue"><i class="fas fa-users"></i> Audience</span>
              </div>
              <div class="metric-label">Subscribers</div>
            </a>

            <a href="./pages/blogs.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['blogs']; ?></span>
                <span class="metric-badge badge-indigo">Stories</span>
              </div>
              <div class="metric-label">Published Blogs</div>
            </a>

            <a href="./pages/accommodation/index.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['accommodations']; ?></span>
                <span class="metric-badge badge-gray">Lodges</span>
              </div>
              <div class="metric-label">Accommodations</div>
            </a>

            <a href="./pages/contact_messages.php" class="metric-card">
              <div class="metric-top">
                <span class="metric-num"><?php echo $stats['contacts']; ?></span>
                <span class="metric-badge badge-gray">Messages</span>
              </div>
              <div class="metric-label">Contact Inquiries</div>
            </a>

          </div>

          <!-- Charts Row (Main Spline Activity + Distribution Doughnut) -->
          <div class="tabler-charts-grid">
            
            <div class="chart-card main-chart-box">
              <div class="chart-card-header">
                <h3 class="chart-title">Development & Booking Activity (<?php echo $currentYear; ?>)</h3>
                <div class="chart-legend-dots">
                  <span class="dot-item"><span class="dot dot-teal"></span> Inquiries</span>
                  <span class="dot-item"><span class="dot dot-blue"></span> Bookings</span>
                </div>
              </div>
              <div class="chart-canvas-wrap">
                <canvas id="tablerActivityChart"></canvas>
              </div>
            </div>

            <div class="chart-card side-chart-box">
              <div class="chart-card-header">
                <h3 class="chart-title">Content Distribution</h3>
              </div>
              <div class="doughnut-canvas-wrap">
                <canvas id="tablerDistributionChart"></canvas>
              </div>
            </div>

          </div>

          <!-- Bottom Data Tables Row -->
          <div class="tabler-tables-grid">
            
            <!-- Recent Inquiries -->
            <div class="data-card">
              <div class="data-card-header">
                <h3 class="data-card-title">Recent Inquiries (Custom Trips)</h3>
                <a href="./pages/buildmessage.php" class="card-link">View All</a>
              </div>
              
              <div class="table-responsive">
                <table class="tabler-table">
                  <thead>
                    <tr>
                      <th>Traveler</th>
                      <th>Duration</th>
                      <th>Party</th>
                      <th>Date</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($recentCustomTrips)): ?>
                      <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No custom trip inquiries logged yet.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($recentCustomTrips as $trip): 
                        $initials = getInitials($trip['names']);
                        $jsonData = htmlspecialchars(json_encode($trip), ENT_QUOTES, 'UTF-8');
                      ?>
                        <tr class="clickable-row" onclick="openTripModal(this)" data-trip="<?php echo $jsonData; ?>">
                          <td>
                            <div class="user-cell">
                              <span class="avatar-initials"><?php echo $initials; ?></span>
                              <div class="user-text">
                                <strong><?php echo htmlspecialchars($trip['names']); ?></strong>
                                <span class="sub-text"><?php echo htmlspecialchars($trip['email']); ?></span>
                              </div>
                            </div>
                          </td>
                          <td><?php echo htmlspecialchars($trip['trip_days'] ?: '1'); ?> Days</td>
                          <td><?php echo htmlspecialchars($trip['group_size'] ?: '1'); ?> Pax</td>
                          <td><?php echo date('M d, Y', strtotime($trip['created_at'])); ?></td>
                          <td style="text-align: right;">
                            <button type="button" class="btn-table-icon" title="View details">
                              <i class="fas fa-eye"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Recent Bookings / Platform Status -->
            <div class="data-card">
              <div class="data-card-header">
                <h3 class="data-card-title">Recent Tour Bookings</h3>
                <a href="./pages/itenary_messages.php" class="card-link">View All</a>
              </div>

              <div class="table-responsive">
                <table class="tabler-table">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Customer</th>
                      <th>Tour Package</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (empty($recentBookings)): ?>
                      <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No recent direct bookings.</td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($recentBookings as $b): ?>
                        <tr>
                          <td><span class="code-id">#BK-<?php echo str_pad($b['booking_id'], 4, '0', STR_PAD_LEFT); ?></span></td>
                          <td>
                            <strong><?php echo htmlspecialchars($b['full_name']); ?></strong>
                          </td>
                          <td><?php echo htmlspecialchars($b['tour_title'] ?: 'Safari Package'); ?></td>
                          <td>
                            <span class="status-badge status-<?php echo strtolower($b['status']); ?>">
                              <?php echo ucfirst($b['status']); ?>
                            </span>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>

          </div>

        </div>
      </main>

    </div>

    <!-- ============================================================ -->
    <!-- MODAL: QUICK VIEW CUSTOM TRIP INQUIRY                        -->
    <!-- ============================================================ -->
    <div class="modal-backdrop" id="tripDetailModal" aria-hidden="true">
      <div class="modal-dialog modal-md" role="dialog" aria-modal="true" aria-labelledby="tripModalTitle">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="tripModalTitle">Custom Trip Inquiry Details</h4>
            <button type="button" class="modal-close-btn" onclick="closeTripModal()" aria-label="Close">
              <i class="fas fa-times"></i>
            </button>
          </div>

          <div class="modal-body-scroll">
            <div class="trip-modal-details">
              
              <div class="trip-info-grid">
                <div class="info-cell">
                  <label>Full Name</label>
                  <strong id="modalTripName">-</strong>
                </div>
                <div class="info-cell">
                  <label>Email Address</label>
                  <span id="modalTripEmail">-</span>
                </div>
                <div class="info-cell">
                  <label>Phone Number</label>
                  <span id="modalTripPhone">-</span>
                </div>
                <div class="info-cell">
                  <label>Travel Date</label>
                  <strong id="modalTripDate">-</strong>
                </div>
                <div class="info-cell">
                  <label>Trip Duration</label>
                  <strong id="modalTripDays">-</strong>
                </div>
                <div class="info-cell">
                  <label>Group Size</label>
                  <strong id="modalTripGroup">-</strong>
                </div>
              </div>

              <div class="trip-text-block">
                <label>Traveler Information & Preferences</label>
                <div class="text-box" id="modalTripTravelers">None specified.</div>
              </div>

              <div class="trip-text-block">
                <label>Budget Notes & Requests</label>
                <div class="text-box" id="modalTripBudget">None specified.</div>
              </div>

            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-outline" onclick="closeTripModal()">Close</button>
            <a href="./pages/buildmessage.php" class="btn btn-sm btn-primary">
              <i class="fas fa-envelope-open"></i> Go to All Custom Messages
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Dashboard Initialization Scripts -->
    <script>
      // Quick Trip Modal Handlers
      function openTripModal(element) {
        try {
          const trip = JSON.parse(element.getAttribute('data-trip'));
          document.getElementById('modalTripName').textContent = trip.names || 'N/A';
          document.getElementById('modalTripEmail').textContent = trip.email || 'N/A';
          document.getElementById('modalTripPhone').textContent = trip.phone || 'N/A';
          document.getElementById('modalTripDate').textContent = trip.travel_date ? new Date(trip.travel_date).toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' }) : 'Flexible';
          document.getElementById('modalTripDays').textContent = (trip.trip_days || '1') + ' Days';
          document.getElementById('modalTripGroup').textContent = (trip.group_size || '1') + ' People';
          document.getElementById('modalTripTravelers').textContent = trip.travelers_info || 'No extra traveler info provided.';
          document.getElementById('modalTripBudget').textContent = trip.budget_notes || 'No budget notes provided.';

          const modal = document.getElementById('tripDetailModal');
          modal.style.display = 'flex';
          setTimeout(() => {
            modal.classList.add('show');
            modal.setAttribute('aria-hidden', 'false');
          }, 10);
        } catch(e) {
          console.error(e);
        }
      }

      function closeTripModal() {
        const modal = document.getElementById('tripDetailModal');
        if (!modal) return;
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        setTimeout(() => modal.style.display = 'none', 200);
      }

      // Close modal on Escape or Backdrop click
      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTripModal();
      });
      document.getElementById('tripDetailModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeTripModal();
      });

      // Render Charts
      document.addEventListener('DOMContentLoaded', function() {
        const primaryColor = '#206bc4';
        const tealColor = '#0ca678';
        const amberColor = '#f59f00';
        const indigoColor = '#4263eb';

        // 1. Activity Chart (Clean Line/Spline Chart)
        const ctxActivity = document.getElementById('tablerActivityChart')?.getContext('2d');
        if (ctxActivity) {
          const monthlyTripsData = <?php echo json_encode(array_values($monthlyTrips)); ?>;
          const monthlyBookingsData = <?php echo json_encode(array_values($monthlyBookings)); ?>;

          new Chart(ctxActivity, {
            type: 'line',
            data: {
              labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
              datasets: [
                {
                  label: 'Custom Inquiries',
                  data: monthlyTripsData,
                  borderColor: tealColor,
                  backgroundColor: 'rgba(12, 166, 120, 0.04)',
                  fill: true,
                  tension: 0.38,
                  borderWidth: 2,
                  pointRadius: 3,
                  pointHoverRadius: 5,
                  pointBackgroundColor: tealColor
                },
                {
                  label: 'Bookings',
                  data: monthlyBookingsData,
                  borderColor: primaryColor,
                  backgroundColor: 'rgba(32, 107, 196, 0.04)',
                  fill: true,
                  tension: 0.38,
                  borderWidth: 2,
                  pointRadius: 3,
                  pointHoverRadius: 5,
                  pointBackgroundColor: primaryColor
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: {
                  backgroundColor: '#1e293b',
                  padding: 10,
                  cornerRadius: 6
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  grid: { color: '#f1f3f6' },
                  ticks: { stepSize: 1, color: '#94a3b8', font: { size: 11 } }
                },
                x: {
                  grid: { display: false },
                  ticks: { color: '#94a3b8', font: { size: 11 } }
                }
              }
            }
          });
        }

        // 2. Distribution Doughnut Chart
        const ctxDoughnut = document.getElementById('tablerDistributionChart')?.getContext('2d');
        if (ctxDoughnut) {
          const distData = <?php echo json_encode(array_values($contentDistribution)); ?>;
          const distLabels = <?php echo json_encode(array_keys($contentDistribution)); ?>;

          new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
              labels: distLabels,
              datasets: [{
                data: distData,
                backgroundColor: [primaryColor, tealColor, amberColor, indigoColor],
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 4
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    usePointStyle: true,
                    padding: 14,
                    font: { size: 11 },
                    color: '#64748b'
                  }
                }
              },
              cutout: '72%'
            }
          });
        }
      });
    </script>
  </body>
</html>
