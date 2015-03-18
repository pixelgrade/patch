<?php
/**
 * The default template for displaying individual posts on archives
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

		<a href="<?php the_permalink(); ?>" <?php patch_post_thumbnail_class( 'entry-image' ); ?>>

			<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
				<span class="sticky-post"></span>
			<?php endif; ?>

			<span class="hover" role="presentation"><?php _e( 'Read More', 'patch_txtd' ); ?></span>
			<?php the_post_thumbnail( 'patch-masonry-image' ); ?>
		</a>

	<?php endif; ?>

	<header <?php patch_post_title_class(); ?>>

		<?php if ( is_sticky() && ! has_post_thumbnail() && is_home() && ! is_paged() ) : ?>
			<span class="sticky-post"></span>
		<?php endif; ?>

		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

	</header><!-- .entry-header -->

	<div <?php patch_post_excerpt_class(); ?>>

		<?php patch_post_excerpt(); ?>

		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links"><span class="pagination-title">' . __( 'Pages:', 'patch_txtd' ),
			'after'  => '</span></div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'patch_txtd' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) ); ?>

		<?php patch_entry_footer(); ?>

	</div><!-- .entry-content -->
	
</article><!-- #post-## -->

<?php if (!is_singular()) { echo '</div>'; } ?>