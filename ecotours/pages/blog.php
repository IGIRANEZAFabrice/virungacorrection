<?php
require_once '../admin/config/connection.php';

$initial_limit = 6; // Number of posts to show initially

// Get the total count of published blog posts
$count_query = "SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'";
$count_result = $conn->query($count_query);
$total_posts = 0;
if ($count_result) {
    $total_posts = $count_result->fetch_assoc()['total'];
}

// Get initial batch of published blog posts with their category slugs
$query = "SELECT bp.*, bc.category_slug, bc.category_name 
          FROM blog_posts bp
          JOIN blog_categories bc ON bp.category_id = bc.category_id
          WHERE bp.status = 'published' 
          ORDER BY bp.published_at DESC, bp.created_at DESC
          LIMIT ?"; // Add LIMIT clause
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $initial_limit);
$stmt->execute();

// Fallback for environments without mysqlnd
$result = [];
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $meta = $stmt->result_metadata();
    $fields = [];
    $row = [];
    while ($field = $meta->fetch_field()) {
        $fields[] = &$row[$field->name];
    }
    call_user_func_array([$stmt, 'bind_result'], $fields);
    while ($stmt->fetch()) {
        $c = [];
        foreach ($row as $key => $val) {
            $c[$key] = $val;
        }
        $result[] = $c;
    }
}
$stmt->close();


// Get only categories that have published posts
$categories_query = "SELECT DISTINCT bc.category_slug, bc.category_name 
                     FROM blog_categories bc
                     JOIN blog_posts bp ON bc.category_id = bp.category_id
                     WHERE bp.status = 'published' 
                     ORDER BY bc.category_name ASC";
$categories_result = $conn->query($categories_query);

function cleanBlogExcerpt($content, $length = 150) {
    $text = stripslashes((string) $content);
    $text = str_replace(['\\r\\n', '\\n', '\\r'], ' ', $text);
    $text = preg_replace('/\s+n{1,3}\s+/i', ' ', $text);
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
    return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <title>Virunga Blog - Stories from the Virunga</title>
    <link
      rel="shortcut icon"
      href="../images/logos/icon.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/earthy-theme.css" />
    <link rel="stylesheet" href="../css/header.css" />
    <link rel="stylesheet" href="../css/blog.css?v=20260902-blog-cards" />
    <script src="../js/header.js"></script>
  </head>
	  <body class="blog-page">
    <?php include('includes/header.php'); ?>
    
    <section class="breadcrumbs">
      <div class="container">
        <ul class="breadcrumbs-list">
          <li><a href="../index.php">Home</a></li>
	          <li>Blog</li>
        </ul>
      </div>
    </section>

	    <section class="hero blog-hero">
	      <div class="hero-image"></div>
	      <div class="hero-overlay">
	        <span class="blog-kicker">Journal</span>
	        <h1 class="hero-title">Stories from the Virunga</h1>
	        <p class="hero-subtitle">
	          Field notes, travel ideas and encounters from Rwanda, Uganda and DR Congo.
	        </p>
	        <a href="#blog-posts" class="cta-btn">Browse Stories</a>
	      </div>
	    </section>

	    <section class="blog-section" id="blog-posts">
	      <div class="container">
	        <div class="section-heading journal-heading">
	          <span class="section-eyebrow">Journal</span>
	          <h2>Stories from the Virunga</h2>
	          <p>Read notes, ideas and encounters from across the Virunga region.</p>
	        </div>

        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">ALL</button>
            <?php if ($categories_result && $categories_result->num_rows > 0): ?>
                <?php while ($category = $categories_result->fetch_assoc()): ?>
                    <button class="filter-btn" data-filter="<?= htmlspecialchars($category['category_slug']) ?>">
                        <?= htmlspecialchars(strtoupper($category['category_name'])) // Display category name ?>
                    </button>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        
        <div class="blog-display"> 
            
	            <div class="blog-grid"> 
                <?php if (!empty($result)): ?>
                    <?php foreach ($result as $post): ?>
                        
	                        <div class="blog-item" data-category="<?= htmlspecialchars($post['category_slug']) // Use category slug ?>"> 
                            <div class="blog-item-image">
                                
                                <img src="../admin/images/blog/covers/<?= htmlspecialchars(stripslashes($post['cover_image'])) ?>"
                                     alt="<?= htmlspecialchars(stripslashes($post['title'])) ?>"
                                     loading="lazy">
	                            </div>
	                            
	                            <div class="blog-item-content"> 
	                                
	                                <span class="blog-item-category"><?= htmlspecialchars(strtoupper(stripslashes($post['category_name']))) ?></span> 
	                                
	                                <h3 class="blog-item-title"><?= htmlspecialchars(stripslashes($post['title'])) ?></h3> 
	                                
	                                <p class="blog-item-description"> 
	                                    <?= htmlspecialchars(cleanBlogExcerpt($post['introduction'])) ?>
	                                </p>
	                                
	                                <div class="blog-item-meta"> 
	                                    <span>By <?= htmlspecialchars(stripslashes($post['author'])) ?></span>
	                                    <span><?= htmlspecialchars(stripslashes($post['read_minutes'])) ?> min read</span>
	                                    <span><?= date('M d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
	                                </div>
	                                
	                                <a href="./blogopen.php?id=<?= $post['blog_id'] ?>" class="blog-item-button">Read More</a> 
	                            </div>
	                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    
                    <p class="no-posts" style="grid-column: 1 / -1; text-align: center; color: #5d4e41; padding: 40px 0;">No published blog posts found.</p> 
                <?php endif; ?>
            </div>
          </div>
          <div class="load-more-container">
              <?php if ($total_posts > $initial_limit): ?>
                  <button id="load-more-btn" 
                          class="load-more-button"
                          data-limit="<?= $initial_limit ?>" 
                          data-offset="<?= $initial_limit ?>" 
                          data-total="<?= $total_posts ?>">
                      Load More Posts
                  </button>
              <?php endif; ?>
          </div>
      </div>
    </section>

     <?php include('includes/footer.php'); ?>

    <script>
      const viewMoreButton = document.getElementById("view-more-btn");
      const hiddenItems = document.querySelectorAll(".hidden-item");

      if (viewMoreButton) { // Check if the button exists
          viewMoreButton.addEventListener("click", () => {
            // Apply staggered fade-in animation to each hidden item
            hiddenItems.forEach((item, index) => {
              setTimeout(() => {
                item.classList.add("fade-in");
                item.classList.remove("hidden-item");
              }, index * 100); // Stagger the animation by 100ms for each item
            });
    
            // Hide the button with a fade-out effect
            viewMoreButton.style.opacity = "0";
            viewMoreButton.style.transition = "opacity 0.3s ease";
    
            // Remove the button from layout after fade completes
            setTimeout(() => {
              viewMoreButton.style.display = "none";
            }, 300);
          });
      }
    </script>
    <script>
      // JavaScript to handle the filter buttons and blog items
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const blogGrid = document.querySelector('.blog-grid'); // Target the grid

        // Function to apply filter
        function applyFilter(filterValue) {
            const blogItems = blogGrid.querySelectorAll('.blog-item'); // Get current items
            blogItems.forEach(item => {
                // Check if the item's category matches the filter or if 'all' is selected
                if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                    item.style.display = 'flex'; // Use flex as defined in CSS
                } else {
                    item.style.display = 'none'; // Hide item
                }
            });
        }

        // Initial filter application (if needed, though usually starts with 'all')
        // applyFilter('all'); 

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const filterValue = button.getAttribute('data-filter');
                applyFilter(filterValue); // Apply the filter
            });
        });

        // --- Load More Button Logic ---
        const loadMoreBtn = document.getElementById('load-more-btn');
        
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                const limit = parseInt(this.dataset.limit);
                let offset = parseInt(this.dataset.offset);
                const total = parseInt(this.dataset.total);
                const currentFilter = document.querySelector('.filter-btn.active').dataset.filter; // Get active filter

                // Indicate loading state (optional)
                this.textContent = 'Loading...';
                this.disabled = true;

                // Fetch more posts from the server
                fetch(`./handlers/load_more_posts.php?offset=${offset}&limit=${limit}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(posts => {
                        if (posts.length > 0) {
                            posts.forEach(post => {
                                // **IMPORTANT**: Create HTML for the new blog item.
                                // This needs to exactly match the structure and escaping used in the PHP loop.
	                                const newItem = document.createElement('div');
	                                newItem.classList.add('blog-item');
	                                newItem.dataset.category = post.category_slug; // Ensure category slug is in JSON


                                // Sanitize function (basic example, consider a library for robustness)
                                function sanitizeHTML(str) {
                                    if (!str) return ''; // Handle null or undefined input
                                    const temp = document.createElement('div');
                                    temp.textContent = str;
                                    return temp.innerHTML;
                                }
                                
                                // Construct inner HTML carefully
	                                newItem.innerHTML = `
	                                    <div class="blog-item-image">
	                                        <img src="../admin/images/blog/covers/${sanitizeHTML(post.cover_image)}"
	                                             alt="${sanitizeHTML(post.title)}">
	                                    </div>
	                                    <div class="blog-item-content"> 
	                                        <span class="blog-item-category">${sanitizeHTML(post.category_name.toUpperCase())}</span> 
	                                        <h3 class="blog-item-title">${sanitizeHTML(post.title)}</h3> 
	                                        <p class="blog-item-description"> 
	                                            ${post.introduction_snippet}
	                                            ${post.introduction_long ? '...' : ''}
	                                        </p>
	                                        <div class="blog-item-meta"> 
	                                            <span>By ${sanitizeHTML(post.author)}</span>
	                                            <span>${sanitizeHTML(post.read_minutes)} min read</span>
	                                            <span>${post.published_date}</span>
	                                        </div>
	                                        <a href="./blogopen.php?id=${post.blog_id}" class="blog-item-button">Read More</a> 
	                                    </div>
	                                `;

                                blogGrid.appendChild(newItem);

                                // Apply current filter to the newly added item
                                if (currentFilter !== 'all' && newItem.dataset.category !== currentFilter) {
                                    newItem.style.display = 'none';
                                } else {
                                     newItem.style.display = 'flex'; // Match CSS display
                                }
                            });

                            // Update offset for the next click
                            offset += posts.length;
                            this.dataset.offset = offset;

                            // Hide button if all posts are loaded
                            if (offset >= total) {
                                this.style.display = 'none'; // Hide the button
                            }
                        } else {
                            // No more posts found
                            this.style.display = 'none'; // Hide the button
                        }
                    })
                    .catch(error => {
                        console.error('Error loading more posts:', error);
                        this.textContent = 'Error loading posts'; // Show error
                        // Optionally re-enable after a delay or keep disabled
                    })
                    .finally(() => {
                        // Reset button state if it's still visible
                        if (this.style.display !== 'none') {
                             this.textContent = 'Load More Posts';
                             this.disabled = false;
                        }
                    });
            });
        }

    });
    </script>
  </body>
</html>
