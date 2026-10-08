<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

require_once __DIR__ . '/../config/connection.php';
require_once __DIR__ . '/../helpers/blog_text.php';

// Get blog post ID from URL
$blog_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blog_id <= 0) {
    header('Location: blogs.php?error=invalid_id');
    exit();
}

// Fetch blog post details along with category name
$post_sql = "SELECT bp.*, bc.category_name
             FROM blog_posts bp
             LEFT JOIN blog_categories bc ON bp.category_id = bc.category_id
             WHERE bp.blog_id = ?";
$post_stmt = $conn->prepare($post_sql);
$post_stmt->bind_param("i", $blog_id);
$post_stmt->execute();
$post_result = $post_stmt->get_result();

if (!$post_result || $post_result->num_rows === 0) {
    $post_stmt->close();
    header('Location: blogs.php?error=not_found');
    exit();
}

$post = $post_result->fetch_assoc();
$post_stmt->close();

// Fetch content blocks using buffered get_result() to prevent "Commands out of sync"
$blocks_sql = "SELECT block_id, block_type FROM blog_content_blocks WHERE blog_id = ? ORDER BY block_order ASC";
$blocks_stmt = $conn->prepare($blocks_sql);
$blocks_stmt->bind_param("i", $blog_id);
$blocks_stmt->execute();
$blocks_result = $blocks_stmt->get_result();

$content_blocks_data = [];
while ($block_row = $blocks_result->fetch_assoc()) {
    $block_id = (int)$block_row['block_id'];
    $block_type = $block_row['block_type'];
    $block_data = ['block_type' => $block_type];

    switch ($block_type) {
        case 'text':
            $text_sql = "SELECT section_title, content FROM blog_text_blocks WHERE block_id = ?";
            $text_stmt = $conn->prepare($text_sql);
            $text_stmt->bind_param("i", $block_id);
            $text_stmt->execute();
            $t_res = $text_stmt->get_result();
            if ($t_row = $t_res->fetch_assoc()) {
                $block_data['section_title'] = $t_row['section_title'];
                $block_data['content'] = $t_row['content'];
            }
            $text_stmt->close();
            break;

        case 'image':
            $image_sql = "SELECT image_path, caption, alignment FROM blog_image_blocks WHERE block_id = ?";
            $image_stmt = $conn->prepare($image_sql);
            $image_stmt->bind_param("i", $block_id);
            $image_stmt->execute();
            $i_res = $image_stmt->get_result();
            if ($i_row = $i_res->fetch_assoc()) {
                $block_data['image_path'] = $i_row['image_path'];
                $block_data['caption'] = $i_row['caption'];
                $block_data['alignment'] = $i_row['alignment'];
            }
            $image_stmt->close();
            break;

        case 'quote':
            $quote_sql = "SELECT quote_text, attribution, style FROM blog_quote_blocks WHERE block_id = ?";
            $quote_stmt = $conn->prepare($quote_sql);
            $quote_stmt->bind_param("i", $block_id);
            $quote_stmt->execute();
            $q_res = $quote_stmt->get_result();
            if ($q_row = $q_res->fetch_assoc()) {
                $block_data['quote_text'] = $q_row['quote_text'];
                $block_data['attribution'] = $q_row['attribution'];
                $block_data['style'] = $q_row['style'];
            }
            $quote_stmt->close();
            break;

        case 'list':
            $list_sql = "SELECT list_block_id, list_title, list_type FROM blog_list_blocks WHERE block_id = ?";
            $list_stmt = $conn->prepare($list_sql);
            $list_stmt->bind_param("i", $block_id);
            $list_stmt->execute();
            $l_res = $list_stmt->get_result();
            if ($l_row = $l_res->fetch_assoc()) {
                $list_block_id = (int)$l_row['list_block_id'];
                $block_data['title'] = $l_row['list_title'];
                $block_data['list_type'] = $l_row['list_type'];

                $items_sql = "SELECT item_text FROM blog_list_items WHERE list_block_id = ? ORDER BY item_order ASC";
                $items_stmt = $conn->prepare($items_sql);
                $items_stmt->bind_param("i", $list_block_id);
                $items_stmt->execute();
                $items_res = $items_stmt->get_result();
                $list_items = [];
                while ($item_row = $items_res->fetch_assoc()) {
                    $list_items[] = $item_row['item_text'];
                }
                $items_stmt->close();
                $block_data['content'] = json_encode($list_items);
            }
            $list_stmt->close();
            break;
    }
    $content_blocks_data[] = $block_data;
}
$blocks_stmt->close();

// Fetch gallery images using buffered get_result()
$gallery_sql = "SELECT gallery_image_id, image_path, image_order FROM blog_gallery_images WHERE blog_id = ? ORDER BY image_order ASC";
$gallery_stmt = $conn->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $blog_id);
$gallery_stmt->execute();
$gallery_result = $gallery_stmt->get_result();
$gallery_images = [];
while ($g_row = $gallery_result->fetch_assoc()) {
    $gallery_images[] = $g_row;
}
$gallery_stmt->close();

$currentStatus = strtolower($post['status'] ?? 'draft');
$coverImg = !empty($post['cover_image']) ? '../images/blog/covers/' . $post['cover_image'] : '../images/costa-rica.jpg';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars(normalizeStoredBlogText($post['title'])); ?> - Virunga Journeys</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/view-blog.css" />
    <script src="../js/common.js" defer></script>
    <style>
      .view-top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 12px;
      }
      .view-actions-group {
        display: flex;
        gap: 10px;
        align-items: center;
      }
      .status-badge-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: capitalize;
      }
      .status-badge-indicator.published {
        background: #e6fcf5;
        color: #0ca678;
        border: 1px solid #c3fae8;
      }
      .status-badge-indicator.draft {
        background: #fff9db;
        color: #f59f00;
        border: 1px solid #ffe066;
      }
      .status-badge-indicator.archived {
        background: #f1f3f5;
        color: #868e96;
        border: 1px solid #dee2e6;
      }
      .btn-action-view {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid #d0d7de;
        background: #fff;
        color: #24292f;
        transition: all 0.2s ease;
      }
      .btn-action-view:hover {
        background: #f6f8fa;
        border-color: #1b1f24;
      }
      .btn-action-view.primary {
        background: #206bc4;
        border-color: #206bc4;
        color: #fff;
      }
      .btn-action-view.primary:hover {
        background: #1a569d;
      }
      .quote-block cite {
        display: block;
        margin-top: 8px;
        font-style: normal;
        color: #64748b;
        font-size: 0.95rem;
      }
    </style>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once __DIR__ . '/includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include_once __DIR__ . '/includes/header.php'; ?>

        <div class="container" style="padding-top: 1rem; padding-bottom: 3rem;">

          <div class="blog-view-container">

            <!-- Top Action Navigation -->
            <div class="view-top-actions">
              <a href="blogs.php" class="btn-action-view">
                <i class="fas fa-arrow-left"></i> Back to Blog List
              </a>

              <div class="view-actions-group">
                <span class="status-badge-indicator <?php echo $currentStatus; ?>">
                  <i class="fas <?php echo ($currentStatus === 'published') ? 'fa-check-circle' : 'fa-file-lines'; ?>"></i>
                  <?php echo ucfirst($currentStatus); ?>
                </span>

                <button type="button" class="btn-action-view" onclick="toggleStatus(<?php echo $post['blog_id']; ?>, '<?php echo $currentStatus; ?>')">
                  <i class="fas fa-arrows-rotate"></i> Change to <?php echo ($currentStatus === 'published') ? 'Draft' : 'Published'; ?>
                </button>

                <a href="edit_blog.php?id=<?php echo $post['blog_id']; ?>" class="btn-action-view primary">
                  <i class="fas fa-edit"></i> Edit Article
                </a>

                <?php if ($currentStatus === 'published'): ?>
                  <a href="../../pages/blogopen.php?id=<?php echo $post['blog_id']; ?>" target="_blank" class="btn-action-view" title="View on live website">
                    <i class="fas fa-arrow-up-right-from-square"></i> Live Page
                  </a>
                <?php endif; ?>
              </div>
            </div>

            <div class="blog-view-header">
              <h1 class="blog-view-title"><?php echo htmlspecialchars(normalizeStoredBlogText($post['title'])); ?></h1>
              <div class="blog-meta">
                <span class="blog-author">
                  <i class="fas fa-user"></i> <?php echo htmlspecialchars(normalizeStoredBlogText($post['author'] ?: 'Virunga Team')); ?>
                </span>
                <span class="blog-read-time">
                  <i class="fas fa-clock"></i> <?php echo htmlspecialchars(normalizeStoredBlogText($post['read_minutes'])); ?> min read
                </span>
                <span class="blog-category">
                  <i class="fas fa-tag"></i> <?php echo htmlspecialchars(normalizeStoredBlogText($post['category_name'] ?: 'Editorial')); ?>
                </span>
                <span>
                  <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                </span>
              </div>
            </div>

            <div class="blog-view-cover">
              <img
                src="<?php echo htmlspecialchars($coverImg); ?>"
                alt="<?php echo htmlspecialchars(normalizeStoredBlogText($post['title'])); ?>"
                onerror="this.onerror=null;this.src='../images/costa-rica.jpg';"
              />
            </div>

            <div class="blog-view-content">
              <?php if (!empty($post['main_headline'])): ?>
                <div class="blog-intro">
                  <h2><?php echo htmlspecialchars(normalizeStoredBlogText($post['main_headline'])); ?></h2>
                  <div><?php echo normalizeStoredBlogText($post['introduction']); ?></div>
                </div>
              <?php else: ?>
                <div class="blog-intro">
                  <div><?php echo normalizeStoredBlogText($post['introduction']); ?></div>
                </div>
              <?php endif; ?>

              <?php foreach ($content_blocks_data as $block): ?>
                <?php if ($block['block_type'] === 'text'): ?>
                  <div class="content-block text-block">
                    <?php if (!empty($block['section_title'])): ?>
                      <h3><?php echo htmlspecialchars(normalizeStoredBlogText($block['section_title'])); ?></h3>
                    <?php endif; ?>
                    <div><?php echo normalizeStoredBlogText($block['content']); ?></div>
                  </div>
                <?php elseif ($block['block_type'] === 'image'): ?>
                  <div class="content-block image-block">
                    <img loading="lazy" decoding="async"
                      src="../images/blog/content/<?php echo htmlspecialchars(normalizeStoredBlogText($block['image_path'])); ?>"
                      alt="<?php echo htmlspecialchars(normalizeStoredBlogText($block['caption'] ?? '')); ?>"
                      onerror="this.style.display='none';"
                    />
                    <?php if (!empty($block['caption'])): ?>
                      <p class="image-caption"><?php echo htmlspecialchars(normalizeStoredBlogText($block['caption'])); ?></p>
                    <?php endif; ?>
                  </div>
                <?php elseif ($block['block_type'] === 'quote'): ?>
                  <div class="content-block quote-block">
                    <blockquote>
                      <?php echo htmlspecialchars(normalizeStoredBlogText($block['quote_text'])); ?>
                    </blockquote>
                    <?php if (!empty($block['attribution'])): ?>
                      <cite>— <?php echo htmlspecialchars(normalizeStoredBlogText($block['attribution'])); ?></cite>
                    <?php endif; ?>
                  </div>
                <?php elseif ($block['block_type'] === 'list'): ?>
                  <div class="content-block list-block">
                    <?php if (!empty($block['title'])): ?>
                      <h3><?php echo htmlspecialchars(normalizeStoredBlogText($block['title'])); ?></h3>
                    <?php endif; ?>
                    <ul class="content-list">
                      <?php
                        $list_items = json_decode($block['content'] ?? '[]', true);
                        if (is_array($list_items)) {
                          foreach ($list_items as $item):
                            $clean_item = trim(normalizeStoredBlogText((string)$item));
                            if ($clean_item !== ''):
                      ?>
                        <li><?php echo htmlspecialchars($clean_item); ?></li>
                      <?php
                            endif;
                          endforeach;
                        }
                      ?>
                    </ul>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>

              <?php if (!empty($gallery_images)): ?>
                <div class="blog-gallery">
                  <h3><i class="fas fa-images"></i> Story Photo Gallery</h3>
                  <div class="gallery-grid">
                    <?php foreach ($gallery_images as $image): ?>
                      <div class="gallery-item">
                        <img loading="lazy" decoding="async"
                          src="../images/blog/gallery/<?php echo htmlspecialchars(normalizeStoredBlogText($image['image_path'])); ?>"
                          alt="Gallery Photo"
                          onerror="this.onerror=null;this.src='../images/costa-rica.jpg';"
                        />
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
            </div>

            <div class="blog-view-footer">
              <a href="blogs.php" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Blog List
              </a>
              <a href="edit_blog.php?id=<?php echo $post['blog_id']; ?>" class="btn-action-view primary">
                <i class="fas fa-edit"></i> Edit Article
              </a>
            </div>

          </div>
        </div>
      </main>
    </div>

    <script>
      async function toggleStatus(blogId, currentStatus) {
        const nextStatus = (currentStatus === 'published') ? 'draft' : 'published';
        if (confirm(`Change article status from '${currentStatus}' to '${nextStatus}'?`)) {
          try {
            const res = await fetch('../handlers/blog/update_blog_status.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `blog_id=${blogId}&new_status=${nextStatus}`
            });
            const data = await res.json();
            if (data.success) {
              window.location.reload();
            } else {
              alert(data.message || 'Error changing status');
            }
          } catch (e) {
            alert('Network or server error updating status');
          }
        }
      }
    </script>
  </body>
</html>