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
$hero_data = $hero_result ? $hero_result->fetch_assoc() : null;

$story_sql = "SELECT * FROM about_story WHERE is_active = 1 LIMIT 1";
$story_result = $conn->query($story_sql);
$story_data = $story_result ? $story_result->fetch_assoc() : null;

$impact_sql = "SELECT * FROM about_impact WHERE is_active = 1 LIMIT 1";
$impact_result = $conn->query($impact_sql);
$impact_data = $impact_result ? $impact_result->fetch_assoc() : null;

$impact_stats = [];
if ($impact_data) {
    $impact_stats_sql = "SELECT * FROM about_impact_stats WHERE impact_id = ? AND is_active = 1 ORDER BY display_order";
    $impact_stats_stmt = $conn->prepare($impact_stats_sql);
    if ($impact_stats_stmt) {
        $impact_stats_stmt->bind_param("i", $impact_data['impact_id']);
        $impact_stats_stmt->execute();
        $res = $impact_stats_stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $impact_stats[] = $row;
            }
        }
        $impact_stats_stmt->close();
    }
}

$team_section_sql = "SELECT * FROM about_team_section WHERE is_active = 1 LIMIT 1";
$team_section_result = $conn->query($team_section_sql);
$team_section_data = $team_section_result ? $team_section_result->fetch_assoc() : null;

$team_members = [];
if ($team_section_data) {
    $team_members_sql = "SELECT * FROM about_team_members WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
    $team_members_stmt = $conn->prepare($team_members_sql);
    if ($team_members_stmt) {
        $team_members_stmt->bind_param("i", $team_section_data['section_id']);
        $team_members_stmt->execute();
        $res = $team_members_stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $team_members[] = $row;
            }
        }
        $team_members_stmt->close();
    }
}

$values_section_sql = "SELECT * FROM about_values_section WHERE is_active = 1 LIMIT 1";
$values_section_result = $conn->query($values_section_sql);
$values_section_data = $values_section_result ? $values_section_result->fetch_assoc() : null;

$values = [];
if ($values_section_data) {
    $values_sql = "SELECT * FROM about_values WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
    $values_stmt = $conn->prepare($values_sql);
    if ($values_stmt) {
        $values_stmt->bind_param("i", $values_section_data['section_id']);
        $values_stmt->execute();
        $res = $values_stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $values[] = $row;
            }
        }
        $values_stmt->close();
    }
}

$gallery_section_sql = "SELECT * FROM about_gallery_section WHERE is_active = 1 LIMIT 1";
$gallery_section_result = $conn->query($gallery_section_sql);
$gallery_section_data = $gallery_section_result ? $gallery_section_result->fetch_assoc() : null;

$gallery_items = [];
if ($gallery_section_data) {
    $gallery_sql = "SELECT * FROM about_gallery WHERE section_id = ? AND is_active = 1 ORDER BY display_order";
    $gallery_stmt = $conn->prepare($gallery_sql);
    if ($gallery_stmt) {
        $gallery_stmt->bind_param("i", $gallery_section_data['section_id']);
        $gallery_stmt->execute();
        $res = $gallery_stmt->get_result();
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $gallery_items[] = $row;
            }
        }
        $gallery_stmt->close();
    }
}

$cta_sql = "SELECT * FROM about_cta WHERE is_active = 1 LIMIT 1";
$cta_result = $conn->query($cta_sql);
$cta_data = $cta_result ? $cta_result->fetch_assoc() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Page Manager - Virunga Admin</title>
    <link rel="shortcut icon" href="../images/icon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

            <div class="about-management-container">
                
                <!-- Page Header Row -->
                <div class="page-header-row">
                    <div class="page-title-wrap">
                        <div class="breadcrumb-trail">
                            <a href="../index.php">Dashboard</a>
                            <span>/</span>
                            <span>About Page Manager</span>
                        </div>
                        <h1 class="page-title">
                            <i class="fas fa-users" style="color: #206bc4;"></i>
                            About Page Content Manager
                        </h1>
                        <p class="page-subtitle">
                            Manage all 7 sections of the About Us page including narrative story, impact statistics, leadership team, values, and galleries.
                        </p>
                    </div>

                    <div class="page-actions-wrap">
                        <a href="../../about.php" target="_blank" class="btn btn-outline btn-sm">
                            <i class="fas fa-eye"></i> View Live About Page
                        </a>
                    </div>
                </div>

                <!-- Status Alert -->
                <?php if (isset($_GET['status'])): ?>
                    <div class="alert <?php echo $_GET['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>">
                        <i class="fas <?php echo $_GET['status'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                        <span><?php echo htmlspecialchars($_GET['message'] ?? ($_GET['status'] === 'success' ? 'Changes saved successfully!' : 'An error occurred while saving.')); ?></span>
                        <button type="button" class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <!-- KPI Metric Row -->
                <div class="kpi-row">
                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num">7</span>
                            <span class="kpi-badge badge-blue"><i class="fas fa-layer-group"></i> Sections</span>
                        </div>
                        <span class="kpi-title">Configured Content Blocks</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo count($team_members); ?></span>
                            <span class="kpi-badge badge-green"><i class="fas fa-user-group"></i> Active</span>
                        </div>
                        <span class="kpi-title">Team Members Listed</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo count($impact_stats); ?></span>
                            <span class="kpi-badge badge-amber"><i class="fas fa-chart-line"></i> Metrics</span>
                        </div>
                        <span class="kpi-title">Impact Statistics</span>
                    </div>

                    <div class="kpi-card">
                        <div class="kpi-top">
                            <span class="kpi-num"><?php echo count($gallery_items); ?></span>
                            <span class="kpi-badge badge-purple"><i class="fas fa-images"></i> Photos</span>
                        </div>
                        <span class="kpi-title">About Gallery Images</span>
                    </div>
                </div>

                <!-- Navigation Tabs -->
                <div class="tabs-container">
                    <div class="tabs">
                        <button class="tab-btn active" data-tab="hero"><i class="fas fa-star"></i> Hero Section</button>
                        <button class="tab-btn" data-tab="story"><i class="fas fa-book-open"></i> Our Story</button>
                        <button class="tab-btn" data-tab="impact"><i class="fas fa-chart-pie"></i> Impact Stats</button>
                        <button class="tab-btn" data-tab="team"><i class="fas fa-users"></i> Team</button>
                        <button class="tab-btn" data-tab="values"><i class="fas fa-heart"></i> Core Values</button>
                        <button class="tab-btn" data-tab="gallery"><i class="fas fa-images"></i> Photo Gallery</button>
                        <button class="tab-btn" data-tab="cta"><i class="fas fa-bullhorn"></i> Call to Action</button>
                    </div>
                </div>

                <!-- Hero Section Tab -->
                <div class="tab-content active" id="hero-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-star"></i> Hero Banner Section</h2>
                                <p>Manage the main headline, subtitle, background imagery, and CTA button</p>
                            </div>
                        </div>

                        <form action="../handlers/about/updateHeroHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="hero_id" value="<?php echo $hero_data['hero_id'] ?? 1; ?>">

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_title">Hero Title</label>
                                    <input type="text" id="hero_title" name="title" value="<?php echo htmlspecialchars($hero_data['title'] ?? ''); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hero_subtitle">Hero Subtitle</label>
                                    <input type="text" id="hero_subtitle" name="subtitle" value="<?php echo htmlspecialchars($hero_data['subtitle'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="hero_button_text">Button Label</label>
                                    <input type="text" id="hero_button_text" name="button_text" value="<?php echo htmlspecialchars($hero_data['button_text'] ?? 'Explore Journeys'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="hero_button_link">Button Target Link</label>
                                    <input type="text" id="hero_button_link" name="button_link" value="<?php echo htmlspecialchars($hero_data['button_link'] ?? '#planner'); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="hero_background_image">Hero Background Image</label>
                                <input type="file" id="hero_background_image" name="background_image" accept="image/*">
                                <input type="hidden" name="existing_background_image" value="<?php echo htmlspecialchars($hero_data['background_image'] ?? ''); ?>">
                                <?php if (!empty($hero_data['background_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($hero_data['background_image']); ?>" alt="Current background" style="max-height: 80px;">
                                        <p>Current: <?php echo htmlspecialchars($hero_data['background_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Hero Section
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Our Story Section Tab -->
                <div class="tab-content" id="story-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-book-open"></i> Our Story & Heritage</h2>
                                <p>Manage the brand origin, 3 narrative paragraphs, and showcase photo</p>
                            </div>
                        </div>

                        <form action="../handlers/about/updateStoryHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="story_id" value="<?php echo $story_data['story_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="story_title">Section Title</label>
                                <input type="text" id="story_title" name="section_title" value="<?php echo htmlspecialchars($story_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_1">Paragraph 1 (The Beginning)</label>
                                <textarea id="story_paragraph_1" name="paragraph_1" rows="3" required><?php echo htmlspecialchars($story_data['paragraph_1'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_2">Paragraph 2 (Our Evolution)</label>
                                <textarea id="story_paragraph_2" name="paragraph_2" rows="3" required><?php echo htmlspecialchars($story_data['paragraph_2'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="story_paragraph_3">Paragraph 3 (Our Vision)</label>
                                <textarea id="story_paragraph_3" name="paragraph_3" rows="3" required><?php echo htmlspecialchars($story_data['paragraph_3'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="story_button_text">Button Text</label>
                                    <input type="text" id="story_button_text" name="button_text" value="<?php echo htmlspecialchars($story_data['button_text'] ?? 'Discover Our Collection'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="story_button_link">Button Link</label>
                                    <input type="text" id="story_button_link" name="button_link" value="<?php echo htmlspecialchars($story_data['button_link'] ?? 'index.php#collection'); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="story_image">Story Feature Image</label>
                                <input type="file" id="story_image" name="story_image" accept="image/*">
                                <input type="hidden" name="existing_story_image" value="<?php echo htmlspecialchars($story_data['story_image'] ?? ''); ?>">
                                <?php if (!empty($story_data['story_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($story_data['story_image']); ?>" alt="Current story image" style="max-height: 80px;">
                                        <p>Current: <?php echo htmlspecialchars($story_data['story_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Story Section
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Impact Stats Section Tab -->
                <div class="tab-content" id="impact-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-chart-pie"></i> Impact Statistics Section</h2>
                                <p>Manage conservation impact metrics, community support numbers, and section header</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="showAddStatForm()">
                                <i class="fas fa-plus"></i> Add New Metric
                            </button>
                        </div>

                        <!-- Impact Section Info -->
                        <form action="../handlers/about/updateImpactHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="impact_id" value="<?php echo $impact_data['impact_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="impact_title">Section Title</label>
                                <input type="text" id="impact_title" name="section_title" value="<?php echo htmlspecialchars($impact_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="impact_intro">Section Introduction</label>
                                <textarea id="impact_intro" name="section_intro" rows="2" required><?php echo htmlspecialchars($impact_data['section_intro'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-actions" style="margin-bottom: 1.5rem;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Section Details
                                </button>
                            </div>
                        </form>

                        <!-- Impact Stats List -->
                        <div class="subsection">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3><i class="fas fa-list-check"></i> Impact Metrics (<?php echo count($impact_stats); ?>)</h3>
                                <button type="button" class="btn btn-success btn-sm" onclick="showAddStatForm()">
                                    <i class="fas fa-plus"></i> Add Statistic
                                </button>
                            </div>

                            <div class="stats-grid">
                                <?php foreach ($impact_stats as $stat): ?>
                                    <div class="stat-card">
                                        <form action="../handlers/about/updateImpactStatHandler.php" method="POST" class="stat-form">
                                            <input type="hidden" name="stat_id" value="<?php echo $stat['stat_id']; ?>">

                                            <div class="form-group">
                                                <label>FontAwesome Icon</label>
                                                <input type="text" name="icon_class" value="<?php echo htmlspecialchars($stat['icon_class']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Metric Count / Value</label>
                                                <input type="number" name="stat_count" value="<?php echo $stat['stat_count']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Metric Title</label>
                                                <input type="text" name="stat_title" value="<?php echo htmlspecialchars($stat['stat_title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Order</label>
                                                <input type="number" name="display_order" value="<?php echo $stat['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger-outline" onclick="deleteStat(<?php echo $stat['stat_id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Team Section Tab -->
                <div class="tab-content" id="team-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-users"></i> Leadership & Guides Team</h2>
                                <p>Manage team section introduction and individual member profiles</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="showAddTeamMemberForm()">
                                <i class="fas fa-plus"></i> Add Team Member
                            </button>
                        </div>

                        <!-- Team Section Info -->
                        <form action="../handlers/about/updateTeamSectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $team_section_data['section_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="team_title">Section Title</label>
                                <input type="text" id="team_title" name="section_title" value="<?php echo htmlspecialchars($team_section_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="team_intro">Section Introduction</label>
                                <textarea id="team_intro" name="section_intro" rows="2" required><?php echo htmlspecialchars($team_section_data['section_intro'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-actions" style="margin-bottom: 1.5rem;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Team Heading
                                </button>
                            </div>
                        </form>

                        <!-- Team Members Grid -->
                        <div class="subsection">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3><i class="fas fa-user-group"></i> Active Team Members (<?php echo count($team_members); ?>)</h3>
                                <button type="button" class="btn btn-success btn-sm" onclick="showAddTeamMemberForm()">
                                    <i class="fas fa-plus"></i> Add New Member
                                </button>
                            </div>

                            <div class="team-grid">
                                <?php foreach ($team_members as $member): ?>
                                    <div class="team-card">
                                        <form action="../handlers/about/updateTeamMemberHandler.php" method="POST" enctype="multipart/form-data" class="member-form">
                                            <input type="hidden" name="member_id" value="<?php echo $member['member_id']; ?>">

                                            <div class="form-group">
                                                <label>Full Name</label>
                                                <input type="text" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Role / Job Title</label>
                                                <input type="text" name="role" value="<?php echo htmlspecialchars($member['role']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Biography</label>
                                                <textarea name="bio" rows="2" required><?php echo htmlspecialchars($member['bio']); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Profile Picture</label>
                                                <input type="file" name="image" accept="image/*">
                                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($member['image']); ?>">
                                                <?php if (!empty($member['image'])): ?>
                                                    <div class="current-image">
                                                        <img src="../images/about/team/<?php echo htmlspecialchars($member['image']); ?>" alt="Current image" style="max-height: 60px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group">
                                                    <label>LinkedIn</label>
                                                    <input type="url" name="linkedin_url" value="<?php echo htmlspecialchars($member['linkedin_url']); ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label>Order</label>
                                                    <input type="number" name="display_order" value="<?php echo $member['display_order']; ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger-outline" onclick="deleteTeamMember(<?php echo $member['member_id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Values Section Tab -->
                <div class="tab-content" id="values-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-heart"></i> Core Values & Ethos</h2>
                                <p>Manage foundational pillars and sustainability principles</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="showAddValueForm()">
                                <i class="fas fa-plus"></i> Add Value
                            </button>
                        </div>

                        <!-- Values Section Info -->
                        <form action="../handlers/about/updateValuesSectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $values_section_data['section_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="values_title">Section Title</label>
                                <input type="text" id="values_title" name="section_title" value="<?php echo htmlspecialchars($values_section_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="values_intro">Section Introduction</label>
                                <textarea id="values_intro" name="section_intro" rows="2" required><?php echo htmlspecialchars($values_section_data['section_intro'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-actions" style="margin-bottom: 1.5rem;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Values Header
                                </button>
                            </div>
                        </form>

                        <!-- Values List -->
                        <div class="subsection">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3><i class="fas fa-shield-heart"></i> Core Value Items (<?php echo count($values); ?>)</h3>
                                <button type="button" class="btn btn-success btn-sm" onclick="showAddValueForm()">
                                    <i class="fas fa-plus"></i> Add Value
                                </button>
                            </div>

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
                                                <label>Pillar Title</label>
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($value['title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" rows="2" required><?php echo htmlspecialchars($value['description']); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Order</label>
                                                <input type="number" name="display_order" value="<?php echo $value['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger-outline" onclick="deleteValue(<?php echo $value['value_id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gallery Section Tab -->
                <div class="tab-content" id="gallery-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-images"></i> About Photo Gallery</h2>
                                <p>Manage field notes and destination photos on the About page</p>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" onclick="showAddGalleryItemForm()">
                                <i class="fas fa-plus"></i> Add Photo
                            </button>
                        </div>

                        <!-- Gallery Section Info -->
                        <form action="../handlers/about/updateGallerySectionHandler.php" method="POST" class="section-form">
                            <input type="hidden" name="section_id" value="<?php echo $gallery_section_data['section_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="gallery_title">Section Title</label>
                                <input type="text" id="gallery_title" name="section_title" value="<?php echo htmlspecialchars($gallery_section_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="gallery_intro">Section Introduction</label>
                                <textarea id="gallery_intro" name="section_intro" rows="2" required><?php echo htmlspecialchars($gallery_section_data['section_intro'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-actions" style="margin-bottom: 1.5rem;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Gallery Header
                                </button>
                            </div>
                        </form>

                        <!-- Gallery Items Grid -->
                        <div class="subsection">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <h3><i class="fas fa-camera"></i> Photos (<?php echo count($gallery_items); ?>)</h3>
                                <button type="button" class="btn btn-success btn-sm" onclick="showAddGalleryItemForm()">
                                    <i class="fas fa-plus"></i> Add Photo
                                </button>
                            </div>

                            <div class="gallery-grid">
                                <?php foreach ($gallery_items as $item): ?>
                                    <div class="gallery-card">
                                        <form action="../handlers/about/updateGalleryItemHandler.php" method="POST" enctype="multipart/form-data" class="gallery-form">
                                            <input type="hidden" name="gallery_id" value="<?php echo $item['gallery_id']; ?>">

                                            <div class="form-group">
                                                <label>Photo Caption / Title</label>
                                                <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Alt Text</label>
                                                <input type="text" name="alt_text" value="<?php echo htmlspecialchars($item['alt_text']); ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Image File</label>
                                                <input type="file" name="image" accept="image/*">
                                                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($item['image']); ?>">
                                                <?php if (!empty($item['image'])): ?>
                                                    <div class="current-image">
                                                        <img src="../images/about/gallery/<?php echo htmlspecialchars($item['image']); ?>" alt="Current image" style="max-height: 70px;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Order</label>
                                                <input type="number" name="display_order" value="<?php echo $item['display_order']; ?>" required>
                                            </div>

                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger-outline" onclick="deleteGalleryItem(<?php echo $item['gallery_id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to Action Section Tab -->
                <div class="tab-content" id="cta-tab">
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h2><i class="fas fa-bullhorn"></i> Call to Action Banner & Social Links</h2>
                                <p>Manage closing action banner and social channel links on the About page</p>
                            </div>
                        </div>

                        <form action="../handlers/about/updateCtaHandler.php" method="POST" enctype="multipart/form-data" class="section-form">
                            <input type="hidden" name="cta_id" value="<?php echo $cta_data['cta_id'] ?? 1; ?>">

                            <div class="form-group">
                                <label for="cta_title">CTA Headline</label>
                                <input type="text" id="cta_title" name="section_title" value="<?php echo htmlspecialchars($cta_data['section_title'] ?? ''); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="cta_description">CTA Subtitle / Description</label>
                                <textarea id="cta_description" name="section_description" rows="2" required><?php echo htmlspecialchars($cta_data['section_description'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="cta_button_text">Button Label</label>
                                    <input type="text" id="cta_button_text" name="button_text" value="<?php echo htmlspecialchars($cta_data['button_text'] ?? 'Plan Your Journey'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="cta_button_link">Button Link</label>
                                    <input type="text" id="cta_button_link" name="button_link" value="<?php echo htmlspecialchars($cta_data['button_link'] ?? '#planner'); ?>" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cta_background_image">CTA Background Banner</label>
                                <input type="file" id="cta_background_image" name="background_image" accept="image/*">
                                <input type="hidden" name="existing_background_image" value="<?php echo htmlspecialchars($cta_data['background_image'] ?? ''); ?>">
                                <?php if (!empty($cta_data['background_image'])): ?>
                                    <div class="current-image">
                                        <img src="../images/about/<?php echo htmlspecialchars($cta_data['background_image']); ?>" alt="Current background" style="max-height: 80px;">
                                        <p>Current: <?php echo htmlspecialchars($cta_data['background_image']); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="subsection">
                                <h3><i class="fas fa-share-nodes"></i> Social Media Channel URLs</h3>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="facebook_url">Facebook URL</label>
                                        <input type="url" id="facebook_url" name="facebook_url" value="<?php echo htmlspecialchars($cta_data['facebook_url'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram_url">Instagram URL</label>
                                        <input type="url" id="instagram_url" name="instagram_url" value="<?php echo htmlspecialchars($cta_data['instagram_url'] ?? ''); ?>">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="twitter_url">X / Twitter URL</label>
                                        <input type="url" id="twitter_url" name="twitter_url" value="<?php echo htmlspecialchars($cta_data['twitter_url'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="youtube_url">YouTube URL</label>
                                        <input type="url" id="youtube_url" name="youtube_url" value="<?php echo htmlspecialchars($cta_data['youtube_url'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Call to Action Section
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
