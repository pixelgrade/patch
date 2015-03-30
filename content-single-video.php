<?php
/**
 * The template for displaying single video post format posts.
 *
 * @package Patch
 * @since Patch 1.0
 */

//get the media objects from the content and bring up only the first one
/* translators: %s: Name of current post */
$content = apply_filters( 'the_content', get_the_content( sprintf(
	__( 'Continue reading %s', 'patch_txtd' ),
	the_title( '<span class="screen-reader-text">', '</span>', false )
) ) );
$media   = patch_get_media_embedded_in_content( $content );
if ( ! empty( $media ) ) {
	$content = str_replace( $media[0], '', $content );
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<div class="entry-meta">

			<?php patch_cats_list(); ?>

			<?php patch_post_format_link(); ?>

			<div class="clearfix">

				<?php patch_posted_on(); ?>

			</div>

		</div><!-- .entry-meta -->

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

	</header><!-- .entry-header -->

	<?php if ( ! empty( $media ) ) : ?>

		<div class="entry-featured entry-media">

			<?php echo apply_filters( 'embed_oembed_html', $media[0] ); ?>

		</div><!-- .entry-media -->

	<?php endif; ?>

	<div class="entry-content">

		<?php
		// the content without the first video in it
		echo $content; ?>

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