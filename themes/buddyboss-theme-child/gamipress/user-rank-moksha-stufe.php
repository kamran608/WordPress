<?php
/**
 * User Rank template
 *
 * This template can be overridden by copying it to yourtheme/gamipress/user-rank.php
 * To override a specific rank type just copy it as yourtheme/gamipress/user-rank-{rank-type}.php
 */
global $gamipress_template_args;

// Shorthand
$a = $gamipress_template_args;

$rank_type = $a['type'];

if( isset( $a['user_id'] ) ) {
    $user_id = $a['user_id'];
} else {
    $user_id = get_current_user_id();
}

$user_rank_id = gamipress_get_user_rank_id( $user_id, $rank_type );

?>

<div class="user-gamipress-tracker">
	<?php echo do_shortcode( '[swr_gamipress_user_progress user_id="' . $user_id . '" points_type="energiepunkte" level_type="moksha-stufe"]' ); ?>
</div>