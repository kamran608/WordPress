<?php
/**
 * Solution Section Template - EXACT MATCH with Original Plugin
 */

if (!defined('ABSPATH')) exit;

$solution_heading = isset($attributes['solutionHeading']) ? $attributes['solutionHeading'] : 'The Solution';
$solution_icon = isset($attributes['solutionIcon']) ? $attributes['solutionIcon'] : '✅';
$solution_description = isset($attributes['solutionDescription']) ? $attributes['solutionDescription'] : '';

if (empty($solution_heading) && empty($solution_description)) return;
?>

<section class="sppm-section sppm-solution-section">
    <div class="sppm-section-header">
        <h2 class="sppm-section-title">
            <?php if ($solution_icon): ?><span class="sppm-section-icon"><?php echo $solution_icon; ?></span><?php endif; ?>
            <?php echo esc_html($solution_heading); ?>
        </h2>
    </div>
    
    <div class="sppm-solution-content">
        <p><?php echo wp_kses_post($solution_description); ?></p>
    </div>
</section>
