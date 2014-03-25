<?php 

function vimeo_shortcode($atts, $content=null) {
	extract(Shortcodes::shortcodeAtts(array(
		'id' 	=> '',
		'width' 	=> '400',
		'height' 	=> '225',
	), $atts));

	if (empty($id) || !is_numeric($id)) return '<!-- Vimeo: Invalid clip_id -->';
	if ($height && !$width) $width = intval($height * 16 / 9);
	if (!$height && $width) $height = intval($width * 9 / 16);

	return "<iframe src='http://player.vimeo.com/video/$id?title=0&amp;byline=0&amp;portrait=0' width='$width' height='$height' frameborder='0'></iframe>";
}
Shortcodes::addShortcode('vimeo', 'vimeo_shortcode', '[vimeo id="" width="" height="" /]');
Shortcodes::addShortcodeDesc('vimeo','Vimeo Video', '[vimeo id="" width="" height="" /]');

?>