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
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'patch_txtd' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'patch_txtd' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( '<span class="theme-name">Theme: %1$s</span> by %2$s.', 'patch_txtd' ), 'Patch', '<a href="http://pixelgrade.com" rel="designer">PixelGrade</a>' ); ?>
		</div><!-- .site-info
		--><div class="back-to-top-wrapper">
			<a href="#top" class="back-to-top-button"><?php get_template_part( 'assets/svg/back-to-top' ); ?></a>
		</div><!--
		--><?php
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'container'      => '',
			'menu_class'     => 'nav  nav--footer',
			'items_wrap'         => '<nav class="footer-menu"><h5 class="screen-reader-text">'.__( 'Footer navigation', 'patch_txtd' ).'</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
			'depth'          => 1,
		) ); ?>
	</footer><!-- #colophon -->
	<div class="overlay--search">
		<div class="overlay__wrapper">
			<?php get_search_form(); ?>
			<p><?php _e( 'Begin typing your search above and press return to search. Press Esc to cancel.', 'hive_txtd' ); ?></p>
		</div>
		<b class="overlay__close"></b>
	</div>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>