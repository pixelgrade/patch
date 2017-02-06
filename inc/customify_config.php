<?php
/**
 * Add extra controls in the Customizer
 *
 * @package Patch
 */

function patch_add_customify_options( $options ) {

	$options['opt-name'] = 'patch_options';

	// Recommended Fonts List - Headings
	$recommended_fonts = apply_filters( 'pixelgrade_header_customify_recommended_headings_fonts',
		array(
			'Oswald',
			'Roboto',
			'Playfair Display',
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
	);

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
		'header_section' => array(
			'title'    => __( 'Header', 'patch' ),
			'options' => array(
				'patch_header_options_customizer_tabs'        => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-header-layout">' . esc_html__( 'Layout', 'patch' ) . '</a>
							<a href="#section-title-header-colors">' . esc_html__( 'Colors', 'patch' ) . '</a>
							<a href="#section-title-header-fonts">' . esc_html__( 'Fonts', 'patch' ) . '</a>
							</nav>',
				),
				// [Section] Layout
				'patch_header_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'patch' ) . '</span>',
				),
				'patch_header_logo_height'              => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Logo Height', 'patch' ),
					'desc'        => esc_html__( 'Adjust the max height of your logo container.', 'patch' ),
					'live'        => true,
					'default'     => 36,
					'input_attrs' => array(
						'min'          => 20,
						'max'          => 200,
						'step'         => 1,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'max-height',
							'selector' => '.site-logo img, .custom-logo-link img',
							'unit'     => 'px',
						),
						array(
							'property' => 'font-size',
							'selector' => '.site-title',
							'unit'     => 'px',
						),
					),
				),
				'patch_navigation_items_spacing' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Navigation Items Spacing', 'patch' ),
					'live'        => true,
					'default'     => 10,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 40,
						'step'         => 1
					),
					'css'         => array(
						array(
							'property' => 'margin-bottom',
							'selector' => '.no-valid-selector-here',
							'unit'     => 'px',
							'callback_filter' => 'patch_navigation_items_spacing_cb'
						),
					),
				),
				'patch_disable_search_in_social_menu' => array(
					'type'    => 'checkbox',
					'label'   => esc_html__( 'Hide search button in Social Menu.', 'patch' ),
					'default' => 0,
				),
				// [Section] COLORS
				'patch_header_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'patch' ) . '</span>',
				),
				'patch_header_navigation_links_color' => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Navigation Links Color', 'patch' ),
					'live'    => true,
					'default' => '#000000',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.site-header a',
						),
					),
				),
				'patch_header_links_active_color'     => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Links Active Color', 'patch' ),
					'live'    => true,
					'default' => '#ffeb00',
					'css'     => array(
						array(
							'property' => 'background-color',
							'selector' => '.nav--main li:hover > a',
						),
					),
				),
				// [Section] FONTS
				'patch_header_title_fonts_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-header-fonts" class="separator section label large">&#x1f4dd;  ' . esc_html__( 'Fonts', 'patch' ) . '</span>',
				),
				'patch_header_links_font' => array(
					'type'     			=> 'font',
					'label'            => esc_html__( 'Navigation Text', 'patch' ),
					'desc'             => esc_html__( '', 'patch' ),
					'selector'         => '.nav--main a',

					// Set the defaults
					'default'  => array(
						'font-family'    => 'Oswald',
						'font-weight'    => '300',
						'font-size'      => 24,
						'line-height'    => 1.181,
						'letter-spacing' => 0.144,
						'text-transform' => 'uppercase'
					),

					// List of recommended fonts defined by theme
					'recommended' => $recommended_fonts,
					// Sub Fields Configuration (optional)
					'fields'   => array(
						'font-size'       => array(                           // Set custom values for a range slider
							'min'  => 8,
							'max'  => 60,
							'step' => 1,
							'unit' => 'px',
						),
						'line-height'     => array( 0, 2, 0.1, '' ),           // Short-hand version
						'letter-spacing'  => array( -1, 2, 0.01, 'em' ),
						'text-align'      => false,                           // Disable sub-field (False by default)
						'text-transform'  => true,
						'text-decoration' => false
					)
				),
			)
		),
		'footer_section' => array(
			'title'    => __( 'Footer', 'patch' ),
			'options' => array(
				'patch_footer_options_customizer_tabs'    => array(
					'type' => 'html',
					'html' => '<nav class="section-navigation  js-section-navigation">
							<a href="#section-title-footer-layout">' . esc_html__( 'Layout', 'patch' ) . '</a>
							<a href="#section-title-footer-colors">' . esc_html__( 'Colors', 'patch' ) . '</a>
							</nav>',
				),
				// [Section] Layout
				'patch_footer_title_layout_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-footer-layout" class="separator section label large">&#x1f4d0; ' . esc_html__( 'Layout', 'patch' ) . '</span>',
				),
				'patch_footer_copyright' => array(
					'type'              => 'textarea',
					'label'             => esc_html__( 'Copyright Text', 'patch' ),
					'desc'              => esc_html__( 'Set the text that will appear in the footer area. Use %year% to display the current year.', 'patch' ),
					'default'           => __( '%year% &copy; Handcrafted with love by <a href="#">Pixelgrade</a> Team', 'patch' ),
					'sanitize_callback' => 'wp_kses_post',
					'live'              => array( '.copyright-text' ),
				),
				'patch_footer_top_spacing' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Top Spacing', 'patch' ),
					'live'        => true,
					'default'     => 12,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 12,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'padding-top',
							'selector' => '.site-footer',
							'unit'     => 'px',
						),
					),
				),
				'patch_footer_bottom_spacing' => array(
					'type'        => 'range',
					'label'       => esc_html__( 'Bottom Spacing', 'patch' ),
					'live'        => true,
					'default'     => 12,
					'input_attrs' => array(
						'min'          => 0,
						'max'          => 120,
						'step'         => 12,
						'data-preview' => true,
					),
					'css'         => array(
						array(
							'property' => 'padding-bottom',
							'selector' => '.site-footer',
							'unit'     => 'px',
						),
					),
				),
				'patch_hide_back_to_top' => array(
					'type'	=> 'checkbox',
					'default' => false,
					'label' => __( 'Hide Back To Top Link', 'patch' ),
					'css' => array(
						array(
							'property' => 'display',
							'selector' => '.back-to-top-button',
							'callback_filter' => 'patch_hide_back_to_top'
						)
					)
				),
				// [Section] COLORS
				'patch_footer_title_colors_section'    => array(
					'type' => 'html',
					'html' => '<span id="section-title-footer-colors" class="separator section label large">&#x1f3a8; ' . esc_html__( 'Colors', 'patch' ) . '</span>',
				),
				'patch_footer_body_text_color'       => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Text Color', 'patch' ),
					'live'    => true,
					'default' => '#b5b5b5',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.site-footer',
						),
					),
				),
				'patch_footer_links_color'           => array(
					'type'    => 'color',
					'label'   => esc_html__( 'Links Color', 'patch' ),
					'live'    => true,
					'default' => '#b5b5b5',
					'css'     => array(
						array(
							'property' => 'color',
							'selector' => '.site-footer a',
						),
					),
				),
			)
		),
		'main_content_section' => array(
			'title'    => __( 'Main Content', 'patch' ),
			'options' => array(
				'container_max_width' => array(
					'type' => 'range',
					'label' => esc_html__( 'Content Width', 'patch' ),
					'live' => true,
					'default' => 620,
					'input_attrs' => array(
						'min' => 480,
						'max' => 1240,
						'step' => 10,
						'data-preview' => true
					),
					'css' => array(
						array(
							'property' => 'max-width',
							'selector' =>
								'.single .hentry,
								.single .comments-area,
								.single .nocomments, 
								.single #respond.comment-respond, 
								.page:not(.entry-card) .hentry, 
								.page:not(.entry-card) .comments-area, 
								.page:not(.entry-card) .nocomments, 
								.page:not(.entry-card) #respond.comment-respond, 
								.attachment-navigation, .nav-links',
							'unit' => 'px'
						)
					)
				),
				'content_sides_spacing' => array(
					'type' => 'range',
					'label' => esc_html__( 'Container Sides Spacing', 'patch' ),
					'live' => true,
					'default' => 180,
					'input_attrs' => array(
						'min' => 0,
						'max' => 400,
						'step' => 10,
						'data-preview' => true
					),
					'css' => array(
						array(
							'property' => 'no-valid-property-here',
							'selector' => '.no-valid-selector-here',
							'unit' => 'px',
							'callback_filter' => 'patch_content_sides_spacing'
						),
					)
				),
				'container_sides_spacing' => array(
					'type' => 'range',
					'label' => esc_html__( 'Container Sides Spacing', 'patch' ),
					'live' => true,
					'default' => 60,
					'input_attrs' => array(
						'min' => 20,
						'max' => 200,
						'step' => 10,
						'data-preview' => true
					),
					'css' => array(
						array(
							'property' => 'padding',
							'selector' =>
								'.single .site-content, 
								.page:not(.entry-card) .site-content, 
								.no-posts .site-content',
							'unit' => 'px'
						),
					)
				)
			)
		),
		'blog_grid_section' => array(
			'title'    => __( 'Blog Grid Items', 'patch' ),
			'options' => array(
				'blog_container_max_width' => array(
					'type' => 'range',
					'label' => esc_html__( 'Container Max Width', 'patch' ),
					'live' => true,
					'default' => 1350,
					'input_attrs' => array(
						'min' => 1000,
						'max' => 2000,
						'step' => 10,
						'data-preview' => true
					),
					'css' => array(
						array(
							'media' => 'only screen and (min-width: 1260px)',
							'property' => 'max-width',
							'selector' => '.grid, .pagination',
							'unit' => 'px'
						),
					)
				),
				'blog_container_sides_spacing' => array(
					'type' => 'range',
					'label' => esc_html__( 'Container Sides Spacing', 'patch' ),
					'live' => true,
					'default' => 60,
					'input_attrs' => array(
						'min' => 20,
						'max' => 200,
						'step' => 10,
						'data-preview' => true
					),
					'css' => array(
						array(
							'media' => 'only screen and (min-width: 1260px)',
							'property' => 'padding-left',
							'selector' => '.layout-grid .site-content',
							'unit' => 'px'
						),
						array(
							'media' => 'only screen and (min-width: 1260px)',
							'property' => 'padding-right',
							'selector' => '.layout-grid .site-content',
							'unit' => 'px'
						),
						array(
							'media' => 'only screen and (min-width: 1260px)',
							'property' => 'padding-right',
							'selector' => '.layout-grid .site-content',
							'unit' => 'px'
						),
					)
				),
			)
		),
		'demo_content_section' => array(
			'title'    => __( 'Demo Content', 'patch' ),
			'priority' => 999999,
			'options' => array(
				'import_demodata_button' => array(
					'title' => 'Import',
					'type'  => 'html',
					'html' =>
						'<input type="hidden" name="wpGrade-nonce-import-posts-pages" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_posts_pages' ) . '" />' .
						'<input type="hidden" name="wpGrade-nonce-import-theme-options" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_theme_options' ) . '" />' .
						'<input type="hidden" name="wpGrade-nonce-import-widgets" value="' . wp_create_nonce( 'wpGrade_nonce_import_demo_widgets' ) . '" />' .
						'<input type="hidden" name="wpGrade_import_ajax_url" value="' . admin_url( "admin-ajax.php" ) . '" />' .
						'<div class="description customize-control-description">' .
							'<p>' . esc_html__( 'Use the Demo Content as a starting point in building your site, rather than beginning with a blank template.', 'patch' ) . '</p>' .
							'<p>' . esc_html__( 'Note that the images will be replaced with free samples as there isn\'t any redistribution license for them.', 'patch' ) . '</p>' .
							'<p>' . sprintf( wp_kses( __( 'Read more about <a href="%s" target="_blank">Adding the Demo Content</a> on our Knowledge Base.', 'patch' ), array(  'a' => array( 'href' => array(), 'target' => array() ) ) ), esc_url( 'https://pixelgrade.com/docs/getting-started/adding-demo-content/' ) ) . '</p>' .
							'<a href="#" class="button button-primary" id="wpGrade_import_demodata_button" style="width: 70%; text-align: center; padding: 10px; display: inline-block; height: auto;  margin: 0 15% 10% 15%;">' .
								__( 'Import demo data', 'patch' ) .
							'</a>' .
						'</div>' .
						'<div class="wpGrade-loading-wrap hidden">' .
							'<span class="wpGrade-loading wpGrade-import-loading"></span>' .
							'<div class="wpGrade-import-wait">' .
								esc_html__( 'Please wait a few minutes (between 1 and 3 minutes usually, but depending on your hosting it can take longer) and ', 'patch' ) .
								'<strong>' . esc_html__( 'don\'t reload the page', 'patch' ) . '</strong>.' .
								esc_html__( 'You will be notified as soon as the import has finished!', 'patch' ) .
							'</div>' .
						'</div>' .
						'<div class="wpGrade-import-results hidden"></div>' .
						'<div class="hr"><div class="inner"><span>&nbsp;</span></div></div>'
				)
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
								.site-footer a:hover, .test',
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
							'selector' => 'h1, .site-title, h2, h4, h5, .site-header, blockquote, .entry-card .entry-image .hover, .entry-card.format-quote cite, .author-info__link, .comments_add-comment, .comment .comment-reply-title a, .tags-links a, .jetpack_subscription_widget input[type="submit"], .widget_blog_subscription input[type="submit"], .search-form .search-submit, .page-numbers.prev, .page-numbers.next, .posts-navigation, #infinite-handle, div#infinite-handle button, .entry-meta, .byline .author, :first-child:not(input) ~ .form-submit #submit',
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

function patch_navigation_items_spacing_cb( $value, $selector, $property, $unit ) {

	$output = '';

	$output .= '.nav--main li' . '{ ' . $property . ': ' . $value . $unit . '; }';

	$output .= '@media only screen and (min-width: 900px) { ';
	$output .= '.single .nav--main > li,
				.page .nav--main > li,
				.no-posts .nav--main > li' . '{ ' . $property . ': ' . 2 * $value . $unit . '; }';
	$output .= '}';

	return $output;
}

function patch_navigation_items_spacing_cb_customizer_preview() { ?>
	<script type="text/javascript">
		function patch_navigation_items_spacing_cb( value, selector, property, unit ) {

			var css = '',
				style = document.getElementById('patch_navigation_items_spacing_cb_style_tag'),
				head = document.head || document.getElementsByTagName('head')[0];

			css += '.nav--main li { margin-bottom: ' + value + unit + '; }';

			css += '@media not screen and (min-width: 900px) {';
			css += '.nav--main ul { margin-top: ' + value + unit + '; }';
			css += '}';

			css += '@media only screen and (min-width: 900px) { ';
			css += '.single .nav--main > li, .page .nav--main > li, .no-posts .nav--main > li' + '{ margin-bottom: ' + 2 * value + unit + '; }';
			css += '}';

			console.log(css);

			if ( style !== null ) {
				style.innerHTML = css;
			} else {
				style = document.createElement('style');
				style.setAttribute('id', 'patch_navigation_items_spacing_cb_style_tag');

				style.type = 'text/css';
				if ( style.styleSheet ) {
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}

				head.appendChild(style);
			}
		}
	</script>
<?php }
add_action( 'customize_preview_init', 'patch_navigation_items_spacing_cb_customizer_preview' );

function patch_hide_back_to_top( $value, $selector, $property, $unit ) {

	$output = '';

	if ( $value ) {
		$output = $selector . '{ display: none }';
	}

	return $output;
}

function patch_content_sides_spacing( $value, $selector, $property, $unit ) {

	$output = '';

	$output .= '@media only screen and (min-width: 1260px) {';

	$output .=
		'.single .site-main, 
		.page:not(.entry-card) .site-main,
		.no-posts .site-main { ' .
			'padding-left: ' . $value . $unit . ';' .
			'padding-right: ' . $value . $unit . ';' .
		'}';

	$output .=
		'.single .entry-image--portrait .entry-featured,
		.single .entry-image--tall .entry-featured, 
		.page:not(.entry-card) .entry-image--portrait .entry-featured, 
		.page:not(.entry-card) .entry-image--tall .entry-featured { ' .
			'margin-left: ' . $value . $unit . ';' .
        '}';

	$output .= '.single .entry-image--landscape .entry-featured,
				.single .entry-image--wide .entry-featured,
				.page:not(.entry-card) .entry-image--landscape .entry-featured,
				.page:not(.entry-card) .entry-image--wide .entry-featured { ' .
		           'margin-left: ' . (-1 * $value) . $unit . ';' .
		           'margin-right: ' . (-1 * $value) . $unit . ';' .
           ' }';

	$output .= '}';

	return $output;
}

/**
 * Outputs the inline JS code used in the Customizer for the aspect ratio live preview.
 */
function patch_content_sides_spacing_customizer_preview() { ?>
	<script type="text/javascript">
		function patch_content_sides_spacing( value, selector, property, unit ) {

			var css = '',
				style = document.getElementById('patch_content_sides_spacing_style_tag'),
				head = document.head || document.getElementsByTagName('head')[0];

			css += '@media only screen and (min-width: 1260px) {';

			css += '.single .site-main,' +
			       '.page:not(.entry-card) .site-main,' +
			       '.no-posts .site-main { ' +
			            'padding-left: ' + value + unit + ';' +
						'padding-right: ' + value + unit + ';' +
			       '}';

			css += '.single .entry-image--portrait .entry-featured,' +
			       '.single .entry-image--tall .entry-featured,' +
			       '.page:not(.entry-card) .entry-image--portrait .entry-featured,' +
			       '.page:not(.entry-card) .entry-image--tall .entry-featured { ' +
			            'margin-left: ' + (-1 * value) + unit + ';' +
			       ' }';

			css += '.single .entry-image--landscape .entry-featured,' +
		           '.single .entry-image--wide .entry-featured,' +
			       '.page:not(.entry-card) .entry-image--landscape .entry-featured,' +
			       '.page:not(.entry-card) .entry-image--wide .entry-featured { ' +
						'margin-left: ' + (-1 * value) + unit + ';' +
						'margin-right: ' + (-1 * value) + unit + ';' +
					' }';

			css += '}';

			if ( style !== null ) {
				style.innerHTML = css;
			} else {
				style = document.createElement('style');
				style.setAttribute('id', 'patch_content_sides_spacing_style_tag');

				style.type = 'text/css';
				if ( style.styleSheet ) {
					style.styleSheet.cssText = css;
				} else {
					style.appendChild(document.createTextNode(css));
				}

				head.appendChild(style);
			}
		}
	</script>
<?php }
add_action( 'customize_preview_init', 'patch_content_sides_spacing_customizer_preview' );


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
				.smart-link:hover, .single .entry-content a:hover, .page .entry-content a:hover, .edit-link a:hover, .author-info__link:hover, .comments_add-comment:hover, .comment .comment-reply-title a:hover, .page-links a:hover, :first-child:not(input) ~ .form-submit #submit:hover, .nav--social a:hover, .site-footer a:hover {
			  color: white;
			}
			';

			return $output;
		}

		// if it is not a dark color, just go for the default way
		$output = $selector . ' {
			  color: ' . $value .';
			}
			';

		return $output;
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