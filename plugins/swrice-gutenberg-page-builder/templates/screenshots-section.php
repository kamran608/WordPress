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

    <div class="sppm-screenshots-gallery" id="<?php echo esc_attr($gallery_id); ?>" data-gallery="professional">
        <!-- Main Featured Screenshot -->
        <div class="sppm-gallery-main">
            <div class="sppm-main-screenshot" data-main-display>
                <?php if (!empty($screenshot_items[0]['imageUrl'])): ?>
                <img src="<?php echo esc_url($screenshot_items[0]['imageUrl']); ?>" 
                     alt="<?php echo esc_attr(isset($screenshot_items[0]['title']) ? $screenshot_items[0]['title'] : 'Screenshot'); ?>"
                     class="sppm-main-image" 
                     data-full-src="<?php echo esc_url($screenshot_items[0]['imageUrl']); ?>" />
                <div class="sppm-screenshot-overlay">
                    <button class="sppm-expand-btn" type="button" aria-label="View full size">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                        </svg>
                    </button>
                    <?php if (isset($screenshot_items[0]['title'])): ?>
                    <div class="sppm-screenshot-info">
                        <h4><?php echo esc_html($screenshot_items[0]['title']); ?></h4>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thumbnail Navigation -->
        <?php if ($total_slides > 1): ?>
        <div class="sppm-gallery-thumbnails">
            <div class="sppm-thumbnails-track" data-thumbnails-track>
                <?php foreach ($screenshot_items as $index => $screenshot): ?>
                    <?php if (!empty($screenshot['imageUrl'])): ?>
                    <button class="sppm-thumbnail<?php echo $index === 0 ? ' active' : ''; ?>" 
                            type="button"
                            data-index="<?php echo $index; ?>"
                            data-full-src="<?php echo esc_url($screenshot['imageUrl']); ?>"
                            aria-label="View screenshot <?php echo $index + 1; ?>">
                        <img src="<?php echo esc_url($screenshot['imageUrl']); ?>" 
                             alt="<?php echo esc_attr(isset($screenshot['title']) ? $screenshot['title'] : 'Screenshot thumbnail'); ?>" />
                        <div class="sppm-thumbnail-overlay"></div>
                    </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            
            <?php if ($total_slides > 4): ?>
            <button class="sppm-nav-btn sppm-nav-prev" type="button" aria-label="Previous thumbnails">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15,18 9,12 15,6"></polyline>
                </svg>
            </button>
            <button class="sppm-nav-btn sppm-nav-next" type="button" aria-label="Next thumbnails">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"></polyline>
                </svg>
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Lightbox Modal -->
        <div class="sppm-lightbox" data-lightbox aria-hidden="true" role="dialog">
            <div class="sppm-lightbox-backdrop" data-lightbox-close></div>
            <div class="sppm-lightbox-content">
                <button class="sppm-lightbox-close" type="button" data-lightbox-close aria-label="Close lightbox">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
                <div class="sppm-lightbox-image-container">
                    <img class="sppm-lightbox-image" src="" alt="" />
                </div>
                <?php if ($total_slides > 1): ?>
                <button class="sppm-lightbox-nav sppm-lightbox-prev" type="button" aria-label="Previous image">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15,18 9,12 15,6"></polyline>
                    </svg>
                </button>
                <button class="sppm-lightbox-nav sppm-lightbox-next" type="button" aria-label="Next image">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9,18 15,12 9,6"></polyline>
                    </svg>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

