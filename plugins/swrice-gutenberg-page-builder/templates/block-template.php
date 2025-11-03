<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Extract all attributes with defaults
$plugin_name = isset($attributes['pluginName']) ? $attributes['pluginName'] : 'My Awesome Plugin';
$hero_subtitle = isset($attributes['heroSubtitle']) ? $attributes['heroSubtitle'] : '';
$plugin_price = isset($attributes['pluginPrice']) ? $attributes['pluginPrice'] : '';
$plugin_original_price = isset($attributes['pluginOriginalPrice']) ? $attributes['pluginOriginalPrice'] : '';
$buy_now_shortcode = isset($attributes['buyNowShortcode']) ? $attributes['buyNowShortcode'] : '';
$demo_link = isset($attributes['demoLink']) ? $attributes['demoLink'] : '';
$hero_image_url = isset($attributes['heroImageUrl']) ? $attributes['heroImageUrl'] : '';

// Section management
$section_order = isset($attributes['sectionOrder']) ? $attributes['sectionOrder'] : array('problem', 'solution', 'how_it_works', 'features', 'testimonials', 'faq', 'bonuses', 'guarantee', 'why_choose', 'about', 'final_cta');
$section_enabled = isset($attributes['sectionEnabled']) ? $attributes['sectionEnabled'] : array();

// Section headings and content
$problem_heading = isset($attributes['problemHeading']) ? $attributes['problemHeading'] : '';
$problem_icon = isset($attributes['problemIcon']) ? $attributes['problemIcon'] : '';
$problem_items = isset($attributes['problemItems']) ? $attributes['problemItems'] : array();

$solution_heading = isset($attributes['solutionHeading']) ? $attributes['solutionHeading'] : '';
$solution_icon = isset($attributes['solutionIcon']) ? $attributes['solutionIcon'] : '';
$solution_description = isset($attributes['solutionDescription']) ? $attributes['solutionDescription'] : '';

$how_it_works_heading = isset($attributes['howItWorksHeading']) ? $attributes['howItWorksHeading'] : '';
$how_it_works_icon = isset($attributes['howItWorksIcon']) ? $attributes['howItWorksIcon'] : '';
$steps_items = isset($attributes['stepsItems']) ? $attributes['stepsItems'] : array();

$features_heading = isset($attributes['featuresHeading']) ? $attributes['featuresHeading'] : '';
$features_icon = isset($attributes['featuresIcon']) ? $attributes['featuresIcon'] : '';
$feature_items = isset($attributes['featureItems']) ? $attributes['featureItems'] : array();

$testimonials_heading = isset($attributes['testimonialsHeading']) ? $attributes['testimonialsHeading'] : '';
$testimonials_icon = isset($attributes['testimonialsIcon']) ? $attributes['testimonialsIcon'] : '';
$testimonial_items = isset($attributes['testimonialItems']) ? $attributes['testimonialItems'] : array();

$faq_heading = isset($attributes['faqHeading']) ? $attributes['faqHeading'] : '';
$faq_icon = isset($attributes['faqIcon']) ? $attributes['faqIcon'] : '';
$faq_items = isset($attributes['faqItems']) ? $attributes['faqItems'] : array();

$bonuses_heading = isset($attributes['bonusesHeading']) ? $attributes['bonusesHeading'] : '';
$bonuses_icon = isset($attributes['bonusesIcon']) ? $attributes['bonusesIcon'] : '';
$bonus_items = isset($attributes['bonusItems']) ? $attributes['bonusItems'] : array();

$guarantee_heading = isset($attributes['guaranteeHeading']) ? $attributes['guaranteeHeading'] : '';
$guarantee_icon = isset($attributes['guaranteeIcon']) ? $attributes['guaranteeIcon'] : '';
$guarantee_text = isset($attributes['guaranteeText']) ? $attributes['guaranteeText'] : '';
$guarantee_points = isset($attributes['guaranteePoints']) ? $attributes['guaranteePoints'] : array();

$why_choose_heading = isset($attributes['whyChooseHeading']) ? $attributes['whyChooseHeading'] : '';
$why_choose_icon = isset($attributes['whyChooseIcon']) ? $attributes['whyChooseIcon'] : '';
$why_choose_items = isset($attributes['whyChooseItems']) ? $attributes['whyChooseItems'] : array();

$about_heading = isset($attributes['aboutHeading']) ? $attributes['aboutHeading'] : '';
$about_icon = isset($attributes['aboutIcon']) ? $attributes['aboutIcon'] : '';
$about_description = isset($attributes['aboutDescription']) ? $attributes['aboutDescription'] : '';

$cta_title = isset($attributes['ctaTitle']) ? $attributes['ctaTitle'] : '';
$cta_subtitle = isset($attributes['ctaSubtitle']) ? $attributes['ctaSubtitle'] : '';
$final_cta_heading = isset($attributes['finalCtaHeading']) ? $attributes['finalCtaHeading'] : '';
$final_cta_icon = isset($attributes['finalCtaIcon']) ? $attributes['finalCtaIcon'] : '';

// Function to render stars for ratings
if (!function_exists('render_stars')) {
    function render_stars($rating) {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= $i <= $rating ? '⭐' : '☆';
        }
        return $stars;
    }
}

// Section rendering functions (copied from original plugin)
if (!function_exists('render_problem_section')) {
    function render_problem_section($problem_items, $problem_heading, $problem_icon) {
    if (empty($problem_items) || !is_array($problem_items)) return;
    ?>
    <section class="sppm-section sppm-problem-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($problem_icon): ?><span class="sppm-section-icon"><?php echo $problem_icon; ?></span><?php endif; ?>
                <?php echo esc_html($problem_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-problem-grid">
            <?php if (is_array($problem_items) && !empty($problem_items)): ?>
                <?php foreach ($problem_items as $problem): ?>
                <div class="sppm-problem-card">
                    <?php if (!empty($problem['icon'])): ?>
                    <div class="sppm-problem-icon"><?php echo $problem['icon']; ?></div>
                    <?php endif; ?>
                    <h3 class="sppm-problem-title"><?php echo esc_html($problem['title']); ?></h3>
                    <p class="sppm-problem-desc"><?php echo esc_html($problem['description']); ?></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_solution_section')) {
    function render_solution_section($solution_heading, $solution_description, $solution_icon) {
    if (empty($solution_heading) && empty($solution_description)) return;
    ?>
    <section class="sppm-section sppm-solution-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($solution_icon): ?><span class="sppm-section-icon"><?php echo $solution_icon; ?></span><?php endif; ?>
                <?php echo esc_html($solution_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-solution-content">
            <p><?php echo esc_html($solution_description); ?></p>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_how_it_works_section')) {
    function render_how_it_works_section($steps_items, $how_it_works_heading, $how_it_works_icon) {
    if (empty($steps_items) || !is_array($steps_items)) return;
    ?>
    <section class="sppm-section sppm-how-it-works-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($how_it_works_icon): ?><span class="sppm-section-icon"><?php echo $how_it_works_icon; ?></span><?php endif; ?>
                <?php echo esc_html($how_it_works_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-steps-grid">
            <?php if (is_array($steps_items) && !empty($steps_items)): ?>
                <?php foreach ($steps_items as $index => $step): ?>
                <div class="sppm-step-card">
                    <div class="sppm-step-number"><?php echo ($index + 1); ?></div>
                    <h3 class="sppm-step-title"><?php echo esc_html($step['title']); ?></h3>
                    <p class="sppm-step-desc"><?php echo esc_html($step['description']); ?></p>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_features_section')) {
    function render_features_section($feature_items, $features_heading, $features_icon) {
    if (empty($feature_items) || !is_array($feature_items)) return;
    ?>
    <section class="sppm-section sppm-features-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($features_icon): ?><span class="sppm-section-icon"><?php echo $features_icon; ?></span><?php endif; ?>
                <?php echo esc_html($features_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-features-grid">
            <?php if (is_array($feature_items) && !empty($feature_items)): ?>
                <?php foreach ($feature_items as $feature): ?>
                <div class="sppm-feature-card">
                    <div class="sppm-feature-card-header">
                        <?php if (!empty($feature['icon'])): ?>
                        <div class="sppm-feature-icon"><?php echo $feature['icon']; ?></div>
                        <?php endif; ?>
                        <h3 class="sppm-feature-title"><?php echo esc_html($feature['title']); ?></h3>
                    </div>
                    <div class="sppm-feature-card-body">
                        <p class="sppm-feature-desc"><?php echo esc_html($feature['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_testimonials_section')) {
    function render_testimonials_section($testimonial_items, $testimonials_heading, $testimonials_icon) {
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
    <?php
    }
}

if (!function_exists('render_faq_section')) {
    function render_faq_section($faq_items, $faq_heading, $faq_icon) {
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
    <?php
    }
}

if (!function_exists('render_bonuses_section')) {
    function render_bonuses_section($bonus_items, $bonuses_heading, $bonuses_icon) {
    if (empty($bonus_items) || !is_array($bonus_items)) return;
    ?>
    <section class="sppm-section sppm-bonuses-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($bonuses_icon): ?><span class="sppm-section-icon"><?php echo $bonuses_icon; ?></span><?php endif; ?>
                <?php echo esc_html($bonuses_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-bonuses-grid">
            <?php foreach ($bonus_items as $bonus): ?>
            <div class="sppm-bonus-card">
                <?php if (!empty($bonus['icon'])): ?>
                <div class="sppm-bonus-icon"><?php echo $bonus['icon']; ?></div>
                <?php endif; ?>
                <h3 class="sppm-bonus-title"><?php echo esc_html($bonus['title']); ?></h3>
                <?php if (!empty($bonus['value'])): ?>
                <div class="sppm-bonus-value">Value: <?php echo esc_html($bonus['value']); ?></div>
                <?php endif; ?>
                <p class="sppm-bonus-desc"><?php echo esc_html($bonus['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_guarantee_section')) {
    function render_guarantee_section($guarantee_text, $guarantee_heading, $guarantee_icon, $guarantee_points = array()) {
    if (empty($guarantee_text) && empty($guarantee_heading)) return;
    ?>
    <section class="sppm-section sppm-guarantee-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($guarantee_icon): ?><span class="sppm-section-icon"><?php echo $guarantee_icon; ?></span><?php endif; ?>
                <?php echo esc_html($guarantee_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-guarantee-content">
            <p class="sppm-guarantee-text"><?php echo esc_html($guarantee_text); ?></p>
            
            <?php if (is_array($guarantee_points) && !empty($guarantee_points)): ?>
            <div class="sppm-guarantee-points">
                <?php foreach ($guarantee_points as $point): ?>
                <div class="sppm-guarantee-point">
                    <span class="sppm-guarantee-check">✅</span>
                    <?php echo esc_html($point['point']); ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_why_choose_section')) {
    function render_why_choose_section($why_choose_items, $why_choose_heading, $why_choose_icon) {
    if (empty($why_choose_items) || !is_array($why_choose_items)) return;
    ?>
    <section class="sppm-section sppm-why-choose-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($why_choose_icon): ?><span class="sppm-section-icon"><?php echo $why_choose_icon; ?></span><?php endif; ?>
                <?php echo esc_html($why_choose_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-why-choose-grid">
            <?php foreach ($why_choose_items as $item): ?>
            <div class="sppm-why-choose-card">
                <?php if (!empty($item['icon'])): ?>
                <div class="sppm-why-choose-icon"><?php echo $item['icon']; ?></div>
                <?php endif; ?>
                <h3 class="sppm-why-choose-title"><?php echo esc_html($item['title']); ?></h3>
                <p class="sppm-why-choose-desc"><?php echo esc_html($item['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_about_section')) {
    function render_about_section($about_description, $about_heading, $about_icon) {
    if (empty($about_description) && empty($about_heading)) return;
    ?>
    <section class="sppm-section sppm-about-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($about_icon): ?><span class="sppm-section-icon"><?php echo $about_icon; ?></span><?php endif; ?>
                <?php echo esc_html($about_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-about-content">
            <p><?php echo esc_html($about_description); ?></p>
        </div>
    </section>
    <?php
    }
}

if (!function_exists('render_final_cta_section')) {
    function render_final_cta_section($cta_title, $cta_subtitle, $final_cta_heading, $final_cta_icon, $buy_now_shortcode) {
    if (empty($cta_title) && empty($final_cta_heading)) return;
    ?>
    <section class="sppm-section sppm-final-cta-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($final_cta_icon): ?><span class="sppm-section-icon"><?php echo $final_cta_icon; ?></span><?php endif; ?>
                <?php echo esc_html($final_cta_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-cta-content">
            <h3 class="sppm-cta-title"><?php echo esc_html($cta_title); ?></h3>
            <p class="sppm-cta-subtitle"><?php echo esc_html($cta_subtitle); ?></p>
            
            <?php if (!empty($buy_now_shortcode)): ?>
            <div class="sppm-cta-buttons">
                <?php echo do_shortcode($buy_now_shortcode); ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php
    }
}
?>

<div class="sppm-plugin-page">
    <div class="sppm-container">
        
        <!-- Hero Section -->
        <section class="sppm-hero">
            <div class="sppm-hero-left">
                <div class="sppm-logo-row">
                    <div class="sppm-logo-mark">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L2 7L12 12L22 7L12 2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M2 17L12 22L22 17" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M2 12L12 17L22 12" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="sppm-logo-text"><?php echo esc_html($plugin_name); ?></div>
                </div>
                
                <div class="sppm-rating">
                    <div class="sppm-rating-stars">⭐⭐⭐⭐⭐</div>
                    <span>5.0 (1,200+ reviews)</span>
                </div>
                
                <h1 class="sppm-hero-title"><?php echo esc_html($plugin_name); ?></h1>
                
                <?php if (!empty($hero_subtitle)): ?>
                <p class="sppm-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
                
                <div class="sppm-pricing">
                    <?php if (!empty($plugin_original_price)): ?>
                    <span class="sppm-original-price">$<?php echo esc_html($plugin_original_price); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($plugin_price)): ?>
                    <span class="sppm-current-price">$<?php echo esc_html($plugin_price); ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="sppm-hero-buttons">
                    <?php if (!empty($buy_now_shortcode)): ?>
                    <?php echo do_shortcode($buy_now_shortcode); ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($demo_link)): ?>
                    <a href="<?php echo esc_url($demo_link); ?>" class="sppm-demo-btn" target="_blank">Live Demo</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (!empty($hero_image_url)): ?>
            <div class="sppm-hero-right">
                <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($plugin_name); ?>" class="sppm-hero-image" />
            </div>
            <?php endif; ?>
        </section>
        
        <!-- Dynamic Sections Based on Order -->
        <?php
        if (is_array($section_order)) {
            foreach ($section_order as $section) {
                // Check if section is enabled
                if (isset($section_enabled[$section]) && !$section_enabled[$section]) {
                    continue;
                }
                
                // Render section based on type
                switch ($section) {
                    case 'problem':
                        render_problem_section($problem_items, $problem_heading, $problem_icon);
                        break;
                    case 'solution':
                        render_solution_section($solution_heading, $solution_description, $solution_icon);
                        break;
                    case 'how_it_works':
                        render_how_it_works_section($steps_items, $how_it_works_heading, $how_it_works_icon);
                        break;
                    case 'features':
                        render_features_section($feature_items, $features_heading, $features_icon);
                        break;
                    case 'testimonials':
                        render_testimonials_section($testimonial_items, $testimonials_heading, $testimonials_icon);
                        break;
                    case 'faq':
                        render_faq_section($faq_items, $faq_heading, $faq_icon);
                        break;
                    case 'bonuses':
                        render_bonuses_section($bonus_items, $bonuses_heading, $bonuses_icon);
                        break;
                    case 'guarantee':
                        render_guarantee_section($guarantee_text, $guarantee_heading, $guarantee_icon, $guarantee_points);
                        break;
                    case 'why_choose':
                        render_why_choose_section($why_choose_items, $why_choose_heading, $why_choose_icon);
                        break;
                    case 'about':
                        render_about_section($about_description, $about_heading, $about_icon);
                        break;
                    case 'final_cta':
                        render_final_cta_section($cta_title, $cta_subtitle, $final_cta_heading, $final_cta_icon, $buy_now_shortcode);
                        break;
                }
            }
        }
        ?>
        
    </div>
</div>
