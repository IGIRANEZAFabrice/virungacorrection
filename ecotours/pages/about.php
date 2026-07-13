<?php
// Database connection and data fetching
require_once('../admin/config/connection.php');

// Fetch all about page data
$hero_sql = "SELECT * FROM about_hero WHERE is_active = 1 LIMIT 1";
$hero_result = $conn->query($hero_sql);
$hero_data = $hero_result->fetch_assoc();

$story_sql = "SELECT * FROM about_story WHERE is_active = 1 LIMIT 1";
$story_result = $conn->query($story_sql);
$story_data = $story_result->fetch_assoc();

$impact_sql = "SELECT * FROM about_impact WHERE is_active = 1 LIMIT 1";
$impact_result = $conn->query($impact_sql);
$impact_data = $impact_result->fetch_assoc();

// Fetch impact stats using bind_result
$impact_stats_sql = "SELECT * FROM about_impact_stats WHERE impact_id = ? AND is_active = 1 ORDER BY display_order";
$impact_stats_stmt = $conn->prepare($impact_stats_sql);
$impact_stats_stmt->bind_param("i", $impact_data['impact_id']);
$impact_stats_stmt->execute();
$impact_stats_stmt->store_result();
$impact_stats_meta = $impact_stats_stmt->result_metadata();

$impact_stats = [];
$bind_vars = [];
$fields = [];
while ($field = $impact_stats_meta->fetch_field()) {
    $bind_vars[] = &$impact_stats_row[$field->name];
    $fields[] = $field->name;
}
call_user_func_array([$impact_stats_stmt, 'bind_result'], $bind_vars);

while ($impact_stats_stmt->fetch()) {
    $row = [];
    foreach ($fields as $f) {
        $row[$f] = $impact_stats_row[$f];
    }
    $impact_stats[] = $row;
}

// Team section
$team_section_sql = "SELECT * FROM about_team_section WHERE is_active = 1 LIMIT 1";
$team_section_result = $conn->query($team_section_sql);
$team_section_data = $team_section_result->fetch_assoc();

// Team members
$team_members_sql = "SELECT * FROM about_team_members WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$team_members_stmt = $conn->prepare($team_members_sql);
$team_members_stmt->bind_param("i", $team_section_data['section_id']);
$team_members_stmt->execute();
$team_members_stmt->store_result();
$team_members_meta = $team_members_stmt->result_metadata();

$team_members = [];
$bind_vars = [];
$fields = [];
while ($field = $team_members_meta->fetch_field()) {
    $bind_vars[] = &$team_members_row[$field->name];
    $fields[] = $field->name;
}
call_user_func_array([$team_members_stmt, 'bind_result'], $bind_vars);

while ($team_members_stmt->fetch()) {
    $row = [];
    foreach ($fields as $f) {
        $row[$f] = $team_members_row[$f];
    }
    $team_members[] = $row;
}

// Values section
$values_section_sql = "SELECT * FROM about_values_section WHERE is_active = 1 LIMIT 1";
$values_section_result = $conn->query($values_section_sql);
$values_section_data = $values_section_result->fetch_assoc();

// Values
$values_sql = "SELECT * FROM about_values WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$values_stmt = $conn->prepare($values_sql);
$values_stmt->bind_param("i", $values_section_data['section_id']);
$values_stmt->execute();
$values_stmt->store_result();
$values_meta = $values_stmt->result_metadata();

$values = [];
$bind_vars = [];
$fields = [];
while ($field = $values_meta->fetch_field()) {
    $bind_vars[] = &$values_row[$field->name];
    $fields[] = $field->name;
}
call_user_func_array([$values_stmt, 'bind_result'], $bind_vars);

while ($values_stmt->fetch()) {
    $row = [];
    foreach ($fields as $f) {
        $row[$f] = $values_row[$f];
    }
    $values[] = $row;
}

// Gallery section
$gallery_section_sql = "SELECT * FROM about_gallery_section WHERE is_active = 1 LIMIT 1";
$gallery_section_result = $conn->query($gallery_section_sql);
$gallery_section_data = $gallery_section_result->fetch_assoc();

// Gallery items
$gallery_sql = "SELECT * FROM about_gallery WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$gallery_stmt = $conn->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $gallery_section_data['section_id']);
$gallery_stmt->execute();
$gallery_stmt->store_result();
$gallery_meta = $gallery_stmt->result_metadata();

$gallery_items = [];
$bind_vars = [];
$fields = [];
while ($field = $gallery_meta->fetch_field()) {
    $bind_vars[] = &$gallery_row[$field->name];
    $fields[] = $field->name;
}
call_user_func_array([$gallery_stmt, 'bind_result'], $bind_vars);

while ($gallery_stmt->fetch()) {
    $row = [];
    foreach ($fields as $f) {
        $row[$f] = $gallery_row[$f];
    }
    $gallery_items[] = $row;
}

// CTA
$cta_sql = "SELECT * FROM about_cta WHERE is_active = 1 LIMIT 1";
$cta_result = $conn->query($cta_sql);
$cta_data = $cta_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Congo Virunga National Park Tours | Eastern Congo Ecotours | Virunga Ecotours</title>
<meta name="description" content="Discover the wild beauty of Virunga National Park in Congo. Authentic ecotours combining wildlife encounters with community engagement and conservation support.">
<meta name="keywords" content="Virunga National Park Congo, Congo ecotours, eastern Congo wildlife, Congo gorilla tours, DRC tourism">
    <title>About Us | EcoTours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/about.css">
</head>
<body>
    <?php include "./includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero" id="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('../admin/images/about/<?php echo htmlspecialchars($hero_data['background_image']); ?>');">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hero_data['title']); ?></h1>
            <p><?php echo htmlspecialchars($hero_data['subtitle']); ?></p>
        </div>
        <div class="scroll-indicator" id="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="our-story" id="our-story">
        <div class="container">
            <div class="story-container">
                <div class="story-text" data-animation="fadeInLeft">
                    <h2><?php echo htmlspecialchars($story_data['section_title']); ?></h2>
                    <p><?php echo htmlspecialchars($story_data['paragraph_1']); ?></p>
                    <p><?php echo htmlspecialchars($story_data['paragraph_2']); ?></p>
                    <p><?php echo htmlspecialchars($story_data['paragraph_3']); ?></p>
                    <a href="<?php echo htmlspecialchars($story_data['button_link']); ?>" class="button"><?php echo htmlspecialchars($story_data['button_text']); ?></a>
                </div>
                <div class="story-image" data-animation="fadeInRight">
                    <img src="../admin/images/about/<?php echo htmlspecialchars($story_data['story_image']); ?>" alt="EcoTours journey">
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Stats Section -->
    <section class="impact" id="impact">
        <div class="container">
            <h2><?php echo htmlspecialchars($impact_data['section_title']); ?></h2>
            <p class="section-intro"><?php echo htmlspecialchars($impact_data['section_intro']); ?></p>

            <div class="stats-container">
                <?php foreach ($impact_stats as $index => $stat): ?>
                    <div class="stat-item" data-animation="fadeIn" data-delay="<?php echo ($index + 1) * 0.2; ?>">
                        <i class="<?php echo htmlspecialchars($stat['icon_class']); ?>"></i>
                        <div class="stat-count" data-count="<?php echo $stat['stat_count']; ?>">0</div>
                        <div class="stat-title"><?php echo htmlspecialchars($stat['stat_title']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team" id="team">

        <div class="container">
            <h2><?php echo htmlspecialchars($team_section_data['section_title']); ?></h2>
            <p class="section-intro"><?php echo htmlspecialchars($team_section_data['section_intro']); ?></p>

            <!-- Team Introduction -->
            <div class="team-introduction">
                <div class="team-intro-content">
                    <div class="team-intro-text">
                        <h3>Introduction to the Team</h3>
                        <p>Virunga Ecotours is led by a multidisciplinary team that blends tourism expertise, creative communication, and strong local engagement. The group unites professional planners, hospitality specialists, and storytellers who together present the Virunga Massif not only as a destination, but as a living cultural and ecological landscape. Their strength lies in combining academic knowledge, creative media skills, and practical experience to provide travelers with seamless service and meaningful encounters.</p>
                    </div>

                    <div class="team-composition">
                        <h4>Composition of the Team</h4>
                        <div class="team-roles-grid">
                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-route"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Tour Planners and Tour Managers</h5>
                                    <p>Professionals who design customized itineraries, manage logistics, and ensure that every aspect of the journey—from permits to transport—is well organized.</p>
                                </div>
                            </div>

                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Photographers and Content Makers</h5>
                                    <p>Skilled in capturing both landscapes and community moments, they create high-quality images and stories that preserve travelers' memories and showcase the beauty of the Virunga region.</p>
                                </div>
                            </div>

                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Social Media and Website Managers</h5>
                                    <p>Digital specialists responsible for maintaining Virunga Ecotours' online presence, updating platforms with inspiring content, and ensuring travelers worldwide can connect easily with services.</p>
                                </div>
                            </div>

                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Marketing Coordinators and Social Influencers</h5>
                                    <p>A team that amplifies visibility through both traditional outreach and modern influencer-driven campaigns, making the Virunga Massif a recognizable brand in global tourism.</p>
                                </div>
                            </div>

                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Community Hosts and Cultural Ambassadors</h5>
                                    <p>Residents who welcome visitors into homestays, cultural programs, and daily life, offering authentic interactions rooted in local traditions.</p>
                                </div>
                            </div>

                            <div class="role-item">
                                <div class="role-icon">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                </div>
                                <div class="role-content">
                                    <h5>Training and Mentorship Staff</h5>
                                    <p>Educators and facilitators who prepare youth and aspiring professionals in hospitality, guiding, and tourism management through the Virunga Ecotours Training Institute.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="team-approach">
                        <h4>Professionalism and Academic Approach</h4>
                        <p>Each team member brings a balance of academic background and practical skills. Tour planners and managers often have formal training in tourism and hospitality, while content creators and media managers apply knowledge of digital communication, marketing, and design. Community hosts contribute lived experience and cultural depth, ensuring that both scholarly insight and authentic storytelling are woven into every encounter.</p>
                    </div>

                    <div class="team-meeting">
                        <h4>How You Can Meet the Team</h4>
                        <div class="meeting-ways">
                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fas fa-bed"></i>
                                </div>
                                <div class="meeting-content">
                                    <h6>At Virunga Homestays</h6>
                                    <p>Upon arrival, community hosts and tour managers personally welcome guests and provide orientation.</p>
                                </div>
                            </div>

                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fas fa-hiking"></i>
                                </div>
                                <div class="meeting-content">
                                    <h6>During Organized Activities</h6>
                                    <p>Tour planners and cultural ambassadors guide guests through community-based experiences, workshops, and excursions.</p>
                                </div>
                            </div>

                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fas fa-laptop"></i>
                                </div>
                                <div class="meeting-content">
                                    <h6>Through Digital Platforms</h6>
                                    <p>Photographers, content makers, and social media managers share stories online, offering travelers a way to connect before and after their visit.</p>
                                </div>
                            </div>

                            <div class="meeting-item">
                                <div class="meeting-icon">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <div class="meeting-content">
                                    <h6>In Marketing and Public Engagements</h6>
                                    <p>Guests may also meet marketing staff and influencers during special events, campaigns, or collaborative community projects.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="team-container">
                <?php foreach ($team_members as $index => $member): ?>
                    <div class="team-card" data-animation="fadeIn" data-delay="<?php echo ($index + 1) * 0.2; ?>">
                        <div class="team-image">
                            <img src="../admin/images/about/team/<?php echo htmlspecialchars($member['image']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        </div>
                        <div class="team-info">
                            <h3><?php echo htmlspecialchars($member['name']); ?></h3>
                            <div class="team-role"><?php echo htmlspecialchars($member['role']); ?></div>
                            <p class="team-bio"><?php echo htmlspecialchars($member['bio']); ?></p>
                            <div class="team-socials">
                                <?php if (!empty($member['linkedin_url']) && $member['linkedin_url'] !== '#'): ?>
                                    <a href="<?php echo htmlspecialchars($member['linkedin_url']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($member['twitter_url']) && $member['twitter_url'] !== '#'): ?>
                                    <a href="<?php echo htmlspecialchars($member['twitter_url']); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($member['instagram_url']) && $member['instagram_url'] !== '#'): ?>
                                    <a href="<?php echo htmlspecialchars($member['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="values" id="values">
        <div class="container">
            <h2><?php echo htmlspecialchars($values_section_data['section_title']); ?></h2>
            <p class="section-intro"><?php echo htmlspecialchars($values_section_data['section_intro']); ?></p>

            <div class="values-container">
                <?php foreach ($values as $index => $value): ?>
                    <div class="value-item" data-animation="fadeIn" data-delay="<?php echo ($index + 1) * 0.2; ?>">
                        <div class="value-icon">
                            <i class="<?php echo htmlspecialchars($value['icon_class']); ?>"></i>
                        </div>
                        <h3 class="value-title"><?php echo htmlspecialchars($value['title']); ?></h3>
                        <p class="value-description"><?php echo htmlspecialchars($value['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery" id="gallery">
        <div class="container">
            <h2><?php echo htmlspecialchars($gallery_section_data['section_title']); ?></h2>
            <p class="section-intro"><?php echo htmlspecialchars($gallery_section_data['section_intro']); ?></p>

            <div class="gallery-grid">
                <?php foreach ($gallery_items as $index => $item): ?>
                    <div class="gallery-item" data-animation="fadeIn" data-delay="<?php echo ($index + 1) * 0.1; ?>">
                        <img src="../admin/images/about/gallery/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['alt_text']); ?>">
                        <div class="gallery-overlay">
                            <h3 class="gallery-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta" id="contact" style="background-color: var(--neutral-beige);">
        <div class="container">
            <div class="cta-content" data-animation="fadeIn">
                <h2><?php echo htmlspecialchars($cta_data['section_title']); ?></h2>
                <p><?php echo htmlspecialchars($cta_data['section_description']); ?></p>
                <a href="<?php echo htmlspecialchars($cta_data['button_link']); ?>" class="button"><?php echo htmlspecialchars($cta_data['button_text']); ?></a>

                <div class="social-links">
                    <?php if (!empty($cta_data['facebook_url']) && $cta_data['facebook_url'] !== '#'): ?>
                        <a href="<?php echo htmlspecialchars($cta_data['facebook_url']); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($cta_data['instagram_url']) && $cta_data['instagram_url'] !== '#'): ?>
                        <a href="<?php echo htmlspecialchars($cta_data['instagram_url']); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($cta_data['twitter_url']) && $cta_data['twitter_url'] !== '#'): ?>
                        <a href="<?php echo htmlspecialchars($cta_data['twitter_url']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php" ?>

    <!-- Modal for Gallery -->
    <div class="modal" id="image-modal">
        <span class="close-modal" id="close-modal">
            <i class="fas fa-times"></i>
        </span>
        <div class="modal-content">
            <img id="modal-image" src="" alt="Gallery Image">
        </div>
    </div>

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

        // Animations on Scroll
        function checkForAnimations() {
            const animatedElements = document.querySelectorAll('[data-animation]');

            animatedElements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const viewportHeight = window.innerHeight;

                // If element is in viewport
                if (elementPosition < viewportHeight - 100) {
                    // Add animation class
                    element.classList.add('animated');

                    // For stat counters, start counting if in impact section
                    if (element.closest('.impact')) {
                        const counters = element.querySelectorAll('.stat-count');
                        counters.forEach(counter => startCounting(counter));
                    }
                }
            });
        }

        // Initial check for animations
        window.addEventListener('load', () => {
            setTimeout(checkForAnimations, 500);
        });

        // Stat Counter Animation
        function startCounting(counter) {
            if (counter.classList.contains('counted')) return;

            counter.classList.add('counted');
            const target = parseInt(counter.dataset.count);
            const duration = 2000; // 2 seconds
            const increment = Math.ceil(target / (duration / 30)); // Update every 30ms
            let current = 0;

            const timer = setInterval(() => {
                current += increment;

                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = current.toLocaleString();
                }
            }, 30);
        }

        // Gallery Modal
        const galleryItems = document.querySelectorAll('.gallery-item');
        const modal = document.getElementById('image-modal');
        const modalImg = document.getElementById('modal-image');
        const closeModal = document.getElementById('close-modal');

        galleryItems.forEach(item => {
            item.addEventListener('click', () => {
                const imgSrc = item.querySelector('img').src;
                const imgAlt = item.querySelector('img').alt;

                modalImg.src = imgSrc;
                modalImg.alt = imgAlt;
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });

        closeModal.addEventListener('click', () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
        });

        // Intersection Observer for better performance
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

                            // For stat counters
                            if (element.classList.contains('stat-item')) {
                                const counter = element.querySelector('.stat-count');
                                startCounting(counter);
                            }
                        }, delay * 1000);

                        // Unobserve after animation
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