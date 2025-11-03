<?php 

/**
 * Plugin Name: Swrice Functionality
 * Plugin URI: www.swrice.com
 * Description: This add-on help to send mail to admin after order complete
 * Author: swrice
 * Author URI: www.swrice.com
 * Version: 1.0
 * Plugin URL: www.swrice.com
 * Text Domain: custom-swrice
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Swrice_Functionality
 */
class Swrice_Functionality {

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

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof Swrice_Functionality ) ) {
            self::$instance = new self;

            self::$instance->setup_constants();
            self::$instance->includes();
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
        define( 'SWR_DIR', plugin_dir_path ( __FILE__ ) );
        define( 'SWR_DIR_FILE', SWR_DIR . basename ( __FILE__ ) );
        define( 'SWR_INCLUDES_DIR', trailingslashit ( SWR_DIR . 'includes' ) );
        define( 'SWR_TEMPLATES_DIR', trailingslashit ( SWR_DIR . 'templates' ) );
        define( 'SWR_BASE_DIR', plugin_basename(__FILE__));

        /**
         * URLs
         */
        define( 'SWR_URL', trailingslashit ( plugins_url ( '', __FILE__ ) ) );
        define( 'SWR_ASSETS_URL', trailingslashit ( SWR_URL . 'assets/' ) );

        /**
         * Text Domain
         */
        define( 'SWR_TEXT_DOMAIN', 'swrice-functionality' );

        /**
         * Version
         */
        define( 'SWR_VERSION', self::VERSION );
    }

    /**
     * Plugin requiered files
     */
    public function includes() {
        
        if( file_exists( SWR_INCLUDES_DIR.'/client-reviews.php' ) ) {
            require SWR_INCLUDES_DIR.'/client-reviews.php';
        }
        if( file_exists( SWR_INCLUDES_DIR.'header.php' ) ) {
            require SWR_INCLUDES_DIR.'header.php';
        }
        if( file_exists( SWR_INCLUDES_DIR.'swrice-menu-customize.php' ) ) {
            require SWR_INCLUDES_DIR.'swrice-menu-customize.php';
        }
		
		function render_learndash_course_progress_buy_button() {
			ob_start();
			?>
			<button id="purchase">Buy Button</button>
			<script type="text/javascript" src="https://checkout.freemius.com/js/v1/"></script>
			<script type="text/javascript">
				document.addEventListener('DOMContentLoaded', function () {
					const handler = new FS.Checkout({
						product_id: '16283',
						plan_id: '31422',
						public_key: 'pk_f570659b025f9f10ec3bd7e1ffa1a',
						image: 'https://your-plugin-site.com/logo-100x100.png'
					});

					const btn = document.getElementById('purchase');
					if (btn) {
						btn.addEventListener('click', function (e) {
							e.preventDefault();
							handler.open({
								name: 'Learndash Course Progress',
								licenses: 1,
								purchaseCompleted: (response) => {
									console.log('Purchase completed:', response);
								},
								success: (response) => {
									console.log('Checkout closed after successful purchase:', response);
								}
							});
						});
					}
				});
			</script>
			<?php
			return ob_get_clean();
		}
		add_shortcode('learndash_course_progress_buy_button', 'render_learndash_course_progress_buy_button');
    }
}

return Swrice_Functionality::instance();