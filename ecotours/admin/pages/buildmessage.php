<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../config/db_connect.php');

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

$query = "SELECT * FROM build_submissions ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Submissions | Virunga Ecotours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/buildmessage.css">
    <script src="../js/common.js" defer></script>
     <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 5px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .view-details {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .view-details:hover {
            background-color: #45a049;
        }
    </style>
    <script>
    function deleteSubmission(id) {
        if (confirm('Are you sure you want to delete this submission?')) {
            fetch('delete_submission.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting submission');
                }
            });
        }
    }

    function viewDetails(id) {
        const modal = document.getElementById('details-modal-' + id);
        modal.style.display = 'block';
    }

    function closeModal(id) {
        const modal = document.getElementById('details-modal-' + id);
        modal.style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }
    </script>
</head>
<body>
    <div class="admin-container">
        <?php include_once './includes/sidebar.php'; ?>
        
        <main class="main-content">
            <?php include_once './includes/header.php'; ?>
            
            <div class="content-wrapper">
                <div class="page-header">
                    <h1>Custom Trip Requests</h1>
                    <p>View and manage trip requests from potential travelers</p>
                </div>
                <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Referral</th>
                <th>Trip Days</th>
                <th>Group Size</th>
                <th>Travel Date</th>
                <th>Newsletter</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
            <tr>
                <td><?= htmlspecialchars($submission['id']) ?></td>
                <td><?= htmlspecialchars($submission['names']) ?></td>
                <td><?= htmlspecialchars($submission['email']) ?></td>
                <td><?= htmlspecialchars($submission['phone']) ?></td>
                <td><?= htmlspecialchars($submission['referral_source']) ?></td>
                <td><?= htmlspecialchars($submission['trip_days']) ?></td>
                <td><?= htmlspecialchars($submission['group_size']) ?></td>
                <td><?= htmlspecialchars($submission['travel_date']) ?></td>
                <td><?= $submission['newsletter'] ? 'Yes' : 'No' ?></td>
                <td><?= htmlspecialchars($submission['created_at']) ?></td>
                <td>
                    <button onclick="viewDetails(<?= $submission['id'] ?>)" class="view-details">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button onclick="deleteSubmission(<?= $submission['id'] ?>)" class="delete-btn">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add modals for each submission -->
    <?php foreach ($submissions as $submission): ?>
    <div id="details-modal-<?= $submission['id'] ?>" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal(<?= $submission['id'] ?>)">&times;</span>
            <h2>Custom Trip Request Details</h2>
            <div class="details-content">
                <p><strong>Name:</strong> <?= htmlspecialchars($submission['names']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($submission['email']) ?></p>
                <p><strong>Phone:</strong> <?= htmlspecialchars($submission['phone']) ?></p>
                <p><strong>Referral Source:</strong> <?= htmlspecialchars($submission['referral_source']) ?></p>
                <p><strong>Trip Days:</strong> <?= htmlspecialchars($submission['trip_days']) ?></p>
                <p><strong>Group Size:</strong> <?= htmlspecialchars($submission['group_size']) ?></p>
                <p><strong>Travel Date:</strong> <?= htmlspecialchars($submission['travel_date']) ?></p>
                <p><strong>Newsletter:</strong> <?= $submission['newsletter'] ? 'Yes' : 'No' ?></p>
                <p><strong>Created At:</strong> <?= htmlspecialchars($submission['created_at']) ?></p>
                <?php if (!empty($submission['message'])): ?>
                <p><strong>Additional Message:</strong></p>
                <p><?= nl2br(htmlspecialchars($submission['message'])) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

                </div>
            </div>
        </main>
    </div>
</body>
</html>