<?php
/**
 * Guarantee Section Template
 */

if (!defined('ABSPATH')) exit;

$guarantee_heading = isset($attributes['guaranteeHeading']) ? $attributes['guaranteeHeading'] : 'Guarantee';
$guarantee_icon = isset($attributes['guaranteeIcon']) ? $attributes['guaranteeIcon'] : '🛡️';
$guarantee_text = isset($attributes['guaranteeText']) ? $attributes['guaranteeText'] : '';
$guarantee_points = isset($attributes['guaranteePoints']) ? $attributes['guaranteePoints'] : array();

if (empty($guarantee_text) && empty($guarantee_points)) return;
?>

<section class="sppm-section sppm-guarantee-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($guarantee_icon): ?><span class="sppm-section-icon"><?php echo $guarantee_icon; ?></span><?php endif; ?>
            <?php echo esc_html($guarantee_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-guarantee-content">
        <p class="sppm-guarantee-text"><?php echo esc_html($guarantee_text); ?></p>
        
        <?php if (is_array($guarantee_points) && !empty($guarantee_points)): ?>
        <div class="sppm-guarantee-points">
            <?php foreach ($guarantee_points as $point): ?>
            <div class="sppm-guarantee-point">
                <span class="sppm-guarantee-check">✅</span>
                <?php echo esc_html($point['point']); ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
