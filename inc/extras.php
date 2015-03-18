<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Patch
 * @since Patch 1.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Patch 1.0
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

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
		$classes[ ] = 'has_infinite-scroll';
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
 * @return array The filtered post class list.
 */
function patch_post_classes( $classes ) {

	if ( is_archive() || is_home() || is_search() ) {
		$classes[] = 'entry-card  js-masonry-item';
	}

	if ( is_single() && has_post_thumbnail() ) {
		$classes[] = patch_get_post_thumbnail_aspect_ratio_class();
	}

	return $classes;
}

add_filter( 'post_class', 'patch_post_classes' );

if ( ! function_exists( 'patch_fonts_url' ) ) :

	/**
	 * Register Google fonts for Patch.
	 *
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
			$fonts[] = 'Roboto:500,400,300,500italic,400italic,300italic:latin';
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
	} #function

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

	/**
	 * Display individual comment layout
	 *
	 * @since Patch 1.0
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
	} #function

endif;

/**
 * Filter wp_link_pages to wrap current page in span.
 *
 * @since Patch 1.0
 *
 * @param string $link
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
 * Constrain the excerpt length to 35 words - about a medium sized excerpt
 */
function patch_excerpt_length( $length ) {
	return 35;
}

add_filter( 'excerpt_length', 'patch_excerpt_length', 999 );

/**
 * Replace the submit input with button because the <input> tag doesn't allow CSS styling with ::before or ::after
 */
function patch_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<label>
					<span class="screen-reader-text">' . _x( 'Search for:', 'label' ) . '</span>
					<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' ) . '" />
				</label>
				<button class="search-submit"><i class="fa fa-search"></i></button>
			</form>';

	return $form;
}

add_filter( 'get_search_form', 'patch_search_form' );

/**
 * Check the content blob for an audio, video, object, embed, or iframe tags.
 * This is a modified version of the current core one, in line with this
 * https://core.trac.wordpress.org/ticket/26675
 * This should end up in the core in version 4.2 or 4.3 hopefully
 *
 * @param string $content A string which might contain media data.
 * @param array $types array of media types: 'audio', 'video', 'object', 'embed', or 'iframe'
 * @return array A list of found HTML media embeds
 */
// @todo Remove this when the right get_media_embedded_in_content() ends up in the core, v4.2 hopefully
function patch_get_media_embedded_in_content( $content, $types = null ) {
	$html = array();

	$allowed_media_types = apply_filters( 'get_media_embedded_in_content_allowed', array( 'audio', 'video', 'object', 'embed', 'iframe' ) );

	if ( ! empty( $types ) ) {
		if ( ! is_array( $types ) ) {
			$types = array( $types );
		}

		$allowed_media_types = array_intersect( $allowed_media_types, $types );
	}

	$tags = implode( '|', $allowed_media_types );

	if ( preg_match_all( '#<(?P<tag>' . $tags . ')[^<]*?(?:>[\s\S]*?<\/(?P=tag)>|\s*\/>)#', $content, $matches ) ) {
		foreach ( $matches[0] as $match ) {
			$html[] = $match;
		}
	}

	return $html;
}

/**
 * Add "Styles" drop-down
 */
function patch_mce_editor_buttons( $buttons ) {
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}

add_filter( 'mce_buttons_2', 'patch_mce_editor_buttons' );

/**
 * Add styles/classes to the "Styles" drop-down
 */
function patch_mce_before_init( $settings ) {

	$style_formats = array(
		array( 'title' => __( 'Intro Text', 'patch_txtd' ), 'selector' => 'p', 'classes' => 'intro' ),
		array( 'title' => __( 'Dropcap', 'patch_txtd' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => __( 'Highlight', 'patch_txtd' ), 'inline' => 'span', 'classes' => 'highlight' ),
		array( 'title' => __( 'Pull Left', 'patch_txtd' ), 'inline' => 'p', 'classes' => 'pull-left' ),
		array( 'title' => __( 'Pull Right', 'patch_txtd' ), 'inline' => 'p', 'classes' => 'pull-right' ),
		array( 'title' => __( 'Two Columns', 'patch_txtd' ), 'selector' => 'p', 'classes' => 'twocolumn', 'wrapper' => true ),
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
} #function

add_filter( 'tiny_mce_before_init', 'patch_mce_before_init' ); ?>