<?php
/**
 * Shortcodes
 *
 * @package     GamiPress\Progress\Shortcodes
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Shortcodes
require_once GAMIPRESS_PROGRESS_DIR . 'includes/shortcodes/gamipress_progress.php';

/**
 * Adds the "progress" parameter to [gamipress_achievement]
 *
 * @since 1.0.0
 *
 * @param array $fields
 *
 * @return mixed
 */
function gamipress_progress_achievement_shortcode_fields( $fields ) {

    $fields['progress'] = array(
        'name'        => __( 'Show Progress', 'gamipress-progress' ),
        'description' => __( 'Display the configured progress on the achievement and in their steps.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' => 'gamipress-switch',
        'default' => 'yes'
    );

    return $fields;

}
add_filter( 'gamipress_gamipress_achievement_shortcode_fields', 'gamipress_progress_achievement_shortcode_fields' );

/**
 * Adds the "progress" parameter to [gamipress_achievement] defaults
 *
 * @since 1.1.3
 *
 * @param array $defaults
 *
 * @return array
 */
function gamipress_progress_achievement_shortcode_defaults( $defaults ) {

    $defaults['progress'] = 'yes';

    return $defaults;

}
add_filter( 'gamipress_achievement_shortcode_defaults', 'gamipress_progress_achievement_shortcode_defaults' );

/**
 * Adds the "progress" parameter to [gamipress_points_types]
 *
 * @since 1.0.0
 *
 * @param array $fields
 *
 * @return mixed
 */
function gamipress_progress_points_types_shortcode_fields( $fields ) {

    $fields['progress'] = array(
        'name'        => __( 'Show Progress', 'gamipress-progress' ),
        'description' => __( 'Display the configured progress on each points type(s) and in their points awards.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' => 'gamipress-switch',
        'default' => 'yes'
    );

    return $fields;

}
add_filter( 'gamipress_gamipress_points_types_shortcode_fields', 'gamipress_progress_points_types_shortcode_fields' );

/**
 * Adds the "progress" parameter to [gamipress_rank]
 *
 * @since 1.0.4
 *
 * @param array $fields
 *
 * @return array
 */
function gamipress_progress_rank_shortcode_fields( $fields ) {

    $fields['progress'] = array(
        'name'        => __( 'Show Progress', 'gamipress-progress' ),
        'description' => __( 'Display the configured progress on the rank and in their requirements.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' => 'gamipress-switch',
        'default' => 'yes'
    );

    return $fields;

}
add_filter( 'gamipress_gamipress_rank_shortcode_fields', 'gamipress_progress_rank_shortcode_fields' );

/**
 * Adds the "progress" parameter to [gamipress_rank] defaults
 *
 * @since 1.1.3
 *
 * @param array $defaults
 *
 * @return array
 */
function gamipress_progress_rank_shortcode_defaults( $defaults ) {

    $defaults['progress'] = 'yes';

    return $defaults;

}
add_filter( 'gamipress_rank_shortcode_defaults', 'gamipress_progress_rank_shortcode_defaults' );