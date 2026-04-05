<?php
/**
 * Backend template for LCP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class LCP_Backend
 */
class LCP_Backend {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return self
     */
    public static function instance() {

        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        add_action( 'admin_enqueue_scripts', [ $this, 'lcp_backend_enqueue_scripts' ] );
        add_action( 'admin_menu', [ $this, 'lcp_add_progress_settings_submenu' ] );
        add_filter( 'plugin_action_links_'.LCP_BASE_DIR, [ $this, 'lcp_plugin_action_links' ] );
    }

    /**
     * Add settings link
     *
     * @param array $links Plugin links.
     * @return array
     */
    public function lcp_plugin_action_links( $links ) {

        // Settings link
        $settings_url  = admin_url( 'admin.php?page=lcp-progress-settings' );
        $settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'learndash-course-progress' ) . '</a>';

        // Upgrade link
        $upgrade_url  = 'https://swrice.com/learndash-course-progress/';
        $upgrade_link = '<a href="' . esc_url( $upgrade_url ) . '" target="_blank" style="font-weight:bold;color:#0073aa;">' . esc_html__( 'Upgrade', 'learndash-course-progress' ) . '</a>';

        // Add both links to the beginning of the array
        array_unshift( $links, $settings_link, $upgrade_link );

        return $links;
    }

    /**
     * Add submenu
     */
    public function lcp_add_progress_settings_submenu() {

        add_submenu_page(
            'learndash-lms',
            esc_html__( 'Progress Settings', 'learndash-course-progress' ),
            esc_html__( 'Progress Settings', 'learndash-course-progress' ),
            'manage_options',
            'lcp-progress-settings',
            [ $this, 'lcp_progress_settings_page_content' ]
        );
    }

    /**
     * Settings page content
     */
    public function lcp_progress_settings_page_content() {

        if ( isset( $_POST['lcp_save_settings'] ) ) {

            if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'lcp_progress_settings_nonce' ) ) {

                add_settings_error(
                    'lcp_progress_settings_messages',
                    'lcp_progress_settings_message',
                    esc_html__( 'Security check failed. Please try again.', 'learndash-course-progress' ),
                    'error'
                );

            } else {

                if ( isset( $_POST['lcp_general_color'] ) ) {

                    $color = sanitize_hex_color( wp_unslash( $_POST['lcp_general_color'] ) );
                    update_option( 'lcp_general_color', $color );
                }

                if ( isset( $_POST['lcp_container_width'] ) ) {

                    $width = sanitize_text_field( wp_unslash( $_POST['lcp_container_width'] ) );
                    update_option( 'lcp_container_width', $width );
                }

                add_settings_error(
                    'lcp_progress_settings_messages',
                    'lcp_progress_settings_message',
                    esc_html__( 'Settings saved successfully.', 'learndash-course-progress' ),
                    'success'
                );
            }
        }

        $lcp_general_color = get_option( 'lcp_general_color', '#00CD6A' );
        $lcp_container_width = get_option( 'lcp_container_width', '80%' );

        ?>

        <div class="wrap lcp-setting-wrap">
            <?php settings_errors( 'lcp_progress_settings_messages' ); ?>

            <h1><?php esc_html_e( 'Progress Settings', 'learndash-course-progress' ); ?></h1>

            <p><?php esc_html_e( 'Use this shortcode to display course progress:', 'learndash-course-progress' ); ?></p>

            <pre><code>[swr_course_progress course_id=123]</code></pre>

            <hr>

            <form method="post" action="">
                <?php wp_nonce_field( 'lcp_progress_settings_nonce' ); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="lcp_general_color">
                                <?php esc_html_e( 'General Color', 'learndash-course-progress' ); ?>
                            </label>
                        </th>
                        <td>
                            <input 
                                type="text" 
                                name="lcp_general_color" 
                                value="<?php echo esc_attr( $lcp_general_color ); ?>" 
                                class="lcp-color-picker" 
                            />
                            <p class="description">
                                <?php esc_html_e( 'Choose the primary color for your course progress bars. Use hex format like #ff0000.', 'learndash-course-progress' ); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="lcp_container_width">
                                <?php esc_html_e( 'Container Width', 'learndash-course-progress' ); ?>
                            </label>
                        </th>
                        <td>
                            <input 
                                type="text" 
                                name="lcp_container_width" 
                                value="<?php echo esc_attr( $lcp_container_width ); ?>" 
                                class="lcp-container-width" 
                            />
                            <p class="description">
                                <?php esc_html_e( 'Set the maximum width of the progress container. You can use px or % (e.g., 600px or 100%).', 'learndash-course-progress' ); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input 
                        type="submit" 
                        name="lcp_save_settings" 
                        id="submit" 
                        class="button button-primary" 
                        value="<?php echo esc_attr__( 'Save Changes', 'learndash-course-progress' ); ?>"
                    >
                </p>
            </form>
        </div>

        <?php
    }

    /**
     * Enqueue backend scripts
     *
     * @param string $hook Hook suffix.
     */
    public function lcp_backend_enqueue_scripts( $hook ) {

        if ( 'learndash-lms_page_lcp-progress-settings' !== $hook ) {
            return;
        }

        $version = wp_rand( 1000, 999999 );

        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_style(
            'lcp-backend-style',
            esc_url( LCP_ASSETS_URL . 'css/backend.css' ),
            [],
            $version
        );

        wp_enqueue_script(
            'lcp-backend-js',
            esc_url( LCP_ASSETS_URL . 'js/backend.js' ),
            [ 'jquery', 'wp-color-picker' ],
            $version,
            true
        );
    }
}

LCP_Backend::instance();