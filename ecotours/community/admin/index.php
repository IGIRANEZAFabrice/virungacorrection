<?php
session_start();
require_once '../../admin/config/connection.php';

// Redirect if already logged in
if (isset($_SESSION['community_admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
$success_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password.';
    } else {
        // Check user credentials
        $query = "SELECT id, username, password, email, full_name, role FROM community_admins WHERE username = '$username' AND status = 'active'";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (assuming passwords are hashed with password_hash())
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['community_admin_id'] = $user['id'];
                $_SESSION['community_admin_username'] = $user['username'];
                $_SESSION['community_admin_name'] = $user['full_name'];
                $_SESSION['community_admin_role'] = $user['role'];
                $_SESSION['community_admin_email'] = $user['email'];
                
                // Update last login
                $update_query = "UPDATE community_admins SET last_login = NOW() WHERE id = " . $user['id'];
                mysqli_query($conn, $update_query);
                
                // Redirect to dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error_message = 'Invalid username or password.';
            }
        } else {
            $error_message = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Virunga Ecotours Community</title>
    <meta name="description" content="Admin login for Virunga Ecotours Community Programs management system.">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/images/logos/logo.jpg">
    <style>
        .password-toggle {
            position: absolute;
            right: 2rem;
            background: none;
            border: none;
            color: var(--text-medium);
            cursor: pointer;
            top: 15px;
            padding: 0.5rem;
            z-index: 2;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--text-dark);
        }

        .password-toggle i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .password-toggle.show i {
            transform: rotate(-90deg);
        }
    </style>
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-background">
            <img src="../../images/hero/gorille.jpg" alt="Virunga Region" loading="lazy">
            <div class="login-overlay"></div>
        </div>
        
        <div class="login-content">
            <div class="login-box">
                <div class="login-header">
                    <div class="logo">
                        <img src="../assets/images/logos/logo.jpg" alt="Virunga Ecotours" class="logo-img">
                    </div>
                    <h1>Community Admin</h1>
                    <p>Sign in to manage community programs</p>
                </div>

                <?php if ($error_message): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="login-form" id="loginForm">
                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" name="username" required 
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   placeholder="Enter your username">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" name="password" required 
                                   placeholder="Enter your password">
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        Sign In
                    </button>
                </form>

                <div class="login-footer">
                    <p>&copy; 2025 Virunga Ecotours. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="../index.php">Back to Community Site</a>
                        <span>|</span>
                        <a href="../../index.php">Main Website</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // Form validation
            const loginForm = document.getElementById('loginForm');
            loginForm.addEventListener('submit', function(e) {
                const username = document.getElementById('username').value.trim();
                const password = document.getElementById('password').value.trim();
                
                if (!username || !password) {
                    e.preventDefault();
                    showAlert('Please fill in all fields.', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('.login-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
                submitBtn.disabled = true;
                
                // Re-enable button after 5 seconds (in case of server issues)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 5000);
            });

            // Auto-focus username field
            document.getElementById('username').focus();
        });

        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlerts = document.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new alert
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <i class="fas fa-${type === 'error' ? 'exclamation-triangle' : 'check-circle'}"></i>
                ${message}
            `;
            
            // Insert before form
            const form = document.querySelector('.login-form');
            form.parentNode.insertBefore(alert, form);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Prevent back button after logout
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        window.addEventListener('popstate', function() {
            window.history.replaceState(null, null, window.location.href);
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
