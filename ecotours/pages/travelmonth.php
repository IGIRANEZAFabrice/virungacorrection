<?php
require_once('../admin/config/connection.php');

// Get month parameter and convert to proper case, default to January if not set
$current_month = isset($_GET['month']) ? ucfirst(strtolower($_GET['month'])) : 'January';

// Fetch the month's destinations data
$sql = "SELECT * FROM travel_destinations WHERE month = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $current_month);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Get the destinations for this month
$destinations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $destinations[] = $row;
}

// Array of month images (same as index.php)
$month_images = [
    'January' => '../images/month/1 (1).jpg',
    'February' => '../images/month/1 (2).jpg',
    'March' => '../images/month/1 (3).jpg',
    'April' => '../images/month/1 (4).jpg',
    'May' => '../images/month/1 (5).jpg',
    'June' => '../images/month/1 (6).jpg',
    'July' => '../images/month/1 (7).jpg',
    'August' => '../images/month/1 (8).jpg',
    'September' => '../images/month/1 (9).jpg',
    'October' => '../images/month/1 (10).jpg',
    'November' => '../images/month/1 (11).jpg',
    'December' => '../images/month/1 (12).jpg'
];

// Validate that the month exists in our array
if (!array_key_exists($current_month, $month_images)) {
    $current_month = 'January'; // Fallback to January if invalid month
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Best destinations to Visit in <?php echo htmlspecialchars($current_month); ?></title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link
      rel="shortcut icon"
      href="./images/logos/icon.png"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/month.css" />
    <script src="../js/header.js" defer></script>
  </head>
  <body>
    <?php include('./includes/header.php'); ?>
    
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <img src="<?php echo htmlspecialchars($month_images[$current_month]); ?>" 
           alt="<?php echo htmlspecialchars($current_month); ?>" 
           class="hero-image">
      <div class="hero-content">
        <h1>Seasonal Guide: Experiencing the Virunga Mountains in <?php echo htmlspecialchars($current_month); ?></h1>
      </div>
    </section>

    <!-- Intro Text -->
    <div class="intro-text">
        <p>Discover the perfect destinations for your next adventure! Each month offers unique experiences and incredible places to explore. From seasonal festivals and natural phenomena to ideal weather conditions and cultural events, we'll help you find the best time to visit your dream destinations.</p>
    </div>

    <!-- Destinations Section -->
    <section class="destin container">
      <?php foreach ($destinations as $destination): ?>
        <div class="destine">
          <div class="destine-image">
            <img src="../admin/<?php echo htmlspecialchars($destination['image_url']); ?>" 
                 alt="<?php echo htmlspecialchars($destination['name']); ?>" />
          </div>
          <div class="destine-content">
            <h2><?php echo htmlspecialchars($destination['name']); ?></h2>
            <p><?php echo htmlspecialchars($destination['description']); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </section>

    <!-- Month Navigation -->
    <section class="month-nav">
      <div class="container">
        <h2>Seasonal Guide to the Virunga Landscape</h2>
        <div class="month-slider-container">
          <div class="slider-arrow prev">&#10094;</div>
          <div class="slider-arrow next">&#10095;</div>

          <div class="month-slider">
            <?php
            $months = [
                'February' => 'Best destinations to visit in February',
                'March' => 'Best destinations to visit in March',
                'April' => 'Best destinations to visit in April',
                'May' => 'Best destinations to visit in May',
                'June' => 'Best destinations to visit in June',
                'July' => 'Best destinations to visit in July',
                'August' => 'Best destinations to visit in August',
                'September' => 'Best destinations to visit in September',
                'October' => 'Best destinations to visit in October',
                'November' => 'Best destinations to visit in November',
                'December' => 'Best destinations to visit in December'
            ];

            foreach ($months as $month => $details): ?>
              <div class="month-card" data-month="<?php echo $month; ?>">
                <a href="travelmonth.php?month=<?php echo urlencode($month); ?>">
                  <img src="<?php echo $month_images[$month]; ?>" alt="<?php echo $month; ?>" />
                  <div class="month-overlay">
                    <h3 class="month-name"><?php echo $month; ?></h3>
                  </div>
                  <div class="month-details"><?php echo $details; ?></div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
          <div class="pagination-dots">
            <?php
            $groups = array_chunk($months, 3, true);
            foreach ($groups as $index => $group): 
              $months_text = implode('-', array_keys($group));
            ?>
              <div class="pagination-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>">
                <div class="month-preview"><?php echo $months_text; ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        const slider = document.querySelector(".month-slider");
        const slides = document.querySelectorAll(".month-card");
        const dots = document.querySelectorAll(".pagination-dot");
        const prevArrow = document.querySelector(".slider-arrow.prev");
        const nextArrow = document.querySelector(".slider-arrow.next");
        
        const slideWidth = slides[0].offsetWidth + 20; // Width + gap
        const visibleSlides = window.innerWidth > 1100 ? 3 : window.innerWidth > 768 ? 2 : 1;
        let currentIndex = 0;
        const maxIndex = Math.ceil(slides.length / visibleSlides) - 1;

        function updateSlider() {
          slider.style.transform = `translateX(-${currentIndex * visibleSlides * slideWidth}px)`;
          
          // Update active dot
          dots.forEach(dot => dot.classList.remove("active"));
          const activeDotIndex = Math.min(
            Math.floor(currentIndex / (slides.length / dots.length)),
            dots.length - 1
          );
          dots[activeDotIndex].classList.add("active");
        }

        prevArrow.addEventListener("click", function() {
          if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
          } else {
            // Wrap to the end
            currentIndex = maxIndex;
            updateSlider();
          }
        });

        nextArrow.addEventListener("click", function() {
          if (currentIndex < maxIndex) {
            currentIndex++;
            updateSlider();
          } else {
            // Wrap to the beginning
            currentIndex = 0;
            updateSlider();
          }
        });

        dots.forEach((dot, index) => {
          dot.addEventListener("click", function() {
            const slideGroup = Math.floor(slides.length / dots.length);
            currentIndex = Math.min(index * slideGroup, maxIndex);
            updateSlider();
          });
        });

        // Add touch swipe functionality
        let touchStartX = 0;
        let touchEndX = 0;

        slider.addEventListener("touchstart", (e) => {
          touchStartX = e.changedTouches[0].screenX;
        }, false);

        slider.addEventListener("touchend", (e) => {
          touchEndX = e.changedTouches[0].screenX;
          handleSwipe();
        }, false);

        function handleSwipe() {
          if (touchEndX < touchStartX - 50) {
            // Swipe left, go next
            if (currentIndex < maxIndex) {
              currentIndex++;
              updateSlider();
            }
          } else if (touchEndX > touchStartX + 50) {
            // Swipe right, go prev
            if (currentIndex > 0) {
              currentIndex--;
              updateSlider();
            }
          }
        }

        // Initial setup
        updateSlider();
      });
    </script>

    <?php include('./includes/footer.php'); ?>
  </body>
  <script src="../js/new.js" defer></script>
  <script src="../js/header.js" defer></script>
</html>