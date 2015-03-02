/* ====== Fixed Sidebars Logic ====== */

var fixedSidebars = (function() {

	var $smallSidebar       = $('#jp-post-flair'),
		smallSidebarPinned  = false,
		smallSidebarPadding = 100,
		smallSidebarPinTop	= $('.top-bar.fixed').outerHeight() + smallSidebarPadding,
		smallSidebarOffset,
		smallSidebarBottom,
		$sidebar        	= $('.sidebar--main'),
		$main           	= $('.site-main'),
		mainHeight      	= $main.outerHeight(),
		mainOffset,
		mainTop,
		mainBottom			= mainTop + mainHeight,
		sidebarPinned   	= false,
		sidebarPadding  	= 60,
		sidebarBottom,
		sidebarHeight,
		sidebarOffset,
		sidebarTop,
		sidebarBottom,

		previousTop = 0,
		animating = false,

		initialized = false,

	/**
	 * initialize sidebar positioning
	 */
	init = function() {

		if ($sidebar.length) {
			sidebarOffset 	= $sidebar.offset();
			sidebarTop 		= sidebarOffset.top;
			sidebarHeight 	= $sidebar.outerHeight();
			sidebarBottom 	= sidebarTop + sidebarHeight;
			mainTop			= $main.offset().top;

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
	wrapJetpackAfterContent = function() {
		// check if we are on single post and the wrap has not been done already by Jetpack
		// (it happens when the theme is activated on a wordpress.com installation)

		if ( $('#jp-post-flair').length != 0 )
			$('body').addClass('has--jetpack-sidebar');

		if( $('body').hasClass('single-post') && $('#jp-post-flair').length == 0 ) {

			var $jpSharing = $('.sharedaddy.sd-sharing-enabled');
			var $jpLikes = $('.sharedaddy.sd-like');
			var $jpRelatedPosts = $('#jp-relatedposts');

			if ( $jpSharing.length || $jpLikes.length || $jpRelatedPosts.length ) {

				$('body').addClass('has--jetpack-sidebar');

				var $jpWrapper = $('<div/>', { id: 'jp-post-flair' });
				$jpWrapper.appendTo($('.entry-content'));

				if( $jpSharing.length ) {
					$jpSharing.appendTo($jpWrapper);
				}

				if( $jpLikes.length ) {
					$jpLikes.appendTo($jpWrapper);
				}

				if( $jpRelatedPosts.length ) {
					$jpRelatedPosts.appendTo($jpWrapper);
				}
			}
		}
	},


	/**
	 * Adding a class and some mark-up to the
	 * archive widget to make it look splendid
	 */
	styleWidgets = function() {

	 	if ($.support.touch) {
	 		return;
	 	}

	 	var $widgets 		= $sidebar.find('.widget_categories, .widget_archive, .widget_tag_cloud'),
	 		separatorMarkup = '<span class="separator  separator--text" role="presentation"><span>More</span></a>';

	 	$widgets.each(function() {

	 		var $widget       	= $(this),
		 		widgetHeight  	= $widget.outerHeight(),
	 			newHeight		= 220,
		 		heightDiffrence	= widgetHeight - newHeight,
		 		widgetWidth   	= $widget.outerWidth();

	 		if ( widgetHeight > widgetWidth ) {

	 			$widget.data('heightDiffrence', heightDiffrence);
	 			$widget.css('max-height', newHeight);

	 			$widget.addClass('shrink');
	 			$widget.append(separatorMarkup);
	 			refresh();
	 			masonry.refresh();

	 			$widget.find('a').focus(function () {
	 				$widget.removeClass('shrink').addClass('focused');
	 			});

	 			$widget.on('mouseenter', function() {

	 				$main.css({
	 					'paddingBottom': $sidebar.offset().top + sidebarHeight + heightDiffrence - mainBottom
	 				});

	 				// $widget.addClass('focused');
	 				$widget.css({
	 					'max-height': widgetHeight
	 				});

	 				setTimeout(function() {
	 					refresh();
	 					update();
	 				}, 600);
	 			});

	 			$widget.on('mouseleave', function() {
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

	delayUpdate = function() {
		setTimeout(function() {
			refresh();
			update();
		}, 600);
	},

	/**
	 * update position of the two sidebars depending on scroll position
	 */
	update = function() {

		if ( !initialized ) {
			init();
		}

		var windowBottom  = latestKnownScrollY + windowHeight;

		sidebarBottom = sidebarHeight + sidebarOffset.top + sidebarPadding;
		mainBottom    = mainHeight + sidebarOffset.top + sidebarPadding;

		/* adjust right sidebar positioning if needed */
		if (mainOffset.top == sidebarOffset.top && sidebarHeight < mainHeight) {

			// pin sidebar
			if ( windowBottom > sidebarBottom && !sidebarPinned ) {
				$sidebar.css({  
					position: 'fixed',
					top:      windowHeight - sidebarHeight - sidebarPadding,
					left:     sidebarOffset.left
				});
				sidebarPinned = true;
			}

			// unpin sidebar
			if ( windowBottom <= sidebarBottom && sidebarPinned ) {
				$sidebar.css({
					position: '',
					top:      '',
					left:     ''
				});
				sidebarPinned = false;
			}

			if ( windowBottom <= mainBottom ) {
				$sidebar.css('top', windowHeight - sidebarHeight - sidebarPadding);
			}

			if ( windowBottom > mainBottom && windowBottom < documentHeight ) {
				$sidebar.css('top', mainBottom - sidebarPadding - sidebarHeight - latestKnownScrollY);
			}

			if ( windowBottom >= documentHeight ) {
				$sidebar.css('top', mainBottom - sidebarPadding - sidebarHeight - documentHeight + windowHeight);
			}
			
		}

		/* adjust left sidebar positioning if needed */
		if ( $smallSidebar.length ) {
			
		 	if ( smallSidebarOffset.top - smallSidebarPinTop < latestKnownScrollY && ! smallSidebarPinned ) {
				$smallSidebar.css({  
					position: 'fixed',
					top: smallSidebarPinTop,
					left: smallSidebarOffset.left
				});
				smallSidebarPinned = true;
			}   

		 	if ( smallSidebarOffset.top - smallSidebarPinTop >= latestKnownScrollY && smallSidebarPinned ) {
				$smallSidebar.css({
					position: '',
					top: '',
					left: ''
				});
				smallSidebarPinned = false;
			}

			if ( windowBottom > mainBottom && windowBottom < documentHeight ) {
				$smallSidebar.css('top', mainBottom - smallSidebarPadding - smallSidebarHeight - latestKnownScrollY);
			}

		}

	},

	refresh = function() {

		if ( $main.length ) {
			mainOffset = $main.offset();
		}

		if ( $sidebar.length ) {

			var positionValue 	= $sidebar.css('position'),
				topValue 		= $sidebar.css('top'),
				leftValue 		= $sidebar.css('left'),
				pinnedValue		= sidebarPinned;

			$sidebar.css({
				position: '',
				top: '',
				left: ''
			});

			sidebarPinned = false;
			sidebarOffset = $sidebar.offset();
			sidebarHeight = $sidebar.outerHeight();
			sidebarBottom = sidebarOffset.top + sidebarHeight;
			mainHeight    = $main.outerHeight();

			$sidebar.css({
				position: positionValue,
				top: topValue,
				left: leftValue
			});

			sidebarPinned = pinnedValue;
		}

		if ( $smallSidebar.length ) {

			$smallSidebar.find('.sd-sharing-enabled, .sd-like, .jp-relatedposts-post').show().each(function(i, obj) {
				var $box 		= $(obj),
					boxOffset	= $box.offset(),
					boxHeight	= $box.outerHeight(),
					boxBottom	= boxOffset.top + boxHeight - latestKnownScrollY;

				if ( smallSidebarPinTop + boxBottom > windowHeight + smallSidebarPadding ) {
					$box.hide();
				} else {
					$box.show();
				}
			});

			var $relatedposts = $('.jp-relatedposts');

			if ( $relatedposts.length ) {
				$relatedposts.show();
				if ( ! $relatedposts.find('.jp-relatedposts-post:visible').length ) {
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
			
})();