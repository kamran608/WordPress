<?php
/**
 * Video Tutorial Section Template - Premium Video Player
 */

if (!defined('ABSPATH')) exit;

$video_heading = isset($attributes['videoTutorialHeading']) ? $attributes['videoTutorialHeading'] : 'Video Tutorial';
$video_icon = isset($attributes['videoTutorialIcon']) ? $attributes['videoTutorialIcon'] : '🎥';
$video_description = isset($attributes['videoTutorialDescription']) ? $attributes['videoTutorialDescription'] : '';
$video_url = isset($attributes['videoUrl']) ? $attributes['videoUrl'] : '';
$video_title = isset($attributes['videoTitle']) ? $attributes['videoTitle'] : 'Plugin Tutorial';
$video_duration = isset($attributes['videoDuration']) ? $attributes['videoDuration'] : '';
$video_thumbnail = isset($attributes['videoThumbnailUrl']) ? $attributes['videoThumbnailUrl'] : '';
$video_bg_color = isset($attributes['videoTutorialBackgroundColor']) ? $attributes['videoTutorialBackgroundColor'] : '#fff5f5';

if (empty($video_url)) return;

// Parse video URL to determine type and extract ID
$video_type = '';
$video_id = '';
$embed_url = '';

if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
    $video_type = 'youtube';
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $embed_url = 'https://www.youtube.com/embed/' . $video_id;
        if (empty($video_thumbnail)) {
            $video_thumbnail = 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg';
        }
    }
} elseif (strpos($video_url, 'vimeo.com') !== false) {
    $video_type = 'vimeo';
    if (preg_match('/vimeo\.com\/(\d+)/', $video_url, $matches)) {
        $video_id = $matches[1];
        $embed_url = 'https://player.vimeo.com/video/' . $video_id;
    }
} else {
    $video_type = 'direct';
    $embed_url = $video_url;
}

$vid_container_id = uniqid('sppm-video-');
?>

<section class="sppm-section sppm-video-tutorial-section" style="background-color: <?php echo esc_attr($video_bg_color); ?>">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($video_icon): ?><span class="sppm-section-icon"><?php echo $video_icon; ?></span><?php endif; ?>
            <?php echo esc_html($video_heading); ?>
        </h2>
        <?php if ($video_description): ?>
        <p class="sppm-section-description"><?php echo esc_html($video_description); ?></p>
        <?php endif; ?>
    </div>

    <div class="sppm-video-showcase">
        <div class="sppm-video-container" id="<?php echo esc_attr($vid_container_id); ?>" data-video-player="premium">
            <?php if ($video_type === 'direct'): ?>
                <!-- Direct Video Player -->
                <div class="sppm-video-wrapper">
                    <video class="sppm-video-element" 
                           controls 
                           poster="<?php echo esc_url($video_thumbnail); ?>" 
                           aria-label="<?php echo esc_attr($video_title); ?>"
                           preload="metadata">
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            <?php else: ?>
                <!-- Premium Embed Player -->
                <div class="sppm-video-embed-premium" data-embed-url="<?php echo esc_url($embed_url); ?>">
                    <div class="sppm-video-thumbnail-premium">
                        <?php if ($video_thumbnail): ?>
                        <img src="<?php echo esc_url($video_thumbnail); ?>" 
                             alt="<?php echo esc_attr($video_title); ?>"
                             class="sppm-thumbnail-image" />
                        <?php endif; ?>
                        
                        <!-- Premium Play Button -->
                        <div class="sppm-play-overlay">
                            <button class="sppm-play-button-premium" type="button" aria-label="Play <?php echo esc_attr($video_title); ?>">
                                <div class="sppm-play-button-inner">
                                    <svg class="sppm-play-icon" width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                                <div class="sppm-play-ripple"></div>
                            </button>
                        </div>

                        <!-- Video Info Overlay -->
                        <div class="sppm-video-info-overlay">
                            <div class="sppm-video-meta">
                                <h3 class="sppm-video-title"><?php echo esc_html($video_title); ?></h3>
                                <?php if ($video_duration): ?>
                                <div class="sppm-video-duration-badge">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12,6 12,12 16,14"></polyline>
                                    </svg>
                                    <?php echo esc_html($video_duration); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Video Type Badge -->
                        <?php if ($video_type === 'youtube'): ?>
                        <div class="sppm-video-platform-badge youtube">
                            <svg width="20" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </div>
                        <?php elseif ($video_type === 'vimeo'): ?>
                        <div class="sppm-video-platform-badge vimeo">
                            <svg width="20" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.977 6.416c-.105 2.338-1.739 5.543-4.894 9.609-3.268 4.247-6.026 6.37-8.29 6.37-1.409 0-2.578-1.294-3.553-3.881L5.322 11.4C4.603 8.816 3.834 7.522 3.01 7.522c-.179 0-.806.378-1.881 1.132L0 7.197c1.185-1.044 2.351-2.084 3.501-3.128C5.08 2.701 6.266 1.984 7.055 1.91c1.867-.18 3.016 1.1 3.447 3.838.465 2.953.789 4.789.971 5.507.539 2.45 1.131 3.674 1.776 3.674.502 0 1.256-.796 2.265-2.385 1.004-1.589 1.54-2.797 1.612-3.628.144-1.371-.395-2.061-1.614-2.061-.574 0-1.167.121-1.777.391 1.186-3.868 3.434-5.757 6.762-5.637 2.473.06 3.628 1.664 3.493 4.797l-.013.01z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Hidden iframe for embed -->
                    <iframe class="sppm-video-iframe-premium" 
                            src="" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            allowfullscreen 
                            aria-label="<?php echo esc_attr($video_title); ?>"
                            style="display:none;"></iframe>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
