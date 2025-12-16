<?php
/**
 * Blocks
 *
 * @package     GamiPress\Progress\Blocks
 * @since       1.1.8
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register plugin block icons
 *
 * @since 1.1.8
 *
 * @param array $icons
 *
 * @return array
 */
function gamipress_progress_block_icons( $icons ) {

    $icons['progress'] =
        '<svg width="24" height="24" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" >
            <path d="m 5,10 c 0,2.76 2.24,5 5,5 2.76,0 5,-2.24 5,-5 C 15,7.24 12.76,5 10,5 V 2 c 4.42,0 8,3.58 8,8 0,4.42 -3.58,8 -8,8 -4.42,0 -8,-3.58 -8,-8 z" />    
        </svg>';

    return $icons;
}
add_filter( 'gamipress_block_icons', 'gamipress_progress_block_icons' );

/**
 * Turn select2 fields into 'post' or 'user' field types
 *
 * @since 1.1.8
 *
 * @param array                 $fields
 * @param GamiPress_Shortcode   $shortcode
 *
 * @return array
 */
function gamipress_progress_block_fields( $fields, $shortcode ) {

    switch ( $shortcode->slug ) {
        case 'gamipress_progress':
            // Achievement ID
            $fields['achievement']['type'] = 'post';
            $fields['achievement']['post_type'] = gamipress_get_achievement_types_slugs();
            // Rank ID
            $fields['rank']['type'] = 'post';
            $fields['rank']['post_type'] = gamipress_get_rank_types_slugs();

            // Form conditional display
            $fields['points']['conditions'] = array( 'from' => 'points' );
            $fields['points_type']['conditions'] = array( 'from' => array( 'points', 'points_type' ) );
            $fields['achievement_type']['conditions'] = array( 'from' => 'achievement_type' );
            $fields['achievement']['conditions'] = array( 'from' => 'achievement' );
            $fields['rank_type']['conditions'] = array( 'from' => array( 'rank_type', 'current_rank' ) );
            $fields['rank']['conditions'] = array( 'from' => 'rank' );

            // Remove preview since block can be previewed yet
            unset( $fields['preview'] );

            // Type conditional display

            // Text fields
            $fields['text_pattern']['conditions'] = array( 'type' => 'text' );

            // Bar fields
            $fields['bar_color']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_background_color']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_text']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_text_color']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_text_format']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_stripe']['conditions'] = array( 'type' => 'bar' );
            $fields['bar_animate']['conditions'] = array( 'type' => 'bar' );

            // Radial bar fields
            $fields['radial_bar_color']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_background_color']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_text']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_text_color']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_text_format']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_text_background_color']['conditions'] = array( 'type' => 'radial-bar' );
            $fields['radial_bar_size']['conditions'] = array( 'type' => 'radial-bar' );

            // Image fields
            $fields['image_complete']['conditions'] = array( 'type' => 'image' );
            //$fields['image_complete_size']['conditions'] = array( 'type' => 'image' );
            $fields['image_complete_width']['conditions'] = array( 'type' => 'image' );
            $fields['image_complete_height']['conditions'] = array( 'type' => 'image' );
            $fields['image_incomplete']['conditions'] = array( 'type' => 'image' );
            //$fields['image_incomplete_size']['conditions'] = array( 'type' => 'image' );
            $fields['image_incomplete_width']['conditions'] = array( 'type' => 'image' );
            $fields['image_incomplete_height']['conditions'] = array( 'type' => 'image' );


            break;
    }

    return $fields;

}
add_filter( 'gamipress_get_block_fields', 'gamipress_progress_block_fields', 11, 2 );
