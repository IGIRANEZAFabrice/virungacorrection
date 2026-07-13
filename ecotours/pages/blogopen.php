<?php
require_once '../admin/config/connection.php';

if (!isset($_GET['id'])) {
    header("Location: blog.php");
    exit();
}

$blog_id = (int)$_GET['id']; // Use blog_id and cast to integer

// Fetch the main blog post and its category name
$query = "SELECT bp.*, bc.category_name 
          FROM blog_posts bp
          JOIN blog_categories bc ON bp.category_id = bc.category_id
          WHERE bp.blog_id = ? AND bp.status = 'published'"; // Use blog_id and check status
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $blog_id);

$stmt->execute();
$stmt->store_result();
$stmt->bind_result(
    $bp_blog_id, $bp_title, $bp_slug, $bp_author, $bp_read_minutes, $bp_category_id, $bp_cover_image, $bp_main_headline, $bp_introduction, $bp_status, $bp_views, $bp_created_by, $bp_created_at, $bp_updated_at, $bp_published_at, $bc_category_name
);
$post = null;
if ($stmt->fetch()) {
    $post = [
        'blog_id' => $bp_blog_id,
        'title' => $bp_title,
        'slug' => $bp_slug,
        'author' => $bp_author,
        'read_minutes' => $bp_read_minutes,
        'category_id' => $bp_category_id,
        'cover_image' => $bp_cover_image,
        'main_headline' => $bp_main_headline,
        'introduction' => $bp_introduction,
        'status' => $bp_status,
        'views' => $bp_views,
        'created_by' => $bp_created_by,
        'created_at' => $bp_created_at,
        'updated_at' => $bp_updated_at,
        'published_at' => $bp_published_at,
        'category_name' => $bc_category_name
    ];
}

if (!$post) {
    header("Location: blog.php"); // Redirect if post not found or not published
    exit();
}
$stmt->close();

// Get content block IDs and types first
$blocks_query = "SELECT block_id, block_type FROM blog_content_blocks WHERE blog_id = ? ORDER BY block_order ASC";
$blocks_stmt = $conn->prepare($blocks_query);
$blocks_stmt->bind_param("i", $blog_id);
$blocks_stmt->execute();
$blocks_stmt->store_result();
$blocks_stmt->bind_result($block_id, $block_type);
$content_blocks_info = [];
while ($blocks_stmt->fetch()) {
    $content_blocks_info[] = [
        'block_id' => $block_id,
        'block_type' => $block_type
    ];
}
$blocks_stmt->close();

// Get related posts based on category_id
$related_query = "SELECT blog_id, title, cover_image, created_at, published_at 
                  FROM blog_posts 
                  WHERE blog_id != ? AND category_id = ? AND status = 'published' 
                  ORDER BY published_at DESC, created_at DESC LIMIT 3"; 
$related_stmt = $conn->prepare($related_query);
$related_stmt->bind_param("ii", $blog_id, $post['category_id']); 

$related_stmt->execute();
$related_stmt->store_result();
$related_stmt->bind_result($r_blog_id, $r_title, $r_cover_image, $r_created_at, $r_published_at);
$related_posts_data = [];
while ($related_stmt->fetch()) {
    $related_posts_data[] = [
        'blog_id' => $r_blog_id,
        'title' => $r_title,
        'cover_image' => $r_cover_image,
        'created_at' => $r_created_at,
        'published_at' => $r_published_at
    ];
}


$exclude_ids = [$blog_id]; 
foreach ($related_posts_data as $related_p) {
    $exclude_ids[] = $related_p['blog_id']; 
}
$exclude_placeholders = implode(',', array_fill(0, count($exclude_ids), '?')); 
$exclude_types = str_repeat('i', count($exclude_ids)); 

$suggestion_query = "SELECT blog_id, title, cover_image, slug 
                     FROM blog_posts 
                     WHERE status = 'published' AND blog_id NOT IN ($exclude_placeholders)
                     ORDER BY published_at DESC, created_at DESC LIMIT 3";
$suggestion_stmt = $conn->prepare($suggestion_query);

$suggestion_stmt->bind_param($exclude_types, ...$exclude_ids); 
$suggestion_stmt->execute();
$suggestion_stmt->store_result();
$suggestion_stmt->bind_result($s_blog_id, $s_title, $s_cover_image, $s_slug);
$suggested_posts = [];
while ($suggestion_stmt->fetch()) {
    $suggested_posts[] = [
        'blog_id' => $s_blog_id,
        'title' => $s_title,
        'cover_image' => $s_cover_image,
        'slug' => $s_slug
    ];
}
$suggestion_stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($post['title']) ?> | Virunga Ecotours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/blogopen.css" />
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <script src="../js/header.js"></script>
    <style>
        .content-paragraph {
            font-family: 'Arial', sans-serif;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: #333;
            line-height: 1.6;
            word-spacing: 0.05em; /* Add proper word spacing */
            letter-spacing: 0.01em; /* Slight letter spacing for readability */
            text-align: justify; /* Optional: for clean alignment */
            max-width: 100%; /* Ensure text doesn't overflow */
            overflow-wrap: break-word; /* Handle long words */
            hyphens: auto; /* Optional: adds hyphens for very long words */
        }

        .main-content a {
            color: #2a7a39; /* A professional green color */
            text-decoration: underline;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .main-content a:hover {
            color: #1e5a28; /* Darker green on hover */
            text-decoration: none;
        }
    </style>
</head>
<body>
  <?php include('./includes/header.php'); ?>
    <!-- Breadcrumbs -->
    <div class="breadcrumbs" style="padding: .5rem 0;">
      <div class="container">
        <a href="./blog.php">Blog</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current"><?= htmlspecialchars($post['title'])?></span>
      </div>
    </div>

    <!-- Article Hero Section -->
    <article class="article-container">
        <div class="article-hero">
            <div class="article-hero-image" style="background-image: url('../admin/images/blog/covers/<?= htmlspecialchars($post['cover_image']) ?>')"></div> 
                <?php
                // Fallback for hero image if not set or file missing
                $hero_image_path = '../admin/images/blog/covers/' . htmlspecialchars($post['cover_image']);
                if (empty($post['cover_image']) || !file_exists($hero_image_path)) {
                    $hero_image_path = '../images/logos/icon.png'; // fallback image
                }
                ?>
                <div class="article-hero-image" style="background-image: url('<?= $hero_image_path ?>')"></div>
            <div class="article-hero-overlay">
                <div class="container">
                    <span class="article-category"><?= htmlspecialchars(ucfirst($post['category_name'])) ?></span> 
                    <h1 class="article-title"><?= htmlspecialchars(stripslashes($post['title'])) ?></h1>
                    <div class="article-meta">
                        <span class="meta-author">By <?= htmlspecialchars($post['author']) ?></span>
                        <span class="meta-divider">|</span>
                        <span class="meta-date"><i class="far fa-calendar-alt"></i> <?= date('F j, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span> 
                        <span class="meta-divider">|</span>
                        <span class="meta-read-time"><i class="far fa-clock"></i> <?= $post['read_minutes'] ?> min read</span> 
                    </div>
                </div>
            </div>
        </div>
    </article>

    <!-- Main Content -->
    <div class="container">
        <div class="article-content">
            <div class="main-content">
                <p class="article-lead">
                    <?= stripslashes($post['main_headline']) ?> 
                </p>
                <div class="content-paragraph">
                    <?= nl2br(stripslashes($post['introduction'])) ?> 
                </div>

                <?php 
                // Loop through fetched block info and query specific content
                foreach ($content_blocks_info as $block_info): 
                    $block_id = $block_info['block_id'];
                    $block_type = $block_info['block_type'];

                    // Prepare and execute query based on block type
                    $content_data = null;
                    if ($block_type == 'text') {
                        $content_stmt = $conn->prepare("SELECT section_title, content FROM blog_text_blocks WHERE block_id = ?");
                        $content_stmt->bind_param("i", $block_id);
                        $content_stmt->execute();
                        $content_stmt->store_result();
                        $content_stmt->bind_result($section_title, $content);
                        if ($content_stmt->fetch()) {
                            $content_data = [
                                'section_title' => $section_title,
                                'content' => $content
                            ];
                        }
                        $content_stmt->close();
                    } elseif ($block_type == 'image') {
                        $content_stmt = $conn->prepare("SELECT image_path, caption FROM blog_image_blocks WHERE block_id = ?");
                        $content_stmt->bind_param("i", $block_id);
                        $content_stmt->execute();
                        $content_stmt->store_result();
                        $content_stmt->bind_result($image_path, $caption);
                        if ($content_stmt->fetch()) {
                            $content_data = [
                                'image_path' => $image_path,
                                'caption' => $caption
                            ];
                        }
                        $content_stmt->close();
                    }

                    // Render block based on type and fetched data
                    if ($content_data):
                        if ($block_type == 'text'): ?>
                            <?php if(!empty($content_data['section_title']) && $content_data['section_title'] != ".") {?>
                                <h2 class="content-subheading"><?= nl2br(htmlspecialchars(stripslashes($content_data['section_title']))) ?></h2>
                            <?php } ?>
                            <div class="content-paragraph">
                                <?= nl2br(stripslashes($content_data['content'])); ?>
                        </div>
                        <?php elseif ($block_type == 'image'): ?>
                            <div class="content-image-container">
                                <img src="../admin/images/blog/content/<?= htmlspecialchars(stripslashes($content_data['image_path'])) ?>" 
                                     alt="<?= htmlspecialchars(stripslashes($content_data['caption'] ?? $post['title'])) ?>"
                                     class="content-image">
                                <?php if (!empty($content_data['caption'])): ?>
                                    <p class="image-caption"><?= htmlspecialchars(stripslashes($content_data['caption'])) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <aside class="asidebar">
                <div class="asidebar-widget">
                    <h3 class="widget-title">Related Posts</h3>
                    <ul class="related-posts-list">
                            <?php
                            if (count($related_posts_data) > 0) {
                                foreach ($related_posts_data as $related) {
                                    $related_img_path = '../admin/images/blog/covers/' . htmlspecialchars(stripslashes($related['cover_image']));
                                    if (empty($related['cover_image']) || !file_exists($related_img_path)) {
                                        $related_img_path = '../images/logos/icon.png'; // fallback image
                                    }
                                    ?>
                                    <li class="related-post-item">
                                        <img src="<?= $related_img_path ?>" 
                                             alt="<?= htmlspecialchars(stripslashes($related['title'])) ?>" 
                                             class="related-post-img">
                                        <div class="related-post-content">
                                            <h4 class="related-post-title">
                                                <a href="blogopen.php?id=<?= $related['blog_id'] ?>"><?= htmlspecialchars(stripslashes($related['title'])) ?></a> 
                                            </h4>
                                            <span class="related-post-date"><?= date('F j, Y', strtotime($related['published_at'] ?? $related['created_at'])) ?></span> 
                                        </div>
                                    </li>
                                    <?php
                                }
                            } else {
                                echo '<li style="color:#666;font-style:italic;">No related posts found.</li>';
                            }
                            ?>
                    </ul>
                </div>
            </aside>
        </div>
    </div>

    <!-- Photo Gallery -->
    <?php 
    // Use blog_gallery_images table and blog_id
    $gallery_query = "SELECT image_path FROM blog_gallery_images WHERE blog_id = ? ORDER BY image_order"; 
    $gallery_stmt = $conn->prepare($gallery_query);
    $gallery_stmt->bind_param("i", $blog_id); // Use blog_id

    $gallery_stmt->execute();
    $gallery_stmt->store_result();
    $gallery_stmt->bind_result($gallery_image_path);
    if ($gallery_stmt->num_rows > 0): ?>
    <div class="photo-gallery">
        <h2 class="gallery-title">Photo Gallery</h2>
        <div class="gallery-grid">
            <?php while ($gallery_stmt->fetch()): ?>
            <div class="gallery-item">
                <img src="../admin/images/blog/gallery/<?= htmlspecialchars($gallery_image_path) ?>"
                     alt="Gallery image for <?= htmlspecialchars(stripslashes($post['title'])) ?>"
                     class="gallery-image">
                <div class="gallery-overlay">
                    <i class="fas fa-search-plus gallery-icon"></i>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <?php 
    endif; 
    $gallery_stmt->close(); // Close gallery statement
    ?>

    <!-- Share and Comments -->
    <div class="article-engagement">
        <div class="share-section">
            <h3 class="share-label">Share this story:</h3>
            <div class="share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" 
                   class="share-button facebook" target="_blank">
                   <i class="fab fa-facebook-f"></i>Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" 
                   class="share-button twitter" target="_blank">
                   <i class="fab fa-twitter"></i>Twitter
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>&title=<?= urlencode($post['title']) ?>" 
                   class="share-button linkedin" target="_blank">
                   <i class="fab fa-linkedin-in"></i>LinkedIn
                </a>
                <a href="mailto:?subject=<?= urlencode($post['title']) ?>&body=Check out this article: <?= urlencode("https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ?>" 
                   class="share-button email" target="_blank">
                   <i class="fas fa-envelope"></i>Email
                </a>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <h3 class="comments-title">Join the Conversation</h3>

            <?php
            // Fetch approved comments for this post
            $comments_query = "SELECT * FROM blog_comments WHERE blog_id = ? AND is_approved = 1 ORDER BY created_at DESC";
            $comments_stmt = $conn->prepare($comments_query);
            $comments_stmt->bind_param("i", $blog_id);

            $comments_stmt->execute();
            $comments_stmt->store_result();
            $meta = $comments_stmt->result_metadata();
            $fields = [];
            while ($field = $meta->fetch_field()) {
                $fields[] = $field->name;
            }
            $row = [];
            $comments_stmt->bind_result(
                $comment_id, $comment_blog_id, $parent_comment_id, $commenter_name, $commenter_email, $comment_text, $is_approved, $created_at
            );
            $comments = [];
            while ($comments_stmt->fetch()) {
                $comments[] = [
                    'comment_id' => $comment_id,
                    'blog_id' => $comment_blog_id,
                    'commenter_name' => $commenter_name,
                    'commenter_email' => $commenter_email,
                    'comment_text' => $comment_text,
                    'is_approved' => $is_approved,
                    'created_at' => $created_at
                ];
            }
            ?>

            <div class="comments-list" style="margin-top: 20px;">
                            <?php if (count($comments) > 0): ?>
                                <?php foreach ($comments as $comment): ?>
                                    <?php
                                        // Generate initials for avatar
                                        $name_parts = explode(' ', trim($comment['commenter_name']));
                                        $initials = '';
                                        if (count($name_parts) >= 2) {
                                            $initials = strtoupper(substr($name_parts[0], 0, 1) . substr(end($name_parts), 0, 1));
                                        } elseif (count($name_parts) == 1 && !empty($name_parts[0])) {
                                            $initials = strtoupper(substr($name_parts[0], 0, 1));
                                        } else {
                                            $initials = '?'; // Fallback if name is empty or unusual
                                        }
                                        // Simple hash function for consistent background color based on name
                                        $hash = crc32($comment['commenter_name']);
                                        $hue = $hash % 360;
                                        $avatar_bg_color = "hsl($hue, 50%, 75%)"; // Generate a pastel color
                                        $avatar_text_color = "hsl($hue, 50%, 25%)"; // Darker shade for text
                                    ?>
                                    <div class="comment-item" style="display: flex; align-items: flex-start; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                                        <div class="comment-avatar" style="
                                            width: 45px;
                                            height: 45px;
                                            border-radius: 50%;
                                            background-color: <?= $avatar_bg_color ?>;
                                            color: <?= $avatar_text_color ?>;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            font-weight: bold;
                                            font-size: 1.1em;
                                            margin-right: 15px;
                                            flex-shrink: 0; /* Prevent avatar from shrinking */
                                        ">
                                            <?= htmlspecialchars($initials) ?>
                                        </div>
                                        <div class="comment-content" style="flex-grow: 1;">
                                            <div class="comment-author" style="font-weight: bold; color: var(--primary-green, #2a4858); margin-bottom: 3px;"><?= htmlspecialchars($comment['commenter_name']) ?></div>
                                            <div class="comment-date" style="font-size: 0.85em; color: #888; margin-bottom: 8px;"><?= date('F j, Y \a\t g:i a', strtotime($comment['created_at'])) ?></div>
                                            <div class="comment-text" style="line-height: 1.6; color: var(--text-medium, #5d4e41);"><?= nl2br($comment['comment_text']) ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p style="color: #666; font-style: italic;">Be the first to comment!</p>
                            <?php endif; ?>
                            <?php $comments_stmt->close(); ?>
            </div>

            <!-- Comment Form -->
            <div class="comment-form-container" style="background-color: #f6f4f0; padding: 25px; border-radius: 8px; margin-top: 30px; border: 1px solid #d8c3a5;">
                <h4 style="margin-top: 0; margin-bottom: 20px; color: #3a3026; font-size: 1.4em;">Leave a Reply</h4>

                <?php
                // Display Success or Error Messages
                $message = '';
                $message_type = ''; // 'success' or 'error'

                if (isset($_GET['success'])) {
                    if ($_GET['success'] == 'comment_submitted') {
                        $message = 'Thank you! Your comment has been submitted and is awaiting moderation.';
                        $message_type = 'success';
                    }
                } elseif (isset($_GET['error'])) {
                    $message_type = 'error';
                    switch ($_GET['error']) {
                        case 'missing_fields':
                            $message = 'Error: Please fill in all required fields.';
                            break;
                        case 'invalid_email':
                            $message = 'Error: Please provide a valid email address.';
                            break;
                        case 'invalid_content_length':
                            $message = 'Error: Comment must be between 10 and 1000 characters.';
                            break;
                        case 'comment_failed':
                            $message = 'Error: Failed to submit comment. Please try again later.';
                            break;
                        // Note: 'invalid_post' error redirects to blog.php, so won't show here.
                        default:
                            $message = 'An unexpected error occurred.';
                            break;
                    }
                }

                if ($message):
                    // Define styles based on earthy-theme.css variables
                    $success_style = 'background-color: #2a4858; color: #f6f4f0; border: 1px solid #2a4858;'; // --primary-green, --text-light
                    $error_style = 'background-color: #967259; color: #f6f4f0; border: 1px solid #8b7355;'; // --accent-terracotta, --text-light, --primary-brown border
                    $base_style = 'padding: 15px; margin-bottom: 20px; border-radius: 5px; text-align: center;';
                    $current_style = ($message_type == 'success') ? $success_style : $error_style;
                ?>
                    <div style="<?= $base_style . $current_style ?>">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form id="commentForm" action="../admin/handlers/blog/process_comment.php" method="POST">
                    <input type="hidden" name="blog_id" value="<?= $blog_id ?>">
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="commenter_name" style="display: block; margin-bottom: 5px; color: #5d4e41; font-weight: bold;">Name *</label>
                        <input type="text" id="commenter_name" name="name" required style="width: 100%; padding: 10px; border: 1px solid #d8c3a5; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 15px;">
                        <label for="commenter_email" style="display: block; margin-bottom: 5px; color: #5d4e41; font-weight: bold;">Email * (will not be published)</label>
                        <input type="email" id="commenter_email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #d8c3a5; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="comment_text" style="display: block; margin-bottom: 5px; color: #5d4e41; font-weight: bold;">Comment *</label>
                        <textarea id="comment_text" name="content" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #d8c3a5; border-radius: 4px; box-sizing: border-box; resize: vertical;"></textarea>
                    </div>
                    <button type="submit" class="submit-comment-btn" style="background-color: #8b7355; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 1em; transition: background-color 0.3s ease;">Post Comment</button>
                </form>
                <div id="commentMessage" style="margin-top: 15px;"></div>
            </div>
        </div> <!-- End Comments Section -->
    </div> <!-- End Article Engagement -->

    <!-- You Might Also Like Section -->
    <?php if (!empty($suggested_posts)): ?>
    <div class="you-might-like-section" style="padding: 40px 0; background-color: #faf8f5; border-top: 1px solid #eee;">
        <div class="container">
            <h3 style="text-align: center; margin-bottom: 30px; color: #3a3026; font-size: 1.8em;">You Might Also Like</h3>
            <div class="suggested-posts-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
                <?php foreach ($suggested_posts as $suggested_post): ?>
                    <div class="suggested-post-card" style="background-color: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.3s ease;">
                        <a href="blogopen.php?id=<?= $suggested_post['blog_id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                            <div class="suggested-post-image" style="height: 180px; overflow: hidden;">
                                <img src="../admin/images/blog/covers/<?= htmlspecialchars($suggested_post['cover_image']) ?>" 
                                     alt="<?= htmlspecialchars(stripslashes($suggested_post['title'])) ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                            <div class="suggested-post-content" style="padding: 15px;">
                                <h4 style="font-size: 1.1em; margin-top: 0; margin-bottom: 10px; color: #333;"><?= htmlspecialchars(stripslashes($suggested_post['title'])) ?></h4>
                                <!-- Optional: Add excerpt or date here if needed -->
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- End You Might Also Like Section -->


    <?php include('./includes/footer.php'); ?>

    <script>

        // Gallery Lightbox (Basic Example - Consider a library for more features)
        const galleryItems = document.querySelectorAll('.gallery-item');
        if (galleryItems.length > 0) {
            const lightbox = document.createElement('div');
            lightbox.id = 'lightbox';
            lightbox.style.display = 'none';
            lightbox.style.position = 'fixed';
            lightbox.style.top = '0';
            lightbox.style.left = '0';
            lightbox.style.width = '100%';
            lightbox.style.height = '100%';
            lightbox.style.backgroundColor = 'rgba(0,0,0,0.8)';
            lightbox.style.zIndex = '1000';
            lightbox.style.display = 'none';
            lightbox.style.justifyContent = 'center';
            lightbox.style.alignItems = 'center';
            
            const lightboxImg = document.createElement('img');
            lightboxImg.style.maxWidth = '90%';
            lightboxImg.style.maxHeight = '80%';
            
            lightbox.appendChild(lightboxImg);
            document.body.appendChild(lightbox);

            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const imgSrc = item.querySelector('img').src;
                    lightboxImg.src = imgSrc;
                    lightbox.style.display = 'flex'; // Show lightbox
                });
            });

            lightbox.addEventListener('click', (e) => {
                if (e.target !== lightboxImg) { // Close if clicking background
                    lightbox.style.display = 'none';
                }
            });
        }

    </script>
</body>
</html>
<?php
$conn->close(); // Close the database connection at the end
?>