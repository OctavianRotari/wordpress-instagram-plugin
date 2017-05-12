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

$instagramBlocks = new InstagramBlocks();

add_shortcode('somethingFunny', array($instagramBlocks, 'somethingFunny'));

class InstagramBlocks {
    function somethingFunny() {
	echo 'something<br>really<br>funny';
    }
}
