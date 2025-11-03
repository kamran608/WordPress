<?php
/**
 * Final CTA Section Template
 */

if (!defined('ABSPATH')) exit;

$final_cta_heading = isset($attributes['finalCtaHeading']) ? $attributes['finalCtaHeading'] : 'Ready to Get Started?';
$final_cta_icon = isset($attributes['finalCtaIcon']) ? $attributes['finalCtaIcon'] : '🚀';
$cta_title = isset($attributes['ctaTitle']) ? $attributes['ctaTitle'] : 'Get Started Today';
$cta_subtitle = isset($attributes['ctaSubtitle']) ? $attributes['ctaSubtitle'] : 'Join thousands of satisfied customers';
$buy_now_shortcode = isset($attributes['buyNowShortcode']) ? $attributes['buyNowShortcode'] : '';
$demo_link = isset($attributes['demoLink']) ? $attributes['demoLink'] : '';
$plugin_price = isset($attributes['pluginPrice']) ? $attributes['pluginPrice'] : '29';

if (empty($cta_title) && empty($final_cta_heading)) return;
?>

<section class="sppm-section sppm-final-cta">
    <div class="sppm-cta">
        <div class="sppm-cta-content">
            <?php if (!empty($final_cta_heading)): ?>
            <h2 class="sppm-section-title">
                <?php if ($final_cta_icon): ?><span class="sppm-section-icon"><?php echo $final_cta_icon; ?></span><?php endif; ?>
                <?php echo esc_html($final_cta_heading); ?>
            </h2>
            <?php endif; ?>
            <?php if (!empty($cta_title)): ?>
            <h3 class="sppm-cta-title"><?php echo esc_html($cta_title); ?></h3>
            <?php endif; ?>
            <?php if (!empty($cta_subtitle)): ?>
            <p class="sppm-cta-subtitle"><?php echo esc_html($cta_subtitle); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="sppm-cta-buttons">
            <?php if ($buy_now_shortcode): ?>
                <?php echo do_shortcode($buy_now_shortcode); ?>
            <?php elseif (!empty($plugin_price)): ?>
                <button class="sppm-btn sppm-btn-primary">Buy Now - $<?php echo esc_html($plugin_price); ?></button>
            <?php endif; ?>
            <?php if (!empty($demo_link) && $demo_link !== '#'): ?>
            <a href="<?php echo esc_url($demo_link); ?>" class="sppm-btn sppm-btn-ghost" target="_blank">Live Demo</a>
            <?php endif; ?>
        </div>
    </div>
</section>
