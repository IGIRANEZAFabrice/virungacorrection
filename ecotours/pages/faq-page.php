<?php
require_once '../admin/config/database.php';

try {
    // Get all active FAQs grouped by category
    $query = "SELECT * FROM faqs WHERE is_active = 1 ORDER BY category, display_order";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    // Fetch all FAQs and group them by category
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $faqsByCategory = [];
    foreach ($faqs as $faq) {
        $faqsByCategory[$faq['category']][] = $faq;
    }
} catch(PDOException $e) {
    error_log("Error fetching FAQs: " . $e->getMessage());
    $faqsByCategory = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
      <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <title>Adventure Excursions - Frequently Asked Questions</title>
    <link rel="stylesheet" href="../css/faqs.css" />
    <script src="../js/header.js" defer></script>
</head>
<body>
    <?php include('./includes/header.php'); ?>

    <div class="hero">
        <h1>Frequently Asked Questions</h1>
        <p>Detailed answers to common questions about our excursions, booking details, and ensuring your trip is as comfortable and enjoyable as possible.</p>
    </div>

    <main class="container">
        <?php if (empty($faqsByCategory)): ?>
            <div style="text-align: center; padding: 50px 0;">
                <h3>No FAQs available at the moment.</h3>
                <p>Please check back later or <a href="contact.php">contact us</a> with any questions.</p>
            </div>
        <?php else: ?>
            <?php 
            $delay = 0.1;
            foreach ($faqsByCategory as $category => $faqs): 
            ?>
                <section class="faq-section" style="animation-delay: <?php echo $delay; ?>s">
                    <h2 class="section-title"><?php echo htmlspecialchars($category); ?></h2>
                    <div class="faq-container">
                        <?php foreach ($faqs as $faq): ?>
                            <div class="faq-item">
                                <div class="faq-question">
                                    <?php echo htmlspecialchars($faq['question']); ?>
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                                <div class="faq-answer">
                                    <div class="faq-answer-content">
                                        <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php 
                $delay += 0.1;
            endforeach; 
            ?>
        <?php endif; ?>

        <section class="contact-form">
            <h2 class="section-title" style="color: white">Still Have Questions?</h2>
            <p style="text-align: center; margin-bottom: 2rem">
                If you couldn't find the answer to your question, you can reach out to our team directly using the form below.
            </p>
            <div style="text-align: center;">
                <button class="quote-btn">
                    <a href="./contactus.php">
                        <i class="fas fa-envelope"></i> Contact Us 
                    </a>
                </button>
            </div>
        </section>
    </main>

    <?php include('./includes/footer.php'); ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // FAQ accordion functionality
            const faqItems = document.querySelectorAll(".faq-item");

            faqItems.forEach((item) => {
                const question = item.querySelector(".faq-question");
                question.addEventListener("click", () => {
                    const isActive = item.classList.contains("active");
                    
                    // Close all items
                    faqItems.forEach(i => i.classList.remove("active"));
                    
                    // If clicked item wasn't active, open it
                    if (!isActive) {
                        item.classList.add("active");
                    }
                });
            });

            // Form validation
            const form = document.getElementById("questionForm");
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                // Add your form submission logic here
                alert("Thank you for your question. We'll get back to you soon!");
                form.reset();
            });
        });
    </script>
</body>
</html>