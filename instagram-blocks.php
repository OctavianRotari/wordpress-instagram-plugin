<?php
/**
 * plugin name: instagram photos
 * plugin uri: none
 * description: this plug in fetches images from instagram
 * version: 1.0.0
 * author: octavian rotari
 * author uri: http://danielpataki.com
 * license: gpl2
 */

// Add the JS
function theme_name_scripts() {
    wp_enqueue_script( 'script-name', plugins_url('instagram-blocks') . '/js/instagram_img.js', array('jquery'), null );
    wp_localize_script( 'script-name', 'MyAjax', array(
	// URL to wp-admin/admin-ajax.php to process the request
	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	// generate a nonce with a unique ID "myajax-post-comment-nonce"
	// so that you can check it later when an AJAX request is sent
	'security' => wp_create_nonce( 'my-special-string' )
    ));
}
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
// The function that handles the AJAX request
function my_action_callback() {
    check_ajax_referer( 'my-special-string', 'security' );

    if(isset($_GET['action'])) {
	$instagramBlocks = new InstagramBlocks();
	$media_array = $instagramBlocks->returnMediaArray();
	echo json_encode($media_array);
	die(); // this is required to return a proper result
    } else {
	echo('No images');
    }
}
add_action( 'wp_ajax_my_action', 'my_action_callback' );


class InstagramBlocks {
    const API_URL = 'https://api.instagram.com/v1/';
    const ACCESS_TOKEN = '1595597096.1677ed0.1655ece25cc5448184e67c1d8ce0b466';
    const USER_ID = '1595597096';

    function __construct() {
	global $index_photos;
	$index_photos = 5;
    }

    function returnMediaArray() {
	$media = $this->_getUserMedia();
	$data = $media->data;
	$images = [];

	foreach ($data as $entry) {
	    array_push($images, $entry->images->standard_resolution->url);
	}
	return $images;
    }

    protected function _getUserMedia($limit = 100) {
	return $this->_makeCall('users/' . self::USER_ID . '/media/recent', ($id === 'self'), array('count' => $limit));
    }

    protected function _makeCall($function, $auth = false, $params = null, $method = 'GET') {
	if (isset($params['count']) && $params['count'] < 1) {
	    throw new InvalidParameterException('InstagramClient: you are trying to query 0 records!');
	}

	$authMethod = '?access_token=' . self::ACCESS_TOKEN;

	if (isset($params) && is_array($params)) {
	    $paramString = '&' . http_build_query($params);
	} else {
	    $paramString = null;
	}

	$apiCall = self::API_URL . $function . $authMethod . (('GET' === $method) ? $paramString : null);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $apiCall);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$jsonData = curl_exec($ch);

	curl_close($ch);

	return json_decode($jsonData);
    }
}

