// /* ====== ON DOCUMENT READY ====== */

$(document).ready(function() {
  init();
});

function init() {
  browserSize();
  platformDetect();
  resizeSiteTitle();
  masonry.refresh();
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
  unwrapBlockImages();
  $( 'body' ).imagesLoaded( function() {
    masonry.refresh();
  } );
});

// /* ====== ON RESIZE ====== */

function onResize() {
  browserSize();
  resizeSiteTitle();
  masonry.refresh();
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
