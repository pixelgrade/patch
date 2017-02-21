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
			$title .= " $sep " . sprintf( __( 'Page %s', 'patch' ), max( $paged, $page ) );
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
		if ( 'off' !== _x( 'on', 'Roboto font: on or off', 'patch' ) ) {
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
		array( 'title' => __( 'Pull Left', 'patch' ), 'selector' => 'p', 'classes' => 'pull-left', 'wrapper' => true ),
		array( 'title' => __( 'Pull Right', 'patch' ), 'selector' => 'p', 'classes' => 'pull-right', 'wrapper' => true ),
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
function patch_add_search_to_nav( $items, $args ) {
	if( $args->theme_location == 'social' && ( ! pixelgrade_option( 'patch_disable_search_in_social_menu', false ) ) ) {
		$items .= '<li class="menu-item menu-item-type-custom menu-item-object-custom"><a href="#search">' . __( 'Search', 'patch' ) . '</a></li>';
	}
	return $items;
}

add_filter( 'wp_nav_menu_items', 'patch_add_search_to_nav', 10, 2 );

function patch_allow_skype_protocol( $protocols ) {
	$protocols[] = 'skype';
	return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'patch_allow_skype_protocol' );

/**
 * This function was borrowed from CakePHP and adapted.
 * https://github.com/cakephp/cakephp/blob/53fdb18655119d4cca966d769b6c33f8eaaa8da0/src/Utility/Text.php
 *
 * Bellow is the copyright notice:
 *
 * ========================
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 *
 * ========================
 *
 * Truncates text.
 *
 * Cuts a string to the length of $length and replaces the last characters
 * with the ellipsis if the text is longer than length.
 *
 * ### Options:
 *
 * - `ellipsis` Will be used as ending and appended to the trimmed string
 * - `exact` If false, $text will not be cut mid-word
 * - `html` If true, HTML tags would be handled correctly
 *
 * @param string $text String to truncate.
 * @param int $length Length of returned string, including ellipsis.
 * @param array $options An array of HTML attributes and options.
 * @return string Trimmed string.
 * @link http://book.cakephp.org/3.0/en/core-libraries/string.html#truncating-text
 */
function patch_truncate($text, $length = 100, $options = array() ) {
	$default = array(
		'ellipsis' => apply_filters('excerpt_more', '[…]' ),
		'exact' => false,
		'html' => false,
	);

	if ( ! empty( $options['html'] ) && strtolower( mb_internal_encoding() ) === 'utf-8') {
		$default['ellipsis'] = "\xe2\x80\xa6";
	}
	$options = array_merge( $default, $options );
	extract($options);

	if ( true === $html ) {
		if ( mb_strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length) {
			return $text;
		}
		$totalLength = mb_strlen( strip_tags( $ellipsis ) );
		$openTags = array();
		$truncate = '';
		preg_match_all( '/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER );
		foreach ( $tags as $tag ) {
			if ( ! preg_match( '/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2] ) ) {
				if ( preg_match( '/<[\w]+[^>]*>/s', $tag[0] ) ) {
					array_unshift( $openTags, $tag[2] );
				} elseif ( preg_match( '/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag ) ) {
					$pos = array_search( $closeTag[1], $openTags );
					if ( false !== $pos ) {
						array_splice( $openTags, $pos, 1 );
					}
				}
			}
			$truncate .= $tag[1];
			$contentLength = mb_strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3] ) );
			if ( $contentLength + $totalLength > $length ) {
				$left = $length - $totalLength;
				$entitiesLength = 0;
				if ( preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE ) ) {
					foreach ( $entities[0] as $entity ) {
						if ( $entity[1] + 1 - $entitiesLength <= $left ) {
							$left--;
							$entitiesLength += mb_strlen( $entity[0] );
						} else {
							break;
						}
					}
				}
				$truncate .= mb_substr( $tag[3], 0, $left + $entitiesLength );
				break;
			} else {
				$truncate .= $tag[3];
				$totalLength += $contentLength;
			}
			if ( $totalLength >= $length ) {
				break;
			}
		}
	} else {
		if ( mb_strlen( $text) <= $length ) {
			return $text;
		}
		$truncate = mb_substr( $text, 0, $length - mb_strlen( $ellipsis ) );
	}
	if ( false === $exact ) {
		$spacepos = mb_strrpos( $truncate, ' ' );
		if ( true === $html ) {
			$truncateCheck = mb_substr( $truncate, 0, $spacepos );
			$lastOpenTag = mb_strrpos( $truncateCheck, '<' );
			$lastCloseTag = mb_strrpos( $truncateCheck, '>' );
			if ( $lastOpenTag > $lastCloseTag ) {
				preg_match_all( '/<[\w]+[^>]*>/s', $truncate, $lastTagMatches );
				$lastTag = array_pop( $lastTagMatches[0] );
				$spacepos = mb_strrpos( $truncate, $lastTag ) + mb_strlen( $lastTag );
			}
			$bits = mb_substr( $truncate, $spacepos );
			preg_match_all( '/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER );
			if ( ! empty( $droppedTags ) ) {
				if ( ! empty( $openTags ) ) {
					foreach ( $droppedTags as $closingTag ) {
						if ( ! in_array( $closingTag[1], $openTags ) ) {
							array_unshift( $openTags, $closingTag[1] );
						}
					}
				} else {
					foreach ( $droppedTags as $closingTag ) {
						$openTags[] = $closingTag[1];
					}
				}
			}
		}
		$truncate = mb_substr( $truncate, 0, $spacepos );
		// If truncate still empty, then we don't need to count ellipsis in the cut.
		if ( 0 === mb_strlen( $truncate ) ) {
			$truncate = mb_substr( $text, 0, $length );
		}
	}
	$truncate .= $ellipsis;
	if ( true === $html ) {
		foreach ( $openTags as $tag ) {
			$truncate .= '</' . $tag . '>';
		}
	}
	return $truncate;
}

function patch_add_classes_to_linked_images( $content ) {
	$classes = 'img-link'; // can do multiple classes, separate with space

	$patterns = array();
	$replacements = array();

	//first if it has class with single quotes
	$patterns[0] = '/<a([^>]*)class=\'([^\']*)\'([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor has existing classes contained in single quotes
	$replacements[0] = '<a\1class="' . $classes . ' \2"\3><img\4></a>';

	//second if it has class with double quotes
	$patterns[1] = '/<a([^>]*)class="([^"]*)"([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor has existing classes contained in double quotes
	$replacements[1] = '<a\1class="' . $classes . ' \2"\3><img\4></a>';

	//third no class attribute
	$patterns[2] = '/<a(?![^>]*class)([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor tag where anchor has no existing classes
	$replacements[2] = '<a\1 class="' . $classes . '"><img\2></a>';

	//make sure that we respected the desired order of execution
	ksort($patterns);
	ksort($replacements);

	$content = preg_replace($patterns, $replacements, $content);

	return $content;
}

add_filter('the_content', 'patch_add_classes_to_linked_images', 99, 1);

// This function should come from Customify, but we need to do our best to make things happen
if ( ! function_exists( 'pixelgrade_option') ) {
	/**
	 * Get option from the database
	 *
	 * @since Patch 1.3.0
	 *
	 * @param string $option The option name.
	 * @param mixed $default Optional. The default value to return when the option was not found or saved.
	 * @param bool $force_default Optional. When true, we will use the $default value provided for when the option was not saved at least once.
	 *                          When false, we will let the option's default set value (in the Customify settings) kick in first, than our $default.
	 *                          It basically, reverses the order of fallback, first the option's default, then our own.
	 *                          This is ignored when $default is null.
	 *
	 * @return mixed
	 */
	function pixelgrade_option( $option, $default = null, $force_default = true ) {
		/** @var PixCustomifyPlugin $pixcustomify_plugin */
		global $pixcustomify_plugin;

		// if there is set an key in url force that value
		if ( isset( $_GET[ $option ] ) && ! empty( $option ) ) {

			return wp_unslash( sanitize_text_field( $_GET[ $option ] ) );

		} elseif ( $pixcustomify_plugin !== null ) {
			// if there is a customify value get it here

			// First we see if we are not supposed to force over the option's default value
			if ( $default !== null && $force_default == false ) {
				// We will not pass the default here so Customify will fallback on the option's default value, if set
				$customify_value = $pixcustomify_plugin->get_option( $option );

				// We only fallback on the $default if none was given from Customify
				if ( $customify_value == null ) {
					return $default;
				}
			} else {
				$customify_value = $pixcustomify_plugin->get_option( $option, $default );
			}

			return $customify_value;
		}

		return $default;
	}
}

/**
 * Retrieve the classes for the portfolio wrapper as an array.
 *
 * @since Patch 1.3.0
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 *
 * @return array Array of classes.
 */
function patch_get_blog_class( $class = '' ) {

	$classes = array();

	$classes[] = 'grid';

	// items per row
	$items_per_row = intval( pixelgrade_option( "blog_items_per_row", 3 ) );
	$items_per_row_at_huge = $items_per_row;
	$items_per_row_at_desk = $items_per_row == 1 ? 1 : $items_per_row > 3 ? $items_per_row - 1 : $items_per_row;
	$items_per_row_at_lap = $items_per_row_at_desk > 1 ? $items_per_row_at_desk - 1 : $items_per_row_at_desk;
	$items_per_row_class = "grid--" . $items_per_row_at_huge . "col-@desk  grid--" . $items_per_row_at_desk . "col-@lap  grid--" . $items_per_row_at_lap . "col-@small";

	$classes[] = $items_per_row_class;

	if ( ! empty( $class ) ) {
		if ( ! is_array( $class ) ) {
			$class = preg_split( '#\s+#', $class );
		}
		$classes = array_merge( $classes, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}

	$classes = array_map( 'esc_attr', $classes );

	return array_unique( $classes );
}

/**
 * Display the classes for the blog wrapper.
 *
 * @param string|array $class Optional. One or more classes to add to the class list.
 * @param string|array $location Optional. The place (template) where the classes are displayed. This is a hint for filters.
 */
function patch_blog_class( $class = '' ) {
	// Separates classes with a single space, collates classes
	echo 'class="' . join( ' ', patch_get_blog_class( $class ) ) . '"';
}
?>