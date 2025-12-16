<?php
/**
 * Functions
 *
 * @package     GamiPress\Progress\Functions
 * @since       1.1.1
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Return the current and total completion progress based on the given user ID
 *
 * @since 1.1.8
 *
 * @param int|string 	    $points_type   The points type ID or slug
 * @param int               $user_id       The user ID
 *
 * @return array|false                     Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
 */
function gamipress_progress_get_points_type_progress( $points_type, $user_id ) {

    if( empty( $points_type ) )
        return false;

    // Try to find the points type by slug
    if( ! is_numeric( $points_type ) && ! empty( $points_type ) ) {
        $points_types = gamipress_get_points_types();

        if( isset( $points_types[$points_type] ) ) {
            $points_type = $points_types[$points_type]['ID'];
        } else {
            return false;
        }
    }

    $progress = array(
        'current' => 0,
        'total' => 0,
    );

    $points_awards = gamipress_get_points_type_points_awards( $points_type );

    // Set total progress
    $progress['total'] = count( $points_awards );

    // Loop all points type points awards
    foreach ( $points_awards as $points_award ) {

        $maximum_earnings = absint( gamipress_get_post_meta( $points_award->ID, '_gamipress_maximum_earnings', true ) );

        // An unlimited maximum of earnings means points awards could be earned always
        if( $maximum_earnings > 0 && $user_id !== 0 ) {
            $earned_times = gamipress_get_earnings_count( array(
                'user_id' => absint( $user_id ),
                'post_id' => absint( $points_award->ID ),
            ) );

            // User has earned it more times than maximum, so is earned
            if( $earned_times >= $maximum_earnings ) {
                $progress['current']++;
            }
        } else {
            $progress['total']--;
        }
    }

    if( $user_id === 0 ) {
        $progress['current'] = 0;
    }

    /**
     * Filters the achievement progress
     *
     * @since 1.1.2
     *
     * @param array $progress           The rank progress
     * @param int   $points_type        The points type ID
     * @param int   $user_id            The User ID
     *
     * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
     */
    return apply_filters( 'gamipress_progress_get_points_type_progress', $progress, $points_type, $user_id );

}

/**
 * Return the current and total completion progress based on the given user ID
 *
 * @since 1.1.1
 *
 * @param int $achievement_id   The Achievement ID
 * @param int $user_id          The User ID
 *
 * @return array|false          Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
 */
function gamipress_progress_get_achievement_progress( $achievement_id, $user_id ) {

    $progress = array(
        'current' => 0,
        'total' => 0,
    );

    // Check how achievement could be earned
    $earned_by = gamipress_get_post_meta( $achievement_id, '_gamipress_earned_by', true );

    if( $earned_by === 'triggers' ) {
        // Completion by steps

        // Get the achievement steps
        $steps = gamipress_get_required_achievements_for_achievement( $achievement_id );

        // If achievement is earned by steps and we don't have any steps, or our steps aren't an array, return nothing
        if ( ! $steps || ! is_array( $steps ) )
            return false;

        // Set total progress
        $progress['total'] = count( $steps );

        $maximum_earnings = absint( gamipress_get_post_meta( $achievement_id, '_gamipress_maximum_earnings', true ) );
        $earned_times = gamipress_get_earnings_count( array(
            'user_id' => absint( $user_id ),
            'post_id' => absint( $achievement_id ),
        ) );

        if( $maximum_earnings != 0 && $earned_times >= $maximum_earnings ) {
            // User has completely earned the achievement so set current progress at maximum
            $progress['current'] = count( $steps );
        } else {
            // We need to calculate the progress based on steps completion

            // Get progress calculation setting to meet how to calculate it
            $progress_calc = gamipress_get_post_meta( $achievement_id, '_gamipress_progress_progress_calc' );

            if( empty( $progress_calc ) )
                $progress_calc = 'requirements_count';

            // Set current progress
            $progress['current'] = 0;

            // Get sequential achievements configurations
            $sequential = (bool) gamipress_get_post_meta( $achievement_id, '_gamipress_sequential', true );
            $is_previous_earned = true;

            // Calculate the progress based on requirements completed
            if( $progress_calc === 'requirements_count' ) {

                // Loop requirements to meet earned ones
                foreach ( $steps as $index => $step ) {

                    // If previous steps has not been earned, then stop to count current progress
                    if( $sequential && $index !== 0 && ! $is_previous_earned )
                        continue;

                    $earned = gamipress_get_earnings_count( array(
                        'user_id' => absint( $user_id ),
                        'post_id' => absint( $step->ID ),
                        'since' => absint( gamipress_achievement_last_user_activity( $achievement_id, $user_id ) )
                    ) );

                    // If user has earned it, increase progress
                    if( $earned ) {
                        $progress['current']++;
                    } else {
                        $is_previous_earned = false;
                    }
                }

            } else {

                // Total progress will be recalculated
                $progress['total'] = 0;

                // Loop requirements to meet their current progress
                foreach ( $steps as $index => $step ) {

                    // Get the requirement progress to sum it to the current progress
                    $requirement_progress = gamipress_progress_get_requirement_progress( $step->ID, $user_id );

                    if( $requirement_progress ) {

                        $progress['total'] += $requirement_progress['total'];

                        // If previous steps has not been earned, then stop to count current progress
                        if( $sequential && $index !== 0 && ! $is_previous_earned ) {
                            continue;
                        }

                        $earned = gamipress_get_earnings_count( array(
                            'user_id' => absint( $user_id ),
                            'post_id' => absint( $step->ID ),
                            'since' => absint( gamipress_achievement_last_user_activity( $achievement_id, $user_id ) )
                        ) ) > 0;

                        if( ! $earned ) {
                            $is_previous_earned = false;
                        }

                        $progress['current'] += $requirement_progress['current'];


                    }

                }

            }

        }

    } else if( $earned_by === 'points' ) {
        // Completion by points

        // Get the achievement required points
        $points_required = absint( gamipress_get_post_meta( $achievement_id, '_gamipress_points_required', true ) );

        if( ! $points_required )
            return false;

        // Get the achievement required points type
        $points_type_required = gamipress_get_post_meta( $achievement_id, '_gamipress_points_type_required', true );

        $user_points = gamipress_get_user_points( $user_id, $points_type_required );

        if ( $user_points >= $points_required ) {
            // User has completely earned the achievement so set current progress at maximum
            $progress['current'] = $points_required;
        } else {
            // Progress is based on current user points
            $progress['current'] = $user_points;
        }

        // Set total progress
        $progress['total'] = $points_required;

    } else if( $earned_by === 'rank' ) {
        // Completion by rank

        // Get the achievement required rank
        $rank_required = absint( gamipress_get_post_meta( $achievement_id, '_gamipress_rank_required', true ) );

        $rank = get_post( $rank_required );

        if( ! $rank )
            return false;

        // Get the achievement required rank type
        $rank_type_required = $rank->post_type;
        $user_rank = gamipress_get_user_rank_id( $user_id, $rank_type_required );

        global $wpdb;

        $ranks_count = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*)
            FROM {$wpdb->posts} AS p
            WHERE p.post_type = %s
             AND p.post_status = %s
             AND p.menu_order < %s
            ORDER BY menu_order DESC",
            $rank_type_required,
            'publish',
            get_post_field( 'menu_order', $rank_type_required )
        ) );

        $ranks_count = absint( $ranks_count );

        $earned = gamipress_get_earnings_count( array(
            'user_id' => absint( $user_id ),
            'post_id' => $rank_required
        ) ) > 0;

        if ( $user_rank === $rank_required || $earned ) {
            // User has completely reached the rank so set current progress at maximum
            $progress['current'] = $ranks_count;
        } else {
            // Progress is based on earned ranks
            $progress['current'] = 0;

            $previous_ranks = $wpdb->get_col( $wpdb->prepare(
                "SELECT p.ID, p.menu_order
                FROM {$wpdb->posts} AS p
                WHERE p.post_type = %s
                 AND p.post_status = %s
                 AND p.menu_order < %s
                ORDER BY menu_order DESC",
                $rank_type_required,
                'publish',
                get_post_field( 'menu_order', $rank_type_required )
            ) );

            foreach( $previous_ranks as $previous_rank ) {

                $earned = gamipress_get_earnings_count( array(
                    'user_id' => absint( $user_id ),
                    'post_id' => $previous_rank->ID )
                ) > 0;

                // if user has earned this rank, then increment progress
                if( $user_rank === $rank_required || $earned || absint( $previous_rank->menu_order ) === 0 ) {
                    $progress['current']++;
                }
            }
        }

        // Set total progress
        $progress['total'] = $ranks_count;
    }

    if( $user_id === 0 ) {
        $progress['current'] = 0;
    }

    /**
     * Filters the achievement progress
     *
     * @since 1.1.2
     *
     * @param array $progress           The rank progress
     * @param int   $achievement_id     The achievement ID
     * @param int   $user_id            The User ID
     *
     * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
     */
    return apply_filters( 'gamipress_progress_get_achievement_progress', $progress, $achievement_id, $user_id );

}

/**
 * Return the current and total completion progress based on the given user ID
 *
 * @since 1.1.1
 *
 * @param int $rank_id The Rank ID
 * @param int $user_id The User ID
 *
 * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
 */
function gamipress_progress_get_rank_progress( $rank_id, $user_id ) {

    $progress = array(
        'current' => 0,
        'total' => 0,
    );

    if( gamipress_is_lowest_priority_rank( $rank_id ) ) {

        // Set progress to 100% on lowest priority rank
        $progress['current'] = 1;
        $progress['total'] = 1;

    } else {

        // Get the rank requirements
        $requirements = gamipress_get_rank_requirements( $rank_id );

        // If we don't have any requirements, or our requirements aren't an array, return nothing
        if ( empty( $requirements ) ) {
            return false;
        }

        // Set total progress
        $progress['total'] = count( $requirements );

        // We need to calculate the progress based on requirements completion

        // Get progress calculation setting to meet how to calculate it
        $progress_calc = gamipress_get_post_meta( $rank_id, '_gamipress_progress_progress_calc' );

        if( empty( $progress_calc ) ) {
            $progress_calc = 'requirements_count';
        }

        // Get sequential achievements configurations
        $sequential = (bool) gamipress_get_post_meta( $rank_id, '_gamipress_sequential', true );
        $is_previous_earned = true;

        // Calculate the progress based on requirements completed
        if( $progress_calc === 'requirements_count' ) {

            // Loop requirements to meet earned ones
            foreach ( $requirements as $index => $requirement ) {

                // If previous steps has not been earned, then stop to count current progress
                if( $sequential && $index !== 0 && ! $is_previous_earned ) {
                    continue;
                }

                $earned = gamipress_get_earnings_count( array(
                    'user_id' => absint( $user_id ),
                    'post_id' => absint( $requirement->ID ),
                    'since' => absint( gamipress_achievement_last_user_activity( $rank_id, $user_id ) )
                ) ) > 0;

                // If user has earned it, increase progress
                if( $earned ) {
                    $progress['current']++;
                } else {
                    $is_previous_earned = false;
                }
            }

        } else {

            // Total progress will be recalculated
            $progress['total'] = 0;

            // Loop requirements to meet their current progress
            foreach ( $requirements as $index => $requirement ) {

                // Get the requirement progress to sum it to the current progress
                $requirement_progress = gamipress_progress_get_requirement_progress( $requirement->ID, $user_id );

                if( $requirement_progress ) {

                    $progress['total'] += $requirement_progress['total'];

                    // If previous steps has not been earned, then stop to count current progress
                    if( $sequential && $index !== 0 && ! $is_previous_earned ) {
                        continue;
                    }

                    $earned = gamipress_get_earnings_count( array(
                        'user_id' => absint( $user_id ),
                        'post_id' => absint( $requirement->ID ),
                        'since' => absint( gamipress_achievement_last_user_activity( $rank_id, $user_id ) )
                    ) ) > 0;

                    // If user has earned it, increase progress
                    if( ! $earned ) {
                        $is_previous_earned = false;
                    }

                    $progress['current'] += $requirement_progress['current'];


                }

            }

        }

        $earned = gamipress_get_earnings_count( array(
            'user_id' => absint( $user_id ),
            'post_id' => absint( $rank_id ),
        ) ) > 0;

        if( $earned ) {
            // User has completely earned the rank so set current progress at maximum
            $progress['current'] = $progress['total'];
        } else if( gamipress_get_rank_priority( $rank_id ) < gamipress_get_rank_priority( gamipress_get_user_rank_id( absint( $user_id ), get_post_type( $rank_id ) ) ) ) {
            // If current user rank is higher than this rank, then set as completed
            $progress['current'] = $progress['total'];
        }

    }

    /**
     * Filters the rank progress
     *
     * @since 1.1.2
     *
     * @param array $progress   The rank progress
     * @param int   $rank_id    The rank ID
     * @param int   $user_id    The User ID
     *
     * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
     */
    return apply_filters( 'gamipress_progress_get_rank_progress', $progress, $rank_id, $user_id );

}

/**
 * Return the current and total completion progress based on the given user ID
 *
 * @since 1.1.2
 *
 * @param int $requirement_id   The requirement ID
 * @param int $user_id          The User ID
 *
 * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
 */
function gamipress_progress_get_requirement_progress( $requirement_id, $user_id ) {

    $progress = array(
        'current' => 0,
        'total' => 0,
    );

    // Check the requirement type
    $requirement_type = gamipress_get_post_type( $requirement_id );

    // Check the requirement trigger type
    $trigger_type = gamipress_get_post_meta( $requirement_id, '_gamipress_trigger_type' );

    // Check points based triggers
    if( in_array( $trigger_type, array( 'earn-points', 'points-balance', 'gamipress_expend_points' ) ) ) {

        $user_points = 0;
        $points_required = absint( gamipress_get_post_meta( $requirement_id, '_gamipress_points_required' ) );

        // Only proceed if user is logged in
        if( $user_id !== 0 ) {

            $points_type_required = gamipress_get_post_meta( $requirement_id, '_gamipress_points_type_required' );

            if( $trigger_type === 'earn-points' ) {

                $args = array();
                $last_activity = absint( gamipress_achievement_last_user_activity( $requirement_id, $user_id ) );

                if( $last_activity > 0 ) {

                    $last_activity += 1;

                    $args['date_query'] = array(
                        'after' => date( 'Y-m-d H:i:s', $last_activity )
                    );
                }

                $user_points = gamipress_get_user_points( $user_id, $points_type_required, $args );

            } else if( $trigger_type === 'points-balance' ) {

                $user_points = gamipress_get_user_points( $user_id, $points_type_required );

            } else if( $trigger_type === 'gamipress_expend_points' ) {

                $user_points = gamipress_get_user_points_expended( $user_id, $points_type_required );

            }

        }

        // Set current progress
        $progress['current'] = ( $user_points > $points_required ? $points_required : $user_points );

        // Set total progress
        $progress['total'] = $points_required;

    } else if( $trigger_type === 'all-achievements' ) {
        // Check for unlock all achievements of a type

        $achievement_type = gamipress_get_post_meta( $requirement_id, '_gamipress_achievement_type' );

        if( gamipress_get_achievement_type( $achievement_type ) ) {

            $all_achievements_of_type = gamipress_get_achievements( array( 'post_type' => $achievement_type ) );

            // Only proceed if user is logged in
            if( $user_id !== 0 ) {

                $earned_achievements = gamipress_get_user_achievements( array( 'user_id' => $user_id, 'achievement_type' => $achievement_type ) );

                $single_earned = array();

                foreach( $earned_achievements as $earned_achievement ) {
                    if( ! in_array( $earned_achievement->ID, $single_earned ) ) {
                        $single_earned[] = $earned_achievement->ID;
                    }
                }

                // Set current progress
                $progress['current'] = count( $single_earned );

            }

            // Set total progress
            $progress['total'] = count( $all_achievements_of_type );

        }

    } else {
        // Rest of trigger types

        // Times required to earn the requirement
        $count = absint( gamipress_get_post_meta( $requirement_id, '_gamipress_count', true ) );

        // Only proceed if user is logged in
        if( $user_id !== 0 ) {

            // Times activity has triggered
            $activity_count = absint( gamipress_progress_get_requirement_activity_count( $requirement_id, $user_id ) );

            if( in_array( $requirement_type, array( 'points-award', 'points-deduct' ) ) ) {
                // Points awards and points deducts

                // Check how many times user has earned this requirement
                $earned_times = gamipress_get_earnings_count( array(
                    'user_id' => absint( $user_id ),
                    'post_id' => absint( $requirement_id ),
                    'since' => absint( gamipress_achievement_last_user_activity( $requirement_id, $user_id ) )
                ) );

                // Set current progress
                $progress['current'] = $activity_count - ( $count * $earned_times );

            } else {
                // Steps and rank requirements

                // Set current progress
                $progress['current'] = ( $activity_count > $count ? $count : $activity_count );

            }

        }

        // Set total progress
        $progress['total'] = $count;

    }

    // Only proceed if user is logged in
    if( $user_id !== 0 ) {

        if( $requirement_type === 'step' ) {

            $achievement = gamipress_get_step_achievement( $requirement_id );

            if( $achievement ) {

                $max_earnings = absint( gamipress_get_post_meta( $achievement->ID, '_gamipress_maximum_earnings' ) );

                if( $max_earnings !== 0 ) {

                    $earned_times = gamipress_get_earnings_count( array(
                        'user_id' => absint( $user_id ),
                        'post_id' => absint( $requirement_id ),
                    ) );

                    // For steps, if user has earned the requirement the same number of times as maximum allowed then set progress as completed
                    if( $earned_times >= $max_earnings )
                        $progress['current'] = $progress['total'];

                }

                // As last check, check if user has earned (or manually awarded) this step
                $last_achievement_activity = absint( gamipress_achievement_last_user_activity( $achievement->ID, $user_id ) );

                $earned = gamipress_get_earnings_count( array(
                    'user_id' => absint( $user_id ),
                    'post_id' => absint( $requirement_id ),
                    'since'   => $last_achievement_activity
                ) ) > 0;

                if( $earned ) {
                    $progress['current'] = $progress['total'];
                }

            }

        } else if( $requirement_type === 'rank-requirement' ) {

            // If is a rank requirement, we need to check if rank requirement is for next rank and not other
            $rank = gamipress_get_rank_requirement_rank( $requirement_id );

            $next_user_rank_id = gamipress_get_next_user_rank_id( $user_id, $rank->post_type );

            if( gamipress_get_rank_priority( $rank->ID ) > gamipress_get_rank_priority( $next_user_rank_id ) && absint( $rank->ID ) !== $next_user_rank_id  ) {
                // If this rank priority is higher than the next rank and not is the next rank to earn, set progress to 0
                $progress['current'] = 0;

            } else {

                $earned = gamipress_get_earnings_count( array(
                    'user_id' => absint( $user_id ),
                    'post_id' => absint( $requirement_id ),
                ) ) > 0;

                // For rank requirements, if user has earned the requirement set progress as completed
                if( $earned ) {
                    $progress['current'] = $progress['total'];
                }

            }

        }

    }

    /**
     * Filters the requirement progress
     *
     * @since 1.1.2
     *
     * @param array $progress           The requirement progress
     * @param int   $requirement_id     The requirement ID
     * @param int   $user_id            The User ID
     *
     * @return array|false Return an array with current and total progress like array( 'current' => 0, 'total' => 0 )
     */
    return apply_filters( 'gamipress_progress_get_requirement_progress', $progress, $requirement_id, $user_id );

}

/**
 * Helper function to get a requirement activity count
 *
 * @since 1.4.0
 *
 * @param int $requirement_id   The requirement ID
 * @param int $user_id          The User ID
 *
 * @return int
 */
function gamipress_progress_get_requirement_activity_count( $requirement_id, $user_id ) {

    if( function_exists( 'gamipress_get_achievement_activity_count_limited' ) ) {
        // Requires GamiPress 2.0.2
        return absint( gamipress_get_achievement_activity_count_limited( $user_id, $requirement_id ) );
    } else {
        return absint( gamipress_get_achievement_activity_count( $user_id, $requirement_id ) );
    }

}