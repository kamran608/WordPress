<?php
/**
 * Bonuses Section Template
 */

if (!defined('ABSPATH')) exit;

$bonuses_heading = isset($attributes['bonusesHeading']) ? $attributes['bonusesHeading'] : 'Bonuses';
$bonuses_icon = isset($attributes['bonusesIcon']) ? $attributes['bonusesIcon'] : '🎁';
$bonus_items = isset($attributes['bonusItems']) ? $attributes['bonusItems'] : array();

if (empty($bonus_items) || !is_array($bonus_items)) return;
?>

<section class="sppm-section sppm-bonuses-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($bonuses_icon): ?><span class="sppm-section-icon"><?php echo $bonuses_icon; ?></span><?php endif; ?>
            <?php echo esc_html($bonuses_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-bonuses-grid">
        <?php foreach ($bonus_items as $bonus): ?>
        <div class="sppm-bonus-card">
            <?php if (!empty($bonus['icon'])): ?>
            <div class="sppm-bonus-icon"><?php echo $bonus['icon']; ?></div>
            <?php endif; ?>
            <h3 class="sppm-bonus-title"><?php echo esc_html($bonus['title']); ?></h3>
            <?php if (!empty($bonus['value'])): ?>
            <div class="sppm-bonus-value">Value: <?php echo esc_html($bonus['value']); ?></div>
            <?php endif; ?>
            <p class="sppm-bonus-desc"><?php echo esc_html($bonus['description']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
