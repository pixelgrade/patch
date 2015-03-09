<?php
/**
 * The template for displaying the link post format on archives.
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>

		<a href="<?php patch_get_post_format_link_url(); ?>" <?php patch_post_thumbnail_class( 'entry-image' ); ?>>
			<?php the_post_thumbnail( 'patch-masonry-image' ); ?>
		</a>

	<?php else : ?>

		<header <?php patch_post_title_class(); ?>>
			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark"><span class="link__text">', esc_url( patch_get_post_format_link_url() ) ), '</span>&nbsp;<i class="link__icon  fa fa-external-link"></i></a></h1>' ); ?>
		</header><!-- .entry-header -->

	<?php endif; ?>

	<footer class="entry-footer">
		<?php patch_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	
</article><!-- #post-## -->