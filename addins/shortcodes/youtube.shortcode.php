<?php 

function addYoutube($atts, $content = null) {
 extract(Shortcodes::shortcodeAtts(array(
    "id" => null,
    "width" => '400',
    "height" => '225'    
  ), $atts));
  return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$id.'?autoplay=0" frameborder="0" allowfullscreen></iframe>';
}
Shortcodes::addShortcode('youtube','addYoutube', '[youtube id="" width="" height="" /]');
Shortcodes::addShortcodeDesc('youtube','Youtube Video', '[youtube id="" width="" height="" /]');

?>