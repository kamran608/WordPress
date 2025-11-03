<?php

/**
 * Plugin Name: Reaction Notification for Buddyboss
 * Version: 1.1
 * Description: This plugin helps you by sending notifications to the post author whenever another user likes or reacts to their activity post. You can now choose to display the reaction name, icon, or both in these notifications.
 * Author: Swrice Agency
 * Plugin URI: https://swrice.com/reaction-notification-for-buddyboss/
 * Author URI: https://swrice.com/
 * Text Domain: reaction-notification-for-buddyboss
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Reaction_Notification_for_Buddyboss
 */
class Reaction_Notification_for_Buddyboss {

    const  VERSION = '1.1' ;

    /**
     * @var self
     */
    private static  $instance = null ;

    /**
     * @since 1.1
     * @return $this
     */
    public static function instance() {
        
        if ( is_null( self::$instance ) && !self::$instance instanceof Reaction_Notification_for_Buddyboss ) {
            self::$instance = new self();
            self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->enable_text_domain();
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
        define( 'RNFB_DIR', plugin_dir_path( __FILE__ ) );
        define( 'RNFB_DIR_FILE', RNFB_DIR . basename( __FILE__ ) );
        define( 'RNFB_INCLUDES_DIR', trailingslashit( RNFB_DIR . 'includes' ) );
        define( 'RNFB_TEMPLATES_DIR', trailingslashit( RNFB_DIR . 'templates' ) );
        define( 'RNFB_BASE_DIR', plugin_basename( __FILE__ ) );
        
        /**
         * URLs
         */
        define( 'RNFB_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
        define( 'RNFB_ASSETS_URL', trailingslashit( RNFB_URL . 'assets/' ) );

        /**
         * Version
         */
        define( 'RNFB_VERSION', self::VERSION );
    }
    
    /**
     * Add freemius code
     */
    public function enable_freemius() {

        if ( ! function_exists( 'rnfb_fs' ) ) {
            // Create a helper function for easy SDK access.
            function rnfb_fs() {
                global $rnfb_fs;

                if ( ! isset( $rnfb_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $rnfb_fs = fs_dynamic_init( array(
                        'id'                  => '15016',
                        'slug'                => 'reaction-notification-for-buddyboss',
                        'type'                => 'plugin',
                        'public_key'          => 'pk_0efc666c25c754f2315b4dc5ca0e7',
                        'is_premium'          => true,
                        'is_premium_only'     => true,
                        'has_addons'          => false,
                        'has_paid_plans'      => true,
                        'is_org_compliant'    => false,
                        'menu'                => array(
                            'slug'           => 'reaction-notification-for-buddyboss',
                            'first-path'     => 'admin.php?page=reaction-notification-for-buddyboss',
                            'parent'         => array(
                                'slug' => 'buddyboss-settings',
                            ),
                        ),
                    ) );
                }

                return $rnfb_fs;
            }

            // Init Freemius.
            rnfb_fs();
            // Signal that SDK was initiated.
            do_action( 'rnfb_fs_loaded' );
        }
    }

    /**
     * enable text domain
     */
    public function enable_text_domain() {

        add_action( 'init', [ $this, 'rnfb_enable_text_domain' ] );
    }

    /**
     * callback function of rnfb_enable_text_domain
     */
    public function rnfb_enable_text_domain() {

        load_plugin_textdomain( 'reaction-notification-for-buddyboss' );
    }
    
    /**
     * Plugin requiered files
     */
    public function includes() {

        if( file_exists( RNFB_INCLUDES_DIR.'admin.php' ) ) {
            require RNFB_INCLUDES_DIR.'admin.php';
        }

        if( file_exists( RNFB_INCLUDES_DIR.'notification-hooks.php' ) ) {
            require RNFB_INCLUDES_DIR.'notification-hooks.php';
        }
    }
}

/**
 * Display admin notifications if dependency not found.
 */
function rnfb_ready() {

    if ( !is_admin() ) {
        return;
    }
    
    if ( !class_exists( 'BuddyPress' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ), true );
        $class = 'notice is-dismissible error';
        $message = __( 'Reaction Notification for Buddyboss Add-on requires to Buddyboss Platform Plugin is to be activated.', 'reaction-notification-for-buddyboss' );
        printf( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
    }
}

/**
 * @return bool
 */
function RNFB() {
    
    if ( !class_exists( 'BuddyPress' ) ) {
        add_action( 'admin_notices', 'rnfb_ready' );
        return false;
    }
    
    return Reaction_Notification_for_Buddyboss::instance();
}

add_action( 'plugins_loaded', 'RNFB' );