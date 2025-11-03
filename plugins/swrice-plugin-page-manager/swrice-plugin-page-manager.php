<?php
/**
 * Plugin Name: Swrice Plugin Page Manager
 * Plugin URI: https://swrice.com/
 * Description: Create and manage professional plugin landing pages with custom post types, shortcodes, and SEO-optimized output.
 * Version: 1.0.0
 * Author: Swrice
 * Author URI: https://swrice.com/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: swrice-plugin-manager
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SPPM_VERSION', '1.0.0');
define('SPPM_PLUGIN_FILE', __FILE__);
define('SPPM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SPPM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SPPM_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main plugin class
 */
class SwricePluginPageManager {
    
    private static $instance = null;
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        load_plugin_textdomain('swrice-plugin-manager', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        $this->register_post_type();
        
        $this->register_shortcodes();
        
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'));
        
        add_filter('manage_plugin_page_posts_columns', array($this, 'add_admin_columns'));
        add_action('manage_plugin_page_posts_custom_column', array($this, 'display_admin_columns'), 10, 2);
    }
    
    public function register_post_type() {
        $labels = array(
            'name'                  => _x('Plugin Pages', 'Post type general name', 'swrice-plugin-manager'),
            'singular_name'         => _x('Plugin Page', 'Post type singular name', 'swrice-plugin-manager'),
            'menu_name'             => _x('Plugin Pages', 'Admin Menu text', 'swrice-plugin-manager'),
            'name_admin_bar'        => _x('Plugin Page', 'Add New on Toolbar', 'swrice-plugin-manager'),
            'add_new'               => __('Add New', 'swrice-plugin-manager'),
            'add_new_item'          => __('Add New Plugin Page', 'swrice-plugin-manager'),
            'new_item'              => __('New Plugin Page', 'swrice-plugin-manager'),
            'edit_item'             => __('Edit Plugin Page', 'swrice-plugin-manager'),
            'view_item'             => __('View Plugin Page', 'swrice-plugin-manager'),
            'all_items'             => __('All Plugin Pages', 'swrice-plugin-manager'),
            'search_items'          => __('Search Plugin Pages', 'swrice-plugin-manager'),
            'parent_item_colon'     => __('Parent Plugin Pages:', 'swrice-plugin-manager'),
            'not_found'             => __('No plugin pages found.', 'swrice-plugin-manager'),
            'not_found_in_trash'    => __('No plugin pages found in Trash.', 'swrice-plugin-manager'),
            'featured_image'        => _x('Plugin Featured Image', 'Overrides the "Featured Image" phrase', 'swrice-plugin-manager'),
            'set_featured_image'    => _x('Set featured image', 'Overrides the "Set featured image" phrase', 'swrice-plugin-manager'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the "Remove featured image" phrase', 'swrice-plugin-manager'),
            'use_featured_image'    => _x('Use as featured image', 'Overrides the "Use as featured image" phrase', 'swrice-plugin-manager'),
            'archives'              => _x('Plugin Page archives', 'The post type archive label', 'swrice-plugin-manager'),
            'insert_into_item'      => _x('Insert into plugin page', 'Overrides the "Insert into post" phrase', 'swrice-plugin-manager'),
            'uploaded_to_this_item' => _x('Uploaded to this plugin page', 'Overrides the "Uploaded to this post" phrase', 'swrice-plugin-manager'),
            'filter_items_list'     => _x('Filter plugin pages list', 'Screen reader text for the filter links', 'swrice-plugin-manager'),
            'items_list_navigation' => _x('Plugin pages list navigation', 'Screen reader text for the pagination', 'swrice-plugin-manager'),
            'items_list'            => _x('Plugin pages list', 'Screen reader text for the items list', 'swrice-plugin-manager'),
        );
        
        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array('slug' => 'plugin-page'),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 20,
            'menu_icon'          => 'dashicons-admin-plugins',
            'supports'           => array('title', 'editor', 'thumbnail'),
            'show_in_rest'       => true,
        );
        
        register_post_type('plugin_page', $args);
    }
    
    public function register_shortcodes() {
        add_shortcode('plugin_page', array($this, 'plugin_page_shortcode'));
        add_shortcode('buy_now_button', array($this, 'buy_now_button_shortcode'));
    }
    
    /**
     * Comprehensive admin context detection for Gutenberg compatibility
     */
    private function is_admin_context() {
        // Traditional admin check
        if (is_admin()) {
            return true;
        }
        
        // REST API request check (Gutenberg uses REST API for saves)
        if (defined('REST_REQUEST') && REST_REQUEST) {
            return true;
        }
        
        // Check for REST API in request URI (Gutenberg pattern)
        if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/wp-json/') !== false) {
            return true;
        }
        
        // Check for AJAX requests (including Gutenberg AJAX)
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return true;
        }
        
        // Check for wp_is_json_request if available (WordPress 5.0+)
        if (function_exists('wp_is_json_request') && wp_is_json_request()) {
            return true;
        }
        
        // Check for Gutenberg-specific actions
        if (isset($_POST['action']) && in_array($_POST['action'], array('edit', 'editpost', 'heartbeat'))) {
            return true;
        }
        
        // Check if user has edit capabilities (additional safety check)
        if (current_user_can('edit_posts') && !is_singular()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Plugin page shortcode
     */
    public function plugin_page_shortcode($atts) {
        // Only process shortcode on frontend, not in admin/backend or during saves
        if ($this->is_admin_context()) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'id' => 0,
        ), $atts, 'plugin_page');
        
        if (empty($atts['id'])) {
            return '<p>Please provide a plugin page ID.</p>';
        }
        
        $post = get_post($atts['id']);
        if (!$post || $post->post_type !== 'plugin_page') {
            return '<p>Plugin page not found.</p>';
        }
        
        $meta = get_post_meta($post->ID);
        
        ob_start();
        
        include SPPM_PLUGIN_DIR . 'templates/plugin-page-template.php';
        
        return ob_get_clean();
    }
    
     /**
      * Buy now button shortcode
      */
    public function buy_now_button_shortcode($atts, $content = '') {
        // Only process shortcode on frontend, not in admin/backend or during saves
        if ($this->is_admin_context()) {
            return '';
        }
        
        $atts = shortcode_atts(array(
            'url' => '#',
            'text' => 'Buy Now',
            'class' => 'sppm-buy-now-btn',
            'target' => '_blank',
        ), $atts, 'buy_now_button');
        
        return sprintf(
            '<div class="sppm-buy-now-container"><a href="%s" class="%s" target="%s">%s</a></div>',
            esc_url($atts['url']),
            esc_attr($atts['class']),
            esc_attr($atts['target']),
            !empty($content) ? $content : esc_html($atts['text'])
        );
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'plugin_page_details',
            __('Plugin Page Details', 'swrice-plugin-manager'),
            array($this, 'plugin_page_details_callback'),
            'plugin_page',
            'normal',
            'high'
        );
        
        add_meta_box(
            'plugin_page_shortcode',
            __('Shortcode', 'swrice-plugin-manager'),
            array($this, 'plugin_page_shortcode_callback'),
            'plugin_page',
            'side',
            'high'
        );
    }
    
     /**
      * Plugin page details meta box callback
      */
    public function plugin_page_details_callback($post) {
        wp_nonce_field('plugin_page_details_nonce', 'plugin_page_details_nonce');
        
        $meta = get_post_meta($post->ID);
        
        include SPPM_PLUGIN_DIR . 'admin/meta-boxes/plugin-details.php';
    }
    
     /**
      * Plugin page shortcode meta box callback
      */
    public function plugin_page_shortcode_callback($post) {
        echo '<p><strong>Use this shortcode to display the plugin page:</strong></p>';
        echo '<input type="text" value="[plugin_page id=&quot;' . $post->ID . '&quot;]" readonly style="width: 100%;" onclick="this.select();" />';
        echo '<p><small>Copy and paste this shortcode anywhere you want to display this plugin page.</small></p>';
    }
    
     /**
      * Save meta boxes
      */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['plugin_page_details_nonce']) || !wp_verify_nonce($_POST['plugin_page_details_nonce'], 'plugin_page_details_nonce')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save simple meta fields
        $meta_fields = array(
            'plugin_price',
            'plugin_original_price',
            'buy_now_shortcode',
            'hero_subtitle',
            'problem_heading',
            'problem_icon',
            'solution_heading',
            'solution_icon',
            'solution_description',
            'how_it_works_heading',
            'how_it_works_icon',
            'faq_heading',
            'faq_icon',
            'features_heading',
            'features_icon',
            'testimonials_heading',
            'testimonials_icon',
            'bonuses_heading',
            'bonuses_icon',
            'guarantee_heading',
            'guarantee_icon',
            'guarantee_text',
            'why_choose_heading',
            'why_choose_icon',
            'about_heading',
            'about_icon',
            'about_description',
            'cta_title',
            'cta_subtitle',
            'demo_link'
        );
        
        foreach ($meta_fields as $field) {
            if (isset($_POST[$field])) {
                // Special handling for URL fields
                if ($field === 'demo_link') {
                    update_post_meta($post_id, $field, esc_url_raw($_POST[$field]));
                } else {
                    update_post_meta($post_id, $field, sanitize_textarea_field($_POST[$field]));
                }
            }
        }
        
        // Save repeater fields
        
        // Save Problem items
        if (isset($_POST['problem_items']) && is_array($_POST['problem_items'])) {
            $problem_items = array();
            foreach ($_POST['problem_items'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $problem_items[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description']),
                        'icon' => sanitize_text_field($item['icon'])
                    );
                }
            }
            update_post_meta($post_id, 'problem_items', $problem_items);
        }
        
        // Save Steps items
        if (isset($_POST['steps_items']) && is_array($_POST['steps_items'])) {
            $steps_items = array();
            foreach ($_POST['steps_items'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $steps_items[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description'])
                    );
                }
            }
            update_post_meta($post_id, 'steps_items', $steps_items);
        }
        
        // Save FAQ items
        if (isset($_POST['faq_items']) && is_array($_POST['faq_items'])) {
            $faq_items = array();
            foreach ($_POST['faq_items'] as $item) {
                if (!empty($item['question']) || !empty($item['answer'])) {
                    $faq_items[] = array(
                        'question' => sanitize_text_field($item['question']),
                        'answer' => sanitize_textarea_field($item['answer'])
                    );
                }
            }
            update_post_meta($post_id, 'faq_items', $faq_items);
        }
        
        // Save Feature items
        if (isset($_POST['feature_items']) && is_array($_POST['feature_items'])) {
            $feature_items = array();
            foreach ($_POST['feature_items'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $feature_items[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description']),
                        'icon' => sanitize_text_field($item['icon'])
                    );
                }
            }
            update_post_meta($post_id, 'feature_items', $feature_items);
        }
        
        // Save Testimonial items
        if (isset($_POST['testimonial_items']) && is_array($_POST['testimonial_items'])) {
            $testimonial_items = array();
            foreach ($_POST['testimonial_items'] as $item) {
                if (!empty($item['name']) || !empty($item['content'])) {
                    $testimonial_items[] = array(
                        'name' => sanitize_text_field($item['name']),
                        'title' => sanitize_text_field($item['title']),
                        'content' => sanitize_textarea_field($item['content']),
                        'rating' => sanitize_text_field($item['rating'])
                    );
                }
            }
            update_post_meta($post_id, 'testimonial_items', $testimonial_items);
        }
        
        // Save Bonus items
        if (isset($_POST['bonus_items']) && is_array($_POST['bonus_items'])) {
            $bonus_items = array();
            foreach ($_POST['bonus_items'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $bonus_items[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description']),
                        'value' => sanitize_text_field($item['value']),
                        'icon' => sanitize_text_field($item['icon'])
                    );
                }
            }
            update_post_meta($post_id, 'bonus_items', $bonus_items);
        }
        
        // Save Guarantee Points
        if (isset($_POST['guarantee_points']) && is_array($_POST['guarantee_points'])) {
            $guarantee_points = array();
            foreach ($_POST['guarantee_points'] as $item) {
                if (!empty($item['point'])) {
                    $guarantee_points[] = array(
                        'point' => sanitize_text_field($item['point'])
                    );
                }
            }
            update_post_meta($post_id, 'guarantee_points', $guarantee_points);
        }
        
        // Save Why Choose items
        if (isset($_POST['why_choose_items']) && is_array($_POST['why_choose_items'])) {
            $why_choose_items = array();
            foreach ($_POST['why_choose_items'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $why_choose_items[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description']),
                        'icon' => sanitize_text_field($item['icon'])
                    );
                }
            }
            update_post_meta($post_id, 'why_choose_items', $why_choose_items);
        }
        
        // Save section order
        if (isset($_POST['section_order']) && is_array($_POST['section_order'])) {
            $section_order = array();
            foreach ($_POST['section_order'] as $section) {
                $section_order[] = sanitize_text_field($section);
            }
            update_post_meta($post_id, 'section_order', $section_order);
        }
        
        // Save section enabled states
        if (isset($_POST['section_enabled']) && is_array($_POST['section_enabled'])) {
            $section_enabled = array();
            // First, set all sections to disabled
            $all_sections = array(
                'problem', 'solution', 'how_it_works', 'features', 
                'testimonials', 'faq', 'bonuses', 'guarantee', 
                'why_choose', 'about', 'final_cta'
            );
            foreach ($all_sections as $section) {
                $section_enabled[$section] = false;
            }
            // Then, enable the checked ones
            foreach ($_POST['section_enabled'] as $section => $value) {
                $section_enabled[sanitize_text_field($section)] = true;
            }
            update_post_meta($post_id, 'section_enabled', $section_enabled);
        } else {
            // If no sections are enabled, disable all
            $section_enabled = array();
            $all_sections = array(
                'problem', 'solution', 'how_it_works', 'features', 
                'testimonials', 'faq', 'bonuses', 'guarantee', 
                'why_choose', 'about', 'final_cta'
            );
            foreach ($all_sections as $section) {
                $section_enabled[$section] = false;
            }
            update_post_meta($post_id, 'section_enabled', $section_enabled);
        }
    }
    
     /**
      * Load and render a section template
      */
    public function load_section($section_key, $all_meta, $post_id) {
        $section_file = SPPM_PLUGIN_DIR . 'templates/sections/section-' . $section_key . '.php';
        
        if (file_exists($section_file)) {
            // Extract meta data for easier access in section templates
            extract($all_meta);
            include $section_file;
        } else {
            // Fallback to inline rendering for sections not yet extracted
            $this->render_section_inline($section_key, $all_meta, $post_id);
        }
    }
    
     /**
      * Render section inline (fallback for sections not yet extracted)
      */
    private function render_section_inline($section_key, $all_meta, $post_id) {
        // This will contain the inline rendering logic for sections
        // that haven't been extracted to separate files yet
        switch ($section_key) {
            case 'problem':
                $this->render_problem_section($all_meta);
                break;
            case 'solution':
                $this->render_solution_section($all_meta);
                break;
            case 'how_it_works':
                $this->render_how_it_works_section($all_meta);
                break;
            case 'features':
                $this->render_features_section($all_meta);
                break;
            case 'testimonials':
                $this->render_testimonials_section($all_meta);
                break;
            case 'faq':
                $this->render_faq_section($all_meta);
                break;
            case 'bonuses':
                $this->render_bonuses_section($all_meta);
                break;
            case 'guarantee':
                $this->render_guarantee_section($all_meta);
                break;
            case 'why_choose':
                $this->render_why_choose_section($all_meta);
                break;
            case 'about':
                $this->render_about_section($all_meta);
                break;
            case 'final_cta':
                $this->render_final_cta_section($all_meta);
                break;
        }
    }
    
     /**
      * Render problem section inline
      */
    private function render_problem_section($all_meta) {
        $problem_heading = isset($all_meta['problem_heading'][0]) ? $all_meta['problem_heading'][0] : 'Common Problems';
        $problem_icon = isset($all_meta['problem_icon'][0]) ? $all_meta['problem_icon'][0] : '';
        $problem_items = maybe_unserialize(isset($all_meta['problem_items'][0]) ? $all_meta['problem_items'][0] : array());
        
        if (!empty($problem_items) && is_array($problem_items)): ?>
        <section class="sppm-section sppm-problem-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($problem_icon): ?><span class="sppm-section-icon"><?php echo $problem_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($problem_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-problem-grid">
                <?php foreach ($problem_items as $problem): ?>
                    <div class="sppm-problem-item">
                        <?php if (!empty($problem['icon'])): ?>
                            <div class="sppm-problem-icon"><?php echo $problem['icon']; ?></div>
                        <?php endif; ?>
                        <h3 class="sppm-problem-title"><?php echo esc_html($problem['title']); ?></h3>
                        <p class="sppm-problem-description"><?php echo esc_html($problem['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render solution section inline
      */
    private function render_solution_section($all_meta) {
        $solution_heading = isset($all_meta['solution_heading'][0]) ? $all_meta['solution_heading'][0] : 'Our Solution';
        $solution_icon = isset($all_meta['solution_icon'][0]) ? $all_meta['solution_icon'][0] : '';
        $solution_description = isset($all_meta['solution_description'][0]) ? $all_meta['solution_description'][0] : '';
        ?>
        <section class="sppm-section sppm-solution-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($solution_icon): ?><span class="sppm-section-icon"><?php echo $solution_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($solution_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-solution-content">
                <p class="sppm-solution-description"><?php echo esc_html($solution_description); ?></p>
            </div>
        </section>
        <?php
    }
    
     /**
      * Render how it works section inline
      */
    private function render_how_it_works_section($all_meta) {
        $how_it_works_heading = isset($all_meta['how_it_works_heading'][0]) ? $all_meta['how_it_works_heading'][0] : 'How It Works';
        $how_it_works_icon = isset($all_meta['how_it_works_icon'][0]) ? $all_meta['how_it_works_icon'][0] : '';
        $how_it_works_items = maybe_unserialize(isset($all_meta['how_it_works_items'][0]) ? $all_meta['how_it_works_items'][0] : array());
        
        if (!empty($how_it_works_items) && is_array($how_it_works_items)): ?>
        <section class="sppm-section sppm-how-it-works-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($how_it_works_icon): ?><span class="sppm-section-icon"><?php echo $how_it_works_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($how_it_works_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-how-it-works-grid">
                <?php foreach ($how_it_works_items as $index => $step): ?>
                    <div class="sppm-how-it-works-item">
                        <div class="sppm-step-number"><?php echo ($index + 1); ?></div>
                        <h3 class="sppm-step-title"><?php echo esc_html($step['title']); ?></h3>
                        <p class="sppm-step-description"><?php echo esc_html($step['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render features section inline
      */
    private function render_features_section($all_meta) {
        $features_heading = isset($all_meta['features_heading'][0]) ? $all_meta['features_heading'][0] : 'Features';
        $features_icon = isset($all_meta['features_icon'][0]) ? $all_meta['features_icon'][0] : '';
        $features_items = maybe_unserialize(isset($all_meta['features_items'][0]) ? $all_meta['features_items'][0] : array());
        
        if (!empty($features_items) && is_array($features_items)): ?>
        <section class="sppm-section sppm-features-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($features_icon): ?><span class="sppm-section-icon"><?php echo $features_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($features_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-features-grid">
                <?php foreach ($features_items as $feature): ?>
                    <div class="sppm-feature-item">
                        <?php if (!empty($feature['icon'])): ?>
                            <div class="sppm-feature-icon"><?php echo $feature['icon']; ?></div>
                        <?php endif; ?>
                        <h3 class="sppm-feature-title"><?php echo esc_html($feature['title']); ?></h3>
                        <p class="sppm-feature-description"><?php echo esc_html($feature['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render testimonials section inline
      */
    private function render_testimonials_section($all_meta) {
        $testimonials_heading = isset($all_meta['testimonials_heading'][0]) ? $all_meta['testimonials_heading'][0] : 'Testimonials';
        $testimonials_icon = isset($all_meta['testimonials_icon'][0]) ? $all_meta['testimonials_icon'][0] : '';
        $testimonials_items = maybe_unserialize(isset($all_meta['testimonials_items'][0]) ? $all_meta['testimonials_items'][0] : array());
        
        if (!empty($testimonials_items) && is_array($testimonials_items)): ?>
        <section class="sppm-section sppm-testimonials-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($testimonials_icon): ?><span class="sppm-section-icon"><?php echo $testimonials_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($testimonials_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-testimonials-grid">
                <?php foreach ($testimonials_items as $testimonial): ?>
                    <div class="sppm-testimonial-item">
                        <div class="sppm-testimonial-content">
                            <p class="sppm-testimonial-text">"<?php echo esc_html($testimonial['text']); ?>"</p>
                            <div class="sppm-testimonial-author">
                                <strong><?php echo esc_html($testimonial['author']); ?></strong>
                                <?php if (!empty($testimonial['position'])): ?>
                                    <span class="sppm-testimonial-position"><?php echo esc_html($testimonial['position']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render FAQ section inline
      */
    private function render_faq_section($all_meta) {
        $faq_heading = isset($all_meta['faq_heading'][0]) ? $all_meta['faq_heading'][0] : 'FAQ';
        $faq_icon = isset($all_meta['faq_icon'][0]) ? $all_meta['faq_icon'][0] : '';
        $faq_items = maybe_unserialize(isset($all_meta['faq_items'][0]) ? $all_meta['faq_items'][0] : array());
        
        if (!empty($faq_items) && is_array($faq_items)): ?>
        <section class="sppm-section sppm-faq-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($faq_icon): ?><span class="sppm-section-icon"><?php echo $faq_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($faq_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-faq-list">
                <?php foreach ($faq_items as $index => $faq): ?>
                    <div class="sppm-faq-item">
                        <div class="sppm-faq-question" data-faq="<?php echo $index; ?>">
                            <h3><?php echo esc_html($faq['question']); ?></h3>
                            <span class="sppm-faq-toggle">+</span>
                        </div>
                        <div class="sppm-faq-answer" id="faq-<?php echo $index; ?>">
                            <p><?php echo esc_html($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render bonuses section inline
      */
    private function render_bonuses_section($all_meta) {
        $bonuses_heading = isset($all_meta['bonuses_heading'][0]) ? $all_meta['bonuses_heading'][0] : 'Bonuses';
        $bonuses_icon = isset($all_meta['bonuses_icon'][0]) ? $all_meta['bonuses_icon'][0] : '';
        $bonuses_items = maybe_unserialize(isset($all_meta['bonuses_items'][0]) ? $all_meta['bonuses_items'][0] : array());
        
        if (!empty($bonuses_items) && is_array($bonuses_items)): ?>
        <section class="sppm-section sppm-bonuses-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($bonuses_icon): ?><span class="sppm-section-icon"><?php echo $bonuses_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($bonuses_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-bonuses-grid">
                <?php foreach ($bonuses_items as $bonus): ?>
                    <div class="sppm-bonus-item">
                        <?php if (!empty($bonus['icon'])): ?>
                            <div class="sppm-bonus-icon"><?php echo $bonus['icon']; ?></div>
                        <?php endif; ?>
                        <h3 class="sppm-bonus-title"><?php echo esc_html($bonus['title']); ?></h3>
                        <p class="sppm-bonus-description"><?php echo esc_html($bonus['description']); ?></p>
                        <?php if (!empty($bonus['value'])): ?>
                            <div class="sppm-bonus-value">Value: $<?php echo esc_html($bonus['value']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render guarantee section inline
      */
    private function render_guarantee_section($all_meta) {
        $guarantee_heading = isset($all_meta['guarantee_heading'][0]) ? $all_meta['guarantee_heading'][0] : 'Guarantee';
        $guarantee_icon = isset($all_meta['guarantee_icon'][0]) ? $all_meta['guarantee_icon'][0] : '';
        $guarantee_text = isset($all_meta['guarantee_text'][0]) ? $all_meta['guarantee_text'][0] : '';
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
            </div>
        </section>
        <?php
    }
    
     /**
      * Render why choose section inline
      */
    private function render_why_choose_section($all_meta) {
        $why_choose_heading = isset($all_meta['why_choose_heading'][0]) ? $all_meta['why_choose_heading'][0] : 'Why Choose Us';
        $why_choose_icon = isset($all_meta['why_choose_icon'][0]) ? $all_meta['why_choose_icon'][0] : '';
        $why_choose_items = maybe_unserialize(isset($all_meta['why_choose_items'][0]) ? $all_meta['why_choose_items'][0] : array());
        
        if (!empty($why_choose_items) && is_array($why_choose_items)): ?>
        <section class="sppm-section sppm-why-choose-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($why_choose_icon): ?><span class="sppm-section-icon"><?php echo $why_choose_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($why_choose_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-why-choose-grid">
                <?php foreach ($why_choose_items as $item): ?>
                    <div class="sppm-why-choose-item">
                        <?php if (!empty($item['icon'])): ?>
                            <div class="sppm-why-choose-icon"><?php echo $item['icon']; ?></div>
                        <?php endif; ?>
                        <h3 class="sppm-why-choose-title"><?php echo esc_html($item['title']); ?></h3>
                        <p class="sppm-why-choose-description"><?php echo esc_html($item['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Render about section inline
      */
    private function render_about_section($all_meta) {
        $about_heading = isset($all_meta['about_heading'][0]) ? $all_meta['about_heading'][0] : 'About';
        $about_icon = isset($all_meta['about_icon'][0]) ? $all_meta['about_icon'][0] : '';
        $about_description = isset($all_meta['about_description'][0]) ? $all_meta['about_description'][0] : '';
        ?>
        <section class="sppm-section sppm-about-section">
            <div class="sppm-section-header">
                <h2 class="sppm-section-title">
                    <?php if ($about_icon): ?><span class="sppm-section-icon"><?php echo $about_icon; ?></span><?php endif; ?>
                    <?php echo esc_html($about_heading); ?>
                </h2>
            </div>
            
            <div class="sppm-about-content">
                <p class="sppm-about-description"><?php echo esc_html($about_description); ?></p>
            </div>
        </section>
        <?php
    }
    
     /**
      * Render final CTA section inline
      */
    private function render_final_cta_section($all_meta) {
        $cta_title = isset($all_meta['cta_title'][0]) ? $all_meta['cta_title'][0] : '';
        $cta_subtitle = isset($all_meta['cta_subtitle'][0]) ? $all_meta['cta_subtitle'][0] : '';
        $buy_now_shortcode = isset($all_meta['buy_now_shortcode'][0]) ? $all_meta['buy_now_shortcode'][0] : '';
        $plugin_price = isset($all_meta['plugin_price'][0]) ? $all_meta['plugin_price'][0] : '';
        $demo_link = isset($all_meta['demo_link'][0]) ? $all_meta['demo_link'][0] : '';
        
        if (!empty($cta_title) || !empty($cta_subtitle)): ?>
        <section class="sppm-section sppm-final-cta">
            <div class="sppm-cta">
                <div class="sppm-cta-content">
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
                    <?php else: ?>
                        <button class="sppm-btn sppm-btn-primary">Buy Now - $<?php echo esc_html($plugin_price); ?></button>
                    <?php endif; ?>
                    <?php if (!empty($demo_link) && $demo_link !== '#'): ?>
                    <a href="<?php echo esc_url($demo_link); ?>" class="sppm-btn sppm-btn-ghost" target="_blank">Live Demo</a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php endif;
    }
    
     /**
      * Add admin columns
      */
    public function add_admin_columns($columns) {
        $columns['shortcode'] = __('Shortcode', 'swrice-plugin-manager');
        $columns['price'] = __('Price', 'swrice-plugin-manager');
        return $columns;
    }
    
     /**
      * Display admin columns
      */
    public function display_admin_columns($column, $post_id) {
        switch ($column) {
            case 'shortcode':
                echo '<code>[plugin_page id="' . $post_id . '"]</code>';
                break;
            case 'price':
                $price = get_post_meta($post_id, 'plugin_price', true);
                echo $price ? '$' . $price : '-';
                break;
        }
    }
    
    public function enqueue_frontend_scripts() {
        wp_enqueue_style('sppm-frontend', SPPM_PLUGIN_URL . 'assets/css/frontend.css', array(), SPPM_VERSION);
        wp_enqueue_script('sppm-frontend', SPPM_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), SPPM_VERSION, true);
    }
    
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'plugin_page') {
            wp_enqueue_style('sppm-admin', SPPM_PLUGIN_URL . 'assets/css/admin.css', array(), SPPM_VERSION);
            wp_enqueue_script('sppm-admin', SPPM_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), SPPM_VERSION, true);
        }
    }
    
     /**
      * Plugin activation
      */
    public function activate() {
        $this->register_post_type();
        flush_rewrite_rules();
    }
    
     /**
      * Plugin deactivation
      */
    public function deactivate() {
        flush_rewrite_rules();
    }
}

// Initialize the plugin
SwricePluginPageManager::get_instance();
