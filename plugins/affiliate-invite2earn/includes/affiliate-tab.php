<?php

// add_action( 'wp', function() {
        
//     $log_file = AIE_INCLUDES_DIR.'error_log.txt';
//     $log_message = '324234234234' . "\n";
//     $handle = fopen($log_file, 'a');
//     if ($handle) {
//         fwrite($handle, $log_message);
//         fclose($handle);
//     }
    
//     file_put_contents($log_file, $log_message, FILE_APPEND);
// } );

/**
 * Create affiliate referral tab
 */
if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Add custom sub-tab on user profile for Referral Program.
 */
function buddyboss_referral_program_tab() {
	if ( ! function_exists( 'bp_core_new_nav_item' ) ||
		 ! function_exists( 'bp_loggedin_user_domain' ) ||
		 empty( get_current_user_id() ) ) {

		return;
	}

	global $bp;

	$args = array();
	$args[] = array(
		'name'            => 'Referral Program',
		'slug'            => 'referral-program-tab',
		'screen_function' => 'referral_program_tab_screen',
		'position'        => 100,
		'parent_url'      => bp_loggedin_user_domain() . '/referral-program-tab/',
		'parent_slug'     => $bp->profile->slug,
	);

	foreach ( $args as $arg ) {
		bp_core_new_nav_item( $arg );
	}
}

add_action( 'bp_setup_nav', 'buddyboss_referral_program_tab' );

/**
 * Set template for the Referral Program tab.
 */
function referral_program_tab_screen() {

	// Add title and content here - last is to call the members plugin.php template.
	add_action( 'bp_template_title', 'referral_program_tab_title' );
	add_action( 'bp_template_content', 'referral_program_tab_content' );
	bp_core_load_template( 'buddypress/members/single/plugins' );

}

/**
 * Set title for the Referral Program tab.
 */
function referral_program_tab_title() {

    global $wpdb;

    $user_id = get_current_user_id();

    if( is_pending_affiliate_user( $user_id ) ) {
        echo __( '<p class="aie-participate-message">Your request is in progress, and we are currently reviewing it. We will respond to it as soon as possible.</p>', 'default' );
        return;
    }

    $is_approved_user = is_approved_affiliate_user( $user_id );
    if( !$is_approved_user ) {
        echo __( '<p class="aie-participate-message">To participate in the referral program, you need to join the Referral program. You can join the referral program by visiting this link:</p><a href="https://swrice.com/referral-program/">Click here</a>', 'default' );
    }
}

/**
 * Check if user has as approved affiliate
 */
function is_approved_affiliate_user( $user_id ) {

    $is_exists = false;

    global $wpdb;
    $affiliate_user_data = $wpdb->prefix.'aie_affiliate_user_data';

    $data = $wpdb->get_results( "SELECT id FROM $affiliate_user_data WHERE user_id = $user_id AND status = 'approved' " );
    if( !empty( $data ) ) {
        $is_exists = true;        
    }

    return $is_exists;
}

/**
 * Check if user has a pending affiliate
 */
function is_pending_affiliate_user( $user_id ) {

    $is_exists = false;

    global $wpdb;
    $affiliate_user_data = $wpdb->prefix.'aie_affiliate_user_data';

    $data = $wpdb->get_results( "SELECT id FROM $affiliate_user_data WHERE user_id = $user_id AND status = 'pending' " );
    if( !empty( $data ) ) {
        $is_exists = true;        
    }

    return $is_exists;
}

/**
 * Display content of the Referral Program tab.
 */
function referral_program_tab_content() {

    // Start output buffering
    ob_start();
	global $wpdb;
	$user_id = get_current_user_id();
    $table_name = $wpdb->prefix . 'aie_affiliate_user_data';

    // Get approved users
    $approved_users = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE status = %s AND user_id = %d ", 'approved', $user_id ),
        ARRAY_A
    );
	if( empty( $approved_users ) ) {
		return false;
	}

    $user_data = isset( $approved_users[0] ) ? $approved_users[0] : [];
    $current_earning = isset( $user_data[ 'user_earnings' ] ) ? $user_data[ 'user_earnings' ] : 0;
    $referal_link = isset( $user_data['referal_link'] ) ? $user_data['referal_link'] : '';

    // Assuming you have a function to get the user's affiliate earning.
    $affiliate_earning = 'working...';

    // Assuming you have a function to get the user's affiliate link.
    $affiliate_link = 'working..';

    ?>
    <div class="affiliate-content">
        <div class="aie-affiliate-earning">
            <div class="aie-earning-label"><i class="fas fa-dollar-sign"></i> <?php echo 'Account Balance'; ?></div>
            <div class="aie-earning-amount"><?php echo 'Rs. '.$current_earning ; ?></div>
            <span class="aie-limit-text"><?php echo __( 'When you have an amount of 1000 or more, you can send a withdrawal request. We will review your request and transfer the payment within an hour.', AIE_TEXT_DOMAIN ); ?></span>
        </div>

        <div class="aie-affiliate-link">
            <div class="aie-link-label"><i class="fas fa-link"></i> <?php echo 'Your Affiliate Link'; ?></div>
            <div class="aie-link-url"><a href="<?php echo $referal_link; ?>"><?php echo $referal_link; ?></a></div>
        </div>

        <div class="aie-withdrawal-option">
            <div class="aie-withdraw-label"><i class="fas fa-money-bill-wave"></i> <?php echo 'Withdraw Earnings'; ?></div>
            <div class="aie-withdraw-link"><?php echo __( 'Withdraw Now', AIE_TEXT_DOMAIN ); ?></div>
        </div>
    </div>

    <?php
    $content = ob_get_clean();
    echo $content;
}