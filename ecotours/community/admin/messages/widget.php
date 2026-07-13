<?php
// Messages Dashboard Widget
// Include this file in the main dashboard to show message statistics and recent messages

// Get message statistics
$messages_stats_query = "SELECT 
    COUNT(*) as total_messages,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_messages,
    COUNT(CASE WHEN status = 'read' THEN 1 END) as read_messages,
    COUNT(CASE WHEN status = 'replied' THEN 1 END) as replied_messages,
    COUNT(CASE WHEN volunteer_interest = 1 THEN 1 END) as volunteer_interested,
    COUNT(CASE WHEN donation_interest = 1 THEN 1 END) as donation_interested,
    COUNT(CASE WHEN DATE(sent_at) = CURDATE() THEN 1 END) as today_messages,
    COUNT(CASE WHEN DATE(sent_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as week_messages
    FROM community_messages";

$messages_stats_result = mysqli_query($conn, $messages_stats_query);
$messages_stats = mysqli_fetch_assoc($messages_stats_result);

// Get recent messages (last 5)
$recent_messages_query = "SELECT id, name, email, subject, message, status, sent_at, volunteer_interest, donation_interest 
                         FROM community_messages 
                         ORDER BY sent_at DESC 
                         LIMIT 5";
$recent_messages_result = mysqli_query($conn, $recent_messages_query);
?>

<!-- Messages Overview Widget -->
<div class="dashboard-widget messages-widget">
    <div class="widget-header">
        <h3><i class="fas fa-envelope"></i> Messages Overview</h3>
        <a href="messages/" class="widget-action">
            <i class="fas fa-external-link-alt"></i>
            View All
        </a>
    </div>
    
    <div class="widget-content">
        <!-- Quick Stats -->
        <div class="quick-stats">
            <div class="stat-item">
                <div class="stat-icon new-messages">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $messages_stats['new_messages']; ?></div>
                    <div class="stat-label">New Messages</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon today-messages">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $messages_stats['today_messages']; ?></div>
                    <div class="stat-label">Today</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon volunteer-interest">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $messages_stats['volunteer_interested']; ?></div>
                    <div class="stat-label">Volunteer Interest</div>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon donation-interest">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-number"><?php echo $messages_stats['donation_interested']; ?></div>
                    <div class="stat-label">Donation Interest</div>
                </div>
            </div>
        </div>
        
        <!-- Recent Messages -->
        <div class="recent-messages">
            <h4><i class="fas fa-clock"></i> Recent Messages</h4>
            
            <?php if (mysqli_num_rows($recent_messages_result) > 0): ?>
                <div class="messages-list">
                    <?php while ($message = mysqli_fetch_assoc($recent_messages_result)): ?>
                        <div class="message-item <?php echo $message['status'] === 'new' ? 'unread' : ''; ?>">
                            <div class="message-avatar">
                                <?php echo strtoupper(substr($message['name'], 0, 1)); ?>
                            </div>
                            
                            <div class="message-content">
                                <div class="message-header">
                                    <div class="message-sender">
                                        <?php echo htmlspecialchars($message['name']); ?>
                                        <?php if ($message['status'] === 'new'): ?>
                                            <span class="new-indicator">
                                                <i class="fas fa-circle"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="message-time">
                                        <?php 
                                        $time_diff = time() - strtotime($message['sent_at']);
                                        if ($time_diff < 3600) {
                                            echo floor($time_diff / 60) . 'm ago';
                                        } elseif ($time_diff < 86400) {
                                            echo floor($time_diff / 3600) . 'h ago';
                                        } else {
                                            echo date('M j', strtotime($message['sent_at']));
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="message-subject">
                                    <?php echo htmlspecialchars($message['subject'] ?: 'No Subject'); ?>
                                </div>
                                
                                <div class="message-preview">
                                    <?php echo htmlspecialchars(substr($message['message'], 0, 80)); ?>...
                                </div>
                                
                                <div class="message-badges">
                                    <?php if ($message['volunteer_interest']): ?>
                                        <span class="mini-badge volunteer">
                                            <i class="fas fa-hands-helping"></i>
                                        </span>
                                    <?php endif; ?>
                                    <?php if ($message['donation_interest']): ?>
                                        <span class="mini-badge donation">
                                            <i class="fas fa-heart"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="message-actions">
                                <a href="messages/view.php?id=<?php echo $message['id']; ?>" 
                                   class="action-btn" title="View Message">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="messages/reply.php?id=<?php echo $message['id']; ?>" 
                                   class="action-btn" title="Reply">
                                    <i class="fas fa-reply"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="no-messages">
                    <i class="fas fa-inbox"></i>
                    <p>No messages yet</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Quick Actions -->
        <div class="widget-actions">
            <a href="messages/" class="action-link">
                <i class="fas fa-list"></i>
                All Messages
            </a>
            <a href="messages/index.php?status=new" class="action-link">
                <i class="fas fa-envelope"></i>
                New Messages
            </a>
            <a href="messages/export.php" class="action-link">
                <i class="fas fa-download"></i>
                Export
            </a>
        </div>
    </div>
</div>

<style>
.messages-widget {
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
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
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
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1rem;
}

.stat-icon.new-messages {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-icon.today-messages {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-icon.volunteer-interest {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-icon.donation-interest {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-details {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-medium);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.recent-messages h4 {
    font-size: 1rem;
    color: var(--text-dark);
    margin: 0 0 1rem 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.recent-messages h4 i {
    color: var(--primary-green);
}

.messages-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.message-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--border-radius-md);
    border: 1px solid var(--neutral-beige);
    transition: all 0.3s ease;
}

.message-item:hover {
    box-shadow: var(--shadow-sm);
    transform: translateY(-1px);
}

.message-item.unread {
    background-color: rgba(42, 72, 88, 0.02);
    border-left: 3px solid var(--primary-green);
}

.message-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: var(--primary-green);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.message-content {
    flex: 1;
    min-width: 0;
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.message-sender {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.new-indicator {
    color: var(--primary-green);
    font-size: 0.5rem;
}

.message-time {
    font-size: 0.8rem;
    color: var(--text-medium);
}

.message-subject {
    font-size: 0.85rem;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
    font-weight: 500;
}

.message-preview {
    font-size: 0.8rem;
    color: var(--text-medium);
    line-height: 1.4;
    margin-bottom: 0.5rem;
}

.message-badges {
    display: flex;
    gap: 0.25rem;
}

.mini-badge {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
    color: white;
}

.mini-badge.volunteer {
    background-color: var(--warning-color);
}

.mini-badge.donation {
    background-color: var(--error-color);
}

.message-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.action-btn {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: var(--neutral-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-medium);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 0.8rem;
}

.action-btn:hover {
    background-color: var(--primary-green);
    color: white;
    transform: scale(1.1);
}

.no-messages {
    text-align: center;
    padding: 2rem;
    color: var(--text-medium);
}

.no-messages i {
    font-size: 2rem;
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
