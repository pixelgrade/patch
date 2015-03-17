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

  ; /* ====== Masonry Logic ====== */

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
          showBlocks($blocks);
          initialized = true;
        });
        },
        
        
        bindEvents = function () {
        $body.off('post-load');
        $body.on('post-load', onLoad);
        $container.masonry('off', 'layoutComplete');
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
          var $post = $(obj);
          animatePost($post, i * 100);
        });
        },
        
        
        animatePost = function ($post, delay) {
        $post.velocity({
          opacity: 1
        }, {
          duration: 300,
          delay: delay,
          easing: 'easeOutCubic'
        });
        },
        
        
        onLayout = function () {

        var values = new Array(),
            newValues = new Array();

        // get left value for each item in the grid
        $container.find('.grid__item').each(function (i, obj) {
          var $obj = $(obj),
              left = $obj.offset().left;
          // cache the value for further use and not trigger any more layouts
          $obj.data('left', left);
          values.push(left);
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
              left = parseInt($obj.data('left'), 10);
          if (newValues.indexOf(left) != -1 && $obj.find('.entry-image--portrait, .entry-image--tall').length) {
            $obj.addClass('entry--even');
          } else {
            $obj.removeClass('entry--even');
          }
        });

        setTimeout(function () {
          $container.masonry('layout');
          bindEvents();
        }, 10);

        setTimeout(function () {
          shadows.init();
        }, 200);

        return true;
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
          easing: 'easeOutQuad'
          };

      $obj.on('mouseenter', function () {
        $obj.velocity("stop").velocity({
          translateY: 15
        }, options);

        $otherShadow.velocity("stop").velocity({
          translateY: -15
        }, options);

        $meta.velocity("stop").velocity({
          translateY: '-100%',
          opacity: 1
        }, options);

        if (typeof $hisShadow !== "undefined") {
          $hisShadow.velocity("stop").velocity({
            translateY: 15
          }, options);
        }
      });

      $obj.on('mouseleave', function () {
        $obj.velocity("stop").velocity({
          translateY: ''
        }, options);

        $otherShadow.velocity("stop").velocity({
          translateY: ''
        }, options);

        $meta.velocity("stop").velocity({
          translateY: '',
          opacity: ''
        }, options);

        if (typeof $hisShadow !== "undefined") {
          $hisShadow.velocity("stop").velocity({
            translateY: ''
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
                  easing: "easeInQuart"
                });
              });

            } else {

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
            imageOffset, imageWidth, imageHeight;

        image.$el = $(obj);

        imageOffset = image.$el.offset();
        imageWidth = image.$el.outerWidth();
        imageHeight = image.$el.outerHeight();
        image.x0 = imageOffset.left;
        image.x1 = image.x0 + imageWidth;
        image.y0 = imageOffset.top;
        image.y1 = image.y0 + imageHeight;

        images.push(image);
      });

      refresh();
    },
        
        
        
        
        // test for overlaps and do some work
        refresh = function () {

        for (var i = 0; i <= images.length - 1; i++) {
          for (var j = i + 1; j <= images.length - 1; j++) {
            // if we're testing the same image back off
            if (images[i].$el == images[j].$el) {
              return;
            }

            if (imageOverlap(images[i], images[j])) {
              createShadow(images[i], images[j]);
            }
          }
        }

        $('.entry-card').addHoverAnimation();
        },
        
        
        createShadow = function (image1, image2) {
        // let's assume image1 is over image2
        // we need to create a div
        var $placeholder = $('<div class="entry-image-shadow">');

        $placeholder.css({
          position: "absolute",
          top: image1.y0 - image2.y0,
          left: image1.x0 - image2.x0,
          width: image1.x1 - image1.x0,
          height: image1.y1 - image1.y0
        });

        image1.$el.closest('.entry-card').data('shadow', $placeholder);
        $placeholder.insertAfter(image2.$el);
        },
        
        
        imageOverlap = function (image1, image2) {
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