<?php 

function addEmail($atts, $content = null) {
$link='';
  foreach(str_split($content) as $letter)
	$link .= '&#'.ord($letter).';';
	return '<a href="mailto:'.$link.'">'.$link.'</a>';
}
Shortcodes::addShortcode('email','addEmail', '[email]emailaddress[/email]');
Shortcodes::addShortcodeDesc('email','Obfucated E-Mail', '[email]emailaddress[/email]');

?>