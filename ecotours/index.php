<?php
require_once 'indexhandler.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php $resolvedAssetBase = !empty($assetBase) ? $assetBase : './'; ?>
    <base href="<?php echo htmlspecialchars($resolvedAssetBase, ENT_QUOTES); ?>">
    <link rel="canonical" href="https://virungajourneys.com/experiences" />

    <!-- Primary Meta Tags -->
    <title>Luxury Rwanda Safaris & Gorilla Trekking Expeditions | Virunga Ecotours</title>
    <meta name="description" content="Experience luxury gorilla trekking safaris and bespoke expeditions in Volcanoes National Park with Virunga Ecotours. Private journeys, expert guides, and authentic community impact.">
    <meta name="keywords" content="luxury Rwanda safari, luxury gorilla trekking Rwanda, private Rwanda safari, Volcanoes National Park tours, Virunga Ecotours, bespoke expeditions">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Luxury Rwanda Safaris & Gorilla Trekking | Virunga Ecotours">
    <meta property="og:description" content="Discover bespoke luxury safaris, gorilla trekking expeditions, and volcanic journeys in Rwanda with local experts.">
    <meta property="og:image" content="https://virungajourneys.com/img/about.jpeg">
    <meta property="og:url" content="https://virungajourneys.com/experiences">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Virunga Ecotours">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Luxury Rwanda Safaris & Gorilla Trekking | Virunga Ecotours">
    <meta name="twitter:description" content="Bespoke luxury safaris, mountain gorilla trekking, and community journeys across the Virunga Massif.">
    <meta name="twitter:image" content="https://virungajourneys.com/img/about.jpeg">

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Virunga Ecotours",
      "alternateName": "Virunga Ecotours Authentic Virunga Journeys",
      "url": "https://virungajourneys.com/experiences",
      "logo": "https://virungajourneys.com/ecotours/images/logos/logo.png",
      "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+250 784 513 435",
        "contactType": "customer service",
        "areaServed": ["RW", "UG", "CD"],
        "availableLanguage": "English"
      },
      "sameAs": [
        "https://www.tripadvisor.com/Attraction_Review-g317075-d21346700-Reviews-VIRUNGA_ECOTOURS-Ruhengeri_Musanze_District_Northern_Province.html"
      ]
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "url": "https://virungajourneys.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "https://virungajourneys.com/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <link
      rel="shortcut icon"
      href="./images/logos/icon.png?v=1.1"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"/>
    <link rel="stylesheet" href="css/earthy-theme.css" />
    <link rel="stylesheet" href="css/header.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/new.css" />
    <link rel="stylesheet" href="css/kids.css" />
     <style>
      .section-subtitle {
        text-align: justify; max-width: 700px; margin: 0 auto 40px; color: #666; line-height: 1.6;
      }
      
      /* Map Section Styles */
        .map-section {
          padding: 0;
          background-color: #fff;
          display:flex;
          align-items: center;
          justify-content: center;
        }

        .map-container {
          align-items: center;
          position: relative;
          width: 93%;
          height: 80vh;
          margin: 0;
          box-shadow: none;
          border-radius: 0;
          overflow: hidden;
          padding: 0;
          background-color: #f8f8f8;
        }

        .map-container iframe {
          width: 100%;
          height: 100%;
          border: none;
        }
        
        
       
    </style>
  </head>
  <body>
  <?php include 'includes/header.php'; ?>
    <main>
      <div class="hero-section">
        <div class="hero-carousel">
          <div class="carousel-container">
            <?php foreach ($hero_slides as $index => $slide): ?>
            <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
              <div class="slide-content">
                <h1><?php echo htmlspecialchars($slide['title']); ?></h1>
                <p style="text-align: left;"><?php echo htmlspecialchars($slide['description']); ?></p>
              </div>
              <img src="./admin/<?php echo htmlspecialchars($slide['image_url']); ?>" alt="<?php echo htmlspecialchars($slide['title']); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
            </div>
            <?php endforeach; ?>
          </div>

          <div class="carousel-controls">
            <button class="carousel-arrow prev-slide" aria-label="Previous slide">
              <i class="fas fa-chevron-left"></i>
            </button>
            <div class="carousel-indicators">
              <?php foreach ($hero_slides as $index => $slide): ?>
              <button class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>" aria-label="Go to slide <?php echo $index + 1; ?>"></button>
              <?php endforeach; ?>
            </div>
            <button class="carousel-arrow next-slide" aria-label="Next slide">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>
        </div>
      </div>
      <section class="tours-section">
        <div class="container">
          <div class="section-header">
            <!--<h1>Authentic Trips Crafted By Experts</h1>-->
            <p class="section-subtitle">
             We design focused expedition journeys across the Virunga region, shaped by access, rhythm, and depth not the number of activities.

Virunga Ecotours is a Rwanda-based immersive travel brand curating wildlife, culture, and conservation journeys across the Virunga landscape.

We are a specialist operator crafting intentional expeditions across Rwanda, Uganda, and DR Congo. No generic packages only defined journeys that connect travelers to people, nature, and meaning.

            </p>
          </div>

          
          <section class="virunga-gallery-wrapper">
            <div class="virunga-grid-container">
              <div class="virunga-grid">
                <div class="grid-item large">
                  <div class="grid-image-wrapper">
                    <img src="./images/hero/7.jpg" alt="Virunga Primates Encounter">
                  </div>
                  <div class="grid-overlay">
                    <span class="grid-tag">Featured</span>
                    <div class="grid-text">
                      <h3>Virunga Primates Encounter</h3>
                      <p>A rare journey into the world of mountain gorillas and golden monkeys in the volcanic forests of the Virunga Mountains.</p>
                    </div>
                  </div>
                </div>
                <div class="grid-right">
                  <div class="grid-item">
                    <div class="grid-image-wrapper">
                      <img src="./images/hero/volucanic.jpeg" alt="Volcanic Peaks Expedition">
                    </div>
                    <div class="grid-overlay">
                      <span class="grid-tag">Adventure</span>
                      <div class="grid-text">
                        <h3>Volcanic Peaks Expedition</h3>
                        <p>A guided ascent into the dramatic volcanic landscapes of the Virunga range, offering breathtaking panoramic views and raw natural immersion.</p>
                      </div>
                    </div>
                  </div>
                  <div class="grid-item">
                    <div class="grid-image-wrapper">
                      <img src="./images/hero/culturedance.jpeg" alt="Mountain Culture Immersion">
                    </div>
                    <div class="grid-overlay">
                      <span class="grid-tag">Cultural</span>
                      <div class="grid-text">
                        <h3>Mountain Culture Immersion</h3>
                        <p>A deeply human experience with local mountain communities, traditions, storytelling, and daily life in the Virunga region.</p>
                      </div>
                    </div>
                  </div>
                  <div class="grid-item">
                    <div class="grid-image-wrapper">
                      <img src="./images/hero/her2.jpg" alt="Virunga Landscape & Lakes Journey">
                    </div>
                    <div class="grid-overlay">
                      <span class="grid-tag">Nature</span>
                      <div class="grid-text">
                        <h3>Bird Watching Expedition</h3>
                        <p>A unique birdwatching experience in the Virunga Mountains, where you can witness a diverse array of birds in their natural habitats</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- Popular Tours Section -->
      <section class="popular-tours-section" style="margin-top: -2rem;">
        <div class="container">
          <div class="section-title">
            <h2>Explore Our Signature Experiences</h2>
            <div class="title-underline"></div>
          </div>
          

          <div class="tours-cards">
            <?php if (empty($tours)): ?>
              <p class="no-tours">No adventure tours available at the moment.</p>
            <?php else: ?>
              <?php foreach ($tours as $tour): ?>
                <div class="tour-card">
                  <div class="tour-card-image">
                    <img
                      src="<?php echo htmlspecialchars($tour['cover_image_path']); ?>"
                      alt="<?php echo htmlspecialchars($tour['title']); ?>"
                    ondragstart="return false;" oncontextmenu="return false;"/>
                    <div class="tour-badge"><?php echo strtoupper(htmlspecialchars($tour['category'])); ?></div>
                    <div class="tour-offer">AVAILABLE</div>
                  </div>
                  <div class="tour-card-content">
                    <div class="tour-tags">
                      <span class="tour-tag">GROUP TOUR</span>
                      <span class="tour-tag">WILDLIFE</span>
                    </div>
                    <div class="tour-tags"></div>
                    <h3><?php echo htmlspecialchars($tour['title']); ?></h3>
                    <div class="tour-duration"><?php echo $tour['days_count']; ?> DAY<?php echo $tour['days_count'] > 1 ? 'S' : ''; ?></div>
                    <p><?php echo htmlspecialchars(substr($tour['short_description'], 0, 200)) . '...'; ?></p>
                    <a href="./pages/itenaryopen.php?id=<?php echo htmlspecialchars($tour['tour_id']); ?>" class="read-more-btn">BOOK NOW</a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

        </div>
      </section>
          
          <div class="tours-grid">
            <div class="section-title">
              <h2>Tailor Your Journey Around the Virunga Mountains</h2>
            </div>
            
            <div class="features-row">
              <div class="feature-col">
                <div class="feature-img">
                  <img src="./admin/<?php echo htmlspecialchars($dest_img1); ?>" alt="<?php echo htmlspecialchars($country1); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
                </div>
                <div class="feature-overlay">
                  <h3><?php echo htmlspecialchars($country1); ?></h3>
                  <p><?php echo htmlspecialchars($desc1); ?></p>
                </div>
              </div>

              <div class="feature-col">
                <div class="feature-img">
                  <img src="./admin/<?php echo htmlspecialchars($dest_img2); ?>" alt="<?php echo htmlspecialchars($country2); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
                </div>
                <div class="feature-overlay">
                  <h3><?php echo htmlspecialchars($country2); ?></h3>
                  <p><?php echo htmlspecialchars($desc2); ?></p>
                </div>
              </div>

              <div class="feature-col">
                <div class="feature-img">
                  <img src="./admin/<?php echo htmlspecialchars($dest_img3); ?>" alt="<?php echo htmlspecialchars($country3); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
                </div>
                <div class="feature-overlay">
                  <h3><?php echo htmlspecialchars($country3); ?></h3>
                  <p><?php echo htmlspecialchars($desc3); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> 
    </div>
   
    <section class="young-explorers-section">
        <div class="container">
            <div class="content-wrapper-kids">
                <div class="content-left-kids">
                    <h2 class="section-title">Pursuit of Feeling</h2>

                    <p class="content-text-kids">
                        The true essence of travel lies beyond movement from one place to another. It lives in the moments that take your breath away, in the quiet intensity of new landscapes, and in the emotional connection that transforms a journey into something unforgettable. These are the experiences that stay with you subtle, powerful, and lasting.<br><br>

The Pursuit of Feeling is a refined collection of journeys, stories, and elevated travel experiences shaped with intention and depth. Each one is designed to awaken a sense of wonder, foster genuine connection, and leave a lasting impression that goes far beyond the journey itself.<br><br>

It is never simply about where you go it is about how the experience stays with you.
                    </p>


              <a href="./pages/itenary.php" class="enquire-btn">
              Learn More
              <i class="fas fa-arrow-right"></i>
            </a>
                </div>

                <div class="image-right">
                    <div class="image-placeholder">
                        
                            <img src="./images/hero/four.jpeg "
                                 alt=" Explorers"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                       
                    </div>
                    <div class="image-overlay">
                        <div class="overlay-text">More than a Destination</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

      <!-- Destinations Section -->
      <section class="destinations-section" style="margin-bottom: -1.5rem;">
        <div class="container">
          <div class="section-title">
            <h2>Volcanoes & Forest Expedition</h2>
          </div>
          <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 20px; color: #333;">
            <p>Curated experiences across the Virunga Mountains, with selected extensions across East Africa.</p>
          </div>
         
          <div class="main-destinations-grid">
            <?php foreach ($attractions as $attraction): ?>
            <a href="./pages/attraction.php?id=<?php echo htmlspecialchars($attraction['id']); ?>" class="destination-item">
              <img
                src="./admin/<?php echo htmlspecialchars($attraction['image_url']); ?>"
                alt="<?php echo htmlspecialchars($attraction['title']); ?>"
              ondragstart="return false;" oncontextmenu="return false;"/>
              <div class="destination-overlay">
                <h3><?php echo htmlspecialchars($attraction['title']); ?></h3>
              </div>
            </a>
            <?php endforeach; ?>
          </div>

          <div class="enquiry-banner">
            <a href="./pages/build.php" class="enquire-btn">
              Book Now
              <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </section>
   
      <!-- Tailor-made Banner -->
      <section class="tailor-made-banner">
        <div class="container">
          <div class="banner-content">
            <h2>Plan your Custom Trip now </h2>

            <p>
              We are dedicated to listening to your preferences and
              requirements, crafting a journey that fits your budget while
              integrating local insights from Rwanda, Uganda, and DR Congo. Our
              objective is to design a personalized dream trip that matches your
              vision perfectly. You can participate in the planning process to
              any extent you desire, and we are excited to take on special
              requests. Uncover the advantages of traveling with us!
            </p>
            <div class="banner-actions">
              <a href="./pages/build.php" class="enquire-btn"
                >Book Now</a
              >
              <div class="call-us">
                <span >Call or Whatsapp</span>
                <a href="tel:+250784513435" class="phone-number" >
                  <i class="fas fa-phone-alt"></i>
                  +(250)784513435
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Popular Tailor-Made Holidays Section -->
      <section class="tailor-made-holidays">
        <div class="container">
          <div class="section-title">
            <h2>Featured Journeys </h2>
          </div>
          

          <div class="holiday-cards">
            <?php if (!empty($random_tours)): ?>
              <?php foreach ($random_tours as $tour): ?>
                <div class="holiday-card">
                  <div class="card-image">
                    <img src="<?php echo htmlspecialchars($tour['cover_image_path']); ?>" alt="<?php echo htmlspecialchars($tour['title']); ?>" ondragstart="return false;" oncontextmenu="return false;"/>
                    <div class="tour-offer">AVAILABLE</div>
                  </div>

                  <div class="card-content">
                    <div class="tour-tags">
                      <span class="tour-tag">TAILOR MADE</span>
                      <span class="tour-tag">WILDLIFE</span>
                    </div>
                    <div class="card-title">
                      <h3><a href="./pages/itenaryopen.php?id=<?php echo htmlspecialchars($tour['tour_id']); ?>"><?php echo htmlspecialchars($tour['title']); ?></a></h3>
                    </div>
                    <div class="card-details"><?php echo $tour['days_count']; ?> DAYS</div>
                    <div class="card-price"></div>
                    <div class="card-description">
                      <?php echo htmlspecialchars(substr($tour['short_description'], 0, 200)) . '...'; ?>
                    </div>
                    <div class="card-button">
                      <a href="./pages/itenaryopen.php?id=<?php echo htmlspecialchars($tour['tour_id']); ?>" class="read-more-btn">BOOK NOW</a>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No tours available at the moment.</p>
            <?php endif; ?>
          </div>

          <div class="view-all-blogs" style="text-align: center; margin-top: 40px;">
            <a href="./pages/itenary.php" class="btn btn-secondary" style="padding: 12px 25px; background-color: #555; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background-color 0.3s ease;">View All Tours</a>
          </div>
        </div>
      </section>

      <!-- youtube video -->
      <!-- youtube video -->
      <?php
        // Assuming $conn is the database connection from indexhandler.php
        $about_sql = "SELECT title, slide_description, youtube_url FROM home_about ORDER BY id DESC LIMIT 1";
        $about_result = $conn->query($about_sql);

        if ($about_result->num_rows > 0) {
            $about_row = $about_result->fetch_assoc();
            $about_title = $about_row['title'];
            $about_description = $about_row['slide_description'];
            $about_youtube_url = $about_row['youtube_url'];
        ?>
      <section class="middle-about-section fade-in-up">
        <div class="container">
          <div class="video-flex-container">
            <!-- Left side: Video container -->
            <div class="video-container fade-in-left">
              <div class="video-wrapper">
                <iframe width="560" height="315" src="<?php echo htmlspecialchars($about_youtube_url); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
            </div>

            <!-- Right side: About Us content -->
            <div class="video-content-container fade-in-right">
              <div class="content-wrapper">
                <div class="content-header">
                  <h2><?php echo htmlspecialchars($about_title); ?></h2>
                </div>

                <div class="content-body">
                  <p>
                    <?php echo nl2br(htmlspecialchars($about_description)); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <?php
        } else {
            // Optional: what to display if no data is found
        }
      ?>

      <!-- Blog Section -->
      <section class="blog-section" style="padding: 40px 0;">
        <div class="container">
            <h2>Blogs And Tips</h2>
          <p class="section-subtitle" style=" text-align: center; max-width: 700px; margin: 0 auto 40px; color: #666; line-height: 1.6;">
            Stay updated with the latest travel insights, tips, and stories from
            the heart of the Virunga Mountains. Explore conservation news, cultural
            highlights, and expert advice for your next eco-adventure.
          </p>

          <div class="blog-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php if (empty($blogs)): ?>
              <p class="no-blogs" style="grid-column: 1 / -1; text-align: center; color: #777;">No blog posts available at the moment.</p>
            <?php else: ?>
              <?php foreach ($blogs as $blog): ?>
                <div class="blog-card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow: hidden; transition: transform 0.3s ease;">
                  <div class="blog-card-image" style="position: relative; height: 200px; overflow: hidden;">
                    <img src="./admin/images/blog/covers/<?php echo htmlspecialchars(stripslashes($blog['cover_image'])); ?>" alt="<?php echo htmlspecialchars(stripslashes($blog['title'])); ?>" style="width: 100%; height: 100%; object-fit: cover; display: block;" ondragstart="return false;" oncontextmenu="return false;"/>
                    <span class="blog-category" style="position: absolute; top: 10px; left: 10px; background-color: var(--primary-brown); color: white; padding: 5px 10px; border-radius: 4px; font-size: 0.8em; font-weight: bold;"><?php echo htmlspecialchars($blog['category_name']); ?></span>
                  </div>
                  <div class="blog-card-content" style="padding: 20px;">
                    <h3 class="blog-title" style="font-size: 1.2em; margin-bottom: 10px; color: #333;">
                      <a href="./pages/blog-single.php?id=<?php echo $blog['blog_id']; ?>" style="text-decoration: none; color: inherit;"><?php echo htmlspecialchars(stripslashes($blog['title'])); ?></a>
                    </h3>
                    <p class="blog-excerpt" style="font-size: 0.9em; color: #666; line-height: 1.5; margin-bottom: 15px;"><?php echo htmlspecialchars(stripslashes($blog['short_intro'])); ?></p>
                    <div class="blog-meta" style="font-size: 0.8em; color: #888; margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px;">
                      <span class="blog-author" style="display: inline-flex; align-items: center;"><i class="fas fa-user" style="margin-right: 5px;"></i> <?php echo htmlspecialchars($blog['author']); ?></span>
                      <span class="blog-date" style="display: inline-flex; align-items: center;"><i class="fas fa-calendar-alt" style="margin-right: 5px;"></i> <?php echo date('M d, Y', strtotime($blog['created_at'])); ?></span>
                      <span class="blog-read-time" style="display: inline-flex; align-items: center;"><i class="fas fa-clock" style="margin-right: 5px;"></i> <?php echo htmlspecialchars($blog['read_minutes']); ?> min read</span>
                    </div>
                    <a href="./pages/blogopen.php?id=<?php echo $blog['blog_id']; ?>" class="read-more-btn">Read More <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>

          <div class="view-all-blogs" style="text-align: center; margin-top: 40px;">
            <a href="./pages/blog.php" class="btn btn-secondary" style="padding: 12px 25px; background-color: #555; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; transition: background-color 0.3s ease;">View All Blogs</a>
          </div>
        </div>
      </section>
      <br><br> <br><br> <br><br>
       <div class="dest-contain">
      <h1 class="title">The Virunga Seasons</h1>
      
      <div class="travel-grid" id="travel-grid">
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=january">
            <img src="./images/month/1 (1).jpg" alt="January - Tropical Beach" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">January</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=february">
            <img src="./images/month/1 (2).jpg" alt="February - Rio de Janeiro" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">February</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=march">
            <img src="./images/month/1 (3).jpg" alt="March - Japan Cherry Blossoms" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">March</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=april">
            <img src="./images/month/1 (4).jpg" alt="April - Abu Simbel, Egypt">
            <div class="month-name">April</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=may">
            <img src="./images/month/1 (5).jpg" alt="May - Porto, Portugal" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">May</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=june">
            <img src="./images/month/1 (6).jpg" alt="June - Machu Picchu, Peru">
            <div class="month-name">June</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=july">
            <img src="./images/month/1 (7).jpg" alt="July - Lake Bled, Slovenia" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">July</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=august">
            <img src="./images/month/1 (8).jpg" alt="August - Tallinn, Estonia" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">August</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=september">
            <img src="./images/month/1 (9).jpg" alt="September - Cartagena, Colombia" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">September</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=october">
            <img src="./images/month/1 (10).jpg" alt="October - Cappadocia, Turkey" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">October</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=november">
            <img src="./images/month/1 (11).jpg" alt="November - Autumn Forest">
            <div class="month-name">November</div>
          </a>
        </div>
        
        <div class="month-card">
          <a href="./pages/travelmonth.php?month=december">
            <img src="./images/month/1 (12).jpg" alt="December - Uluru, Australia" ondragstart="return false;" oncontextmenu="return false;">
            <div class="month-name">December</div>
          </a>
        </div>
      </div>
      
      <div class="month-indicator" id="month-indicator">January</div>
      
      <div class="navigation">
        <button class="nav-btn" id="prev-btn">←</button>
        <button class="nav-btn" id="next-btn">→</button>
      </div>
  </div>
   <br><br> <br><br> <br><br>
   <div class="last-cont">

   <section class="lcta-section">
      <div class="lcta-content">
        <h2 class="lcta-title">Begin your next journey into the Virunga landscape ?</h2>
        <p class="lcta-text">Let us help you create memories that last a lifetime. Whether you're seeking cultural immersion, natural wonders, or historical journeys, our expert team is ready to craft your perfect travel experience.</p>
        <div class="lcta-buttons">
          <a href="./pages/build.php" class="lcta-primary">Book Now</a>
            <a href="./pages/contactus.php" class="lcta-secondary"><i class="fas fa-phone"></i> Reach To Us </a>
        </div>
      </div>
    </section>
    <!-- last-her Section -->
    <section class="last-her">
      <div class="last-her-content">
        <div class="top-image">
          <img src="./images/hero/night.jpg" alt="" srcset="" ondragstart="return false;" oncontextmenu="return false;">
        </div>
        <br><br><br>
        <!-- Why Travel With Us Section -->
    
        <div class="last-her-image-last-cont">
          <div class="de-image">
            <img src="./images/last/last (3).jpg" alt="India destination" ondragstart="return false;" oncontextmenu="return false;">
          </div>
          <div class="de-image">
            <img src="./images/last/l.jpg" alt="Costa Rica destination" ondragstart="return false;" oncontextmenu="return false;">
          </div>
        </div>
      </div>
    </section>

    <section class="features">
      <h2 class="features-title">Why Choose Us </h2>
      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3 class="feature-title">Small Group Travel</h3>
          <p class="feature-description">The most memorable journeys are rarely rushed or crowded.
Our carefully designed experiences prioritize intimacy, flexibility, and genuine connection creating space to engage more deeply with landscapes, cultures, and the rhythm of life across the Virunga region.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-globe"></i>
          </div>
          <h3 class="feature-title">Locally Guided Tours</h3>
          <p class="feature-description">The most meaningful journeys are led by those who truly understand the land.
Our team combines deep regional knowledge with thoughtful service to create experiences that feel immersive, seamless, and unforgettable.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shield-alt"></i>
          </div>
          <h3 class="feature-title">Responsible Travel</h3>
          <p class="feature-description">The most memorable journeys are those that create connection beyond the traveler.
Through locally grounded experiences and thoughtful partnerships, our journeys help support the communities, traditions, and landscapes that define the Virunga region creating travel experiences with depth, purpose, and enduring value.</p>
        </div>
        
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-history"></i>
          </div>
          <h3 class="feature-title">Tailor-Made Experiences</h3>
          <p class="feature-description">The finest travel experiences feel natural, personal, and perfectly paced.
Our team works closely with every traveler to design journeys that reflect individual interests and meaningful experiences — while providing attentive support throughout every stage of the adventure.</p>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    
  </div>

      <section class="partners-section">
        <h2 class="section-title">Our Partners</h2>
        <div class="partners-slider">
          <button class="slider-control slider-prev">&#10094;</button>
          <div class="slider-container" id="sliderContainer">
            <?php foreach ($partners as $partner): ?>
              <div class="slider-item">
                <?php if (!empty($partner['web_url'])): ?>
                  <a href="<?php echo htmlspecialchars($partner['web_url']); ?>" target="_blank" rel="noopener noreferrer">
                <?php endif; ?>
                  <img src="./admin/<?php echo htmlspecialchars($partner['logo_url']); ?>" alt="Partner Logo" ondragstart="return false;" oncontextmenu="return false;"/>
                <?php if (!empty($partner['web_url'])): ?>
                  </a>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="slider-control slider-next">&#10095;</button>
        </div>
      </section>
     
<?php include 'includes/footer.php'; ?>
    </main>

  
  <script src="js/script.js" defer></script>
 
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/ScrollTrigger.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
  <script src="js/new.js" defer></script>
  <script src="js/last.js" defer></script>
  </body>
</html>
