<?php
/*
Plugin Name: Swrice Contact Form
Description: Lightweight contact form with AJAX, admin panel, and floating labels.
Version: 1.1
Author: Swrice
*/

if( !defined('ABSPATH') ) {
    exit; // Exit if accessed directly
}

if( file_exists(plugin_dir_path(__FILE__) . 'admin-entries.php') ) {
    require(plugin_dir_path(__FILE__) . 'admin-entries.php');
}

if( file_exists(plugin_dir_path(__FILE__) . 'subscribe.php') ) {
    require(plugin_dir_path(__FILE__) . 'subscribe.php');
}

// Enqueue scripts and styles
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('swrice-contact-form-style', plugin_dir_url(__FILE__) . 'style.css');
    wp_enqueue_script('swrice-contact-form-script', plugin_dir_url(__FILE__) . 'script.js', ['jquery'], null, true);
    wp_localize_script('swrice-contact-form-script', 'swrice_ajax', ['ajax_url' => admin_url('admin-ajax.php')]);
});

// Shortcode
add_shortcode('swr_contact_form', function () {
    ob_start(); ?>

    <form id="swr-contact-form" class="swr-contact-form">
        <div class="swr-contact-form-wrap">
            <h2 class="swr-form-title">Contact Us</h2>
            
            <div class="swr-field">
                <input type="text" id="swr_name" name="swr_name" placeholder=" " required>
                <label for="swr_name">Your Name</label>
            </div>
            
            <div class="swr-field">
                <input type="email" id="swr_email" name="swr_email" placeholder=" " required>
                <label for="swr_email">Your Email</label>
            </div>
            
            <div class="swr-field">
                <select id="swr_reason" name="swr_reason" required>
                    <option value="" disabled selected hidden>Select a Reason</option>
                    <option value="General Inquiry">General Inquiry</option>
                    <option value="Support">Support</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Partnership">Partnership</option>
                    <option value="Other">Other</option>
                </select>
                <label for="swr_reason" class="select-label">Reason for Contact</label>
            </div>
            
            <div class="swr-field">
                <textarea id="swr_message" name="swr_message" placeholder=" " rows="4" required></textarea>
                <label for="swr_message">Your Message</label>
            </div>
            
            <button type="submit">Send Message</button>
            
            <div class="swr-loader"></div>
            <div id="swr-message"></div>
        </div>
    </form>

    <?php return ob_get_clean();
});

// AJAX handler
add_action('wp_ajax_swr_contact_submit', 'swr_contact_submit');
add_action('wp_ajax_nopriv_swr_contact_submit', 'swr_contact_submit');

function swr_contact_submit() {
    global $wpdb;
    $table = $wpdb->prefix . 'swr_contact_entries';

    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $reason = sanitize_text_field($_POST['reason']);
    $message = sanitize_textarea_field($_POST['message']);
    $wpdb->insert($table, compact('name', 'email', 'reason', 'message'));

    $admin_email = get_option('admin_email');
    $subject = "SWR Contact Form Submission - $reason";
    $body = "You've received a new message via the SWR contact form:\n\n" .
            "Name: $name\n" .
            "Email: $email\n" .
            "Reason: $reason\n" .
            "Message:\n$message\n";

    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: SWR Contact <noreply@swrice.com>'
    );

    $mail_sent = wp_mail($admin_email, $subject, $body, $headers);

    if ($mail_sent) {
        wp_send_json_success("Thanks! We'll get back to you soon.");
    } else {
        wp_send_json_error("Failed to send email. Please try again later.");
    }
    die();
}

// Create table on activation
register_activation_hook(__FILE__, function () {
    global $wpdb;
    $table = $wpdb->prefix . 'swr_contact_entries';
    $charset = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100),
        email VARCHAR(100),
        reason VARCHAR(100),
        message TEXT,
        submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) $charset;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    $email_hygiene_table = $wpdb->prefix . 'swr_email_hygiene'; 
    if ($wpdb->get_var("SHOW TABLES LIKE '$email_hygiene_table'") === $email_hygiene_table) {
        return;
    }

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $email_hygiene_table (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_email VARCHAR(100) NOT NULL,
        user_id BIGINT(20) UNSIGNED NOT NULL,
        user_datetime DATETIME NOT NULL,
        utc_datetime DATETIME NOT NULL,
        status VARCHAR(50) NOT NULL,
        email_template_name VARCHAR(255) NULL DEFAULT NULL,
        PRIMARY KEY (id),
        KEY user_email (user_email),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
});
