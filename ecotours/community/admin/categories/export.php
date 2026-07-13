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

// Build WHERE clause
$where_conditions = ["1=1"];

if ($status_filter) {
    $where_conditions[] = "c.status = '$status_filter'";
}

if ($search_query) {
    $where_conditions[] = "(c.name LIKE '%$search_query%' OR c.description LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get all categories with program counts based on filters
$export_query = "SELECT 
    c.id,
    c.name,
    c.description,
    c.icon,
    c.color,
    c.status,
    c.created_at,
    c.updated_at,
    COUNT(p.id) as program_count,
    COUNT(CASE WHEN p.status = 'active' THEN 1 END) as active_programs,
    COUNT(CASE WHEN p.status = 'inactive' THEN 1 END) as inactive_programs
    FROM community_categories c 
    LEFT JOIN community_programs p ON c.name = p.category 
    WHERE $where_clause
    GROUP BY c.id 
    ORDER BY c.created_at DESC";

$export_result = mysqli_query($conn, $export_query);

if (!$export_result) {
    die('Error fetching category data: ' . mysqli_error($conn));
}

// Generate filename with filters applied
$filename_parts = ['categories'];
if ($status_filter) {
    $filename_parts[] = $status_filter;
}
if ($search_query) {
    $filename_parts[] = 'search_' . preg_replace('/[^a-zA-Z0-9]/', '_', $search_query);
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
    'Description',
    'Icon Class',
    'Color',
    'Status',
    'Total Programs',
    'Active Programs',
    'Inactive Programs',
    'Created Date',
    'Created Time',
    'Last Updated Date',
    'Last Updated Time'
];

fputcsv($output, $headers);

// Add data rows
while ($row = mysqli_fetch_assoc($export_result)) {
    $csv_row = [
        $row['id'],
        $row['name'],
        $row['description'],
        $row['icon'],
        $row['color'],
        ucfirst($row['status']),
        $row['program_count'],
        $row['active_programs'],
        $row['inactive_programs'],
        date('Y-m-d', strtotime($row['created_at'])),
        date('H:i:s', strtotime($row['created_at'])),
        date('Y-m-d', strtotime($row['updated_at'])),
        date('H:i:s', strtotime($row['updated_at']))
    ];
    
    fputcsv($output, $csv_row);
}

// Add summary row
fputcsv($output, []); // Empty row
fputcsv($output, ['EXPORT SUMMARY']);
fputcsv($output, ['Total Categories', mysqli_num_rows($export_result)]);
fputcsv($output, ['Export Date', date('Y-m-d H:i:s')]);
fputcsv($output, ['Exported By', $_SESSION['community_admin_name'] ?? 'Admin']);

if ($status_filter) {
    fputcsv($output, ['Status Filter', ucfirst($status_filter)]);
}

if ($search_query) {
    fputcsv($output, ['Search Query', $search_query]);
}

// Add category usage statistics
fputcsv($output, []); // Empty row
fputcsv($output, ['CATEGORY USAGE STATISTICS']);

// Reset result pointer to calculate statistics
mysqli_data_seek($export_result, 0);
$total_programs = 0;
$categories_in_use = 0;
$most_used_category = '';
$most_used_count = 0;

while ($row = mysqli_fetch_assoc($export_result)) {
    $total_programs += $row['program_count'];
    if ($row['program_count'] > 0) {
        $categories_in_use++;
    }
    if ($row['program_count'] > $most_used_count) {
        $most_used_count = $row['program_count'];
        $most_used_category = $row['name'];
    }
}

fputcsv($output, ['Categories in Use', $categories_in_use]);
fputcsv($output, ['Total Programs Categorized', $total_programs]);
if ($most_used_category) {
    fputcsv($output, ['Most Used Category', $most_used_category . ' (' . $most_used_count . ' programs)']);
}

// Close file pointer
fclose($output);

// Close database connection
mysqli_close($conn);

// Log the export action
error_log("Categories exported by admin ID: " . $_SESSION['community_admin_id'] . " - Filters: " . json_encode([
    'status' => $status_filter,
    'search' => $search_query
]));

exit;
?>
