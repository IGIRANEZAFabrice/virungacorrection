<?php require_once '../admin/config/connection.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agro Tours around the Virunga Massif | Virunga Ecotours</title>
    <meta name="description" content="Experience agro tours with Virunga Ecotours: coffee & tea, banana & sorghum, community farms, dairy & livestock, and farm‑to‑table meals." />

    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/agrotours.css" />
    <link rel="stylesheet" href="../community/assets/css/kids.css" />
</head>
<body>
    <?php include './includes/header.php'; ?>

    <!-- Hero -->
    <section class="agro-hero">
        <div class="container">
            <h1>Agro Tours around the Virunga Massif</h1>
            <p>From Soil to Soul: Discover Virunga through its Fields.</p>
        </div>
    </section>

    <!-- Overview -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Overview</h2>
            <p class="lead">Agro tours through Virunga Ecotours open a unique window into the daily rhythms of rural life surrounding the Virunga Massif. Unlike traditional tourism, agro tourism allows travelers to step into fertile farmlands, coffee and tea plantations, and small-scale community gardens where the heartbeat of local culture begins. It is a journey that blends discovery, participation, and storytelling—inviting guests to experience how agriculture sustains livelihoods while offering authentic interaction with farming communities.</p>
        </div>
    </section>

    <!-- Why Agro Tours -->
    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Why Agro Tours around the Virunga Massif</h2>
            <div class="agro-highlights">
                <div class="card">
                    <h3>Heritage & Identity</h3>
                    <p class="lead" style="margin:0; font-size:0.98rem;">The Virunga Massif's fertile volcanic soils nurture crops and traditions. Agriculture here is more than an economic activity—it is heritage, resilience, and identity.</p>
                </div>
                <div class="card">
                    <h3>Why Choose Agro Tours</h3>
                    <ul class="list">
                        <li>Understand the role of farming in shaping local lifestyles</li>
                        <li>Contribute directly to community well‑being via farmer‑led initiatives</li>
                        <li>Create cross‑cultural connections through shared work and meals</li>
                        <li>Enjoy immersive, hands‑on experiences beyond wildlife encounters</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Activities You Do -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Activities You Do through Agro Tours</h2>
            <div class="agro-highlights">
                <div class="card">
                    <h3>Coffee & Tea Experiences</h3>
                    <ul class="list">
                        <li>Walk through plantations; pick coffee or pluck tea</li>
                        <li>See roasting or drying processes</li>
                        <li>Enjoy tasting the final brew</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Banana & Sorghum Traditions</h3>
                    <ul class="list">
                        <li>Learn banana beer and sorghum processing</li>
                        <li>From cultivation to fermentation with tastings</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Community Farm Visits</h3>
                    <ul class="list">
                        <li>Participate in planting, weeding, or harvesting</li>
                        <li>Explore traditional and modern techniques</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Dairy & Livestock Moments</h3>
                    <ul class="list">
                        <li>Join morning milking routines</li>
                        <li>Try traditional churning; hear cattle culture stories</li>
                    </ul>
                </div>
                <div class="card">
                    <h3>Farm‑to‑Table Meals</h3>
                    <ul class="list">
                        <li>Share freshly prepared meals from harvested produce</li>
                        <li>Connect soil‑to‑plate in a personal way</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Expectations -->
    <section class="section" style="background: var(--neutral-light);">
        <div class="container">
            <h2 class="section-title">Expectations for Visitors</h2>
            <p class="lead">Expect a blend of learning, hands‑on participation, and cultural exchange. This is not a staged performance but real‑life interaction—mud on your boots, warmth in local smiles, and stories that remain long after the journey ends. Visitors leave with a deeper appreciation of how agriculture underpins community resilience in the Virunga region.</p>
        </div>
    </section>

    <!-- Agro Tour Activities & Highlights Table -->
    <section class="section">
        <div class="container">
            <h2 class="section-title">Agro Tour Activities & Highlights</h2>
            <div style="overflow-x:auto; margin-top:1.25rem;">
                <table class="agro-table">
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Experience Highlights</th>
                            <th>Cultural Connection</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Coffee & Tea Tours</td>
                            <td>Harvesting, roasting, tasting</td>
                            <td>Local rituals around drinks</td>
                        </tr>
                        <tr>
                            <td>Banana & Sorghum Brewing</td>
                            <td>Brewing banana wine, sorghum beer</td>
                            <td>Traditional celebration drinks</td>
                        </tr>
                        <tr>
                            <td>Community Farm Work</td>
                            <td>Planting, weeding, harvesting</td>
                            <td>Shared labor and storytelling</td>
                        </tr>
                        <tr>
                            <td>Dairy & Livestock</td>
                            <td>Milking, churning, cattle culture</td>
                            <td>Role of cattle in traditions</td>
                        </tr>
                        <tr>
                            <td>Farm‑to‑Table Meals</td>
                            <td>Freshly cooked meals from the farm</td>
                            <td>Sharing food with families</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Related Activities: Agro Tourism -->
    <section class="kids-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Agro‑Tourism Activities</h2>
                <p>Hands‑on community activities around coffee, tea, banana, sorghum, farms and livestock</p>
            </div>

            <div class="tours-activities-grid">
                <?php
                // Fetch beekeeping‑style activities: agro keywords
                $activities_query = "SELECT * FROM community_activities 
                                     WHERE LOWER(title) LIKE '%coffee%'
                                        OR LOWER(title) LIKE '%tea%'
                                        OR LOWER(title) LIKE '%garden%'
                                        OR LOWER(title) LIKE '%farm%'
                                        OR LOWER(title) LIKE '%dairy%'
                                        OR LOWER(title) LIKE '%livestock%'
                                        OR LOWER(title) LIKE '%farm-to-table%'
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
                        <h3>No Agro‑Tourism Activities Found</h3>
                        <p>Check back soon or contact us to curate an agro‑tour with our community partners.</p>
                        <a href="contactus.php" class="contact-btn"><i class="fas fa-envelope"></i> Contact Us</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include './includes/footer.php'; ?>
    <script src="../js/header.js" defer></script>
</body>
</html>
