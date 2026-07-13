<?php
require_once '../config/connection.php';

// Check admin login
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

// Get all comments with post titles
$query = "SELECT bc.*, bp.title AS post_title FROM blog_comments bc 
          LEFT JOIN blog_posts bp ON bc.blog_id = bp.blog_id 
          ORDER BY bc.created_at DESC";
$result = $conn->query($query);
$comments = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}

// Handle comment approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    if (isset($_POST['is_approved'])) {
        $comment_id = $conn->real_escape_string($_POST['comment_id']);
        $is_approved = $_POST['is_approved'] === '1' ? 1 : 0;
        
        $update_query = "UPDATE blog_comments SET is_approved = $is_approved WHERE comment_id = $comment_id";
        $conn->query($update_query);
    } elseif (isset($_POST['delete'])) {
        $comment_id = $conn->real_escape_string($_POST['comment_id']);
        $delete_query = "DELETE FROM blog_comments WHERE comment_id = $comment_id";
        $conn->query($delete_query);
    }
    
    header('Location: blog_comments.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog Comments</title>
    <link rel="stylesheet" href="../css/common.css">
    <!-- <link rel="stylesheet" href="../css/charts-tables.css"> -->
     <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="../js/common.js" defer></script>
    <style>
        /* Define root variables locally for fallback or direct use */
        :root {
            --primary-green: #2a4858;
            --primary-brown: #8b7355;
            --accent-sage: #2a4858ac;
            --accent-terracotta: #967259;
            --neutral-cream: #f2e8dc;
            --neutral-beige: #d8c3a5;
            --neutral-light: #f6f4f0;
            --neutral-dark: #3a3026;
            --text-dark: #3a3026;
            --text-medium: #5d4e41;
            --text-light: #f6f4f0;
        }

        .container {
            padding: 20px;
        }

        .main-content h1 {
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        .table-container {
            background-color: var(--neutral-light);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow-x: auto; /* Ensure table is scrollable on small screens */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .data-table th, .data-table td {
            border: 1px solid var(--neutral-beige);
            padding: 12px 15px;
            text-align: left;
            color: var(--text-dark);
            vertical-align: middle;
        }

        .data-table th {
            background-color: var(--primary-green);
            color: var(--text-light);
            font-weight: bold;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: var(--neutral-cream);
        }

        .data-table tbody tr:hover {
            background-color: var(--neutral-beige);
        }

        .data-table td:nth-child(4) { /* Comment column */
            max-width: 300px; /* Limit width */
            white-space: normal; /* Allow wrapping */
            word-wrap: break-word;
        }

        .status-btn, .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
            color: var(--text-light);
            font-size: 0.9em;
        }

        .status-btn.approved {
            background-color: var(--primary-green); /* Green for approved */
        }
        .status-btn.approved:hover {
            background-color: #1e3540; /* Darker green */
        }

        .status-btn.pending {
            background-color: var(--accent-terracotta); /* Orange/Terracotta for pending */
        }
         .status-btn.pending:hover {
            background-color: #7a5c47; /* Darker terracotta */
        }

        .delete-btn {
            background-color: #c0392b; /* Red for delete */
        }
        .delete-btn:hover {
            background-color: #a93226; /* Darker red */
        }

        /* Ensure forms inside table cells don't add extra margin */
        .approval-form, .data-table td form {
            margin: 0;
            padding: 0;
            display: inline-block; /* Keep buttons side-by-side if needed */
        }

    </style>
</head>
<body>
  <?php include_once './includes/sidebar.php'; ?>

    <main class="main-content">
        <?php include_once './includes/header.php'; ?>

        <div class="container">
            <h1>Blog Comments Management</h1>
            
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Post Title</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Comment</th>
                            <th>Date</th>
                            <th>Approved</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comments as $comment): ?>
                        <tr>
                            <td><?= htmlspecialchars($comment['post_title'] ?? 'Unknown Post') ?></td>
                            <td><?= htmlspecialchars($comment['commenter_name']) ?></td>
                            <td><?= htmlspecialchars($comment['commenter_email']) ?></td>
                            <td><?= $comment['comment_text'] ?></td>
                            <td><?= date('M j, Y', strtotime($comment['created_at'])) ?></td>
                            <td>
                                <form method="POST" class="approval-form" onsubmit="return confirm('Are you sure you want to <?= $comment['is_approved'] ? 'disapprove' : 'approve' ?> this comment?')">
                                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                    <input type="hidden" name="is_approved" value="<?= $comment['is_approved'] ? '0' : '1' ?>">
                                    <button type="submit" class="status-btn <?= $comment['is_approved'] ? 'approved' : 'pending' ?>" title="<?= $comment['is_approved'] ? 'Click to disapprove' : 'Click to approve' ?>">
                                        <?= $comment['is_approved'] ? 'Approved' : 'Pending' ?>
                                    </button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;" action="">
                                    <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                                    <input type="hidden" name="delete" value="1">
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>