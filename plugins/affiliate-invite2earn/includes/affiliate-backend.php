<?php

/**
 * Create backend list table for affiliate system
 */
if( ! defined( 'ABSPATH' ) ) exit;
 
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Affiliate_List_Table extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'affiliate',
            'plural' => 'affiliates',
            'ajax' => false
        ));
    }

    public function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'user_earnings' => 'Earnings',
            'payment_method' => 'Payment Method',
            'referal_link' => 'Referral Link',
            'status' => 'Status',
            'actions' => 'Actions'
        );
        return $columns;
    }

    public function column_default($item, $column_name) {
        return $item[$column_name];
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="affiliate_id[]" value="%s" />',
            $item['id']
        );
    }

    public function column_actions($item) {
        $actions = array(
            'approve' => sprintf('<a href="?page=%s&action=%s&affiliate_id=%s">Approve</a>', $_REQUEST['page'], 'approve', $item['id']),
            'delete' => sprintf('<a href="?page=%s&action=%s&affiliate_id=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']),
        );

        return $this->row_actions($actions);
    }

    public function get_bulk_actions() {
        $actions = array(
            'delete' => 'Delete',
            'approve' => 'Approve'
        );
        return $actions;
    }

    public function process_bulk_action() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aie_affiliate_user_data';

        if ('delete' === $this->current_action()) {
            $affiliate_ids = isset($_REQUEST['affiliate_id']) ? $_REQUEST['affiliate_id'] : array();
            if (!empty($affiliate_ids)) {
                foreach ($affiliate_ids as $affiliate_id) {
                    $wpdb->delete($table_name, array('id' => $affiliate_id), array('%d'));
                }
            }
        } elseif ('approve' === $this->current_action()) {
            $affiliate_ids = isset($_REQUEST['affiliate_id']) ? $_REQUEST['affiliate_id'] : array();
            if (!empty($affiliate_ids)) {
                foreach ($affiliate_ids as $affiliate_id) {
                    $wpdb->update($table_name, array('status' => 'approved'), array('id' => $affiliate_id), array('%s'), array('%d'));
                }
            }
        }
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'aie_affiliate_user_data';

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();

        $per_page = 10;
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        $data = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $table_name ORDER BY user_id DESC LIMIT %d OFFSET %d", $per_page, ($current_page - 1) * $per_page),
            ARRAY_A
        );

        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }
}

function affiliate_menu_page() {
    add_menu_page('Affiliate Management', 'Affiliate Management', 'manage_options', 'affiliate_management', 'render_affiliate_management_page');
	add_submenu_page('affiliate_management', 'Send Email & Update Earnings', 'Send Email & Update Earnings', 'manage_options', 'send_email_update_earnings', 'render_send_email_update_earnings_page');
}
/**
 * Send point to user 
 */
function render_send_email_update_earnings_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'aie_affiliate_user_data';

    // Get approved users
    $approved_users = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE status = %s", 'approved'),
        ARRAY_A
    );

    // Add content for the Send Email & Update Earnings submenu page
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Send Email & Update Earnings</h1>

        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th class="manage-column check-column">
                            <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                            <input id="cb-select-all-1" type="checkbox">
                        </th>
                        <th>User ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($approved_users as $user) {
                        ?>
                        <tr>
                            <th class="check-column">
                                <input type="checkbox" name="selected_users[]" value="<?php echo esc_attr($user['id']); ?>">
                            </th>
                            <td><?php echo esc_html($user['user_id']); ?></td>
                            <td><?php echo esc_html($user['full_name']); ?></td>
                            <td><?php echo esc_html($user['email']); ?></td>
                            <td><input type="text" name="user_points[<?php echo esc_attr($user['id']); ?>]" value="<?php echo $user['user_earnings']; ?>"></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
			<?php wp_nonce_field('update_earnings_action', 'update_earnings_nonce'); ?>
            <input type="hidden" name="action" value="update_earnings">
            <p class="submit">
                <input type="submit" name="update_earnings" class="button button-primary" value="Update Earnings">
            </p>
        </form>
    </div>
    <?php
}

function handle_update_earnings() {

    if (isset($_POST['update_earnings']) && isset($_POST['selected_users']) && isset($_POST['user_points']) && wp_verify_nonce($_POST['update_earnings_nonce'], 'update_earnings_action')) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'aie_affiliate_user_data';

        $selected_users = $_POST['selected_users'];
        $user_points = $_POST['user_points'];
			
        foreach ($selected_users as $user_id) {
            if (isset($user_points[$user_id])) {
                $points = sanitize_text_field($user_points[$user_id]);
                $wpdb->update(
                    $table_name,
                    array('user_earnings' => $points),
                    array('id' => $user_id),
                    array('%s'),
                    array('%d')
                );
            }
        }
    }
	wp_safe_redirect( esc_url_raw( add_query_arg( 'message', 'updated', $_POST['_wp_http_referer'] ) ) );
}

add_action('admin_post_update_earnings', 'handle_update_earnings');

function render_affiliate_management_page() {
    $affiliate_list_table = new Affiliate_List_Table();
    $affiliate_list_table->prepare_items();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Affiliate Management</h1>
        <form method="post">
            <?php $affiliate_list_table->display(); ?>
        </form>
    </div>
    <?php
}

add_action('admin_menu', 'affiliate_menu_page');