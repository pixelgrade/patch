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

  ;
  var animator = (function () {

    var initialize = function () {

    },
        
        
        animate = function () {

        var isSingle = $('.site-main--single').length,
            hasSlider = $('.flexslider').length,
            hasSidebar = $('.sidebar--main').length,
            delay;

        animateTopBar();
        setTimeout(animateLogo, 100);
        setTimeout(animateMenu, 200);

        if (hasSlider) {
          setTimeout(animateSlider, 300);
          setTimeout(animateMain, 600);
          delay = 600;
        } else {
          setTimeout(animateMain, 300);
          delay = 600;
        }

        if (hasSidebar) {
          setTimeout(animateSidebar, delay + 200);
          setTimeout(animateFooter, delay + 400);
          delay = delay + 400;
        } else {
          setTimeout(animateFooter, delay + 200);
          delay = delay + 200;
        }
        },
        
        
        animateTopBar = function () {
        $('.top-bar').velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: "easeOutCubic"
        });
        },
        
        
        animateLogo = function () {

        var $title = $('.site-title'),
            $description = $('.site-description'),
            $descText = $('.site-description-text'),
            $after = $('.site-description-after'),
            descWidth;

        $title.velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });

        if ($description.length) {
          descWidth = $descText.outerWidth();

          $('.site-description').velocity({
            color: '#b8b6b7'
          }, {
            duration: 300,
            delay: 100,
            easing: 'easeOutCubic'
          });

          $after.css({
            width: descWidth,
            opacity: 1
          });

          $after.velocity({
            width: '100%'
          }, {
            duration: 300,
            delay: 200,
            easing: 'easeOutCubic'
          });
        }
        },
        
        
        animateMenu = function () {

        $('.nav--main').velocity({
          borderTopColor: '#e6e6e6'
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });

        $('.nav--main > li').velocity({
          opacity: 1
        }, {
          duration: 300,
          delay: 100,
          easing: 'easeOutCubic'
        });
        },
        
        
        animateSlider = function () {

        var $slider = $('.flexslider'),
            $container = $slider.find('.flag__body'),
            $thumbnail = $slider.find('.flag__img img'),
            $border = $slider.find('.entry-thumbnail-border'),
            $meta = $container.find('.entry-meta'),
            $title = $container.find('.entry-title'),
            $content = $container.find('.entry-content'),
            $divider = $container.find('.divider.narrow'),
            $dividerBig = $container.find('.divider.wide');

        $thumbnail.add($meta).velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: 'easeOutQuad'
        });

        $border.velocity({
          borderWidth: 0,
        }, {
          duration: 300,
          easing: 'easeOutQuad'
        });

        $title.velocity({
          opacity: 1
        }, {
          duration: 400,
          delay: 100,
          easing: 'easeOutCubic'
        });

        $content.velocity({
          opacity: 1
        }, {
          duration: 400,
          delay: 200,
          easing: 'easeOutCubic'
        });

        setTimeout(function () {
          animateLargeDivider($dividerBig);
        }, 300);

        setTimeout(function () {
          animateSmallDivider($divider);
        }, 600);

        $slider.velocity({
          borderBottomColor: '#e6e6e6'
        }, {
          duration: 300,
          easing: 'easeOutCubic',
          delay: 200
        });
        },
        
        
        animateSmallDivider = function ($divider) {

        var $squareLeft = $divider.find('.square-left'),
            $squareMiddle = $divider.find('.square-middle'),
            $squareRight = $divider.find('.square-right'),
            $lineLeft = $divider.find('.line-left'),
            $lineRight = $divider.find('.line-right');

        $lineLeft.velocity({
          'transform-origin': '0 50%',
          scaleX: 0
        }, {
          duration: 0
        });

        $lineRight.velocity({
          'transform-origin': '100% 50%',
          scaleX: 0
        }, {
          duration: 0
        });

        $squareLeft.add($squareMiddle).add($squareRight).velocity({
          scale: 0,
          'transform-origin': '50% 50%'
        }, {
          duration: 0
        })

        $divider.css({
          opacity: 1
        });

        $lineLeft.add($lineRight).velocity({
          scaleX: 1
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });

        $squareLeft.add($squareRight).velocity({
          scale: 1
        }, {
          duration: 300,
          delay: 240,
          easing: 'easeOutCubic'
        });

        $squareMiddle.velocity({
          scale: 1
        }, {
          duration: 300,
          delay: 340,
          easing: 'easeOutCubic'
        });
        },
        
        
        animateLargeDivider = function ($divider) {
        var $square = $divider.find('.square'),
            $line = $divider.find('.line');

        $square.velocity({
          'transform-origin': '50% 50%',
          scale: 0
        }, {
          duration: 0
        });

        $line.velocity({
          'transform-origin': '50% 50%',
          scaleX: 0
        }, {
          duration: 0
        });

        $divider.css('opacity', 1);

        $line.velocity({
          scaleX: 1
        }, {
          duration: 200
        });

        $square.velocity({
          scale: 1
        }, {
          duration: 300,
          delay: 100
        });
        },
        
        
        animateMain = function () {

        var $posts = $('.archive__grid').children();

        if ($posts.length) {

          masonry.init();

          $('.posts-navigation').velocity({
            opacity: 1
          }, {
            duration: 300,
            delay: 100,
            easing: 'easeOutCubic'
          });

          $('.page-header').velocity({
            opacity: 1
          }, {
            duration: 300,
            delay: 100,
            easing: 'easeOutCubic'
          });

        } else {
          animateMainSingle();
        }
        },
        
        
        animateMainSingle = function () {

        var $main = $('.site-main'),
            $header = $main.find('.entry-header');
        $meta = $header.find('.entry-meta'), $title = $header.find('.entry-title')
        $excerpt = $title.next('.intro'), $content = $main.find('.entry-featured, .entry-content, .entry-footer, .post-navigation, .comments-area');

        $meta.velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });

        $title.velocity({
          opacity: 1
        }, {
          duration: 300,
          delay: 100,
          easing: 'easeOutCubic'
        });

        $excerpt.velocity({
          opacity: 1
        }, {
          duration: 300,
          delay: 200,
          easing: 'easeOutCubic'
        });

        $content.velocity({
          opacity: 1
        }, {
          duration: 300,
          delay: 300,
          easing: 'easeOutCubic'
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

        var $divider = $post.find('.divider.narrow'),
            $dividerBig = $post.find('.divider.wide');

        setTimeout(function () {
          animateLargeDivider($dividerBig);
        }, 100);

        setTimeout(function () {
          animateSmallDivider($divider);
        }, 400);
        },
        
        
        animateSidebar = function () {
        $('.sidebar--main').velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });
        },
        
        
        animateFooter = function () {
        $('.site-footer').velocity({
          opacity: 1
        }, {
          duration: 300,
          easing: 'easeOutCubic'
        });
        };

    return {
      animate: animate,
      animatePost: animatePost
    }

  })(); /* ====== Masonry Logic ====== */

  var masonry = (function () {

    var $container = $('.archive__grid'),
        $sidebar = $('.sidebar--main'),
        $blocks = $container.children().addClass('post--animated  post--loaded'),
        initialized = false,
        containerTop, containerBottom, sidebarTop,
        
        init = function () {

        if ($container.length) {
          containerTop = $container.offset().top;
          containerBottom = containerTop + $container.outerHeight();
        }

        if ($sidebar.length) {
          sidebarTop = $sidebar.offset().top;
        }

        $container.masonry({
          itemSelector: '.grid__item',
          transitionDuration: 0
        });

        if (sidebarMasonry()) {
          $sidebar.masonry({
            itemSelector: '.grid__item',
            transitionDuration: 0
          });
        }

        bindEvents();
        showBlocks($blocks);
        initialized = true;
        refresh();
        },
        
        
        sidebarMasonry = function () {
        return false;
        // return $sidebar.length && sidebarTop >= containerBottom;
        },
        
        
        bindEvents = function () {
        $body.on('post-load', onLoad);

        $container.masonry('on', 'layoutComplete', function () {
          setTimeout(function () {
            browserSize();
            fixedSidebars.refresh();
            fixedSidebars.update();
          }, 350);
        });
        },
        
        
        refresh = function () {

        if (!initialized) {
          return;
        }

        $container.masonry('layout');
        if (sidebarMasonry()) {
          $sidebar.masonry('layout');
        }
        },
        
        
        showBlocks = function ($blocks) {
        $blocks.each(function (i, obj) {
          var $post = $(obj);
          animator.animatePost($post, i * 100);
        });
        if (!$.support.touch) {
          $blocks.addHoverAnimation();
        }
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
          $top = $obj.find('.entry-header'),
          $img = $obj.find('.entry-featured'),
          $border = $obj.find('.entry-image-border'),
          $content = $obj.find('.entry-content'),
          $bottom = $content.children().not($img);

      // if we don't have have elements that need to be animated return
      if (!$obj.length || !$img.length) {
        return;
      }

      // bind the tweens we created above to mouse events accordingly, through hoverIntent to avoid flickering
      $obj.find('.entry__wrapper').hoverIntent({
        over: animateHoverIn,
        out: animateHoverOut,
        timeout: 0,
        interval: 0
      });

      function animateHoverIn() {
        $top.velocity({
          translateY: 10
        }, {
          duration: 200,
          easing: 'easeOutQuad'
        });

        $border.velocity({
          'outline-width': 1
        }, {
          duration: 0
        });

        $border.velocity({
          'border-width': 10
        }, {
          duration: 100,
          easing: 'easeOutQuad'
        });

        $bottom.velocity({
          translateY: -10
        }, {
          duration: 200,
          easing: 'easeOutQuad'
        });
      }

      function animateHoverOut() {
        $top.velocity({
          translateY: 0
        }, {
          duration: 200,
          easing: 'easeOutQuad'
        });

        $border.velocity({
          'border-width': 0
        }, {
          duration: 150,
          easing: 'easeOutQuad'
        });

        $border.velocity({
          'outline-width': 0
        }, {
          duration: 0
        });

        $bottom.velocity({
          translateY: 0
        }, {
          duration: 200,
          easing: 'easeOutQuad'
        });
      }

    });

  } /* ====== Navigation Logic ====== */

  var navigation = (function () {

    var $nav = $('.nav--main'),
        
        
        init = function () {
        // initialize the logic behind the main navigation
        $nav.ariaNavigation();

        if ($('.floating-nav').length) {

          $nav.clone(true).removeClass('nav--main').addClass('nav--toolbar').appendTo('.floating-nav .flag__body');

          $('.nav--toolbar--left').clone().removeClass('nav--toolbar nav--toolbar--left').addClass('nav--stacked nav--floating nav--floating--left').appendTo('.floating-nav');

          $('.nav__item--search').prependTo('.nav--floating--left');

          $('.nav--toolbar--right').clone().removeClass('nav--toolbar nav--toolbar--right').addClass('nav--stacked nav--floating nav--floating--right').appendTo('.floating-nav');

          // make sure that the links in the floating-nav, that shows on scroll, are ignored by TAB
          $('.floating-nav').find('a').attr('tabIndex', -1);
          handleTopBar();

        }

        mobileNav();
        },
        
        
        toggleTopBar = function () {
        var navBottom = $nav.offset().top + $nav.outerHeight();

        if (navBottom < latestKnownScrollY) {
          $('.top-bar.fixed').addClass('visible');
          $('.nav--floating').addClass('nav--floating--is-visible');
          $('.article-navigation .navigation-item').addClass('hover-state');
        } else {
          $('.top-bar.fixed').removeClass('visible');
          $('.nav--floating').removeClass('nav--floating--is-visible');
          $('.article-navigation .navigation-item').removeClass('hover-state');
        }

        },
        
        
        mobileNav = function () {
        var $nav = $('.nav--main'),
            $navTrigger = $('.js-nav-trigger'),
            $navContainer = $('.main-navigation'),
            navTop = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().top : 0,
            navLeft = (typeof $navContainer.offset() !== 'undefined') ? $navContainer.offset().left : 0,
            navWidth = $nav.outerWidth(),
            containerWidth = $navContainer.outerWidth(),
            navHeight = $navContainer.outerHeight(),
            $toolbar = $('.toolbar'),
            isOpen = false,
            pageTop = $('#page').offset().top,
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
          $('body').toggleClass('nav--is-open');

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

        // clone and append secondary menus
        $('<li/>', {
          class: "nav--toolbar--left_wrapper"
        }).appendTo('.js-nav--main');

        $('<li/>', {
          class: "nav--toolbar--right_wrapper"
        }).appendTo('.js-nav--main');

        $('<li/>', {
          class: "nav-dropdown_wrapper"
        }).appendTo('.js-nav--main');

        $('.nav--toolbar--left_wrapper').append($('.nav--toolbar--left').clone());
        $('.nav--toolbar--right_wrapper').append($('.nav--toolbar--right').clone());

        $('.nav-dropdown_wrapper').append($('.nav--dropdown').clone());

        },
        
        
        handleTopBar = function () {
        if ($('body').hasClass('admin-bar') && is_small) {
          var offset = $('#wpadminbar').height();

          $(window).scroll(function () {
            if ($(this).scrollTop() > offset) {
              $('.main-navigation').addClass('fixed');
            } else {
              $('.main-navigation').removeClass('fixed');
            }
          });
        }
        };

    return {
      init: init,
      toggleTopBar: toggleTopBar,
      handleTopBar: handleTopBar
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

  })(); /* ====== Fixed Sidebars Logic ====== */

  var fixedSidebars = (function () {

    var $smallSidebar = $('#jp-post-flair'),
        smallSidebarPinned = false,
        smallSidebarPadding = 100,
        smallSidebarPinTop = $('.top-bar.fixed').outerHeight() + smallSidebarPadding,
        smallSidebarOffset, smallSidebarBottom, $sidebar = $('.sidebar--main'),
        $main = $('.site-main'),
        mainHeight = $main.outerHeight(),
        mainOffset, mainTop, mainBottom = mainTop + mainHeight,
        sidebarPinned = false,
        sidebarPadding = 60,
        sidebarBottom, sidebarHeight, sidebarOffset, sidebarTop, sidebarBottom,
        
        previousTop = 0,
        animating = false,
        
        
        initialized = false,
        
        
        
        /**
         * initialize sidebar positioning
         */
        
        init = function () {

        if ($sidebar.length) {
          sidebarOffset = $sidebar.offset();
          sidebarTop = sidebarOffset.top;
          sidebarHeight = $sidebar.outerHeight();
          sidebarBottom = sidebarTop + sidebarHeight;
          mainTop = $main.offset().top;

          if (mainTop >= sidebarTop) {
            styleWidgets();
          }
        }
        wrapJetpackAfterContent();
        refresh();
        initialized = true;
        },
        
        
        
        /**
         * Wrap Jetpack's related posts and
         * Sharedaddy sharing into one div
         * to make a left sidebar on single posts
         */
        
        wrapJetpackAfterContent = function () {
        // check if we are on single post and the wrap has not been done already by Jetpack
        // (it happens when the theme is activated on a wordpress.com installation)
        if ($('#jp-post-flair').length != 0) $('body').addClass('has--jetpack-sidebar');

        if ($('body').hasClass('single-post') && $('#jp-post-flair').length == 0) {

          var $jpSharing = $('.sharedaddy.sd-sharing-enabled');
          var $jpLikes = $('.sharedaddy.sd-like');
          var $jpRelatedPosts = $('#jp-relatedposts');

          if ($jpSharing.length || $jpLikes.length || $jpRelatedPosts.length) {

            $('body').addClass('has--jetpack-sidebar');

            var $jpWrapper = $('<div/>', {
              id: 'jp-post-flair'
            });
            $jpWrapper.appendTo($('.entry-content'));

            if ($jpSharing.length) {
              $jpSharing.appendTo($jpWrapper);
            }

            if ($jpLikes.length) {
              $jpLikes.appendTo($jpWrapper);
            }

            if ($jpRelatedPosts.length) {
              $jpRelatedPosts.appendTo($jpWrapper);
            }
          }
        }
        },
        
        
        
        
        /**
         * Adding a class and some mark-up to the
         * archive widget to make it look splendid
         */
        
        styleWidgets = function () {

        if ($.support.touch) {
          return;
        }

        var $widgets = $sidebar.find('.widget_categories, .widget_archive, .widget_tag_cloud'),
            separatorMarkup = '<span class="separator  separator--text" role="presentation"><span>More</span></a>';

        $widgets.each(function () {

          var $widget = $(this),
              widgetHeight = $widget.outerHeight(),
              newHeight = 220,
              heightDiffrence = widgetHeight - newHeight,
              widgetWidth = $widget.outerWidth();

          if (widgetHeight > widgetWidth) {

            $widget.data('heightDiffrence', heightDiffrence);
            $widget.css('max-height', newHeight);

            $widget.addClass('shrink');
            $widget.append(separatorMarkup);
            refresh();
            masonry.refresh();

            $widget.find('a').focus(function () {
              $widget.removeClass('shrink').addClass('focused');
            });

            $widget.on('mouseenter', function () {

              $main.css({
                'paddingBottom': $sidebar.offset().top + sidebarHeight + heightDiffrence - mainBottom
              });

              // $widget.addClass('focused');
              $widget.css({
                'max-height': widgetHeight
              });

              setTimeout(function () {
                refresh();
                update();
              }, 600);
            });

            $widget.on('mouseleave', function () {
              $main.css({
                'paddingBottom': ''
              })
              // $widget.removeClass('focused');
              $widget.css('max-height', newHeight);

              delayUpdate();
            });
          }

          delayUpdate();

        });

        },
        
        
        delayUpdate = function () {
        setTimeout(function () {
          refresh();
          update();
        }, 600);
        },
        
        
        
        /**
         * update position of the two sidebars depending on scroll position
         */
        
        update = function () {

        if (!initialized) {
          init();
        }

        var windowBottom = latestKnownScrollY + windowHeight;

        sidebarBottom = sidebarHeight + sidebarOffset.top + sidebarPadding;
        mainBottom = mainHeight + sidebarOffset.top + sidebarPadding;

        /* adjust right sidebar positioning if needed */
        if (mainOffset.top == sidebarOffset.top && sidebarHeight < mainHeight) {

          // pin sidebar
          if (windowBottom > sidebarBottom && !sidebarPinned) {
            $sidebar.css({
              position: 'fixed',
              top: windowHeight - sidebarHeight - sidebarPadding,
              left: sidebarOffset.left
            });
            sidebarPinned = true;
          }

          // unpin sidebar
          if (windowBottom <= sidebarBottom && sidebarPinned) {
            $sidebar.css({
              position: '',
              top: '',
              left: ''
            });
            sidebarPinned = false;
          }

          if (windowBottom <= mainBottom) {
            $sidebar.css('top', windowHeight - sidebarHeight - sidebarPadding);
          }

          if (windowBottom > mainBottom && windowBottom < documentHeight) {
            $sidebar.css('top', mainBottom - sidebarPadding - sidebarHeight - latestKnownScrollY);
          }

          if (windowBottom >= documentHeight) {
            $sidebar.css('top', mainBottom - sidebarPadding - sidebarHeight - documentHeight + windowHeight);
          }

        }

        /* adjust left sidebar positioning if needed */
        if ($smallSidebar.length) {

          if (smallSidebarOffset.top - smallSidebarPinTop < latestKnownScrollY && !smallSidebarPinned) {
            $smallSidebar.css({
              position: 'fixed',
              top: smallSidebarPinTop,
              left: smallSidebarOffset.left
            });
            smallSidebarPinned = true;
          }

          if (smallSidebarOffset.top - smallSidebarPinTop >= latestKnownScrollY && smallSidebarPinned) {
            $smallSidebar.css({
              position: '',
              top: '',
              left: ''
            });
            smallSidebarPinned = false;
          }

          if (windowBottom > mainBottom && windowBottom < documentHeight) {
            $smallSidebar.css('top', mainBottom - smallSidebarPadding - smallSidebarHeight - latestKnownScrollY);
          }

        }

        },
        
        
        refresh = function () {

        if ($main.length) {
          mainOffset = $main.offset();
        }

        if ($sidebar.length) {

          var positionValue = $sidebar.css('position'),
              topValue = $sidebar.css('top'),
              leftValue = $sidebar.css('left'),
              pinnedValue = sidebarPinned;

          $sidebar.css({
            position: '',
            top: '',
            left: ''
          });

          sidebarPinned = false;
          sidebarOffset = $sidebar.offset();
          sidebarHeight = $sidebar.outerHeight();
          sidebarBottom = sidebarOffset.top + sidebarHeight;
          mainHeight = $main.outerHeight();

          $sidebar.css({
            position: positionValue,
            top: topValue,
            left: leftValue
          });

          sidebarPinned = pinnedValue;
        }

        if ($smallSidebar.length) {

          $smallSidebar.find('.sd-sharing-enabled, .sd-like, .jp-relatedposts-post').show().each(function (i, obj) {
            var $box = $(obj),
                boxOffset = $box.offset(),
                boxHeight = $box.outerHeight(),
                boxBottom = boxOffset.top + boxHeight - latestKnownScrollY;

            if (smallSidebarPinTop + boxBottom > windowHeight + smallSidebarPadding) {
              $box.hide();
            } else {
              $box.show();
            }
          });

          var $relatedposts = $('.jp-relatedposts');

          if ($relatedposts.length) {
            $relatedposts.show();
            if (!$relatedposts.find('.jp-relatedposts-post:visible').length) {
              $relatedposts.hide();
            }
          }

          smallSidebarPinned = false;
          smallSidebarOffset = $smallSidebar.offset();
          smallSidebarHeight = $smallSidebar.outerHeight();
          smallSidebarBottom = smallSidebarOffset.top + smallSidebarHeight;
        }

        };

    return {
      init: init,
      update: update,
      refresh: refresh
    }

  })(); /* ====== Slider Logic ====== */

  var slider = (function () {

    var $sliders = $('.flexslider'),
        
        
        init = function () {

        if (!useSlider()) {
          initFallback();
          return;
        }

        if ($.flexslider !== undefined && $sliders.length) {

          $sliders.flexslider({
            animation: "fade",
            slideshow: false,
            //no autostart slideshow for accessibility purposes
            controlNav: false,
            prevText: '<span class="screen-reader-text">' + silkFeaturedSlider.prevText + '</span>',
            nextText: '<span class="screen-reader-text">' + silkFeaturedSlider.nextText + '</span>',
            start: function () {
              var $arrow = $('.svg-templates .slider-arrow');
              $arrow.clone().appendTo('.flex-direction-nav .flex-prev');
              $arrow.clone().appendTo('.flex-direction-nav .flex-next');
            }
          });
        }
        },
        
        
        initFallback = function () {
        $sliders.closest('.featured-content').insertAfter('#masthead').addClass('featured-content--scroll');
        },
        
        
        useSlider = function () {
        // return !(touch && windowWidth < 800);
        // return !(windowWidth < 800);
        return !$.support.touch;
        };

    return {
      init: init
    }

  })();
  window.svgLogo = (function () {

    var $logo = $('.site-branding .site-title'),
        $svg = $logo.find('svg'),
        logoOffset = $logo.offset(),
        logoTop = logoOffset.top,
        $nav = $('.main-navigation'),
        navHeight = $nav.outerHeight(),
        $logoClone = $logo.clone().empty(),
        $svgClone = $svg.clone(),
        $newSvg = $svgClone,
        logoInitialized = false,
        
        
        init = function () {

        if (!$.support.svg) return;

        var $header = $('.site-header'),
            $title = $('.site-header .site-title'),
            $span = $title.find('span'),
            $svg = $title.find('svg'),
            $text = $svg.find('text'),
            $rect = $svg.find('rect'),
            titleWidth = $title.width(),
            titleHeight = $title.height(),
            spanWidth = $span.width(),
            spanHeight = $span.height(),
            fontSize = parseInt($span.css('font-size')),
            scaling = spanWidth / parseFloat(titleWidth);

        setTimeout(function () {

          $svg.removeAttr('viewBox').hide();
          $span.css({
            'white-space': 'nowrap',
            display: 'inline-block'
          });

          fontSize = parseInt($span.css('font-size')), spanWidth = $span.width();
          spanHeight = $span.height();
          titleWidth = $title.width();
          scaling = spanWidth / parseFloat(titleWidth);

          if (spanWidth > titleWidth) {
            fontSize = parseInt(fontSize / scaling);
            $span.css('font-size', fontSize);
            spanWidth = $span.width();
            spanHeight = $span.height();
          }

          $span.css({
            'font-size': '',
            'white-space': ''
          }).hide();

          $svg.width(spanWidth);
          $svg.attr('viewBox', "0 0 " + spanWidth + " " + spanHeight);
          $text.attr('font-size', fontSize);

          var newSvg = $svg.clone().wrap('<div>').parent().html(),
              $newSvg = $(newSvg);

          $svg.remove();
          $title.children('a').append($newSvg);
          $newSvg.show();

          var $topLogo = $('.top-bar .site-title'),
              topLogoHeight = $topLogo.outerHeight(),
              $clone = $newSvg.clone();

          $clone.outerHeight(topLogoHeight);
          $clone.find('text').attr('stroke', '#000');

          $topLogo.empty().append($clone);

          logoAnimation();
        }, 60);
        },
        
        
        logoAnimation = function () {

        if (!is_small) return;

        $logoClone = $logo.clone().empty();
        $svgClone = $svg.clone();

        $svgClone.appendTo($logoClone).show();
        $svgClone.css({
          display: 'block',
          height: navHeight * 2 / 3,
          marginLeft: 'auto',
          marginRight: 'auto'
        });

        $logoClone.css({
          marginTop: navHeight / 6,
          height: navHeight * 5 / 6,
          overflow: 'hidden'
        });

        $newSvg = $($svgClone.clone().wrap('<div>').parent().html());

        $svgClone.remove();
        $logoClone.append($newSvg);
        $logoClone.appendTo($nav);
        $newSvg.velocity({
          translateY: navHeight
        }, {
          duration: 0
        });
        // $logoClone.width($newSvg.width());
        if (!$body.hasClass('home')) {
          window.scrollTo(0, $('.site-header').height() - navHeight);
        }

        logoInitialized = true;
        },
        
        
        updateLogoAnimation = function () {

        var newVal = 0;

        if (latestKnownScrollY > logoTop - navHeight) {
          if (latestKnownScrollY - logoTop < 0) {
            newVal = logoTop - latestKnownScrollY;
          } else {
            newVal = 0;
          }
        } else {
          newVal = navHeight
        }

        $newSvg.velocity({
          translateY: newVal
        }, {
          duration: 0
        });
        };

    return {
      init: init,
      update: updateLogoAnimation
    }
  })(); /* ====== ON DOCUMENT READY ====== */

  $(document).ready(function () {
    init();
  });

  function init() {
    browserSize();
    platformDetect();
  }

  /* ====== ON WINDOW LOAD ====== */

  $window.load(function () {
    browserSize();
    navigation.init();
    slider.init();
    fixedSidebars.update();
    svgLogo.init();
    animator.animate();
    scrollToTop();
    infinityHandler();

    if (latestKnownScrollY) $window.trigger('scroll');
  });

  /* ====== ON RESIZE ====== */

  function onResize() {
    browserSize();
    masonry.refresh();
    fixedSidebars.refresh();
    fixedSidebars.update();
  }

  $window.on('debouncedresize', onResize);

  /* ====== ON SCROLL ====== */

  function onScroll() {
    latestKnownScrollY = window.scrollY;
    requestTick();
  }

  $window.on('scroll', onScroll);

  function requestTick() {
    if (!ticking) {
      requestAnimationFrame(update);
    }
    ticking = true;
  }

  function update() {
    fixedSidebars.update();
    navigation.toggleTopBar();
    svgLogo.update();
    ticking = false;
  } /* ====== HELPER FUNCTIONS ====== */



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

  /**
   * Infinite scroll behaviour
   */

  function infinityHandler() {
    $("#infinite-handle").on("click", function () {
      $('body').addClass('loading-posts');
    });

    $(document.body).on("post-load", function () {
      $('body').removeClass('loading-posts');
    });
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