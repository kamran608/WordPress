<?php
/**
 * Screenshots Section Template - Professional Gallery with Lightbox
 */

if (!defined('ABSPATH')) exit;

$screenshots_heading = isset($attributes['screenshotsHeading']) ? $attributes['screenshotsHeading'] : 'Screenshots';
$screenshots_icon = isset($attributes['screenshotsIcon']) ? $attributes['screenshotsIcon'] : '📸';
$screenshots_description = isset($attributes['screenshotsDescription']) ? $attributes['screenshotsDescription'] : '';
$screenshot_items = isset($attributes['screenshotItems']) ? $attributes['screenshotItems'] : array();

if (empty($screenshot_items) || !is_array($screenshot_items)) return;

$gallery_id = uniqid('sppm-gallery-');
$total_slides = count($screenshot_items);
?>

<section class="sppm-section sppm-screenshots-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($screenshots_icon): ?><span class="sppm-section-icon"><?php echo $screenshots_icon; ?></span><?php endif; ?>
            <?php echo esc_html($screenshots_heading); ?>
        </h2>
        <?php if ($screenshots_description): ?>
        <p class="sppm-section-description"><?php echo esc_html($screenshots_description); ?></p>
        <?php endif; ?>
    </div>

    <div class="sppm-screenshots-slider" id="<?php echo esc_attr($gallery_id); ?>" data-slider="simple" data-total="<?php echo intval($total_slides); ?>">
        <!-- Screenshots Container -->
        <div class="sppm-slider-container">
            <div class="sppm-slides-track">
                <?php foreach ($screenshot_items as $index => $screenshot): ?>
                    <?php if (!empty($screenshot['imageUrl'])): ?>
                    <div class="sppm-slide<?php echo $index === 0 ? ' active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($screenshot['imageUrl']); ?>" 
                             alt="<?php echo esc_attr(isset($screenshot['title']) ? $screenshot['title'] : 'Screenshot'); ?>"
                             class="sppm-slide-image" />
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Navigation Arrows -->
            <?php if ($total_slides > 1): ?>
            <button class="sppm-arrow sppm-arrow-left" type="button" aria-label="Previous screenshot">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
            </button>
            <button class="sppm-arrow sppm-arrow-right" type="button" aria-label="Next screenshot">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </button>
            <?php endif; ?>
        </div>
    </div>
</section>
