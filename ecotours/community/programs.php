<?php
require_once '../admin/config/connection.php';

// Get filter parameters
$country_filter = isset($_GET['country']) ? mysqli_real_escape_string($conn, $_GET['country']) : '';
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';
$search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 9;
$offset = ($page - 1) * $per_page;

// Build WHERE clause
$where_conditions = ["status IN ('active', 'completed')"];

if ($country_filter) {
    $where_conditions[] = "country = '$country_filter'";
}

if ($category_filter) {
    $where_conditions[] = "category = '$category_filter'";
}

if ($search_query) {
    $where_conditions[] = "(title LIKE '%$search_query%' OR description LIKE '%$search_query%' OR short_description LIKE '%$search_query%')";
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_query = "SELECT COUNT(*) as total FROM community_programs WHERE $where_clause";
$count_result = mysqli_query($conn, $count_query);
$total_programs = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_programs / $per_page);

// Get programs
$programs_query = "SELECT * FROM community_programs WHERE $where_clause ORDER BY featured DESC, created_at DESC LIMIT $per_page OFFSET $offset";
$programs_result = mysqli_query($conn, $programs_query);

// Get categories for filter
$categories_query = "SELECT DISTINCT category FROM community_programs WHERE status IN ('active', 'completed') ORDER BY category";
$categories_result = mysqli_query($conn, $categories_query);

// Get countries for filter
$countries = ['rwanda', 'uganda', 'congo'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Programs - Virunga Ecotours Community</title>
    <meta name="description" content="Explore all community programs by Virunga Ecotours across Rwanda, DRC Congo, and Uganda. Filter by country, category, or search for specific programs.">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="assets/css/community.css">
    <link rel="stylesheet" href="assets/css/programs.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../images/logos/logo.png">
</head>
<body>
    <!-- Include Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-background">
            <img src="assets/images/IMG_9320.jpg" alt="Community Programs" loading="lazy">
            <div class="page-header-overlay"></div>
        </div>
        <div class="container">
            <div class="page-header-content">
                <nav class="breadcrumb">
                    <a href="index.php">Community</a>
                    <span class="separator"><i class="fas fa-chevron-right"></i></span>
                    <span class="current">Programs</span>
                </nav>
                <h1>Our Community Programs</h1>
                <p>Discover how we're making a positive impact across the Virunga region through sustainable development, conservation, and community empowerment initiatives.</p>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="filters-section">
        <div class="container">
            <div class="filters-wrapper">
                <!-- Search Bar -->
                

                <!-- Filter Buttons -->
                <div class="filter-tabs">
                    <!-- Country Filters -->
                    <div class="filter-group">
                        <label class="filter-label">Country:</label>
                        <div class="filter-buttons">
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['country' => '', 'page' => 1])); ?>" 
                               class="filter-btn <?php echo !$country_filter ? 'active' : ''; ?>">
                                All Countries
                            </a>
                            <?php foreach ($countries as $country): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['country' => $country, 'page' => 1])); ?>" 
                                   class="filter-btn <?php echo $country_filter === $country ? 'active' : ''; ?>">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?php echo ucfirst($country); ?>
                                </a>
                            <?php endforeach; ?>

                            <div class="search-container">
                    <form method="GET" class="search-form">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search" class="search-input" 
                                   placeholder="Search programs..." 
                                   value="<?php echo htmlspecialchars($search_query); ?>">
                            <?php if ($country_filter): ?>
                                <input type="hidden" name="country" value="<?php echo htmlspecialchars($country_filter); ?>">
                            <?php endif; ?>
                            <?php if ($category_filter): ?>
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
                        </div>
                    </div>

                    <!-- Category Filters -->
                    <div class="filter-group">
                        <label class="filter-label">Category:</label>
                        <div class="filter-buttons">
                            <a href="?<?php echo http_build_query(array_merge($_GET, ['category' => '', 'page' => 1])); ?>" 
                               class="filter-btn <?php echo !$category_filter ? 'active' : ''; ?>">
                                All Categories
                            </a>
                            <?php while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['category' => $category['category'], 'page' => 1])); ?>" 
                                   class="filter-btn <?php echo $category_filter === $category['category'] ? 'active' : ''; ?>">
                                    <i class="fas fa-tag"></i>
                                    <?php echo htmlspecialchars($category['category']); ?>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>

                <!-- Results Info -->
                <div class="results-info">
                    <span class="results-count">
                        Showing <?php echo min($per_page, $total_programs - $offset); ?> of <?php echo $total_programs; ?> programs
                    </span>
                    <?php if ($search_query || $country_filter || $category_filter): ?>
                        <a href="programs.php" class="clear-filters">
                            <i class="fas fa-times"></i>
                            Clear Filters
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Grid -->
    <section class="programs-grid-section">
        <div class="container">
            <?php if (mysqli_num_rows($programs_result) > 0): ?>
                <div class="programs-grid">
                    <?php while ($program = mysqli_fetch_assoc($programs_result)): ?>
                        <div class="program-card" data-category="<?php echo htmlspecialchars($program['category']); ?>">
                            <div class="program-image">
                                <img src="assets/images/programs/<?php echo htmlspecialchars($program['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($program['title']); ?>" loading="lazy">
                                <div class="program-overlay">
                                    <div class="program-badges">
                                        <span class="program-country">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php echo ucfirst(htmlspecialchars($program['country'])); ?>
                                        </span>
                                        <?php if ($program['featured']): ?>
                                            <span class="program-featured">
                                                <i class="fas fa-star"></i>
                                                Featured
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="program-status status-<?php echo $program['status']; ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php echo ucfirst($program['status']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="program-content">
                                <div class="program-meta">
                                    <span class="program-category">
                                        <i class="fas fa-tag"></i>
                                        <?php echo htmlspecialchars($program['category']); ?>
                                    </span>
                                    <span class="program-date">
                                        <i class="fas fa-calendar"></i>
                                        <?php echo date('M Y', strtotime($program['date_started'])); ?>
                                    </span>
                                </div>
                                <h3><?php echo htmlspecialchars($program['title']); ?></h3>
                                <p><?php echo htmlspecialchars($program['short_description']); ?></p>
                                
                                <div class="program-stats">
                                    <div class="stat">
                                        <i class="fas fa-users"></i>
                                        <span><?php echo number_format($program['beneficiaries']); ?> beneficiaries</span>
                                    </div>
                                    <?php if ($program['location']): ?>
                                        <div class="stat">
                                            <i class="fas fa-map-pin"></i>
                                            <span><?php echo htmlspecialchars($program['location']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="program-actions">
                                    <a href="program-detail.php?slug=<?php echo htmlspecialchars($program['slug']); ?>" class="btn btn-primary">
                                        <i class="fas fa-arrow-right"></i>
                                        Learn More
                                    </a>
                                    <button class="btn btn-outline share-btn" data-url="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>" data-title="<?php echo htmlspecialchars($program['title']); ?>">
                                        <i class="fas fa-share-alt"></i>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <nav class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>" class="pagination-btn prev">
                                    <i class="fas fa-chevron-left"></i>
                                    Previous
                                </a>
                            <?php endif; ?>

                            <div class="pagination-numbers">
                                <?php
                                $start = max(1, $page - 2);
                                $end = min($total_pages, $page + 2);
                                
                                if ($start > 1): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => 1])); ?>" class="pagination-number">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>" 
                                       class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($end < $total_pages): ?>
                                    <?php if ($end < $total_pages - 1): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $total_pages])); ?>" class="pagination-number"><?php echo $total_pages; ?></a>
                                <?php endif; ?>
                            </div>

                            <?php if ($page < $total_pages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>" class="pagination-btn next">
                                    Next
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>No Programs Found</h3>
                    <p>We couldn't find any programs matching your criteria. Try adjusting your filters or search terms.</p>
                    <a href="programs.php" class="btn btn-primary">
                        <i class="fas fa-refresh"></i>
                        View All Programs
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript Files -->
    <script src="assets/js/community.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
