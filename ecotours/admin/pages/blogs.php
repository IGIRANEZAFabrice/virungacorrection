<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header('Location: login.html');
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tours Management - Virunga Ecotours</title>
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

        <div class="container">
          <h2 class="section-title">Our Stories
            <a href="create_blog.php" class="create-button" id="showFormBtn">
              <i class="fas fa-plus"></i> Create New Blog Post
            </a>
          </h2>

          <?php
            require_once('../config/connection.php');

            

            // Fetch all blog posts from database, joining with categories and counting comments
            $sql = "SELECT 
                        bp.blog_id, 
                        bp.title, 
                        bp.cover_image, 
                        bp.status, -- Added status field
                        bc.category_name, 
                        COUNT(bco.comment_id) AS comment_count 
                    FROM 
                        blog_posts bp 
                    LEFT JOIN 
                        blog_categories bc ON bp.category_id = bc.category_id 
                    LEFT JOIN 
                        blog_comments bco ON bp.blog_id = bco.blog_id -- Assuming blog_comments uses post_id FK referencing blog_id PK
                    GROUP BY 
                        bp.blog_id, bp.title, bp.cover_image, bp.status, bc.category_name -- Added status to GROUP BY
                    ORDER BY 
                        bp.created_at DESC";
            
            $result = $conn->query($sql);

            if ($result === false) {
                // Output error message if query fails
                echo '<p>Error fetching blog posts: ' . $conn->error . '</p>';
            } elseif ($result->num_rows > 0) {
              echo '<div class="blogs-grid">';
              
              // Output data of each row
              while($row = $result->fetch_assoc()) {
                echo '<div class="blog-card">';
                echo '  <div class="blog-img">';
                echo '    <img src="../images/blog/covers/'.$row["cover_image"].'" alt="'.$row["title"].'" />';
                echo '  </div>';
                echo '  <div class="blog-content">';
                // Use category_name from the joined table
                echo '    <span class="blog-category">'.$row["category_name"].'</span>'; 
                echo '    <h3 class="blog-title">'.$row["title"].'</h3>';
                echo '    <div class="blog-footer">';
                echo '      <div class="blog-comments">';
                echo '        <i class="fas fa-comment"></i>';
                echo '        <span>'.$row["comment_count"].' Comments</span>';
                echo '      </div>';
                echo '      <div class="blog-actions">';
                echo '        <button class="action-menu-toggle" aria-label="Actions">';
                echo '          <i class="fas fa-ellipsis-v"></i>';
                echo '        </button>';
                echo '        <div class="action-dropdown">';
                // Use blog_id for data-id attribute
                echo '          <button class="action-btn view" aria-label="View" data-id="'.$row["blog_id"].'">'; 
                echo '            <i class="fas fa-eye"></i> View';
                echo '          </button>';
                 // Use blog_id for data-id attribute
                echo '          <button class="action-btn edit" aria-label="Edit" data-id="'.$row["blog_id"].'">';
                echo '            <i class="fas fa-edit"></i> Edit';
                echo '          </button>';
                 // Use blog_id for data-id attribute
                echo '          <button class="action-btn delete" aria-label="Delete" data-id="'.$row["blog_id"].'">';
                echo '            <i class="fas fa-trash"></i> Delete';
                echo '          </button>';

                // --- Add Status Update Button ---
                $current_status = $row['status'];
                $next_status = '';
                $button_text = '';
                $button_icon = '';

                if ($current_status === 'draft') {
                    $next_status = 'published';
                    $button_text = 'Publish';
                    $button_icon = 'fa-check-circle';
                } elseif ($current_status === 'published') {
                    $next_status = 'archived';
                    $button_text = 'Archive';
                    $button_icon = 'fa-archive';
                } elseif ($current_status === 'archived') {
                    $next_status = 'published';
                    $button_text = 'Publish';
                    $button_icon = 'fa-check-circle';
                }

                if ($next_status) {
                    echo '          <button class="action-btn update-status" aria-label="'.$button_text.'" data-id="'.$row["blog_id"].'" data-current-status="'.$current_status.'" data-next-status="'.$next_status.'">';
                    echo '            <i class="fas '.$button_icon.'"></i> '.$button_text;
                    echo '          </button>';
                }
                // --- End Status Update Button ---

                echo '        </div>';
                echo '      </div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
              }
              
              echo '</div>';
            } else {
              echo '<p>No blog posts found.</p>';
            }
            $conn->close();
          ?>
          </div>
        </div>
      </main>
    </div>
    
    <script src="../js/blog.js"></script>
    <script>
      // Add JavaScript to handle status updates
      document.addEventListener('DOMContentLoaded', () => {
        const blogGrid = document.querySelector('.blogs-grid');

        if (blogGrid) {
          blogGrid.addEventListener('click', async (event) => {
            if (event.target.closest('.update-status')) {
              const button = event.target.closest('.update-status');
              const blogId = button.dataset.id;
              const nextStatus = button.dataset.nextStatus;
              const currentStatus = button.dataset.currentStatus; // For confirmation

              if (confirm(`Are you sure you want to change status from '${currentStatus}' to '${nextStatus}'?`)) {
                try {
                  // This is the fetch call that might be failing
                  const response = await fetch('../handlers/blog/update_blog_status.php', { 
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `blog_id=${blogId}&new_status=${nextStatus}`
                  });

                  const result = await response.json(); // This might fail if the response isn't valid JSON

                  if (result.success) {
                    alert('Status updated successfully!');
                    window.location.reload(); 
                  } else {
                    alert(`Error updating status: ${result.message || 'Unknown error'}`);
                  }
                } catch (error) { // The error lands here
                  console.error('Error updating status:', error);
                  alert('An error occurred while updating the status.'); // This is the alert you are seeing
                }
              }
            }
          });
        }
      });
    </script>
  </body>
</html>
