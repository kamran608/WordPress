<?php
/**
 * Plugin Name: Swrice Plugin Sell Page Builder
 * Plugin URI: https://swrice.com
 * Description: Modern Gutenberg blocks for creating professional plugin landing pages. Individual blocks for each section with complete customization.
 * Version: 2.0.0
 * Author: Swrice
 * License: GPL v2 or later
 * Text Domain: swrice-gutenberg-page-builder
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SGPB_VERSION', '2.0.0');
define('SGPB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SGPB_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class Swrice_Gutenberg_Page_Builder {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
        add_filter('block_categories_all', array($this, 'add_block_categories'), 10, 2);
        
        // Add Yoast SEO integration
        add_filter('wpseo_pre_analysis_post_content', array($this, 'add_swrice_content_to_yoast'), 10, 2);
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Register blocks
        $this->register_blocks();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'swrice-plugin-page-builder-frontend',
            SGPB_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            SGPB_VERSION
        );
        
        wp_enqueue_script(
            'swrice-plugin-page-builder-frontend',
            SGPB_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            SGPB_VERSION,
            true
        );
        
        // Enqueue Yoast SEO integration script
        wp_enqueue_script(
            'swrice-yoast-seo-integration',
            SGPB_PLUGIN_URL . 'assets/js/yoast-seo-integration.js',
            array('jquery'),
            SGPB_VERSION,
            true
        );
    }
    
    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'swrice-plugin-page-builder-editor',
            SGPB_PLUGIN_URL . 'assets/css/editor.css',
            array(),
            SGPB_VERSION . '-' . time() // Cache busting for development
        );
        
        // Enqueue Yoast SEO integration script for editor
        wp_enqueue_script(
            'swrice-yoast-seo-integration-editor',
            SGPB_PLUGIN_URL . 'assets/js/yoast-seo-integration.js',
            array('wp-blocks', 'wp-element', 'wp-data'),
            SGPB_VERSION,
            true
        );
    }
    
    /**
     * Register all blocks
     */
    public function register_blocks() {
        // Register the blocks script
        wp_register_script(
            'swrice-plugin-page-builder-blocks',
            SGPB_PLUGIN_URL . 'assets/js/blocks.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
            SGPB_VERSION,
            true
        );
        
        // Register individual section blocks
        $this->register_hero_block();
        $this->register_problem_block();
        $this->register_solution_block();
        $this->register_how_it_works_block();
        $this->register_features_block();
        $this->register_testimonials_block();
        $this->register_faq_block();
        $this->register_bonuses_block();
        $this->register_guarantee_block();
        $this->register_why_choose_block();
        $this->register_about_block();
        $this->register_final_cta_block();
        $this->register_screenshots_block();
        $this->register_video_tutorial_block();
        $this->register_version_changelog_block();
    }
    
    /**
     * Register Hero Section Block
     */
    public function register_hero_block() {
        register_block_type('swrice/hero-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_hero_section'),
            'attributes' => array(
                'pluginName' => array('type' => 'string', 'default' => 'My Awesome Plugin'),
                'heroSubtitle' => array('type' => 'string', 'default' => 'Transform your WordPress experience'),
                'pluginPrice' => array('type' => 'string', 'default' => '49'),
                'pluginOriginalPrice' => array('type' => 'string', 'default' => '99'),
                'buyNowShortcode' => array('type' => 'string', 'default' => ''),
                'demoLink' => array('type' => 'string', 'default' => ''),
                'heroImageId' => array('type' => 'number', 'default' => 0),
                'heroImageUrl' => array('type' => 'string', 'default' => '')
            )
        ));
    }
    
    /**
     * Register Problem Section Block
     */
    public function register_problem_block() {
        register_block_type('swrice/problem-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_problem_section'),
            'attributes' => array(
                'problemHeading' => array('type' => 'string', 'default' => 'The Problem'),
                'problemIcon' => array('type' => 'string', 'default' => '⚠️'),
                'problemItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'title' => 'Problem 1',
                            'description' => 'Description of the problem',
                            'icon' => '❌'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register Solution Section Block
     */
    public function register_solution_block() {
        register_block_type('swrice/solution-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_solution_section'),
            'attributes' => array(
                'solutionHeading' => array('type' => 'string', 'default' => 'The Solution'),
                'solutionIcon' => array('type' => 'string', 'default' => '✅'),
                'solutionDescription' => array('type' => 'string', 'default' => 'Our plugin solves all your problems.')
            )
        ));
    }
    
    /**
     * Register Features Section Block
     */
    public function register_features_block() {
        register_block_type('swrice/features-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_features_section'),
            'attributes' => array(
                'featuresHeading' => array('type' => 'string', 'default' => 'Features'),
                'featuresIcon' => array('type' => 'string', 'default' => '🚀'),
                'featureItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'title' => 'Feature 1',
                            'description' => 'Description of the feature',
                            'icon' => '✨'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register FAQ Section Block
     */
    public function register_faq_block() {
        register_block_type('swrice/faq-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_faq_section'),
            'attributes' => array(
                'faqHeading' => array('type' => 'string', 'default' => 'FAQ'),
                'faqIcon' => array('type' => 'string', 'default' => '❓'),
                'faqItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'question' => 'How does it work?',
                            'answer' => 'It works great!'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register How It Works Section Block
     */
    public function register_how_it_works_block() {
        register_block_type('swrice/how-it-works-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_how_it_works_section'),
            'attributes' => array(
                'howItWorksHeading' => array('type' => 'string', 'default' => 'How It Works'),
                'howItWorksIcon' => array('type' => 'string', 'default' => '⚙️'),
                'stepsItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'title' => 'Step 1',
                            'description' => 'Description of the step'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register Testimonials Section Block
     */
    public function register_testimonials_block() {
        register_block_type('swrice/testimonials-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_testimonials_section'),
            'attributes' => array(
                'testimonialsHeading' => array('type' => 'string', 'default' => 'Testimonials'),
                'testimonialsIcon' => array('type' => 'string', 'default' => '💬'),
                'testimonialItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'name' => 'John Doe',
                            'title' => 'CEO, Company',
                            'content' => 'This plugin is amazing!',
                            'rating' => '5'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register Bonuses Section Block
     */
    public function register_bonuses_block() {
        register_block_type('swrice/bonuses-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_bonuses_section'),
            'attributes' => array(
                'bonusesHeading' => array('type' => 'string', 'default' => 'Bonuses'),
                'bonusesIcon' => array('type' => 'string', 'default' => '🎁'),
                'bonusItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'title' => 'Bonus 1',
                            'description' => 'Description of the bonus',
                            'value' => '$50',
                            'icon' => '🎁'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register Guarantee Section Block
     */
    public function register_guarantee_block() {
        register_block_type('swrice/guarantee-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_guarantee_section'),
            'attributes' => array(
                'guaranteeHeading' => array('type' => 'string', 'default' => 'Guarantee'),
                'guaranteeIcon' => array('type' => 'string', 'default' => '🛡️'),
                'guaranteeText' => array('type' => 'string', 'default' => 'We offer a 30-day money back guarantee.'),
                'guaranteePoints' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'point' => '30-day money back guarantee'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register Why Choose Section Block
     */
    public function register_why_choose_block() {
        register_block_type('swrice/why-choose-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_why_choose_section'),
            'attributes' => array(
                'whyChooseHeading' => array('type' => 'string', 'default' => 'Why Choose Us'),
                'whyChooseIcon' => array('type' => 'string', 'default' => '⭐'),
                'whyChooseItems' => array(
                    'type' => 'array',
                    'default' => array(
                        array(
                            'title' => 'Reason 1',
                            'description' => 'Why you should choose us',
                            'icon' => '⭐'
                        )
                    )
                )
            )
        ));
    }
    
    /**
     * Register About Section Block
     */
    public function register_about_block() {
        register_block_type('swrice/about-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_about_section'),
            'attributes' => array(
                'aboutHeading' => array('type' => 'string', 'default' => 'About'),
                'aboutIcon' => array('type' => 'string', 'default' => 'ℹ️'),
                'aboutDescription' => array('type' => 'string', 'default' => 'Learn more about our company and mission.')
            )
        ));
    }

    /**
     * Register Final CTA Section Block
     */
    public function register_final_cta_block() {
        register_block_type('swrice/final-cta-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_final_cta_section'),
            'attributes' => array(
                'finalCtaHeading' => array('type' => 'string', 'default' => 'Ready to Get Started?'),
                'finalCtaIcon' => array('type' => 'string', 'default' => '🚀'),
                'ctaTitle' => array('type' => 'string', 'default' => 'Get Started Today'),
                'ctaSubtitle' => array('type' => 'string', 'default' => 'Join thousands of satisfied customers'),
                'buyNowShortcode' => array('type' => 'string', 'default' => ''),
                'demoLink' => array('type' => 'string', 'default' => ''),
                'pluginPrice' => array('type' => 'string', 'default' => '29')
            )
        ));
    }
    
    /**
     * Add Swrice block content to Yoast SEO analysis
     * 
     * @param string $content The current post content
     * @param WP_Post $post The post object
     * @return string Modified content with Swrice block data
     */
    public function add_swrice_content_to_yoast($content, $post) {
        // Only process if we have a post
        if (!$post || !isset($post->ID)) {
            return $content;
        }
        
        // Get the post content and parse blocks
        $post_content = get_post_field('post_content', $post->ID);
        
        if (empty($post_content)) {
            return $content;
        }
        
        // Parse Gutenberg blocks
        $blocks = parse_blocks($post_content);
        $swrice_content = '';
        
        // Extract content from Swrice blocks
        foreach ($blocks as $block) {
            if (isset($block['blockName']) && strpos($block['blockName'], 'swrice/') === 0) {
                $swrice_content .= ' ' . $this->extract_block_content($block);
            }
        }
        
        // Add the extracted content to the original content
        if (!empty($swrice_content)) {
            $content .= ' ' . trim($swrice_content);
        }
        
        return $content;
    }
    
    /**
     * Extract text content from a Swrice block
     * 
     * @param array $block The block data
     * @return string Extracted text content
     */
    private function extract_block_content($block) {
        $content = '';
        
        if (!isset($block['attrs']) || !is_array($block['attrs'])) {
            return $content;
        }
        
        $attributes = $block['attrs'];
        
        // Common text attributes to extract
        $text_attributes = array(
            'pluginName', 'heroSubtitle', 'problemHeading', 'solutionHeading',
            'featuresHeading', 'faqHeading', 'howItWorksHeading', 'testimonialsHeading',
            'bonusesHeading', 'guaranteeHeading', 'whyChooseHeading', 'aboutHeading',
            'finalCtaHeading', 'screenshotsHeading', 'videoTutorialHeading',
            'versionChangelogHeading', 'content', 'description', 'text', 'ctaTitle',
            'aboutDescription', 'guaranteeDescription', 'solutionDescription'
        );
        
        // Extract text from string attributes
        foreach ($text_attributes as $attr) {
            if (isset($attributes[$attr]) && is_string($attributes[$attr])) {
                $content .= ' ' . strip_tags($attributes[$attr]);
            }
        }
        
        // Extract text from array attributes
        $array_attributes = array(
            'problemItems', 'features', 'faqItems', 'howItWorksSteps',
            'testimonials', 'bonuses', 'whyChooseItems', 'screenshots'
        );
        
        foreach ($array_attributes as $attr) {
            if (isset($attributes[$attr]) && is_array($attributes[$attr])) {
                foreach ($attributes[$attr] as $item) {
                    if (is_string($item)) {
                        $content .= ' ' . strip_tags($item);
                    } elseif (is_array($item)) {
                        // Extract text from object properties
                        foreach ($item as $value) {
                            if (is_string($value)) {
                                $content .= ' ' . strip_tags($value);
                            }
                        }
                    }
                }
            }
        }
        
        return trim($content);
    }
    
    // Render callbacks for individual blocks
    public function render_hero_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/hero-section.php';
        return ob_get_clean();
    }
    
    public function render_problem_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/problem-section.php';
        return ob_get_clean();
    }
    
    public function render_solution_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/solution-section.php';
        return ob_get_clean();
    }
    
    public function render_features_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/features-section.php';
        return ob_get_clean();
    }
    
    public function render_faq_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/faq-section.php';
        return ob_get_clean();
    }
    
    public function render_how_it_works_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/how-it-works-section.php';
        return ob_get_clean();
    }
    
    public function render_testimonials_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/testimonials-section.php';
        return ob_get_clean();
    }
    
    public function render_bonuses_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/bonuses-section.php';
        return ob_get_clean();
    }
    
    public function render_guarantee_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/guarantee-section.php';
        return ob_get_clean();
    }
    
    public function render_why_choose_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/why-choose-section.php';
        return ob_get_clean();
    }
    
    public function render_about_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/about-section.php';
        return ob_get_clean();
    }

    public function render_final_cta_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/final-cta-section.php';
        return ob_get_clean();
    }
    
    public function render_screenshots_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/screenshots-section.php';
        return ob_get_clean();
    }
    
    public function render_video_tutorial_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/video-tutorial-section.php';
        return ob_get_clean();
    }
    
    public function render_version_changelog_section($attributes) {
        ob_start();
        include SGPB_PLUGIN_DIR . 'templates/version-changelog-section.php';
        return ob_get_clean();
    }
    
    /**
     * Register Screenshots Section Block
     */
    public function register_screenshots_block() {
        register_block_type('swrice/screenshots-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_screenshots_section'),
            'attributes' => array(
                'screenshotsHeading' => array('type' => 'string', 'default' => 'Screenshots'),
                'screenshotsIcon' => array('type' => 'string', 'default' => '📸'),
                'screenshotsDescription' => array('type' => 'string', 'default' => 'Take a look at our plugin in action'),
                'screenshotItems' => array('type' => 'array', 'default' => array())
            )
        ));
    }
    
    /**
     * Register Video Tutorial Section Block
     */
    public function register_video_tutorial_block() {
        register_block_type('swrice/video-tutorial-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_video_tutorial_section'),
            'attributes' => array(
                'videoTutorialHeading' => array('type' => 'string', 'default' => 'Video Tutorial'),
                'videoTutorialIcon' => array('type' => 'string', 'default' => '🎥'),
                'videoTutorialDescription' => array('type' => 'string', 'default' => 'Watch how to use our plugin step by step'),
                'videoUrl' => array('type' => 'string', 'default' => ''),
                'videoTitle' => array('type' => 'string', 'default' => 'Plugin Tutorial'),
                'videoDuration' => array('type' => 'string', 'default' => ''),
                'videoThumbnailUrl' => array('type' => 'string', 'default' => '')
            )
        ));
    }
    
    /**
     * Register Version & Changelog Section Block
     */
    public function register_version_changelog_block() {
        register_block_type('swrice/version-changelog-section', array(
            'editor_script' => 'swrice-plugin-page-builder-blocks',
            'render_callback' => array($this, 'render_version_changelog_section'),
            'attributes' => array(
                'versionChangelogHeading' => array('type' => 'string', 'default' => 'Version & Changelog'),
                'versionChangelogIcon' => array('type' => 'string', 'default' => '📋'),
                'versionChangelogDescription' => array('type' => 'string', 'default' => 'Stay updated with the latest features and improvements'),
                'currentVersion' => array('type' => 'string', 'default' => '1.0.0'),
                'upgradeNotice' => array('type' => 'string', 'default' => ''),
                'changelogItems' => array('type' => 'array', 'default' => array())
            )
        ));
    }

    /**
     * Add custom block category
     */
    public function add_block_categories($categories, $post) {
        return array_merge(
            array(
                array(
                    'slug' => 'swrice-blocks',
                    'title' => __('Swrice Plugin Sell Page Builder', 'swrice-gutenberg-page-builder'),
                    'icon' => '📄'
                )
            ),
            $categories
        );
    }
}

// Initialize the plugin
new Swrice_Gutenberg_Page_Builder();
