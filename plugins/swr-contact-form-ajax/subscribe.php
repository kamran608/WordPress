<?php

/**
 * Unsubscribe from the swrice
 */
function swr_subscribe_form_shortcode() {
	ob_start();
	?>
	<form id="swr-subscribe-form">
		<input type="email" name="user_email" required placeholder="Enter your email" />
		<select name="status">
			<option value="subscribed">Subscribe</option>
			<option value="unsubscribed">Unsubscribe</option>
		</select>
		<button type="submit">Submit</button>
        <div class="swr-loader" style="display:none;"></div>
		<div class="swr-response"></div>
	</form>

	<script>
	jQuery(document).ready(function($) {
        $('#swr-subscribe-form').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            const $response = $form.find('.swr-response');
            const $loader = $form.find('.swr-loader');
            const data = $form.serialize();

            $response.hide().removeClass('success error').html('');
            $loader.show();

            $.post('<?php echo admin_url('admin-ajax.php'); ?>', {
                action: 'swr_handle_subscription',
                ...Object.fromEntries(new URLSearchParams(data))
            }, function(response) {
                $loader.hide();
                const isError = response?.error;
                $response
                    .addClass(isError ? 'error' : 'success')
                    .html(response.message)
                    .fadeIn();
            }, 'json');
        });
    });
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode('swr_subscribe_form', 'swr_subscribe_form_shortcode');

add_action('wp_ajax_nopriv_swr_handle_subscription', 'swr_handle_subscription');
add_action('wp_ajax_swr_handle_subscription', 'swr_handle_subscription');

function swr_handle_subscription() {
    
	global $wpdb;
	$table = $wpdb->prefix . 'swr_email_hygiene';

	$email  = sanitize_email($_POST['user_email'] ?? '');
	$status = sanitize_text_field($_POST['status'] ?? '');

	// Validate email
	if (empty($email) || !is_email($email)) {
		wp_send_json([
			'message' => 'Valid email is required.',
			'error'   => true,
		]);
	}

	$datetime     = current_time('mysql');
	$utc_datetime = gmdate('Y-m-d H:i:s');

	// Check if email already exists
	$existing = $wpdb->get_var(
		$wpdb->prepare("SELECT id FROM $table WHERE user_email = %s", $email)
	);

	if ($existing) {
		// Update existing record
		$wpdb->update(
			$table,
			[
				'status'        => $status,
				'user_datetime' => $datetime,
				'utc_datetime'  => $utc_datetime,
			],
			['user_email' => $email],
			['%s', '%s', '%s'],
			['%s']
		);
		$msg   = 'Your subscription status has been updated.';
		$error = false;
	} else {
		// Insert new record
		$inserted = $wpdb->insert(
			$table,
			[
				'user_email'     => $email,
				'user_id'        => 0,
				'status'         => $status,
				'user_datetime'  => $datetime,
				'utc_datetime'   => $utc_datetime,
			],
			['%s', '%d', '%s', '%s', '%s']
		);

		if ($inserted) {
			$msg   = 'You have been successfully ' . $status . '.';
			$error = false;
		} else {
			$msg   = 'An error occurred. Please try again later.';
			$error = true;
		}
	}

	wp_send_json([
		'message' => $msg,
		'error'   => $error,
	]);
}