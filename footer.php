<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Fifteen
 */
?>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'fifteen_txtd' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'fifteen_txtd' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( '<span class="theme-name">Theme: %1$s</span> by %2$s.', 'fifteen_txtd' ), 'Fifteen', '<a href="http://pixelgrade.com" rel="designer">PixelGrade</a>' ); ?>
		</div><!-- .site-info
		--><div class="back-to-top-wrapper">
			<a href="#top" class="back-to-top-button"><?php get_template_part( 'assets/svg/back-to-top' ); ?></a>
		</div><!--
		--><?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer',
				'container'      => '',
				'menu_class'     => 'nav  nav--footer',
				'items_wrap'         => '<nav class="footer-menu"><h5 class="screen-reader-text">'.__( 'Footer navigation', 'hive_txtd' ).'</h5><ul id="%1$s" class="%2$s">%3$s</ul></nav>',
				'depth'          => 1,
			)
		); ?>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
