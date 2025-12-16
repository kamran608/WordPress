<?php
/**
 * Progress Bar template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/progress/progress-bar.php
 */
global $gamipress_progress_template_args;

// Shorthand
$a = $gamipress_progress_template_args;

$classes = array();

if( (bool) $a['bar_stripe'] ) {
    $classes[] = 'gamipress-progress-bar-striped';
}

if( (bool) $a['bar_animate'] ) {
    $classes[] = 'gamipress-progress-bar-animated';
}

$a['bar_completed'] = 0;

if( $a['total'] > 0 ) {
    $a['bar_completed'] = round( ($a['current'] / $a['total']) * 100 );
}

$a['bar_display_text'] = ( $a['bar_text_format'] === 'percent' ?  $a['bar_completed'] . '%' : $a['current'] . '/' . $a['total'] );
?>

<div class="gamipress-progress-bar-wrapper">

    <?php
    /**
     * Before render progress bar
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_before_render_progress_bar', $a['current'], $a['total'], $a ); ?>

    <div class="gamipress-progress-bar" style="background-color: <?php echo $a['bar_background_color']; ?>;">

        <div class="gamipress-progress-bar-completed <?php echo implode( ' ', $classes ); ?>" role="progressbar" style="width: <?php echo $a['bar_completed']; ?>%; background-color: <?php echo $a['bar_color']; ?>;">
            <div style="display: <?php echo ( $a['bar_text'] ? 'inline-block' : 'none' ); ?>; color: <?php echo $a['bar_text_color']; ?>;"><?php echo $a['bar_display_text']; ?></div>
        </div>

    </div>

    <?php
    /**
     * After render progress bar
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_after_render_progress_bar', $a['current'], $a['total'], $a ); ?>

</div>
