<?php
/**
 * Frontend template for LCP
 *
 * @package SWR_LearnDash_Course_Progress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class LCP_Frontend
 */
class LCP_Frontend {

	private static $instance = null;
	private $lcp_general_color;
    private $lcp_container_width;

	public static function instance() {

		if ( null === self::$instance || ! ( self::$instance instanceof self ) ) {
			self::$instance = new self();
			self::$instance->hooks();
		}

		return self::$instance;
	}

	private function hooks() {

		$this->lcp_general_color = get_option( 'lcp_general_color', '#00CD6A' );
        $this->lcp_container_width = get_option( 'lcp_container_width', '100%' );

		add_action( 'wp_enqueue_scripts', [ $this, 'frontend_enqueue_scripts' ] );
		add_shortcode( 'swr_course_progress', [ $this, 'render_course_progress' ] );
	}

	public function render_course_progress( $atts ) {

		if ( ! is_user_logged_in() ) {
			return esc_html__( 'You must be logged in to view this content.', 'learndash-course-progress' );
		}

		$atts      = shortcode_atts(
			[
				'course_id' => 0,
				'user_id'   => get_current_user_id(),
			],
			$atts,
			'swr_course_progress'
		);

		$course_id = absint( $atts['course_id'] );
		$user_id   = absint( $atts['user_id'] );

		if ( empty( $course_id ) ) {
			return esc_html__( 'The Course ID is required to display the course progress.', 'learndash-course-progress' );
		}

		if ( ! sfwd_lms_has_access( $course_id, $user_id ) ) {
			return esc_html__( 'You are not enrolled in this course.', 'learndash-course-progress' );
		}

		$lesson_list = learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' );
		$topic_list  = learndash_course_get_steps_by_type( $course_id, 'sfwd-topic' );
		$quiz_list   = learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' );

		$completed_lessons = 0;
		foreach ( $lesson_list as $lesson_id ) {
			if ( learndash_is_lesson_complete( $user_id, $lesson_id, $course_id ) ) {
				$completed_lessons++;
			}
		}

		$completed_topics = 0;
		foreach ( $topic_list as $topic_id ) {
			if ( learndash_is_topic_complete( $user_id, $topic_id, $course_id ) ) {
				$completed_topics++;
			}
		}

		$completed_quizzes = 0;
		foreach ( $quiz_list as $quiz_id ) {
			if ( learndash_is_quiz_complete( $user_id, $quiz_id, $course_id ) ) {
				$completed_quizzes++;
			}
		}

		$course_progress = learndash_user_get_course_progress( $user_id, $course_id );
		$completed       = isset( $course_progress['completed'] ) ? absint( $course_progress['completed'] ) : 0;
		$total           = isset( $course_progress['total'] ) ? absint( $course_progress['total'] ) : 0;

		if ( isset( $course_progress['status'] ) && 'completed' === $course_progress['status'] ) {
			$completed = $total;
		}

		$percentage = ( $total > 0 ) ? min( 100, intval( $completed * 100 / $total ) ) : 0;
		$lcp_color  = esc_attr( $this->lcp_general_color );
        $lcp_container_width = esc_attr( $this->lcp_container_width );

		ob_start();
		?>
		<div class="lcp-course-card" style="max-width: <?php echo esc_attr( $lcp_container_width ); ?>;">
			<div class="course-card-wrapper">
				<div class="course-header">
					<div class="course-icon">
						<?php if ( has_post_thumbnail( $course_id ) ) : ?>
							<img src="<?php echo esc_url( get_the_post_thumbnail_url( $course_id ) ); ?>" alt="<?php echo esc_attr( get_the_title( $course_id ) ); ?>">
						<?php endif; ?>
					</div>
					<div class="course-title">
						<h2><?php echo esc_html( LearnDash_Custom_Label::get_label( 'Course' ) ); ?></h2>
						<h3><?php echo esc_html( get_the_title( $course_id ) ); ?></h3>
					</div>
				</div>

				<div class="progress-bar">
					<div class="progress-fill" style="width: <?php echo esc_attr( $percentage ); ?>%; background-color: <?php echo esc_attr( $lcp_color ); ?>;"></div>
				</div>

				<div class="progress-info">
					<div class="progress-stats">
						<?php if ( ! empty( $lesson_list ) ) : ?>
							<span class="progress-pill">
								<?php echo esc_html( $completed_lessons . '/' . count( $lesson_list ) ); ?>
								<small><?php echo esc_html( LearnDash_Custom_Label::get_label( 'lessons' ) ); ?></small>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $topic_list ) ) : ?>
							<span class="progress-pill">
								<?php echo esc_html( $completed_topics . '/' . count( $topic_list ) ); ?>
								<small><?php echo esc_html( LearnDash_Custom_Label::get_label( 'Topics' ) ); ?></small>
							</span>
						<?php endif; ?>

						<?php if ( ! empty( $quiz_list ) ) : ?>
							<span class="progress-pill">
								<?php echo esc_html( $completed_quizzes . '/' . count( $quiz_list ) ); ?>
								<small><?php echo esc_html( LearnDash_Custom_Label::get_label( 'Quizzes' ) ); ?></small>
							</span>
						<?php endif; ?>
					</div>

					<div class="progress-percentage" style="color: <?php echo esc_attr( $lcp_color ); ?>">
						<?php echo esc_html( $percentage ); ?>%
					</div>
				</div>

				<ul class="lesson-list">
					<?php foreach ( $lesson_list as $lesson_id ) :
						$is_complete     = learndash_is_lesson_complete( $user_id, $lesson_id, $course_id );
						$lesson_progress = learndash_lesson_progress( $lesson_id, $course_id );
						$lesson_percent  = isset( $lesson_progress['percentage'] ) ? intval( $lesson_progress['percentage'] ) : 0;
						$stroke_color    = $lesson_percent ? $lcp_color : '';
						?>
						<a target="_blank" href="<?php echo esc_url( get_permalink( $lesson_id ) ); ?>">
							<li>
								<span class="lcp-lesson-title"><?php echo esc_html( get_the_title( $lesson_id ) ); ?></span>

								<div class="progress-circle-container">
									<svg class="progress-circle" viewBox="0 0 36 36" width="40" height="40" <?php echo $is_complete ? 'style="background-color:' . esc_attr( $lcp_color ) . ';"' : ''; ?>>
										<path class="circle-bg"
											d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
											fill="none"
											stroke="#e6e6e6"
											stroke-width="3"/>
										<path class="circle"
											stroke-dasharray="<?php echo esc_attr( $lesson_percent ); ?>, 100"
											d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
											fill="none"
											stroke="<?php echo esc_attr( $stroke_color ); ?>"
											stroke-width="3"
											stroke-linecap="round"/>
									</svg>

									<?php if ( $is_complete ) : ?>
										<div class="checkmark-container" style="position: absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
											<div class="lcp-tick"></div>
										</div>
									<?php endif; ?>
								</div>
							</li>
						</a>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	public function frontend_enqueue_scripts() {

		$version = wp_rand( 1000, 999999 );

		wp_enqueue_style(
			'lcp-frontend-style',
			LCP_ASSETS_URL . 'css/frontend.css',
			[],
			$version
		);

		wp_enqueue_script(
			'lcp-frontend-js',
			LCP_ASSETS_URL . 'js/frontend.js',
			[ 'jquery' ],
			$version,
			true
		);

		wp_localize_script(
			'lcp-frontend-js',
			'CPS',
			[
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'general_color' => esc_attr( $this->lcp_general_color ),
			]
		);
	}
}

LCP_Frontend::instance();