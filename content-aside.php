<?php
/**
 * The template for displaying the aside post format on archives.
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content entry-content--long">

		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(
			__( 'Continue reading %s', 'patch_txtd' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		) ); ?>

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