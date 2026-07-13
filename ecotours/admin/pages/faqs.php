<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

// Initialize message variables
$message = '';
$messageType = '';

// Handle FAQ deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM faqs WHERE id = ?");
        if ($stmt->execute([$_GET['delete']])) {
            $message = "FAQ deleted successfully!";
            $messageType = "success";
        }
    } catch(PDOException $e) {
        $message = "Error deleting FAQ: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Handle form submission (Add/Edit FAQ)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $category = $_POST['category'];
        $question = $_POST['question'];
        $answer = $_POST['answer'];
        $display_order = intval($_POST['display_order']);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (isset($_POST['faq_id']) && !empty($_POST['faq_id'])) {
            // Update existing FAQ
            $stmt = $pdo->prepare("UPDATE faqs SET 
                category = ?, 
                question = ?, 
                answer = ?, 
                display_order = ?, 
                is_active = ? 
                WHERE id = ?");
            
            if ($stmt->execute([$category, $question, $answer, $display_order, $is_active, $_POST['faq_id']])) {
                $message = "FAQ updated successfully!";
                $messageType = "success";
            }
        } else {
            // Add new FAQ
            $stmt = $pdo->prepare("INSERT INTO faqs (category, question, answer, display_order, is_active) 
                                 VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$category, $question, $answer, $display_order, $is_active])) {
                $message = "FAQ added successfully!";
                $messageType = "success";
            }
        }
    } catch(PDOException $e) {
        $message = "Error processing FAQ: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Fetch FAQ for editing
$editFaq = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = ?");
        $stmt->execute([$_GET['edit']]);
        $editFaq = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $message = "Error fetching FAQ: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Fetch all FAQs
try {
    $stmt = $pdo->query("SELECT * FROM faqs ORDER BY category, display_order");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group FAQs by category
    $faqsByCategory = [];
    foreach ($faqs as $faq) {
        $faqsByCategory[$faq['category']][] = $faq;
    }

    // Get unique categories
    $categories = array_unique(array_column($faqs, 'category'));
} catch(PDOException $e) {
    $message = "Error fetching FAQs: " . $e->getMessage();
    $messageType = "danger";
    $faqsByCategory = [];
    $categories = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Management - Admin Dashboard</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/faqs.css">
    <script src="../js/common.js" defer></script>
</head>
<body>
    <div class="admin-container">
           <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Include header template -->
        <?php include_once './includes/header.php'; ?>

            <div class="faq-container">
                <h1 class="page-title">FAQ Management</h1>

                <button class="add-faq-btn" id="toggleFormBtn">
                    <i class="fas fa-plus"></i>
                    <span>Add New FAQ</span>
                </button>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- Add/Edit FAQ Form -->
                <div class="form-container" id="faqForm">
                    <h3><?php echo $editFaq ? 'Edit FAQ' : 'Add New FAQ'; ?></h3>
                    <form method="POST" action="">
                        <?php if ($editFaq): ?>
                            <input type="hidden" name="faq_id" value="<?php echo $editFaq['id']; ?>">
                        <?php endif; ?>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" class="form-control" name="category" list="categoryList" 
                                       value="<?php echo $editFaq ? htmlspecialchars($editFaq['category']) : ''; ?>" required>
                                <datalist id="categoryList">
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo htmlspecialchars($cat); ?>">
                                    <?php endforeach; ?>
                                </datalist>
                            </div>
                            
                            <div class="form-group">
                                <label>Display Order</label>
                                <input type="number" class="form-control" name="display_order" 
                                       value="<?php echo $editFaq ? $editFaq['display_order'] : '0'; ?>" min="0">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-switch">
                                    <input type="checkbox" name="is_active" 
                                           <?php echo (!$editFaq || $editFaq['is_active']) ? 'checked' : ''; ?>>
                                    Active
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Question</label>
                            <input type="text" class="form-control" name="question" 
                                   value="<?php echo $editFaq ? htmlspecialchars($editFaq['question']) : ''; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Answer</label>
                            <textarea class="form-control" name="answer" required>
                                <?php echo $editFaq ? htmlspecialchars($editFaq['answer']) : ''; ?>
                            </textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <?php echo $editFaq ? 'Update FAQ' : 'Add FAQ'; ?>
                            </button>
                            <?php if ($editFaq): ?>
                                <a href="faqs.php" class="btn btn-secondary">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>

                <!-- FAQ List -->
                <div class="faq-list">
                    <h3>Existing FAQs</h3>
                    <?php if (empty($faqsByCategory)): ?>
                        <div class="alert alert-info">No FAQs found. Add your first FAQ above!</div>
                    <?php else: ?>
                        <?php foreach ($faqsByCategory as $category => $categoryFaqs): ?>
                            <div class="category-header">
                                <i class="fas fa-folder"></i>
                                <?php echo htmlspecialchars($category); ?>
                            </div>
                            
                            <?php foreach ($categoryFaqs as $faq): ?>
                                <div class="faq-item">
                                    <div class="flex-between">
                                        <div class="faq-content">
                                            <h4><?php echo htmlspecialchars($faq['question']); ?></h4>
                                            <p><?php echo nl2br(htmlspecialchars($faq['answer'])); ?></p>
                                            <div class="text-small">
                                                Order: <?php echo $faq['display_order']; ?> | 
                                                Status: <?php echo $faq['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </div>
                                        </div>
                                        <div class="faq-actions">
                                            <a href="?edit=<?php echo $faq['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?delete=<?php echo $faq['id']; ?>" class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this FAQ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleBtn = document.getElementById('toggleFormBtn');
                    const faqForm = document.getElementById('faqForm');
                    
                    // Show form if there are validation errors or editing
                    <?php if ($message || isset($_GET['edit'])): ?>
                        faqForm.classList.add('active');
                        toggleBtn.classList.add('active');
                    <?php endif; ?>

                    toggleBtn.addEventListener('click', function() {
                        faqForm.classList.toggle('active');
                        this.classList.toggle('active');
                        
                        // Change button text based on state
                        const btnText = this.querySelector('span');
                        if (faqForm.classList.contains('active')) {
                            btnText.textContent = 'Close Form';
                        } else {
                            btnText.textContent = 'Add New FAQ';
                            // Reset form if it's not in edit mode
                            if (!document.querySelector('input[name="faq_id"]')) {
                                document.querySelector('form').reset();
                            }
                        }
                    });
                });
            </script>
        </main>
    </div>
</body>
</html>