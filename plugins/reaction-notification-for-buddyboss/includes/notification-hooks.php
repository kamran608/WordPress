<?php

/**
 * File for notification hooks
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Notification_hooks
 */
class Notification_hooks {

    /**
     * @var self
     */
    private static  $instance = null ;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {
        
        if ( is_null( self::$instance ) && !self::$instance instanceof Notification_hooks ) {
            self::$instance = new self();

            self::$instance->hooks();
        }
        
        return self::$instance;
    }
    
    /**
     * Plugin hooks
     */
    private function hooks() {

        add_filter( 'bp_notifications_get_registered_components', [ $this, 'rnfb_filter_notifications_get_registered_components' ] );
        add_filter( 'bp_notifications_get_notifications_for_user', [ $this, 'rnfb_format_buddypress_notifications' ], 10, 8 );
        add_action( 'bp_activity_add_user_favorite', [ $this, 'send_rnfb_notification_on_like_or_react' ], 10, 2 );
    }
    
    /**
     * Send notifiction to the post author when someone like/react their comments
     */
    public function send_rnfb_notification_on_like_or_react( $activity_id, $user_id ) {

        $activity = new BP_Activity_Activity( $activity_id );
        $activity_type = isset( $activity->type ) ? $activity->type : '';
        $activity_component = isset( $activity->component ) ? $activity->component : '';

        if ( bp_is_active( 'notifications' ) && !empty($activity->user_id) && $activity->user_id !== $user_id && 'activity_update' == $activity_type ) {

            bp_notifications_add_notification( array(
                'user_id'           => $activity->user_id,
                'item_id'           => $activity_id,
                'secondary_item_id' => $user_id,
                'component_name'    => 'rnfb_react',
                'component_action'  => 'rnfb_react_notification',
                'date_notified'     => bp_core_current_time(),
                'is_new'            => 1,
            ) );
        }
    }
    
    /**
     * Format buddyboss notifications
     */
    public function rnfb_format_buddypress_notifications( $action, $item_id, $secondary_item_id, $total_items, $format = 'string', $main_action = '', $screen = '', $notification_id = '' ) {

        global $wpdb;
        $bb_user_reaction = $wpdb->prefix . 'bb_user_reactions';

        $reaction_id = (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT reaction_id
                FROM {$bb_user_reaction}
                WHERE item_type = %s
                AND user_id = %d
                AND item_id = %d",
                'activity',
                $secondary_item_id,
                $item_id
            )
        );

        if ( 'rnfb_react_notification' === $action ) {

            $all_emotions = bb_active_reactions();
            $reaction = isset( $all_emotions[ $reaction_id ] ) ? $all_emotions[ $reaction_id ] : [];
            $icon_text = isset( $reaction['icon_text'] ) ? $reaction['icon_text'] : '';
            $icon_url  = isset( $reaction['icon_path'] ) ? $reaction['icon_path'] : '';

            $selected_option = get_option( 'rnfb_display_setting', 'both' );

            $updated_message = '';

            if ( $selected_option === 'name' && $icon_text ) {
                $updated_message = '<span style="font-weight: 600; color: #2d3b4c;">' . esc_html( $icon_text ) . '</span> to your post';
            } elseif ( $selected_option === 'icon' && $icon_url ) {
                $updated_message = '<img style="width: 20px; vertical-align:middle;" src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $icon_text ) . '"> to your post';
            } elseif ( $selected_option === 'both' ) {
                $icon_part = $icon_url ? '<img style="width: 20px; vertical-align:middle;" src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $icon_text ) . '"> ' : '';
                $text_part = $icon_text ? '<span style="font-weight: 600; color: #2d3b4c;">' . esc_html( $icon_text ) . '</span> ' : '';
                $updated_message = $icon_part . $text_part . 'to your post';
            }

            $comment = get_comment( $item_id );
            $custom_title = $comment->comment_author . ' ' . strip_tags( $updated_message ) . ' ' . get_the_title( $comment->comment_post_ID );
            $custom_link = add_query_arg( 'rid', (int) $notification_id, bp_activity_get_permalink( $item_id ) );
            $name = bp_core_get_user_displayname( $secondary_item_id );
            $custom_text = sprintf( esc_html__( '%s ', 'buddyboss-theme' ), $name ) . $updated_message;

            if ( 'string' === $format ) {
                $notification_html = sprintf(
                    '<a href="%s" title="%s">%s</a>',
                    esc_url( $custom_link ),
                    esc_attr( $custom_title ),
                    $custom_text // intentionally unescaped to allow image rendering
                );

                $return = apply_filters(
                    'rnfb_filter',
                    $notification_html,
                    $custom_text,
                    $custom_link
                );
            } else {
                $return = apply_filters(
                    'rnfb_filter',
                    array(
                        'text' => wp_strip_all_tags( $custom_text ),
                        'link' => esc_url( $custom_link ),
                    ),
                    $custom_link,
                    (int) $total_items,
                    $custom_text,
                    $custom_title
                );
            }

            return $return;
        }
    }
    
    /**
     * Fake component to BuddyPress. A registered component is needed to add notifications
     */
    public function rnfb_filter_notifications_get_registered_components( $component_names = array() ) {
        
        if ( !is_array( $component_names ) ) {
            $component_names = array();
        }

        array_push( $component_names, 'rnfb_react' );
        return $component_names;
    }
}

Notification_hooks::instance();