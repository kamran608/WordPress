<?php 

/**
 * Frontend template for LCP
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class LCP_Frontend
 */
class LCP_Frontend {

    /**
     * @var self
     */
    private static $instance = null;

    private $lcp_general_color;

    /**
     * @since 1.0
     * @return $this
     */
    public static function instance() {

        if ( is_null( self::$instance ) && ! ( self::$instance instanceof LCP_Frontend ) ) {
            self::$instance = new self;

            self::$instance->hooks();
        }

        return self::$instance;
    }

    /**
     * Define hooks
     */
    private function hooks() {

        $this->lcp_general_color = get_option('lcp_general_color', '#00CD6A');

        add_action( 'wp_enqueue_scripts', [ $this, 'LCP_frontend_enqueue_scripts' ] );
        add_shortcode( 'swr_course_progress', [ $this, 'swr_course_progress_for_a_course' ] );
    }

    /**
     * Shortcode to Display course progress for a user
     */
    public function swr_course_progress_for_a_course( $atts ) {

        ob_start();

        if( !is_user_logged_in() ) {
            
            echo __( 'You must be logged in to view this content.', 'learndash-course-progress' );
            $content = ob_get_contents();
            ob_get_clean();
            return $content;
        }

        $course_id = isset( $atts['course_id'] ) ? intval( $atts['course_id'] ) : 0;
        if( empty( $course_id ) ) {

            echo __( 'The Course ID is required to display the course progress.', 'learndash-course-progress' );
            $content = ob_get_contents();
            ob_get_clean();
            return $content;
        }

        $user_id = isset( $atts['user_id'] ) && !empty( $atts['user_id'] ) ? intval( $atts['user_id'] ) : get_current_user_id();
        if( !sfwd_lms_has_access( $course_id, $user_id ) ) {

            echo __( 'You are not enrolled in this course.', 'learndash-course-progress' );
            $content = ob_get_contents();
            ob_get_clean();
            return $content;
        }

        $lesson_list = learndash_course_get_steps_by_type( $course_id, 'sfwd-lessons' );
        $lesson_count = count( $lesson_list );
        $topic_list = learndash_course_get_steps_by_type( $course_id, 'sfwd-topic' );
        $topic_count = count( $topic_list );
        $quiz_list = learndash_course_get_steps_by_type( $course_id, 'sfwd-quiz' );
        $quiz_count = count( $quiz_list );

        $completed_lessons = 0;
        if( !empty( $lesson_list ) ) {
            foreach( $lesson_list as $lesson_list_id ) {
                if( learndash_is_lesson_complete( $user_id, $lesson_list_id, $course_id ) ) {
                    $completed_lessons++;
                }
            }
        }

        $completed_topics = 0;
        if( !empty( $topic_list ) ) {
            foreach( $topic_list as $topic_list_id ) {
                if( learndash_is_topic_complete( $user_id, $topic_list_id, $course_id ) ) {
                    $completed_topics++;
                }
            }
        }

        $completed_quizzes = 0;
        if( !empty( $quiz_list ) ) {
            foreach( $quiz_list as $quiz_list_id ) {
                if( learndash_is_quiz_complete( $user_id, $quiz_list_id, $course_id ) ) {
                    $completed_quizzes++;
                }
            }
        }

        $course_progress = learndash_user_get_course_progress( $user_id, $course_id );
        if ( isset( $course_progress['completed'] ) ) {
            $completed = absint( $course_progress['completed'] );
        }

        $total = 0;
        if ( isset( $course_progress['total'] ) ) {
            $total = absint( $course_progress['total'] );
        }

        if ( ( isset( $course_progress['status'] ) ) && ( 'completed' === $course_progress['status'] ) ) {
            $completed = $total;
        }

        $percentage = 0;
        if ( $total > 0 ) {
            $percentage = intval( $completed * 100 / $total );
            $percentage = ( $percentage > 100 ) ? 100 : $percentage;
        }

        $lcp_general_color = $this->lcp_general_color;

        ?>
        <div class="course-card">
            <div class="course-card-wrapper">
                <div class="course-header">
                    <div class="course-icon">
                        <?php 
                        if( !empty( get_the_post_thumbnail_url( $course_id ) ) ) {
                            ?>
                            <img src="<?php echo get_the_post_thumbnail_url( $course_id ); ?>">
                            <?php
                        }
                        ?>
                    </div>
                    <div class="course-title">
                        <h2><?php echo LearnDash_Custom_Label::get_label( 'Course' ); ?></h2>
                        <h3><?php echo get_the_title( $course_id ); ?></h3>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo $percentage; ?>%; background-color: <?php echo $lcp_general_color; ?>;"></div>
                </div>
                <div class="progress-info">
                    <?php 
                    if( !empty( $lesson_list ) ) {
                        ?>
                        <span><?php echo $completed_lessons; ?> / <?php echo $lesson_count; ?> <?php echo LearnDash_Custom_Label::get_label( 'lessons' ); ?></span>
                        <?php
                    }

                    if( !empty( $topic_list ) ) {
                        ?>
                        <span><?php echo $completed_topics; ?> / <?php echo $topic_count; ?> <?php echo LearnDash_Custom_Label::get_label( 'Topics' ); ?></span>
                        <?php
                    }

                    if( !empty( $quiz_list ) ) {
                        ?>
                        <span><?php echo $completed_quizzes; ?> / <?php echo $quiz_count; ?> <?php echo LearnDash_Custom_Label::get_label( 'Quizzes' ); ?></span>
                        <?php
                    }
                    ?>
                    <span class="lcp-progress-percentage"><?php echo $percentage; ?>%</span>
                </div>
                <ul class="lesson-list">
                    <?php 
                    if (!empty($lesson_list)) {
                        foreach ($lesson_list as $lesson_id) {
                            $is_lesson_complete = learndash_is_lesson_complete($user_id, $lesson_id, $course_id);
                            $lesson_progress = learndash_lesson_progress($lesson_id, $course_id);
                            $lesson_percentage = isset($lesson_progress['percentage']) ? $lesson_progress['percentage'] : 0;
                            ?>
                            <a href="<?php echo get_the_permalink($lesson_id); ?>">
                                <li>
                                    <span class="lcp-lesson-title"><?php echo get_the_title($lesson_id); ?></span>
                                    
                                    <?php 
                                    $stroke = $lcp_general_color;
                                    if( empty( $lesson_percentage ) ) {
                                        $stroke = '';
                                    }
                                    ?>

                                    <!-- Progress bar container -->
                                    <div class="progress-circle-container" style="display: inline-block; position: relative; width: 22px; height: 22px;">
                                        <!-- Progress circle -->
                                        <svg 
                                            class="progress-circle" 
                                            viewBox="0 0 36 36" 
                                            width="40" 
                                            height="40"
                                            <?php if ( $is_lesson_complete === true ) : ?>
                                                style="background-color: <?php echo esc_attr( $lcp_general_color ); ?>;"
                                            <?php endif; ?>
                                        >

                                            <path class="circle-bg"
                                                d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="#e6e6e6"
                                                stroke-width="4"/>
                                            <path class="circle"
                                                stroke-dasharray="<?php echo $lesson_percentage; ?>, 100"
                                                d="M18 2.0845
                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                a 15.9155 15.9155 0 0 1 0 -31.831"
                                                fill="none"
                                                stroke="<?php echo $stroke; ?>"
                                                stroke-width="4"
                                                stroke-linecap="round"/>
                                        </svg>
                                        <!-- Checkmark if complete -->
                                        <?php if ($is_lesson_complete === true) : ?>
                                            <div class="checkmark-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                                <div class="lcp-tick"></div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            </a>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php

        $content = ob_get_contents();
        ob_get_clean();
        return $content;
    }

    /**
     *  Frontend Enqueue script
     */
    public function LCP_frontend_enqueue_scripts() {

        $random_number = rand( 329423, 39284932 );

        wp_enqueue_style( 'LCP_frontend_style', LCP_ASSETS_URL.'css/frontend.css', [], $random_number );
        wp_enqueue_script( 'LCP_frontend_js', LCP_ASSETS_URL.'js/frontend.js', [ 'jquery' ], $random_number, true );

        wp_localize_script( 'LCP_frontend_js', 'CPS', 
            [
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'general_color' => $this->lcp_general_color,
            ] 
        );
    }
}

LCP_Frontend::instance();