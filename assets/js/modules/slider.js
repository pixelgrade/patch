/* ====== Slider Logic ====== */

var slider = (function () {
    
    var $sliders = $('.flexslider'),

    init = function() {

        if (!useSlider()) {
            initFallback();
            return;
        }

        if ($.flexslider !== undefined && $sliders.length) {

            $sliders.flexslider({
                animation: "fade",
                slideshow: false, //no autostart slideshow for accessibility purposes
                controlNav: false,
                prevText: '<span class="screen-reader-text">' + silkFeaturedSlider.prevText + '</span>',
                nextText: '<span class="screen-reader-text">' + silkFeaturedSlider.nextText + '</span>',
                start: function() {
                    var $arrow = $('.svg-templates .slider-arrow');
                    $arrow.clone().appendTo('.flex-direction-nav .flex-prev');
                    $arrow.clone().appendTo('.flex-direction-nav .flex-next');
                }
            });
        }
    },

    initFallback = function() {
        $sliders.closest('.featured-content').insertAfter('#masthead').addClass('featured-content--scroll');
    },

    useSlider = function() {
        // return !(touch && windowWidth < 800);
        // return !(windowWidth < 800);
        return !$.support.touch;
    };

    return { 
        init: init
    }

})();