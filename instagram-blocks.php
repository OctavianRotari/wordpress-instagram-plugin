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

require 'vendor/autoload.php';

$instagramBlocks = new InstagramBlocks();

add_shortcode('somethingFunny', array($instagramBlocks, 'somethingFunny'));

class InstagramBlocks {
    const API_URL = 'https://api.instagram.com/v1/';
    const ACCESS_TOKEN = '1595597096.1677ed0.1655ece25cc5448184e67c1d8ce0b466';
    const USER_ID = '1595597096';

    function __construct() {
	global $index_photos;
	$index_photos = 5;
    }

    function somethingFunny() {
	$media = $this->getUserMedia();
	$data = $media->data;

	foreach ($data as $entry) {
	    echo "<img src=\"{$entry->images->standard_resolution->url}\">";
	}
    }

    public function getUserMedia($limit = 100) {
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
