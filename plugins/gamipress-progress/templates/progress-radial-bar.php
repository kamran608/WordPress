<?php
/**
 * Progress Radial Bar template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/progress/progress-radial-bar.php
 */
global $gamipress_progress_template_args;

// Shorthand
$a = $gamipress_progress_template_args;

$a['radial_bar_completed'] = 0;

if( $a['total'] > 0 ) {
    $a['radial_bar_completed'] = round( ( $a['current'] / $a['total'] ) * 100 );
}

$a['radial_bar_background_image'] = gamipress_progress_radial_bar_gradient(
    $a['radial_bar_completed'],
    $a['radial_bar_color'],
    $a['radial_bar_background_color']
);

$a['radial_bar_display_text'] = ( $a['radial_bar_text_format'] === 'percent' ?  $a['radial_bar_completed'] . '%' : $a['current'] . '/' . $a['total'] );
?>

<div class="gamipress-progress-radial-bar-wrapper">

    <?php
    /**
     * Before render progress radial bar
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_before_render_progress_radial_bar', $a['current'], $a['total'], $a ); ?>

    <div class="gamipress-progress-radial-bar" style="background-image: <?php echo $a['radial_bar_background_image']; ?>; width: <?php echo $a['radial_bar_size']; ?>px; height: <?php echo $a['radial_bar_size']; ?>px;">

        <div class="gamipress-progress-radial-bar-overlay" style="background-color: <?php echo $a['radial_bar_text_background_color']; ?>;">
            <div style="display: <?php echo ( $a['radial_bar_text'] ? 'inline' : 'none' ); ?>; color: <?php echo $a['radial_bar_text_color']; ?>;"><?php echo $a['radial_bar_display_text']; ?></div>
        </div>

    </div>

    <?php
    /**
     * After render progress radial bar
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_after_render_progress_radial_bar', $a['current'], $a['total'], $a ); ?>

</div>
