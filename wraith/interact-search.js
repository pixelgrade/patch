// Interact with navigation
module.exports = function ( casper, ready ) {
    var windowWidth = casper.page.viewportSize.width;

    // Tests for mobile devices
    if ( windowWidth < 900 ) {
        casper.then( function() {
            this.click( '.search__trigger' );
        });
    }

    if ( windowWidth >= 900 ) {
        casper.then( function() {
            this.click( '.nav--main a[href*="search"]' );
        });
    }

    casper.wait( 5000, function() {
        ready();
    });
}
