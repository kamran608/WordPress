<?php
/**
 * Progress Widget
 *
 * @package     GamiPress\Progress\Widgets\Widget\Progress
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

class GamiPress_Progress_Widget extends GamiPress_Widget {

    /**
     * Shortcode for this widget.
     *
     * @var string
     */
    protected $shortcode = 'gamipress_progress';

    public function __construct() {
        parent::__construct(
            $this->shortcode . '_widget',
            __( 'GamiPress: Progress', 'gamipress-progress' ),
            __( 'Display progress of a desired element or progress of a custom goal.', 'gamipress-progress' )
        );
    }

    public function get_tabs() {
        return GamiPress()->shortcodes[$this->shortcode]->tabs;
    }

    public function get_fields() {
        return GamiPress()->shortcodes[$this->shortcode]->fields;
    }

    public function get_widget( $args, $instance ) {
        // Build shortcode attributes from widget instance
        $atts = gamipress_build_shortcode_atts( $this->shortcode, $instance );

        echo gamipress_do_shortcode( $this->shortcode, $atts );
    }

}