<?php
/**
 * The template for displaying the post content on single post view
 *
 * @package Patch
 * @since Patch 1.0
 */

//get the post thumbnail aspect ratio specific class
if ( has_post_thumbnail() ) {
	$ar_class = patch_get_post_thumbnail_aspect_ratio_class();
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() && ( 'entry-image--tall' == $ar_class || 'entry-image--portrait' == $ar_class ) ) { ?>

		<div class="entry-featured  entry-thumbnail">
			<?php the_post_thumbnail( 'patch-single-image' ); ?>
		</div>

	<?php } ?>

	<header class="entry-header">
		<div class="entry-meta">

			<?php patch_cats_list(); ?>

			<?php patch_posted_on(); ?>

		</div><!-- .entry-meta -->

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() && ! ( 'entry-image--tall' == $ar_class || 'entry-image--portrait' == $ar_class ) ) { ?>

		<div class="entry-featured  entry-thumbnail">
			<?php the_post_thumbnail( 'patch-single-image' ); ?>
		</div>

	<?php } ?>

	<div class="entry-content">

		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . __( 'Pages:', 'patch_txtd' ),
			'after'  => '</div>',
		) ); ?>

	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php patch_single_entry_footer(); ?>

	</footer><!-- .entry-footer -->
</article><!-- #post-## -->