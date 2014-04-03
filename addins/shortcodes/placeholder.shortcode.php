<?php 

// only enaled if GD is enabled on the host. 

if (extension_loaded('gd') && function_exists('gd_info')) {

	function placeholderImage($atts, $content = null) {
	 extract(Shortcodes::shortcodeAtts(array(
	    "size" => '100x100',
	    "bg" => 'eee',
	    "fg" => '999',
	    "text" => '',
	    "alt" => 'Placeholder Image'    
	  ), $atts));
	 	if ($text=="") $text=$size;
	  return '<img src="/3rdparty/placeholder/placeholder.php?size='.$size.'&bg='.$bg.'&fg='.$fg.'&text='.$text.'&alt='.$alt.'" >';
	}
	Shortcodes::addShortcode('placeholder','placeholderImage', '[placeholder size="" bg="" fg="" text="" alt="" /]');
	Shortcodes::addShortcodeDesc('placeholder','Placeholder Image', '[placeholder size="" bg="" fg="" text="" alt=""  /]');

}
?>