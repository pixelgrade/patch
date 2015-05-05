<?php
/**
 * Add extra controls in the Customizer
 *
 * @package Patch
 */

function patch_add_customify_options( $options ) {

	$options['opt-name'] = 'patch_options';


	/**
	 * COLORS - This section will handle different elements colors (eg. links, headings)
	 */
	$options['sections'] = array(
		'presets_section' => array(
			'title'    => __( 'Style Presets', 'patch' ),
			'options' => array(
				'theme_style'   => array(
					'type'      => 'preset',
					'label'     => __( 'Select a style:', 'patch' ),
					'desc' => __( 'Conveniently change the design of your site with built-in style presets. Easy as pie.', 'patch' ),
					'default'   => 'patch',
					'choices_type' => 'awesome',
					'choices'  => array(
						'patch' => array(
							'label' => __( 'Patch', 'patch' ),
							'preview' => array(
								'color-text' => '#ffffff',
								'background-card' => '#121012',
								'background-label' => '#fee900',
								'font-main' => 'Oswald',
								'font-alt' => 'Roboto',
							),
							'options' => array(
								'accent_color' => '#ffeb00',
								'headings_color' => '#171617',
								'body_color' => '#3d3e40',
								'headings_font' => 'Oswald',
								'headings_caps' => true,
								'body_font' => 'Roboto',
							)
						),


						'adler' => array(
							'label' => __( 'Adler', 'patch' ),
							'preview' => array(
								'color-text' => '#fff',
								'background-card' => '#0e364f',
								'background-label' => '#000000',
								'font-main' => 'Permanent Marker',
								'font-alt' => 'Droid Sans Mono',
							),
							'options' => array(
								'accent_color' => '#68f3c8',
								'headings_color' => '#0e364f',
								'body_color' => '#45525a',
								'headings_font' => 'Permanent Marker',
								'headings_caps' => true,
								'body_font' => 'Droid Sans Mono'
							)
						),

						'royal' => array(
							'label' => __( 'Royal', 'patch' ),
							'preview' => array(
								'color-text' => '#ffffff',
								'background-card' => '#615375',
								'background-label' => '#46414c',
								'font-main' => 'Abril Fatface',
								'font-alt' => 'PT Serif',
							),
							'options' => array(
								'accent_color' => '#8eb2c5',
								'headings_color' => '#725c92',
								'body_color' => '#6f8089',
								'headings_font' => 'Abril Fatface',
								'headings_caps' => false,
								'body_font' => 'PT Serif',
							)
						),

						'queen' => array(
							'label' => __( 'Queen', 'patch' ),
							'preview' => array(
								'color-text' => '#fbedec',
								'background-card' => '#a33b61',
								'background-label' => '#41212a',
								'font-main' => 'Playfair Display',
								'font-alt' => 'Merriweather',
							),
							'options' => array(
								'accent_color' => '#c17390',
								'headings_color' => '#a33b61',
								'body_color' => '#403b3c',
								'headings_font' => 'Playfair Display',
								'headings_caps' => false,
								'body_font' => 'Merriweather',
							)
						),
						'carrot' => array(
							'label' => __( 'Carrot', 'patch' ),
							'preview' => array(
								'color-text' => '#ffffff',
								'background-card' => '#df421d',
								'background-label' => '#85210a',
								'font-main' => 'Oswald',
								'font-alt' => 'PT Sans Narrow',
							),
							'options' => array(
								'accent_color' => '#df421d',
								'headings_color' => '#df421d',
								'body_color' => '#7e7e7e',
								'headings_font' => 'Oswald',
								'headings_caps' => false,
								'body_font' => 'PT Sans Narrow',
							)
						),
						'velvet' => array(
							'label' => __( 'Velvet', 'patch' ),
							'preview' => array(
								'color-text' => '#ffffff',
								'background-card' => '#282828',
								'background-label' => '#000000',
								'font-main' => 'Pinyon Script',
								'font-alt' => 'Josefin Sans',
							),
							'options' => array(
								'accent_color' => '#000000',
								'headings_color' => '#000000',
								'body_color' => '#000000',
								'headings_font' => 'Pinyon Script',
								'headings_caps' => false,
								'body_font' => 'Josefin Sans',
							)
						),

					)
				),
			)
		),
		'colors_section' => array(
			'title'    => __( 'Colors', 'patch' ),
			'options' => array(
				'accent_color'   => array(
					'type'      => 'color',
					'label'     => __( 'Accent Color', 'patch' ),
					'live' => true,
					'default'   => '#ffeb00',
					'css'  => array(
						array(
							'property' => 'text-shadow',
							'selector' => '.dropcap',
							'callback_filter' => 'patch_dropcap_text_shadow'
						),
						array(
							'property' => 'box-shadow',
							'selector' => '.entry-card.format-quote .entry-content a',
							'callback_filter' => 'patch_link_box_shadow'
						),
						array(
							'property' => 'color',
							'selector' =>
								'h1 a,
								.site-title a,
								h2 a,
								h3 a,
								.entry-card.format-quote .entry-content a:hover,
								.bypostauthor .comment__author-name:before,
								.site-footer a:hover',
							'callback_filter' => 'patch_color_contrast'
						),
						array(
							'property' => 'fill',
							'selector' => '#bar'
						),
						array(
							'property' => 'background-color',
							'selector' =>
								'.smart-link,
								.single .entry-content a,
								.page .entry-content a,
								.edit-link a,
								.author-info__link,
								.comments_add-comment,
								.comment .comment-reply-title a,
								.page-links a,
								:first-child:not(input) ~ .form-submit #submit,
								.sidebar .widget a:hover,
								.nav--main li[class*="current-menu"] > a,
								.nav--main li:hover > a,
								.highlight,
								.sticky .sticky-post,
								.nav--social a:hover:before,
								.jetpack_subscription_widget input[type="submit"],
								.widget_blog_subscription input[type="submit"],
								.search-form .search-submit,
								div#infinite-handle span:after,
								.cat-links,
								.entry-format',
						),
						array(
							'property' => 'background-color',
							'selector' => '::-moz-selection'
						),
						array(
							'property' => 'background-color',
							'selector' => '::selection'
						),
						array(
							'property' => 'border-top-color',
							'selector' => '.sticky .sticky-post:before,
								.sticky .sticky-post:after'
						)
					),
				),
				'headings_color' => array(
					'type'      => 'color',
					'label'     => __( 'Headings Color', 'patch' ),
					'live' => true,
					'default'   => '#171617',
					'css'  => array(
						array(
							'property' => 'color',
							'selector' => '.site-title a, h1, h2, h3, h4, h5, h6',
						)
					)
				),
				'body_color'     => array(
					'type'      => 'color',
					'label'     => __( 'Body Color', 'patch' ),
					'live' => true,
					'default'   => '#3d3e40',
					'css'  => array(
						array(
							'selector' => 'body',
							'property' => 'color'
						)
					)
				),
			)
		),



		/**
		 * FONTS - This section will handle different elements fonts (eg. headings, body)
		 */

		'typography_section' => array(
			'title'    => __( 'Fonts', 'patch' ),
			'options' => array(
				'headings_font' => array(
					'type'     => 'typography',
					'label'    => __( 'Headings', 'patch' ),
					'default'  => 'Oswald", sans-serif',
					'selector' => 'h1,
					.site-title,
					h2,
					h3,
					h4,
					.edit-link a,
					blockquote,
					.dropcap,
					.mfp-container,
					.entry-card .entry-image .hover,
					.entry-card .entry-title,
					.nav--main,
					.author-info__link,
					.comments-area-title .comments-title,
					.comment-reply-title .comments-title,
					.comments_add-comment,
					.comment-reply-title,
					.comment .comment-reply-title a,
					:first-child:not(input) ~ .form-submit #submit,
					.jetpack_subscription_widget input[type="submit"],
					.widget_blog_subscription input[type="submit"],
					.search-form .search-submit,
					.overlay--search .search-form,
					.overlay--search .search-field,
					.posts-navigation, #infinite-handle,
					body div.sharedaddy h3.sd-title,
					body div#jp-relatedposts h3.jp-relatedposts-headline,
					.entry-meta',
					'font_weight' => false,
					'load_all_weights' => true,
					'subsets' => true,
					'recommended' => array(
						'Oswald',
						'Lato',
						'Open Sans',
						'Exo',
						'PT Sans',
						'Ubuntu',
						'Vollkorn',
						'Lora',
						'Arvo',
						'Josefin Slab',
						'Crete Round',
						'Kreon',
						'Bubblegum Sans',
						'The Girl Next Door',
						'Pacifico',
						'Handlee',
						'Satify',
						'Pompiere'
					)
				),
				'headings_caps' => array(
					'type'	=> 'checkbox',
					'default' => true,
					'label' => __( 'Capitalize Headings', 'patch' ),
					'css'	=> array(
						array(
							'property' => 'text-transform',
							'selector' => 'h1, .site-title, h2, h4, h5, .site-header, blockquote, .entry-card .entry-image .hover, .entry-card.format-quote cite, .author-info__link, .comments_add-comment, .comment .comment-reply-title a, .tags-links a, .jetpack_subscription_widget input[type="submit"], .widget_blog_subscription input[type="submit"], .search-form .search-submit, .page-numbers.prev, .page-numbers.next, .posts-navigation, #infinite-handle, div#infinite-handle button, .entry-meta, .byline .author',
							'callback_filter' => 'patch_capitalize_headings'
						),
					)
				),
				'body_font'     => array(
					'type'    => 'typography',
					'label'   => __( 'Body Text', 'patch' ),
					'default' => 'Roboto, sans-serif',
					'selector' => 'body, h5, .entry-card .entry-meta',
					'load_all_weights' => true,
					'recommended' => array(
						'Roboto',
						'Lato',
						'Open Sans',
						'PT Sans',
						'Cabin',
						'Gentium Book Basic',
						'PT Serif'
					)
				)
			)
		)

	);

	return $options;
}

function patch_capitalize_headings( $value, $selector, $property, $unit ) {

	$result = $value ? 'uppercase' : 'none';

	$output = $selector .'{
		text-transform: ' . $result . ";\n" .
	"}\n";

	return $output;
}


if ( !function_exists('patch_dropcap_text_shadow') ) {
	function patch_dropcap_text_shadow( $value, $selector, $property, $unit ) {
		$output = $selector . '{
			text-shadow: 2px 2px 0 white, 4px 4px 0 ' . $value . ";\n".
		          "}\n";
		return $output;
	}
}

if ( !function_exists('patch_link_box_shadow') ) {
	function patch_link_box_shadow( $value, $selector, $property, $unit ) {
		$output = $selector . '{
			box-shadow: inset 0 -3px 0 ' . $value . ";\n".
		          "}\n";
		return $output;
	}
}

if ( !function_exists('patch_color_contrast') ) {
	function patch_color_contrast( $value, $selector, $property, $unit ) {

		// Get our color
		if( empty($value) || ! preg_match('/^#[a-f0-9]{6}$/i', $value)) {
			return '';
		}

		$color = $value;
		// Calculate straight from RGB
		$r = hexdec($color[0].$color[1]);
		$g = hexdec($color[2].$color[3]);
		$b = hexdec($color[4].$color[5]);
		$is_dark = (( $r * 0.2126 + $g * 0.7152 + $b * 0.0722 ) < 40);

		// Determine if the color is considered to be dark
		if( $is_dark ){
			$output = '.cat-links a, .highlight, .search-form .search-submit,
				.smart-link:hover, .single .entry-content a:hover, .page .entry-content a:hover, .edit-link a:hover, .author-info__link:hover, .comments_add-comment:hover, .comment .comment-reply-title a:hover, .page-links a:hover, :first-child:not(input) ~ .form-submit #submit:hover, .sidebar .widget a:hover, .nav--social a:hover {
			  color: white;
			}';
		return $output;

		}
		return '';
	}
}

function load_javascript_thing() { ?>
	<script>
		function patch_color_contrast($value, $selector, $property, $unit) {
			var c = $value.substring(1);      // strip #
			var rgb = parseInt(c, 16);   // convert rrggbb to decimal
			var r = (rgb >> 16) & 0xff;  // extract red
			var g = (rgb >>  8) & 0xff;  // extract green
			var b = (rgb >>  0) & 0xff;  // extract blue

			var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
			// pick a different colour
			var this_selector = ".cat-links a, .highlight, .search-form .search-submit, .smart-link:hover, .single .entry-content a:hover, .page .entry-content a:hover, .edit-link a:hover, .author-info__link:hover, .comments_add-comment:hover, .comment .comment-reply-title a:hover, .page-links a:hover, :first-child:not(input) ~ .form-submit #submit:hover, .sidebar .widget a:hover, .nav--social a:hover";
			var elements = document.querySelectorAll(this_selector);
			if (luma < 40) {
				for (var i = 0; i < elements.length; i++) {
					elements[i].style.color = 'white';
				}
			} else {
				for (var i = 0; i < elements.length; i++) {
					elements[i].style.color = 'black';
				}
			}
		}
	</script>
<?php }

add_action('customize_preview_init', 'load_javascript_thing');

add_filter( 'customify_filter_fields', 'patch_add_customify_options' ); ?>