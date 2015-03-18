<?php
/**
 * The template for displaying image attachments
 *
 * @package Patch
 * @since Patch 1.0
 */
global $content_width;

$content_width = 892; /* pixels */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post();

				get_template_part( 'content', 'attachment' );

				patch_the_image_navigation();

				// The parent post lin

				echo '<nav class="nav-links"><a class="smart-link" href="' . get_the_permalink($post->post_parent) . '">Posted in "' . get_the_title($post->post_parent) . '</a></nav>';

				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End the loop.
		?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>