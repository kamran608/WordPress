<?php 

/**
 * Backnd Class
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class GLTI_Backend
 */
class GLTI_Backend {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof GLTI_Backend ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        add_action( 'admin_enqueue_scripts', [ $this, 'glti_backend_enqueue_scripts' ] );
        add_action( 'add_meta_boxes', [ $this, 'glti_add_metaboxes' ], 10, 2 );
        add_action( 'wp_ajax_glc_add_trigger', [ $this, 'glti_add_custom_trigger' ] );
        add_action( 'wp_ajax_glc_deleting_trigger', [ $this, 'glti_delete_trigger' ] );
    }

    /**
     *  Delete trigger
     */
    public function glti_delete_trigger() {

        $response = [];
        $trigger_id = isset( $_POST['trigger_id'] ) ? intval( $_POST['trigger_id'] ) : 0;

        if( empty( $trigger_id ) ) {

            $response['message'] = __( 'Trigger_id not Found', 'gami-ld-customization' );
            $response['status'] = 'false';

            echo json_encode( $response );
            wp_die();
        }

        wp_delete_post( $trigger_id, true );

        $response['message'] = __( 'Trigger has Deleted successfully.', 'gami-ld-customization' );
        $response['status'] = 'true';

        echo json_encode( $response );
        wp_die();
    }

    /**
     * GLC Add gamipress trigger
     */
    public function glti_add_custom_trigger() {

        $response = [];

        $point_type = isset( $_POST['point_type'] ) ? $_POST['point_type'] : '';
        $points = isset( $_POST['points'] ) ? $_POST['points'] : '';
        $limited_time = isset( $_POST['limited_time'] ) ? $_POST['limited_time'] : '';
        $max_amount = isset( $_POST['max_amount'] ) ? $_POST['max_amount'] : '';
        $glc_label = isset( $_POST['glc_label'] ) ? $_POST['glc_label'] : '';
        $point_type_id = isset( $_POST['point_type_id'] ) ? $_POST['point_type_id'] : 0;
        $post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0;
        $post_type = get_post_type( $post_id );

        if( empty( $point_type ) || empty( $points ) || empty( $limited_time ) ) {

            $response['message'] = __( 'Please select all fields', 'gami-ld-customization' );
            $response['status'] = 'false';

            echo json_encode( $response );
            wp_die();
        }

        if ( $post_type == 'sfwd-courses' ) {
            $trigger_type = 'gamipress_ld_complete_specific_course';
        } elseif ( $post_type == 'sfwd-lessons' ) {
            $trigger_type = 'gamipress_ld_complete_specific_lesson';
        } elseif ( $post_type == 'sfwd-topic' ) {
            $trigger_type = 'gamipress_ld_complete_specific_topic';
        } elseif ( $post_type == 'sfwd-quiz' ) {
            $trigger_type = 'gamipress_ld_pass_specific_quiz';
        }

        $trigger_args = array(
            'post_title'    => $glc_label,
            'post_status'   => 'publish',
            'post_parent'   => $point_type_id,
            'post_author'   => 1,
            'post_type'     => 'points-award'
        );

        $trigger_id = wp_insert_post( $trigger_args );
        if( !empty( $trigger_id ) ) {

            $post_meta_array = [
                '_gamipress_points_type'        => $point_type,
                '_gamipress_achievement_post'   => $post_id,
                '_gamipress_points_required'    => 1,
                '_gamipress_user_role_required' => 'administrator',
                '_gamipress_limit'              => 1,
                '_gamipress_limit_type'         => $limited_time,
                '_gamipress_trigger_type'       => $trigger_type,
                '_gamipress_points'             => $points,
                '_gamipress_maximum_earnings'   => $max_amount,
                '_glc_trigger'                  => $post_id
            ];

            foreach( $post_meta_array as $meta_key => $meta_value ) {

                update_post_meta( $trigger_id, $meta_key, $meta_value );
            }
        }

        $trigger_content = self::instance()->get_all_trigger( $post_id );

        $response['message'] = __( 'Trigger is created successfully.', 'gami-ld-customization' );
        $response['trigger_content'] = $trigger_content;
        $response['status'] = 'true';

        echo json_encode( $response );
        wp_die();
    }

    /**
     * Add metabox on quiz/lesson pages
     */
    public function glti_add_metaboxes( $post_type, $post ) {

        if( $post_type != 'sfwd-courses' && $post_type != 'sfwd-lessons' && $post_type != 'sfwd-topic' && $post_type != 'sfwd-quiz' ) {
            return false;
        }

        add_meta_box(
            'glc-create-gamipress-trigger',
            __( 'Create GamiPress Trigger', 'gami-ld-customization' ),
            [ $this, 'glti_create_gamipress_trigger_cb' ],
            $post_type,
            'advanced',
            'default'
        );
    }

    /**
     * Create gamipress trigger content html
     */
    public function glti_create_gamipress_trigger_cb( $post ) {

        $point_type_slugs = gamipress_get_points_types_slugs();
        $points_types = gamipress_get_points_types();
        $post_id = get_the_ID();

        ?>

        <table class="glc-table">
            <thead>
                <tr>
                    <th><?php echo esc_html(__('Point Type', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Points', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Label', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Trigger Type', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Max Earning', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Limited Type', 'gami-ld-customization')); ?></th>
                    <th><?php echo esc_html(__('Action', 'gami-ld-customization')); ?></th>
                </tr>
            </thead>
            <tbody class="glc-display-trigger">
                <?php
                echo self::instance()->get_all_trigger( $post_id );
                ?>
            </tbody>
        </table>


        <div class="glc-trigger-wrapper" data-post_id="<?php echo $post_id; ?>">
            <div class="glc-trigger-column">
                <label><?php echo __( 'Select Point Type:', 'gami-ld-customization' ); ?></label>
                <select class="glc-point-types">
                    <option value=""><?php echo __( 'Select a point type', 'gami-ld-customization' ); ?></option>
                    <?php 
                    if( !empty( $point_type_slugs ) ) {
                        foreach( $point_type_slugs as $slug ) {

                            $point_type_data = isset( $points_types[$slug] ) ? $points_types[$slug] : '';

                            ?>
                            <option data-point_type_id="<?php echo $point_type_data['ID']; ?>" value="<?php echo $slug; ?>"><?php echo ucwords( $slug ); ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="glc-trigger-column">
                <label><?php echo __( 'Number of Points:', 'gami-ld-customization' ); ?></label>
                <input type="number" value="" class="glc-number-of-point">
            </div>
            <div class="glc-trigger-column">
                <label><?php echo __( 'Select limited Time:', 'gami-ld-customization' ); ?></label>
                <select class="glc-limited-time">
                    <option value="unlimited"><?php echo __( 'Unlimited', 'gami-ld-customization' ); ?></option>
                    <option value="minutely"><?php echo __( 'Per minute', 'gami-ld-customization' ); ?></option>
                    <option value="hourly"><?php echo __( 'Per hour', 'gami-ld-customization' ); ?></option>
                    <option value="daily"><?php echo __( 'Per day', 'gami-ld-customization' ); ?></option>
                    <option value="weekly"><?php echo __( 'Per week', 'gami-ld-customization' ); ?></option>
                    <option value="monthly"><?php echo __( 'Per month', 'gami-ld-customization' ); ?></option>
                    <option value="yearly"><?php echo __( 'Per year', 'gami-ld-customization' ); ?></option>
                </select>
            </div>
            <div class="glc-trigger-column">
                <label><?php echo __( 'Enter Max Earning Points:', 'gami-ld-customization' ); ?></label>
                <input type="number" class="glc-max-number-earning" value="">
            </div>
            <div class="glc-trigger-column">
                <label><?php echo __( 'Enter Label:', 'gami-ld-customization' ); ?></label>
                <input type="text" class="glc-label" value="">
            </div>

            <div class="glc-add-trigger button button-primary"><?php echo __( 'Add Trigger', 'gami-ld-customization' ); ?></div>

        </div>
        <?php
    }

    /**
     *  Get all trigger
     */
    public function get_all_trigger( $post_id ) {

        global $wpdb;
        $postmeta_table = $wpdb->prefix.'postmeta';
        $point_type_slugs = gamipress_get_points_types_slugs();
        $points_types = gamipress_get_points_types();

        $glc_triggers = $wpdb->get_results( "SELECT post_id FROM $postmeta_table 
            WHERE meta_key = '_glc_trigger' AND meta_value = $post_id ORDER BY meta_id DESC" );
        
        ob_start();
        if ( ! empty( $glc_triggers ) ) {
            foreach ( $glc_triggers as $trigger ) {
                
                $trigger_id = isset( $trigger->post_id ) ? intval( $trigger->post_id ) : 0;

                $point_type = get_post_meta( $trigger_id, '_gamipress_points_type', true );
                $points = get_post_meta( $trigger_id, '_gamipress_points', true );
                $trigger_type = get_post_meta( $trigger_id, '_gamipress_trigger_type', true );
                $max_earning = get_post_meta( $trigger_id, '_gamipress_maximum_earnings', true );
                $limited_type = get_post_meta( $trigger_id, '_gamipress_limit_type', true );

                ?>
                <tr>
                    <td><?php echo ucwords( $point_type ); ?></td>
                    <td><?php echo $points; ?></td>
                    <td><?php echo get_the_title( $trigger_id ); ?></td>
                    <td><?php echo $trigger_type; ?></td>
                    <td><?php echo $max_earning ; ?></td>
                    <td><?php echo $limited_type; ?></td>
                    <td><span data-trigger_id="<?php echo $trigger_id; ?>" class="glc-delete-trigger dashicons dashicons-trash"></span></td>
                </tr>
                <?php
            }
        } else {

            ?>
            <tr>
                <td colspan="7"> <?php echo __( 'No Triggers Found.', 'gami-ld-customization' ); ?></td>
            </tr>
            <?php
        }
        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     *  Backend Enqueue script
     */
    public function glti_backend_enqueue_scripts() {

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_style( 'glti_backend_style', GLTI_ASSETS_URL.'css/backend.css', [], $random_number );
        wp_enqueue_script( 'glti_backend_js', GLTI_ASSETS_URL.'js/backend.js', [ 'jquery' ], $random_number, true );

        wp_localize_script( 'glti_backend_js', 'GLTI', 
            [
                'ajax_url' => admin_url( 'admin-ajax.php' )
            ] 
        );
    }
}

GLTI_Backend::instance();