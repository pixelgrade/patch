<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Patch
 * @since Patch 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<button class="navigation__trigger  js-nav-trigger">
	<i class="fa fa-bars"></i><span class="screen-reader-text"><?php _e( 'Menu', 'patch_txtd' ); ?></span>
</button>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'patch_txtd' ); ?></a>

	<div id="content" class="site-content">
		<div class="container">

		<?php if ( is_single() || is_page() || is_attachment() ) {
			get_template_part( 'content', 'header' );
		} ?>