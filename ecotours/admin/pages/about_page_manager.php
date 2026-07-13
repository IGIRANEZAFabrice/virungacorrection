<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit();
}

require_once('../config/connection.php');

// Fetch all about page data
$hero_sql = "SELECT * FROM about_hero WHERE is_active = 1 LIMIT 1";
$hero_result = $conn->query($hero_sql);
$hero_data = $hero_result->fetch_assoc();

$story_sql = "SELECT * FROM about_story WHERE is_active = 1 LIMIT 1";
$story_result = $conn->query($story_sql);
$story_data = $story_result->fetch_assoc();

$impact_sql = "SELECT * FROM about_impact WHERE is_active = 1 LIMIT 1";
$impact_result = $conn->query($impact_sql);
$impact_data = $impact_result->fetch_assoc();

$impact_stats_sql = "SELECT * FROM about_impact_stats WHERE impact_id = ? AND is_active = 1 ORDER BY display_order";
$impact_stats_stmt = $conn->prepare($impact_stats_sql);
$impact_stats_stmt->bind_param("i", $impact_data['impact_id']);
$impact_stats_stmt->execute();
$impact_stats = [];
$impact_stats_stmt->store_result();
$meta = $impact_stats_stmt->result_metadata();
$fields = [];
while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}
$bindResult = call_user_func_array([$impact_stats_stmt, 'bind_result'], $fields);
while ($impact_stats_stmt->fetch()) {
    $item = [];
    foreach ($row as $key => $val) {
        $item[$key] = $val;
    }
    $impact_stats[] = $item;
}

$team_section_sql = "SELECT * FROM about_team_section WHERE is_active = 1 LIMIT 1";
$team_section_result = $conn->query($team_section_sql);
$team_section_data = $team_section_result->fetch_assoc();

$team_members_sql = "SELECT * FROM about_team_members WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$team_members_stmt = $conn->prepare($team_members_sql);
$team_members_stmt->bind_param("i", $team_section_data['section_id']);
$team_members_stmt->execute();
$team_members = [];
$team_members_stmt->store_result();
$meta = $team_members_stmt->result_metadata();
$fields = [];
while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}
$bindResult = call_user_func_array([$team_members_stmt, 'bind_result'], $fields);
while ($team_members_stmt->fetch()) {
    $item = [];
    foreach ($row as $key => $val) {
        $item[$key] = $val;
    }
    $team_members[] = $item;
}

$values_section_sql = "SELECT * FROM about_values_section WHERE is_active = 1 LIMIT 1";
$values_section_result = $conn->query($values_section_sql);
$values_section_data = $values_section_result->fetch_assoc();

$values_sql = "SELECT * FROM about_values WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$values_stmt = $conn->prepare($values_sql);
$values_stmt->bind_param("i", $values_section_data['section_id']);
$values_stmt->execute();
$values = [];
$values_stmt->store_result();
$meta = $values_stmt->result_metadata();
$fields = [];
while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}
$bindResult = call_user_func_array([$values_stmt, 'bind_result'], $fields);
while ($values_stmt->fetch()) {
    $item = [];
    foreach ($row as $key => $val) {
        $item[$key] = $val;
    }
    $values[] = $item;
}

$gallery_section_sql = "SELECT * FROM about_gallery_section WHERE is_active = 1 LIMIT 1";
$gallery_section_result = $conn->query($gallery_section_sql);
$gallery_section_data = $gallery_section_result->fetch_assoc();

$gallery_sql = "SELECT * FROM about_gallery WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
$gallery_stmt = $conn->prepare($gallery_sql);
$gallery_stmt->bind_param("i", $gallery_section_data['section_id']);
$gallery_stmt->execute();
$gallery_items = [];
$gallery_stmt->store_result();
$meta = $gallery_stmt->result_metadata();
$fields = [];
while ($field = $meta->fetch_field()) {
    $fields[] = &$row[$field->name];
}
$bindResult = call_user_func_array([$gallery_stmt, 'bind_result'], $fields);
while ($gallery_stmt->fetch()) {
    $item = [];
    foreach ($row as $key => $val) {
        $item[$key] = $val;
    }
    $gallery_items[] = $item;
}

$cta_sql = "SELECT * FROM about_cta WHERE is_active = 1 LIMIT 1";
$cta_result = $conn->query($cta_sql);
$cta_data = $cta_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page Manager - Virunga Ecotours</title>
    <link rel="shortcut icon" href="../../images/logos/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../css/common.css">
    <link rel="stylesheet" href="../css/about_manager.css">
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
                <div class="page-header">
                    <h1><i class="fas fa-info-circle"></i> About Page Manager</h1>
                    <p>Manage all content sections of the About page</p>
                </div>

                <?php if (isset($_GET['status'])): ?>
                    <div class="message-receiver <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
                        <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                        <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] === 'success' ? 'Changes saved successfully!' : 'An error occurred')); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Navigation Tabs -->
                <div class="tabs-container">
                    <div class="tabs">
                        <button class="tab-btn active" data-tab="hero">Hero Section</button>
                        <button class="tab-btn" data-tab="story">Our Story</button>
                        <button class="tab-btn" data-tab="impact">Impact Stats</button>
                        <button class="tab-btn" data-tab="team">Team</button>
                        <button class="tab-btn" data-tab="values">Values</button>
                        <button class="tab-btn" data-tab="gallery">Gallery</button>
                        <button class="tab-btn" data-tab="cta">Call to Action</button>
                    </div>
                </div>

                <!-- Hero Section Tab -->
                <div class="tab-content active" id="hero-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-star"></i> Hero Section</h2>
                            <p>Manage the main hero banner content</p>
                        </div>

                        <form action="../handlers/about/updateHeroHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="hero_id" value="<?php echo $hero_data['hero_id']; ?>">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_title">Title</label>
                                    <input type="text" id="hero_title" name="title" value="<?php echo htmlspecialchars($hero_data['title']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hero_subtitle">Subtitle</label>
                                    <input type="text" id="hero_subtitle" name="subtitle" value="<?php echo htmlspecialchars($hero_data['subtitle']); ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_button_text">Button Text</label>
                                    <input type="text" id="hero_button_text" name="button_text" value="<?php echo htmlspecialchars($hero_data['button_text']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hero_button_link">Button Link</label>
                                    <input type="text" id="hero_button_link" name="button_link" value="<?php echo htmlspecialchars($hero_data['button_link']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hero_background_image">Background Image</label>
                                <input type="file" id="hero_background_image" name="background_image" accept="image/*">
                                <input type="hidden" name="existing_background_image" value="<?php echo htmlspecialchars($hero_data['background_image']); ?>">
                                <?php if (!empty($hero_data['background_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($hero_data['background_image']); ?>" alt="Current background" style="max-width: 200px; margin-top: 10px;">
                                        <p>Current image: <?php echo htmlspecialchars($hero_data['background_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Hero Section
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Our Story Section Tab -->
                <div class="tab-content" id="story-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-book"></i> Our Story Section</h2>
                            <p>Manage the story content and image</p>
                        </div>

                        <form action="../handlers/about/updateStoryHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="story_id" value="<?php echo $story_data['story_id']; ?>">

                            <div class="form-group">
                                <label for="story_title">Section Title</label>
                                <input type="text" id="story_title" name="section_title" value="<?php echo htmlspecialchars($story_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_1">First Paragraph</label>
                                <textarea id="story_paragraph_1" name="paragraph_1" rows="4" required><?php echo htmlspecialchars($story_data['paragraph_1']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_2">Second Paragraph</label>
                                <textarea id="story_paragraph_2" name="paragraph_2" rows="4" required><?php echo htmlspecialchars($story_data['paragraph_2']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_3">Third Paragraph</label>
                                <textarea id="story_paragraph_3" name="paragraph_3" rows="4" required><?php echo htmlspecialchars($story_data['paragraph_3']); ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="story_button_text">Button Text</label>
                                    <input type="text" id="story_button_text" name="button_text" value="<?php echo htmlspecialchars($story_data['button_text']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="story_button_link">Button Link</label>
                                    <input type="text" id="story_button_link" name="button_link" value="<?php echo htmlspecialchars($story_data['button_link']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="story_image">Story Image</label>
                                <input type="file" id="story_image" name="story_image" accept="image/*">
                                <input type="hidden" name="existing_story_image" value="<?php echo htmlspecialchars($story_data['story_image']); ?>">
                                <?php if (!empty($story_data['story_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($story_data['story_image']); ?>" alt="Current story image" style="max-width: 200px; margin-top: 10px;">
                                        <p>Current image: <?php echo htmlspecialchars($story_data['story_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Story Section
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Impact Stats Section Tab -->
                <div class="tab-content" id="impact-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-chart-bar"></i> Impact Stats Section</h2>
                            <p>Manage impact statistics and section content</p>
                        </div>

                        <!-- Impact Section Info -->
                        <form action="../handlers/about/updateImpactHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="impact_id" value="<?php echo $impact_data['impact_id']; ?>">

                            <div class="form-group">
                                <label for="impact_title">Section Title</label>
                                <input type="text" id="impact_title" name="section_title" value="<?php echo htmlspecialchars($impact_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="impact_intro">Section Introduction</label>
                                <textarea id="impact_intro" name="section_intro" rows="3" required><?php echo htmlspecialchars($impact_data['section_intro']); ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Impact Section
                                </button>
                            </div>
                        </form>

                        <!-- Impact Stats -->
                        <div class="subsection">
                            <h3><i class="fas fa-list"></i> Impact Statistics</h3>
                            <div class="stats-grid">
                                <?php foreach ($impact_stats as $stat): ?>
                                    <div class="stat-card">
                                        <form action="../handlers/about/updateImpactStatHandler.php" method="POST" class="stat-form">
                                            <input type="hidden" name="stat_id" value="<?php echo $stat['stat_id']; ?>">

                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="icon_class" value="<?php echo htmlspecialchars($stat['icon_class']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Count</label>
                                                <input type="number" name="stat_count" value="<?php echo $stat['stat_count']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="stat_title" value="<?php echo htmlspecialchars($stat['stat_title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Order</label>
                                                <input type="number" name="display_order" value="<?php echo $stat['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Update
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteStat(<?php echo $stat['stat_id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="add-new-section">
                                <button type="button" class="btn btn-success" onclick="showAddStatForm()">
                                    <i class="fas fa-plus"></i> Add New Statistic
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Section Tab -->
                <div class="tab-content" id="team-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-users"></i> Team Section</h2>
                            <p>Manage team section and team members</p>
                        </div>

                        <!-- Team Section Info -->
                        <form action="../handlers/about/updateTeamSectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $team_section_data['section_id']; ?>">

                            <div class="form-group">
                                <label for="team_title">Section Title</label>
                                <input type="text" id="team_title" name="section_title" value="<?php echo htmlspecialchars($team_section_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="team_intro">Section Introduction</label>
                                <textarea id="team_intro" name="section_intro" rows="3" required><?php echo htmlspecialchars($team_section_data['section_intro']); ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Team Section
                                </button>
                            </div>
                        </form>

                        <!-- Team Members -->
                        <div class="subsection">
                            <h3><i class="fas fa-user-friends"></i> Team Members</h3>
                            <div class="team-grid">
                                <?php foreach ($team_members as $member): ?>
                                    <div class="team-card">
                                        <form action="../handlers/about/updateTeamMemberHandler.php" method="POST" enctype="multipart/form-data" class="member-form">
                                            <input type="hidden" name="member_id" value="<?php echo $member['member_id']; ?>">

                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Role</label>
                                                <input type="text" name="role" value="<?php echo htmlspecialchars($member['role']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Bio</label>
                                                <textarea name="bio" rows="3" required><?php echo htmlspecialchars($member['bio']); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" accept="image/*">
                                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($member['image']); ?>">
                                                <?php if (!empty($member['image'])): ?>
                                                    <div class="current-image">
                                                        <img src="../images/about/team/<?php echo htmlspecialchars($member['image']); ?>" alt="Current image" style="max-width: 100px; margin-top: 5px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label>LinkedIn URL</label>
                                                    <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($member['linkedin_url']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Twitter URL</label>
                                                    <input type="url" name="twitter_url" value="<?php echo htmlspecialchars($member['twitter_url']); ?>">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label>Instagram URL</label>
                                                    <input type="url" name="instagram_url" value="<?php echo htmlspecialchars($member['instagram_url']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Display Order</label>
                                                    <input type="number" name="display_order" value="<?php echo $member['display_order']; ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Update
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteTeamMember(<?php echo $member['member_id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="add-new-section">
                                <button type="button" class="btn btn-success" onclick="showAddTeamMemberForm()">
                                    <i class="fas fa-plus"></i> Add New Team Member
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Values Section Tab -->
                <div class="tab-content" id="values-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-heart"></i> Values Section</h2>
                            <p>Manage values section and individual values</p>
                        </div>

                        <!-- Values Section Info -->
                        <form action="../handlers/about/updateValuesSectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $values_section_data['section_id']; ?>">

                            <div class="form-group">
                                <label for="values_title">Section Title</label>
                                <input type="text" id="values_title" name="section_title" value="<?php echo htmlspecialchars($values_section_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="values_intro">Section Introduction</label>
                                <textarea id="values_intro" name="section_intro" rows="3" required><?php echo htmlspecialchars($values_section_data['section_intro']); ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Values Section
                                </button>
                            </div>
                        </form>

                        <!-- Values -->
                        <div class="subsection">
                            <h3><i class="fas fa-list"></i> Values</h3>
                            <div class="values-grid">
                                <?php foreach ($values as $value): ?>
                                    <div class="value-card">
                                        <form action="../handlers/about/updateValueHandler.php" method="POST" class="value-form">
                                            <input type="hidden" name="value_id" value="<?php echo $value['value_id']; ?>">

                                            <div class="form-group">
                                                <label>Icon Class</label>
                                                <input type="text" name="icon_class" value="<?php echo htmlspecialchars($value['icon_class']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($value['title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" rows="3" required><?php echo htmlspecialchars($value['description']); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Order</label>
                                                <input type="number" name="display_order" value="<?php echo $value['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Update
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteValue(<?php echo $value['value_id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="add-new-section">
                                <button type="button" class="btn btn-success" onclick="showAddValueForm()">
                                    <i class="fas fa-plus"></i> Add New Value
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section Tab -->
                <div class="tab-content" id="gallery-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-images"></i> Gallery Section</h2>
                            <p>Manage gallery section and gallery items</p>
                        </div>

                        <!-- Gallery Section Info -->
                        <form action="../handlers/about/updateGallerySectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $gallery_section_data['section_id']; ?>">

                            <div class="form-group">
                                <label for="gallery_title">Section Title</label>
                                <input type="text" id="gallery_title" name="section_title" value="<?php echo htmlspecialchars($gallery_section_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="gallery_intro">Section Introduction</label>
                                <textarea id="gallery_intro" name="section_intro" rows="3" required><?php echo htmlspecialchars($gallery_section_data['section_intro']); ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Gallery Section
                                </button>
                            </div>
                        </form>

                        <!-- Gallery Items -->
                        <div class="subsection">
                            <h3><i class="fas fa-image"></i> Gallery Items</h3>
                            <div class="gallery-grid">
                                <?php foreach ($gallery_items as $item): ?>
                                    <div class="gallery-card">
                                        <form action="../handlers/about/updateGalleryItemHandler.php" method="POST" enctype="multipart/form-data" class="gallery-form">
                                            <input type="hidden" name="gallery_id" value="<?php echo $item['gallery_id']; ?>">

                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Alt Text</label>
                                                <input type="text" name="alt_text" value="<?php echo htmlspecialchars($item['alt_text']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Image</label>
                                                <input type="file" name="image" accept="image/*">
                                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="current-image">
                                                        <img src="../images/about/gallery/<?php echo htmlspecialchars($item['image']); ?>" alt="Current image" style="max-width: 150px; margin-top: 5px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Order</label>
                                                <input type="number" name="display_order" value="<?php echo $item['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Update
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteGalleryItem(<?php echo $item['gallery_id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="add-new-section">
                                <button type="button" class="btn btn-success" onclick="showAddGalleryItemForm()">
                                    <i class="fas fa-plus"></i> Add New Gallery Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Section Tab -->
                <div class="tab-content" id="cta-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <h2><i class="fas fa-bullhorn"></i> Call to Action Section</h2>
                            <p>Manage the call to action section and social links</p>
                        </div>

                        <form action="../handlers/about/updateCtaHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="cta_id" value="<?php echo $cta_data['cta_id']; ?>">

                            <div class="form-group">
                                <label for="cta_title">Section Title</label>
                                <input type="text" id="cta_title" name="section_title" value="<?php echo htmlspecialchars($cta_data['section_title']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="cta_description">Section Description</label>
                                <textarea id="cta_description" name="section_description" rows="3" required><?php echo htmlspecialchars($cta_data['section_description']); ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cta_button_text">Button Text</label>
                                    <input type="text" id="cta_button_text" name="button_text" value="<?php echo htmlspecialchars($cta_data['button_text']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="cta_button_link">Button Link</label>
                                    <input type="text" id="cta_button_link" name="button_link" value="<?php echo htmlspecialchars($cta_data['button_link']); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cta_background_image">Background Image</label>
                                <input type="file" id="cta_background_image" name="background_image" accept="image/*">
                                <input type="hidden" name="existing_background_image" value="<?php echo htmlspecialchars($cta_data['background_image']); ?>">
                                <?php if (!empty($cta_data['background_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($cta_data['background_image']); ?>" alt="Current background" style="max-width: 200px; margin-top: 10px;">
                                        <p>Current image: <?php echo htmlspecialchars($cta_data['background_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="subsection">
                                <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="facebook_url">Facebook URL</label>
                                        <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($cta_data['facebook_url']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram_url">Instagram URL</label>
                                        <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($cta_data['instagram_url']); ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="twitter_url">Twitter URL</label>
                                        <input type="url" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($cta_data['twitter_url']); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="youtube_url">YouTube URL</label>
                                        <input type="url" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($cta_data['youtube_url']); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="pinterest_url">Pinterest URL</label>
                                    <input type="url" id="pinterest_url" name="pinterest_url" value="<?php echo htmlspecialchars($cta_data['pinterest_url']); ?>">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Call to Action Section
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../js/about_manager.js"></script>
</body>
</html>
