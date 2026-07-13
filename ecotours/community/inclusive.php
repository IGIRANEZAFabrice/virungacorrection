<?php require_once '../admin/config/connection.php';
// Load main page
$page = null;
$pq = mysqli_query($conn, "SELECT id, hero_title, hero_subtitle, hero_image, intro_text FROM inclusive_page ORDER BY id DESC LIMIT 1");
if ($pq && mysqli_num_rows($pq) > 0) {
    $page = mysqli_fetch_assoc($pq);
} else {
    mysqli_query($conn, "INSERT INTO inclusive_page (hero_title, hero_subtitle, hero_image, intro_text) VALUES ('Inclusive Community-Based Tourism', 'Empowering Persons with Disabilities in Rural Areas', 'assets/images/inclusive-hero.jpg', '')");
    $page = [
        'id' => mysqli_insert_id($conn),
        'hero_title' => 'Inclusive Community-Based Tourism',
        'hero_subtitle' => 'Empowering Persons with Disabilities in Rural Areas',
        'hero_image' => 'assets/images/inclusive-hero.jpg',
        'intro_text' => ''
    ];
}
$page_id = (int)$page['id'];

// Use separate content_page_id for cards/stats/cta, keeping intro from latest page
$content_page_id = $page_id;
// If the latest page has no cards, fallback to any page that has cards
$has_cards_q = mysqli_query($conn, "SELECT id FROM approach_cards WHERE page_id = $content_page_id LIMIT 1");
if (!$has_cards_q || mysqli_num_rows($has_cards_q) === 0) {
    $fallback_page_q = mysqli_query(
        $conn,
        "SELECT p.id
         FROM inclusive_page p
         INNER JOIN approach_cards c ON c.page_id = p.id
         GROUP BY p.id
         ORDER BY p.id ASC
         LIMIT 1"
    );
    if ($fallback_page_q && mysqli_num_rows($fallback_page_q) > 0) {
        $fallback_page = mysqli_fetch_assoc($fallback_page_q);
        $content_page_id = (int)$fallback_page['id'];
    }
}

// Approach cards
$cards = [];
$cq = mysqli_query($conn, "SELECT id, number, image, title, description FROM approach_cards WHERE page_id = $content_page_id ORDER BY number ASC, id ASC");
if ($cq) { while ($r = mysqli_fetch_assoc($cq)) { $cards[] = $r; } }

// Stats
$stats = [];
$sq = mysqli_query($conn, "SELECT id, stat_number, stat_label FROM inclusive_stats WHERE page_id = $content_page_id ORDER BY id ASC");
if ($sq) { while ($r = mysqli_fetch_assoc($sq)) { $stats[] = $r; } }

// CTA
$cta = null;
$ctaq = mysqli_query($conn, "SELECT id, title, text, button_text, button_link FROM inclusive_cta WHERE page_id = $content_page_id ORDER BY id DESC LIMIT 1");
if ($ctaq && mysqli_num_rows($ctaq) > 0) { $cta = mysqli_fetch_assoc($ctaq); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inclusive Community-Based Tourism</title>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/inclusive.css">
</head>
<body>
     <?php include 'includes/header.php'; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg">
            <?php if (!empty($page['hero_image'])): ?>
                <img src="<?php echo htmlspecialchars($page['hero_image']); ?>" alt="Hero">
            <?php else: ?>
                <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #2a4858 0%, #1a3a48 100%);"></div>
            <?php endif; ?>
        </div>
        
        <div class="hero-content">
            <h1 class="hero-title"><?php echo htmlspecialchars($page['hero_title'] ?? 'Inclusive Community-Based Tourism'); ?></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($page['hero_subtitle'] ?? ''); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Introduction Section -->
        <section class="intro-section fade-in">
            <p class="intro-text"><?php echo $page['intro_text'] ?? ''; ?></p>
        </section>

        <!-- Our Approach Section -->
        <section class="fade-in">
            <h2 class="section-title">Our Approach</h2>
            
            <div class="approach-grid">
                <?php if (count($cards) === 0): ?>
                    <div class="programs-intro">No approach cards yet.</div>
                <?php else: ?>
                    <?php foreach ($cards as $card): ?>
                    <div class="approach-card">
                        <div class="card-image">
                            <?php if (!empty($card['image'])): ?>
                                <img src="<?php echo htmlspecialchars($card['image']); ?>" alt="<?php echo htmlspecialchars($card['title']); ?>">
                            <?php else: ?>
                                <div class="card-image-placeholder"></div>
                            <?php endif; ?>
                            <div class="card-overlay"></div>
                            <div class="card-number"><?php echo (int)$card['number']; ?></div>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title"><?php echo htmlspecialchars($card['title']); ?></h3>
                            <p class="card-description"><?php echo htmlspecialchars($card['description']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- Statistics Section -->
        <section class="stats-section fade-in">
            <div class="stats-grid">
                <?php if (count($stats) === 0): ?>
                    <div class="programs-intro">No stats yet.</div>
                <?php else: foreach ($stats as $s): ?>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo htmlspecialchars($s['stat_number']); ?></span>
                        <span class="stat-label"><?php echo htmlspecialchars($s['stat_label']); ?></span>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </section>



        <!-- Call to Action -->
        <section class="cta-section fade-in">
            <h2 class="cta-title"><?php echo htmlspecialchars($cta['title'] ?? ''); ?></h2>
            <p class="cta-text"><?php echo htmlspecialchars($cta['text'] ?? ''); ?></p>
            <?php if (!empty($cta['button_link'])): ?>
                <a href="<?php echo htmlspecialchars($cta['button_link']); ?>" class="cta-button"><?php echo htmlspecialchars($cta['button_text'] ?? 'Get Involved Today'); ?></a>
            <?php endif; ?>
        </section>
    </div>
     <?php include 'includes/footer.php'; ?>
    <script src="assets/js/community.js"></script>
    <script src="assets/js/community-header-footer.js"></script>
    <script>
        // Intersection Observer for fade-in animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all fade-in elements
        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Parallax effect for hero background
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroBackground = document.querySelector('.hero-bg');
            if (heroBackground) {
                heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        });

        // Card hover effects with enhanced interactivity
        document.querySelectorAll('.approach-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-15px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Counter animation for stats
        function animateCounter(element, target, duration = 2000) {
            const start = 0;
            const increment = target / (duration / 16);
            let current = start;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                
                const displayValue = target.toString().includes('%') 
                    ? Math.round(current) + '%' 
                    : target.toString().includes('+')
                    ? Math.round(current) + '+'
                    : Math.round(current);
                    
                element.textContent = displayValue;
            }, 16);
        }

        // Trigger counter animation when stats section is visible
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const numbers = entry.target.querySelectorAll('.stat-number');
                    numbers.forEach(num => {
                        const target = parseInt(num.textContent);
                        animateCounter(num, target);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }

        // Smooth scrolling for CTA button
        document.querySelector('.cta-button')?.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
</body>
</html>
<?php mysqli_close($conn); ?>