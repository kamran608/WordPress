<?php
/**
 * GamiPress Progress Shortcode
 *
 * @package     GamiPress\Progress\Shortcodes\Shortcode\GamiPress_Progress
 * @since       1.1.8
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the [gamipress_progress] shortcode.
 *
 * @since 1.1.8
 */
function gamipress_register_progress_shortcode() {

    $progress_fields = gamipress_progress_get_fields( '', 'shortcode' );

    gamipress_register_shortcode( 'gamipress_progress', array(
        'name'              => __( 'Progress', 'gamipress-progress' ),
        'description'       => __( 'Render progress of a desired element or progress of a custom goal.', 'gamipress-progress' ),
        'output_callback'   => 'gamipress_progress_shortcode',
        'icon'              => 'progress',
        'tabs' => array(
            'general' => array(
                'icon' => 'dashicons-admin-generic',
                'title' => __( 'General', 'gamipress-progress' ),
                'fields' => array(
                    'from',
                    'points',
                    'points_type',
                    'achievement_type',
                    'achievement',
                    'rank_type',
                    'rank',
                    'current_user',
                    'user_id',
                ),
            ),
            'style' => array(
                'icon' => 'dashicons-admin-appearance',
                'title' => __( 'Style', 'gamipress-progress' ),
                'fields' => array_keys( $progress_fields ),
            ),
        ),
        'fields'            => array_merge( array(
            'from' => array(
                'name'              => __( 'From', 'gamipress-progress' ),
                'desc'              => __( 'Choose from progress will be calculated.', 'gamipress-progress' ),
                'type'              => 'select',
                'options'           => array(
                    ''                  => __( 'Choose an option', 'gamipress-progress' ),
                    'points_type'       => __( 'Points Type', 'gamipress-progress' ),
                    'points'            => __( 'Points', 'gamipress-progress' ),
                    'achievement_type'  => __( 'Achievement Type', 'gamipress-progress' ),
                    'achievement'       => __( 'Achievement', 'gamipress-progress' ),
                    'rank_type'         => __( 'Rank Type', 'gamipress-progress' ),
                    'rank'              => __( 'Rank', 'gamipress-progress' ),
                    'current_rank'      => __( 'Current User Rank', 'gamipress-progress' ),
                ),
                'default'           => '',
            ),
            'points' => array(
                'name'              => __( 'Points amount', 'gamipress-progress' ),
                'desc'              => __( 'Set the points amount.', 'gamipress-progress' ),
                'type'              => 'text',
                'attributes' 	    => array(
                    'type'  => 'number',
                ),
                'default'           => '100',
            ),
            'points_type' => array(
                'name'              => __( 'Points Type', 'gamipress-progress' ),
                'desc'              => __( 'Choose the points type.', 'gamipress-progress' ),
                'type'              => 'select',
                'classes' 	        => 'gamipress-selector',
                'attributes' 	    => array(
                    'data-placeholder'  => __( 'Select a points type', 'gamipress-progress' ),
                ),
                'option_none'       => true,
                'option_all'        => false,
                'default'           => '',
                'options_cb'        => 'gamipress_options_cb_points_types',
            ),
            'achievement_type' => array(
                'name'              => __( 'Achievement Type', 'gamipress-progress' ),
                'desc'              => __( 'Choose the achievement type.', 'gamipress-progress' ),
                'type'              => 'select',
                'classes' 	        => 'gamipress-selector',
                'attributes' 	    => array(
                    'data-placeholder'  => __( 'Select an achievement type', 'gamipress-progress' ),
                ),
                'option_none'       => true,
                'option_all'        => false,
                'default'           => '',
                'options_cb'        => 'gamipress_options_cb_achievement_types',
            ),
            'achievement' => array(
                'name'              => __( 'Achievement', 'gamipress-progress' ),
                'description'       => __( 'The achievement to render progress.', 'gamipress-progress' ),
                'shortcode_desc'    => __( 'The ID of the achievement to render progress.', 'gamipress-progress' ),
                'type'              => 'select',
                'classes' 	        => 'gamipress-post-selector',
                'attributes' 	    => array(
                    'data-post-type' => implode( ',',  gamipress_get_achievement_types_slugs() ),
                    'data-placeholder' => __( 'Select an achievement', 'gamipress-progress' ),
                ),
                'default'           => '',
                'options_cb'        => 'gamipress_options_cb_posts'
            ),
            'rank_type' => array(
                'name'              => __( 'Rank Type', 'gamipress-progress' ),
                'desc'              => __( 'Choose the rank type.', 'gamipress-progress' ),
                'type'              => 'select',
                'classes' 	        => 'gamipress-selector',
                'attributes' 	    => array(
                    'data-placeholder'  => __( 'Select a rank type', 'gamipress-progress' ),
                ),
                'option_none'       => true,
                'option_all'        => false,
                'default'           => '',
                'options_cb'        => 'gamipress_options_cb_rank_types',
            ),
            'rank' => array(
                'name'              => __( 'Rank', 'gamipress-progress' ),
                'description'       => __( 'The rank to render progress.', 'gamipress-progress' ),
                'shortcode_desc'    => __( 'The ID of the rank to render progress.', 'gamipress-progress' ),
                'type'              => 'select',
                'classes' 	        => 'gamipress-post-selector',
                'attributes' 	    => array(
                    'data-post-type' => implode( ',',  gamipress_get_rank_types_slugs() ),
                    'data-placeholder' => __( 'Select a rank', 'gamipress-progress' ),
                ),
                'default'           => '',
                'options_cb'        => 'gamipress_options_cb_posts'
            ),

            'current_user' => array(
                'name'        => __( 'Current User', 'gamipress-progress' ),
                'description' => __( 'Show progress of current logged in user.', 'gamipress-progress' ),
                'type' 		  => 'checkbox',
                'classes' 	  => 'gamipress-switch',
                'default'     => 'yes'
            ),
            'user_id' => array(
                'name'        => __( 'User', 'gamipress-progress' ),
                'description' => __( 'Show progress of a specific user.', 'gamipress-progress' ),
                'type'        => 'select',
                'classes' 	  => 'gamipress-user-selector',
                'default'     => '',
                'options_cb'  => 'gamipress_options_cb_users'
            ),
        ), $progress_fields ),
    ) );
}
add_action( 'init', 'gamipress_register_progress_shortcode' );

/**
 * Progress Shortcode.
 *
 * @since  1.1.8
 *
 * @param  array $atts Shortcode attributes.
 * @return string 	   HTML markup.
 */
function gamipress_progress_shortcode( $atts = array(), $content = '' ) {

    global $wpdb;

    $fields_defaults = gamipress_progress_get_fields_defaults( '', 'shortcode' );

    // Setup default attrs
    $atts = shortcode_atts( array_merge( array(
        'from'              => 'points_type',
        'points'            => '100',
        'points_type'       => '',
        'achievement_type'  => '',
        'achievement'       => '',
        'rank_type'         => '',
        'rank'              => '',
        'current_user'      => 'yes',
        'user_id'           => '',
    ), $fields_defaults ), $atts, 'gamipress_progress' );

    // Force to set current user as user ID
    if( $atts['current_user'] === 'yes' ) {

        /**
         * Filter to override shortcode workflow with not logged in users when current user is set to yes
         *
         * @since 1.1.8
         *
         * @param bool      $empty_if_not_logged_in     Final workflow to follow
         * @param array     $atts                       Shortcode attributes
         * @param string    $content                    Shortcode content
         */
        $empty_if_not_logged_in = apply_filters( 'gamipress_progress_shortcode_empty_if_not_logged_in', true, $atts, $content );

        // Return if current_user is set to yes and current user is a guest
        if( get_current_user_id() === 0 && $empty_if_not_logged_in )
            return '';

        $atts['user_id'] = get_current_user_id();

    }

    $prefix = '_gamipress_progress_';
    $points_types = gamipress_get_points_types();
    $achievement_types = gamipress_get_achievement_types();
    $rank_types = gamipress_get_rank_types();
    $posts = GamiPress()->db->posts;
    $atts['points'] = absint( $atts['points'] );
    $atts['achievement'] = absint( $atts['achievement'] );
    $atts['rank'] = absint( $atts['rank'] );
    $atts['user_id'] = absint( $atts['user_id'] );

    $progress = false;

    switch( $atts['from'] ) {
        case 'points_type':
            // Progress from points type

            // Return if points type is not registered
            if( ! isset( $points_types[$atts['points_type']] ) ) {
                return gamipress_shortcode_error( __( 'Please, provide a valid points type.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            $points_type_id = $points_types[$atts['points_type']]['ID'];

            // Check if option to show is not checked
            if( ! (bool) gamipress_get_post_meta( $points_type_id, $prefix . 'show', true ) ) {
                return gamipress_shortcode_error( __( 'Points type provided has not been configured a the progress to show.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the current points type progress
            $current_progress = gamipress_progress_get_points_type_progress( $atts['points_type'], $atts['user_id'] );

            if( $current_progress !== false ) {
                // Get the points type progress fields
                $progress = gamipress_progress_get_fields_values( $points_type_id, $prefix );

                $progress['current'] = $current_progress['current'];
                $progress['total'] = $current_progress['total'];
            }
            break;
        case 'points':
            // Progress from points amount

            // Return if points type is not registered
            if( ! isset( $points_types[$atts['points_type']] ) ) {
                return gamipress_shortcode_error( __( 'Please, provide a valid points type.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the shortcode progress fields
            $progress = gamipress_progress_get_fields_values_from_shortcode( '', $atts );

            // Current progress is user points balance
            $progress['current'] = gamipress_get_user_points( $atts['user_id'], $atts['points_type'] );

            // Total progress is points setup
            $progress['total'] = $atts['points'];
            break;
        case 'achievement_type':
            // Progress from achievements completed of a specific type

            // Return if achievement type is not registered
            if( ! isset( $achievement_types[$atts['achievement_type']] ) ) {
                return gamipress_shortcode_error( __( 'Please, provide a valid achievement type.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the shortcode progress fields
            $progress = gamipress_progress_get_fields_values_from_shortcode( '', $atts );

            // Current progress is user earned achievements
            $progress['current'] = count( gamipress_get_user_achievements( array(
                'user_id' => $atts['user_id'],
                'achievement_type' => $atts['achievement_type']
            ) ) );

            // Total progress is all achievements
            $progress['total'] = absint( $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM {$posts} AS p WHERE p.post_status = 'publish' AND p.post_type = %s",
                $atts['achievement_type']
            ) ) );
            break;
        case 'achievement':
            // Progress from achievement

            // Check if option to show is not checked
            if( ! (bool) gamipress_get_post_meta( $atts['achievement'], $prefix . 'show', true ) ) {
                return gamipress_shortcode_error( __( 'Achievement provided has not been configured a progress to show.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the current achievement progress
            $current_progress = gamipress_progress_get_achievement_progress( $atts['achievement'], $atts['user_id'] );

            if( $current_progress !== false ) {
                // Get the achievement progress fields
                $progress = gamipress_progress_get_fields_values( $atts['achievement'], $prefix );

                $progress['current'] = $current_progress['current'];
                $progress['total'] = $current_progress['total'];
            }
            break;
        case 'rank_type':
            // Progress from current rank of a specific type

            // Return if rank type is not registered
            if( ! isset( $rank_types[$atts['rank_type']] ) ) {
                return gamipress_shortcode_error( __( 'Please, provide a valid rank type.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the shortcode progress fields
            $progress = gamipress_progress_get_fields_values_from_shortcode( '', $atts );

            $progress['current'] = 0;
            $current_rank = gamipress_get_user_rank( $atts['user_id'], $atts['rank_type'] );

            if( $current_rank ) {
                // Current progress is all ranks previous to current rank
                $progress['current'] = absint( $wpdb->get_var( $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$posts} AS p WHERE p.post_status = 'publish' AND p.post_type = %s AND p.menu_order <= %d",
                    $atts['rank_type'],
                    $current_rank->menu_order
                ) ) );
            }

            // Progress is always 1 for default rank
            if( $progress['current'] === 0 )
                $progress['current'] = 1;

            // Total progress is all ranks
            $progress['total'] = absint( $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM {$posts} AS p WHERE p.post_status = 'publish' AND p.post_type = %s",
                $atts['rank_type']
            ) ) );
            break;
        case 'rank':
            // Progress from rank

            // Check if option to show is not checked
            if( ! (bool) gamipress_get_post_meta( $atts['rank'], $prefix . 'show', true ) ) {
                return gamipress_shortcode_error( __( 'Rank provided has not been configured a progress to show.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the current rank progress
            $current_progress = gamipress_progress_get_rank_progress( $atts['rank'], $atts['user_id'] );

            if( $current_progress !== false ) {
                // Get the rank progress fields
                $progress = gamipress_progress_get_fields_values( $atts['rank'], $prefix );

                $progress['current'] = $current_progress['current'];
                $progress['total'] = $current_progress['total'];
            }
            break;
        case 'current_rank':
            // Progress from current rank (aka the next to unlock rank since current rank is already unlocked)

            // Return if rank type is not registered
            if( ! isset( $rank_types[$atts['rank_type']] ) ) {
                return gamipress_shortcode_error( __( 'Please, provide a valid rank type.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            $next_rank_id = gamipress_get_next_user_rank_id( $atts['user_id'], $atts['rank_type'] );

            // If user is on latest rank, show progress of current one
            if( $next_rank_id === 0 ) {
                $next_rank_id = gamipress_get_user_rank_id( $atts['user_id'], $atts['rank_type'] );
            }

            // Check if option to show is not checked
            if( ! (bool) gamipress_get_post_meta( $next_rank_id, $prefix . 'show', true ) ) {
                return gamipress_shortcode_error( __( 'Current rank has not been configured a progress to show.', 'gamipress-progress' ), 'gamipress_progress' );
            }

            // Get the current rank progress
            $current_progress = gamipress_progress_get_rank_progress( $next_rank_id, $atts['user_id'] );

            if( $current_progress !== false ) {
                // Get the rank progress fields
                $progress = gamipress_progress_get_fields_values( $next_rank_id, $prefix );

                $progress['current'] = $current_progress['current'];
                $progress['total'] = $current_progress['total'];
            }
            break;
    }


    // Render the progress
    ob_start();
    if( $progress !== false )
        gamipress_progress_render_progress( $progress );
    $output = ob_get_clean();

    /**
     * Filter to override shortcode output
     *
     * @since 1.1.8
     *
     * @param string    $output     Final output
     * @param array     $atts       Shortcode attributes
     * @param string    $content    Shortcode content
     */
    return apply_filters( 'gamipress_progress_shortcode_output', $output, $atts, $content );

}
