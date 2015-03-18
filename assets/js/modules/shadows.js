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
				card = new Object(),
				$obj = $(obj),
				imageOffset,
				imageWidth,
				imageHeight,
				cardOffset,
				cardWidth,
				cardHeight;

			image.$el 			= $obj;
			imageOffset 		= image.$el.offset();
			imageWidth 			= image.$el.outerWidth();
			imageHeight 		= image.$el.outerHeight();
			image.x0			= imageOffset.left;
			image.y0			= imageOffset.top;
			image.x1			= image.x0 + imageWidth;
			image.y1			= image.y0 + imageHeight;
			image.isPortrait 	= $obj.closest('.entry-image').hasClass('entry-image--tall') || $obj.closest('.entry-image').hasClass('entry-image--portrait');

			card.$el 			= $obj.closest('.entry-card');
			cardOffset  		= card.$el.offset();
			cardWidth  			= card.$el.outerWidth();
			cardHeight  		= card.$el.outerHeight();
			card.x0			= cardOffset.left;
			card.y0			= cardOffset.top;
			card.x1			= card.x0 + cardWidth;
			card.y1			= card.y0 + cardHeight;

			image.card = card;

			images.push(image);
		});

		refresh();
	},

	// test for overlaps and do some work
	refresh = function() {

		for (var i = 0; i <= images.length - 1; i++) {
			for (var j = i+1; j <= images.length - 1; j++) {

				var source, destination, left, right;

				// if we're testing the same image back off
				if (images[i].$el == images[j].$el) {
					return;
				}

				if (images[i].card.x0 < images[j].card.x0) {
					left = images[i];
					right = images[j];
				} else {
					left = images[j];
					right = images[i];
				}

				source 		= left.isPortrait ? left.card : left;
				destination = right;


				if (imagesOverlap(source, destination)) {
					createShadow(source, destination);
				}

			}
		}

		if (!$.support.touch) {
			$('.entry-card').addHoverAnimation();
		}
	},

	createShadow = function(source, destination) {
		// let's assume image1 is over image2
		// we need to create a div
		var $placeholder = $('<div class="entry-image-shadow">'),
			$shadows = source.$el.data('shadow');

		$placeholder.css({
			position: "absolute",
			top: source.y0 - destination.y0,
			left: source.x0 - destination.x0,
			width: source.x1 - source.x0,
			height: source.y1 - source.y0
		});

		if (typeof $shadows == "undefined") {
			$shadows = $placeholder;
		} else {
			$shadows = $shadows.add($placeholder);
		}

		source.$el.data('shadow', $shadows);
		$placeholder.insertAfter(destination.$el);
	},

	imagesOverlap = function(image1, image2) {
		return (image1.x0 < image2.x1 && image1.x1 > image2.x0 && image1.y0 < image2.y1 && image1.y1 > image2.y0);
	};

	return {
		init: init,
		refresh: refresh
	}
})();