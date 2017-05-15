jQuery(document).ready(function($) {
	var img_div = "<div class='photo' id='photo-%img_id%' data-img_url='%img_url%'></div> ";

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
});

