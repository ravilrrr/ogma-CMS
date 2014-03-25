<?php

 /**
 *	ogmaCMS Components Module
 *
 *	@package ogmaCMS
 *	@author Mike Swan / n00dles101
 *	@copyright 2013 Mike Swan / n00dles101
 *	@since 1.0.0
 *
 */


class Component {

	public function __construct() {

    }
    


    /**
    * Show a Component
    *
    * Components are small block of HTML/PHP code. 
    * This function fetches and displays a component. 
    * If the component does not exist it will display and error. 
    *
    *
    * <code>
    *      Component::show($name);
    * </code>
    *
    * @param string $name Component Name
    */
    public static function show($name){

        $file = Utils::findRecordID('components','slug',$name);
        if (self::exists($file)) {
    	   $component = (Xml::xml2array(Core::$settings['rootpath'] . '/data/components/'.$file.'.xml'));
           if (Core::trueFalse($component['active']==true)){
               echo eval("?>" . Utils::safe_strip_decode($component['content']) . "<?php ");
           }
        } else {
           echo "Error: Unable to find Component: ".$name;
        }
    }

    /**
    * Return a Component
    *
    * Components are small block of HTML/PHP code. 
    * This function fetches and displays a component. 
    * If the component does not exist it will display and error. 
    *
    *
    * <code>
    *      echo Component::get($name);
    * </code>
    *
    * @param string $name Component Name
    */
    public static function get($name){
         $file = Utils::findRecordID('components','slug',$name);
        if (self::exists($file)) {
           $component = (Xml::xml2array(Core::$settings['rootpath'] . '/data/components/'.$file.'.xml'));
           if (Core::trueFalse($component['active']==true)){
               return eval("?>" . Utils::safe_strip_decode($component['content']) . "<?php ");
           }
        } else {
           return "Error: Unable to find Component: ".$name;
        }
    }


    public static function exists($name){
        return file_exists(Core::getRootPath() . '/data/components/'.$name.'.xml') ? true : false ;
    }

    public static function isActive($name){
        if (self::exists($name)){
             $component = (Xml::xml2array(Core::$settings['rootpath'] . '/data/components/'.$name.'.xml'));
             return Core::trueFalse($component['active']);   
        } else {
            return false;
        }
    }
	
}
