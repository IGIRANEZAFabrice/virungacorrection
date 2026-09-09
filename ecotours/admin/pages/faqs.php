<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.html");
  exit();
}

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
        $category = trim($_POST['category'] ?? 'General');
        $question = trim($_POST['question'] ?? '');
        $answer = trim($_POST['answer'] ?? '');
        $display_order = intval($_POST['display_order'] ?? 1);
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (isset($_POST['faq_id']) && !empty($_POST['faq_id'])) {
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
            $stmt = $pdo->prepare("INSERT INTO faqs (category, question, answer, display_order, is_active) 
                                 VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt->execute([$category, $question, $answer, $display_order, $is_active])) {
                $message = "FAQ added successfully!";
                $messageType = "success";
            }
        }
    } catch(PDOException $e) {
        $message = "Error saving FAQ: " . $e->getMessage();
        $messageType = "danger";
    }
}

// Fetch all FAQs
try {
    $stmt = $pdo->query("SELECT * FROM faqs ORDER BY category, display_order ASC");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group FAQs by category
    $faqsByCategory = [];
    foreach ($faqs as $faq) {
        $faqsByCategory[$faq['category']][] = $faq;
    }

    $categories = array_unique(array_column($faqs, 'category'));
} catch(PDOException $e) {
    $message = "Error fetching FAQs: " . $e->getMessage();
    $messageType = "danger";
    $faqsByCategory = [];
    $categories = [];
}

$total_faqs = count($faqs);
$active_faqs = 0;
foreach ($faqs as $f) {
    if ($f['is_active']) $active_faqs++;
}

$selected_cat = trim($_GET['cat'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Management - Virunga Admin</title>
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/faqs.css">
    <script src="../js/common.js" defer></script>
</head>
<body>
    <div class="admin-container">
        <!-- Include sidebar template -->
        <?php include_once './includes/sidebar.php'; ?>

        <main class="main-content">
            <!-- Top Header -->
            <?php include_once './includes/header.php'; ?>

            <div class="faq-management-container">
                
                <!-- Page Header Row -->
                <div class="page-header-row">
                    <div class="page-title-wrap">
                        <div class="breadcrumb-trail">
                            <a href="../index.php">Dashboard</a>
                            <span>/</span>
                            <span>FAQs Management</span>
                        </div>
                        <h1 class="page-title">
                            <i class="fas fa-circle-question" style="color: #206bc4;"></i>
                            Frequently Asked Questions
                        </h1>
                        <p class="page-subtitle">
                            Curate travel advisories, permit requirements, booking logistics, and common inquiries.
                        </p>
                    </div>

                    <div class="page-actions-wrap">
                        <a href="../../faq.php" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Live FAQ Page
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openFaqModal()">
                            <i class="fas fa-plus"></i> Add New Question
                        </button>
                    </div>
                </div>

                <!-- Status Alert -->
                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?>">
                        <i class="fas <?php echo $messageType === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                        <span><?php echo htmlspecialchars($message); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- KPI Metric Row -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $total_faqs; ?></span>
                            <span class="kpi-badge badge-blue"><i class="fas fa-question"></i> Questions</span>
                        </div>
                        <span class="kpi-title">Total FAQ Entries</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo count($categories); ?></span>
                            <span class="kpi-badge badge-purple"><i class="fas fa-folder-tree"></i> Topics</span>
                        </div>
                        <span class="kpi-title">Knowledge Categories</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo $active_faqs; ?></span>
                            <span class="kpi-badge badge-green"><i class="fas fa-check"></i> Published</span>
                        </div>
                        <span class="kpi-title">Active on Website</span>
                    </div>
                </div>

                <!-- Category Filter Toolbar -->
                <div class="toolbar-card">
                    <div class="filter-pills-wrap">
                        <a href="faqs.php" class="filter-pill <?php echo empty($selected_cat) ? 'active' : ''; ?>">
                            All Categories <span class="count"><?php echo $total_faqs; ?></span>
                        </a>
                        <?php foreach ($categories as $cat): 
                            $cCount = count($faqsByCategory[$cat] ?? []);
                        ?>
                            <a href="faqs.php?cat=<?php echo urlencode($cat); ?>" class="filter-pill <?php echo ($selected_cat === $cat) ? 'active' : ''; ?>">
                                <i class="fas fa-tag" style="font-size: 10px;"></i> <?php echo htmlspecialchars($cat); ?> <span class="count"><?php echo $cCount; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- FAQ Grouped Content -->
                <?php if (!empty($faqsByCategory)): ?>
                    <?php foreach ($faqsByCategory as $catName => $catFaqs): 
                        if (!empty($selected_cat) && $selected_cat !== $catName) continue;
                    ?>
                        <div class="faq-category-block">
                            <div class="category-header-pill">
                                <i class="fas fa-folder-open"></i> <?php echo htmlspecialchars($catName); ?>
                                <span style="font-size: 11px; font-weight: 500; color: #64748b;">(<?php echo count($catFaqs); ?> questions)</span>
                            </div>

                            <div class="faq-items-stack">
                                <?php foreach ($catFaqs as $item): ?>
                                    <div class="faq-item-card">
                                        <div class="faq-item-top">
                                            <h4 class="faq-question">
                                                <i class="fas fa-circle-question"></i>
                                                <?php echo htmlspecialchars($item['question']); ?>
                                            </h4>
                                            <span class="badge <?php echo $item['is_active'] ? 'badge-green' : 'badge-rose'; ?>" style="font-size: 10.5px;">
                                                <?php echo $item['is_active'] ? 'Active' : 'Draft'; ?>
                                            </span>
                                        </div>

                                        <p class="faq-answer">
                                            <?php echo nl2br(htmlspecialchars($item['answer'])); ?>
                                        </p>

                                        <div class="faq-meta-bar">
                                            <span><i class="fas fa-arrow-down-1-9"></i> Order: <strong>#<?php echo $item['display_order']; ?></strong></span>
                                            
                                            <div class="faq-actions">
                                                <button type="button" class="btn-icon" title="Edit FAQ" onclick="openFaqModal(<?php echo htmlspecialchars(json_encode($item)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="faqs.php?delete=<?php echo $item['id']; ?>" class="btn-icon danger" title="Delete FAQ" onclick="return confirm('Are you sure you want to delete this FAQ?');">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card" style="text-align: center; padding: 3rem 1rem; color: #64748b;">
                        <i class="fas fa-circle-question" style="font-size: 40px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                        <p style="margin: 0 0 12px;">No FAQs configured yet.</p>
                        <button type="button" class="btn btn-primary btn-sm" onclick="openFaqModal()">Add First FAQ</button>
                    </div>
                <?php endif; ?>

            </div>
        </main>
    </div>

    <!-- Modern Add/Edit FAQ Modal -->
    <div id="faqModal" class="modal-overlay">
        <div class="modal-card">
            <div class="modal-header">
                <h3 id="modalHeadingText"><i class="fas fa-plus-circle" style="color: #206bc4; margin-right: 6px;"></i> Add New FAQ</h3>
                <button type="button" class="modal-close" onclick="closeFaqModal()">&times;</button>
            </div>
            <form method="POST" action="faqs.php">
                <input type="hidden" id="faqIdInput" name="faq_id" />

                <div class="modal-body">
                    <div class="form-group">
                        <label for="faqCategoryInput">Category / Topic</label>
                        <input type="text" id="faqCategoryInput" name="category" list="categoryList" class="form-control" placeholder="e.g. Gorilla Permits, Travel Logistics..." required />
                        <datalist id="categoryList">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="faqQuestionInput">Question</label>
                        <input type="text" id="faqQuestionInput" name="question" class="form-control" placeholder="e.g. How far in advance do I need to book gorilla permits?" required />
                    </div>

                    <div class="form-group">
                        <label for="faqAnswerInput">Detailed Answer</label>
                        <textarea id="faqAnswerInput" name="answer" class="form-control" rows="4" placeholder="Provide clear, concise guidance for travelers..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="faqOrderInput">Display Order</label>
                        <input type="number" id="faqOrderInput" name="display_order" class="form-control" value="1" required />
                    </div>

                    <div class="form-switch">
                        <input type="checkbox" id="faqActiveCheck" name="is_active" value="1" checked />
                        <label for="faqActiveCheck" style="margin: 0; font-size: 13px; font-weight: 600; color: #0f172a; cursor: pointer;">
                            Display Live on Website
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline btn-sm" onclick="closeFaqModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Save Question
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openFaqModal(data = null) {
            const modal = document.getElementById('faqModal');
            const heading = document.getElementById('modalHeadingText');
            const idInput = document.getElementById('faqIdInput');
            const catInput = document.getElementById('faqCategoryInput');
            const qInput = document.getElementById('faqQuestionInput');
            const aInput = document.getElementById('faqAnswerInput');
            const oInput = document.getElementById('faqOrderInput');
            const activeCheck = document.getElementById('faqActiveCheck');

            if (data) {
                heading.innerHTML = '<i class="fas fa-edit" style="color: #206bc4; margin-right: 6px;"></i> Edit FAQ';
                idInput.value = data.id;
                catInput.value = data.category || '';
                qInput.value = data.question || '';
                aInput.value = data.answer || '';
                oInput.value = data.display_order || 1;
                activeCheck.checked = (data.is_active == 1);
            } else {
                heading.innerHTML = '<i class="fas fa-plus-circle" style="color: #206bc4; margin-right: 6px;"></i> Add New FAQ';
                idInput.value = '';
                catInput.value = '<?php echo addslashes($selected_cat ?: "General Travel"); ?>';
                qInput.value = '';
                aInput.value = '';
                oInput.value = 1;
                activeCheck.checked = true;
            }

            modal.classList.add('show');
        }

        function closeFaqModal() {
            document.getElementById('faqModal').classList.remove('show');
        }

        document.getElementById('faqModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeFaqModal();
            }
        });
    </script>
</body>
</html>