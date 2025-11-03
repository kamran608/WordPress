<?php 

/**
 * Plugin Name: Affiliate User Invite to Earn
 * Plugin URI: www.swrice.com
 * Description: This add-on help to send mail to admin after order complete
 * Author: swrice
 * Author URI: www.swrice.com
 * Version: 1.0
 * Plugin URL: www.swrice.com
 * Text Domain: affiliate-system-for-user
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class affiliate_system_for_user
 */
class affiliate_system_for_user {

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

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof affiliate_system_for_user ) ) {
            self::$instance = new self;

            self::$instance->setup_constants();
            self::$instance->includes();
            self::$instance->create_buyer_code_request_table();
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
        define( 'AIE_DIR', plugin_dir_path ( __FILE__ ) );
        define( 'AIE_DIR_FILE', AIE_DIR . basename ( __FILE__ ) );
        define( 'AIE_INCLUDES_DIR', trailingslashit ( AIE_DIR . 'includes' ) );
        define( 'AIE_TEMPLATES_DIR', trailingslashit ( AIE_DIR . 'templates' ) );
        define( 'AIE_BASE_DIR', plugin_basename(__FILE__));

        /**
         * URLs
         */
        define( 'AIE_URL', trailingslashit ( plugins_url ( '', __FILE__ ) ) );
        define( 'AIE_ASSETS_URL', trailingslashit ( AIE_URL . 'assets/' ) );

        /**
         * Text Domain
         */
        define( 'AIE_TEXT_DOMAIN', 'affiliate_system_for_user' );

        /**
         * Version
         */
        define( 'AIE_VERSION', self::VERSION );
    }

    /**
     * Plugin requiered files
     */
    public function includes() {
        
        if( file_exists( AIE_INCLUDES_DIR.'/affiliate-system.php' ) ) {
            require AIE_INCLUDES_DIR.'/affiliate-system.php';
        }

        if( file_exists( AIE_INCLUDES_DIR.'/affiliate-tab.php' ) ) {
            require AIE_INCLUDES_DIR.'/affiliate-tab.php';
        }

        if( file_exists( AIE_INCLUDES_DIR.'/affiliate-backend.php' ) ) {
            require AIE_INCLUDES_DIR.'/affiliate-backend.php';
        }

        if( file_exists( AIE_INCLUDES_DIR.'/set-cookie.php' ) ) {
            require AIE_INCLUDES_DIR.'/set-cookie.php';
        }
    }

    public function create_buyer_code_request_table() {

        global $wpdb;
        $affiliate_user_table = $wpdb->prefix.'aie_affiliate_user_data';

        if( $wpdb->get_var( "SHOW TABLES LIKE '$affiliate_user_table'" ) != $affiliate_user_table ){

            $affiliate_user_query = "CREATE TABLE $affiliate_user_table (
                id INT( 255 ) AUTO_INCREMENT PRIMARY KEY,
                user_id INT( 255 ),
                full_name VARCHAR( 255 ),
                email VARCHAR( 255 ),
                user_earnings VARCHAR( 255 ),
                payment_method VARCHAR( 255 ),
                payment_receipt VARCHAR( 255 ),
                referal_link VARCHAR( 255 ),
                status VARCHAR( 255 )
            )";

            $wpdb->query( $affiliate_user_query );
        }
        
        $affiliate_meta_table = $wpdb->prefix.'aie_affiliate_user_meta';
        if( $wpdb->get_var( "SHOW TABLES LIKE '$affiliate_meta_table'" ) != $affiliate_meta_table ){

            $affiliate_meta_query = "CREATE TABLE $affiliate_meta_table (
                meta_id INT( 255 ) AUTO_INCREMENT PRIMARY KEY,
                parent_user_id INT( 255 ),
                user_id INT( 255 )
            )";

            $wpdb->query( $affiliate_meta_query );
        }
    }
}
return affiliate_system_for_user::instance();
add_action( 'init', 'hide_footer' );
function hide_footer() {
	$current_url="https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	if( $current_url == 'https://swrice.com/register/' ){
	?>
	<style>
	footer {
		display: none !important;
	}
	</style>
	<?php
	}
}
