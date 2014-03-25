<?php 

function showMedia($atts) {
	extract(Shortcodes::shortcodeAtts(array(  
	    "id" 		=> '',
	    "class" 	=> ''
	), $atts));
	$allmedia = new Query('media');

	if ($id != ""){
		$media = $allmedia->getRecord($id);	
	}
	if ($class!=""){ 
		$class=" class='".$class."' ";
	} else {
		$class="";
	}
	return  "<img src='".Core::$site['siteurl'].$media['fileurl']."' alt='' ".$class." />"; 
}

Shortcodes::addShortcode( 'media', 'showMedia', '[media tag="" id="" class="" ]' );
Shortcodes::addShortcodeDesc('media','Media Image', '[media tag="" id="" class="" ]');

?>