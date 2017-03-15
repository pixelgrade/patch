// /* ====== ON DOCUMENT READY ====== */

$(document).ready(function() {
  init();
});

var masonryRefresh = debounce(masonry.refresh, 100);

function init() {
  browserSize();
  platformDetect(); 
  masonryRefresh();
  reorderSingleFooter();
}

// /* ====== ON WINDOW LOAD ====== */

$window.load(function() {
  browserSize();
  Sidebar.init();
  navigation.init();
  scrollToTop();
  moveFeaturedImage();
  magnificPopupInit();
  logoAnimation.init();
  logoAnimation.update();
});

// /* ====== ON RESIZE ====== */

function onResize() {
  browserSize();
  masonryRefresh();
  Sidebar.init();
}

function requestTick() {
  if (!ticking) {
    requestAnimationFrame(update);
  }
  ticking = true;  
}

function update() {
  logoAnimation.update();
  ticking = false;
}

$window.on('debouncedresize', onResize);

$window.on('scroll', function() {
  latestKnownScrollY = window.scrollY;
  requestTick();
});

(function() {
	var observer, config;

	if ( typeof MutationObserver === "undefined" ) return;

	observer = new MutationObserver( function( mutations ) {
		mutations.forEach( function( mutation ) {
			if ( mutation.type === "childList" ) {
				$.each( mutation.addedNodes, function(i, node) {
					var $node = $( node );
					if ( $node.is( 'iframe' ) ) {
						$node.on( 'load', function() {
							masonryRefresh();
						});
					}
				});
			}
		} );
	} );

	config = {
		childList: true,
		characterData: false,
		attributes: false,
		subtree: true
	};

	$( ".grid .fb-video" ).each( function() {
		observer.observe( this, config );
	} );

})();
