<?php

/**
 * Set referral cookie when user come with referral link
 */
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Set cookie
 */
function aie_save_affiliate_user_cookie() {
    
    if( isset( $_GET['ref'] ) ) {

        $affiliate_ref = sanitize_text_field( $_GET['ref'] );
        $expiration = time() + ( 24 * 60 * 60);
        setcookie( 'swrice_ref', $affiliate_ref, $expiration, COOKIEPATH, COOKIE_DOMAIN );
    }
}

add_action( 'wp', 'aie_save_affiliate_user_cookie' );

/**
 * Redirect user
 */
function aie_redirect_under_condition() {

    $current_url = $_SERVER['REQUEST_URI'];
    if( $current_url === '/referral-program/' ) {

        if( ! is_user_logged_in() ) {
            if( wp_redirect( 'https://swrice.com/wp-login.php') ) {
                exit;
            }
        }
        
        global $wpdb;
        $table_name = $wpdb->prefix . 'aie_affiliate_user_data';
        $approved_users = $wpdb->get_results(
            $wpdb->prepare("SELECT user_id FROM $table_name WHERE status = %s", 'approved'),
            ARRAY_A
        );
        
        if( !empty( $approved_users ) ) {
            foreach ( $approved_users as $user_id ) {
                if( $user_id['user_id'] == get_current_user_id() ) {
                    
                    if ( wp_redirect( 'https://swrice.com/members/swrice-com/referral-program-tab/') ) {
                        exit;
                    }
                }
            }
        }

        $approved_users = $wpdb->get_results(
            $wpdb->prepare("SELECT user_id FROM $table_name WHERE status = %s", 'pending'),
            ARRAY_A
        );

        if( !empty( $approved_users ) ) {
            foreach ( $approved_users as $user_id ) {
                if( $user_id['user_id'] == get_current_user_id() ) {

                    if ( wp_redirect( 'https://swrice.com/referral-pending/') ) {
                        exit;
                    }
                }
            }
        }
    }
}

add_action( 'wp', 'aie_redirect_under_condition' );