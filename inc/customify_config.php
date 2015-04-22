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
									'font-main' => 'Playfair Display',
									'font-alt' => 'Droid Serif',
								),
								'options' => array(
									'accent_color' => '#ffeb00',
									'headings_color' => '#171617',
									'body_color' => '#3d3e40',
									'border_color' => '#000000',
									'border_text_color' => '#ffffff',
									'headings_font' => 'Playfair Display',
									'body_font' => 'Droid Serif',
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
									'border_color' => '#000000',
									'border_text_color' => '#68f3c8',
									'headings_font' => 'Permanent Marker',
									'body_font' => 'Droid Sans Mono',
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
									'border_color' => '#615375',
									'border_text_color' => '#ffffff',
									'headings_font' => 'Abril Fatface',
									'body_font' => 'PT Serif',
								)
							),
							
							'queen' => array(
								'label' => __( 'Queen', 'patch' ),
								'preview' => array(
									'color-text' => '#fbedec',
									'background-card' => '#773347',
									'background-label' => '#41212a',
									'font-main' => 'Cinzel Decorative',
									'font-alt' => 'Gentium Basic',
								),
								'options' => array(
									'accent_color' => '#cd8085',
									'headings_color' => '#54323c',
									'body_color' => '#cd8085',
									'border_color' => '#41212a',
									'border_text_color' => '#ffffff',
									'headings_font' => 'Cinzel Decorative',
									'body_font' => 'Gentium Basic',
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
									'border_color' => '#fff',
									'border_text_color' => '#4b4b4b',
									'headings_font' => 'Oswald',
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
									'border_color' => '#000000',
									'border_text_color' => '#ffffff',
									'headings_font' => 'Pinyon Script',
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
							'property'     => 'color',
							'selector' => 'blockquote a:hover,
												.format-quote .edit-link a:hover,
												.content-quote blockquote:before,
												.widget a:hover,
												.widget_blog_subscription input[type="submit"],
												.widget_blog_subscription a:hover,
												blockquote a:hover',

						),
						array(
							'property'     => 'outline-color',
							'selector' => 'select:focus,
												textarea:focus,
												input[type="text"]:focus,
												input[type="password"]:focus,
												input[type="datetime"]:focus,
												input[type="datetime-local"]:focus,
												input[type="date"]:focus,
												input[type="month"]:focus,
												input[type="time"]:focus,
												input[type="week"]:focus,
												input[type="number"]:focus,
												input[type="email"]:focus,
												input[type="url"]:focus,
												input[type="search"]:focus,
												input[type="tel"]:focus,
												input[type="color"]:focus,
												.form-control:focus',
						),
						array(
							'property'     => 'border-color',
							'selector' => '.widget_blog_subscription input[type="submit"]',
						),
						array(
							'property'     => 'background',
							'selector' => '.highlight:before,
												.arcpatch__grid .accent-box,
												.sticky:after,
												.content-quote blockquote:after',
						),
						array(
							'property'		=> 'box-shadow',
							'selector'	=> '.content-quote blockquote:after',
							'callback_filter' => 'accent_color_box_shadow',
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
							'property'     => 'color',
							'selector' => '.site-title a, h1, h2, h3, blockquote, .dropcap, .single .entry-content:before, .page .entry-content:before',
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
							'selector' => 'body, .posted-on a, .posted-by a, .entry-title a',
							'property'     => 'color',
						)
					)
				),

				'border_color'	=> array(
					'type'      => 'color',
					'label'     => __( 'Border Color', 'patch' ),
					'live' => true,
					'default'   => '#171617',
					'css'  => array(
						array(
							'selector' => 'body:before, body:after',
							'media' => 'screen and (min-width: 1000px)',
							'property'     => 'background',
						),
						array(
							'selector' => '#infinite-footer, .site-footer',
							'property'     => 'background-color',
						),
					)
				),
				'border_text_color'	=> array(
					'type'      => 'color',
					'label'     => __( 'Border Text Color', 'patch' ),
					'live' => true,
					'default'   => '#ffffff',
					'css'  => array(
						array(
							'selector' => '.site-footer a, #infinite-footer .blog-info a, #infinite-footer .blog-credits a',
							'property'     => 'color',
						)
					)
				)
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
					'default'  => 'Playfair Display", serif',
					'selector' => '.dropcap,  .single .entry-content:before,  .page .entry-content:before,
									.site-title, h1, h2, h3, h4, h5, h6,
									.fs-36px,  .arcpatch__grid .entry-title,
									blockquote',
					'font_weight' => false,
					'load_all_weights' => true,
					'subsets' => true,
					'recommended' => array(
						'Playfair Display',
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
						'Pompiere',
					)
				),
				'body_font'     => array(
					'type'    => 'typography',
					'label'   => __( 'Body Text', 'patch' ),
					'default' => '"Droid Serif", serif',
					'selector' => 'html body, blockquote cite, .widget, div.sharedaddy .sd-social h3.sd-title',
					'load_all_weights' => true,
					'recommended' => array(
						'Droid Serif',
						'Lato',
						'Open Sans',
						'PT Sans',
						'Cabin',
						'Gentium Book Basic',
						'PT Serif',
					)
				)
			)
		)

	);

	return $options;
}

if ( ! function_exists('accent_color_box_shadow') ) {
	function accent_color_box_shadow( $value, $selector, $property, $unit ) {
		$output = $selector .'{ 
									-webkit-box-shadow: '. $value .' 5.5em 0 0;
										box-shadow: '. $value .' 5.5em 0 0; 
								}';
		return $output;
	}
}

add_filter( 'customify_filter_fields', 'patch_add_customify_options' ); ?>