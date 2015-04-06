/* --- Magnific Popup Initialization --- */

function magnificPopupInit() {
        $('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]').filter(function(elem) {
            return !$(this).parents('.gallery').length
        }).magnificPopup({
            type: 'image',
            closeOnContentClick: false,
            closeBtnInside: false,
            removalDelay: 500,
            mainClass: 'mfp-fade',
            image: {
                markup: '<div class="mfp-figure">' +
                '<div class="mfp-img"></div>' +
                '<div class="mfp-bottom-bar">' +
                '<div class="mfp-title"></div>' +
                '<div class="mfp-counter"></div>' +
                '</div>' +
                '</div>',
                titleSrc: function (item) {
                    var output = '';
                    if (typeof item.el.attr('data-alt') !== "undefined" && item.el.attr('data-alt') !== "") {
                        output += '<small>' + item.el.attr('data-alt') + '</small>';
                    }
                    return output;
                }
            }
        });
}
