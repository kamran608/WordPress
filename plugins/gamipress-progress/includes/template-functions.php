<?php
/**
 * Template Functions
 *
 * @package GamiPress\Progress\Template_Functions
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register plugin templates directory on GamiPress template engine
 *
 * @since 1.0.0
 *
 * @param array $file_paths
 *
 * @return array
 */
function gamipress_progress_template_paths( $file_paths ) {

    $file_paths[] = trailingslashit( get_stylesheet_directory() ) . 'gamipress/progress/';
    $file_paths[] = trailingslashit( get_template_directory() ) . 'gamipress/progress/';
    $file_paths[] =  GAMIPRESS_PROGRESS_DIR . 'templates/';

    return $file_paths;

}
add_filter( 'gamipress_template_paths', 'gamipress_progress_template_paths' );

/**
 * Render the received progress
 *
 * @since 1.0.0
 *
 * @param array $progress
 */
function gamipress_progress_render_progress( $progress ) {

    global $gamipress_progress_template_args;

    // let's ensure progress is well setup
    $progress['current'] = isset( $progress['current'] ) ? absint( $progress['current'] ) : 0;
    $progress['total'] = isset( $progress['total'] ) ? absint( $progress['total'] ) : 0;

    // Prevent more progress than total
    if( $progress['current'] > $progress['total'] ) {
        $progress['current'] = $progress['total'];
    }

    $gamipress_progress_template_args = $progress;

    gamipress_get_template_part( 'progress', $progress['type'] );

}

/**
 * Get a string with the desired pattern tags html markup
 *
 * @since  1.0.0
 *
 * @return string Pattern tags html markup
 */
function gamipress_progress_get_pattern_tags_list_html() {
    return '<ul class="gamipress-pattern-tags-list gamipress-progress-pattern-tags-list">'
        . '<li><code>{current}</code> - ' . __( 'Current progress', 'gamipress-progress' ) . '</li></li>'
        . '<li><code>{total}</code> - ' . __( 'Total progress', 'gamipress-progress' ) . '</li></li>'
    . '</ul>';
}

/**
 * Return the radial bar gradient css rule
 *
 * Based on https://codepen.io/geedmo/pen/InFfd
 *
 * @since  1.0.0
 *
 * @param float     $percent
 * @param string    $bar_color
 * @param string    $background_color
 *
 * @return string
 */
function gamipress_progress_radial_bar_gradient( $percent, $bar_color, $background_color ) {

    $left_degrees = ( $percent < 50 ) ? 90 : -90 + ( 3.6 * ( $percent - 50 ) );
    $right_degrees = ( $percent < 50 ) ? 90 + ( 3.6 * $percent ) : 270;

    $left_bar_color = ( $percent < 50 ) ? $background_color : $bar_color;

    return "linear-gradient({$left_degrees}deg, {$left_bar_color} 50%, transparent 50%, transparent), "
        . "linear-gradient({$right_degrees}deg, {$bar_color} 50%, {$background_color} 50%, {$background_color})";

}