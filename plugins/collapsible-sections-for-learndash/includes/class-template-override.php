<?php
/**
 * Template override class for Collapsible Sections for LearnDash
 *
 * @package CollapsibleSectionsLearnDash
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Template override management class
 */
class CSLD_Template_Override {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Override LearnDash templates
        add_filter('learndash_template', array($this, 'override_section_template'), 10, 5);
        
        // Template override functionality only
    }
    
    /**
     * Detect active LearnDash UI variation (Classic or Modern)
     * 
     * @return string 'classic' or 'modern'
     */
    private function get_active_ui_variation() {
        // Method 1: Check LearnDash settings for Modern UI indicators
        $learndash_settings = get_option('learndash_settings_courses_themes', array());
        
        // Check if there's a specific theme variation setting
        if (isset($learndash_settings['active_theme']) && $learndash_settings['active_theme'] === 'ld30') {
            // Check for Modern UI specific settings
            if (isset($learndash_settings['theme_variation']) && $learndash_settings['theme_variation'] === 'modern') {
                return 'modern';
            }
        }
        
        // Method 2: Check for Modern UI template files existence
        if (defined('LEARNDASH_LMS_PLUGIN_DIR')) {
            $modern_template_path = LEARNDASH_LMS_PLUGIN_DIR . 'themes/ld30/templates/modern/course/accordion/section.php';
            if (file_exists($modern_template_path)) {
                // Modern templates exist, check if Modern UI is likely active
                
                // Check general appearance settings for Modern UI indicators
                $general_settings = get_option('learndash_settings_general_appearance', array());
                if (!empty($general_settings) && isset($general_settings['registration_enabled']) && $general_settings['registration_enabled'] === 'yes') {
                    return 'modern';
                }
                
                // Check if any Modern UI specific options are set
                if (isset($learndash_settings['modern_ui_enabled']) && $learndash_settings['modern_ui_enabled'] === 'yes') {
                    return 'modern';
                }
            }
        }
        
        // Method 3: Check for LearnDash version and Modern UI availability
        if (defined('LEARNDASH_VERSION')) {
            $version = LEARNDASH_VERSION;
            // Modern UI was introduced in 4.16.0
            if (version_compare($version, '4.16.0', '>=')) {
                // For newer versions, check if Modern UI is explicitly disabled
                $appearance_settings = get_option('learndash_settings_general_appearance', array());
                if (isset($appearance_settings['modern_ui_disabled']) && $appearance_settings['modern_ui_disabled'] === 'yes') {
                    return 'classic';
                }
                
                // In 4.25.2+, Modern UI is default unless explicitly disabled
                if (version_compare($version, '4.25.0', '>=')) {
                    // Check if Classic UI is explicitly enabled
                    if (isset($learndash_settings['force_classic_ui']) && $learndash_settings['force_classic_ui'] === 'yes') {
                        return 'classic';
                    }
                    // Default to Modern for 4.25.0+
                    return 'modern';
                }
            }
        }
        
        // Method 4: Template name detection fallback
        // This will be used during template override to detect based on template name
        // For now, default to Classic for safety
        return 'classic';
    }
    
    /**
     * Override LearnDash templates
     */
    public function override_section_template($filepath, $name, $args, $echo, $return_file_path) {
        // Check if plugin is enabled (default to 'yes' if not set)
        $plugin_instance = CollapsibleSectionsLearnDash::get_instance();
        if ($plugin_instance->get_setting('enable_plugin', 'yes') === 'no') {
            return $filepath; // Return original template if plugin is disabled
        }
        
        // First, try to detect UI variation from template name (most reliable)
        $active_variation = $this->detect_ui_from_template_name($name);
        
        // If we can't detect from template name, use general detection
        if ($active_variation === 'unknown') {
            $active_variation = $this->get_active_ui_variation();
        }
        
        // Handle Classic UI templates
        if ($active_variation === 'classic') {
            // Override section template for Classic UI
            if ($name === 'lesson/partials/section.php' && $this->is_ld30_theme($filepath)) {
                $custom_template = $this->get_custom_template_path('section.php', 'classic');
                if (file_exists($custom_template)) {
                    return $custom_template;
                }
            }
            
            // Override course listing template for Classic UI
            if ($name === 'course/listing.php' && $this->is_ld30_theme($filepath)) {
                $custom_template = $this->get_custom_template_path('listing.php', 'classic');
                if (file_exists($custom_template)) {
                    return $custom_template;
                }
            }
        }
        
        // Handle Modern UI templates
        elseif ($active_variation === 'modern') {
            // Override section template for Modern UI (accordion-based)
            if ($name === 'modern/course/accordion/section.php' || 
                (strpos($name, 'modern/') === 0 && strpos($name, 'section.php') !== false)) {
                $custom_template = $this->get_custom_template_path('section-modern.php', 'modern');
                if (file_exists($custom_template)) {
                    return $custom_template;
                }
            }
            
            // Override course listing template for Modern UI
            if ($name === 'modern/course/listing.php' || 
                (strpos($name, 'modern/course') === 0 && strpos($name, 'listing.php') !== false)) {
                $custom_template = $this->get_custom_template_path('listing-modern.php', 'modern');
                if (file_exists($custom_template)) {
                    return $custom_template;
                }
            }
        }
        
        return $filepath;
    }
    
    /**
     * Detect UI variation from template name (most reliable method)
     * 
     * @param string $template_name Template name being requested
     * @return string 'classic', 'modern', or 'unknown'
     */
    private function detect_ui_from_template_name($template_name) {
        // Modern UI templates always start with 'modern/'
        if (strpos($template_name, 'modern/') === 0) {
            return 'modern';
        }
        
        // Classic UI templates use traditional paths
        if ($template_name === 'lesson/partials/section.php' || 
            $template_name === 'course/listing.php' ||
            strpos($template_name, 'lesson/') === 0 ||
            strpos($template_name, 'course/') === 0) {
            return 'classic';
        }
        
        return 'unknown';
    }
    
    /**
     * Check if the template is from LD30 theme
     */
    private function is_ld30_theme($filepath) {
        return strpos($filepath, 'ld30') !== false;
    }
    
    /**
     * Get custom template path
     * 
     * @param string $template_name Template filename
     * @param string $variation UI variation ('classic' or 'modern')
     * @return string Template file path
     */
    private function get_custom_template_path($template_name, $variation = 'classic') {
        // For Modern UI, check for variation-specific template first
        if ($variation === 'modern') {
            $modern_template = CSLD_PLUGIN_DIR . 'templates/modern/' . $template_name;
            if (file_exists($modern_template)) {
                return $modern_template;
            }
        }
        
        // For Classic UI or fallback, use standard template directory
        $classic_template = CSLD_PLUGIN_DIR . 'templates/' . $template_name;
        if (file_exists($classic_template)) {
            return $classic_template;
        }
        
        // Return the requested path even if file doesn't exist (for error handling)
        return CSLD_PLUGIN_DIR . 'templates/' . $template_name;
    }
    
    /**
     * Get available custom templates
     */
    public function get_available_templates() {
        $template_dir = CSLD_PLUGIN_DIR . 'templates/';
        $templates = array();
        
        if (is_dir($template_dir)) {
            $files = scandir($template_dir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && $file !== 'admin-page.php') {
                    $templates[] = $file;
                }
            }
        }
        
        return $templates;
    }
    
    /**
     * Check if template override is working
     */
    public function is_override_working() {
        $custom_template = $this->get_custom_template_path('section.php');
        return file_exists($custom_template) && is_readable($custom_template);
    }
}

// Template override class is instantiated by main plugin file
