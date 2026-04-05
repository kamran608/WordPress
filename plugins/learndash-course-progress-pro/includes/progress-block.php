<?php 

/**
 * Frontend template for LCP
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class LCP_Progress_Block
 */
class LCP_Progress_Block {

    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof LCP_Progress_Block ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        add_action( 'enqueue_block_editor_assets', [ $this, 'lpc_enqueue_block_assets' ] );
        add_action( 'init', [ $this, 'register_swr_course_progress_block' ] );
        add_action( 'wp_ajax_lpc_render_shortcode', [ $this, 'ajax_render_shortcode_on_gutenberg' ] );
    }

    /**
     * Enqueue block assets
     */ 
    public function ajax_render_shortcode_on_gutenberg() {

        $course_id = intval($_POST['course_id'] ?? 0);
        $user_id = intval($_POST['user_id'] ?? get_current_user_id());
    
        echo do_shortcode('[swr_course_progress course_id="' . $course_id . '" user_id="' . $user_id . '"]');

        wp_die();
    }

    /**
     * Register block
     */
    public function register_swr_course_progress_block() {

        // Register block script
        wp_register_script(
            'lpc-course-progress-block',
            plugins_url( 'assets/js/block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor' ),
            filemtime( plugin_dir_path( __DIR__ ) . '/assets/js/block.js' )
        );

        // Register block
        register_block_type( 'lpc-course-progress/course-progress', array(
            'title' => __( 'LPC Course Progress', 'learndash-course-progress' ),
            'editor_script' => 'lpc-course-progress-block',
            'editor_style'  => 'lpc-course-progress-editor-style',
            'render_callback' => [ $this, 'render_swr_course_progress_shortcode' ],
            'attributes' => array(
                'courseId' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
                'userId' => array(
                    'type' => 'number',
                    'default' => 0,
                ),
            ),
        ) );
    }

    /**
     * Render LPC Course Progress Shortcode
     */
    public function render_swr_course_progress_shortcode( $attributes ) {

        // Get the course ID and user ID from the block attributes
        $course_id = isset( $attributes['courseId'] ) ? $attributes['courseId'] : 0;
        $user_id = isset( $attributes['userId'] ) ? $attributes['userId'] : get_current_user_id();

        ob_start();

        echo do_shortcode( '[swr_course_progress course_id="' . esc_attr( $course_id ) . '" user_id="' . esc_attr( $user_id ) . '"]' );

        $content = ob_get_contents();
        ob_end_clean();

        // Render the course progress shortcode
        return $content;  
    }

    /**
     *  Frontend Enqueue script
     */
    public function lpc_enqueue_block_assets() {

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_script( 'lcp-block-js', LCP_ASSETS_URL.'js/block.js', [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ], $random_number, true );
    }
}

LCP_Progress_Block::instance();