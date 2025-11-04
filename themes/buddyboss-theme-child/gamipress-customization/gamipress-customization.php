<?php
/**
 * GamiPress Leaderboard Customization
 * 
 * This file contains customizations for GamiPress Leaderboards including
 * custom column options and shortcode functionality.
 * 
 * @package GamiPress_Leaderboard_Customization
 * @version 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * GamiPress Leaderboard Customization Class
 * 
 * Handles all customizations for GamiPress Leaderboards using OOP principles
 */
class GamiPress_Leaderboard_Customization {

    /**
     * Instance of this class
     * 
     * @var GamiPress_Leaderboard_Customization
     */
    private static $instance = null;

    /**
     * Custom column key for daily average
     * 
     * @var string
     */
    const DAILY_AVERAGE_COLUMN = 'daily_average_30_days';

    /**
     * Get singleton instance
     * 
     * @return GamiPress_Leaderboard_Customization
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor - Initialize hooks
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Add custom column to leaderboard options
        add_filter( 'gamipress_leaderboards_columns_options', array( $this, 'add_custom_column_option' ) );
        
        // Ensure custom column is included in frontend display
        add_filter( 'gamipress_leaderboards_leaderboard_columns_info', array( $this, 'include_custom_column_in_frontend' ), 10, 3 );
        
        // Handle custom column rendering
        add_filter( 'gamipress_leaderboards_leaderboard_column_' . self::DAILY_AVERAGE_COLUMN, array( $this, 'render_daily_average_column' ), 10, 6 );
        
        // Register shortcode
        add_shortcode( 'gamipress_average_points', array( $this, 'gamipress_average_points_shortcode' ) );
    }

    /**
     * Add custom column option to leaderboard settings
     * 
     * @param array $columns_options Existing column options
     * @return array Modified column options
     */
    public function add_custom_column_option( $columns_options ) {
        // Add our custom column option
        $columns_options[ self::DAILY_AVERAGE_COLUMN ] = __( '30-Day Daily Avg', 'gamipress-leaderboards' );
        
        return $columns_options;
    }

    /**
     * Include custom column in frontend leaderboard display
     * 
     * This method ensures our custom column appears in the frontend even though
     * it's not part of the metrics system.
     * 
     * @param array $final_columns The final columns array
     * @param int $leaderboard_id The leaderboard ID
     * @param object $leaderboard_table The leaderboard table object
     * @return array Modified final columns array
     */
    public function include_custom_column_in_frontend( $final_columns, $leaderboard_id, $leaderboard_table ) {
        // Get the selected columns from the leaderboard settings
        $selected_columns = $leaderboard_table->get_columns();
        
        // Check if our custom column is selected
        if ( in_array( self::DAILY_AVERAGE_COLUMN, $selected_columns ) ) {
            // Get the column options to get the proper label
            $columns_options = gamipress_leaderboards_get_columns_options();
            
            // Add our custom column to the final columns array
            $final_columns[ self::DAILY_AVERAGE_COLUMN ] = $columns_options[ self::DAILY_AVERAGE_COLUMN ];
        }
        
        return $final_columns;
    }

    /**
     * Render the daily average column content
     * 
     * @param string $output The column output
     * @param int $leaderboard_id The leaderboard ID
     * @param int $position The user position
     * @param array $item The user item data
     * @param string $column_name The column name
     * @param object $leaderboard_table The leaderboard table object
     * @return string The rendered column content
     */
    public function render_daily_average_column( $output, $leaderboard_id, $position, $item, $column_name, $leaderboard_table ) {
        // Get user ID from the item
        $user_id = isset( $item['user_id'] ) ? intval( $item['user_id'] ) : 0;
        
        if ( ! $user_id ) {
            return '0';
        }

        // Use our shortcode to get the daily average
        $daily_average = $this->calculate_daily_average( $user_id );
        
        return $daily_average;
    }

    /**
     * Calculate daily average points for a user over the last 30 days
     * 
     * @param int $user_id The user ID
     * @param string $type The points type (default: 'energiepunkte')
     * @return string Formatted daily average
     */
    private function calculate_daily_average( $user_id, $type = 'energiepunkte' ) {
        
        if ( ! $user_id ) {
            return '0';
        }

        global $wpdb;
        $log_table = $wpdb->prefix . 'gamipress_logs';
        $achievement_id = 4669;

        // Try to get achievement earned date
        $result = $wpdb->get_row( $wpdb->prepare(
            "SELECT date 
            FROM {$wpdb->prefix}gamipress_user_earnings 
            WHERE post_id = %d AND user_id = %d
            ORDER BY user_earning_id DESC 
            LIMIT 1",
            $achievement_id,
            $user_id
        ) );

        $current_date = date( 'Y-m-d', current_time( 'timestamp' ) );
        $start_date = date( 'Y-m-d', strtotime( '-30 days', current_time( 'timestamp' ) ) );
        $end_date   = $current_date;
        $days_for_average = 30;

        if ( ! empty( $result ) && ! empty( $result->date ) ) {

            $achievement_earned_date = date( 'Y-m-d', strtotime( $result->date ) );
            $days_since_earned = floor( ( strtotime( $current_date ) - strtotime( $achievement_earned_date ) ) / DAY_IN_SECONDS ) + 1;

            if ( $days_since_earned < 30 ) {
                $start_date = $achievement_earned_date;
                $days_for_average = max( 1, floor( $days_since_earned ) );
            }
        }

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
        $average = $total_points / $days_for_average;

        if ( floor( $average ) == $average ) {
            return number_format( $average, 0 );
        } else {
            return number_format( $average, 2 );
        }
    }

    /**
     * Shortcode handler for gamipress_average_points
     * 
     * Usage: [gamipress_average_points user_id=1 type=points]
     * Supports: type="all" to include all point types
     * 
     * @param array $atts Shortcode attributes
     * @return string The calculated average points
     */
    public function gamipress_average_points_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'user_id' => get_current_user_id(),
            'type'    => 'energiepunkte',
        ), $atts, 'gamipress_average_points' );

        $user_id = intval( $atts['user_id'] );
        $type    = sanitize_text_field( $atts['type'] );

        if ( ! $user_id ) {
            return __( 'User not found.', 'gamipress-leaderboards' );
        }

        $average = $this->calculate_daily_average( $user_id, $type );

        return sprintf(
            '<div class="gamipress-daily-practice" style="text-align:center; font-weight:bold; font-size:1.5em; color:#333;">%s</div>',
            sprintf(
                __( 'Deine durchschnittliche tägliche Trainingsdauer der letzten 30 Tage: %s Minuten', 'gamipress-leaderboards' ),
                $average
            )
        );
    }
}

// Initialize the customization class
GamiPress_Leaderboard_Customization::get_instance();