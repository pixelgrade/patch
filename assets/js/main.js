/**
 * jQuery plugin to make the main navigation WAI-ARIA compatible
 * Inspired by http://simplyaccessible.com/examples/css-menu/option-6/
 *
 * It needs jquery.hoverIntent
 */
(function ($) {

  $.fn.ariaNavigation = function (settings) {

    //Map of all the alphanumeric keys so one can jump through submenus by typing the first letter
    //Also use the ESC key to close a submenu
    var keyCodeMap = {
      48: "0",
      49: "1",
      50: "2",
      51: "3",
      52: "4",
      53: "5",
      54: "6",
      55: "7",
      56: "8",
      57: "9",
      59: ";",
      65: "a",
      66: "b",
      67: "c",
      68: "d",
      69: "e",
      70: "f",
      71: "g",
      72: "h",
      73: "i",
      74: "j",
      75: "k",
      76: "l",
      77: "m",
      78: "n",
      79: "o",
      80: "p",
      81: "q",
      82: "r",
      83: "s",
      84: "t",
      85: "u",
      86: "v",
      87: "w",
      88: "x",
      89: "y",
      90: "z",
      96: "0",
      97: "1",
      98: "2",
      99: "3",
      100: "4",
      101: "5",
      102: "6",
      103: "7",
      104: "8",
      105: "9"
    },
        $nav = $(this),
        $allLinks = $nav.find('li.nav__item > a'),
        $topLevelLinks = $nav.find('> li > a'),
        subLevelLinks = $topLevelLinks.parent('li').find('ul').find('a');
    navWidth = $nav.outerWidth();

    //default settings
    settings = jQuery.extend({
      menuHoverClass: 'show-menu',
      topMenuHoverClass: 'hover'
    }, settings);


    /**
     *  First add the needed WAI-ARIA markup - supercharge the menu
     */

    // Add ARIA role to menubar and menu items
    $nav.attr('role', 'menubar').find('li').attr('role', 'menuitem');

    $topLevelLinks.each(function () {
      //for regular sub-menus
      // Set tabIndex to -1 so that links can't receive focus until menu is open
      $(this).next('ul').attr({
        'aria-hidden': 'true',
        'role': 'menu'
      }).find('a').attr('tabIndex', -1);

      // Add aria-haspopup for appropriate items
      if ($(this).next('ul').length > 0) {
        $(this).parent('li').attr('aria-haspopup', 'true');
      }

      //for mega menus
      // Set tabIndex to -1 so that links can't receive focus until menu is open
      $(this).next('.sub-menu-wrapper').children('ul').attr({
        'aria-hidden': 'true',
        'role': 'menu'
      }).find('a').attr('tabIndex', -1);

      $(this).next('.sub-menu-wrapper').find('a').attr('tabIndex', -1);

      // Add aria-haspopup for appropriate items
      if ($(this).next('.sub-menu-wrapper').length > 0) $(this).parent('li').attr('aria-haspopup', 'true');
    });


    /**
     * Now let's begin binding things to their proper events
     */

    // First, bind to the hover event
    // use hoverIntent to make sure we avoid flicker
    $allLinks.closest('li').hoverIntent({
      over: function () {
        //clean up first
        $(this).closest('ul').find('ul.' + settings.menuHoverClass).attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);

        $(this).closest('ul').find('.' + settings.topMenuHoverClass).removeClass(settings.topMenuHoverClass);

        //now do things
        showSubMenu($(this));

      },
      out: function () {
        hideSubMenu($(this));
      },
      timeout: 300
    });

    // Secondly, bind to the focus event - very important for WAI-ARIA purposes
    $allLinks.focus(function () {
      //clean up first
      $(this).closest('ul').find('ul.' + settings.menuHoverClass).attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);

      $(this).closest('ul').find('.' + settings.topMenuHoverClass).removeClass(settings.topMenuHoverClass);

      //now do things
      showSubMenu($(this).closest('li'));

    });


    // Now bind arrow keys for navigating the menu
    // First the top level links (the permanent visible links)
    $topLevelLinks.keydown(function (e) {
      var $item = $(this);

      if (e.keyCode == 37) { //left arrow
        e.preventDefault();
        // This is the first item
        if ($item.parent('li').prev('li').length == 0) {
          $item.parents('ul').find('> li').last().find('a').first().focus();
        } else {
          $item.parent('li').prev('li').find('a').first().focus();
        }
      } else if (e.keyCode == 38) { //up arrow
        e.preventDefault();
        if ($item.parent('li').find('ul').length > 0) {
          $item.parent('li').find('ul').attr('aria-hidden', 'false').addClass(settings.menuHoverClass).find('a').attr('tabIndex', 0).last().focus();
        }
      } else if (e.keyCode == 39) { //right arrow
        e.preventDefault();

        // This is the last item
        if ($item.parent('li').next('li').length == 0) {
          $item.parents('ul').find('> li').first().find('a').first().focus();
        } else {
          $item.parent('li').next('li').find('a').first().focus();
        }
      } else if (e.keyCode == 40) { //down arrow
        e.preventDefault();
        if ($item.parent('li').find('ul').length > 0) {
          $item.parent('li').find('ul').attr('aria-hidden', 'false').addClass(settings.menuHoverClass).find('a').attr('tabIndex', 0).first().focus();
        }
      } else if (e.keyCode == 32) { //space key
        // If submenu is hidden, open it
        e.preventDefault();
        $item.parent('li').find('ul[aria-hidden=true]').attr('aria-hidden', 'false').addClass(settings.menuHoverClass).find('a').attr('tabIndex', 0).first().focus();
      } else if (e.keyCode == 27) { //escape key
        e.preventDefault();
        $('.' + settings.menuHoverClass).attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);
      } else { //cycle through the child submenu items based on the first letter
        $item.parent('li').find('ul[aria-hidden=false] > li > a').each(function () {
          if ($(this).text().substring(0, 1).toLowerCase() == keyCodeMap[e.keyCode]) {
            $(this).focus();
            return false;
          }
        });
      }
    });

    // Now do the keys bind for the submenus links
    $(subLevelLinks).keydown(function (e) {
      var $item = $(this);

      if (e.keyCode == 38) { //up arrow
        e.preventDefault();
        // This is the first item
        if ($item.parent('li').prev('li').length == 0) {
          $item.parents('ul').parents('li').find('a').first().focus();
        } else {
          $item.parent('li').prev('li').find('a').first().focus();
        }
      } else if (e.keyCode == 39) { //right arrow
        e.preventDefault();

        //if it has sub-menus we should go into them
        if ($item.parent('li').hasClass('menu-item-has-children')) {
          $item.next('ul').find('> li').first().find('a').first().focus();
        } else {
          // This is the last item
          if ($item.parent('li').next('li').length == 0) {
            $item.closest('ul').closest('li').children('a').first().focus();
          } else {
            $item.parent('li').next('li').find('a').first().focus();
          }
        }
      } else if (e.keyCode == 40) { //down arrow
        e.preventDefault();

        // This is the last item
        if ($item.parent('li').next('li').length == 0) {
          $item.closest('ul').closest('li').children('a').first().focus();
        } else {
          $item.parent('li').next('li').find('a').first().focus();
        }
      } else if (e.keyCode == 27 || e.keyCode == 37) { //escape key or left arrow => jump to the upper level links
        e.preventDefault();

        //focus on the upper level link
        $item.closest('ul').closest('li').children('a').first().focus();

      } else if (e.keyCode == 32) { //space key
        e.preventDefault();
        window.location = $item.attr('href');
      } else {

        //cycle through the menu items based on the first letter
        var found = false;
        $item.parent('li').nextAll('li').find('a').each(function () {
          if ($(this).text().substring(0, 1).toLowerCase() == keyCodeMap[e.keyCode]) {
            $(this).focus();
            found = true;
            return false;
          }
        });

        if (!found) {
          $item.parent('li').prevAll('li').find('a').each(function () {
            if ($(this).text().substring(0, 1).toLowerCase() == keyCodeMap[e.keyCode]) {
              $(this).focus();
              return false;
            }
          });
        }
      }
    });


    // Hide menu if click or focus occurs outside of navigation
    $nav.find('a').last().keydown(function (e) {
      if (e.keyCode == 9) { //tab key
        // If the user tabs out of the navigation hide all menus
        hideSubMenus();
      }
    });

    //close all menus when pressing ESC key
    $(document).keydown(function (e) {
      if (e.keyCode == 27) { //esc key
        hideSubMenus();
      }
    });

    //close all menus on click outside
    $(document).click(function () {
      hideSubMenus();
    });

    $nav.on('click touchstart', function (e) {
      e.stopPropagation();
    });

    $nav.find('.menu-item-has-children > a').on('touchstart', function (e) {

      var $item = $(this).parent();

      if (!$item.hasClass('hover')) {
        e.preventDefault();
        $item.addClass('hover');
        $item.siblings().removeClass('hover');
        return;
      } else {
        // $item.removeClass("hover");
      }

    });

    $('body').on('touchstart', function () {
      $('.menu-item-has-children').removeClass('hover');
    });

    function showSubMenu($item) {

      if ($item.hasClass('menu-item--mega')) {

        var $subMenu = $item.children('.sub-menu-wrapper'),
            offset, subMenuWidth;


        if ($subMenu.length) {

          subMenuWidth = $subMenu.outerWidth();

          // calculations for positioning the sub-menu
          var a = $item.index(),
              b = $nav.children().length,
              c = navWidth - subMenuWidth,
              x = (a - b / 2 + 1 / 2) * c / b + c / 2;

          $subMenu.css('left', x);
        }


      }

      $item.addClass(settings.topMenuHoverClass);

      $item.find('.sub-menu').first() //affect only the first ul found - the one with the submenus, ignore the mega menu items
      .attr('aria-hidden', 'false').addClass(settings.menuHoverClass);

      $item.find('a').attr('tabIndex', 0); //set the tabIndex to 0 so we let the browser figure out the tab order
    }

    function hideSubMenu($item) {

      if ($item.hasClass('menu-item--mega')) {
        $item.children('.sub-menu-wrapper').css('left', '');
      }

      $item.children('a').first().next('ul').attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);

      //when dealing with first level submenus - they are wrapped
      $item.children('a').first().next('.sub-menu-wrapper').children('ul').attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);

      $item.removeClass(settings.topMenuHoverClass);
    }

    function hideSubMenus() {

      $('.' + settings.menuHoverClass).attr('aria-hidden', 'true').removeClass(settings.menuHoverClass).find('a').attr('tabIndex', -1);

      $('.' + settings.topMenuHoverClass).removeClass(settings.topMenuHoverClass);

    }
  }

})(jQuery);
/*
 * debouncedresize: special jQuery event that happens once after a window resize
 *
 * latest version and complete README available on Github:
 * https://github.com/louisremi/jquery-smartresize
 *
 * Copyright 2012 @louis_remi
 * Licensed under the MIT license.
 *
 * This saved you an hour of work? 
 * Send me music http://www.amazon.co.uk/wishlist/HNTU0468LQON
 */
(function ($) {

  var $event = $.event,
      $special, resizeTimeout;

  $special = $event.special.debouncedresize = {
    setup: function () {
      $(this).on("resize", $special.handler);
    },
    teardown: function () {
      $(this).off("resize", $special.handler);
    },
    handler: function (event, execAsap) {
      // Save the context
      var context = this,
          args = arguments,
          dispatch = function () {
          // set correct event type
          event.type = "debouncedresize";
          $event.dispatch.apply(context, args);
          };

      if (resizeTimeout) {
        clearTimeout(resizeTimeout);
      }

      execAsap ? dispatch() : resizeTimeout = setTimeout(dispatch, $special.threshold);
    },
    threshold: 150
  };

})(jQuery); /*global jQuery */
/*!
* FitText.js 1.2
*
* Copyright 2011, Dave Rupert http://daverupert.com
* Released under the WTFPL license
* http://sam.zoy.org/wtfpl/
*
* Date: Thu May 05 14:23:00 2011 -0600
*/

(function ($) {

  $.fn.fitText = function (kompressor, options) {

    // Setup options
    var compressor = kompressor || 1,
        settings = $.extend({
        'minFontSize': Number.NEGATIVE_INFINITY,
        'maxFontSize': Number.POSITIVE_INFINITY
      }, options);

    return this.each(function () {

      // Store the object
      var $this = $(this);

      // Resizer() resizes items based on the object width divided by the compressor * 10
      var resizer = function () {
        $this.css('font-size', Math.max(Math.min($this.width() / (compressor * 10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
      };

      // Call once to set.
      resizer();

      // Call on resize. Opera debounces their resize by default.
      $(window).on('resize.fittext orientationchange.fittext', resizer);

    });

  };

})(jQuery);
// Magnific Popup v1.0.0 by Dmitry Semenov
// http://bit.ly/magnific-popup#build=image+gallery+retina+imagezoom+fastclick
(function (a) {
  typeof define == "function" && define.amd ? define(["jquery"], a) : typeof exports == "object" ? a(require("jquery")) : a(window.jQuery || window.Zepto)
})(function (a) {
  var b = "Close",
      c = "BeforeClose",
      d = "AfterClose",
      e = "BeforeAppend",
      f = "MarkupParse",
      g = "Open",
      h = "Change",
      i = "mfp",
      j = "." + i,
      k = "mfp-ready",
      l = "mfp-removing",
      m = "mfp-prevent-close",
      n, o = function () {},
      p = !! window.jQuery,
      q, r = a(window),
      s, t, u, v, w = function (a, b) {
      n.ev.on(i + a + j, b)
      },
      x = function (b, c, d, e) {
      var f = document.createElement("div");
      return f.className = "mfp-" + b, d && (f.innerHTML = d), e ? c && c.appendChild(f) : (f = a(f), c && f.appendTo(c)), f
      },
      y = function (b, c) {
      n.ev.triggerHandler(i + b, c), n.st.callbacks && (b = b.charAt(0).toLowerCase() + b.slice(1), n.st.callbacks[b] && n.st.callbacks[b].apply(n, a.isArray(c) ? c : [c]))
      },
      z = function (b) {
      if (b !== v || !n.currTemplate.closeBtn) n.currTemplate.closeBtn = a(n.st.closeMarkup.replace("%title%", n.st.tClose)), v = b;
      return n.currTemplate.closeBtn
      },
      A = function () {
      a.magnificPopup.instance || (n = new o, n.init(), a.magnificPopup.instance = n)
      },
      B = function () {
      var a = document.createElement("p").style,
          b = ["ms", "O", "Moz", "Webkit"];
      if (a.transition !== undefined) return !0;
      while (b.length) if (b.pop() + "Transition" in a) return !0;
      return !1
      };
  o.prototype = {
    constructor: o,
    init: function () {
      var b = navigator.appVersion;
      n.isIE7 = b.indexOf("MSIE 7.") !== -1, n.isIE8 = b.indexOf("MSIE 8.") !== -1, n.isLowIE = n.isIE7 || n.isIE8, n.isAndroid = /android/gi.test(b), n.isIOS = /iphone|ipad|ipod/gi.test(b), n.supportsTransition = B(), n.probablyMobile = n.isAndroid || n.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent), s = a(document), n.popupsCache = {}
    },
    open: function (b) {
      var c;
      if (b.isObj === !1) {
        n.items = b.items.toArray(), n.index = 0;
        var d = b.items,
            e;
        for (c = 0; c < d.length; c++) {
          e = d[c], e.parsed && (e = e.el[0]);
          if (e === b.el[0]) {
            n.index = c;
            break
          }
        }
      } else n.items = a.isArray(b.items) ? b.items : [b.items], n.index = b.index || 0;
      if (n.isOpen) {
        n.updateItemHTML();
        return
      }
      n.types = [], u = "", b.mainEl && b.mainEl.length ? n.ev = b.mainEl.eq(0) : n.ev = s, b.key ? (n.popupsCache[b.key] || (n.popupsCache[b.key] = {}), n.currTemplate = n.popupsCache[b.key]) : n.currTemplate = {}, n.st = a.extend(!0, {}, a.magnificPopup.defaults, b), n.fixedContentPos = n.st.fixedContentPos === "auto" ? !n.probablyMobile : n.st.fixedContentPos, n.st.modal && (n.st.closeOnContentClick = !1, n.st.closeOnBgClick = !1, n.st.showCloseBtn = !1, n.st.enableEscapeKey = !1), n.bgOverlay || (n.bgOverlay = x("bg").on("click" + j, function () {
        n.close()
      }), n.wrap = x("wrap").attr("tabindex", -1).on("click" + j, function (a) {
        n._checkIfClose(a.target) && n.close()
      }), n.container = x("container", n.wrap)), n.contentContainer = x("content"), n.st.preloader && (n.preloader = x("preloader", n.container, n.st.tLoading));
      var h = a.magnificPopup.modules;
      for (c = 0; c < h.length; c++) {
        var i = h[c];
        i = i.charAt(0).toUpperCase() + i.slice(1), n["init" + i].call(n)
      }
      y("BeforeOpen"), n.st.showCloseBtn && (n.st.closeBtnInside ? (w(f, function (a, b, c, d) {
        c.close_replaceWith = z(d.type)
      }), u += " mfp-close-btn-in") : n.wrap.append(z())), n.st.alignTop && (u += " mfp-align-top"), n.fixedContentPos ? n.wrap.css({
        overflow: n.st.overflowY,
        overflowX: "hidden",
        overflowY: n.st.overflowY
      }) : n.wrap.css({
        top: r.scrollTop(),
        position: "absolute"
      }), (n.st.fixedBgPos === !1 || n.st.fixedBgPos === "auto" && !n.fixedContentPos) && n.bgOverlay.css({
        height: s.height(),
        position: "absolute"
      }), n.st.enableEscapeKey && s.on("keyup" + j, function (a) {
        a.keyCode === 27 && n.close()
      }), r.on("resize" + j, function () {
        n.updateSize()
      }), n.st.closeOnContentClick || (u += " mfp-auto-cursor"), u && n.wrap.addClass(u);
      var l = n.wH = r.height(),
          m = {};
      if (n.fixedContentPos && n._hasScrollBar(l)) {
        var o = n._getScrollbarSize();
        o && (m.marginRight = o)
      }
      n.fixedContentPos && (n.isIE7 ? a("body, html").css("overflow", "hidden") : m.overflow = "hidden");
      var p = n.st.mainClass;
      return n.isIE7 && (p += " mfp-ie7"), p && n._addClassToMFP(p), n.updateItemHTML(), y("BuildControls"), a("html").css(m), n.bgOverlay.add(n.wrap).prependTo(n.st.prependTo || a(document.body)), n._lastFocusedEl = document.activeElement, setTimeout(function () {
        n.content ? (n._addClassToMFP(k), n._setFocus()) : n.bgOverlay.addClass(k), s.on("focusin" + j, n._onFocusIn)
      }, 16), n.isOpen = !0, n.updateSize(l), y(g), b
    },
    close: function () {
      if (!n.isOpen) return;
      y(c), n.isOpen = !1, n.st.removalDelay && !n.isLowIE && n.supportsTransition ? (n._addClassToMFP(l), setTimeout(function () {
        n._close()
      }, n.st.removalDelay)) : n._close()
    },
    _close: function () {
      y(b);
      var c = l + " " + k + " ";
      n.bgOverlay.detach(), n.wrap.detach(), n.container.empty(), n.st.mainClass && (c += n.st.mainClass + " "), n._removeClassFromMFP(c);
      if (n.fixedContentPos) {
        var e = {
          marginRight: ""
        };
        n.isIE7 ? a("body, html").css("overflow", "") : e.overflow = "", a("html").css(e)
      }
      s.off("keyup" + j + " focusin" + j), n.ev.off(j), n.wrap.attr("class", "mfp-wrap").removeAttr("style"), n.bgOverlay.attr("class", "mfp-bg"), n.container.attr("class", "mfp-container"), n.st.showCloseBtn && (!n.st.closeBtnInside || n.currTemplate[n.currItem.type] === !0) && n.currTemplate.closeBtn && n.currTemplate.closeBtn.detach(), n._lastFocusedEl && a(n._lastFocusedEl).focus(), n.currItem = null, n.content = null, n.currTemplate = null, n.prevHeight = 0, y(d)
    },
    updateSize: function (a) {
      if (n.isIOS) {
        var b = document.documentElement.clientWidth / window.innerWidth,
            c = window.innerHeight * b;
        n.wrap.css("height", c), n.wH = c
      } else n.wH = a || r.height();
      n.fixedContentPos || n.wrap.css("height", n.wH), y("Resize")
    },
    updateItemHTML: function () {
      var b = n.items[n.index];
      n.contentContainer.detach(), n.content && n.content.detach(), b.parsed || (b = n.parseEl(n.index));
      var c = b.type;
      y("BeforeChange", [n.currItem ? n.currItem.type : "", c]), n.currItem = b;
      if (!n.currTemplate[c]) {
        var d = n.st[c] ? n.st[c].markup : !1;
        y("FirstMarkupParse", d), d ? n.currTemplate[c] = a(d) : n.currTemplate[c] = !0
      }
      t && t !== b.type && n.container.removeClass("mfp-" + t + "-holder");
      var e = n["get" + c.charAt(0).toUpperCase() + c.slice(1)](b, n.currTemplate[c]);
      n.appendContent(e, c), b.preloaded = !0, y(h, b), t = b.type, n.container.prepend(n.contentContainer), y("AfterChange")
    },
    appendContent: function (a, b) {
      n.content = a, a ? n.st.showCloseBtn && n.st.closeBtnInside && n.currTemplate[b] === !0 ? n.content.find(".mfp-close").length || n.content.append(z()) : n.content = a : n.content = "", y(e), n.container.addClass("mfp-" + b + "-holder"), n.contentContainer.append(n.content)
    },
    parseEl: function (b) {
      var c = n.items[b],
          d;
      c.tagName ? c = {
        el: a(c)
      } : (d = c.type, c = {
        data: c,
        src: c.src
      });
      if (c.el) {
        var e = n.types;
        for (var f = 0; f < e.length; f++) if (c.el.hasClass("mfp-" + e[f])) {
          d = e[f];
          break
        }
        c.src = c.el.attr("data-mfp-src"), c.src || (c.src = c.el.attr("href"))
      }
      return c.type = d || n.st.type || "inline", c.index = b, c.parsed = !0, n.items[b] = c, y("ElementParse", c), n.items[b]
    },
    addGroup: function (a, b) {
      var c = function (c) {
        c.mfpEl = this, n._openClick(c, a, b)
      };
      b || (b = {});
      var d = "click.magnificPopup";
      b.mainEl = a, b.items ? (b.isObj = !0, a.off(d).on(d, c)) : (b.isObj = !1, b.delegate ? a.off(d).on(d, b.delegate, c) : (b.items = a, a.off(d).on(d, c)))
    },
    _openClick: function (b, c, d) {
      var e = d.midClick !== undefined ? d.midClick : a.magnificPopup.defaults.midClick;
      if (!e && (b.which === 2 || b.ctrlKey || b.metaKey)) return;
      var f = d.disableOn !== undefined ? d.disableOn : a.magnificPopup.defaults.disableOn;
      if (f) if (a.isFunction(f)) {
        if (!f.call(n)) return !0
      } else if (r.width() < f) return !0;
      b.type && (b.preventDefault(), n.isOpen && b.stopPropagation()), d.el = a(b.mfpEl), d.delegate && (d.items = c.find(d.delegate)), n.open(d)
    },
    updateStatus: function (a, b) {
      if (n.preloader) {
        q !== a && n.container.removeClass("mfp-s-" + q), !b && a === "loading" && (b = n.st.tLoading);
        var c = {
          status: a,
          text: b
        };
        y("UpdateStatus", c), a = c.status, b = c.text, n.preloader.html(b), n.preloader.find("a").on("click", function (a) {
          a.stopImmediatePropagation()
        }), n.container.addClass("mfp-s-" + a), q = a
      }
    },
    _checkIfClose: function (b) {
      if (a(b).hasClass(m)) return;
      var c = n.st.closeOnContentClick,
          d = n.st.closeOnBgClick;
      if (c && d) return !0;
      if (!n.content || a(b).hasClass("mfp-close") || n.preloader && b === n.preloader[0]) return !0;
      if (b !== n.content[0] && !a.contains(n.content[0], b)) {
        if (d && a.contains(document, b)) return !0
      } else if (c) return !0;
      return !1
    },
    _addClassToMFP: function (a) {
      n.bgOverlay.addClass(a), n.wrap.addClass(a)
    },
    _removeClassFromMFP: function (a) {
      this.bgOverlay.removeClass(a), n.wrap.removeClass(a)
    },
    _hasScrollBar: function (a) {
      return (n.isIE7 ? s.height() : document.body.scrollHeight) > (a || r.height())
    },
    _setFocus: function () {
      (n.st.focus ? n.content.find(n.st.focus).eq(0) : n.wrap).focus()
    },
    _onFocusIn: function (b) {
      if (b.target !== n.wrap[0] && !a.contains(n.wrap[0], b.target)) return n._setFocus(), !1
    },
    _parseMarkup: function (b, c, d) {
      var e;
      d.data && (c = a.extend(d.data, c)), y(f, [b, c, d]), a.each(c, function (a, c) {
        if (c === undefined || c === !1) return !0;
        e = a.split("_");
        if (e.length > 1) {
          var d = b.find(j + "-" + e[0]);
          if (d.length > 0) {
            var f = e[1];
            f === "replaceWith" ? d[0] !== c[0] && d.replaceWith(c) : f === "img" ? d.is("img") ? d.attr("src", c) : d.replaceWith('<img src="' + c + '" class="' + d.attr("class") + '" />') : d.attr(e[1], c)
          }
        } else b.find(j + "-" + a).html(c)
      })
    },
    _getScrollbarSize: function () {
      if (n.scrollbarSize === undefined) {
        var a = document.createElement("div");
        a.style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;", document.body.appendChild(a), n.scrollbarSize = a.offsetWidth - a.clientWidth, document.body.removeChild(a)
      }
      return n.scrollbarSize
    }
  }, a.magnificPopup = {
    instance: null,
    proto: o.prototype,
    modules: [],
    open: function (b, c) {
      return A(), b ? b = a.extend(!0, {}, b) : b = {}, b.isObj = !0, b.index = c || 0, this.instance.open(b)
    },
    close: function () {
      return a.magnificPopup.instance && a.magnificPopup.instance.close()
    },
    registerModule: function (b, c) {
      c.options && (a.magnificPopup.defaults[b] = c.options), a.extend(this.proto, c.proto), this.modules.push(b)
    },
    defaults: {
      disableOn: 0,
      key: null,
      midClick: !1,
      mainClass: "",
      preloader: !0,
      focus: "",
      closeOnContentClick: !1,
      closeOnBgClick: !0,
      closeBtnInside: !0,
      showCloseBtn: !0,
      enableEscapeKey: !0,
      modal: !1,
      alignTop: !1,
      removalDelay: 0,
      prependTo: null,
      fixedContentPos: "auto",
      fixedBgPos: "auto",
      overflowY: "auto",
      closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',
      tClose: "Close (Esc)",
      tLoading: "Loading..."
    }
  }, a.fn.magnificPopup = function (b) {
    A();
    var c = a(this);
    if (typeof b == "string") if (b === "open") {
      var d, e = p ? c.data("magnificPopup") : c[0].magnificPopup,
          f = parseInt(arguments[1], 10) || 0;
      e.items ? d = e.items[f] : (d = c, e.delegate && (d = d.find(e.delegate)), d = d.eq(f)), n._openClick({
        mfpEl: d
      }, c, e)
    } else n.isOpen && n[b].apply(n, Array.prototype.slice.call(arguments, 1));
    else b = a.extend(!0, {}, b), p ? c.data("magnificPopup", b) : c[0].magnificPopup = b, n.addGroup(c, b);
    return c
  };
  var C, D = function (b) {
    if (b.data && b.data.title !== undefined) return b.data.title;
    var c = n.st.image.titleSrc;
    if (c) {
      if (a.isFunction(c)) return c.call(n, b);
      if (b.el) return b.el.attr(c) || ""
    }
    return ""
  };
  a.magnificPopup.registerModule("image", {
    options: {
      markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
      cursor: "mfp-zoom-out-cur",
      titleSrc: "title",
      verticalFit: !0,
      tError: '<a href="%url%">The image</a> could not be loaded.'
    },
    proto: {
      initImage: function () {
        var c = n.st.image,
            d = ".image";
        n.types.push("image"), w(g + d, function () {
          n.currItem.type === "image" && c.cursor && a(document.body).addClass(c.cursor)
        }), w(b + d, function () {
          c.cursor && a(document.body).removeClass(c.cursor), r.off("resize" + j)
        }), w("Resize" + d, n.resizeImage), n.isLowIE && w("AfterChange", n.resizeImage)
      },
      resizeImage: function () {
        var a = n.currItem;
        if (!a || !a.img) return;
        if (n.st.image.verticalFit) {
          var b = 0;
          n.isLowIE && (b = parseInt(a.img.css("padding-top"), 10) + parseInt(a.img.css("padding-bottom"), 10)), a.img.css("max-height", n.wH - b)
        }
      },
      _onImageHasSize: function (a) {
        a.img && (a.hasSize = !0, C && clearInterval(C), a.isCheckingImgSize = !1, y("ImageHasSize", a), a.imgHidden && (n.content && n.content.removeClass("mfp-loading"), a.imgHidden = !1))
      },
      findImageSize: function (a) {
        var b = 0,
            c = a.img[0],
            d = function (e) {
            C && clearInterval(C), C = setInterval(function () {
              if (c.naturalWidth > 0) {
                n._onImageHasSize(a);
                return
              }
              b > 200 && clearInterval(C), b++, b === 3 ? d(10) : b === 40 ? d(50) : b === 100 && d(500)
            }, e)
            };
        d(1)
      },
      getImage: function (b, c) {
        var d = 0,
            e = function () {
            b && (b.img[0].complete ? (b.img.off(".mfploader"), b === n.currItem && (n._onImageHasSize(b), n.updateStatus("ready")), b.hasSize = !0, b.loaded = !0, y("ImageLoadComplete")) : (d++, d < 200 ? setTimeout(e, 100) : f()))
            },
            f = function () {
            b && (b.img.off(".mfploader"), b === n.currItem && (n._onImageHasSize(b), n.updateStatus("error", g.tError.replace("%url%", b.src))), b.hasSize = !0, b.loaded = !0, b.loadError = !0)
            },
            g = n.st.image,
            h = c.find(".mfp-img");
        if (h.length) {
          var i = document.createElement("img");
          i.className = "mfp-img", b.el && b.el.find("img").length && (i.alt = b.el.find("img").attr("alt")), b.img = a(i).on("load.mfploader", e).on("error.mfploader", f), i.src = b.src, h.is("img") && (b.img = b.img.clone()), i = b.img[0], i.naturalWidth > 0 ? b.hasSize = !0 : i.width || (b.hasSize = !1)
        }
        return n._parseMarkup(c, {
          title: D(b),
          img_replaceWith: b.img
        }, b), n.resizeImage(), b.hasSize ? (C && clearInterval(C), b.loadError ? (c.addClass("mfp-loading"), n.updateStatus("error", g.tError.replace("%url%", b.src))) : (c.removeClass("mfp-loading"), n.updateStatus("ready")), c) : (n.updateStatus("loading"), b.loading = !0, b.hasSize || (b.imgHidden = !0, c.addClass("mfp-loading"), n.findImageSize(b)), c)
      }
    }
  });
  var E, F = function () {
    return E === undefined && (E = document.createElement("p").style.MozTransform !== undefined), E
  };
  a.magnificPopup.registerModule("zoom", {
    options: {
      enabled: !1,
      easing: "ease-in-out",
      duration: 300,
      opener: function (a) {
        return a.is("img") ? a : a.find("img")
      }
    },
    proto: {
      initZoom: function () {
        var a = n.st.zoom,
            d = ".zoom",
            e;
        if (!a.enabled || !n.supportsTransition) return;
        var f = a.duration,
            g = function (b) {
            var c = b.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image"),
                d = "all " + a.duration / 1e3 + "s " + a.easing,
                e = {
                position: "fixed",
                zIndex: 9999,
                left: 0,
                top: 0,
                "-webkit-backface-visibility": "hidden"
                },
                f = "transition";
            return e["-webkit-" + f] = e["-moz-" + f] = e["-o-" + f] = e[f] = d, c.css(e), c
            },
            h = function () {
            n.content.css("visibility", "visible")
            },
            i, j;
        w("BuildControls" + d, function () {
          if (n._allowZoom()) {
            clearTimeout(i), n.content.css("visibility", "hidden"), e = n._getItemToZoom();
            if (!e) {
              h();
              return
            }
            j = g(e), j.css(n._getOffset()), n.wrap.append(j), i = setTimeout(function () {
              j.css(n._getOffset(!0)), i = setTimeout(function () {
                h(), setTimeout(function () {
                  j.remove(), e = j = null, y("ZoomAnimationEnded")
                }, 16)
              }, f)
            }, 16)
          }
        }), w(c + d, function () {
          if (n._allowZoom()) {
            clearTimeout(i), n.st.removalDelay = f;
            if (!e) {
              e = n._getItemToZoom();
              if (!e) return;
              j = g(e)
            }
            j.css(n._getOffset(!0)), n.wrap.append(j), n.content.css("visibility", "hidden"), setTimeout(function () {
              j.css(n._getOffset())
            }, 16)
          }
        }), w(b + d, function () {
          n._allowZoom() && (h(), j && j.remove(), e = null)
        })
      },
      _allowZoom: function () {
        return n.currItem.type === "image"
      },
      _getItemToZoom: function () {
        return n.currItem.hasSize ? n.currItem.img : !1
      },
      _getOffset: function (b) {
        var c;
        b ? c = n.currItem.img : c = n.st.zoom.opener(n.currItem.el || n.currItem);
        var d = c.offset(),
            e = parseInt(c.css("padding-top"), 10),
            f = parseInt(c.css("padding-bottom"), 10);
        d.top -= a(window).scrollTop() - e;
        var g = {
          width: c.width(),
          height: (p ? c.innerHeight() : c[0].offsetHeight) - f - e
        };
        return F() ? g["-moz-transform"] = g.transform = "translate(" + d.left + "px," + d.top + "px)" : (g.left = d.left, g.top = d.top), g
      }
    }
  });
  var G = function (a) {
    var b = n.items.length;
    return a > b - 1 ? a - b : a < 0 ? b + a : a
  },
      H = function (a, b, c) {
      return a.replace(/%curr%/gi, b + 1).replace(/%total%/gi, c)
      };
  a.magnificPopup.registerModule("gallery", {
    options: {
      enabled: !1,
      arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
      preload: [0, 2],
      navigateByImgClick: !0,
      arrows: !0,
      tPrev: "Previous (Left arrow key)",
      tNext: "Next (Right arrow key)",
      tCounter: "%curr% of %total%"
    },
    proto: {
      initGallery: function () {
        var c = n.st.gallery,
            d = ".mfp-gallery",
            e = Boolean(a.fn.mfpFastClick);
        n.direction = !0;
        if (!c || !c.enabled) return !1;
        u += " mfp-gallery", w(g + d, function () {
          c.navigateByImgClick && n.wrap.on("click" + d, ".mfp-img", function () {
            if (n.items.length > 1) return n.next(), !1
          }), s.on("keydown" + d, function (a) {
            a.keyCode === 37 ? n.prev() : a.keyCode === 39 && n.next()
          })
        }), w("UpdateStatus" + d, function (a, b) {
          b.text && (b.text = H(b.text, n.currItem.index, n.items.length))
        }), w(f + d, function (a, b, d, e) {
          var f = n.items.length;
          d.counter = f > 1 ? H(c.tCounter, e.index, f) : ""
        }), w("BuildControls" + d, function () {
          if (n.items.length > 1 && c.arrows && !n.arrowLeft) {
            var b = c.arrowMarkup,
                d = n.arrowLeft = a(b.replace(/%title%/gi, c.tPrev).replace(/%dir%/gi, "left")).addClass(m),
                f = n.arrowRight = a(b.replace(/%title%/gi, c.tNext).replace(/%dir%/gi, "right")).addClass(m),
                g = e ? "mfpFastClick" : "click";
            d[g](function () {
              n.prev()
            }), f[g](function () {
              n.next()
            }), n.isIE7 && (x("b", d[0], !1, !0), x("a", d[0], !1, !0), x("b", f[0], !1, !0), x("a", f[0], !1, !0)), n.container.append(d.add(f))
          }
        }), w(h + d, function () {
          n._preloadTimeout && clearTimeout(n._preloadTimeout), n._preloadTimeout = setTimeout(function () {
            n.preloadNearbyImages(), n._preloadTimeout = null
          }, 16)
        }), w(b + d, function () {
          s.off(d), n.wrap.off("click" + d), n.arrowLeft && e && n.arrowLeft.add(n.arrowRight).destroyMfpFastClick(), n.arrowRight = n.arrowLeft = null
        })
      },
      next: function () {
        n.direction = !0, n.index = G(n.index + 1), n.updateItemHTML()
      },
      prev: function () {
        n.direction = !1, n.index = G(n.index - 1), n.updateItemHTML()
      },
      goTo: function (a) {
        n.direction = a >= n.index, n.index = a, n.updateItemHTML()
      },
      preloadNearbyImages: function () {
        var a = n.st.gallery.preload,
            b = Math.min(a[0], n.items.length),
            c = Math.min(a[1], n.items.length),
            d;
        for (d = 1; d <= (n.direction ? c : b); d++) n._preloadItem(n.index + d);
        for (d = 1; d <= (n.direction ? b : c); d++) n._preloadItem(n.index - d)
      },
      _preloadItem: function (b) {
        b = G(b);
        if (n.items[b].preloaded) return;
        var c = n.items[b];
        c.parsed || (c = n.parseEl(b)), y("LazyLoad", c), c.type === "image" && (c.img = a('<img class="mfp-img" />').on("load.mfploader", function () {
          c.hasSize = !0
        }).on("error.mfploader", function () {
          c.hasSize = !0, c.loadError = !0, y("LazyLoadError", c)
        }).attr("src", c.src)), c.preloaded = !0
      }
    }
  });
  var I = "retina";
  a.magnificPopup.registerModule(I, {
    options: {
      replaceSrc: function (a) {
        return a.src.replace(/\.\w+$/, function (a) {
          return "@2x" + a
        })
      },
      ratio: 1
    },
    proto: {
      initRetina: function () {
        if (window.devicePixelRatio > 1) {
          var a = n.st.retina,
              b = a.ratio;
          b = isNaN(b) ? b() : b, b > 1 && (w("ImageHasSize." + I, function (a, c) {
            c.img.css({
              "max-width": c.img[0].naturalWidth / b,
              width: "100%"
            })
          }), w("ElementParse." + I, function (c, d) {
            d.src = a.replaceSrc(d, b)
          }))
        }
      }
    }
  }), function () {
    var b = 1e3,
        c = "ontouchstart" in window,
        d = function () {
        r.off("touchmove" + f + " touchend" + f)
        },
        e = "mfpFastClick",
        f = "." + e;
    a.fn.mfpFastClick = function (e) {
      return a(this).each(function () {
        var g = a(this),
            h;
        if (c) {
          var i, j, k, l, m, n;
          g.on("touchstart" + f, function (a) {
            l = !1, n = 1, m = a.originalEvent ? a.originalEvent.touches[0] : a.touches[0], j = m.clientX, k = m.clientY, r.on("touchmove" + f, function (a) {
              m = a.originalEvent ? a.originalEvent.touches : a.touches, n = m.length, m = m[0];
              if (Math.abs(m.clientX - j) > 10 || Math.abs(m.clientY - k) > 10) l = !0, d()
            }).on("touchend" + f, function (a) {
              d();
              if (l || n > 1) return;
              h = !0, a.preventDefault(), clearTimeout(i), i = setTimeout(function () {
                h = !1
              }, b), e()
            })
          })
        }
        g.on("click" + f, function () {
          h || e()
        })
      })
    }, a.fn.destroyMfpFastClick = function () {
      a(this).off("touchstart" + f + " click" + f), c && r.off("touchmove" + f + " touchend" + f)
    }
  }(), A()
})
/**
 * requestAnimationFrame polyfill by Erik Möller.
 * Fixes from Paul Irish, Tino Zijdel, Andrew Mao, Klemen Slavič, Darius Bacon
 *
 * MIT license
 */
if (!Date.now) Date.now = function () {
  return new Date().getTime();
};

(function () {
  'use strict';

  var vendors = ['webkit', 'moz'];
  for (var i = 0; i < vendors.length && !window.requestAnimationFrame; ++i) {
    var vp = vendors[i];
    window.requestAnimationFrame = window[vp + 'RequestAnimationFrame'];
    window.cancelAnimationFrame = (window[vp + 'CancelAnimationFrame'] || window[vp + 'CancelRequestAnimationFrame']);
  }
  if (/iP(ad|hone|od).*OS 6/.test(window.navigator.userAgent) // iOS6 is buggy
  || !window.requestAnimationFrame || !window.cancelAnimationFrame) {
    var lastTime = 0;
    window.requestAnimationFrame = function (callback) {
      var now = Date.now();
      var nextTime = Math.max(lastTime + 16, now);
      return setTimeout(function () {
        callback(lastTime = nextTime);
      }, nextTime - now);
    };
    window.cancelAnimationFrame = clearTimeout;
  }
}());
(function ($, undefined) {
  /**
   * Shared variables
   */
  var ua = navigator.userAgent.toLowerCase(),
      platform = navigator.platform.toLowerCase(),
      $window = $(window),
      $document = $(document),
      $html = $('html'),
      $body = $('body'),
      
      
      iphone = platform.indexOf("iphone"),
      ipod = platform.indexOf("ipod"),
      android = platform.indexOf("android"),
      android_ancient = (ua.indexOf('mozilla/5.0') !== -1 && ua.indexOf('android') !== -1 && ua.indexOf('applewebKit') !== -1) && ua.indexOf('chrome') === -1,
      apple = ua.match(/(iPad|iPhone|iPod|Macintosh)/i),
      windows_phone = ua.indexOf('windows phone') != -1,
      webkit = ua.indexOf('webkit') != -1,
      
      
      firefox = ua.indexOf('gecko') != -1,
      firefox_3x = firefox && ua.match(/rv:1.9/i),
      ie = ua.indexOf('msie' != -1),
      ie_newer = ua.match(/msie (9|([1-9][0-9]))/i),
      ie_older = ie && !ie_newer,
      ie_ancient = ua.indexOf('msie 6') != -1,
      safari = ua.indexOf('safari') != -1 && ua.indexOf('chrome') == -1,
      
      
      is_small = $('.js-nav-trigger').is(':visible');

  windowHeight = $window.height(), windowWidth = $window.width(), documentHeight = $document.height(),

  latestKnownScrollY = window.scrollY, ticking = false;

  ; /* --- Magnific Popup Initialization --- */

  function magnificPopupInit() {
    $('.entry-content').each(function () { // the containers for all your galleries should have the class gallery
      $(this).magnificPopup({
        delegate: 'a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]',
        // the container for each your gallery items
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: false,
        removalDelay: 500,
        mainClass: 'mfp-fade',
        image: {
          markup: '<div class="mfp-figure">' + '<div class="mfp-img"></div>' + '<div class="mfp-bottom-bar">' + '<div class="mfp-title"></div>' + '<div class="mfp-counter"></div>' + '</div>' + '</div>',
          titleSrc: function (item) {
            var output = '';
            if (typeof item.el.attr('data-alt') !== "undefined" && item.el.attr('data-alt') !== "") {
              output += '<small>' + item.el.attr('data-alt') + '</small>';
            }
            return output;
          }
        },
        gallery: {
          enabled: true,
          navigateByImgClick: true
          //arrowMarkup: '<a href="#" class="gallery-arrow gallery-arrow--%dir% control-item arrow-button arrow-button--%dir%">%dir%</a>'
        }
        //callbacks: {
        //    elementParse: function (item) {
        //
        //        if (this.currItem != undefined) {
        //            item = this.currItem;
        //        }
        //
        //        var output = '';
        //        if (typeof item.el.attr('data-alt') !== "undefined" && item.el.attr('data-alt') !== "") {
        //            output += '<small>' + item.el.attr('data-alt') + '</small>';
        //        }
        //
        //        $('.mfp-title').html(output);
        //    },
        //    change: function (item) {
        //        var output = '';
        //        if (typeof item.el.attr('data-alt') !== "undefined" && item.el.attr('data-alt') !== "") {
        //            output += '<small>' + item.el.attr('data-alt') + '</small>';
        //        }
        //
        //        $('.mfp-title').html(output);
        //    }
        //}
      });
    });
  }

  /* ====== Masonry Logic ====== */

  var masonry = (function () {

    var $container = $('.grid'),
        
        
        // $sidebar		= $('.sidebar--main'),
        $blocks = $container.children().addClass('post--animated  post--loaded'),
        initialized = false,
        columns = 1,
        containerTop, containerBottom,
        
        // sidebarTop,
        
        init = function () {

        if (windowWidth < 800) {
          $container.imagesLoaded(function () {
            showBlocks($blocks);
          });
        }

        if ($container.length) {
          containerTop = $container.offset().top;
          containerBottom = containerTop + $container.outerHeight();
        }

        $container.imagesLoaded(function () {
          $container.masonry({
            itemSelector: '.grid__item',
            columnWidth: ".grid__item:not(.site-header)",
            transitionDuration: 0
          });
          bindEvents();
          onLayout();

          setTimeout(function () {
            $container.masonry('layout');
          }, 100);

          showBlocks($blocks);

          initialized = true;
        });
        },
        
        
        unbindEvents = function () {
        $body.off('post-load');
        $container.masonry('off', 'layoutComplete', onLayout);
        },
        
        
        bindEvents = function () {
        $body.on('post-load', onLoad);
        $container.masonry('on', 'layoutComplete', onLayout);
        },
        
        
        refresh = function () {

        if (!initialized) {
          init();
          return;
        }

        if (windowWidth < 800) {
          $container.masonry('destroy');
          initialized = false;
          init();
          return;
        }

        $container.masonry('layout');
        },
        
        
        showBlocks = function ($blocks) {
        $blocks.each(function (i, obj) {
          var $post = $(obj).find('.entry-card, .site-header, .page-header');

          // if ($post.find('.entry-image--portrait').length) {
          // 	$post.addClass('entry-card--portrait');
          // }
          // if ($post.find('.entry-image--tall').length) {
          // 	$post.addClass('entry-card--tall');
          // }
          animatePost($post, i * 100);
        });
        },
        
        
        animatePost = function ($post, delay) {
        // $post.velocity({
        // 	opacity: 1
        // }, {
        // 	duration: 300,
        // 	delay: delay,
        // 	easing: 'easeOutCubic'
        // });
        setTimeout(function () {
          $post.addClass('is-visible');
        }, delay);
        },
        
        
        onLayout = function () {

        var values = new Array(),
            newValues = new Array();

        // get left value for each item in the grid
        $container.find('.grid__item').each(function (i, obj) {
          values.push($(obj).offset().left);
        });

        // get unique values representing columns' left offset
        values = values.getUnique(values);

        // keep only the even ones so we can identify what columns need new css classes
        for (var k in values) {
          if (values.hasOwnProperty(k) && k % 2 == 0) {
            newValues.push(values[k]);
          }
        }

        $container.find('.grid__item').each(function (i, obj) {
          var $obj = $(obj),
              left = $obj.offset().left;

          $obj.css('z-index', values.length - values.indexOf(left));

          if (newValues.indexOf(left) != -1) {
            $obj.addClass('entry--even');
          } else {
            $obj.removeClass('entry--even');
          }
        });

        unbindEvents();
        $container.masonry('layout');
        bindEvents();

        setTimeout(function () {
          shadows.init();
        }, 200);
        },
        
        
        onLoad = function () {
        var $newBlocks = $container.children().not('.post--loaded').addClass('post--loaded');
        $newBlocks.imagesLoaded(function () {
          $container.masonry('appended', $newBlocks, true).masonry('layout');
          showBlocks($newBlocks);
        });
        };

    return {
      init: init,
      refresh: refresh
    }

  })();

  /**
   * cardHover jQuery plugin
   *
   * we need to create a jQuery plugin so we can easily create the hover animations on the archive
   * both an window.load and on jetpack's infinite scroll 'post-load'
   */
  $.fn.addHoverAnimation = function () {

    return this.each(function (i, obj) {

      var $obj = $(obj),
          $otherShadow = $obj.find('.entry-image-shadow'),
          $hisShadow = $obj.data('shadow'),
          $meta = $obj.find('.entry-meta'),
          options = {
          duration: 300,
          easing: 'easeOutQuad',
          queue: false
          };

      $obj.off('mouseenter').on('mouseenter', function () {
        $obj.velocity({
          translateY: 15
        }, options);

        $otherShadow.velocity({
          translateY: -15
        }, options);

        $meta.velocity({
          translateY: '-100%',
          opacity: 1
        }, options);

        if (typeof $hisShadow !== "undefined") {
          $hisShadow.velocity({
            translateY: 15
          }, options);
        }
      });

      $obj.off('mouseleave').on('mouseleave', function () {

        $obj.velocity({
          translateY: 0
        }, options);

        $otherShadow.velocity({
          translateY: 0
        }, options);

        $meta.velocity({
          translateY: 0,
          opacity: ''
        }, options);

        if (typeof $hisShadow !== "undefined") {
          $hisShadow.velocity({
            translateY: 0
          }, options);
        }
      });

    });

  }

  Array.prototype.getUnique = function () {
    var u = {},
        a = [];
    for (var i = 0, l = this.length; i < l; ++i) {
      if (u.hasOwnProperty(this[i])) {
        continue;
      }
      a.push(this[i]);
      u[this[i]] = 1;
    }
    return a;
  } /* ====== Navigation Logic ====== */

  var navigation = (function () {

    var $nav = $('.nav--main'),
        
        
        init = function () {
        // initialize the logic behind the main navigation
        // $nav.ariaNavigation();
        mobileNav();
        },
        
        
        mobileNav = function () {
        var $nav = $('.main-navigation'),
            $navTrigger = $('.js-nav-trigger'),
            isOpen = false,
            sticked = false;

        /**
         * bind toggling the navigation drawer to click and touchstart
         *in order to get rid of the 300ms delay on touch devices we use the touchstart event
         */
        var triggerEvents = 'click touchstart';

        if (android_ancient) triggerEvents = 'click';

        var $navParent = $nav.parent();

        $navTrigger.on(triggerEvents, function (e) {

          // but we still have to prevent the default behavior of the touchstart event
          // because this way we're making sure the click event won't fire anymore
          e.preventDefault();
          e.stopPropagation();

          isOpen = !isOpen;
          // $('body').toggleClass('nav--is-open');
          var offset;

          navWidth = $nav.outerWidth();

          if ($('body').hasClass('rtl')) {
            offset = -1 * navWidth;
          } else {
            offset = navWidth;
          }

          if (!android_ancient) {
            if (!isOpen) {

              $([$nav, $navTrigger]).each(function (i, obj) {
                $(obj).velocity({
                  translateX: 0,
                  translateZ: 0.01
                }, {
                  duration: 300,
                  easing: "easeInQuart",
                  complete: function () {
                    $nav.appendTo($navParent);
                  }
                });
              });

            } else {

              $nav.insertAfter($navTrigger);

              $([$nav, $navTrigger]).each(function (i, obj) {
                $(obj).velocity({
                  translateX: offset,
                  translateZ: 0.01
                }, {
                  easing: "easeOutCubic",
                  duration: 300
                });
              });

            }

            $nav.toggleClass('shadow', isOpen);
          }
        });

        };

    return {
      init: init
    }

  })();
  // /* ====== Search Overlay Logic ====== */
  (function () {

    var isOpen = false,
        $overlay = $('.overlay--search');

    // update overlay position (if it's open) on window.resize
    $window.on('debouncedresize', function () {

      windowWidth = $window.outerWidth();

      if (isOpen) {
        $overlay.velocity({
          translateX: -1 * windowWidth
        }, {
          duration: 200,
          easing: "easeInCubic"
        });
      }

    });

    /**
     * dismiss overlay
     */

    function closeOverlay() {

      if (!isOpen) {
        return;
      }

      var offset;

      if ($body.hasClass('rtl')) {
        offset = windowWidth
      } else {
        offset = -1 * windowWidth
      }

      // we don't need a timeline for this animations so we'll use a simple tween between two states
      $overlay.velocity({
        translateX: offset
      }, {
        duration: 0
      });
      $overlay.velocity({
        translateX: 0
      }, {
        duration: 300,
        easing: "easeInCubic"
      });

      // remove focus from the search field
      $overlay.find('input').blur();

      isOpen = false;
    }

    function escOverlay(e) {
      if (e.keyCode == 27) {
        closeOverlay();
      }
    }

    // create animation and run it on
    $('.nav__item--search, [href*="#search"]').on('click touchstart', function (e) {
      // prevent default behavior and stop propagation
      e.preventDefault();
      e.stopPropagation();

      // if through some kind of sorcery the navigation drawer is already open return
      if (isOpen) {
        return;
      }

      var offset;

      if ($body.hasClass('rtl')) {
        offset = windowWidth
      } else {
        offset = -1 * windowWidth
      }

      // automatically focus the search field so the user can type right away
      $overlay.find('input').focus();

      $overlay.velocity({
        translateX: 0
      }, {
        duration: 0
      }).velocity({
        translateX: offset
      }, {
        duration: 300,
        easing: "easeOut",
        queue: false
      });

      $('.search-form').velocity({
        translateX: 300,
        opacity: 0
      }, {
        duration: 0
      }).velocity({
        opacity: 1
      }, {
        duration: 200,
        easing: "easeOutQuad",
        delay: 200,
        queue: false
      }).velocity({
        translateX: 0
      }, {
        duration: 400,
        easeing: [0.175, 0.885, 0.320, 1.275],
        delay: 50,
        queue: false
      });

      $('.overlay__wrapper > p').velocity({
        translateX: 200,
        opacity: 0
      }, {
        duration: 0
      }).velocity({
        opacity: 1
      }, {
        duration: 400,
        easing: "easeOutQuad",
        delay: 350,
        queue: false
      }).velocity({
        translateX: 0
      }, {
        duration: 400,
        easing: [0.175, 0.885, 0.320, 1.275],
        delay: 250,
        queue: false
      });

      // bind overlay dismissal to escape key
      $(document).on('keyup', escOverlay);

      isOpen = true;
    });

    // create function to hide the search overlay and bind it to the click event
    $('.overlay__close').on('click touchstart', function (e) {

      e.preventDefault();
      e.stopPropagation();

      closeOverlay();

      // unbind overlay dismissal from escape key
      $(document).off('keyup', escOverlay);

    });

  })();
  var shadows = (function () {

    var images,

    // get all images and info about them and store them
    // in the images array;
    init = function () {

      images = new Array();

      jQuery('.entry-image-shadow').remove();
      jQuery('.entry-card').removeData('shadow');

      $('.entry-card .entry-image img').each(function (i, obj) {
        var image = new Object(),
            card = new Object(),
            $obj = $(obj),
            imageOffset, imageWidth, imageHeight, cardOffset, cardWidth, cardHeight;

        image.$el = $obj;
        imageOffset = image.$el.offset();
        imageWidth = image.$el.outerWidth();
        imageHeight = image.$el.outerHeight();
        image.x0 = imageOffset.left;
        image.y0 = imageOffset.top;
        image.x1 = image.x0 + imageWidth;
        image.y1 = image.y0 + imageHeight;
        image.isPortrait = $obj.closest('.entry-image').hasClass('entry-image--tall') || $obj.closest('.entry-image').hasClass('entry-image--portrait');
        image.isEven = $obj.closest('.grid__item').hasClass('entry--even');

        card.$el = $obj.closest('.entry-card');
        cardOffset = card.$el.offset();
        cardWidth = card.$el.outerWidth();
        cardHeight = card.$el.outerHeight();
        card.x0 = cardOffset.left;
        card.y0 = cardOffset.top;
        card.x1 = card.x0 + cardWidth;
        card.y1 = card.y0 + cardHeight;

        image.card = card;

        images.push(image);
      });

      refresh();
    },
        
        
        
        
        // test for overlaps and do some work
        refresh = function () {

        for (var i = 0; i <= images.length - 1; i++) {
          for (var j = i + 1; j <= images.length - 1; j++) {

            var source, destination, left, right;

            // if we're testing the same image back off
            if (images[i].$el == images[j].$el) {
              return;
            }

            if (images[i].card.x0 < images[j].card.x0) {
              left = images[i];
              right = images[j];
            } else {
              left = images[j];
              right = images[i];
            }

            source = !left.isPortrait || left.isEven ? left : left.card;
            destination = right;


            if (imagesOverlap(source, destination)) {
              createShadow(source, destination);
            }

          }
        }

        if (!$.support.touch) {
          $('.entry-card').addHoverAnimation();
        }
        },
        
        
        createShadow = function (source, destination) {
        // let's assume image1 is over image2
        // we need to create a div
        var $placeholder = $('<div class="entry-image-shadow">'),
            $shadows = source.$el.data('shadow'),
            $card = typeof source.card == "undefined" ? source.$el : source.card.$el;

        $placeholder.css({
          position: "absolute",
          top: source.y0 - destination.y0,
          left: source.x0 - destination.x0,
          width: source.x1 - source.x0,
          height: source.y1 - source.y0
        });

        if (typeof $shadows == "undefined") {
          $shadows = $placeholder;
        } else {
          $shadows = $shadows.add($placeholder);
        }

        $card.data('shadow', $shadows);
        $placeholder.data('source', source.$el[0]);
        $placeholder.insertAfter(destination.$el);
        },
        
        
        imagesOverlap = function (image1, image2) {
        return (image1.x0 < image2.x1 && image1.x1 > image2.x0 && image1.y0 < image2.y1 && image1.y1 > image2.y0);
        };

    return {
      init: init,
      refresh: refresh
    }
  })();
  // /* ====== ON DOCUMENT READY ====== */
  $(document).ready(function () {
    init();
  });

  function init() {
    browserSize();
    platformDetect();
  }

  // /* ====== ON WINDOW LOAD ====== */
  $window.load(function () {
    browserSize();
    navigation.init();
    masonry.refresh();
    // shadows.init();
    //   svgLogo.init();
    //   animator.animate();
    scrollToTop();
    moveFeaturedImage();
    magnificPopupInit();

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
  /* ====== HELPER FUNCTIONS ====== */



  /**
   * Detect what platform are we on (browser, mobile, etc)
   */

  function platformDetect() {
    $.support.touch = 'ontouchend' in document;
    $.support.svg = (document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#BasicStructure", "1.1")) ? true : false;
    $.support.transform = getSupportedTransform();

    $html.addClass($.support.touch ? 'touch' : 'no-touch').addClass($.support.svg ? 'svg' : 'no-svg').addClass( !! $.support.transform ? 'transform' : 'no-transform');
  }



  function browserSize() {
    windowHeight = $window.height();
    windowWidth = $window.width();
    documentHeight = $document.height();
  }



  function getSupportedTransform() {
    var prefixes = ['transform', 'WebkitTransform', 'MozTransform', 'OTransform', 'msTransform'];
    for (var i = 0; i < prefixes.length; i++) {
      if (document.createElement('div').style[prefixes[i]] !== undefined) {
        return prefixes[i];
      }
    }
    return false;
  }

  /**
   * Handler for the back to top button
   */

  function scrollToTop() {
    $('a[href=#top]').click(function (event) {
      event.preventDefault();
      event.stopPropagation();

      $('html').velocity("scroll", 1000);
    });
  }

  function moveFeaturedImage() {
    if ($('article[class*="post"]').hasClass('entry-image--portrait') || $('article[class*="post"]').hasClass('entry-image--tall')) {
      $('.entry-featured').prependTo('article[class*="post"]');
    }
  }

  /**
   * function similar to PHP's empty function
   */

  function empty(data) {
    if (typeof(data) == 'number' || typeof(data) == 'boolean') {
      return false;
    }
    if (typeof(data) == 'undefined' || data === null) {
      return true;
    }
    if (typeof(data.length) != 'undefined') {
      return data.length === 0;
    }
    var count = 0;
    for (var i in data) {
      // if(data.hasOwnProperty(i))
      //
      // This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
      // http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
      //
      // for hosts objects we do this
      if (Object.prototype.hasOwnProperty.call(data, i)) {
        count++;
      }
    }
    return count === 0;
  }

  /**
   * function to add/modify a GET parameter
   */

  function setQueryParameter(uri, key, value) {
    var re = new RegExp("([?|&])" + key + "=.*?(&|$)", "i");
    separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
      return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
      return uri + separator + key + "=" + value;
    }
  }

  function is_touch() {
    return $.support.touch;
  }
})(jQuery);