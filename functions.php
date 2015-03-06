<?php
/**
 * Patch functions and definitions
 *
 * @package Patch
 * @since Patch 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'patch_content_width' ) ) :
	/**
	 * Adjusts content_width value depending on situation.
	 */
	function patch_content_width() {
		global $content_width;

		if ( ! is_active_sidebar( 'sidebar-1' ) ) {
			$content_width = 1062; /* pixels */
		}

		//for attachments the $content_width is set in image.php
	}
endif; //patch_content_width
add_action( 'template_redirect', 'patch_content_width' );

if ( ! function_exists( 'patch_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function patch_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Patch, use a find and replace
	 * to change 'patch_txtd' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'patch_txtd', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	//used as featured image for posts on archive pages
	//also for the background image of About Me widget
	add_image_size( 'patch-masonry-image', 450, 9999, false );

	//used for the post thumbnail of posts on archives when displayed in a single column (no masonry)
	//and for the single post featured image
	add_image_size( 'patch-single-image', 1024, 9999, false );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' 	=> __( 'Primary Menu', 'patch_txtd' ),
		'social' 	=> __( 'Social Menu', 'patch_txtd' ),
		'footer'    => __( 'Footer Menu', 'patch_txtd' ),
	) );

	/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'gallery',
		'image',
		'audio',
		'video',
		'quote',
		'link',
	) );

	/*
	 * Add editor custom style to make it look more like the frontend
	 * Also enqueue the custom Google Fonts also
	 */
	add_editor_style( array( 'editor-style.css', patch_fonts_url() ) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'patch_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // patch_setup
add_action( 'after_setup_theme', 'patch_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function patch_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'patch_txtd' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'patch_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function patch_scripts() {
	//FontAwesome Stylesheet
	wp_enqueue_style( 'patch-font-awesome-style', get_stylesheet_directory_uri() . '/assets/css/font-awesome.css', array(), '4.3.0' );

	//Main Stylesheet
	wp_enqueue_style( 'patch-style', get_stylesheet_uri(), array( 'patch-font-awesome-style' ) );

	//Default Fonts
	wp_enqueue_style( 'patch-fonts', patch_fonts_url(), array(), null );

	//Enqueue jQuery
	wp_enqueue_script( 'jquery' );

	//Enqueue Masonry
	wp_enqueue_script( 'masonry' );

	//Enqueue ImagesLoaded plugin
	wp_enqueue_script( 'patch-imagesloaded', get_stylesheet_directory_uri() . '/assets/js/imagesloaded.js', array(), '3.1.8', true );

	//Enqueue HoverIntent plugin
	wp_enqueue_script( 'patch-hoverintent', get_stylesheet_directory_uri() . '/assets/js/jquery.hoverIntent.js', array( 'jquery' ), '1.8.0', true );

	//Enqueue Velocity.js plugin
	wp_enqueue_script( 'patch-velocity', get_stylesheet_directory_uri() . '/assets/js/velocity.js', array(), '1.1.0', true );

	//Enqueue Patch Custom Scripts
	wp_enqueue_script( 'patch-scripts', get_stylesheet_directory_uri() . '/assets/js/main.js', array(
		'jquery',
		'masonry',
		'patch-imagesloaded',
		'patch-hoverintent',
		'patch-velocity',
	), '1.0.0', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'patch_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php'; ?>