<?php
require_once '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $conn->begin_transaction();
        $isUpdate = isset($_POST['update_tour']);
        $tourId = $isUpdate ? $_POST['tour_id'] : null;

        // Handle cover image upload
        $coverImagePath = '';
        if (isset($_FILES['coverImage']) && $_FILES['coverImage']['error'] == 0) {
            $targetDir = "../../images/tours/";
            if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = uniqid() . '_' . basename($_FILES['coverImage']['name']);
            if (move_uploaded_file($_FILES['coverImage']['tmp_name'], $targetDir . $fileName)) {
                $coverImagePath = "images/tours/" . $fileName;
            }
        }

        if ($isUpdate) {
            $sql = "UPDATE tours SET title = ?, category = ?, country = ?, days_count = ?, short_description = ?, why_attend = ?";
            $params = [$_POST['tourTitle'], $_POST['tourCategory'], $_POST['tourCountry'], $_POST['tourDays'], $_POST['tourDesc'], $_POST['whyAttend']];
            $types = "sssisss";
            if ($coverImagePath) {
                $sql .= ", cover_image_path = ?";
                $params[] = $coverImagePath;
                $types .= "s";
            }
            $sql .= " WHERE tour_id = ?";
            $params[] = $tourId;
            $types .= "i";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
        } else {
            // Insert main tour details
            $stmt = $conn->prepare("INSERT INTO tours (title, category, country, days_count, cover_image_path, short_description, why_attend) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisss", $_POST['tourTitle'], $_POST['tourCategory'], $_POST['tourCountry'], $_POST['tourDays'], $coverImagePath, $_POST['tourDesc'], $_POST['whyAttend']);
            $stmt->execute();
            $tourId = $conn->insert_id;
        }

        // Process highlight images
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_FILES["highlight{$i}"]) && $_FILES["highlight{$i}"]['error'] == 0) {
                $targetDir = "../../images/tours/highlights/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = uniqid() . '_' . basename($_FILES["highlight{$i}"]['name']);
                if (move_uploaded_file($_FILES["highlight{$i}"]['tmp_name'], $targetDir . $fileName)) {
                    $dbPath = "images/tours/highlights/" . $fileName;
                    
                    if ($isUpdate) {
                        // Check if highlight exists for this order
                        $checkStmt = $conn->prepare("SELECT highlight_id FROM tour_highlights WHERE tour_id = ? AND display_order = ?");
                        $checkStmt->bind_param("ii", $tourId, $i);
                        $checkStmt->execute();
                        $result = $checkStmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            $hStmt = $conn->prepare("UPDATE tour_highlights SET image_path = ? WHERE highlight_id = ?");
                            $hStmt->bind_param("si", $dbPath, $row['highlight_id']);
                        } else {
                            $hStmt = $conn->prepare("INSERT INTO tour_highlights (tour_id, image_path, display_order) VALUES (?, ?, ?)");
                            $hStmt->bind_param("isi", $tourId, $dbPath, $i);
                        }
                    } else {
                        $hStmt = $conn->prepare("INSERT INTO tour_highlights (tour_id, image_path, display_order) VALUES (?, ?, ?)");
                        $hStmt->bind_param("isi", $tourId, $dbPath, $i);
                    }
                    $hStmt->execute();
                }
            }
        }

        // Helper for JSON child tables
        $processJsonTable = function($conn, $tourId, $postKey, $insertSql, $types, $mapFunc, $tableName) use ($isUpdate) {
            if ($isUpdate) {
                $delStmt = $conn->prepare("DELETE FROM $tableName WHERE tour_id = ?");
                $delStmt->bind_param("i", $tourId);
                $delStmt->execute();
            }
            if (isset($_POST[$postKey])) {
                $items = json_decode($_POST[$postKey], true);
                if (is_array($items)) {
                    $stmt = $conn->prepare($insertSql);
                    foreach ($items as $item) {
                        $params = $mapFunc($tourId, $item);
                        if ($params) {
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                        }
                    }
                }
            }
        };

        // Process activities
        $processJsonTable($conn, $tourId, 'activities', 
            "INSERT INTO tour_days (tour_id, day_number, day_title, day_description) VALUES (?, ?, ?, ?)", "iiss",
            fn($tid, $i) => !empty($i['title']) ? [$tid, $i['day_number'], $i['title'], $i['description']] : null, 'tour_days');

        // Process included items
        $processJsonTable($conn, $tourId, 'includedItems', 
            "INSERT INTO tour_included (tour_id, item_description) VALUES (?, ?)", "is",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null, 'tour_included');

        // Process excluded items
        $processJsonTable($conn, $tourId, 'excludedItems', 
            "INSERT INTO tour_excluded (tour_id, item_description) VALUES (?, ?)", "is",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null, 'tour_excluded');

        // Process what to bring items
        $processJsonTable($conn, $tourId, 'toBringItems', 
            "INSERT INTO tour_to_bring (tour_id, item_description) VALUES (?, ?)", "is",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null, 'tour_to_bring');

        // Process pricing tiers
        $processJsonTable($conn, $tourId, 'pricingTiers', 
            "INSERT INTO pricing_tiers (tour_id, group_size, price_per_person) VALUES (?, ?, ?)", "isd",
            fn($tid, $i) => !empty($i['group_size']) ? [$tid, $i['group_size'], $i['price_per_person']] : null, 'pricing_tiers');

        // Process pricing notes
        $processJsonTable($conn, $tourId, 'pricingNotes', 
            "INSERT INTO pricing_notes (tour_id, note) VALUES (?, ?)", "is",
            fn($tid, $i) => !empty($i) ? [$tid, $i] : null, 'pricing_notes');

        $conn->commit();
        echo json_encode(['success' => true, 'message' => 'Tour ' . ($isUpdate ? 'updated' : 'created') . ' successfully']);

    } catch (Exception $e) {
        if ($conn->connect_errno === 0) $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
