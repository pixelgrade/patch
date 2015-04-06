/* --- Magnific Popup Initialization --- */

function magnificPopupInit() {
    $('.entry-content').each(function () { // the containers for all your galleries should have the class gallery
        $(this).magnificPopup({
            delegate: 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]', // the container for each your gallery items
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
    });
}
