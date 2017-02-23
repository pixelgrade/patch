<?php
/**
 * Patch Theme Customizer
 *
 * @package Patch
 * @since Patch 1.0
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
	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', 'patch' );

	$wp_customize->add_section( 'title_tagline' , array(
		'title'		=> __( 'Site Title &amp; Logo', 'patch' ),
		'priority'	=> 20,
	));

	$wp_customize->get_control( 'blogname' )->priority = 1;
	$wp_customize->get_control( 'blogdescription' )->priority = 1;
	$wp_customize->get_control( 'header_text' )->priority = 1;
	$wp_customize->get_control( 'custom_logo' )->description = __('Upload a logo image to replace the Site Title and personalize it with your branding. Use the Header section to adjust its size.', 'patch');


	/*
	 * Add custom settings
	 */

	$wp_customize->add_section( 'patch_theme_options', array(
		'title'             => __( 'Theme', 'patch' ),
		'priority'          => 30,
	) );

	$wp_customize->add_setting( 'patch_hide_author_bio', array(
		'default'           => '',
		'sanitize_callback' => 'patch_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'patch_hide_author_bio', array(
		'label'             => __( 'Hide the author bio on single posts.', 'patch' ),
		'section'           => 'patch_theme_options',
		'type'              => 'checkbox',
	) );

	$wp_customize->add_control( 'patch_footer_copyright', array(
		'label'             => __( 'Additional Copyright Text', 'patch' ),
		'description' => '',
		'section'           => 'patch_theme_options',
		'type'              => 'text',
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
	wp_enqueue_script( 'patch_customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20150318', true );
}

add_action( 'customize_preview_init', 'patch_customize_preview_js' );


/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function patch_customize_admin_js() {
	wp_enqueue_script( 'patch_customizer_admin', get_template_directory_uri() . '/assets/js/admin/customizer.js', array( 'wp-ajax-response' ), null, true );
	$translation_array = array(
		'import_failed'               => esc_html__( 'The import didn\'t work completely!', 'patch' ) . '<br/>' . esc_html__( 'Check out the errors given. You might want to try reloading the page and try again.', 'patch' ),
		'import_confirm'              => esc_html__( 'Importing the demo data will overwrite your current site content and options. Proceed anyway?', 'patch' ),
		'import_phew'                 => esc_html__( 'Phew...that was a hard one!', 'patch' ),
		'import_success_note'         => esc_html__( 'The demo data was imported without a glitch! Awesome! ', 'patch' ) . '<br/><br/>',
		'import_success_reload'       => esc_html__( '<i>We have reloaded the page on the right, so you can see the brand new data!</i>', 'patch' ),
		'import_success_warning'      => '<p>' . esc_html__( 'Remember to update the passwords and roles of imported users.', 'patch' ) . '</p><br/>',
		'import_all_done'             => esc_html__( "All done!", 'patch' ),
		'import_working'              => esc_html__( "Working...", 'patch' ),
		'import_widgets_failed'       => esc_html__( "The setting up of the demo widgets failed...", 'patch' ),
		'import_widgets_error'        => esc_html__( 'The setting up of the demo widgets failed', 'patch' ) . '</i><br />(' . esc_html__( 'The script returned the following message', 'patch' ),
		'import_widgets_done'         => esc_html__( 'Finished setting up the demo widgets...', 'patch' ),
		'import_theme_options_failed' => esc_html__( "The importing of the theme options has failed...", 'patch' ),
		'import_theme_options_error'  => esc_html__( 'The importing of the theme options has failed', 'patch' ) . '</i><br />(' . esc_html__( 'The script returned the following message', 'patch' ),
		'import_theme_options_done'   => esc_html__( 'Finished importing the demo theme options...', 'patch' ),
		'import_posts_failed'         => esc_html__( "The importing of the theme options has failed...", 'patch' ),
		'import_posts_step'           => esc_html__( 'Importing posts | Step', 'patch' ),
		'import_error'                => esc_html__( "Error:", 'patch' ),
		'import_try_reload'           => esc_html__( "You can reload the page and try again.", 'patch' ),
	);
	wp_localize_script( 'patch_customizer_admin', 'patch_admin_js_texts', $translation_array );
}

add_action( 'customize_controls_enqueue_scripts', 'patch_customize_admin_js' );


// @todo CLEANUP refactor function names
/**
 * Imports the demo data from the demo_data.xml file
 */
if ( ! function_exists( 'wpGrade_ajax_import_posts_pages' ) ) {
	function wpGrade_ajax_import_posts_pages() {
		// initialize the step importing
		$stepNumber    = 1;
		$numberOfSteps = 1;

		// get the data sent by the ajax call regarding the current step
		// and total number of steps
		if ( ! empty( $_REQUEST['step_number'] ) ) {
			$stepNumber = wp_unslash( sanitize_text_field( $_REQUEST['step_number'] ) );
		}

		if ( ! empty( $_REQUEST['number_of_steps'] ) ) {
			$numberOfSteps = wp_unslash( sanitize_text_field( $_REQUEST['number_of_steps'] ) );
		}

		$response = array(
			'what'         => 'import_posts_pages',
			'action'       => 'import_submit',
			'id'           => 'true',
			'supplemental' => array(
				'stepNumber'    => $stepNumber,
				'numberOfSteps' => $numberOfSteps,
			)
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_posts_pages' );
		}

		require_once( get_template_directory() . '/inc/import/import-demo-posts-pages.php' );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	// hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_posts_pages', 'wpGrade_ajax_import_posts_pages' );
}

/**
 * Imports the theme options from the demo_data.php file
 */
if ( ! function_exists( 'wpGrade_ajax_import_theme_options' ) ) {
	function wpGrade_ajax_import_theme_options() {

		$response = array(
			'what'   => 'import_theme_options',
			'action' => 'import_submit',
			'id'     => 'true',
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_theme_options' );
		}
		require_once( get_template_directory() . '/inc/import/import-demo-theme-options' . EXT );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	// hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_theme_options', 'wpGrade_ajax_import_theme_options' );
}

/**
 * This function imports the widgets from the demo_data.php file and the menus
 */
if ( ! function_exists( 'wpGrade_ajax_import_widgets' ) ) {
	function wpGrade_ajax_import_widgets() {
		$response = array(
			'what'   => 'import_widgets',
			'action' => 'import_submit',
			'id'     => 'true',
		);

		// check if user is allowed to save and if its his intention with
		// a nonce check
		if ( function_exists( 'check_ajax_referer' ) ) {
			check_ajax_referer( 'wpGrade_nonce_import_demo_widgets' );
		}

		require_once( get_template_directory() . '/inc/import/import-demo-widgets.php' );

		$response = new WP_Ajax_Response( $response );
		$response->send();
	}

	//hook into wordpress admin.php
	add_action( 'wp_ajax_wpGrade_ajax_import_widgets', 'wpGrade_ajax_import_widgets' );
} ?>