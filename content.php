<?php
/**
 * @package Fifteen
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" <?php fifteen_post_thumbnail_class(); ?>>
			<?php the_post_thumbnail( 'fifteen-masonry-image' ); ?>
		</a>
	<?php endif; ?>

	<header <?php fifteen_post_title_class(); ?>>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<div <?php fifteen_post_excerpt_class(); ?>>

		<?php fifteen_post_excerpt(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links"><span class="pagination-title">' . __( 'Pages:', 'fifteen_txtd' ),
			'after'  => '</span></div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'fifteen_txtd' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) ); ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php fifteen_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->