<?php

// Shortcode: [gamipress_average_points user_id=1 type=points]
// Supports: type="all" to include all point types
function gamipress_average_points_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'user_id' => get_current_user_id(),
        'type'    => 'points',
    ), $atts, 'gamipress_average_points' );

    $user_id = intval( $atts['user_id'] );
    $type    = sanitize_text_field( $atts['type'] );
    $type = 'energiepunkte';

    if ( ! $user_id ) {
        return 'User not found.';
    }

    global $wpdb;
    $log_table = $wpdb->prefix . 'gamipress_logs';

    // Date range (last 30 days)
    $end_date   = date( 'Y-m-d', current_time( 'timestamp' ) );
    $start_date = date( 'Y-m-d', strtotime( '-30 days', current_time( 'timestamp' ) ) );

    // Allowed types that represent earning points
    $earning_types = array( 'points_award', 'points_earn' );
    $types_in_sql  = "'" . implode( "','", $earning_types ) . "'";

    if ( strtolower( $type ) === 'all' ) {
        // All point types
        $sql = $wpdb->prepare("
            SELECT SUM(points)
            FROM {$log_table}
            WHERE user_id = %d
            AND type IN ($types_in_sql)
            AND DATE(date) BETWEEN %s AND %s
        ", $user_id, $start_date, $end_date );
    } else {
        // Specific point type
        $sql = $wpdb->prepare("
            SELECT SUM(points)
            FROM {$log_table}
            WHERE user_id = %d
            AND type IN ($types_in_sql)
            AND points_type = %s
            AND DATE(date) BETWEEN %s AND %s
        ", $user_id, $type, $start_date, $end_date );
    }

    $total_points = floatval( $wpdb->get_var( $sql ) );
    $average = $total_points / 30;

    if (floor($average) == $average) {
        return number_format($average, 0);
    } else {
        return number_format($average, 2);
    }
}
add_shortcode( 'gamipress_average_points', 'gamipress_average_points_shortcode' );




