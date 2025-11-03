<?php
if( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class SWR_Header
 */
class SWR_Header {
    
    /**
     * @var self
     */
    private static $instance = null;
    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {
        if ( is_null( self::$instance ) && ! ( self::$instance instanceof SWR_Header ) ) {
            self::$instance = new self;
            self::$instance->hooks();
        }
        return self::$instance;
    }
    /**
     * Define hooks
     */
    private function hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'swr_header_enqueue_scripts' ] );
        add_shortcode( 'swrice_header', [ $this, 'swr_header_content' ] ) ;
        add_action( 'after_setup_theme', [$this, 'swr_register_nav_menu'], 10 );
    }

    /** 
    * Register menus
    */ 
    public function swr_register_nav_menu() {
        register_nav_menus( array(
            'primary_menu' => __( 'Primary Menu', 'custom-swrice' ),
            'footer_menu'  => __( 'Footer Menu', 'custom-swrice' ),
        ) );
    }
    /**
    *  Enqueues frontend scripts
    */
    public function swr_header_enqueue_scripts() {
        $rand = rand( 999, 999999);
        wp_enqueue_script( 'swr_font_awesome_icon', 'https://kit.fontawesome.com/7f146f54bf.js', true,[], $rand );
        wp_enqueue_style( 'swr-header-style', SWR_ASSETS_URL.'css/header.css', [], $rand );
        wp_enqueue_script( 'swr-header-js', SWR_ASSETS_URL . 'js/header.js', [ 'jquery' ], $rand, true );
        wp_localize_script( 'swr-header-js', 'SWR', [
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'security'  => wp_create_nonce( 'ld_admin_nonce' ),
            ] 
        );    
    }

    /**
    *  Header Content
    */
    public function swr_header_content() {

        $menu_items = $this->swr_get_meneus( 'swrice-menu' );
        ob_start();
        ?>
        <div class="swrice-header-wrapper" id="swrice_header_location">
            <header class="swrice-header-wrap">
                <div class="sw-spacing  swrice-logo-wrapper">
                    <a href="<?php echo home_url(); ?>"><img src="<?php echo SWR_ASSETS_URL . 'images/swrice-logo.png'; ?>" alt="Swrice Logo"></a>
                </div>
                <div class="swrice-menu-list-wrap">
                    <div class="swrice-header-mob-wrapper">
                        <div class="sw-spacing swrice-logo-wrapper-mob">
                            <a href="<?php echo home_url(); ?>"><img src="<?php echo SWR_ASSETS_URL . 'images/swrice-logo.png'; ?>" alt="Swrice Logo"></a>
                        </div>
                        <div class="swrice-menu-icon-wrapper-mob">
                            <div class="swrice-menu-icon-holder-mob">
                                <i class="swrice-menu-bar-icon-mob fa-solid fa-xmark" aria-hidden="true"></i>    
                            </div>
                        </div>      
                    </div>
                <?Php
                if ( !empty( $menu_items ) ) {
                    
                    foreach ( $menu_items  as $menu_item ) {
                         $submenu_child_count = isset( $menu_item["children"] ) ? count( $menu_item["children"] ) : 0;
                         $hover_class ='';
                         if ( $submenu_child_count != 0 ) {
                               $hover_class = 'swrice-menu-hover';
                           }  
                        ?>  
                            <li class="swrice-order-list <?php echo $hover_class; ?>">
                                <div class="swrice-menu-handler">
                                    <a class="swrice-bottom-menu" href="<?php echo $menu_item['url'] ?>"><?php echo $menu_item['title']; ?></a>
                                    <?php
                                    $submenu_child_count = isset( $menu_item["children"] ) ? count( $menu_item["children"] ) : 0;  
                                    if ( $submenu_child_count > 0 ) {
                                        ?>
                                        <i class="swrice-down-arrow-icon fa-solid fa-chevron-down"></i>
                                        <?php     
                                    }         
                                    ?>
                                </div>
                                <div class="swrice-bottom-content">
                                    <div class="swrice-header-bottom-grid">
                                        <div class="swrice-submenu-content-holder">
                                            <?php           
                                            foreach( $menu_item["children"] as $submenu ) {
                                           
                                            ?>
                                            <div class="swrice-header-sub-section"> 
                                                <div class="swrice-submenu-heading"><a href="<?php echo esc_url($submenu['url']); ?>"><?php echo $submenu['title'] ?></a></div>
                                                <div class="swrice-submenu-content">
                                                    <?php
                                                    foreach ( $submenu['children'] as $submenu_child ) {
                                                        ?>
                                                        <div>
                                                            <a class="swrice-submenu-list-item" href="<?php echo esc_url($submenu_child['url']); ?>">
                                                            <img src="<?php echo isset($submenu_child['icon_url']) && !empty($submenu_child['icon_url']) ? esc_url($submenu_child['icon_url']) : esc_url(SWR_ASSETS_URL . 'images/default-menu-icon.png'); ?>" alt="Icon">
                                                            <span><?php echo __($submenu_child['title']); ?></span>
                                                            </a>
                                                        </div>
                                                        <?php
                                                     } 
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                            }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php
                    }
                }
                ?>
                </div>
                <div class="swrice-social-icons">
                     <div class="social-icons">
                        <a href="https://www.linkedin.com" target="_blank">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="https://www.facebook.com" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.twitter.com" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                    <a href="<?php echo site_url().'/subscribe/'; ?>" class="swr-subscribe-button">Subscribe</a>
                </div>
                    
                <div class="swrice-menu-bar-icon-wrapper">
                    <div class="swrice-menu-icon-holder">
                        <i class="swrice-menu-bar-icon fa-solid fa-bars-staggered" aria-hidden="true"></i>    
                    </div>
                </div>
            </header>
        </div>
        <?php
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     * Swrice all menues
     */
    public function swr_get_meneus( $menu_name ) {

        $menu_items = wp_get_nav_menu_items( $menu_name );
        $menu_array = [];
        function build_menu_structure( $items, $parent_id = 0 ) {

            $menu_structure = [];
            if ( !empty( $items ) ) {
               
            }
            if ( !empty( $items ) && is_array( $items ) ) {
                foreach( $items as $item ) {

                    if ( $item->menu_item_parent == $parent_id ) {
                        $submenu = build_menu_structure($items, $item->ID);
                        $img_url = get_term_meta( $item->ID, 'swrice_menu_icon', true);
                        $menu_structure[] = [
                            'title' => $item->title,
                            'url' => $item->url,
                            'children' => $submenu,
                            'icon_url' => $img_url
                        ];
                    }
                }
            }
            return $menu_structure;
        }

        $menu_array = build_menu_structure($menu_items);
        function print_menu( $menu_array ) {
            foreach ($menu_array as $menu_item) {
                if( !empty( $menu_item['children'] ) ) {
                    print_menu( $menu_item['children'] );
                }
            }
        }

        return $menu_array;
    }
}

SWR_Header::instance();