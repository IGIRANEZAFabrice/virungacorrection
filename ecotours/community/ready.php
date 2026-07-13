<?php
// Database connection (optional for future dynamic content)
require_once '../admin/config/connection.php';

// Fetch some basic stats for the page (optional)
$stats_query = "SELECT 
    COUNT(*) as total_programs,
    SUM(beneficiaries) as total_beneficiaries
    FROM community_programs WHERE status IN ('active', 'completed')";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Read for the Future - Community Education Initiative | Virunga Ecotours</title>
    <meta name="description" content="Join our Read for the Future initiative - empowering local children with literacy opportunities while connecting travelers to the cultural heartbeat of the Virunga Massif region.">
    <meta name="keywords" content="read for the future, community education, literacy program, Virunga Massif, community-based tourism, educational empowerment">
    
    <!-- External CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/ready.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Read for the Future</h1>
                <h2 class="hero-subtitle">Community Based Tourism Initiative</h2>
                <p class="hero-description">Empowering local children with access to books and literacy opportunities while connecting travelers to the cultural heartbeat of the Virunga Massif region.</p>
                <a href="#introduction" class="hero-cta">
                    <i class="fas fa-book-open"></i>
                    Discover Our Mission
                </a>
            </div>
        </div>
        <div class="scroll-indicator" id="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="introduction-section" id="introduction">
        <div class="container">
            <div class="intro-content">
                <div class="intro-text scroll-animate">
                    <h2 class="section-title">Introduction</h2>
                    <p class="intro-paragraph">"Read for the Future" is a community-centered initiative by Virunga Ecotours that blends education with tourism around the Virunga Massif. It was designed to empower local children with access to books and literacy opportunities while connecting travelers to the cultural heartbeat of the region.</p>
                    <p class="intro-paragraph">This project recognizes that the future of the Virunga Massif lies not only in its wildlife but also in the education of the young generation who will one day be custodians of both community and conservation.</p>
                </div>
                <div class="intro-image scroll-animate">
                    <div class="image-placeholder">
                        <i class="fas fa-book-reader"></i>
                        <p>Children discovering new worlds through books</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Role and Purpose Section -->
    <section class="role-purpose-section">
        <div class="container">
            <h2 class="section-title">Role and Purpose</h2>
            <p class="section-intro">The project serves a dual role in our community development approach:</p>
            
            <div class="purpose-grid">
                <div class="purpose-card scroll-animate">
                    <div class="purpose-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3>Educational Empowerment</h3>
                    <p>It provides schools and community centers with reading materials, storybooks, and basic learning resources, nurturing literacy and knowledge among children who often lack such opportunities.</p>
                </div>
                
                <div class="purpose-card scroll-animate">
                    <div class="purpose-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Tourism with a Human Touch</h3>
                    <p>It creates a bridge between visitors and local youth, where guests can contribute by donating books, reading stories, or engaging in interactive learning sessions. This allows travelers to participate in meaningful experiences beyond the park.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="impact-section">
        <div class="container">
            <h2 class="section-title">Impact on the Community</h2>
            <div class="impact-grid">
                <div class="impact-card scroll-animate">
                    <div class="impact-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>Children</h3>
                    <p>Improved literacy levels, greater curiosity for learning, and stronger confidence in pursuing education.</p>
                </div>
                
                <div class="impact-card scroll-animate">
                    <div class="impact-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Families</h3>
                    <p>A sense of pride as parents see their children benefit directly from tourism.</p>
                </div>
                
                <div class="impact-card scroll-animate">
                    <div class="impact-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Community at Large</h3>
                    <p>Strengthened cultural identity and reduced vulnerability, as literacy opens doors to better livelihoods and informed decision-making.</p>
                </div>
                
                <div class="impact-card scroll-animate">
                    <div class="impact-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <h3>Tourism-Community Relations</h3>
                    <p>Visitors become part of the community's story, seeing firsthand how tourism revenue and participation contribute to long-term social development.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Why This Matters Section -->
    <section class="why-matters-section">
        <div class="container">
            <div class="matters-content">
                <div class="matters-text scroll-animate">
                    <h2 class="section-title">Why This Project Matters</h2>
                    <p>The Virunga Massif is not just a natural treasure—it is also a home to communities whose futures depend on opportunity and hope. By investing in education, "Read for the Future" ensures that conservation is tied to human growth.</p>
                    <p>It demonstrates that when tourism supports literacy, both people and nature thrive together.</p>
                    <div class="slogan">
                        <i class="fas fa-quote-left"></i>
                        <span>"Every book opened is a door to a brighter Virunga future."</span>
                        <i class="fas fa-quote-right"></i>
                    </div>
                </div>
                <div class="matters-image scroll-animate">
                    <div class="image-placeholder">
                        <i class="fas fa-seedling"></i>
                        <p>Growing minds, growing futures</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visitor Experience Section -->
    <section class="visitor-experience-section">
        <div class="container">
            <h2 class="section-title">What Visitors Can Expect</h2>
            <p class="section-intro">Guests who take part in this activity will have transformative experiences:</p>
            
            <div class="experience-timeline">
                <div class="timeline-item scroll-animate">
                    <div class="timeline-icon">
                        <i class="fas fa-school"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Visit Local Schools</h3>
                        <p>Experience authentic community learning environments and interact with local educators and students in their natural setting.</p>
                    </div>
                </div>
                
                <div class="timeline-item scroll-animate">
                    <div class="timeline-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Share Stories & Donate Books</h3>
                        <p>Participate in creative reading activities, share stories from your culture, and contribute to the community's growing library.</p>
                    </div>
                </div>
                
                <div class="timeline-item scroll-animate">
                    <div class="timeline-icon">
                        <i class="fas fa-smile"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Witness Pure Joy</h3>
                        <p>Experience the enthusiasm and wonder of children discovering new worlds through books and learning.</p>
                    </div>
                </div>
                
                <div class="timeline-item scroll-animate">
                    <div class="timeline-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="timeline-content">
                        <h3>Create Lasting Impact</h3>
                        <p>Leave knowing that your travel directly impacted young lives in a tangible, lasting way that will benefit the community for years to come.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Project Overview Table Section -->
    <section class="overview-table-section">
        <div class="container">
            <h2 class="section-title">Project Overview</h2>
            <p class="section-intro">A comprehensive breakdown of our initiative's structure and impact</p>
            
            <div class="table-container scroll-animate">
                <table class="project-table">
                    <thead>
                        <tr>
                            <th>Aspect</th>
                            <th>Description</th>
                            <th>Impact</th>
                            <th>Visitor Involvement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="aspect-cell">
                                    <i class="fas fa-bullseye"></i>
                                    <span>Role</span>
                                </div>
                            </td>
                            <td>Community-based literacy and educational empowerment initiative.</td>
                            <td>Provides children with reading skills and opportunities for growth.</td>
                            <td>Learn about the program and its importance through guided visits.</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="aspect-cell">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <span>Educational Activities</span>
                                </div>
                            </td>
                            <td>Donation of books, reading sessions, and interactive storytelling with children.</td>
                            <td>Boosts literacy, creativity, and confidence among local youth.</td>
                            <td>Bring books, read with children, or share your own cultural stories.</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="aspect-cell">
                                    <i class="fas fa-hands-helping"></i>
                                    <span>Community Benefits</span>
                                </div>
                            </td>
                            <td>Supports schools and community reading hubs with materials and engagement.</td>
                            <td>Builds pride, identity, and long-term opportunities for families.</td>
                            <td>Witness how tourism supports local education efforts.</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="aspect-cell">
                                    <i class="fas fa-map-marked-alt"></i>
                                    <span>Tourism Value</span>
                                </div>
                            </td>
                            <td>Blends cultural immersion with meaningful community interaction.</td>
                            <td>Creates stronger bonds between travelers and host communities.</td>
                            <td>Experience authentic human connection beyond the wildlife encounters.</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="aspect-cell">
                                    <i class="fas fa-trophy"></i>
                                    <span>Long-Term Goal</span>
                                </div>
                            </td>
                            <td>Build a literate generation prepared to protect and promote the Virunga region.</td>
                            <td>Sustains conservation and community development side by side.</td>
                            <td>Become part of a legacy by contributing to children's futures.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content scroll-animate">
                <h2>Join the Read for the Future Initiative</h2>
                <p>Transform tourism into a powerful tool for social good. Be part of shaping tomorrow's leaders, storytellers, and guardians of the Virunga Massif.</p>
                <div class="cta-buttons">
                    <a href="../pages/contact.php" class="cta-button primary">
                        <i class="fas fa-envelope"></i>
                        Get Involved
                    </a>
                    <a href="./programs.php" class="cta-button secondary">
                        <i class="fas fa-eye"></i>
                        View All Programs
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

    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/community.js"></script>
    <script src="assets/js/scroll.js"></script>
    <script src="assets/js/community-header-footer.js"></script>
    <script>
        // Smooth Scroll for Scroll Down Button
        const scrollDown = document.getElementById('scroll-down');
        
        if (scrollDown) {
            scrollDown.addEventListener('click', () => {
                const nextSection = document.querySelector('#introduction');
                if (nextSection) {
                    nextSection.scrollIntoView({ behavior: 'smooth' });
                }
            });
        }

        // Enhanced scroll animations
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const handleIntersect = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    observer.unobserve(entry.target);
                }
            });
        };

        const observer = new IntersectionObserver(handleIntersect, observerOptions);

        // Observe all scroll-animate elements
        document.querySelectorAll('.scroll-animate').forEach(element => {
            observer.observe(element);
        });

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
