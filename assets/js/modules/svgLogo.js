window.svgLogo = (function() {

	var $logo 			= $('.site-branding .site-title'),
		$svg 			= $logo.find('svg'),
		logoOffset		= $logo.offset(),
		logoTop			= logoOffset.top,
		$nav			= $('.main-navigation'),
		navHeight		= $nav.outerHeight(),
		$logoClone		= $logo.clone().empty(),
		$svgClone		= $svg.clone(),
		$newSvg			= $svgClone,
		logoInitialized = false,

	init = function() {

		if ( ! $.support.svg ) return;

		var $header     = $('.site-header'),
			$title      = $('.site-header .site-title'),
			$span       = $title.find('span'),
			$svg        = $title.find('svg'),
			$text       = $svg.find('text'),
			$rect       = $svg.find('rect'),
			titleWidth	= $title.width(),
			titleHeight	= $title.height(),
			spanWidth 	= $span.width(),
			spanHeight	= $span.height(),
			fontSize    = parseInt($span.css('font-size')),
			scaling     = spanWidth / parseFloat(titleWidth);

		setTimeout(function() {

			$svg.removeAttr('viewBox').hide();
			$span.css({
				'white-space': 'nowrap',
				display: 'inline-block'
			});
			
			fontSize    = parseInt($span.css('font-size')),
			spanWidth	= $span.width();
			spanHeight	= $span.height();
			titleWidth	= $title.width();
			scaling     = spanWidth / parseFloat(titleWidth);

			if (spanWidth > titleWidth) { 
				fontSize 	= parseInt(fontSize / scaling);
				$span.css('font-size', fontSize);
				spanWidth	= $span.width();
				spanHeight	= $span.height();
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

			var $topLogo 		= $('.top-bar .site-title'),
				topLogoHeight	= $topLogo.outerHeight(),
				$clone 			= $newSvg.clone();

			$clone.outerHeight(topLogoHeight);
			$clone.find('text').attr('stroke', '#000');

			$topLogo.empty().append($clone);

			logoAnimation();
		}, 60);
	},

	logoAnimation = function() {

		if ( ! is_small ) return;

		$logoClone	= $logo.clone().empty();
		$svgClone	= $svg.clone();
		
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
		
		if ( ! $body.hasClass('home') ) {
			window.scrollTo(0, $('.site-header').height() - navHeight);
		}

		logoInitialized = true; 
	},

	updateLogoAnimation = function() {

		var newVal = 0;

		if ( latestKnownScrollY > logoTop - navHeight ) {
			if ( latestKnownScrollY - logoTop < 0 ) {
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
})();