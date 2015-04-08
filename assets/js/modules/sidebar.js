var Sidebar = (function () {

	var init = function() {

		if (!isSingle) {
			return;
		}

		var $header 		= $('.site-header'),
			$sidebar		= $('#secondary'),
			$target 		= $header,
			$clone 			= $header.clone(),
			$siteContent 	= $('.site-content');

		if ($sidebar.length) {
			$header.hide();
			$clone.css('float', 'none');
			$clone.prependTo($sidebar).show();
			$target = $sidebar;
		}

		if (!sidebarFits) {
			return;
		}

		$target.css('position', 'fixed');

	},

	isSingle = function() {
		return $body.hasClass('single');
	},

	sidebarFits = function() {
		return windowHeight < $target.outerHeight() + $siteContent.css('paddingTop') + $siteContent.css('paddingBottom') + $html.css('marginTop') + $body.css('borderTopWidth') + $body.css('borderBottomWidth');
	};

	return {
		init: init
	}

})();