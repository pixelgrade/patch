<?php
/**
 * The template for displaying search results pages.
 *
 * @package Patch
 * @since Patch 1.0
 */

get_header();

$classes = 'grid'; ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<div id="posts" class="<?php echo esc_attr( $classes ); ?>">

			<?php get_template_part( 'content-header' ); ?>

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'patch_txtd' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				</header><!-- .page-header -->

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'content', 'search' ); ?>

				<?php endwhile; ?>

				<?php the_posts_navigation(); ?>

			<?php else : ?>

				<?php get_template_part( 'content', 'none' ); ?>

			<?php endif; ?>
		</div><!-- #posts -->
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>