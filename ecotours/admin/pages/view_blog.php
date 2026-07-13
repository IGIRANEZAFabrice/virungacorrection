<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.html');
  exit();
}

require_once('../config/connection.php');

// Get blog post ID from URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Changed variable name post_id to blog_id

// Fetch blog post details along with category name
$post_sql = "SELECT bp.*, bc.category_name
             FROM blog_posts bp
             JOIN blog_categories bc ON bp.category_id = bc.category_id
             WHERE bp.blog_id = ?"; // Changed post_id to blog_id
$post_stmt = $conn->prepare($post_sql);
$post_stmt->bind_param("i", $blog_id); // Use blog_id
$post_stmt->execute();
$post_stmt->bind_result($f_blog_id, $f_title, $f_slug, $f_author, $f_read_minutes, $f_category_id, $f_cover_image, $f_main_headline, $f_introduction, $f_status, $f_views, $f_created_by, $f_created_at, $f_updated_at, $f_published_at, $f_category_name);

if (!$post_stmt->fetch()) {
  // Blog post not found
  header('Location: blogs.php'); // Consider adding an error message
  exit();
}

$post = [
  'blog_id' => $f_blog_id,
  'title' => $f_title,
  'slug' => $f_slug,
  'author' => $f_author,
  'read_minutes' => $f_read_minutes,
  'category_id' => $f_category_id,
  'cover_image' => $f_cover_image,
  'main_headline' => $f_main_headline,
  'introduction' => $f_introduction,
  'status' => $f_status,
  'views' => $f_views,
  'created_by' => $f_created_by,
  'created_at' => $f_created_at,
  'updated_at' => $f_updated_at,
  'published_at' => $f_published_at,
  'category_name' => $f_category_name
];
$post_stmt->close(); // Close statement after fetching

// Fetch content block IDs and types first
$blocks_sql = "SELECT block_id, block_type FROM blog_content_blocks WHERE blog_id = ? ORDER BY block_order ASC"; // Changed post_id to blog_id
$blocks_stmt = $conn->prepare($blocks_sql);
$blocks_stmt->bind_param("i", $blog_id); // Use blog_id
$blocks_stmt->execute();
$blocks_stmt->bind_result($block_id, $block_type);

$content_blocks_data = [];
while ($blocks_stmt->fetch()) {
    $block_data = ['block_type' => $block_type]; // Initialize with type

    // Fetch specific block data based on type
    switch ($block_type) {
        case 'text':
            $text_sql = "SELECT section_title, content FROM blog_text_blocks WHERE block_id = ?";
            $text_stmt = $conn->prepare($text_sql);
            $text_stmt->bind_param("i", $block_id);
            $text_stmt->execute();
            $text_stmt->bind_result($section_title, $content);
            $text_stmt->fetch();
            $block_data = array_merge($block_data, ['section_title' => $section_title, 'content' => $content]);
            $text_stmt->close();
            break;
        case 'image':
            $image_sql = "SELECT image_path, caption FROM blog_image_blocks WHERE block_id = ?"; // Removed alignment as it's not used in view
            $image_stmt = $conn->prepare($image_sql);
            $image_stmt->bind_param("i", $block_id);
            $image_stmt->execute();
            $image_stmt->bind_result($image_path, $caption);
            $image_stmt->fetch();
            $block_data = array_merge($block_data, ['image_path' => $image_path, 'caption' => $caption]);
            $image_stmt->close();
            break;
        case 'quote':
            $quote_sql = "SELECT quote_text, attribution FROM blog_quote_blocks WHERE block_id = ?"; // Removed style as it's not used in view
            $quote_stmt = $conn->prepare($quote_sql);
            $quote_stmt->bind_param("i", $block_id);
            $quote_stmt->execute();
            $quote_stmt->bind_result($quote_text, $attribution);
            $quote_stmt->fetch();
            $block_data = array_merge($block_data, ['quote_text' => $quote_text, 'attribution' => $attribution]);
            $quote_stmt->close();
            break;
        case 'list':
            $list_sql = "SELECT list_title FROM blog_list_blocks WHERE block_id = ?"; // Fetch list title
            $list_stmt = $conn->prepare($list_sql);
            $list_stmt->bind_param("i", $block_id);
            $list_stmt->execute();
            $list_stmt->bind_result($list_title);
            $list_stmt->fetch();
            $list_stmt->close();

            // Fetch list items
            $items_sql = "SELECT item_text FROM blog_list_items WHERE list_block_id = ? ORDER BY item_order ASC";
            $items_stmt = $conn->prepare($items_sql);
            // Assuming list_block_id in blog_list_items corresponds to block_id in blog_list_blocks
            // Need to get the list_block_id first if it's different from block_id
            // For now, assuming block_id from blog_content_blocks is the foreign key used in blog_list_blocks
            // And blog_list_items uses list_block_id which refers to blog_list_blocks primary key.
            // Let's get the list_block_id from blog_list_blocks first.
            $get_list_block_id_sql = "SELECT list_block_id FROM blog_list_blocks WHERE block_id = ?";
            $get_list_block_id_stmt = $conn->prepare($get_list_block_id_sql);
            $get_list_block_id_stmt->bind_param("i", $block_id);
            $get_list_block_id_stmt->execute();
            $get_list_block_id_stmt->bind_result($list_block_id);
            if($get_list_block_id_stmt->fetch()) {
                $items_stmt->bind_param("i", $list_block_id);
                $items_stmt->execute();
                $items_stmt->bind_result($item_text);
                $list_items = [];
                while ($items_stmt->fetch()) {
                    $list_items[] = $item_text;
                }
                $block_data['title'] = $list_title ?? null; // Use 'title' key consistent with old structure
                $block_data['content'] = json_encode($list_items); // Store items as JSON string, similar to old structure
                $items_stmt->close();
            }
            $get_list_block_id_stmt->close();

            break;
    }
    $content_blocks_data[] = $block_data;
}
$blocks_stmt->close(); // Close statement after fetching

// Fetch gallery images for this post
$gallery_sql = "SELECT * FROM blog_gallery_images WHERE blog_id = ? ORDER BY image_order ASC"; // Changed table name and post_id to blog_id
$gallery_stmt = $conn->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $blog_id); // Use blog_id
$gallery_stmt->execute();
$gallery_stmt->bind_result($gallery_image_id, $g_blog_id, $image_path, $image_order, $created_at);

$gallery_images = [];
while ($gallery_stmt->fetch()) {
  $gallery_images[] = [
    'gallery_image_id' => $gallery_image_id,
    'blog_id' => $g_blog_id,
    'image_path' => $image_path,
    'image_order' => $image_order,
    'created_at' => $created_at
  ];
}
$gallery_stmt->close(); // Close statement after fetching

// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(stripslashes($post['title'])); // Added stripslashes ?> - Virunga Ecotours</title>
    <link
      rel="shortcut icon"
      href="../../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/view-blog.css" />
    <script src="../js/common.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>


      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>


        <div class="container">
          <div class="blog-view-container">
            <div class="blog-view-header">
              <h1 class="blog-view-title"><?php echo htmlspecialchars(stripslashes($post['title'])); // Added stripslashes ?></h1>
              <div class="blog-meta">
                <span class="blog-author">
                  <i class="fas fa-user"></i> <?php echo htmlspecialchars(stripslashes($post['author'])); // Added stripslashes ?>
                </span>
                <span class="blog-read-time">
                  <i class="fas fa-clock"></i> <?php echo htmlspecialchars(stripslashes($post['read_minutes'])); // Added stripslashes ?> min read <!-- Changed read_time to read_minutes -->
                </span>
                <span class="blog-category">
                  <i class="fas fa-tag"></i> <?php echo htmlspecialchars(stripslashes($post['category_name'])); // Added stripslashes ?> <!-- Changed category to category_name -->
                </span>
              </div>
            </div>

            <div class="blog-view-cover">
              <img
                src="../images/blog/covers/<?php echo htmlspecialchars(stripslashes($post['cover_image'])); // Added stripslashes ?>"
                alt="<?php echo htmlspecialchars(stripslashes($post['title'])); // Added stripslashes ?>"
              />
            </div>

            <div class="blog-view-content">
              <div class="blog-intro">
                <h2><?php echo htmlspecialchars(stripslashes($post['main_headline'])); // Added stripslashes ?></h2> <!-- Changed headline to main_headline -->
                <p><?php echo stripslashes($post['introduction']); // Already had stripslashes, no htmlspecialchars needed here unless intro contains HTML tags you want to display as text ?></p>
              </div>

              <?php foreach ($content_blocks_data as $block): ?>
                <?php if ($block['block_type'] === 'text'): ?>
                  <div class="content-block text-block">
                    <?php if (!empty($block['section_title'])): ?>
                      <h3><?php echo htmlspecialchars(stripslashes($block['section_title'])); ?></h3> <!-- Use section_title -->
                    <?php endif; ?>
                    <p><?php echo stripslashes($block['content']); ?></p>
                  </div>
                <?php elseif ($block['block_type'] === 'image'): ?>
                  <div class="content-block image-block">
                    <img
                      src="../images/blog/content/<?php echo htmlspecialchars(stripslashes($block['image_path'])); // Added stripslashes ?>"
                      alt="<?php echo htmlspecialchars(stripslashes($block['caption'] ?? '')); // Added stripslashes ?>"
                    />
                    <?php if (!empty($block['caption'])): ?>
                      <p class="image-caption"><?php echo htmlspecialchars(stripslashes($block['caption'])); // Added stripslashes ?></p> <!-- Use caption -->
                    <?php endif; ?>
                  </div>
                <?php elseif ($block['block_type'] === 'quote'): ?>
                  <div class="content-block quote-block">
                    <blockquote>
                      <?php echo htmlspecialchars(stripslashes($block['quote_text'])); // Added stripslashes ?> <!-- Use quote_text -->
                    </blockquote>
                    <?php if (!empty($block['attribution'])): ?>
                      <cite>— <?php echo htmlspecialchars(stripslashes($block['attribution'])); // Added stripslashes ?></cite> <!-- Use attribution -->
                    <?php endif; ?>
                  </div>
                <?php elseif ($block['block_type'] === 'list'): ?>
                  <div class="content-block list-block">
                    <?php if (!empty($block['title'])): ?> <!-- Use title (fetched from list_title) -->
                      <h3><?php echo htmlspecialchars(stripslashes($block['title'])); // Already updated ?></h3>
                    <?php endif; ?>
                    <ul class="content-list">
                      <?php
                        // Content is now stored as a JSON array string
                        $list_items = json_decode($block['content'] ?? '[]');
                        foreach ($list_items as $item):
                          $clean_item = trim(stripslashes($item)); // Already updated
                          if ($clean_item !== ''):
                      ?>
                        <li><?php echo htmlspecialchars($clean_item); // Already updated ?></li>
                      <?php
                          endif;
                        endforeach;
                      ?>
                    </ul>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>

              <?php if (!empty($gallery_images)): ?>
                <div class="blog-gallery">
                  <h3>Gallery</h3>
                  <div class="gallery-grid">
                    <?php foreach ($gallery_images as $image): ?>
                      <div class="gallery-item">
                        <img
                          src="../images/blog/gallery/<?php echo htmlspecialchars(stripslashes($image['image_path'])); // Added stripslashes ?>"
                          alt="Gallery Image"
                        />
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="blog-view-footer">
              <a href="blogs.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Blogs
              </a>
            </div>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
<?php
$conn->close(); // Close the database connection at the end
?>