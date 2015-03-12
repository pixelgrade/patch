<?php
/**
 * The template for displaying author bio and links
 *
 * @package Patch
 * @since Patch 1.0
 */
?>

<aside class="author-info  media" itemscope itemtype="http://schema.org/Person">
	<div class="author-info__avatar  media__img">

		<?php echo get_avatar( get_the_author_meta( 'user_email' ), 120 ); ?>

	</div>
	<div class="author-info__description  media__body">
		<h3 class="author-info__title"><?php echo get_the_author(); ?></h3>
		<a class="author-info__link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'patch_txtd' ), get_the_author() ); ?>"><?php _e( 'All posts', 'patch_txtd' ); ?></a>
		<p class="author-info__bio" itemprop="description"><?php the_author_meta( 'description' ); ?></p>

		<?php patch_author_bio_links(); ?>

	</div><!-- .author__description -->
</aside><!-- .author__info -->