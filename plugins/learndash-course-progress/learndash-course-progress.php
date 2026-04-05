<?php
/**
 * Plugin Name: SWR LearnDash Course Progress
 * Plugin URI: https://swrice.com/learndash-course-progress/
 * Description: Swrice LearnDash Course Progress Add-on is a powerful and easy-to-use plugin that allows you to display student progress for any LearnDash course using a shortcode.
 * Author: Swrice
 * Author URI: https://swrice.com
 * Version: 1.0
 * Text Domain: learndash-course-progress
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class
 */
class SWR_Learndash_Course_Progress {

	const VERSION = '1.0';

	/**
	 * Singleton instance
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Get instance
	 *
	 * @return self
	 */
	public static function instance() {
		if ( null === self::$instance || ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
			self::$instance->setup_constants();
			self::$instance->includes();
		}
		return self::$instance;
	}

	/**
	 * Setup plugin constants
	 */
	private function setup_constants() {

		if ( ! defined( 'LCP_DIR' ) ) {
			define( 'LCP_DIR', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'LCP_FILE' ) ) {
			define( 'LCP_FILE', __FILE__ );
		}
		if ( ! defined( 'LCP_INCLUDES_DIR' ) ) {
			define( 'LCP_INCLUDES_DIR', trailingslashit( LCP_DIR . 'includes' ) );
		}
		if ( ! defined( 'LCP_TEMPLATES_DIR' ) ) {
			define( 'LCP_TEMPLATES_DIR', trailingslashit( LCP_DIR . 'templates' ) );
		}
		if ( ! defined( 'LCP_BASE_DIR' ) ) {
			define( 'LCP_BASE_DIR', plugin_basename( __FILE__ ) );
		}
		if ( ! defined( 'LCP_URL' ) ) {
			define( 'LCP_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
		}
		if ( ! defined( 'LCP_ASSETS_URL' ) ) {
			define( 'LCP_ASSETS_URL', trailingslashit( LCP_URL . 'assets/' ) );
		}
		if ( ! defined( 'LCP_VERSION' ) ) {
			define( 'LCP_VERSION', self::VERSION );
		}
	}

	/**
	 * Include required files
	 */
	private function includes() {

		if ( file_exists( LCP_INCLUDES_DIR . 'admin.php' ) ) {
			require_once LCP_INCLUDES_DIR . 'admin.php';
		}

		if ( file_exists( LCP_INCLUDES_DIR . 'frontend.php' ) ) {
			require_once LCP_INCLUDES_DIR . 'frontend.php';
		}
	}
}

/**
 * Show admin notice if LearnDash is not active
 */
function lcp_admin_notice_missing_learndash() {
	if ( ! class_exists( 'SFWD_LMS' ) ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php echo esc_html__( 'SWR LearnDash Course Progress requires LearnDash to be installed and active.', 'learndash-course-progress' ); ?></p>
		</div>
		<?php
	}
}

/**
 * Initialize plugin
 */
function LCP() {

	if ( ! class_exists( 'SFWD_LMS' ) ) {
		add_action( 'admin_notices', 'lcp_admin_notice_missing_learndash' );
		return false;
	}

	return SWR_Learndash_Course_Progress::instance();
}

add_action( 'plugins_loaded', 'LCP' );