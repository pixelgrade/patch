var shadows = (function() {

	var images,

	// get all images and info about them and store them
	// in the images array;
	init = function() {

		images = new Array();

		jQuery('.entry-image-shadow').remove();
		jQuery('.entry-card').removeData('shadow');

		$('.entry-card .entry-image img').each(function(i, obj) {
			var image = new Object(),
				$obj = $(obj),
				imageOffset,
				imageWidth,
				imageHeight;

			image.$el = $obj;
			image.$img = $obj;

			if ($obj.closest('.entry-image').hasClass('entry-image--tall') || $obj.closest('.entry-image').hasClass('entry-image--portrait')) {
				$obj = $obj.closest('.entry-card');
			}

			imageOffset = $obj.offset();
			imageWidth 	= $obj.outerWidth();
			imageHeight = $obj.outerHeight();
			image.$el 	= $obj;
			image.x0 	= imageOffset.left;
			image.x1	= image.x0 + imageWidth;
			image.y0	= imageOffset.top;
			image.y1	= image.y0 + imageHeight;

			images.push(image);
		});

		refresh();
	},

	// test for overlaps and do some work
	refresh = function() {

		for (var i = 0; i <= images.length - 1; i++) {
			for (var j = i+1; j <= images.length - 1; j++) {
				// if we're testing the same image back off
				if (images[i].$el == images[j].$el) {
					return;
				}

				if (imageOverlap(images[i], images[j])) {
					var $card = images[i].$el,
						$card2 = images[j].$el;

					if ($card.offset().left < $card2.offset().left) {
						createShadow(images[i], images[j]);
					} else {
						createShadow(images[j], images[i]);
					}
				}
			}
		}

		if (!$.support.touch) {
			$('.entry-card').addHoverAnimation();
		}
	},

	createShadow = function(image1, image2) {
		// let's assume image1 is over image2
		// we need to create a div
		var $placeholder = $('<div class="entry-image-shadow">');

		$placeholder.css({
			position: "absolute",
			top: image1.y0 - image2.y0,
			left: image1.x0 - image2.x0,
			width: image1.x1 - image1.x0,
			height: image1.y1 - image1.y0
		});

		image1.$el.closest('.entry-card').data('shadow', $placeholder);
		$placeholder.insertAfter(image2.$el);
	},

	imageOverlap = function(image1, image2) {
		if (image1.$el.hasClass('entry-card') && image2.$el.hasClass('entry-card')) {

			var imageOffset, imageWidth, imageHeight;

			$obj 		= image2.$el.find('.entry-image');
			imageOffset = $obj.offset();
			imageWidth 	= $obj.outerWidth();
			imageHeight = $obj.outerHeight();
			image2.$el 	= $obj;
			image2.x0 	= imageOffset.left;
			image2.x1	= image2.x0 + imageWidth;
			image2.y0	= imageOffset.top;
			image2.y1	= image2.y0 + imageHeight;
		}
		return (image1.x0 < image2.x1 && image1.x1 > image2.x0 && image1.y0 < image2.y1 && image1.y1 > image2.y0);
	};

	return {
		init: init,
		refresh: refresh
	}
})();