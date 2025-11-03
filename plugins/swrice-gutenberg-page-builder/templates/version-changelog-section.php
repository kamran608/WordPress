<?php
/**
 * Version & Changelog Section Template
 */

if (!defined('ABSPATH')) exit;

$version_heading = isset($attributes['versionChangelogHeading']) ? $attributes['versionChangelogHeading'] : 'Version & Changelog';
$version_icon = isset($attributes['versionChangelogIcon']) ? $attributes['versionChangelogIcon'] : '📋';
$version_description = isset($attributes['versionChangelogDescription']) ? $attributes['versionChangelogDescription'] : '';
$current_version = isset($attributes['currentVersion']) ? $attributes['currentVersion'] : '1.0.0';
$upgrade_notice = isset($attributes['upgradeNotice']) ? $attributes['upgradeNotice'] : '';
$changelog_items = isset($attributes['changelogItems']) ? $attributes['changelogItems'] : array();

// Show section even if changelog is empty, but at least show current version
?>

<section class="sppm-section sppm-version-changelog-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($version_icon): ?><span class="sppm-section-icon"><?php echo $version_icon; ?></span><?php endif; ?>
            <?php echo esc_html($version_heading); ?>
        </h2>
        <?php if ($version_description): ?>
        <p class="sppm-section-description"><?php echo esc_html($version_description); ?></p>
        <?php endif; ?>
    </div>
    
    <!-- Current Version Display -->
    <div class="sppm-current-version-wrapper">
        <div class="sppm-current-version-card">
            <div class="sppm-version-header">
                <div class="sppm-version-badge">
                    <span class="sppm-version-icon">🆕</span>
                    <span class="sppm-version-label">Current Version</span>
                </div>
                <div class="sppm-version-number"><?php echo esc_html($current_version); ?></div>
            </div>
            
            <?php if ($upgrade_notice): ?>
            <div class="sppm-upgrade-notice">
                <div class="sppm-notice-icon">⚠️</div>
                <div class="sppm-notice-content">
                    <div class="sppm-notice-title">Important Notice</div>
                    <div class="sppm-notice-text"><?php echo esc_html($upgrade_notice); ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (is_array($changelog_items) && !empty($changelog_items)): ?>
    <!-- Changelog Timeline -->
    <div class="sppm-changelog-container">
        <h3 class="sppm-changelog-title">
            <span class="sppm-changelog-icon">📜</span>
            Release History
        </h3>
        
        <div class="sppm-changelog-timeline">
            <?php foreach ($changelog_items as $index => $item): ?>
                <?php
                $version = isset($item['version']) ? $item['version'] : '1.0.0';
                $release_date = isset($item['releaseDate']) ? $item['releaseDate'] : '';
                $changes = isset($item['changes']) ? $item['changes'] : '';
                $type = isset($item['type']) ? $item['type'] : 'minor';
                
                // Define type styling
                $type_config = array(
                    'major' => array('color' => '#dc3545', 'icon' => '🚀', 'label' => 'Major Release'),
                    'minor' => array('color' => '#007bff', 'icon' => '✨', 'label' => 'Minor Release'),
                    'patch' => array('color' => '#28a745', 'icon' => '🐛', 'label' => 'Bug Fix'),
                    'security' => array('color' => '#fd7e14', 'icon' => '🔒', 'label' => 'Security Update')
                );
                
                $config = isset($type_config[$type]) ? $type_config[$type] : $type_config['minor'];
                ?>
                
                <div class="sppm-changelog-item">
                    <div class="sppm-changelog-marker" style="background-color: <?php echo $config['color']; ?>">
                        <span class="sppm-marker-icon"><?php echo $config['icon']; ?></span>
                    </div>
                    
                    <div class="sppm-changelog-content">
                        <div class="sppm-changelog-header">
                            <div class="sppm-version-info">
                                <h4 class="sppm-changelog-version">Version <?php echo esc_html($version); ?></h4>
                                <?php if ($release_date): ?>
                                <span class="sppm-release-date"><?php echo esc_html($release_date); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="sppm-release-type" style="background-color: <?php echo $config['color']; ?>">
                                <?php echo $config['icon']; ?> <?php echo $config['label']; ?>
                            </div>
                        </div>
                        
                        <?php if ($changes): ?>
                        <div class="sppm-changelog-changes">
                            <?php 
                            // Convert line breaks to proper formatting
                            $formatted_changes = nl2br(esc_html($changes));
                            
                            // Convert bullet points if they exist
                            $formatted_changes = preg_replace('/^[\-\*\+]\s+/m', '<span class="sppm-bullet">•</span> ', $formatted_changes);
                            
                            echo $formatted_changes;
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<style>
/* Version & Changelog Section - Themed Design */
.sppm-version-changelog-section {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 40px;
    box-shadow: var(--shadow);
    margin: 40px 0;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
}

.sppm-current-version-wrapper {
    margin-bottom: 40px;
}

.sppm-current-version-card {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
    color: white;
    padding: 30px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(95,160,216,0.25);
    position: relative;
    overflow: hidden;
}

.sppm-current-version-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: shimmer 4s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { transform: rotate(0deg); }
    50% { transform: rotate(180deg); }
}

.sppm-version-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
    position: relative;
    z-index: 1;
}

.sppm-version-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.2);
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 600;
}

.sppm-version-number {
    font-size: 42px;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.sppm-upgrade-notice {
    margin-top: 20px;
    background: rgba(255,255,255,0.15);
    padding: 20px;
    border-radius: 12px;
    display: flex;
    align-items: flex-start;
    gap: 15px;
    position: relative;
    z-index: 1;
}

.sppm-notice-icon {
    font-size: 24px;
    flex-shrink: 0;
    margin-top: 2px;
}

.sppm-notice-content {
    flex: 1;
}

.sppm-notice-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 8px;
}

.sppm-notice-text {
    font-size: 14px;
    line-height: 1.5;
    opacity: 0.95;
}

.sppm-changelog-container {
    background: var(--soft);
    border-radius: 16px;
    padding: 40px;
    box-shadow: inset 0 2px 8px rgba(29,42,63,0.04);
}

.sppm-changelog-title {
    display: flex;
    align-items: center;
    gap: 15px;
    font-size: 28px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 40px 0;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(95,160,216,0.2);
}

.sppm-changelog-icon {
    font-size: 32px;
}

.sppm-changelog-timeline {
    position: relative;
    padding-left: 50px;
}

.sppm-changelog-timeline::before {
    content: '';
    position: absolute;
    left: 25px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(to bottom, var(--accent), var(--soft));
    border-radius: 2px;
}

.sppm-changelog-item {
    position: relative;
    margin-bottom: 40px;
    padding-bottom: 40px;
}

.sppm-changelog-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
}

.sppm-changelog-marker {
    position: absolute;
    left: -33px;
    top: 8px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 2;
    border: 3px solid var(--card-bg);
}

.sppm-marker-icon {
    font-size: 18px;
    color: white;
}

.sppm-changelog-content {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 30px;
    border: 1px solid rgba(95,160,216,0.1);
    transition: all 0.3s ease;
    box-shadow: var(--shadow);
}

.sppm-changelog-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(29,42,63,0.1);
}

.sppm-changelog-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.sppm-version-info h4 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 8px 0;
}

.sppm-release-date {
    color: var(--muted);
    font-size: 14px;
    font-weight: 500;
    background: var(--soft);
    padding: 4px 10px;
    border-radius: 6px;
}

.sppm-release-type {
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.sppm-changelog-changes {
    color: var(--text);
    line-height: 1.8;
    font-size: 16px;
}

.sppm-bullet {
    color: var(--accent);
    font-weight: bold;
    margin-right: 8px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .sppm-version-changelog-section {
        padding: 30px 20px;
    }
    
    .sppm-current-version-card {
        padding: 25px 20px;
    }
    
    .sppm-version-header {
        flex-direction: column;
        align-items: flex-start;
        text-align: left;
    }
    
    .sppm-version-number {
        font-size: 36px;
    }
    
    .sppm-changelog-container {
        padding: 30px 20px;
    }
    
    .sppm-changelog-title {
        font-size: 24px;
    }
    
    .sppm-changelog-timeline {
        padding-left: 40px;
    }
    
    .sppm-changelog-timeline::before {
        left: 20px;
    }
    
    .sppm-changelog-marker {
        left: -28px;
        width: 32px;
        height: 32px;
    }
    
    .sppm-marker-icon {
        font-size: 14px;
    }
    
    .sppm-changelog-content {
        padding: 25px 20px;
    }
    
    .sppm-changelog-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .sppm-version-info h4 {
        font-size: 20px;
    }
}

@media (max-width: 480px) {
    .sppm-upgrade-notice {
        padding: 15px;
        flex-direction: column;
        gap: 10px;
    }
    
    .sppm-notice-title {
        font-size: 14px;
    }
    
    .sppm-notice-text {
        font-size: 13px;
    }
    
    .sppm-changelog-timeline::before {
        left: 15px;
    }
    
    .sppm-changelog-timeline {
        padding-left: 30px;
    }
    
    .sppm-changelog-marker {
        left: -23px;
        width: 28px;
        height: 28px;
    }
    
    .sppm-marker-icon {
        font-size: 12px;
    }
    
    .sppm-changelog-changes {
        font-size: 14px;
    }
}
</style>
