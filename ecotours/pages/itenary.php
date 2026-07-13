<?php
// Check if we're on the production server or local environment
$base_path = __DIR__;


// If we're on the production server, adjust the path
if (strpos(__FILE__, '/home2/dmxewbmy/public_html/website_58827336/') !== false) {
    // Create a path that works on the production server
    require_once '/home2/dmxewbmy/public_html/website_58827336/pages/handlers/itenary_handler.php';
} else {
    // Use the local path
    require_once $base_path . '/handlers/itenary_handler.php';
}

// Get parameters
$country = isset($_GET['country']) ? strtolower($_GET['country']) : 'rwanda';
$type = isset($_GET['type']) ? $_GET['type'] : null;
$category = isset($_GET['category']) ? strtolower($_GET['category']) : null;

// Get data from handler
$data = getItenaryData($country, $type, $category);

// Extract data for use in the template
$tours = $data['tours'] ?? [];
$categories = $data['categories'] ?? [];
$debug_total = $data['debug_total'] ?? '';
$debug_info = $data['debug_info'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/main.css" />
    <link rel="stylesheet" href="../css/new.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <script src="../js/script.js" defer></script>
    <title><?php echo ucfirst($type); ?> Tours in <?php echo ucfirst($country); ?> - Virunga Ecotours</title>
    <link rel="stylesheet" href="../css/itenary.css" />
    
    <style>
      .tours-grid { 
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
      }
      .tour-card {
        border: 1px solid #ddd;
        border-radius: 0;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }
      .tour-card-image {
        position: relative;
        height: 200px;
        overflow: hidden;
      }
      .tour-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      .tour-card-content {
        padding: 15px;
      }
      .debug-box {
        background: #f9f9f9;
        padding: 15px;
        margin: 15px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: monospace;
      }
    </style>
  </head>
  <body>
   <?php 
   // Use the same conditional approach for including the header
   if (strpos(__FILE__, '/home2/dmxewbmy/public_html/website_58827336/') !== false) {
       include('/home2/dmxewbmy/public_html/website_58827336/pages/includes/header.php');
   } else {
       include($base_path . '/includes/header.php');
   }
   ?>

    <!-- Output debug info -->
    <?php if(isset($_GET['debug']) && $_GET['debug'] == 'true'): ?>
    <div class="debug-box">
      <h3>Debug Information</h3>
      <p>Country: <?php echo $country; ?></p>
      <p>Type: <?php echo $type; ?></p>
      <p>Number of tours found: <?php echo count($tours); ?></p>
      <?php if(!empty($tours)): ?>
        <p>First tour title: <?php echo htmlspecialchars($tours[0]['title']); ?></p>
      <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Hero Section with Carousel -->
    <section class="hero-section">
      <div class="hero-image-container">
        <img
          src="../images/country/<?php 
            if ($type === 'day') {
              echo 'single/';
            } elseif ($type === 'multi') {
              echo 'mult/';
            } else {
              // No type specified, use the country image directly
              echo '';
            }
          ?><?php echo $country; ?>.jpg"
          alt="Tours in <?php echo ucfirst($country); ?>"
          class="hero-image"
        />
        <div class="hero-overlay"></div>
        <h1 class="hero-title">
          <?php 
            if ($type) {
              echo ucfirst($type) . ' ';
            }
          ?>Tours in <?php echo ucfirst($country); ?>
        </h1>
      </div>
    </section>

    <section class="info-section">
      <h2 class="info-title">Did you know we can tailor any tour?</h2>
      <p class="info-subtitle">Our team is here to plan your tailor-made holiday</p>

      <div class="specialist-container">

        <div class="phone-number">
            <i class="fas fa-phone"></i>
          <span>+250 784 513 435</span>
        </div>
      </div>
    </section>

    <!-- description-article-section -->
    <section class="description-section">
      <h2 class="main-heading">
        Discover magnificent <?php echo ucfirst($country); ?> through our 
        <?php 
          if ($type) {
            echo ucfirst($type) . ' ';
          }
        ?>Tours - 
        Experience wildlife, scenery and vibrant culture
      </h2>
      <p class="description-text">
        Experience the warm welcome from <?php echo ucfirst($country); ?>'s citizens as
        you explore the local culture, communities, and adventures. 
        <?php if ($type): ?>
          Our <?php echo ($type === 'day') ? 'single day' : 'multi-day'; ?> tours are designed to give you an authentic and memorable experience.
        <?php else: ?>
          Our tours are designed to give you an authentic and memorable experience, whether you're looking for a quick day trip or an extended adventure.
        <?php endif; ?>
      </p>
    </section>
    <!-- description-article-section -->

    <!-- tab-navigation -->
    <section class="popular-tours-section">
      <div class="container">
        <div class="filter-container">
          <div class="filter-categories">
            <button type="button" 
                    data-category="all" 
                    class="filter-btn <?php echo !$category ? 'active' : ''; ?>">
                ALL
            </button>
            <?php foreach ($categories as $cat): ?>
            <button type="button"
                    data-category="<?php echo htmlspecialchars(strtolower($cat)); ?>" 
                    class="filter-btn <?php echo $category === strtolower($cat) ? 'active' : ''; ?>">
                <?php echo htmlspecialchars(strtoupper($cat)); ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="tours-cards" id="toursContainer">
          <?php if (empty($tours)): ?>
            <p class="no-tours">No tours available for <?php echo ucfirst($country); ?> at the moment.</p>
          <?php else: ?>
            <?php foreach ($tours as $tour): ?>
              <div class="tour-card">
                <!-- Removed initial visible class - will be handled by JS -->
                <div class="tour-card-image">
                  <img
                    src="../<?php echo htmlspecialchars($tour['cover_image_path']); ?>"
                    alt="<?php echo htmlspecialchars($tour['title']); ?>"
                  />
                  <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($tour['category'])); ?></div>
                  <div class="tour-offer">AVAILABLE</div>
                </div>
                <div class="tour-card-content">
                  <div class="tour-tags"></div>
                  <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                  <div class="tour-duration"><?php echo $tour['days_count']; ?> DAY<?php echo $tour['days_count'] > 1 ? 'S' : ''; ?></div>
                  <p><?php echo htmlspecialchars(substr($tour['short_description'], 0, 200)) . '...'; ?></p>
                  <a href="itenaryopen.php?id=<?php echo $tour['tour_id']; ?>" class="read-more-btn">READ MORE</a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="view-more-container">
          <button id="viewMoreBtn" class="view-more-btn">View More</button>
        </div>
    </section>
    <?php 
    // Use the same conditional approach for including the footer
    if (strpos(__FILE__, '/home2/dmxewbmy/public_html/website_58827336/') !== false) {
        include('/home2/dmxewbmy/public_html/website_58827336/pages/includes/footer.php');
    } else {
        include($base_path . '/includes/footer.php');
    }
    ?>
    <script src="../js/header.js" defer></script>
    <script src="../js/itenary.js" defer></script>
    <script src="../js/category-filter.js" defer></script>
  </body>

</html>