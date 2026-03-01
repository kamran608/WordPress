<?php
/**
 * LearnDash LD30 Displays section with toggle functionality
 * Custom template for Collapsible Sections for LearnDash Plugin
 *
 * Available Variables:
 * WIP
 *
 * @since 3.0.0
 *
 * @package CollapsibleSectionsLearnDash
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fires before the section title (outside wrapper).
 *
 * @since 3.0.0
 *
 * @param WP_Post $section   `WP_Post` object for section.
 * @param int     $course_id Course ID.
 * @param int     $user_id   User ID.
 */
do_action( 'learndash-before-section-heading', $section, $course_id, $user_id ); ?>
<div class="ld-item-list-section-heading ld-item-section-heading-<?php echo esc_attr( $section->ID ); ?>">
	<?php
	/**
	 * Fires before the section title (inside wrapper).
	 *
	 * @since 3.0.0
	 *
	 * @param WP_Post $section   `WP_Post` object for section.
	 * @param int     $course_id Course ID.
	 * @param int     $user_id   User ID.
	 */
	do_action( 'learndash-before-inner-section-heading', $section, $course_id, $user_id );
	?>
	
	<?php
	// Check if this is the first section and if expand_first_section setting is enabled
	static $is_first_section = true;
	$plugin_instance = CollapsibleSectionsLearnDash::get_instance();
	$expand_first_section = $plugin_instance->get_setting('expand_first_section', true);
	
	// Determine if this section should be expanded by default
	$should_expand = $is_first_section && $expand_first_section;
	$aria_expanded = $should_expand ? 'true' : 'false';
	$expanded_class = $should_expand ? ' expanded' : '';
	$icon_class = $should_expand ? 'dashicons-arrow-down' : 'dashicons-arrow-right';
	
	// Mark that we've processed the first section
	$is_first_section = false;
	?>
	
	<div class="custom-section-heading-wrapper">
		<div class="custom-section-item">
			<div class="custom-section-toggle-btn<?php echo esc_attr($expanded_class); ?>" data-custom-section-id="<?php echo esc_attr( $section->ID ); ?>" role="button" tabindex="0" aria-expanded="<?php echo esc_attr($aria_expanded); ?>" aria-controls="custom-section-content-<?php echo esc_attr( $section->ID ); ?>">
				<div class="custom-section-left">
					<?php 
					if ( function_exists('learndash_is_course_post') && learndash_is_course_post(get_the_ID()) ) {
						?><span class="custom-toggle-icon dashicons <?php echo esc_attr($icon_class); ?>" aria-hidden="true"></span><?php
					}
					?>
					<span class="custom-toggle-text"><?php echo esc_html( $section->post_title ); ?></span>
				</div>
			</div>
		</div>
	</div>
	
	<?php
	/**
	 * Fires after the section title (inside wrapper).
	 *
	 * @since 3.0.0
	 *
	 * @param WP_Post $section   `WP_Post` object for section.
	 * @param int     $course_id Course ID.
	 * @param int     $user_id   User ID.
	 */
	do_action( 'learndash-after-inner-section-heading', $section, $course_id, $user_id );
	?>
</div>
<?php
/**
 * Fires after the section title (outside wrapper).
 *
 * @since 3.0.0
 *
 * @param WP_Post $section   `WP_Post` object for section.
 * @param int     $course_id Course ID.
 * @param int     $user_id   User ID.
 */
do_action( 'learndash-after-section-heading', $section, $course_id, $user_id );
