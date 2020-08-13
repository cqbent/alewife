<?php
/**
 * Alewife Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package alewife
 */

add_action( 'wp_enqueue_scripts', 'twentytwenty_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function twentytwenty_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'alewife-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'twentytwenty-style' )
	);

}
