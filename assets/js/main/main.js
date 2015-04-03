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
  logoAnimation.init();
});

// /* ====== ON RESIZE ====== */

function onResize() {
  browserSize();
  masonry.refresh();
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