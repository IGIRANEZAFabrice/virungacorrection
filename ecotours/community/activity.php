<?php
require_once '../admin/config/connection.php';

// Fetch activities from the database
$activities_query = "SELECT * FROM community_activities WHERE status = 'active' AND is_active = 1 ORDER BY display_order ASC";
$activities_result = mysqli_query($conn, $activities_query);
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/logos/logo.jpg">
    <link rel="stylesheet" href="assets/css/impact.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/about.css">
    <link rel="stylesheet" href="assets/css/activity.css">
<?php include 'includes/header.php'; ?>

<!-- Page Header Section -->
<section class="page-header">
    <div class="page-header-background">
        <img src="uploads/impact/hero.jpg" alt="Community Activities" loading="lazy">
        <div class="page-header-overlay"></div>
    </div>
    <div class="container">
        <div class="page-header-content">
            <nav class="breadcrumb">
                <a href="index.php">Community</a>
                <span class="separator"><i class="fas fa-chevron-right"></i></span>
                <span class="current">Activities</span>
            </nav>
            <h1 style="color: #ffffff;">Community Activities</h1>
            <p style="color: #ffffff;">Authentic experiences that directly benefit local communities</p>
        </div>
    </div>
</section>

<!-- Introduction Section -->
<section class="intro-section">
    <div class="intro-container">
        <h2 class="intro-title">Community-Focused Experiences</h2>
        <p class="intro-subtitle">Discover meaningful activities that create positive impact</p>
        
        <div class="intro-description">
            <p>Our community activities are carefully designed to provide authentic cultural experiences while directly supporting local communities in the Virunga region. Unlike traditional tourist activities, these experiences are developed and led by community members themselves, ensuring that benefits flow directly to local families and organizations.</p>
            
            <p>Each activity not only offers you a unique glimpse into local culture and traditions but also contributes to sustainable livelihoods, cultural preservation, and community development. By participating in these activities, you become an active partner in our mission to create positive change.</p>
        </div>
        
        <div class="intro-benefits">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <h3 class="benefit-title">Direct Community Support</h3>
                <p class="benefit-description">100% of activity proceeds go directly to community members and local initiatives, creating sustainable income opportunities and supporting families.</p>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="benefit-title">Cultural Preservation</h3>
                <p class="benefit-description">These activities help preserve traditional knowledge, crafts, and practices by creating economic value for cultural heritage and passing skills to younger generations.</p>
            </div>
            
            <div class="benefit-item">
                <div class="benefit-icon">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="benefit-title">Authentic Connection</h3>
                <p class="benefit-description">Experience genuine cultural exchange and form meaningful connections with local community members through shared activities and experiences.</p>
            </div>
        </div>
    </div>
</section>

<!-- Activities Section -->
<section class="activities-section">
    <div class="container">
        <div class="section-header">
            <h2>Explore Our Activities</h2>
            <p>Immerse yourself in authentic experiences that make a difference</p>
        </div>
        
        <div class="activities-grid" id="activitiesGrid">
            <?php if (mysqli_num_rows($activities_result) > 0): ?>
                <?php
                $activity_count = 0;
                mysqli_data_seek($activities_result, 0); // Reset result pointer
                while ($activity = mysqli_fetch_assoc($activities_result)):
                    $activity_count++;
                ?>
                    <div class="activity-card" data-index="<?php echo $activity_count; ?>">
                        <div class="activity-image">
                            <img src="uploads/activities/<?php echo htmlspecialchars($activity['image']); ?>"
                                 alt="<?php echo htmlspecialchars($activity['title']); ?>" loading="lazy">
                            <div class="activity-overlay">
                                <div class="activity-duration">
                                    <i class="fas fa-clock"></i>
                                    <?php echo htmlspecialchars($activity['duration']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="activity-content">
                            <h3 class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></h3>
                            <p class="activity-description">
                                <?php
                                // Display a summary of the content
                                $summary = substr(strip_tags($activity['content']), 0, 150);
                                echo htmlspecialchars($summary) . (strlen(strip_tags($activity['content'])) > 150 ? '...' : '');
                                ?>
                            </p>
                            <div class="activity-meta">
                                <a href="activity-detail.php?id=<?php echo $activity['id']; ?>" class="activity-link">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-activities">
                    <i class="fas fa-info-circle"></i>
                    <p>No activities available at the moment. Please check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if (mysqli_num_rows($activities_result) > 6): ?>
        <div class="load-more-container">
            <button id="loadMoreBtn" class="load-more-btn">
                <i class="fas fa-plus-circle"></i>
                Load More
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Booking Information Section -->
<section class="booking-info-section" id="booking-activity">
    <div class="container">
        <div class="booking-info-content">
            <div class="booking-header">
                <h2>Explore Community-Based Tourism Opportunities</h2>
                <p class="booking-subtitle">Professional and Academic Approach to Authentic Cultural Experiences</p>
            </div>

            <div class="booking-description">
                <p>Virunga Ecotours, as a pioneer in community-based tourism around the Virunga Massif, provides travelers with authentic, culturally immersive experiences that extend beyond conventional park visits. These activities are designed to support local communities while offering visitors meaningful engagement with Rwandan culture, traditions, and daily life.</p>
            </div>

            <div class="booking-process">
                <h3>Professional and Academic Approach to Booking:</h3>
                <div class="process-steps">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Activity Selection</h4>
                            <p>Review the range of community-based tourism activities available, which may include local craft workshops, traditional cooking experiences, guided village tours, agricultural visits, or cultural performances. Each activity is curated to provide educational, sustainable, and socially responsible tourism experiences.</p>
                        </div>
                    </div>

                    <div class="process-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>Information Gathering</h4>
                            <p>Acquire detailed information about each activity, including duration, group size, seasonal availability, and any prerequisites (e.g., appropriate attire or physical fitness requirements). This ensures informed decision-making aligned with both traveler expectations and community readiness.</p>
                        </div>
                    </div>

                    <div class="process-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Direct Booking Process</h4>
                            <p>To secure a reservation, travelers can initiate direct communication through professional channels. For Virunga Ecotours, WhatsApp provides an efficient and immediate booking interface. Travelers should provide their full name, desired activity, preferred date, and number of participants. This method allows for rapid confirmation and personalized assistance.</p>
                        </div>
                    </div>

                    <div class="process-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Confirmation and Payment</h4>
                            <p>Once the request is received, Virunga Ecotours provides an official confirmation detailing the itinerary, cost, and any preparation requirements. Payment terms are transparently communicated, often with options for mobile money or bank transfer.</p>
                        </div>
                    </div>

                    <div class="process-step">
                        <div class="step-number">5</div>
                        <div class="step-content">
                            <h4>Pre-Activity Briefing</h4>
                            <p>Before the activity, participants receive an orientation covering cultural sensitivities, safety measures, and expected engagement levels. This step ensures that the experience is mutually respectful, educational, and rewarding.</p>
                        </div>
                    </div>

                    <div class="process-step">
                        <div class="step-number">6</div>
                        <div class="step-content">
                            <h4>Post-Activity Feedback</h4>
                            <p>Travelers are encouraged to provide feedback to improve community programs, contribute to research on sustainable tourism practices, and strengthen community-tourist relationships.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="booking-contact">
                <div class="contact-card">
                    <div class="contact-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="contact-content">
                        <h4>Booking Contact</h4>
                        <p>For direct bookings and inquiries, travelers can reach Virunga Ecotours via WhatsApp:</p>
                        <a href="https://wa.me/250784513435" class="whatsapp-link" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                            +250 784 513 435
                        </a>
                    </div>
                </div>
            </div>

            <div class="booking-conclusion">
                <p>By following these structured steps, travelers engage in tourism that is both academically grounded and professionally organized, fostering cultural appreciation and socio-economic benefits for communities around the Virunga Massif.</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Ready to Make an Impact?</h2>
            <p>Join us in creating positive change through meaningful community experiences.</p>
            <div class="cta-buttons">
                <a href="contact.php?action=book" class="btn btn-primary">
                    <i class="fas fa-calendar-check"></i>
                    Book an Activity
                </a>
                <a href="volunteer.php" class="btn btn-secondary">
                    <i class="fas fa-hands-helping"></i>
                    Volunteer With Us
                </a>
            </div>
        </div>
    </div>
</section>

<script>
    // Simple animation for benefits and activity load more functionality
    document.addEventListener('DOMContentLoaded', function() {
        const benefitItems = document.querySelectorAll('.benefit-item');

        benefitItems.forEach((item, index) => {
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 200 * index);
        });

        // Activity Load More Functionality
        const activityCards = document.querySelectorAll('.activity-card');
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const activitiesPerPage = 6;
        let currentlyVisible = activitiesPerPage;

        // Initially hide activities beyond the first 6
        function initializeActivities() {
            activityCards.forEach((card, index) => {
                if (index >= activitiesPerPage) {
                    card.style.display = 'none';
                    card.classList.add('hidden-activity');
                } else {
                    card.style.display = 'block';
                    card.classList.remove('hidden-activity');
                }
            });

            // Update button text and visibility
            updateLoadMoreButton();
        }

        // Load more activities
        function loadMoreActivities() {
            const hiddenCards = document.querySelectorAll('.activity-card.hidden-activity');
            const cardsToShow = Math.min(activitiesPerPage, hiddenCards.length);

            for (let i = 0; i < cardsToShow; i++) {
                const card = hiddenCards[i];
                card.style.display = 'block';
                card.classList.remove('hidden-activity');

                // Add animation
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    card.style.transition = 'all 0.5s ease';

                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                }, i * 100);
            }

            currentlyVisible += cardsToShow;
            updateLoadMoreButton();
        }

        // Update load more button
        function updateLoadMoreButton() {
            if (!loadMoreBtn) return;

            const hiddenCards = document.querySelectorAll('.activity-card.hidden-activity');
            const remainingCards = hiddenCards.length;

            if (remainingCards === 0) {
                loadMoreBtn.style.display = 'none';
            } else {
                loadMoreBtn.style.display = 'block';
                const buttonText = remainingCards >= activitiesPerPage ?
                    `Load More Activities (${activitiesPerPage} more)` :
                    `Load More Activities (${remainingCards} more)`;
                loadMoreBtn.innerHTML = `<i class="fas fa-plus-circle"></i> ${buttonText}`;
            }
        }

        // Event listener for load more button
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                loadMoreActivities();

                // Add loading animation
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                this.disabled = true;

                setTimeout(() => {
                    this.disabled = false;
                    updateLoadMoreButton();
                }, 500);
            });
        }

        // Initialize the activities display
        initializeActivities();

        // Animate booking process steps
        const processSteps = document.querySelectorAll('.process-step');

        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }
            });
        }, observerOptions);

        processSteps.forEach((step, index) => {
            step.style.opacity = '0';
            step.style.transform = 'translateX(-30px)';
            step.style.transition = `all 0.6s ease ${index * 0.1}s`;
            observer.observe(step);
        });
    });
</script>

<?php include 'includes/footer.php'; ?>

<?php
// Close database connection
mysqli_close($conn);
?>