<?php
/**
 * Testimonials Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

// Include render_stars function if not already defined
if (!function_exists('render_stars')) {
    function render_stars($rating) {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $rating ? '⭐' : '☆';
        }
        return $stars;
    }
}

$testimonials_heading = isset($attributes['testimonialsHeading']) ? $attributes['testimonialsHeading'] : 'Testimonials';
$testimonials_icon = isset($attributes['testimonialsIcon']) ? $attributes['testimonialsIcon'] : '💬';
$testimonial_items = isset($attributes['testimonialItems']) ? $attributes['testimonialItems'] : array();

if (empty($testimonial_items) || !is_array($testimonial_items)) return;
?>

<section class="sppm-section sppm-testimonials-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($testimonials_icon): ?><span class="sppm-section-icon"><?php echo $testimonials_icon; ?></span><?php endif; ?>
            <?php echo esc_html($testimonials_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-testimonials-grid">
        <?php foreach ($testimonial_items as $testimonial): ?>
        <div class="sppm-testimonial-card">
            <div class="sppm-testimonial-rating">
                <?php echo render_stars(intval($testimonial['rating'])); ?>
            </div>
            <div class="sppm-testimonial-content">"<?php echo esc_html($testimonial['content']); ?>"</div>
            <div class="sppm-testimonial-author">
                <strong><?php echo esc_html($testimonial['name']); ?></strong>
                <span><?php echo esc_html($testimonial['title']); ?></span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
