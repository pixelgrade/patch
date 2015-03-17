/* ====== Masonry Logic ====== */

var masonry = (function() {

	var $container 		= $('.grid'),
		// $sidebar		= $('.sidebar--main'),
		$blocks			= $container.children().addClass('post--animated  post--loaded'),
		initialized		= false,
		columns 		= 1,
		containerTop,
		containerBottom,
		// sidebarTop,

	init = function() {

		if (windowWidth < 800) {
			$container.imagesLoaded(function() {
				showBlocks($blocks);
			});
		}

		if ($container.length) {
			containerTop = $container.offset().top;
			containerBottom = containerTop + $container.outerHeight();
		}

		$container.imagesLoaded(function() {
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

	bindEvents = function() {
		$body.off('post-load');
		$body.on('post-load', onLoad);
		$container.masonry('off', 'layoutComplete');
		$container.masonry('on', 'layoutComplete', onLayout);
	},

	refresh = function() {

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

	showBlocks = function($blocks) {
		$blocks.each(function(i, obj) {
			var $post = $(obj);
			animatePost($post, i * 100);
		});
	},

	animatePost = function($post, delay) {
		$post.velocity({
			opacity: 1
		}, {
			duration: 300,
			delay: delay,
			easing: 'easeOutCubic'
		});
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
		    if (values.hasOwnProperty(k) && k % 2 == 0) {
		         newValues.push(values[k]);
		    }
		}

		$container.find('.grid__item').each(function (i, obj) {
			var $obj = $(obj),
				left = parseInt($obj.data('left'), 10);
			if (newValues.indexOf(left) != -1) {
				$obj.addClass('entry--even');
			} else {
				$obj.removeClass('entry--even');
			}
		});

		setTimeout(function () {
			$container.masonry('layout');
			bindEvents();
		}, 10);

		setTimeout(function() {
			shadows.init();
		}, 200);

		return true;
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

	    var $obj 			= $(obj),
	    	$otherShadow	= $obj.find('.entry-image-shadow'),
	    	$hisShadow		= $obj.data('shadow'),
	    	$meta			= $obj.find('.entry-meta'),
	    	options 		= {
	    		duration: 300,
	    		easing: 'easeOutQuad'
	    	};

	    $obj.off('mouseenter').on('mouseenter', function() {
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

	    $obj.off('mouseleave').on('mouseleave', function() {
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