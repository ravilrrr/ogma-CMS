<?php 

function yoast_rss_shortcode( $atts ) {
	extract(Shortcodes::shortcodeAtts(array(  
	    "feed" 		=> '',  
		"num" 		=> '5',  
		"excerpt" 	=> true,
		"target"	=> '_self'
	), $atts));
	$content = file_get_contents($feed);
	 $x = new SimpleXmlElement($content);  
    $content = "<ul>";  
    foreach($x->channel->item as $entry) {  
        $content .=  "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";  
    }  
    $content .=  "</ul>";  
	return $content;
}

Shortcodes::addShortcode( 'rss', 'yoast_rss_shortcode', '[rss feed="" num="5" excerpt="true" target="_self" ]'  );
Shortcodes::addShortcodeDesc('rss','RSS Feed', '[rss feed="" num="5" excerpt="true" target="_self" ]');


?>