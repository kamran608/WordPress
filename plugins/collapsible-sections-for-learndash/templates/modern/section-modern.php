<?php
/**
 * LearnDash Modern UI Section with Collapsible Functionality
 * Custom template for Collapsible Sections for LearnDash Plugin
 * Compatible with LearnDash Modern UI (4.16.0+)
 *
 * @since 1.1.0
 * @package CollapsibleSectionsLearnDash
 * 
 * @var string   $title Section Title.
 * @var Template $this  Current Instance of template engine rendering this template.
 */

use LearnDash\Core\Template\Template;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $title ) ) {
	return;
}

// Get section ID from context or generate unique ID
$section_id = isset( $section ) && isset( $section->ID ) ? $section->ID : uniqid( 'section_' );
$course_id = isset( $course_id ) ? $course_id : get_the_ID();
$user_id = isset( $user_id ) ? $user_id : get_current_user_id();

// Simple first section detection using static counter
static $modern_section_counter = 0;
$modern_section_counter++;
$is_first_section = ($modern_section_counter === 1);

// Get plugin setting
$plugin_instance = CollapsibleSectionsLearnDash::get_instance();
$expand_first_section = $plugin_instance->get_setting('expand_first_section', true);

// Determine initial state
$should_expand = $is_first_section && $expand_first_section;
$expanded_class = $should_expand ? ' expanded' : '';
$aria_expanded = $should_expand ? 'true' : 'false';
$icon_class = $should_expand ? 'dashicons-arrow-down' : 'dashicons-arrow-right';
?>

<div class="ld-accordion__subheading-wrapper custom-modern-section-wrapper">
	<div class="custom-modern-section-toggle-btn<?php echo esc_attr($expanded_class); ?>" 
		 data-custom-section-id="<?php echo esc_attr( $section_id ); ?>" 
		 role="button" 
		 tabindex="0" 
		 aria-expanded="<?php echo esc_attr($aria_expanded); ?>" 
		 aria-controls="custom-modern-section-content-<?php echo esc_attr( $section_id ); ?>">
		
		<span class="custom-toggle-icon dashicons <?php echo esc_attr($icon_class); ?>" aria-hidden="true"></span>
		
		<span
			aria-level="3"
			class="ld-accordion__subheading custom-modern-section-title"
			role="heading"
		>
			<?php echo wp_kses_post( $title ); ?>
		</span>
	</div>
</div>
