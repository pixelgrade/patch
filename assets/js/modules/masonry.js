/* ====== Masonry Logic ====== */

var masonry = (function() {

	var $container 		= $('.grid'),
		// $sidebar		= $('.sidebar--main'),
		$blocks			= $container.children().addClass('post--animated  post--loaded'),
		initialized		= false,
		containerTop,
		containerBottom,
		// sidebarTop,

	init = function() {

		if ($container.length) {
			containerTop = $container.offset().top;
			containerBottom = containerTop + $container.outerHeight();
		}

		$container.masonry({
			itemSelector: '.grid__item',
			transitionDuration: 0
		});

		bindEvents();
		showBlocks($blocks);
		initialized = true;
		refresh();
	},

	bindEvents = function() {
		$body.on('post-load', onLoad);
		$container.masonry('on', 'layoutComplete', onLayout);
	},

	refresh = function() {

		if (!initialized) {
			return;
		}
		
		$container.masonry('layout');
	},

	showBlocks = function($blocks) {
		// $blocks.each(function(i, obj) {
		// 	var $post = $(obj);
		// 	animator.animatePost($post, i * 100);
		// });
		// if ( ! $.support.touch ) {
		// 	$blocks.addHoverAnimation();
		// }
	},

	onLayout = function() {

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
		for (var k in values){
		    if (values.hasOwnProperty(k) && k % 2 == 1) {
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

	},

	onLoad = function() {
		// var $newBlocks = $container.children().not('.post--loaded').addClass('post--loaded');
		// $newBlocks.imagesLoaded(function() {
		// 	$container.masonry('appended', $newBlocks, true).masonry('layout');
		// 	showBlocks($newBlocks);
		// });
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

Array.prototype.getUnique = function(){
   var u = {}, a = [];
   for(var i = 0, l = this.length; i < l; ++i){
      if(u.hasOwnProperty(this[i])) {
         continue;
      }
      a.push(this[i]);
      u[this[i]] = 1;
   }
   return a;
}