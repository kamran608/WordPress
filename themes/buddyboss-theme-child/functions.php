<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */  

add_action( 'wp_enqueue_scripts', 'buddyboss_theme_child_style' );
				function buddyboss_theme_child_style() {
					wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
					wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
				}

/**
 * Your code goes below.
 */

/**
 * Enqueue custom scripts and styles for LearnDash collapsible sections
 */
add_action( 'wp_enqueue_scripts', 'learndash_collapsible_sections_assets' );
function learndash_collapsible_sections_assets() {
	// Only load on course pages
	if ( function_exists('learndash_is_course_post') && learndash_is_course_post(get_the_ID()) ) {
	    // Random version number (ya timestamp) for cache busting
	    $version = time(); // ya rand(1000, 9999);

	    // Enqueue JavaScript
	    wp_enqueue_script( 
	        'course-sections-toggle', 
	        get_stylesheet_directory_uri() . '/assets/js/course-sections-toggle.js', 
	        array('jquery'), 
	        $version, 
	        true 
	    );
	    
	    // Enqueue CSS file instead of inline styles
	    wp_enqueue_style( 
	        'course-sections-toggle-css', 
	        get_stylesheet_directory_uri() . '/assets/css/course-sections-toggle.css', 
	        array('child-style'), 
	        $version 
	    );
	}
}

if( file_exists( get_stylesheet_directory().'/gamipress-customization/gamipress-customization.php' ) ) {
	require get_stylesheet_directory().'/gamipress-customization/gamipress-customization.php';
}