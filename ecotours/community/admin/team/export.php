<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get all team members
$export_query = "SELECT 
    id,
    name,
    title,
    bio,
    email,
    phone,
    facebook,
    twitter,
    linkedin,
    instagram,
    order_position,
    status,
    created_at,
    updated_at
    FROM community_team 
    ORDER BY order_position ASC, created_at DESC";

$export_result = mysqli_query($conn, $export_query);

if (!$export_result) {
    die('Error fetching team data: ' . mysqli_error($conn));
}

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="team_members_' . date('Y-m-d_H-i-s') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Create file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Add CSV headers
$headers = [
    'ID',
    'Name',
    'Job Title',
    'Biography',
    'Email',
    'Phone',
    'Facebook URL',
    'Twitter URL',
    'LinkedIn URL',
    'Instagram URL',
    'Display Order',
    'Status',
    'Created Date',
    'Last Updated'
];

fputcsv($output, $headers);

// Add data rows
while ($row = mysqli_fetch_assoc($export_result)) {
    $csv_row = [
        $row['id'],
        $row['name'],
        $row['title'],
        $row['bio'],
        $row['email'],
        $row['phone'],
        $row['facebook'],
        $row['twitter'],
        $row['linkedin'],
        $row['instagram'],
        $row['order_position'],
        $row['status'],
        date('Y-m-d H:i:s', strtotime($row['created_at'])),
        date('Y-m-d H:i:s', strtotime($row['updated_at']))
    ];
    
    fputcsv($output, $csv_row);
}

// Close file pointer
fclose($output);

// Close database connection
mysqli_close($conn);

// Log the export action
error_log("Team members exported by admin ID: " . $_SESSION['community_admin_id']);

exit;
?>
