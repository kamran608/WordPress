<?php
/**
 * Tempalte for affiliate system
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Affiliate_system
 */
class Affiliate_system {
    
    /**
     * @var self
     */
    private static $instance = null;
    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {
        if ( is_null( self::$instance ) && ! ( self::$instance instanceof Affiliate_system ) ) {
            self::$instance = new self;
            self::$instance->hooks();
        }
        return self::$instance;
    }
    /**
     * Define hooks
     */
    private function hooks() {

        add_shortcode( 'affiliate_payment_form', [ $this, 'affiliate_payment_form_shortcode'] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_files'] );
        add_action( 'wp', [ $this, 'Register_affiliate_account' ] );
    }

    /**
     *  frontend file enqueue
     */
    public function enqueue_frontend_files() {

        $rand = rand( 1,99999999 );
        wp_enqueue_style( 'aie_frontend_style', AIE_ASSETS_URL.'css/frontend.css', [], $rand );
        wp_enqueue_script( 'aie_frontend_script', AIE_ASSETS_URL.'js/frontend.js', ['jquery'], $rand );
    }
    /**
     *  affiliate form 
     */
    public function affiliate_payment_form_shortcode() {
        
        ob_start(); ?>
        
        <div class="aie-form-container">
            <h2 class="aie-heading" id="join-form-button"><?php echo __( 'Swrice Referral Registration', 'affiliate-system-for-user' ); ?></h2>
            <form class="form-group" method="post" enctype="multipart/form-data">
                <label for="fullname" class="aie-label"><?php echo __( 'Full Name:', 'affiliate-system-for-user' ); ?></label>
                <input type="text" id="fullname" name="fullname" class="aie-input" required>

                <label for="email" class="aie-label"><?php echo __( 'Email:', 'affiliate-system-for-user' ); ?></label>
                <input type="email" id="email" name="email" class="aie-input" required>
                <label for="password" class="aie-label"><?php echo __( 'Before Registration you need to pay 100 rupees affiliate fees', 'affiliate-system-for-user' ); ?></label>
                <select name="payment_method" class="aie-payment-info-box">
                    <option value=""><?php echo __( 'Registration fees 100 rupees', 'affiliate-system-for-user' ); ?></option>
                    <option value="bank_transfer" ><?php echo __( 'Bank Transfer', 'affiliate-system-for-user' ); ?></option>
                    <option value="easypaisa"><?php echo __( 'Easypaisa', 'affiliate-system-for-user' ); ?></option>
                </select>
                <div class="aie-payment-details">
                  <div class="aie-account-owner-name">NAME: MUHAMMAD ZEESHAN</div>
                  <div>BANK NAME: Meezan Bank</div>
                  <div>Account Number: 01860106691596</div>
                  <div>IBAN: PK86MEZN0001860106691596</div>
                </div>
                <label for="paymentReceipt" class="aie-label"><?php echo __( 'Upload Payment Receipt:', 'affiliate-system-for-user' ); ?></label>
                <div class="aie-file-wrapper">
                    <input type="file" name="paymentReceipt" class="input-file" accept="image/*" required>
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="aie-icon dashicons dashicons-format-image"></i></span>
                        <input readonly type="text" class="aie-form-control" value="Upload Receipt">
                        <span class="input-group-btn">
                            <button class="ai-upload upload-field btn btn-info" type="button"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <?php wp_nonce_field('affiliate_registration_nonce', 'affiliate_registration_nonce'); ?>
                <input name="aie_submit" type="submit" value="Register & Get Code" class="aie-submit-button">
            </form>
        </div>
        <?php

        global $wpdb;
        $table_name = $wpdb->prefix . 'aie_affiliate_user_data';
        $approved_users = $wpdb->get_results(
        $wpdb->prepare("SELECT user_id FROM $table_name WHERE status = %s", 'approved'),
        ARRAY_A
        );

        foreach ( $approved_users as $user_id ) {

            if( $user_id == get_current_user_id() ) {

                if ( wp_redirect( 'https://swrice.com/members/swrice-com/referral-program-tab/') ) {
                    exit;
                }
            }
        }

        $approved_users = $wpdb->get_results(
        $wpdb->prepare("SELECT user_id FROM $table_name WHERE status = %s", 'pending'),
        ARRAY_A
        );

        foreach ( $approved_users as $user_id ) {

            if( $user_id == get_current_user_id() ) {

                if ( wp_redirect( 'https://swrice.com/referral-pending/') ) {
                    exit;
                }
            }
        }

        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     *  Register Affiliate ID
     */

    public function Register_affiliate_account() {

        global $wpdb;
        $affiliate_table = $wpdb->prefix.'aie_affiliate_user_data';
        $affiliate_meta_table = $wpdb->prefix.'aie_affiliate_user_meta';
        $user_id = get_current_user_id();

        if ( isset( $_POST['aie_submit'] ) && isset( $_POST['affiliate_registration_nonce']) && wp_verify_nonce($_POST['affiliate_registration_nonce'], 'affiliate_registration_nonce')) {

            $full_name = isset($_POST['fullname']) ? sanitize_text_field($_POST['fullname']) : '';
            $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
            $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
            if( empty( $payment_method ) ) {
                $payment_method = 'bank_transfer';
            }
            $payment_receipt = isset($_FILES['paymentReceipt']['name']) ? sanitize_text_field($_FILES['paymentReceipt']['name']) : '';
            $referal_link = 'https://swrice.com/?ref='.$user_id;

            // Uploading and moving the file
            $upload_dir = wp_upload_dir();
            $target_dir = $upload_dir['basedir'] . '/affiliate_payment_receipts/';
        
            if (!file_exists($target_dir)) {
                wp_mkdir_p($target_dir);
            }
        
            $file_name = $_FILES['paymentReceipt']['name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $new_file_name = $user_id . '_' . $file_name;
        
            $target_file = $target_dir . $new_file_name;
            move_uploaded_file($_FILES['paymentReceipt']['tmp_name'], $target_file);
        
            // Get the image URL
            $image_url = $upload_dir['baseurl'] . '/affiliate_payment_receipts/' . $new_file_name;
        
            // Inserting data into the database with the image URL
            $wpdb->insert(
                $affiliate_table,
                [
                    'user_id'           => $user_id,
                    'full_name'         => $full_name,
                    'email'             => $email,
                    'user_earnings'     => '0',
                    'payment_method'    => $payment_method,
                    'payment_receipt'   => $image_url,
                    'referal_link'      => $referal_link,
                    'status'            => 'pending'
                ],
                array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );

            if( isset( $_COOKIE['swrice_ref'] ) ) {

                $parent_user_id = intval( $_COOKIE['swrice_ref'] );
                $wpdb->insert(
                $affiliate_meta_table,
                    [
                        'parent_user_id'  => $parent_user_id,
                        'user_id'         => $user_id,
                    ],
                    array( '%d', '%d' )
                );
            }

			if( wp_redirect( 'https://swrice.com/members/swrice-com/referral-program-tab/' ) ){
				exit;
			}
        }
    }
}
Affiliate_system::instance();