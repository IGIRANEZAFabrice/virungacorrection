<?php
require_once '../admin/config/connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Museums of Rwanda - Cultural Heritage Tours | Virunga Ecotours</title>
    <meta name="description" content="Explore Rwanda's rich cultural heritage through its museums. From the Kigali Genocide Memorial to the King's Palace Museum, discover the stories that shaped Rwanda.">
    <meta name="keywords" content="Rwanda museums, Kigali Genocide Memorial, King's Palace Museum, Ethnographic Museum, Rwanda Art Museum, cultural tours Rwanda">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="../css/museums.css">
</head>
<body>
    <?php include "./includes/header.php" ?>

    <!-- Hero Section -->
    <section class="museums-hero" id="hero">
        <div class="hero-content">
            <h1>Museums of Rwanda</h1>
            <p>Where History, Culture, and Memory Come Alive</p>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="intro-content" data-animation="fadeInUp">
                <h2>Guardians of Rwanda's Memory</h2>
                <p>Rwanda is often celebrated for its rolling green hills, rare mountain gorillas, and warm hospitality. Yet beyond its landscapes, Rwanda holds another treasure: its museums. These institutions are the guardians of the nation's memory, creativity, and identity. They bring to life the ancient traditions of the royal courts, the resilience of communities, the tragedy and lessons of the 1994 Genocide against the Tutsi, and the hope that drives Rwanda's future.</p>
                
                <p>Exploring Rwanda's museums is a journey of understanding. Each museum is more than a building filled with objects—it is a living storybook. Through exhibitions, artifacts, gardens, and historic sites, museums invite visitors to reflect, to learn, and to connect with the soul of Rwanda. Whether you are a first-time traveler or a returning guest, visiting one or several museums will deepen your experience of the country.</p>
            </div>
        </div>
    </section>

    <!-- Why Museums Matter Section -->
    <section class="why-museums-section">
        <div class="container">
            <div class="section-header">
                <h2>Why Rwanda's Museums Matter</h2>
                <p>Discover the profound impact these cultural institutions have on preserving and sharing Rwanda's rich heritage</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card" data-animation="fadeIn" data-delay="0.2">
                    <div class="benefit-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3>Preserve Culture & Heritage</h3>
                    <p>Rwanda's museums safeguard centuries of traditions, from royal rituals to music, dance, and crafts.</p>
                </div>
                
                <div class="benefit-card" data-animation="fadeIn" data-delay="0.4">
                    <div class="benefit-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Educate & Inspire</h3>
                    <p>They tell stories of resilience, remembrance, and renewal, giving visitors a profound understanding of Rwanda's journey.</p>
                </div>
                
                <div class="benefit-card" data-animation="fadeIn" data-delay="0.6">
                    <div class="benefit-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Spread Across the Country</h3>
                    <p>Museums are located in Kigali, the south, the west, and the north—each stop adding depth to your travels.</p>
                </div>
                
                <div class="benefit-card" data-animation="fadeIn" data-delay="0.8">
                    <div class="benefit-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Accessible & Enriching</h3>
                    <p>With most tours lasting about half a day, museums can easily be combined with other activities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Museums by Region Section -->
    <section class="museums-by-region">
        <div class="container">
            <div class="section-header">
                <h2>Museums Across Rwanda</h2>
                <p>Explore cultural treasures from Kigali to the countryside</p>
            </div>

            <!-- Kigali Museums -->
            <div class="region-section">
                <h3 class="region-title">In Kigali (Capital City)</h3>
                <div class="museums-grid">
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.2">
                        <div class="museum-content">
                            <h4 class="museum-name">Kigali Genocide Memorial</h4>
                            <div class="museum-location">Gisozi</div>
                            <p class="museum-description">A powerful memorial and education center honoring the victims of the 1994 Genocide against the Tutsi. It includes detailed exhibitions, memorial gardens, and a place for reflection.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Half-day tour • Educational experience • Memorial gardens
                            </div>
                        </div>
                    </div>
                    
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.4">
                        <div class="museum-content">
                            <h4 class="museum-name">Campaign Against Genocide Museum</h4>
                            <div class="museum-location">Parliament Building</div>
                            <p class="museum-description">Chronicles the Rwandan Patriotic Front's military campaign to end the genocide, featuring underground command posts and war history.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Historical site • Underground bunkers • War history
                            </div>
                        </div>
                    </div>
                    
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.6">
                        <div class="museum-content">
                            <h4 class="museum-name">Rwanda Art Museum</h4>
                            <div class="museum-location">Kanombe (Former Presidential Palace)</div>
                            <p class="museum-description">Celebrates contemporary Rwandan and African art within the historic walls of the old presidential palace, with preserved palace rooms and plane wreckage displayed outside.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Contemporary art • Historic palace • Cultural exhibits
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Southern Rwanda Museums -->
            <div class="region-section">
                <h3 class="region-title">Southern Rwanda</h3>
                <div class="museums-grid">
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.2">
                        <div class="museum-content">
                            <h4 class="museum-name">Ethnographic Museum</h4>
                            <div class="museum-location">Huye (Butare)</div>
                            <p class="museum-description">One of East Africa's largest ethnographic collections, with seven exhibition halls showcasing artifacts, clothing, music, tools, and reconstructed traditional homes.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Seven exhibition halls • Traditional artifacts • Cultural heritage
                            </div>
                        </div>
                    </div>
                    
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.4">
                        <div class="museum-content">
                            <h4 class="museum-name">King's Palace Museum</h4>
                            <div class="museum-location">Nyanza</div>
                            <p class="museum-description">Offers an authentic experience of Rwanda's royal history with a reconstructed palace, traditional huts, and the ceremonial long-horned Inyambo cattle.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Royal history • Traditional architecture • Inyambo cattle
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Western Rwanda Museums -->
            <div class="region-section">
                <h3 class="region-title">Western Rwanda</h3>
                <div class="museums-grid">
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.2">
                        
                        <div class="museum-content">
                            <h4 class="museum-name">Museum of Environment</h4>
                            <div class="museum-location">Karongi (Lake Kivu)</div>
                            <p class="museum-description">A unique museum dedicated to environmental education, renewable energy, and biodiversity. Its rooftop ethnobotanical garden overlooks Lake Kivu, making it one of Rwanda's most scenic museums.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Environmental focus • Lake Kivu views • Ethnobotanical garden
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Northern Rwanda Museums -->
            <div class="region-section">
                <h3 class="region-title">Northern Rwanda</h3>
                <div class="museums-grid">
                    <div class="museum-card" data-animation="fadeIn" data-delay="0.2">
                        <div class="museum-content">
                            <h4 class="museum-name">National Liberation Museum Park</h4>
                            <div class="museum-location">Mulindi, Gicumbi District</div>
                            <p class="museum-description">A historic site where Rwanda's liberation struggle was planned. Visitors can see underground bunkers, photographs, and exhibitions narrating the country's four-year fight for freedom.</p>
                            <div class="visit-info">
                                <i class="fas fa-info-circle"></i> Liberation history • Underground bunkers • Freedom struggle
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value of Museum Visit Section -->
    <section class="value-section">
        <div class="container">
            <div class="value-content" data-animation="fadeIn">
                <h2>The Value of a Museum Visit</h2>
                <p>Visiting a museum in Rwanda is more than sightseeing. It is about entering into a dialogue with history, culture, and the people who lived it. Standing in the King's Palace transports you back to an age of tradition and ceremony. Walking through the Ethnographic Museum reveals how everyday life was lived centuries ago.</p>
                
                <p>At the Kigali Genocide Memorial, you witness the lessons of history that continue to shape Rwanda's future. The Museum of Environment, meanwhile, connects visitors with Rwanda's modern role in global sustainability.</p>
                
                <p>Every museum offers a different perspective, and together they create a complete story of Rwanda—from its origins and struggles to its rebirth and creativity.</p>
            </div>
        </div>
    </section>

    <!-- Planning Section -->
    <section class="planning-section">
        <div class="container">
            <div class="section-header">
                <h2>Plan Your Museum Journey</h2>
                <p>Each museum is best experienced as a half-day tour, making it easy to include them in your itinerary alongside other activities</p>
            </div>
            
            <div class="planning-grid">
                <div class="planning-card" data-animation="fadeIn" data-delay="0.2">
                    <h4><i class="fas fa-city"></i> In Kigali</h4>
                    <p>You can spend the morning at the Kigali Genocide Memorial and the afternoon at the Rwanda Art Museum.</p>
                </div>
                
                <div class="planning-card" data-animation="fadeIn" data-delay="0.4">
                    <h4><i class="fas fa-mountain"></i> In the South</h4>
                    <p>Combine the Ethnographic Museum in Huye with the King's Palace in Nyanza for a cultural day trip.</p>
                </div>
                
                <div class="planning-card" data-animation="fadeIn" data-delay="0.6">
                    <h4><i class="fas fa-water"></i> Along Lake Kivu</h4>
                    <p>The Museum of Environment pairs beautifully with a lakeside lunch or boat ride.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Museum Tours Section -->
    <section class="museum-tours-section">
        <div class="container">
            <div class="section-header">
                <h2>Museum Tours & Itineraries</h2>
                <p>Discover our curated museum experiences and cultural journeys</p>
            </div>
            
            <div class="tours-grid">
                <?php
                // Fetch tours containing "museum" in title or description
                $museum_tours_query = "SELECT * FROM tours WHERE (title LIKE '%museum%' OR short_description LIKE '%museum%' OR title LIKE '%memorial%' OR title LIKE '%cultural%' OR title LIKE '%heritage%' OR title LIKE '%palace%') ORDER BY created_at DESC";
                $museum_tours_result = mysqli_query($conn, $museum_tours_query);
                
                if (mysqli_num_rows($museum_tours_result) > 0):
                    while ($tour = mysqli_fetch_assoc($museum_tours_result)):
                ?>
                    <div class="tour-card" data-animation="fadeIn" data-delay="0.2">
                        <div class="tour-image">
                            <img src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>" 
                                 alt="<?php echo htmlspecialchars($tour['title']); ?>" loading="lazy"
                                 onerror="this.src='../images/museums/default-tour.jpg'">
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
                            <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="tour-btn">
                                <i class="fas fa-arrow-right"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="no-tours-message">
                        <div style="font-size: 3rem; color: var(--accent-terracotta); margin-bottom: 1rem;">
                            <i class="fas fa-university"></i>
                        </div>
                        <h3>Museum Tours Coming Soon</h3>
                        <p>We're currently developing specialized museum tours. Contact us to create a custom cultural itinerary that includes visits to Rwanda's most significant museums and cultural sites.</p>
                        <a href="contactus.php" class="tour-btn" style="margin-top: 1rem;">
                            <i class="fas fa-envelope"></i>
                            Contact Us
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta" id="contact" style="background-color: var(--neutral-beige);">
        <div class="container">
            <div class="cta-content">
                <h2>Ready to Explore Rwanda's Cultural Heritage?</h2>
                <p>If you would like to book one museum tour or design a multi-museum itinerary, our team will help plan your personalized cultural journey through Rwanda.</p>
                <a href="contactus.php" class="button">Plan Your Museum Journey</a>
                
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
    <script>
        // Navigation and Mobile Menu
        const scrollDown = document.getElementById('scroll-down');
        const heroSection = document.getElementById('hero');

        // Smooth Scroll for Scroll Down Button
        scrollDown.addEventListener('click', () => {
            const nextSection = heroSection.nextElementSibling;
            nextSection.scrollIntoView({ behavior: 'smooth' });
        });

        // Intersection Observer for animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const handleIntersect = (entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        const delay = element.dataset.delay || 0;

                        setTimeout(() => {
                            element.classList.add('animated');
                        }, delay * 1000);

                        observer.unobserve(element);
                    }
                });
            };

            const observer = new IntersectionObserver(handleIntersect, observerOptions);

            // Target elements to observe
            const elementsToObserve = document.querySelectorAll('[data-animation]');
            elementsToObserve.forEach(element => {
                observer.observe(element);
            });
        }
    </script>
</body>
</html>
