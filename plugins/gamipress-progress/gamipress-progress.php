<?php
/**
 * Plugin Name:     GamiPress - Progress
 * Plugin URI:      https://gamipress.com/add-ons/gamipress-progress
 * Description:     Attractively show to your users their progress of completion of any achievement.
 * Version:         1.4.3
 * Author:          GamiPress
 * Author URI:      https://gamipress.com/
 * Text Domain:     gamipress-progress
 * License:         GNU AGPL v3.0 (http://www.gnu.org/licenses/agpl.txt)
 *
 * @package         GamiPress\Progress
 * @author          GamiPress
 * @copyright       Copyright (c) GamiPress
 */

final class GamiPress_Progress {

    /**
     * @var         GamiPress_Progress $instance The one true GamiPress_Progress
     * @since       1.0.0
     */
    private static $instance;

    /**
     * Get active instance
     *
     * @access      public
     * @since       1.0.0
     * @return      GamiPress_Progress self::$instance The one true GamiPress_Progress
     */
    public static function instance() {
        if( ! self::$instance ) {
            self::$instance = new GamiPress_Progress();
            self::$instance->constants();
            self::$instance->includes();
            self::$instance->hooks();
            self::$instance->load_textdomain();
        }

        return self::$instance;
    }

    /**
     * Setup plugin constants
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function constants() {

        // Plugin version
        define( 'GAMIPRESS_PROGRESS_VER', '1.4.3' );

        // GamiPress minimum required version
        define( 'GAMIPRESS_PROGRESS_GAMIPRESS_MIN_VER', '3.0.0' );

        // Plugin file
        define( 'GAMIPRESS_PROGRESS_FILE', __FILE__ );

        // Plugin path
        define( 'GAMIPRESS_PROGRESS_DIR', plugin_dir_path( __FILE__ ) );

        // Plugin URL
        define( 'GAMIPRESS_PROGRESS_URL', plugin_dir_url( __FILE__ ) );

    }

    /**
     * Include plugin files
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function includes() {

        if( $this->meets_requirements() ) {

            require_once GAMIPRESS_PROGRESS_DIR . 'includes/admin.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/blocks.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/filters.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/functions.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/scripts.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/shortcodes.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/template-functions.php';
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/widgets.php';

            // Integrations
            require_once GAMIPRESS_PROGRESS_DIR . 'includes/integrations/gamipress-restrict-unlock.php';

        }
    }

    /**
     * Setup plugin hooks
     *
     * @access      private
     * @since       1.0.0
     * @return      void
     */
    private function hooks() {
        // Setup our activation and deactivation hooks
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_action( 'admin_notices', array( $this, 'admin_notices' ) );
    }

    /**
     * Activation hook for the plugin.
     *
     * @since  1.0.0
     */
    function activate() {

        if( $this->meets_requirements() ) {

        }

    }

    /**
     * Deactivation hook for the plugin.
     *
     * @since  1.0.0
     */
    function deactivate() {

    }

    /**
     * Plugin admin notices.
     *
     * @since  1.0.0
     */
    public function admin_notices() {

        if ( ! $this->meets_requirements() && ! defined( 'GAMIPRESS_ADMIN_NOTICES' ) ) : ?>

            <div id="message" class="notice notice-error is-dismissible">
                <p>
                    <?php printf(
                        __( 'GamiPress - Progress requires %s (%s or higher) in order to work. Please install and activate them.', 'gamipress-progress' ),
                        '<a href="https://wordpress.org/plugins/gamipress/" target="_blank">GamiPress</a>',
                        GAMIPRESS_PROGRESS_GAMIPRESS_MIN_VER
                    ); ?>
                </p>
            </div>

            <?php define( 'GAMIPRESS_ADMIN_NOTICES', true ); ?>

        <?php endif;

    }

    /**
     * Check if there are all plugin requirements
     *
     * @since  1.0.0
     *
     * @return bool True if installation meets all requirements
     */
    private function meets_requirements() {

        if ( class_exists( 'GamiPress' ) && version_compare( GAMIPRESS_VER, GAMIPRESS_PROGRESS_GAMIPRESS_MIN_VER, '>=' ) ) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Internationalization
     *
     * @access      public
     * @since       1.0.0
     * @return      void
     */
    public function load_textdomain() {

        // Set filter for language directory
        $lang_dir = GAMIPRESS_PROGRESS_DIR . '/languages/';
        $lang_dir = apply_filters( 'gamipress_progress_languages_directory', $lang_dir );

        // Traditional WordPress plugin locale filter
        $locale = apply_filters( 'plugin_locale', get_locale(), 'gamipress-progress' );
        $mofile = sprintf( '%1$s-%2$s.mo', 'gamipress-progress', $locale );

        // Setup paths to current locale file
        $mofile_local   = $lang_dir . $mofile;
        $mofile_global  = WP_LANG_DIR . '/gamipress-progress/' . $mofile;

        if( file_exists( $mofile_global ) ) {
            // Look in global /wp-content/languages/gamipress/ folder
            load_textdomain( 'gamipress-progress', $mofile_global );
        } elseif( file_exists( $mofile_local ) ) {
            // Look in local /wp-content/plugins/gamipress/languages/ folder
            load_textdomain( 'gamipress-progress', $mofile_local );
        } else {
            // Load the default language files
            load_plugin_textdomain( 'gamipress-progress', false, $lang_dir );
        }

    }

}

/**
 * The main function responsible for returning the one true GamiPress_Progress instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \GamiPress_Progress The one true GamiPress_Progress
 */
function GamiPress_Progress() {
    return GamiPress_Progress::instance();
}
add_action( 'plugins_loaded', 'GamiPress_Progress' );
