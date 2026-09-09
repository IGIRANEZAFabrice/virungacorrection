<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config/db_connect.php');

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

// Handle status updates and deletions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $booking_id = (int)$_POST['booking_id'];
        $status = $_POST['status'] ?? 'pending';
        $notes = $_POST['admin_notes'] ?? '';
        
        $update_query = "UPDATE tour_bookings 
                        SET status = :status, 
                            admin_notes = :notes,
                            updated_at = NOW()
                        WHERE booking_id = :booking_id";
        
        $stmt = $pdo->prepare($update_query);
        $stmt->execute([
            ':status' => $status,
            ':notes' => $notes,
            ':booking_id' => $booking_id
        ]);
        
        header('Location: itenary_messages.php?status=success&message=Booking+status+updated');
        exit();
    } elseif (isset($_POST['delete_booking'])) {
        $booking_id = (int)$_POST['booking_id'];
        
        $delete_query = "DELETE FROM tour_bookings WHERE booking_id = :booking_id";
        $stmt = $pdo->prepare($delete_query);
        $stmt->execute([':booking_id' => $booking_id]);
        
        header('Location: itenary_messages.php?status=success&message=Booking+deleted+successfully');
        exit();
    }
}

// Filter parameters
$status_filter = trim($_GET['status_filter'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = "SELECT b.*, t.title as tour_title, t.cover_image_path 
        FROM tour_bookings b
        LEFT JOIN tours t ON b.tour_id = t.tour_id";

$conditions = [];
$params = [];

if (!empty($status_filter)) {
    $conditions[] = "b.status = :status";
    $params[':status'] = $status_filter;
}

if (!empty($search)) {
    $conditions[] = "(b.full_name LIKE :s OR b.email LIKE :s OR b.phone LIKE :s OR t.title LIKE :s)";
    $params[':s'] = "%$search%";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY b.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Counts for KPI
$total_count = (int)$pdo->query("SELECT COUNT(*) FROM tour_bookings")->fetchColumn();
$pending_count = (int)$pdo->query("SELECT COUNT(*) FROM tour_bookings WHERE status = 'pending'")->fetchColumn();
$confirmed_count = (int)$pdo->query("SELECT COUNT(*) FROM tour_bookings WHERE status = 'confirmed'")->fetchColumn();
$cancelled_count = (int)$pdo->query("SELECT COUNT(*) FROM tour_bookings WHERE status = 'cancelled'")->fetchColumn();

// Helper initials
function getTravelerInitials($name) {
    $parts = explode(' ', trim($name ?: 'Traveler'));
    $in = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $in .= strtoupper($p[0] ?? '');
    }
    return $in ?: 'TR';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tour Bookings & Inquiries - Virunga Admin</title>
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/itenaryMessage.css" />
    <script src="../js/common.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <!-- Include sidebar template -->
        <?php include_once './includes/sidebar.php'; ?>

        <main class="main-content">
            <!-- Top Header -->
            <?php include_once './includes/header.php'; ?>

            <div class="bookings-management-container">
                
                <!-- Page Header Row -->
                <div class="page-header-row">
                    <div class="page-title-wrap">
                        <div class="breadcrumb-trail">
                            <a href="../index.php">Dashboard</a>
                            <span>/</span>
                            <span>Tour Bookings & Inquiries</span>
                        </div>
                        <h1 class="page-title">
                            <i class="fas fa-calendar-check" style="color: #206bc4;"></i>
                            Tour Booking Inquiries
                        </h1>
                        <p class="page-subtitle">
                            Manage incoming traveler requests, booking confirmations, custom trip dates, and client communication notes.
                        </p>
                    </div>

                    <div class="page-actions-wrap">
                        <a href="../../index.php" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Live Site
                        </a>
                    </div>
                </div>

                <!-- Status Alert -->
                <?php if (isset($_GET['status'])): ?>
                    <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                        <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                        <span><?php echo htmlspecialchars($_GET['message'] ?? 'Operation completed successfully'); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- KPI Metric Row -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $total_count; ?></span>
                            <span class="kpi-badge badge-blue"><i class="fas fa-inbox"></i> Total</span>
                        </div>
                        <span class="kpi-title">Total Inquiries Received</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $pending_count; ?></span>
                            <span class="kpi-badge badge-amber"><i class="fas fa-hourglass-half"></i> Action Needed</span>
                        </div>
                        <span class="kpi-title">Pending Inquiries</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $confirmed_count; ?></span>
                            <span class="kpi-badge badge-green"><i class="fas fa-check-circle"></i> Approved</span>
                        </div>
                        <span class="kpi-title">Confirmed Bookings</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $cancelled_count; ?></span>
                            <span class="kpi-badge badge-rose"><i class="fas fa-ban"></i> Cancelled</span>
                        </div>
                        <span class="kpi-title">Cancelled / Archived</span>
                    </div>
                </div>

                <!-- Filter & Search Toolbar -->
                <div class="toolbar-card">
                    <div class="filter-pills-wrap">
                        <a href="itenary_messages.php" class="filter-pill <?php echo empty($status_filter) ? 'active' : ''; ?>">
                            All <span class="count"><?php echo $total_count; ?></span>
                        </a>
                        <a href="itenary_messages.php?status_filter=pending" class="filter-pill <?php echo ($status_filter === 'pending') ? 'active' : ''; ?>">
                            <i class="fas fa-clock" style="font-size: 11px;"></i> Pending <span class="count"><?php echo $pending_count; ?></span>
                        </a>
                        <a href="itenary_messages.php?status_filter=confirmed" class="filter-pill <?php echo ($status_filter === 'confirmed') ? 'active' : ''; ?>">
                            <i class="fas fa-check" style="font-size: 11px;"></i> Confirmed <span class="count"><?php echo $confirmed_count; ?></span>
                        </a>
                        <a href="itenary_messages.php?status_filter=cancelled" class="filter-pill <?php echo ($status_filter === 'cancelled') ? 'active' : ''; ?>">
                            <i class="fas fa-times" style="font-size: 11px;"></i> Cancelled <span class="count"><?php echo $cancelled_count; ?></span>
                        </a>
                    </div>

                    <form method="GET" action="itenary_messages.php" class="search-wrap">
                        <?php if (!empty($status_filter)): ?>
                            <input type="hidden" name="status_filter" value="<?php echo htmlspecialchars($status_filter); ?>">
                        <?php endif; ?>
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Search traveler, email, tour..." value="<?php echo htmlspecialchars($search); ?>">
                    </form>
                </div>

                <!-- Data Table Card -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title"><i class="fas fa-list-ul"></i> Booking Inquiries</h3>
                            <p class="card-subtitle">Showing <?php echo count($bookings); ?> bookings matching criteria</p>
                        </div>
                    </div>

                    <div class="card-body" style="padding: 0;">
                        <div class="table-responsive">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>Traveler</th>
                                        <th>Requested Tour Package</th>
                                        <th>Target Travel Date</th>
                                        <th>Status</th>
                                        <th>Admin Notes</th>
                                        <th>Received Date</th>
                                        <th style="text-align: right;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($bookings)): ?>
                                        <?php foreach ($bookings as $b): 
                                            $initials = getTravelerInitials($b['full_name']);
                                            $stClass = strtolower($b['status'] ?: 'pending');
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="traveler-info-wrap">
                                                    <div class="avatar-initials"><?php echo htmlspecialchars($initials); ?></div>
                                                    <div class="traveler-meta">
                                                        <span class="traveler-name"><?php echo htmlspecialchars($b['full_name']); ?></span>
                                                        <div class="traveler-contact">
                                                            <a href="mailto:<?php echo htmlspecialchars($b['email']); ?>" title="Send Email">
                                                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($b['email']); ?>
                                                            </a>
                                                            <span>•</span>
                                                            <a href="tel:<?php echo htmlspecialchars($b['phone']); ?>" title="Call Traveler">
                                                                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($b['phone']); ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span style="font-weight: 600; color: #0f172a;">
                                                    <?php echo htmlspecialchars($b['tour_title'] ?: 'Custom Journey Request'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span style="color: #334155; font-size: 12.5px; font-weight: 500;">
                                                    <i class="far fa-calendar-alt" style="color: #206bc4; margin-right: 4px;"></i>
                                                    <?php echo !empty($b['travel_date']) ? date('M d, Y', strtotime($b['travel_date'])) : 'Flexible / TBD'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-pill <?php echo $stClass; ?>">
                                                    <?php if ($stClass === 'confirmed'): ?>
                                                        <i class="fas fa-check" style="font-size: 9px;"></i>
                                                    <?php elseif ($stClass === 'pending'): ?>
                                                        <i class="fas fa-clock" style="font-size: 9px;"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-times" style="font-size: 9px;"></i>
                                                    <?php endif; ?>
                                                    <?php echo ucfirst($stClass); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span style="color: #64748b; font-size: 12px; max-width: 180px; display: inline-block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    <?php echo htmlspecialchars($b['admin_notes'] ?: 'No notes'); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span style="font-size: 12px; color: #64748b;">
                                                    <?php echo date('M d, Y g:i A', strtotime($b['created_at'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="table-actions" style="justify-content: flex-end;">
                                                    <button type="button" class="btn-icon primary" title="View & Edit Booking Details" onclick="openBookingModal(<?php echo htmlspecialchars(json_encode($b)); ?>)">
                                                        <i class="fas fa-sliders"></i>
                                                    </button>
                                                    <form method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this booking inquiry?');">
                                                        <input type="hidden" name="booking_id" value="<?php echo $b['booking_id']; ?>">
                                                        <input type="hidden" name="delete_booking" value="1">
                                                        <button type="submit" class="btn-icon danger" title="Delete Booking">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                                                <i class="fas fa-inbox" style="font-size: 36px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                                <p style="margin: 0;">No booking inquiries match your selected filter.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <span style="font-size: 12px; color: #64748b;">
                            <i class="fas fa-shield-alt"></i> Managing bookings updates client reservation status and internal operations records in real time.
                        </span>
                        <span style="font-size: 12px; font-weight: 600; color: #334155;">
                            Total: <?php echo count($bookings); ?> bookings listed
                        </span>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <!-- Modern Manage Booking Modal -->
    <div id="bookingManageModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3><i class="fas fa-clipboard-check" style="color: #206bc4; margin-right: 6px;"></i> Manage Booking Inquiry</h3>
                <button type="button" class="modal-close" onclick="closeBookingModal()">&times;</button>
            </div>
            <form method="POST" action="itenary_messages.php">
                <input type="hidden" id="modalBookingId" name="booking_id" />
                <input type="hidden" name="update_status" value="1" />

                <div class="modal-body">
                    <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 1rem;">
                        <h4 id="modalTravelerName" style="margin: 0 0 4px; font-size: 14px; font-weight: 700; color: #0f172a;"></h4>
                        <p id="modalTourTitle" style="margin: 0 0 8px; font-size: 12.5px; color: #206bc4; font-weight: 600;"></p>
                        <div style="display: flex; gap: 12px; font-size: 12px; color: #64748b; flex-wrap: wrap;">
                            <span id="modalTravelerEmail"><i class="fas fa-envelope"></i> </span>
                            <span id="modalTravelerPhone"><i class="fas fa-phone"></i> </span>
                            <span id="modalTravelDate"><i class="fas fa-calendar"></i> </span>
                        </div>
                    </div>

                    <div id="modalMessageContainer" style="margin-bottom: 1rem; display: none;">
                        <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px; display: block;">Traveler's Message / Notes:</label>
                        <div id="modalMessageText" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px; font-size: 12.5px; color: #334155; line-height: 1.5;"></div>
                    </div>

                    <div class="form-group">
                        <label for="modalStatusSelect">Booking Status</label>
                        <select id="modalStatusSelect" name="status" required>
                            <option value="pending">Pending (Reviewing / Awaiting client payment)</option>
                            <option value="confirmed">Confirmed (Secured & Itinerary locked)</option>
                            <option value="cancelled">Cancelled (Declined / Refunded)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="modalAdminNotes">Internal Admin & Operations Notes</label>
                        <textarea id="modalAdminNotes" name="admin_notes" rows="3" placeholder="Add private notes on permits, hotel vouchers, payments..."></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeBookingModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Save Booking Status
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBookingModal(data) {
            document.getElementById('modalBookingId').value = data.booking_id;
            document.getElementById('modalTravelerName').textContent = data.full_name;
            document.getElementById('modalTourTitle').textContent = data.tour_title || 'Custom Safari Journey';
            document.getElementById('modalTravelerEmail').innerHTML = `<i class="fas fa-envelope"></i> ${data.email}`;
            document.getElementById('modalTravelerPhone').innerHTML = `<i class="fas fa-phone"></i> ${data.phone}`;
            document.getElementById('modalTravelDate').innerHTML = `<i class="fas fa-calendar"></i> Travel: ${data.travel_date || 'Flexible'}`;
            document.getElementById('modalStatusSelect').value = data.status || 'pending';
            document.getElementById('modalAdminNotes').value = data.admin_notes || '';

            const msgContainer = document.getElementById('modalMessageContainer');
            const msgText = document.getElementById('modalMessageText');
            if (data.message && data.message.trim() !== '') {
                msgContainer.style.display = 'block';
                msgText.textContent = data.message;
            } else {
                msgContainer.style.display = 'none';
            }

            const modal = document.getElementById('bookingManageModal');
            modal.classList.add('show');
        }

        function closeBookingModal() {
            document.getElementById('bookingManageModal').classList.remove('show');
        }

        // Close when clicking outside
        document.getElementById('bookingManageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });
    </script>
</body>
</html>