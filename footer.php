<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Patch
 * @since Patch 1.0
 */
?>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">

			<?php
//			if ( get_theme_mod( 'patch_footer_copyright', false ) ) {
//				echo get_theme_mod( 'patch_footer_copyright', '' );
//			} else {
//				echo '&copy; '.get_bloginfo('name').' –';
//			}

			$copyright_text = pixelgrade_option( 'patch_footer_copyright_text' );

			var_dump($copyright_text);

			if ( ! empty( $copyright_text ) ) {
				// We need to parse some tags
				// like %year%
				$copyright_text = str_replace( '%year%', date( 'Y' ), $copyright_text );
				echo do_shortcode( $copyright_text );
			}

			printf( ' %1$s <span>'. __('by', 'patch') .'</span> %2$s', 
					'<a href="https://pixelgrade.com/themes/patch/" title="'. __( 'Patch - A Newspaper-Inspired WordPress Theme', 'patch' ) .'" rel="theme">'. __( 'Patch Theme', 'patch' ) .'</a>', 
					'<a href="https://pixelgrade.com" title="'. __( 'The PixelGrade Website', 'patch' ) .'" rel="designer">PixelGrade</a>')?>

		</div><!-- .site-info
		--><div class="back-to-top-wrapper">
			<a href="#top" class="back-to-top-button"><?php get_template_part( 'assets/svg/back-to-top' ); ?></a>
		</div><!--
		--><?php
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'container'      => '',
			'menu_class'     => 'nav  nav--footer',
			'items_wrap'         => '<nav class="footer-menu"><h5 class="screen-reader-text">'.__( 'Footer navigation', 'patch' ).'</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
			'depth'          => 1,
			'fallback_cb'    => '',
		) ); ?>
	</footer><!-- #colophon -->
	<div class="overlay--search">
		<div class="overlay__wrapper">
			<?php get_search_form(); ?>
			<p><?php _e( 'Begin typing your search above and press return to search. Press Esc to cancel.', 'patch' ); ?></p>
		</div>
		<b class="overlay__close"></b>
	</div>
</div><!-- #page -->

<div class="mobile-header">
	<div class="mobile-header-wrapper">
		<button class="navigation__trigger  js-nav-trigger">
			<i class="fa fa-bars"></i><span class="screen-reader-text"><?php _e( 'Menu', 'patch' ); ?></span>
		</button>
		<button class="nav__item--search  search__trigger">
			<i class="fa fa-search"></i>
		</button>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>