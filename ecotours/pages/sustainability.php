<?php
// Database connection (if needed for future dynamic content)
require_once('../admin/config/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sustainability & Community Impact | Virunga Ecotours</title>
    <meta name="description" content="Learn about our sustainability initiatives and community contributions around the Virunga Massif. Discover how tourism revenue supports local communities and conservation efforts.">
    <meta name="keywords" content="Virunga sustainability, community tourism, conservation funding, Rwanda TRSP, Uganda revenue sharing, Batwa cultural tourism">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
   
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/sustainability.css">
    
</head>
<body>
    <?php include "./includes/header.php" ?>

    <!-- Hero Section -->
    <section class="sustainability-hero">
        <div class="hero-content">
            <h1>Sustainability & Community Impact</h1>
            <p>Supporting Communities Around the Virunga Massif Through Responsible Tourism</p>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="our-story">
        <div class="container">
            <div class="story-container">
                <div class="story-text">
                    <h2>Our Commitment to Sustainable Tourism</h2>
                    <p>At Virunga Ecotours, we believe that tourism should benefit not only travelers but also the communities and ecosystems we visit. Our operations around the Virunga Massif are designed to create meaningful economic opportunities for local communities while supporting critical conservation efforts.</p>
                    <p>Through established revenue-sharing mechanisms and community partnerships, every journey with us contributes directly to local development, conservation initiatives, and the preservation of cultural heritage in Rwanda, Uganda, and the Democratic Republic of Congo.</p>
                </div>
                <div class="story-image">
                    <img src="../images/sustainability/1.jpg" alt="Community impact through sustainable tourism">
                </div>
            </div>
        </div>
    </section>

    <!-- Policy Architecture Section -->
    <section class="policy-section">
        <div class="container">
            <h2>Policy Architecture & Financing Mechanisms</h2>
            <div class="policy-grid">
                <div class="policy-card">
                    <h3> Rwanda - TRSP</h3>
                    <p><strong>Revenue Share:</strong> 10% of gross park tourism revenues allocated to communities.</p>
                    <p><strong>Impact:</strong> Linked to improved local services and reduced incentives for illegal resource use.</p>
                </div>
                <div class="policy-card">
                    <h3> Uganda - Community Revenue Sharing</h3>
                    <p><strong>Revenue Share:</strong> 20% of park entry fees shared with neighboring local governments.</p>
                    <p><strong>Impact:</strong> Associated with declines in illegal activities and more positive conservation attitudes.</p>
                </div>
                <div class="policy-card">
                    <h3> DR Congo - Virunga Alliance</h3>
                    <p><strong>Model:</strong> Park-led platform linking conservation, poverty reduction, and peacebuilding.</p>
                    <p><strong>Focus:</strong> Integrated development including energy projects and enterprise support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Channels Section -->
    <section class="benefits-section">
        <div class="container">
            <h2>Thematic Channels of Benefit</h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-home"></i></div>
                    <h3>Local Livelihood Projects</h3>
                    <p>Schools, water systems, and local enterprises that reduce dependence on park resources.</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-users"></i></div>
                    <h3>Batwa-Focused Initiatives</h3>
                    <p>Community-based cultural tourism, housing, and training for Batwa communities.</p>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Anti-Poaching & Ranger Support</h3>
                    <p>Revenue-backed operations supporting ranger salaries, equipment, and community liaison programs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stats Section -->
    <section class="impact-stats">
        <div class="container">
            <h2>Documented Impacts</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-number">UGX 2.197B</span>
                    <p>Disbursed to Bwindi-Mgahinga communities in 2025</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">10%</span>
                    <p>Rwanda's tourism revenue shared with communities</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">US$50</span>
                    <p>Per guest-night for Batwa programs by some lodges</p>
                </div>
                <div class="stat-card">
                    <span class="stat-number">35%</span>
                    <p>Of Rwanda's community pool for Volcanoes NP</p>
                </div>
            </div>
            <div class="key-outcomes">
                <h3>Key Outcomes</h3>
                <div class="outcomes-grid">
                    <div class="outcome-item">
                        <i class="fas fa-chart-line"></i>
                        <h4>Reduced Illegal Activity</h4>
                        <p>Long-term community funding correlates with declining illegal activities.</p>
                    </div>
                    <div class="outcome-item">
                        <i class="fas fa-heart"></i>
                        <h4>Improved Attitudes</h4>
                        <p>Improved local support for conservation with tangible benefits.</p>
                    </div>
                    <div class="outcome-item">
                        <i class="fas fa-hands-helping"></i>
                        <h4>Service Delivery</h4>
                        <p>Improvements in water, health, and education infrastructure.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Technical Reference Table -->
    <section class="reference-section">
        <div class="container">
            <h2>Technical Reference Table</h2>
            <div class="reference-table">
                <table>
                    <thead>
                        <tr>
                            <th>Jurisdiction / Mechanism</th>
                            <th>Statutory Share & Basis</th>
                            <th>Primary Uses</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Rwanda - TRSP</strong></td>
                            <td>10% of gross park tourism revenue</td>
                            <td>Community infrastructure, livelihoods</td>
                            <td>Independent reviews document outcomes</td>
                        </tr>
                        <tr>
                            <td><strong>Uganda - Revenue Sharing</strong></td>
                            <td>20% of park entry fees + US$10 per gorilla permit</td>
                            <td>Livelihood projects: goats, pigs, apiculture</td>
                            <td>Policy reported by UWA</td>
                        </tr>
                        <tr>
                            <td><strong>DRC - Virunga Alliance</strong></td>
                            <td>Integrated investment model</td>
                            <td>Community energy, enterprise, ranger ops</td>
                            <td>Security context affects delivery</td>
                        </tr>
                        <tr>
                            <td><strong>Batwa Cultural Tourism</strong></td>
                            <td>Activity fees (e.g., US$80 Batwa Trail)</td>
                            <td>Direct income to guides, cultural preservation</td>
                            <td>Fee schedule varies by residency</td>
                        </tr>
                        <tr>
                            <td><strong>Lodge-linked Funds</strong></td>
                            <td>US$50 per guest-night + grants</td>
                            <td>Batwa housing, training, enterprises</td>
                            <td>Lodges publish project portfolios</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section class="big-img">
        <img src="../images/sustainability/2.jpg" alt="">
    </section>
    <!-- Transparency Boilerplate -->
    <section class="boilerplate-section">
        <div class="container">
            <h2>Our Transparency Commitment</h2>
            <div class="boilerplate-text">
                <p>"A minimum of the statutory revenue share from this package is returned to communities neighboring the Virunga Massif. These funds support livelihood projects, social infrastructure, and human–wildlife conflict mitigation, and complement ranger operations and anti-poaching. Independent studies show that sustained community funding is associated with declines in illegal activities and stronger conservation support."</p>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta">
        <div class="container">
            <div class="cta-content">
                <h2>Join Us in Sustainable Tourism</h2>
                <p>Experience the Virunga Massif while directly contributing to community development and conservation. Every journey makes a measurable difference.</p>
                <a href="contact.php" class="button">Plan Your Sustainable Journey</a>
                <div class="social-links">
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php" ?>
    <script src="../js/header.js"></script>
</body>
</html>