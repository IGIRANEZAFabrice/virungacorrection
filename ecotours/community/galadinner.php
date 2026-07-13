<?php
require_once '../admin/config/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gala Local Dinner & Culture Experience in Musanze</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/gala.css">
</head>
<body>
    <?php
    include 'includes/header.php';

    // Get gala content from database
    $hero_query = "SELECT * FROM gala_hero LIMIT 1";
    $hero_result = mysqli_query($conn, $hero_query);
    $hero = mysqli_fetch_assoc($hero_result);

    $activities_query = "SELECT * FROM gala_activities ORDER BY display_order ASC";
    $activities_result = mysqli_query($conn, $activities_query);

    $importance_query = "SELECT * FROM gala_importance ORDER BY display_order ASC";
    $importance_result = mysqli_query($conn, $importance_query);
    ?>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hero['hero_title'] ?? 'Gala Local Dinner & Culture Experience'); ?></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($hero['hero_subtitle'] ?? 'in Musanze'); ?></p>
            <p class="hero-description"><?php echo htmlspecialchars($hero['hero_description'] ?? ''); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        <!-- Introduction -->
        <section class="section">
            <p class="intro-text"><?php echo htmlspecialchars($hero['intro_text'] ?? ''); ?></p>
        </section>

        <!-- Afternoon Activities -->
        <section class="section">
            <h2 class="section-title"><?php echo htmlspecialchars($hero['activities_title'] ?? 'Afternoon Cultural Activities'); ?></h2>
            <p class="intro-text"><?php echo htmlspecialchars($hero['activities_intro'] ?? ''); ?></p>

            <div class="activities-grid">
                <?php
                if ($activities_result && mysqli_num_rows($activities_result) > 0) {
                    while ($activity = mysqli_fetch_assoc($activities_result)) {
                        $image_url = $activity['activity_image'] ?? '../images/gala/default.jpg';
                        echo '<div class="activity-card">';
                        echo '<div class="activity-image" style="background-image: url(\'' . htmlspecialchars($image_url) . '\');"></div>';
                        echo '<div class="activity-content">';
                        echo '<h3 class="activity-title">' . htmlspecialchars($activity['activity_title']) . '</h3>';
                        echo '<p class="activity-description">' . htmlspecialchars($activity['activity_description']) . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </section>

        <!-- Gala Dinner Experience -->
        <section class="gala-dinner-section">
            <div class="container">
                <h2 class="section-title" style="color: var(--primary-green);"><?php echo htmlspecialchars($hero['dinner_title'] ?? 'The Gala Dinner Experience'); ?></h2>
                <div class="dinner-content">
                    <div class="dinner-text">
                        <?php
                        if (!empty($hero['dinner_text'])) {
                            echo '<p style="font-size: 0.95rem; line-height: 1.8; color: var(--text-medium);">' . nl2br(htmlspecialchars($hero['dinner_text'])) . '</p>';
                        }
                        ?>
                    </div>
                    <div class="dinner-image"></div>
                </div>
            </div>
        </section>

        <!-- Why This Exchange Matters -->
        <section class="section">
            <h2 class="section-title">Why This Exchange Matters</h2>
            <p class="intro-text">The Gala Dinner & Culture Experience is designed as a genuine exchange, not a staged show. For visitors, it is a chance to step into the rhythm of local life and discover the beauty of Rwanda through food and connection.</p>

            <div class="importance-grid">
                <?php
                if ($importance_result && mysqli_num_rows($importance_result) > 0) {
                    while ($card = mysqli_fetch_assoc($importance_result)) {
                        echo '<div class="importance-card">';
                        echo '<div class="importance-icon">';
                        echo '<i class="' . htmlspecialchars($card['importance_icon']) . '"></i>';
                        echo '</div>';
                        echo '<h3 class="importance-title">' . htmlspecialchars($card['importance_title']) . '</h3>';
                        echo '<p>' . htmlspecialchars($card['importance_description']) . '</p>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </section>
    </div>

    <!-- Final Section -->
    <section class="final-section">
        <div class="container">
            <h2><?php echo htmlspecialchars($hero['final_title'] ?? 'More Than a Dinner—A Bridge of Friendship'); ?></h2>
            <?php
            if (!empty($hero['final_text'])) {
                echo '<p class="final-text">' . nl2br(htmlspecialchars($hero['final_text'])) . '</p>';
            }
            ?>
            <p class="final-text" style="margin-top: var(--spacing-lg); font-style: italic;">This is the true essence of travel with Virunga Ecotours: where every bite tells a story, and every story builds a bridge.</p>
        </div>
    </section>
 <?php include 'includes/footer.php'; ?>
    <script>
        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all activity cards and importance cards
        document.querySelectorAll('.activity-card, .importance-card').forEach(card => {
            observer.observe(card);
        });

        // Smooth scroll behavior for any internal links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
</html>