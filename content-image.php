<?php
/**
 * The template for displaying the image post format on archives.
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<?php if (!is_singular()) { echo '<div class="grid__item">'; } ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-meta">

		<?php patch_first_category(); ?>

		<?php patch_posted_on(); ?>

	</div><!-- .entry-meta -->

	<?php if ( has_post_thumbnail() ) : ?>

		<a class="entry-image entry-image--landscape" href="<?php the_permalink(); ?>">

			<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
				<span class="sticky-post"></span>
			<?php endif; ?>

			<?php the_post_thumbnail( 'patch-masonry-image' ); ?>

		</a>

	<?php  else : // we need to search in the content for an image - maybe we find one
		$first_image = patch_get_post_format_first_image();
		if ( ! empty( $first_image ) ) : ?>

			<a class="entry-image entry-image--landscape" href="<?php the_permalink(); ?>">

				<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
					<span class="sticky-post"></span>
				<?php endif; ?>

				<?php echo $first_image; ?>

			</a>

		<?php endif;
	endif; ?>

	<header <?php patch_post_title_class(); ?>>

		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

	</header><!-- .entry-header -->

	<footer class="entry-footer">

		<?php patch_entry_footer(); ?>

	</footer><!-- .entry-footer -->
	
</article><!-- #post-## -->

<?php if (!is_singular()) { echo '</div>'; } ?>