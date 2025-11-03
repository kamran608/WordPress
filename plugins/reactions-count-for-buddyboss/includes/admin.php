<?php 

/**
 * Backend template for LQC
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class BRC_Backend
 */
class BRC_Backend {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof BRC_Backend ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        add_action( 'admin_enqueue_scripts', [ $this, 'brc_backend_enqueue_scripts' ] );
        add_action( 'admin_menu', [ $this, 'brc_reactions_count_menu_page' ] );
        add_action( 'admin_post_brc_settings_action', [ $this, 'brc_save_settings' ] );
        add_action( 'admin_notices', [ $this, 'brc_display_admin_notices' ] );
        add_filter( 'plugin_action_links_'. BRC_BASE_DIR, [ $this, 'brc_plugin_setting_links' ] );
    }

    /**
     * Display setting option in plugin page
     */
    public function brc_plugin_setting_links( $links ) {

        $settings_link = '<a href="'. admin_url( 'admin.php?page=reactions-count-for-buddyboss' ) .'">'. __( 'Settings', 'reactions-count-for-buddyboss' ) .'</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }

    /**
     * Display save settings admin notices
     */
    public function brc_display_admin_notices() {

        if( isset( $_GET['message'] ) && 'brc_updated' == $_GET['message'] ) {

            $class = 'notice notice-success is-dismissible';
            $message = __( 'Reactions Setting Saved.', 'reactions-count-for-buddyboss' );
            printf ( '<div id="message" class="%s"> <p>%s</p></div>', $class, $message );
        }
    }

    /**
     * Save reactions count settings
     */
    public function brc_save_settings() {

        if( !isset( $_POST['brc_save_settings'] ) ) {
            return false;
        }

        $brc_likes_count = isset( $_POST['brc_likes_count'] ) ? $_POST['brc_likes_count'] : 'off';

        update_option( 'brc_likes_count', $brc_likes_count );

        wp_redirect( add_query_arg( 'message', 'brc_updated', admin_url( 'admin.php?page=reactions-count-for-buddyboss' ) ) );
        exit;
    }

    /**
     * Display reactions count menu page
     */
    public function brc_reactions_count_menu_page() {

        add_submenu_page(
            'buddyboss-settings',
            __( 'Reactions Count', 'reactions-count-for-buddyboss' ),
            __( 'Reactions Count', 'reactions-count-for-buddyboss' ),
            'manage_options',
            'reactions-count-for-buddyboss',
            [ $this, 'brc_reactions_count_page_content' ]
        );
    }

    /**
     * Reactions count menu page content HTML
     */
    public function brc_reactions_count_page_content() {

        $brc_likes_count = get_option( 'brc_likes_count' );

        $likes_checked = false;
        if( 'on' == $brc_likes_count ) {
            $likes_checked = true;
        }

        ?>
        <h2><?php echo __( 'Buddyboss Reactions Settings', 'reactions-count-for-buddyboss' ); ?></h2>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>" class="brc-reactions-form">
            <div class="brc-reactions-count-wrap">
                <div class="brc-likes-count-wrap">
                    <label class="brc-label"><?php echo __( 'Display Like Count:', 'reactions-count-for-buddyboss' ); ?></label>
                    <label class="switch">
                        <input type="checkbox" name="brc_likes_count" <?php echo checked( $likes_checked, true, true ); ?> >
                        <span class="slider round"></span>
                    </label>
                    <span class="dashicons dashicons-info-outline brc-info-icon"></span>
                    <span class="brc-info-icon text"><?php echo __( 'If you enable it, you will see the like count displayed on the BuddyBoss activity post.', 'reactions-count-for-buddyboss' ); ?></span>
                </div>
                <input type="hidden" name="action" value="brc_settings_action">
                <input type="submit" class="button button-primary brc-save-button" name="brc_save_settings" value="<?php echo __( 'Save Settings', 'reactions-count-for-buddyboss' ); ?>">
            </div>
        </form>
        <?php
    }

    /**
     *  Backend Enqueue script
     */
    public function brc_backend_enqueue_scripts() {

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_style( 'BRC_backend_style', BRC_ASSETS_URL.'css/backend.css', [], $random_number );
        wp_enqueue_script( 'BRC_backend_js', BRC_ASSETS_URL.'js/backend.js', [ 'jquery' ], $random_number, true );

        wp_localize_script( 'BRC_backend_js', 'BRC', 
            [
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ] 
        );
    }
}

BRC_Backend::instance();