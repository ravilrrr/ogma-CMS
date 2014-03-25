<?php 

function showSnippet($atts) {
	extract(Shortcodes::shortcodeAtts(array(  
	    "name" 		=> ''
	), $atts));
	return Shortcodes::doShortcode(Snippet::get($name)); 
}
Shortcodes::addShortcode( 'snippet', 'showSnippet', '[snippet name="" ]'  );
Shortcodes::addShortcodeDesc('snippet','Snippet', '[snippet name="" ]');

?>