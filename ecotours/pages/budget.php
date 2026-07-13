<?php
require_once 'handlers/itenary_handler.php';
$budgetTours = getToursByTitleKeyword('Budget', 3);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget-Friendly Tours around the Virunga Mountains | Virunga Ecotours</title>
    <meta name="description" content="Explore the Virunga Mountains on a budget. Authentic experiences, affordable stays, and expert local guides for the value-conscious traveler.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../images/logos/icon.png">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/budget.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Include Header -->
    <?php include "includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title">Budget-Friendly Tours around the Virunga Mountains</h1>
                <p class="hero-subtitle">Authentic adventures that don't break the bank—locally guided, community-focused, and deeply immersive</p>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="budget-container">

        <!-- Introduction Section -->
        <section class="intro-section">
            <h2 class="section-title">Authentic Travel for Every Budget</h2>
            <p>Our budget-friendly tours are designed for the conscious traveler who values <strong>authenticity over opulence</strong>. We prioritize local guesthouses, public-private transport, and community-led activities to keep costs low while ensuring your money goes directly to the people who protect these landscapes.</p>
        </section>

        <!-- Featured Budget Tours -->
        <section class="content-section budget-tours">
            <h2 class="section-title">Explore Our Budget Adventures</h2>
            <div class="tours-cards" id="budgetToursContainer">
                <?php if (!empty($budgetTours)): ?>
                    <?php foreach ($budgetTours as $index => $tour): ?>
                        <div class="tour-card <?php echo $index >= 3 ? 'hidden-card' : ''; ?>">
                            <div class="tour-card-image">
                                <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" />
                                <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($tour['category'])); ?></div>
                                <div class="tour-offer">AVAILABLE</div>
                            </div>
                            <div class="tour-card-content">
                                <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                                <div class="tour-duration"><?php echo $tour['days_count']; ?> DAY<?php echo $tour['days_count'] > 1 ? 'S' : ''; ?></div>
                                <p><?php echo htmlspecialchars(substr($tour['short_description'], 0, 150)) . '...'; ?></p>
                                <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="read-more-btn">BOOK NOW</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-tours">Budget adventures coming soon.</p>
                <?php endif; ?>
            </div>
            <?php if (count($budgetTours) > 3): ?>
                <div class="view-more-container">
                    <button id="viewMoreBudgetBtn" class="view-more-btn">View More</button>
                </div>
            <?php endif; ?>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const viewMoreBtn = document.getElementById('viewMoreBudgetBtn');
                const cards = document.querySelectorAll('#budgetToursContainer .tour-card');
                let currentlyShown = 3;

                if (viewMoreBtn) {
                    viewMoreBtn.addEventListener('click', function() {
                        let newlyShown = 0;
                        for (let i = currentlyShown; i < cards.length && newlyShown < 3; i++) {
                            cards[i].classList.remove('hidden-card');
                            newlyShown++;
                        }
                        currentlyShown += newlyShown;
                        if (currentlyShown >= cards.length) {
                            viewMoreBtn.style.display = 'none';
                        }
                    });
                }
            });
        </script>

        <!-- Value Proposition -->
        <section class="content-section">
            <div class="value-proposition">
                <h2 class="section-title">Why Choose Our Budget Tours?</h2>
                <ul class="feature-list">
                    <li><strong>Community Stays:</strong> Stay in vetted local guesthouses and homestays that offer warmth and genuine connection.</li>
                    <li><strong>Local Transport:</strong> Experience the region like a local with safe, efficient, and cost-effective logistics.</li>
                    <li><strong>Expert Local Guides:</strong> Learn from the people who know the mountains best, with stories you won't find in any guidebook.</li>
                    <li><strong>Direct Impact:</strong> Your trip directly supports local cooperatives, small businesses, and conservation efforts.</li>
                </ul>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <h3><i class="fas fa-wallet"></i> Start Your Adventure Today</h3>
            <p>Ready to explore the Virungas without the high price tag? Tell us your interests and we'll help you find the perfect budget-friendly route.</p>
            
            <div class="section-divider"></div>
            
            <p><strong>Contact us to customize your budget trip.</strong></p>
        </section>
    </div>

    <!-- Include Footer -->
    <?php include "includes/footer.php" ?>
    
    <!-- JavaScript -->
    <script src="../js/header.js" defer></script>
</body>
</html>
