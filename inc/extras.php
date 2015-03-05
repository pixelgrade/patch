<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Patch
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function patch_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ( is_single() || is_page() ) && is_active_sidebar( 'sidebar-1' ) ) {
		$classes[ ] = 'has_sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'patch_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * @since Patch 1.0
 *
 * @param array $classes A list of existing post class values.
 *
 * @return array The filtered post class list.
 */
function patch_post_classes( $classes ) {
	$post_format = get_post_format();

	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'grid__item';
	}

	return $classes;
}

add_filter( 'post_class', 'patch_post_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function patch_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'patch_txtd' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'patch_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function patch_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'patch_render_title' );
endif;

if ( ! function_exists( 'patch_fonts_url' ) ) :
	/**
	 * Register Google fonts for Patch.
	 * @since Patch 1.0
	 *
	 * @return string Google fonts URL for the theme.
	 */
	function patch_fonts_url() {
		$fonts_url = '';
		$fonts     = array();
		$subsets   = 'latin,latin-ext';

		/* Translators: If there are characters in your language that are not
		* supported by Roboto, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'fifteen_txtd' ) ) {
			$fonts[] = 'Roboto:400,700';
		}

		/* Translators: If there are characters in your language that are not
		* supported by Oswald, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'Oswald font: on or off', 'patch_txtd' ) ) {
			$fonts[] = 'Oswald:300,400,700';
		}

		/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'patch_txtd' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic,cyrillic-ext';
		} elseif ( 'greek' == $subset ) {
			$subsets .= ',greek,greek-ext';
		} elseif ( 'devanagari' == $subset ) {
			$subsets .= ',devanagari';
		} elseif ( 'vietnamese' == $subset ) {
			$subsets .= ',vietnamese';
		}

		if ( $fonts ) {
			$fonts_url = add_query_arg( array(
				'family' => urlencode( implode( '|', $fonts ) ),
				'subset' => urlencode( $subsets ),
			), '//fonts.googleapis.com/css' );
		}

		return $fonts_url;
	}
endif;

/**
 * Sets the authordata global when viewing an author archive.
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function patch_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'patch_setup_author' );

if ( ! function_exists( 'patch_comment' ) ) :
	/*
	 * Individual comment layout
	 */
	function patch_comment( $comment, $args, $depth ) {
		static $comment_number;

		if ( ! isset( $comment_number ) ) {
			$comment_number = $args['per_page'] * ( $args['page'] - 1 ) + 1;
		} else {
			$comment_number ++;
		}

		$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="comment-<?php comment_ID() ?>" class="comment-article  media">
			<span class="comment-number"><?php echo $comment_number ?></span>
			<?php
			//grab the avatar - by default the Mystery Man
			$avatar = get_avatar( $comment ); ?>

			<aside class="comment__avatar  media__img"><?php echo $avatar; ?></aside>

			<div class="media__body">
				<header class="comment__meta comment-author">
					<?php printf( '<span class="comment__author-name">%s</span>', get_comment_author_link() ) ?>
					<time class="comment__time" datetime="<?php comment_time( 'c' ); ?>">
						<a href="<?php echo esc_url( get_comment_link( get_comment_ID() ) ) ?>" class="comment__timestamp"><?php printf( __( 'on %s at %s', 'patch_txtd' ), get_comment_date(), get_comment_time() ); ?> </a>
					</time>
					<div class="comment__links">
						<?php
						//we need some space before Edit
						edit_comment_link( __( 'Edit', 'patch_txtd' ), '  ' );

						comment_reply_link( array_merge( $args, array(
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
						) ) );
						?>
					</div>
				</header>
				<!-- .comment-meta -->
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<div class="alert info">
						<p><?php _e( 'Your comment is awaiting moderation.', 'patch_txtd' ) ?></p>
					</div>
				<?php endif; ?>
				<section class="comment__content comment">
					<?php comment_text() ?>
				</section>
			</div>
		</article>
		<!-- </li> is added by WordPress automatically -->
	<?php
	} // don't remove this bracket!
endif; //patch_comment

/**
 * Filter comment_form_defaults to remove the notes after the comment form textarea.
 *
 * @param array $defaults
 *
 * @return array
 */
function patch_comment_form_remove_notes_after( $defaults ) {
	$defaults['comment_notes_after'] = '';

	return $defaults;
}

add_filter( 'comment_form_defaults', 'patch_comment_form_remove_notes_after' );

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @param string $link
 *
 * @return string
 */
function patch_link_pages( $link ) {
	if ( is_numeric( $link ) ) {
		return '<span class="current">' . $link . '</span>';
	}

	return $link;
}

add_filter( 'wp_link_pages_link', 'patch_link_pages' );

/**
 * Wrap more link
 */
function patch_read_more_link( $link ) {
	return '<div class="more-link-wrapper">' . $link . '</div>';
}

add_filter( 'the_content_more_link', 'patch_read_more_link' );

/**
 * Constrain the excerpt length
 */
function patch_excerpt_length( $length ) {
	return 18;
}

add_filter( 'excerpt_length', 'patch_excerpt_length', 999 );

/**
 * Add "Styles" drop-down
 */
add_filter( 'mce_buttons_2', 'patch_mce_editor_buttons' );
function patch_mce_editor_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter( 'tiny_mce_before_init', 'patch_mce_before_init' );
function patch_mce_before_init( $settings ) {

	$style_formats = array(
		array( 'title' => __( 'Intro Text', 'patch_txtd' ), 'selector' => 'p', 'classes' => 'intro' ),
		array( 'title' => __( 'Dropcap', 'patch_txtd' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => __( 'Highlight', 'patch_txtd' ), 'inline' => 'span', 'classes' => 'highlight' ),
		array( 'title' => __( 'Two Columns', 'patch_txtd' ), 'selector' => 'p', 'classes' => 'twocolumn', 'wrapper' => true ),
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
}

function patch_get_post_excerpt( $post_id = null ) {
	$post = get_post( $post_id );

	$excerpt = '';

	if ( empty( $post ) )
		return $excerpt;

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
} ?>