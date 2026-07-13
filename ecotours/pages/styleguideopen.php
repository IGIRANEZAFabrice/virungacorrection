<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>East Africa Travel Style Guide - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/earthy-theme.css">
    <link rel="stylesheet" href="../css/header.css">
    <script src="../js/header.js" defer></script>
    <style>
        
        /* Hero Section */
        .hero-section {
            position: relative;
            height: 400px;
            margin-top: 0;
            overflow: hidden;
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(42, 72, 88, 0.7), rgba(42, 72, 88, 0.5));
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--text-light);
            font-size: 36px;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            text-align: center;
            max-width: 800px;
            line-height: 1.2;
        }

        .breadcrumb {
            background-color: var(--neutral-cream);
            padding: 15px 0;
            font-size: 14px;
        }

        .breadcrumb a {
            color: var(--primary-green);
            text-decoration: none;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: var(--text-medium);
            margin: 0 8px;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Main Content */
        .main-content {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        .content {
            flex: 7;
            padding-right: 30px;
        }
        
        .sidebar {
            flex: 3;
            padding-left: 20px;
            border-left: 1px solid var(--neutral-beige);
        }
          /* Intro Text */
        .intro-text {
            margin: 2rem 0 3rem;
            font-size: 1.125rem;
            color: var(--text-medium);
            line-height: 1.8;
            padding: 2rem;
            background: linear-gradient(to right, var(--neutral-cream), rgba(255, 255, 255, 0.5));
            border-left: 4px solid var(--primary-green);
            border-radius: 0 8px 8px 0;
            position: relative;
        }

        .intro-text::before {
            content: '"';
            position: absolute;
            top: -10px;
            left: 20px;
            font-size: 4rem;
            color: var(--primary-green);
            opacity: 0.2;
            font-family: Georgia, serif;
        }

        .intro-text::after {
            content: '"';
            position: absolute;
            bottom: -40px;
            right: 20px;
            font-size: 4rem;
            color: var(--primary-green);
            opacity: 0.2;
            font-family: Georgia, serif;
        }
        
        /* Main Content Styles */
        #mainContentArea {
            line-height: 1.7;
        }

        #mainContentArea h2 {
            font-size: 1.75rem;
            color: var(--text-dark);
            margin: 2.5rem 0 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--neutral-beige);
            position: relative;
        }

        #mainContentArea h2::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background-color: var(--primary-green);
        }

        #mainContentArea p {
            margin-bottom: 1.5rem;
            color: var(--text-medium);
            font-size: 1.1rem;
        }

        #mainContentArea .numbered-list {
            margin: 2rem 0;
            counter-reset: item;
            list-style: none;
            padding-left: 0;
        }

        #mainContentArea .numbered-list li {
            margin-bottom: 1.5rem;
            padding-left: 3.5rem;
            position: relative;
            min-height: 2.5rem;
        }

        #mainContentArea .numbered-list li::before {
            counter-increment: item;
            content: counter(item);
            position: absolute;
            left: 0;
            top: 0;
            width: 2.5rem;
            height: 2.5rem;
            background-color: var(--primary-green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        #mainContentArea .numbered-list li span {
            color: var(--text-medium);
            display: block;
            padding-top: 0.3rem;
        }

        /* Section highlight effects */
        #mainContentArea section {
            margin-bottom: 3rem;
            padding: 2rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        #mainContentArea section:hover {
            transform: translateY(-5px);
        }
        
        /* Section Titles */
        h2 {
            margin: 25px 0 15px;
            color: var(--text-dark);
            font-size: 22px;
            font-weight: bold;
        }
        
        h3 {
            margin: 20px 0 10px;
            color: var(--text-dark);
            font-size: 18px;
            font-weight: bold;
        }
        
        /* Links */
        a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        /* Buttons and Highlight Elements */
        .highlight {
            color: var(--primary-green);
            font-weight: bold;
        }
        
        .search-form {
            display: flex;
            margin: 15px 0;
        }
        
        .search-input {
            flex-grow: 1;
            padding: 8px;
            border: 1px solid var(--neutral-beige);
            border-right: none;
        }
        
        .search-button {
            padding: 8px 15px;
            background: var(--primary-green);
            color: var(--text-light);
            border: none;
            cursor: pointer;
        }
        
        /* Guide List Section */
        .guide-list {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        .guide-list h2 {
            margin-bottom: 15px;
        }
        
        .guide-list ol {
            padding-left: 35px;
            margin-bottom: 15px;
        }
        
        .guide-list li {
            margin-bottom: 5px;
            color: var(--text-medium);
        }
        
        .guide-list a {
            font-weight: bold;
        }
        
        /* About Section */
        .about-section {
            background-color: var(--neutral-cream);
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 5px;
            flex-direction: column;
            gap: .5rem;
        }
        
        .about-section h3 {
            color: var(--text-dark);
            margin-top: 0;
        }
        
        /* Blog Posts */
        .recent-posts {
            margin-top: 30px;
        }
        
        .post-item {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }
        
        .post-thumbnail {
            width: 70px;
            height: 70px;
            margin-right: 10px;
            object-fit: cover;
        }
        
        .post-title {
            font-size: 14px;
            line-height: 1.3;
        }
        
        /* Popular Tours */
        .popular-tours {
            margin-top: 30px;
        }
        
        .tour-item {
            display: flex;
            margin-bottom: 15px;
            align-items: start;
        }
        
        .tour-thumbnail {
            width: 80px;
            height: 80px;
            margin-right: 10px;
            object-fit: cover;
        }
        
        .tour-info h4 {
            font-size: 14px;
            margin-bottom: 5px;
        }
        
        .tour-price {
            color: var(--primary-brown);
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
            }
            
            .content {
                padding-right: 0;
            }
            
            .sidebar {
                padding-left: 0;
                border-left: none;
                margin-top: 30px;
            }

            .sidebar {
                width: 100%;
            }
        }
        
        /* Image at bottom of article */
        .article-image {
            width: 100%;
            max-width: 600px;
            margin: 20px 0;
        }
        
        /* Lists */
        ul {
            list-style-type: disc;
            padding-left: 20px;
            margin-bottom: 15px;
        }
        
        ol {
            padding-left: 20px;
            margin-bottom: 15px;
        }
        
        /* Colored list items */
        .numbered-list {
            list-style-type: decimal;
            color: var(--primary-green);
            padding-left: 25px;
        }
        
        .numbered-list li {
            margin-bottom: 8px;
        }
        
        .numbered-list li span {
            color: var(--text-medium);
        }
        
        .numbered-list a {
            color: var(--primary-green);
        }

        /* Error Messages */
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
        }

        .error-message h2 {
            color: #721c24;
            margin-top: 0;
        }

        .error-message a {
            color: #721c24;
            font-weight: bold;
        }

        /* Post Items */
        .post-info {
            flex: 1;
        }

        .post-date {
            font-size: 12px;
            color: var(--text-medium);
            margin-top: 4px;
        }

        .tour-info a {
            color: var(--text-dark);
            text-decoration: none;
        }

        .tour-info a:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--neutral-beige);
        }

        .pagination a {
            color: var(--text-medium);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include('./includes/header.php'); ?>

    <div class="hero-section">
        <img src="" alt="" class="hero-image" ondragstart="return false;" oncontextmenu="return false;">
        <div class="hero-overlay"></div>
        <h1 class="hero-text"></h1>
    </div>

    <div class="breadcrumb">
        <div class="container">
            <a href="../index.php">Home</a>
            <span>></span>
            <a href="styleguide.php">Style Guides</a>
            <span>></span>
            <span id="currentGuideTitle">Travel Guide</span>
        </div>
    </div>
    
    <div class="container">
        <div class="main-content">
            <div class="content">
                <div id="mainContentArea">
                    <?php
                    require_once('../admin/config/database.php');

                    $guideId = isset($_GET['id']) ? intval($_GET['id']) : 0;

                    if ($guideId > 0) {
                        try {
                            // Fetch guide data with content
                            $stmt = $pdo->prepare("
                                SELECT
                                    sg.card_id,
                                    sg.title,
                                    sg.thumbnail_image,
                                    sg.created_at,
                                    sc.hero_image,
                                    sc.intro_text,
                                    sc.main_content,
                                    sc.updated_at
                                FROM styleguide_cards sg
                                LEFT JOIN styleguide_content sc ON sg.card_id = sc.card_id
                                WHERE sg.card_id = ?
                            ");

                            $stmt->execute([$guideId]);
                            $guide = $stmt->fetch(PDO::FETCH_ASSOC);

                            if ($guide) {
                                // Update page title and hero section
                                echo "<script>
                                    document.title = '" . addslashes($guide['title']) . " - Virunga Ecotours';
                                    document.querySelector('.hero-text').textContent = '" . addslashes($guide['title']) . "';
                                    document.getElementById('currentGuideTitle').textContent = '" . addslashes($guide['title']) . "';
                                </script>";

                                // Set hero image if available
                                if ($guide['hero_image']) {
                                    echo "<script>
                                        document.querySelector('.hero-image').src = '../admin/images/style-guide/" . $guide['hero_image'] . "';
                                        document.querySelector('.hero-image').alt = '" . addslashes($guide['title']) . "';
                                    </script>";
                                }

                                // Display intro text
                                if ($guide['intro_text']) {
                                    echo '<p class="intro-text">' . nl2br(htmlspecialchars($guide['intro_text'])) . '</p>';
                                }

                                // Display main content
                                if ($guide['main_content']) {
                                    $mainContent = json_decode($guide['main_content'], true);

                                    if (is_array($mainContent)) {
                                        foreach ($mainContent as $section) {
                                            if ($section['type'] === 'text') {
                                                echo '<h2>' . htmlspecialchars($section['title']) . '</h2>';
                                                echo '<p>' . nl2br(htmlspecialchars($section['content'])) . '</p>';
                                            } elseif ($section['type'] === 'list') {
                                                echo '<h2>' . htmlspecialchars($section['title']) . '</h2>';
                                                echo '<ul class="numbered-list">';
                                                foreach ($section['items'] as $item) {
                                                    echo '<li><span>' . htmlspecialchars($item) . '</span></li>';
                                                }
                                                echo '</ul>';
                                            }
                                        }
                                    } else {
                                        // Fallback for non-JSON content
                                        echo $guide['main_content'];
                                    }
                                }
                            } else {
                                echo '<div class="error-message">
                                        <h2>Style Guide Not Found</h2>
                                        <p>The requested style guide could not be found. Please check the URL or return to the <a href="styleguide.php">style guides page</a>.</p>
                                      </div>';
                            }
                        } catch(PDOException $e) {
                            echo '<div class="error-message">
                                    <h2>Error Loading Content</h2>
                                    <p>There was an error loading the style guide content. Please try again later.</p>
                                  </div>';
                        }
                    } else {
                        echo '<div class="error-message">
                                <h2>Invalid Style Guide</h2>
                                <p>No style guide ID provided. Please return to the <a href="styleguide.php">style guides page</a>.</p>
                              </div>';
                    }
                    ?>
                </div>
            </div>
            
            <div class="sidebar">
                <div class="search-container">
                    <form class="search-form">
                        <input type="text" placeholder="Search..." class="search-input">
                        <button type="submit" class="search-button">GO</button>
                    </form>
                </div>
                
                <div class="about-section">
                    <h3>ABOUT VIRUNGA ECOTOURS</h3>
                    <p>
                        Virunga Ecotours is dedicated to providing sustainable, responsible travel experiences across Rwanda, Uganda, and DR Congo.
                        We are committed to enriching lives, conserving nature, and supporting local communities while showcasing the incredible beauty of East Africa.
                    </p>
                </div>
                
                <div class="recent-posts">
                    <h3>Recent Posts</h3>
                    <div id="recentPostsList">
                        <?php
                        try {
                            // Fetch recent blog posts
                            $stmt = $pdo->prepare("
                                SELECT blog_id, title, cover_image, slug, created_at
                                FROM blog_posts
                                WHERE status = 'published'
                                ORDER BY created_at DESC
                                LIMIT 5
                            ");
                            $stmt->execute();
                            $recentPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if ($recentPosts) {
                                foreach ($recentPosts as $post) {
                                    $postImage = $post['cover_image'] ? '../admin/images/blog/covers/' . $post['cover_image'] : '../images/default-blog.jpg';
                                    echo '
                                    <div class="post-item">
                                        <img src="' . htmlspecialchars($postImage) . '" alt="' . htmlspecialchars($post['title']) . '" class="post-thumbnail" ondragstart="return false;" oncontextmenu="return false;">
                                        <div class="post-info">
                                            <a href="../blog/post.php?slug=' . htmlspecialchars($post['slug']) . '" class="post-title">
                                                ' . htmlspecialchars($post['title']) . '
                                            </a>
                                            <div class="post-date">' . date('M j, Y', strtotime($post['created_at'])) . '</div>
                                        </div>
                                    </div>';
                                }
                            } else {
                                echo '<p>No recent posts available.</p>';
                            }
                        } catch(PDOException $e) {
                            echo '<p>Unable to load recent posts.</p>';
                        }
                        ?>
                    </div>
                </div>

                <div class="popular-tours">
                    <h3>Popular Style Guides</h3>
                    <?php
                    try {
                        // Fetch other style guides
                        $stmt = $pdo->prepare("
                            SELECT card_id, title, thumbnail_image
                            FROM styleguide_cards
                            WHERE card_id != ?
                            ORDER BY created_at DESC
                            LIMIT 3
                        ");
                        $stmt->execute([$guideId]);
                        $otherGuides = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($otherGuides) {
                            foreach ($otherGuides as $otherGuide) {
                                $guideImage = $otherGuide['thumbnail_image'] ? '../admin/images/style-guide/' . $otherGuide['thumbnail_image'] : '../images/default-guide.jpg';
                                echo '
                                <div class="tour-item">
                                    <img src="' . htmlspecialchars($guideImage) . '" alt="' . htmlspecialchars($otherGuide['title']) . '" class="tour-thumbnail" ondragstart="return false;" oncontextmenu="return false;">
                                    <div class="tour-info">
                                        <h4><a href="styleguideopen.php?id=' . $otherGuide['card_id'] . '">' . htmlspecialchars($otherGuide['title']) . '</a></h4>
                                        <div class="tour-price">Travel Style Guide</div>
                                    </div>
                                </div>';
                            }
                        } else {
                            echo '<p>No other style guides available.</p>';
                        }
                    } catch(PDOException $e) {
                        echo '<p>Unable to load other guides.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <?php include('./includes/footer.php'); ?>
</body>
</html>