<?php
/**
 * Admin
 *
 * @package GamiPress\Progress\Admin
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

require_once GAMIPRESS_PROGRESS_DIR . 'includes/admin/meta-boxes.php';
require_once GAMIPRESS_PROGRESS_DIR . 'includes/admin/tools.php';

/**
 * Shortcut function to get plugin options
 *
 * @since  1.0.0
 *
 * @param string    $option_name
 * @param bool      $default
 *
 * @return mixed
 */
function gamipress_progress_get_option( $option_name, $default = false ) {

    $prefix = 'gamipress_progress_';

    return gamipress_get_option( $prefix . $option_name, $default );
}

/**
 * GamiPress Progress Licensing meta box
 *
 * @since  1.0.0
 *
 * @param $meta_boxes
 *
 * @return mixed
 */
function gamipress_progress_licenses_meta_boxes( $meta_boxes ) {

    $meta_boxes['gamipress-progress-license'] = array(
        'title' => __( 'GamiPress Progress', 'gamipress-progress' ),
        'fields' => array(
            'gamipress_progress_license' => array(
                'name' => __( 'License', 'gamipress-progress' ),
                'type' => 'edd_license',
                'file' => GAMIPRESS_PROGRESS_FILE,
                'item_name' => 'Progress',
            ),
        )
    );

    return $meta_boxes;

}
add_filter( 'gamipress_settings_licenses_meta_boxes', 'gamipress_progress_licenses_meta_boxes' );

/**
 * GamiPress Progress automatic updates
 *
 * @since  1.0.0
 *
 * @param array $automatic_updates_plugins
 *
 * @return array
 */
function gamipress_progress_automatic_updates( $automatic_updates_plugins ) {

    $automatic_updates_plugins['gamipress-progress'] = __( 'Progress', 'gamipress-progress' );

    return $automatic_updates_plugins;
}
add_filter( 'gamipress_automatic_updates_plugins', 'gamipress_progress_automatic_updates' );