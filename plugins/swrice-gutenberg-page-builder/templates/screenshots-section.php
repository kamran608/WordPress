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
            <button class="sppm-nav-arrow sppm-nav-prev" type="button" aria-label="Previous screenshot"
                    style="position:absolute;left:12px;top:50%;transform:translateY(-50%);width:40px;height:40px;padding:4px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;z-index:10;">
                <svg class="sppm-icon sppm-icon-arrow" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M15 18 L9 12 L15 6"></path>
                </svg>
            </button>
            <button class="sppm-nav-arrow sppm-nav-next" type="button" aria-label="Next screenshot"
                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);width:40px;height:40px;padding:4px;display:flex;align-items:center;justify-content:center;border:none;cursor:pointer;z-index:10;">
                <svg class="sppm-icon sppm-icon-arrow" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="M9 6 L15 12 L9 18"></path>
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
