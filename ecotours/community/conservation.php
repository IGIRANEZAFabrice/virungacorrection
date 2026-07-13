<?php
require_once '../admin/config/connection.php';

$page_title = "Conservation Tourism Programs - Virunga Ecotours";
$page_description = "Discover how Virunga Ecotours integrates community-based activities with conservation tourism to protect biodiversity while empowering local communities in the Virunga Massif.";

// Get conservation programs count
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE category = 'Conservation' AND status IN ('active', 'completed')";
$count_result = mysqli_query($conn, $count_query);
$total_conservation_programs = mysqli_fetch_assoc($count_result)['total'];

// Get conservation programs
$programs_query = "SELECT * FROM community_programs WHERE category = 'Conservation' AND status IN ('active', 'completed') ORDER BY featured DESC, created_at DESC";
$programs_result = mysqli_query($conn, $programs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">

    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/conservation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="conservation-page">
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="assets/images/IMG_9320.jpg" alt="Conservation Tourism Programs" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Conservation</span>
                </nav>
                <h1>Conservation Tourism Programs</h1>
                <p>Protecting biodiversity through community empowerment and sustainable tourism that creates lasting conservation impact across the Virunga Massif.</p>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text">
                    <h2>Integration of Community-Based Activities with Conservation Tourism</h2>
                    <p>Virunga Ecotours employs a holistic approach to tourism that connects biodiversity conservation with community empowerment. The Virunga Massif—shared by Rwanda, Uganda, and the Democratic Republic of Congo—is a critical ecological region known for its endangered mountain gorillas, unique volcanic ecosystems, and rich cultural landscapes. However, the sustainability of conservation efforts depends not only on protecting the parks but also on ensuring that local communities directly benefit from tourism.</p>

                    <p>By integrating community-based tourism (CBT) with conservation tourism, Virunga Ecotours bridges the gap between ecological preservation and socio-economic development.</p>
                </div>

                <div class="intro-highlights">
                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <h3>Benefit-Sharing</h3>
                        <p>Tourism revenue supports local livelihoods and builds conservation ownership</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h3>Participatory Development</h3>
                        <p>Communities as active partners in decision-making and service delivery</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Environmental Stewardship</h3>
                        <p>Tourism creates tangible incentives for habitat protection</p>
                    </div>

                    <div class="highlight-item">
                        <div class="highlight-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <h3>Product Diversification</h3>
                        <p>Beyond gorilla trekking to cultural immersions and voluntourism</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Principles Section -->
    <section class="principles-section">
        <div class="container">
            <h2 class="section-title">Four Academic Principles</h2>
            <p class="section-description">Our conservation tourism model operates under established academic principles that ensure sustainable development and effective conservation outcomes.</p>

            <div class="principles-grid">
                <div class="principle-card">
                    
                    <div class="principle-content">
                        <h3>Benefit-Sharing</h3>
                        <p>Tourism revenue is partly redirected to support local livelihoods, reducing dependence on natural resource exploitation and building local ownership of conservation.</p>
                        <ul class="principle-benefits">
                            <li>Direct income for local communities</li>
                            <li>Reduced resource exploitation pressure</li>
                            <li>Local ownership of conservation efforts</li>
                            <li>Sustainable livelihood alternatives</li>
                        </ul>
                    </div>
                </div>

                <div class="principle-card">
                    
                    <div class="principle-content">
                        <h3>Participatory Development</h3>
                        <p>Local communities are not passive beneficiaries but active partners in decision-making and service delivery. This includes roles in guiding, homestays, handicraft cooperatives, and cultural performances.</p>
                        <ul class="principle-benefits">
                            <li>Community-led decision making</li>
                            <li>Active service delivery roles</li>
                            <li>Capacity building programs</li>
                            <li>Leadership development</li>
                        </ul>
                    </div>
                </div>

                <div class="principle-card">
                   
                    <div class="principle-content">
                        <h3>Environmental Stewardship through Tourism</h3>
                        <p>Visitors are sensitized to conservation values during their experiences, while communities receive tangible incentives to protect habitats.</p>
                        <ul class="principle-benefits">
                            <li>Visitor conservation education</li>
                            <li>Habitat protection incentives</li>
                            <li>Community ranger programs</li>
                            <li>Ecosystem monitoring</li>
                        </ul>
                    </div>
                </div>

                <div class="principle-card">
                  
                    <div class="principle-content">
                        <h3>Diversification of Tourism Products</h3>
                        <p>Activities extend beyond gorilla trekking to include cultural immersions, agricultural tours, homestays, and voluntourism. This diversifies income streams and reduces ecological pressure on flagship species.</p>
                        <ul class="principle-benefits">
                            <li>Multiple income streams</li>
                            <li>Reduced ecological pressure</li>
                            <li>Cultural preservation</li>
                            <li>Enhanced visitor experiences</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Integration Table Section -->
    <section class="integration-table-section">
        <div class="container">
            <h2 class="section-title">Integration of Community-Based Activities with Conservation Tourism</h2>
            <p class="section-description">A comprehensive overview of how community-based activities are integrated with conservation tourism in the Virunga Massif.</p>

            <div class="table-container">
                <table class="integration-table">
                    <thead>
                        <tr>
                            <th>Community-Based Activity</th>
                            <th>Integration with Conservation Tourism</th>
                            <th>Expected Impact</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-home"></i>
                                    <strong>Homestays & Local Accommodation</strong>
                                </div>
                            </td>
                            <td>Provides alternative lodging that complements park visits, reducing dependency on large-scale hotels.</td>
                            <td>Direct income for households, cultural exchange, reduced leakage of tourism revenue.</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-hands"></i>
                                    <strong>Handicraft Cooperatives</strong>
                                </div>
                                <small>(weaving, woodcarving, basketry)</small>
                            </td>
                            <td>Souvenirs are marketed to tourists visiting the parks.</td>
                            <td>Economic empowerment, preservation of traditional knowledge, reduced reliance on forest resources.</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-seedling"></i>
                                    <strong>Agritourism</strong>
                                </div>
                                <small>(coffee, banana beer, honey tours)</small>
                            </td>
                            <td>Connects agricultural livelihoods with eco-tourism, offered as pre/post-park visit experiences.</td>
                            <td>Income diversification, food security, greater value chain participation.</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-heart"></i>
                                    <strong>Voluntourism</strong>
                                </div>
                                <small>(school visits, women's groups, tree planting)</small>
                            </td>
                            <td>Short-term volunteering aligns visitors with conservation values and community needs.</td>
                            <td>Strengthened conservation awareness, capacity building, improved social infrastructure.</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-music"></i>
                                    <strong>Cultural Experiences</strong>
                                </div>
                                <small>(dance, music, storytelling)</small>
                            </td>
                            <td>Offered alongside wildlife excursions to highlight cultural identity as part of conservation.</td>
                            <td>Cultural pride, tourism diversification, enhanced visitor satisfaction.</td>
                        </tr>
                        <tr>
                            <td class="activity-cell">
                                <div class="activity-header">
                                    <i class="fas fa-shield-alt"></i>
                                    <strong>Community Rangers / Eco-guards</strong>
                                </div>
                            </td>
                            <td>Local youths involved in guiding and monitoring reduce illegal activities.</td>
                            <td>Improved conservation outcomes, community ownership of protection efforts.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Academic Perspective Section -->
    <section class="academic-section">
        <div class="container">
            <div class="academic-content">
                <div class="academic-text">
                    <h2>Academic Perspective</h2>
                    <p>This integration reflects sustainable tourism theory, where tourism is conceptualized not merely as an economic driver but as a tool for conservation and community resilience. It draws from <strong>Elinor Ostrom's principles of common-pool resource management</strong>, which emphasize that communities protect natural resources more effectively when they gain tangible benefits.</p>

                    <p>Additionally, it aligns with the <strong>triple bottom line of sustainable development</strong>: economic viability, environmental protection, and socio-cultural enrichment.</p>
                </div>

                <div class="academic-highlights">
                    <div class="academic-item">
                        
                        <h3>Sustainable Tourism Theory</h3>
                        <p>Tourism as a tool for conservation and community resilience</p>
                    </div>

                    <div class="academic-item">
                        
                        <h3>Ostrom's Principles</h3>
                        <p>Common-pool resource management through community benefits</p>
                    </div>

                    <div class="academic-item">
                        
                        <h3>Triple Bottom Line</h3>
                        <p>Economic, environmental, and socio-cultural sustainability</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="programs-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Conservation Programs</h2>
                <p class="section-description">Explore our comprehensive conservation programs that integrate community development with biodiversity protection.</p>
            </div>

            <div class="programs-grid" id="programs-grid">
                <?php if (mysqli_num_rows($programs_result) > 0): ?>
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <div class="program-card <?php echo $program['featured'] ? 'featured' : ''; ?>">
                            <div class="program-image">
                                <?php if (!empty($program['image'])): ?>
                                    <img src="assets/images/programs/<?php echo htmlspecialchars($program['image']); ?>" alt="<?php echo htmlspecialchars($program['title']); ?>" loading="lazy">
                                <?php else: ?>
                                    <img src="assets/images/default-program.jpg" alt="No Image" loading="lazy">
                                <?php endif; ?>
                            </div>
                            <div class="program-content">
                                <h3 class="program-title"><?php echo htmlspecialchars($program['title']); ?></h3>
                                <p class="program-description"><?php echo htmlspecialchars($program['short_description']); ?></p>
                                <div class="program-meta">
                                    <span class="program-status <?php echo $program['status']; ?>">
                                        <?php echo ucfirst($program['status']); ?>
                                    </span>
                                    <span class="program-date">
                                        <?php echo date('M d, Y', strtotime($program['created_at'])); ?>
                                    </span>
                                </div>
                                <?php if (!empty($program['details_url'])): ?>
                                    <a href="<?php echo htmlspecialchars($program['details_url']); ?>" class="btn btn-primary">Learn More</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-programs" style="display: block;">
                        <div class="no-programs-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>No Programs Found</h3>
                        <p>We're currently developing new conservation programs. Check back soon!</p>
                        <a href="programs.php" class="btn btn-primary">View All Programs</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="loading-state" id="loading-state">
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
                <p>Loading conservation programs...</p>
            </div>

            <div class="no-programs" id="no-programs" style="display: none;">
                <div class="no-programs-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h3>No Programs Found</h3>
                <p>We're currently developing new conservation programs. Check back soon!</p>
                <a href="programs.php" class="btn btn-primary">View All Programs</a>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <div class="cta-text">
                    <h2>Join Our Conservation Mission</h2>
                    <p>Be part of groundbreaking conservation efforts that protect the Virunga Massif's unique biodiversity while empowering local communities. Your participation directly supports habitat protection and sustainable livelihoods.</p>
                    <div class="cta-stats">
                        <div class="cta-stat">
                            <span class="stat-number">1000+</span>
                            <span class="stat-label">Gorillas Protected</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Community Rangers</span>
                        </div>
                        <div class="cta-stat">
                            <span class="stat-number">95%</span>
                            <span class="stat-label">Habitat Preserved</span>
                        </div>
                    </div>
                </div>
                <div class="cta-actions">
                    <a href="../pages/contactus.php" class="btn btn-primary">
                        <i class="fas fa-leaf"></i>
                        Support Conservation
                    </a>
                    <a href="programs.php" class="btn btn-secondary">
                        <i class="fas fa-eye"></i>
                        View All Programs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/conservation.js"></script>
    <script>
        // Load conservation programs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadConservationPrograms();
        });
    </script>
</body>
</html>