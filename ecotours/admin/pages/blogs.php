<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.html');
  exit();
}

require_once('../config/connection.php');

$status_filter = trim($_GET['status'] ?? '');
$search = trim($_GET['search'] ?? '');

$sql = "SELECT 
            bp.blog_id, 
            bp.title, 
            bp.cover_image, 
            bp.status, 
            bp.created_at,
            bc.category_name, 
            COUNT(bco.comment_id) AS comment_count 
        FROM 
            blog_posts bp 
        LEFT JOIN 
            blog_categories bc ON bp.category_id = bc.category_id 
        LEFT JOIN 
            blog_comments bco ON bp.blog_id = bco.blog_id";

$where = [];
if (!empty($status_filter)) {
    $where[] = "bp.status = '" . mysqli_real_escape_string($conn, $status_filter) . "'";
}
if (!empty($search)) {
    $s = mysqli_real_escape_string($conn, $search);
    $where[] = "(bp.title LIKE '%$s%' OR bc.category_name LIKE '%$s%')";
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " GROUP BY bp.blog_id, bp.title, bp.cover_image, bp.status, bp.created_at, bc.category_name 
          ORDER BY bp.created_at DESC";

$result = $conn->query($sql);
$posts = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}

// KPI Counts
$total_posts = (int)$conn->query("SELECT COUNT(*) as c FROM blog_posts")->fetch_assoc()['c'];
$published_count = (int)$conn->query("SELECT COUNT(*) as c FROM blog_posts WHERE status = 'published'")->fetch_assoc()['c'];
$draft_count = (int)$conn->query("SELECT COUNT(*) as c FROM blog_posts WHERE status = 'draft'")->fetch_assoc()['c'];
$total_comments = (int)$conn->query("SELECT COUNT(*) as c FROM blog_comments")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blogs & Journal Stories - Virunga Admin</title>
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
    <link rel="stylesheet" href="../css/common.css" />
    <link rel="stylesheet" href="../css/blog.css" />
    <script src="../js/common.js" defer></script>
  </head>
  <body>
    <div class="admin-container">
      <!-- Include sidebar template -->
      <?php include_once './includes/sidebar.php'; ?>

      <main class="main-content">
        <!-- Top Header -->
        <?php include_once './includes/header.php'; ?>

        <div class="blogs-management-container">
          
          <!-- Page Header Row -->
          <div class="page-header-row">
            <div class="page-title-wrap">
              <div class="breadcrumb-trail">
                <a href="../index.php">Dashboard</a>
                <span>/</span>
                <span>Editorial & Stories</span>
              </div>
              <h1 class="page-title">
                <i class="fas fa-newspaper" style="color: #206bc4;"></i>
                Journal & Field Stories
              </h1>
              <p class="page-subtitle">
                Publish conservation dispatches, gorilla trek insights, cultural stories, and traveler field guides.
              </p>
            </div>

            <div class="page-actions-wrap">
              <a href="../../journal.php" target="_blank" class="btn btn-outline btn-sm">
                <i class="fas fa-eye"></i> View Live Journal
              </a>
              <a href="blog_comments.php" class="btn btn-outline btn-sm">
                <i class="fas fa-comments"></i> Manage Comments (<?php echo $total_comments; ?>)
              </a>
              <a href="create_blog.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Write New Story
              </a>
            </div>
          </div>

          <!-- KPI Metric Row -->
          <div class="kpi-row">
            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_posts; ?></span>
                <span class="kpi-badge badge-blue"><i class="fas fa-book"></i> Total</span>
              </div>
              <span class="kpi-title">All Journal Articles</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $published_count; ?></span>
                <span class="kpi-badge badge-green"><i class="fas fa-globe"></i> Live</span>
              </div>
              <span class="kpi-title">Published Stories</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $draft_count; ?></span>
                <span class="kpi-badge badge-amber"><i class="fas fa-file-pen"></i> Drafts</span>
              </div>
              <span class="kpi-title">Drafts / In Progress</span>
            </div>

            <div class="kpi-card">
              <div class="kpi-top">
                <span class="kpi-num"><?php echo $total_comments; ?></span>
                <span class="kpi-badge badge-purple"><i class="fas fa-comments"></i> Reader</span>
              </div>
              <span class="kpi-title">Reader Comments</span>
            </div>
          </div>

          <!-- Filter Toolbar -->
          <div class="toolbar-card">
            <div class="filter-pills-wrap">
              <a href="blogs.php" class="filter-pill <?php echo empty($status_filter) ? 'active' : ''; ?>">
                All Articles <span class="count"><?php echo $total_posts; ?></span>
              </a>
              <a href="blogs.php?status=published" class="filter-pill <?php echo ($status_filter === 'published') ? 'active' : ''; ?>">
                <i class="fas fa-check-circle" style="font-size: 11px;"></i> Published <span class="count"><?php echo $published_count; ?></span>
              </a>
              <a href="blogs.php?status=draft" class="filter-pill <?php echo ($status_filter === 'draft') ? 'active' : ''; ?>">
                <i class="fas fa-file-lines" style="font-size: 11px;"></i> Drafts <span class="count"><?php echo $draft_count; ?></span>
              </a>
            </div>

            <form method="GET" action="blogs.php" class="search-wrap">
              <?php if (!empty($status_filter)): ?>
                <input type="hidden" name="status" value="<?php echo htmlspecialchars($status_filter); ?>" />
              <?php endif; ?>
              <i class="fas fa-search"></i>
              <input type="text" name="search" placeholder="Search by title or category..." value="<?php echo htmlspecialchars($search); ?>" />
            </form>
          </div>

          <!-- Stories Grid -->
          <?php if (!empty($posts)): ?>
            <div class="blogs-grid">
              <?php foreach ($posts as $post): 
                $coverImg = !empty($post['cover_image']) ? '../images/blog/covers/' . $post['cover_image'] : '../images/blog/default.jpg';
                $status = strtolower($post['status'] ?: 'draft');
              ?>
                <div class="blog-card">
                  <div class="blog-img">
                    <img src="<?php echo htmlspecialchars($coverImg); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" onerror="this.src='../images/costa-rica.jpg';" />
                    <span class="blog-badge-tag"><?php echo htmlspecialchars($post['category_name'] ?: 'Editorial'); ?></span>
                    <span class="blog-status-tag status-pill <?php echo $status; ?>">
                      <?php echo ucfirst($status); ?>
                    </span>
                  </div>

                  <div class="blog-content">
                    <div>
                      <span style="font-size: 11.5px; color: #64748b; margin-bottom: 4px; display: block;">
                        <i class="far fa-calendar-alt"></i> <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                      </span>
                      <h3 class="blog-title" title="<?php echo htmlspecialchars($post['title']); ?>">
                        <?php echo htmlspecialchars($post['title']); ?>
                      </h3>
                    </div>

                    <div class="blog-footer">
                      <div class="blog-comments-count">
                        <i class="fas fa-comments" style="color: #206bc4;"></i>
                        <span><?php echo (int)$post['comment_count']; ?> Comments</span>
                      </div>

                      <div class="blog-card-actions">
                        <a href="view_blog.php?id=<?php echo $post['blog_id']; ?>" class="btn-icon" title="View Article">
                          <i class="fas fa-eye"></i>
                        </a>
                        <a href="edit_blog.php?id=<?php echo $post['blog_id']; ?>" class="btn-icon" title="Edit Article">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn-icon" title="Toggle Status (Publish/Draft)" onclick="toggleBlogStatus(<?php echo $post['blog_id']; ?>, '<?php echo $status; ?>')">
                          <i class="fas fa-arrows-rotate"></i>
                        </button>
                        <button type="button" class="btn-icon danger" title="Delete Article" onclick="deleteBlogPost(<?php echo $post['blog_id']; ?>)">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <div class="card" style="text-align: center; padding: 3.5rem 1rem; color: #64748b;">
              <i class="fas fa-feather-pointed" style="font-size: 40px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
              <h3 style="font-size: 16px; color: #334155; margin: 0 0 6px;">No articles found</h3>
              <p style="margin: 0 0 16px;">Publish new articles or adjust your filter.</p>
              <a href="create_blog.php" class="btn btn-primary btn-sm">Create New Article</a>
            </div>
          <?php endif; ?>

        </div>
      </main>
    </div>

    <script>
      async function toggleBlogStatus(blogId, currentStatus) {
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

      async function deleteBlogPost(blogId) {
        if (confirm('Are you sure you want to delete this blog post? This action cannot be undone.')) {
          try {
            const res = await fetch('../handlers/blog/delete_blog.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
              body: `blog_id=${blogId}`
            });
            window.location.reload();
          } catch (e) {
            window.location.reload();
          }
        }
      }
    </script>
  </body>
</html>
