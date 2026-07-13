<?php
require_once('../admin/config/connection.php');

// Fetch hero image URL only
$hero_image_url = null;
$hero_query = "SELECT image_url FROM accommodation_hero_images ORDER BY created_at DESC LIMIT 1";
$hero_result = $conn->query($hero_query);
if ($hero_result && $row = $hero_result->fetch_assoc()) {
    $hero_image_url = $row['image_url'];
}

// Hardcoded hero content
$hero_heading = 'Virunga Volcanoes Accommodation';
$hero_subheading = 'Experience world-class gorilla trekking with accommodations ranging from authentic homestays to ultra-luxury eco-lodges in Rwanda\'s Volcanoes National Park';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virunga Accommodation - Gorilla Trekking Lodges</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/accomodation.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
<?php include "./includes/header.php" ?>
    <!-- Hero Section -->
    <section class="hero" <?php
        if ($hero_image_url) {
            // Ensure the path is correct
            if (strpos($hero_image_url, '/') === 0) {
                // Already absolute path
                $image_path = $hero_image_url;
            } else {
                // Relative path
                $image_path = '/' . ltrim($hero_image_url, '/');
            }
            echo 'style="background-image: url(\'' . htmlspecialchars($image_path) . '\'); background-size: cover; background-position: center; background-attachment: fixed;"';
        }
    ?>>
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hero_heading); ?></h1>
            <p><?php echo htmlspecialchars($hero_subheading); ?></p>
            <span class="permit-badge">Gorilla Permit: USD $1,500 per person</span>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container">
        
        <!-- Introduction -->
        <section class="section">
            <div class="intro-text">
                <p>Accommodations around the Virunga Massif are central to the region's tourism-driven economy. They provide direct employment and steady demand for local producers while forming part of a conservation finance loop—visitor revenues fund park protection, anti-poaching patrols, and community benefit-sharing programs, translating lodging spending into both household incomes and conservation outcomes.</p>
            </div>
        </section>

        <!-- Market Profile -->
        <section class="section">
            <h2 class="section-title">Who Visits</h2>
            <div class="market-grid">
                <div class="market-card">
                    <h3>Luxury Travelers</h3>
                    <p>High-spend visitors from Europe, North America, China and Australasia booking all-inclusive packages centered on gorilla trekking. They choose Bisate, Singita, One&Only, or Virunga Lodge.</p>
                </div>
                <div class="market-card">
                    <h3>Mid-Range Tourists</h3>
                    <p>Travelers from Europe and East African markets wanting comfort without ultra-luxury. They prefer boutique lodges like Amakoro, Farmhouse, or Bishop's House.</p>
                </div>
                <div class="market-card">
                    <h3>Budget Travelers</h3>
                    <p>Backpackers, volunteers, and value-minded families choosing homestays and guesthouses for authentic cultural experiences and direct community support.</p>
                </div>
            </div>
        </section>

        <!-- Budget Accommodations -->
        <section class="tier-section">
            <div class="tier-header">
                <span class="tier-badge budget">Budget</span>
                <h2 class="section-title">Budget Accommodations</h2>
            </div>
            <div class="accommodation-grid">
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Virunga Homestay</h3>
                        <div class="price">$20–90 / night</div>
                        <div class="location">Musanze / Kinigi</div>
                        <p class="description">Family-run homestay offering cultural stays with local meals. Basic rooms start at $20–40, with private rooms available. Perfect for independent travelers and volunteers seeking authentic community connection.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Hotel Muhabura</h3>
                        <div class="price">$60–100 / night</div>
                        <div class="location">Musanze</div>
                        <p class="description">Traditional guesthouse close to town and park access. Simple double/twin rooms with reliable amenities—a trusted budget option for gorilla trekkers.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Amahoro Guest House</h3>
                        <div class="price">$50–70 / night</div>
                        <div class="location">Ruhengeri / Musanze</div>
                        <p class="description">Local guesthouse with basic B&B comforts, conveniently located near town and park transfer meeting points.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mid-Range Accommodations -->
        <section class="tier-section">
            <div class="tier-header">
                <span class="tier-badge mid-range">Mid-Range</span>
                <h2 class="section-title">Mid-Range Accommodations</h2>
            </div>
            <div class="accommodation-grid">
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Amakoro Songa Lodge</h3>
                        <div class="price">$160–2,100 / night</div>
                        <div class="location">Near Volcanoes NP</div>
                        <p class="description">Upscale boutique lodge close to Volcanoes National Park. Offers excellent meals and arranged transfers/activities. Rates vary by room type, package, and season.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>The Bishop's House</h3>
                        <div class="price">Mid-range seasonal</div>
                        <div class="location">Musanze / Ruhengeri</div>
                        <p class="description">All-inclusive-style property offering comfortable accommodations. Conference and group-friendly with flexible seasonal rates.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Farmhouse Rwanda</h3>
                        <div class="price">$150–600+ / night</div>
                        <div class="location">Foothills / Private Villa</div>
                        <p class="description">Working farm and boutique villa with stunning volcano views. Private chef options available. Entire-house or suite pricing—popular choice for families and groups.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Luxury Accommodations -->
        <section class="tier-section">
            <div class="tier-header">
                <span class="tier-badge luxury">Luxury</span>
                <h2 class="section-title">Luxury Accommodations</h2>
            </div>
            <div class="accommodation-grid">
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Bisate Lodge</h3>
                        <div class="price">$1,400–3,600+ / person/night</div>
                        <div class="location">Wilderness Area</div>
                        <p class="description">Eco-luxury mountain lodge with private villas and immersive conservation focus. Premium all-inclusive pricing with exclusive guiding experiences.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>One&Only Gorilla's Nest</h3>
                        <div class="price">$1,150–4,000+ / night</div>
                        <div class="location">Luxury Resort</div>
                        <p class="description">New ultra-luxury resort offering private lodges with high-touch service. Targets top-tier gorilla travelers with exceptional amenities and personalized experiences.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Singita Kwitonda Lodge</h3>
                        <div class="price">$1,500–3,000+ / person/night</div>
                        <div class="location">Exclusive Conservation Area</div>
                        <p class="description">Very exclusive conservation-centered luxury lodge with limited suites. Premium private experiences with suite and exclusive-use pricing options.</p>
                    </div>
                </div>
                <div class="accommodation-card">
                    <div class="card-content">
                        <h3>Virunga Lodge</h3>
                        <div class="price">$2,000+ / person/night</div>
                        <div class="location">Volcano Ridge</div>
                        <p class="description">Established higher-end lodge on the volcano ridge with excellent volcano views. All-inclusive seasonal rates with packaged excursions included.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Booking Tips -->
        <div class="tips-box">
            <h3>Important Booking Guidelines</h3>
            <ul>
                <li>Always separate lodge costs from gorilla permits ($1,500) and park fees on quotes</li>
                <li>Present the value ladder: Budget → Mid-Range → Luxury with clear amenity differences</li>
                <li>Luxury lodge prices are typically per person, all-inclusive; mid and budget prices are usually per room</li>
                <li>Confirm whether transfers, breakfast, taxes, and service charges are included before finalizing quotes</li>
                <li>For homestays and guesthouses, clearly specify what's included (meals, hot water, Wi-Fi, transfers)</li>
                <li>Highlight community impact—homestays channel income directly to families; luxury rates support conservation programs</li>
            </ul>
        </div>

    </div>
<?php include "./includes/footer.php" ?>
<script src="../js/header.js" defer></script>
</body>
</html>