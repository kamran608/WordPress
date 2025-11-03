<?php
/**
 * FAQ Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$faq_heading = isset($attributes['faqHeading']) ? $attributes['faqHeading'] : 'FAQ';
$faq_icon = isset($attributes['faqIcon']) ? $attributes['faqIcon'] : '❓';
$faq_items = isset($attributes['faqItems']) ? $attributes['faqItems'] : array();

if (empty($faq_items) || !is_array($faq_items)) return;
?>

<section class="sppm-section sppm-faq-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($faq_icon): ?><span class="sppm-section-icon"><?php echo $faq_icon; ?></span><?php endif; ?>
            <?php echo esc_html($faq_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-faq-list">
        <?php foreach ($faq_items as $index => $faq): ?>
        <div class="sppm-faq-item" data-faq="<?php echo $index; ?>">
            <div class="sppm-faq-question">
                <?php echo esc_html($faq['question']); ?>
                <span>+</span>
            </div>
            <div class="sppm-faq-answer"><?php echo esc_html($faq['answer']); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
