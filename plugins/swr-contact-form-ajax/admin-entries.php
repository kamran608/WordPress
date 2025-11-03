<?php

/**
 * This file handles the admin page functionality for displaying and managing 
 * entries submitted through the SWR Contact Form Ajax plugin.
 * 
 * It ensures secure access and provides the necessary logic for rendering 
 * the entries in the WordPress admin panel.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', function () {
    add_menu_page('Contact Entries', 'Contact Entries', 'manage_options', 'swr-contact-entries', 'swr_show_entries');
});

function swr_show_entries() {
    global $wpdb;
    $table = $wpdb->prefix . 'swr_contact_entries';

    // Handle single delete
    if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'delete' && check_admin_referer('swr_delete_entry_' . $_GET['id'])) {
        $wpdb->delete($table, ['id' => intval($_GET['id'])]);
        ?>
        <div class="notice notice-success is-dismissible"><p>Entry deleted.</p></div>
        <?php
    }

    // Handle bulk delete
    if (isset($_POST['bulk_delete']) && !empty($_POST['entry_ids']) && check_admin_referer('swr_bulk_delete')) {
        $ids = array_map('intval', $_POST['entry_ids']);
        $wpdb->query("DELETE FROM $table WHERE id IN (" . implode(',', $ids) . ")");
        ?>
        <div class="notice notice-success is-dismissible"><p>Selected entries deleted.</p></div>
        <?php
    }

    // Handle update
    if (isset($_POST['swr_save_entry']) && check_admin_referer('swr_edit_entry')) {
        $id = intval($_POST['id']);
        $wpdb->update($table, [
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_email($_POST['email']),
            'reason' => sanitize_text_field($_POST['reason']),
            'message' => sanitize_textarea_field($_POST['message']),
        ], ['id' => $id]);
        ?>
        <div class="notice notice-success is-dismissible"><p>Entry updated.</p></div>
        <?php
    }

    $entries = $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC");
    ?>

    <div class="wrap">
        <h1>Contact Form Submissions</h1>

        <?php if (isset($_GET['action'], $_GET['id']) && $_GET['action'] === 'edit'):
            $entry = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", intval($_GET['id'])));
            if ($entry): ?>
                <h2>Edit Entry</h2>
                <form method="post">
                    <?php wp_nonce_field('swr_edit_entry'); ?>
                    <input type="hidden" name="id" value="<?= esc_attr($entry->id); ?>">
                    <table class="form-table">
                        <tr><th><label>Name</label></th><td><input type="text" name="name" value="<?= esc_attr($entry->name); ?>" class="regular-text" required></td></tr>
                        <tr><th><label>Email</label></th><td><input type="email" name="email" value="<?= esc_attr($entry->email); ?>" class="regular-text" required></td></tr>
                        <tr><th><label>Reason</label></th><td><input type="text" name="reason" value="<?= esc_attr($entry->reason); ?>" class="regular-text"></td></tr>
                        <tr><th><label>Message</label></th><td><textarea name="message" rows="5" class="large-text"><?= esc_textarea($entry->message); ?></textarea></td></tr>
                    </table>
                    <?php submit_button('Update Entry', 'primary', 'swr_save_entry'); ?>
                </form>
                <hr>
            <?php endif;
        endif; ?>

        <form method="post">
            <?php wp_nonce_field('swr_bulk_delete'); ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column"><input type="checkbox" id="cb-select-all"></td>
                        <th>Name</th><th>Email</th><th>Reason</th><th>Message</th><th>Date</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($entries): foreach ($entries as $entry):
                        $delete_url = wp_nonce_url(admin_url("admin.php?page=swr-contact-entries&action=delete&id={$entry->id}"), 'swr_delete_entry_' . $entry->id);
                        $edit_url = admin_url("admin.php?page=swr-contact-entries&action=edit&id={$entry->id}");
                        ?>
                        <tr>
                            <th class="check-column"><input type="checkbox" name="entry_ids[]" value="<?= esc_attr($entry->id); ?>"></th>
                            <td><?= esc_html($entry->name); ?></td>
                            <td><?= esc_html($entry->email); ?></td>
                            <td><?= esc_html($entry->reason); ?></td>
                            <td><?= esc_html($entry->message); ?></td>
                            <td><?= esc_html($entry->submitted_at); ?></td>
                            <td>
                                <a href="<?= esc_url($edit_url); ?>" class="button button-small">Edit</a>
                                <a href="<?= esc_url($delete_url); ?>" class="button button-small delete-entry" onclick="return confirm('Are you sure you want to delete this entry?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="7">No entries found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <br>
            <?php submit_button('Delete Selected', 'delete', 'bulk_delete', false); ?>
        </form>
    </div>

    <script>
        document.getElementById("cb-select-all").addEventListener("click", function(){
            document.querySelectorAll("input[name='entry_ids[]']").forEach(cb => cb.checked = this.checked);
        });
    </script>
    <?php
}
