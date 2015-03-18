<?php
/**
 * The template for displaying the quote post format on archives.
 *
 * @package Patch
 */
?>

<?php if ( ! is_singular() ) { echo '<div class="grid__item">'; } ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-meta">

		<?php patch_first_category(); ?>

		<?php patch_posted_on(); ?>

	</div><!-- .entry-meta -->

	<?php
	//let's see if we have a featured image
	$post_thumbnail_style = '';
	if ( has_post_thumbnail() ) {
		$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'patch-single-image' );
		if ( isset( $post_thumbnail[0] ) ) {
			$post_thumbnail_style = 'style="background-image: url(' . esc_url( $post_thumbnail[0] ) . ');"';
		}
	} ?>

	<div class="entry-content" <?php echo $post_thumbnail_style; ?> >

		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"></span>
		<?php endif; ?>

		<div class="content-quote">
			<div class="flexbox">
				<div class="flexbox__item">

					<?php
					/* translators: %s: Name of current post */
					$content = get_the_content( sprintf(
						__( 'Continue reading %s', 'patch_txtd' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );

					//test if there is a </blockquote> tag in here
					if ( false !== strpos( $content,'</blockquote>' ) ) {
						echo $content;
					} else {
						//we will wrap the whole content in blockquote since this is definitely intended as a quote
						echo '<blockquote>' . $content . '</blockquote>';
					} ?>

				</div>
			</div>
		</div>

	</div><!-- .entry-content -->

</article><!-- #post-## -->

<?php if ( ! is_singular() ) { echo '</div>'; } ?>