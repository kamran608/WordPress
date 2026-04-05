<?php 

/**
 * Backend template for LCP
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class LCP_Backend
 */
class LCP_Backend {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof LCP_Backend ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {
        
        add_action( 'admin_enqueue_scripts', [ $this, 'LCP_Backend_enqueue_scripts' ] );
        add_action( 'admin_menu', [ $this, 'lcp_add_progress_settings_submenu' ] );
        add_filter( 'plugin_action_links_'.LCP_BASE_DIR, [ $this, 'lcp_plugin_action_links' ] );
    }

    /**
     * Add a settings link to the plugin actions
     */
    public function lcp_plugin_action_links( $links ) {

        $settings_link = '<a href="'. admin_url( 'admin.php?page=lcp-progress-settings' ) .'">'. __( 'Settings', 'learndash-course-progress' ) .'</a>';
        array_unshift( $links, $settings_link );

        return $links;
    }

    /**
     * Hook to admin menu to create submenu under Learndash
     */
    public function lcp_add_progress_settings_submenu() {

        add_submenu_page(
            'learndash-lms',
            __( 'Progress Settings', 'learndash-course-progress' ),
            __( 'Progress Settings', 'learndash-course-progress' ),
            'manage_options',
            'lcp-progress-settings',
            [ $this, 'lcp_progress_settings_page_content' ]
        );
    }

    /**
     * Callback function for the submenu page content
     */
    public function lcp_progress_settings_page_content() {

        if (isset($_POST['lcp_save_settings'])) {

            if (check_admin_referer('lcp_progress_settings_nonce')) {

                if ( isset( $_POST['lcp_general_color'] ) ) {
                    update_option( 'lcp_general_color', sanitize_hex_color( $_POST['lcp_general_color'] ) );
                }

                add_settings_error(
                    'lcp_progress_settings_messages',
                    'lcp_progress_settings_message',
                    __('Settings saved successfully.', 'learndash-course-progress'),
                    'success'
                );
            } else {

                add_settings_error(
                    'lcp_progress_settings_messages',
                    'lcp_progress_settings_message',
                    __('Security check failed. Please try again.', 'learndash-course-progress'),
                    'error'
                );
            }
        }

        $lcp_general_color = get_option('lcp_general_color');

        ?>
        <div class="wrap lcp-setting-wrap">
            <?php settings_errors('lcp_progress_settings_messages'); ?>
            <h1><?php esc_html_e('Progress Settings', 'learndash-course-progress'); ?></h1>
            <p><?php esc_html_e('Use this shortcode to display course progress:', 'learndash-course-progress'); ?></p>
            <pre><code>[swr_course_progress course_id=123]</code></pre>

            <hr>

            <h2><?php esc_html_e('Gutenberg Block Details', 'learndash-course-progress'); ?></h2>
            <p><?php esc_html_e('You can also use the Gutenberg block to display course progress. Below are the details:', 'learndash-course-progress'); ?></p>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Block Name', 'learndash-course-progress'); ?></th>
                    <td><code>SWR Course Progress</code></td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Attributes', 'learndash-course-progress'); ?></th>
                    <td>
                        <ul>
                            <li><strong>Course ID:</strong> <?php esc_html_e('The ID of the course (default: 0)', 'learndash-course-progress'); ?></li>
                            <li><strong>User ID:</strong> <?php esc_html_e('The ID of the user (default: current user)', 'learndash-course-progress'); ?></li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Usage', 'learndash-course-progress'); ?></th>
                    <td>
                        <p><?php esc_html_e('Add the block to your page or post using the Gutenberg editor. Configure the Course ID and User ID in the block settings.', 'learndash-course-progress'); ?></p>
                    </td>
                </tr>
            </table>

            <hr>

            <form method="post" action="">
                <?php wp_nonce_field('lcp_progress_settings_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="lcp_general_color"><?php esc_html_e('General Color', 'learndash-course-progress'); ?></label></th>
                        <td>
                            <input type="text" name="lcp_general_color" value="<?php echo esc_attr($lcp_general_color); ?>" class="lcp-color-picker" />
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="lcp_save_settings" id="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'learndash-course-progress'); ?>">
                </p>
            </form>
        </div>
        <?php
    }

    /**
     *  Frontend Enqueue script
     */
    public function LCP_Backend_enqueue_scripts() {

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'LCP_Backend_style', LCP_ASSETS_URL.'css/backend.css', [], $random_number );
        wp_enqueue_script( 'LCP_Backend_js', LCP_ASSETS_URL.'js/backend.js', [ 'jquery' ], $random_number, true );

        // Enqueue frontend style for gutenberg preview
        wp_enqueue_style( 'LCP_frontend_style', LCP_ASSETS_URL.'css/frontend.css', [], $random_number );

        wp_localize_script( 'LCP_Backend_js', 'CPS', 
            [
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ] 
        );
    }
}

LCP_Backend::instance();