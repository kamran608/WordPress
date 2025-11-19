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

    <div class="sppm-screenshots-gallery" id="<?php echo esc_attr($gallery_id); ?>" data-gallery="wordpress-style" data-total="<?php echo intval($total_slides); ?>">
        <!-- Main Screenshot Display -->
        <div class="sppm-main-screenshot-container">
            <div class="sppm-main-screenshot">
                <?php foreach ($screenshot_items as $index => $screenshot): ?>
                    <?php if (!empty($screenshot['imageUrl'])): ?>
                    <div class="sppm-screenshot-slide<?php echo $index === 0 ? ' active' : ''; ?>" data-index="<?php echo $index; ?>">
                        <img src="<?php echo esc_url($screenshot['imageUrl']); ?>" 
                             alt="<?php echo esc_attr(isset($screenshot['title']) ? $screenshot['title'] : 'Screenshot'); ?>"
                             class="sppm-screenshot-image" />
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <!-- Navigation Arrows -->
            <?php if ($total_slides > 1): ?>
            <button class="sppm-nav-arrow sppm-nav-prev" type="button" aria-label="Previous screenshot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
            </button>
            <button class="sppm-nav-arrow sppm-nav-next" type="button" aria-label="Next screenshot">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </button>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Preview Strip (WordPress.org style) -->
        <?php if ($total_slides > 1): ?>
        <div class="sppm-thumbnails-strip">
            <?php foreach ($screenshot_items as $index => $screenshot): ?>
                <?php if (!empty($screenshot['imageUrl'])): ?>
                <button class="sppm-thumbnail<?php echo $index === 0 ? ' active' : ''; ?>" 
                        type="button"
                        data-index="<?php echo $index; ?>"
                        aria-label="View screenshot <?php echo $index + 1; ?>">
                    <img src="<?php echo esc_url($screenshot['imageUrl']); ?>" 
                         alt="<?php echo esc_attr(isset($screenshot['title']) ? $screenshot['title'] : 'Screenshot thumbnail'); ?>" />
                </button>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
