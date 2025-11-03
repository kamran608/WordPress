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
     * Override LearnDash templates
     */
    public function override_section_template($filepath, $name, $args, $echo, $return_file_path) {
        // Check if plugin is enabled (default to 'yes' if not set)
        $plugin_instance = CollapsibleSectionsLearnDash::get_instance();
        if ($plugin_instance->get_setting('enable_plugin', 'yes') === 'no') {
            return $filepath; // Return original template if plugin is disabled
        }
        // Override section template
        if ($name === 'lesson/partials/section.php' && $this->is_ld30_theme($filepath)) {
            $custom_template = $this->get_custom_template_path('section.php');
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        
        // Override course listing template
        if ($name === 'course/listing.php' && $this->is_ld30_theme($filepath)) {
            $custom_template = $this->get_custom_template_path('listing.php');
            if (file_exists($custom_template)) {
                return $custom_template;
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
     */
    private function get_custom_template_path($template_name) {
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
