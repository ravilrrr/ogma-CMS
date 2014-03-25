<?php 

function showSection($atts, $content = null) {
	extract(Shortcodes::shortcodeAtts(array(  
	    "name" 		=> '',
	    "class"		=> ''
	), $atts));
	return "<div id='".$name."' class='".$class."'>".Shortcodes::doShortcode($content)."</div>"; 
}
Shortcodes::addShortcode( 'section', 'showSection', '[section name="" ][/section]'  );
Shortcodes::addShortcodeDesc('section','DIV Section', '[section name="" ][/section]');

?>