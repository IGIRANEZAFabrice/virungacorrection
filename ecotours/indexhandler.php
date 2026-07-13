<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
require_once './admin/config/connection.php';

// Initialize default values
$hero_slides = [];
$card_title1 = $card_title2 = "Explore With Us";
$card_desc1 = $card_desc2 = "Discover amazing destinations";
$card_img1 = $card_img2 = "images/default-card.jpg";
$country1 = $country2 = $country3 = "Featured Destination";
$desc1 = $desc2 = $desc3 = "Experience unique adventures";
$dest_img1 = $dest_img2 = $dest_img3 = "images/default-destination.jpg";
$attractions = [];
$tours = [];
$partners = [];
$blogs = [];
$tour_categories = [];

// Function to clean image path
function cleanImagePath($path) {
    // Remove '../../' from the beginning of the path
    return preg_replace('/^\.\.\/\.\.\//', '', $path);
}

if ($conn) {
    // Fetch hero slides from the database
    $hero_stmt = $conn->prepare("SELECT id, title, description, image_url FROM home_hero ORDER BY created_at ASC");
    if ($hero_stmt && $hero_stmt->execute()) {
        $hero_stmt->store_result();
        $hero_stmt->bind_result($id, $title, $description, $image_url);
        while ($hero_stmt->fetch()) {
            $image_url = cleanImagePath($image_url);
            $hero_slides[] = [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'image_url' => $image_url
            ];
        }
        $hero_stmt->close();
    }

    // Fallback: If no slides found, create a default slide
    if (empty($hero_slides)) {
        $hero_slides[] = [
            'id' => 1,
            'title' => 'Welcome to Virunga Ecotours',
            'description' => 'Experience the beauty of nature with sustainable tourism',
            'image_url' => 'images/default-hero.jpg'
        ];
    }
    
    // Fetch under hero cards
    $cards_stmt = $conn->prepare("SELECT title, description, image_url FROM home_under_hero_cards ORDER BY id ASC LIMIT 2");
    if ($cards_stmt && $cards_stmt->execute()) {
        $cards_stmt->store_result();

        $cards_stmt->bind_result($card_title1, $card_desc1, $card_img1);
        $cards_stmt->fetch();

        $cards_stmt->bind_result($card_title2, $card_desc2, $card_img2);
        $cards_stmt->fetch();

        $cards_stmt->close();
    }

    // Fetch destinations
    $dest_stmt = $conn->prepare("SELECT country, description, image_url FROM home_destinations ORDER BY id ASC LIMIT 3");
    if ($dest_stmt && $dest_stmt->execute()) {
        $dest_stmt->store_result();

        $dest_stmt->bind_result($country1, $desc1, $dest_img1);
        $dest_stmt->fetch();
        $dest_img1 = cleanImagePath($dest_img1);

        $dest_stmt->bind_result($country2, $desc2, $dest_img2);
        $dest_stmt->fetch();
        $dest_img2 = cleanImagePath($dest_img2);

        $dest_stmt->bind_result($country3, $desc3, $dest_img3);
        $dest_stmt->fetch();
        $dest_img3 = cleanImagePath($dest_img3);

        $dest_stmt->close();
    }

    // Fetch attractions
    $attractions_stmt = $conn->prepare("SELECT id, title, image_url FROM home_attractions ORDER BY id ASC LIMIT 8");
    if ($attractions_stmt && $attractions_stmt->execute()) {
        $attractions_stmt->store_result();
        $attractions_stmt->bind_result($id, $title, $image_url);
        while ($attractions_stmt->fetch()) {
            $image_url = cleanImagePath($image_url);
            $attractions[] = ['id' => $id, 'title' => $title, 'image_url' => $image_url];
        }
        $attractions_stmt->close();
    }

    // Fetch community tours
    $tours_stmt = $conn->prepare("SELECT tour_id, title, category, days_count, cover_image_path, short_description FROM tours WHERE category = 'adventure' ORDER BY created_at DESC LIMIT 3");
    if ($tours_stmt && $tours_stmt->execute()) {
        $tours_stmt->store_result();
        $tours_stmt->bind_result($tour_id, $title, $category, $days_count, $cover_image_path, $short_description);
        while ($tours_stmt->fetch()) {
            $tours[] = [
                'tour_id' => $tour_id,
                'title' => $title,
                'category' => $category,
                'days_count' => $days_count,
                'cover_image_path' => $cover_image_path,
                'short_description' => $short_description
            ];
        }
        $tours_stmt->close();
    }

    // Modified fetch for random tours - fetch ALL tours except adventure category
    $random_tours_stmt = $conn->prepare("
        SELECT 
            t.tour_id,
            t.title,
            t.days_count,
            t.cover_image_path,
            t.short_description,
            t.category 
        FROM tours t 
        WHERE LOWER(t.category) != 'adventure'
        ORDER BY t.created_at DESC LIMIT 3
    ");

    if ($random_tours_stmt && $random_tours_stmt->execute()) {
        $random_tours_stmt->store_result();
        $random_tours = [];
        $random_tours_stmt->bind_result($tour_id, $title, $days_count, $cover_image_path, $short_description, $category);
        while ($random_tours_stmt->fetch()) {
            $random_tours[] = [
                'tour_id' => $tour_id,
                'title' => $title,
                'days_count' => $days_count,
                'cover_image_path' => $cover_image_path,
                'short_description' => $short_description,
                'category' => $category
            ];
        }
        $random_tours_stmt->close();
    }

    // Fetch partners
    $partners_stmt = $conn->prepare("SELECT web_url, logo_url FROM home_partners ORDER BY id ASC");
    if ($partners_stmt && $partners_stmt->execute()) {
        $partners_stmt->store_result();
        $partners_stmt->bind_result($web_url, $logo_url);
        while ($partners_stmt->fetch()) {
            $logo_url = cleanImagePath($logo_url);
            $partners[] = ['web_url' => $web_url, 'logo_url' => $logo_url];
        }
        $partners_stmt->close();
    }

    // Fetch blogs for the blog section - Updated Query
    $blogs_stmt = $conn->prepare("
        SELECT 
            p.blog_id,
            p.title,
            p.cover_image,
            p.author,
            p.read_minutes,
            p.main_headline,
            p.introduction,
            p.created_at,
            bc.category_name
        FROM blog_posts p
        JOIN blog_categories bc ON p.category_id = bc.category_id
        WHERE p.status = 'published'
        ORDER BY p.created_at DESC 
        LIMIT 3
    ");
    
    if ($blogs_stmt && $blogs_stmt->execute()) {
        $blogs_stmt->store_result();
        $blogs = []; // Re-initialize blogs array here
        $blogs_stmt->bind_result($blog_id, $title, $cover_image, $author, $read_minutes, $main_headline, $introduction, $created_at, $category_name);
        while ($blogs_stmt->fetch()) {
            $cover_image = cleanImagePath($cover_image);
            $short_intro = substr(strip_tags($introduction), 0, 200) . '...';
            $blogs[] = [
                'blog_id' => $blog_id,
                'title' => $title,
                'cover_image' => $cover_image,
                'author' => $author,
                'read_minutes' => $read_minutes,
                'main_headline' => $main_headline,
                'introduction' => $introduction,
                'created_at' => $created_at,
                'category_name' => $category_name,
                'short_intro' => $short_intro
            ];
        }
        $blogs_stmt->close();
    } else {
        // Log error if statement preparation or execution fails
        error_log("Blog fetching failed: " . $conn->error);
    }

    // Fetch home about section data
    $about_stmt = $conn->prepare("SELECT title, slide_description, youtube_url FROM home_about WHERE id = 1 LIMIT 1");
    if ($about_stmt && $about_stmt->execute()) {
        $about_stmt->store_result();
        $about_stmt->bind_result($title, $slide_description, $youtube_url);
        $about_data = null;
        if ($about_stmt->fetch()) {
            $about_data = [
                'title' => $title,
                'slide_description' => $slide_description,
                'youtube_url' => $youtube_url
            ];
        }
        $about_stmt->close();
        if (!$about_data) {
            $about_data = [
                'title' => 'Transforming Ideas Into Reality',
                'slide_description' => 'Virunga Ecotours is a leader in sustainable travel...',
                'youtube_url' => ''
            ];
        }
    } else {
        // Default values if query fails
        $about_data = [
            'title' => 'Transforming Ideas Into Reality',
            'slide_description' => 'Virunga Ecotours is a leader in sustainable travel...',
            'youtube_url' => ''
        ];
    }
} else {
    error_log("Database connection failed - using default values");
}
?>