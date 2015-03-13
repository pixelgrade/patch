<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Patch
 * @since Patch 1.0
 */

if ( ! function_exists( 'patch_posted_on' ) ) :

	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function patch_posted_on() {

		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s<span class="entry-time">%3$s</span></time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s<span class="entry-time">%3$s</span></time><time class="updated" hidden datetime="%4$s">%5$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_html( get_the_time() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';

		$byline = sprintf(
			_x( 'by %s', 'post author', 'patch_txtd' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . $posted_on . '</span>';

	} #function

endif;

if ( ! function_exists( 'patch_get_cats_list' ) ) :

	/**
	 * Returns HTML with comma separated category links
	 */
	function patch_get_cats_list( $post_ID = null) {

		//use the current post ID is none given
		if ( empty( $post_ID ) ) {
			$post_ID = get_the_ID();
		}

		$cats = '';
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'patch_txtd' ), '', $post_ID );
		if ( $categories_list && patch_categorized_blog() ) {
			$cats = '<span class="cat-links">' . $categories_list . '</span>';
		}

		return $cats;

	} #function

endif;

if ( ! function_exists( 'patch_cats_list' ) ) :

	/**
	 * Prints HTML with comma separated category links
	 */
	function patch_cats_list( $post_ID = null) {

		echo patch_get_cats_list( $post_ID );

	} #function

endif;

/**
 * Prints HTML with the category of a certain post, with the most posts in it
 * The most important category of a post
 */
function patch_first_category( $post_ID = null) {
	global $wp_rewrite;

	//use the current post ID is none given
	if ( empty( $post_ID ) ) {
		$post_ID = get_the_ID();
	}

	//first get all categories ordered by count
	$all_categories = get_categories( array(
		'orderby' => 'count',
		'order' => 'DESC',
	) );

	//get the post's categories
	$categories = get_the_category( $post_ID );
	if ( empty( $categories ) ) {
		//get the default category instead
		$categories = get_the_category_by_ID( get_option( 'default_category' ) );
	}

	//now intersect them so that we are left with e descending ordered array of the post's categories
	$categories = array_uintersect( $all_categories, $categories, function ($a1, $a2) {
		if ( $a1->term_id == $a2->term_id ) { return 0; } //we are only interested by equality but PHP wants the whole thing
		if ( $a1->term_id > $a2->term_id ) { return 1; }
		return -1; } );

	if ( ! empty ( $categories ) ) {
		$category = array_shift($categories);
		$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

		echo '<span class="cat-links"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '" ' . $rel . '>' . $category->name . '</a></span>';
	}

} #function

if ( ! function_exists( 'patch_entry_footer' ) ) :

	/**
	 * Prints HTML with meta information for posts on archives.
	 */
	function patch_entry_footer() {
		edit_post_link( __( 'Edit', 'patch_txtd' ), '<span class="edit-link">', '</span>' );
	}

endif;

if ( ! function_exists( 'patch_single_entry_footer' ) ) :

	/**
	 * Prints HTML with meta information for the categories, tags, Jetpack likes, shares, related, and comments.
	 */
	function patch_single_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' == get_post_type() ) {

			$tags_list = get_the_tag_list( '', ' ' );
			if ( $tags_list ) {
				/* translators: There is a space at the end */
				echo '<span class="screen-reader-text">' . __( 'Tagged with: ', 'patch_txtd' ) . '</span><span class="tags-links">' . $tags_list . '</span>';
			}

			// Jetpack share buttons.
			if ( function_exists( 'sharing_display' ) ) {
				sharing_display( '', true );
			}

			// Jetpack Likes.
			if ( class_exists( 'Jetpack_Likes' ) ) {
				$custom_likes = new Jetpack_Likes;
				echo $custom_likes->post_likes( '' );
			}

			// Author bio.
			if ( ! get_theme_mod( 'patch_hide_author_bio', false ) && get_the_author_meta( 'description' ) ) {
				get_template_part( 'author-bio' );
			}

			//Jetpack Related Posts
			if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
				echo do_shortcode( '[jetpack-related-posts]' );
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( __( 'Leave a comment', 'patch_txtd' ), __( '1 Comment', 'patch_txtd' ), __( '% Comments', 'patch_txtd' ) );
			echo '</span>';
		}

		edit_post_link( __( 'Edit', 'patch_txtd' ), '<span class="edit-link">', '</span>' );
	} #function

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
			$title = sprintf( __( 'Category: %s', 'patch_txtd' ), single_cat_title( '', false ) );
		} elseif ( is_tag() ) {
			$title = sprintf( __( 'Tag: %s', 'patch_txtd' ), single_tag_title( '', false ) );
		} elseif ( is_author() ) {
			$title = sprintf( __( 'Author: %s', 'patch_txtd' ), '<span class="vcard">' . get_the_author() . '</span>' );
		} elseif ( is_year() ) {
			$title = sprintf( __( 'Year: %s', 'patch_txtd' ), get_the_date( _x( 'Y', 'yearly archives date format', 'patch_txtd' ) ) );
		} elseif ( is_month() ) {
			$title = sprintf( __( 'Month: %s', 'patch_txtd' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'patch_txtd' ) ) );
		} elseif ( is_day() ) {
			$title = sprintf( __( 'Day: %s', 'patch_txtd' ), get_the_date( _x( 'F j, Y', 'daily archives date format', 'patch_txtd' ) ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Asides', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'patch_txtd' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'patch_txtd' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = sprintf( __( 'Archives: %s', 'patch_txtd' ), post_type_archive_title( '', false ) );
		} elseif ( is_tax() ) {
			$tax = get_taxonomy( get_queried_object()->taxonomy );
			/* translators: 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf( __( '%1$s: %2$s', 'patch_txtd' ), $tax->labels->singular_name, single_term_title( '', false ) );
		} else {
			$title = __( 'Archives', 'patch_txtd' );
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
	} #function

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
	} #function

endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function patch_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'patch_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'patch_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so patch_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so patch_categorized_blog should return false.
		return false;
	}
} #function

/**
 * Flush out the transients used in patch_categorized_blog.
 */
function patch_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'patch_categories' );
}
add_action( 'edit_category', 'patch_category_transient_flusher' );
add_action( 'save_post',     'patch_category_transient_flusher' );

/**
 * Display the classes for the post thumbail div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function patch_post_thumbnail_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for post thumbnail DIV
	echo 'class="' . join( ' ', patch_get_post_thumbnail_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'patch_get_post_thumbnail_class' ) ) :

	/**
	 * Retrieve the classes for the post_thumbnail,
	 * depending on the aspect ratio of the featured image
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 * @return array Array of classes.
	 */
	function patch_get_post_thumbnail_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) ) {
			return $classes;
		}

		//get the aspect ratio specific class
		$classes[] = patch_get_post_thumbnail_aspect_ratio_class( $post );

		if ( ! empty( $class ) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}

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
		$classes = apply_filters( 'patch_post_thumbnail_class', $classes, $class, $post->ID );

		return array_unique( $classes );
	} #function

endif;

if ( ! function_exists( 'patch_get_post_thumbnail_aspect_ratio_class' ) ) :

	/**
	 * Get the aspect ratio of the featured image
	 *
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 * @return string Aspect ratio specific class.
	 */
	function patch_get_post_thumbnail_aspect_ratio_class( $post_id = null ) {

		$post = get_post( $post_id );

		$class = '';

		if ( empty( $post ) ) {
			return $class;
		}

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
		$image_data = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), "full" );

		if ( ! empty( $image_data[1] ) && ! empty( $image_data[2] ) ) {
			$image_aspect_ratio = $image_data[1] / $image_data[2];

			//now let's begin to see what kind of featured image we have
			//first TALL ones; lower than 9:16
			if ( $image_aspect_ratio < 0.5625 ) {
				$class = 'entry-image--tall';
			} elseif ( $image_aspect_ratio < 0.75 ) {
				//now PORTRAIT ones; lower than 3:4
				$class = 'entry-image--portrait';
			} elseif ( $image_aspect_ratio > 1.78 ) {
				//now WIDE ones; higher than 16:9
				$class = 'entry-image--wide';
			} elseif ( $image_aspect_ratio > 1.34 ) {
				//now LANDSCAPE ones; higher than 4:3
				$class = 'entry-image--landscape';
			} else {
				//it's definitely a SQUARE-ish one; between 3:4 and 4:3
				$class = 'entry-image--square';
			}
		}

		return $class;
	} #function

endif;

/**
 * Display the classes for the post title div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function patch_post_title_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for post title
	echo 'class="' . join( ' ', patch_get_post_title_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'patch_get_post_title_class' ) ) :

	/**
	 * Retrieve the classes for the post title,
	 * depending on the length of the title
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function patch_get_post_title_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) ) {
			return $classes;
		}

		$classes[] = 'entry-header';

		// .entry-header--[short|medium|long] depending on the title length
		// 0-29 chars = short
		// 30-59 = medium
		// 60+ = long
		// @ todo Put in the needed logic for when mb functions are not present
		$title_length = mb_strlen( get_the_title( $post ) );

		if ( $title_length < 30 ) {
			$classes[] = 'entry-header--short';
		} elseif ( $title_length < 60 ) {
			$classes[] = 'entry-header--medium';
		} else {
			$classes[] = 'entry-header--long';
		}

		if ( !empty($class) ) {
			if ( ! is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}

			$classes = array_merge( $classes, $class );
		}

		$classes = array_map( 'esc_attr', $classes );

		/**
		 * Filter the list of CSS classes for the current post title.
		 *
		 * @param array  $classes An array of post classes.
		 * @param string $class   A comma-separated list of additional classes added to the post.
		 * @param int    $post_id The post ID.
		 */
		$classes = apply_filters( 'patch_post_title_class', $classes, $class, $post->ID );

		return array_unique( $classes );
	} #function

endif;

/**
 * Display the classes for the post excerpt div.
 *
 * @param string|array $class One or more classes to add to the class list.
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function patch_post_excerpt_class( $class = '', $post_id = null ) {
	// Separates classes with a single space, collates classes for the post excerpt div
	echo 'class="' . join( ' ', patch_get_post_excerpt_class( $class, $post_id ) ) . '"';
}

if ( ! function_exists( 'patch_get_post_excerpt_class' ) ) :

	/**
	 * Retrieve the classes for the post excerpt,
	 * depending on the length of the excerpt
	 *
	 * @param string|array $class One or more classes to add to the class list.
	 * @return array Array of classes.
	 */
	function patch_get_post_excerpt_class( $class = '', $post_id = null ) {

		$post = get_post( $post_id );

		$classes = array();

		if ( empty( $post ) ) {
			return $classes;
		}

		$classes[] = 'entry-content';

		// .entry-title--[short|medium|long] depending on the title length
		// 0-99 chars = short
		// 100-199 = medium
		// 200+ = long
		// @ todo Put in the needed logic for when mb functions are not present
		$excerpt_length = mb_strlen( patch_get_post_excerpt( $post ) );

		if ( $excerpt_length < 99 ) {
			$classes[] = 'entry-content--short';
		} elseif ( $excerpt_length < 199 ) {
			$classes[] = 'entry-content--medium';
		} else {
			$classes[] = 'entry-content--long';
		}

		if ( !empty($class) ) {
			if ( !is_array( $class ) ) {
				$class = preg_split( '#\s+#', $class );
			}

			$classes = array_merge( $classes, $class );
		}

		$classes = array_map( 'esc_attr', $classes );

		/**
		 * Filter the list of CSS classes for the current post excerpt.
		 *
		 * @param array  $classes An array of post classes.
		 * @param string $class   A comma-separated list of additional classes added to the post.
		 * @param int    $post_id The post ID.
		 */
		$classes = apply_filters( 'patch_post_excerpt_class', $classes, $class, $post->ID );

		return array_unique( $classes );
	} #function

endif;

if ( ! function_exists( 'patch_post_excerpt' ) ) :
	/**
	 * Display the post excerpt, either with the <!--more--> tag or regular excerpt
	 */
	function patch_post_excerpt( $post_id = null ) {
		$post = get_post( $post_id );

		if ( empty( $post ) ) {
			return '';
		}

		// Check the content for the more text
		$has_more = strpos( $post->post_content, '<!--more' );

		if ( $has_more ) {
			/* translators: %s: Name of current post */
			the_content( sprintf(
				__( 'Continue reading %s', 'patch_txtd' ),
				the_title( '<span class="screen-reader-text">', '</span>', false )
			) );
		} else {
			the_excerpt();
		}
	} #function
endif;

/**
 * Get the post excerpt, either with the <!--more--> tag or regular excerpt
 *
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 * @return string The post excerpt.
 */
function patch_get_post_excerpt( $post_id = null ) {
	$post = get_post( $post_id );

	$excerpt = '';

	if ( empty( $post ) ) {
		return $excerpt;
	}

	// Check the content for the more text
	$has_more = strpos( $post->post_content, '<!--more' );

	if ( $has_more ) {
		/* translators: %s: Name of current post */
		$excerpt = get_the_content( sprintf(
			__( 'Continue reading %s', 'patch_txtd' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		) );
	} else {
		$excerpt = get_the_excerpt();
	}

	return $excerpt;
} #function

/**
 * Display the markup for the author bio links.
 * These are the links/websites added by one to it's Gravatar profile
 *
 * @param int|WP_Post $post_id Optional. Post ID or post object.
 */
function patch_author_bio_links( $post_id = null ) {
	echo patch_get_author_bio_links( $post_id );
}

if ( ! function_exists( 'patch_get_author_bio_links' ) ) :

	/**
	 * Return the markup for the author bio links.
	 * These are the links/websites added by one to it's Gravatar profile
	 *
	 * @param int|WP_Post $post_id Optional. Post ID or post object.
	 * @return string The HTML markup of the author bio links list.
	 */
	function patch_get_author_bio_links( $post_id = null ) {
		$post = get_post( $post_id );

		$markup = '';

		if ( empty( $post ) ) {
			return $markup;
		}

		$str = file_get_contents( 'https://www.gravatar.com/' . md5( strtolower( trim( get_the_author_meta( 'user_email' ) ) ) ) . '.php' );

		$profile = unserialize( $str );

		if ( is_array( $profile ) && ! empty( $profile['entry'][0]['urls'] ) ) {
			$markup .= '<ul class="author__social-links">' . PHP_EOL;

			foreach ( $profile['entry'][0]['urls'] as $link ) {
				if ( !empty( $link['value'] ) && ! empty( $link['title'] ) ) {
					$markup .= '<li class="author__social-links__list-item">' . PHP_EOL;
					$markup .= '<a class="author__social-link" href="' . $link['value'] . '" target="_blank">' . $link['title'] . '</a>' . PHP_EOL;
					$markup .= '</li>' . PHP_EOL;
				}
			}

			$markup .= '</ul>' . PHP_EOL;
		}

		return $markup;
	} #function

endif;

if ( ! function_exists( 'patch_secondary_page_title' ) ) :

	/**
	 * Display the markup for the archive or search pages title.
	 */
	function patch_the_secondary_page_title() {

		if ( is_archive() ) : ?>

			<header class="page-header grid__item entry-card">

				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>

				<?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?>

			</header><!-- .page-header -->

		<?php elseif ( is_search() ) : ?>

			<header class="page-header grid__item entry-card">
				<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'patch_txtd' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

		<?php endif;
	} #function

endif;

if ( ! function_exists( 'patch_the_image_navigation' ) ) :

	/**
	 * Display navigation to next/previous image attachment
	 */
	function patch_the_image_navigation() {
		// Don't print empty markup if there's nowhere to navigate.
		$prev_image = patch_get_adjacent_image();
		$next_image = patch_get_adjacent_image( false );

		if ( ! $next_image && ! $prev_image ) {
			return;
		} ?>

		<nav class="navigation post-navigation" role="navigation">
			<h5 class="screen-reader-text"><?php _e( 'Image navigation', 'patch_txtd' ); ?></h5>
			<div class="article-navigation">
				<?php
				if ( $prev_image ) {
					$prev_thumbnail = wp_get_attachment_image( $prev_image->ID, 'patch-tiny-image' ); ?>

					<span class="navigation-item  navigation-item--previous">
						<a href="<?php echo get_attachment_link( $prev_image->ID ); ?>" rel="prev">
							<span class="arrow"></span>
		                    <span class="navigation-item__content">
		                        <span class="navigation-item__wrapper  flexbox">
		                            <span class="flexbox__item">
		                                <span class="post-thumb"><?php echo $prev_thumbnail; ?></span>
		                            </span>
		                            <span class="flexbox__item">
		                                <span class="navigation-item__name"><?php _e( 'Previous image', 'patch_txtd' ); ?></span>
		                                <h3 class="post-title"><?php echo get_the_title( $prev_image->ID ); ?></h3>
		                            </span>
		                        </span>
		                    </span>
						</a>
					</span>

				<?php }

				if ( $next_image ) {
					$next_thumbnail = wp_get_attachment_image( $next_image->ID, 'patch-tiny-image' ); ?>

					<span class="navigation-item  navigation-item--next">
						<a href="<?php echo get_attachment_link( $next_image->ID ); ?>" rel="prev">
							<span class="arrow"></span>
		                    <span class="navigation-item__content">
		                        <span class="navigation-item__wrapper  flexbox">
		                            <span class="flexbox__item">
		                                <span class="post-thumb"><?php echo $next_thumbnail; ?></span>
		                            </span>
		                            <span class="flexbox__item">
		                                <span class="navigation-item__name"><?php _e( 'Next image', 'patch_txtd' ); ?></span>
		                                <h3 class="post-title"><?php echo get_the_title( $next_image->ID ); ?></h3>
		                            </span>
		                        </span>
		                    </span>
						</a>
					</span>

				<?php } ?>

		</nav><!-- .navigation -->

	<?php
	} #function

endif;

if ( ! function_exists( 'patch_get_adjacent_image' ) ) :

	/**
	 * Inspired by the core function adjacent_image_link() from wp-includes/media.php
	 *
	 * @param bool $prev Optional. Default is true to display previous link, false for next.
	 * @return mixed  Attachment object if successful. Null if global $post is not set. false if no corresponding attachment exists.
	 */
	function patch_get_adjacent_image( $prev = true ) {
		if ( ! $post = get_post() ) {
			return null;
		}

		$attachments = get_attached_media( 'image', $post->post_parent );

		foreach ( $attachments as $k => $attachment ) {
			if ( $attachment->ID == $post->ID ) {
				break;
			}
		}

		if ( $attachments ) {
			$k = $prev ? $k - 1 : $k + 1;

			if ( isset( $attachments[ $k ] ) ) {
				return $attachments[ $k ];
			}
		}

		return false;
	} #function

endif;

if ( ! function_exists( 'patch_get_post_format_first_image' ) ) :

	function patch_get_post_format_first_image() {
		global $post;

		$output = '';
		$pattern = get_shortcode_regex();

		//first search for an image with a caption shortcode
		if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
		       && array_key_exists( 2, $matches )
		       && in_array( 'caption', $matches[2] ) ) {

			$key = array_search( 'caption', $matches[2] );
			if ( false !== $key ) {
				$output = do_shortcode( $matches[0][ $key ] );
			}
		} else {
			//find regular images
			preg_match( '/<img [^\>]*\ \/>/i', $post->post_content, $matches );

			if ( ! empty( $matches[0] ) ) {
				$output = $matches[0];
			}
		}

		return $output;
	} #function

endif;

if ( ! function_exists( 'patch_get_post_format_link_url' ) ) :

	/**
	 * Returns the URL to use for the link post format.
	 *
	 * First it tries to get the first URL in the content; if not found it uses the permalink instead
	 *
	 * @return string URL
	 */
	function patch_get_post_format_link_url() {
		$has_url = get_url_in_content( get_the_content() );

		return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
	}

endif;

if ( ! function_exists( 'patch_paging_nav' ) ) :

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function patch_paging_nav() {
		global $wp_query, $wp_rewrite;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%'; ?>

		<nav class="pagination" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'patch_txtd' ); ?></h1>

			<div class="nav-links">

				<?php
				//output a disabled previous "link" if on the fist page
				if ( 1 == $paged ) {
					echo '<span class="prev page-numbers disabled">' . __( 'Previous', 'patch_txtd' ) . '</span>';
				}

				//output the numbered page links
				echo paginate_links( array(
					'base'      => $pagenum_link,
					'format'    => $format,
					'total'     => $wp_query->max_num_pages,
					'current'   => $paged,
					'prev_next' => true,
					'prev_text' => __( 'Previous', 'patch_txtd' ),
					'next_text' => __( 'Next', 'patch_txtd' ),
					'add_args'  => array_map( 'urlencode', $query_args ),
				) );

				//output a disabled next "link" if on the last page
				if ( $paged == $wp_query->max_num_pages ) {
					echo '<span class="next page-numbers disabled">' . __( 'Next', 'patch_txtd' ) . '</span>';
				} ?>

			</div><!-- .nav-links -->

		</nav><!-- .navigation -->
	<?php
	} #function

endif; ?>