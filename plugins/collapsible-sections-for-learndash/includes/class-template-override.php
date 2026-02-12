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
        // Method 1: Check if Modern UI is enabled via registration appearance setting
        if (class_exists('LearnDash_Settings_Section_General_Appearance')) {
            if (LearnDash_Settings_Section_General_Appearance::get_setting('registration_enabled') === 'yes') {
                return 'modern';
            }
        }
        
        // Method 2: Check theme variation settings
        if (class_exists('LearnDash_Theme_Register_LD30')) {
            $theme_instance = LearnDash_Theme_Register_LD30::get_instance();
            if ($theme_instance && method_exists($theme_instance, 'get_current_variation')) {
                $variation = $theme_instance->get_current_variation();
                if (in_array($variation, array('classic', 'modern'), true)) {
                    return $variation;
                }
            }
        }
        
        // Method 3: Check settings option directly
        $themes = get_option('learndash_settings_courses_themes', array());
        if (isset($themes['theme_variation']) && in_array($themes['theme_variation'], array('classic', 'modern'), true)) {
            return $themes['theme_variation'];
        }
        
        // Method 4: Check for Modern UI template existence as fallback
        $modern_template_path = LEARNDASH_LMS_PLUGIN_DIR . 'themes/ld30/templates/modern/course/accordion/section.php';
        if (file_exists($modern_template_path)) {
            // If Modern templates exist but no explicit setting found, check for Modern UI indicators
            if (class_exists('LearnDash_Settings_Section_General_Appearance')) {
                // Modern UI is likely active if the class exists and no explicit Classic setting
                return 'modern';
            }
        }
        
        // Default fallback to Classic
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
        
        // Detect active UI variation
        $active_variation = $this->get_active_ui_variation();
        
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
