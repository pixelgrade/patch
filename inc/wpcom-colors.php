<?php
/* Custom Colors: Patch */

//Site Border
add_color_rule( 'bg', '#000000', array(
	array( 'body', 'border-color' ),
	array( 'body:before', 'background' ),
	array( '#bar', 'fill' ),
	array( '.highlight', 'color' ),
	array( 'div#infinite-footer, .site-footer', 'background-color' ),
	array( '.entry-card--text .entry-header', 'background-color' ),
	array( '.entry-card.format-quote .entry-content', 'background-color' ),
	array( '.comment-number--dark, .comments-area:after, .comment-reply-title:before, .add-comment .add-comment__button', 'background-color' ),
),
__( 'Body Border' ) );

add_color_rule( 'txt', '#ffde00', array(
	array( '.search-form .search-submit', 'background-color' ),
	array( '.back-to-top-button #bar', 'fill', 'bg' ),
	array( '.nav--social a:hover::before', 'background-color' ),
	array( '.jetpack_subscription_widget input[type="submit"], .widget_blog_subscription input[type="submit"], .sidebar .widget a:hover, .grid__item .entry-content a, .page-links a, .smart-link, .single .entry-content a, .page .entry-content a, .edit-link a, .author-info__link, .comments_add-comment, .comment .comment-reply-title a, :first-child:not(input) ~ .form-submit #submit, div#infinite-handle span, .sidebar a:hover, .nav--main li[class*="current-menu"] > a, .nav--main li:hover > a', 'background-color' ),
	array( '.nav--main li[class*="current-menu"] > a:before, .nav--main li:hover > a:before, .sidebar a:hover:before', 'background-color' ),
	array( '.cat-links, .entry-format, .sticky .sticky-post', 'background-color' ),
	array( '.sticky .sticky-post:before, .sticky .sticky-post:after', 'border-top-color' ),
	array( '.smart-link:hover, .single .entry-content a:hover, .page .entry-content a:hover, .edit-link a:hover, .author-info__link:hover, .comments_add-comment:hover, .comment .comment-reply-title a:hover, div#infinite-handle span:hover', 'background-color' ),
	array( '.site-footer a:hover', 'color', 'bg' ),
	array( '::selection', 'background' ),
	array( '::-moz-selection', 'background' ),
	array( '.nav--social a:hover:before', 'background' ),
	array( '.bypostauthor .comment__author-name:before', 'color' ),
),
__( 'Main Accent' ) );

add_color_rule( 'link', '#ffffff', array(

) );

add_color_rule( 'fg1', '#ffffff', array(

) );

add_color_rule( 'fg2', '#ffffff', array(

) );

add_color_rule( 'extra', '#000000', array(
	array( '.cat-links a, .entry-format a, .cat-links, .entry-format', 'color', 'txt' ),
	array( '.page-links a:hover, .smart-link:hover, .single .entry-content a:hover, .page .entry-content a:hover, .edit-link a:hover, .author-info__link:hover, .comments_add-comment:hover, .comment .comment-reply-title a:hover, div#infinite-handle span:hover', 'color', 'txt' ),
	array( '::selection', 'color', 'txt' ),
	array( '::-moz-selection', 'color', 'txt' ),
) );

add_color_rule( 'extra', '#ffffff', array(
	array( '.nav--social a:hover::before', 'color', 'txt' ),
	array( '.search-form .search-submit', 'color', 'txt' ),
	array( '.comment-number--dark, .comments-area:after, .comment-reply-title:before, .add-comment .add-comment__button', 'color', 'bg' ),
	array( '.back-to-top-button #arrow', 'fill', 'bg' ),
	array( 'div#infinite-footer, .site-footer a[rel="designer"], .site-footer a', 'color', 'bg' ),
	array( '.entry-card--text .entry-header a, .entry-card--text .entry-title', 'color', 'bg' ),
	array( '.entry-card.format-quote .entry-content, .entry-card.format-quote blockquote, .entry-card.format-quote cite', 'color', 'bg' ),
) );

add_color_rule( 'extra', '#d5d5d5', array(
	array( 'div#infinite-footer .blog-info a, div#infinite-footer .blog-credits a, .site-footer', 'color', 'bg' ),
) );

// Additional CSS
add_theme_support( 'custom_colors_extra_css', 'patch_extra_css' );
function patch_extra_css() { ?>
	.single .entry-content h1 a,
	.single .entry-content h2 a,
	.single .entry-content h3 a,
	.sidebar h1 a,
	.sidebar h2 a,
	.sidebar h3 a,
	.entry-card.format-quote .entry-content a:hover {
		color: #171617;
	}

	.dropcap {
		text-shadow: none;
	}

	.entry-card.format-quote .entry-content a {
		box-shadow: none;
	}

<?php
}

// Free color palettes
add_color_palette( array(
	'#725c92',
	'#8eb2c5',
), 'Purple' );

add_color_palette( array(
	'#0e364f',
	'#68f3c8',
), 'Aqua' );

add_color_palette( array(
	'#606060',
	'#ff4828',
), 'Red' );
