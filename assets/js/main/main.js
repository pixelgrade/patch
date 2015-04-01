// /* ====== ON DOCUMENT READY ====== */

$(document).ready(function() {
  init();
});

function init() {
  browserSize();
  platformDetect(); 
}

// /* ====== ON WINDOW LOAD ====== */

$window.load(function() {
  browserSize();
  navigation.init();
  masonry.refresh();
  scrollToTop();
  moveFeaturedImage();
  magnificPopupInit();
});

// /* ====== ON RESIZE ====== */

function onResize() {
  browserSize();
  masonry.refresh();
}

$window.on('debouncedresize', onResize);