<?php require_once '../admin/config/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beekeeping Experiences around the Virunga Massif | Virunga Ecotours</title>
    <meta name="description" content="Discover community-led beekeeping around the Virunga Massif: hive visits, honey harvesting, tastings, and cultural exchange while supporting conservation." />

    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/beekeeping.css" />
    <!-- Reuse kids card design for tours grid -->
    <link rel="stylesheet" href="../community/assets/css/kids.css" />
    
</head>
<body>
    <?php include "./includes/header.php"; ?>

    <section class="bk-hero">
        <div class="container">
            <h1>Beekeeping Experiences around the Virunga Massif</h1>
            <p>Sweet Traditions, Strong Communities – Discover Beekeeping in the Heart of the Virunga Massif.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Overview</h2>
            <p class="lead">Beekeeping is one of the oldest and most valued traditions practiced by communities living around the Virunga Massif. Through Virunga Ecotours’ community-based tourism program, visitors are invited to discover this fascinating agro-tour activity, where conservation, livelihood, and cultural heritage meet. By joining our beekeeping tours, travelers gain unique insights into how honey production sustains families while protecting the fragile ecosystems surrounding the Volcanoes National Park and beyond.</p>
        </div>
    </section>

    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Why Beekeeping around the Massif?</h2>
            <p class="lead">The Virunga Massif offers ideal conditions for beekeeping thanks to its rich biodiversity, lush forests, and abundance of flowering plants. Beekeeping here is more than honey—it is a vital source of income for local families, an alternative to environmentally harmful practices, and a cultural symbol of resilience and harmony with nature. Visitors not only learn the art of honey harvesting but also understand how bees contribute to crop pollination, forest health, and community well-being.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Activities through Agrotourism</h2>
            <div class="bk-highlights">
                <div class="bk-card">
                    <h3>Hands-on Experiences</h3>
                    <ul class="bk-list">
                        <li>Visiting traditional and modern beehives with trained local beekeepers</li>
                        <li>Learning about hive construction and bee colony management</li>
                        <li>Watching live honey harvesting demonstrations (with protective gear)</li>
                        <li>Tasting fresh organic honey and local honey-based products</li>
                        <li>Engaging with community cooperatives and stories</li>
                        <li>Understanding how beekeeping supports conservation</li>
                    </ul>
                </div>
                <div class="bk-card">
                    <h3>What to Expect</h3>
                    <p class="lead" style="margin:0; font-size: 0.98rem;">Travelers can expect a safe, engaging, and educational half‑day experience combining learning with cultural exchange. Interact with local beekeepers, enjoy scenic views of the Virunga volcanoes, and leave with a deeper appreciation of rural livelihoods. Every visit directly supports families and encourages sustainable practices.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Beekeeping Experience Table</h2>
            <div style="overflow-x:auto; margin-top: 1.25rem;">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Duration</th>
                            <th>Highlights</th>
                            <th>What You Gain</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Hive Visit & Introduction</td>
                            <td>1 hour</td>
                            <td>Traditional & modern hive designs</td>
                            <td>Learn basics of colony care</td>
                        </tr>
                        <tr>
                            <td>Honey Harvesting Demo</td>
                            <td>1.5 hours</td>
                            <td>Protective gear, live harvesting</td>
                            <td>Experience the process of extraction</td>
                        </tr>
                        <tr>
                            <td>Honey Tasting</td>
                            <td>30 minutes</td>
                            <td>Fresh organic honey, local snacks</td>
                            <td>Taste unique flavors of Virunga honey</td>
                        </tr>
                        <tr>
                            <td>Community Exchange</td>
                            <td>1 hour</td>
                            <td>Cooperative visit, storytelling</td>
                            <td>Cultural insights, support local families</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Related Activities: Beekeeping -->
    <section class="kids-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Beekeeping‑Related Activities</h2>
                <p>Hands‑on community activities featuring beekeeping visits, honey tasting, and apiary learning</p>
            </div>

            <div class="tours-activities-grid">
                <?php
                // Fetch only activities that mention beekeeping related keywords
                $activities_query = "SELECT * FROM community_activities 
                                     WHERE LOWER(title) LIKE '%beekeeping%'
                                        OR LOWER(title) LIKE '%apiary%'
                                        OR LOWER(title) LIKE '%hive%'
                                     ORDER BY display_order ASC";
                $activities_result = isset($conn) ? mysqli_query($conn, $activities_query) : false;
                $has_content = false;

                if ($activities_result && mysqli_num_rows($activities_result) > 0):
                    $has_content = true;
                    while ($activity = mysqli_fetch_assoc($activities_result)):
                ?>
                <div class="tour-activity-card activity-card">
                    <div class="card-image">
                        <img src="../community/uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>" 
                             alt="<?php echo htmlspecialchars($activity['title']); ?>" loading="lazy">
                        <div class="card-badge activity-badge">
                            <i class="fas fa-seedling"></i>
                            Activity
                        </div>
                        <?php if (!empty($activity['duration'])): ?>
                        <div class="card-duration">
                            <i class="fas fa-clock"></i>
                            <?php echo htmlspecialchars($activity['duration']); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($activity['title']); ?></h3>
                        <p class="card-description">
                            <?php 
                            $summary = substr(strip_tags($activity['content']), 0, 120);
                            echo htmlspecialchars($summary) . (strlen(strip_tags($activity['content'])) > 120 ? '...' : '');
                            ?>
                        </p>
                        <div class="card-features">
                            <span class="feature-tag"><i class="fas fa-seedling"></i> Agro</span>
                            <span class="feature-tag"><i class="fas fa-leaf"></i> Community</span>
                        </div>
                        <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="card-btn">
                            <i class="fas fa-arrow-right"></i>
                            Learn More
                        </a>
                    </div>
                </div>
                <?php endwhile; endif; ?>

                <?php if (!$has_content): ?>
                    <div class="no-content-message">
                        <div class="no-content-icon"><i class="fas fa-search"></i></div>
                        <h3>No Beekeeping Activities Found</h3>
                        <p>Check back soon or contact us to curate a beekeeping activity with our community partners.</p>
                        <a href="contactus.php" class="contact-btn"><i class="fas fa-envelope"></i> Contact Us</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php"; ?>
    <script src="../js/header.js" defer></script>
</body>
</html>
