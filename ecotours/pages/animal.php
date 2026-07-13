<?php
require_once '../admin/config/connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsible Animal Welfare Care - Virunga Ecotours</title>
    <meta name="description" content="Learn about responsible animal welfare care awareness in community-based tourism around the Virunga Massif">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon" />
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../community/assets/css/animal.css">
    
    <script src="../js/header.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-background">
            <img src="../images/animal/IMG_2828.jpg" alt="Mountain Gorillas in Virunga" loading="lazy">
            <div class="hero-overlay"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Responsible Animal Welfare Care</h1>
                <p class="hero-subtitle">Promoting ethical wildlife interactions through community-based tourism in the Virunga Massif</p>
                <a href="#introduction" class="hero-cta">
                    <i class="fas fa-leaf"></i>
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section id="introduction" class="intro-section">
        <div class="container">
            <div class="section-header">
                <h2>Introduction</h2>
            </div>
            <div class="intro-content">
                <p>The Virunga Massif is one of the most ecologically significant regions in the world, home to endangered mountain gorillas, golden monkeys, and diverse wildlife. Tourism in this region brings economic opportunities, yet it also carries a responsibility: to ensure the welfare of animals and the integrity of their habitats.</p>
                
                <p>Virunga Ecotours integrates Responsible Animal Welfare Care Awareness into its community-based tourism (CBT) activities, promoting ethical interactions, minimizing human-wildlife conflict, and fostering respect for wildlife within and beyond park boundaries.</p>
            </div>
        </div>
    </section>

    <!-- Why It Matters Section -->
    <section class="why-matters-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Responsible Animal Welfare Matters</h2>
            </div>
            <div class="matters-content">
                <div class="matters-text">
                    <p>Animal welfare is at the heart of sustainable tourism. In the Virunga Massif, where primates and other wildlife share space with local communities, responsible welfare practices safeguard both conservation goals and community livelihoods.</p>
                    
                    <p>Without careful management, tourism can unintentionally stress animals, alter their natural behavior, or contribute to habitat degradation. Responsible awareness ensures animals thrive, communities benefit, and visitors leave with meaningful experiences grounded in respect.</p>
                </div>
                <div class="matters-image">
                    <img src="../images/animal/IMG_0402.jpg" alt="Golden Monkey Conservation" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Community Role Section -->
    <section class="community-role-section">
        <div class="container">
            <div class="section-header">
                <h2>Role of the Community in the Program</h2>
                <p>Communities are not just neighbors of the park—they are custodians of its wildlife</p>
            </div>
            <div class="community-roles">
                <div class="role-item">
                    <div class="role-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Education and Awareness</h3>
                    <p>Local guides and homestay hosts share knowledge about respectful behavior around wildlife.</p>
                </div>
                
                <div class="role-item">
                    <div class="role-icon">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <h3>Benefit Sharing</h3>
                    <p>Income from tourism motivates households to protect animals rather than exploit them.</p>
                </div>
                
                <div class="role-item">
                    <div class="role-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Cultural Ambassadors</h3>
                    <p>Communities communicate the cultural significance of animals, linking conservation with heritage.</p>
                </div>
                
                <div class="role-item">
                    <div class="role-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Mitigation of Negative Practices</h3>
                    <p>Communities learn alternative livelihoods to reduce dependence on activities that harm animal welfare.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Analysis Section -->
    <section class="impact-section">
        <div class="container">
            <div class="section-header">
                <h2>Positive and Negative Impacts Analysis</h2>
                <p>Understanding the comprehensive effects of animal welfare programs</p>
            </div>
            <div class="impact-table-container">
                <div class="impact-table">
                    <div class="table-header">
                        <div class="header-cell">Impact Type</div>
                        <div class="header-cell positive">Positive Impacts</div>
                        <div class="header-cell negative">Negative Impacts (Challenges)</div>
                    </div>

                    <div class="table-row">
                        <div class="row-label">For Animals</div>
                        <div class="impact-cell positive">
                            <ul>
                                <li>Reduced stress and better health</li>
                                <li>Protection of habitats</li>
                                <li>Decreased poaching risk</li>
                            </ul>
                        </div>
                        <div class="impact-cell negative">
                            <ul>
                                <li>Risk of habituation</li>
                                <li>Human diseases transmitted to wildlife</li>
                                <li>Dependency on human tolerance</li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="row-label">For Communities</div>
                        <div class="impact-cell positive">
                            <ul>
                                <li>Tourism income and job creation</li>
                                <li>Pride in cultural-ecological identity</li>
                                <li>Education and capacity building</li>
                            </ul>
                        </div>
                        <div class="impact-cell negative">
                            <ul>
                                <li>Restrictions on resource use</li>
                                <li>Potential conflicts if benefits unequally shared</li>
                                <li>Dependency on tourism fluctuations</li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="row-label">For Parks</div>
                        <div class="impact-cell positive">
                            <ul>
                                <li>Improved conservation outcomes</li>
                                <li>Stronger law enforcement</li>
                                <li>Greater scientific monitoring support</li>
                            </ul>
                        </div>
                        <div class="impact-cell negative">
                            <ul>
                                <li>Higher visitor pressure if not managed</li>
                                <li>Difficulty balancing access with protection</li>
                                <li>Resource allocation challenges</li>
                            </ul>
                        </div>
                    </div>

                    <div class="table-row">
                        <div class="row-label">For Visitors</div>
                        <div class="impact-cell positive">
                            <ul>
                                <li>Ethical, meaningful wildlife encounters</li>
                                <li>Better education about conservation</li>
                                <li>Authentic cultural experiences</li>
                            </ul>
                        </div>
                        <div class="impact-cell negative">
                            <ul>
                                <li>Reduced "close encounters" due to stricter policies</li>
                                <li>Higher permit costs</li>
                                <li>More restrictive viewing conditions</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Structure Section -->
    <section class="program-structure-section">
        <div class="container">
            <div class="section-header">
                <h2>Program Structure</h2>
                <p>Comprehensive approach to responsible animal welfare care</p>
            </div>
            <div class="program-elements">
                <div class="program-item">
                    <div class="program-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3>Community Training</h3>
                    <p class="program-description">Workshops on animal welfare, guiding ethics, and safe visitor practices.</p>
                    <p class="program-outcome"><strong>Expected Outcome:</strong> Improved community knowledge and ethical tourism services.</p>
                </div>

                <div class="program-item">
                    <div class="program-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visitor Awareness</h3>
                    <p class="program-description">Pre-trek briefings and codes of conduct (distance, silence, no feeding).</p>
                    <p class="program-outcome"><strong>Expected Outcome:</strong> Reduced wildlife stress, better visitor behavior.</p>
                </div>

                <div class="program-item">
                    <div class="program-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <h3>Revenue Sharing</h3>
                    <p class="program-description">Homestays, guiding, and craft markets linked to conservation benefits.</p>
                    <p class="program-outcome"><strong>Expected Outcome:</strong> Direct financial incentives to protect wildlife.</p>
                </div>

                <div class="program-item">
                    <div class="program-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>Youth Engagement</h3>
                    <p class="program-description">School programs, storytelling, and art projects about wildlife welfare.</p>
                    <p class="program-outcome"><strong>Expected Outcome:</strong> A new generation of conservation-minded citizens.</p>
                </div>

                <div class="program-item">
                    <div class="program-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Monitoring & Reporting</h3>
                    <p class="program-description">Community scouts assisting rangers in observing animal health and welfare.</p>
                    <p class="program-outcome"><strong>Expected Outcome:</strong> Early detection of threats and improved park-community trust.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="parallax-section-v1">
            <div class="parallax-overlay-v">
                <div class="parallax-content-v">
                    <h2>Preserving Heritage, Empowering Futures</h2>
                    <p style="color: #ffffff;">Empowering you to give back to communities around the world.</p>
                </div>
            </div>
    </section>
    <!-- Beyond Park Section -->
    <section class="beyond-park-section">
        <div class="container">
            <div class="section-header">
                <h2>Positive Impacts Beyond the Park</h2>
            </div>
            <div class="beyond-content">
                <div class="beyond-text">
                    <p>Outside the park boundaries, animal welfare awareness fosters human-wildlife coexistence. Villagers learn to safeguard crops without harming wildlife, cultural respect for animals is reinforced, and eco-friendly enterprises such as homestays thrive.</p>

                    <p>It builds pride in regional identity and positions the Virunga Massif as a global leader in ethical tourism.</p>
                </div>
                <div class="beyond-benefits">
                    <div class="benefit-card">
                        <i class="fas fa-seedling"></i>
                        <h4>Sustainable Coexistence</h4>
                        <p>Communities develop wildlife-friendly farming practices</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-heart"></i>
                        <h4>Cultural Respect</h4>
                        <p>Traditional values for wildlife protection are strengthened</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-home"></i>
                        <h4>Eco-Enterprises</h4>
                        <p>Homestays and local businesses flourish sustainably</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-globe"></i>
                        <h4>Global Leadership</h4>
                        <p>Virunga Massif becomes a model for ethical tourism</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Conclusion Section -->
    <section class="conclusion-section">
        <div class="container">
            <div class="conclusion-content">
                <h2>Conclusion</h2>
                <div class="conclusion-text">
                    <p>The Responsible Animal Welfare Care Awareness program under Virunga Ecotours is more than an initiative—it is a commitment to ensuring that every interaction between people, wildlife, and landscapes is respectful, ethical, and sustainable.</p>

                    <p>By actively engaging communities in the Virunga Massif, this program safeguards animal welfare, enhances tourism experiences, and secures livelihoods. While challenges remain—such as balancing visitor expectations and community needs—the positive impacts far outweigh the negatives.</p>

                    <p class="conclusion-highlight">This program is vital because it guarantees that animal welfare in the Virunga Massif is not just a principle but a lived reality, sustained through community pride, visitor respect, and collective responsibility.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- References Section -->
    <section class="references-section">
        <div class="container">
            <div class="section-header">
                <h2>References</h2>
            </div>
            <div class="references-content">
                <ul class="references-list">
                    <li>
                        <strong>International Gorilla Conservation Programme (IGCP).</strong> Field reports and conservation guidelines.
                    </li>
                    <li>
                        <strong>Rwanda Development Board (RDB).</strong> Volcanoes National Park visitor code of conduct.
                    </li>
                    <li>
                        <strong>IUCN (International Union for Conservation of Nature).</strong> Guidelines for sustainable tourism and wildlife interaction.
                    </li>
                    <li>
                        <strong>Dian Fossey Gorilla Fund.</strong> Community-based conservation initiatives in the Virunga Massif.
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Join Our Conservation Efforts</h2>
                <p>Experience responsible wildlife tourism that makes a positive impact on animals, communities, and conservation.</p>
                <div class="cta-buttons">
                    <a href="../pages/contact.php" class="cta-btn primary">
                        <i class="fas fa-envelope"></i>
                        Contact Us
                    </a>
                    <a href="../pages/activity.php" class="cta-btn secondary">
                        <i class="fas fa-leaf"></i>
                        Explore Activities
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
<?php
// Close database connection
if (isset($conn)) {
    mysqli_close($conn);
}
?>
