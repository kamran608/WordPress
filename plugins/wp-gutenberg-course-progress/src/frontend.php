<?php 

/**
 * Frontend template for displaying course progress via shortcode
 */

if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Shortcode to Display course progress for a user
 */
function display_course_progress_for_a_course( $atts ) {
    ob_start();

    $course_id = isset( $atts['course_id'] ) ? intval( $atts['course_id'] ) : 0;
    if( empty( $course_id ) ) {
        echo __( 'The Course ID is required to display the course progress.', 'buddyboss-theme' );
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    $user_id = isset( $atts['user_id'] ) ? intval( $atts['user_id'] ) : get_current_user_id();
    if( !sfwd_lms_has_access( $course_id, $user_id ) ) {
        echo __( 'You are not enrolled in this course.', 'buddyboss-theme' );
        $content = ob_get_contents();
        ob_end_clean();
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

    if ( isset( $course_progress['total'] ) ) {
        $total = absint( $course_progress['total'] );
    }

    if ( ( isset( $course_progress['status'] ) ) && ( 'completed' === $course_progress['status'] ) ) {
        $completed = $total;
    }

    if ( $total > 0 ) {
        $percentage = intval( $completed * 100 / $total );
        $percentage = ( $percentage > 100 ) ? 100 : $percentage;
    }

    $lcp_general_color = get_option('lcp_general_color', '#00CD6A');

    ?>
    <div class="course-card">
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
            <div class="progress-fill" style="width: <?php echo $percentage; ?>%;"></div>
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
                            <span><?php echo get_the_title($lesson_id); ?></span>
                            
                            <?php 
                            $stroke = $lcp_general_color;
                            if( empty( $lesson_percentage ) ) {
                                $stroke = '';
                            }
                            ?>

                            <div class="progress-circle-container" style="display: inline-block; position: relative; width: 22px; height: 22px;">
                                <svg class="progress-circle" viewBox="0 0 36 36" width="40" height="40" <?php echo $is_lesson_complete === true ? 'style="background:'.$lcp_general_color.';"' : ''; ?> >
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
                                <?php if ($is_lesson_complete === true) : ?>
                                    <div class="checkmark-container" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                        <i class="bb-icon-l bb-icon-check" style="font-size: 21px; color: #ffffff;"></i>
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
    <?php

    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode( 'display_course_progress', 'display_course_progress_for_a_course' );