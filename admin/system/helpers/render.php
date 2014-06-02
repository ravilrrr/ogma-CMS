<?php

/**
 *  OGMA CMS Rendering Module
 *
 *  @package ogmaCMS
 *  @author Ravilr / ravilrrr
 *  @copyright 2014 Ravilr / ravilrrr
 *  @since 1.0.0
 *
 */

class Render {

	public static $data_tpl = array();
	
	public function __construct() {
		// nothing
	}
	
/**
 * Rendering template
 * 	Example:
 *  <code>
 *	echo Render::show(
 *		ROOT . 'addins/plugins/myplugin/template.tpl', 
 *		array(
 *		'size' => $size,
 *		'url' => $url
 *		)
 *	);
 *  </code>
 *
 * @param  string $file_tpl      Name and path of the template file
 * @param  array  $data_tpl Array of view variables
 * @return Content
 */
	
	public static function show($file_tpl, $data_tpl) {
		    
		if (file_exists($file_tpl)) {
			extract($data_tpl);
			
      		ob_start();
      
	  		require($file_tpl);
      
	  		$content = ob_get_contents();

			ob_end_clean();

			return $content;
			
		} else {
			trigger_error('Error: Could not load template ' . $file_tpl . '!');
			exit();
		}	
	}

}