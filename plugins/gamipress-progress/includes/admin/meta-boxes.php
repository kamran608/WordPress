<?php
/**
 * Meta Boxes
 *
 * @package GamiPress\Progress\Admin\Meta_Boxes
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register custom meta boxes used throughout GamiPress
 *
 * @since  1.0.0
 */
function gamipress_progress_meta_boxes() {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_gamipress_progress_';

    // Points Type
    $points_type_fields = array();

    $points_type_fields[$prefix . 'show'] = array(
        'name' 	=> __( 'Show progress of points types', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress of all points awards completed.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $points_type_fields = array_merge( $points_type_fields, gamipress_progress_get_fields( $prefix ) );

    gamipress_add_meta_box(
        'points-type-progress',
        __( 'Points Type Progress', 'gamipress-progress' ),
        'points-type',
        $points_type_fields
    );

    // Points awards

    $points_type_points_awards_fields = array();

    $points_type_points_awards_fields[$prefix . 'points_awards_show'] = array(
        'name' 	=> __( 'Show progress of points awards', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress on each points award.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $points_type_points_awards_fields = array_merge( $points_type_points_awards_fields, gamipress_progress_get_fields( $prefix . 'points_awards_' ) );

    gamipress_add_meta_box(
        'points-type-points-awards-progress',
        __( 'Points Awards Progress', 'gamipress-progress' ),
        'points-type',
        $points_type_points_awards_fields
    );

    // Points deducts

    $points_type_points_deducts_fields = array();

    $points_type_points_deducts_fields[$prefix . 'points_deducts_show'] = array(
        'name' 	=> __( 'Show progress of points deductions', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress on each points deduction.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $points_type_points_deducts_fields = array_merge( $points_type_points_deducts_fields, gamipress_progress_get_fields( $prefix . 'points_deducts_' ) );

    gamipress_add_meta_box(
        'points-type-points-deducts-progress',
        __( 'Points Deducts Progress', 'gamipress-progress' ),
        'points-type',
        $points_type_points_deducts_fields
    );

    // Achievements

    // Grab our achievement types as an array
    $achievement_types = gamipress_get_achievement_types_slugs();

    $achievement_fields = array();

    $achievement_fields[$prefix . 'show'] = array(
        'name' 	    => __( 'Show progress of achievement', 'gamipress-progress' ),
        'desc' 	    => __( 'Check this option to show a progress of all steps completed.', 'gamipress-progress' ),
        'type' 	    => 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $achievement_fields[$prefix . 'progress_calc'] = array(
        'name' 	    => __( 'How progress should be calculated?', 'gamipress-progress' ),
        'desc' 	=> __( '<strong>Steps completed:</strong> Progress will be calculated based on the number of steps completed, for example, if rank has 3 steps and user has completed 2 of this steps then achievement progress will be 2/3.', 'gamipress-progress' )
            . '<br>' . __( '<strong>Steps progress:</strong> Progress will be calculated based on the steps progress, for example, if rank has 3 steps related to comment on a post 3 times (3*3=9) and user has commented 5 times then achievement progress will be 5/9.', 'gamipress-progress' ),
        'type' 	    => 'radio',
        'options' 	=> array(
            'requirements_count'        => __( 'Steps completed', 'gamipress-progress' ),
            'requirements_completion'   => __( 'Steps progress', 'gamipress-progress' ),
        ),
        'default' => 'requirements_count',
        'classes' 	=> 'gamipress-progress-progress-calc',
    );

    $achievement_fields = array_merge( $achievement_fields, gamipress_progress_get_fields( $prefix ) );

    gamipress_add_meta_box(
        'achievement-progress',
        __( 'Achievement Progress', 'gamipress' ),
        $achievement_types,
        $achievement_fields
    );

    $achievement_steps_fields = array();

    $achievement_steps_fields[$prefix . 'steps_show'] = array(
        'name' 	=> __( 'Show progress of steps', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress on each step.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    // Steps

    $achievement_steps_fields = array_merge( $achievement_steps_fields, gamipress_progress_get_fields( $prefix . 'steps_' ) );

    gamipress_add_meta_box(
        'achievement-steps-progress',
        __( 'Achievement Steps Progress', 'gamipress' ),
        $achievement_types,
        $achievement_steps_fields
    );


    // Ranks

    // Grab our rank types as an array
    $rank_types = gamipress_get_rank_types_slugs();

    $rank_fields = array();

    $rank_fields[$prefix . 'show'] = array(
        'name' 	=> __( 'Show progress of rank', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress of all requirements completed.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $rank_fields[$prefix . 'progress_calc'] = array(
        'name' 	    => __( 'How progress should be calculated?', 'gamipress-progress' ),
        'desc' 	=> __( '<strong>Rank requirements completed:</strong> Progress will be calculated based on the number of rank requirements completed, for example, if rank has 3 requirements and user has completed 2 of this requirements then rank progress will be 2/3.', 'gamipress-progress' )
            . '<br>' . __( '<strong>Rank requirements progress:</strong> Progress will be calculated based on the rank requirements progress, for example, if rank has 3 requirements related to comment on a post 3 times (3*3=9) and user has commented 5 times then rank progress will be 5/9.', 'gamipress-progress' ),
        'type' 	    => 'radio',
        'options' 	=> array(
            'requirements_count'        => __( 'Rank requirements completed', 'gamipress-progress' ),
            'requirements_completion'   => __( 'Rank requirements progress', 'gamipress-progress' ),
        ),
        'default' => 'requirements_count',
        'classes' 	=> 'gamipress-progress-progress-calc',
    );

    $rank_fields = array_merge( $rank_fields, gamipress_progress_get_fields( $prefix ) );

    gamipress_add_meta_box(
        'rank-progress',
        __( 'Rank Progress', 'gamipress' ),
        $rank_types,
        $rank_fields
    );

    // Rank requirements

    $rank_requirements_fields = array();

    $rank_requirements_fields[$prefix . 'rank_requirements_show'] = array(
        'name' 	=> __( 'Show progress of requirements', 'gamipress-progress' ),
        'desc' 	=> __( 'Check this option to show a progress on each requirement.', 'gamipress-progress' ),
        'type' 	=> 'checkbox',
        'classes' 	=> 'gamipress-switch gamipress-progress-toggle',
    );

    $rank_requirements_fields = array_merge( $rank_requirements_fields, gamipress_progress_get_fields( $prefix . 'rank_requirements_' ) );

    gamipress_add_meta_box(
        'rank-requirements-progress',
        __( 'Rank Requirements Progress', 'gamipress' ),
        $rank_types,
        $rank_requirements_fields
    );

}
add_action( 'cmb2_admin_init', 'gamipress_progress_meta_boxes' );

/**
 * Setup an array of CMB2 fields used to setup the progress display
 *
 * @since  1.0.0
 *
 * @param string $prefix    Prefix to use on progress fields
 * @param string $context   Context where fields are being rendered (form|shortcode)
 *
 * @return array
 */
function gamipress_progress_get_fields( $prefix = '', $context = 'form' ) {

    $fields = array(

        // Preview

        $prefix . 'preview' => array(
            'name' 	=> __( 'Preview', 'gamipress-progress' ),
            'type' 	=> 'title',
            'after' => 'gamipress_progress_preview_after_cb',
            'classes' => 'gamipress-progress-preview'
        ),

        // Type

        $prefix . 'type' => array(
            'name' 	=> __( 'Choose the progress type', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'radio',
            'inline' 	=> true,
            'options' => array(
                'text'          => __( 'Text', 'gamipress-progress' ),
                'bar'           => __( 'Progress Bar', 'gamipress-progress' ),
                'radial-bar'    => __( 'Radial Progress Bar', 'gamipress-progress' ),
                'image'         => __( 'Image', 'gamipress-progress' ),
            ),
            'default' => 'text',
            'classes' => 'gamipress-progress-type'
        ),

        // Text settings
        // -----------------------------
        // Pattern ({current}/{total})

        $prefix . 'text_pattern' => array(
            'name' 	=> __( 'Pattern', 'gamipress-progress' ),
            'desc' 	=> __( 'Available template tags:', 'gamipress-progress' ) . gamipress_progress_get_pattern_tags_list_html(),
            'type' 	=> 'text',
            'default' => '({current}/{total})',
            'classes' => 'gamipress-progress-text-pattern'
        ),

        // Progress Bar settings
        // -----------------------------
        // Bar Color
        // Background Color
        // Text
        // Text Color
        // Text Format
        // Stripe Effect
        // Animate Bar

        $prefix . 'bar_color' => array(
            'name' 	=> __( 'Progress Bar Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#0098d7',
            'classes' => 'gamipress-progress-bar-color'
        ),
        $prefix . 'bar_background_color' => array(
            'name' 	=> __( 'Background Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#eeeeee',
            'classes' => 'gamipress-progress-bar-background-color'
        ),
        $prefix . 'bar_text' => array(
            'name' 	=> __( 'Show Progress Text', 'gamipress-progress' ),
            'type' 	=> 'checkbox',
            'classes' 	=> 'gamipress-switch gamipress-progress-bar-text',
        ),
        $prefix . 'bar_text_color' => array(
            'name' 	=> __( 'Progress Text Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#fff',
            'classes' => 'gamipress-progress-bar-text-color'
        ),
        $prefix . 'bar_text_format' => array(
            'name' 	=> __( 'Progress Text Format', 'gamipress-progress' ),
            'type' 	=> 'radio',
            'options' 	=> array(
                'percent' => __( 'Percent (60%)', 'gamipress-progress' ),
                'fraction' => __( 'Fraction (3/5)', 'gamipress-progress' )
            ),
            'inline' => true,
            'default' => 'percent',
            'classes' 	=> 'gamipress-progress-bar-text-format',
        ),
        $prefix . 'bar_stripe' => array(
            'name' 	=> __( 'Stripe Style', 'gamipress-progress' ),
            'type' 	=> 'checkbox',
            'classes' 	=> 'gamipress-switch gamipress-progress-bar-stripe',
        ),
        $prefix . 'bar_animate' => array(
            'name' 	=> __( 'Animate Stripe', 'gamipress-progress' ),
            'type' 	=> 'checkbox',
            'classes' 	=> 'gamipress-switch gamipress-progress-bar-animate',
        ),

        // Radial Progress Bar settings
        // -----------------------------
        // Bar Color
        // Background Color
        // Text
        // Text Color
        // Text Format
        // Text Background Color
        // Bar size

        $prefix . 'radial_bar_color' => array(
            'name' 	=> __( 'Progress Bar Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#0098d7',
            'classes' => 'gamipress-progress-radial-bar-color'
        ),
        $prefix . 'radial_bar_background_color' => array(
            'name' 	=> __( 'Progress Bar Background Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#eeeeee',
            'classes' => 'gamipress-progress-radial-bar-background-color'
        ),
        $prefix . 'radial_bar_text' => array(
            'name' 	=> __( 'Show Progress Text', 'gamipress-progress' ),
            'type' 	=> 'checkbox',
            'classes' 	=> 'gamipress-switch gamipress-progress-radial-bar-text',
        ),
        $prefix . 'radial_bar_text_color' => array(
            'name' 	=> __( 'Progress Text Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#000',
            'classes' => 'gamipress-progress-radial-bar-text-color'
        ),
        $prefix . 'radial_bar_text_format' => array(
            'name' 	=> __( 'Progress Text Format', 'gamipress-progress' ),
            'type' 	=> 'radio',
            'options' 	=> array(
                'percent' => __( 'Percent (60%)', 'gamipress-progress' ),
                'fraction' => __( 'Fraction (3/5)', 'gamipress-progress' )
            ),
            'inline' => true,
            'default' => 'percent',
            'classes' 	=> 'gamipress-progress-radial-bar-text-format',
        ),
        $prefix . 'radial_bar_text_background_color' => array(
            'name' 	=> __( 'Progress Percent Background Color', 'gamipress-progress' ),
            'desc' 	=> '',
            'type' 	=> 'colorpicker',
            'options' => array( 'alpha' => true ),
            'default' => '#fff',
            'classes' => 'gamipress-progress-radial-bar-text-background-color'
        ),
        $prefix . 'radial_bar_size' => array(
            'name' => __( 'Radial Bar Size', 'gamipress-progress' ),
            'desc' => __( 'Maximum dimensions for the radial bar.', 'gamipress-progress' ),
            'type' => 'text',
            'attributes' => array(
                'type' => 'number'
            ),
            'classes' => 'gamipress-progress-radial-bar-size',
            'default' => 100
        ),

        // Image settings
        // -----------------------------
        // Completed image
        // Completed image size
        // Uncompleted image
        // Uncompleted image size

        $prefix . 'image_complete' => array(
            'name' 	=> __( 'Complete Image', 'gamipress-progress' ),
            'type' 	=> 'file',
            'options' => array(
                'url' => false,
            ),
            'classes' => 'gamipress-progress-image-complete'
        ),
        $prefix . 'image_complete_size' => array(
            'name' => __( 'Complete Image Size', 'gamipress-progress' ),
            'desc' => __( 'Maximum dimensions for the complete image.', 'gamipress-progress' ),
            'type' => 'size',
            'classes' => 'gamipress-progress-image-complete-size',
            'default' => array( 'width' => 50, 'height' => 50 )
        ),
        $prefix . 'image_incomplete' => array(
            'name' 	=> __( 'Incomplete Image', 'gamipress-progress' ),
            'type' 	=> 'file',
            'options' => array(
                'url' => false,
            ),
            'classes' => 'gamipress-progress-image-incomplete'
        ),
        $prefix . 'image_incomplete_size' => array(
            'name' => __( 'incomplete Image Size', 'gamipress-progress' ),
            'desc' => __( 'Maximum dimensions for the incomplete image.', 'gamipress-progress' ),
            'type' => 'size',
            'classes' => 'gamipress-progress-image-incomplete-size',
            'default' => array( 'width' => 50, 'height' => 50 )
        ),
    );

    // Shortcode safe fields
    if( $context === 'shortcode' ) {

        // Remove size fields
        unset( $fields[ $prefix . 'image_complete_size' ] );
        unset( $fields[ $prefix . 'image_incomplete_size' ] );

        $fields = array_merge( $fields, array(

            // Image settings
            // -----------------------------
            // Completed image (as text field)
            // Completed image width
            // Completed image height
            // Uncompleted image (as text field)
            // Uncompleted image width
            // Uncompleted image height

            $prefix . 'image_complete' => array(
                'name' 	=> __( 'Complete Image URL', 'gamipress-progress' ),
                'type' 	=> 'text',
                'classes' => 'gamipress-progress-image-complete'
            ),
            $prefix . 'image_complete_width' => array(
                'name' => __( 'Complete Image Width', 'gamipress-progress' ),
                'desc' => __( 'Maximum width for the complete image.', 'gamipress-progress' ),
                'type' => 'text',
                'attributes' => array(
                    'type' => 'number'
                ),
                'classes' => 'gamipress-progress-image-complete-width',
                'default' => '50'
            ),
            $prefix . 'image_complete_height' => array(
                'name' => __( 'Complete Image Height', 'gamipress-progress' ),
                'desc' => __( 'Maximum height for the complete image.', 'gamipress-progress' ),
                'type' => 'text',
                'attributes' => array(
                    'type' => 'number'
                ),
                'classes' => 'gamipress-progress-image-complete-height',
                'default' => '50'
            ),
            $prefix . 'image_incomplete' => array(
                'name' 	=> __( 'Incomplete Image URL', 'gamipress-progress' ),
                'type' 	=> 'text',
                'classes' => 'gamipress-progress-image-incomplete'
            ),
            $prefix . 'image_incomplete_width' => array(
                'name' => __( 'Incomplete Image Width', 'gamipress-progress' ),
                'desc' => __( 'Maximum width for the incomplete image.', 'gamipress-progress' ),
                'type' => 'text',
                'attributes' => array(
                    'type' => 'number'
                ),
                'classes' => 'gamipress-progress-image-incomplete-width',
                'default' => '50'
            ),
            $prefix . 'image_incomplete_height' => array(
                'name' => __( 'Incomplete Image Height', 'gamipress-progress' ),
                'desc' => __( 'Maximum height for the incomplete image.', 'gamipress-progress' ),
                'type' => 'text',
                'attributes' => array(
                    'type' => 'number'
                ),
                'classes' => 'gamipress-progress-image-incomplete-height',
                'default' => '50'
            ),

        ) );
    }

    return $fields;
}

/**
 * Setup an array of fields values to setup the progress display
 *
 * @since  1.0.0
 *
 * @param int       $object_id  Object ID from retrieve the configured progress fields
 * @param string    $prefix     Prefix used on progress fields
 *
 * @return array
 */
function gamipress_progress_get_fields_values( $object_id, $prefix ) {

    $progress = array();

    $progress['type']                                   = gamipress_get_post_meta( $object_id, $prefix . 'type', true );

    if( $progress['type'] === 'text' ) {

        $progress['text_pattern']                       = gamipress_get_post_meta( $object_id, $prefix . 'text_pattern', true );

    } else if( $progress['type'] === 'bar' ) {

        $progress['bar_color']                          = gamipress_get_post_meta( $object_id, $prefix . 'bar_color', true );
        $progress['bar_background_color']               = gamipress_get_post_meta( $object_id, $prefix . 'bar_background_color', true );
        $progress['bar_text']                           = (bool) gamipress_get_post_meta( $object_id, $prefix . 'bar_text', true );
        $progress['bar_text_color']                     = gamipress_get_post_meta( $object_id, $prefix . 'bar_text_color', true );
        $progress['bar_text_format']                    = gamipress_get_post_meta( $object_id, $prefix . 'bar_text_format', true );
        $progress['bar_stripe']                         = (bool) gamipress_get_post_meta( $object_id, $prefix . 'bar_stripe', true );
        $progress['bar_animate']                        = (bool) gamipress_get_post_meta( $object_id, $prefix . 'bar_animate', true );

        if( empty( $progress['bar_text_format'] ) ) {
            $progress['bar_text_format'] = 'percent';
        }

    } else if( $progress['type'] === 'radial-bar' ) {

        $progress['radial_bar_color']                   = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_color', true );
        $progress['radial_bar_background_color']        = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_background_color', true );
        $progress['radial_bar_text']                    = (bool) gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_text', true );
        $progress['radial_bar_text_color']              = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_text_color', true );
        $progress['radial_bar_text_format']             = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_text_format', true );
        $progress['radial_bar_text_background_color']   = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_text_background_color', true );
        $progress['radial_bar_size']                    = gamipress_get_post_meta( $object_id, $prefix . 'radial_bar_size', true );

        if( empty( $progress['radial_bar_text_format'] ) ) {
            $progress['radial_bar_text_format'] = 'percent';
        }

        // Check radial bar size
        if( empty( $progress['radial_bar_size'] ) || absint( $progress['radial_bar_size'] ) === 0 ) {
            $progress['radial_bar_size'] = 100;
        }

    } else if( $progress['type'] === 'image' ) {

        $progress['image_complete']                     = gamipress_get_post_meta( $object_id, $prefix . 'image_complete', true );
        $progress['image_complete_size']                = gamipress_get_post_meta( $object_id, $prefix . 'image_complete_size', true );
        $progress['image_incomplete']                   = gamipress_get_post_meta( $object_id, $prefix . 'image_incomplete', true );
        $progress['image_incomplete_size']              = gamipress_get_post_meta( $object_id, $prefix . 'image_incomplete_size', true );

        // Check image sizes
        if( empty( $progress['image_complete_size'] ) || ! is_array( $progress['image_complete_size'] ) ) {
            $progress['image_complete_size'] = array( 'width' => 50, 'height' => 50 );
        }

        if( empty( $progress['image_incomplete_size'] ) || ! is_array( $progress['image_incomplete_size'] ) ) {
            $progress['image_incomplete_size'] = array( 'width' => 50, 'height' => 50 );
        }

    }

    return $progress;

}

/**
 * Setup an array of fields values from shortcode attributes used to setup the progress display
 *
 * @since  1.1.8
 *
 * @param string    $prefix     Prefix used on progress fields
 * @param array     $atts       Shortcode attributes
 *
 * @return array
 */
function gamipress_progress_get_fields_values_from_shortcode( $prefix, $atts ) {

    $progress = array();

    $progress['type']                                   = $atts['type'];

    if( $progress['type'] === 'text' ) {

        $progress['text_pattern']                       = $atts['text_pattern'];

    } else if( $progress['type'] === 'bar' ) {

        $progress['bar_color']                          = $atts['bar_color'];
        $progress['bar_background_color']               = $atts['bar_background_color'];
        $progress['bar_text']                           = ( $atts['bar_text'] === 'yes' );
        $progress['bar_text_color']                     = $atts['bar_text_color'];
        $progress['bar_text_format']                    = $atts['bar_text_format'];
        $progress['bar_stripe']                         = ( $atts['bar_stripe'] === 'yes' );
        $progress['bar_animate']                        = ( $atts['bar_animate'] === 'yes' );

        if( empty( $progress['bar_text_format'] ) ) {
            $progress['bar_text_format'] = 'percent';
        }

    } else if( $progress['type'] === 'radial-bar' ) {

        $progress['radial_bar_color']                   = $atts['radial_bar_color'];
        $progress['radial_bar_background_color']        = $atts['radial_bar_background_color'];
        $progress['radial_bar_text']                    = ( $atts['radial_bar_text'] === 'yes' );
        $progress['radial_bar_text_color']              = $atts['radial_bar_text_color'];
        $progress['radial_bar_text_format']             = $atts['radial_bar_text_format'];
        $progress['radial_bar_text_background_color']   = $atts['radial_bar_text_background_color'];
        $progress['radial_bar_size']                    = $atts['radial_bar_size'];

        if( empty( $progress['radial_bar_text_format'] ) ) {
            $progress['radial_bar_text_format'] = 'percent';
        }

        // Check radial bar size
        if( empty( $progress['radial_bar_size'] ) || absint( $progress['radial_bar_size'] ) === 0 ) {
            $progress['radial_bar_size'] = 100;
        }

    } else if( $progress['type'] === 'image' ) {

        $progress['image_complete']                     = $atts['image_complete'];
        $progress['image_complete_size']                = array();
        $progress['image_incomplete']                   = $atts['image_incomplete'];
        $progress['image_incomplete_size']              = array();

        // Setup image sizes
        $progress['image_complete_size'] = array(
            'width' => $atts['image_complete_width'],
            'height' => $atts['image_complete_height']
        );

        $progress['image_incomplete_size'] = array(
            'width' => $atts['image_incomplete_width'],
            'height' => $atts['image_incomplete_height']
        );

    }

    return $progress;

}

/**
 * Setup an array of default values of progress fields
 *
 * @since  1.1.8
 *
 * @param string $prefix    Prefix to use on progress fields
 * @param string $context   Context where fields are being rendered (form|shortcode)
 *
 * @return array
 */
function gamipress_progress_get_fields_defaults( $prefix = '', $context = 'form' ) {

    $fields = gamipress_progress_get_fields( $prefix, $context );
    $defaults = array();

    foreach( $fields as $field_id => $field ) {

        // Skip tittles
        if( $field['type'] === 'title' ) continue;

        // initialize default if not set
        if( ! isset( $field['default'] ) ) {

            // Default on checkboxes
            if( $field['type'] === 'checkbox' )
                $field['default'] = 'no';

            $field['default'] = '';

        }

        $defaults[$field_id] = $field['default'];

    }

    return $defaults;
}

function gamipress_progress_preview_after_cb( $field_args, $field ) {
    ?>
    <div class="gamipress-progress-preview-container">

        <div class="gamipress-progress-preview-text"></div>

        <div class="gamipress-progress-preview-bar">
            <div class="gamipress-progress-bar">
                <div class="gamipress-progress-bar-completed" role="progressbar" style="width: 60%">
                    <span>60%</span>
                </div>
            </div>
        </div>

        <div class="gamipress-progress-preview-radial-bar">
            <div class="gamipress-progress-radial-bar" data-progress="60%">
                <span class="gamipress-progress-radial-bar-overlay">
                    <span>60%</span>
                </span>
            </div>
        </div>

        <div class="gamipress-progress-preview-image"></div>
    </div>

    <p class="cmb2-metabox-description"><?php _e( 'This preview renders a sample progress of 3 of 5.', 'gamipress-progress' ); ?></p>
    <?php
}