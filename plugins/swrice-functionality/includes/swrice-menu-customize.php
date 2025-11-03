<?php
if (!defined('ABSPATH')) exit;

/**
 * Class Swrice_menu_Customization
 */
class Swrice_menu_Customization {

    private static $instance = null;

    public static function instance() {
        if (is_null(self::$instance) && !(self::$instance instanceof Swrice_menu_Customization)) {
            self::$instance = new self;
            self::$instance->hooks();
        }
        return self::$instance;
    }

    private function hooks() {
        add_filter('wp_nav_menu_item_custom_fields', array($this, 'swrice_menu_custom_fields'), 10, 4);
        add_action('wp_update_nav_menu_item', [ $this, 'swrice_field_save'], 10, 3);
        add_action('admin_enqueue_scripts', [$this, 'swrice_backend_enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_media_uploader_scripts']);
    }

    /**
     *  Enqueue Media uploader scripts
     */
    public function enqueue_media_uploader_scripts() {
        wp_enqueue_media();
    }

    /**
     *  Enqueue Admin menu scripts
     */
    public function swrice_backend_enqueue_scripts() {
        wp_enqueue_script('swrice-menus-js', SWR_ASSETS_URL . 'js/admin-header-menu.js', ['jquery'], SWR_VERSION, true);
    }

    /**
     *  Add Custom Menu Field 
     * 
     *  @param $id
     *  @param $item
     *  @param $depth
     *  @param $args
     */
    public function swrice_menu_custom_fields($id, $item, $depth, $args) {
        
        $icon_url = get_term_meta($id, 'swrice_menu_icon', true);
        ?>
        <div class="swrice-menu-custom-field">
            <p class="description description-wide">
                <label for="swrice-menu-icon-<?php echo $id; ?>"><?php echo __('Menu Icon', 'swrice'); ?></label>
                <a href="#" data-id="<?php echo $id; ?>" class="swrice-upload-media-button button"><?php echo __('Upload Icon', 'swrice'); ?></a>
                <input type="hidden" id="swrice-menu-icon-<?php echo $id; ?>" class="widefat code ld-field" name="swrice_menu_icon[<?php echo $id; ?>]" value="<?php echo $icon_url; ?>" />
            </p>
            <div class="swrice-menu-icon-preview-<?php echo $id; ?>">
                <?php
                if ($icon_url) {
                    echo '<img src="' . esc_url($icon_url) . '" alt="Menu Icon Preview" style="max-width:100px;max-height:100px;" />';
                }
                ?>
            </div>
        </div>
        <?php
        return $id;
    }

    /**
     *  Save Menu Icon
     * 
     *  @param $menu_id
     *  @param $menu_item_db_id
     *  @param $menu_item_args
     */
    public function swrice_field_save($menu_id, $menu_item_db_id, $menu_item_args) {

        if (isset( $_REQUEST['swrice_menu_icon'][$menu_item_db_id] ) ) {

            update_term_meta($menu_item_db_id, 'swrice_menu_icon', sanitize_text_field($_REQUEST['swrice_menu_icon'][$menu_item_db_id]));
        }
    }
}

Swrice_menu_Customization::instance();
?>
