/* ====== Navigation Logic ====== */

var navigation = (function() {

	var $nav = $('.nav--main'),

	init = function() {
		// initialize the logic behind the main navigation
		$nav.ariaNavigation();

		if ( $('.floating-nav').length ) {

			$nav.clone(true)
				.removeClass('nav--main')
				.addClass('nav--toolbar')
				.appendTo('.floating-nav .flag__body');

			$('.nav--toolbar--left')
				.clone()
				.removeClass('nav--toolbar nav--toolbar--left')
				.addClass('nav--stacked nav--floating nav--floating--left')
				.appendTo('.floating-nav');

			$('.nav__item--search').prependTo('.nav--floating--left');

			$('.nav--toolbar--right')
				.clone()
				.removeClass('nav--toolbar nav--toolbar--right')
				.addClass('nav--stacked nav--floating nav--floating--right')
				.appendTo('.floating-nav');

			// make sure that the links in the floating-nav, that shows on scroll, are ignored by TAB
			$('.floating-nav').find('a').attr( 'tabIndex', -1 );
			handleTopBar();
			
		}

		mobileNav();
	},

	toggleTopBar = function() {
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

			if(!android_ancient) {
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

	handleTopBar = function() {
		if( $('body').hasClass('admin-bar') && is_small ) {
			var offset = $('#wpadminbar').height();

			$(window).scroll(function() {
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