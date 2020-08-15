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
	wp_enqueue_style('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css');
	wp_enqueue_style( 'twentytwenty-style', get_template_directory_uri() . '/style.css', array('bootstrap') );
	wp_enqueue_style( 'alewife-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'twentytwenty-style', 'bootstrap' )
	);

}

function add_categories_to_pages() {
	register_taxonomy_for_object_type( 'category', 'page' );
}
add_action( 'init', 'add_categories_to_pages' );

function add_pageposts_to_categories($wp_query) {
	if ( $wp_query->get('category_name') ) {
		$posttypes_list = $wp_query->get('post_type');
		if ( is_string ( $posttypes_list ) ) {  // we convert the string var in an array when it's necessary
			//$posttypes_list[] = $posttypes_list;
			//$posttypes_list[] = 'page';           // And here we add the additional type of post_Type, the 'page'.
			$wp_query->set('post_type', 'any');
		}
    }
}
add_action( 'pre_get_posts', 'add_pageposts_to_categories');
