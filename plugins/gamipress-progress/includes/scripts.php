<?php
/**
 * Scripts
 *
 * @package     GamiPress\Progress\Scripts
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_progress_register_scripts() {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Stylesheets
    wp_register_style( 'gamipress-progress-css', GAMIPRESS_PROGRESS_URL . 'assets/css/gamipress-progress' . $suffix . '.css', array( ), GAMIPRESS_PROGRESS_VER, 'all' );

}
add_action( 'init', 'gamipress_progress_register_scripts' );

/**
 * Enqueue frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_progress_enqueue_scripts( $hook = null ) {

    // Stylesheets
    wp_enqueue_style( 'gamipress-progress-css' );

}
add_action( 'wp_enqueue_scripts', 'gamipress_progress_enqueue_scripts', 100 );

/**
 * Register admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_progress_admin_register_scripts() {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Stylesheets
    wp_register_style( 'gamipress-progress-admin-css', GAMIPRESS_PROGRESS_URL . 'assets/css/gamipress-progress-admin' . $suffix . '.css', array( ), GAMIPRESS_PROGRESS_VER, 'all' );

    // Scripts
    wp_register_script( 'gamipress-progress-admin-js', GAMIPRESS_PROGRESS_URL . 'assets/js/gamipress-progress-admin' . $suffix . '.js', array( 'jquery', 'wp-color-picker' ), GAMIPRESS_PROGRESS_VER, true );
    wp_register_script( 'gamipress-progress-shortcode-editor-js', GAMIPRESS_PROGRESS_URL . 'assets/js/gamipress-progress-shortcode-editor' . $suffix . '.js', array( 'jquery', 'wp-color-picker' ), GAMIPRESS_PROGRESS_VER, true );
    wp_register_script( 'gamipress-progress-admin-widgets-js', GAMIPRESS_PROGRESS_URL . 'assets/js/gamipress-progress-admin-widgets' . $suffix . '.js', array( 'jquery', 'wp-color-picker' ), GAMIPRESS_PROGRESS_VER, true );

}
add_action( 'admin_init', 'gamipress_progress_admin_register_scripts' );

/**
 * Enqueue admin scripts
 *
 * @since       1.0.0
 * @return      void
 */
function gamipress_progress_admin_enqueue_scripts( $hook ) {

    // Stylesheets
    wp_enqueue_style( 'gamipress-progress-admin-css' );

    wp_enqueue_script( 'wp-color-picker' );

    // Scripts
    wp_enqueue_script( 'gamipress-progress-admin-js' );
    wp_enqueue_script( 'gamipress-progress-shortcode-editor-js' );

    // Widgets scripts
    if( $hook === 'widgets.php' ) {
        wp_enqueue_script( 'gamipress-progress-admin-widgets-js' );
    }

}
add_action( 'admin_enqueue_scripts', 'gamipress_progress_admin_enqueue_scripts', 100 );

/**
 * Enqueue Gutenberg block assets for backend editor
 *
 * @since 1.1.8
 */
function gamipress_progress_blocks_editor_assets() {

    // Use minified libraries if SCRIPT_DEBUG is turned off
    $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    // Styles
    wp_enqueue_style( 'gamipress-progress-css', GAMIPRESS_PROGRESS_URL . 'assets/css/gamipress-progress' . $suffix . '.css', array( 'wp-edit-blocks' ), GAMIPRESS_PROGRESS_VER );
}
add_action( 'enqueue_block_editor_assets', 'gamipress_progress_blocks_editor_assets' );