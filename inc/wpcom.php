<?php
/**
 * WordPress.com-specific functions and definitions.
 *
 * @package Patch
 * @since   Patch 1.0
 */

/**
 * Adds support for wp.com-specific theme functions.
 *
 * @global array $themecolors
 */
function patch_wpcom_setup() {
	global $themecolors;

	// Set theme colors for third party services.
	if ( ! isset( $themecolors ) ) {
		$themecolors = array(
			'bg'     => 'ffffff',
			'border' => '000000',
			'text'   => '3e3f40',
			'link'   => 'ffde00',
			'url'    => 'ffde00',
		);
	}
}
add_action( 'after_setup_theme', 'patch_wpcom_setup' );

/**
 * Remove sharing from blog home
 *
 */
function patch_remove_share_from_home() {
	if ( ! is_home() ) {
		return;
	}

	remove_filter( 'post_flair', 'sharing_display', 20 );

	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'post_flair', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	}
}
add_action( 'loop_start', 'patch_remove_share_from_home' ); ?>