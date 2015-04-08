var Sidebar = (function () {

	var $header 		= $('.site-header'),
		$sidebar		= $('#secondary'),
		$siteContent 	= $('.site-content'),
		$target 		= $header,
		$clone,

	init = function() {

		if (!isSingle()) {
			return;
		}

		if ($sidebar.length) {
			$sidebar.find('.site-header').remove();
			$header.hide();
			$clone = $header.clone(true).css('float', 'none').prependTo($sidebar).show();
			$target = $sidebar;
		}

		if (!sidebarFits()) {
			$target.css('position', '');
			return;
		}

		$target.css('position', 'fixed');

	},

	isSingle = function() {
		return $body.hasClass('single');
	},

	sidebarFits = function() {
		return windowHeight > parseInt($target.outerHeight(), 10) + parseInt($siteContent.css('paddingTop'), 10) + parseInt($siteContent.css('paddingBottom'), 10) + parseInt($html.css('marginTop'), 10) + parseInt($body.css('borderTopWidth'), 10) + parseInt($body.css('borderBottomWidth'), 10);
	};

	return {
		init: init
	}

})();