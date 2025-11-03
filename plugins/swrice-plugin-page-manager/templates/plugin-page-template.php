<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$all_meta = get_post_meta($post->ID);
$plugin_name = $post->post_title;
$hero_subtitle = isset($all_meta['hero_subtitle'][0]) ? $all_meta['hero_subtitle'][0] : '';
$plugin_price = isset($all_meta['plugin_price'][0]) ? $all_meta['plugin_price'][0] : '';
$plugin_original_price = isset($all_meta['plugin_original_price'][0]) ? $all_meta['plugin_original_price'][0] : '';
$buy_now_shortcode = isset($all_meta['buy_now_shortcode'][0]) ? $all_meta['buy_now_shortcode'][0] : '';

$hero_image = get_the_post_thumbnail_url($post->ID, 'large');

$problem_heading = isset($all_meta['problem_heading'][0]) ? $all_meta['problem_heading'][0] : '';
$problem_icon = isset($all_meta['problem_icon'][0]) ? $all_meta['problem_icon'][0] : '';
$problem_items = isset($all_meta['problem_items'][0]) ? maybe_unserialize($all_meta['problem_items'][0]) : array();

$solution_heading = isset($all_meta['solution_heading'][0]) ? $all_meta['solution_heading'][0] : '';
$solution_icon = isset($all_meta['solution_icon'][0]) ? $all_meta['solution_icon'][0] : '';
$solution_description = isset($all_meta['solution_description'][0]) ? $all_meta['solution_description'][0] : '';

$how_it_works_heading = isset($all_meta['how_it_works_heading'][0]) ? $all_meta['how_it_works_heading'][0] : '';
$how_it_works_icon = isset($all_meta['how_it_works_icon'][0]) ? $all_meta['how_it_works_icon'][0] : '';
$steps_items = isset($all_meta['steps_items'][0]) ? maybe_unserialize($all_meta['steps_items'][0]) : array();

$features_heading = isset($all_meta['features_heading'][0]) ? $all_meta['features_heading'][0] : '';
$features_icon = isset($all_meta['features_icon'][0]) ? $all_meta['features_icon'][0] : '';
$feature_items = isset($all_meta['feature_items'][0]) ? maybe_unserialize($all_meta['feature_items'][0]) : array();

$testimonials_heading = isset($all_meta['testimonials_heading'][0]) ? $all_meta['testimonials_heading'][0] : '';
$testimonials_icon = isset($all_meta['testimonials_icon'][0]) ? $all_meta['testimonials_icon'][0] : '';
$testimonial_items = isset($all_meta['testimonial_items'][0]) ? maybe_unserialize($all_meta['testimonial_items'][0]) : array();

$faq_heading = isset($all_meta['faq_heading'][0]) ? $all_meta['faq_heading'][0] : '';
$faq_icon = isset($all_meta['faq_icon'][0]) ? $all_meta['faq_icon'][0] : '';
$faq_items = isset($all_meta['faq_items'][0]) ? maybe_unserialize($all_meta['faq_items'][0]) : array();

$bonuses_heading = isset($all_meta['bonuses_heading'][0]) ? $all_meta['bonuses_heading'][0] : '';
$bonuses_icon = isset($all_meta['bonuses_icon'][0]) ? $all_meta['bonuses_icon'][0] : '';
$bonus_items = isset($all_meta['bonus_items'][0]) ? maybe_unserialize($all_meta['bonus_items'][0]) : array();

$guarantee_heading = isset($all_meta['guarantee_heading'][0]) ? $all_meta['guarantee_heading'][0] : '';
$guarantee_icon = isset($all_meta['guarantee_icon'][0]) ? $all_meta['guarantee_icon'][0] : '';
$guarantee_text = isset($all_meta['guarantee_text'][0]) ? $all_meta['guarantee_text'][0] : '';
$guarantee_points = isset($all_meta['guarantee_points'][0]) ? maybe_unserialize($all_meta['guarantee_points'][0]) : array();

$why_choose_heading = isset($all_meta['why_choose_heading'][0]) ? $all_meta['why_choose_heading'][0] : '';
$why_choose_icon = isset($all_meta['why_choose_icon'][0]) ? $all_meta['why_choose_icon'][0] : '';
$why_choose_items = isset($all_meta['why_choose_items'][0]) ? maybe_unserialize($all_meta['why_choose_items'][0]) : array();

$about_heading = isset($all_meta['about_heading'][0]) ? $all_meta['about_heading'][0] : '';
$about_icon = isset($all_meta['about_icon'][0]) ? $all_meta['about_icon'][0] : '';
$about_description = isset($all_meta['about_description'][0]) ? $all_meta['about_description'][0] : '';
$about_section = isset($all_meta['about_section'][0]) ? $all_meta['about_section'][0] : '';

$cta_title = isset($all_meta['cta_title'][0]) ? $all_meta['cta_title'][0] : '';
$cta_subtitle = isset($all_meta['cta_subtitle'][0]) ? $all_meta['cta_subtitle'][0] : '';
$demo_link = isset($all_meta['demo_link'][0]) ? $all_meta['demo_link'][0] : '';
$final_cta_heading = isset($all_meta['final_cta_heading'][0]) ? $all_meta['final_cta_heading'][0] : '';
$final_cta_icon = isset($all_meta['final_cta_icon'][0]) ? $all_meta['final_cta_icon'][0] : '';

// CRITICAL: Section ordering and enabling logic
$section_order = get_post_meta($post->ID, 'section_order', true);
$section_enabled = get_post_meta($post->ID, 'section_enabled', true);

if (!is_array($section_order) || empty($section_order)) {
    $section_order = array('problem', 'solution', 'how_it_works', 'features', 'testimonials', 'faq', 'bonuses', 'guarantee', 'why_choose', 'about', 'final_cta');
}
if (!is_array($section_enabled)) {
    $section_enabled = array_fill_keys($section_order, true);
}

// Function to render stars for ratings
function render_stars($rating) {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '⭐' : '☆';
    }
    return $stars;
}

// PHP-based section rendering functions
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
            <?php foreach ($why_choose_items as $benefit): ?>
            <div class="sppm-benefit-card">
                <?php if (!empty($benefit['icon'])): ?>
                <div class="sppm-benefit-icon"><?php echo $benefit['icon']; ?></div>
                <?php endif; ?>
                <h3 class="sppm-benefit-title"><?php echo esc_html($benefit['title']); ?></h3>
                <p class="sppm-benefit-desc"><?php echo esc_html($benefit['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

function render_about_section($about_section, $about_heading, $about_icon) {
    if (empty($about_section)) return;
    ?>
    <section class="sppm-section sppm-about-section">
        <div class="sppm-section-header">
            <h2 class="sppm-section-title">
                <?php if ($about_icon): ?><span class="sppm-section-icon"><?php echo $about_icon; ?></span><?php endif; ?>
                <?php echo esc_html($about_heading); ?>
            </h2>
        </div>
        
        <div class="sppm-about-content">
            <p><?php echo esc_html($about_section); ?></p>
        </div>
    </section>
    <?php
}

function render_final_cta_section($buy_now_shortcode, $final_cta_heading, $final_cta_icon, $cta_title = '', $cta_subtitle = '', $plugin_price = '', $demo_link = '') {
    // Show section if any content exists
    if (empty($cta_title) && empty($cta_subtitle) && empty($buy_now_shortcode) && empty($demo_link)) return;
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
    <?php
}
?>

<div class="sppm-plugin-page">
    <div class="sppm-container">
        
        <!-- HERO SECTION - FULLY DYNAMIC -->
        <section class="sppm-hero">
            <div class="sppm-hero-left">
                <div class="sppm-logo-row">
                    <div class="sppm-logo-mark">
                        <svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="20" height="4" rx="2" fill="#5fa0d8"/>
                            <rect x="2" y="10" width="16" height="4" rx="2" fill="#82bfe4"/>
                            <rect x="2" y="18" width="12" height="4" rx="2" fill="#bcdff6"/>
                        </svg>
                    </div>
                    <div class="sppm-logo-text">
                        <?php echo esc_html($plugin_name); ?>
                    </div>
                </div>

                <div class="sppm-rating">
                    <div class="sppm-rating-stars">★ ★ ★ ★ ★</div>
                    <div>5.0</div>
                </div>

                <h1 class="sppm-hero-title"><?php echo esc_html($plugin_name); ?></h1>
                <p class="sppm-hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>

                <div class="sppm-hero-ctas">
                    <?php if ($buy_now_shortcode): ?>
                        <?php echo do_shortcode($buy_now_shortcode); ?>
                    <?php else: ?>
                        <button class="sppm-btn sppm-btn-primary">Buy Now - $<?php echo esc_html($plugin_price); ?></button>
                    <?php endif; ?>
                    <?php if (!empty($demo_link) && $demo_link !== '#'): ?>
                        <a href="<?php echo esc_url($demo_link); ?>" class="sppm-btn sppm-btn-ghost" target="_blank">Live Demo</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="sppm-hero-right">
                <?php if ($hero_image): ?>
                    <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($plugin_name); ?>" class="sppm-hero-image" />
                <?php else: ?>
                    <div class="sppm-device">
                        <div class="sppm-device-inner">
                            <h3>Plugin Preview</h3>
                            <div class="sppm-section-row">Getting Started <span>▾</span></div>
                            <div class="sppm-section-row">Configuration <span>▾</span></div>
                            <div class="sppm-section-row">Advanced Features <span>▾</span></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
        // DYNAMIC SECTION RENDERING - Render sections in admin-specified order
        foreach ($section_order as $section_key) {
            // Only render if section is enabled
            if (!isset($section_enabled[$section_key]) || !$section_enabled[$section_key]) {
                continue;
            }
            
            switch ($section_key) {
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
                    render_about_section($about_section, $about_heading, $about_icon);
                    break;
                case 'final_cta':
                    render_final_cta_section($buy_now_shortcode, $final_cta_heading, $final_cta_icon, $cta_title, $cta_subtitle, $plugin_price, $demo_link);
                    break;
            }
        }
        ?>

    </div>
</div>
