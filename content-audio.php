<?php
/**
 * The template for displaying the audio post format on archives.
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
$media   = get_media_embedded_in_content( $content );
if ( ! empty( $media ) ) {
	$content = str_replace( $media[0], '', $content );
} ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php if ( ! empty( $media ) ) : ?>
		<div class="entry-media">
			<?php echo apply_filters( 'embed_oembed_html', $media[0] ); ?>
		</div><!-- .entry-media -->
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