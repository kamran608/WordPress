<?php

/**
 * File for admin hooks
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Admin_Hooks {

    /**
     * @var self
     */
    private static  $instance = null ;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {
        
        if ( is_null( self::$instance ) && !self::$instance instanceof Admin_Hooks ) {
            self::$instance = new self();

            self::$instance->hooks();
        }
        
        return self::$instance;
    }
    
    /**
     * Plugin hooks
     */
    private function hooks() {

        add_action( 'admin_menu', [ $this, 'rnfb_menu_page' ] );
        add_action( 'admin_post_rnfb_save_settings', [ $this, 'rnfb_handle_settings_save' ] );
        add_filter( 'plugin_action_links_' . RNFB_BASE_DIR, [ $this, 'rnfb_add_plugin_settings_link' ] );
    }

    /**
     * Add Settings link on plugin page.
     */
    public function rnfb_add_plugin_settings_link( $links ) {
        
        $settings_link = '<a href="' . admin_url( 'admin.php?page=reaction-notification-for-buddyboss' ) . '">' . __( 'Settings', 'reaction-notification-for-buddyboss' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Handles saving settings securely.
     */
    public function rnfb_handle_settings_save() {
        // Check nonce
        if ( ! isset( $_POST['rnfb_save_settings_nonce'] ) || ! wp_verify_nonce( $_POST['rnfb_save_settings_nonce'], 'rnfb_save_settings_action' ) ) {
            wp_die( __( 'Security check failed.', 'reaction-notification-for-buddyboss' ) );
        }

        // Check capability
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You are not allowed to perform this action.', 'reaction-notification-for-buddyboss' ) );
        }

        // Save option
        $display_setting = isset( $_POST['rnfb_display_setting'] ) ? sanitize_text_field( $_POST['rnfb_display_setting'] ) : 'both';
        update_option( 'rnfb_display_setting', $display_setting );

        // Redirect with success message
        wp_redirect( add_query_arg( [
            'page'    => 'reaction-notification-for-buddyboss',
            'message' => 'rnfb_updated',
        ], admin_url( 'admin.php' ) ) );
        exit;
    }

    /**
     * Reaction notification for buddyboss setting page
     */
    public function rnfb_menu_page( ) {

        add_submenu_page(
            'buddyboss-settings',
            __( 'Reaction Notifications', 'reaction-notification-for-buddyboss' ),
            __( 'Reaction Notifications', 'reaction-notification-for-buddyboss' ),
            'manage_options',
            'reaction-notification-for-buddyboss',
            [ $this, 'rnfb_menu_page_content' ]
        );
    }

    /**
     * Setting page content
     */
    public function rnfb_menu_page_content() {

        ?>
        <div class="wrap">
            <h1 style="margin-bottom: 20px;"><?php esc_html_e( 'Reaction Notifications Settings', 'reaction-notification-for-buddyboss' ); ?></h1>

            <?php
            if ( isset( $_GET['message'] ) && $_GET['message'] === 'rnfb_updated' ) {
                echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Settings saved successfully.', 'reaction-notification-for-buddyboss' ) . '</p></div>';
            }

            $selected_option = get_option( 'rnfb_display_setting', 'both' );
            ?>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="background: #fff; padding: 20px; width: 90%; border: 1px solid #ccd0d4; border-radius: 8px;">
                <input type="hidden" name="action" value="rnfb_save_settings">
                <?php wp_nonce_field( 'rnfb_save_settings_action', 'rnfb_save_settings_nonce' ); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="rnfb_display_setting"><?php esc_html_e( 'Notification Display Style', 'reaction-notification-for-buddyboss' ); ?></label>
                        </th>
                        <td>
                            <select name="rnfb_display_setting" id="rnfb_display_setting" class="regular-text">
                                <option value="name" <?php selected( $selected_option, 'name' ); ?>><?php esc_html_e( 'Reaction Name', 'reaction-notification-for-buddyboss' ); ?></option>
                                <option value="icon" <?php selected( $selected_option, 'icon' ); ?>><?php esc_html_e( 'Reaction Icon', 'reaction-notification-for-buddyboss' ); ?></option>
                                <option value="both" <?php selected( $selected_option, 'both' ); ?>><?php esc_html_e( 'Both', 'reaction-notification-for-buddyboss' ); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e( 'Choose how reactions should display in notifications.', 'reaction-notification-for-buddyboss' ); ?></p>
                        </td>
                    </tr>
                </table>

                <?php submit_button( __( 'Save Settings', 'reaction-notification-for-buddyboss' ) ); ?>
            </form>
        </div>
        <?php
    }

}

Admin_Hooks::instance();