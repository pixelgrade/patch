<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header <?php patch_post_thumbnail_class( 'entry-header' ); ?>>

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<?php if ( has_post_thumbnail() ) : ?>

			<div class="entry-featured  entry-thumbnail">

				<?php the_post_thumbnail( 'patch-single-image' ); ?>

			</div>

		<?php endif; ?>

	</header><!-- .entry-header -->

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'patch' ),
				'after'  => '</div>',
			) );
		?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php edit_post_link( __( 'Edit', 'patch' ), '<span class="edit-link">', '</span>' ); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->