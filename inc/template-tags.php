<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fifteen
 */

if ( ! function_exists( 'the_posts_navigation' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_posts_navigation() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation posts-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'fifteen_txtd' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( 'Older posts', 'fifteen_txtd' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts', 'fifteen_txtd' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'the_post_navigation' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 */
function the_post_navigation() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'fifteen_txtd' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', '%title' );
				next_post_link( '<div class="nav-next">%link</div>', '%title' );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'fifteen_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function fifteen_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		_x( 'Posted on %s', 'post date', 'fifteen_txtd' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		_x( 'by %s', 'post author', 'fifteen_txtd' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>';

}
endif;

if ( ! function_exists( 'fifteen_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function fifteen_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' == get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'fifteen_txtd' ) );
		if ( $categories_list && fifteen_categorized_blog() ) {
			printf( '<span class="cat-links">' . __( 'Posted in %1$s', 'fifteen_txtd' ) . '</span>', $categories_list );
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'fifteen_txtd' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . __( 'Tagged %1$s', 'fifteen_txtd' ) . '</span>', $tags_list );
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( __( 'Leave a comment', 'fifteen_txtd' ), __( '1 Comment', 'fifteen_txtd' ), __( '% Comments', 'fifteen_txtd' ) );
		echo '</span>';
	}

	edit_post_link( __( 'Edit', 'fifteen_txtd' ), '<span class="edit-link">', '</span>' );
}
endif;

if ( ! function_exists( 'the_archive_title' ) ) :
/**
 * Shim for `the_archive_title()`.
 *
 * Display the archive title based on the queried object.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the title. Default empty.
 * @param string $after  Optional. Content to append to the title. Default empty.
 */
function the_archive_title( $before = '', $after = '' ) {
	if ( is_category() ) {
		$title = sprintf( __( 'Category: %s', 'fifteen_txtd' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( __( 'Tag: %s', 'fifteen_txtd' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( __( 'Author: %s', 'fifteen_txtd' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( __( 'Year: %s', 'fifteen_txtd' ), get_the_date( _x( 'Y', 'yearly archives date format', 'fifteen_txtd' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( __( 'Month: %s', 'fifteen_txtd' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'fifteen_txtd' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( __( 'Day: %s', 'fifteen_txtd' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'fifteen_txtd' ) ) );
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			$title = _x( 'Asides', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			$title = _x( 'Galleries', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			$title = _x( 'Images', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			$title = _x( 'Videos', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			$title = _x( 'Quotes', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			$title = _x( 'Links', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			$title = _x( 'Statuses', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			$title = _x( 'Audio', 'post format archive title', 'fifteen_txtd' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			$title = _x( 'Chats', 'post format archive title', 'fifteen_txtd' );
		}
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( __( 'Archives: %s', 'fifteen_txtd' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
		$title = sprintf( __( '%1$s: %2$s', 'fifteen_txtd' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = __( 'Archives', 'fifteen_txtd' );
	}

	/**
	 * Filter the archive title.
	 *
	 * @param string $title Archive title to be displayed.
	 */
	$title = apply_filters( 'get_the_archive_title', $title );

	if ( ! empty( $title ) ) {
		echo $before . $title . $after;
	}
}
endif;

if ( ! function_exists( 'the_archive_description' ) ) :
/**
 * Shim for `the_archive_description()`.
 *
 * Display category, tag, or term description.
 *
 * @todo Remove this function when WordPress 4.3 is released.
 *
 * @param string $before Optional. Content to prepend to the description. Default empty.
 * @param string $after  Optional. Content to append to the description. Default empty.
 */
function the_archive_description( $before = '', $after = '' ) {
	$description = apply_filters( 'get_the_archive_description', term_description() );

	if ( ! empty( $description ) ) {
		/**
		 * Filter the archive description.
		 *
		 * @see term_description()
		 *
		 * @param string $description Archive description to be displayed.
		 */
		echo $before . $description . $after;
	}
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function fifteen_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'fifteen_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'fifteen_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so fifteen_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so fifteen_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in fifteen_categorized_blog.
 */
function fifteen_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'fifteen_categories' );
}
add_action( 'edit_category', 'fifteen_category_transient_flusher' );
add_action( 'save_post',     'fifteen_category_transient_flusher' );

/**
 * Display the classes for the post thumbail div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function fifteen_post_thumbnail_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for post thumbnail DIV
	echo 'class="' . join( ' ', fifteen_get_post_thumbnail_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'fifteen_get_post_thumbnail_class' ) ) :
	/**
	 * Retrieve the classes for the post_thumbnail,
	 * depending on the aspect ratio of the featured image
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function fifteen_get_post_thumbnail_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) )
			return $classes;

		$classes[] = 'entry-image';

		// .entry-image--[tall|portrait|square|landscape|wide] class depending on the aspect ratio
		// 16:9 = 1.78
		// 3:2 = 1.500
		// 4:3 = 1.34
		// 1:1 = 1.000
		// 3:4 = 0.750
		// 2:3 = 0.67
		// 9:16 = 0.5625

		//$image_data[1] is width
		//$image_data[2] is height
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), "full" );

		if ( ! empty( $image_data[1] ) && ! empty( $image_data[2] ) ) {
			$image_aspect_ratio = $image_data[1] / $image_data[2];

			//now let's begin to see what kind of featured image we have
			//first TALL ones; lower than 9:16
			if ( $image_aspect_ratio < 0.5625 ) {
				$classes[] = 'entry-image--tall';
			} elseif ( $image_aspect_ratio < 0.75 ) {
				//now PORTRAIT ones; lower than 3:4
				$classes[] = 'entry-image--portrait';
			} elseif ( $image_aspect_ratio > 1.78 ) {
				//now WIDE ones; higher than 16:9
				$classes[] = 'entry-image--wide';
			} elseif ( $image_aspect_ratio > 1.34 ) {
				//now LANDSCAPE ones; higher than 4:3
				$classes[] = 'entry-image--landscape';
			} else {
				//it's definitely a SQUARE-ish one; between 3:4 and 4:3
				$classes[] = 'entry-image--square';
			}
		}

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) )
				$class = preg_split( '#\s+#', $class );
			$classes = array_merge( $classes, $class );
		}

		$classes = array_map( 'esc_attr', $classes );

		/**
		 * Filter the list of CSS classes for the current post thumbnail.
		 *
		 * @param array  $classes An array of post classes.
		 * @param string $class   A comma-separated list of additional classes added to the post.
		 * @param int    $post_id The post ID.
		 */
		$classes = apply_filters( 'fifteen_post_thumbnail_class', $classes, $class, $post->ID );

		return array_unique( $classes );

	}
endif;

/**
 * Display the classes for the post title div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function fifteen_post_title_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for post title
	echo 'class="' . join( ' ', fifteen_get_post_title_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'fifteen_get_post_title_class' ) ) :
	/**
	 * Retrieve the classes for the post title,
	 * depending on the length of the title
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function fifteen_get_post_title_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) )
			return $classes;

		$classes[] = 'entry-title';

		// .entry-title--[short|medium|long] depending on the title length
		// 0-29 chars = short
		// 30-59 = medium
		// 60+ = long
		// @ todo Put in the needed logic for when mb functions are not present
		$title_length = mb_strlen( get_the_title( $post ) );

		if ( $title_length < 30 ) {
			$classes[] = 'entry-title--short';
		} elseif ( $title_length < 60 ) {
			$classes[] = 'entry-title--medium';
		} else {
			$classes[] = 'entry-title--long';
		}

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		/**
		 * Filter the list of CSS classes for the current post title.
		 *
		 * @param array  $classes An array of post classes.
		 * @param string $class   A comma-separated list of additional classes added to the post.
		 * @param int    $post_id The post ID.
		 */
		$classes = apply_filters( 'fifteen_post_title_class', $classes, $class, $post->ID );

		return array_unique( $classes );

	}
endif;

/**
 * Display the classes for the post excerpt div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function fifteen_post_excerpt_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for the post excerpt div
	echo 'class="' . join( ' ', fifteen_get_post_excerpt_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'fifteen_get_post_excerpt_class' ) ) :
	/**
	 * Retrieve the classes for the post excerpt,
	 * depending on the length of the excerpt
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function fifteen_get_post_excerpt_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) )
			return $classes;

		$classes[] = 'entry-excerpt';

		// .entry-title--[short|medium|long] depending on the title length
		// 0-99 chars = short
		// 100-199 = medium
		// 200+ = long
		// @ todo Put in the needed logic for when mb functions are not present
		$excerpt_length = mb_strlen( fifteen_get_post_excerpt( $post ) );

		if ( $excerpt_length < 99 ) {
			$classes[] = 'entry-excerpt--short';
		} elseif ( $excerpt_length < 199 ) {
			$classes[] = 'entry-excerpt--medium';
		} else {
			$classes[] = 'entry-excerpt--long';
		}

		if ( !empty($class) ) {
			if ( !is_array( $class ) )
				$class = preg_split('#\s+#', $class);
			$classes = array_merge($classes, $class);
		}

		$classes = array_map('esc_attr', $classes);

		/**
		 * Filter the list of CSS classes for the current post excerpt.
		 *
		 * @param array  $classes An array of post classes.
		 * @param string $class   A comma-separated list of additional classes added to the post.
		 * @param int    $post_id The post ID.
		 */
		$classes = apply_filters( 'fifteen_post_excerpt_class', $classes, $class, $post->ID );

		return array_unique( $classes );

	}
endif;

if ( ! function_exists( 'fifteen_post_excerpt' ) ) :

	function fifteen_post_excerpt( $post_id = null ) {
		$post = get_post( $post_id );

		if ( empty( $post ) )
			return '';

		// Check the content for the more text
		$has_more = strpos( $post->post_content, '<!--more' );

		if ( $has_more ) {
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'fifteen_txtd' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );
		} else {
			the_excerpt();
		}
	}
endif;