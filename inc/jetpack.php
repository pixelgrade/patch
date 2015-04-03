<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Patch
 * @since Patch 1.0
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function patch_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'type'           => 'scroll',
		'container'      => 'posts',
		'wrapper'        => false,
		'footer'         => 'page',
	) );

	/**
	 * Add theme support for site logo
	 *
	 * First, it's the image size we want to use for the logo thumbnails
	 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
	 */
	add_theme_support( 'site-logo', array(
		'size'        => 'patch-site-logo',
		'header-text' => array(
			'site-title',
			'site-description-text',
		)
	) );

	add_image_size( 'patch-site-logo', 1000, 500, false );

	/**
	 * Add theme support for Jetpack responsive videos
	 */
	add_theme_support( 'jetpack-responsive-videos' );
}

add_action( 'after_setup_theme', 'patch_jetpack_setup' );

/**
 * Customize the placement of the sharing and likes
 * http://jetpack.me/2013/06/10/moving-sharing-icons/
 */
function patch_remove_to_move_share() {
	if ( ! is_single() ) {
		return; //bail as we don't want to affect anything
	}

	remove_filter( 'post_flair', 'sharing_display', 20 );

	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'post_flair', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
	}
}

//add_action( 'loop_start', 'patch_remove_to_move_share' );

/**
 * Customize the placement of the sharing and likes
 * http://jetpack.me/support/related-posts/customize-related-posts/
 */
function patch_remove_to_move_relatedposts() {
	if ( ! is_single() ) {
		return; //bail as we don't want to affect anything
	}

	if ( class_exists('WPCOM_RelatedPosts') && method_exists( 'WPCOM_RelatedPosts', 'init' ) ) {
		$jprp     = WPCOM_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}

//add_filter( 'wp', 'patch_remove_to_move_relatedposts', 20 );


/**
 * Detect if the footer menu is active and if it is
 * switch Infinite Scroll to click mode
 */
function switch_infinite_scroll_mode() {

	if ( has_nav_menu( 'footer' ) ) return true;
	else return false;
}

add_filter( 'infinite_scroll_has_footer_widgets', 'switch_infinite_scroll_mode' ); ?>