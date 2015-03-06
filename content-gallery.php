<?php
/**
 * The template for displaying the gallery post format on archives.
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	//output the first gallery in the content - if it exists
	$gallery = get_post_gallery();
	if ( $gallery ) : ?>
		<aside class="entry-gallery">
			<?php echo $gallery; ?>
		</aside><!-- .entry-gallery -->
	<?php endif; ?>

	<header <?php patch_post_title_class(); ?>>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<div <?php patch_post_excerpt_class(); ?>>

		<?php the_excerpt(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links"><span class="pagination-title">' . __( 'Pages:', 'patch_txtd' ),
			'after'  => '</span></div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'patch_txtd' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) ); ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php patch_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	
</article><!-- #post-## -->