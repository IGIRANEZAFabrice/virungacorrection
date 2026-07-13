<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer With Us</title>
    <link rel="stylesheet" href="assets/css/volunteer.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    <link rel="stylesheet" href="assets/css/impact.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/about.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
        <!-- Hero Section -->
         <section class="page-header">
            <div class="page-header-background">
                <img src="uploads/impact/hero.jpg" alt="About Virunga Ecotours Community" loading="lazy" ondragstart="return false;" oncontextmenu="return false;">
                <div class="page-header-overlay"></div>
            </div>
            <div class="container">
                <div class="page-header-content">
                    <nav class="breadcrumb">
                        <a href="index.php">Community</a>
                        <span class="separator"><i class="fas fa-chevron-right"></i></span>
                        <span class="current">Volunteer with us</span>
                    </nav>
                    <h1 style="color: #ffffff;">Our Impact on Community</h1>
                    <p style="color: #ffffff;">There’s a Virunga Ecotours Community Progarm volunteering opportunity for everyone. All are welcome to work to together, a simple help changes lives of noens in need .</p>
                </div>
            </div>
        </section>
      

        <!-- Introduction Section -->
        <section class="introduction-section">
            <div class="container">
                <h2 class="section-title">Help Change Lives</h2>
                <p class="section-description">We believe people want to help communities that they travel to. Join us in inspiring community growth and connection through the joy of travel and meaningful action.</p>
            </div>
        </section>

        <!-- Get Involved Section -->
        <section class="get-involved-section">
            <div class="container">
                <h2 class="section-title">Get Involved</h2>
                <p class="section-description">Discover ways you can give back and learn more about our initiatives.</p>

                <div class="involved-cards">
                    <div class="involved-card">
                        <div class="card-icon">💰</div>
                        <h3>Set Up a Fundraiser</h3>
                        <p>Start your own fundraiser and rally your community to raise essential funds. We help you set up a good online fundraiser to maximize your impact.</p>
                    </div>
                    <div class="involved-card">
                        <div class="card-icon">🗓️</div>
                        <h3>Get Behind an Event</h3>
                        <p>Support our appeals set up to combat current crises. We have well-planned events and community tours to help you engage directly with the community.</p>
                    </div>
                    <div class="involved-card">
                        <div class="card-icon">🌿</div>
                        <h3>Save Nature</h3>
                        <p>Volunteer and take action to save nature. Learn more about our conservation efforts and how your participation can contribute to a sustainable future.</p>
                    </div>
                </div>
            </div>
            
        </section>
          <!-- Parallax Scrolling Section -->
        <section class="parallax-section-v">
            <div class="parallax-overlay-v">
                <div class="parallax-content-v">
                    <h2>Preserving Heritage, Empowering Futures</h2>
                    <p style="color: #ffffff;">Empowering you to give back to communities around the world.</p>
                </div>
            </div>
        </section>

        
        <!-- Learn More Section -->
        <section class="learn-more-section">
            <div class="container">
                <h2 class="section-title">Empowering Women Through Traditional Arts</h2>
                <p class="section-description">Discover how Virunga community programs create lasting impact through cultural preservation and economic empowerment.</p>
                
                <div class="empowerment-content">
                    <div class="empowerment-text">
                        <p>At the heart of Virunga's community initiatives are our women's empowerment programs focused on traditional arts and crafts. These programs including sewing, handcrafts, pottery, and painting serve as powerful vehicles for cultural preservation while providing sustainable livelihoods for local women artisans.</p>
                        
                        <p>Each handcrafted item tells a story of cultural heritage passed down through generations. When you support these initiatives whether through direct funding or purchasing handmade products you're not just acquiring a beautiful piece of art; you're directly contributing to:</p>
                        
                        <ul class="impact-list">
                            <?php
                            $impact_string = "Economic Independence: Providing women with reliable income streams that support their families and reduce financial vulnerability,Cultural Preservation: Ensuring traditional techniques and artistic expressions continue to thrive in modern times,Skill Development: Offering training that combines ancestral knowledge with contemporary market awareness,Community Strength: Fostering collaborative environments where women support each other and build social bonds,Sustainable Development: Creating economic opportunities that respect and celebrate local culture and environment";
                            $impact_points = array_map('trim', explode(',', $impact_string));
                            foreach ($impact_points as $point) {
                                $parts = explode(':', $point, 2);
                                if (count($parts) === 2) {
                                    echo '<li><strong>' . htmlspecialchars($parts[0]) . ':</strong>' . htmlspecialchars($parts[1]) . '</li>';
                                } else {
                                    echo '<li>' . htmlspecialchars($point) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                        
                        <p>These programs have transformed countless lives, allowing women to become financial contributors to their households, gain respect within their communities, and pass their skills to younger generations. The ripple effects extend beyond individual artisans to strengthen entire communities.</p>
                    </div>
                </div>
                
                <div class="shop-action">
                    <a href="https://virungahomestay.com/pages/shop.php" class="shop-button">
                        Support Women Artisans - Shop Handcrafted Products
                    </a>
                    <p class="shop-note">Your purchase directly supports women artisans and their families while preserving cultural heritage.</p>
                </div>
            </div>
        </section>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>

