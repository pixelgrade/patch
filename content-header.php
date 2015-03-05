<?php
/**
 * @package Patch
 */
?>
<header id="masthead" class="site-header grid__item" role="banner">
	<div class="entry-card">
		<?php if ( function_exists( 'jetpack_the_site_logo' ) ) { // display the Site Logo if present
			jetpack_the_site_logo();
		} ?>

		<?php
		// on the front page and home page we use H1 for the title
		echo ( is_front_page() && is_home() ) ? '<h1 class="site-title">' : '<div class="site-title">'; ?>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php bloginfo( 'name' ); ?>
			</a>

		<?php echo ( is_front_page() && is_home() ) ? '</h1>' : '</div>'; ?>

		<?php
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) : ?>

			<div class="site-description">
				<span class="site-description-text"><?php bloginfo( 'description' ); ?></span>
			</div>

		<?php endif; ?>

		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="menu-primary-menu" aria-expanded="false"><?php _e( 'Primary Menu', 'patch_txtd' ); ?></button>
			<?php
			$menu_args = array(
				'theme_location' => 'primary',
				'container'      => '',
				'menu_class'     => 'nav  nav--main',
				'fallback_cb' => false,
				'echo' => false,
			);
			$menu = wp_nav_menu( $menu_args ); ?>

		</nav><!-- #site-navigation -->
	</div>
</header><!-- #masthead -->