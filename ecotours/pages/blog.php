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
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      Virunga Ecotours - Discover the Heart of Africa's Most Diverse Mountain
      Range
    </title>
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
    <link rel="stylesheet" href="../css/blog.css" />
    <script src="../js/header.js"></script>
  </head>
  <body>
    <?php include('includes/header.php'); ?>
    
    <section class="breadcrumbs">
      <div class="container">
        <ul class="breadcrumbs-list">
          <li><a href="../index.php">Home</a></li>
          <li>Blogs</li>
        </ul>
      </div>
    </section>

    <section class="hero">
      <div class="hero-image"></div>
      <div class="hero-overlay">
        <h1 class="hero-title">Virunga Ecotours Stories</h1>
        <p class="hero-subtitle">
          Discover the heart of Africa's most diverse mountain range through our
          expert insights, travel tips, and authentic local experiences.
        </p>
        <a href="#" class="cta-btn">Plan Your Journey</a>
      </div>
    </section>

    <section class="blog-section">
      <div class="container">
        <h2 class="section-title">Virunga Ecotours Stories</h2>

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
            
            <div class="blog-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; width: 100%; max-width: 1200px; margin: 0 auto;"> 
                <?php if (!empty($result)): ?>
                    <?php foreach ($result as $post): ?>
                        
                        <div class="blog-item" 
                             data-category="<?= htmlspecialchars($post['category_slug']) // Use category slug ?>"
                             style="background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #d8c3a5; display: flex; flex-direction: column;"> 
                            <div class="blog-item-image">
                                
                                <img src="../admin/images/blog/covers/<?= htmlspecialchars(stripslashes($post['cover_image'])) ?>"
                                     alt="<?= htmlspecialchars(stripslashes($post['title'])) ?>"
                                     style="width: 100%; height: 200px; object-fit: cover; display: block;">
                            </div>
                            
                            <div class="blog-item-content" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;"> 
                                
                                <span class="blog-item-category" style="display: inline-block; background-color: #a68c69; color: #f6f4f0; padding: 4px 10px; border-radius: 4px; font-size: 0.8em; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; align-self: flex-start;"><?= htmlspecialchars(strtoupper(stripslashes($post['category_name']))) ?></span> 
                                
                                <h3 class="blog-item-title" style="margin-top: 0; margin-bottom: 10px; font-size: 1.4em; color: #3a3026; line-height: 1.3;"><?= htmlspecialchars(stripslashes($post['title'])) ?></h3> 
                                
                                <p class="blog-item-description" style="font-size: 0.95em; color: #5d4e41; line-height: 1.6; margin-bottom: 15px; flex-grow: 1;"> 
                                    <?php 
                                        // Get the raw snippet
                                        $intro_snippet = substr(strip_tags($post['introduction']), 0, 150);
                                        // Apply stripslashes and htmlspecialchars ONLY when echoing
                                        echo $intro_snippet;
                                        // Add ellipsis if the original introduction was longer
                                        if (strlen(strip_tags($post['introduction'])) > 150) {
                                            echo '...'; 
                                        }
                                    ?>
                                </p>
                                
                                <div class="blog-item-meta" style="font-size: 0.85em; color: #777; margin-bottom: 15px; margin-top: auto;"> 
                                    <span>By <?= htmlspecialchars(stripslashes($post['author'])) ?></span> | 
                                    <span><?= htmlspecialchars(stripslashes($post['read_minutes'])) ?> min read</span> |
                                    <span><?= date('M d, Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
                                </div>
                                
                                <a href="./blogopen.php?id=<?= $post['blog_id'] ?>" class="blog-item-button" style="display: inline-block; background-color: #2a4858; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; text-align: center; margin-top: 10px; align-self: flex-start;">READ MORE</a> 
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
                                newItem.style.cssText = "background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #d8c3a5; display: flex; flex-direction: column;";


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
                                             alt="${sanitizeHTML(post.title)}"
                                             style="width: 100%; height: 200px; object-fit: cover; display: block;">
                                    </div>
                                    <div class="blog-item-content" style="padding: 20px; display: flex; flex-direction: column; flex-grow: 1;"> 
                                        <span class="blog-item-category" style="display: inline-block; background-color: #a68c69; color: #f6f4f0; padding: 4px 10px; border-radius: 4px; font-size: 0.8em; font-weight: bold; margin-bottom: 10px; text-transform: uppercase; align-self: flex-start;">${sanitizeHTML(post.category_name.toUpperCase())}</span> 
                                        <h3 class="blog-item-title" style="margin-top: 0; margin-bottom: 10px; font-size: 1.4em; color: #3a3026; line-height: 1.3;">${sanitizeHTML(post.title)}</h3> 
                                        <p class="blog-item-description" style="font-size: 0.95em; color: #5d4e41; line-height: 1.6; margin-bottom: 15px; flex-grow: 1;"> 
                                            ${post.introduction_snippet}
                                            ${post.introduction_long ? '...' : ''}
                                        </p>
                                        <div class="blog-item-meta" style="font-size: 0.85em; color: #777; margin-bottom: 15px; margin-top: auto;"> 
                                            <span>By ${sanitizeHTML(post.author)}</span> | 
                                            <span>${sanitizeHTML(post.read_minutes)} min read</span> |
                                            <span>${post.published_date}</span>
                                        </div>
                                        <a href="./blogopen.php?id=${post.blog_id}" class="blog-item-button" style="display: inline-block; background-color: #2a4858; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; text-align: center; margin-top: 10px; align-self: flex-start;">READ MORE</a> 
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