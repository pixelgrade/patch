/* ====== Masonry Logic ====== */

var masonry = (function() {

	var $container 		= $('.archive__grid'),
		$sidebar		= $('.sidebar--main'),
		$blocks			= $container.children().addClass('post--animated  post--loaded'),
		initialized		= false,
		containerTop,
		containerBottom,
		sidebarTop,

	init = function() {

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

	sidebarMasonry = function() {
		return false;
		// return $sidebar.length && sidebarTop >= containerBottom;
	},

	bindEvents = function() {
		$body.on('post-load', onLoad);

		$container.masonry('on', 'layoutComplete', function() {
			setTimeout(function() {
				browserSize();
				fixedSidebars.refresh();
				fixedSidebars.update();
			}, 350);
		});
	},

	refresh = function() {

		if (!initialized) {
			return;
		}
		
		$container.masonry('layout');
		if (sidebarMasonry()) {
			$sidebar.masonry('layout');
		}
	},

	showBlocks = function($blocks) {
		$blocks.each(function(i, obj) {
			var $post = $(obj);
			animator.animatePost($post, i * 100);
		});
		if ( ! $.support.touch ) {
			$blocks.addHoverAnimation();
		}
	},

	onLoad = function() {
		var $newBlocks = $container.children().not('.post--loaded').addClass('post--loaded');
		$newBlocks.imagesLoaded(function() {
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
$.fn.addHoverAnimation = function() {

	return this.each(function(i, obj) {

	    var $obj 		= $(obj),
	    	$top    	= $obj.find('.entry-header'),
	    	$img 		= $obj.find('.entry-featured'),
	    	$border		= $obj.find('.entry-image-border'),
	    	$content 	= $obj.find('.entry-content'),
	    	$bottom 	= $content.children().not($img);

	    // if we don't have have elements that need to be animated return
	    if ( !$obj.length || !$img.length ) {
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

}