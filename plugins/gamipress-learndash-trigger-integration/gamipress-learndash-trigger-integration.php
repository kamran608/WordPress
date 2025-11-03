<?php 

/**
 * Plugin Name: GamiPress Learndash Trigger Integration
 * Plugin URI: https://swrice.com/
 * Description: Seamlessly integrate GamiPress with LearnDash by adding specific Course, Lesson, Topic, and Quiz triggers directly from the LearnDash edit pages.
 * Author: Swrice Agency
 * Author URI: https://swrice.com/
 * Version: 1.1
 * Plugin URL: https://swrice.com/
 * Text Domain: gamipress-learndash-trigger-integration
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class GamiPress_LearnDash_Trigger_Integration {
 */
class GamiPress_LearnDash_Trigger_Integration {

    const VERSION = '1.0';

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof GamiPress_LearnDash_Trigger_Integration ) ) {

            self::$instance = new self;

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
        define( 'GLTI_DIR', plugin_dir_path ( __FILE__ ) );
        define( 'GLTI_DIR_FILE', GLTI_DIR . basename ( __FILE__ ) );
        define( 'GLTI_INCLUDES_DIR', trailingslashit ( GLTI_DIR . 'includes' ) );
        define( 'GLTI_TEMPLATES_DIR', trailingslashit ( GLTI_DIR . 'templates' ) );
        define( 'GLTI_BASE_DIR', plugin_basename(__FILE__));

        /**
         * URLs
         */
        define( 'GLTI_URL', trailingslashit ( plugins_url ( '', __FILE__ ) ) );
        define( 'GLTI_ASSETS_URL', trailingslashit ( GLTI_URL . 'assets/' ) );

        /**
         * Version
         */
        define( 'GLTI_VERSION', self::VERSION );
    }

    /**
     * Plugin requiered files
     */
    public function includes() {

        if( file_exists( GLTI_INCLUDES_DIR.'admin.php' ) ) {
            require GLTI_INCLUDES_DIR.'admin.php';
        }
    }

    /**
     * Freemius integration
     */
    public function enable_freemius() {

        if ( ! function_exists( 'glti_fs' ) ) {
            // Create a helper function for easy SDK access.
            function glti_fs() {
                global $glti_fs;

                if ( ! isset( $glti_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $glti_fs = fs_dynamic_init( array(
                        'id'                  => '14127',
                        'slug'                => 'gamipress-ld-trigger-integration',
                        'premium_slug'        => 'gamipress-ld-trigger-integration',
                        'type'                => 'plugin',
                        'public_key'          => 'pk_8bd928ba5bd6a4cc61f733a4f75ee',
                        'is_premium'          => true,
                        'is_premium_only'     => true,
                        'has_addons'          => false,
                        'has_paid_plans'      => true,
                        'is_org_compliant'    => false,
                        'menu'                => array(
                            'first-path'     => 'plugins.php',
                            'support'        => false,
                        ),
                    ) );
                }

                return $glti_fs;
            }

            // Init Freemius.
            glti_fs();
            // Signal that SDK was initiated.
            do_action( 'glti_fs_loaded' );
        }
    }
}

/**
 * Display admin notifications if dependency not found.
 */
function glti_ready() {

    if( ! is_admin() ) {
        return;
    }

    if( ! class_exists( 'SFWD_LMS' ) || ! class_exists( 'GamiPress' ) ) {
        deactivate_plugins ( plugin_basename ( __FILE__ ), true );
        $class = 'notice is-dismissible error';
        $message = __( 'GamiPress Learndash Trigger Integration Add-on requires to LearnDash and GamiPress Plugin are to be activated.', 'gamipress-learndash-trigger-integration' );
        printf ( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
    }
}

/**
 * @return bool
 */
function GLTI() {
    
    if ( ! class_exists( 'SFWD_LMS' ) || ! class_exists( 'GamiPress' ) ) {
        add_action( 'admin_notices', 'glti_ready' );
        return false;
    }

    return GamiPress_LearnDash_Trigger_Integration::instance();
}

add_action( 'plugins_loaded', 'GLTI' );