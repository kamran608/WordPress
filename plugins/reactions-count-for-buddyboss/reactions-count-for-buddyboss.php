<?php

/**
 * Plugin Name: Reactions Count for BuddyBoss
 * Plugin URI: https://swrice.com/plugins/reactions-count-for-buddyboss/
 * Description: This plugin enhances your BuddyBoss platform by elegantly displaying real-time reaction counts in a sleek and user-friendly format.
 * Author: Swrice
 * Author URI: https://swrice.com/
 * Version: 1.1
 * Text Domain: reactions-count-for-buddyboss
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Buddyboss_Reactions_Count
 */
class Buddyboss_Reactions_Count
{
    const  VERSION = '1.0' ;
    /**
     * @var self
     */
    private static  $instance = null ;
    /**
     * @since 1.0
     * @return $this
     */
    public static function instance()
    {
        
        if ( is_null( self::$instance ) && !self::$instance instanceof Buddyboss_Reactions_Count ) {
            self::$instance = new self();
            self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->enable_text_domain();
            self::$instance->enable_freemius();
        }
        
        return self::$instance;
    }
    
    /**
     * Add freemius code
     */
    public function enable_freemius()
    {
        
        if ( !function_exists( 'rcfb_fs' ) ) {
            // Create a helper function for easy SDK access.
            function rcfb_fs()
            {
                global  $rcfb_fs ;
                
                if ( !isset( $rcfb_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $rcfb_fs = fs_dynamic_init( array(
                        'id'              => '14176',
                        'slug'            => 'reactions-count-for-buddyboss',
                        'type'            => 'plugin',
                        'public_key'      => 'pk_ee417a1e27199462f36c5ec408a3d',
                        'is_premium'      => true,
                        'is_premium_only' => true,
                        'has_addons'      => false,
                        'has_paid_plans'  => true,
                        'menu'            => array(
                        'slug'       => 'reactions-count-for-buddyboss',
                        'first-path' => 'admin.php?page=reactions-count-for-buddyboss',
                        'parent'     => array(
                        'slug' => 'buddyboss-settings',
                    ),
                    ),
                        'is_live'         => true,
                    ) );
                }
                
                return $rcfb_fs;
            }
            
            // Init Freemius.
            rcfb_fs();
            // Signal that SDK was initiated.
            do_action( 'rcfb_fs_loaded' );
        }
    }
    
    /**
     * enable text domain
     */
    public function enable_text_domain()
    {
        add_action( 'init', [ $this, 'brc_enable_text_domain' ] );
    }
    
    /**
     * callback function of ctld_enable_text_domain
     */
    public function brc_enable_text_domain()
    {
        load_plugin_textdomain( 'reactions-count-for-buddyboss' );
    }
    
    /**
     * defining constants for plugin
     */
    public function setup_constants()
    {
        /**
         * Directory
         */
        define( 'BRC_DIR', plugin_dir_path( __FILE__ ) );
        define( 'BRC_DIR_FILE', BRC_DIR . basename( __FILE__ ) );
        define( 'BRC_INCLUDES_DIR', trailingslashit( BRC_DIR . 'includes' ) );
        define( 'BRC_TEMPLATES_DIR', trailingslashit( BRC_DIR . 'templates' ) );
        define( 'BRC_BASE_DIR', plugin_basename( __FILE__ ) );
        /**
         * URLs
         */
        define( 'BRC_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
        define( 'BRC_ASSETS_URL', trailingslashit( BRC_URL . 'assets/' ) );
        /**
         * Version
         */
        define( 'BRC_VERSION', self::VERSION );
    }
    
    /**
     * Plugin requiered files
     */
    public function includes()
    {
        if ( file_exists( BRC_INCLUDES_DIR . 'frontend.php' ) ) {
            require BRC_INCLUDES_DIR . 'frontend.php';
        }
        if ( file_exists( BRC_INCLUDES_DIR . 'admin.php' ) ) {
            require BRC_INCLUDES_DIR . 'admin.php';
        }
    }

}
/**
 * Display admin notifications if dependency not found.
 */
function BRC_ready()
{
    if ( !is_admin() ) {
        return;
    }
    
    if ( !class_exists( 'BuddyPress' ) ) {
        deactivate_plugins( plugin_basename( __FILE__ ), true );
        $class = 'notice is-dismissible error';
        $message = __( 'Buddyboss Reactions Count Add-on requires to Buddyboss and Buddyboss Platform are to be activated.', 'reactions-count-for-buddyboss' );
        printf( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
    }

}

/**
 * @return bool
 */
function BRC()
{
    
    if ( !class_exists( 'BuddyPress' ) ) {
        add_action( 'admin_notices', 'BRC_ready' );
        return false;
    }
    
    return Buddyboss_Reactions_Count::instance();
}

add_action( 'plugins_loaded', 'BRC' );