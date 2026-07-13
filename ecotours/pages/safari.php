<?php
// Database connection and data fetching
require_once('../admin/config/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safari Tours in Rwanda, Uganda & Congo | Virunga Ecotours</title>
    <meta name="description" content="Experience unforgettable safari adventures across Rwanda, Uganda & Congo. Meet mountain gorillas, explore dramatic landscapes, and discover Africa's wild frontier.">
    <meta name="keywords" content="safari tours Rwanda Uganda Congo, mountain gorillas, wildlife safari, Akagera National Park, Queen Elizabeth Park, Virunga National Park">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/about.css">
    <link rel="stylesheet" href="../css/safari.css">
</head>
<body>
    <?php include "./includes/header.php" ?>

    <!-- Hero Section -->
    <section class="safari-hero" id="hero">
        <div class="hero-content">
            <h1>Safari Tours in Rwanda, Uganda & Congo</h1>
            <p>Step into a land where adventure begins the moment the sun rises over misty mountains, where the call of chimpanzees echoes through ancient forests, and where lions patrol the golden savannah.</p>
        </div>
        <div class="scroll-indicator" id="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content">
                <h2>Journey into the Heart of Africa</h2>
                <p>A safari through Rwanda, Uganda, and Congo is not just a holiday it is a journey into the beating heart of Africa. These three neighboring countries create one of the richest safari circuits in the world.</p>
                
                <p>Here, you can meet the last mountain gorillas on earth, cruise the Nile alongside hippos, and stand face-to-face with the world's largest primates the lowland gorillas of Congo. Every corner reveals something new: a different landscape, a new rhythm, another unforgettable encounter.</p>
            </div>
        </div>
    </section>

    <!-- Countries Section -->
    <section class="countries-section">
        <div class="container">
            <!-- Rwanda -->
            <div class="country-card">
                <div class="country-header">
                    
                    <div>
                        <h3 class="country-title">Rwanda</h3>
                        <p class="country-subtitle">Land of a Thousand Hills & Wild Encounters</p>
                    </div>
                </div>
                <p class="country-description">Rwanda is small yet overflowing with treasures. Why Rwanda? It is a place where every safari feels close, personal, and deeply moving a seamless blend of wildlife and wonder.</p>
                
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <h4><i class="fas fa-elephant"></i> Akagera National Park</h4>
                        <p>Herds of elephants and giraffes wander against a backdrop of shimmering lakes. Boat safaris on Lake Ihema reveal pods of hippos and rare birds gliding through papyrus swamps.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-mountain"></i> Volcanoes National Park</h4>
                        <p>World-famous gorilla treks lead you into lush bamboo forests where silverbacks rest with their families. Golden monkeys swing through the trees, and volcanoes rise in dramatic silhouettes.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-tree"></i> Nyungwe Forest</h4>
                        <p>The air is alive with the chatter of chimpanzees and colobus monkeys. The canopy walkway sways high above emerald treetops, offering breathtaking views of Africa's oldest rainforest.</p>
                    </div>
                </div>
            </div>

            <!-- Uganda -->
            <div class="country-card">
                <div class="country-header">
                   
                    <div>
                        <h3 class="country-title">Uganda</h3>
                        <p class="country-subtitle">The Pearl of Africa</p>
                    </div>
                </div>
                <p class="country-description">Uganda is where savannah meets rainforest, where every park has its own secret. Why Uganda? It's the safari of variety: gorillas one day, lions the next, and the drama of the Nile in between.</p>
                
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <h4><i class="fas fa-paw"></i> Bwindi Impenetrable Forest</h4>
                        <p>Nearly half of the world's mountain gorillas make their home here. Trekking through tangled jungle to meet them is a life-changing experience.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-lion"></i> Queen Elizabeth National Park</h4>
                        <p>Surprises travelers with tree-climbing lions, giant hippos, and elephants gathering at the Kazinga Channel.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-water"></i> Murchison Falls National Park</h4>
                        <p>Roars with power as the Nile River squeezes through a narrow gorge, plunging in thunderous beauty before flowing through plains filled with giraffes and buffalo.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-monkey"></i> Kibale Forest</h4>
                        <p>A primate paradise where chimpanzees and 12 other species thrive in their natural habitat.</p>
                    </div>
                </div>
            </div>

            <!-- Congo -->
            <div class="country-card">
                <div class="country-header">
                    
                    <div>
                        <h3 class="country-title">Congo</h3>
                        <p class="country-subtitle">Africa's Wild Frontier</p>
                    </div>
                </div>
                <p class="country-description">For the bold traveler, Congo offers safaris found nowhere else. Why Congo? It's a journey off the beaten path—wild, authentic, and unforgettable.</p>
                
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <h4><i class="fas fa-volcano"></i> Virunga National Park</h4>
                        <p>Africa's oldest park is home to gorillas, chimpanzees, and volcanoes cloaked in mist.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-gorilla"></i> Kahuzi-Biega National Park</h4>
                        <p>Protects the mighty eastern lowland gorillas, the world's largest primates, with treks that feel raw and untamed.</p>
                    </div>
                    <div class="highlight-item">
                        <h4><i class="fas fa-globe-africa"></i> Garamba National Park</h4>
                        <p>Stretches into endless savannahs, where elephants and lions roam in solitude far from the crowds.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Section -->
    <section class="why-choose-section">
        <div class="container">
            <div class="section-header">
                <h2 style="color: white; text-align: center;">Why Choose a Safari Here?</h2>
                <p style="color: rgba(255,255,255,0.9); text-align: center; font-size: 1.1rem;">Experience the ultimate African adventure across three incredible countries</p>
            </div>    
            <div class="why-choose-grid">
                <div class="why-choose-item">
                    
                    <h3>See the Big Five</h3>
                    <p>Lions, elephants, buffalo, rhinos, and leopards roam Rwanda and Uganda's parks in spectacular abundance.</p>
                </div>
                
                <div class="why-choose-item">
                    
                    <h3>Explore Dramatic Landscapes</h3>
                    <p>Volcanoes, rainforests, lakes, and rivers create a constantly changing backdrop for your adventure.</p>
                </div>
                
                <div class="why-choose-item">
                    
                    <h3>Feel the Adventure</h3>
                    <p>Each country adds a new chapter, making your safari a story of discovery and wonder.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Planning Section -->
    <section class="planning-section">
        <div class="container">
            <div class="planning-content">
                <h2>Plan Your Safari Journey</h2>
                <p>A safari here can be as short as a 3-day gorilla trek in Rwanda, or as wide as a 10-day journey across three countries. Many travelers choose to combine them—starting with gorillas in Rwanda, crossing into Uganda for chimpanzees and savannah game drives, and finishing in Congo for the rare lowland gorillas.</p>
                
                <p>Whether you dream of a single park or a multi-country adventure, we are here to create your perfect safari. Choose from our safari itinerary cards below, and our team will bring your African adventure to life.</p>
            </div>
        </div>
    </section>

    <!-- Safari Tours Section -->
    <section class="safari-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Safari Tours & Itineraries</h2>
                <p>Discover our curated safari experiences across Rwanda, Uganda, and Congo</p>
            </div>
            
            <div class="tours-grid">
                <?php
                // Fetch safari tours from database
                $safari_tours_query = "SELECT * FROM tours WHERE (title LIKE '%safari%' OR title LIKE '%gorilla%' OR title LIKE '%wildlife%' OR title LIKE '%akagera%' OR title LIKE '%queen elizabeth%' OR title LIKE '%murchison%' OR title LIKE '%bwindi%' OR title LIKE '%virunga%' OR short_description LIKE '%safari%' OR short_description LIKE '%gorilla%' OR short_description LIKE '%wildlife%') ORDER BY created_at DESC";
                $safari_tours_result = mysqli_query($conn, $safari_tours_query);
                $total_tours = mysqli_num_rows($safari_tours_result);
                
                if ($total_tours > 0):
                    while ($tour = mysqli_fetch_assoc($safari_tours_result)):
                ?>
                    <div class="safari-tour-card">
                        <div class="tour-image">
                            <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($tour['title']); ?>" loading="lazy"
                                 onerror="this.src='../images/safari/default-safari.jpg'">
                            <div class="tour-badge">
                                <?php echo $tour['days_count']; ?> Day<?php echo $tour['days_count'] > 1 ? 's' : ''; ?>
                            </div>
                        </div>
                        <div class="tour-content">
                            <h3 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h3>
                            <p class="tour-description">
                                <?php 
                                $summary = substr(strip_tags($tour['short_description']), 0, 150);
                                echo htmlspecialchars($summary) . (strlen(strip_tags($tour['short_description'])) > 150 ? '...' : '');
                                ?>
                            </p>
                            <div class="tour-highlights">
                                <span class="tour-highlight">Wildlife Safari</span>
                                <span class="tour-highlight">Expert Guides</span>
                                <span class="tour-highlight">All Inclusive</span>
                            </div>
                            <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="tour-btn">
                                <i class="fas fa-arrow-right"></i>
                                View Safari Details
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="no-tours-message">
                        <div style="font-size: 3rem; color: var(--accent-terracotta); margin-bottom: 1rem;">
                            <i class="fas fa-binoculars"></i>
                        </div>
                        <h3>Safari Adventures Coming Soon</h3>
                        <p>We're currently developing our safari tour packages. Contact us to create a custom safari itinerary across Rwanda, Uganda, and Congo tailored to your adventure dreams.</p>
                        <a href="contactus.php" class="tour-btn" style="margin-top: 1rem;">
                            <i class="fas fa-envelope"></i>
                            Plan My Safari
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- View More Button (will be shown/hidden by JavaScript based on number of tours) -->
            <?php if ($total_tours > 6): ?>
                <div class="view-more-container">
                    <button id="viewMoreBtn" class="view-more-btn">
                        <i class="fas fa-plus"></i>
                        Load More Tours (<?php echo ($total_tours - 6); ?> remaining)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta" id="contact" style="background-color: var(--neutral-beige);">
        <div class="container">
            <div class="cta-content">
                <h2>Ready for Your African Safari Adventure?</h2>
                <p>Whether you dream of a single park or a multi-country adventure, we are here to create your perfect safari experience across Rwanda, Uganda, and Congo.</p>
                <a href="contactus.php" class="button">Start Planning Your Safari</a>
                
                <div class="social-links">
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php" ?>

    <!-- JavaScript -->
    <script src="../js/header.js" defer></script>
    <script src="../js/safari.js" defer></script>
</body>
</html>
