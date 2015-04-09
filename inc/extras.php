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
	global $wp_query;

	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ( is_single() || is_page() ) && is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'has_sidebar';
	}

	//add this class where we have the masonry layout
	if ( ! is_singular() ) {
		$classes[] = 'layout-grid';

		//add a.no-posts class when the loop is empty
		if ( ! $wp_query->posts ) {
			$classes[] = 'no-posts';
		}
	}

	if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) {
		$classes[] = 'infinite-scroll';
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

	if ( has_post_thumbnail() ) {
		//no need for the featured image aspect ratio class for quotes because we are not using it in a regular fashion
		if ( 'quote' != get_post_format() ) {
			if ( is_singular() ) {
				$classes[] = 'entry-image--' . patch_get_post_thumbnail_aspect_ratio_class();
			} else {
				$classes[] = 'entry-card--' . patch_get_post_thumbnail_aspect_ratio_class();
			}
		}
	} else {
		//handle other post formats
		$prefix = 'entry-card--';
		if ( is_singular() ) {
			$prefix = 'entry-image--';
		}

		switch ( get_post_format() ) {
			case 'image': $classes[] = $prefix . 'landscape';
				break;
			case 'gallery': $classes[] = $prefix . 'landscape';
				break;
			case 'video': ;
			case 'audio': $classes[] = $prefix . 'landscape';
				break;
			default: $classes[] = $prefix . 'text';
		}
	}

	return $classes;
}

add_filter( 'post_class', 'patch_post_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @since Patch 1.0
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
			$fonts[] = 'Roboto:500,400,300,500italic,400italic,300italic';
		}

		/* Translators: If there are characters in your language that are not
		* supported by Oswald, translate this to 'off'. Do not translate
		* into your own language.
		*/
		if ( 'off' !== _x( 'on', 'Oswald font: on or off', 'patch' ) ) {
			$fonts[] = 'Oswald:300,400,700';
		}

		/* translators: To add an additional character subset specific to your language, translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'patch' );

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
						<a href="<?php echo esc_url( get_comment_link( get_comment_ID() ) ) ?>" class="comment__timestamp"><?php printf( __( 'on %s at %s', 'patch' ), get_comment_date(), get_comment_time() ); ?> </a>
					</time>
					<div class="comment__links">
						<?php
						//we need some space before Edit
						edit_comment_link( __( 'Edit', 'patch' ) );

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
						<p><?php _e( 'Your comment is awaiting moderation.', 'patch' ) ?></p>
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
 * Filter comment_form_defaults to remove the notes after the comment form textarea.
 *
 * @since Patch 1.0
 *
 * @param array $defaults
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
					<span class="screen-reader-text">' . _x( 'Search for:', 'label' , 'patch' ) . '</span>
					<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder' , 'patch' ) . '" value="' . get_search_query() . '" name="s" title="' . esc_attr_x( 'Search for:', 'label' , 'patch' ) . '" />
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
 * When dealing with gallery post format, we need to strip the first gallery in the content since we show it at the top
 */
function patch_strip_first_content_gallery( $content ) {
	if ( 'gallery' == get_post_format() ) {
		$regex   = '/\[gallery.*]/';
		$content = preg_replace( $regex, '', $content, 1 );
	}

	return $content;
}

add_filter( 'the_content', 'patch_strip_first_content_gallery' );

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
		array( 'title' => __( 'Intro Text', 'patch' ), 'selector' => 'p', 'classes' => 'intro' ),
		array( 'title' => __( 'Dropcap', 'patch' ), 'inline' => 'span', 'classes' => 'dropcap' ),
		array( 'title' => __( 'Highlight', 'patch' ), 'inline' => 'span', 'classes' => 'highlight' ),
		array( 'title' => __( 'Pull Left', 'patch' ), 'inline' => 'p', 'classes' => 'pull-left' ),
		array( 'title' => __( 'Pull Right', 'patch' ), 'inline' => 'p', 'classes' => 'pull-right' ),
		array( 'title' => __( 'Two Columns', 'patch' ), 'selector' => 'p', 'classes' => 'twocolumn', 'wrapper' => true ),
	);

	$settings['style_formats'] = json_encode( $style_formats );

	return $settings;
} #function

add_filter( 'tiny_mce_before_init', 'patch_mce_before_init' );

/*
 * Due to the fact that we need a wrapper for center aligned images and for the ones with alignnone, we need to wrap the images without a caption
 * The images with captions already are wrapped by the figure tag
 */
function patch_wrap_images_in_figure( $content ) {
	$classes = array( 'aligncenter', 'alignnone' );

	foreach ( $classes as $class ) {

		//this regex basically tells this
		//match all the images that are not in captions and that have the X class
		//when an image is wrapped by an anchor tag, match that too
		$regex = '~\[caption[^\]]*\].*\[\/caption\]|((?:<a[^>]*>\s*)?<img.*class="[^"]*' . $class . '[^"]*[^>]*>(?:\s*<\/a>)?)~i';

		$callback = new PatchWrapImagesInFigureCallback( $class );

		// Replace the matches
		$content = preg_replace_callback(
			$regex,
			// in the callback function, if Group 1 is empty,
			// set the replacement to the whole match,
			// i.e. don't replace
			array( $callback, 'callback' ),
			$content );
	}

	return $content;
}

add_filter( 'the_content', 'patch_wrap_images_in_figure' );

//We need to use a class so we can pass the $class variable to the callback function
class PatchWrapImagesInFigureCallback {
	private $class;

	function __construct( $class ) {
		$this->class = $class;
	}

	public function callback( $match ) {
		if ( empty( $match[1] ) ) {
			return $match[0];
		}

		return '<span class="' . $this->class . '">' . $match[1] . '</span>';
	}
}

/**
 * Add a search link to the Social Menu
 *
 * @param string $items The HTML list content for the menu items.
 * @param object $args  An object containing wp_nav_menu() arguments.
 *
 * @return string
 */
function patch_add_search_to_nav( $items, $args )
{
	if( $args->theme_location == 'social' && ( ! get_theme_mod( 'patch_disable_search_in_social_menu', false ) ) ) {
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#search">' . __( 'Search', 'patch' ) . '</a></li>';
	}
	return $items;
}

add_filter( 'wp_nav_menu_items', 'patch_add_search_to_nav', 10, 2 ); ?>
