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
		<a class="author__link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" title="<?php printf( __( 'View all posts by %s', 'patch_txtd' ), get_the_author() ); ?>">
			<?php _e( 'All posts', 'patch_txtd' ); ?>
		</a>
		<p class="author__bio" itemprop="description"><?php the_author_meta('description'); ?></p>
		<ul class="author__social-links">
			<?php if ( get_the_author_meta('url') ): ?>
				<li class="author__social-links__list-item">
					<a class="author__social-link" href="<?php echo get_the_author_meta('url') ?>" target="_blank"><?php _e('Website', 'patch_txtd' ); ?></a>
				</li>
			<?php endif; ?>
			<?php if ( get_the_author_meta('user_tw') ): ?>
				<li class="author__social-links__list-item">
					<a class="author__social-link" href="https://twitter.com/<?php echo get_the_author_meta('user_tw') ?>" target="_blank">Twitter</a>
				</li>
			<?php endif; ?>
			<?php if ( get_the_author_meta('user_fb') ): ?>
				<li class="author__social-links__list-item">
					<a class="author__social-link" href="https://www.facebook.com/<?php echo get_the_author_meta('user_fb') ?>" target="_blank">Facebook</a>
				</li>
			<?php endif; ?>
			<?php if ( get_the_author_meta('google_profile') ): ?>
				<li class="author__social-links__list-item">
					<a class="author__social-link" href="<?php echo get_the_author_meta('google_profile') ?>" target="_blank">Google+</a>
				</li>
			<?php endif; ?>
		</ul>
	</div><!-- .author__description -->
</aside><!-- .author__info -->