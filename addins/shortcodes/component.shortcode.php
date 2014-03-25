<?php 

function showComponent($atts) {
	extract(Shortcodes::shortcodeAtts(array(  
	    "name" 		=> ''
	), $atts));
	return Component::get($name); 
}
Shortcodes::addShortcode( 'component', 'showComponent', '[component name="" ]'  );
Shortcodes::addShortcodeDesc('component','Add Component', '[component name="" ]' );

?>