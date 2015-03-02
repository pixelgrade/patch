/**
 * Silk Customizer JavaScript - keeps things nicer for all
 * v 1.0.1
 */

/**
 * Some AJAX powered controls
 * jQuery is available
 */
(function( $ ) {

	// Change site title and description when they are typed
	wp.customize( 'blogname', function( value ) {
		value.bind( function( text ) {
			$( '.site-title a span, .site-title text' ).text( text );
			svgLogo.init();
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( text ) {
			$( '.site-description-text' ).text( text );
		} );
	} );

	wp.customize('silk_site_title_outline', function (value) {
		value.bind( function (text) {
			$('.site-title text').attr('stroke-width', text);
		} );
	})

})( jQuery );