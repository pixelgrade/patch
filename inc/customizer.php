<?php
/**
 * Patch Theme Customizer
 *
 * @package Patch
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function patch_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	// Rename the label to "Display Site Title & Tagline" in order to make this option clearer.
	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', 'patch_txtd' );

	/*
	 * Add custom settings
	 */

	$wp_customize->add_section( 'patch_theme_options', array(
		'title'             => __( 'Theme', 'patch_txtd' ),
		'priority'          => 30,
	) );

	$wp_customize->add_setting( 'patch_hide_author_bio', array(
		'default'           => '',
		'sanitize_callback' => 'patch_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'patch_hide_author_bio', array(
		'label'             => __( 'Hide the author bio on single posts.', 'patch_txtd' ),
		'section'           => 'patch_theme_options',
		'type'              => 'checkbox',
	) );

}
add_action( 'customize_register', 'patch_customize_register' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean true if is 1 or '1', false if anything else
 */
function patch_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function patch_customize_preview_js() {
	wp_enqueue_script( 'patch_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'patch_customize_preview_js' ); ?>