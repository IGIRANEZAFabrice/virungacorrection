<?php
// Categories Dashboard Widget
// Include this file in the main dashboard to show category statistics and recent categories

// Get category statistics
$categories_stats_query = "SELECT 
    COUNT(*) as total_categories,
    COUNT(CASE WHEN status = 'active' THEN 1 END) as active_categories,
    COUNT(CASE WHEN status = 'inactive' THEN 1 END) as inactive_categories,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today_categories,
    COUNT(CASE WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as week_categories
    FROM community_categories";

$categories_stats_result = mysqli_query($conn, $categories_stats_query);
$categories_stats = mysqli_fetch_assoc($categories_stats_result);

// Get category usage statistics
$usage_stats_query = "SELECT 
    COUNT(DISTINCT p.category) as used_categories,
    COUNT(p.id) as total_programs
    FROM community_programs p 
    INNER JOIN community_categories c ON p.category = c.name";
$usage_stats_result = mysqli_query($conn, $usage_stats_query);
$usage_stats = mysqli_fetch_assoc($usage_stats_result);

// Get recent categories (last 5)
$recent_categories_query = "SELECT c.id, c.name, c.description, c.icon, c.color, c.status, c.created_at,
                           COUNT(p.id) as program_count
                           FROM community_categories c 
                           LEFT JOIN community_programs p ON c.name = p.category
                           GROUP BY c.id
                           ORDER BY c.created_at DESC 
                           LIMIT 5";
$recent_categories_result = mysqli_query($conn, $recent_categories_query);

// Get most used categories
$popular_categories_query = "SELECT c.name, c.icon, c.color, COUNT(p.id) as program_count
                            FROM community_categories c 
                            INNER JOIN community_programs p ON c.name = p.category
                            GROUP BY c.id
                            ORDER BY program_count DESC
                            LIMIT 3";
$popular_categories_result = mysqli_query($conn, $popular_categories_query);
?>

<!-- Categories Overview Widget -->
<div class="dashboard-widget categories-widget">
    <div class="widget-header">
        <h3><i class="fas fa-tags"></i> Categories Overview</h3>
        <a href="categories/" class="widget-action">
            <i class="fas fa-external-link-alt"></i>
            View All
        </a>
    </div>
    
    <div class="widget-content">
        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-item">
                <div class="stat-icon total-categories">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $categories_stats['total_categories']; ?></div>
                    <div class="stat-label">Total Categories</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon active-categories">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $categories_stats['active_categories']; ?></div>
                    <div class="stat-label">Active</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon used-categories">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $usage_stats['used_categories'] ?: 0; ?></div>
                    <div class="stat-label">In Use</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon total-programs">
                    <i class="fas fa-list-alt"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $usage_stats['total_programs'] ?: 0; ?></div>
                    <div class="stat-label">Programs</div>
                </div>
            </div>
        </div>
        
        <!-- Popular Categories -->
        <?php if (mysqli_num_rows($popular_categories_result) > 0): ?>
            <div class="popular-categories">
                <h4><i class="fas fa-star"></i> Most Used Categories</h4>
                
                <div class="categories-list">
                    <?php while ($category = mysqli_fetch_assoc($popular_categories_result)): ?>
                        <div class="category-item">
                            <div class="category-icon-small" style="background-color: <?php echo htmlspecialchars($category['color']); ?>;">
                                <i class="<?php echo htmlspecialchars($category['icon']); ?>"></i>
                            </div>
                            
                            <div class="category-info">
                                <div class="category-name"><?php echo htmlspecialchars($category['name']); ?></div>
                                <div class="category-usage"><?php echo $category['program_count']; ?> programs</div>
                            </div>
                            
                            <div class="usage-bar">
                                <div class="usage-fill" style="width: <?php echo min(100, ($category['program_count'] / max(1, $usage_stats['total_programs'])) * 100); ?>%; background-color: <?php echo htmlspecialchars($category['color']); ?>;"></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Recent Categories -->
        <div class="recent-categories">
            <h4><i class="fas fa-clock"></i> Recent Categories</h4>
            
            <?php if (mysqli_num_rows($recent_categories_result) > 0): ?>
                <div class="categories-list">
                    <?php while ($category = mysqli_fetch_assoc($recent_categories_result)): ?>
                        <div class="category-item">
                            <div class="category-icon-small" style="background-color: <?php echo htmlspecialchars($category['color']); ?>;">
                                <i class="<?php echo htmlspecialchars($category['icon']); ?>"></i>
                            </div>
                            
                            <div class="category-info">
                                <div class="category-name">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                    <span class="status-indicator status-<?php echo $category['status']; ?>"></span>
                                </div>
                                <div class="category-meta">
                                    <?php 
                                    $time_diff = time() - strtotime($category['created_at']);
                                    if ($time_diff < 3600) {
                                        echo floor($time_diff / 60) . 'm ago';
                                    } elseif ($time_diff < 86400) {
                                        echo floor($time_diff / 3600) . 'h ago';
                                    } else {
                                        echo date('M j', strtotime($category['created_at']));
                                    }
                                    ?>
                                    • <?php echo $category['program_count']; ?> programs
                                </div>
                            </div>
                            
                            <div class="category-actions">
                                <a href="categories/edit.php?id=<?php echo $category['id']; ?>" 
                                   class="action-btn" title="Edit Category">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-categories">
                    <i class="fas fa-tags"></i>
                    <p>No categories yet</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="widget-actions">
            <a href="categories/" class="action-link">
                <i class="fas fa-list"></i>
                All Categories
            </a>
            <a href="categories/create.php" class="action-link">
                <i class="fas fa-plus"></i>
                Add Category
            </a>
            <a href="categories/export.php" class="action-link">
                <i class="fas fa-download"></i>
                Export
            </a>
        </div>
    </div>
</div>

<style>
.categories-widget {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--neutral-beige);
    overflow: hidden;
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, var(--neutral-light) 0%, white 100%);
    border-bottom: 1px solid var(--neutral-beige);
}

.widget-header h3 {
    font-size: 1.2rem;
    color: var(--text-dark);
    margin: 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.widget-header h3 i {
    color: var(--primary-green);
}

.widget-action {
    color: var(--primary-green);
    text-decoration: none;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.widget-action:hover {
    color: var(--text-dark);
}

.widget-content {
    padding: 1.5rem;
}

.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background-color: var(--neutral-light);
    border-radius: var(--border-radius-md);
    transition: transform 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.stat-icon.total-categories {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-icon.active-categories {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stat-icon.used-categories {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-icon.total-programs {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-number {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.popular-categories, .recent-categories {
    margin-bottom: 1.5rem;
}

.popular-categories h4, .recent-categories h4 {
    font-size: 1rem;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.popular-categories h4 i, .recent-categories h4 i {
    color: var(--primary-green);
}

.categories-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.category-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: var(--border-radius-md);
    border: 1px solid var(--neutral-beige);
    transition: all 0.3s ease;
}

.category-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.category-icon-small {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.category-info {
    flex: 1;
    min-width: 0;
}

.category-name {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.category-usage, .category-meta {
    font-size: 0.8rem;
    color: var(--text-medium);
}

.status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.status-indicator.status-active {
    background-color: var(--success-color);
}

.status-indicator.status-inactive {
    background-color: var(--warning-color);
}

.usage-bar {
    width: 60px;
    height: 4px;
    background-color: var(--neutral-beige);
    border-radius: 2px;
    overflow: hidden;
}

.usage-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.category-actions {
    display: flex;
    gap: 0.25rem;
}

.action-btn {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    background-color: var(--neutral-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-medium);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.7rem;
}

.action-btn:hover {
    background-color: var(--primary-green);
    color: white;
    transform: scale(1.1);
}

.no-categories {
    text-align: center;
    padding: 1.5rem;
    color: var(--text-medium);
}

.no-categories i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
}

.widget-actions {
    display: flex;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 1px solid var(--neutral-beige);
}

.action-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-green);
    text-decoration: none;
    font-size: 0.85rem;
    transition: color 0.3s ease;
}

.action-link:hover {
    color: var(--text-dark);
}

@media (max-width: 768px) {
    .quick-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .widget-actions {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>
