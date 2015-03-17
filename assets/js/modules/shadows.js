var shadows = (function() {

	var images,

	// get all images and info about them and store them
	// in the images array;
	init = function() {

		images = new Array();

		$('.entry-card .entry-image img').each(function(i, obj) {
			var image = new Object(),
				imageOffset,
				imageWidth,
				imageHeight;

			image.$el 	= $(obj);
			imageOffset = image.$el.offset();
			imageWidth 	= image.$el.outerWidth();
			imageHeight = image.$el.outerHeight();
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
					createShadow(images[i], images[j]);
				}
			}
		}

		$('.entry-card').addHoverAnimation();
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
		return (image1.x0 < image2.x1 && image1.x1 > image2.x0 && image1.y0 < image2.y1 && image1.y1 > image2.y0);
	};

	return {
		init: init,
		refresh: refresh
	}
})();