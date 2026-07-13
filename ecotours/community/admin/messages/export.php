<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get filter parameters from URL
$status_filter = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$date_from = isset($_GET['date_from']) ? mysqli_real_escape_string($conn, $_GET['date_from']) : '';
$date_to = isset($_GET['date_to']) ? mysqli_real_escape_string($conn, $_GET['date_to']) : '';

// Build WHERE clause
$where_conditions = ["1=1"];

if ($status_filter) {
    $where_conditions[] = "status = '$status_filter'";
}

if ($search_query) {
    $where_conditions[] = "(name LIKE '%$search_query%' OR email LIKE '%$search_query%' OR subject LIKE '%$search_query%' OR message LIKE '%$search_query%')";
}

if ($date_from) {
    $where_conditions[] = "DATE(sent_at) >= '$date_from'";
}

if ($date_to) {
    $where_conditions[] = "DATE(sent_at) <= '$date_to'";
}

$where_clause = implode(' AND ', $where_conditions);

// Get all messages based on filters
$export_query = "SELECT 
    id,
    name,
    email,
    phone,
    subject,
    message,
    country,
    program_interest,
    volunteer_interest,
    donation_interest,
    status,
    admin_notes,
    ip_address,
    user_agent,
    sent_at,
    replied_at
    FROM community_messages 
    WHERE $where_clause
    ORDER BY sent_at DESC";

$export_result = mysqli_query($conn, $export_query);

if (!$export_result) {
    die('Error fetching message data: ' . mysqli_error($conn));
}

// Generate filename with filters applied
$filename_parts = ['messages'];
if ($status_filter) {
    $filename_parts[] = $status_filter;
}
if ($date_from || $date_to) {
    if ($date_from && $date_to) {
        $filename_parts[] = $date_from . '_to_' . $date_to;
    } elseif ($date_from) {
        $filename_parts[] = 'from_' . $date_from;
    } else {
        $filename_parts[] = 'until_' . $date_to;
    }
}
$filename_parts[] = date('Y-m-d_H-i-s');

$filename = implode('_', $filename_parts) . '.csv';

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Create file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add CSV headers
$headers = [
    'ID',
    'Name',
    'Email',
    'Phone',
    'Subject',
    'Message',
    'Country',
    'Program Interest',
    'Volunteer Interest',
    'Donation Interest',
    'Status',
    'Admin Notes',
    'IP Address',
    'User Agent',
    'Sent Date',
    'Sent Time',
    'Replied Date'
];

fputcsv($output, $headers);

// Add data rows
while ($row = mysqli_fetch_assoc($export_result)) {
    $csv_row = [
        $row['id'],
        $row['name'],
        $row['email'],
        $row['phone'],
        $row['subject'],
        // Clean message content for CSV
        str_replace(["\r\n", "\r", "\n"], ' | ', $row['message']),
        $row['country'],
        $row['program_interest'],
        $row['volunteer_interest'] ? 'Yes' : 'No',
        $row['donation_interest'] ? 'Yes' : 'No',
        ucfirst($row['status']),
        $row['admin_notes'] ? str_replace(["\r\n", "\r", "\n"], ' | ', $row['admin_notes']) : '',
        $row['ip_address'],
        $row['user_agent'],
        date('Y-m-d', strtotime($row['sent_at'])),
        date('H:i:s', strtotime($row['sent_at'])),
        $row['replied_at'] ? date('Y-m-d H:i:s', strtotime($row['replied_at'])) : ''
    ];
    
    fputcsv($output, $csv_row);
}

// Add summary row
fputcsv($output, []); // Empty row
fputcsv($output, ['EXPORT SUMMARY']);
fputcsv($output, ['Total Messages', mysqli_num_rows($export_result)]);
fputcsv($output, ['Export Date', date('Y-m-d H:i:s')]);
fputcsv($output, ['Exported By', $_SESSION['community_admin_name']]);

if ($status_filter) {
    fputcsv($output, ['Status Filter', ucfirst($status_filter)]);
}

if ($search_query) {
    fputcsv($output, ['Search Query', $search_query]);
}

if ($date_from || $date_to) {
    $date_range = '';
    if ($date_from && $date_to) {
        $date_range = $date_from . ' to ' . $date_to;
    } elseif ($date_from) {
        $date_range = 'From ' . $date_from;
    } else {
        $date_range = 'Until ' . $date_to;
    }
    fputcsv($output, ['Date Range', $date_range]);
}

// Close file pointer
fclose($output);

// Close database connection
mysqli_close($conn);

// Log the export action
error_log("Messages exported by admin ID: " . $_SESSION['community_admin_id'] . " - Filters: " . json_encode([
    'status' => $status_filter,
    'search' => $search_query,
    'date_from' => $date_from,
    'date_to' => $date_to
]));

exit;
?>
