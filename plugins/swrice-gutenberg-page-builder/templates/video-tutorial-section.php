<?php
/**
 * Video Tutorial Section Template - Hero-Style Layout
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
?>

<section class="sppm-section sppm-video-tutorial-section">
    <!-- Hero-Style Layout: Content Left, Video Right -->
    <div class="sppm-hero">
        <!-- Left Side: Content -->
        <div class="sppm-hero-left">
            <h2 class="sppm-hero-title">
                <?php if ($video_icon): ?><span class="sppm-section-icon"><?php echo $video_icon; ?></span><?php endif; ?>
                <?php echo esc_html($video_heading); ?>
            </h2>
            <?php if ($video_description): ?>
            <p class="sppm-hero-subtitle"><?php echo esc_html($video_description); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Right Side: Video -->
        <div class="sppm-hero-right">
            <div class="sppm-video-wrapper">
                <?php if ($video_type === 'direct'): ?>
                    <!-- Direct Video -->
                    <video class="sppm-video-player" controls poster="<?php echo esc_url($video_thumbnail); ?>">
                        <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <!-- Embedded Video with Custom Play Button -->
                    <div class="sppm-video-embed" id="video-embed">
                        <div class="sppm-video-thumbnail" onclick="loadVideo()">
                            <?php if ($video_thumbnail): ?>
                            <img src="<?php echo esc_url($video_thumbnail); ?>" alt="<?php echo esc_attr($video_title); ?>">
                            <?php endif; ?>
                            <div class="sppm-play-button">
                                <div class="sppm-play-icon">▶</div>
                            </div>
                            <?php if ($video_duration): ?>
                            <div class="sppm-video-duration"><?php echo esc_html($video_duration); ?></div>
                            <?php endif; ?>
                        </div>
                        <iframe id="video-iframe" 
                                src="" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                style="display: none;"></iframe>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function loadVideo() {
    const embedContainer = document.getElementById('video-embed');
    const thumbnail = embedContainer.querySelector('.sppm-video-thumbnail');
    const iframe = document.getElementById('video-iframe');
    
    // Set the iframe source and show it
    iframe.src = '<?php echo esc_js($embed_url); ?>?autoplay=1';
    iframe.style.display = 'block';
    
    // Hide the thumbnail
    thumbnail.style.display = 'none';
}
</script>

<style>
/* Video Tutorial Section - Exact Hero Section Match */
.sppm-video-wrapper {
    position: relative;
    width: 100%;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(29,42,63,0.06);
}

.sppm-video-embed {
    position: relative;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
}

.sppm-video-thumbnail {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #000;
}

.sppm-video-thumbnail img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.sppm-play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.sppm-play-button:hover {
    background: rgba(255, 255, 255, 1);
    transform: translate(-50%, -50%) scale(1.1);
}

.sppm-play-icon {
    font-size: 32px;
    color: #5fa0d8;
    margin-left: 4px; /* Slight offset for visual centering */
}

.sppm-video-duration {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
}

#video-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.sppm-video-player {
    width: 100%;
    height: auto;
    max-height: 500px;
}

@media (max-width: 768px) {
    .sppm-play-button {
        width: 60px;
        height: 60px;
    }
    
    .sppm-play-icon {
        font-size: 24px;
    }
    
    .sppm-video-duration {
        bottom: 10px;
        right: 10px;
        padding: 4px 8px;
        font-size: 12px;
    }
}
</style>
