<?php
/**
 * How It Works Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$how_it_works_heading = isset($attributes['howItWorksHeading']) ? $attributes['howItWorksHeading'] : 'How It Works';
$how_it_works_icon = isset($attributes['howItWorksIcon']) ? $attributes['howItWorksIcon'] : '⚙️';
$steps_items = isset($attributes['stepsItems']) ? $attributes['stepsItems'] : array();

if (empty($steps_items) || !is_array($steps_items)) return;
?>

<section class="sppm-section sppm-how-it-works-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($how_it_works_icon): ?><span class="sppm-section-icon"><?php echo $how_it_works_icon; ?></span><?php endif; ?>
            <?php echo esc_html($how_it_works_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-steps-grid">
        <?php if (is_array($steps_items) && !empty($steps_items)): ?>
            <?php foreach ($steps_items as $index => $step): ?>
            <div class="sppm-step-card">
                <div class="sppm-step-number"><?php echo ($index + 1); ?></div>
                <h3 class="sppm-step-title"><?php echo esc_html($step['title']); ?></h3>
                <p class="sppm-step-desc"><?php echo esc_html($step['description']); ?></p>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
