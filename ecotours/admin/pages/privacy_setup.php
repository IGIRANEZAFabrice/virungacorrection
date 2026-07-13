<?php
session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup_privacy'])) {
    try {
        // Read and execute the SQL file
        $sqlFile = '../database/privacy_management.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Split the SQL into individual statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
            
            $message = "Privacy management tables and data have been set up successfully!";
        } else {
            $error = "SQL file not found: " . $sqlFile;
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Check if tables exist
$tablesExist = false;
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_policy'");
    $privacyPolicyExists = $stmt->rowCount() > 0;
    
    $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_requests'");
    $privacyRequestsExists = $stmt->rowCount() > 0;
    
    $tablesExist = $privacyPolicyExists && $privacyRequestsExists;
} catch (PDOException $e) {
    $error = "Error checking tables: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Management Setup - Admin Dashboard</title>
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .setup-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .setup-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--neutral-beige);
        }
        
        .setup-header h1 {
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }
        
        .status-card {
            background: var(--neutral-light);
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            border-left: 4px solid var(--primary-green);
        }
        
        .status-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .status-item:last-child {
            margin-bottom: 0;
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-badge.exists {
            background: #d4edda;
            color: #155724;
        }
        
        .status-badge.missing {
            background: #f8d7da;
            color: #721c24;
        }
        
        .setup-actions {
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary-green);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--accent-sage);
            transform: translateY(-1px);
        }
        
        .btn-secondary {
            background: var(--neutral-beige);
            color: var(--text-dark);
            margin-left: 1rem;
        }
        
        .btn-secondary:hover {
            background: var(--neutral-cream);
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            color: #004085;
            padding: 1.5rem;
            border-radius: var(--border-radius);
            margin-bottom: 2rem;
        }
        
        .info-box h3 {
            margin-top: 0;
            color: #004085;
        }
        
        .info-box ul {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <?php include './includes/sidebar.php'; ?>
        
        <main class="main-content">
            <div class="setup-container">
                <div class="setup-header">
                    <h1><i class="fas fa-shield-alt"></i> Privacy Management Setup</h1>
                    <p>Initialize the privacy management system for your admin panel</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <h3><i class="fas fa-info-circle"></i> What will be set up?</h3>
                    <p>This setup will create the following components for privacy management:</p>
                    <ul>
                        <li><strong>Privacy Policy Table:</strong> Store and manage your privacy policy content</li>
                        <li><strong>Privacy Requests Table:</strong> Handle data subject requests (GDPR, CCPA, etc.)</li>
                        <li><strong>Audit Log Table:</strong> Track all privacy-related administrative actions</li>
                        <li><strong>Privacy Settings Table:</strong> Configure privacy management options</li>
                        <li><strong>Sample Data:</strong> Demo privacy requests and default policy content</li>
                    </ul>
                </div>

                <div class="status-card">
                    <h3><i class="fas fa-database"></i> Database Status</h3>
                    
                    <?php
                    try {
                        $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_policy'");
                        $privacyPolicyExists = $stmt->rowCount() > 0;
                        
                        $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_requests'");
                        $privacyRequestsExists = $stmt->rowCount() > 0;
                        
                        $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_audit_log'");
                        $auditLogExists = $stmt->rowCount() > 0;
                        
                        $stmt = $pdo->query("SHOW TABLES LIKE 'privacy_settings'");
                        $settingsExists = $stmt->rowCount() > 0;
                    } catch (PDOException $e) {
                        $privacyPolicyExists = false;
                        $privacyRequestsExists = false;
                        $auditLogExists = false;
                        $settingsExists = false;
                    }
                    ?>
                    
                    <div class="status-item">
                        <span>Privacy Policy Table</span>
                        <span class="status-badge <?php echo $privacyPolicyExists ? 'exists' : 'missing'; ?>">
                            <?php echo $privacyPolicyExists ? 'Exists' : 'Missing'; ?>
                        </span>
                    </div>
                    
                    <div class="status-item">
                        <span>Privacy Requests Table</span>
                        <span class="status-badge <?php echo $privacyRequestsExists ? 'exists' : 'missing'; ?>">
                            <?php echo $privacyRequestsExists ? 'Exists' : 'Missing'; ?>
                        </span>
                    </div>
                    
                    <div class="status-item">
                        <span>Audit Log Table</span>
                        <span class="status-badge <?php echo $auditLogExists ? 'exists' : 'missing'; ?>">
                            <?php echo $auditLogExists ? 'Exists' : 'Missing'; ?>
                        </span>
                    </div>
                    
                    <div class="status-item">
                        <span>Privacy Settings Table</span>
                        <span class="status-badge <?php echo $settingsExists ? 'exists' : 'missing'; ?>">
                            <?php echo $settingsExists ? 'Exists' : 'Missing'; ?>
                        </span>
                    </div>
                </div>

                <div class="setup-actions">
                    <?php if (!$tablesExist): ?>
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="setup_privacy" class="btn btn-primary">
                                <i class="fas fa-play"></i> Set Up Privacy Management
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="./privacy_management.php" class="btn btn-primary">
                            <i class="fas fa-shield-alt"></i> Go to Privacy Management
                        </a>
                        <form method="POST" style="display: inline;">
                            <button type="submit" name="setup_privacy" class="btn btn-secondary">
                                <i class="fas fa-sync-alt"></i> Reinstall Tables
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
