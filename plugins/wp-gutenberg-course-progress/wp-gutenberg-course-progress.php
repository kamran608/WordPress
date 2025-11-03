<?php
/**
 * Plugin Name: Gutenberg Course Progress
 * Description: A plugin that integrates a Gutenberg block for displaying course progress using the display_course_progress shortcode.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: wp-gutenberg-course-progress
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin directory
define( 'WGC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Include necessary files
require_once WGC_PLUGIN_DIR . 'includes/register-block.php';

// Enqueue block assets
function wgc_enqueue_block_assets() {
    wp_enqueue_style( 'wgc-editor-style', plugins_url( 'assets/css/editor.css', __FILE__ ) );
    wp_enqueue_script( 'wgc-block-js', plugins_url( 'assets/js/block.js', __FILE__ ), array( 'wp-blocks', 'wp-element', 'wp-editor' ), null, true );
}
add_action( 'enqueue_block_editor_assets', 'wgc_enqueue_block_assets' );

// Initialize the plugin
function wgc_init() {
    // Register any additional hooks or functionality here
}
add_action( 'init', 'wgc_init' );
?>