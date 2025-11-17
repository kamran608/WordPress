<?php
/**
 * Screenshots Section Template - Improved Responsive Slider
 */

if (!defined('ABSPATH')) exit;

$screenshots_heading = isset($attributes['screenshotsHeading']) ? $attributes['screenshotsHeading'] : 'Screenshots';
$screenshots_icon = isset($attributes['screenshotsIcon']) ? $attributes['screenshotsIcon'] : '📸';
$screenshots_description = isset($attributes['screenshotsDescription']) ? $attributes['screenshotsDescription'] : '';
$screenshot_items = isset($attributes['screenshotItems']) ? $attributes['screenshotItems'] : array();

if (empty($screenshot_items) || !is_array($screenshot_items)) return;

$slider_id = uniqid('sppm-ss-');
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

    <div class="sppm-screenshots-container" id="<?php echo esc_attr($slider_id); ?>" data-slider="sppm" data-total="<?php echo intval($total_slides); ?>">
        <div class="sppm-screenshots-viewport">
            <div class="sppm-screenshots-track">
                <?php foreach ($screenshot_items as $index => $screenshot): ?>
                    <div class="sppm-screenshot-slide<?php echo $index === 0 ? ' is-active' : ''; ?>">
                        <?php if (!empty($screenshot['imageUrl'])): ?>
                        <img src="<?php echo esc_url($screenshot['imageUrl']); ?>"
                             alt="<?php echo esc_attr(isset($screenshot['title']) ? $screenshot['title'] : 'Screenshot'); ?>"
                             class="sppm-screenshot-image" />
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($total_slides > 1): ?>
        <button class="sppm-arrow sppm-arrow-left" type="button" aria-label="Previous slide">&#8249;</button>
        <button class="sppm-arrow sppm-arrow-right" type="button" aria-label="Next slide">&#8250;</button>

        <div class="sppm-dots" role="tablist" aria-label="Screenshots pagination">
            <?php foreach ($screenshot_items as $index => $screenshot): ?>
                <button class="sppm-dot<?php echo $index === 0 ? ' active' : ''; ?>" type="button" role="tab" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($slider_id); ?>" data-slide="<?php echo $index; ?>">
                    <span class="screen-reader-text">Go to slide <?php echo $index + 1; ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

