<?php
/**
 * Register Gutenberg Block for Course Progress
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Function to register the block
function register_lpc_course_progress_block() {
    // Register block script
    wp_register_script(
        'lpc-course-progress-block',
        plugins_url( 'assets/js/block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        filemtime( plugin_dir_path( __DIR__ ) . '/assets/js/block.js' )
    );

    // Register block editor styles
    wp_register_style(
        'lpc-course-progress-editor-style',
        plugins_url( 'assets/css/editor.css', __FILE__ ),
        array( 'wp-edit-blocks' ),
        filemtime( plugin_dir_path( __DIR__ ) . '/assets/css/editor.css' )
    );

    // Register block
    register_block_type( 'lpc-course-progress/course-progress', array(
        'title' => __( 'LPC Course Progress', 'text-domain' ), // Block title
        'editor_script' => 'lpc-course-progress-block',
        'editor_style'  => 'lpc-course-progress-editor-style',
        'render_callback' => 'render_lpc_course_progress_shortcode',
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

// Hook to register the block
add_action( 'init', 'register_lpc_course_progress_block' );

// Render function for the shortcode
function render_lpc_course_progress_shortcode( $attributes ) {
    $course_id = isset( $attributes['courseId'] ) ? intval( $attributes['courseId'] ) : 0;
    $user_id = isset( $attributes['userId'] ) ? intval( $attributes['userId'] ) : get_current_user_id();

    return do_shortcode( '[display_course_progress course_id="' . $course_id . '" user_id="' . $user_id . '"]' );
}
?>