<?php 

function showFlickrGallery($atts){
	extract(Shortcodes::shortcodeAtts(array(  
	    "name" 		=> ''
	), $atts));
	$ch = @curl_init();
	@curl_setopt($ch, CURLOPT_URL, "http://api.flickr.com/services/feeds/photoset.gne?set=72157634215973638&nsid=97716697@N07&lang=en-us&format=json&nojsoncallback=1");
	@curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1");
	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	@curl_setopt($ch, CURLOPT_TIMEOUT, 10);

	$response       = @curl_exec($ch);
	$errno          = @curl_errno($ch);
	$error          = @curl_error($ch);

	if( $errno == CURLE_OK) {
	    $pics = json_decode($response);
	}
	$ret='';
	foreach($pics->items as $item) {
		$ret.= "<img src='".$item->media->m."' alt='".$item->title."' />";
	}
	return $ret; 
}

Shortcodes::addShortcode( 'flickr', 'showFlickrGallery', '[flickr name="" ][/flickr]'  );
Shortcodes::addShortcodeDesc('flickr','Flickr Album', '[flickr name="" ][/flickr]');

?>