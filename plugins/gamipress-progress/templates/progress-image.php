<?php
/**
 * Progress Image template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/progress/progress-image.php
 */
global $gamipress_progress_template_args;

// Shorthand
$a = $gamipress_progress_template_args;

$image_complete_style    = 'width:' . $a['image_complete_size']['width'] . 'px;height:' . $a['image_complete_size']['height'] . 'px;';
$image_complete_attr    = 'width="' . $a['image_complete_size']['width'] . '" height="' . $a['image_complete_size']['height'] . '" style="' . $image_complete_style . '"';


$image_incomplete_style  = 'width:' . $a['image_incomplete_size']['width'] . 'px;height:' . $a['image_incomplete_size']['height'] . 'px;';
$image_incomplete_attr  = 'width="' . $a['image_incomplete_size']['width'] . '" height="' . $a['image_incomplete_size']['height'] . '" style="' . $image_incomplete_style . '"';

$image_complete     = ! empty( $a['image_complete'] ) ? '<img src="' . $a['image_complete'] .'" class="gamipress-progress-image-complete" ' . $image_complete_attr . ' />' : '';
$image_incomplete   = ! empty( $a['image_incomplete'] ) ? '<img src="' . $a['image_incomplete'] .'" class="gamipress-progress-image-incomplete" ' . $image_incomplete_attr . ' />' : '';

?>

<div class="gamipress-progress-image-wrapper">

    <?php
    /**
     * Before render progress image
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_before_render_progress_image', $a['current'], $a['total'], $a ); ?>

    <div class="gamipress-progress-image">

        <?php echo str_repeat( $image_complete, $a['current'] ); ?>
        <?php echo str_repeat( $image_incomplete, $a['total'] - $a['current'] ); ?>

    </div>

    <?php
    /**
     * After render progress image
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_after_render_progress_image', $a['current'], $a['total'], $a ); ?>

</div>
