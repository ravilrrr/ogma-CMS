<?php 

function addPageField($atts){
	global $id;
	global $page;
	 extract(Shortcodes::shortcodeAtts(array(
        'field' => '',
        'page' => ''
    ), $atts));
	if ($page=='') $page=$id;
	$field=Page::getPageField($page,$field);
	return $field;
}
Shortcodes::addShortcode('pagefield','addPageField', '[pagefield page="" field="" /]');
Shortcodes::addShortcodeDesc('pagefield','Page Field', '[pagefield page="" field="" /]');

?>