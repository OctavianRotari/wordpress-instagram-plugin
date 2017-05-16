jQuery(document).ready(function($) {
	var img_div = "<div class='photo col-6' id='photo-%img_id%' data-img_url='%img_url%'></div> ";
	var imgArray;
	var imgArrayIndex = 0;

	jQuery.when(getImgUrlArray()).done(function(data) {
		imgArray = data;
		addDivImgToHtml(imgArrayIndex, data, setBackgroundImg);
		jQuery('.instagram-photos .show-more').click(function() {
			showNextFive()
		})
	})

	function getImgUrlArray() {
		var data = {
			action: 'my_action',
			security : MyAjax.security
		};
		return jQuery.ajax({
			type: "GET",
			url: MyAjax.ajaxurl,
			data: data,
			dataType: "json"
		});
	}

	function addDivImgToHtml(imgNum, data, callback) {
		for(var i = imgNum; i < imgNum + 5; i++) {
			var final_div = img_div.replace('%img_id%', i).replace('%img_url%', data[i])
			jQuery('.instagram-photos .show-more').before(final_div);
		};
		callback();
	}

	function setBackgroundImg() {
		jQuery('.photo').each(function() {
			jQuery(this).css({
				'background-image': 'url(' + this.dataset.img_url + ')',
				'background-size': 'cover'
			});
		})
	}

	function showNextFive() {
		imgArrayIndex += 5;
		$( ".photo" ).remove();
		addDivImgToHtml(imgArrayIndex, imgArray, setBackgroundImg);
	}
});

