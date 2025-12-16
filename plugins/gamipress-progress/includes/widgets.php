<?php
/**
 * Widgets
 *
 * @package     GamiPress\Progress\Widgets
 * @since       1.1.8
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

require_once GAMIPRESS_PROGRESS_DIR .'includes/widgets/progress-widget.php';

// Register plugin widgets
function gamipress_progress_register_widgets() {

    register_widget( 'gamipress_progress_widget' );

}
add_action( 'widgets_init', 'gamipress_progress_register_widgets' );