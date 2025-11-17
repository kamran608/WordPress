<?php
/**
 * Video Tutorial Section Template - Clean Hero-Style Layout
 */

if (!defined('ABSPATH')) exit;

$video_heading = isset($attributes['videoTutorialHeading']) ? $attributes['videoTutorialHeading'] : 'Video Tutorial';
$video_icon = isset($attributes['videoTutorialIcon']) ? $attributes['videoTutorialIcon'] : '🎥';
$video_description = isset($attributes['videoTutorialDescription']) ? $attributes['videoTutorialDescription'] : '';
$video_url = isset($attributes['videoUrl']) ? $attributes['videoUrl'] : '';
$video_title = isset($attributes['videoTitle']) ? $attributes['videoTitle'] : 'Plugin Tutorial';
$video_duration = isset($attributes['videoDuration']) ? $attributes['videoDuration'] : '';
$video_thumbnail = isset($attributes['videoThumbnailUrl']) ? $attributes['videoThumbnailUrl'] : '';

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

$vid_container_id = uniqid('sppm-vid-');
?>

<section class="sppm-section sppm-video-tutorial-section">
    <div class="sppm-hero">
        <div class="sppm-hero-left">
            <h2 class="sppm-hero-title">
                <?php if ($video_icon): ?><span class="sppm-section-icon"><?php echo $video_icon; ?></span><?php endif; ?>
                <?php echo esc_html($video_heading); ?>
            </h2>
            <?php if ($video_description): ?>
            <p class="sppm-hero-subtitle"><?php echo esc_html($video_description); ?></p>
            <?php endif; ?>
        </div>

        <div class="sppm-hero-right">
            <div class="sppm-video-wrapper">
                <?php if ($video_type === 'direct'): ?>
                    <video class="sppm-video-player" controls poster="<?php echo esc_url($video_thumbnail); ?>" aria-label="<?php echo esc_attr($video_title); ?>">
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <div class="sppm-video-embed" id="<?php echo esc_attr($vid_container_id); ?>" data-embed-url="<?php echo esc_url($embed_url); ?>">
                        <div class="sppm-video-thumbnail">
                            <?php if ($video_thumbnail): ?>
                                <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php echo esc_attr($video_title); ?>">
                            <?php endif; ?>
                            <button class="sppm-play-button" type="button" aria-label="Play video">
                                <span class="sppm-play-icon">▶</span>
                            </button>
                            <?php if ($video_duration): ?>
                            <div class="sppm-video-duration"><?php echo esc_html($video_duration); ?></div>
                            <?php endif; ?>
                        </div>
                        <iframe class="sppm-video-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen aria-label="<?php echo esc_attr($video_title); ?>" style="display:none;"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

