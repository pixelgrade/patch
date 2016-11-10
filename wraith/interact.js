// Interact with the page using CasperJS
module.exports = function ( casper, ready ) {
    var windowWidth = casper.page.viewportSize.width;
    var windowHeight = casper.page.viewportSize.height;

    // Tests for mobile devices
    if ( windowWidth < 1000 ) {

        // var scrollAgain = true;
        // var scrollPosition = 0;
        // var index = 0;
        //
        // while ( scrollAgain ) {
        //
        //
        //     casper.wait( 1000 );
        //
        //     console.log ( casper.page.scrollPosition.top );
        //
        //     casper.then( function() {
        //         this.capture('screenshots/screenshot_' + encodeURIComponent(casper.page.url) + '.png', {
        //             top: 0,
        //             left: 0,
        //             width: windowWidth,
        //             height: windowHeight
        //         });
        //     });
        //
        //     if ( scrollPosition == casper.page.scrollPosition.top ) {
        //         scrollAgain = false;
        //     }
        //
        //     scrollPosition = casper.page.scrollPosition.top;
        //     index++;
        // }

        casper.then( function() {
            this.click( '.navigation__trigger' );
        });
    }

    casper.wait( 5000, function() {
        ready();
    });
}
