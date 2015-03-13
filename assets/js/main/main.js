// /* ====== ON DOCUMENT READY ====== */

$(document).ready(function() {
  init();
});

function init() {
  browserSize();
  platformDetect(); 
}

console.log("wtf");

// /* ====== ON WINDOW LOAD ====== */

$window.load(function() {
  browserSize();
  navigation.init();
  masonry.refresh();
//   fixedSidebars.update();
//   svgLogo.init();
//   animator.animate();
   scrollToTop();
  moveFeaturedImage();

//   if (latestKnownScrollY) $window.trigger('scroll');
});

// /* ====== ON RESIZE ====== */

function onResize() {
  browserSize();
  masonry.refresh();
//   fixedSidebars.refresh();
//   fixedSidebars.update();
}

$window.on('debouncedresize', onResize);

// /* ====== ON SCROLL ====== */

var scrollingTimer;

function onScroll() {
  // disableHoverOnScroll();
  // latestKnownScrollY = window.scrollY;
  // requestTick();
}

// function disableHoverOnScroll() {
//   clearTimeout(scrollingTimer);
//   $body.addClass('disable-hover');
//   scrollingTimer = setTimeout(function(){
//     $body.removeClass('disable-hover');
//   }, 500);
// }

$window.on('scroll', onScroll);

// function requestTick() {
//   if (!ticking) {
//     requestAnimationFrame(update);
//   }
//   ticking = true;
// }

// function update() {
//   fixedSidebars.update();
//   navigation.toggleTopBar();
//   svgLogo.update();
//   ticking = false;
// }