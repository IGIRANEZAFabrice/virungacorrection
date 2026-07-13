<?php
session_start();
require_once '../../../admin/config/connection.php';

// Check if user is logged in
if (!isset($_SESSION['community_admin_id'])) {
    header('Location: ../index.php');
    exit;
}

// Predefined icon options
$icon_options = [
    'fas fa-graduation-cap' => 'Education',
    'fas fa-heartbeat' => 'Health',
    'fas fa-leaf' => 'Conservation',
    'fas fa-chart-line' => 'Economic Development',
    'fas fa-hammer' => 'Infrastructure',
    'fas fa-female' => 'Women Empowerment',
    'fas fa-users' => 'Community',
    'fas fa-seedling' => 'Agriculture',
    'fas fa-water' => 'Water & Sanitation',
    'fas fa-solar-panel' => 'Energy',
    'fas fa-road' => 'Transportation',
    'fas fa-home' => 'Housing',
    'fas fa-child' => 'Child Welfare',
    'fas fa-elderly' => 'Elderly Care',
    'fas fa-hands-helping' => 'Volunteer Programs',
    'fas fa-globe-africa' => 'Cultural Heritage',
    'fas fa-tree' => 'Forestry',
    'fas fa-fish' => 'Fisheries',
    'fas fa-mountain' => 'Tourism',
    'fas fa-laptop' => 'Technology',
    'fas fa-book' => 'Literacy',
    'fas fa-utensils' => 'Food Security',
    'fas fa-shield-alt' => 'Security',
    'fas fa-balance-scale' => 'Legal Aid',
    'fas fa-microphone' => 'Advocacy'
];

// Predefined color options
$color_options = [
    '#2a4858' => 'Primary Green',
    '#967259' => 'Earthy Brown',
    '#8b7355' => 'Sage Green',
    '#4a90e2' => 'Sky Blue',
    '#7ed321' => 'Bright Green',
    '#f5a623' => 'Orange',
    '#d0021b' => 'Red',
    '#9013fe' => 'Purple',
    '#50e3c2' => 'Teal',
    '#b8e986' => 'Light Green',
    '#4a4a4a' => 'Dark Gray',
    '#bd10e0' => 'Magenta',
    '#f8e71c' => 'Yellow',
    '#8b572a' => 'Brown',
    '#417505' => 'Forest Green'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category - Community Admin</title>
    <meta name="robots" content="noindex, nofollow">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../../../css/earthy-theme.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/forms.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../../assets/images/logos/logo.jpg">
    
    <style>
        .form-card {
            background: white;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--neutral-beige);
            overflow: hidden;
        }
        
        .form-header {
            padding: 2rem;
            background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
            border-bottom: 1px solid var(--neutral-beige);
        }
        
        .form-header h3 {
            font-size: 1.3rem;
            color: var(--text-dark);
            margin: 0 0 0.5rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .form-header h3 i {
            color: var(--primary-green);
        }
        
        .form-header p {
            color: var(--text-medium);
            margin: 0;
            font-size: 0.95rem;
        }
        
        .category-form {
            padding: 2rem;
        }
        
        .form-section {
            margin-bottom: 2rem;
        }
        
        .form-section:last-of-type {
            margin-bottom: 0;
        }
        
        .form-section h4 {
            font-size: 1.1rem;
            color: var(--text-dark);
            margin: 0 0 1.5rem 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--neutral-light);
        }
        
        .form-section h4 i {
            color: var(--primary-green);
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .icon-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border: 2px solid var(--neutral-beige);
            border-radius: var(--border-radius-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .icon-option:hover {
            border-color: var(--primary-green);
            background-color: var(--neutral-light);
        }
        
        .icon-option.selected {
            border-color: var(--primary-green);
            background-color: var(--primary-green);
            color: white;
        }
        
        .icon-option i {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }
        
        .icon-option span {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }
        
        .color-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            border: 2px solid var(--neutral-beige);
            border-radius: var(--border-radius-md);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .color-option:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .color-option.selected {
            border-color: var(--text-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .color-swatch {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: var(--shadow-sm);
        }
        
        .color-option span {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-dark);
        }
        
        .preview-section {
            background-color: var(--neutral-light);
            padding: 1.5rem;
            border-radius: var(--border-radius-md);
            margin-top: 1.5rem;
        }
        
        .preview-category {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: white;
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-sm);
        }
        
        .preview-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            background-color: var(--primary-green);
        }
        
        .preview-content h5 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0 0 0.25rem 0;
        }
        
        .preview-content p {
            font-size: 0.9rem;
            color: var(--text-medium);
            margin: 0;
        }
        
        @media (max-width: 768px) {
            .icon-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            }
            
            .color-grid {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include '../includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Top Bar -->
            <?php include '../includes/topbar.php'; ?>

            <!-- Content Area -->
            <div class="content-area">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-content">
                        <h1><i class="fas fa-plus-circle"></i> Add Category</h1>
                        <p>Create a new category for organizing programs</p>
                    </div>
                    <div class="page-header-actions">
                        <a href="index.php" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i>
                            Back to Categories
                        </a>
                    </div>
                </div>

                <!-- Alerts -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <div class="form-card">
                    <div class="form-header">
                        <h3><i class="fas fa-tag"></i> Category Information</h3>
                        <p>Fill in the details for the new category</p>
                    </div>
                    
                    <form action="handlers/create-handler.php" method="POST" class="category-form" id="categoryForm">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h4><i class="fas fa-info-circle"></i> Basic Information</h4>
                            
                            <div class="form-group">
                                <label for="name" class="required">Category Name</label>
                                <input type="text" id="name" name="name" required 
                                       placeholder="Enter category name">
                            </div>
                            
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea id="description" name="description" rows="3" 
                                          placeholder="Brief description of this category"></textarea>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Icon Selection -->
                        <div class="form-section">
                            <h4><i class="fas fa-icons"></i> Icon Selection</h4>
                            <p class="section-description">Choose an icon that represents this category</p>
                            
                            <input type="hidden" id="icon" name="icon" value="fas fa-tag">
                            
                            <div class="icon-grid">
                                <?php foreach ($icon_options as $icon_class => $icon_name): ?>
                                    <div class="icon-option" data-icon="<?php echo htmlspecialchars($icon_class); ?>">
                                        <i class="<?php echo htmlspecialchars($icon_class); ?>"></i>
                                        <span><?php echo htmlspecialchars($icon_name); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Color Selection -->
                        <div class="form-section">
                            <h4><i class="fas fa-palette"></i> Color Selection</h4>
                            <p class="section-description">Choose a color theme for this category</p>
                            
                            <input type="hidden" id="color" name="color" value="#2a4858">
                            
                            <div class="color-grid">
                                <?php foreach ($color_options as $color_code => $color_name): ?>
                                    <div class="color-option" data-color="<?php echo htmlspecialchars($color_code); ?>">
                                        <div class="color-swatch" style="background-color: <?php echo htmlspecialchars($color_code); ?>;"></div>
                                        <span><?php echo htmlspecialchars($color_name); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Preview -->
                        <div class="form-section">
                            <h4><i class="fas fa-eye"></i> Preview</h4>
                            <p class="section-description">See how your category will appear</p>
                            
                            <div class="preview-section">
                                <div class="preview-category">
                                    <div class="preview-icon" id="previewIcon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="preview-content">
                                        <h5 id="previewName">Category Name</h5>
                                        <p id="previewDescription">Category description will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Create Category
                            </button>
                            <a href="index.php" class="btn btn-outline">
                                <i class="fas fa-times"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="../assets/js/admin.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const iconInput = document.getElementById('icon');
            const colorInput = document.getElementById('color');
            const categoryForm = document.getElementById('categoryForm');
            
            // Preview elements
            const previewName = document.getElementById('previewName');
            const previewDescription = document.getElementById('previewDescription');
            const previewIcon = document.getElementById('previewIcon');
            
            // Icon selection
            const iconOptions = document.querySelectorAll('.icon-option');
            iconOptions.forEach(option => {
                option.addEventListener('click', function() {
                    iconOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const iconClass = this.dataset.icon;
                    iconInput.value = iconClass;
                    updatePreview();
                });
            });
            
            // Color selection
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    const colorCode = this.dataset.color;
                    colorInput.value = colorCode;
                    updatePreview();
                });
            });
            
            // Set default selections
            iconOptions[0].classList.add('selected');
            colorOptions[0].classList.add('selected');
            
            // Update preview on input change
            nameInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            
            function updatePreview() {
                const name = nameInput.value || 'Category Name';
                const description = descriptionInput.value || 'Category description will appear here';
                const icon = iconInput.value || 'fas fa-tag';
                const color = colorInput.value || '#2a4858';
                
                previewName.textContent = name;
                previewDescription.textContent = description;
                previewIcon.innerHTML = `<i class="${icon}"></i>`;
                previewIcon.style.backgroundColor = color;
            }
            
            // Form validation
            categoryForm.addEventListener('submit', function(e) {
                const name = nameInput.value.trim();
                
                if (!name) {
                    e.preventDefault();
                    alert('Please enter a category name.');
                    nameInput.focus();
                    return;
                }
                
                // Show loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
                submitBtn.disabled = true;
                
                // Re-enable button after 10 seconds (in case of server issues)
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            });
            
            // Initialize preview
            updatePreview();
        });
    </script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
