var animator = (function() {

	var initialize = function() {

	},

	animate = function() {

		var isSingle 	= $('.site-main--single').length,
			hasSlider 	= $('.flexslider').length,
			hasSidebar	= $('.sidebar--main').length,
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

	animateTopBar = function() {
		$('.top-bar').velocity({
			opacity: 1
		}, {
			duration: 300,
			easing: "easeOutCubic"
		});
	},

	animateLogo = function() {

		var $title 			= $('.site-title'),
			$description 	= $('.site-description'),
			$descText		= $('.site-description-text'),
			$after 			= $('.site-description-after'),
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

	animateMenu = function() {

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

	animateSlider = function() {

		var $slider 	= $('.flexslider'),
			$container	= $slider.find('.flag__body'),
			$thumbnail	= $slider.find('.flag__img img'),
			$border 	= $slider.find('.entry-thumbnail-border'),
			$meta 		= $container.find('.entry-meta'),
			$title		= $container.find('.entry-title'),
			$content	= $container.find('.entry-content'),
			$divider 	= $container.find('.divider.narrow'),
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

		setTimeout(function() {
			animateLargeDivider($dividerBig);
		}, 300);

		setTimeout(function() {
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

	animateSmallDivider = function($divider) {

		var $squareLeft 	= $divider.find('.square-left'),
			$squareMiddle 	= $divider.find('.square-middle'),
			$squareRight 	= $divider.find('.square-right'),
			$lineLeft		= $divider.find('.line-left'),
			$lineRight		= $divider.find('.line-right');

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

		$divider.css({ opacity: 1 });

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

	animateLargeDivider = function($divider) {
		var $square 		= $divider.find('.square'),
			$line 			= $divider.find('.line');

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

	animateMain = function() {

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

	animateMainSingle = function() {

		var $main 		= $('.site-main'),
			$header 	= $main.find('.entry-header');
			$meta 		= $header.find('.entry-meta'),
			$title 		= $header.find('.entry-title')
			$excerpt	= $title.next('.intro'),
			$content	= $main.find('.entry-featured, .entry-content, .entry-footer, .post-navigation, .comments-area');

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

	animatePost = function($post, delay) {
		$post.velocity({
			opacity: 1
		}, {
			duration: 300,
			delay: delay,
			easing: 'easeOutCubic'
		});

		var $divider 	= $post.find('.divider.narrow'),
			$dividerBig = $post.find('.divider.wide');
			
		setTimeout(function() {
			animateLargeDivider($dividerBig);
		}, 100);

		setTimeout(function() {
			animateSmallDivider($divider);
		}, 400);
	},

	animateSidebar = function() {
		$('.sidebar--main').velocity({
			opacity: 1
		}, {
			duration: 300,
			easing: 'easeOutCubic'
		});
	},

	animateFooter = function() {
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

})();