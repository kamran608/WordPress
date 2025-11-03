<?php

/**
 * Plugin Name: Learndash Course Progress
 * Plugin URI: www.swrice.com
 * Description: Swrice LearnDash Course Progress Add-on is a powerful and easy-to-use plugin that allows you to display student progress for any LearnDash course using either a shortcode or a Gutenberg block.
 * Author: Swrice
 * Author URI: www.swrice.com
 * Version: 1.1
 * Update URI: https://swrice.com/learndash-course-progress/
 * Plugin URL: https://swrice.com/learndash-course-progress/
 * Text Domain: learndash-course-progress
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Learndash_Course_Progress
 */
class Learndash_Course_Progress {

    const VERSION = '1.1';

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {
        if ( is_null( self::$instance ) && !self::$instance instanceof Learndash_Course_Progress ) {
            self::$instance = new self();
            self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->enable_freemius();
        }
        return self::$instance;
    }

    /**
     * defining constants for plugin
     */
    public function setup_constants() {

        /**
         * Directory
         */
        define( 'LCP_DIR', plugin_dir_path( __FILE__ ) );
        define( 'LCP_DIR_FILE', LCP_DIR . basename( __FILE__ ) );
        define( 'LCP_INCLUDES_DIR', trailingslashit( LCP_DIR . 'includes' ) );
        define( 'LCP_TEMPLATES_DIR', trailingslashit( LCP_DIR . 'templates' ) );
        define( 'LCP_BASE_DIR', plugin_basename( __FILE__ ) );
        /**
         * URLs
         */
        define( 'LCP_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
        define( 'LCP_ASSETS_URL', trailingslashit( LCP_URL . 'assets/' ) );
        /**
         * Text Domain
         */
        define( 'LCP_TEXT_DOMAIN', 'learndash-course-progress' );
        /**
         * Version
         */
        define( 'LCP_VERSION', self::VERSION );
    }

    /**
     * Plugin requiered files
     */
    public function includes() {

        if ( file_exists( LCP_INCLUDES_DIR . 'admin.php' ) ) {
            require LCP_INCLUDES_DIR . 'admin.php';
        }
        
        if ( file_exists( LCP_INCLUDES_DIR . 'frontend.php' ) ) {
            require LCP_INCLUDES_DIR . 'frontend.php';
        }

        if ( file_exists( LCP_INCLUDES_DIR . 'progress-block.php' ) ) {
            require LCP_INCLUDES_DIR . 'progress-block.php';
        }
    }

    /**
     * Load freemius
     */
    public function enable_freemius() {

        if ( ! function_exists( 'lcp_fs' ) ) {
            // Create a helper function for easy SDK access.
            function lcp_fs() {
                global $lcp_fs;
        
                if ( ! isset( $lcp_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $lcp_fs = fs_dynamic_init( array(
                        'id'                  => '16283',
                        'slug'                => 'learndash-course-progress',
                        'type'                => 'plugin',
                        'public_key'          => 'pk_f570659b025f9f10ec3bd7e1ffa1a',
                        'is_premium'          => true,
                        'is_premium_only'     => false,
                        'has_addons'          => false,
                        'has_paid_plans'      => true,
                        'is_org_compliant'    => false,
                        'menu'                => array(
                            'slug'           => 'lcp-progress-settings',
                            'first-path'     => 'admin.php?page=lcp-progress-settings',
                            'contact'        => true,
                            'support'        => false,
                            'parent'         => array(
                                'slug' => 'learndash-lms',
                            ),
                        ),
                    ) );
                }
        
                return $lcp_fs;
            }
        
            // Init Freemius.
            lcp_fs();
            // Signal that SDK was initiated.
            do_action( 'lcp_fs_loaded' );
        }
    }
}

/**
 * Display admin notifications if dependency not found.
 */
function lcp_ready() {
    if ( !is_admin() ) {
        return;
    }
    if ( !class_exists( 'SFWD_LMS' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ), true );
        $class = 'notice is-dismissible error';
        $message = __( 'Learndash Course Progress Add-on requires to LearnDash Plugin is to be activated.', 'learndash-course-progress' );
        printf( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
    }
}

/**
 * @return bool
 */
function LCP() {
    load_plugin_textdomain( 'learndash-course-progress' );
    if ( !class_exists( 'SFWD_LMS' ) ) {
        add_action( 'admin_notices', 'lcp_ready' );
        return false;
    }
    return Learndash_Course_Progress::instance();
}

add_action( 'plugins_loaded', 'LCP' );