<?php
/**
 * GamiPress Restrict Unlock Integration
 *
 * @package GamiPress\Progress\Integrations\GamiPress_Restrict_Unlock
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'GamiPress_Restrict_Unlock' ) ) {
    return;
}

/**
 * Restores the current progress if requirement is restricted
 *
 * @since 1.0.0
 *
 * @param array $progress       The progress data
 * @param int   $requirement_id The requirement ID
 * @param int   $user_id        The User ID
 *
 * @return array
 */
function gamipress_progress_restrict_unlock_get_requirement_progress( $progress, $requirement_id, $user_id ) {

    if( gamipress_restrict_unlock_is_restricted( $requirement_id )
        && ! gamipress_restrict_unlock_is_user_granted( $requirement_id, $user_id ) ) {
        $progress['current'] = 0;
    }

    return $progress;
}
add_filter( 'gamipress_progress_get_requirement_progress', 'gamipress_progress_restrict_unlock_get_requirement_progress', 10, 3 );