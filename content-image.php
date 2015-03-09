<?php
/**
 * The template for displaying the image post format on archives.
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-image entry-image--landscape">>
			<?php the_post_thumbnail( 'patch-masonry-image' ); ?>
		</div>
	<?php  else : // we need to search in the content for an image - maybe we find one
		$first_image = patch_get_post_format_first_image();
		if ( ! empty( $first_image ) ) : ?>
			<div class="entry-image entry-image--landscape">
				<?php echo $first_image; ?>
			</div>
		<?php endif;
	endif; ?>

	<footer class="entry-footer">
		<?php patch_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	
</article><!-- #post-## -->