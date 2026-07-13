<?php
// Database connection (optional for future dynamic content)
// require_once('../admin/config/connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kids for Life: Photography Training for Conservation | Virunga Ecotours</title>
    <meta name="description" content="Join our Kids for Life Photography Training program - an immersive educational experience combining photography skills with conservation awareness in the Virunga Massif.">
    <meta name="keywords" content="photography training, conservation education, kids for life, Virunga Massif, youth empowerment, nature photography, community-based tourism">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
    
    <!-- External CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/photograph.css">
</head>
<body>
    <?php include "./includes/header.php" ?>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Kids for Life</h1>
            <h2>Photography Training for Conservation</h2>
            <p>Inspiring young minds to connect with nature through the art of photography</p>
            <a href="#overview" class="hero-btn">
                <i class="fas fa-camera"></i>
                Discover the Program
            </a>
        </div>
        <div class="scroll-indicator" id="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Overview Section -->
    <section class="overview" id="overview">
        <div class="container">
            <div class="overview-content" data-animation="fadeInUp">
                <h2>Program Overview</h2>
                <p class="overview-intro">The Kids for Life Photography Training is an immersive educational program designed to inspire young people to connect deeply with nature and conservation through the art of photography. Organized by Virunga Ecotours, this training combines classroom learning with hands-on field tours around the breathtaking Virunga Massif.</p>
                <p>Children will not only learn photography skills but also discover the importance of protecting wildlife, landscapes, and local cultural heritage.</p>
            </div>
        </div>
    </section>

    <!-- Why Important Section -->
    <section class="why-important" id="why-important">
        <div class="container">
            <h2 data-animation="fadeInUp">Why This Training is Important</h2>
            <div class="importance-grid">
                <div class="importance-item" data-animation="fadeInLeft" data-delay="0.2">
                    <div class="importance-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Conservation Awareness</h3>
                    <p>Photography becomes a powerful tool for storytelling. Children learn how images can raise awareness and inspire people to protect nature.</p>
                </div>
                
                <div class="importance-item" data-animation="fadeInUp" data-delay="0.4">
                    <div class="importance-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3>Creative Expression</h3>
                    <p>It gives kids an avenue to express their creativity while engaging with real-world conservation issues.</p>
                </div>
                
                <div class="importance-item" data-animation="fadeInRight" data-delay="0.6">
                    <div class="importance-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Youth Empowerment</h3>
                    <p>Early exposure to conservation builds pride, responsibility, and leadership among children in local communities.</p>
                </div>
                
                <div class="importance-item" data-animation="fadeInUp" data-delay="0.8">
                    <div class="importance-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Connection to Community</h3>
                    <p>Kids see firsthand how their environment is tied to local livelihoods, tourism, and global interest.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Expectations Section -->
    <section class="expectations" id="expectations">
        <div class="container">
            <div class="expectations-content">
                <div class="expectations-text" data-animation="fadeInLeft">
                    <h2>What Kids Can Expect After the Training</h2>
                    <div class="expectations-list">
                        <div class="expectation-item">
                            <div class="expectation-icon">
                                <i class="fas fa-camera-retro"></i>
                            </div>
                            <div class="expectation-content">
                                <h4>Technical Skills</h4>
                                <p>Understanding camera basics, lighting, framing, and nature photography techniques.</p>
                            </div>
                        </div>
                        
                        <div class="expectation-item">
                            <div class="expectation-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="expectation-content">
                                <h4>Storytelling Power</h4>
                                <p>Learning how to tell conservation stories through pictures.</p>
                            </div>
                        </div>
                        
                        <div class="expectation-item">
                            <div class="expectation-icon">
                                <i class="fas fa-mountain"></i>
                            </div>
                            <div class="expectation-content">
                                <h4>Field Experience</h4>
                                <p>Guided tours in forests, villages, and landscapes of the Virunga Massif to practice photography in real-life settings.</p>
                            </div>
                        </div>
                        
                        <div class="expectation-item">
                            <div class="expectation-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="expectation-content">
                                <h4>Confidence & Leadership</h4>
                                <p>Building the confidence to share their images and conservation messages in schools, communities, and online platforms.</p>
                            </div>
                        </div>
                        
                        <div class="expectation-item">
                            <div class="expectation-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="expectation-content">
                                <h4>Ambassadors for Conservation</h4>
                                <p>Graduates of the training become "Kids for Life Ambassadors," using photography to inspire peers and families.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="expectations-image" data-animation="fadeInRight">
                    <div class="image-placeholder">
                        <img style="width: 100%; height: auto;" src="../images/photo/down.png" alt="">
                        <p>Young photographers in action</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Educational Table Section -->
    <section class="educational-table" id="program-details">
        <div class="container">
            <h2 data-animation="fadeInUp">Strong Educative Program Structure</h2>
            <p class="section-intro" data-animation="fadeInUp" data-delay="0.2">A comprehensive breakdown of our training components and their educational value</p>
            
            <div class="table-container" data-animation="fadeInUp" data-delay="0.4">
                <table class="program-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th>Description</th>
                            <th>Educational Value for Kids</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-camera"></i>
                                    <span>Introduction to Photography</span>
                                </div>
                            </td>
                            <td>Basics of camera use, mobile photography, and framing</td>
                            <td>Builds technical and creative confidence</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-seedling"></i>
                                    <span>Conservation Concepts</span>
                                </div>
                            </td>
                            <td>Interactive sessions on wildlife, forests, and cultural heritage</td>
                            <td>Connects photography to real-world conservation</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-hiking"></i>
                                    <span>Field Tours in Virunga Massif</span>
                                </div>
                            </td>
                            <td>Guided walks and village visits with photography practice</td>
                            <td>Hands-on experience, observing and documenting nature</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-images"></i>
                                    <span>Storytelling with Photos</span>
                                </div>
                            </td>
                            <td>Teaching kids how to create visual stories about nature</td>
                            <td>Strengthens communication and advocacy skills</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-handshake"></i>
                                    <span>Community Connection</span>
                                </div>
                            </td>
                            <td>Interaction with local guides, elders, and ecotourism activities</td>
                            <td>Instills pride in culture and responsibility toward environment</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-trophy"></i>
                                    <span>Photo Exhibitions</span>
                                </div>
                            </td>
                            <td>Showcasing kids' work in community spaces or online</td>
                            <td>Encourages public speaking, confidence, and recognition</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="component-cell">
                                    <i class="fas fa-certificate"></i>
                                    <span>Certification & Ambassadorship</span>
                                </div>
                            </td>
                            <td>Kids become "Kids for Life Ambassadors"</td>
                            <td>Inspires lifelong conservation leadership</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta" id="contact">
        <div class="container">
            <div class="cta-content" data-animation="fadeIn">
                <h2>Ready to Inspire Young Conservation Leaders?</h2>
                <p>Join our Kids for Life Photography Training program and help nurture the next generation of environmental ambassadors. Contact us to learn more about enrollment, partnerships, or bringing this program to your community.</p>
                <div class="cta-buttons">
                    <a href="../pages/contact.php" class="button primary">
                        <i class="fas fa-envelope"></i>
                        Contact Us
                    </a>
                    <a href="../pages/tours.php" class="button secondary">
                        <i class="fas fa-binoculars"></i>
                        Explore Tours
                    </a>
                </div>
                
                <div class="social-links">
                    <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="#" target="_blank"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
        </div>
    </section>

    <?php include "./includes/footer.php" ?>

    <!-- JavaScript -->
    <script src="../js/header.js" defer></script>
    <script>
        // Smooth Scroll for Scroll Down Button
        const scrollDown = document.getElementById('scroll-down');
        const heroSection = document.getElementById('hero');

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

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
