<?php
/**
 * LearnDash Modern UI Lessons with Collapsible Sections
 * Custom template for Collapsible Sections for LearnDash Plugin
 * Compatible with LearnDash Modern UI (4.16.0+)
 *
 * This template overrides the default Modern UI lessons template to add
 * collapsible functionality by grouping section headings with their lesson content.
 *
 * @since 1.1.0
 * @package CollapsibleSectionsLearnDash
 * 
 * @var array<int, string>                               $sections   Section titles indexed by lesson IDs.
 * @var Course                                           $course     Course model object.
 * @var Lesson[]                                         $lessons    Array of lesson model objects.
 * @var array{lessons: array{paged: int, per_page: int}} $pagination Pagination data.
 * @var Template                                         $this       Current Instance of template engine rendering this template.
 */

use LearnDash\Core\Models\Course;
use LearnDash\Core\Models\Lesson;
use LearnDash\Core\Template\Template;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $lessons ) ) {
	return;
}

// Group lessons by section
$lessons_by_section = array();
$current_section = '';

foreach ( $lessons as $lesson ) {
    $lesson_section = $sections[ $lesson->get_id() ] ?? '';
    
    // If this lesson has a section title, it starts a new section
    if ( ! empty( $lesson_section ) ) {
        $current_section = $lesson_section;
    }
    
    // Group lessons under their section (or under empty string if no section)
    if ( ! isset( $lessons_by_section[ $current_section ] ) ) {
        $lessons_by_section[ $current_section ] = array();
    }
    
    $lessons_by_section[ $current_section ][] = $lesson;
}
?>

<div
	class="ld-accordion__section ld-accordion__section--lessons"
	data-ld-pagination-target="<?php echo esc_attr( LDLMS_Post_Types::LESSON ); ?>"
>
	<div class="ld-accordion__items ld-accordion__items--lessons">
		<?php foreach ( $lessons_by_section as $section_title => $section_lessons ) : ?>
			<?php if ( ! empty( $section_title ) ) : ?>
				<?php 
				// Generate unique section ID
				$section_id = 'section_' . md5( $section_title );
				
				// Get plugin settings
				$plugin_instance = CollapsibleSectionsLearnDash::get_instance();
				$expand_first_section = $plugin_instance->get_setting('expand_first_section', true);
				
				// Use static variable to track first section within this template file only
				static $section_counter = 0;
				$section_counter++;
				$is_first_section = ($section_counter === 1);
				
				// Determine if this section should be expanded by default
				$should_expand = $is_first_section && $expand_first_section;
				$aria_expanded = $should_expand ? 'true' : 'false';
				$expanded_class = $should_expand ? ' expanded' : '';
				$content_expanded_class = $should_expand ? ' expanded' : '';
				$icon_class = $should_expand ? 'dashicons-arrow-down' : 'dashicons-arrow-right';
				?>
				
				<!-- Collapsible Section Header -->
				<div class="custom-modern-section-wrapper">
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
							<?php echo wp_kses_post( $section_title ); ?>
						</span>
					</div>
					
					<!-- Collapsible Section Content -->
					<div class="custom-modern-section-content<?php echo esc_attr($content_expanded_class); ?>" 
						 id="custom-modern-section-content-<?php echo esc_attr( $section_id ); ?>"
						 aria-labelledby="custom-modern-section-toggle-<?php echo esc_attr( $section_id ); ?>">
						
						<?php foreach ( $section_lessons as $lesson ) : ?>
							<?php $this->template( 'modern/course/accordion/lessons/lesson', [ 'lesson' => $lesson ] ); ?>
						<?php endforeach; ?>
						
					</div>
				</div>
				
			<?php else : ?>
				<!-- Lessons without section (render normally) -->
				<?php foreach ( $section_lessons as $lesson ) : ?>
					<?php $this->template( 'modern/course/accordion/lessons/lesson', [ 'lesson' => $lesson ] ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php $this->template( 'modern/course/accordion/lessons/pagination' ); ?>
	</div>
</div>
