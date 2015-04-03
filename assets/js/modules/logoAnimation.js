var logoAnimation = (function() {

	var $logo  = $('img.site-logo'),
		$clone,
		distance,

	init = function() {

		if ($logo.length) {

			$clone = $logo.clone().appendTo('.mobile-header');

			var cloneOffset 	= $clone.offset(),
				cloneTop 		= cloneOffset.top,
				cloneHeight		= $clone.height(),
				cloneMid 		= cloneTop + cloneHeight / 2,
				$header 		= $('.mobile-header'),
				headerOffset 	= $header.offset(),
				logoOffset		= $logo.offset(),
				logoTop			= logoOffset.top,
				logoWidth 		= $logo.width(),
				logoHeight		= $logo.height(),
				logoMid 		= logoTop + logoHeight / 2;

			distance = logoMid - cloneMid;

			$clone.velocity({
				translateY: distance,
				translateX: '-50%'
			}, {
				duration: 0
			});
		}
	},

	update = function() {

		if (distance < latestKnownScrollY) {
			$clone.velocity({
				translateY: 0,
				translateX: '-50%'
			}, {
				duration: 0
			});
			return;
		}

		$clone.velocity({
			translateY: distance - latestKnownScrollY,
			translateX: '-50%'
		}, {
			duration: 0
		});
	};

	return {
		init: init,
		update: update
	}

})();