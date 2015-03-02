/**
 * Shared variables
 */
var ua                  = navigator.userAgent.toLowerCase(),
    platform            = navigator.platform.toLowerCase(),
    $window             = $(window),
    $document           = $(document),
    $html               = $('html'),
    $body               = $('body'),
    
    iphone              = platform.indexOf("iphone"),
    ipod                = platform.indexOf("ipod"),
    android             = platform.indexOf("android"),
    android_ancient     = (ua.indexOf('mozilla/5.0') !== -1 && ua.indexOf('android') !== -1 && ua.indexOf('applewebKit') !== -1) && ua.indexOf('chrome') === -1,
    apple               = ua.match(/(iPad|iPhone|iPod|Macintosh)/i),
    windows_phone       = ua.indexOf('windows phone') != -1,
    webkit              = ua.indexOf('webkit') != -1,

    firefox             = ua.indexOf('gecko') != -1,
    firefox_3x          = firefox && ua.match(/rv:1.9/i),
    ie                  = ua.indexOf('msie' != -1),
    ie_newer            = ua.match(/msie (9|([1-9][0-9]))/i),
    ie_older            = ie && !ie_newer,
    ie_ancient          = ua.indexOf('msie 6') != -1,
    safari              = ua.indexOf('safari') != -1 && ua.indexOf('chrome') == -1,

    is_small            = $('.js-nav-trigger').is(':visible');

    windowHeight        = $window.height(),
    windowWidth         = $window.width(),
    documentHeight      = $document.height(),

    latestKnownScrollY  = window.scrollY,
    ticking             = false;

;