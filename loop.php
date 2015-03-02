<?php
/**
 * The template for displaying the archives loop content.
 *
 * @package Fifteen
 */

$classes = 'grid'; ?>

<div id="posts" class="<?php echo esc_attr( $classes ); ?>">

<?php
/* Start the Loop */
while ( have_posts() ) : the_post(); ?>

	<div class="grid__item">
		<?php
			/* Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
		get_template_part( 'content', get_post_format() ); ?>
	</div>

<?php endwhile; ?>

</div><!-- .archive__grid -->

<?php the_posts_navigation(); ?>