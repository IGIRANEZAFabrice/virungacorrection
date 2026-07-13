<?php require_once '../admin/config/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee Tours in the Virunga Massif | Virunga Ecotours</title>
    <meta name="description" content="Experience community-led coffee tours around the Virunga Massif: farm walks, harvesting, processing, roasting & tasting, and cultural exchange while supporting local livelihoods." />

    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/coffee.css" />
    <!-- Reuse kids card design for tours grid -->
    <link rel="stylesheet" href="../community/assets/css/kids.css" />
</head>
<body>
    <?php include "./includes/header.php"; ?>

    <section class="cf-hero">
        <div class="container">
            <h1>Coffee Tours in the Virunga Massif</h1>
            <p>From Volcanic Soil to Your Soul – Discover the story of coffee with local communities.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Overview</h2>
            <p class="lead">Coffee is more than just a drink—it is a story of land, people, and tradition. Around the Virunga Massif, local farming communities nurture some of the richest volcanic soils on earth, producing beans that are aromatic, bold, and full of character. With Virunga Ecotours, visitors can trace the entire journey from farm to cup, experiencing firsthand the art and culture of coffee.</p>
            <p class="lead">Our coffee tours are community-based experiences, where guests walk through smallholder farms, learn traditional cultivation methods, join in harvesting and roasting, and finally share a freshly brewed cup with local farmers. It is an immersive way to connect with the land and its people, while supporting livelihoods and celebrating one of the region’s finest agricultural treasures.</p>
        </div>
    </section>

    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Why Coffee Tours Matter</h2>
            <ul class="cf-list">
                <li>Support local communities through direct participation in their craft</li>
                <li>Gain an authentic understanding of how coffee shapes culture and community identity</li>
                <li>Discover the connection between volcanic soil, high altitude, and exceptional coffee flavor</li>
                <li>Strengthen the local economy by encouraging agrotourism as a sustainable livelihood option</li>
            </ul>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h2 class="section-title">Virunga Ecotours Engagement</h2>
            <ul class="cf-list">
                <li>Organize guided farm visits and workshops led by local farmers</li>
                <li>Connect visitors with authentic community stories and cultural practices</li>
                <li>Facilitate participation in coffee conferences and exhibitions, where guests can meet local producers, taste different varieties, and see how the Virunga Massif is positioning itself in international markets</li>
                <li>Ensure that a fair share of tourism benefits flow directly back into the farming families</li>
            </ul>
            <p class="lead" style="margin-top:1rem;">By doing so, we strengthen the bond between travelers and communities, making every coffee experience not only memorable but also impactful.</p>
        </div>
    </section>

    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Why Conferences & Exhibitions</h2>
            <p class="lead">Coffee is not just about farming; it is about sharing knowledge, building networks, and promoting the region on a global stage. Conferences and exhibitions allow farmers to showcase their beans and techniques, visitors to learn about innovations in coffee processing, and international buyers to connect with producers—bringing the Virunga Massif to the world’s attention.</p>
             <p class="lead"><em>“Virunga Coffee: From Volcanic Soil to Your Soul.”</em></p>
        </div>
    </section>

    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Coffee Tour Highlights</h2>
            <div style="overflow-x:auto; margin-top: 1.25rem;">
                <table class="cf-table">
                    <thead>
                        <tr>
                            <th>Experience</th>
                            <th>What You Do</th>
                            <th>Why It’s Special</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Farm Walk</td>
                            <td>Visit coffee plantations, meet farmers, learn about cultivation</td>
                            <td>Understand how volcanic soil shapes unique flavors</td>
                        </tr>
                        <tr>
                            <td>Harvesting</td>
                            <td>Pick ripe cherries alongside farmers</td>
                            <td>Hands-on experience of the coffee cycle</td>
                        </tr>
                        <tr>
                            <td>Processing Workshop</td>
                            <td>Try washing, drying, and hulling coffee beans</td>
                            <td>See traditional vs. modern techniques</td>
                        </tr>
                        <tr>
                            <td>Roasting &amp; Tasting</td>
                            <td>Roast beans on open fire and taste fresh brews</td>
                            <td>Savor flavors at their purest form</td>
                        </tr>
                        <tr>
                            <td>Community Exchange</td>
                            <td>Share stories, songs, and traditions with farmers</td>
                            <td>Deep cultural immersion</td>
                        </tr>
                        <tr>
                            <td>Conferences &amp; Exhibitions</td>
                            <td>Attend local coffee events with Virunga Ecotours</td>
                            <td>Connect to the global coffee movement</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Coffee Tours & Activities -->
    <section class="kids-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Coffee Tours & Activities</h2>
                <p>Discover guided coffee tours and hands‑on community activities: farm walks, processing workshops, roasting, and tasting.</p>
            </div>

            <div class="tours-activities-grid">
                <?php
                // Fetch activities that mention coffee related keywords
                $activities_query = "SELECT * FROM community_activities 
                                     WHERE LOWER(title) LIKE '%coffee%' 
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
                            <i class="fas fa-mug-hot"></i>
                            Coffee
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
                            <span class="feature-tag"><i class="fas fa-mug-hot"></i> Coffee</span>
                        </div>
                        <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="card-btn">
                            <i class="fas fa-arrow-right"></i>
                            Learn More
                        </a>
                    </div>
                </div>
                <?php endwhile; endif; ?>

                <?php
                // Fetch coffee-related tours (itineraries)
                $tours_query = "SELECT * FROM tours 
                                 WHERE LOWER(title) LIKE '%coffee%'
                                 ORDER BY created_at DESC";
                $tours_result = isset($conn) ? mysqli_query($conn, $tours_query) : false;

                if ($tours_result && mysqli_num_rows($tours_result) > 0):
                    $has_content = true;
                    while ($tour = mysqli_fetch_assoc($tours_result)):
                ?>
                <div class="tour-activity-card tour-card">
                    <div class="card-image">
                        <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($tour['title']); ?>" loading="lazy">
                        <div class="card-badge tour-badge">
                            <i class="fas fa-route"></i>
                            Tour
                        </div>
                        <?php if (!empty($tour['days_count'])): ?>
                        <div class="card-duration">
                            <i class="fas fa-calendar"></i>
                            <?php echo (int)$tour['days_count']; ?> Day<?php echo ((int)$tour['days_count'] > 1 ? 's' : ''); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                        <p class="card-description">
                            <?php 
                            $summary = substr(strip_tags($tour['short_description']), 0, 120);
                            echo htmlspecialchars($summary) . (strlen(strip_tags($tour['short_description'])) > 120 ? '...' : '');
                            ?>
                        </p>
                        <div class="card-features">
                            <span class="feature-tag"><i class="fas fa-mug-hot"></i> Coffee</span>
                            <span class="feature-tag"><i class="fas fa-seedling"></i> Agro</span>
                        </div>
                        <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="card-btn">
                            <i class="fas fa-arrow-right"></i>
                            View Details
                        </a>
                    </div>
                </div>
                <?php endwhile; endif; ?>

                <?php if (!$has_content): ?>
                    <div class="no-content-message">
                        <div class="no-content-icon"><i class="fas fa-search"></i></div>
                        <h3>No Coffee Tours or Activities Found</h3>
                        <p>Check back soon or contact us to curate a coffee experience with our community partners.</p>
                        <a href="contactus.php" class="contact-btn"><i class="fas fa-envelope"></i> Contact Us</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php"; ?>
    <script src="../js/header.js"></script>
</body>
</html>
