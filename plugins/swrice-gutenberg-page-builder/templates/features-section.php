<?php
/**
 * Features Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$features_heading = isset($attributes['featuresHeading']) ? $attributes['featuresHeading'] : 'Features';
$features_icon = isset($attributes['featuresIcon']) ? $attributes['featuresIcon'] : '🚀';
$feature_items = isset($attributes['featureItems']) ? $attributes['featureItems'] : array();

if (empty($feature_items) || !is_array($feature_items)) return;
?>

<section class="sppm-section sppm-features-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($features_icon): ?><span class="sppm-section-icon"><?php echo $features_icon; ?></span><?php endif; ?>
            <?php echo esc_html($features_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-features-grid">
        <?php if (is_array($feature_items) && !empty($feature_items)): ?>
            <?php foreach ($feature_items as $feature): ?>
            <div class="sppm-feature-card">
                <div class="sppm-feature-card-header">
                    <?php if (!empty($feature['icon'])): ?>
                    <div class="sppm-feature-icon"><?php echo $feature['icon']; ?></div>
                    <?php endif; ?>
                    <h3 class="sppm-feature-title"><?php echo esc_html($feature['title']); ?></h3>
                </div>
                <div class="sppm-feature-card-body">
                    <p class="sppm-feature-desc"><?php echo esc_html($feature['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
