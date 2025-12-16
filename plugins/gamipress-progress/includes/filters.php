<?php
/**
 * Content Filters
 *
 * @package GamiPress\Progress\Content_Filters
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Filter to render points type progress
 *
 * @since 1.0.0
 *
 * @param string  $points_type      Points type slug
 * @param array   $points_awards    Array of points awards
 * @param array   $points_deducts   Array of points deducts
 * @param array   $points_types     Array of points types to be rendered
 * @param array   $template_args    Template received arguments
 */
function gamipress_progress_after_points_type_title( $points_type, $points_awards, $points_deducts, $points_types, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return;
    }

    $points_types = gamipress_get_points_types();
    $prefix = '_gamipress_progress_';

    // Return if points type is not registered
    if( ! isset( $points_types[$points_type] ) ) {
        return;
    }

    $points_type_id = $points_types[$points_type]['ID'];

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $points_type_id, $prefix . 'show', true ) ) {
        return;
    }

    $progress = gamipress_progress_get_fields_values( $points_type_id, $prefix );

    $user_id = get_current_user_id();

    // Get the current achievement progress
    $current_progress = gamipress_progress_get_points_type_progress( $points_type, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        gamipress_progress_render_progress( $progress );

    }

}
add_action( 'gamipress_after_points_type_title', 'gamipress_progress_after_points_type_title', 10, 5 );

/**
 * Filter to render points award progress
 *
 * @since 1.0.0
 *
 * @param string  $title            Points award title
 * @param WP_Post $points_award     Points awards object
 * @param integer $user_id          The user ID
 * @param array   $template_args    An array with the template args
 *
 * @return string
 */
function gamipress_progress_points_award_title_display( $title, $points_award, $user_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return $title;
    }

    $prefix = '_gamipress_progress_points_awards_';

    $points_type = gamipress_get_points_award_points_type( $points_award->ID );

    // Return if not points type
    if( ! $points_type ) {
        return $title;
    }

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $points_type->ID, $prefix . 'show', true ) ) {
        return $title;
    }

    $maximum_earnings = absint( gamipress_get_post_meta( $points_award->ID, '_gamipress_maximum_earnings', true ) );

    // Return if points awards could be earned unlimited times
    if( $maximum_earnings === 0 ) {
        return $title;
    }

    $progress = gamipress_progress_get_fields_values( $points_type->ID, $prefix );

    // Get the current points deduct progress
    $current_progress = gamipress_progress_get_requirement_progress( $points_award->ID, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        ob_start();
        gamipress_progress_render_progress( $progress );
        $progress_output = ob_get_clean();

        // Append the progress output to the title
        $title .= $progress_output;

    }

    return $title;

}
add_filter( 'gamipress_points_award_title_display', 'gamipress_progress_points_award_title_display', 10, 4 );

/**
 * Filter to render points deduct progress
 *
 * @since 1.0.7
 *
 * @param $title            string  Points deduct title
 * @param $points_deduct    WP_Post Points deducts object
 * @param $user_id          integer The user ID
 * @param $template_args    array   An array with the template args
 *
 * @return string
 */
function gamipress_progress_points_deduct_title_display( $title, $points_deduct, $user_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return $title;
    }

    $prefix = '_gamipress_progress_points_deducts_';

    $points_type = gamipress_get_points_deduct_points_type( $points_deduct->ID );

    // Return if not points type
    if( ! $points_type ) {
        return $title;
    }

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $points_type->ID, $prefix . 'show', true ) ) {
        return $title;
    }

    $maximum_earnings = absint( gamipress_get_post_meta( $points_deduct->ID, '_gamipress_maximum_earnings', true ) );

    // Return if points deducts could be earned unlimited times
    if( $maximum_earnings === 0 ) {
        return $title;
    }

    $progress = gamipress_progress_get_fields_values( $points_type->ID, $prefix );

    // Get the current points deduct progress
    $current_progress = gamipress_progress_get_requirement_progress( $points_deduct->ID, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        ob_start();
        gamipress_progress_render_progress( $progress );
        $progress_output = ob_get_clean();

        // Append the progress output to the title
        $title .= $progress_output;

    }

    return $title;

}
add_filter( 'gamipress_points_deduct_title_display', 'gamipress_progress_points_deduct_title_display', 10, 4 );

/**
 * Filter to render achievement progress
 *
 * @since   1.0.0
 * @updated 1.1.1 Added extra checks to ensure output and moved achievement progress calculation to functions.php
 *
 * @param $achievement_id   integer The Achievement ID
 * @param $template_args    array   Template received arguments
 */
function gamipress_progress_after_achievement_title( $achievement_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return;
    }

    // To ensure progress output need to check current filter and template args
    $current_filter = current_filter();

    // Check before render achievement filter
    if( $current_filter === 'gamipress_before_render_achievement' ) {

        // Progress will be rendered on title or on thumbnail
        if( $template_args['title'] === 'yes' || $template_args['thumbnail'] === 'yes' ) {
            return;
        }

    }

    // Check after render achievement thumbnail filter
    if( $current_filter === 'gamipress_after_achievement_thumbnail' ) {

        // Progress will be rendered on title
        if( $template_args['title'] === 'yes' ) {
            return;
        }

    }

    $prefix = '_gamipress_progress_';

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $achievement_id, $prefix . 'show', true ) ) {
        return;
    }

    // Determine the user to check
    if( isset( $template_args['user_id'] ) ) {
        $user_id = $template_args['user_id'];
    } else {
        $user_id = get_current_user_id();
    }

    // Get the achievement progress fields
    $progress = gamipress_progress_get_fields_values( $achievement_id, $prefix );

    // Get the current achievement progress
    $current_progress = gamipress_progress_get_achievement_progress( $achievement_id, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        gamipress_progress_render_progress( $progress );

    }

}
add_action( 'gamipress_before_render_achievement', 'gamipress_progress_after_achievement_title', 10, 2 );
add_action( 'gamipress_after_achievement_thumbnail', 'gamipress_progress_after_achievement_title', 10, 2 );
add_action( 'gamipress_after_achievement_title', 'gamipress_progress_after_achievement_title', 10, 2 );
add_action( 'gamipress_after_single_achievement_content', 'gamipress_progress_after_achievement_title', 10, 2 );

/**
 * Filter to render step progress
 *
 * @since 1.0.0
 *
 * @param $title            string  Step title
 * @param $step             WP_Post Step object
 * @param $user_id          integer The user ID
 * @param $template_args    array   An array with the template args
 *
 * @return string
 */
function gamipress_progress_step_title_display( $title, $step, $user_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return $title;
    }

    $prefix = '_gamipress_progress_steps_';

    $achievement = gamipress_get_parent_of_achievement( $step->ID );

    // Return if not achievement
    if( ! $achievement ) {
        return $title;
    }

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $achievement->ID, $prefix . 'show', true ) ) {
        return $title;
    }

    $progress = gamipress_progress_get_fields_values( $achievement->ID, $prefix );

    // Get the current step progress
    $current_progress = gamipress_progress_get_requirement_progress( $step->ID, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        ob_start();
        gamipress_progress_render_progress( $progress );
        $progress_output = ob_get_clean();

        // Append the progress output to the title
        $title .= $progress_output;

    }

    return $title;

}
add_filter( 'gamipress_step_title_display', 'gamipress_progress_step_title_display', 10, 4 );

/**
 * Filter to render rank progress
 *
 * @since   1.0.4
 * @updated 1.1.1 Added extra checks to ensure output and moved rank progress calculation to functions.php
 *
 * @param $rank_id          integer The Rank ID
 * @param $template_args    array   Template received arguments
 */
function gamipress_progress_after_rank_title( $rank_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return;
    }

    // To ensure progress output need to check current filter and template args
    $current_filter = current_filter();

    // Check before render rank filter
    if( $current_filter === 'gamipress_before_render_rank' ) {

        // Progress will be rendered on title or on thumbnail
        if( $template_args['title'] === 'yes' || $template_args['thumbnail'] === 'yes' ) {
            return;
        }

    }

    // Check after render rank thumbnail filter
    if( $current_filter === 'gamipress_after_rank_thumbnail' ) {

        // Progress will be rendered on title
        if( $template_args['title'] === 'yes' ) {
            return;
        }

    }

    $prefix = '_gamipress_progress_';

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $rank_id, $prefix . 'show', true ) ) {
        return;
    }

    // Determine the user to check
    if( isset( $template_args['user_id'] ) ) {
        $user_id = $template_args['user_id'];
    } else {
        $user_id = get_current_user_id();
    }

    // Get the rank progress fields
    $progress = gamipress_progress_get_fields_values( $rank_id, $prefix );

    // Get the current rank progress
    $current_progress = gamipress_progress_get_rank_progress( $rank_id, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        gamipress_progress_render_progress( $progress );

    }

}
add_action( 'gamipress_before_render_rank', 'gamipress_progress_after_rank_title', 10, 2 );
add_action( 'gamipress_after_rank_thumbnail', 'gamipress_progress_after_rank_title', 10, 2 );
add_action( 'gamipress_after_rank_title', 'gamipress_progress_after_rank_title', 10, 2 );
add_action( 'gamipress_after_single_rank_content', 'gamipress_progress_after_rank_title', 10, 2 );

/**
 * Filter to render rank requirement progress
 *
 * @since 1.0.4
 *
 * @param $title            string  Rank requirement title
 * @param $rank_requirement WP_Post Rank requirement object
 * @param $user_id          integer The user ID
 * @param $template_args    array   An array with the template args
 *
 * @return string
 */
function gamipress_progress_rank_requirement_title_display( $title, $rank_requirement, $user_id, $template_args ) {

    // Set default value
    if( ! isset( $template_args['progress'] ) ) {
        $template_args['progress'] = 'yes';
    }

    // Return if progress is set to no
    if( $template_args['progress'] !== 'yes' ) {
        return $title;
    }

    $prefix = '_gamipress_progress_rank_requirements_';

    $rank = gamipress_get_rank_requirement_rank( $rank_requirement->ID );

    // Return if not rank
    if( ! $rank ) {
        return $title;
    }

    // Return if option to show is not checked
    if( ! (bool) gamipress_get_post_meta( $rank->ID, $prefix . 'show', true ) ) {
        return $title;
    }

    $progress = gamipress_progress_get_fields_values( $rank->ID, $prefix );

    // Get the current rank requirement progress
    $current_progress = gamipress_progress_get_requirement_progress( $rank_requirement->ID, $user_id );

    if( $current_progress !== false ) {

        $progress['current'] = $current_progress['current'];
        $progress['total'] = $current_progress['total'];

        // Render the progress
        ob_start();
        gamipress_progress_render_progress( $progress );
        $progress_output = ob_get_clean();

        // Append the progress output to the title
        $title .= $progress_output;

    }

    return $title;

}
add_filter( 'gamipress_rank_requirement_title_display', 'gamipress_progress_rank_requirement_title_display', 10, 4 );