<?php
/**
 * Problem Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$problem_heading = isset($attributes['problemHeading']) ? $attributes['problemHeading'] : 'Problems';
$problem_icon = isset($attributes['problemIcon']) ? $attributes['problemIcon'] : '⚠️';
$problem_items = isset($attributes['problemItems']) ? $attributes['problemItems'] : array();

if (empty($problem_items) || !is_array($problem_items)) return;
?>

<section class="sppm-section sppm-problem-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($problem_icon): ?><span class="sppm-section-icon"><?php echo $problem_icon; ?></span><?php endif; ?>
            <?php echo esc_html($problem_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-problem-grid">
        <?php if (is_array($problem_items) && !empty($problem_items)): ?>
            <?php foreach ($problem_items as $problem): ?>
            <div class="sppm-problem-card">
                <?php if (!empty($problem['icon'])): ?>
                <div class="sppm-problem-icon"><?php echo $problem['icon']; ?></div>
                <?php endif; ?>
                <h3 class="sppm-problem-title"><?php echo esc_html($problem['title']); ?></h3>
                <p class="sppm-problem-desc"><?php echo esc_html($problem['description']); ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
