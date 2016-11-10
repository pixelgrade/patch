// Interact with navigation
module.exports = function ( casper, ready ) {
    var windowWidth = casper.page.viewportSize.width;

    casper.wait(2000, function() {
        // Tests for mobile devices
        if ( windowWidth < 900 ) {
            casper.then( function() {
                this.click( '.js-nav-trigger' );
            });
        }

        if ( windowWidth >= 900 ) {
            casper.then( function() {
                this.mouse.move( '.nav--main > li:first-child' );
            });
        }
    });

    casper.wait( 5000, function() {
        ready();
    });
}
