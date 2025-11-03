<?php 

/**
 * Clients reviews template
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Clients_Reviews
 */
class Clients_Reviews {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof Clients_Reviews ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * defining constants for plugin
     */
    public function hooks() {

        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_files'] );
        add_action( 'init', [ $this, 'clients_review_post_type' ] );
        add_action( 'add_meta_boxes', [ $this, 'add_review_star_meta_box' ] );
        add_action( 'save_post_clients_review', [ $this, 'save_review_star_meta_data' ], 10, 3 );
        add_shortcode( 'clients_review', [ $this, 'swr_display_clients_review' ] );
		add_shortcode( 'swrice_tabs', [ $this, 'swr_display_swrice_tabs' ] );
    }
		
	/**
     * Shortcode to display swrice tabs
     */
    public function swr_display_swrice_tabs() {

        ob_start();

        ?>
        <div class="swrice-tabs-wrap">
            <div class="swrice-tabs">
                <div class="swrice-tab active swrice-tab-1">1</div>
                <div class="swrice-tab swrice-tab-2">2</div>
                <div class="swrice-tab swrice-tab-3">3</div>
                <div class="swrice-tab swrice-tab-4">4</div>
                <div class="swrice-tab swrice-tab-5">5</div>
                <div class="swrice-tab swrice-tab-6">6</div>
            </div>
            <div class="tab-content-wrap">
                <div class="tab-heading tab-heading-1"><span>Understanding</span> and Research</div>
                <div class="tab-content tab-content-1">
                    Before undertaking any project, we try to fully understand the client's requirements. Only after fully understanding them do we take on the projects. If there are any questions or concerns related to the projects, we address them with the clients beforehand to avoid any problems later on and to ensure the projects are completed successfully.
                </div>
                <div class="tab-heading tab-heading-2"><span>Planning</span> and Concept</div>
                <div class="tab-content tab-content-2">
                    After taking on projects, we first create the basic GUI structure to make the development process easier. We start the development process only after fully planning the project. Then the developer begins working on it, and within 3 to 4 days, we deliver the project to the client.
                </div>
                <div class="tab-heading tab-heading-3"><span>Development</span> and Validation</div>
                <div class="tab-content tab-content-3">
                    While developing a project, we pay special attention to the code structure and security to ensure that no issues arise in the future. We aim for the project to remain fully secure and for the code not to slow down or break the site in any way.
                </div>
                <div class="tab-heading tab-heading-4"><span>QA</span> and Overview</div>
                <div class="tab-content tab-content-4">
                    After developing a project, we thoroughly test and review it so that if there are any issues, we can detect them carefully and fix them before delivery. This ensures that the client does not face any issues. We also conduct a thorough QA of the project's code and pay close attention to the code structure.
                </div>
                <div class="tab-heading tab-heading-5"><span>Project Clearification</span> for Client's</div>
                <div class="tab-content tab-content-5">
                    After the project is completed, we provide the client with a video consultation to ensure they fully understand the project and can enjoy and be satisfied with the development. If the client has any questions, we also address them thoroughly.
                </div>
                <div class="tab-heading tab-heading-6"><span>Project Deliver</span> to Client</div>
                <div class="tab-content tab-content-6">
                    After all the research, QA, and consultations, we hand over the project to the client. The client then tests the project at their leisure. Afterward, we request full payment from the client. They typically pay 50% upfront, and the remaining 50% upon completion of the work. Alternatively, the client may directly place an order on Fiverr once the project is delivered and completed.
                </div>
            </div>
        </div>
        <?php

        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }
	
    /**
     * Shortcode to display clients review
     */
    public function swr_display_clients_review( $atts ) {

        ob_start();

        $args = [
            'post_type'     => 'clients_review',
            'fields'        => 'ids',
            'post_status'   => 'publish',
            'numberposts'   => -1
        ];

        $reviews = get_posts( $args );
        $reviews_count = count( $reviews );
        $second_review_id = 0;
    
        ?>
        <div class="clients-review-wrap">
            <div class="clients-review-sections">
                <div class="client-list" data-total_reviews="<?php echo $reviews_count; ?>">
                    <?php 
                    if( !empty( $reviews ) ) {
                        foreach( $reviews as $index => $review_id ) {

                            $index++;
                            
                            if( $index == 2 ) {
                                $second_review_id = $review_id;
                            }

                            $review_content = get_post_field( 'post_content', $review_id );
                            $review_content = preg_replace('/<!--\s*wp:.*?-->(.*?)<!--\s*\/wp:.*?-->/s', '$1', $review_content);
                            $thumbnail_url = get_the_post_thumbnail_url( $review_id );
                            if( empty( $thumbnail_url ) ) {
                                $thumbnail_url = SWR_ASSETS_URL.'images/client-default-profile.png';
                            }

                            ?>
                            <div class="main-client-img <?php echo 'user-'.$index; ?>">
                                <div class="review-content"><?php echo strip_tags( $review_content ); ?></div>
                                <div class="user-profile">
                                    <img class="user-profile-img" src="<?php echo $thumbnail_url; ?>">
                                </div>
                                <img class="user-quite-icon" src="<?php echo SWR_ASSETS_URL.'images/quotes.svg'; ?>">
                                <div class="user-name"><?php echo get_the_title( $review_id ); ?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="review-section">
                    <div class="star">
                        <span class="dashicons dashicons-star-filled"></span>
                        <span class="dashicons dashicons-star-filled"></span>
                        <span class="dashicons dashicons-star-filled"></span>
                        <span class="dashicons dashicons-star-filled"></span>
                        <span class="dashicons dashicons-star-filled"></span>
                    </div>
                    <div class="content">
                        <?php echo get_post_field( 'post_content', $second_review_id ); ?>
                    </div>
                </div>
            </div>
            <div class="review-section-arrows">
                <div class="review-section-arrow-wrap">
                    <div class="dashicons dashicons-arrow-left-alt review-arrow left-arrow"></div>
                    <div class="dashicons dashicons-arrow-right-alt review-arrow right-arrow"></div>
                </div>
            </div>
        </div>
        <?php

        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     * Save reivews start meta data
     */
    public function save_review_star_meta_data( $post_id, $post, $update ) {

        $review_start = isset( $_POST['review_star_count'] ) ? intval( $_POST['review_star_count'] ) : 0;
        update_post_meta( $post_id, 'review_star_count', $review_start );
    }

    /**
     * Add reviews start count metabox
     */
    public function add_review_star_meta_box() {

        add_meta_box(
            'review_star_meta_box',
            __( 'Review Star Count', 'swrice-functionality' ),
            [ $this, 'render_review_star_meta_box_content' ],
            'clients_review',
            'normal',
            'default'
        );
    }

    /**
     * Reviews start count metabox content
     */
    public function render_review_star_meta_box_content( $post ) {

        $star_count = get_post_meta($post->ID, 'review_star_count', true);
        if( empty( $star_count ) ) {
            $star_count = 5;
        }

        ?>
        <label for="review_star_count"><?php echo __( 'Star Count:', 'swrice-functionality' ); ?></label>
        <input type="number" id="review_star_count" name="review_star_count" value="<?php echo esc_attr($star_count); ?>" min="1" max="5">
        <?php
    }

    /**
     * Create clients review post type
     */
    public function clients_review_post_type() {

        $labels = array(
            'name'                  => _x( 'Client Reviews', 'Post Type General Name', 'swrice-functionality' ),
            'singular_name'         => _x( 'Client Review', 'Post Type Singular Name', 'swrice-functionality' ),
            'menu_name'             => __( 'Client Reviews', 'swrice-functionality' ),
            'name_admin_bar'        => __( 'Client Review', 'swrice-functionality' ),
            'archives'              => __( 'Client Review Archives', 'swrice-functionality' ),
            'attributes'            => __( 'Client Review Attributes', 'swrice-functionality' ),
            'parent_item_colon'     => __( 'Parent Client Review:', 'swrice-functionality' ),
            'all_items'             => __( 'All Client Reviews', 'swrice-functionality' ),
            'add_new_item'          => __( 'Add New Client Review', 'swrice-functionality' ),
            'add_new'               => __( 'Add New', 'swrice-functionality' ),
            'new_item'              => __( 'New Client Review', 'swrice-functionality' ),
            'edit_item'             => __( 'Edit Client Review', 'swrice-functionality' ),
            'update_item'           => __( 'Update Client Review', 'swrice-functionality' ),
            'view_item'             => __( 'View Client Review', 'swrice-functionality' ),
            'view_items'            => __( 'View Client Reviews', 'swrice-functionality' ),
            'search_items'          => __( 'Search Client Review', 'swrice-functionality' ),
            'not_found'             => __( 'Not found', 'swrice-functionality' ),
            'not_found_in_trash'    => __( 'Not found in Trash', 'swrice-functionality' ),
            'featured_image'        => __( 'Featured Image', 'swrice-functionality' ),
            'set_featured_image'    => __( 'Set featured image', 'swrice-functionality' ),
            'remove_featured_image' => __( 'Remove featured image', 'swrice-functionality' ),
            'use_featured_image'    => __( 'Use as featured image', 'swrice-functionality' ),
            'insert_into_item'      => __( 'Insert into client review', 'swrice-functionality' ),
            'uploaded_to_this_item' => __( 'Uploaded to this client review', 'swrice-functionality' ),
            'items_list'            => __( 'Client Reviews list', 'swrice-functionality' ),
            'items_list_navigation' => __( 'Client Reviews list navigation', 'swrice-functionality' ),
            'filter_items_list'     => __( 'Filter client reviews list', 'swrice-functionality' ),
        );

        $args = array(
            'label'                 => __( 'Client Review', 'swrice-functionality' ),
            'description'           => __( 'Client Reviews', 'swrice-functionality' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
            'taxonomies'            => array(),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => true,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
        );

        register_post_type( 'clients_review', $args );
    }

    /**
     *  frontend file enqueue
     */
    public function enqueue_frontend_files() {

        $rand = rand( 1,99999999 );
		wp_enqueue_style('dashicons');
        wp_enqueue_style( 'swr-frontend_style', SWR_ASSETS_URL.'css/frontend.css', [], $rand );
        wp_enqueue_script( 'swr-frontend_script', SWR_ASSETS_URL.'js/frontend.js', ['jquery'], $rand );
    }
}

return Clients_Reviews::instance();