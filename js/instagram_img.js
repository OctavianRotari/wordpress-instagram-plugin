jQuery(document).ready(function($) {
	var imgDiv = "<div class='photo' id='photo-%img_id%' data-imgUrl='%img_url%'></div> ";

	jQuery.when(getImgUrlArray()).done(function(data) {
		addDivImgToHtml(data, setBackgroundImg);
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

	function addDivImgToHtml(data, callback) {
		for(var i = 0; i < 5; i++) {
			console.log(data[i]);
			var finalDiv = imgDiv.replace('%img_id%', i).replace('%img_url%', data[i])
			jQuery('.instagram-photos .show-more').before(finalDiv);
		};
		callback();
	}

	function setBackgroundImg() {
		jQuery('.photo').each(function() {
			jQuery(this).css({
				'background-image': 'url(' + this.dataset.imgUrl + ')',
				'background-size': 'cover'
			});
		})
	}
});

