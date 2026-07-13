<?php
// Community Programs Installation Script
// This script sets up the database tables and sample data

require_once '../../admin/config/connection.php';

$success_messages = [];
$error_messages = [];

// Function to execute SQL file
function executeSQLFile($conn, $file_path) {
    if (!file_exists($file_path)) {
        return false;
    }
    
    $sql = file_get_contents($file_path);
    $queries = explode(';', $sql);
    
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if (!mysqli_query($conn, $query)) {
                return false;
            }
        }
    }
    return true;
}

// Check if installation is requested
if (isset($_POST['install'])) {
    // Create database tables
    if (executeSQLFile($conn, '../database/community_schema.sql')) {
        $success_messages[] = 'Database tables created successfully.';
        
        // Insert sample data
        if (executeSQLFile($conn, '../database/sample_data.sql')) {
            $success_messages[] = 'Sample data inserted successfully.';
        } else {
            $error_messages[] = 'Error inserting sample data: ' . mysqli_error($conn);
        }
    } else {
        $error_messages[] = 'Error creating database tables: ' . mysqli_error($conn);
    }
    
    // Create upload directories
    $upload_dirs = [
        '../assets/images/programs',
        '../assets/images/testimonials',
        '../assets/images/team',
        '../admin/uploads/programs',
        '../admin/uploads/testimonials',
        '../admin/uploads/team'
    ];
    
    foreach ($upload_dirs as $dir) {
        if (!file_exists($dir)) {
            if (mkdir($dir, 0755, true)) {
                $success_messages[] = "Created directory: $dir";
            } else {
                $error_messages[] = "Failed to create directory: $dir";
            }
        }
    }
    
    // Create default admin user if not exists
    $admin_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM community_admins WHERE username = 'admin'");
    if ($admin_check && mysqli_fetch_assoc($admin_check)['count'] == 0) {
        $default_password = password_hash('admin123', PASSWORD_DEFAULT);
        $admin_query = "INSERT INTO community_admins (username, password, email, full_name, role) 
                       VALUES ('admin', '$default_password', 'admin@virungaecotours.com', 'System Administrator', 'super_admin')";
        
        if (mysqli_query($conn, $admin_query)) {
            $success_messages[] = 'Default admin user created (username: admin, password: admin123)';
        } else {
            $error_messages[] = 'Error creating default admin user: ' . mysqli_error($conn);
        }
    }
}

// Check if tables exist
$tables_exist = true;
$required_tables = [
    'community_admins',
    'community_programs', 
    'community_testimonials',
    'community_team',
    'community_messages',
    'community_categories'
];

foreach ($required_tables as $table) {
    $check_query = "SHOW TABLES LIKE '$table'";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) == 0) {
        $tables_exist = false;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Programs Installation</title>
    <link rel="stylesheet" href="../../css/earthy-theme.css">
    <link rel="stylesheet" href="../admin/assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .install-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
        }
        .install-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .install-header h1 {
            color: var(--primary-green);
            margin-bottom: 1rem;
        }
        .status-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            border-left: 4px solid var(--primary-green);
            background-color: var(--neutral-light);
        }
        .requirements-list {
            list-style: none;
            padding: 0;
        }
        .requirements-list li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .requirements-list .check {
            color: var(--success-color);
        }
        .requirements-list .cross {
            color: var(--error-color);
        }
        .install-actions {
            text-align: center;
            margin-top: 2rem;
        }
        .success-message {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
            padding: 1rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1rem;
            border: 1px solid rgba(76, 175, 80, 0.2);
        }
        .error-message {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--error-color);
            padding: 1rem;
            border-radius: var(--border-radius-md);
            margin-bottom: 1rem;
            border: 1px solid rgba(244, 67, 54, 0.2);
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <img src="../../images/logos/logo.png" alt="Virunga Ecotours" style="height: 60px; margin-bottom: 1rem;">
            <h1>Community Programs Installation</h1>
            <p>Set up the database and initial configuration for the Community Programs system.</p>
        </div>

        <!-- Messages -->
        <?php foreach ($success_messages as $message): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>

        <?php foreach ($error_messages as $message): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endforeach; ?>

        <!-- System Requirements -->
        <div class="status-section">
            <h3>System Requirements</h3>
            <ul class="requirements-list">
                <li>
                    <i class="fas fa-check check"></i>
                    PHP <?php echo PHP_VERSION; ?> (Required: 7.4+)
                </li>
                <li>
                    <?php if (extension_loaded('mysqli')): ?>
                        <i class="fas fa-check check"></i>
                        MySQLi Extension
                    <?php else: ?>
                        <i class="fas fa-times cross"></i>
                        MySQLi Extension (Not installed)
                    <?php endif; ?>
                </li>
                <li>
                    <?php if (is_writable('../assets/images')): ?>
                        <i class="fas fa-check check"></i>
                        Write permissions for uploads
                    <?php else: ?>
                        <i class="fas fa-times cross"></i>
                        Write permissions for uploads (Check folder permissions)
                    <?php endif; ?>
                </li>
                <li>
                    <?php if ($conn): ?>
                        <i class="fas fa-check check"></i>
                        Database connection
                    <?php else: ?>
                        <i class="fas fa-times cross"></i>
                        Database connection (Check config/connection.php)
                    <?php endif; ?>
                </li>
            </ul>
        </div>

        <!-- Installation Status -->
        <div class="status-section">
            <h3>Installation Status</h3>
            <?php if ($tables_exist): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    Community Programs system is already installed and configured.
                </div>
                <div class="install-actions">
                    <a href="../admin/" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i>
                        Go to Admin Panel
                    </a>
                    <a href="../index.php" class="btn btn-outline" target="_blank">
                        <i class="fas fa-external-link-alt"></i>
                        View Community Site
                    </a>
                </div>
            <?php else: ?>
                <p>The Community Programs system is not yet installed. Click the button below to install the database tables and sample data.</p>
                
                <div class="install-actions">
                    <form method="POST">
                        <button type="submit" name="install" class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            Install Community Programs System
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- Post-Installation Instructions -->
        <?php if ($tables_exist): ?>
            <div class="status-section">
                <h3>Next Steps</h3>
                <ol>
                    <li>Access the admin panel using the default credentials (admin/admin123)</li>
                    <li>Change the default admin password in Settings</li>
                    <li>Add your community programs, team members, and testimonials</li>
                    <li>Customize the categories and settings as needed</li>
                    <li>Upload images for your programs and team members</li>
                </ol>
            </div>
        <?php endif; ?>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--neutral-beige); color: var(--text-medium);">
            <p>&copy; 2025 Virunga Ecotours Community Programs. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
