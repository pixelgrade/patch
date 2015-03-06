<?php
/**
 * The template for displaying Author bios
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<aside class="author__info" itemscope itemtype="http://schema.org/Person">
	<div class="author__avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>
	</div>
	<div class="author__description">
		<h3 class="author__title"><?php echo get_the_author(); ?></h3>
		<a class="author__link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'patch_txtd' ), get_the_author() ); ?>"><?php _e( 'All posts', 'patch_txtd' ); ?></a>
		<p class="author__bio" itemprop="description"><?php the_author_meta('description'); ?></p>

		<?php patch_author_bio_links(); ?>

	</div><!-- .author__description -->
</aside><!-- .author__info -->