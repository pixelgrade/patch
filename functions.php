<?php
/**
 * Patch Child functions and definitions
 *
 * Bellow you will find several ways to tackle the enqueue of static resources/files
 * It depends on the amount of customization you want to do
 * If you either wish to simply overwrite/add some CSS rules or JS code
 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)
 *
 * @package PatchChild
 */

/**
 * Setup Patch Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function patch_child_theme_setup() {
	load_child_theme_textdomain( 'patch-child-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'patch_child_theme_setup' );

/**
 * Add all the extra static resources of the child theme - right now only the style.css file
 */
function patch_child_enqueue_styles() {
	// Here we are adding the child style.css while still retaining all of the parents assets (style.css, JS files, etc)
	wp_enqueue_style( 'patch-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('patch-style') //make sure the the child's style.css comes after the parents so you can overwrite rules
	);
}
add_action( 'wp_enqueue_scripts', 'patch_child_enqueue_styles' );

/**
 * If you want to overwrite whole static resources files from the parent theme, this is the way to do it
 */

/*

function patch_child_enqueue_styles() {
	//Let's assume you want to completely overwrite the main.js file in the parent

	//First you will have to make sure the parent's file is not added
	// see the parent's function.php -> the patch_scripts_styles() function for details like resources names
	wp_dequeue_script( 'patch-scripts' );
	//Remember that the rest of the static resources will still get added like patch-imagesloaded, patch-hoverintent and patch-velocity

	//We will add the main.js from the child theme (located let's say in assets/js/main.js) with the same dependecies as the main.js in the parent
	//This is not required, but I assume you are not modifying that much :)
	wp_enqueue_script( 'patch-child-scripts',
		get_stylesheet_directory_uri() . '/assets/js/main.js',
		array( 'jquery', 'masonry', 'patch-imagesloaded', 'patch-hoverintent', 'patch-velocity' ),
		'1.0.0', true );

	//Now for the style.css

	// Here we are adding the child style.css
	wp_enqueue_style( 'patch-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('patch-style') //make sure the the child's style.css comes after the parents so you can overwrite rules
	);
}
add_action( 'wp_enqueue_scripts', 'patch_child_enqueue_styles', 11 );
//the 11 priority parameter is need so we do this after the function in the parent so there is something to dequeue
//the default priority of any action is 10

*/

/*
 * Let me give you a second example like the above only in this case we will assume you have copied the whole style.css from the parent into your child theme
 */

/*

function patch_child_enqueue_styles() {
	//First you will have to make sure the parent's file is not added
	// see the parent's function.php -> the patch_scripts_styles() function for details like resources names
	wp_dequeue_style( 'patch-style' );

	//Now for the style.css

	// Here we are adding the child style.css
	wp_enqueue_style( 'patch-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('patch-font-awesome-style') //use the same dependencies as the parent because we want to still use FontAwesome icons
	);
}
add_action( 'wp_enqueue_scripts', 'patch_child_enqueue_styles', 11 );

*/