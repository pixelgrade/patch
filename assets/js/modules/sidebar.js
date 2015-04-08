var Sidebar = (function () {

	var $header 		= $('.site-header'),
		$sidebar		= $('#secondary'),
		$target 		= $header,
		$clone 			= $header.clone(),
		$siteContent 	= $('.site-content'),

	init = function() {

		if (!isSingle()) {
			return;
		}

		if ($sidebar.length) {
			$header.hide();
			$clone.css('float', 'none');
			$clone.prependTo($sidebar).show();
			$target = $sidebar;
		}

		if (!sidebarFits()) {
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