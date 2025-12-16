<?php
/**
 * Tools
 *
 * @package GamiPress\Progress\Admin\Tools
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Import setup tool meta value
 *
 * @since  1.0.0
 *
 * @param mixed $meta_value
 * @param string $meta_key
 * @param array $post
 *
 * @return mixed
 */
function gamipress_progress_import_setup_tool_post_meta_value( $meta_value, $meta_key, $post ) {

    $prefix = '_gamipress_progress_';

    // Check if is image complete and incomplete metas and is a external URL to import the external file to this install
    if(
        ! empty( $meta_value ) && ! is_numeric( $meta_value )
        && gamipress_starts_with( $meta_key, '_gamipress_progress_' )
        && ( gamipress_ends_with( $meta_key, '_image_complete' ) || gamipress_ends_with( $meta_key, '_image_incomplete' ) )
    ) {

        $meta_value = gamipress_import_attachment( $meta_value );

    }
    return $meta_value;
}
add_filter( 'gamipress_import_setup_tool_post_meta_value', 'gamipress_progress_import_setup_tool_post_meta_value', 10, 3 );