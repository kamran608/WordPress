<?php
/**
 * Progress Text template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/progress/progress-text.php
 */
global $gamipress_progress_template_args;

// Shorthand
$a = $gamipress_progress_template_args;

$processed_text = str_replace( array(
    '{current}',
    '{total}',
),
    array(
        $a['current'],
        $a['total']
    ),
    $a['text_pattern']
); ?>

<div class="gamipress-progress-text-wrapper">

    <?php
    /**
     * Before render progress text
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_before_render_progress_text', $a['current'], $a['total'], $a ); ?>

    <div class="gamipress-progress-text">

        <?php echo $processed_text; ?>

    </div>

    <?php
    /**
     * After render progress text
     *
     * @param $current_progress integer Template received arguments
     * @param $total_progress   integer Template received arguments
     * @param $template_args    array   Template received arguments
     */
    do_action( 'gamipress_after_render_progress_text', $a['current'], $a['total'], $a ); ?>

</div>
