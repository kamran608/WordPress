<?php
/**
 * Why Choose Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$why_choose_heading = isset($attributes['whyChooseHeading']) ? $attributes['whyChooseHeading'] : 'Why Choose Us';
$why_choose_icon = isset($attributes['whyChooseIcon']) ? $attributes['whyChooseIcon'] : '⭐';
$why_choose_items = isset($attributes['whyChooseItems']) ? $attributes['whyChooseItems'] : array();

if (empty($why_choose_items) || !is_array($why_choose_items)) return;
?>

<section class="sppm-section sppm-why-choose-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($why_choose_icon): ?><span class="sppm-section-icon"><?php echo $why_choose_icon; ?></span><?php endif; ?>
            <?php echo esc_html($why_choose_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-why-choose-grid">
        <?php foreach ($why_choose_items as $benefit): ?>
        <div class="sppm-benefit-card">
            <?php if (!empty($benefit['icon'])): ?>
            <div class="sppm-benefit-icon"><?php echo $benefit['icon']; ?></div>
            <?php endif; ?>
            <h3 class="sppm-benefit-title"><?php echo esc_html($benefit['title']); ?></h3>
            <p class="sppm-benefit-desc"><?php echo esc_html($benefit['description']); ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>
